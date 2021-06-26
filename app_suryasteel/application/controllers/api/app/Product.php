<?php

class Product extends MY_Controller{

	public function __construct(){
		parent::__construct();
	}

	public function getAllProducts() {
        $this->db->select(
            'p.product_id,
             p.category_id,
             p.sub_category,
             p.sub_category_type,
             p.product_name,
             p.mrp,
             p.sell_price,
             b.brand_name,
             t.type_value,
             q.quality_value,
             s.size_value,
             i.thumbnail,
             i.actual
            '
        );

        $this->db->from('products as p');

        $this->db->join('brand as b', 'p.brand_id = b.brand_id');
        $this->db->join('type as t', 'p.type = t.type_id');
        $this->db->join('quality as q', 'p.quality = q.quality_id');
        $this->db->join('size as s', 'p.size = s.size_id');
        $this->db->join('images as i', 'p.thumbnail = i.image_id');
        
        if($this->input->post('brand_id')){
            $this->db->where('b.brand_id', $this->input->post('brand_id'));
        }
        if($this->input->post('category_id')){
            $this->db->where('category_id', $this->input->post('category_id'));
        }
        if($this->input->post('quality')){
            $this->db->where('q.quality_id', $this->input->post('quality'));
        }
        if($this->input->post('size')){
            $this->db->where('s.size_id', $this->input->post('size'));
        }
        if($this->input->post('type')){
            $this->db->where('t.type_id', $this->input->post('type'));
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

        echo json_encode($products);
        exit();
    }







//CLASS ENDS
}