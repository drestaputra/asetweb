<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

// use namespace
use Restserver\Libraries\REST_Controller;
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
class About extends REST_Controller {
	
	function __construct($config = 'rest') {
        parent::__construct($config);        
        $this->load->database();
        $this->load->model('Mabout');        
    }
    public function index_get()
    {         
     	$data=$this->Mabout->get_about();     	
     	$this->response($data);     
    }
    public function id_owner_get(){
        $id_kolektor = AUTHORIZATION::get_id_kolektor();
        $id_owner = $this->function_lib->get_id_owner($id_kolektor);
        if (trim($id_owner)!="") {
            $status = 200;
            $msg = $id_owner;
        }else{
            $status = 500;
            $msg = "";
        }
        $this->response(array("status"=>$status,"msg"=>$msg));     
    }
    public function bunga_default_get(){
        $id_kolektor = AUTHORIZATION::get_id_kolektor();
        $id_owner = $this->function_lib->get_id_owner($id_kolektor);
        if (trim($id_owner)!="") {
            $bunga = $this->function_lib->get_one('bunga_pinjaman','owner','id_owner='.$id_owner.'');
            $status = 200;
            $msg = $bunga;
        }
        $this->response(array("status"=>$status,"msg"=>$msg));     
    }
}

/* End of file About.php */
/* Location: ./application/controllers/android/About.php */