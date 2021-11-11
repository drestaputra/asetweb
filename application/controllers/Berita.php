<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Berita extends CI_Controller {

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
        $crud->set_table('berita');        
        $crud->set_subject('Data Informasi Program');
        $crud->set_language('indonesian');

        $crud->where("status_berita != 'deleted'");

       
        $crud->columns('judul_berita','deskripsi_berita','foto_berita','tgl_berita','status_berita');                 
        // $crud->columns('judul_berita','deskripsi_berita','foto_berita','tgl_berita','status_berita','is_notif');                 
        $crud->unset_fields('is_notif');
        
        $crud->display_as('judul_berita','Judul')
             ->display_as('deskripsi_berita','Deskripsi')
             ->display_as('foto_berita','Foto')             
             ->display_as('tgl_berita','Tanggal')             
             ->display_as('is_notif','Kirim Notifikasi')
             ->display_as('status_berita','STATUS') ;                                      

        // $crud->callback_field('deskripsi_berita',array($this,'clearhtml'));
        $crud->change_field_type('is_notif', 'dropdown', array('0' => 'Tidak','1' => 'Ya'));
        $crud->set_field_upload('foto_berita','api/assets/foto_berita');        
               
        $crud->required_fields('judul_berita','deskripsi_berita','foto_berita','status_berita','tgl_berita');                
        $crud->callback_delete(array($this,'delete_data'));
        $crud->callback_after_insert(array($this, 'after_insert_baru'));
        $data = $crud->render();
        $data->id_user = $id_user;
        $data->level = $level;
        $data->state_data = $crud->getState();
 
        $this->load->view('berita/index', $data, FALSE);

    }   
    function clearhtml($value = '', $primary_key = null)
    {
        return '<input type="text" maxlength="50" value="'.$value.'" name="phone" style="width:462px">';
    }
    function after_insert_baru($post_array,$primary_key){      
        // $this->load->model('Mnotifikasi');
        // $dataInformasi = $this->function_lib->get_row('berita','id_berita='.$this->db->escape($primary_key).'');
        // if (!empty($dataInformasi)) {
        //     if (isset($dataInformasi['is_notif']) AND $dataInformasi['is_notif']=="1") {
        //         // jika notif aktif jalankan function notifikasi
                
        //         $content = array(
        //             "title"=> "Artakita",
        //             "message"=> isset($dataInformasi['judul_berita']) ? strip_tags($dataInformasi['judul_berita']) : "",
        //             "tag" => $primary_key,
        //             "news_permalink" => $primary_key
        //         );
        //         $this->Mnotifikasi->sendToTopic("all",$content);                    
        //     }
        // }
        return true;
    }
    function delete_data($primary_key){        
        $user_sess = $this->function_lib->get_user_level();
        $level = isset($user_sess['level']) ? $user_sess['level'] : "";
        $id_user = isset($user_sess['id_user']) ? $user_sess['id_user'] : "";
        
        $columnUpdate = array(
            'status_berita' => 'deleted'
        );
        $this->db->where('id_berita', $primary_key);
        return $this->db->update('berita', $columnUpdate);       
    } 
}

/* End of file Berita.php */
/* Location: ./application/controllers/Berita.php */