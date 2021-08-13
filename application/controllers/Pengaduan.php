<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengaduan extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		//Do your magic here
		$this->function_lib->cek_auth(array("super_admin","admin","owner"));
		$this->load->library(array('grocery_CRUD','ajax_grocery_crud'));   
	}		
 
    public function index() {
        $this->load->config('grocery_crud');        
        $crud = new Ajax_grocery_CRUD();
        $user_sess = $this->function_lib->get_user_level();
        $level = isset($user_sess['level']) ? $user_sess['level'] : "";
        $id_user = isset($user_sess['id_user']) ? $user_sess['id_user'] : "";

        $crud->set_theme('adminlte');
        $crud->set_table('pengaduan');        
        $crud->set_subject('Data Pengaduan Aplikasi');
        $crud->set_language('indonesian');        
        
        if ($level == "owner") {            
            $crud->where("pengaduan.id_owner",$id_user);
            $crud->field_type('id_owner', 'hidden', $id_user);            
            if($crud->getState() != 'add' AND $crud->getState() != 'list') {
                if ($crud->getState() == "read" OR $crud->getState() == "edit") {                    
                    $crud->unset_edit_fields(array('id_owner'));
                    $stateInfo = (array) $crud->getStateInfo();
                    $pk = isset($stateInfo['primary_key']) ? $stateInfo['primary_key'] : 0;
                    $id_pengaduan = $this->function_lib->get_one('id_pengaduan','pengaduan','id_pengaduan="'.$pk.'" AND id_owner="'.$id_user.'"');
                    if (empty($id_pengaduan)) {
                        redirect(base_url().'pengaduan/index/');
                        exit();
                    }
                }else{
                    $crud->set_relation('id_owner','owner','nama_koperasi');
                }
                
            }
        }else{
            $crud->set_relation('id_owner','owner','nama_koperasi');            
            $crud->required_fields('id_owner');
        }

        $crud->columns('nama_lengkap_pengirim','email_pengirim','isi_pengaduan','tgl_pengaduan');                 
        
        $crud->display_as('nama_lengkap_pengirim','Nama Pengirim')             
             ->display_as('email_pengirim','Email')             
             ->display_as('isi_pengaduan','Isi')                     
             ->display_as('tgl_pengaduan','Tanggal')
             ->display_as('id_owner','Koperasi');             

        // $crud->callback_field('deskripsi_request',array($this,'clearhtml'));
        $crud->unset_texteditor(array('isi_pengaduan','full_text'));
                    
        $crud->required_fields('nama_lengkap_pengirim','email_pengirim','isi_pengaduan','tgl_pengaduan');                        
        $crud->callback_column('Paket',array($this,'get_paket'));
        $crud->callback_column('Status Pengaduan',array($this,'get_status'));
        $data = $crud->render();
        $data->id_user = $id_user;
        $data->level = $level;
        $data->state_data = $crud->getState();
 
        $this->load->view('pengaduan/index', $data, FALSE);

    }          
}

/* End of file Pengaduan.php */
/* Location: ./application/controllers/Pengaduan.php */