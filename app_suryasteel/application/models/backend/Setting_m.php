<?php 
defined('BASEPATH') or exit('no dierct script access allowed');

class Setting_m extends MY_Model {

	protected $tbl_name = 'setting';
    protected $primary_col = 'id';
    protected $order_by = 'created_on';

    public function __construct()
	{
		parent::__construct();   
	}


	public function update_system_settings() {

        $data['value'] = html_escape($this->input->post('app_name'));
        $this->db->where('name', 'app_name');
        $this->db->update('settings', $data);

        $data['value'] = html_escape($this->input->post('app_description'));
        $this->db->where('name', 'app_description');
        $this->db->update('settings', $data);

        $data['value'] = html_escape($this->input->post('address'));
        $this->db->where('name', 'address');
        $this->db->update('settings', $data);

        $data['value'] = html_escape($this->input->post('author_name'));
        $this->db->where('name', 'author_name');
        $this->db->update('settings', $data);

        $data['value'] = html_escape($this->input->post('whatsappno'));
        $this->db->where('name', 'APP_WHATSAPP_NO');
        $this->db->update('settings', $data);

    }

    


//end class

}
