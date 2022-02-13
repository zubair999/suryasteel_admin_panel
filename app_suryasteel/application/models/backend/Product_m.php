<?php 
defined('BASEPATH') or exit('no dierct script access allowed');

class Product_m extends MY_Model {

	protected $tbl_name = 'products';
    protected $primary_col = 'product_id';
    protected $order_by = 'created_on';

    public function __construct()
	{
		parent::__construct();   
	}

    public $productAddRulesApp = array(
        0 => array(
            'field' => 'categoryId',
            'label' => 'category',
            'rules' => 'trim|required'
        ),
        1 => array(
            'field' => 'imageId',
            'label' => 'Image',
            'rules' => 'trim|required'
        ),
        2 => array(
            'field' => 'productName',
            'label' => 'product name',
            'rules' => 'trim|required'
        ),
        3 => array(
            'field' => 'weightPerPiece',
            'label' => 'Weight per piece',
            'rules' => 'trim|required'
        ),
        4 => array(
            'field' => 'size',
            'label' => 'Size',
            'rules' => 'trim|required'
        ),
        5 => array(
            'field' => 'length',
            'label' => 'Length',
            'rules' => 'trim|required'
        )
    );

    public function getProductCountCategoryWise($category) {
        return $this->db->get_where('products', array('category_id'=> $category))->num_rows();
    }

    public function get_product_by_id($id) {
        $this->db->select('*');
        $this->db->from('products');
        $this->db->where('product_id', $id);
        return $this->db->get()->row();
    }
    
    public function get_product($id) {
        $this->db->select('*');
        $this->db->from('products as p');
        $this->db->join('images as i', 'p.thumbnail = i.image_id');
        $this->db->where('p.product_id', $id);
        return $this->db->get('products')->row();
    }

	public function getProduct(){
		$requestData = $_REQUEST;
        $start = (int)$requestData['start'];

        $sql = "select * from products";

        //echo $sql;
        $query = $this->db->query($sql);
        $queryqResults = $query->result();
        $totalData = $query->num_rows(); // rules datatable
        $totalFiltered = $totalData; // rules datatable

        if (!empty($requestData['search']['value'])) { // if there is a search parameter, $requestData['search']['value'] contains search parameter
            $searchValue = $requestData['search']['value'];
            // $this->db->like('product_name', $searchValue);
            // $this->db->or_like('mobile', $searchValue);
            // $this->db->or_like('email', $searchValue);
            // $this->db->or_like('profile', $searchValue);
        }

        $query = $this->db->query($sql);
        $totalFiltered = $query->num_rows();
        $sql.= " order by product_id asc limit " . $start . " ," . $requestData['length'] . "   ";
        $query = $this->db->query($sql);

        $SearchResults = $query->result();


        $data = array();
        $counter = 0;
        foreach ($SearchResults as $row) {
            $counter++;
            $nestedData = array();
            $id = $row->product_id;
            // $crypted_id = $this->outh_m->Encryptor('encrypt', $id);
            $action = $this->data_table_factory_model->productsButtonFactory($id);
            $columnFactory = $this->data_table_factory_model->productsColumnFactory($row);
            $tableCol = $this->data_table_factory_model->drawTableData($counter, $id, $columnFactory,$row);
            $j = 0;
            foreach ($tableCol as $key => $value) {
                $nestedData[] = $tableCol[$j];
                $j++;
            }
            $nestedData[] = $action;
            $data[] = $nestedData;
        }
        return $json_data = array("draw" => intval($requestData['draw']), "recordsTotal" => intval($totalData), // total number of records
        "recordsFiltered" => intval($totalFiltered), // total number of records after searching,
        "data" => $data
        // total data array
        );
        // FUNCTION ENDS
    }

