<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

// use namespace
use Restserver\Libraries\REST_Controller;
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

class Request extends Rest_Controller {

	function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->database();
        $this->load->model('Mrequest');
        // AUTHORIZATION::check_token();
    }
   
    public function data_request_post(){
        $id_kolektor = AUTHORIZATION::get_id_kolektor();

        $params = isset($_POST) ? $_POST : array();
        $start = (int)$this->input->post('page');
        $device_id = $this->input->post('device_id');
        $additional_where= ' AND device_id='.$this->db->escape($device_id).'';                
        
        $query_arr= $this->Mrequest->data_request($params,$custom_select='',$count=false,$additional_where);        
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
       		$response[$key]['tgl_request'] = date("d F Y, H:i:s",strtotime($value['tgl_request']));
       		$id_paket = isset($value['id_paket']) ? $value['id_paket'] : 0;
       		$response[$key]['nama_paket'] = $this->function_lib->get_one('nama_paket','paket','id_paket='.$this->db->escape($id_paket).'');
          if ($value['status'] == "proses") {
            $response[$key]['status'] = "Menunggu Bukti Pembayaran";
          }else if($value['status'] == "selesai"){
            $response[$key]['status'] = "Selesai";
          }else if($value['status'] == "tolak"){
            $response[$key]['status'] = "Bukti pembayaran ditolak";
          }else{
            $response[$key]['status'] = "";
          }
       		// unset($response[$key]['password']);
       	}       
        
        $json_data = array('status'=>$status,'msg'=>$msg,'page' => $start,'totalPage'=>$totalPage, 'recordsFiltered' => ((int)$this->input->post('perPage')>0)?$this->input->post('perPage'):$total, 'totalRecords' => $total, 'data' => $response);
       

        $this->response($json_data);    
    }  
    public function detail_request_post()
    {    
        $status = 200;
        $msg = "OK";
        $id_request = $this->input->post('id_request');
        $device_id = $this->input->post('device_id');
        $data = $this->function_lib->get_row('request_owner','id_request='.$this->db->escape($id_request).' AND device_id='.$this->db->escape($device_id).'');
       	$data = !empty($data) ? $data : array();
       	if (!empty($data)) {
       		$data['tgl_request'] = date("D, d-m-y H:i:s", strtotime($data['tgl_request']));
       	}
        $this->response($data);    
    }  
    public function upload_bukti_pembayaran_post(){
        $device_id = $this->input->post('device_id');
        $id_request = $this->input->post('id_request');
        $cek = $this->function_lib->get_one('id_request','request_owner','id_request='.$this->db->escape($id_request).' AND device_id='.$this->db->escape($device_id).'');
        $data = array(
          "status"=>500,
          "msg"=>"Data permintaan request tidak ditemukan, silahkan hubungi admin di halaman contact",
          "data"=> "0"
        );
        if (!empty($cek)) {
          $data = $this->Mrequest->upload_bukti_pembayaran();
        }
        $this->response($data);
    }
    public function get_image_bukti_pembayaran_post(){
      $status = 500;
      $msg = "";
      $id_request = $this->input->post('id_request');
      $device_id = $this->input->post('device_id');
      $id_pembayaran = $this->input->post('id_pembayaran');
      $cek = $this->function_lib->get_one('bukti_pembayaran','pembayaran','

        id_request IN (SELECT id_request FROM request_owner WHERE id_request='.$this->db->escape($id_request).' AND device_id='.$this->db->escape($device_id).') AND
        id_pembayaran='.$this->db->escape($id_pembayaran).'');
      if (!empty($cek)) {
          $status =200;
          $cek = base_url('assets/bukti_pembayaran/').$cek;
          $msg = $cek;
      }
      $data = array(
        "status"=> $status,
        "msg"=> $msg,
      ); 
      $this->response($data);

    }
     /*@Field("id_pembayaran") String id_pembayaran,
                @Field("nama_pembayar") String nama_pembayar,
                @Field("no_hp_pembayar") String no_hp_pembayar,
                @Field("email_pembayar") String email_pembayar,
                @Field("jumlah_pembayaran") String jumlah_pembayaran,
                @Field("id_request") String id_request,
                @Field("device_id") String device_id*/
    public function konfirmasi_pembayaran_post(){
      $status = 500;
      $msg = "";
      $id_request = $this->input->post('id_request');
      $device_id = $this->input->post('device_id');
      $id_pembayaran = $this->input->post('id_pembayaran');
      $cek = $this->function_lib->get_one('bukti_pembayaran','pembayaran','id_request IN (SELECT id_request FROM request_owner WHERE id_request='.$this->db->escape($id_request).' AND device_id='.$this->db->escape($device_id).') AND id_pembayaran='.$this->db->escape($id_pembayaran).'');

      if (!empty($cek)) {
          $konfirmasi = $this->Mrequest->konfirmasi_pembayaran();
          $status = isset($konfirmasi['status']) ? $konfirmasi['status'] : 500;
          $msg = isset($konfirmasi['msg']) ? $konfirmasi['msg'] : "";

      }
      $data = array(
        "status"=> $status,
        "msg"=> $msg,
      ); 
      $this->response($data);
    }
    public function ubah_rekening_post(){
        $id_request = $this->input->post('id_request');
        $device_id = $this->input->post('device_id');
        $cek = $this->function_lib->get_one('id_request','request_owner','id_request='.$this->db->escape($id_request).' AND device_id='.$this->db->escape($device_id).'');
        $data = array(
          "status"=>500,
          "msg"=>"Data permintaan request tidak ditemukan, silahkan hubungi admin di halaman contact",
          "data"=> "0"
        );
        if (!empty($cek)) {
          $data = $this->Mrequest->ubah_rekening();
        }
        $this->response($data);
    }
}

/* End of file Request.php */
/* Location: ./application/controllers/android/Request.php */