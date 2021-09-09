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

    public function getOrderItemByOrderItemId($orderItemId) {
        return $this->db->get_where('order_item', array('order_item_id'=> $orderItemId))->row();
    }

    public function getDispatchedItem($dispatchId) {
        return $this->db->get_where('order_item_dispatch', array('order_item_dispatch_id'=> $dispatchId))->row();
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
                             o.created_by,
                             o.user_id,
                             o.payment_mode,
                             o.bill_no,
                             DATE_FORMAT(o.expected_delivery_date, "%d-%b-%Y") as expected_delivery_date,
                             DATE_FORMAT(o.created_on, "%d-%b-%Y") as created_on,
                             DATE_FORMAT(o.updated_on, "%d-%b-%Y") as updated_on,
                             o.remarks,
                             u.firstname,
                             u.lastname,
                             st.status_value,
                             st.status_color,
                             '
                        );
        $this->db->from('orders as o');
        $this->db->join('users as u', 'o.user_id = u.user_id', 'left');
        $this->db->join('order_status_catalog as st', 'o.order_status_catalog_id = st.order_status_catalog_id');
        
        if($this->input->post('orderStatus')){
            $this->db->where('o.order_status_catalog_id', $this->input->post('orderStatus'));
        }
        if($this->input->post('since')){
            $this->db->where('o.created_on >=', $this->input->post('since'));
        }
        if($this->input->post('until')){
            $this->db->where('o.created_on <=', $this->input->post('until'));
        }
        if($this->input->post('userId')){
            $this->db->where('o.user_id', $this->input->post('userId'));
        }
        if($this->input->post('createdBy')){
            $this->db->where('o.created_by', $this->input->post('createdBy'));
        }
        
        // $this->db->limit(1);
        $this->db->order_by('o.order_status_catalog_id', 'asc');
        $order = $this->db->get()->result_array();
        
        foreach ($order as $key => $o){
            $od = $this->get_order_item_by_order_id($o['order_id']);
            $createdBy = $this->auth_m->getUserById($o['created_by']);
            $order[$key]['order_detail'] = $od;
            $order[$key]['orderCreatedBy'] = $createdBy->firstname. ' ' .$createdBy->lastname;

        }

        return $order;
        // FUNCTION ENDS
    }

    public function get_order_item_by_order_id($id){
        $this->db->select(
                            '
                                oi.order_item_id, 
                                oi.order_id, 
                                oi.product_id, 
                                oi.order_qty,
                                oi.dispatched_qty,
                                oi.unit,
                                oi.sold_at,
                                oi.item_added_by,
                                DATE_FORMAT(oi.created_on, "%d-%b-%Y") as created_on,
                                p.product_name,
                                CONCAT(u.firstname ," ",  u.lastname) as added_by,
                                un.unit_value,
                            '
                        );
        $this->db->from('order_item as oi');
        $this->db->join('products as p', 'oi.product_id = p.product_id');
        $this->db->join('users as u', 'oi.item_added_by = u.user_id');
        $this->db->join('units as un', 'oi.unit = un.unit_id');
        $this->db->where('oi.order_id', $id);
        $order_item =  $this->db->get()->result_array();
    
        foreach ($order_item as $key => $oi){
            $od = $this->get_order_item_dispatch_detail($oi['order_item_id']);
            $role = $this->roles_m->getOnlyRoleNameByUserId($oi['item_added_by']);
            $order_item[$key]['order_item_dispatch_detail'] = $od;
            $order_item[$key]['roles_name'] = $role;
        }

        return $order_item;
    }

    private function get_order_item_dispatch_detail($oi){
        $this->db->select(
                            '
                                oid.order_item_dispatch_id, 
                                oid.order_item_id, 
                                oid.dispatch_quantity, 
                                DATE_FORMAT(oid.created_on, "%d-%b-%Y") as created_on, 
                                u.firstname, 
                                u.lastname,
                                un.unit_value,
                                r.role_id,
                                r.roles_name
                            '
                        );
        $this->db->from('order_item_dispatch as oid');
        $this->db->join('users as u', 'oid.dispatch_by = u.user_id');
        $this->db->join('units as un', 'oid.dispatch_unit = un.unit_id');
        $this->db->join('roles as r', 'u.role_id = r.role_id');
        $this->db->where('oid.order_item_id', $oi);

        return $this->db->get()->result_array();
    }

    public function productWiseOrderCount($product_id){
        return $this->db->where(['product_id'=>$product_id])->from("order_item")->count_all_results();
    }


    public function get_order_by_product_id(){
        $this->db->select(
                            'o.order_id,
                             o.bill_no,
                             o.order_amount,
                             o.remarks,
                             DATE_FORMAT(o.dispatching_date, "%d-%b-%Y") as dispatching_date,
                             DATE_FORMAT(o.created_on, "%d-%b-%Y") as created_on,
                             DATE_FORMAT(o.updated_on, "%d-%b-%Y") as updated_on,
                             u.firstname,
                             u.lastname,
                             st.status_value
                             '
                        );
        $this->db->from('orders as o');
        $this->db->join('users as u', 'o.user_id = u.user_id');
        $this->db->join('order_status_catalog as st', 'o.order_status_catalog_id = st.order_status_catalog_id');
        $this->db->order_by('created_on', 'asc');
        $order = $this->db->get()->result_array();
        
        foreach ($order as $key => $o){
            $od = $this->get_order_item_by_order_id($o['order_id']);
            $order[$key]['order_detail'] = $od;
        }

        return $order;
        // FUNCTION ENDS
    }

    public function addOrder($created_by){
        $orderData = array(
            'created_by' => $this->input->post('createdBy'),
            'user_id' => $this->input->post('userId'),
            'order_status_catalog_id' => 1,
            'bill_no' => null,
            'payment_mode' => $this->input->post('paymentMode'),
            'expected_delivery_date' => $this->input->post('expectedDeliveryData'),
            'remarks' => $this->input->post('remarks'),
            'created_on' => $this->today,
            'created_by' => $created_by
        );
        $response = $this->db->insert('orders', $orderData);
        if($response){
            return ['response'=>true, 'last_order_id'=>$this->db->insert_id()];
        }
        else{
            return ['response'=>false];
        }
    }

    public function addOrderItem($lastOrderId){
        $product = $this->input->post('product');
        $quantity = $this->input->post('quantity');
        $unit = $this->input->post('unit');
        $sold_at = $this->input->post('soldAt');
        $user_id = $this->input->post('userId');
        $created_by = $this->input->post('createdBy');
        $payment_mode = $this->input->post('paymentMode');

        $i = 0;
        foreach (array_combine($product, $quantity) as $p => $q){
            $unit_id = $unit[$i];
            $sold_price = $sold_at[$i];
            $this->insertOrderItem($lastOrderId, $user_id, $created_by, $p, $q, $unit_id, $sold_price);
            $i++;
        }
    }

    public function insertOrderItem($lastOrderId, $user_id, $created_by, $p, $q, $u, $sold_price){
        $orderItemData = array(
            'order_id' => $lastOrderId,
            'user_id' => $user_id,
            'item_added_by' => $created_by,
            'product_id' => $p,
            'order_qty' => $q,
            'dispatched_qty' => 0,
            'unit' => $u,
            'sold_at' => $sold_price,
            'created_on' => $this->today
        );
        $response = $this->db->insert('order_item', $orderItemData);
        if($response){
            return true;
        }
        else{
            return false;
        }
    }

    public function editOrder($orderId){
        $orderData = array(
            'payment_mode' => $this->input->post('paymentMode'),
            'expected_delivery_date' => $this->input->post('expectedDeliveryData'),
            'remarks' => $this->input->post('remarks'),
            'updated_on' => $this->today
        );
        $this->db->where('order_id', $orderId);
        $response = $this->db->update('orders', $orderData);
        if($response){
            return true;
        }
        else{
            return false;
        }
    }

    public function editOrderItem(){
        $product = $this->input->post('product');
        $quantity = $this->input->post('quantity');
        $unit = $this->input->post('unit');
        $sold_at = $this->input->post('soldAt');
        $orderId = $this->input->post('orderId');
        $payment_mode = $this->input->post('paymentMode');

        $i = 0;
        foreach (array_combine($product, $quantity) as $p => $q){
            $unit_id = $unit[$i];
            $sold_price = $sold_at[$i];
            $this->updateOrderItem($orderId, $p, $q, $unit_id, $sold_price);
            $i++;
        }
    }

    public function updateOrderItem($orderId, $p, $q, $u, $sold_price){
        $orderItemData = array(
            'order_qty' => $q,
            'unit' => $u,
            'sold_at' => $sold_price,
            'updated_on' => $this->today
        );
        
        $this->db->where('order_id', $orderId);
        $this->db->where('product_id', $p);
        $response = $this->db->update('order_item', $orderItemData);
        if($response){
            return true;
        }
        else{
            return false;
        }
    }

    public function isOrderItemDispatched($orderItemId){
        $orderItemCount =  $this->db->get_where('order_item_dispatch', array('order_item_id'=> $orderItemId))->num_rows();
        if($orderItemCount > 0){
            return true;
        }
        else{
            return false;
        }
    }

    public function deleteOrderItem($orderItemId){
        $this->db->where('order_item_id', $orderItemId);
        return $this->db->delete('order_item');
    }

    public function dispatchOrderItem(){
        $dispatchData = array(
            'dispatch_by' => $this->input->post('dispatchBy'),
            'order_item_id' => $this->input->post('orderItemId'),
            'dispatch_quantity' => $this->input->post('dispatchQty'),
            'dispatch_unit' => $this->input->post('dispatchUnit'),
            'created_on' => $this->today
        );

        $response = $this->db->insert('order_item_dispatch', $dispatchData);
        if($response){
            return true;
        }
        else{
            return false;
        }
    }

    public function addDispatchQtyInOrderItem(){
        $orderItemId = $this->getOrderItemByOrderItemId($this->input->post('orderItemId'));
        $orderItemData = array(
            'dispatched_qty' => (float)$orderItemId->dispatched_qty + (float)$this->input->post('dispatchQty'),
        );
        $this->db->where('order_item_id', $this->input->post('orderItemId'));
        return $this->db->update('order_item', $orderItemData);
    }

    public function changeOrderStatus($status){
        $orderData = array(
            'order_status_catalog_id' => $status,
        );
        $this->db->where('order_id', $this->input->post('orderId'));
        $this->db->update('orders', $orderData);
    }

    public function checkIfOrderIsFullyDispatched(){
        $this->db->select('order_item_id, order_qty, dispatched_qty');
        $this->db->from('order_item');
        $this->db->where('order_id', $this->input->post('orderId'));
        $order_item = $this->db->get()->result_array();
        foreach ($order_item as $key => $oi){
            if($oi['dispatched_qty'] >=  $oi['order_qty']){
                unset($order_item[$key]);
            }
        }

        if(count($order_item) > 0){
            // ORDER IS NOT FULLY DISPATCHED.
            $this->changeOrderStatus(3);
        }
        else{
            // ORDER IS FULLY DISPATCHED.
            $this->changeOrderStatus(4);
        }
    }
        
    public function decreaseDispatchedQtyInOrderItem($orderItemId, $dispatchedQty, $dispatchedId){
        $order_item = $this->getOrderItemByOrderItemId($orderItemId);
        $itemDispatchQty = $order_item->dispatched_qty;
        $newQty = (float)$itemDispatchQty - (float)$dispatchedQty;
        
        $itemData = array(
            'dispatched_qty' => $newQty
        );

        $this->db->where('order_item_id', $orderItemId);
        $this->db->update('order_item', $itemData);
        $this->deleteDispatchedItem($dispatchedId);
    }

    public function deleteDispatchedItem($dispatchedId){
        $this->db->where('order_item_dispatch_id', $dispatchedId);
        return $this->db->delete('order_item_dispatch');
    }


    public function getOrderStatusCatalog(){
        $this->db->select('order_status_catalog_id,status_value');
        $this->db->from('order_status_catalog');
        $this->db->order_by('order_status_catalog_id','asc');
        $orderStatus = $this->db->get()->result_array();
        foreach ($orderStatus as $key => $value) {
            $orderStatus[$key]['value'] = $value['order_status_catalog_id'];
            $orderStatus[$key]['label'] = ucwords($value['status_value']);
            unset($orderStatus[$key]['order_status_catalog_id']);
            unset($orderStatus[$key]['status_value']);
        }
            
        return $orderStatus;
    }

//end class

}
