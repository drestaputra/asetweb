<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pembayaran extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		//Do your magic here
		$this->function_lib->cek_auth(array("super_admin","admin"));
		$this->load->library(array('grocery_CRUD','ajax_grocery_crud'));   
	}		
 
    public function index() {
        $this->load->config('grocery_crud');        
        $crud = new Ajax_grocery_CRUD();
        $crud->where('pembayaran.status_pembayaran!="hapus"');            
        $user_sess = $this->function_lib->get_user_level();
        $level = isset($user_sess['level']) ? $user_sess['level'] : "";
        $id_user = isset($user_sess['id_user']) ? $user_sess['id_user'] : "";

        $crud->set_theme('adminlte');
        $crud->set_table('pembayaran');        
        $crud->set_subject('Data Pembayaran Request Akun');
        $crud->set_language('indonesian');
        $crud->set_relation('id_request','request_owner','id_request');           
        
        $crud->columns('nama_pembayar','tgl_pembayaran','no_hp_pembayar','status_pembayaran','email_pembayar','bukti_pembayaran','jumlah_pembayaran','id_request','Invoice');                 
        
               
        
        $crud->field_type('no_hp_pembayar','integer');
        $crud->set_field_upload('bukti_pembayaran','api/assets/bukti_pembayaran');
        $crud->unset_add();                
        $crud->required_fields('nama_pembayar','no_hp_pembayar','email_pembayar','jumlah_pembayaran');                
        $crud->callback_delete(array($this,'delete_data'));    
        $crud->callback_column('Invoice',array($this,'get_invoice'));
        
        // $crud->callback_column('Status',array($this,'get_status'));
        $data = $crud->render();
        $data->id_user = $id_user;
        $data->level = $level;
        $data->state_data = $crud->getState();
 
        $this->load->view('pembayaran/index', $data, FALSE);

    }       
    public function get_invoice($value, $row){    	    	    	
    	$no_invoice = $this->function_lib->get_one('no_invoice','request_owner','id_request='.$this->db->escape($row->id_request).'');
        return '<a href="'.base_url("request/index/edit/".$row->id_request).'" > '.$no_invoice.'</a>';
    }
    public function get_rekening($value, $row){
        $nama_bank = $this->function_lib->get_one('nama_bank','rekening','id_rekening='.$this->db->escape($row->id_rekening).'');
        return '<a href="'.base_url("rekening/index/read/".$row->id_rekening).'">'.$row->s62518090.' - '.$nama_bank.'</a>';
    }
    public function get_status($value, $row){
        $label = $row->status_pembayaran;
        if (isset($row->status_pembayaran) AND $row->status_pembayaran=="proses") {
            $label = '<a href="'.base_url("").'" class="btn btn-info btn-sm"><i class="fa fa-pencil"></i> Proses</a>';
        }else if(isset($row->status) AND $row->status=="selesai"){
            $label = '<a href="#" class="btn btn-success btn-sm"><i class="fa fa-check"></i> Selesai</a>';
        }else if(isset($row->status) AND $row->status=="tolak"){
            $label = '<a href="#" class="btn btn-danger btn-sm"><i class="fa fa-block"></i> Tolak</a>';
        }
        return $label;
    }
    function delete_data($primary_key){                
        $columnUpdate = array(
            'status_pembayaran' => 'hapus'
        );
        $this->db->where('id_pembayaran', $primary_key);
        return $this->db->update('pembayaran', $columnUpdate);                
    } 
}

/* End of file Pembayaran.php */
/* Location: ./application/controllers/Pembayaran.php */