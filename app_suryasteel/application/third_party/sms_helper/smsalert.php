<?php

/**
 *  @author    Cozy Vision Technologies Pvt. Ltd.
 *  @copyright 2010-2016 cozyvision Technology Pvt Ltd.
 */
 class Smsalert{
	private $apikey;         // declare api key of user
	private $sender;       // declare senderid of user
	private $route;         // declare route of user


	 //constructor passes the values of variable
	public function __construct($apikey,$sender,$route){
		$this->apikey=$apikey;
		$this->sender=$sender;
		$this->route=$route;
	}

	// function for sending sms
	public function send($mobileno,$message)
	{
		$url = "http://www.smsalert.co.in/api/push.json?apikey=$this->apikey&route=$this->route&sender=$this->sender&mobileno=$mobileno&text=".urlencode($message);
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$output = curl_exec($ch);
		curl_close($ch);
		return json_decode($output,true);
    }



    public function generateOtp($mobileno,$message)
	{
		$url = "http://www.smsalert.co.in/api/mverify.json?apikey=$this->apikey&sender=$this->sender&mobileno=$mobileno&template=".urlencode($message);
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$output = curl_exec($ch);
		curl_close($ch);
		return json_decode($output,true);
    }


    public function verifyOtp($mobileno,$code)
	{
		$url = "http://www.smsalert.co.in/api/mverify.json?apikey=$this->apikey&mobileno=$mobileno&code=$code";
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$output = curl_exec($ch);
		curl_close($ch);
		return json_decode($output,true);
    }



 }
