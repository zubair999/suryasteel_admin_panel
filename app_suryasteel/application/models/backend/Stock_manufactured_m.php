<?php 
defined('BASEPATH') or exit('no dierct script access allowed');

class Stock_manufactured_m extends MY_Model {

	protected $tbl_name = 'stock_manufactured';
    protected $primary_col = 'stock_manufactured_id';
    protected $order_by = 'created_on';

    public $addManufacturedStockRule = array(
        0 => array(
            'field' => 'categoryId',
            'label' => 'category',
            'rules' => 'trim|required'
        ),
        1 => array(
            'field' => 'imageId',
            'label' => 'Image',
            'rules' => 'trim|required'
        )
    );

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
                    sm.is_added_to_stock,
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

            $this->db->order_by('sm.is_added_to_stock', 'asc');

            $stock_manufactured = $this->db->get()->result_array();
    
            return $stock_manufactured;
        }
        else{
            return [];
        }
    }

    public function total_manufactured_weight($purchase_item_id){
        $stock_manufactured_count = $this->getStockManufacturedCountByPurchaseItemId($purchase_item_id);
        if($stock_manufactured_count > 0){
            $this->db->select_sum('stock_in_kg');
            $this->db->from('stock_manufactured');
            $this->db->where('purchase_item_id', $purchase_item_id);
            $sum = $this->db->get()->result();
            return $sum[0]->stock_in_kg;
        }
        else{
            return 0;
        }
    }

    public function total_manufactured_piece($purchase_item_id){
        $stock_manufactured_count = $this->getStockManufacturedCountByPurchaseItemId($purchase_item_id);

        if($stock_manufactured_count > 0){
            $this->db->select_sum('stock_in_pcs');
            $this->db->from('stock_manufactured');
            $this->db->where('purchase_item_id', $purchase_item_id);
            $sum = $this->db->get()->result();
            return $sum[0]->stock_in_pcs;
        }
        else{
            return 0;
        }
    }


//end class
}
