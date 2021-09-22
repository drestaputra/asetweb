<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Aset extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		//Do your magic here
		$this->function_lib->cek_auth(array("super_admin","admin","koordinator","pengurus_barang"));
		$this->load->library(array('grocery_CRUD','ajax_grocery_crud'));   
	}		
 
    public function index() {
        $this->load->model('Mopd');
        $this->load->config('grocery_crud');        
        $this->config->set_item('grocery_crud_xss_clean', false);
        $crud = new Ajax_grocery_CRUD();
        $user_sess = $this->function_lib->get_user_level();
        $level = isset($user_sess['level']) ? $user_sess['level'] : "";
        $id_user = isset($user_sess['id_user']) ? $user_sess['id_user'] : "";

        $crud->set_theme('adminlte');
        $crud->set_table('aset');        
        $crud->set_subject('Aset Tanah');
        $crud->where("aset.status_aset != 'deleted'");
        $crud->set_language('indonesian');
        
        
        $crud->columns("nama_aset","galeri","kode_barang","register","kecamatan","desa","jenis_hak","tanggal_sertifikat","nomor_sertifikat","penggunaan","asal_perolehan","harga_perolehan","keterangan","latitude","longitude","status_aset","status_verifikasi_aset");
        $crud->unset_columns("created_by","created_by_id");
        // $crud->callback_column('jumlah_modal_usaha',array($this,'set_number_format_with_rp'));        
        // $crud->field_type('jumlah_modal_usaha','integer');
        // $crud->field_type('tgl_modal_usaha','datetime');
        // $crud->unset_add_fields('status_modal_usaha','modal_input_by');        
        // $crud->unset_edit();        
        // $crud->unset_texteditor(array('deskripsi_paket','full_text'));                
        // $crud->required_fields('jumlah_modal_usaha','catatan_modal_usaha'); 


        $crud->display_as('id_opd_aset','OPD')
             ->display_as('galeri','Galeri')             
             ->display_as('id_kecamatan','Kecamatan')
             ->display_as('id_desa','Desa')
             ->display_as('tgl_berita','Tanggal')             
             ->display_as('created_datetime','Tanggal Data');

        $crud->callback_column('galeri',array($this,'getGaleriUrl'));
        // mengurangi beban load desa
        $action = $crud->getState();
        $where_desa = null;
        if (!empty($action) AND $action=="add") {
            $where_desa= "id_kecamatan<10";
        }else if(!empty($action) AND $action=="edit"){
            $id = $this->uri->segment(4,0);            
            $asetArr = $this->function_lib->get_row_select_by('id_kecamatan,id_desa','aset','id_aset='.$this->db->escape($id).'');
            $id_kecamatan = isset($asetArr['id_kecamatan']) ? $asetArr['id_kecamatan'] : 0;
            $id_desa = isset($asetArr['id_desa']) ? $asetArr['id_desa'] : 0;
            $where_desa = 'id_kecamatan ="'.$id_kecamatan.'"';
        }
        
        // pertimbangkan ttg performa karena load relasi saat di list
            $crud->set_relation('id_kecamatan','kecamatan','nama', ' id_kabupaten = "3305"');
            $crud->set_relation_dependency('id_kecamatan','kabupaten','id_kabupaten');
            $crud->set_relation('id_desa','desa','nama', $where_desa);        
            $crud->set_relation_dependency('id_desa','kecamatan','id_kecamatan');
        

        if ($level == "pengurus_barang") {
            $id_admin = $this->function_lib->get_one('id_admin_pengurus_barang', 'pengurus_barang','id_pengurus_barang='.$this->db->escape($id_user).'');
            $id_opd_admin = $this->function_lib->get_one('id_opd_admin', 'admin','id_admin='.$this->db->escape($id_admin).'');
            $crud->set_relation('id_opd_aset','admin','id_opd_admin,(SELECT label_opd FROM opd where id_opd=id_opd_admin)', 'status!="deleted" AND id_opd_admin='.$this->db->escape($id_opd_admin).'');
            $crud->unset_add();
            $crud->unset_delete();
            $crud->where('jea67d6ad.id_opd_admin', $id_opd_admin);
        }else if($level == "koordinator"){
            $id_admin = $this->function_lib->get_one('id_admin_koordinator', 'koordinator','id_koordinator='.$this->db->escape($id_user).'');
            $id_opd_admin = $this->function_lib->get_one('id_opd_admin', 'admin','id_admin='.$this->db->escape($id_admin).'');
            $crud->set_relation('id_opd_aset','admin','id_opd_admin,(SELECT label_opd FROM opd where id_opd=id_opd_admin)', 'status!="deleted" AND id_opd_admin='.$this->db->escape($id_opd_admin).'');
            $crud->unset_add();
            $crud->unset_delete();
            $crud->where('jea67d6ad.id_opd_admin', $id_opd_admin);
        }else if($level == "super_admin"){
            $crud->set_relation('id_opd_aset','admin','id_opd_admin,(SELECT label_opd FROM opd where id_opd=id_opd_admin)', 'status!="deleted" ');
        }


        $crud->callback_delete(array($this,'delete_data'));    
        $crud->unset_texteditor(array('catatan_modal_usaha','full_text'));
        // $crud->callback_after_insert(array($this,'set_user'));
        $crud->unset_texteditor(array('alamat','full_text'));
        $crud->unset_texteditor(array('penggunaan','full_text'));
        $crud->unset_texteditor(array('asal_perolehan','full_text'));
        $crud->unset_texteditor(array('asal_perolehan','full_text'));

        $crud->change_field_type('status_aset', 'dropdown', array('aktif' => 'Aktif','non_aktif' => 'Non Aktif', 'deleted' => 'Deleted'));
        $crud->change_field_type('status_verifikasi_aset', 'dropdown', array('valid' => 'Valid','tidak_valid' => 'Tidak Valid', 'sedang_diverifikasi' => 'Sedang Diverifikasi'));

        $data = $crud->render();
        $data->id_user = $id_user;
        $data->level = $level;
        $data->state_data = $crud->getState();
        $data->dataOpd = $this->Mopd->getAllOpd();
        
        if ($data->state_data == "list" OR $data->state_data == "success") {
            
        }
 
        $this->load->view('aset/index', $data, FALSE);

    }   
    public function getGaleriUrl($value, $row){                      
        return '<a class="btn btn-info" href="'.base_url("foto_aset/index/".$row->id_aset).'" ><i class="fa fa-eye"></i> Lihat</a>';
    }
     public function verifikasi() {
       $this->load->model('Mopd');
        $this->load->config('grocery_crud');        
        $this->config->set_item('grocery_crud_xss_clean', false);
        $crud = new Ajax_grocery_CRUD();
        $user_sess = $this->function_lib->get_user_level();
        $level = isset($user_sess['level']) ? $user_sess['level'] : "";
        $id_user = isset($user_sess['id_user']) ? $user_sess['id_user'] : "";

        $crud->set_theme('adminlte');
        $crud->set_table('aset');        
        $crud->set_subject('Aset Tanah');
        $crud->where("aset.status_aset != 'deleted'");
        $crud->where("aset.status_verifikasi_aset = 'sedang_diverifikasi'");
        $crud->set_language('indonesian');
        
        
        $crud->columns("nama_aset","galeri","kode_barang","register","kecamatan","desa","jenis_hak","tanggal_sertifikat","nomor_sertifikat","penggunaan","asal_perolehan","harga_perolehan","keterangan","latitude","longitude","status_aset","status_verifikasi_aset");
        $crud->unset_columns("created_by","created_by_id");
        // $crud->callback_column('jumlah_modal_usaha',array($this,'set_number_format_with_rp'));        
        // $crud->field_type('jumlah_modal_usaha','integer');
        // $crud->field_type('tgl_modal_usaha','datetime');
        // $crud->unset_add_fields('status_modal_usaha','modal_input_by');        
        // $crud->unset_edit();        
        // $crud->unset_texteditor(array('deskripsi_paket','full_text'));                
        // $crud->required_fields('jumlah_modal_usaha','catatan_modal_usaha'); 


        $crud->display_as('id_opd_aset','OPD')
             ->display_as('galeri','Galeri')             
             ->display_as('id_kecamatan','Kecamatan')
             ->display_as('id_desa','Desa')
             ->display_as('tgl_berita','Tanggal')             
             ->display_as('created_datetime','Tanggal Data');

        $crud->callback_column('galeri',array($this,'getGaleriUrl'));
        // mengurangi beban load desa
        $action = $crud->getState();
        $where_desa = null;
        if (!empty($action) AND $action=="add") {
            $where_desa= "id_kecamatan<10";
        }else if(!empty($action) AND $action=="edit"){
            $id = $this->uri->segment(4,0);            
            $asetArr = $this->function_lib->get_row_select_by('id_kecamatan,id_desa','aset','id_aset='.$this->db->escape($id).'');
            $id_kecamatan = isset($asetArr['id_kecamatan']) ? $asetArr['id_kecamatan'] : 0;
            $id_desa = isset($asetArr['id_desa']) ? $asetArr['id_desa'] : 0;
            $where_desa = 'id_kecamatan ="'.$id_kecamatan.'"';
        }
        
        // pertimbangkan ttg performa karena load relasi saat di list
            $crud->set_relation('id_kecamatan','kecamatan','nama', ' id_kabupaten = "3305"');
            $crud->set_relation_dependency('id_kecamatan','kabupaten','id_kabupaten');
            $crud->set_relation('id_desa','desa','nama', $where_desa);        
            $crud->set_relation_dependency('id_desa','kecamatan','id_kecamatan');
        

        if ($level == "pengurus_barang") {
            $id_admin = $this->function_lib->get_one('id_admin_pengurus_barang', 'pengurus_barang','id_pengurus_barang='.$this->db->escape($id_user).'');
            $id_opd_admin = $this->function_lib->get_one('id_opd_admin', 'admin','id_admin='.$this->db->escape($id_admin).'');
            $crud->set_relation('id_opd_aset','admin','id_opd_admin,(SELECT label_opd FROM opd where id_opd=id_opd_admin)', 'status!="deleted" AND id_opd_admin='.$this->db->escape($id_opd_admin).'');
            $crud->unset_add();
            $crud->unset_delete();
            $crud->where('jea67d6ad.id_opd_admin', $id_opd_admin);
        }else if($level == "koordinator"){
            $id_admin = $this->function_lib->get_one('id_admin_koordinator', 'koordinator','id_koordinator='.$this->db->escape($id_user).'');
            $id_opd_admin = $this->function_lib->get_one('id_opd_admin', 'admin','id_admin='.$this->db->escape($id_admin).'');
            $crud->set_relation('id_opd_aset','admin','id_opd_admin,(SELECT label_opd FROM opd where id_opd=id_opd_admin)', 'status!="deleted" AND id_opd_admin='.$this->db->escape($id_opd_admin).'');
            $crud->unset_add();
            $crud->unset_delete();
            $crud->where('jea67d6ad.id_opd_admin', $id_opd_admin);
        }else if($level == "super_admin"){
            $crud->set_relation('id_opd_aset','admin','id_opd_admin,(SELECT label_opd FROM opd where id_opd=id_opd_admin)', 'status!="deleted" ');
        }


        $crud->callback_delete(array($this,'delete_data'));    
        $crud->unset_texteditor(array('catatan_modal_usaha','full_text'));
        // $crud->callback_after_insert(array($this,'set_user'));
        $crud->unset_texteditor(array('alamat','full_text'));
        $crud->unset_texteditor(array('penggunaan','full_text'));
        $crud->unset_texteditor(array('asal_perolehan','full_text'));
        $crud->unset_texteditor(array('asal_perolehan','full_text'));

        $crud->change_field_type('status_aset', 'dropdown', array('aktif' => 'Aktif','non_aktif' => 'Non Aktif', 'deleted' => 'Deleted'));
        $crud->change_field_type('status_verifikasi_aset', 'dropdown', array('valid' => 'Valid','tidak_valid' => 'Tidak Valid', 'sedang_diverifikasi' => 'Sedang Diverifikasi'));

        $data = $crud->render();
        $data->id_user = $id_user;
        $data->level = $level;
        $data->state_data = $crud->getState();
        $data->dataOpd = $this->Mopd->getAllOpd();
        
        if ($data->state_data == "list" OR $data->state_data == "success") {
            
        }
 
        $this->load->view('aset/index', $data, FALSE);

    }          
    function delete_data($primary_key){                
        $columnUpdate = array(
            'status_aset' => 'deleted'
        );
        $this->db->where('id_aset', $primary_key);
        return $this->db->update('aset', $columnUpdate);                
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
    public function import(){
        $this->load->model('Maset');

        
        if ($this->input->post('preview')) {
            $upload = $this->Maset->upload();
            if (isset($upload['status']) AND $upload['status'] == 200) {
                $filename = (isset($upload['data']['file_name'])) ? $upload['data']['file_name'] : "";
                redirect('aset/preview_import/'.$filename);
            }else{
                $msg = isset($upload['msg']) ? $upload['msg'] : "";
                redirect('aset/import?status=500&msg='.base64_encode($msg));
            }
            
        }
        $this->load->view('aset/import', null, FALSE);
    }
    public function preview_import($filename = ""){

        $this->load->model('Maset');
        $path = "./assets/excel/aset/";
        if (empty($filename)) {
            redirect('aset/import?status=500&msg='.base64_encode("File Excel kosong"));
            return;
        }else if(!file_exists($path.$filename)){
            redirect('aset/import?status=500&msg='.base64_encode("file import tidak ditemukan, silahkan upload ulang"));
            return;
        }
        if (trim($this->input->post('save')) == "1") {
            // simpan data excel ke database
            $save = $this->Maset->save_import($filename);
            $status = isset($save['status']) ? $save['status'] : 500;
            $msg = isset($save['msg']) ? $save['msg'] : 500;
            if ($status == 200) {
                redirect("aset/index?status=200&msg=".base64_encode($msg));
            }else{
                redirect("aset/preview_import/".$filename."?status=500&msg=".base64_encode($msg));
            }
        }
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        
        $reader = new PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        if (!empty($ext) && $ext == "xls") {
            $reader = new PhpOffice\PhpSpreadsheet\Reader\Xls();
        }
        $spreadsheet = $reader->setReadDataOnly(true)->setReadEmptyCells(false)->load($path.$filename); // Load file yang tadi diupload ke folder tmp
        
        $sheet = $spreadsheet->getActiveSheet()->toArray(null, false, false, true);
        $sheet = array_splice($sheet, 1);
        $insertData = array();
        foreach ($sheet as $key => $value) {
            if (isset($value['A']) && !empty($value['A']) && isset($value['B']) && !empty($value['B'])) {
                $insertData[$key] = $sheet[$key];
            }
        }
        
        $data['filename'] = $filename;
        
        $data['dataAset'] = $insertData;
        
        $this->load->view('aset/preview_import', $data, FALSE);
    }
}

/* End of file Modal_usaha.php */
/* Location: ./application/controllers/Modal_usaha.php */