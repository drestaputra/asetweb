<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Opd extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		//Do your magic here
		$this->function_lib->cek_auth(array("super_admin","admin"));
		$this->load->library(array('grocery_CRUD','ajax_grocery_crud'));   
	}		
 
    public function index() {
        $this->load->config('grocery_crud');
        $this->config->set_item('grocery_crud_xss_clean', false);
        $crud = new Ajax_grocery_CRUD();

        $user_sess = $this->function_lib->get_user_level();
        $level = isset($user_sess['level']) ? $user_sess['level'] : "";
        $id_user = isset($user_sess['id_user']) ? $user_sess['id_user'] : "";

        $crud->set_theme('adminlte');
        $crud->set_table('opd');        
        $crud->set_subject('Data Organisasi Perangkat Desa');
        $crud->set_language('indonesian');

       
        $crud->columns('nama_opd','label_opd','kode_opd','alamat_opd','status_opd');                 
        
        $crud->display_as('nama_opd','Nama')
             ->display_as('label_opd','Label')
             ->display_as('kode_opd','Kode')             
             ->display_as('alamat_opd','Alamat')             
             ->display_as('status_opd','STATUS') ;                                      

        // $crud->change_field_type('stat', 'dropdown', array('0' => 'Tidak','1' => 'Ya'));
               
        $crud->required_fields('nama_opd','label_opd');                
        $crud->callback_delete(array($this,'delete_data'));
        $crud->unset_texteditor(array('alamat_opd','full_text'));
        $crud->unique_fields(['label_opd']);        
        $data = $crud->render();
        $data->id_user = $id_user;
        $data->level = $level;
        $data->state_data = $crud->getState();
 
        $this->load->view('opd/index', $data, FALSE);

    }   
    function delete_data($primary_key){        
        $user_sess = $this->function_lib->get_user_level();
        $level = isset($user_sess['level']) ? $user_sess['level'] : "";
        $id_user = isset($user_sess['id_user']) ? $user_sess['id_user'] : "";
        
        $columnUpdate = array(
            'status_opd' => 'non_aktif'
        );
        $this->db->where('id_opd', $primary_key);
        return $this->db->update('opd', $columnUpdate);       
    } 
}

/* End of file Berita.php */
/* Location: ./application/controllers/Berita.php */