<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rekening extends CI_Controller {

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
        $crud->where("status","aktif");
        // $crud->or_where("status","non_aktif");
        $crud->set_theme('adminlte');
        $crud->set_table('rekening');        
        $crud->set_subject('Data Rekening');
        $crud->set_language('indonesian');
       
        $crud->columns('no_rekening','nama_pemilik_rekening','nama_bank','gambar_bank','status');                 
        
        $crud->display_as('no_rekening','Nomor Rekening')
             ->display_as('nama_pemilik_rekening','Nama Pemilik')
             ->display_as('nama_bank','Bank')             
             ->display_as('gambar_bank','Gambar Bank')                      
             ->display_as('status','Status');             
        
        $crud->change_field_type('status', 'dropdown', array('aktif' => 'Aktif','non_aktif' => 'Non Aktif'));
        $crud->set_field_upload('gambar_bank','api/assets/bank');        
               
        $crud->required_fields('no_rekening','nama_pemilik_rekening','nama_bank');                
        $crud->callback_delete(array($this,'delete_data'));        
        $data = $crud->render();
        $data->id_user = $id_user;
        $data->level = $level;
        $data->state_data = $crud->getState();
 
        $this->load->view('rekening/index', $data, FALSE);

    }   
    
    function delete_data($primary_key){        
        $user_sess = $this->function_lib->get_user_level();
        $level = isset($user_sess['level']) ? $user_sess['level'] : "";
        $id_user = isset($user_sess['id_user']) ? $user_sess['id_user'] : "";
      
        $columnUpdate = array(
            'status' => 'non_aktif'
        );
        $this->db->where('id_rekening', $primary_key);
        return $this->db->update('rekening', $columnUpdate);                
    } 
}

/* End of file Rekening.php */
/* Location: ./application/controllers/Rekening.php */