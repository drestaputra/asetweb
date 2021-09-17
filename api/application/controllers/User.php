<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

// use namespace
use Restserver\Libraries\REST_Controller;
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

class User extends Rest_Controller {

	function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->database();
        $this->load->model('Muser');
        AUTHORIZATION::check_token();
    }

    public function profil_get(){  
        $id_user = AUTHORIZATION::get_id_user();
        $status = 500;
        $msg = "Akun tidak ditemukan";
        $data = array();
        if (trim($id_user)!="") {
            $data_user = $this->function_lib->get_row('user','id_user="'.$id_user.'"');
            $id_provinsi = isset($data_user['provinsi']) ? $data_user['provinsi'] : "";
            $id_kabupaten = isset($data_user['kabupaten']) ? $data_user['kabupaten'] : "";
            $id_kecamatan = isset($data_user['kecamatan']) ? $data_user['kecamatan'] : "";
            $data_user['provinsi'] = isset($data_user['provinsi']) ? $data_user['provinsi'] : "";
            $data_user['kabupaten'] = isset($data_user['kabupaten']) ? $data_user['kabupaten'] : "";
            $data_user['kecamatan'] = isset($data_user['kecamatan']) ? $data_user['kecamatan'] : "";
            $data_user['label_provinsi'] = $this->function_lib->get_one('nama','provinsi','id="'.$id_provinsi.'"');
            $data_user['label_kabupaten'] = $this->function_lib->get_one('nama','kabupaten','id="'.$id_kabupaten.'"');
            $data_user['label_kecamatan'] = $this->function_lib->get_one('nama','kecamatan','id="'.$id_kecamatan.'"');
            $status = 200;
            $msg = "Sukses";
        }
        $response = array("status"=>$status,"msg"=>$msg,"data"=>$data_user);
        $this->response($response);
    }
    
    public function edit_profil_post(){
        $id_user = AUTHORIZATION::get_id_user();
        $cek = $this->function_lib->get_one('id_user','user','id_user="'.$id_user.'"');
        $status = 500;
        $msg = "";
        if (!empty($cek)) {
            $edit = $this->Muser->edit_profil($id_user);
            $status = isset($edit['status']) ? $edit['status'] : 500;
            $msg = isset($edit['msg']) ? $edit['msg'] : "";
        }else{
            $status = 500;
            $msg = "Data user tidak ditemukan";
        }
        $this->response(array("status"=>$status,"msg"=>$msg)); 
    }
    public function ganti_password_post(){
        $id_user = AUTHORIZATION::get_id_user();
        $cek = $this->function_lib->get_one('id_user','user','id_user="'.$id_user.'"');
        $status = 500;
        $msg = "";
        if (!empty($cek)) {
            $edit = $this->Muser->ganti_password($id_user);
            $status = isset($edit['status']) ? $edit['status'] : 500;
            $msg = isset($edit['msg']) ? $edit['msg'] : "";
        }else{
            $status = 500;
            $msg = "Data user tidak ditemukan";
        }
        $this->response(array("status"=>$status,"msg"=>$msg)); 
    }
    
}

/* End of file User.php */
/* Location: ./application/controllers/android/User.php */