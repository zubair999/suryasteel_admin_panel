<?php 
defined('BASEPATH') or exit('no dierct script access allowed');

class Grinding_m extends MY_Model {

	protected $tbl_name = 'grinding_process';
    protected $primary_col = 'grinding_process_id';
    protected $order_by = 'created_on';

    public function __construct()
	{
		parent::__construct();   
	}

    public $grindingHistoryRules = array(
        0 => array(
            'field' => 'pieceGrinded',
            'label' => 'No. of piece grinded',
            'rules' => 'trim|required|is_natural'
        ),
    );

    public function getGrindingBatchById($id) {
        return $this->db->get_where('grinding_process', array('grinding_process_id'=> $id))->row();
    }

    public function addGrindingBatch($cuttingProcessHistotryId, $pieceToBeGrinded, $size, $length){
        $data = array(
            'purchase_item_id' => $this->input->post('purchaseItemId'),
            'cutting_process_history_id' => $cuttingProcessHistotryId,
            'size_id' => $size,
            'length_id' => $length,
            'piece_to_be_grinded' => $pieceToBeGrinded,
            'created_on' => $this->today
        );
        return $this->db->insert('grinding_process', $data);
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

    public function addGrindingHistory($completedBy){
        $grindingProcess = $this->getGrindingBatchById($this->input->post('grindingProcessId'));
        $pieceGrinded = (int)$grindingProcess->piece_grinded + (int)$this->input->post('pieceGrinded');        
        $isAddedPieceGreaterThanCompletedPiece = is_greater_than($grindingProcess->piece_to_be_grinded, $pieceGrinded);
        if($isAddedPieceGreaterThanCompletedPiece){
            $data1 = array(
                'piece_grinded' => $pieceGrinded,
                'process_status_catalog_id' => get_process_status($grindingProcess->piece_to_be_grinded, $pieceGrinded),
                'updated_on' => $this->today
            );

            $this->db->where('grinding_process_id', $this->input->post('grindingProcessId'));
            $this->db->update('grinding_process', $data1);
            

            $data = array(
                'grinded_by' => $completedBy,
                'purchase_item_id' => $this->input->post('purchaseItemId'),
                'grinding_process_id' => $this->input->post('grindingProcessId'),
                'machine_id' => $this->input->post('machineId'),                
                'piece_grinded' => $this->input->post('pieceGrinded'),
                'remarks' => $this->input->post('remarks'),
                'created_on' => $this->today,
            );
            $this->db->insert('grinding_process_history', $data);

            $this->forging_m->addForgingBatch($grindingProcess->size_id, $grindingProcess->length_id);


            return ['status'=>'success', 'message'=>'These Round are drawn successfully.'];
        }
        else{
            return ['status'=>'error', 'message'=>'Completed round cannot be more than round drawn earlier in the draw process.'];
        }
    }
    

    public function get_grinding_batch(){
        $this->db->select(
                            'g.grinding_process_id,
                             g.purchase_item_id,
                             g.cutting_process_history_id,
                             g.piece_to_be_grinded,
                             g.piece_grinded,
                             g.remarks,
                             DATE_FORMAT(g.created_on, "%d-%b-%Y") as created_on,
                             DATE_FORMAT(g.updated_on, "%d-%b-%Y") as updated_on,
                             p.status_value,
                             p.status_color,
                             s.size_value,
                             l.length_value
                             '
                        );
        $this->db->from('grinding_process as g');
        $this->db->join('process_status_catalog as p', 'g.process_status_catalog_id = p.process_status_catalog_id');
        $this->db->join('size as s', 'g.size_id  = s.size_id ');
        $this->db->join('length as l', 'g.length_id  = l.length_id');

        
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
        $grinding_process = $this->db->get()->result_array();
        
        foreach ($grinding_process as $key => $gp){
            $grinding_history = $this->get_grinding_history_by_grinding_batch_id($gp['grinding_process_id']);
            $grinding_process[$key]['grinding_history'] = $grinding_history;
        }

        return $grinding_process;
        // FUNCTION ENDS
    }


    public function get_grinding_history_by_grinding_batch_id($id){
        $this->db->select(
                            '
                                gh.grinding_process_history_id, 
                                gh.purchase_item_id, 
                                gh.grinding_process_id,
                                gh.piece_grinded,
                                gh.remarks,
                                DATE_FORMAT(gh.created_on, "%d-%b-%Y") as created_on,
                                DATE_FORMAT(gh.updated_on, "%d-%b-%Y") as updated_on,
                                CONCAT(u.firstname ," ",  u.lastname) as grinded_by,
                                m.machine_name
                            '
                        );
        $this->db->from('grinding_process_history as gh');
        $this->db->join('users as u', 'gh.grinded_by = u.user_id');
        $this->db->join('machine as m', 'gh.machine_id = m.machine_id');
        $this->db->where('gh.grinding_process_id', $id);
        $grinding_history =  $this->db->get()->result_array();
        return $grinding_history;
    }

//end class

}
