<?php
require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Stockmanufactured extends REST_Controller
{

	public function __construct() {
		parent::__construct();
	}

	public function addManufacturedStock_post(){
        $method = $this->_detect_method();
        if (!$method == 'POST') {
            $this->response(['status' => 400, 'messsage'=>'error', 'description' => 'Bad request.'], REST_Controller::HTTP_BAD_REQUEST);
            exit();
        }
        else{
            $this->form_validation->set_rules($this->stock_manufactured_m->addManufacturedStockRule);
            if ($this->form_validation->run() == FALSE) {
				$response = ['status' => 200, 'message' => 'error', 'description' => validation_errors()];
			} else {
                $isAdded = $this->galvanisation_m->addGalvanisationHistory($this->input->post('completedBy'));
                if($isAdded['status'] == 'success'){
                    $response = ['status' => 200, 'message' => 'success', 'description' => 'Galvanisation process history added for the current batch successfully.'];
                }
                else{
                    $response = ['status' => 200, 'message' => 'error', 'description' => $isAdded['message']];
                }
			}
            $this->response($response, REST_Controller::HTTP_OK);
            exit();
        }
	}

    public function getUserPermission_post(){
        $method = $this->_detect_method();
        if (!$method == 'POST') {
            $this->response(['status' => 200, 'message'=>'error', 'description' => 'Bad request.'], REST_Controller::HTTP_BAD_REQUEST);
        }
        else{
            $this->form_validation->set_rules('user_id','User Id', 'trim|required');
            if ($this->form_validation->run() == FALSE) {
                $res = ['status'=>200,'message'=>'error','description'=>'Invalid details'];
            }
            else{
                $user = $this->auth_m->getUserById($this->input->post('user_id'));
                $userPermission = $this->roles_m->getUserPermission($user->role_id);
                $userPermission = unserialize($userPermission);
                foreach($userPermission as $key => $p){
                    $userPermission[$p] = $p;
                    unset($userPermission[$key]);
                };

                $res = ['status'=>200,'message'=>'success','description'=>'User permission fetched successfully.', 'data'=>$userPermission];
            }
            $this->response($res, REST_Controller::HTTP_OK);
        }
    }


	

	






	//CLASS ENDS
}
