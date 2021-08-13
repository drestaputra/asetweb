<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

// use namespace
use Restserver\Libraries\REST_Controller;
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

class Pinjaman extends Rest_Controller {

	function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->database();
        $this->load->model('Mpinjaman');
        AUTHORIZATION::check_token();
    }

    public function data_pinjaman_post()
    {
        $id_kolektor = AUTHORIZATION::get_id_kolektor();
        $params = isset($_POST) ? $_POST : array();
        $start = (int)$this->input->post('page');
        
        $additional_where= ' AND id_kolektor="'.$id_kolektor.'" AND status_pinjaman!="non_aktif"';
        
        $query_arr= $this->Mpinjaman->data_pinjaman($params,$custom_select='',$count=false,$additional_where);        
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
            $response[$key]['tgl_pinjaman'] = date("d F Y", strtotime($response[$key]['tgl_pinjaman']));
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
    public function detail_pinjaman_post()
    {
        $id_pinjaman=$this->input->post('id_pinjaman',TRUE);
        $id_kolektor = AUTHORIZATION::get_id_kolektor();
        $dataPinjaman=$this->function_lib->get_row('pinjaman','id_pinjaman='.$this->db->escape($id_pinjaman).' AND id_kolektor="'.$id_kolektor.'"');
        if (!empty($dataPinjaman)) {            
                $nasabah = $this->function_lib->get_row('nasabah','id_nasabah="'.$dataPinjaman['id_nasabah'].'"');
                $nasabah['foto_nasabah'] = isset($nasabah['foto_nasabah']) ? $nasabah['foto_nasabah'] : "";
                $nasabah['foto_nasabah'] = base_url('assets/foto_nasabah/').$nasabah['foto_nasabah'];
                $dataPinjaman['nasabah'] = $nasabah;
        }
        $this->response($dataPinjaman);    
    }
    public function balance_pinjaman_post()
    {
        $id_pinjaman=$this->input->post('id_pinjaman');
        $id_kolektor = AUTHORIZATION::get_id_kolektor();
        $data = array();
        $cek = $this->function_lib->get_one('id_pinjaman','pinjaman','id_pinjaman="'.$id_pinjaman.'" AND id_kolektor="'.$id_kolektor.'"');
        if (!empty($cek)) {            
            $jumlah_angsuran = (float) $this->function_lib->get_one('sum(jumlah_perangsuran)','pinjaman','id_pinjaman="'.$id_pinjaman.'" AND status_pinjaman="aktif"');
            $jumlah_pinjaman = (float) $this->function_lib->get_one('sum(jumlah_pinjaman_setelah_bunga)','pinjaman','id_pinjaman="'.$id_pinjaman.'" AND status_pinjaman="aktif"');
            $jumlah_simpanan = (float) $this->function_lib->get_one('sum(jumlah_simpanan)', 'simpanan', 'id_pinjaman = "'.$id_pinjaman.'"');
            $data = array("angsuran"=>$jumlah_angsuran, "pinjaman"=>$jumlah_pinjaman,"simpanan"=>$jumlah_simpanan);
        }else{
            $data = array("angsuran"=> 0, "pinjaman"=> 0, "simpanan"=> 0);
        }
        $this->response($data); 
    }   
     // function untuk menampilkan autocompletetext id_pinjaman,id_nasabah, nama_nasabah, jumlah_pinjaman
    public function get_all_id_pinjaman_get(){
        $id_kolektor = AUTHORIZATION::get_id_kolektor();                
        $data = $this->Mpinjaman->get_all_id_pinjaman($id_kolektor);        
        $this->response(array("status"=>200,"msg"=>"","data"=>$data));    
    }
    public function get_all_id_nasabah_for_pinjaman_get(){
        $id_kolektor = AUTHORIZATION::get_id_kolektor();                
        $data = $this->Mpinjaman->get_all_id_nasabah_for_pinjaman($id_kolektor);        
        $this->response(array("status"=>200,"msg"=>"","data"=>$data));    
    }
    public function bayar_angsuran_post(){
        $id_kolektor = AUTHORIZATION::get_id_kolektor();                
        $id_pinjaman = $this->input->post('id_pinjaman',TRUE);
        $status = 500;
        $msg = "";
        $cek = $this->function_lib->get_one('id_pinjaman','pinjaman','id_pinjaman='.$this->db->escape($id_pinjaman).' AND id_kolektor='.$this->db->escape($id_kolektor).'');
        if (!empty($cek)) {
            $response = $this->Mpinjaman->bayar_angsuran($id_kolektor);            
            $status = isset($response['status']) ? $response['status'] : 500;
            $msg = isset($response['msg']) ? $response['msg'] : "";            
        }else{
            $status = 500;
            $msg = "ID Pinjaman tidak ditemukan";
        }
        $this->response(array("status"=>$status,"msg"=>$msg));
    }
    public function validasi_tambah_pinjaman_post(){
        $id_kolektor = AUTHORIZATION::get_id_kolektor();       
        $response = $this->Mpinjaman->validasi_tambah_pinjaman($id_kolektor);
        $status = isset($response['status']) ? $response['status'] : 500;
        $msg = isset($response['msg']) ? $response['msg'] : "";            
        $data = isset($response['data']) ? $response['data'] : array();
        $this->response(array("status"=>$status,"msg"=>$msg,"data"=>$data));
    }
    public function tambah_pinjaman_post(){
        $id_kolektor = AUTHORIZATION::get_id_kolektor();       
        $response = $this->Mpinjaman->tambah_pinjaman($id_kolektor);
        $status = isset($response['status']) ? $response['status'] : 500;
        $msg = isset($response['msg']) ? $response['msg'] : "";                    
        $this->response(array("status"=>$status,"msg"=>$msg));
    }
    
}

/* End of file Pinjaman.php */
/* Location: ./application/controllers/android/Pinjaman.php */