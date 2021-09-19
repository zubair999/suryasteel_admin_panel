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
    }
}

if (! function_exists('get_process_status')) {
    function get_process_status($number1 = '', $number2 = '') {
        $CI	=&	get_instance();
        $CI->load->database();

        if($number1 == $number2){
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





// ------------------------------------------------------------------------
/* End of file common_helper.php */
/* Location: ./system/helpers/common.php */
