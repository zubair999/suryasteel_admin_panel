<?php

class Useraddress extends MY_Controller{

	public function __construct(){
		parent::__construct();
	}


    public function add_user_address(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $this->form_validation->set_rules('user_id', 'Shipping Name', 'trim|required|is_natural_no_zero');
            $this->form_validation->set_rules('shipping_name', 'Shipping Name', 'trim|required|alpha_numeric_spaces');
            $this->form_validation->set_rules('mobile_no', 'Mobile no', 'trim|required|is_natural|exact_length[10]');
            $this->form_validation->set_rules('pincode', 'Pincode', 'trim|required|is_natural');
            $this->form_validation->set_rules('flat_house_no', 'Flat/House No', 'trim|alpha_numeric_spaces');
            $this->form_validation->set_rules('tower_no', 'Tower No', 'trim|alpha_numeric_spaces');
            $this->form_validation->set_rules('building_apartment', 'Building Apartment', 'trim|alpha_numeric_spaces');
            $this->form_validation->set_rules('address', 'Address', 'trim|required');
            $this->form_validation->set_rules('landmark_area', 'Landmark Area', 'trim|alpha_numeric_spaces');
            $this->form_validation->set_rules('city_state', 'City State', 'trim|alpha_numeric_spaces');
            if($this->form_validation->run() === FALSE){
                $errors = array( 
                    'shipping_name'=> (form_error('shipping_name') ? "Please enter shipping name." : null),
                    'mobile_no'=> (form_error('mobile_no') ? "Please enter mobile no." : null),
                    'pincode'=> (form_error('pincode') ? "Please enter pincode." : null),
                    'address'=> (form_error('address') ? "Please enter address." : null),
                );
                $res = ['status'=> 200,'message'=>'error','description'=> 'Invalid Detail', 'validation'=>$errors];
            }
            else{

                $this->db->set('is_default',0);
                $this->db->where('user_id',$this->input->post('user_id'));
                $this->db->update('user_address');

                $data = array(
                    'user_id' => $this->input->post('user_id'),
                    'is_default' =>1,
                    'shipping_name'=>$this->input->post('shipping_name'),
                    'mobile_no'=>$this->input->post('mobile_no'),
                    'pincode'=>$this->input->post('pincode'),
                    'flat_house_no'=>$this->input->post('flat_house_no'),
                    'tower_no'=>$this->input->post('tower_no'),
                    'building_apartment'=>$this->input->post('building_apartment'),
                    'address'=>$this->input->post('address'),
                    'landmark_area'=>$this->input->post('landmark_area'),
                    'city_state'=>$this->input->post('city_state')
                );

                $isDataSaved = $this->db->insert('user_address',$data);
                if($isDataSaved){

                    $this->db->select('*');
                    $this->db->from('user_address');
                    $this->db->where('user_id', $this->input->post('user_id'));
                    $this->db->order_by('is_default', 'desc');
                    $newlySavedAddresses = $this->db->get()->result_array();

                    $res = ['status'=> 200,'message'=>'success','description'=> 'New address saved successfully.', 'data'=>$newlySavedAddresses];
                }
                else{
                    $res = ['status'=> 200,'message'=>'error','description'=>'Something went wrong. Try again.'];
                }
            }
        }
        else{
            $res = ['status'=> 200,'message'=>'error','description'=> 'Bad request.'];
        }
        echo json_encode($res);
    }

    public function edit_user_address(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $this->form_validation->set_rules('user_id', 'Shipping Name', 'trim|required|is_natural_no_zero');
            $this->form_validation->set_rules('shipping_name', 'Shipping Name', 'trim|required|alpha_numeric_spaces');
            $this->form_validation->set_rules('mobile_no', 'Mobile no', 'trim|required|is_natural|exact_length[10]');
            $this->form_validation->set_rules('pincode', 'Pincode', 'trim|required|is_natural');
            $this->form_validation->set_rules('flat_house_no', 'Flat/House No', 'trim|required|alpha_numeric_spaces');
            $this->form_validation->set_rules('tower_no', 'Tower No', 'trim|required|alpha_numeric_spaces');
            $this->form_validation->set_rules('building_apartment', 'Building Apartment', 'trim|required|alpha_numeric_spaces');
            $this->form_validation->set_rules('address', 'Address', 'trim|required');
            $this->form_validation->set_rules('landmark_area', 'Landmark Area', 'trim|required|alpha_numeric_spaces');
            $this->form_validation->set_rules('city_state', 'City State', 'trim|required|alpha_numeric_spaces');
            if($this->form_validation->run() === FALSE){
                $res = ['status'=> 200,'message'=>'error','description'=> 'Invalid Detail'];
            }
            else{
                $this->db->set('is_default',0);
                $this->db->where('user_id',$this->input->post('user_id'));
                $this->db->update('user_address');

                $data = array(
                    'is_default' =>$this->input->post('is_default'),
                    'shipping_name'=>$this->input->post('shipping_name'),
                    'mobile_no'=>$this->input->post('mobile_no'),
                    'pincode'=>$this->input->post('pincode'),
                    'flat_house_no'=>$this->input->post('flat_house_no'),
                    'tower_no'=>$this->input->post('tower_no'),
                    'building_apartment'=>$this->input->post('building_apartment'),
                    'address'=>$this->input->post('address'),
                    'landmark_area'=>$this->input->post('landmark_area'),
                    'city_state'=>$this->input->post('city_state'),
                    'updated_on'=>$this->current_time
                );

                $this->db->where('user_id',$this->input->post('user_id'));
                $isDataSaved = $this->db->update('user_address', $data);
                if($isDataSaved){
                    $res = ['status'=> 200,'message'=>'success','description'=> 'Address updated successfully.'];
                }
                else{
                    $res = ['status'=> 200,'message'=>'error','description'=> 'Something went wrong.'];
                }
            }   
        }
        else{
            $res = ['status'=> 200,'message'=>'error','description'=>'Bad request.'];
        }
        echo json_encode($res);

    }

    public function change_default_address(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $this->form_validation->set_rules('user_id', 'User Id', 'trim|required|is_natural_no_zero');
            $this->form_validation->set_rules('user_address_id', 'User Address Id', 'trim|required|is_natural_no_zero');
            if($this->form_validation->run() === FALSE){
                $res = ['status'=> 200,'message'=>'error','description'=> 'Invalid Detail'];
            }
            else{
                $this->db->set('is_default',0);
                $this->db->where('user_id',$this->input->post('user_id'));
                $this->db->update('user_address');

                $this->db->set('is_default',1);
                $this->db->where('user_address_id',$this->input->post('user_address_id'));
                $isDataSaved = $this->db->update('user_address');

                if($isDataSaved){

                    $this->db->select('*');
                    $this->db->from('users');
                    $this->db->where('user_id', $this->input->post('user_id'));
                    $user = $this->db->get()->row();

                    $this->db->select('*');
                    $this->db->from('user_address');
                    $this->db->where('user_id', $this->input->post('user_id'));
                    $this->db->order_by('is_default', 'desc');
                    $newSavedAddresses = $this->db->get()->result_array();
                    
                    $newdata = array(
                        'uid' => $user->user_id,
                        'email' => $user->email,
                        'phone' => $user->contact,
                        'name' => $user->name,
                        'role_id' => $user->role_id,
                        'user_address' => $newSavedAddresses,
                    );

                    $res = ['status'=> 200,'message'=>'success','description'=> 'Address updated successfully.', 'data'=>$newdata];
                }
                else{
                    $res = ['status'=> 200,'message'=>'error','description'=> 'Something went wrong.'];
                }
            }
        }
        else{
            $res = ['status'=> 200,'message'=>'error','description'=> 'Bad request.'];    
        }
        echo json_encode($res);
    }


//CLASS ENDS
}