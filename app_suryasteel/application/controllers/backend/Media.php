<?php

class Media extends Backend_Controller{

	public function __construct(){
		parent::__construct();
	}

	public function index(){
        $this->data['drawTable'] 	= $this->mediaTableHead();
		$this->data['tableId']	    =	'medialist';
		$this->data['pl']			=	false;
        $this->data['page_title'] = 'media list';
        $this->admin_view('backend/media/index', $this->data);
    }
    public function mediaTableHead(){
        $tableHead = array(
                  1 => 'Your media'
        );
        return $tableHead;
    }
    public function getMedia(){
        $data = $this->media_m->getMedia();
        echo json_encode($data);
    }


	// File upload
	public function fileUpload(){
		
		if(!empty($_FILES['file']['name'])){
				
			$renmae = md5(date('Y-m-d H:i:s:u')) . time();

			$ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
			$_FILES['file']['name'] = md5(date('Y-m-d H:i:s:u')) . time() . '.' . $ext;


			// Set preference
			$config['upload_path'] = 'upload/';	
			$config['allowed_types'] = 'jpg|jpeg|png|gif';
			$config['max_size']    = '1024'; // max_size in kb
			$config['file_name'] = $_FILES['file']['name'];


			
					
			//Load upload library
			$this->load->library('upload');			
			$this->upload->initialize($config);
			// File upload
			if($this->upload->do_upload('file')){
				// Get data about the file

				$uploadData = $this->upload->data();

            	



				$config2['image_library']    = 'gd2'; 
				$config2['source_image']     = 'upload/' . $uploadData['file_name']; 
				$config2['new_image']         = 'upload/' . 'thumb_' . $uploadData['file_name']; 
				$config2['maintain_ratio']     = false; 
				$config2['width']            = 150; 
				$config2['height']           = 150;

				$this->image_lib->initialize($config2);
				$this->image_lib->resize();



				$arr_data = [];
				$arr_data['actual'] = $uploadData['file_name'];
				$arr_data['thumbnail'] = 'thumb_' . $uploadData['file_name'];



				$this->db->insert('images', $arr_data);
				
				$this->image_lib->clear();

				
			}
		}
		
	}


	
	public function getAllImage() {
		$this->db->select('image_id, thumbnail');
		$this->db->from('images');
		$images = $this->db->get()->result_array();

		foreach($images as $key => $i){
			$images[$key]['thumbnail'] = BASEURL.'upload/'.$i['thumbnail'];
        }

		$res = ['status'=>200, 'message'=>'ok', 'data'=> $images];
		echo json_encode($res);
	}


	public function deleteimage(){
		foreach($this->input->post('image') as $i){
			$img = $this->db->get_where('images', array('image_id'=>$i))->row();
			if (file_exists('upload/'.$img->actual)){
				unlink('upload/'.$img->actual);
				unlink('upload/'.$img->thumbnail);
			}
		}
		
		$this->db->where_in('image_id', $this->input->post('image'));
		$this->db->delete('images');
		$res = ['status'=>200, 'message'=>'success', 'description'=>$this->input->post('image')];
		echo json_encode($res);
	}

//CLASS ENDS
}