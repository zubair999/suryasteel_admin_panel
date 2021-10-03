<?php 
defined('BASEPATH') or exit('no dierct script access allowed');

class Cutting_m extends MY_Model {

	protected $tbl_name = 'cutting_process';
    protected $primary_col = 'cutting_process_id';
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

    public function getCuttingBatchById($id) {
        return $this->db->get_where('cutting_process', array('cutting_process_id'=> $id))->row();
    }

    public function addCuttingBatch($drawProcessHistotryId, $roundLengthCompleted){
        $data = array(
            'purchase_item_id' => $this->input->post('purchaseItemId'),
            'draw_process_history_id' => $drawProcessHistotryId,
            'process_status_catalog_id' => 1,
            'size_id' => $this->input->post('sizeDrawn'),
            'length_id' => $this->input->post('lengthToBeCut'),
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
    

    public function get_cutting_batch(){
        $this->db->select(
                            'c.cutting_process_id,
                             c.purchase_item_id,
                             c.draw_process_history_id,
                             c.round_or_length_to_be_completed,
                             c.round_or_length_completed,
                             c.round_or_length_completed,
                             c.total_piece_generated,
                             c.remarks,
                             DATE_FORMAT(c.created_on, "%d-%b-%Y") as created_on,
                             DATE_FORMAT(c.updated_on, "%d-%b-%Y") as updated_on,
                             p.status_value,
                             p.status_color,
                             s.size_value,
                             l.length_value
                             '
                        );
        $this->db->from('cutting_process as c');
        $this->db->join('process_status_catalog as p', 'c.process_status_catalog_id = p.process_status_catalog_id');
        $this->db->join('size as s', 'c.size_id  = s.size_id ');
        $this->db->join('length as l', 'c.length_id  = l.length_id');
        
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
        $this->db->order_by('c.process_status_catalog_id', 'asc');
        $cutting_process = $this->db->get()->result_array();
        
        foreach ($cutting_process as $key => $cp){
            $cutting_item = $this->get_cutting_item_by_cutting_batch_id($cp['cutting_process_id']);
            $cutting_process[$key]['cutting_item'] = $cutting_item;
        }

        return $cutting_process;
        // FUNCTION ENDS
    }


    public function get_cutting_item_by_cutting_batch_id($id){
        $this->db->select(
                            '
                                ch.cutting_process_history_id , 
                                ch.purchase_item_id, 
                                ch.cutting_process_id,
                                ch.round_or_length_completed,
                                ch.piece_generated,
                                ch.remarks,
                                DATE_FORMAT(ch.created_on, "%d-%b-%Y") as created_on,
                                DATE_FORMAT(ch.updated_on, "%d-%b-%Y") as updated_on,
                                CONCAT(u.firstname ," ",  u.lastname) as cutting_completed_by,
                                m.machine_name
                            '
                        );
        $this->db->from('cutting_process_history as ch');
        $this->db->join('users as u', 'ch.cutting_completed_by = u.user_id');
        $this->db->join('machine as m', 'ch.machine_id = m.machine_id');
        $this->db->where('ch.cutting_process_id', $id);
        $cutting_item =  $this->db->get()->result_array();
        return $cutting_item;
    }

//end class

}
