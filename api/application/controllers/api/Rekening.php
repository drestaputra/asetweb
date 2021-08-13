<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

// use namespace
use Restserver\Libraries\REST_Controller;
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

class Rekening extends Rest_Controller {

	function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->database();
        $this->load->model('Mrekening');
        // AUTHORIZATION::check_token();
    }
   
     public function data_rekening_post(){
        $id_kolektor = AUTHORIZATION::get_id_kolektor();

        $params = isset($_POST) ? $_POST : array();
        $start = (int)$this->input->post('page');
        $id_owner = $this->function_lib->get_id_owner($id_kolektor);
        $additional_where= ' AND status="aktif"';                
        
        $query_arr= $this->Mrekening->data_rekening($params,$custom_select='',$count=false,$additional_where);        
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
            $response[$key]['gambar_bank'] = isset($response[$key]['gambar_bank']) ? $response[$key]['gambar_bank'] : "";
            $response[$key]['gambar_bank'] = base_url('assets/bank/').$response[$key]['gambar_bank'];       		
       	}     
        $json_data = array('status'=>$status,'msg'=>$msg,'page' => $start,'totalPage'=>$totalPage, 'recordsFiltered' => ((int)$this->input->post('perPage')>0)?$this->input->post('perPage'):$total, 'totalRecords' => $total, 'data' => $response);
       

        $this->response($json_data);    
    }  
    public function detail_rekening_post()
    {    
        $status = 200;
        $msg = "OK";
        $id_rekening = $this->input->post('id_rekening');
        $data = $this->function_lib->get_row('rekening','id_rekening='.$this->db->escape($id_rekening).'');
        $data = !empty($data) ? $data : array();
        if (!empty($data) AND isset($data['gambar_bank']) AND !empty($data['gambar_bank'])) {
            $data['gambar_bank'] = base_url('assets/bank/').$data['gambar_bank'];               
        }
        $this->response($data);    
    } 
}

/* End of file Rekening.php */
/* Location: ./application/controllers/android/Rekening.php */