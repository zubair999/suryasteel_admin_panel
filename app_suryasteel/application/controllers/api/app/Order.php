<?php

class Order extends MY_Controller{

	public function __construct(){
		parent::__construct();
	}

	public function create_razorpay_order(){
        $totalAmount = $this->getTotalAmountOfProduct($this->input->post('product'), $this->input->post('quantity'));
        $orderId = 'ORDER'.md5(uniqid(mt_rand(), true));
        $order = $this->razorpay->order->create(['receipt'=>$orderId,'amount'=>$totalAmount['total_sell_price']*100,'currency'=>'INR']);
        $res = ['status'=>200,'message'=>'success','order'=>$order->id, 'amount'=>$totalAmount['total_sell_price']];
        echo json_encode($res);
        exit();
	}

    public function verify_razorpay_signature(){
		$generated_signature = hash_hmac('sha256', $this->input->post('order_id'). "|" . $this->input->post('payment_id'), RAZOR_KEY_SECRET);
		if ($generated_signature == $this->input->post('signature')) {
			$product = $this->sanitize_array($this->input->post('product'));
            $quantity = $this->sanitize_array($this->input->post('quantity'));
			if($product === FALSE){
				$res = ['status'=>404,'message'=>'Invalid product detail.'];
			}
			else{
				$courseTotal = 0;
				$discount = 0;
				$paid = 0;
				$couponId = NULL;
                $totalAmount = $this->getTotalAmountOfProduct($this->input->post('product'), $this->input->post('quantity'));
				$totalAmount = $totalAmount;

				$paymentRazorpay = array(
					'coupon_code' => $couponId,
					'order_id' => $this->input->post('order_id'),
					'payment_id' => $this->input->post('payment_id'),
					'signature' => $this->input->post('signature'),
					'is_verified' => 1,
					'user_id' => $this->input->post('user_id'),
					'total_mrp' => $totalAmount['total_mrp'],
					'total_sell_price' => $totalAmount['total_sell_price'],
					'discount' => $totalAmount['total_mrp']-$totalAmount['total_sell_price'],
					'paid' => $totalAmount['total_sell_price']
				);
				$this->save_razorpay_detail($paymentRazorpay);

				$paymentMain = array(
					'user_id' => $this->input->post('user_id'),
					'transaction_id' => $this->db->insert_id(),
					'transaction_name' => $this->input->post('payment_id'),
					'payment_method_id' => 7,
					'coupon_id' => $couponId,
					'total_mrp' => $totalAmount['total_mrp'],
					'total_sell_price' => $totalAmount['total_sell_price'],
					'discount_amount' => $totalAmount['total_mrp']-$totalAmount['total_sell_price'],
					'amount_paid' => $totalAmount['total_sell_price'],
				);
				$this->save_main_payment($paymentMain);
				$payId = $this->db->insert_id();

                $this->db->select('user_address_id');
                $this->db->from('user_address');
                $this->db->where(['user_id'=> $this->input->post('user_id'), 'is_default'=>1]);
                $shipping_id = $this->db->get()->row()->user_address_id;


                $orderData = array(
                    'payment_main_id' => $payId,
                    'shipping_id' => $shipping_id,
                    'user_id' => $this->input->post('user_id'),
                    'total_mrp' => $totalAmount['total_mrp'],
					'total_sell_price' => $totalAmount['total_sell_price'],
                    'discount' => $totalAmount['total_mrp']-$totalAmount['total_sell_price'],
					'paid' => $totalAmount['total_sell_price'],
                );

                $this->db->insert('orders', $orderData);
                $order_id = $this->db->insert_id();
                $this->save_order_item($product, $quantity, $order_id);
                $this->decrease_stock($product, $quantity);
				$res = ['status'=>200,'message'=>'success'];
			}
		}
		else{
			$res = ['status'=>200,'message'=>'failure'];
		}
		echo json_encode($res);
	}

    private function sanitize_array(array $course) {
		if(is_array($course)){
			if(array_filter($course) == []){
				return false;
			}
			else{
				foreach ($course as $c){
					if(is_int($c) && (int)$c > 0 || ctype_digit($c)){
						return $course;
					}
					else{
						return false;
					}
				}
			}
		}
		else{
			return false;
		}
	}

    private function getProductDetail($id){
        $this->db->select('p.mrp, p.sell_price, p.stock, g.gst_value');
        $this->db->from('products as p');
        $this->db->join('gst as g', 'p.gst_id = g.gst_id');
        $this->db->where('p.product_id', $id);
        return $this->db->get()->row();
    }

    private function getTotalAmountOfProduct($id){
        $total_mrp = 0;
        $total_sell_price = 0;
        foreach(array_combine($this->input->post('product'), $this->input->post('quantity')) as $p => $q){
            $product = $this->getProductDetail($p);
            $total_mrp += (int)$q*(float)$product->mrp;
            $total_sell_price += (int)$q*(float)$product->sell_price;
        }
        return ['total_mrp' => $total_mrp, 'total_sell_price'=> $total_sell_price];
    }

    public function getMyOrder(){
        $id = $this->input->post('user_id');
        $this->db->select('
                            o.order_id, 
                            o.total_mrp, 
                            o.total_sell_price,
                            o.status,
                            o.created_on,
                            ua.shipping_name,
                            ua.mobile_no,
                            ua.pincode,
                            ua.flat_house_no,
                            ua.tower_no,
                            ua.building_apartment,
                            ua.address,
                            ua.city_state
                        '
                    );
        $this->db->from('orders as o');
        $this->db->join('user_address as ua', 'o.shipping_id = ua.user_address_id');
        $this->db->where('ua.user_id', $id);
        $query =  $this->db->get();
        $orders = $query->result();

       
        if(!empty($orders)){
            $res = ['status'=>200, 'message'=>'successs', 'data'=>$orders ];
        }else{
            $res = ['status'=>200, 'message'=>'error'];
        }
        echo json_encode($res);
        exit();
    }


    private function save_razorpay_detail(array $razorpay){
		$this->db->insert('payment_by_razorpay', $razorpay);
	}

	private function save_main_payment(array $mainPayment){
		$this->db->insert('payment_main', $mainPayment);
	}

    private function save_order_item(array $product, array $quantity, string $orderId){
        if(!empty($orderId)){
            foreach(array_combine($product, $quantity) as $p => $q){

                $product = $this->getProductDetail($p);

                $data = array(
                    'order_id' => $orderId,
                    'user_id' => $p,
                    'product_id' => $p,
                    'qty' => $q,
                    'product_mrp' => $product->mrp,
                    'product_sell_price' => $product->mrp,
                    'gst_value' => $product->gst_value,
                    'total_product_cost'=> $q*$product->mrp
                );

                $this->db->insert('order_item', $data);
                $this->db->insert_id();
            }
        }else{
            $res = ['status'=>200, 'message'=>'error'];
        }
	}

    private function decrease_stock(array $product, array $quantity){
        foreach(array_combine($product, $quantity) as $p => $q){
            $product = $this->getProductDetail($p);
            $currentStock = $product->stock;
            $newStock = $currentStock - $q;
            $data = array(
                'stock' => $newStock,
            );
            $this->db->where('product_id', $p);
            $this->db->update('products', $data);
        }
    }

//CLASS ENDS
}