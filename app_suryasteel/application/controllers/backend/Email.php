<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Email extends MY_Controller {
    public function __construct() {
        parent::__construct();
        parent::is_profile_set();
    }

    public function compose(){
    	$data['page_title'] = 'compose email';
        $this->view('admin/email/compose', $data);
    }
    public function send_email(){
    	echo $receiver = $this->input->post('to');
    	$subject = $this->input->post('subject');
    	$message = $this->input->post('message');
    	$this->sendEmail($message,$subject,$receiver);
    	
    }

// CLASS ENDS
}