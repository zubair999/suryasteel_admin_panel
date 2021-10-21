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
            'field' => 'unit',
            'label' => 'Unit',
            'rules' => 'trim|required'
        ),
        5 => array(
            'field' => 'size',
            'label' => 'Size',
            'rules' => 'trim|required'
        ),
        6 => array(
            'field' => 'length',
            'label' => 'Length',
            'rules' => 'trim|required'
        )
    );

    public function get_product($id) {
        return $this->db->get_where('products', array('product_id'=> $id))->row();
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
            'unit' => $this->input->post('unit'),
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
            'unit' => $this->input->post('unit'),
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
        $product = $this->get_product($productId);
        $currentStock = $product->stock;
        $orderQty = $this->input->post('dispatchQty');



        $result = is_greater_than($currentStock, $orderQty);
        if($result){
            $newStock = $currentStock - $orderQty;
            $data = array(
                'stock' => $newStock
            );
            $this->db->where('product_id', 1);
            $this->db->update('products', $data);
        }
        else{
            return false;
        }
    }


    public function checkStock(){
        $orderItem = $this->order_m->getOrderItemByOrderItemId($this->input->post('orderItemId'));
        $productId = $orderItem->product_id;
        $product = $this->get_product($productId);
        $currentStock = $product->stock;
        $orderQty = $this->input->post('dispatchQty');

        $result = is_greater_than($currentStock, $orderQty);
        if($result){
            return true;
        }
        else{
            return false;
        }
    }

    


//end class

}
