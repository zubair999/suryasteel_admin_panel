<?php 
defined('BASEPATH') or exit('no dierct script access allowed');

class Galvanisation_m extends MY_Model {

	protected $tbl_name = 'galvanising_process';
    protected $primary_col = 'galvanising_process_id';
    protected $order_by = 'created_on';

    public function __construct()
	{
		parent::__construct();   
	}

    public $galvanisationHistoryRules = array(
        0 => array(
            'field' => 'pieceGalvanised',
            'label' => 'Piece pieceGalvanised',
            'rules' => 'trim|required|is_natural'
        ),
    );

    public function getGalvanisationBatchById($id) {
        return $this->db->get_where('galvanising_process', array('galvanising_process_id'=> $id))->row();
    }

    public function getGalvanisationProcessCountByPurchaseItemId($id) {
        return $this->db->get_where('galvanising_process', array('purchase_item_id'=> $id))->num_rows();
    }

    public function addGalvanisationBatch($weldedProcessHistoryId, $size, $length, $category_id){
        $data = array(
            'category_id' => $category_id,
            'purchase_item_id' => $this->input->post('purchaseItemId'),
            'welding_process_history_id' => $weldedProcessHistoryId,
            'size_id' => $size,
            'length_id' => $length,
            'piece_to_be_galvanised' => $this->input->post('pieceWelded'),
            'created_on' => $this->today
        );
        return $this->db->insert('galvanising_process', $data);
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

    public function addGalvanisationHistory($completedBy){
        $galvanisedProcess = $this->getGalvanisationBatchById($this->input->post('galvanisingProcessId'));
        $pieceAlreadyGalvanised = (int)$galvanisedProcess->piece_galvanised + (int)$this->input->post('pieceGalvanised');      
        $scrapPieces = (int)$galvanisedProcess->scrap_pieces + (int)$this->input->post('scrapPieces');
        
        $pieceGalvanisedAndScrapPiece = (float)$pieceAlreadyGalvanised + (float)$scrapPieces;

        
        $isAddedPieceGreaterThanCompletedGalvanisedPiece = is_greater_than($galvanisedProcess->piece_to_be_galvanised, $pieceGalvanisedAndScrapPiece);
        if($isAddedPieceGreaterThanCompletedGalvanisedPiece){
            $data1 = array(
                'piece_galvanised' => $pieceAlreadyGalvanised,
                'scrap_pieces' => $scrapPieces,
                'process_status_catalog_id' => get_process_status($galvanisedProcess->piece_to_be_galvanised, $pieceGalvanisedAndScrapPiece),
                'updated_on' => $this->today
            );

            $this->db->where('galvanising_process_id', $this->input->post('galvanisingProcessId'));
            $this->db->update('galvanising_process', $data1);
            

            $data = array(
                'galvanised_by' => $completedBy,
                'purchase_item_id' => $this->input->post('purchaseItemId'),
                'galvanising_process_id' => $this->input->post('galvanisingProcessId'),
                'machine_id' => $this->input->post('machineId'),                
                'piece_galvanised' => $this->input->post('pieceGalvanised'),
                'scrap_pieces' => $this->input->post('scrapPieces'),
                'remarks' => $this->input->post('remarks'),
                'created_on' => $this->today,
            );
            $this->db->insert('galvanising_process_history', $data);
            return ['status'=>'success', 'message'=>'The galvanised pieces are added succesfully.'];
        }
        else{
            return ['status'=>'error', 'message'=>'Completed round cannot be more than round drawn earlier in the draw process.'];
        }
    }
    

    public function get_galvanisation_batch(){
        $this->db->select(
                            'g.galvanising_process_id,
                             g.purchase_item_id,
                             g.piece_to_be_galvanised,
                             g.piece_galvanised,
                             g.remarks,
                             DATE_FORMAT(g.created_on, "%d-%b-%Y") as created_on,
                             DATE_FORMAT(g.updated_on, "%d-%b-%Y") as updated_on,
                             p.status_value,
                             p.status_color,
                             s.size_value,
                             l.length_value,
                             ct.category_name
                             '
                        );
        $this->db->from('galvanising_process as g');
        $this->db->join('process_status_catalog as p', 'g.process_status_catalog_id = p.process_status_catalog_id');
        $this->db->join('size as s', 'g.size_id  = s.size_id ');
        $this->db->join('length as l', 'g.length_id  = l.length_id ');
        $this->db->join('category as ct', 'ct.category_id = g.category_id');
        
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
        $this->db->order_by('g.process_status_catalog_id', 'asc');
        $galvanisation_batch = $this->db->get()->result_array();
        
        foreach ($galvanisation_batch as $key => $gb){
            $galvanisation_history = $this->get_galvanisation_batch_history_by_galvanisation_batch_batch_id($gb['galvanising_process_id']);
            $galvanisation_batch[$key]['galvanisation_history'] = $galvanisation_history;
        }

        return $galvanisation_batch;
        // FUNCTION ENDS
    }


    public function get_galvanisation_batch_history_by_galvanisation_batch_batch_id($id){
        $this->db->select(
                            '
                                gh.galvanising_process_history_id, 
                                gh.purchase_item_id, 
                                gh.galvanising_process_id,
                                gh.piece_galvanised,
                                gh.remarks,
                                DATE_FORMAT(gh.created_on, "%d-%b-%Y") as created_on,
                                DATE_FORMAT(gh.updated_on, "%d-%b-%Y") as updated_on,
                                CONCAT(u.firstname ," ",  u.lastname) as galvanised_by,
                                m.machine_name
                            '
                        );
        $this->db->from('galvanising_process_history as gh');
        $this->db->join('users as u', 'gh.galvanised_by = u.user_id');
        $this->db->join('machine as m', 'gh.machine_id = m.machine_id');
        $this->db->where('gh.galvanising_process_id', $id);
        return $this->db->get()->result_array();
    }

    public function get_galvanisation_process_overview_by_purchase_item_id($purchase_item_id){
        $galvanising_process_count = $this->getGalvanisationProcessCountByPurchaseItemId($purchase_item_id);

        if($galvanising_process_count == 0){
            $galvanising_process_overview = [
                'pieces_galvanised' => PROCESS_NOT_STARTED,
                'scrap_piece' => PROCESS_NOT_STARTED
            ];
            return $galvanising_process_overview;
        }
        else{
            $this->db->select('*');
            $this->db->from('galvanising_process');
            $this->db->where('purchase_item_id', $purchase_item_id);
            $galvanising_process = $this->db->get()->result_array();   

            $pieces_galvanised = '';
            $scrap_pieces = '';

            foreach ($galvanising_process as $key => $a){
                $pieces_galvanised .= $a['piece_galvanised'].'/'.$a['piece_to_be_galvanised'].', ';
                $scrap_pieces .= $a['scrap_pieces'].'/'.$a['piece_to_be_galvanised'].', ';
            }

            $galvanising_process_overview = [
                'pieces_galvanised' => $pieces_galvanised,
                'scrap_piece' => $scrap_pieces
            ];
            return $galvanising_process_overview;
        }
    }

//end class

}
