<?php

class Banner extends MY_Controller{

	public function __construct(){
		parent::__construct();
	}

	public function getbanner(){
        

        $banner = [
            "https://admin.landmark.revopions.com/upload/5752f5803130f3336f3eb6998615df391619201110.jpg",
            "https://admin.landmark.revopions.com/upload/5752f5803130f3336f3eb6998615df3916192011101.jpg",
            "https://admin.landmark.revopions.com/upload/6bdf984a296f4ab5f70fa61526c3b2871619201111.jpg",
        ];

        echo json_encode($banner);
        exit();
	}

    


	



//CLASS ENDS
}