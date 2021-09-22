<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Foto_aset extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		//Do your magic here
		$this->function_lib->cek_auth(array("super_admin","admin","koordinator","pengurus_barang"));
		$this->load->library(array('grocery_CRUD','ajax_grocery_crud'));   
	}		
 
    public function index($id_aset) {
        $this->load->model('Mopd');
        $this->load->config('grocery_crud');        
        $this->config->set_item('grocery_crud_xss_clean', false);
        $id_aset = $this->function_lib->get_one('id_aset','aset','id_aset='.$this->db->escape($id_aset).'');
        if (empty($id_aset)) {
        	redirect("aset/index?status=500&msg=".base64_encode("ID aset kosong"));
        }
        $nama_aset = $this->function_lib->get_one('nama_aset','aset','id_aset='.$this->db->escape($id_aset).'');
        $crud = new Ajax_grocery_CRUD();
        $user_sess = $this->function_lib->get_user_level();
        $level = isset($user_sess['level']) ? $user_sess['level'] : "";
        $id_user = isset($user_sess['id_user']) ? $user_sess['id_user'] : "";
        $crud->field_type('id_aset', 'hidden', $id_aset);            
        $crud->set_theme('adminlte');
        $crud->set_table('foto_aset');        
        $crud->set_subject('Galeri Foto Aset Tanah : '. $nama_aset);
        $crud->where("foto_aset.status_foto != 'deleted'");
        $crud->where("id_aset", $id_aset);
        $crud->set_language('indonesian');
        
        $crud->unset_columns("id_aset");
        $crud->unset_fields("created_datetime");

        // mengurangi beban load desa
        $action = $crud->getState();


        $crud->callback_delete(array($this,'delete_data'));    
        $crud->set_field_upload('foto_aset','api/assets/foto_aset');        
        $crud->change_field_type('status_foto', 'dropdown', array('aktif' => 'Aktif','non_aktif' => 'Non Aktif', 'deleted' => 'Deleted'));

        $data = $crud->render();
        $data->id_user = $id_user;
        $data->level = $level;
        $data->state_data = $crud->getState();
        $data->nama_aset = $nama_aset;
 
        $this->load->view('foto_aset/index', $data, FALSE);

    }   
    
           
    function delete_data($primary_key){                
        $columnUpdate = array(
            'status_foto' => 'deleted'
        );
        $this->db->where('id_foto_aset', $primary_key);
        return $this->db->update('foto_aset', $columnUpdate);                
    } 
    
}

/* End of file Modal_usaha.php */
/* Location: ./application/controllers/Modal_usaha.php */