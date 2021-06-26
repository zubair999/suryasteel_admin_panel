<?php

class Filter extends MY_Controller{

	public function __construct(){
		parent::__construct();
	}

	public function brandFilter(){
        $brands = $this->db->get('brand')->result_array();

        foreach($brands as $key => $b){
            $brands[$key]['label'] = $b['brand_name'];
            $brands[$key]['value'] = $b['brand_id'];
            unset($brands[$key]['brand_id']);
            unset($brands[$key]['brand_name']);
            unset($brands[$key]['product_count']);
            unset($brands[$key]['thumbnail']);
            unset($brands[$key]['created_on']);
            unset($brands[$key]['updated_on']);
        }

        echo json_encode($brands);
        exit();
	}

    public function categoryFilter(){
        $category = $this->db->get('category')->result_array();

        foreach($category as $key => $c){
            $category[$key]['label'] = $c['category_name'];
            $category[$key]['value'] = $c['category_id'];
            unset($category[$key]['category_id']);
            unset($category[$key]['category_name']);
            unset($category[$key]['thumbnail']);
            unset($category[$key]['created_on']);
            unset($category[$key]['updated_on']);
        }

        echo json_encode($category);
        exit();
	}

    public function subCategoryFilter($categoryId){
        $this->db->where('category_id', $categoryId);
        $category = $this->db->get('sub_category')->result_array();

        foreach($category as $key => $c){
            $category[$key]['label'] = $c['sub_category_name'];
            $category[$key]['value'] = $c['sub_category_id'];
            unset($category[$key]['category_id']);
            unset($category[$key]['sub_category_id']);
            unset($category[$key]['sub_category_name']);
            unset($category[$key]['created_on']);
            unset($category[$key]['updated_on']);
        }

        echo json_encode($category);
        exit();
	}

    public function subCategoryTypeFilter($subCategoryId){
        $this->db->where('sub_category_id', $subCategoryId);
        $category = $this->db->get('sub_category_type')->result_array();

        foreach($category as $key => $c){
            $category[$key]['label'] = $c['sub_category_type_name'];
            $category[$key]['value'] = $c['sub_category_type_id'];
            unset($category[$key]['sub_category_id']);
            unset($category[$key]['sub_category_type_id']);
            unset($category[$key]['sub_category_type_name']);
            unset($category[$key]['created_on']);
            unset($category[$key]['updated_on']);
        }

        echo json_encode($category);
        exit();
	}

    public function typeFilter(){
        $type = $this->db->get('type')->result_array();

        foreach($type as $key => $t){
            $type[$key]['label'] = $t['type_value'];
            $type[$key]['value'] = $t['type_id'];
            unset($type[$key]['type_id']);
            unset($type[$key]['type_value']);
            unset($type[$key]['created_on']);
            unset($type[$key]['updated_on']);
        }

        echo json_encode($type);
        exit();
	}

    public function sizeFilter(){
        $size = $this->db->get('size')->result_array();

        foreach($size as $key => $s){
            $size[$key]['label'] = $s['size_value'];
            $size[$key]['value'] = $s['size_id'];
            unset($size[$key]['size_id']);
            unset($size[$key]['size_value']);
            unset($size[$key]['created_on']);
            unset($size[$key]['updated_on']);
        }

        echo json_encode($size);
        exit();
	}


    public function qualityFilter(){
        $quality = $this->db->get('quality')->result_array();

        foreach($quality as $key => $q){
            $quality[$key]['label'] = $q['quality_value'];
            $quality[$key]['value'] = $q['quality_id'];
            unset($quality[$key]['quality_id']);
            unset($quality[$key]['quality_value']);
            unset($quality[$key]['created_on']);
            unset($quality[$key]['updated_on']);
        }

        echo json_encode($quality);
        exit();
	}

    

    







//CLASS ENDS
}