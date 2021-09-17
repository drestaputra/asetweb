<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

// use namespace
use Restserver\Libraries\REST_Controller;
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

class Berita extends Rest_Controller {

	function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->database();
        $this->load->model('Mberita');
        AUTHORIZATION::check_token();
    }
   
    public function data_berita_post(){
        $id_kolektor = AUTHORIZATION::get_id_user();

        $params = isset($_POST) ? $_POST : array();
        $start = (int)$this->input->post('page');
        $additional_where= ' AND status_berita="aktif"';                
        
        $query_arr= $this->Mberita->data_berita($params,$custom_select='',$count=false,$additional_where);        
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
            $response[$key]['foto_berita'] = isset($response[$key]['foto_berita']) ? $response[$key]['foto_berita'] : "";
            $response[$key]['foto_berita'] = base_url('assets/foto_berita/').$response[$key]['foto_berita'];
       		$response[$key]['tgl_berita'] = date("d F Y, H:i:s",strtotime($value['tgl_berita']));
       	}       
        
        $json_data = array('status'=>$status,'msg'=>$msg,'page' => $start,'totalPage'=>$totalPage, 'recordsFiltered' => ((int)$this->input->post('perPage')>0)?$this->input->post('perPage'):$total, 'totalRecords' => $total, 'data' => $response);
       

        $this->response($json_data);    
    }  
    public function detail_berita_post(){
        $id_kolektor = AUTHORIZATION::get_id_user();
        $id_berita = $this->input->post('id_berita',TRUE);
        
        $cek = $this->function_lib->get_one('id_berita','berita','id_berita='.$this->db->escape($id_berita).'');
        $status = 500;
        $msg = "";
        $data = array();
        if (!empty($cek)) {
            $status = 200;
            $msg = "OK";
            $data = $this->function_lib->get_row('berita','id_berita='.$this->db->escape($cek).'');
            $data['deskripsi_berita'] = isset($data['deskripsi_berita']) ?  html_entity_decode($data['deskripsi_berita']) : "";
            $data['foto_berita'] = isset($data['foto_berita']) ? base_url('assets/foto_berita/').$data['foto_berita'] : "";
        }
        $this->response(array("status"=>$status,"msg"=>$msg,"data"=>$data));    
    }  
    // public function tes_notif_post()    {
    //     $primary_key = $this->input->post('id');
    //     $this->load->model('Mnotifikasi');
    //     $dataInformasi = $this->function_lib->get_row('berita','id_berita='.$this->db->escape($primary_key).'');
    //     if (!empty($dataInformasi)) {            
    //         if (isset($dataInformasi['is_notif']) AND $dataInformasi['is_notif']=="1") {
    //             // jika notif aktif jalankan function notifikasi
    //             $id_owner = isset($dataInformasi['id_owner']) ? $dataInformasi['id_owner'] : "";
    //             $content = array(
    //                 "title"=> "Artakita",
    //                 "message"=> isset($dataInformasi['judul_berita']) ? strip_tags($dataInformasi['judul_berita']) : "",
    //                 "tag" => $primary_key,
    //                 "news_permalink" => $primary_key
    //             );
    //             if (isset($dataInformasi['id_owner']) AND trim($dataInformasi['id_owner'])!="") {
    //                 // // $message = array("title"=>$title,"message"=>$messageNotif,"tag"=>$key,"news_permalink"=>$value['news_permalink']);
    //                 $this->Mnotifikasi->sendToTopic($id_owner,$content);                    
    //             }else{
    //                 $this->Mnotifikasi->sendToTopic("all",$content);
    //             }
    //         }
    //     }
        
    // }
}

/* End of file Berita.php */
/* Location: ./application/controllers/android/Berita.php */