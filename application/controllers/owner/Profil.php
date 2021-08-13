<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profil extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		//Do your magic here
		$this->function_lib->cek_auth(array("owner"));
		$this->load->library(array('grocery_CRUD','ajax_grocery_crud'));   
	}		
 
    public function index() {
        $crud = new Ajax_grocery_CRUD();

        $user_sess = $this->function_lib->get_user_level();
        $level = isset($user_sess['level']) ? $user_sess['level'] : "";
        $id_user = isset($user_sess['id_user']) ? $user_sess['id_user'] : "";

        $crud->set_theme('adminlte');
        $crud->set_table('profil_koperasi');        
        $crud->set_subject('Profil Koperasi');
        $crud->set_language('indonesian');
               
        // $crud->columns('id_owner','judul_profil_koperasi','deskripsi_profil_koperasi','foto_profil_koperasi','tgl_profil_koperasi','status');         
        // jika owner sudah set profil maka hilangkan operasi add
        $cek_profil = $this->function_lib->get_one('id_profil_koperasi','profil_koperasi','id_owner='.$this->db->escape($id_user).'');
        $crud->field_type('id_owner', 'hidden', $id_user);
        if (!empty($cek_profil)) {
        	$crud->unset_add();
        	$crud->unset_delete();
        }

        // $crud->display_as('id_owner','Koperasi')
        //      ->display_as('judul_profil_koperasi','Judul')
        //      ->display_as('deskripsi_profil_koperasi','Deskripsi')
        //      ->display_as('foto_profil_koperasi','Foto')             
        //      ->display_as('tgl_profil_koperasi','Tanggal')             
        //      ->display_as('status','STATUS') ;                                      
        $crud->unset_columns(array('id_owner'));
        $crud->unset_texteditor(array('alamat','full_text'));
        $crud->field_type('no_hp', 'integer');
        $crud->set_field_upload('foto','api/assets/foto_profil_koperasi');        
               
        $data = $crud->render();
        $data->id_user = $id_user;
        $data->level = $level;
        $data->state_data = $crud->getState();
 
        $this->load->view('owner/profil_koperasi/index', $data, FALSE);

    }   
    
}

/* End of file Informasi_program.php */
/* Location: ./application/controllers/Informasi_program.php */