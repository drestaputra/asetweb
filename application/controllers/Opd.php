<?php
defined('BASEPATH') OR exit('No direct script access allowed');


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xls;

// use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Opd extends CI_Controller {

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
        $crud->set_table('opd');        
        $crud->set_subject('Data Organisasi Perangkat Desa');
        $crud->set_language('indonesian');
        $crud->where("status_opd != 'deleted'");

        // jika user adalah admin, maka hanya bisa :  mengelola OPD miliknya sendiri, menambah 1 OPD jika belum ada
        $isNotHaveOpd = false;
        if ($level == "admin") {            
            $id_opd_admin = $this->function_lib->get_one('id_opd_admin','admin','id_admin='.$this->db->escape($id_user).' AND id_opd_admin IN (SELECT id_opd FROM opd WHERE status_opd != "deleted")');
            // hanya bisa mengelola opd miliknya sendiri
            if (!empty($id_opd_admin)) {
                $crud->where("id_opd", $id_opd_admin);
                // jika sudah mempunyai OPD, tidak bisa menambah OPD
                $crud->unset_add();
            }else{
                // jika belum mempunyai OPD, bisa menambah 1 OPD atau memilih OPD lewat edit Profil
                $isNotHaveOpd = true;
                $crud->where(0);
                // setelah menambah OPD dan berhasil, set OPD milik admin menjadi opd yg baru saja ditambahkan

            }
                $crud->callback_after_insert(array($this, 'setOpdAdmin'));
        }


       
        $crud->columns('nama_opd','label_opd','kode_opd','alamat_opd','status_opd');                 
        
        $crud->display_as('nama_opd','Nama')
             ->display_as('label_opd','Label')
             ->display_as('kode_opd','Kode')             
             ->display_as('alamat_opd','Alamat')             
             ->display_as('status_opd','STATUS') ;                                      

        // $crud->change_field_type('stat', 'dropdown', array('0' => 'Tidak','1' => 'Ya'));
               
        $crud->required_fields('nama_opd','label_opd');                
        $crud->unique_fields(array('nama_opd'));
        $crud->callback_delete(array($this,'delete_data'));
        $crud->unset_texteditor(array('alamat_opd','full_text'));
        $crud->unique_fields(['label_opd']);        
        $data = $crud->render();
        $data->id_user = $id_user;
        $data->level = $level;
        $data->isNotHaveOpd = $isNotHaveOpd;
        $data->state_data = $crud->getState();
 
        $this->load->view('opd/index', $data, FALSE);

    }   
    public function import(){
        $this->load->model('Mopd');

        
        if ($this->input->post('preview')) {
            $upload = $this->Mopd->upload();
            if (isset($upload['status']) AND $upload['status'] == 200) {
                $filename = (isset($upload['data']['file_name'])) ? $upload['data']['file_name'] : "";
                redirect('opd/preview_import/'.$filename);
            }else{
                $msg = isset($upload['msg']) ? $upload['msg'] : "";
                redirect('opd/import?status=500&msg='.base64_encode($msg));
            }
            
        }
        $this->load->view('opd/import', null, FALSE);
    }
    public function preview_import($filename = ""){
        $this->load->model('Mopd');
        $path = "./assets/excel/opd/";
        if (empty($filename)) {
            redirect('opd/import?status=500&msg='.base64_encode("File Excel kosong"));
            return;
        }else if(!file_exists($path.$filename)){
            redirect('opd/import?status=500&msg='.base64_encode("file import tidak ditemukan, silahkan upload ulang"));
            return;
        }
        if (trim($this->input->post('save')) == "1") {
            // simpan data excel ke database
            $save = $this->Mopd->save_import($filename);
            $status = isset($save['status']) ? $save['status'] : 500;
            $msg = isset($save['msg']) ? $save['msg'] : 500;
            if ($status == 200) {
                redirect("opd/index?status=200&msg=".base64_encode($msg));
            }else{
                redirect("opd/preview_import/".$filename."?status=500&msg=".base64_encode($msg));
            }
        }
        
        $reader = new PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load($path.$filename); // Load file yang tadi diupload ke folder tmp
        $sheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
        $sheet = array_splice($sheet, 1);
        $insertData = array();
        foreach ($sheet as $key => $value) {
            if (isset($value['A']) && !empty($value['A']) && isset($value['B']) && !empty($value['B'])) {
                $insertData[$key] = $sheet[$key];
            }
        }
        
        $data['filename'] = $filename;
        
        $data['dataOpd'] = $insertData;
        
        $this->load->view('opd/preview_import', $data, FALSE);
    }
    function setOpdAdmin($post_array,$primary_key){      
        $this->function_lib->cek_auth(array("admin"));
        $user_sess = $this->function_lib->get_user_level();
        $level = isset($user_sess['level']) ? $user_sess['level'] : "";
        $id_user = isset($user_sess['id_user']) ? $user_sess['id_user'] : "";

        $this->db->where('id_admin', $id_user);
        $this->db->set("id_opd_admin", $primary_key);
        $this->db->update('admin');
        return true;
    }
    function delete_data($primary_key){        
        $user_sess = $this->function_lib->get_user_level();
        $level = isset($user_sess['level']) ? $user_sess['level'] : "";
        $id_user = isset($user_sess['id_user']) ? $user_sess['id_user'] : "";
        
        $columnUpdate = array(
            'status_opd' => 'non_aktif'
        );
        $this->db->where('id_opd', $primary_key);
        return $this->db->update('opd', $columnUpdate);       
    } 
    function getAllOpd(){
        $this->function_lib->cek_auth(array("super_admin","admin"));
        header("Content-type: Application/json");
        $this->load->model('Mopd');
        $dataOpd = $this->Mopd->getAllOpd();
        echo(json_encode($dataOpd));
    }
}

/* End of file Berita.php */
/* Location: ./application/controllers/Berita.php */