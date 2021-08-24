<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Aset extends CI_Controller {

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
        $crud->set_table('aset');        
        $crud->set_subject('Aset Tanah');
        $crud->where("aset.status_aset", "aktif");
        $crud->set_language('indonesian');
        
    
        // $crud->callback_column('jumlah_modal_usaha',array($this,'set_number_format_with_rp'));        
        // $crud->field_type('jumlah_modal_usaha','integer');
        // $crud->field_type('tgl_modal_usaha','datetime');
        // $crud->unset_add_fields('status_modal_usaha','modal_input_by');        
        // $crud->unset_edit();        
        // $crud->unset_texteditor(array('deskripsi_paket','full_text'));                
        // $crud->required_fields('jumlah_modal_usaha','catatan_modal_usaha');                
        $crud->callback_delete(array($this,'delete_data'));    
        $crud->unset_texteditor(array('catatan_modal_usaha','full_text'));
        // $crud->callback_after_insert(array($this,'set_user'));
        $crud->unset_texteditor(array('alamat','full_text'));
        $crud->unset_texteditor(array('penggunaan','full_text'));
        $crud->unset_texteditor(array('asal_perolehan','full_text'));
        $crud->unset_texteditor(array('asal_perolehan','full_text'));
        $data = $crud->render();
        $data->id_user = $id_user;
        $data->level = $level;
        $data->state_data = $crud->getState();
        
        if ($data->state_data == "list" OR $data->state_data == "success") {
            
        }
 
        $this->load->view('aset/index', $data, FALSE);

    }          
    function delete_data($primary_key){                
        $columnUpdate = array(
            'status_aset' => 'deleted'
        );
        $this->db->where('id_modal_usaha', $primary_key);
        return $this->db->update('modal_usaha', $columnUpdate);                
    } 
    public function set_number_format_with_rp($value, $row){
        return "Rp. ".number_format($value,'2',',','.');
    }
    function set_user($post_array,$primary_key) {
        $user_sess = $this->function_lib->get_user_level();
        $level = isset($user_sess['level']) ? $user_sess['level'] : "";
        $username = isset($user_sess['username']) ? $user_sess['username'] : "";
        $post_array['modal_input_by'] = $level . '-' . $username;
        $post_array['tgl_modal_usaha'] = isset($post_array['tgl_modal_usaha']) ? date("Y-m-d H:i:s", strtotime($post_array['tgl_modal_usaha'])) : date("Y-m-d H:i:s");
        $this->db->where('id_modal_usaha', $primary_key);
        $this->db->update('modal_usaha',$post_array);
     
        return true;
    }  
}

/* End of file Modal_usaha.php */
/* Location: ./application/controllers/Modal_usaha.php */