<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Modal_usaha extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		//Do your magic here
		$this->function_lib->cek_auth(array("super_admin","admin","owner"));
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
        $crud->set_table('modal_usaha');        
        $crud->set_subject('Modal Usaha');
        $crud->where("modal_usaha.status_modal_usaha", "aktif");
        $crud->set_language('indonesian');
        if ($level == "owner") {            
            $crud->where("modal_usaha.id_owner",$id_user);
            $crud->or_where("modal_usaha.id_owner", null);
            $crud->field_type('id_owner', 'hidden', $id_user);            
            if($crud->getState() != 'add' AND $crud->getState() != 'list') {
                if ($crud->getState() == "read" OR $crud->getState() == "edit") {                    
                    $crud->unset_edit_fields(array('id_owner'));
                    $stateInfo = (array) $crud->getStateInfo();
                    $pk = isset($stateInfo['primary_key']) ? $stateInfo['primary_key'] : 0;
                    $id_modal_usaha = $this->function_lib->get_one('id_modal_usaha','modal_usaha','id_modal_usaha="'.$pk.'" AND id_owner="'.$id_user.'"');
                    if (empty($id_modal_usaha)) {
                        redirect(base_url().'modal_usaha/index/');
                        exit();
                    }
                }else{
                    $crud->set_relation('id_owner','owner','nama_koperasi');
                }
                
            }
            $crud->columns('catatan_modal_usaha','jumlah_modal_usaha','tgl_modal_usaha','modal_input_by');                 
        }else{
            $crud->set_relation('id_owner','owner','nama_koperasi');            
            $crud->required_fields('id_owner');
            $crud->columns('id_owner','catatan_modal_usaha','jumlah_modal_usaha','status_modal_usaha','tgl_modal_usaha','modal_input_by');                 
        }
        
    
        $crud->callback_column('jumlah_modal_usaha',array($this,'set_number_format_with_rp'));        
        $crud->field_type('jumlah_modal_usaha','integer');
        // $crud->field_type('tgl_modal_usaha','datetime');
        $crud->unset_add_fields('status_modal_usaha','modal_input_by');        
        // $crud->unset_edit();        
        // $crud->unset_texteditor(array('deskripsi_paket','full_text'));                
        $crud->required_fields('jumlah_modal_usaha','catatan_modal_usaha');                
        $crud->callback_delete(array($this,'delete_data'));    
        $crud->unset_texteditor(array('catatan_modal_usaha','full_text'));
        $crud->callback_after_insert(array($this,'set_user'));
        $data = $crud->render();
        $data->id_user = $id_user;
        $data->level = $level;
        $data->state_data = $crud->getState();
        $data->total_modal_usaha = 0;
        $data->total_modal_simpanan = 0;
        if ($data->state_data == "list" OR $data->state_data == "success") {
            if ($level == "owner") {
                $data->total_modal_usaha = $this->function_lib->get_one('sum(jumlah_modal_usaha)','modal_usaha','status_modal_usaha="aktif" AND id_owner="'.$this->db->escape_str($id_user).'"');
                $data->total_modal_simpanan = $this->function_lib->get_one('sum(jumlah_simpanan)','simpanan','status_simpanan="aktif" AND id_owner="'.$this->db->escape_str($id_user).'"');
            }else{
                $data->total_modal_usaha = $this->function_lib->get_one('sum(jumlah_modal_usaha)','modal_usaha','status_modal_usaha="aktif"');
                $data->total_modal_simpanan = $this->function_lib->get_one('sum(jumlah_simpanan)','simpanan','status_simpanan="aktif"');

            }
        }
 
        $this->load->view('modal_usaha/index', $data, FALSE);

    }          
    function delete_data($primary_key){                
        $columnUpdate = array(
            'status_modal_usaha' => 'non_aktif'
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