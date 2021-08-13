<?php

defined('BASEPATH') OR exit('No direct script access allowed');
// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';

// use namespace
use Restserver\Libraries\REST_Controller;

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Dresta Twas Ardha Putra
 */

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

class Login extends REST_Controller  {

	function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->database();        

    }

	public function kolektor_post(){
		$this->load->library('form_validation');
		$response=array("status"=>500,"msg"=>"Gagal Login");
		$post=$this->input->post();
		if ($post) {
			$this->form_validation->set_rules('username', 'Username', 'trim|required');
			$this->form_validation->set_rules('password', 'Password', 'trim|required');
			if ($this->form_validation->run() == TRUE) {
				$this->load->model('Mandroid');								
				$hasil=$this->Mandroid->cek_login_kolektor();
				if ($hasil['status']==200) {
					$response["status"]=200;
					$response["msg"]=$hasil['msg'];
					$response["data"]=$hasil["data"];
				}else{
					$response["status"]=500;
					$response["msg"]=$hasil['msg'];
				}				
			}else{
				$response["status"]=500;
				$response["msg"]=validation_errors('','');
			}
		} else {
			$response["status"]=500;
			$response["msg"]="Terjadi kesalahan jaringan silahkan coba lagi";
		}
		$this->response($response);
		
	}	

	public function forget_password_post(){				
		$this->load->model('Mandroid');
		$forget = $this->Mandroid->forget_password();
		$status = isset($forget['status']) ? $forget['status'] : 500;
		$msg = isset($forget['msg']) ? $forget['msg'] : "";
		$this->response(array(
			"status" => $status,
			"msg" =>$msg
		));
	}

}

/* End of file Login.php */
/* Location: ./application/controllers/android/Login.php */