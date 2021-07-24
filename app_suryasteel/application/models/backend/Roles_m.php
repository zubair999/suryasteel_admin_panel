<?php 
defined('BASEPATH') or exit('no dierct script access allowed');

class Roles_m extends MY_Model {

	protected $tbl_name = 'roles';
    protected $primary_col = 'role_id';
    protected $order_by = 'created_on';

    public function __construct()
	{
		parent::__construct();   
	}

    public function getRoles(){
		$requestData = $_REQUEST;
        $start = (int)$requestData['start'];

        $sql = "select * from roles";

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
        $sql.= " order by role_id asc limit " . $start . " ," . $requestData['length'] . "   ";
        $query = $this->db->query($sql);

        $SearchResults = $query->result();


        $data = array();
        $counter = 0;
        foreach ($SearchResults as $row) {
            $counter++;
            $nestedData = array();
            $id = $row->role_id;
            // $crypted_id = $this->outh_m->Encryptor('encrypt', $id);
            $action = $this->data_table_factory_model->roleButtonFactory($id);
            $columnFactory = $this->data_table_factory_model->roleColumnFactory($row);
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

    public function getAllRoles() {
        $this->db->select('role_id, roles_name');
        $this->db->from('roles');
        return $this->db->get()->result_array();
    }

    public function getUserPermission($role_id){
        $this->db->select('permission');
        $this->db->from('roles');
        $this->db->where('role_id', $role_id);
        return $this->db->get()->row()->permission;
    }

    public function getRoleByRoleId($role_id) {
        $this->db->select('role_id, roles_name, permission');
        $this->db->from('roles');
        $this->db->where('role_id',$role_id);
        return $this->db->get()->row();
    }


//end class

}
