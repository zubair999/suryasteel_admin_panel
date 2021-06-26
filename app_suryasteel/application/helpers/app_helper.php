<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('toLowerCase')){
  function toLowerCase($data){
    //get main CodeIgniter object
    $ci =& get_instance();
   	foreach ($data as $key => $value) {
   		$data[$key] = strtolower($value);
   	}
   	return $data;
   }
}




