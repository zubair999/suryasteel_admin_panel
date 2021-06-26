<?php

class Brand extends Backend_Controller{

	public function __construct(){
		parent::__construct();
	}


	public function add(){
		if($this->input->post()){
			$this->form_validation->set_rules('brand_name', 'Brand', 'trim|required');
			$this->form_validation->set_rules('status', 'Status', 'trim|required');
			$this->form_validation->set_rules('thumbnail_id', 'Thumbnail', 'trim|required');
			if($this->form_validation->run() == FALSE){
				$this->data['page_title'] = 'add brand';
				$this->admin_view('backend/brand/add', $this->data);
			}
			else{
				$data = array(
					'brand_name' => strtoupper($this->input->post('brand_name')),
					'thumbnail' => $this->input->post('thumbnail_id'),
					'status' => $this->input->post('status'),
					'created_by' => $this->uid,
					'created_on' => $this->current_time,
				);			
				$this->session->set_flashdata('notification', "You successfully read this important alert message.");
				$isDataSave = $this->db->insert('brand', $data);
				$this->data['page_title'] = 'add brand';
				$this->admin_view('backend/brand/add', $this->data);
			}
		}
		else{
			$this->data['page_title'] = 'add brand';;
			$this->admin_view('backend/brand/add', $this->data);
		}
	}

	public function edit($id){
		if($this->input->post()){
			$this->form_validation->set_rules('brand_name', 'Brand', 'trim|required');
			$this->form_validation->set_rules('status', 'Status', 'trim|required');
			$this->form_validation->set_rules('thumbnail_id', 'Thumbnail', 'trim|required');
			if($this->form_validation->run() == FALSE){
				$this->data['brand'] = $this->brand_m->getBrand($id);
				$this->data['status'] = $this->status();
				$this->data['page_title'] = 'Edit brand';
				$this->admin_view('backend/brand/edit', $this->data);
			}
			else{
				$data = array(
					'brand_name' => strtoupper($this->input->post('brand_name')),
					'thumbnail' => $this->input->post('thumbnail_id'),
					'status' => $this->input->post('status'),
					'updated_on' => $this->current_time,
				);			
				$this->session->set_flashdata('notification', "Brand is updated successfully.");
				$this->db->where('brand_id',$id);
				$this->db->update('brand', $data);
				redirect('edit-brand-'.$id);
			}
		}
		else{
			$this->data['brand_id'] = $id;
			$this->data['brand'] = $this->brand_m->getBrand($id);
			$this->data['status'] = $this->status();
			$this->data['page_title'] = 'Edit brand';;
			$this->admin_view('backend/brand/edit', $this->data);
		}
	}
	
	public function index(){
		$this->db->select('brand.brand_id, brand.brand_name, brand.product_count, images.actual, images.thumbnail');
		$this->db->from('brand');
		$this->db->join('images', 'brand.thumbnail = images.image_id');
		$this->db->order_by('brand.brand_name', 'asc');
		$brand = $this->db->get()->result_array();

		foreach($brand as $key => $b){
            $brand[$key]['actual'] = BASEURL.'upload/'.$b['actual'];
			$brand[$key]['thumbnail'] = BASEURL.'upload/'.$b['thumbnail'];
        }

		$this->data['brand'] = $brand;
		$this->data['page_title'] = 'Brand';
		$this->admin_view('backend/brand/index', $this->data);
    }


	



//CLASS ENDS
}