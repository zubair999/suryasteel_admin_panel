<?php

class Product extends MY_Controller{

	public function __construct(){
		parent::__construct();
	}

	public function getAllProducts() {
        $this->db->select(
            'p.product_id,
             p.category_id,
             p.product_name,
             p.mrp,
             i.thumbnail,
             i.actual
            '
        );

        $this->db->from('products as p');
        $this->db->join('images as i', 'p.thumbnail = i.image_id');
        
        if($this->input->post('category_id')){
            $this->db->where('category_id', $this->input->post('category_id'));
        }

        // $this->db->offset(0);
        $this->db->limit(25);
        $this->db->order_by('p.product_id DESC');

        $products = $this->db->get()->result_array();

        foreach($products as $key => $p){
            $products[$key]['actual'] = BASEURL.'upload/'.$p['actual'];
            $products[$key]['thumbnail'] = BASEURL.'upload/'.$p['thumbnail'];
            $products[$key]['isAddedToCart'] = false;
            $products[$key]['cartQuantity'] = null;
        }

        $res = ['status'=>200,'message'=>'success','description'=>'Products fetched successfully.', 'data'=>$products];


        echo json_encode($res);
        exit();
    }

    // public function getAllProducts() {
    //     $this->db->select('*');
    //     $this->db->from('products');
    //     $res = $this->db->get()->result_array();
    //     echo json_encode($res);
    // }







//CLASS ENDS
}