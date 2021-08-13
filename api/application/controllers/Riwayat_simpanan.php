<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

// use namespace
use Restserver\Libraries\REST_Controller;
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

class Riwayat_simpanan extends Rest_Controller {

	function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->database();
        $this->load->model('Mriwayat_simpanan');
        AUTHORIZATION::check_token();
    }

    public function data_riwayat_simpanan_post()
    {
        $id_kolektor = AUTHORIZATION::get_id_kolektor();
        $params = isset($_POST) ? $_POST : array();
        $start = (int)$this->input->post('page');
        
        $additional_where= ' AND id_simpanan IN (SELECT id_simpanan FROM pinjaman WHERE id_kolektor="'.$id_kolektor.'")';
        
        $query_arr= $this->Mriwayat_simpanan->data_riwayat_simpanan($params,$custom_select='',$count=false,$additional_where);        
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
        	$jumlah_riwayat_simpanan = "";
        	$response[$key]['tgl_riwayat_simpanan'] = date("d F Y H:i", strtotime($response[$key]['tgl_riwayat_simpanan']));
        	if (floatval($value['jumlah_riwayat_simpanan'])>0) {
        		$jumlah_riwayat_simpanan = "+";
        	}else{
        		$jumlah_riwayat_simpanan = "-";
        	}
        	$response[$key]['jumlah_riwayat_simpanan'] = $jumlah_riwayat_simpanan." Rp. ".number_format($value['jumlah_riwayat_simpanan'],0,'.','.');
        	$jumlah_simpanan_sebelumnya = $this->get_simpanan_sebelumnya($value['id_riwayat_simpanan'],$value['id_simpanan']);
        	$response[$key]['jumlah_simpanan_sebelumnya'] = "Rp. ".number_format($jumlah_simpanan_sebelumnya,0,'.','.');
        }                
        $json_data = array('status'=>$status,'msg'=>$msg,'page' => $start,'totalPage'=>$totalPage, 'recordsFiltered' => ((int)$this->input->post('perPage')>0)?$this->input->post('perPage'):$total, 'totalRecords' => $total, 'data' => $response);
       

        $this->response($json_data);    
    }
    public function get_simpanan_sebelumnya($id_riwayat_simpanan,$id_simpanan){
    	$jumlah_simpanan_sebelumnya = $this->function_lib->get_one('sum(jumlah_riwayat_simpanan)','riwayat_simpanan','id_riwayat_simpanan<='.$this->db->escape($id_riwayat_simpanan).' AND id_simpanan='.$id_simpanan.'');
    	return (float) $jumlah_simpanan_sebelumnya;
    }
    public function detail_riwayat_simpanan_post()
    {
        $id_riwayat_simpanan=$this->input->post('id_riwayat_simpanan');
        $id_kolektor = AUTHORIZATION::get_id_kolektor();
        $dataRiwayat_simpanan=$this->function_lib->get_row('riwayat_simpanan','id_riwayat_simpanan="'.$id_riwayat_simpanan.'" AND id_kolektor="'.$id_kolektor.'"');
        if (!empty($dataRiwayat_simpanan)) {            
            $dataRiwayat_simpanan['foto_riwayat_simpanan'] = (isset($dataRiwayat_simpanan['foto_riwayat_simpanan']) AND trim($dataRiwayat_simpanan['foto_riwayat_simpanan'])!="") ? base_url('assets/foto_riwayat_simpanan/').$dataRiwayat_simpanan['foto_riwayat_simpanan'] : "";
            $dataRiwayat_simpanan['provinsi'] = $this->function_lib->get_one('nama','provinsi','id="'.$dataRiwayat_simpanan['provinsi'].'"');
            $dataRiwayat_simpanan['kabupaten'] = $this->function_lib->get_one('nama','kabupaten','id="'.$dataRiwayat_simpanan['kabupaten'].'"');
            $dataRiwayat_simpanan['kecamatan'] = $this->function_lib->get_one('nama','kecamatan','id="'.$dataRiwayat_simpanan['kecamatan'].'"');
            $dataRiwayat_simpanan['kelurahan'] = $this->function_lib->get_one('nama','desa','id="'.$dataRiwayat_simpanan['kelurahan'].'"');
            $dataRiwayat_simpanan['tgl_bergabung'] = (isset($dataRiwayat_simpanan['tgl_bergabung']) AND trim($dataRiwayat_simpanan['tgl_bergabung'])!="") ? date("d F Y H:i:s",strtotime($dataRiwayat_simpanan['tgl_bergabung'])) : "";
        }
        $this->response($dataRiwayat_simpanan);    
    }   
}

/* End of file Riwayat_simpanan.php */
/* Location: ./application/controllers/android/Riwayat_simpanan.php */