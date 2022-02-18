<?php 
defined('BASEPATH') or exit('no dierct script access allowed');

class Log_m extends MY_Model {

	protected $tbl_name = 'logs';
    protected $primary_col = 'log_id';
    protected $order_by = 'created_on';

    public function __construct()
	{
		parent::__construct();   
	}


	public function getLog(){
		$requestData = $_REQUEST;
        $start = (int)$requestData['start'];

        $sql = "select l.log_id, l.title, l.description, l.created_on, u.firstname, u.lastname from logs as l
            join users as u on u.user_id = l.user_id
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
        $sql.= " order by l.created_on desc limit " . $start . " ," . $requestData['length'] . "   ";
        $query = $this->db->query($sql);

        $SearchResults = $query->result();


        $data = array();
        $counter = 0;
        foreach ($SearchResults as $row) {
            $counter++;
            $nestedData = array();
            $id = $row->log_id;
            $columnFactory = $this->data_table_factory_model->logColumnFactory($row);
            $tableCol = $this->data_table_factory_model->drawTableData($counter, $id, $columnFactory,$row);
            $j = 0;
            foreach ($tableCol as $key => $value) {
                $nestedData[] = $tableCol[$j];
                $j++;
            }
            // $nestedData[] = $action;
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

    public function log($user_id,$title,$description){
        $logData = array(
            'user_id' => $user_id,
            'title' => $title,
            'description' => $description,
            'created_on' => $this->today
        );
        $this->db->insert('logs', $logData);
    }

    public function getAllLog(){
        $this->db->select('*');
        $this->db->from('logs');
        $this->db->order_by('log_id','desc');


        $this->db->limit(50);
        return $this->db->get()->result_array();

    }


//end class

}
