<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* CodeIgniter
*
* An open source application development framework for PHP 5.1.6 or newer
*
* @package		CodeIgniter
* @author		ExpressionEngine Dev Team
* @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
* @license		http://codeigniter.com/user_guide/license.html
* @link		http://codeigniter.com
* @since		Version 1.0
* @filesource
*/

if (! function_exists('get_settings')) {
    function get_settings($key = '') {
        $CI	=&	get_instance();
        $CI->load->database();

        $CI->db->where('name', $key);
        $result = $CI->db->get('settings')->row('value');
        return $result;
    }
}


if (! function_exists('is_greater_than')) {
    function is_greater_than($number1 = '', $number2 = '') {
        $CI	=&	get_instance();
        $CI->load->database();

        if($number1 >= $number2){
            return true;
        }
        else{
            return false;
        }
    }
}

if (! function_exists('is_less_than')) {
    function is_less_than($number1 = '', $number2 = '') {
        $CI	=&	get_instance();
        $CI->load->database();

        if((float)$number1 < (float)$number2){
            return false;
        }
        else{
            return true;
        }
    }
}

if (! function_exists('is_greater_than_equal_to')) {
    function is_greater_than_equal_to($number1 = '', $number2 = '') {
        $CI	=&	get_instance();
        $CI->load->database();

        if($number1 >= $number2){
            return true;
        }
        else{
            return false;
        }
    }
}

if (! function_exists('is_equal_to')) {
    function is_equal_to($number1 = '', $number2 = '') {
        $CI	=&	get_instance();
        $CI->load->database();

        if($number1 == $number2){
            return true;
        }
        else{
            return false;
        }
    }
}

if (! function_exists('get_process_status')) {
    function get_process_status($number1 = '', $number2 = '') {
        $CI	=&	get_instance();
        $CI->load->database();

        if($number2 == 0){
            return 1;
        }
        else if($number1 == $number2){
            return 3;
        }
        else if($number1 > $number2){
            return 2;
        }
    }
}


if (! function_exists('check_row_count')) {
    function check_row_count($table, $col, $value) {
        $CI	=&	get_instance();
        $CI->load->database();

        return $CI->db->get_where($table, array($col=> $value))->num_rows();
    }
}


if (! function_exists('set_purchase_status_catalog')) {
    function set_purchase_status_catalog($id, $statusValue) {
        $CI	=&	get_instance();
        $CI->load->database();

        $CI->db->where("purchase_item_id", $id);
        $CI->db->set('purchase_status_catalog_id', $statusValue);        
        return $CI->db->update('purchase_item');
        
    }
}

if (! function_exists('get_order_item_status')) {
    function get_order_item_status($number1 = '', $number2 = '') {
        $CI	=&	get_instance();
        $CI->load->database();

        if($number2 == 0){
            return 1;
        }
        else if($number1 == $number2){
            return 3;
        }
        else if($number2 < $number1){
            return 2;
        }
        else if($number2 > $number1){
            return 4;
        }
        
    }
}





// ------------------------------------------------------------------------
/* End of file common_helper.php */
/* Location: ./system/helpers/common.php */
