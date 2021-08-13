<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

// use namespace
use Restserver\Libraries\REST_Controller;
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

class Mahasiswa extends Rest_Controller {

	function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->database();
        $this->load->model('Mmahasiswa');
        if (!AUTHORIZATION::verify_request()) {         
            $response = ['status' => 401, 'msg' => 'Unauthorized Access! '];
            echo json_encode($response);
            exit();
        }
    }

    public function data_mahasiswa_post()
    {
        $params = isset($_POST) ? $_POST : array();
        $start = (int)$this->input->post('page');
        
        $additional_where= "";
        
        $query_arr= $this->Mmahasiswa->data_mahasiswa($params,$custom_select='',$count=false,$additional_where);        
        $query = $query_arr['query'];
        $total = $query_arr['total'];
        $status = $query_arr['status'];
        $msg = $query_arr['msg'];
        $response=$query->result_array();        
        $perPage=((int)$this->input->post('perPage')>0)?$this->input->post('perPage'):$total;
        if ($total!=0) {            
        $totalPage=ceil($total/$perPage)-1;
        }else{$totalPage=0;}
        $json_data = array('status'=>$status,'msg'=>$msg,'page' => $start,'totalPage'=>$totalPage, 'recordsFiltered' => ((int)$this->input->post('perPage')>0)?$this->input->post('perPage'):$total, 'totalRecords' => $total, 'data' => $response);
       

        $this->response($json_data);    
    }

    public function data_peringkat_mahasiswa_post()
    {
        $params = isset($_POST) ? $_POST : array();
        $start = (int)$this->input->post('page');
        
        $additional_where= "";
        
        $query_arr= $this->Mmahasiswa->data_peringkat_mahasiswa($params,$custom_select='',$count=false,$additional_where);        
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
            $data_mahasiswa=$this->function_lib->get_row('mahasiswa','id_mahasiswa="'.$value['id_mahasiswa'].'"');            
            $response[$key]=array_merge($response[$key],$data_mahasiswa);
        }
        $json_data = array('status'=>$status,'msg'=>$msg,'page' => $start,'totalPage'=>$totalPage, 'recordsFiltered' => ((int)$this->input->post('perPage')>0)?$this->input->post('perPage'):$total, 'totalRecords' => $total, 'data' => $response);
       

        $this->response($json_data);    
    }
	
	public function riwayat_tutorial_post()
	{
		$params = isset($_POST) ? $_POST : array();
        $start = (int)$this->input->post('page');
        
        $additional_where= "";
        
        $query_arr= $this->Mmahasiswa->riwayat_tutorial($params,$custom_select='',$count=false,$additional_where);        
        $query = $query_arr['query'];
        $total = $query_arr['total'];
        $status = $query_arr['status'];
        $msg = $query_arr['msg'];
        $response=$query->result_array();
        foreach ($response as $key => $value) {
            $response[$key]['waktu_mulai']=$this->function_lib->convert_date($value['waktu_mulai']);
            $response[$key]['waktu_selesai']=$this->function_lib->convert_date($value['waktu_selesai']);
        }
        $perPage=((int)$this->input->post('perPage')>0)?$this->input->post('perPage'):$total;
        if ($total!=0) {            
        $totalPage=ceil($total/$perPage)-1;
        }else{$totalPage=0;}
        $json_data = array('status'=>$status,'msg'=>$msg,'page' => $start,'totalPage'=>$totalPage, 'recordsFiltered' => ((int)$this->input->post('perPage')>0)?$this->input->post('perPage'):$total, 'totalRecords' => $total, 'data' => $response);
       

        $this->response($json_data);    
	}
// tutorial active di mahasiswa
    public function tutorial_active_post()
    {
        $params = isset($_POST) ? $_POST : array();
        $start = (int)$this->input->post('page');
        
        $additional_where= "";
        
        $query_arr= $this->Mmahasiswa->tutorial_active($params,$custom_select='',$count=false,$additional_where);        
        $query = $query_arr['query'];
        $total = $query_arr['total'];
        $status = $query_arr['status'];
        $msg = $query_arr['msg'];
        $response=$query->result_array();
        foreach ($response as $key => $value) {
            $response[$key]['waktu_mulai']=$this->function_lib->convert_date($value['waktu_mulai']);
            $response[$key]['waktu_selesai']=$this->function_lib->convert_date($value['waktu_selesai']);
        }
        $perPage=((int)$this->input->post('perPage')>0)?$this->input->post('perPage'):$total;
        if ($total!=0) {            
        $totalPage=ceil($total/$perPage)-1;
        }else{$totalPage=0;}
        $json_data = array('status'=>$status,'msg'=>$msg,'page' => $start,'totalPage'=>$totalPage, 'recordsFiltered' => ((int)$this->input->post('perPage')>0)?$this->input->post('perPage'):$total, 'totalRecords' => $total, 'data' => $response);
       

        $this->response($json_data);    
    }

    public function join_tutorial_post(){
        if ($this->input->post()) {
            $response=$this->Mmahasiswa->join_tutorial();
            $this->response($response);
        }
    }
    public function pilih_tempat_duduk_post(){
        if ($this->input->post()) {
            $response=$this->Mmahasiswa->pilih_tempat_duduk();
            $this->response($response);
        }   
    }
    public function profil_post(){
        if ($this->input->post()) {
            $response=$this->Mmahasiswa->profil();
            $this->response($response);
        }
    }
    public function statistik_post(){
        if ($this->input->post()) {
            $response=$this->Mmahasiswa->statistik();
            $this->response($response);
        }
    }
    public function update_profil_post(){
        if ($this->input->post()) {
            $response=$this->Mmahasiswa->update_profil();
            $this->response($response);
        }
    }
    public function riwayat_mahasiswa_post(){
        $this->load->model('Mtutorial');
        $params = isset($_POST) ? $_POST : array();
        $start = (int)$this->input->post('page');        
        $additional_where= "";
        
        $query_arr= $this->Mtutorial->data_tutorial_mahasiswa($params,$custom_select='',$count=false,$additional_where);        
        $query = $query_arr['query'];
        $total = $query_arr['total'];
        $status = $query_arr['status'];
        $msg = $query_arr['msg'];
        $response=$query->result_array();
        foreach ($response as $key => $value) {
            $response[$key]['waktu_mulai']=$this->function_lib->convert_date($value['waktu_mulai']);
            $response[$key]['waktu_selesai']=$this->function_lib->convert_date($value['waktu_selesai']);
        }
        $perPage=((int)$this->input->post('perPage')>0)?$this->input->post('perPage'):$total;
        if ($total!=0) {            
        $totalPage=ceil($total/$perPage)-1;
        }else{$totalPage=0;}
        $json_data = array('status'=>$status,'msg'=>$msg,'page' => $start,'totalPage'=>$totalPage, 'recordsFiltered' => ((int)$this->input->post('perPage')>0)?$this->input->post('perPage'):$total, 'totalRecords' => $total, 'data' => $response);
       

        $this->response($json_data);    
    }
}

/* End of file Mahasiswa.php */
/* Location: ./application/controllers/android/Mahasiswa.php */