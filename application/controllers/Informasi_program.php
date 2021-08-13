<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Informasi_program extends CI_Controller {

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
        $crud->set_table('informasi_program');        
        $crud->set_subject('Data Informasi Program');
        $crud->set_language('indonesian');

        if ($level == "owner") {            
            $crud->where("informasi_program.id_owner",$id_user);
            $crud->field_type('id_owner', 'hidden', $id_user);            
            if($crud->getState() != 'add' AND $crud->getState() != 'list') {
                if ($crud->getState() == "read" OR $crud->getState() == "edit") {                    
                    $crud->unset_edit_fields(array('id_owner'));
                    $stateInfo = (array) $crud->getStateInfo();
                    $pk = isset($stateInfo['primary_key']) ? $stateInfo['primary_key'] : 0;
                    $id_informasi_program = $this->function_lib->get_one('id_informasi_program','informasi_program','id_informasi_program="'.$pk.'" AND id_owner="'.$id_user.'"');
                    if (empty($id_informasi_program)) {
                        redirect(base_url().'informasi_program/index/');
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
        $crud->columns('id_owner','judul_informasi_program','deskripsi_informasi_program','foto_informasi_program','tgl_informasi_program','status','is_notif');                 
        
        $crud->display_as('id_owner','Koperasi')
             ->display_as('judul_informasi_program','Judul')
             ->display_as('deskripsi_informasi_program','Deskripsi')
             ->display_as('foto_informasi_program','Foto')             
             ->display_as('tgl_informasi_program','Tanggal')             
             ->display_as('is_notif','Kirim Notifikasi')
             ->display_as('status','STATUS') ;                                      

        // $crud->callback_field('deskripsi_informasi_program',array($this,'clearhtml'));
        $crud->change_field_type('is_notif', 'dropdown', array('0' => 'Tidak','1' => 'Ya'));
        $crud->set_field_upload('foto_informasi_program','api/assets/foto_informasi_program');        
               
        $crud->required_fields('judul_informasi_program','deskripsi_informasi_program','foto_informasi_program','status','tgl_informasi_program');                
        $crud->callback_delete(array($this,'delete_data'));
        $crud->callback_after_insert(array($this, 'after_insert_baru'));
        $data = $crud->render();
        $data->id_user = $id_user;
        $data->level = $level;
        $data->state_data = $crud->getState();
 
        $this->load->view('informasi_program/index', $data, FALSE);

    }   
    function clearhtml($value = '', $primary_key = null)
    {
        return '<input type="text" maxlength="50" value="'.$value.'" name="phone" style="width:462px">';
    }
    function after_insert_baru($post_array,$primary_key){      
        $this->load->model('Mnotifikasi');
        $dataInformasi = $this->function_lib->get_row('informasi_program','id_informasi_program='.$this->db->escape($primary_key).'');
        if (!empty($dataInformasi)) {
            if (isset($dataInformasi['is_notif']) AND $dataInformasi['is_notif']=="1") {
                // jika notif aktif jalankan function notifikasi
                $id_owner = isset($dataInformasi['id_owner']) ? $dataInformasi['id_owner'] : "";
                $content = array(
                    "title"=> "Artakita",
                    "message"=> isset($dataInformasi['judul_informasi_program']) ? strip_tags($dataInformasi['judul_informasi_program']) : "",
                    "tag" => $primary_key,
                    "news_permalink" => $primary_key
                );
                if (isset($dataInformasi['id_owner']) AND trim($dataInformasi['id_owner'])!="") {
                    // // $message = array("title"=>$title,"message"=>$messageNotif,"tag"=>$key,"news_permalink"=>$value['news_permalink']);
                    $this->Mnotifikasi->sendToTopic($id_owner,$content);
                }else{
                    $this->Mnotifikasi->sendToTopic("all",$content);                    
                }
            }
        }
        return true;
    }
    function delete_data($primary_key){        
        $user_sess = $this->function_lib->get_user_level();
        $level = isset($user_sess['level']) ? $user_sess['level'] : "";
        $id_user = isset($user_sess['id_user']) ? $user_sess['id_user'] : "";
        if ($level == "owner") {            
            $id_informasi_program = $this->function_lib->get_one('id_informasi_program','informasi_program','id_informasi_program="'.$primary_key.'" AND id_owner="'.$id_user.'"');
            
            if (empty($id_informasi_program)) {
                return false;
            }else{
                $columnUpdate = array(
                    'status' => 'non_aktif'
                );
                $this->db->where('id_informasi_program', $primary_key);
                return $this->db->update('informasi_program', $columnUpdate);            
            }
        }else{
            $columnUpdate = array(
                'status' => 'non_aktif'
            );
            $this->db->where('id_informasi_program', $primary_key);
            return $this->db->update('informasi_program', $columnUpdate);        
        }
    } 
}

/* End of file Informasi_program.php */
/* Location: ./application/controllers/Informasi_program.php */