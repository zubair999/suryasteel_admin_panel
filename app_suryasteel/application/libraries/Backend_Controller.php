<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Backend_Controller extends MY_Controller {
   public function __construct() {
      parent::__construct();
   }
    
   public function admin_header($data){
      $this->data['page_title'] = 'header';
      $this->load->view('backend/includes/header', $this->data);
   }

   public function admin_footer($data){
      $this->load->view('backend/includes/footer', $this->data);
   }

   public function admin_main_top($data){
      $this->data['page_title'] = 'header';
      $this->load->view('backend/includes/main_top', $this->data);
   }

   public function admin_main_bottom($data){
      $this->load->view('backend/includes/main_bottom', $this->data);
   }

   public function admin_view($template, $data){
      $this->admin_header($data);
      $this->admin_main_top($data);
      $this->load->view($template, $data);
      $this->admin_main_bottom($data);
      $this->admin_footer($data);

   }

   public function status(){
      return array(
         0 => array(
            'status_id'=> 1,
            'status_value'=>'active'
         ),
         1 => array(
            'status_id'=> 2,
            'status_value'=>'inactive'
         )
      );
   }


// CLASS ENDS
}
