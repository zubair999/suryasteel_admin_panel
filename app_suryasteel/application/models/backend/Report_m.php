<?php 
defined('BASEPATH') or exit('no dierct script access allowed');

class Report_m extends MY_Model {

    public function __construct()
	{
		parent::__construct();   
	}

    public function get_welding_batch(){
        $this->db->select(
                            'w.welding_process_id,
                             w.purchase_item_id,
                             w.piece_to_be_weld,
                             w.piece_welded,
                             w.remarks,
                             DATE_FORMAT(w.created_on, "%d-%b-%Y") as created_on,
                             DATE_FORMAT(w.updated_on, "%d-%b-%Y") as updated_on,
                             p.status_value,
                             p.status_color,
                             s.size_value,
                             l.length_value
                             '
                        );
        $this->db->from('welding_process as w');
        $this->db->join('process_status_catalog as p', 'w.process_status_catalog_id = p.process_status_catalog_id');
        $this->db->join('size as s', 'w.size_id  = s.size_id ');
        $this->db->join('length as l', 'w.length_id  = l.length_id ');

        
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

//end class

}
