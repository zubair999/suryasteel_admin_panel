<?php 
defined('BASEPATH') or exit('no dierct script access allowed');

class Order_m extends MY_Model {

	protected $tbl_name = 'orders';
    protected $primary_col = 'order_id';
    protected $order_by = 'created_on';

    public function __construct()
	{
		parent::__construct();   
	}


	public function getOrder(){
		$requestData = $_REQUEST;
        $start = (int)$requestData['start'];

        $sql = "select * from orders
            join users on users.user_id = orders.user_id
        ";

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
        $sql.= " order by orders.order_id asc limit " . $start . " ," . $requestData['length'] . "   ";
        $query = $this->db->query($sql);

        $SearchResults = $query->result();


        $data = array();
        $counter = 0;
        foreach ($SearchResults as $row) {
            $counter++;
            $nestedData = array();
            $id = $row->order_id;
            // $crypted_id = $this->outh_m->Encryptor('encrypt', $id);
            $action = $this->data_table_factory_model->orderButtonFactory($id);
            $columnFactory = $this->data_table_factory_model->orderColumnFactory($row);
            $tableCol = $this->data_table_factory_model->drawTableData($counter, $id, $columnFactory, $row);
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
                             p.total,
                             p.status,
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

    public function get_order(){
        $this->db->select(
                            'o.order_id,
                             o.bill_no,
                             o.order_amount,
                             o.remarks,
                             o.created_on,
                             o.updated_on,
                             u.firstname,
                             u.lastname,
                             st.status_value
                             '
                        );
        $this->db->from('orders as o');
        $this->db->join('users as u', 'o.user_id = u.user_id');
        $this->db->join('order_status_catalog as st', 'o.order_status_catalog_id = st.order_status_catalog_id');
        $this->db->order_by('created_on', 'asc');
        return $this->db->get()->result_array();
        // FUNCTION ENDS
    }


//end class

}
