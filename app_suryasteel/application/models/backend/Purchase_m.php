<?php 
defined('BASEPATH') or exit('no dierct script access allowed');

class Purchase_m extends MY_Model {

	protected $tbl_name = 'purchase';
    protected $primary_col = 'purchase_id';
    protected $order_by = 'created_on';

    public $purchaseAddRules = array(
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

    public function __construct()
	{
		parent::__construct();   
	}

    public function getPurchaseItems($purchaseId) {
        return $this->db->get_where('purchase_item', array('purchase_id'=> $purchaseId))->result_array();
    }

    public function getPurchaseItem($purchaseItemId) {
        return $this->db->get_where('purchase_item', array('purchase_item_id '=> $purchaseItemId))->row();
    }

	public function getPurchase(){
		$requestData = $_REQUEST;
        $start = (int)$requestData['start'];

        $sql = "select * from purchase";

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
        $sql.= " order by purchase_date desc limit " . $start . " ," . $requestData['length'] . "   ";
        $query = $this->db->query($sql);

        $SearchResults = $query->result();


        $data = array();
        $counter = 0;
        foreach ($SearchResults as $row) {
            $counter++;
            $nestedData = array();
            $id = $row->purchase_id;
            // $crypted_id = $this->outh_m->Encryptor('encrypt', $id);
            $action = $this->data_table_factory_model->purchaseButtonFactory($id);
            $columnFactory = $this->data_table_factory_model->purchaseColumnFactory($row);
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

    public function addPurchase($created_by){
        $purchaseData = array(
            'created_by' => $this->input->post('createdBy'),
            'vendor' => $this->input->post('vendor'),
            'bill_no' => $this->input->post('billNo'),
            'invoice_weight' => $this->input->post('invoiceWeight'),
            'actual_weight' => $this->input->post('actualWeight'),
            'rate' => $this->input->post('rate'),
            'freight_charge' => $this->input->post('freightCharge'),
            'unloading_charge' => $this->input->post('unloadingCharge'),
            'remarks' => $this->input->post('remarks'),
            'created_on' => $this->today,
            'created_by' => $created_by
        );
        $response = $this->db->insert('purchase', $purchaseData);
        if($response){
            return ['response'=>true, 'last_purchase_id'=>$this->db->insert_id()];
        }
        else{
            return ['response'=>false];
        }
    }

    public function addPurchaseItem($lastPurchaseId){
        $size = $this->input->post('size');
        $weight = $this->input->post('weight');
        $round = $this->input->post('round');

        $i = 0;
        foreach (array_combine($size, $weight) as $s => $w){
            $roundCount = $round[$i];
            $this->insertPurchaseItem($lastPurchaseId, $s, $w, $roundCount);
            $i++;
        }
    }

    public function insertPurchaseItem($lastPurchaseId, $s, $w, $round){
        $orderItemData = array(
            'purchase_id' => $lastPurchaseId,
            'size_id' => $s,
            'weight' => $w,
            'round_or_length_availble' => $round,
            'created_on' => $this->today
        );

        $response = $this->db->insert('purchase_item', $orderItemData);
        if($response){
            return true;
        }
        else{
            return false;
        }
    }

    public function editPurchase($purchaseId){
        $purchaseData = array(
            'vendor' => $this->input->post('vendor'),
            'invoice_weight' => $this->input->post('invoiceWeight'),
            'actual_weight' => $this->input->post('actualWeight'),
            'rate' => $this->input->post('rate'),
            'freight_charge' => $this->input->post('freightCharge'),
            'unloading_charge' => $this->input->post('unloadingCharge'),
            'remarks' => $this->input->post('remarks'),
            'updated_on' => $this->today
        );

        $this->db->where('purchase_id', $purchaseId);
        $response = $this->db->update('purchase', $purchaseData);
        if($response){
            return true;
        }
        else{
            return false;
        }
    }

    public function editPurchaseItem(){
        $size = $this->input->post('size');
        $weight = $this->input->post('weight');
        $purchaseItem = $this->input->post('purchaseItemId');

        $i = 0;
        foreach (array_combine($size, $weight) as $s => $w){
            $purchaseItemId = $purchaseItem[$i];
            $this->updatePurchaseItem($purchaseItemId, $s, $w);
            $i++;
        }
    }

    public function updatePurchaseItem($purchaseItemId, $s, $w){
        $purchaseItemData = array(
            'size' => $s,
            'weight' => $w,
            'updated_on' => $this->today
        );
        
        $this->db->where('purchase_item_id', $purchaseItemId);
        $response = $this->db->update('purchase_item', $purchaseItemData);
        if($response){
            return true;
        }
        else{
            return false;
        }
    }
    

    public function get_purchase(){
        $this->db->select(
                            'p.purchase_id,
                             p.created_by,
                             p.vendor,
                             p.invoice_weight,
                             p.actual_weight,
                             p.rate,
                             p.freight_charge,
                             p.unloading_charge,
                             p.remarks,
                             DATE_FORMAT(p.created_on, "%d-%b-%Y") as created_on,
                             DATE_FORMAT(p.updated_on, "%d-%b-%Y") as updated_on,
                             u.firstname,
                             u.lastname
                             '
                        );
        $this->db->from('purchase as p');
        $this->db->join('users as u', 'p.created_by = u.user_id');
        
        if($this->input->post('since')){
            $this->db->where('p.created_on >=', $this->input->post('since'));
        }
        if($this->input->post('until')){
            $this->db->where('p.created_on <=', $this->input->post('until'));
        }
        
        // $this->db->limit(1);
        $this->db->order_by('p.created_on', 'desc');
        $purchase = $this->db->get()->result_array();
        
        $allSize = array();
        foreach ($purchase as $key => $p){
            $purchaseDetail = $this->get_purchase_item_by_purchase_id($p['purchase_id']);

            foreach ($purchaseDetail as $key1 => $pd){
                array_push($allSize, $pd['size_value']);
                // $allSize .= ' ,' .$pd['size_value'];
            }

            $purchase[$key]['purchase_detail'] = $purchaseDetail;
            $purchase[$key]['all_size'] = implode(", ", $allSize);
        }

        return $purchase;
        // FUNCTION ENDS
    }

    public function get_purchase_item_by_purchase_id($id){
        $this->db->select(
                            '
                                pi.purchase_item_id, 
                                pi.purchase_id,  
                                pi.weight,
                                pi.round_or_length_availble,
                                pi.round_or_length_added_to_process,
                                DATE_FORMAT(pi.created_on, "%d-%b-%Y") as created_on,
                                s.size_value
                            '
                        );
        $this->db->from('purchase_item as pi');
        $this->db->join('size as s', 'pi.size_id = s.size_id');
        $this->db->where('purchase_id', $id);
        $order_item =  $this->db->get()->result_array();


        return $order_item;
    }

    public function get_purchase_item(){
        $this->db->select(
                            '
                                pi.purchase_item_id,
                                pi.purchase_id,
                                pi.purchase_status_catalog_id,
                                pi.weight,
                                pi.round_or_length_availble,
                                pi.round_or_length_added_to_process,
                                DATE_FORMAT(pi.created_on, "%d-%b-%Y") as created_on,
                                DATE_FORMAT(pi.updated_on, "%d-%b-%Y") as updated_on,
                                p.vendor,
                                ps.status_value,
                                ps.status_color,
                                s.size_value
                            '
                        );
        $this->db->from('purchase_item as pi');
        $this->db->join('purchase as p', 'pi.purchase_id = p.purchase_id');
        $this->db->join('purchase_item_status_catalog as ps', 'pi.purchase_status_catalog_id = ps.purchase_item_status_catalog_id');
        $this->db->join('size as s', 'pi.size_id = s.size_id');
        return $this->db->get()->result_array();
    }

    public function updateRoundLengthInPurchase(){
        $purchaseItem = $this->getPurchaseItem($this->input->post('purchaseItemId'));
        $totalRoundAdded = (int)$this->input->post('roundOrLengthToBeCompleted') + $purchaseItem->round_or_length_added_to_process;

        $purchaseItemData = array(
            'round_or_length_added_to_process' => $totalRoundAdded,
            'updated_on' => $this->today
        );
        
        $this->db->where('purchase_item_id', $this->input->post('purchaseItemId'));
        $response = $this->db->update('purchase_item', $purchaseItemData);
        if($response){
            return true;
        }
        else{
            return false;
        }
    }

    public function delete_purchase_item_by_purchase_id($id){
        $this->db->where('purchase_id', $id);
        return $this->db->delete('purchase_item');
    }

    public function delete_purchase_item_by_purchase_item_id($id){
        $this->db->where('purchase_item_id', $id);
        return $this->db->delete('purchase_item');
    }

    public function delete_purchase_entry($id){
        $this->db->where('purchase_id', $id);
        return $this->db->delete('purchase');
    }

    public function delete_purchase($id) {
        $isPurchaseItemDeleted = $this->delete_purchase_item_by_purchase_id($id);
        if($isPurchaseItemDeleted){
            return $this->delete_purchase_entry($id);
        }
        else{
            return false;
        }
    }

    public function delete_purchase_item($id) {
        $isPurchaseItemDeleted = $this->delete_purchase_item_by_purchase_item_id($id);
        if($isPurchaseItemDeleted){
            return true;
        }
        else{
            return false;
        }
    }





//end class
}
