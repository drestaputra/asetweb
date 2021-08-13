<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

// use namespace
use Restserver\Libraries\REST_Controller;
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

class Simpanan extends Rest_Controller {

	function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->database();
        $this->load->model('Msimpanan');
        AUTHORIZATION::check_token();
    }

    public function data_simpanan_post()
    {
        $id_kolektor = AUTHORIZATION::get_id_kolektor();
        $params = isset($_POST) ? $_POST : array();
        $start = (int)$this->input->post('page');
        
        $additional_where= ' AND id_kolektor="'.$id_kolektor.'" AND status_simpanan!="non_aktif"';
        
        $query_arr= $this->Msimpanan->data_simpanan($params,$custom_select='',$count=false,$additional_where);        
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
            $response[$key]['tgl_simpanan'] = date("d F Y H:i", strtotime($response[$key]['tgl_simpanan']));
            $response[$key]['last_update'] = (isset($value['last_update']) AND !empty($value['last_update'])) ? date("d F Y H:i", strtotime($response[$key]['last_update'])) : "-";
            if (!empty($value['id_nasabah'])) {
                $nasabah = $this->function_lib->get_row('nasabah','id_nasabah="'.$value['id_nasabah'].'"');
                $nasabah['foto_nasabah'] = isset($nasabah['foto_nasabah']) ? $nasabah['foto_nasabah'] : "";
                $nasabah['foto_nasabah'] = base_url('assets/foto_nasabah/').$nasabah['foto_nasabah'];
                $response[$key]['nasabah'] = $nasabah;
            }
        }
        
        $json_data = array('status'=>$status,'msg'=>$msg,'page' => $start,'totalPage'=>$totalPage, 'recordsFiltered' => ((int)$this->input->post('perPage')>0)?$this->input->post('perPage'):$total, 'totalRecords' => $total, 'data' => $response);
       

        $this->response($json_data);    
    }
    public function detail_simpanan_post()
    {
        $id_simpanan=$this->input->post('id_simpanan',TRUE);
        $id_kolektor = AUTHORIZATION::get_id_kolektor();
        $dataSimpanan=$this->function_lib->get_row('simpanan','id_simpanan='.$this->db->escape($id_simpanan).' AND id_kolektor="'.$id_kolektor.'"');
        if (!empty($dataSimpanan)) {            
                $dataSimpanan['last_update'] = (isset($dataSimpanan['last_update']) AND !empty($dataSimpanan['last_update'])) ? date("d F Y H:i", strtotime($dataSimpanan['last_update'])) : "-";
                $nasabah = $this->function_lib->get_row('nasabah','id_nasabah="'.$dataSimpanan['id_nasabah'].'"');
                $nasabah['foto_nasabah'] = isset($nasabah['foto_nasabah']) ? $nasabah['foto_nasabah'] : "";
                $nasabah['foto_nasabah'] = base_url('assets/foto_nasabah/').$nasabah['foto_nasabah'];
                $dataSimpanan['nasabah'] = $nasabah;
        }
        $this->response($dataSimpanan);    
    }   
    public function detail_simpanan_by_id_nasabah_post()
    {
        $id_nasabah=$this->input->post('id_nasabah',TRUE);
        $id_kolektor = AUTHORIZATION::get_id_kolektor();
        $dataSimpanan=$this->function_lib->get_row('simpanan','id_nasabah='.$this->db->escape($id_nasabah).' AND id_kolektor="'.$id_kolektor.'"');
        if (!empty($dataSimpanan)) {            
                $dataSimpanan['last_update'] = (isset($dataSimpanan['last_update']) AND !empty($dataSimpanan['last_update'])) ? date("d F Y H:i", strtotime($dataSimpanan['last_update'])) : "-";
                $nasabah = $this->function_lib->get_row('nasabah','id_nasabah="'.$dataSimpanan['id_nasabah'].'"');
                $nasabah['foto_nasabah'] = isset($nasabah['foto_nasabah']) ? $nasabah['foto_nasabah'] : "";
                $nasabah['foto_nasabah'] = base_url('assets/foto_nasabah/').$nasabah['foto_nasabah'];
                $dataSimpanan['nasabah'] = $nasabah;
        }else{
            $dataSimpanan['jumlah_simpanan'] = "0";
            $dataSimpanan['id_simpanan'] = "0";
            $dataSimpanan['tgl_simpanan'] = "";
            $dataSimpanan['last_update'] = "";
        }
        $this->response($dataSimpanan);    
    }   
     // function untuk menampilkan autocompletetext id_simpanan,id_nasabah, nama_nasabah, jumlah_simpanan
    public function get_all_id_simpanan_post(){
        $id_kolektor = AUTHORIZATION::get_id_kolektor();                
        $data = $this->Msimpanan->get_all_id_simpanan($id_kolektor);        
        $this->response(array("status"=>200,"msg"=>"","data"=>$data));    
    }
         // function untuk menampilkan autocompletetext id_simpanan,id_nasabah, nama_nasabah, jumlah_simpanan
    public function get_all_id_nasabah_for_simpanan_get(){
        $id_kolektor = AUTHORIZATION::get_id_kolektor();                
        $data = $this->Msimpanan->get_all_id_nasabah_for_simpanan($id_kolektor);        
        $this->response(array("status"=>200,"msg"=>"","data"=>$data));    
    }
    public function tambah_simpan_post(){
        $id_kolektor = AUTHORIZATION::get_id_kolektor();                
        $id_simpanan = $this->input->post('id_simpanan',TRUE);
        $status = 500;
        $msg = "";
        $cek = $this->function_lib->get_one('id_simpanan','simpanan','id_simpanan='.$this->db->escape($id_simpanan).' AND id_kolektor='.$this->db->escape($id_kolektor).'');
        if (!empty($cek)) {
            $response = $this->Msimpanan->tambah_simpan($id_kolektor);            
            $status = isset($response['status']) ? $response['status'] : 500;
            $msg = isset($response['msg']) ? $response['msg'] : "";            
        }else{
            $status = 500;
            $msg = "ID Simpanan tidak ditemukan";
        }
        $this->response(array("status"=>$status,"msg"=>$msg));
    }
    public function tambah_simpanan_post(){
        $id_kolektor = AUTHORIZATION::get_id_kolektor();                
        $id_nasabah = $this->input->post('id_nasabah',TRUE);
        $status = 500;
        $msg = "";
        $cek = $this->function_lib->get_one('id_simpanan','simpanan','id_nasabah='.$this->db->escape($id_nasabah).' AND id_kolektor='.$this->db->escape($id_kolektor).'');
        if (empty($cek)) {
            $response = $this->Msimpanan->tambah_simpanan($id_kolektor);            
            $status = isset($response['status']) ? $response['status'] : 500;
            $msg = isset($response['msg']) ? $response['msg'] : "";            
        }else{
            $status = 500;
            $msg = "Nasabah tersebut sudah mempunyai akun tabungan.";
        }
        $this->response(array("status"=>$status,"msg"=>$msg));
    }
    
}

/* End of file Simpanan.php */
/* Location: ./application/controllers/android/Simpanan.php */