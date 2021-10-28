<?php 
defined('BASEPATH') or exit('no dierct script access allowed');

class Staff_m extends MY_Model {

	protected $tbl_name = 'users';
    protected $primary_col = 'user_id';
    protected $order_by = 'created_on';

    public $staffAddRules = array(
        0 => array(
            'field' => 'username',
            'label' => 'Username/Email',
            'rules' => 'trim|required|valid_email|is_unique[users.email]',
            'errors' => array(
                'is_unique' => "The Username/Email is already added."
            ),
        ),
        1 => array(
            'field' => 'role',
            'label' => 'Role',
            'rules' => 'trim|required'
        ),
        2 => array(
            'field' => 'password',
            'label' => 'Password',
            'rules' => 'trim|required|min_length[5]|max_length[10]'
        ),
        3 => array(
            'field' => 'mobileno',
            'label' => 'Mobile no',
            'rules' => 'trim|required|exact_length[10]|is_natural|is_unique[users.mobile_no]',
            'errors' => array(
                'is_unique' => "This mobile no is already added."
            ),
        )
    );

    public $staffAddRulesApp = array(
        0 => array(
            'field' => 'username',
            'label' => 'Username/Email',
            'rules' => 'trim|required|valid_email',
            'errors' => array(
                'is_unique' => "The Username/Email is already added."
            ),
        ),
        1 => array(
            'field' => 'role',
            'label' => 'Role',
            'rules' => 'trim|required'
        ),
        2 => array(
            'field' => 'password',
            'label' => 'Password',
            'rules' => 'trim|required|min_length[5]|max_length[10]'
        ),
        3 => array(
            'field' => 'mobileno',
            'label' => 'Mobile no',
            'rules' => 'trim|required|exact_length[10]|is_natural',
            'errors' => array(
                'is_unique' => "This mobile no is already added."
            ),
        )
    );

    public function __construct()
	{
		parent::__construct();   
	}


	public function getStaff(){
		$requestData = $_REQUEST;
        $start = (int)$requestData['start'];

        $sql = "select * from users
            join roles on users.role_id = roles.role_id
            where users.role_id != 8
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
        $sql.= " order by users.user_id asc limit " . $start . " ," . $requestData['length'] . "   ";
        $query = $this->db->query($sql);

        $SearchResults = $query->result();


        $data = array();
        $counter = 0;
        foreach ($SearchResults as $row) {
            $counter++;
            $nestedData = array();
            $id = $row->user_id;
            // $crypted_id = $this->outh_m->Encryptor('encrypt', $id);
            $action = $this->data_table_factory_model->staffButtonFactory($id);
            $columnFactory = $this->data_table_factory_model->staffColumnFactory($row);
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

    public function addLog($user_id){
        $logData = array(
            'user_id' => $user_id,
            'title' => 'Staff',
            'description' => 'A staff is added succesfully',
            'created_on' => $this->today
        );
        $this->db->insert('logs', $logData);
    }

    public function editLog($user_id){
        $logData = array(
            'user_id' => $user_id,
            'title' => 'Staff',
            'description' => 'A staff is updated succesfully',
            'created_on' => $this->today
        );
        $this->db->insert('logs', $logData);
    }

    public function addStaff($created_by){
        $hashedPwd = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
        $staffData = array(
            'role_id' => $this->input->post('role'),
            'image_id' => get_settings('default_user_image'),
            'firstname' => $this->input->post('firstname'),
            'lastname' => $this->input->post('lastname'),
            'email' => $this->input->post('username'),
            'password' => $hashedPwd,
            'mobile_no' => $this->input->post('mobileno'),
            'is_active' => 'active',
            'created_on' => $this->today,
            'created_by' => $created_by
        );
        return $this->db->insert('users', $staffData);
    }

    public function editStaff($id){
        $staffData = array(
            'role_id' => $this->input->post('role'),
            'firstname' => $this->input->post('firstname'),
            'lastname' => $this->input->post('lastname'),
            'mobile_no' => $this->input->post('mobileno'),
            'is_active' => $this->input->post('status')
        );

        $this->db->where('user_id', $id);
        return $this->db->update('users', $staffData);
    }

    public function get_staff_list(){
        $this->db->select(
            'u.user_id,
            u.role_id,
            u.is_active,
            u.mobile_no,
            u.firstname,
            u.lastname,
            u.email,
            r.roles_name
            '
        );

        $this->db->from('users as u');
        $this->db->join('roles as r', 'u.role_id=r.role_id');
        $this->db->where('r.role_id != ', null);
        $this->db->limit(25);
        $this->db->order_by('u.firstname ASC');
        return $this->db->get()->result_array();
    }

    public function staff_count(){
		$this->db->select('user_id');
        $this->db->from('users');
        $this->db->where('role_id != ', null);
    	return $this->db->get()->num_rows();
	}

//end class

}
