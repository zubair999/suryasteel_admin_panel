<?php 
defined('BASEPATH') or exit('no dierct script access allowed');

class Stockhistory_m extends MY_Model {

	protected $tbl_name = 'product_stock_history';
    protected $primary_col = 'product_stock_history_id';
    protected $order_by = 'created_on';
    public $stock_manufactured_id;
    public $product_id;
    public $stock_type;
    public $stock_in_kg;
    public $stock_in_pcs;
    public $vendor_name;
    public $bill_no;
    public $added_by;
    public $name;


    public $addProductStockHistory = array(
        0 => array(
            'field' => 'stockManufacturedId',
            'label' => 'Stock Manufactured Id',
            'rules' => 'trim|required|is_natural'
        ),
    );

    public function __construct(){
		parent::__construct();
	}

    public function get_name(){
        return $this->name;
    }

    public function add_stock_history() {
        return array(
            'added_by' => $this->added_by,
            'stock_manufactured_id' => $this->stock_manufactured_id,
            'product_id' => $this->product_id,
            'stock_type' => $this->stock_type,
            'stock_in_kg' => $this->stock_in_kg,
            'stock_in_pcs' => $this->stock_in_pcs,
            'vendor_name' => $this->vendor_name,
            'bill_no' => $this->bill_no,
            'created_on' => $this->today
        );
    }

    public function addProductStockHistory(){
        $data = $this->add_stock_history();
        $isAdded = $this->db->insert($this->tbl_name, $data);
        if($isAdded){
            return true;
        }
        else{
            return false;
        }
    }

//end class
}
