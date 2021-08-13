<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

// use namespace
use Restserver\Libraries\REST_Controller;
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
class Alamat extends REST_Controller {
	
	function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->database();      
        $this->load->model('Malamat'); 	
    }
    
	public function provinsi_get(){					
		$data = $this->Malamat->provinsi();		
		$this->response(array("status"=>200,"msg"=>"OK","data"=>$data));
	}
	public function kabupaten_post(){					
		$data = $this->Malamat->kabupaten();		
		$this->response(array("status"=>200,"msg"=>"OK","data"=>$data));
	}
	public function kecamatan_post(){					
		$data = $this->Malamat->kecamatan();		
		$this->response(array("status"=>200,"msg"=>"OK","data"=>$data));
	}
	public function kelurahan_post(){					
		$data = $this->Malamat->kelurahan();		
		$this->response(array("status"=>200,"msg"=>"OK","data"=>$data));
	}
}

/* End of file Alamat.php */
/* Location: ./application/controllers/android/Alamat.php */