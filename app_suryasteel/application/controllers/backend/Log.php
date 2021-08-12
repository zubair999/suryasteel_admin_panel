<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Log extends Backend_Controller {
    public function __construct() {
        parent::__construct();
    }

    public function index(){
        if(!in_array('viewLogs', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
        $this->data['drawTable'] 	= $this->logTableHead();
		$this->data['tableId']	    =	'logList';
		$this->data['pl']			=	null;
        $this->data['page_title'] = 'log list';
        $this->admin_view('backend/log/index', $this->data);
    }
    public function logTableHead(){
        $tableHead = array(
                  0 => 'sr. no.',
                  1 => 'created by',
                  2 => 'title',
                  3 => 'description',
                  4 => 'created on'
        );
        return $tableHead;
    }
    public function getLog(){
        $data = $this->log_m->getLog();
        echo json_encode($data);
    }

    
    

    

// CLASS ENDS
}


 
