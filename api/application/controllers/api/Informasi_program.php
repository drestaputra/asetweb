<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

// use namespace
use Restserver\Libraries\REST_Controller;
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

class Informasi_program extends Rest_Controller {

	function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->database();
        $this->load->model('Minformasi_program');
        // AUTHORIZATION::check_token();
    }
   
    public function data_informasi_program_post(){
        $id_kolektor = AUTHORIZATION::get_id_kolektor();

        $params = isset($_POST) ? $_POST : array();
        $start = (int)$this->input->post('page');
        $id_owner = $this->function_lib->get_id_owner($id_kolektor);
        $additional_where= ' AND id_owner="'.$id_owner.'" OR ( id_owner is null OR id_owner="" OR id_owner="0") AND status="aktif"';                
        
        $query_arr= $this->Minformasi_program->data_informasi_program($params,$custom_select='',$count=false,$additional_where);        
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
            $response[$key]['foto_informasi_program'] = isset($response[$key]['foto_informasi_program']) ? $response[$key]['foto_informasi_program'] : "";
            $response[$key]['foto_informasi_program'] = base_url('assets/foto_informasi_program/').$response[$key]['foto_informasi_program'];
       		$response[$key]['tgl_informasi_program'] = date("d F Y, H:i:s",strtotime($value['tgl_informasi_program']));
       	}       
        
        $json_data = array('status'=>$status,'msg'=>$msg,'page' => $start,'totalPage'=>$totalPage, 'recordsFiltered' => ((int)$this->input->post('perPage')>0)?$this->input->post('perPage'):$total, 'totalRecords' => $total, 'data' => $response);
       

        $this->response($json_data);    
    }  
    public function detail_informasi_program_post(){
        $id_kolektor = AUTHORIZATION::get_id_kolektor();
        $id_informasi_program = $this->input->post('id_informasi_program',TRUE);
        // cek apakah informasi program tersebut milik owner dari kolektor yg request, atau yg id_owner nya = null atau 0
        $id_owner = $this->function_lib->get_id_owner($id_kolektor);
        $cek = $this->function_lib->get_one('id_informasi_program','informasi_program','(id_owner='.$this->db->escape($id_owner).' OR id_owner is null OR id_owner=0) AND id_informasi_program='.$this->db->escape($id_informasi_program).'');
        $status = 500;
        $msg = "";
        $data = array();
        if (!empty($cek)) {
            $status = 200;
            $msg = "OK";
            $data = $this->function_lib->get_row('informasi_program','id_informasi_program='.$this->db->escape($cek).'');
            $data['deskripsi_informasi_program'] = isset($data['deskripsi_informasi_program']) ?  html_entity_decode($data['deskripsi_informasi_program']) : "";
            $data['foto_informasi_program'] = isset($data['foto_informasi_program']) ? base_url('assets/foto_informasi_program/').$data['foto_informasi_program'] : "";
        }
        $this->response(array("status"=>$status,"msg"=>$msg,"data"=>$data));    
    }  
    // public function tes_notif_post()    {
    //     $primary_key = $this->input->post('id');
    //     $this->load->model('Mnotifikasi');
    //     $dataInformasi = $this->function_lib->get_row('informasi_program','id_informasi_program='.$this->db->escape($primary_key).'');
    //     if (!empty($dataInformasi)) {            
    //         if (isset($dataInformasi['is_notif']) AND $dataInformasi['is_notif']=="1") {
    //             // jika notif aktif jalankan function notifikasi
    //             $id_owner = isset($dataInformasi['id_owner']) ? $dataInformasi['id_owner'] : "";
    //             $content = array(
    //                 "title"=> "Artakita",
    //                 "message"=> isset($dataInformasi['judul_informasi_program']) ? strip_tags($dataInformasi['judul_informasi_program']) : "",
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

/* End of file Informasi_program.php */
/* Location: ./application/controllers/android/Informasi_program.php */