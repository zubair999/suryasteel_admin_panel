<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Gift extends Backend_Controller {
    public function __construct() {
        parent::__construct();
    }

    public function index(){
        $this->data['page_title'] = 'Gift Voucher';
        $this->admin_view('backend/gift_voucher/index', $this->data);
    }

    public function add(){        
        if($this->input->server('REQUEST_METHOD') === 'POST'){
            $this->form_validation->set_rules('name', 'Category Name', 'required|alpha_numeric');
            $this->form_validation->set_rules('parent', 'Parent', 'required|numeric');
            if($this->form_validation->run() === FALSE){
                echo "form error";
                die;
            }
            else{
                echo "form ok";
                die;
            }
        }
        else{
            $this->data['page_title'] = 'add categories';
            $this->admin_view('backend/categories/add', $this->data);
        }
    }



// CLASS ENDS
}
