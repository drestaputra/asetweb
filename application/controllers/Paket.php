<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Paket extends CI_Controller {

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
        $crud->set_table('paket');        
        $crud->set_subject('Data Paket');
        $crud->set_language('indonesian');
        
        $crud->columns('nama_paket','durasi_paket','deskripsi_paket','harga_paket','status_paket');                 
        
        $crud->display_as('nama_paket','Nama')             
             ->display_as('deskripsi_paket','Deskripsi')             
             ->display_as('harga_paket','Harga')             
             ->display_as('status_paket','Status');             

        // $crud->callback_field('deskripsi_paket',array($this,'clearhtml'));
        $crud->field_type('harga_paket','integer');
        $crud->unset_add_fields('status_paket');        
        // $crud->unset_texteditor(array('deskripsi_paket','full_text'));                
        $crud->required_fields('nama_paket','deskripsi_paket','harga');                
        $crud->callback_delete(array($this,'delete_data'));    
        $data = $crud->render();
        $data->id_user = $id_user;
        $data->level = $level;
        $data->state_data = $crud->getState();
 
        $this->load->view('paket/index', $data, FALSE);

    }          
    function delete_data($primary_key){                
        $columnUpdate = array(
            'status_paket' => 'hapus'
        );
        $this->db->where('id_paket', $primary_key);
        return $this->db->update('paket', $columnUpdate);                
    } 
}

/* End of file Paket.php */
/* Location: ./application/controllers/Paket.php */