<?php 
defined('BASEPATH') or exit('no dierct script access allowed');

class Stock_manufactured_m extends MY_Model {

	protected $tbl_name = 'stock_manufactured';
    protected $primary_col = 'stock_manufactured_id';
    protected $order_by = 'created_on';

    public function __construct(){
		parent::__construct();
	}

    public function getStockManufacturedCountByPurchaseItemId($purchaseItemId) {
        return $this->db->get_where('stock_manufactured', array('stock_manufactured_id'=> $purchaseItemId))->num_rows();
    }

    public function stock_manufactured($purchase_item_id){
        $stock_manufactured_count = $this->getStockManufacturedCountByPurchaseItemId($purchase_item_id);
        
        if($stock_manufactured_count > 0){
            $this->db->select(
                '
                    sm.stock_manufactured_id,
                    sm.stock_in_kg,
                    sm.stock_in_pcs,
                    DATE_FORMAT(sm.created_on, "%d-%b-%Y") as created_on,
                    ct.category_name,
                    sz.size_value,
                    ln.length_value
                '
            );
            
            $this->db->from('stock_manufactured as sm');
            $this->db->join('category as ct', 'ct.category_id = sm.category_id');
            $this->db->join('size as sz', 'sz.size_id = sm.size_id');
            $this->db->join('length as ln', 'ln.length_id = sm.length_id');
            $this->db->where('sm.purchase_item_id', $purchase_item_id);
            $stock_manufactured = $this->db->get()->result_array();
    
            return $stock_manufactured;
        }
        else{
            return [];
        }
    }


//end class
}
