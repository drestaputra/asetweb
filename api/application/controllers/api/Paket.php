<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

// use namespace
use Restserver\Libraries\REST_Controller;
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

class Paket extends Rest_Controller {

	function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->database();
        $this->load->model('Mpaket');
        // AUTHORIZATION::check_token();
    }
   
    public function data_paket_post(){
        $id_kolektor = AUTHORIZATION::get_id_kolektor();

        $params = isset($_POST) ? $_POST : array();
        $start = (int)$this->input->post('page');
        $id_owner = $this->function_lib->get_id_owner($id_kolektor);
        $additional_where= ' AND status_paket="aktif"';                
        
        $query_arr= $this->Mpaket->data_paket($params,$custom_select='',$count=false,$additional_where);        
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
        
        $json_data = array('status'=>$status,'msg'=>$msg,'page' => $start,'totalPage'=>$totalPage, 'recordsFiltered' => ((int)$this->input->post('perPage')>0)?$this->input->post('perPage'):$total, 'totalRecords' => $total, 'data' => $response);
       

        $this->response($json_data);    
    }  
    public function detail_paket_post()
    {    
        $status = 200;
        $msg = "OK";
        $id_paket = $this->input->post('id_paket');
        $data = $this->function_lib->get_row('paket','id_paket='.$this->db->escape($id_paket).'');
        $data['deskripsi_paket'] = isset($data['deskripsi_paket']) ?  html_entity_decode($data['deskripsi_paket']) : "";
        $this->response($data);    
    }  
    public function request_post(){    	        
               
        $response = $this->Mpaket->request();
        $status = isset($response['status']) ? $response['status'] : 500;
        $msg = isset($response['msg']) ? $response['msg'] : "";        
        $this->response(array("status"=>$status,"msg"=>$msg)); 
    }
       
}

/* End of file Paket.php */
/* Location: ./application/controllers/android/Paket.php */