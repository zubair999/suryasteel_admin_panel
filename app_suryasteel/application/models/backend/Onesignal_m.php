<?php 
defined('BASEPATH') or exit('no dierct script access allowed');

class OneSignal_m extends MY_Model {

    public function __construct()
	{
		parent::__construct();   
	}

    public function send_push_notification($customer_company){
		$content = "You have a new order from ".$customer_company;
		$content = array(
			"en" => $content
		);
		$headings = array(
			"en" => 'New Order'
		);
	
		$one_signal_app_id = get_settings('onesignal_app_id');

		$fields = array(
			'app_id' => $one_signal_app_id,
			'headings' => $headings,
			'contents' => $content,
  			"included_segments" => ["Subscribed Users"]
		);
	
		$fields = json_encode($fields);
		// print("\nJSON sent:\n");
		// print($fields);
	
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8','Authorization: Basic NjA1MjFhMTQtZTc0Mi00MzkzLWFkYjYtNjJkZDI2NDRmNDVi'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);    
	
		$response = curl_exec($ch);
		curl_close($ch);
	
		// print_r($response);
		return $response;
	}


    public function send_push_notification_by_player_id($recepient_name, $oneSignalPlayId){
		$content = array(
			"en" => 'Your form has been filled kindly check your email and app.'
		);
		$headings = array(
			"en" => 'Hello '.$recepient_name
		);

		$pid = $oneSignalPlayId;
	
		$one_signal_app_id = get_settings('onesignal_app_id');

		$fields = array(
			'app_id' => $one_signal_app_id,
			'headings' => $headings,
			'contents' => $content,
			'large_icon' => "https://i0.wp.com/www.azoncode.com/wp-content/uploads/2017/03/fb-icom-1.png?fit=512%2C512&ssl=1",
  			"include_player_ids" => [$pid]
		);
	
		$fields = json_encode($fields);
		// print("\nJSON sent:\n");
		// print($fields);
	
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);    
	
		$response = curl_exec($ch);
		curl_close($ch);
	
		// print_r($response);
		return $response;
	}

//end class

}
