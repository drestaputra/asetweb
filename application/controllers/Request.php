<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Request extends CI_Controller {

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
        $crud->where('request_owner.status!="hapus"');            
        $user_sess = $this->function_lib->get_user_level();
        $level = isset($user_sess['level']) ? $user_sess['level'] : "";
        $id_user = isset($user_sess['id_user']) ? $user_sess['id_user'] : "";

        $crud->set_theme('adminlte');
        $crud->set_table('request_owner');        
        $crud->set_subject('Data Request Akun Koperasi');
        $crud->set_language('indonesian');
        $crud->set_relation('id_paket','paket','nama_paket');   
        $crud->set_relation('id_rekening','rekening','no_rekening');   
        
        $crud->columns('no_invoice','username','email','no_hp','Paket','tgl_request','Status Request','Rekening','total_tagihan_invoice');                 
        
        $crud->display_as('no_invoice','Invoice')             
            ->display_as('username','Nama')             
             ->display_as('email','Email')             
             ->display_as('no_hp','No. HP')                          
             ->display_as('total_tagihan_invoice','Tagihan')
             ->display_as('tgl_request','Tgl Request');             

        // $crud->callback_field('deskripsi_request',array($this,'clearhtml'));
        $crud->field_type('no_hp','integer');
        $crud->unset_add();                
        $crud->required_fields('username','email','no_hp','id_paket','tgl_request','status','id_rekening');                
        $crud->callback_delete(array($this,'delete_data'));    
        $crud->callback_column('Paket',array($this,'get_paket'));
        $crud->callback_column('Rekening',array($this,'get_rekening'));
        $crud->callback_column('Status Request',array($this,'get_status'));
        $crud->unset_edit_fields('id_paket');
        $crud->unset_edit_fields('total_tagihan_invoice');
        $data = $crud->render();
        $data->id_user = $id_user;
        $data->level = $level;
        $data->state_data = $crud->getState();
 
        $this->load->view('request/index', $data, FALSE);

    }       
    public function get_paket($value, $row){
        return '<a href="'.base_url("paket/index/read/".$row->id_paket).'" > '.$row->s66a086c3.'</a>';
    }
    public function get_rekening($value, $row){
        $nama_bank = $this->function_lib->get_one('nama_bank','rekening','id_rekening='.$this->db->escape($row->id_rekening).'');
        return '<a href="'.base_url("rekening/index/read/".$row->id_rekening).'">'.$row->s62518090.' - '.$nama_bank.'</a>';
    }
    public function get_status($value, $row){
        if (isset($row->status) AND $row->status=="proses") {
            $label = '<a href="'.base_url("user/owner/tambah/".$row->id_request).'" class="btn btn-info btn-sm"><i class="fa fa-pencil"></i> Proses</a>';
        }else if(isset($row->status) AND $row->status=="selesai"){
            $label = '<a href="#" class="btn btn-success btn-sm"><i class="fa fa-check"></i> Selesai</a>';
        }else if(isset($row->status) AND $row->status=="tolak"){
            $label = '<a href="#" class="btn btn-danger btn-sm"><i class="fa fa-block"></i> Tolak</a>';
        }
        return $label;
    }
    function delete_data($primary_key){                
        $columnUpdate = array(
            'status' => 'hapus'
        );
        $this->db->where('id_request', $primary_key);
        return $this->db->update('request_owner', $columnUpdate);                
    } 
}

/* End of file Request.php */
/* Location: ./application/controllers/Request.php */