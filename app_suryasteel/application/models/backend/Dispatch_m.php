<?php 
defined('BASEPATH') or exit('no dierct script access allowed');

class Dispatch_m extends MY_Model {

	protected $tbl_name = 'order_item_dispatch';
    protected $primary_col = 'order_item_dispatch_id';
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

    public function updateDeliveryForDispatchItem(){
        $dispatchItem = $this->input->post('dispatchItem');
        foreach ($dispatchItem as $key => $di){
            $this->changeDispatchDeliveryStatus($di);
        }
    }

    public function changeDispatchDeliveryStatus($dispatchItemId){
        $deliveryData = array(
            'delivery_option_id' => $this->input->post('deliveryOptionId'),
            'delivery_status' => 'Delivered',
            'delivery_remarks' => $this->input->post('deliveryRemark'),
            'delivery_date' => $this->today
        );
        $this->db->where('order_item_dispatch_id', $dispatchItemId);
        $this->db->update('order_item_dispatch', $deliveryData);
    }


//end class
}
