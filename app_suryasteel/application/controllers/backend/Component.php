<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Component extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        // parent::is_profile_set();
        $this->load->model('admin/component_m');
    }
/*View the state list*/
    public function index()
    {
        $data['tableId'] = 'componentListing';
        $data['pageTitle'] = 'component list';
        $data['pl'] = 'add-component';
        $data['drawTable'] = $this->subTableHead();
        $this->parsed('admin/components/main/index', $data);
    }


/* To Add Table head*/
    public function subTableHead()
    {
       $tableHead = array(
            'srno' => 'sr. no.',
            'name' =>'component name',
            'position' =>'position',
            'active' =>'active',
            'discount' =>'discount',
            'action' => 'action'
        );
        return $tableHead;
    }

/**/
    public function showcomponent(){
        $json_data = $this->component_m->showcomponent();
        echo json_encode($json_data);
    }

/*Added Profile of the school*/
    public function add_component(){
        $rules = $this->component_m->add_component_rules;
        $this->form_validation->set_rules($rules);
        if($this->form_validation->run() == TRUE){
            $result = $this->array_from_post(array('available_components_id','component_name','position','data_type','all_parents','discount','is_active'));
            $this->component_m->add($result,NULL);
            $this->notifier("component added successfully");
            redirect('view-component');
        }
        $this->data['page_title'] = 'add component';
        $this->data['comp_head_arr'] = $this->db->get_obj('available_components','*')->result_array();
        $this->data['data_type_arr'] = $this->db->get_obj('datatype','*')->result_array();
        $this->data['cate_c'] = $this->component_m->dispatch_category();
        $this->view('admin/components/main/add', $this->data);
    }

/*Update the school profile*/
    public function edit_component($component_id){
        $rules = $this->component_m->update_component_rules;
        $this->form_validation->set_rules($rules);
        if($this->form_validation->run() == TRUE){
            $component_id = $this->outh_m->Encryptor('decrypt', $component_id);
            $result = $this->array_from_post(array('available_components_id','component_name','position','data_type','all_parents','discount','is_active'));
            $this->component_m->add($result,$component_id);
            $this->notifier("component Name updated successfully");
            redirect('view-component');
        }
        $this->data['component_id'] = $component_id;
        $component_id = $this->outh_m->Encryptor('decrypt', $component_id);

    /*To get the state object*/
        $this->data['component_obj'] = $this->db->get_obj('components','*',array('components_id'=>$component_id))->row();
        $this->data['comp_head_arr'] = $this->db->get_obj('available_components','*')->result_array();
        $this->data['data_type_arr'] = $this->db->get_obj('datatype','*')->result_array();
        $this->data['page_title'] = 'edit component';
        $this->view('admin/components/main/edit', $this->data);
    }


/*To delete state*/
    public function delete_component($component_id){
        $component_id = $this->outh_m->Encryptor('decrypt', $component_id);
        $delete_id = $this->component_m->delete_component($component_id);
        if($delete_id == TRUE){
            $this->notifier("component deleted successfully");
            redirect('view-component');
        }
        $this->notifier("Something went wrong");
        redirect('view-component');
    }

}

/* End of file Colr.php */
/* Location: ./application/controllers/admin/component.php */
