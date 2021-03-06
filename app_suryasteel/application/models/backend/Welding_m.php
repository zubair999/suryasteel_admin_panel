<?php 
defined('BASEPATH') or exit('no dierct script access allowed');

class Welding_m extends MY_Model {

	protected $tbl_name = 'welding_process';
    protected $primary_col = 'welding_process_id';
    protected $order_by = 'created_on';

    public function __construct()
	{
		parent::__construct();   
	}

    public $weldingHistoryRules = array(
        0 => array(
            'field' => 'pieceWelded',
            'label' => 'Piece welded',
            'rules' => 'trim|required|is_natural'
        ),
    );

    public function getWeldingBatchById($id) {
        return $this->db->get_where('welding_process', array('welding_process_id'=> $id))->row();
    }

    public function getWeldingProcessCountByPurchaseItemId($id) {
        return $this->db->get_where('welding_process', array('purchase_item_id'=> $id))->num_rows();
    }

    public function addWeldingBatch($drillProcessHistoryId, $size, $length, $category_id){
        $data = array(
            'category_id' => $category_id,
            'purchase_item_id' => $this->input->post('purchaseItemId'),
            'drill_process_history_id' => $drillProcessHistoryId,
            'size_id' => $size,
            'length_id' => $length,
            'piece_to_be_weld' => $this->input->post('pieceDrilled'),
            'created_on' => $this->today
        );
        return $this->db->insert('welding_process', $data);
    }

    public function updateDrawProcess($roundLengthAlreadyCompleted){
        $data1 = array(
            'process_status_catalog_id' => 2,
            'round_or_length_to_be_completed' => $roundLengthAlreadyCompleted,
            'updated_on' => $this->today
        );

        $this->db->where('draw_process_id', $this->input->post('acidTreatmentId'));
        $this->db->update('draw_process', $data1);
    }

    public function addWeldingHistory($completedBy){
        $weldingProcess = $this->getWeldingBatchById($this->input->post('weldingProcessId'));
        $pieceAlreadyWelded = (int)$weldingProcess->piece_welded + (int)$this->input->post('pieceWelded');    
        $scrapPieces = (int)$weldingProcess->scrap_pieces + (int)$this->input->post('scrapPieces');
        
        $pieceWeldedAndScrapPiece = (float)$pieceAlreadyWelded + (float)$scrapPieces;

        
        $isAddedWeldedPieceGreaterThanCompletedWeldedPiece = is_greater_than($weldingProcess->piece_to_be_weld, $pieceWeldedAndScrapPiece);
        
        $isTaskCompleted = is_task_completed($weldingProcess->piece_to_be_weld, $pieceWeldedAndScrapPiece);
        
        
        if($isAddedWeldedPieceGreaterThanCompletedWeldedPiece){
            $data1 = array(
                'piece_welded' => $pieceAlreadyWelded,
                'scrap_pieces' => $scrapPieces,
                'process_status_catalog_id' => get_process_status($weldingProcess->piece_to_be_weld, $pieceWeldedAndScrapPiece),
                'is_completed' => $isTaskCompleted,
                'updated_on' => $this->today
            );

            $this->db->where('welding_process_id', $this->input->post('weldingProcessId'));
            $this->db->update('welding_process', $data1);
            

            $data = array(
                'welded_by' => $completedBy,
                'purchase_item_id' => $this->input->post('purchaseItemId'),
                'welding_process_id' => $this->input->post('weldingProcessId'),
                'machine_id' => $this->input->post('machineId'),
                'piece_welded' => $this->input->post('pieceWelded'),
                'scrap_pieces' => $this->input->post('scrapPieces'),
                'remarks' => $this->input->post('remarks'),
                'created_on' => $this->today,
            );
            $this->db->insert('welding_process_history', $data);
            $weldedProcessHistoryId = $this->db->insert_id();
            $this->galvanisation_m->addGalvanisationBatch($weldedProcessHistoryId, $weldingProcess->size_id, $weldingProcess->length_id, $weldingProcess->category_id);
            return ['status'=>'success', 'message'=>'These Pieces are welded successfully.'];
        }
        else{
            return ['status'=>'error', 'message'=>'Completed round cannot be more than round drawn earlier in the draw process.'];
        }
    }
    

