<?php 
defined('BASEPATH') or exit('no dierct script access allowed');

class Category_m extends MY_Model {

	protected $tbl_name = 'category';
    protected $primary_col = 'category_id';
    protected $order_by = 'created_on';

    public function __construct()
	{
		parent::__construct();   
	}

    public function getAllCategory() {
        $this->db->select('category_id, category_name');
        $this->db->from('category');
        $this->db->where('status', 'active');
        return $this->db->get()->result_array();
    }

    public function getCategory($id){
        $this->db->select('c.category_id, c.category_name, c.thumbnail as thumb_id,  i.thumbnail');
        $this->db->from('category as c');
        $this->db->join('images as i', 'c.thumbnail = i.image_id');
        $this->db->where('c.category_id', $id);
        $data = $this->db->get()->row();
        $data->thumbnail = BASEURL.'upload/'.$data->thumbnail;
        return $data;
        // FUNCTION ENDS
    }

	public function getBrand($id){
        $this->db->select('b.brand_id, b.brand_name, b.status, b.thumbnail as thumb_id,  i.thumbnail');
        $this->db->from('brand as b');
        $this->db->join('images as i', 'b.thumbnail = i.image_id');
        $this->db->where('b.brand_id', $id);
        $data = $this->db->get()->row();
        $data->thumbnail = BASEURL.'upload/'.$data->thumbnail;
        return $data;
    }

    public function getSubcategoryByParent($categoryId){
        return $this->db->get_where('sub_category', array('category_id' => $categoryId))->result_array();
    }

    public function increaseProductCountInCategory($categoryId, $subCategoryId, $subCategoryTypeId){
        $category = $this->db->get_where('category', array('category_id' => $categoryId))->row();
        $previousCategoryProductCount = $category->product_count;
        
        $subCategory = $this->db->get_where('sub_category', array('sub_category_id' => $subCategoryId))->row();
        $previousSubCategoryProductCount = $subCategory->product_count;

        $subCategoryType = $this->db->get_where('sub_category_type', array('sub_category_type_id' => $subCategoryTypeId))->row();
        $previousSubCategoryTypeProductCount = $subCategoryType->product_count;

        $this->db->set('product_count', $previousCategoryProductCount + 1);
        $this->db->where('category_id', $categoryId);
        $this->db->update('category');

        $this->db->set('product_count', $previousSubCategoryProductCount + 1);
        $this->db->where('sub_category_id', $subCategoryId);
        $this->db->update('sub_category');

        $this->db->set('product_count', $previousSubCategoryTypeProductCount + 1);
        $this->db->where('sub_category_type_id', $subCategoryTypeId);
        $this->db->update('sub_category_type');
    }

    public function updateProductCountInCategory($categoryId, $subCategoryId, $subCategoryTypeId){
        $category = $this->db->get_where('category', array('category_id' => $categoryId))->row();
        $previousCategoryProductCount = $category->product_count;
        
        $subCategory = $this->db->get_where('sub_category', array('sub_category_id' => $subCategoryId))->row();
        $previousSubCategoryProductCount = $subCategory->product_count;

        $subCategoryType = $this->db->get_where('sub_category_type', array('sub_category_type_id' => $subCategoryTypeId))->row();
        $previousSubCategoryTypeProductCount = $subCategoryType->product_count;

        $this->db->set('product_count', $previousCategoryProductCount + 1);
        $this->db->where('category_id', $categoryId);
        $this->db->update('category');

        $this->db->set('product_count', $previousSubCategoryProductCount + 1);
        $this->db->where('sub_category_id', $subCategoryId);
        $this->db->update('sub_category');

        $this->db->set('product_count', $previousSubCategoryTypeProductCount + 1);
        $this->db->where('sub_category_type_id', $subCategoryTypeId);
        $this->db->update('sub_category_type');
    }


    public function get_all_category_with_image(){
        $this->db->select('c.category_id, c.category_name, c.product_count, c.thumbnail as image_id, i.actual, i.thumbnail');
		$this->db->from('category as c');
		$this->db->join('images as i', 'c.thumbnail = i.image_id');
		$category = $this->db->get()->result_array();

		foreach($category as $key => $c){
            $category[$key]['actual'] = BASEURL.'upload/'.$c['actual'];
			$category[$key]['thumbnail'] = BASEURL.'upload/'.$c['thumbnail'];			
		}
        return $category;
    }

//end class

}