    public function getProductById($id){
        $this->db->select(
                            'p.product_id,
                             p.brand_id,
                             p.category_id,
                             p.sub_category,
                             p.sub_category_type,
                             p.gst_id,
                             p.type, 
                             p.type, 
                             p.product_name,
                             p.mrp,
                             p.sell_price,
                             p.status,
                             p.thumbnail as image_id,
                             i.thumbnail
                             '
                        );
        $this->db->from('products as p');
        $this->db->join('images as i', 'p.thumbnail = i.image_id');
        $this->db->where('p.product_id', $id);
        $data = $this->db->get()->row();
        $data->thumbnail = BASEURL.'upload/'.$data->thumbnail;
        return $data;
        // FUNCTION ENDS
    }

    public function addProduct($created_by) {
        $data = array(
            'category_id' => $this->input->post('categoryId'),
            'thumbnail' => $this->input->post('imageId'),
            'product_name' => $this->input->post('productName'),
            'unit' => 1,
            'size' => $this->input->post('size'),
            'length' => $this->input->post('length'),
            'weight_per_piece' => $this->input->post('weightPerPiece'),
            'capacity' => $this->input->post('capacity'),
            'zinc_or_without_zinc' => $this->input->post('zincOrWithoutZinc'),
            'having_kunda' => $this->input->post('havingkunda'),
            'having_nut' => $this->input->post('havingNut'),
            'is_active' => 'active',
            'created_by' => $created_by,
            'created_on' => $this->today
        );

        $response = $this->db->insert('products', $data);
        if($response){
            return true;
        }
        else{
            false;
        }
    }

    public function editProduct($id){
        $productData = array(
            'category_id' => $this->input->post('categoryId'),
            'thumbnail' => $this->input->post('imageId'),
            'product_name' => $this->input->post('productName'),
            'unit' => 1,
            'size' => $this->input->post('size'),
            'length' => $this->input->post('length'),
            'weight_per_piece' => $this->input->post('weightPerPiece'),
            'capacity' => $this->input->post('capacity'),
            'zinc_or_without_zinc' => $this->input->post('zincOrWithoutZinc'),
            'having_kunda' => $this->input->post('havingkunda'),
            'having_nut' => $this->input->post('havingNut'),
            'updated_on' => $this->today
        );

        $this->db->where('product_id', $id);
        return $this->db->update('products', $productData);
    }

    public function decreaseStock(){
        $orderItem = $this->order_m->getOrderItemByOrderItemId($this->input->post('orderItemId'));
        $productId = $orderItem->product_id;
        $product = $this->get_product_by_id($productId);
        $currentStock = $product->stock;
        $orderQty = $this->input->post('dispatchQty');
        // $result = is_greater_than($currentStock, $orderQty);
        // if($result){
        $newStock = (float)$currentStock - (float)$orderQty;
        $data = array(
            'stock' => $newStock
        );

        // echo $orderItem->product_id;
        // echo "-";
        // echo $product->stock;
        // echo "-";
        // echo $orderQty;
        // die;

        $this->db->where('product_id', $productId);
        $this->db->update('products', $data);
        // }
        // else{
        //     return false;
        // }
    }

    public function returnStock($orderItemId, $deletedDispatchedQty){
        $orderItem = $this->order_m->getOrderItemByOrderItemId($orderItemId);
        $productId = $orderItem->product_id;
        $product = $this->get_product_by_id($productId);
        $currentStock = $product->stock;
        $newStock = $currentStock + $deletedDispatchedQty;
        $data = array(
            'stock' => $newStock
        );
        $this->db->where('product_id', $productId);
        $this->db->update('products', $data);
    }


    public function checkStock(){
        $orderItem = $this->order_m->getOrderItemByOrderItemId($this->input->post('orderItemId'));
        $productId = $orderItem->product_id;
        $product = $this->get_product_by_id($productId);
        $currentStock = $product->stock;
        $orderQty = (float)$this->input->post('dispatchQty');

        $stockCheck = is_less_than($currentStock, $orderQty);
        if($stockCheck){
            return true;
        }
        else{
            return false;
        }
    }

