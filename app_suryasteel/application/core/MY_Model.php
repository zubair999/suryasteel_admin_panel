<?php defined('BASEPATH') or exit ('No direct script access allowed');

class MY_Model extends Components
{
    public $tbl_class = 'classes';
    public $tbl_subjects = 'subjects';
    public $tbl_quiz_type = 'quiz_type';

    public function __construct(){
        parent::__construct();
    }

    public function add($data, $id = NULL){
        if($id !== NULL){
            $this->db->set($data);
            $this->db->where($this->primary_col, $id);
            return $this->db->update($this->tbl_name);
        }else{
            if(!($this->db->insert($this->tbl_name, $data))){
                $error = $this->db->error();
                if($error['code'] == 1062){
                    return "Already Exist!";
                }
            }
        }
    }


    public function rehash($arr){
        $i=0;
        foreach ($arr as $primary_hashed_key) {
            $primaryid = $this->outh_model->Encryptor('decrypt', $primary_hashed_key);
            $arr[$i] = $primaryid;
        $i++;
        }
        return $arr;
    }



    public function get_an_obj($tbl, $col, $where, $method){
        if($col == '*'){
          if($method == 'row'){
            if($where == null){
              return $this->db->get($tbl)->row();
            }
            else{
              return $this->db->get_where($tbl, $where)->row();
            }
          }
          else if($method == 'array'){
            if($where == null){
              return $this->db->get($tbl)->result_array();
            }
            else{
              return $this->db->get_where($tbl, $where)->result_array();
            }
          }
        }
        else {
          if($method == 'row'){
            if($where == null){
              return $this->db->get($tbl)->row();
            }
            else {
              return $this->db->get_where($tbl, $where)->row();
            }
          }
          else if($method == 'array'){
            if($where == null){
              return $this->db->get($tbl)->result_array();
            }
            else{
              return $this->db->get_where($tbl, $where)->result_array();
            }
          }
        }
    }

    public function delete($where){
		    return $this->db->delete($this->tbl_name, $where);
	  }

    public function duplicate($tbl,$where){
        return $this->db->get_where($tbl,$where)->num_rows();
    }

    public function get_obj_explode($select,$arr=null){
            $this->db->select($select);
            return $this->db->where_in($this->primary_col,explode(",", $arr))->get($this->tbl_name)->result_array();
    }



//CLASS END
}

class Components extends CI_Model{

  public function __construct(){
    parent::__construct();
  }

  public function hash($arr, $primaryColumnName){
      $i = 0;
      foreach ($arr as $value) {
          $primaryid = $value[$primaryColumnName];
          $crypted_id = $this->outh_m->Encryptor('encrypt', $primaryid);
          $arr[$i][$primaryColumnName.'Id'] = $crypted_id;
          $arr[$i][$primaryColumnName] = '';
          $i++;
      }
      return $arr;
  }

  public function select_city(){
      $data['class_arr'] = $this->hash( $this->db->get_obj('classes')->result_array(), 'cid');
      return $this->load->view('admin/components/form/select/select_class',$data,true);
  }
  public function select_year(){
      return $this->load->view('admin/components/form/select/select_year','',true);
  }
  public function select_subjects(){
    $data['subjects_arr'] = $this->hash( $this->db->get_obj('subjects')->result_array(), 'sjid');
    return $this->load->view('admin/components/form/select/select_subject',$data,true);
  }
  public function select_exam(){
    $data['exam_arr'] = $this->hash( $this->db->get_obj('quiz')->result_array(), 'quid');
    return $this->load->view('admin/components/form/select/select_exam',$data,true);
  }
  public function select_month(){
      $data['month_arr'] = array('0'=>'january','1'=>'february','2'=>'march','3'=>'april','4'=>'may','5'=>'june','6'=>'july','7'=>'august','8'=>'september','9'=>'octobar','10'=>'november','11'=>'december'
      );
      return $this->load->view('admin/components/form/select/select_month',$data,true);
  }
  public function dispatch_category(){
    $this->db->order_by('cname','asc');
    $data['category_arr'] = $this->db->get_obj('categories','cid,cname')->result_array();
    return $this->load->view('admin/components/form/select/select_category',$data,true);
  }
  public function select_city_web(){
      $data['name'] = 'city_id';
      $data['select_arr'] = $this->db->get_obj('city')->result_array();
      $data['key_value'] = 'city_id';
      $data['select_value'] = 'city_name';
      return $this->load->view('web/components/form/select_box_c',$data,true);
  }
  public function select_state_web(){
      $data['name'] = 'state_id';
      $data['select_arr'] = $this->db->get_obj('state')->result_array();
      $data['key_value'] = 'state_id';
      $data['select_value'] = 'state_name';
      return $this->load->view('web/components/form/select_box_c',$data,true);
  }




// CLASS ENDS
}
