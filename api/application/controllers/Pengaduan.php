<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

// use namespace
use Restserver\Libraries\REST_Controller;
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

class Pengaduan extends Rest_Controller {

	function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->database();
        $this->load->model('Mpengaduan');
        // AUTHORIZATION::check_token();
    }
      
    public function kirim_post(){
    	// nama_lengkap, email,isi aduan
        $id_kolektor = AUTHORIZATION::get_id_kolektor();        
        // cek apakah informasi program tersebut milik owner dari kolektor yg request, atau yg id_owner nya = null atau 0
        $id_owner = $this->function_lib->get_id_owner($id_kolektor);        
        $response = $this->Mpengaduan->kirim($id_owner);            
        $status = isset($response['status']) ? $response['status'] : 500;
        $msg = isset($response['msg']) ? $response['msg'] : "";        
        $this->response(array("status"=>$status,"msg"=>$msg));    
    }      
}

/* End of file Pengaduan.php */
/* Location: ./application/controllers/android/Pengaduan.php */