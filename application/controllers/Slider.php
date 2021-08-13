<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Slider extends CI_Controller {

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
        $crud->set_table('slider');        
        $crud->set_subject('Data Slider');
        $crud->set_language('indonesian');
        
        $crud->columns('judul_slider','link_slider','image_slider','is_active');                 
        
        $crud->display_as('judul_slider','Judul')             
             ->display_as('link_slider','Link')             
             ->display_as('image_slider','Gambar')             
             ->display_as('is_active','Status');             

        // $crud->callback_field('deskripsi_slider',array($this,'clearhtml'));
        $crud->unset_fields('deskripsi_slider');
        $crud->change_field_type('is_active', 'dropdown', array('0' => 'Non Aktif','1' => 'Aktif'));
        $crud->set_field_upload('image_slider','api/assets/slider');        
               
        $crud->required_fields('judul_slider','image_slider');                
        $crud->callback_delete(array($this,'delete_data'));    
        $data = $crud->render();
        $data->id_user = $id_user;
        $data->level = $level;
        $data->state_data = $crud->getState();
 
        $this->load->view('slider/index', $data, FALSE);

    }          
    function delete_data($primary_key){                
        $columnUpdate = array(
            'is_active' => '0'
        );
        $this->db->where('id_slider', $primary_key);
        return $this->db->update('slider', $columnUpdate);                
    } 
}

/* End of file Slider.php */
/* Location: ./application/controllers/Slider.php */