<?php 
defined('BASEPATH') or exit('no dierct script access allowed');

class Drill_m extends MY_Model {

	protected $tbl_name = 'drill_process';
    protected $primary_col = 'drill_process_id';
    protected $order_by = 'created_on';

    public function __construct()
	{
		parent::__construct();   
	}

    public $drillHistoryRules = array(
        0 => array(
            'field' => 'pieceDrilled',
            'label' => 'Piece drilled',
            'rules' => 'trim|required|is_natural'
        ),
    );

    public function getDrillBatchById($id) {
        return $this->db->get_where('drill_process', array('drill_process_id'=> $id))->row();
    }

    public function addDrillBatch($headProcessHistotryId, $size, $length){
        $data = array(
            'purchase_item_id' => $this->input->post('purchaseItemId'),
            'head_process_history_id' => $headProcessHistotryId,
            'size_id' => $size,
            'length_id' => $length,
            'piece_to_be_drill' => $this->input->post('pieceHeaded'),
            'created_on' => $this->today
        );
        return $this->db->insert('drill_process', $data);
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

    public function addDrillHistory($completedBy){
        $drillProcess = $this->getDrillBatchById($this->input->post('drillProcessId'));
        $peiceAlreadyDrilled = (int)$drillProcess->piece_drilled + (int)$this->input->post('pieceDrilled');        
        $isAddedRoundGreaterThanCompletedRound = is_greater_than($drillProcess->piece_to_be_drill, $peiceAlreadyDrilled);
        if($isAddedRoundGreaterThanCompletedRound){
            $data1 = array(
                'piece_drilled' => $peiceAlreadyDrilled,
                'process_status_catalog_id' => get_process_status($drillProcess->piece_to_be_drill, $peiceAlreadyDrilled),
                'updated_on' => $this->today
            );

            $this->db->where('drill_process_id', $this->input->post('drillProcessId'));
            $this->db->update('drill_process', $data1);
            

            $data = array(
                'drilled_by' => $completedBy,
                'purchase_item_id' => $this->input->post('purchaseItemId'),
                'drill_process_id' => $this->input->post('drillProcessId'),
                'machine_id' => $this->input->post('machineId'),
                'piece_drilled' => $this->input->post('pieceDrilled'),
                'remarks' => $this->input->post('remarks'),
                'created_on' => $this->today,
            );
            $this->db->insert('drill_process_history', $data);
            $drillProcessHistoryId = $this->db->insert_id();
            $this->welding_m->addWeldingBatch($drillProcessHistoryId, $drillProcess->size_id, $drillProcess->length_id);
            return ['status'=>'success', 'message'=>'The piece drilled successfully.'];
        }
        else{
            return ['status'=>'error', 'message'=>'Completed round cannot be more than round drawn earlier in the draw process.'];
        }
    }
    

    public function get_drill_batch(){
        $this->db->select(
                            'd.drill_process_id,
                             d.purchase_item_id,
                             d.piece_to_be_drill,
                             d.piece_drilled,
                             d.remarks,
                             DATE_FORMAT(d.created_on, "%d-%b-%Y") as created_on,
                             DATE_FORMAT(d.updated_on, "%d-%b-%Y") as updated_on,
                             p.status_value,
                             p.status_color,
                             s.size_value
                             '
                        );
        $this->db->from('drill_process as d');
        $this->db->join('process_status_catalog as p', 'd.process_status_catalog_id = p.process_status_catalog_id');
        $this->db->join('size as s', 'd.size_id  = s.size_id ');
        
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
        $drill_batch = $this->db->get()->result_array();
        
        foreach ($drill_batch as $key => $db){
            $drill_history = $this->get_drill_history_by_drill_batch_id($db['drill_process_id']);
            $drill_batch[$key]['drill_history'] = $drill_history;
        }

        return $drill_batch;
        // FUNCTION ENDS
    }


    public function get_drill_history_by_drill_batch_id($id){
        $this->db->select(
                            '
                                dh.drill_process_history_id, 
                                dh.purchase_item_id, 
                                dh.drill_process_id,
                                dh.piece_drilled,
                                dh.remarks,
                                DATE_FORMAT(dh.created_on, "%d-%b-%Y") as created_on,
                                DATE_FORMAT(dh.updated_on, "%d-%b-%Y") as updated_on,
                                CONCAT(u.firstname ," ",  u.lastname) as drilled_by,
                                m.machine_name
                            '
                        );
        $this->db->from('drill_process_history as dh');
        $this->db->join('users as u', 'dh.drilled_by = u.user_id');
        $this->db->join('machine as m', 'dh.machine_id = m.machine_id');
        $this->db->where('dh.drill_process_id', $id);
        $drill_history =  $this->db->get()->result_array();
        return $drill_history;
    }

//end class

}
