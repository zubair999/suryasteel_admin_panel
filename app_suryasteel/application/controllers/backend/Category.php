<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Category extends Backend_Controller {
    public function __construct() {
        parent::__construct();
    }

    public function index(){

        $this->db->select('c.category_id, c.category_name, i.actual, i.thumbnail');
		$this->db->from('category as c');
		$this->db->join('images as i', 'c.thumbnail = i.image_id');
        $this->db->order_by('c.category_name', 'asc');
		$category = $this->db->get()->result_array();

		foreach($category as $key => $c){
            $category[$key]['actual'] = BASEURL.'upload/'.$c['actual'];
			$category[$key]['thumbnail'] = BASEURL.'upload/'.$c['thumbnail'];
        }

		$this->data['category'] = $category;
		$this->data['page_title'] = 'Category';
		$this->admin_view('backend/category/index', $this->data);

    }

    public function add(){
		if($this->input->post()){
			$this->form_validation->set_rules('name', 'Category', 'trim|required');
			$this->form_validation->set_rules('thumbnail_id', 'Thumbnail', 'trim|required');
			if($this->form_validation->run() == FALSE){
				$this->data['page_title'] = 'add category';
				$this->admin_view('backend/category/add', $this->data);
			}
			else{
				$data = array(
					'category_name' => strtoupper($this->input->post('name')),
					'thumbnail' => $this->input->post('thumbnail_id'),
					'created_by' => $this->uid,
					'created_on' => $this->current_time,
				);			
				$this->session->set_flashdata('notification', "You successfully read this important alert message.");
				$isDataSave = $this->db->insert('category', $data);
				$this->data['page_title'] = 'add category';
                redirect('add-category');
			}
		}
		else{
			$this->data['page_title'] = 'add category';;
			$this->admin_view('backend/category/add', $this->data);
		}
	}

    public function edit($id){
		if($this->input->post()){
			$this->form_validation->set_rules('name', 'Category', 'trim|required');
			if($this->form_validation->run() == FALSE){
				$this->data['category_id'] = $id;
				$this->data['category'] = $this->category_m->getCategory($id);
				$this->data['page_title'] = 'Edit category';
				$this->admin_view('backend/category/edit', $this->data);
			}
			else{
				$data = array(
					'category_name' => strtoupper($this->input->post('name')),
					'thumbnail' => $this->input->post('thumbnail_id'),
					'updated_on' => $this->current_time,
				);			
				$this->session->set_flashdata('notification', "Category is updated successfully.");
				$this->db->where('category_id',$id);
				$this->db->update('category', $data);
				redirect('edit-category-'.$id);
			}
		}
		else{
			$this->data['category_id'] = $id;
			$this->data['category'] = $this->category_m->getCategory($id);
			$this->data['page_title'] = 'Edit category';;
			$this->admin_view('backend/category/edit', $this->data);
		}
	}

    public function getSubCategory(){
        $response = $this->category_m->getSubcategoryByParent($this->input->post('category'));
        echo json_encode($response);
    }


// CLASS ENDS
}
