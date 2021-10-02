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

    public $cuttingHistoryRules = array(
        0 => array(
            'field' => 'roundLengthCompleted',
            'label' => 'Round/Length',
            'rules' => 'trim|required|is_natural'
        ),
    );

    public function getGrindingBatchById($id) {
        return $this->db->get_where('grinding_process', array('grinding_process_id'=> $id))->row();
    }

    public function addCuttingBatch($drawProcessHistotryId, $roundLengthCompleted){
        $data = array(
            'purchase_item_id' => $this->input->post('purchaseItemId'),
            'draw_process_history_id' => $this->input->post('drawProcessHistoryId'),
            'process_status_catalog_id' => 1,
            'cutting_size_id' => $this->input->post('cuttingSizeId'),
            'round_or_length_to_be_completed' => $roundLengthCompleted,
            'created_on' => $this->today
        );
        return $this->db->insert('cutting_process', $data);
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

    public function addDrawHistory($completedBy){
        $drawProcess = $this->getDrawProcessById($this->input->post('drawProcessId'));
        $roundLengthAlreadyCompleted = (int)$drawProcess->round_or_length_completed + (int)$this->input->post('roundLengthCompleted');        
        $isAddedRoundGreaterThanCompletedRound = is_greater_than($drawProcess->round_or_length_to_be_completed, $roundLengthAlreadyCompleted);
        if($isAddedRoundGreaterThanCompletedRound){
            $data1 = array(
                'round_or_length_completed' => $roundLengthAlreadyCompleted,
                'process_status_catalog_id' => get_process_status($drawProcess->round_or_length_to_be_completed, $roundLengthAlreadyCompleted),
                'updated_on' => $this->today
            );

            $this->db->where('draw_process_id', $this->input->post('drawProcessId'));
            $this->db->update('draw_process', $data1);
            

            $data = array(
                'completed_by' => $completedBy,
                'purchase_id' => $this->input->post('purchaseId'),
                'purchase_item_id' => $this->input->post('purchaseItemId'),
                'draw_process_id' => $this->input->post('drawProcessId'),
                'machine_id' => $this->input->post('machineId'),                
                'size_drawn' => $this->input->post('sizeDrawn'),
                'round_or_length_completed' => $this->input->post('roundLengthCompleted'),
                'remarks' => $this->input->post('remarks'),
                'created_on' => $this->today,
            );
            $this->db->insert('draw_process_history', $data);
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
                             g.cutting_process_id,
                             g.piece_to_be_grinded,
                             g.piece_grinded,
                             g.remarks,
                             DATE_FORMAT(g.created_on, "%d-%b-%Y") as created_on,
                             DATE_FORMAT(g.updated_on, "%d-%b-%Y") as updated_on,
                             p.status_value,
                             p.status_color,
                             s.size_value
                             '
                        );
        $this->db->from('grinding_process as g');
        $this->db->join('process_status_catalog as p', 'g.process_status_catalog_id = p.process_status_catalog_id');
        $this->db->join('size as s', 'g.size_id  = s.size_id ');
        
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
                                gh.cutting_process_id,
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