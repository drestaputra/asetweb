<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

// use namespace
use Restserver\Libraries\REST_Controller;
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

class Riwayat_pinjaman extends Rest_Controller {

	function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->database();
        $this->load->model('Mriwayat_pinjaman');
        AUTHORIZATION::check_token();
    }

    public function data_riwayat_pinjaman_post()
    {
        $id_kolektor = AUTHORIZATION::get_id_kolektor();
        $params = isset($_POST) ? $_POST : array();
        $start = (int)$this->input->post('page');
        
        $additional_where= ' AND id_pinjaman IN (SELECT id_pinjaman FROM pinjaman WHERE id_kolektor="'.$id_kolektor.'")';
        
        $query_arr= $this->Mriwayat_pinjaman->data_riwayat_pinjaman($params,$custom_select='',$count=false,$additional_where);        
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
        	$response[$key]['tgl_riwayat_pinjaman'] = date("d F Y H:i", strtotime($response[$key]['tgl_riwayat_pinjaman']));
        	$response[$key]['jumlah_riwayat_pembayaran'] = "Rp. ".number_format($value['jumlah_riwayat_pembayaran'],0,'.','.');
        }                
        $json_data = array('status'=>$status,'msg'=>$msg,'page' => $start,'totalPage'=>$totalPage, 'recordsFiltered' => ((int)$this->input->post('perPage')>0)?$this->input->post('perPage'):$total, 'totalRecords' => $total, 'data' => $response);
       

        $this->response($json_data);    
    }
    public function pinjaman_hari_ini_post()
    {
        $id_kolektor = AUTHORIZATION::get_id_kolektor();
        $params = isset($_POST) ? $_POST : array();
        $start = (int)$this->input->post('page');
        
        $additional_where= ' AND id_pinjaman IN (SELECT id_pinjaman FROM pinjaman WHERE id_kolektor="'.$id_kolektor.'") AND date(tgl_riwayat_pinjaman)= '.$this->db->escape(date("Y-m-d")).'';
        
        $query_arr= $this->Mriwayat_pinjaman->data_riwayat_pinjaman($params,$custom_select='',$count=false,$additional_where);        
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
            $response[$key]['tgl_riwayat_pinjaman'] = date("d F Y H:i", strtotime($response[$key]['tgl_riwayat_pinjaman']));
            $response[$key]['jumlah_riwayat_pembayaran'] = "Rp. ".number_format($value['jumlah_riwayat_pembayaran'],0,'.','.');
        }                
        $json_data = array('status'=>$status,'msg'=>$msg,'page' => $start,'totalPage'=>$totalPage, 'recordsFiltered' => ((int)$this->input->post('perPage')>0)?$this->input->post('perPage'):$total, 'totalRecords' => $total, 'data' => $response);
       

        $this->response($json_data);    
    }
    public function summary_pinjaman_hari_ini_post(){
        $id_kolektor = AUTHORIZATION::get_id_kolektor();
        $data = $this->Mriwayat_pinjaman->summary_pinjaman_hari_ini($id_kolektor);
        $response = array(
            "status" => 200,
            "msg" => "",
            "data" => $data
        );
        $this->response($response);
    }

    public function detail_riwayat_pinjaman_post()
    {
        $id_riwayat_pinjaman=$this->input->post('id_riwayat_pinjaman');
        $id_kolektor = AUTHORIZATION::get_id_kolektor();
        $dataRiwayat_pinjaman=$this->function_lib->get_row('riwayat_pinjaman','id_riwayat_pinjaman="'.$id_riwayat_pinjaman.'" AND id_kolektor="'.$id_kolektor.'"');
        if (!empty($dataRiwayat_pinjaman)) {            
            $dataRiwayat_pinjaman['foto_riwayat_pinjaman'] = (isset($dataRiwayat_pinjaman['foto_riwayat_pinjaman']) AND trim($dataRiwayat_pinjaman['foto_riwayat_pinjaman'])!="") ? base_url('assets/foto_riwayat_pinjaman/').$dataRiwayat_pinjaman['foto_riwayat_pinjaman'] : "";
            $dataRiwayat_pinjaman['provinsi'] = $this->function_lib->get_one('nama','provinsi','id="'.$dataRiwayat_pinjaman['provinsi'].'"');
            $dataRiwayat_pinjaman['kabupaten'] = $this->function_lib->get_one('nama','kabupaten','id="'.$dataRiwayat_pinjaman['kabupaten'].'"');
            $dataRiwayat_pinjaman['kecamatan'] = $this->function_lib->get_one('nama','kecamatan','id="'.$dataRiwayat_pinjaman['kecamatan'].'"');
            $dataRiwayat_pinjaman['kelurahan'] = $this->function_lib->get_one('nama','desa','id="'.$dataRiwayat_pinjaman['kelurahan'].'"');
            $dataRiwayat_pinjaman['tgl_bergabung'] = (isset($dataRiwayat_pinjaman['tgl_bergabung']) AND trim($dataRiwayat_pinjaman['tgl_bergabung'])!="") ? date("d F Y H:i:s",strtotime($dataRiwayat_pinjaman['tgl_bergabung'])) : "";
        }
        $this->response($dataRiwayat_pinjaman);    
    }
    public function balance_riwayat_pinjaman_post()
    {
        $id_riwayat_pinjaman=$this->input->post('id_riwayat_pinjaman');
        $id_kolektor = AUTHORIZATION::get_id_kolektor();
        $data = array();
        $cek = $this->function_lib->get_one('id_riwayat_pinjaman','riwayat_pinjaman','id_riwayat_pinjaman="'.$id_riwayat_pinjaman.'" AND id_kolektor="'.$id_kolektor.'"');
        if (!empty($cek)) {            
            $jumlah_angsuran = (float) $this->function_lib->get_one('sum(jumlah_perangsuran)','pinjaman','id_riwayat_pinjaman="'.$id_riwayat_pinjaman.'" AND status_pinjaman="aktif"');
            $jumlah_pinjaman = (float) $this->function_lib->get_one('sum(jumlah_pinjaman_setelah_bunga)','pinjaman','id_riwayat_pinjaman="'.$id_riwayat_pinjaman.'" AND status_pinjaman="aktif"');
            $jumlah_simpanan = (float) $this->function_lib->get_one('sum(jumlah_simpanan)', 'simpanan', 'id_riwayat_pinjaman = "'.$id_riwayat_pinjaman.'"');
            $data = array("angsuran"=>$jumlah_angsuran, "pinjaman"=>$jumlah_pinjaman,"simpanan"=>$jumlah_simpanan);
        }else{
            $data = array("angsuran"=> 0, "pinjaman"=> 0, "simpanan"=> 0);
        }
        $this->response($data); 
    }
    public function data_riwayat_pinjaman_favorit_post()
    {
        $params = isset($_POST) ? $_POST : array();
        $start = (int)$this->input->post('page');
        $device_id=$this->input->post('device_id');
        $additional_where= 'AND id_riwayat_pinjaman in (SELECT id_riwayat_pinjaman from riwayat_pinjaman_favorit where device_id="'.$device_id.'")';
        
        $query_arr= $this->Mriwayat_pinjaman->data_riwayat_pinjaman($params,$custom_select='',$count=false,$additional_where);        
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
            $response[$key]['kategori']=$this->function_lib->get_row('kategori_riwayat_pinjaman','id_kategori_riwayat_pinjaman="'.$value['id_kategori_riwayat_pinjaman'].'"');
            $response[$key]['gambar_riwayat_pinjaman'] = isset($response[$key]['gambar_riwayat_pinjaman']) ? $response[$key]['gambar_riwayat_pinjaman'] : "";
            $response[$key]['gambar_riwayat_pinjaman'] = base_url('assets/image_riwayat_pinjaman/').$response[$key]['gambar_riwayat_pinjaman'];
        }
        $json_data = array('status'=>$status,'msg'=>$msg,'page' => $start,'totalPage'=>$totalPage, 'recordsFiltered' => ((int)$this->input->post('perPage')>0)?$this->input->post('perPage'):$total, 'totalRecords' => $total, 'data' => $response);
       

        $this->response($json_data);    
    }
    public function favorit_post(){
        $device_id=$this->input->post('device_id');
        if (trim($device_id)!="") {
            $response=$this->Mriwayat_pinjaman->setFavorit();
            $this->response($response);
        }
    }
    public function tambah_view_post(){
        $id_riwayat_pinjaman = $this->input->post('id_riwayat_pinjaman');
        if (trim($id_riwayat_pinjaman)!="") {
            $response = $this->Mriwayat_pinjaman->tambahView();
            $this->response($response);
        }
    }
}

/* End of file Riwayat_pinjaman.php */
/* Location: ./application/controllers/android/Riwayat_pinjaman.php */