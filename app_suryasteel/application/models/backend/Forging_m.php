<?php 
defined('BASEPATH') or exit('no dierct script access allowed');

class Forging_m extends MY_Model {

	protected $tbl_name = 'forging_process';
    protected $primary_col = 'forging_process_id';
    protected $order_by = 'created_on';

    public function __construct()
	{
		parent::__construct();   
	}

    public $forgingHistoryRules = array(
        0 => array(
            'field' => 'pieceForged',
            'label' => 'No. of piece forged',
            'rules' => 'trim|required|is_natural'
        ),
    );

    public function getForgingBatchById($id) {
        return $this->db->get_where('forging_process', array('forging_process_id'=> $id))->row();
    }

    public function addForgingBatch($size, $length){
        $data = array(
            'purchase_item_id' => $this->input->post('purchaseItemId'),
            'grinding_process_id' => $this->input->post('grindingProcessId'),
            'process_status_catalog_id' => 1,
            'size_id' => $size,
            'length_id' => $length,
            'piece_to_be_forged' => $this->input->post('pieceGrinded'),
            'created_on' => $this->today
        );
        return $this->db->insert('forging_process', $data);
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

    public function addForgingHistory($completedBy){
        $forgingProcess = $this->getForgingBatchById($this->input->post('forgingProcessId'));
        $pieceAlreadyForged = (int)$forgingProcess->piece_forged + (int)$this->input->post('pieceForged');        
        $isAddedPieceForgedGreaterThanCompletedPieceForged = is_greater_than($forgingProcess->piece_to_be_forged, $pieceAlreadyForged);
        if($isAddedPieceForgedGreaterThanCompletedPieceForged){
            $data1 = array(
                'piece_forged' => $pieceAlreadyForged,
                'process_status_catalog_id' => get_process_status($forgingProcess->piece_to_be_forged, $pieceAlreadyForged),
                'updated_on' => $this->today
            );

            $this->db->where('forging_process_id', $this->input->post('forgingProcessId'));
            $this->db->update('forging_process', $data1);
            
            $data = array(
                'forged_by' => $completedBy,
                'purchase_item_id' => $this->input->post('purchaseItemId'),
                'forging_process_id' => $this->input->post('forgingProcessId'),
                'machine_id' => $this->input->post('machineId'),                
                'piece_forged' => $this->input->post('pieceForged'),
                'remarks' => $this->input->post('remarks'),
                'created_on' => $this->today,
            );
            $this->db->insert('forging_process_history', $data);
            $forgingProcessHistoryId = $this->db->insert_id();
            $this->head_m->addHeadBatch($forgingProcessHistoryId, $forgingProcess->size_id, $forgingProcess->length_id);
            return ['status'=>'success', 'message'=>'These Round are drawn successfully.'];
        }
        else{
            return ['status'=>'error', 'message'=>'Completed round cannot be more than round drawn earlier in the draw process.'];
        }
    }
    

    public function get_forging_batch(){
        $this->db->select(
                            'f.forging_process_id,
                             f.purchase_item_id,
                             f.grinding_process_id,
                             f.piece_to_be_forged,
                             f.piece_forged,
                             f.remarks,
                             DATE_FORMAT(f.created_on, "%d-%b-%Y") as created_on,
                             DATE_FORMAT(f.updated_on, "%d-%b-%Y") as updated_on,
                             p.status_value,
                             p.status_color,
                             s.size_value,
                             l.length_value
                             '
                        );
        $this->db->from('forging_process as f');
        $this->db->join('process_status_catalog as p', 'f.process_status_catalog_id = p.process_status_catalog_id');
        $this->db->join('size as s', 'f.size_id  = s.size_id ');
        $this->db->join('length as l', 'f.length_id  = l.length_id ');
        
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
        $this->db->order_by('f.process_status_catalog_id', 'asc');
        $forging_batch = $this->db->get()->result_array();
        
        foreach ($forging_batch as $key => $fb){
            $forging_history = $this->get_forging_history_by_forging_batch_id($fb['forging_process_id']);
            $forging_batch[$key]['forging_history'] = $forging_history;
        }

        return $forging_batch;
        // FUNCTION ENDS
    }


    public function get_forging_history_by_forging_batch_id($id){
        $this->db->select(
                            '
                                fh.forging_process_history_id , 
                                fh.purchase_item_id,
                                fh.forging_process_id,
                                fh.piece_forged,
                                fh.remarks,
                                DATE_FORMAT(fh.created_on, "%d-%b-%Y") as created_on,
                                DATE_FORMAT(fh.updated_on, "%d-%b-%Y") as updated_on,
                                CONCAT(u.firstname ," ",  u.lastname) as forged_by,
                                m.machine_name
                            '
                        );
        $this->db->from('forging_process_history as fh');
        $this->db->join('users as u', 'fh.forged_by = u.user_id');
        $this->db->join('machine as m', 'fh.machine_id = m.machine_id');
        $this->db->where('fh.forging_process_id', $id);
        $forging_history =  $this->db->get()->result_array();
        return $forging_history;
    }

//end class

}
