<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

// use namespace
use Restserver\Libraries\REST_Controller;
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

class Oper_berkas extends Rest_Controller {

	function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->database();
        $this->load->model('Moper_berkas');
        AUTHORIZATION::check_token();
    }
   
    public function riwayat_oper_berkas_post(){
        $id_kolektor = AUTHORIZATION::get_id_kolektor();
        $params = isset($_POST) ? $_POST : array();
        $start = (int)$this->input->post('page');
        
        $additional_where= ' AND (id_kolektor_dari="'.$id_kolektor.'" OR id_kolektor_ke="'.$id_kolektor.'")';
        
        $query_arr= $this->Moper_berkas->data_oper_berkas($params,$custom_select='',$count=false,$additional_where);        
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
        foreach ($response as $key => $value) {
       		$response[$key]['username_kolektor_dari'] = $this->function_lib->get_one('username','kolektor','id_kolektor="'.$value['id_kolektor_dari'].'"');
       		$response[$key]['username_kolektor_ke'] = $this->function_lib->get_one('username','kolektor','id_kolektor="'.$value['id_kolektor_ke'].'"');
       		$response[$key]['tgl_oper_berkas'] = date("d F Y, H:i:s",strtotime($value['tgl_oper_berkas']));
            $response[$key]['nama_nasabah'] = $this->function_lib->get_one('nama_nasabah','nasabah','id_nasabah='.$this->db->escape($value['id_nasabah']).'');
       	}       
        
        $json_data = array('status'=>$status,'msg'=>$msg,'page' => $start,'totalPage'=>$totalPage, 'recordsFiltered' => ((int)$this->input->post('perPage')>0)?$this->input->post('perPage'):$total, 'totalRecords' => $total, 'data' => $response);
       

        $this->response($json_data);    
    }    
    public function proses_oper_berkas_post(){
    	$id_kolektor = AUTHORIZATION::get_id_kolektor();
    	$status = 500;
    	$msg = "Data oper berkas tidak ditemukan";
    	$id_oper_berkas = $this->input->post('id_oper_berkas',true);
    	$status_post = $this->input->post('status',true);
    	$cek_id = $this->function_lib->get_one('id_oper_berkas','oper_berkas','id_oper_berkas="'.$id_oper_berkas.'" AND (id_kolektor_dari="'.$id_kolektor.'" OR id_kolektor_ke="'.$id_kolektor.'")');
    	if (!empty($cek_id) AND !empty($status_post) AND($status_post=="proses" OR $status_post=="done" OR $status_post=="tolak")) {
    		$terima = $this->Moper_berkas->proses_oper_berkas($id_kolektor);
    		$status = isset($terima['status']) ? $terima['status'] : 500;
    		$msg = isset($terima['msg']) ? $terima['msg'] : "";
    	}
    	$response = array(
    		"status" => $status,
    		"msg" => $msg
    	);
    	$this->response($response);    
    }
}

/* End of file Oper_berkas.php */
/* Location: ./application/controllers/android/Oper_berkas.php */