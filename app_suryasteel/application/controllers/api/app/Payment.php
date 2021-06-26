<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment extends MY_Controller {

	public function __construct(){
		parent::__construct();
	}

	public function check_payment_method(){
		if($this->input->post('paytm')){
			$this->paytm_request($this->input->post());
		}
	}

	private function razor_pay_request(){
		$this->data['key'] = RAZOR_KEY;
		$this->load->view('web/components/cart/payment.php',$this->data);
	}

	public function create_razorpay_order(){
		// if($_SERVER['REQUEST_METHOD'] == 'POST'){
		// 	$this->form_validation->set_rules('couponcode','Coupon', 'trim|alpha_numeric');
		// 	if($this->form_validation->run() === FALSE){
		// 		$res = ['status'=>404,'message'=>'Invalid coupon code.'];
		// 	}
		// 	else{
		// 		$course = $this->sanitize_array($this->input->post('course'));
		// 		if($course === FALSE){
		// 			$res = ['status'=>404,'message'=>'Invalid course detail.'];
		// 		}
		// 		else{
		// 			$couponCode = $this->input->post('couponcode');
		// 			$payment = $this->get_payment($course,$couponCode);
					$orderId = 'ORDER'.md5(uniqid(mt_rand(), true));
					$order = $this->razorpay->order->create(['receipt'=>$orderId,'amount'=>15000*100,'currency'=>'INR']);
					$res = ['status'=>200,'message'=>'success','order'=>$order->id, 'amount'=>15000];
		// 		}
		// 	}
		// }
		// else{
		// 	$res = ['status'=>404,'message'=>'error','description'=>'Baq request.'];
		// }
		$res = ['status'=>200, 'message' => $res];
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

	private function get_payment(array $course, string $couponCode){
		$courseTotal = 0;
		$discount = 0;
		$discountedPrice = 0;
		foreach($course as $c){
			$courseTotal += (float)$this->db->get_obj('course','sell_price',array('course_id'=>$c))->row()->sell_price;
		}
		$coupon = $this->is_coupon_valid($couponCode);
		if($coupon['message'] == 'Success'){
			$discount = (float)$courseTotal * (int)$coupon['percentage_amt']/100;
			$discountedPrice = (float)$courseTotal - (float)$discount;
			return ['total'=>$courseTotal,'discount'=>$discount,'discount_price'=>$discountedPrice];
		}
		return ['total'=>$courseTotal,'discount'=>$discount,'discount_price'=>$courseTotal];
	}

	private function is_coupon_valid(string $coupon=null){
		if($coupon){
			$couponCount = $this->db->get_obj('coupon','coupon_id',array('code'=>$coupon))->num_rows();
			if($couponCount > 0){
				$coupon_obj = $this->db->get_obj('coupon','*',array('code'=>$coupon))->row();
				$consumedCouponCount = $this->db->where(['coupon_id'=> $coupon_obj->coupon_id, 'auid'=>$this->auid])->count_all_results('consumed_coupon');
				if($this->current_time > $coupon_obj->expiry_date){
					$res = ['status'=>200,'code'=>104, 'message'=>'Coupon Expired', 'currentTime'=>$this->current_time, 'expiry'=>$coupon_obj->expiry_date];
				}
				elseif($consumedCouponCount > 0){
					$res = ['status'=>200,'code'=>102,'message'=>"Used"];
				}
				else{
					$res = ['status'=>200,'code'=>103,'message'=>"Success","percentage_amt"=>$coupon_obj->amount,'coupon_id'=>$coupon_obj->coupon_id];	
				}
			}
			else{
				$res = ['status'=>200,'code'=>103,'message'=>"No coupon available. Wrong coupon applied."];
			}
		}
		else{
			$res = ['status'=>200,'code'=>103,'message'=>"No coupon applied"];
		}
		return $res;
	}

	public function verify_razorpay_signature(){
		$generated_signature = hash_hmac('sha256', $this->input->post('order_id'). "|" . $this->input->post('payment_id'), RAZOR_KEY_SECRET);
		if ($generated_signature == $this->input->post('signature')) {
			$course = $this->sanitize_array($this->input->post('course'));
			if($course === FALSE){
				$res = ['status'=>404,'message'=>'Invalid course detail.'];
			}
			else{
				$courseTotal = 0;
				$discount = 0;
				$paid = 0;
				$couponCode = NULL;
				$couponId = NULL;
				$couponCode = $this->input->post('couponcode');
				$coupon = $this->is_coupon_valid($couponCode);
				if($coupon['message'] == 'Success'){
					$couponId = $coupon['coupon_id'];
				}
				$payment = $this->get_payment($course, $couponCode);
				$totalAmount = $payment['total'];
				$discount = $payment['discount'];
				$paid = $payment['discount_price'];

				$paymentRazorpay = array(
					'coupon_code' => $couponId,
					'order_id' => $this->input->post('order_id'),
					'payment_id' => $this->input->post('payment_id'),
					'signature' => $this->input->post('signature'),
					'is_verified' => 1,
					'auid' => $this->auid,
					'total' => $totalAmount,
					'discount' => $discount,
					'paid' => $paid
				);
				$this->save_razorpay_detail($paymentRazorpay);

				$paymentMain = array(
					'auid' => $this->auid,
					'transaction_id' => $this->db->insert_id(),
					'transaction_name' => $this->input->post('payment_id'),
					'payment_method_id' => 7,
					'coupan_id' => $couponId,
					'total_amount' => $totalAmount,
					'discount_amount' => $discount,
					'amount_paid' => $paid,
					'balance' => 0
				);
				$this->save_main_payment($paymentMain);
				$payId = $this->db->insert_id();
				$this->save_student_course($course, $payId, 'online');

				if($coupon['message'] == 'Success'){
					$couponData = array(
						'auid' => $this->auid,
						'payment_id' => $payId,
						'coupon_id' => $couponId
					);
					$this->save_coupon_detail($couponData);
				}
				$res = ['status'=>200,'message'=>'success'];
			}
		}
		else{
			$res = ['status'=>200,'message'=>'failure'];
		}
		echo json_encode($res);
	}

	private function save_razorpay_detail(array $razorpay){
		$this->db->insert('payment_by_razorpay', $razorpay);
	}

	private function save_main_payment(array $mainPayment){
		$this->db->insert('payment_main', $mainPayment);
	}

	private function save_student_course(array $studentCourse, string $lastPid, string $paymentMode){
		foreach($studentCourse as $cr){
			$course = $this->db->get_obj('course','sell_price,base_price',array('course_id'=>$cr))->row();
			$saveCourse = array(
				'payment_id' => $lastPid,
				'course_id' => $cr,
				'auid' => $this->auid,
				'base_price' => $course->base_price,
				'sell_price' => $course->sell_price,
				'payment_mode' => $paymentMode,
			);
			$this->db->insert('student_course', $saveCourse);
		}
	}

	private function save_coupon_detail(array $coupon){
		$this->db->insert('consumed_coupon', $coupon);
	}







	public function paytm_request(){
		// ORDER DETAIL
		$course = $this->sanitize_array($this->input->post('course'));
		if($course === FALSE){
			$res = ['status'=>404,'message'=>'Invalid course detail.'];
			redirect('cart/checkout');
		}
		else{
			$transaction = $this->get_payment($this->input->post('course'),$this->input->post('couponcode'));
			$pdetail_id_string = $this->arr_to_string($this->input->post('course'));
			
			// USER DETAIL
			$MOBILE = $this->phone;
			$STUDENTID = $this->auid;
			if(!empty($this->input->post('couponcode'))){
				$coupon_id = $this->db->get_obj('coupon','coupon_id',array('code'=>$this->input->post('couponcode')))->row()->coupon_id;
				$isCouponConsumed = $this->db->get_obj('consumed_coupon','consumed_coupon_id',array('coupon_id'=>$coupon_id))->num_rows();
				if($isCouponConsumed > 0){
					$NEWTXNAMOUNT = $transaction['total'];
					$coupanName = 'empty';
				}
				else{
					$discountPer = $this->db->get_obj('coupon','amount',array('code'=>$this->input->post('couponcode')))->row()->amount;
					$NEWTXNAMOUNT = round((float)($transaction['total']) - (float)($discountPer/100)*(float)$transaction['total'],0);
					$coupanName = $this->input->post('couponcode');	
				}
			}
			else{
				$NEWTXNAMOUNT = $transaction['total'];
				$coupanName = 'empty';
			}
			
			
			// GENERAING ORDER NO.
			$today = date("Ymd");
			$rand1 = rand(1111111111,time());
			$randBytes = bin2hex(random_bytes(10));
			$rand = strtoupper(substr(uniqid(sha1(time())),0,4));
			$ORDER = $today.$rand.$rand1.$randBytes;
			// $ORDER = $today.$rand.$rand1;

			define("MERCHENTMID", "YacywN87895340401626");
			define("ORDERID", $ORDER);
			define("CHANNEL", "WEB");
			define("TXNAMOUNT", $NEWTXNAMOUNT);
			define("WEBSITE", "WEBSTAGING");
			define("MOBILE", $MOBILE);
			define("CUSTID", $STUDENTID);
			define("INDUSTRYTYPE", "Ecommerce");

			$paytmParams = array();
			$paytmParams["MID"] = MERCHENTMID;
			$paytmParams["ORDER_ID"] = ORDERID;
			$paytmParams["CUST_ID"] = CUSTID;
			$paytmParams["MOBILE_NO"] = MOBILE;
			$paytmParams["CHANNEL_ID"] = CHANNEL;
			$paytmParams["TXN_AMOUNT"] = TXNAMOUNT;
			$paytmParams["WEBSITE"] = WEBSITE;
			$paytmParams["INDUSTRY_TYPE_ID"] = INDUSTRYTYPE;
			$paytmParams['MERC_UNQ_REF'] = $pdetail_id_string;

			


			// $paytmParams['AUTH_MODE'] = $qty_string;
			$paytmParams["CALLBACK_URL"] = BASE_URL.'/web/payment/response/'.$pdetail_id_string.'/'.$coupanName. '/'.$this->auid;
			$this->data['paytmParams'] = $paytmParams;
			$this->data['MKEY'] = 't1ELwM1dy1RLM4R&';
			$this->data['page_title'] = 'Fill your profile details.';
			$this->data['footer_cart'] = '';
			$this->web_view('web/cart/payment', $this->data);
			// $transactionURL = "https://securegw.paytm.in/theia/processTransaction"; // for production
		}
	}

	public function response($course,$coupanName,$user){
		$couponId = NULL;

		

		$studentCount = $this->db->get_obj('authentication','phone',array('auid'=>$user))->num_rows();
		if($studentCount > 0){
			$auth_obj = $this->db->get_obj('authentication','phone,role_id,is_active,is_verify,uid',array('auid'=>$user))->row();
			$student_obj = $this->db->get_obj('student','*',array('stu_phone'=>$auth_obj->phone))->row();
			

			$studentdata = array(
				'auid' => $user,
				'role_id' => $auth_obj->role_id,
				'active' => $auth_obj->is_active,
				'verified' => $auth_obj->is_verify,
				'phone' => $auth_obj->phone,
				'name' => $student_obj->stu_name,
				'email' => $student_obj->stu_username,
				'uid' => $auth_obj->uid
			  );

			$this->session->set_userdata($studentdata);
		}
    


		$course = explode("-",$course);

		$paytmChecksum = "";
		$paramList = array();
		$isValidChecksum = "FALSE";
		define('KEY', 't1ELwM1dy1RLM4R&');
		$paramList = $_POST;
		$paytmChecksum = isset($_POST["CHECKSUMHASH"]) ? $_POST["CHECKSUMHASH"] : ""; //Sent by Paytm pg
		//Verify all parameters received from Paytm pg to your application. Like MID received from paytm pg is same as your application’s MID, TXN_AMOUNT and ORDER_ID are same as what was sent by you to Paytm PG for initiating transaction etc.
		$isValidChecksum = verifychecksum_e($paramList, KEY, $paytmChecksum); //will return TRUE or FALSE string.










		if($isValidChecksum == "TRUE"){
			$payment = array(
				'AUID' => $this->auid,
				'ORDERID' => $_POST['ORDERID'],
				'MID' => $_POST['MID'],
				'TXNID' => $_POST['TXNID'],
				'TXNAMOUNT' => $_POST['TXNAMOUNT'],
				'PAYMENTMODE' => $_POST['PAYMENTMODE'],
				'CURRENCY' => $_POST['CURRENCY'],
				'TXNDATE' => $_POST['TXNDATE'],
				'STATUS' => $_POST['STATUS'],
				'RESPCODE' => $_POST['RESPCODE'],
				'RESPMSG' => $_POST['RESPMSG'],
				'GATEWAYNAME' => $_POST['GATEWAYNAME'],
				'BANKTXNID' => $_POST['BANKTXNID'],
				'BANKNAME' => $_POST['BANKNAME'],
				'CHECKSUMHASH' => $_POST['CHECKSUMHASH']
			);



			$this->db->insert('payment_by_paytm',$payment);
			$lpi = $this->db->insert_id();
			
			$coupon = $this->is_coupon_valid($coupanName);
			if($coupon['message'] == 'Success'){
				$couponId = $coupon['coupon_id'];
			}
			
			$transaction = $this->get_payment($course,$coupanName);

			$payment_main = array(
				'auid' => $this->auid,
				'transaction_id' => $lpi,
				'transaction_name' => 'PAYTM'.$_POST['ORDERID'],
				'payment_method_id' => 8,
				'coupan_id' => $couponId,
				'total_amount' => $transaction['total'],
				'discount_amount' => $transaction['discount'],
				'amount_paid' => $transaction['discount_price'],
				'date' => $this->current_time,
			);

			$this->save_main_payment($payment_main);
			$paymentId = $this->db->insert_id();

			// SAVING STUDENT COURSE
			$this->save_student_course($course, $paymentId, 'online');

			// SAVING COUPON CONSUMED
			if($coupon['message'] == 'Success'){
				$couponData = array(
					'auid' => $this->auid,
					'payment_id' => $paymentId,
					'coupon_id' => $couponId
				);
				$this->save_coupon_detail($couponData);
			}

			$this->session->set_flashdata('notification', 'Your payment of '.$_POST['TXNAMOUNT'].' is processed successfully. Click <a class="text-red" href="'.base_url('account').'" >here</a> to view your course. ');
			redirect('account');
			exit();
		}
		else {
			echo "somethiong went wrong";
			//Process transaction as suspicious.
		}
	}

	private function transaction_amount($course,$couponcode){
		$total = 0;
		if(!empty($couponcode)){
			$couponCount = $this->db->where('code',$couponcode)->get('coupon')->num_rows();
			if($couponCount > 0){
				$total = $this->cart_total_with_coupon($course,$couponcode);
			}
			else{
				$total = $this->cart_total_without_coupon($course,$couponcode);
			}
		}
		else{
			$total = $this->cart_total_without_coupon($course,$couponcode);
		}
		return $total;
	}

	private function cart_total_without_coupon($course,$couponcode){
		$couponAmount = 0;
		$total = 0;
		foreach ($course as $c) {
			$this->db->select('sell_price');
			$this->db->from('course');
			$this->db->where('course_id', $c);
			$total += (float)$this->db->get()->row()->sell_price;
		}
		return $total;
	}

	private function cart_total_with_coupon($course, $couponcode){
		$couponAmount = 0;
		$course_total = 0;
		$total = 0;
		foreach ($course as $c) { 
			$this->db->select('sell_price');
			$this->db->from('course');
			$this->db->where('course_id', $c);
			$course_total += (float)$this->db->get()->row()->sell_price;
		}
		$couponAmount = $this->db->where('code',$couponcode)->get('coupon')->row()->amount;
		$total = (int)($course_total - ($course_total * $couponAmount/100));
		return $total;
	}

	private function arr_to_string($course){
		return implode("-",$course);
	}


//CLASS ENDS
}











//End of file Payment.php /