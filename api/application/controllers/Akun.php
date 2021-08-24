<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

// use namespace
use Restserver\Libraries\REST_Controller;
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
class Akun extends REST_Controller {
	
	function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->database();
       	AUTHORIZATION::check_token();
    }
    
	public function profil_post(){	
		$id_user = AUTHORIZATION::get_id_user();
		$status = 500;
		$msg = "Akun tidak ditemukan";
		$data = array();
		if (trim($id_user)!="") {
			$data_user = $this->function_lib->get_row('user','id_user="'.$id_user.'"');
			$status = 200;
			$msg = "Sukses";
		}
		$response = array("status"=>$status,"msg"=>$msg,"data"=>$data_user);
		$this->response($response);
	}
}

/* End of file Akun.php */
/* Location: ./application/controllers/android/Akun.php */