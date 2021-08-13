<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

// use namespace
use Restserver\Libraries\REST_Controller;
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

class Nasabah extends Rest_Controller {

	function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->database();
        $this->load->model('Mnasabah');
        AUTHORIZATION::check_token();
    }

    public function data_nasabah_post()
    {
        $id_kolektor = AUTHORIZATION::get_id_kolektor();
        $params = isset($_POST) ? $_POST : array();
        $start = (int)$this->input->post('page');
        
        $additional_where= ' AND id_kolektor="'.$id_kolektor.'"';
        
        $query_arr= $this->Mnasabah->data_nasabah($params,$custom_select='',$count=false,$additional_where);        
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
            $response[$key]['foto_nasabah'] = isset($response[$key]['foto_nasabah']) ? $response[$key]['foto_nasabah'] : "";
            $response[$key]['foto_nasabah'] = base_url('assets/foto_nasabah/').$response[$key]['foto_nasabah'];
        }      
        $json_data = array('status'=>$status,'msg'=>$msg,'page' => $start,'totalPage'=>$totalPage, 'recordsFiltered' => ((int)$this->input->post('perPage')>0)?$this->input->post('perPage'):$total, 'totalRecords' => $total, 'data' => $response);
       

        $this->response($json_data);    
    }
    public function riwayat_nasabah_post()
    {
        $id_kolektor = AUTHORIZATION::get_id_kolektor();
        $params = isset($_POST) ? $_POST : array();
        $start = (int)$this->input->post('page');
        
        // $additional_where= ' AND id_kolektor="'.$id_kolektor.'"';
        $additional_where= ' AND 1';
        
        $query_arr= $this->Mnasabah->riwayat_nasabah($params,$custom_select='',$count=false,$additional_where, 'tgl_riwayat ASC', $id_kolektor);        
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
            $response[$key]['jumlah_riwayat'] = "Rp. ".number_format(floatval($response[$key]['jumlah_riwayat']),0,'.','.');
            $response[$key]['tgl_riwayat'] = date("d F Y H:i", strtotime($response[$key]['tgl_riwayat']));
        }      
        $json_data = array('status'=>$status,'msg'=>$msg,'page' => $start,'totalPage'=>$totalPage, 'recordsFiltered' => ((int)$this->input->post('perPage')>0)?$this->input->post('perPage'):$total, 'totalRecords' => $total, 'data' => $response);
       

        $this->response($json_data);    
    }
    public function detail_nasabah_post()
    {
        $id_nasabah=$this->input->post('id_nasabah');
        $id_kolektor = AUTHORIZATION::get_id_kolektor();
        $dataNasabah=$this->function_lib->get_row('nasabah','id_nasabah="'.$id_nasabah.'" AND id_kolektor="'.$id_kolektor.'"');
        if (!empty($dataNasabah)) {            
            $dataNasabah['foto_nasabah'] = (isset($dataNasabah['foto_nasabah']) AND trim($dataNasabah['foto_nasabah'])!="") ? base_url('assets/foto_nasabah/').$dataNasabah['foto_nasabah'] : "";
            $dataNasabah['id_provinsi'] = $dataNasabah['provinsi'];
            $dataNasabah['provinsi'] = $this->function_lib->get_one('nama','provinsi','id="'.$dataNasabah['provinsi'].'"');
            $dataNasabah['id_kabupaten'] = $dataNasabah['kabupaten'];
            $dataNasabah['kabupaten'] = $this->function_lib->get_one('nama','kabupaten','id="'.$dataNasabah['kabupaten'].'"');
            $dataNasabah['id_kecamatan'] = $dataNasabah['kecamatan'];
            $dataNasabah['kecamatan'] = $this->function_lib->get_one('nama','kecamatan','id="'.$dataNasabah['kecamatan'].'"');
            $dataNasabah['id_kelurahan'] = $dataNasabah['kelurahan'];
            $dataNasabah['kelurahan'] = $this->function_lib->get_one('nama','desa','id="'.$dataNasabah['kelurahan'].'"');
            $dataNasabah['tgl_bergabung'] = (isset($dataNasabah['tgl_bergabung']) AND trim($dataNasabah['tgl_bergabung'])!="") ? date("d F Y H:i:s",strtotime($dataNasabah['tgl_bergabung'])) : "";
        }
        $this->response($dataNasabah);    
    }
    public function balance_nasabah_post()
    {
        $id_nasabah=$this->input->post('id_nasabah');
        $id_kolektor = AUTHORIZATION::get_id_kolektor();
        $data = array();
        $cek = $this->function_lib->get_one('id_nasabah','nasabah','id_nasabah='.$this->db->escape($id_nasabah).' AND id_kolektor="'.$id_kolektor.'"');
        if (!empty($cek)) {            
            $jumlah_angsuran = (float) $this->function_lib->get_one('sum(jumlah_perangsuran)','pinjaman','id_nasabah="'.$id_nasabah.'" AND status_pinjaman="aktif"');
            $jumlah_pinjaman = (float) $this->function_lib->get_one('sum(jumlah_pinjaman_setelah_bunga)','pinjaman','id_nasabah="'.$id_nasabah.'" AND status_pinjaman="aktif"');
            $jumlah_simpanan = (float) $this->function_lib->get_one('sum(jumlah_simpanan)', 'simpanan', 'id_nasabah = "'.$id_nasabah.'"');
            $data = array("angsuran"=>$jumlah_angsuran, "pinjaman"=>$jumlah_pinjaman,"simpanan"=>$jumlah_simpanan);
        }else{
            $data = array("angsuran"=> 0, "pinjaman"=> 0, "simpanan"=> 0);
        }
        $this->response($data); 
    }
    public function data_nasabah_favorit_post()
    {
        $params = isset($_POST) ? $_POST : array();
        $start = (int)$this->input->post('page');
        $device_id=$this->input->post('device_id');
        $additional_where= 'AND id_nasabah in (SELECT id_nasabah from nasabah_favorit where device_id="'.$device_id.'")';
        
        $query_arr= $this->Mnasabah->data_nasabah($params,$custom_select='',$count=false,$additional_where);        
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
            $response[$key]['kategori']=$this->function_lib->get_row('kategori_nasabah','id_kategori_nasabah="'.$value['id_kategori_nasabah'].'"');
            $response[$key]['gambar_nasabah'] = isset($response[$key]['gambar_nasabah']) ? $response[$key]['gambar_nasabah'] : "";
            $response[$key]['gambar_nasabah'] = base_url('assets/image_nasabah/').$response[$key]['gambar_nasabah'];
        }
        $json_data = array('status'=>$status,'msg'=>$msg,'page' => $start,'totalPage'=>$totalPage, 'recordsFiltered' => ((int)$this->input->post('perPage')>0)?$this->input->post('perPage'):$total, 'totalRecords' => $total, 'data' => $response);
       

        $this->response($json_data);    
    }
    public function favorit_post(){
        $device_id=$this->input->post('device_id');
        if (trim($device_id)!="") {
            $response=$this->Mnasabah->setFavorit();
            $this->response($response);
        }
    }
    public function tambah_view_post(){
        $id_nasabah = $this->input->post('id_nasabah');
        if (trim($id_nasabah)!="") {
            $response = $this->Mnasabah->tambahView();
            $this->response($response);
        }
    }
    public function daftar_nasabah_post(){
        $id_kolektor = AUTHORIZATION::get_id_kolektor();
        $daftar = $this->Mnasabah->daftar_nasabah($id_kolektor);
        $status = isset($daftar['status']) ? $daftar['status'] : 500;
        $msg = isset($daftar['msg']) ? $daftar['msg'] : "";
        $this->response(array("status"=>$status,"msg"=>$msg));
    }
    public function edit_nasabah_post(){
        $id_kolektor = AUTHORIZATION::get_id_kolektor();
        $edit = $this->Mnasabah->edit_nasabah($id_kolektor);
        $status = isset($edit['status']) ? $edit['status'] : 500;
        $msg = isset($edit['msg']) ? $edit['msg'] : "";
        $this->response(array("status"=>$status,"msg"=>$msg));
    }
    public function upload_foto_post(){
        $data = $this->Mnasabah->upload_foto();
        $this->response($data);
    }
}

/* End of file Nasabah.php */
/* Location: ./application/controllers/android/Nasabah.php */