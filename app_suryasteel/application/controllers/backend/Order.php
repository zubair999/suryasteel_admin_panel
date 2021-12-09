<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Order extends Backend_Controller {
    public function __construct() {
        parent::__construct();
    }

    public function index(){
        $this->data['drawTable'] 	= $this->orderTableHead();
		$this->data['tableId']	    =	'orderlist';
		$this->data['pl']			=	false;
        $this->data['page_title'] = 'order list';
        $this->admin_view('backend/order/index', $this->data);
    }
    public function orderTableHead(){
        $tableHead = array(
                  0 => 'sr. no.',
                  1 => 'customer',
                  3 => 'status',
                  4 => 'action',

        );
        return $tableHead;
    }

    public function getOrder(){
        $data = $this->order_m->getOrder();
        echo json_encode($data);
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


 
