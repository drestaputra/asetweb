<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Angsuran extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		//Do your magic here
		$this->function_lib->cek_auth(array("super_admin","admin","owner"));
		$this->load->library('grocery_CRUD');   
	}		
 
    public function index() {    	
        $crud = new grocery_CRUD();
 
        $crud->set_theme('adminlte');
        $crud->set_table('angsuran');
        $crud->set_subject('Data Jenis Angsuran');
        $crud->set_language('indonesian');
        $crud->columns('periode_angsuran','lama_angsuran','status_angsuran');                
                        
         $crud->display_as('status_angsuran','Status');		
        $crud->required_fields('periode_angsuran','lama_angsuran');
        $crud->unset_add_fields('status_angsuran');                
        $crud->callback_delete(array($this,'delete_data'));
        
        $data = $crud->render();
 
        $this->load->view('angsuran/index', $data, FALSE);

    }

    function delete_data($primary_key){
        $columnUpdate = array(
            "status_angsuran" => "non_aktif"
        );
        $this->db->where('id_angsuran', $this->security->sanitize_filename($primary_key));
        $this->db->update('angsuran', $columnUpdate);                
    } 
}

/* End of file Riwayat Simpanan.php */
/* Location: ./application/controllers/Riwayat Simpanan.php */