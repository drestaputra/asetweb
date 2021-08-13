<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

// use namespace
use Restserver\Libraries\REST_Controller;
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

class Jadwal extends Rest_Controller {

	function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->database();
        $this->load->model('Mjadwal');
        AUTHORIZATION::check_token();
    }

    public function data_jadwal_tagihan_post()
    {
        $id_kolektor = AUTHORIZATION::get_id_kolektor();
        $params = isset($_POST) ? $_POST : array();
        $start = (int)$this->input->post('page');
        
        $additional_where= ' AND id_kolektor="'.$id_kolektor.'" ';
        
        $query_arr= $this->Mjadwal->jadwal_tagihan($params,$custom_select='',$count=false,$additional_where);        
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
}

/* End of file Jadwal.php */
/* Location: ./application/controllers/android/Jadwal.php */