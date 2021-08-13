<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

// use namespace
use Restserver\Libraries\REST_Controller;
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

class Kolektor extends Rest_Controller {

	function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->database();
        $this->load->model('Mkolektor');
        AUTHORIZATION::check_token();
    }

    public function data_kolektor_post()
    {
        $id_kolektor = AUTHORIZATION::get_id_kolektor();
        $params = isset($_POST) ? $_POST : array();
        $start = (int)$this->input->post('page');
        $id_owner = $this->function_lib->get_one('id_owner','kolektor','id_kolektor="'.$this->security->sanitize_filename($id_kolektor).'"');
        $id_owner = (isset($id_owner) AND !empty($id_owner)) ? $id_owner : "0";
        $additional_where= ' AND id_owner="'.$id_owner.'"';
        
        $query_arr= $this->Mkolektor->data_kolektor($params,$custom_select='',$count=false,$additional_where);        
        $query = $query_arr['query'];
        $total = $query_arr['total'];
        $status = $query_arr['status'];
        $msg = $query_arr['msg'];
        $response=$query->result_array();        
        $nasabah = array();
        $perPage=((int)$this->input->post('perPage')>0)?$this->input->post('perPage'):$total;
        if ($total!=0) {            
        $totalPage=ceil($total/$perPage)-1;
        }else{$totalPage=0;}                        
        $json_data = array('status'=>$status,'msg'=>$msg,'page' => $start,'totalPage'=>$totalPage, 'recordsFiltered' => ((int)$this->input->post('perPage')>0)?$this->input->post('perPage'):$total, 'totalRecords' => $total, 'data' => $response);
       

        $this->response($json_data);    
    }    
    public function get_all_kolektor_username_get(){
    	$id_kolektor = AUTHORIZATION::get_id_kolektor();        
        $id_owner = $this->function_lib->get_one('id_owner','kolektor','id_kolektor="'.$this->security->sanitize_filename($id_kolektor).'"');
        $id_owner = (isset($id_owner) AND !empty($id_owner)) ? $id_owner : "0";
        $data = $this->Mkolektor->get_all_kolektor_username($id_owner,$id_kolektor);
        if (!empty($data)) {
        	$status = 200;
        	$msg = "OK";        	
        }else{
        	$status = 500;
        	$msg = "";
        	$data = array();
        }
        $this->response(array("status"=>$status,"msg"=>$msg,"data"=>$data));    
    }
    public function request_oper_berkas_post(){
    	$id_kolektor = AUTHORIZATION::get_id_kolektor();
    	$status = 500;
    	$msg = "";
    	$id_nasabah = $this->input->post('id_nasabah',TRUE);
    	$cek_id_nasabah = $this->function_lib->get_one('id_nasabah','nasabah','id_nasabah="'.$this->security->sanitize_filename($id_nasabah).'" AND status="aktif"');
    	if (!empty($cek_id_nasabah)) {    		
    		$request = $this->Mkolektor->request_oper_berkas($id_kolektor);
    		$status = isset($request['status']) ? $request['status'] : 500;
    		$msg = isset($request['msg']) ? $request['msg'] : "";
    	}

    	$response = array("status"=>$status,"msg"=>$msg);
    	$this->response($response);    
    }
    public function profil_get(){  
        $id_kolektor = AUTHORIZATION::get_id_kolektor();
        $status = 500;
        $msg = "Akun tidak ditemukan";
        $data = array();
        if (trim($id_kolektor)!="") {
            $data_kolektor = $this->function_lib->get_row('kolektor','id_kolektor="'.$id_kolektor.'"');
            $id_provinsi = isset($data_kolektor['provinsi']) ? $data_kolektor['provinsi'] : "";
            $id_kabupaten = isset($data_kolektor['kabupaten']) ? $data_kolektor['kabupaten'] : "";
            $id_kecamatan = isset($data_kolektor['kecamatan']) ? $data_kolektor['kecamatan'] : "";
            $data_kolektor['label_provinsi'] = $this->function_lib->get_one('nama','provinsi','id="'.$id_provinsi.'"');
            $data_kolektor['label_kabupaten'] = $this->function_lib->get_one('nama','kabupaten','id="'.$id_kabupaten.'"');
            $data_kolektor['label_kecamatan'] = $this->function_lib->get_one('nama','kecamatan','id="'.$id_kecamatan.'"');
            $status = 200;
            $msg = "Sukses";
        }
        $response = array("status"=>$status,"msg"=>$msg,"data"=>$data_kolektor);
        $this->response($response);
    }
    public function ratio_modal_get(){  
        $id_kolektor = AUTHORIZATION::get_id_kolektor();
        $id_owner = $this->function_lib->get_id_owner($id_kolektor);
        $status = 500;
        $msg = "Akun tidak ditemukan";
        $data = array();
        $ratio_modal_pinjaman = 0;
        $ratio_modal_config_owner = 0;
        $ratio_msg = "";
        $ratio_string = "";
        $ratio = 0;
        if (trim($id_kolektor)!="") {
            $total_modal_usaha = $this->function_lib->get_one('sum(jumlah_modal_usaha)','modal_usaha','status_modal_usaha="aktif" AND id_owner="'.$this->db->escape_str($id_owner).'"');
            $total_modal_simpanan = $this->function_lib->get_one('sum(jumlah_simpanan)','simpanan','status_simpanan="aktif" AND id_owner="'.$this->db->escape_str($id_owner).'"');
            $total_modal = floatval($total_modal_usaha) + floatval($total_modal_simpanan);
            $total_pinjaman = $this->function_lib->get_one('sum(jumlah_pinjaman)','pinjaman','status_pinjaman="aktif" AND id_owner="'.$this->db->escape_str($id_owner).'"');
            $ratio_modal_pinjaman = floatval($total_pinjaman) / floatval($total_modal);
            $ratio_modal_config_owner = (float) $this->function_lib->get_one('ratio_modal','owner','id_owner='.$this->db->escape($id_owner).'');

            if (($ratio_modal_pinjaman) > floatval($ratio_modal_config_owner/100)) {
                // ratio over limit
                $ratio_msg = "Ratio Modal melebihi batas, tidak bisa melakukan pinjaman";
            }else{
                $ratio_msg = "Ratio Modal masih aman, Anda masih bisa menyalurkan pinjaman";
            }       
            $ratio_modal_pinjaman = (int)ceil(($ratio_modal_pinjaman/($ratio_modal_config_owner/100))*100);
            $ratio_string = $ratio_modal_pinjaman."%";

            $status = 200;
            $msg = "Aman";
            // kirimkan pesan melebihi atau tidak
        }
        $data = array(
            "ratio" => $ratio_modal_pinjaman,
            "ratio_config" => $ratio_modal_config_owner,
            "ratio_msg" => $ratio_msg,
            "ratio_string" => $ratio_string,
        );
        $response = array("status"=>$status,"msg"=>$msg,"data" => $data);
        $this->response($response);
    }
    public function summary_kolektor_get(){         
        $id_kolektor = AUTHORIZATION::get_id_kolektor();
        $data = array();
        $cek = $this->function_lib->get_one('id_kolektor','kolektor','id_kolektor="'.$id_kolektor.'"');
        if (!empty($cek)) {            
            $jumlah_nasabah = (float) $this->function_lib->get_one('count(id_nasabah)','nasabah','id_kolektor="'.$id_kolektor.'" AND status="aktif"');
            $jumlah_pinjaman = (float) $this->function_lib->get_one('count(id_pinjaman)','pinjaman','id_kolektor="'.$id_kolektor.'" AND status_pinjaman="aktif"');
            $jumlah_simpanan = (float) $this->function_lib->get_one('count(id_simpanan)', 'simpanan', 'id_kolektor = "'.$id_kolektor.'"');
            $data = array("nasabah"=>$jumlah_nasabah, "pinjaman"=>$jumlah_pinjaman,"simpanan"=>$jumlah_simpanan);
        }else{
            $data = array("nasabah"=> 0, "pinjaman"=> 0, "simpanan"=> 0);
        }
        $this->response($data); 
    }
    public function edit_profil_post(){
        $id_kolektor = AUTHORIZATION::get_id_kolektor();
        $cek = $this->function_lib->get_one('id_kolektor','kolektor','id_kolektor="'.$id_kolektor.'"');
        $status = 500;
        $msg = "";
        if (!empty($cek)) {
            $edit = $this->Mkolektor->edit_profil($id_kolektor);
            $status = isset($edit['status']) ? $edit['status'] : 500;
            $msg = isset($edit['msg']) ? $edit['msg'] : "";
        }else{
            $status = 500;
            $msg = "Data kolektor tidak ditemukan";
        }
        $this->response(array("status"=>$status,"msg"=>$msg)); 
    }
    public function ganti_password_post(){
        $id_kolektor = AUTHORIZATION::get_id_kolektor();
        $cek = $this->function_lib->get_one('id_kolektor','kolektor','id_kolektor="'.$id_kolektor.'"');
        $status = 500;
        $msg = "";
        if (!empty($cek)) {
            $edit = $this->Mkolektor->ganti_password($id_kolektor);
            $status = isset($edit['status']) ? $edit['status'] : 500;
            $msg = isset($edit['msg']) ? $edit['msg'] : "";
        }else{
            $status = 500;
            $msg = "Data kolektor tidak ditemukan";
        }
        $this->response(array("status"=>$status,"msg"=>$msg)); 
    }
    public function profil_koperasi_get()
    {        
        $id_kolektor = AUTHORIZATION::get_id_kolektor();
        $id_owner = $this->function_lib->get_id_owner($id_kolektor);
        $dataProfilKoperasi = $this->function_lib->get_row('profil_koperasi','id_owner="'.$id_owner.'"');
        $status = 500;
        $msg = "";
        if (!empty($dataProfilKoperasi)) {            
            $status = 200;
            $msg = "";
            $dataProfilKoperasi['foto'] = (isset($dataProfilKoperasi['foto']) AND !empty($dataProfilKoperasi['foto'])!="") ? base_url('assets/foto_profil_koperasi/').$dataProfilKoperasi['foto'] : "";          
            $dataProfilKoperasi['nama_koperasi'] = $this->function_lib->get_one('nama_koperasi','owner','id_owner="'.$id_owner.'"');
        }
        $this->response(array("status"=>$status,"msg"=>$msg,"data"=>$dataProfilKoperasi));   
    }
}

/* End of file Kolektor.php */
/* Location: ./application/controllers/android/Kolektor.php */