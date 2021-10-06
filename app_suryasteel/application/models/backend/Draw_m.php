<?php 
defined('BASEPATH') or exit('no dierct script access allowed');

class Draw_m extends MY_Model {

	protected $tbl_name = 'draw_process';
    protected $primary_col = 'draw_process_id';
    protected $order_by = 'created_on';

    public function __construct()
	{
		parent::__construct();   
	}

    public $drawHistoryRules = array(
        0 => array(
            'field' => 'roundLengthCompleted',
            'label' => 'Round/Length',
            'rules' => 'trim|required|is_natural'
        ),
    );

    public function getDrawProcessById($id) {
        return $this->db->get_where('draw_process', array('draw_process_id'=> $id))->row();
    }

    public function getDrawProcessByAcidTreatmentId($id) {
        return $this->db->get_where('draw_process', array('acid_treatment_id'=> $id))->row();
    }

    public function addDrawProcess($roundLength){
        $data = array(
            'purchase_id' => $this->input->post('purchaseId'),
            'purchase_item_id' => $this->input->post('purchaseItemId'),
            'acid_treatment_id' => $this->input->post('acidTreatmentId'),
            'round_or_length_to_be_completed' => $roundLength,
            'created_on' => $this->today
        );
        return $this->db->insert('draw_process', $data);
    }

    public function updateDrawProcess(){
        $drawProcess = $this->getDrawProcessByAcidTreatmentId($this->input->post('acidTreatmentId'));
        $totalRoundLengthAlreadyCompleted = (int)$drawProcess->round_or_length_to_be_completed + (int)$this->input->post('roundLengthCompleted');

        $data1 = array(
            'process_status_catalog_id' => get_process_status($totalRoundLengthAlreadyCompleted, $drawProcess->round_or_length_completed),
            'round_or_length_to_be_completed' => $totalRoundLengthAlreadyCompleted,
            'updated_on' => $this->today
        );

        $this->db->where('acid_treatment_id', $this->input->post('acidTreatmentId'));
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
                'size_id' => $this->input->post('sizeDrawn'),
                'length_id' => $this->input->post('lengthToBeCut'),
                'round_or_length_completed' => $this->input->post('roundLengthCompleted'),
                'remarks' => $this->input->post('remarks'),
                'created_on' => $this->today,
            );
            $this->db->insert('draw_process_history', $data);
            $drawProcessHistotryId = $this->db->insert_id();

            $this->cutting_m->addCuttingBatch($drawProcessHistotryId, $this->input->post('roundLengthCompleted'));


            return ['status'=>'success', 'message'=>'These Round are drawn successfully.'];
        }
        else{
            return ['status'=>'error', 'message'=>'Completed round cannot be more than round drawn earlier in the draw process.'];
        }
    }
    

    public function get_draw_batch(){
        $this->db->select(
                            'd.draw_process_id,
                             d.purchase_id,
                             d.purchase_item_id,
                             d.acid_treatment_id,
                             d.round_or_length_to_be_completed,
                             d.round_or_length_completed,
                             d.remarks,
                             DATE_FORMAT(d.created_on, "%d-%b-%Y") as created_on,
                             DATE_FORMAT(d.updated_on, "%d-%b-%Y") as updated_on,
                             p.status_value,
                             p.status_color,
                             sz.size_value
                             '
                        );
        $this->db->from('draw_process as d');
        $this->db->join('purchase_item as pi', 'd.purchase_item_id = pi.purchase_item_id');
        $this->db->join('size as sz', 'pi.size_id = sz.size_id');
        $this->db->join('process_status_catalog as p', 'd.process_status_catalog_id = p.process_status_catalog_id');
        
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
        $this->db->order_by('d.process_status_catalog_id', 'asc');
        $draw_process = $this->db->get()->result_array();
        
        foreach ($draw_process as $key => $dp){
            $draw_process[$key]['draw_history'] = $this->get_draw_history_by_head_batch_id($dp['draw_process_id']);
        }

        return $draw_process;
        // FUNCTION ENDS
    }

    public function get_draw_history_by_head_batch_id($id){
        $this->db->select(
                            '
                                dh.draw_process_history_id,
                                dh.purchase_id, 
                                dh.purchase_item_id, 
                                dh.draw_process_id,
                                dh.round_or_length_completed as round_drawn,
                                dh.remarks,
                                DATE_FORMAT(dh.created_on, "%d-%b-%Y") as created_on,
                                DATE_FORMAT(dh.updated_on, "%d-%b-%Y") as updated_on,
                                CONCAT(u.firstname ," ",  u.lastname) as completed_by,
                                m.machine_name,
                                s.size_value as size_to_be_drawn,
                                l.length_value as length_to_be_cut
                            '
                        );
        $this->db->from('draw_process_history as dh');
        $this->db->join('users as u', 'dh.completed_by = u.user_id');
        $this->db->join('machine as m', 'dh.machine_id = m.machine_id');
        $this->db->join('size as s', 'dh.size_id = s.size_id');
        $this->db->join('length as l', 'dh.length_id = l.length_id');
        $this->db->where('dh.draw_process_id', $id);
        return $this->db->get()->result_array();
    }

//end class

}