    public function get_welding_batch(){
        $this->db->select(
                            'w.welding_process_id,
                             w.purchase_item_id,
                             w.piece_to_be_weld,
                             w.piece_welded,
                             w.scrap_pieces,
                             w.is_completed,
                             w.remarks,
                             DATE_FORMAT(w.created_on, "%d-%b-%Y") as created_on,
                             DATE_FORMAT(w.updated_on, "%d-%b-%Y") as updated_on,
                             p.status_value,
                             p.status_color,
                             s.size_value,
                             l.length_value,
                             ct.category_name
                             '
                        );
        $this->db->from('welding_process as w');
        $this->db->join('process_status_catalog as p', 'w.process_status_catalog_id = p.process_status_catalog_id');
        $this->db->join('size as s', 'w.size_id  = s.size_id ');
        $this->db->join('length as l', 'w.length_id  = l.length_id ');
        $this->db->join('category as ct', 'ct.category_id = w.category_id');

        
        // if($this->input->post('orderStatus')){
        //     $this->db->where('o.order_status_catalog_id', $this->input->post('orderStatus'));
        // }
        // if($this->input->post('since')){
        //     $this->db->where('o.created_on >=', $this->input->post('since'));
        // }
        // if($this->input->post('until')){
        //     $this->db->where('o.created_on <=', $this->input->post('until'));
        // }
        // if($this->input->post('userId')){
        //     $this->db->where('o.user_id', $this->input->post('userId'));
        // }
        // if($this->input->post('createdBy')){
        //     $this->db->where('o.created_by', $this->input->post('createdBy'));
        // }
        
        // $this->db->limit(1);
        $this->db->order_by('w.process_status_catalog_id', 'asc');
        $welding_batch = $this->db->get()->result_array();
        
        foreach ($welding_batch as $key => $wb){
            $welding_history = $this->get_welding_history_by_welding_batch_id($wb['welding_process_id']);
            $welding_batch[$key]['welding_history'] = $welding_history;
        }

        return $welding_batch;
        // FUNCTION ENDS
    }


    public function get_welding_history_by_welding_batch_id($id){
        $this->db->select(
                            '
                                wh.welding_process_history_id, 
                                wh.purchase_item_id, 
                                wh.welding_process_id,
                                wh.piece_welded,
                                wh.remarks,
                                DATE_FORMAT(wh.created_on, "%d-%b-%Y") as created_on,
                                DATE_FORMAT(wh.updated_on, "%d-%b-%Y") as updated_on,
                                CONCAT(u.firstname ," ",  u.lastname) as welded_by,
                                m.machine_name
                            '
                        );
        $this->db->from('welding_process_history as wh');
        $this->db->join('users as u', 'wh.welded_by = u.user_id');
        $this->db->join('machine as m', 'wh.machine_id = m.machine_id');
        $this->db->where('wh.welding_process_id', $id);
        $weld_history =  $this->db->get()->result_array();
        return $weld_history;
    }

    public function get_welding_process_overview_by_purchase_item_id($purchase_item_id){
        $welding_process_count = $this->getWeldingProcessCountByPurchaseItemId($purchase_item_id);

        if($welding_process_count == 0){
            $welding_process_overview = [
                'pieces_welded' => PROCESS_NOT_STARTED,
                'scrap_piece' => PROCESS_NOT_STARTED
            ];
            return $welding_process_overview;
        }
        else{
            $this->db->select('*');
            $this->db->from('welding_process');
            $this->db->where('purchase_item_id', $purchase_item_id);
            $weld_process = $this->db->get()->result_array();   

            $pieces_welded = '';
            $scrap_pieces = '';

            foreach ($weld_process as $key => $a){
                $pieces_welded .= $a['piece_welded'].'/'.$a['piece_to_be_weld'].', ';
                $scrap_pieces .= $a['scrap_pieces'].'/'.$a['piece_to_be_weld'].', ';
            }

            $welding_process_overview = [
                'pieces_welded' => $pieces_welded,
                'scrap_piece' => $scrap_pieces
            ];
            return $welding_process_overview;
        }
    }

//end class

}