    public function productSectionList($out_of_stock_threshold_limit, $short_stock_threshold_limit){
        $this->db->distinct();
        $this->db->select('p.category_id, c.category_name');
        $this->db->from('products as p');
        $this->db->join('category as c', 'p.category_id = c.category_id');
        $this->db->join('size as sz', 'p.size = sz.size_id');
        if($this->input->post('categoryId')){
            $this->db->where('p.category_id', $this->input->post('categoryId'));
        }
        if($this->input->post('size')){
            $this->db->where('p.size', $this->input->post('size'));
        }
        if($this->input->post('length')){
            $this->db->where('p.length', $this->input->post('length'));
        }
        if($this->input->post('outOfStock')){
            $this->db->where('p.stock', $out_of_stock_threshold_limit);
        }
        if($this->input->post('shortStock')){
            $this->db->where('p.stock < ', $short_stock_threshold_limit);
        }

        $category = $this->db->get()->result_array();

        foreach ($category as $key => $c){
            $products = $this->getProductByCategory($c['category_id'], $out_of_stock_threshold_limit, $short_stock_threshold_limit);            
            $category[$key]['title'] = $c['category_name'].'('.$this->getProductCountCategoryWise($c['category_id']).')';
            $category[$key]['data'] = $products;
            unset($category[$key]['category_id']);
            unset($category[$key]['category_name']);
        }
        return $category;
    }

    public function getProductByCategory($id, $out_of_stock_threshold_limit, $short_stock_threshold_limit){
        $this->db->select(
            'p.product_id,
            p.category_id,
            p.product_name,
            p.thumbnail as image_id,
            p.stock,
            p.is_active,
            p.weight_per_piece,
            p.unit,
            p.zinc_or_without_zinc,
            p.having_kunda,
            p.having_nut,
            u.unit_value,
            c.category_name,
            i.thumbnail,
            i.actual,
            si.size_id as size,
            si.size_value,
            le.length_id as length,
            le.length_value
            '
        );

        $this->db->from('products as p');
        $this->db->join('images as i', 'p.thumbnail = i.image_id');
        $this->db->join('category as c', 'c.category_id = p.category_id');
        $this->db->join('units as u', 'p.unit = u.unit_id');
        $this->db->join('size as si', 'p.size = si.size_id');
        $this->db->join('length as le', 'p.length = le.length_id');

        $this->db->where('p.category_id', $id);
        if($this->input->post('size')){
            $this->db->where('p.size', $this->input->post('size'));
        }
        if($this->input->post('length')){
            $this->db->where('p.length', $this->input->post('length'));
        }
        if($this->input->post('outOfStock')){
            $this->db->where('p.stock', $out_of_stock_threshold_limit);
        }
        if($this->input->post('shortStock')){
            $this->db->where('p.stock < ', $short_stock_threshold_limit);
        }
        if($this->input->post('searchterm')){
            $this->db->like('p.product_name', $this->input->post('searchterm'), 'both');
        }



        // $this->db->order_by('p.product_name','asc');
        $this->db->order_by('p.size','asc');
        $this->db->order_by('p.length','asc');

        $products = $this->db->get()->result_array();

        foreach($products as $key => $p){
            $products[$key]['actual'] = BASEURL.'upload/'.$p['actual'];
            $products[$key]['thumbnail'] = BASEURL.'upload/'.$p['thumbnail'];
            $products[$key]['isAddedToCart'] = false;
            $products[$key]['productWiseOrderCount'] = $this->order_m->productWiseOrder($p['product_id'])['total_count'];
            $products[$key]['productWiseOrders'] = $this->order_m->productWiseOrder($p['product_id'])['order'];
            $products[$key]['totalPendingWeight'] = $this->order_m->productWiseOrder($p['product_id'])['total_pending_weight'];
        }

        if(count($products) == 0){
            return false;
        }

        return $products;
    }

    public function galvanisation () {
        return  array (
                    0 => array (
                        "label" => "Zinc",
                        "value" =>"zinc"
                    ),
                    1 => array(
                        "label" => "Without Zinc",
                        "value" => "without_zinc"
                    )
                );
    }

    

    public function yesno(){
        return array (
                    0 => array (
                        "label" => "Yes",
                        "value" => "yes"
                    ),
                    1 => array (
                        "label" => "No",
                        "value" => "no"
                    )
                );
    }

//end class

}
