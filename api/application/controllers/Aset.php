<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

// use namespace
use Restserver\Libraries\REST_Controller;
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
class Aset extends REST_Controller {
	
	function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->database();      
        $this->load->model('Maset'); 	
        $this->load->model('Mfoto_aset');    
    }

     public function data_aset_post()
    {
        $id_user = AUTHORIZATION::get_id_user();
        $params = isset($_POST) ? $_POST : array();
        $start = (int)$this->input->post('page');
        
        $additional_where= ' AND 1';
        
        $query_arr= $this->Maset->data($params,$custom_select='',$count=false,$additional_where, 'created_by ASC');        
        $query = $query_arr['query'];
        $total = $query_arr['total'];
        $status = $query_arr['status'];
        $msg = $query_arr['msg'];
        $response=$query->result_array();        
        $perPage=((int)$this->input->post('perPage')>0)?$this->input->post('perPage'):$total;
        if ($total!=0) {            
        $totalPage=ceil($total/$perPage)-1;
        }else{$totalPage=0;}                 
        foreach ($response as $key => $value) {
            $response[$key]['foto_aset'] = $this->Mfoto_aset->getFotoAset($value['id_aset']);
            $response[$key]['tanggal_sertifikat'] = date("d-m-Y", strtotime($response[$key]['tanggal_sertifikat']));
            $response[$key]['harga_perolehan'] = "Rp. ".number_format($response[$key]['harga_perolehan'],0,'.','.');
            $response[$key]['luas_tanah'] = number_format($response[$key]['luas_tanah'],0,'.','.');
            $response[$key]['latitude'] = empty($response[$key]['latitude']) ? "0" : $response[$key]['latitude'];
            $response[$key]['longitude'] = empty($response[$key]['longitude']) ? "0" : $response[$key]['longitude'];
        }      
        $json_data = array('status'=>$status,'msg'=>$msg,'page' => $start,'totalPage'=>$totalPage, 'recordsFiltered' => ((int)$this->input->post('perPage')>0)?$this->input->post('perPage'):$total, 'totalRecords' => $total, 'data' => $response);
       

        $this->response($json_data);    
    }
    public function detail_aset_post(){
        $id_user = AUTHORIZATION::get_id_user();
        $id_aset = $this->input->post('id_aset',TRUE);
        
        $cek = $this->function_lib->get_one('id_aset','aset','id_aset='.$this->db->escape($id_aset).'');
        $status = 500;
        $msg = "";
        $data = array();
        if (!empty($cek)) {
            $status = 200;
            $msg = "OK";
            $data = $this->function_lib->get_row('aset','id_aset='.$this->db->escape($cek).'');
            // $data['deskripsi_informasi_program'] = isset($data['deskripsi_informasi_program']) ?  html_entity_decode($data['deskripsi_informasi_program']) : "";
            // $data['foto_aset'] = isset($data['foto_informasi_program']) ? base_url('assets/foto_informasi_program/').$data['foto_informasi_program'] : "";
            $data['foto_aset'] = $this->Mfoto_aset->getFotoAset($data['id_aset']);
            $data['tanggal_sertifikat'] = date("d-m-Y", strtotime($data['tanggal_sertifikat']));
            $data['harga_perolehan'] = "Rp. ".number_format($data['harga_perolehan'],0,'.','.');
            $data['luas_tanah'] = number_format($data['luas_tanah'],0,'.','.');
        }
        $this->response(array("status"=>$status,"msg"=>$msg,"data"=>$data));    
    }  
}

/* End of file Aset.php */
/* Location: ./application/controllers/android/Aset.php */