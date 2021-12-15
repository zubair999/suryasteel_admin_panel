<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Product extends Backend_Controller {
    public function __construct() {
        parent::__construct();
    }

    public function index(){
        $this->data['drawTable'] 	= $this->productTableHead();
		$this->data['tableId']	    =	'productlist';
		$this->data['pl']			=	'add-product';
        $this->data['page_title'] = 'product list';
        $this->admin_view('backend/product/index', $this->data);
    }
    public function productTableHead(){
        $tableHead = array(
                  0 => 'sr. no.',
                  1 => 'product name',
                  2 => 'Action'
        );
        return $tableHead;
    }
    public function getProduct(){
        $data = $this->product_m->getProduct();
        echo json_encode($data);
    }

    public function add(){
		if($this->input->post()){
			$this->form_validation->set_rules($this->product_m->productAddRulesApp);
			if($this->form_validation->run() == FALSE){
                $this->data['status'] = $this->status();
                $this->data['gst'] = $this->gst_m->getAllGst();
                $this->data['category'] = $this->category_m->getAllCategory();
				$this->data['page_title'] = 'add product';
                $this->session->set_flashdata('error', "Please fill the carefully!");
				$this->admin_view('backend/product/add', $this->data);
			}
			else{
                $this->data['status'] = $this->status();
                $this->data['gst'] = $this->gst_m->getAllGst();
                $this->data['length'] = $this->length_m->getAllLength();
                $this->data['size'] = $this->size_m->getAllSize();
                $this->data['galvanisation'] = $this->product_m->galvanisation();
                $this->data['yesno'] = $this->product_m->yesno();
                $this->data['category'] = $this->category_m->getAllCategory();
                $this->data['page_title'] = 'add product';                
                $this->session->set_flashdata('success', "New product added successfully.");
                $this->product_m->addProduct($this->uid);
                $this->admin_view('backend/product/add', $this->data);
			}
		}
		else{
            $this->data['status'] = $this->status();
            $this->data['gst'] = $this->gst_m->getAllGst();
            $this->data['category'] = $this->category_m->getAllCategory();
            $this->data['length'] = $this->length_m->getAllLength();
            $this->data['size'] = $this->size_m->getAllSize();
            $this->data['galvanisation'] = $this->product_m->galvanisation();
            $this->data['yesno'] = $this->product_m->yesno();
			$this->data['page_title'] = 'add product';;
			$this->admin_view('backend/product/add', $this->data);
		}
	}

    public function edit($id){
		if($this->input->post()){
			$this->form_validation->set_rules($this->product_m->productAddRulesApp);
			if($this->form_validation->run() == FALSE){
                $this->data['product_id'] = $id;
                $this->data['product'] = $this->product_m->get_product($id);
                $this->data['length'] = $this->length_m->getAllLength();
                $this->data['size'] = $this->size_m->getAllSize();
                $this->data['galvanisation'] = $this->product_m->galvanisation();
                $this->data['yesno'] = $this->product_m->yesno();
                $this->data['category'] = $this->category_m->getAllCategory();
				$this->data['page_title'] = 'edit product';
				$this->admin_view('backend/product/edit', $this->data);
			}
			else{
                $this->product_m->editProduct($id);
                $this->data['status'] = $this->status();
                $this->data['length'] = $this->length_m->getAllLength();
                $this->data['size'] = $this->size_m->getAllSize();
                $this->data['galvanisation'] = $this->product_m->galvanisation();
                $this->data['yesno'] = $this->product_m->yesno();
                $this->data['category'] = $this->category_m->getAllCategory();
                $this->session->set_flashdata('success', "New product added successfully.");
				$this->data['page_title'] = 'edit product';
				redirect('edit-product-'.$id);
			}
		}
		else{
            $this->data['product_id'] = $id;
            $this->data['product'] = $this->product_m->get_product($id);
            $this->data['length'] = $this->length_m->getAllLength();
            $this->data['size'] = $this->size_m->getAllSize();
            $this->data['galvanisation'] = $this->product_m->galvanisation();
            $this->data['yesno'] = $this->product_m->yesno();
            $this->data['category'] = $this->category_m->getAllCategory();
			$this->data['page_title'] = 'edit product';;
			$this->admin_view('backend/product/edit', $this->data);
		}
	}



// CLASS ENDS
}


 
