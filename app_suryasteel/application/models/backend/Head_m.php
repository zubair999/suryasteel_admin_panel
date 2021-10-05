<?php 
defined('BASEPATH') or exit('no dierct script access allowed');

class Head_m extends MY_Model {

	protected $tbl_name = 'head_process';
    protected $primary_col = 'head_process_id';
    protected $order_by = 'created_on';

    public function __construct()
	{
		parent::__construct();   
	}

    public $headHistoryRules = array(
        0 => array(
            'field' => 'pieceHeaded',
            'label' => 'Round/Length',
            'rules' => 'trim|required|is_natural'
        ),
    );

    public function getHeadBatchById($id) {
        return $this->db->get_where('head_process', array('head_process_id'=> $id))->row();
    }

    public function addHeadBatch($forgingProcessHistoryId, $size, $length){
        $data = array(
            'purchase_item_id' => $this->input->post('purchaseItemId'),
            'forging_process_history_id' => $forgingProcessHistoryId,
            'size_id' => $size,
            'length_id' => $length,
            'piece_to_be_head' => $this->input->post('pieceForged'),
            'created_on' => $this->today
        );
        return $this->db->insert('head_process', $data);
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

    public function addHeadHistory($completedBy){
        $headProcess = $this->getHeadBatchById($this->input->post('headProcessId'));
        $pieceAlreadyHeaded = (int)$headProcess->piece_headed + (int)$this->input->post('pieceHeaded');        
        $isAddedPieceGreaterThanCompletedPieceHeaded = is_greater_than($headProcess->piece_to_be_head, $pieceAlreadyHeaded);
        if($isAddedPieceGreaterThanCompletedPieceHeaded){
            $data1 = array(
                'piece_headed' => $pieceAlreadyHeaded,
                'process_status_catalog_id' => get_process_status($headProcess->piece_to_be_head, $pieceAlreadyHeaded),
                'updated_on' => $this->today
            );

            $this->db->where('head_process_id', $this->input->post('headProcessId'));
            $this->db->update('head_process', $data1);
            

            $data = array(
                'headed_by' => $completedBy,
                'purchase_item_id' => $this->input->post('purchaseItemId'),
                'head_process_id' => $this->input->post('headProcessId'),
                'machine_id' => $this->input->post('machineId'),
                'piece_headed' => $this->input->post('pieceHeaded'),
                'remarks' => $this->input->post('remarks'),
                'created_on' => $this->today,
            );
            $this->db->insert('head_process_history', $data);
            $headProcessHistoryId = $this->db->insert_id();
            $this->drill_m->addDrillBatch($headProcessHistoryId, $headProcess->size_id, $headProcess->length_id);

            return ['status'=>'success', 'message'=>'The piece heading work added successfully.'];
        }
        else{
            return ['status'=>'error', 'message'=>'Completed round cannot be more than round drawn earlier in the draw process.'];
        }
    }
    

    public function get_head_batch(){
        $this->db->select(
                            'h.head_process_id,
                             h.purchase_item_id,
                             h.cutting_process_id,
                             h.piece_to_be_head,
                             h.piece_headed,
                             h.remarks,
                             DATE_FORMAT(h.created_on, "%d-%b-%Y") as created_on,
                             DATE_FORMAT(h.updated_on, "%d-%b-%Y") as updated_on,
                             p.status_value,
                             p.status_color,
                             s.size_value
                             '
                        );
        $this->db->from('head_process as h');
        $this->db->join('process_status_catalog as p', 'h.process_status_catalog_id = p.process_status_catalog_id');
        $this->db->join('size as s', 'h.size_id  = s.size_id ');
        
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
        $this->db->order_by('h.process_status_catalog_id', 'asc');
        $head_batch = $this->db->get()->result_array();
        
        foreach ($head_batch as $key => $hb){
            $head_history = $this->get_head_history_by_head_batch_id($hb['head_process_id']);
            $head_batch[$key]['head_history'] = $head_history;
        }

        return $head_batch;
        // FUNCTION ENDS
    }


    public function get_head_history_by_head_batch_id($id){
        $this->db->select(
                            '
                                hh.head_process_history_id, 
                                hh.purchase_item_id, 
                                hh.cutting_process_id,
                                hh.head_process_id,
                                hh.piece_headed,
                                hh.remarks,
                                DATE_FORMAT(hh.created_on, "%d-%b-%Y") as created_on,
                                DATE_FORMAT(hh.updated_on, "%d-%b-%Y") as updated_on,
                                CONCAT(u.firstname ," ",  u.lastname) as headed_by,
                                m.machine_name
                            '
                        );
        $this->db->from('head_process_history as hh');
        $this->db->join('users as u', 'hh.headed_by = u.user_id');
        $this->db->join('machine as m', 'hh.machine_id = m.machine_id');
        $this->db->where('hh.head_process_id', $id);
        $head_history =  $this->db->get()->result_array();
        return $head_history;
    }

//end class

}
