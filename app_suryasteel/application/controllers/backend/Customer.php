<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer extends Backend_Controller {
    public function __construct() {
        parent::__construct();
    }

    public function index(){
        if(!in_array('viewCustomer', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
        $this->data['drawTable'] 	= $this->customerTableHead();
		$this->data['tableId']	    =	'customerlist';
		$this->data['pl']			=	'add-customer';
        $this->data['page_title'] = 'customer list';
        $this->admin_view('backend/customer/index', $this->data);
    }
    public function customerTableHead(){
        $tableHead = array(
                  0 => 'sr. no.',
                  1 => 'company name',
                  2 => 'username/email',
                  3 => 'contact no',
                  4 => 'customer name'
                //   5 => 'gst_reg_type',
                //   5 => 'status',
                //   7 => 'action'
        );
        return $tableHead;
    }
    public function getCustomer(){
        $data = $this->customer_m->getCustomer();
        echo json_encode($data);
    }

    public function add(){
        if(!in_array('addCustomer', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
		if($this->input->post()){
			$this->form_validation->set_rules($this->customer_m->customerAddRules);
			if($this->form_validation->run() == FALSE){
                $this->data['state'] = $this->state_m->getAllState();
                $this->data['yesOrNo'] = $this->yesOrNo();
                $this->data['gst_reg_type'] = $this->gst_reg_type_m->getAllGstRegType();
				$this->data['page_title'] = 'add customer';
                $this->session->set_flashdata('error', "Please fill the form carefully!");
				$this->admin_view('backend/customer/add', $this->data);
			}
			else{
				$customerData = array(
                    'firstname' => $this->input->post('firstname'),
                    'lastname' => $this->input->post('lastname'),
                    'email' => $this->input->post('username'),
                    'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                    'mobile_no' => $this->input->post('mobileno'),
                    'customer_company' => $this->input->post('companyName'),
                    'gst_reg_type' => $this->input->post('gstRegType'),
                    'gstn' => $this->input->post('gst_no'),
                    'plot_factory_no' => $this->input->post('plotFactoryNo'),
                    'complete_address' => $this->input->post('fullAddress'),
                    'state_id' => $this->input->post('state'),
                    'landmark' => $this->input->post('landmark'),
                    'is_active' => 'active',
                    'is_allowed_to_view_product' => $this->input->post('yesno'),
                    'created_by' => $this->uid,
                    'created_on' => $this->today
                );
                $this->db->insert('users', $customerData);

                $logData = array(
                    'user_id' => $this->uid,
                    'title' => 'Customer',
                    'description' => 'A customer is added succesfully',
                    'created_on' => $this->today
                );
                $this->db->insert('logs', $logData);
                $this->data['state'] = $this->state_m->getAllState();
                $this->data['yesOrNo'] = $this->yesOrNo();
                $this->data['gst_reg_type'] = $this->gst_reg_type_m->getAllGstRegType();
				$this->session->set_flashdata('success', "Customer added successfully.");
				redirect('add-customer','refresh');
			}
		}
		else{
            $this->data['state'] = $this->state_m->getAllState();
            $this->data['yesOrNo'] = $this->yesOrNo();
            $this->data['gst_reg_type'] = $this->gst_reg_type_m->getAllGstRegType();
			$this->data['page_title'] = 'add customer';;
			$this->admin_view('backend/customer/add', $this->data);
		}
	}


    public function edit($id){
        if(!in_array('editCustomer', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
        $this->data['user'] = $this->auth_m->getUserById($id);
		if($this->input->post()){
            if($this->input->post('mobileno') != $this->data['user']->mobile_no) {
                $is_unique =  '|is_unique[users.mobile_no]';
            } else {
                $is_unique =  '';
            }

            $this->form_validation->set_rules('mobileno', 'Mobile no', 'trim|required|exact_length[10]|is_natural'.$is_unique);

			if($this->form_validation->run() == FALSE){
                $this->data['state'] = $this->state_m->getAllState();
                $this->data['status'] = $this->status();
                $this->data['yesOrNo'] = $this->yesOrNo();
                $this->data['gst_reg_type'] = $this->gst_reg_type_m->getAllGstRegType();
				$this->data['page_title'] = 'edit customer';
                $this->session->set_flashdata('error', "Please fill the form carefully!");
				$this->admin_view('backend/customer/edit', $this->data);
			}
			else{
				$customerData = array(
                    'firstname' => $this->input->post('firstname'),
                    'lastname' => $this->input->post('lastname'),
                    'mobile_no' => $this->input->post('mobileno'),
                    'customer_company' => $this->input->post('companyName'),
                    'gst_reg_type' => $this->input->post('gstRegType'),
                    'gstn' => $this->input->post('gst_no'),
                    'plot_factory_no' => $this->input->post('plotFactoryNo'),
                    'complete_address' => $this->input->post('fullAddress'),
                    'state_id' => $this->input->post('state'),
                    'landmark' => $this->input->post('landmark'),
                    'is_active' => $this->input->post('status'),
                    'is_allowed_to_view_product' => $this->input->post('yesno')
                );

                $this->db->where('user_id',$id);
                $this->db->update('users', $customerData);

                $logData = array(
                    'user_id' => $this->uid,
                    'title' => 'Customer',
                    'description' => 'A customer is updated succesfully',
                    'created_on' => $this->today
                );
                $this->db->insert('logs', $logData);

                $this->data['status'] = $this->status();
                $this->data['yesOrNo'] = $this->yesOrNo();
                $this->data['state'] = $this->state_m->getAllState();
                $this->data['gst_reg_type'] = $this->gst_reg_type_m->getAllGstRegType();
				$this->session->set_flashdata('success', "Customer updated successfully.");
				redirect('edit-customer-'.$id,'refresh');
			}
		}
		else{
            $this->data['status'] = $this->status();
            $this->data['yesOrNo'] = $this->yesOrNo();
            $this->data['state'] = $this->state_m->getAllState();
            $this->data['gst_reg_type'] = $this->gst_reg_type_m->getAllGstRegType();
			$this->data['page_title'] = 'edit customer';;
			$this->admin_view('backend/customer/edit', $this->data);
		}
	}

    



// CLASS ENDS
}


 
