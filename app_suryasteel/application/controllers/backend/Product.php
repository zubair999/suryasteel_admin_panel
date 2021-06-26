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
                  1 => 'product detail',
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
			$this->form_validation->set_rules('product_name', 'Product', 'trim|required');
			if($this->form_validation->run() == FALSE){
                $this->data['brand'] = $this->brand_m->getAllBrand();
                $this->data['status'] = $this->status();
                $this->data['type'] = $this->type_m->getAllType();
                $this->data['gst'] = $this->gst_m->getAllGst();
                $this->data['category'] = $this->category_m->getAllCategory();
				$this->data['page_title'] = 'add product';
                $this->session->set_flashdata('notification', "Please fill the carefully!");
				$this->admin_view('backend/product/add', $this->data);
			}
			else{
				$product = array(
					'product_name' => ucwords($this->input->post('product_name')),
					'thumbnail' => $this->input->post('thumbnail_id'),
					'brand_id' => $this->input->post('brand'),
                    'category_id' => $this->input->post('category'),
					'sub_category' => $this->input->post('subcategory'),
                    'sub_category_type' => $this->input->post('subcategorytype'),
					'gst_id' => $this->input->post('gst'),
                    'type' => $this->input->post('type'),
					'mrp' => $this->input->post('mrp'),
                    'sell_price' => $this->input->post('sell_price'),
                    'mrp' => $this->input->post('mrp'),
                    'shipping' => 100,
					'created_by' => $this->uid,
					'created_on' => $this->current_time,
				);
                $isDataSave = $this->db->insert('products', $product);
                $this->category_m->increaseProductCountInCategory($this->input->post('category'),$this->input->post('subcategory'),$this->input->post('subcategorytype'));
                $this->data['brand'] = $this->brand_m->getAllBrand();
                $this->data['status'] = $this->status();
                $this->data['type'] = $this->type_m->getAllType();
                $this->data['gst'] = $this->gst_m->getAllGst();
                $this->data['category'] = $this->category_m->getAllCategory();
				$this->session->set_flashdata('notification', "Product added successfully");
				$this->data['page_title'] = 'add product';
				$this->admin_view('backend/product/add', $this->data);
			}
		}
		else{
            $this->data['brand'] = $this->brand_m->getAllBrand();
            $this->data['status'] = $this->status();
            $this->data['type'] = $this->type_m->getAllType();
            $this->data['size'] = $this->size_m->getAllSize();
            $this->data['quality'] = $this->quality_m->getAllQuality();
            $this->data['gst'] = $this->gst_m->getAllGst();
            $this->data['category'] = $this->category_m->getAllCategory();
			$this->data['page_title'] = 'add product';;
			$this->admin_view('backend/product/add', $this->data);
		}
	}

    public function edit($id){
		if($this->input->post()){
			$this->form_validation->set_rules('product_name', 'Product', 'trim|required');
			if($this->form_validation->run() == FALSE){
                $this->data['product_id'] = $id;
                $this->data['status'] = $this->status();
                $this->data['product'] = $this->product_m->getProductById($id);
				$this->data['brand'] = $this->brand_m->getAllBrand();
                $this->data['type'] = $this->type_m->getAllType();
                $this->data['gst'] = $this->gst_m->getAllGst();
                $this->data['category'] = $this->category_m->getAllCategory();
				$this->data['page_title'] = 'edit product';
				$this->admin_view('backend/product/edit', $this->data);
			}
			else{
				// $this->category_m->updateProductCountInCategory($this->input->post('category'),$this->input->post('subcategory'),$this->input->post('subcategorytype'));


                $product = array(
					'product_name' => ucwords($this->input->post('product_name')),
					'thumbnail' => $this->input->post('thumbnail_id'),
					'brand_id' => $this->input->post('brand'),
                    'category_id' => $this->input->post('category'),
					'sub_category' => $this->input->post('subcategory'),
                    'sub_category_type' => $this->input->post('subcategorytype'),
					'gst_id' => $this->input->post('gst'),
                    'type' => $this->input->post('type'),
					'mrp' => $this->input->post('mrp'),
                    'sell_price' => $this->input->post('sell_price'),
                    'mrp' => $this->input->post('mrp'),
                    'shipping' => 100,
					'created_by' => $this->uid,
                    'updated_on' => $this->current_time,
				);


                $this->db->where('product_id',$id);
				$this->db->update('products', $product);
                
                $this->session->set_flashdata('notification', "You successfully read this important alert message.");
                $this->data['status'] = $this->status();
				$this->data['brand'] = $this->brand_m->getAllBrand();
                $this->data['type'] = $this->type_m->getAllType();
                $this->data['gst'] = $this->gst_m->getAllGst();
                $this->data['category'] = $this->category_m->getAllCategory();
				$this->data['page_title'] = 'edit product';
				redirect('edit-product-'.$id);
			}
		}
		else{
            $this->data['product_id'] = $id;
            $this->data['status'] = $this->status();
            $this->data['product'] = $this->product_m->getProductById($id);
			$this->data['brand'] = $this->brand_m->getAllBrand();
            $this->data['type'] = $this->type_m->getAllType();
            $this->data['gst'] = $this->gst_m->getAllGst();
            $this->data['category'] = $this->category_m->getAllCategory();
            $this->data['subcategory'] = $this->subcategory_m->getAllSubCategory();
            $this->data['subcategorytype'] = $this->subcategorytype_m->getAllSubCategoryType();
            $this->data['product_id'] = $id;
			$this->data['page_title'] = 'edit product';;
			$this->admin_view('backend/product/edit', $this->data);
		}
	}



// CLASS ENDS
}


 
