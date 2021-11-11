<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Aset extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		//Do your magic here
		$this->function_lib->cek_auth(array("super_admin","admin","koordinator","pengurus_barang"));
		$this->load->library(array('grocery_CRUD','ajax_grocery_crud'));   
        $this->load->model('Mpemanfaatan');
        $this->load->model('Msaran_pemanfaatan');
        $this->load->model('Mpemanfaatan_aset');
        $this->load->model('Msaran_pemanfaatan_aset');
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
        
        $crud->columns("id_opd_aset","nama_aset","galeri","pemanfaatan","saran_pemanfaatan","kode_barang","register","kecamatan","desa","jenis_hak","tanggal_sertifikat","nomor_sertifikat","penggunaan","asal_perolehan","harga_perolehan","keterangan","latitude","longitude","status_aset","status_verifikasi_aset");
        $crud->unset_columns("created_by","created_by_id");
        $crud->unset_fields("created_by","created_by_id","created_datetime");
        
        // $crud->field_type('jumlah_modal_usaha','integer');
        // $crud->field_type('tgl_modal_usaha','datetime');

        $crud->set_export_custom();
        $crud->set_url_export_custom(base_url('aset/export_custom'));


        $crud->display_as('id_opd_aset','OPD')
             ->display_as('galeri','Galeri')             
             ->display_as('id_kecamatan','Kecamatan')
             ->display_as('id_desa','Desa')
             ->display_as('tgl_berita','Tanggal')             
             ->display_as('pemanfaatan','Pemanfaatan')             
             ->display_as('saran_pemanfaatan','Saran Pemanfaatan')             
             ->display_as('created_datetime','Tanggal Data');

        $crud->callback_column('pemanfaatan',array($this,'getPemanfaatanByIdAset'));        
        $crud->callback_column('saran_pemanfaatan',array($this,'getSaranPemanfaatanByIdAset'));        
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
            $crud->set_relation('id_opd_aset','admin','id_opd_admin,(SELECT label_opd FROM opd where id_opd=id_opd_admin)', 'status!="deleted" AND (id_opd_admin='.$this->db->escape($id_opd_admin).' )');
            $crud->unset_add();
            $crud->unset_delete();
            $crud->where('(id_opd_admin = '.$this->db->escape($id_opd_admin).' OR id_opd_aset="0")');
        }else if($level == "koordinator"){
            $id_admin = $this->function_lib->get_one('id_admin_koordinator', 'koordinator','id_koordinator='.$this->db->escape($id_user).'');
            $id_opd_admin = $this->function_lib->get_one('id_opd_admin', 'admin','id_admin='.$this->db->escape($id_admin).'');
            $crud->set_relation('id_opd_aset','admin','id_opd_admin,(SELECT label_opd FROM opd where id_opd=id_opd_admin)', 'status!="deleted" AND (id_opd_admin='.$this->db->escape($id_opd_admin).' )');
            $crud->unset_add();
            $crud->unset_delete();
            $crud->where('(id_opd_admin = '.$this->db->escape($id_opd_admin).' OR id_opd_aset="0")');
        }else if($level == "super_admin"){
            $crud->set_relation('id_opd_aset','opd','label_opd', 'status_opd!="deleted" ');
        }else if($level == "admin"){
            $id_admin = $id_user;
            $id_opd_admin = $this->function_lib->get_one('id_opd_admin', 'admin','id_admin='.$this->db->escape($id_admin).'');
            $crud->set_relation('id_opd_aset','admin','id_opd_admin,(SELECT label_opd FROM opd where id_opd=id_opd_admin)', 'status!="deleted" AND (id_opd_admin='.$this->db->escape($id_opd_admin).' )');
            $crud->unset_add();
            $crud->unset_delete();
            $crud->where('(id_opd_admin = '.$this->db->escape($id_opd_admin).' OR id_opd_aset="0")');
            
        }


        $crud->callback_delete(array($this,'delete_data'));    
        // $crud->unset_texteditor(array('catatan_modal_usaha','full_text'));
        // $crud->callback_after_insert(array($this,'set_user'));
        $crud->callback_after_update(array($this,'setPemanfaatanAfterUpdate'));
        $crud->callback_after_insert(array($this,'setPemanfaatanAfterInsert'));
        $crud->unset_texteditor(array('alamat','full_text'));
        $crud->unset_texteditor(array('penggunaan','full_text'));
        $crud->unset_texteditor(array('asal_perolehan','full_text'));
        $crud->unset_texteditor(array('asal_perolehan','full_text'));

        $crud->change_field_type('latitude', 'integer');
        $crud->change_field_type('longitude', 'integer');
        $crud->change_field_type('tahun_perolehan', 'integer');
        $crud->change_field_type('harga_perolehan', 'integer');

        $crud->required_fields('status_aset'); 

        $crud->change_field_type('status_aset', 'dropdown', array('idle' => 'Idle','non_idle' => 'Non Idle'));
        $crud->change_field_type('status_verifikasi_aset', 'dropdown', array('valid' => 'Valid','tidak_valid' => 'Tidak Valid', 'sedang_diverifikasi' => 'Sedang Diverifikasi'));

        $data = $crud->render();
        $data->id_user = $id_user;
        $data->level = $level;
        $data->state_data = $crud->getState();
        $data->dataOpd = $this->Mopd->getAllOpd();

        // tambahan fitur pemanfaatan
        $data->dataPemanfaatan = array();
        $data->selectedDataPemanfaatan = array();
        $data->dataSaranPemanfaatan = array();
        $data->selectedDataSaranPemanfaatan = array();
        if (!(empty($action)) && ($action == "add" || $action == "edit")) {
            // pemanfaatan
            
            $data->dataPemanfaatan =  $this->Mpemanfaatan->getAllPemanfaatan();
            // saran pemanfaatan
            $data->dataSaranPemanfaatan =  $this->Msaran_pemanfaatan->getAllSaranPemanfaatan();
            
            if ($action == "edit") {
                $state_info = $crud->getStateInfo();
                $pk =  $state_info->primary_key;
                
                $data->selectedDataPemanfaatan = $this->Mpemanfaatan_aset->getAllPemanfaatanAsetById($pk);
                
                $data->selectedDataSaranPemanfaatan = $this->Msaran_pemanfaatan_aset->getAllSaranPemanfaatanAsetById($pk);
            }
        }
        
        if ($data->state_data == "list" OR $data->state_data == "success") {
            
        }
 
        $this->load->view('aset/index', $data, FALSE);

    }   
    function setPemanfaatanAfterUpdate($post_array,$primary_key) {
        // hapus semua data relasi pemanfaatan dengan primary key = $primary key
        $this->Mpemanfaatan_aset->deletePemanfaatanTanahByIdAset($primary_key);
        // insert ulang pemanfaatan yg dipilih ke table pemanfaatan tanah
        $dataPemanfaatan = (isset($post_array['pemanfaatan'])) ? $post_array['pemanfaatan']  : array();
        if (!empty($dataPemanfaatan)) {
            $this->Mpemanfaatan_aset->insertDataPemanfaatanAset($dataPemanfaatan, $primary_key);
        }
        // SARAN PEMANFAATAN
        // hapus semua data relasi pemanfaatan dengan primary key = $primary key
        $this->Msaran_pemanfaatan_aset->deleteSaranPemanfaatanTanahByIdAset($primary_key);
        // insert ulang pemanfaatan yg dipilih ke table pemanfaatan tanah
        $dataSaranPemanfaatan = (isset($post_array['saran_pemanfaatan'])) ? $post_array['saran_pemanfaatan']  : array();
        if (!empty($dataSaranPemanfaatan)) {
            $this->Msaran_pemanfaatan_aset->insertDataSaranPemanfaatanAset($dataSaranPemanfaatan, $primary_key);
        }
        return true;
    }
    function setPemanfaatanAfterInsert($post_array,$primary_key) {
        // hapus semua data relasi pemanfaatan dengan primary key = $primary key
        $this->Mpemanfaatan_aset->deletePemanfaatanTanahByIdAset($primary_key);
        // insert ulang pemanfaatan yg dipilih ke table pemanfaatan tanah
        $dataPemanfaatan = (isset($post_array['pemanfaatan'])) ? $post_array['pemanfaatan']  : array();
        if (!empty($dataPemanfaatan)) {
            $this->Mpemanfaatan_aset->insertDataPemanfaatanAset($dataPemanfaatan, $primary_key);
        }
        // SARAN PEMANFAATAN
        // hapus semua data relasi pemanfaatan dengan primary key = $primary key
        $this->Msaran_pemanfaatan_aset->deleteSaranPemanfaatanTanahByIdAset($primary_key);
        // insert ulang pemanfaatan yg dipilih ke table pemanfaatan tanah
        $dataSaranPemanfaatan = (isset($post_array['saran_pemanfaatan'])) ? $post_array['saran_pemanfaatan']  : array();
        if (!empty($dataSaranPemanfaatan)) {
            $this->Msaran_pemanfaatan_aset->insertDataSaranPemanfaatanAset($dataSaranPemanfaatan, $primary_key);
        }
        return true;
    }
    public function getGaleriUrl($value, $row){                      
        return '<a class="btn btn-info" href="'.base_url("foto_aset/index/".$row->id_aset).'" ><i class="fa fa-eye"></i> Lihat</a>';
    }
    public function getPemanfaatanByIdAset($value, $row){                      
        return "<b>".$this->Mpemanfaatan_aset->getPemanfaatanByIdAset($row->id_aset)."</b>"."<br><a class='btn btn-info btn-sm' href='".base_url('pemanfaatan/index?id_aset='.$row->id_aset.'')."'><i class='fa fa-eye'></i> LIHAT</a>";
        // return '<a class="btn btn-info" href="'.base_url("foto_aset/index/".$row->id_aset).'" ><i class="fa fa-eye"></i> Lihat</a>';
    }
    public function getSaranPemanfaatanByIdAset($value, $row){            
    
        if (isset($row->status_aset) && trim($row->status_aset) == "idle") {
            return "<b>".$this->Msaran_pemanfaatan_aset->getSaranPemanfaatanByIdAset($row->id_aset)."</b>";
        }else{
            return "<b>NON IDLE ASET</b>";
        }
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
        $crud->where("aset.status_verifikasi_aset = 'sedang_diverifikasi'");
        $crud->set_language('indonesian');
        
        $crud->columns("id_opd_aset","nama_aset","galeri","pemanfaatan","saran_pemanfaatan","kode_barang","register","kecamatan","desa","jenis_hak","tanggal_sertifikat","nomor_sertifikat","penggunaan","asal_perolehan","harga_perolehan","keterangan","latitude","longitude","status_aset","status_verifikasi_aset");
        $crud->unset_columns("created_by","created_by_id");
        $crud->unset_fields("created_by","created_by_id","created_datetime");
        
        // $crud->field_type('jumlah_modal_usaha','integer');
        // $crud->field_type('tgl_modal_usaha','datetime');

        $crud->set_export_custom();
        $crud->set_url_export_custom(base_url('aset/export_custom'));


        $crud->display_as('id_opd_aset','OPD')
             ->display_as('galeri','Galeri')             
             ->display_as('id_kecamatan','Kecamatan')
             ->display_as('id_desa','Desa')
             ->display_as('tgl_berita','Tanggal')             
             ->display_as('pemanfaatan','Pemanfaatan')             
             ->display_as('saran_pemanfaatan','Saran Pemanfaatan')             
             ->display_as('created_datetime','Tanggal Data');

        $crud->callback_column('pemanfaatan',array($this,'getPemanfaatanByIdAset'));        
        $crud->callback_column('saran_pemanfaatan',array($this,'getSaranPemanfaatanByIdAset'));        
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
            $crud->set_relation('id_opd_aset','admin','id_opd_admin,(SELECT label_opd FROM opd where id_opd=id_opd_admin)', 'status!="deleted" AND (id_opd_admin='.$this->db->escape($id_opd_admin).' )');
            $crud->unset_add();
            $crud->unset_delete();
            $crud->where('(id_opd_admin = '.$this->db->escape($id_opd_admin).' OR id_opd_aset="0")');
        }else if($level == "koordinator"){
            $id_admin = $this->function_lib->get_one('id_admin_koordinator', 'koordinator','id_koordinator='.$this->db->escape($id_user).'');
            $id_opd_admin = $this->function_lib->get_one('id_opd_admin', 'admin','id_admin='.$this->db->escape($id_admin).'');
            $crud->set_relation('id_opd_aset','admin','id_opd_admin,(SELECT label_opd FROM opd where id_opd=id_opd_admin)', 'status!="deleted" AND (id_opd_admin='.$this->db->escape($id_opd_admin).' )');
            $crud->unset_add();
            $crud->unset_delete();
            $crud->where('(id_opd_admin = '.$this->db->escape($id_opd_admin).' OR id_opd_aset="0")');
        }else if($level == "super_admin"){
            $crud->set_relation('id_opd_aset','opd','label_opd', 'status_opd!="deleted" ');
        }else if($level == "admin"){
            $id_admin = $id_user;
            $id_opd_admin = $this->function_lib->get_one('id_opd_admin', 'admin','id_admin='.$this->db->escape($id_admin).'');
            $crud->set_relation('id_opd_aset','admin','id_opd_admin,(SELECT label_opd FROM opd where id_opd=id_opd_admin)', 'status!="deleted" AND (id_opd_admin='.$this->db->escape($id_opd_admin).' )');
            $crud->unset_add();
            $crud->unset_delete();
            $crud->where('(id_opd_admin = '.$this->db->escape($id_opd_admin).' OR id_opd_aset="0")');
            
        }


        $crud->callback_delete(array($this,'delete_data'));    
        // $crud->unset_texteditor(array('catatan_modal_usaha','full_text'));
        // $crud->callback_after_insert(array($this,'set_user'));
        $crud->callback_after_update(array($this,'setPemanfaatanAfterUpdate'));
        $crud->callback_after_insert(array($this,'setPemanfaatanAfterInsert'));
        $crud->unset_texteditor(array('alamat','full_text'));
        $crud->unset_texteditor(array('penggunaan','full_text'));
        $crud->unset_texteditor(array('asal_perolehan','full_text'));
        $crud->unset_texteditor(array('asal_perolehan','full_text'));

        $crud->required_fields('status_aset'); 

        $crud->change_field_type('status_aset', 'dropdown', array('idle' => 'Idle','non_idle' => 'Non Idle'));
        $crud->change_field_type('latitude', 'integer');
        $crud->change_field_type('longitude', 'integer');
        $crud->change_field_type('tahun_perolehan', 'integer');
        $crud->change_field_type('harga_perolehan', 'integer');
        $crud->change_field_type('status_verifikasi_aset', 'dropdown', array('valid' => 'Valid','tidak_valid' => 'Tidak Valid', 'sedang_diverifikasi' => 'Sedang Diverifikasi'));

        $data = $crud->render();
        $data->id_user = $id_user;
        $data->level = $level;
        $data->state_data = $crud->getState();
        $data->dataOpd = $this->Mopd->getAllOpd();

        // tambahan fitur pemanfaatan
        $data->dataPemanfaatan = array();
        $data->selectedDataPemanfaatan = array();
        $data->dataSaranPemanfaatan = array();
        $data->selectedDataSaranPemanfaatan = array();
        if (!(empty($action)) && ($action == "add" || $action == "edit")) {
            // pemanfaatan
            
            $data->dataPemanfaatan =  $this->Mpemanfaatan->getAllPemanfaatan();
            // saran pemanfaatan
            $data->dataSaranPemanfaatan =  $this->Msaran_pemanfaatan->getAllSaranPemanfaatan();
            
            if ($action == "edit") {
                $state_info = $crud->getStateInfo();
                $pk =  $state_info->primary_key;
                
                $data->selectedDataPemanfaatan = $this->Mpemanfaatan_aset->getAllPemanfaatanAsetById($pk);
                
                $data->selectedDataSaranPemanfaatan = $this->Msaran_pemanfaatan_aset->getAllSaranPemanfaatanAsetById($pk);
            }
        }
        
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
    public function export_custom(){
        header('Content-Type application/json');
        $id = $this->input->post('id');
        // use PhpOffice\PhpSpreadsheet\Spreadsheet;
        // use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
        $stringIdAset = implode("','",$id);
         
        
        $spreadsheet = new PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'OPD');
        $sheet->setCellValue('B1', 'Nama Aset');
        $sheet->setCellValue('C1', 'Galeri');
        $sheet->setCellValue('D1', 'Pemanfaatan');
        $sheet->setCellValue('E1', 'Saran Pemanfaatan');
        $sheet->setCellValue('F1', 'Kode barang');
        $sheet->setCellValue('G1', 'Register');
        $sheet->setCellValue('H1', 'Kecamatan');
        $sheet->setCellValue('I1', 'Desa');
        $sheet->setCellValue('J1', 'Jenis Hak');
        $sheet->setCellValue('K1', 'Tanggal sertifikat');
        $sheet->setCellValue('L1', 'Nomor sertifikat');
        $sheet->setCellValue('M1', 'Penggunaan');
        $sheet->setCellValue('N1', 'Asal Perolehan');
        $sheet->setCellValue('O1', 'Harga perolehan');
        $sheet->setCellValue('P1', 'Keterangan');
        $sheet->setCellValue('Q1', 'Latitude');
        $sheet->setCellValue('R1', 'Longitude');
        $sheet->setCellValue('S1', 'Status aset');
        $sheet->setCellValue('T1', 'Status verifikasi');
         
        // SELECT `aset`.*, j7dd2b894.nama AS s7dd2b894, j0214e037.nama AS s0214e037, jea67d6ad.id_opd_admin, (SELECT label_opd FROM opd where id_opd=id_opd_admin) AS sea67d6ad FROM `aset` LEFT JOIN `kecamatan` as `j7dd2b894` ON `j7dd2b894`.`id` = `aset`.`id_kecamatan` LEFT JOIN `desa` as `j0214e037` ON `j0214e037`.`id` = `aset`.`id_desa` LEFT JOIN `admin` as `jea67d6ad` ON `jea67d6ad`.`id_admin` = `aset`.`id_opd_aset` WHERE `aset`.`status_aset` != 'deleted' AND `galeri` LIKE '%1%' ESCAPE '!' ORDER BY `sea67d6ad` DESC 

        $query = $this->db->query("SELECT `aset`.*, j7dd2b894.nama AS s7dd2b894, j0214e037.nama AS s0214e037, jea67d6ad.id_opd_admin, (SELECT label_opd FROM opd where id_opd=id_opd_admin) AS sea67d6ad FROM `aset` LEFT JOIN `kecamatan` as `j7dd2b894` ON `j7dd2b894`.`id` = `aset`.`id_kecamatan` LEFT JOIN `desa` as `j0214e037` ON `j0214e037`.`id` = `aset`.`id_desa` LEFT JOIN `admin` as `jea67d6ad` ON `jea67d6ad`.`id_admin` = `aset`.`id_opd_aset` WHERE `aset`.`status_aset` != 'deleted' AND id_aset IN ('".$stringIdAset."') ORDER BY `sea67d6ad` DESC ");
         $data = $query->result_array();
        $i = 2;
        $no = 1;
        foreach ($data as $key => $value) {
            $sheet->setCellValue('A'.$i, isset($value['sea67d6ad']) ? $value['sea67d6ad'] : "");
            $sheet->setCellValue('B'.$i, isset($value['nama_aset']) ? $value['nama_aset'] : "");
            $sheet->setCellValue('C'.$i, "");
            $sheet->setCellValue('D'.$i, "");   
            $sheet->setCellValue('E'.$i, "");   
            $sheet->setCellValue('F'.$i, isset($value['kode_barang']) ? $value['kode_barang'] : "");   
            $sheet->setCellValue('G'.$i, isset($value['register']) ? $value['register'] : "");   
            $sheet->setCellValue('H'.$i, isset($value['s7dd2b894']) ? $value['s7dd2b894'] : "");   
            $sheet->setCellValue('I'.$i, isset($value['s0214e037']) ? $value['s0214e037'] : "");   
            $sheet->setCellValue('J'.$i, isset($value['jenis_hak']) ? $value['jenis_hak'] : "");   
            $sheet->setCellValue('K'.$i, isset($value['tanggal_sertifikat']) ? $value['tanggal_sertifikat'] : "");   
            $sheet->setCellValue('L'.$i, isset($value['nomor_sertifikat']) ? $value['nomor_sertifikat'] : "");   
            $sheet->setCellValue('M'.$i, isset($value['penggungaan']) ? $value['penggungaan'] : "");   
            $sheet->setCellValue('N'.$i, isset($value['asal_perolehan']) ? $value['asal_perolehan'] : "");   
            $sheet->setCellValue('O'.$i, isset($value['harga_perolehan']) ? $value['harga_perolehan'] : "");   
            $sheet->setCellValue('P'.$i, isset($value['keterangan']) ? $value['keterangan'] : "");   
            $sheet->setCellValue('Q'.$i, isset($value['latitude']) ? $value['latitude'] : "");   
            $sheet->setCellValue('R'.$i, isset($value['longitude']) ? $value['longitude'] : "");   
            $sheet->setCellValue('S'.$i, isset($value['status_aset']) ? $value['status_aset'] : "");   
            $sheet->setCellValue('T'.$i, isset($value['status_verifikasi_aset']) ? $value['status_verifikasi_aset'] : "");   
            $i++;
        }
         
        $styleArray = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],
                ];
        $i = $i - 1;
        $sheet->getStyle('A1:T'.$i)->applyFromArray($styleArray);
         
        $writer = new PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $lokasi = "/assets/excel/export.xlsx";
        $writer->save('./'.$lokasi);
        $response = array(
            "status" => 200,
            "msg" => "OK",
            "url" => base_url($lokasi)
        );
        echo(json_encode($response));

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