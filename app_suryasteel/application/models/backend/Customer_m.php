<?php 
defined('BASEPATH') or exit('no dierct script access allowed');

class Customer_m extends MY_Model {

	protected $tbl_name = 'users';
    protected $primary_col = 'user_id';
    protected $order_by = 'created_on';

    public $customerAddRules = array(
        0 => array(
            'field' => 'username',
            'label' => 'Username/Email',
            'rules' => 'trim|required|valid_email|is_unique[users.email]',
            'errors' => array(
                'is_unique' => "The Username/Email is already added."
            ),
        ),
        1 => array(
            'field' => 'password',
            'label' => 'Password',
            'rules' => 'trim|required|min_length[5]|max_length[10]'
        ),
        2 => array(
            'field' => 'mobileno',
            'label' => 'Mobile no',
            'rules' => 'trim|required|exact_length[10]|is_natural|is_unique[users.mobile_no]',
            'errors' => array(
                'is_unique' => "This mobile no is already added."
            ),
        ),
        3 => array(
            'field' => 'state',
            'label' => 'State',
            'rules' => 'trim|required'
        )
    );

    public $customerAddRulesApp = array(
        0 => array(
            'field' => 'username',
            'label' => 'Username/Email',
            'rules' => 'trim|required|valid_email|is_unique[users.email]',
            'errors' => array(
                'is_unique' => "The Username/Email is already added."
            ),
        ),
        1 => array(
            'field' => 'password',
            'label' => 'Password',
            'rules' => 'trim|required|min_length[5]|max_length[10]'
        ),
        2 => array(
            'field' => 'mobileno',
            'label' => 'Mobile no',
            'rules' => 'trim|required|exact_length[10]|is_natural|is_unique[users.mobile_no]',
            'errors' => array(
                'is_unique' => "This mobile no is already added."
            ),
        ),
        3 => array(
            'field' => 'state',
            'label' => 'State',
            'rules' => 'trim|required'
        ),
        4 => array(
            'field' => 'gstRegType',
            'label' => 'Gst Reg Type',
            'rules' => 'trim|required'
        ),
        5 => array(
            'field' => 'yesno',
            'label' => 'Is product allowed to view',
            'rules' => 'trim|required'
        )
    );

    public $customerRegistrationRulesApp = array(
        0 => array(
            'field' => 'username',
            'label' => 'Username/Email',
            'rules' => 'trim|required|valid_email|is_unique[users.email]',
            'errors' => array(
                'is_unique' => "The Username/Email is already added."
            ),
        ),
        1 => array(
            'field' => 'password',
            'label' => 'Password',
            'rules' => 'trim|required|min_length[5]|max_length[10]'
        ),
        2 => array(
            'field' => 'mobileno',
            'label' => 'Mobile no',
            'rules' => 'trim|required|exact_length[10]|is_natural|is_unique[users.mobile_no]',
            'errors' => array(
                'is_unique' => "This mobile no is already added."
            ),
        ),
        3 => array(
            'field' => 'state',
            'label' => 'State',
            'rules' => 'trim|required'
        ),
        4 => array(
            'field' => 'gstRegType',
            'label' => 'Gst Reg Type',
            'rules' => 'trim|required'
        )
    );

    public function __construct()
	{
		parent::__construct();   
	}


