<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Subcategory extends Backend_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {

        $this->db->select('c.category_id, c.sub_category_id, c.sub_category_name');
        $this->db->from('sub_category as c');
        $this->db->order_by('c.sub_category_name', 'asc');
        $category = $this->db->get()->result_array();
        $this->data['category'] = $category;
        $this->data['page_title'] = 'Sub-Category';
        $this->admin_view('backend/subcategory/index', $this->data);
    }



    public function getcategory()
    {
        $this->db->select('category_id, category_name');
        $this->db->from('category');
        return $this->db->get()->result_array();
    }




    public function add()
    {

        // print_r($this->input->post());
        //         die;

        $this->data['main_categories'] = $this->getcategory();
        // var_dump($this->input->post()); exit;
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $this->form_validation->set_rules('name', 'Category Name', 'required|alpha_numeric_spaces');
            $this->form_validation->set_rules('parent', 'Parent', 'required|numeric');
            if ($this->form_validation->run() === FALSE) {

                

                $this->session->set_flashdata('notification', "Incorrect details");
                $this->admin_view('backend/subcategory/add', $this->data);
            } else {
                $data['sub_category_name'] = strtoupper($this->input->post('name'));
                $data['category_id'] = $this->input->post('parent');

                

                $insert = $this->subcategory_m->addCategory($data);



                if ($insert == true) {
                    $this->session->set_flashdata('notification', "Sub Category has successfully created.");
                    $this->admin_view('backend/subcategory/add', $this->data);
                } else {
                    $this->session->set_flashdata('notification', "There is some error! Please try again.");
                    $this->admin_view('backend/subcategory/add', $this->data);
                }
            }
        } else {
            $this->data['page_title'] = 'add Sub categories';
            // var_dump($this->data); exit;
            $this->admin_view('backend/subcategory/add', $this->data);
        }

    
    }



    public function edit($id)
    {
        $this->data['main_categories'] = $this->getcategory();
        if ($this->input->post()) {
            $this->form_validation->set_rules('name', 'Category', 'trim|required');
            $this->form_validation->set_rules('parent', 'Parent', 'required|numeric');
            if ($this->form_validation->run() == FALSE) {
                $this->data['category_id'] = $id;
                $this->data['category'] = $this->subcategory_m->getsubCategory($id);
                $this->data['page_title'] = 'Edit sub-category';
                $this->admin_view('backend/subcategory/edit', $this->data);
            } else {
                $data = array(
                    'sub_category_name' => strtoupper($this->input->post('name')),
                    'category_id' => $this->input->post('parent'),
                    'updated_on' => $this->current_time,
                );
                $this->session->set_flashdata('notification', "Category is updated successfully.");
                $this->db->where('sub_category_id', $id);
                $this->db->update('sub_category', $data);
                redirect('edit-subcategory-' . $id);
            }
        } else {
            $this->data['sub_category_id'] = $id;
            $this->data['category'] = $this->subcategory_m->getsubCategory($id);
            $this->data['page_title'] = 'Edit sub-category';;
            $this->admin_view('backend/subcategory/edit', $this->data);
        }
    }



    public function getSubCategoryByCategory($id)
    {
        $this->db->select('sub_category_id, sub_category_name');
        $this->db->from('category');
        $this->db->where('category_id', $id);
        return $this->db->get()->result_array();
    }

    public function getSubCategoryType()
    {
        $response = $this->subcategory_m->getSubCategoryType($this->input->post('subcategory'));
        echo json_encode($response);
    }


    // CLASS ENDS
}
