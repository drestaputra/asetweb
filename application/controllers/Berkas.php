<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Berkas extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		//Do your magic here
		$this->function_lib->cek_auth(array("super_admin","admin"));
		$this->load->library(array('grocery_CRUD','ajax_grocery_crud'));   
	}		
 
    public function index() {
        $this->load->config('grocery_crud');
        $this->config->set_item('grocery_crud_file_upload_max_file_size', "50MB");
        $this->config->set_item('grocery_crud_xss_clean', false);
        $crud = new Ajax_grocery_CRUD();

        $user_sess = $this->function_lib->get_user_level();
        $level = isset($user_sess['level']) ? $user_sess['level'] : "";
        $id_user = isset($user_sess['id_user']) ? $user_sess['id_user'] : "";

        $crud->set_theme('adminlte');
        $crud->set_table('berkas');        
        $crud->set_subject('Data Berkas');
        $crud->set_language('indonesian');
        $crud->where('status_berkas!="deleted"');

       
        $crud->columns('nama_berkas','deskripsi_berkas','berkas','status_berkas');                 
        
        $crud->display_as('nama_berkas','Nama Berkas')
             ->display_as('deskripsi_berkas','Deskripsi')
             ->display_as('berkas','berkas')             
             ->display_as('status_berkas','STATUS') ;                                      

        // $crud->change_field_type('is_notif', 'dropdown', array('0' => 'Tidak','1' => 'Ya'));
        $crud->set_field_upload('berkas','api/assets/berkas');        
               
        $crud->required_fields('nama_berkas','berkas','status_berkas');                
        $crud->callback_delete(array($this,'delete_data'));
        $data = $crud->render();
        $data->id_user = $id_user;
        $data->level = $level;
        $data->state_data = $crud->getState();
 
        $this->load->view('berkas/index', $data, FALSE);

    }   
   
    function delete_data($primary_key){        
        $user_sess = $this->function_lib->get_user_level();
        $level = isset($user_sess['level']) ? $user_sess['level'] : "";
        $id_user = isset($user_sess['id_user']) ? $user_sess['id_user'] : "";
        
        $columnUpdate = array(
            'status_berkas' => 'deleted'
        );
        $this->db->where('id_berkas', $primary_key);
        return $this->db->update('berkas', $columnUpdate);       
    } 
}

/* End of file Berkas.php */
/* Location: ./application/controllers/Berkas.php */