	public function getCustomer(){
		$requestData = $_REQUEST;
        $start = (int)$requestData['start'];

        $sql = "select * from users 
                where users.role_id = 12
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
            $action = $this->data_table_factory_model->customerButtonFactory($id);
            $columnFactory = $this->data_table_factory_model->customerColumnFactory($row);
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


    public function customer_count(){
		$this->db->select('user_id');
        $this->db->from('users');
    	return $this->db->get()->num_rows();
	}

    public function get_customer_list(){
        $this->db->select('*');
        $this->db->where('role_id = ', 12);

        if($this->input->post('searchterm')){
            $this->db->like('firstname', $this->input->post('searchterm'), 'after');
            $this->db->or_like('customer_company', $this->input->post('searchterm'), 'both');
            $this->db->or_like('mobile_no', $this->input->post('searchterm'), 'after');
            $this->db->or_like('is_active', $this->input->post('searchterm'), 'after');
        }

        // $this->db->limit(25);
        // $this->db->order_by('customer_company ASC');
        $this->db->order_by('is_active desc');
        $customer = $this->db->get('users')->result_array();

        foreach ($customer as $key => $c){
            $image = $this->get_image($c['image_id']);
            $customer[$key]['actual'] = base_url('upload/'.$image->actual);
            $customer[$key]['thumbnail'] = base_url('upload/'.$image->thumbnail);
        }
        return $customer;
    }

    public function get_image($id) {
        return $this->db->get_where('images', array('image_id'=> $id))->row();
    }

    public function addCustomer($created_by, $imageId){
        $hashedPwd = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
        $customerData = array(
            'firstname' => $this->input->post('firstname'),
            'lastname' => $this->input->post('lastname'),
            'email' => $this->input->post('username'),
            'password' => $hashedPwd,
            'mobile_no' => $this->input->post('mobileno'),
            'state_id' => $this->input->post('state'),
            'image_id' => $imageId,
            'is_allowed_to_view_product' => $this->input->post('yesno'),
            'company_email' => $this->input->post('companyMail'),
            'customer_company' => $this->input->post('companyName'),
            'gst_reg_type' => $this->input->post('gstRegType'),
            'gstn' => $this->input->post('gst_no'),
            'plot_factory_no' => $this->input->post('plotFactoryNo'),
            'complete_address' => $this->input->post('fullAddress'),
            'landmark' => $this->input->post('landmark'),
            'is_active' => 'active',
            'created_on' => $this->today,
            'created_by' => $created_by
        );
        return $this->db->insert('users', $customerData);
    }

    public function addLog($user_id){
        $logData = array(
            'user_id' => $user_id,
            'title' => 'Customer',
            'description' => 'A customer is added succesfully',
            'created_on' => $this->today
        );
        $this->db->insert('logs', $logData);
    }

    public function editCustomer($id, $imageId){
        $customerData = array(
            'firstname' => $this->input->post('firstname'),
            'lastname' => $this->input->post('lastname'),
            'mobile_no' => $this->input->post('mobileno'),
            'state_id' => $this->input->post('state'),
            'image_id' => $imageId,
            'is_allowed_to_view_product' => $this->input->post('yesno'),
            'company_email' => $this->input->post('companyMail'),
            'customer_company' => $this->input->post('companyName'),
            'gst_reg_type' => $this->input->post('gstRegType'),
            'gstn' => $this->input->post('gst_no'),
            'plot_factory_no' => $this->input->post('plotFactoryNo'),
            'complete_address' => $this->input->post('fullAddress'),
            'landmark' => $this->input->post('landmark'),
            'is_active' => $this->input->post('status')
        );

        $this->db->where('user_id', $id);
        return $this->db->update('users', $customerData);
    }

    
    public function customerRegistration($created_by, $imageId){
        $hashedPwd = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
        $customerData = array(
            'firstname' => $this->input->post('firstname'),
            'lastname' => $this->input->post('lastname'),
            'email' => $this->input->post('username'),
            'password' => $hashedPwd,
            'mobile_no' => $this->input->post('mobileno'),
            'state_id' => $this->input->post('state'),
            'image_id' => $imageId,
            'is_allowed_to_view_product' => "no",
            'company_email' => $this->input->post('companyMail'),
            'customer_company' => $this->input->post('companyName'),
            'gst_reg_type' => $this->input->post('gstRegType'),
            'gstn' => $this->input->post('gst_no'),
            'plot_factory_no' => $this->input->post('plotFactoryNo'),
            'complete_address' => $this->input->post('fullAddress'),
            'landmark' => $this->input->post('landmark'),
            'is_active' => 'inactive',
            'role_id' => 12,
            'created_on' => $this->today,
            'created_by' => 'self'
        );
        return $this->db->insert('users', $customerData);
    }





//end class

}
