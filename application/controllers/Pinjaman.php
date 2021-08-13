<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pinjaman extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		//Do your magic here
		$this->function_lib->cek_auth(array("admin","super_admin","owner","kasir"));
		$this->load->library(array('grocery_CRUD','ajax_grocery_crud'));   
	}		
 
    public function index() {
        $crud = new Ajax_grocery_CRUD();
        $crud->set_theme('adminlte');
        $crud->set_table('pinjaman');

        $user_sess = $this->function_lib->get_user_level();
        $level = isset($user_sess['level']) ? $user_sess['level'] : "";
        $id_user = isset($user_sess['id_user']) ? $user_sess['id_user'] : "";
        $crud->order_by('tgl_pinjaman','desc');
        if ($level == "owner") {            
            $crud->where("pinjaman.id_owner",$id_user);
            $crud->field_type('id_owner', 'hidden', $id_user);            
            if($crud->getState() != 'add' AND $crud->getState() != 'list') {
                $crud->set_relation('id_owner','owner','nama_koperasi');
                if ($crud->getState() == "read" OR $crud->getState() == "edit") {
                    $stateInfo = (array) $crud->getStateInfo();
                    $pk = isset($stateInfo['primary_key']) ? $stateInfo['primary_key'] : 0;
                    $id_pinjaman = $this->function_lib->get_one('id_pinjaman','pinjaman','id_pinjaman="'.$pk.'" AND id_owner="'.$id_user.'"');
                    if (empty($id_pinjaman)) {
                        redirect(base_url().'pinjaman/index/');
                        exit();
                    }
                }
                
            }
        }else if($level == "kasir"){
            $id_user = $this->function_lib->get_one('id_owner','kasir','id_kasir='.$this->db->escape($id_user).'');
            $crud->where("pinjaman.id_owner",$id_user);
            $crud->field_type('id_owner', 'hidden', $id_user);            
            if($crud->getState() != 'add' AND $crud->getState() != 'list') {
                $crud->set_relation('id_owner','owner','nama_koperasi');
                if ($crud->getState() == "read" OR $crud->getState() == "edit") {
                    $stateInfo = (array) $crud->getStateInfo();
                    $pk = isset($stateInfo['primary_key']) ? $stateInfo['primary_key'] : 0;
                    $id_pinjaman = $this->function_lib->get_one('id_pinjaman','pinjaman','id_pinjaman="'.$pk.'" AND id_owner="'.$id_user.'"');
                    if (empty($id_pinjaman)) {
                        redirect(base_url().'pinjaman/index/');
                        exit();
                    }
                }
                
            }
        }else{
            $crud->set_relation('id_owner','owner','nama_koperasi');
        }
        $crud->where('status_pinjaman',"aktif");
        $crud->set_subject('Data Pinjaman');
        $crud->set_language('indonesian');
        $crud->columns('id_pinjaman','status_pinjaman','Laporan','id_nasabah','id_owner','id_kolektor','jumlah_pinjaman','jumlah_diterima','biaya_admin','biaya_simpanan','persentase_bunga','jumlah_pinjaman_setelah_bunga','angsuran','jumlah_terbayar','Kekurangan','input_oleh','tgl_pinjaman','tgl_terakhir_angsuran','angsuran_ke');        
        // set_relation( string $field_name , string  $related_table, string  $related_title_field  [, mixed $where [, string $order_by ] ] )
        // $crud->field_type('status_pinjaman','enum',array('aktif'=>"Aktif",'non_aktif'=>"non_aktif"));
        $crud->set_relation('id_nasabah','nasabah','username');
        $crud->set_relation_dependency('id_nasabah','id_kolektor','id_kolektor');        
        // $crud->field_type('id_owner', 'hidden', $id_user);
        $crud->set_relation('id_kolektor','kolektor','username');                                
        $crud->set_relation_dependency('id_kolektor','id_owner','id_owner');
        $crud->field_type('jumlah_pinjaman','integer');
        $crud->field_type('persentase_bunga','integer');
        $crud->field_type('jumlah_pinjaman_setelah_bunga','integer');
        $crud->field_type('jumlah_terbayar','integer');
        $crud->unset_texteditor(array('note','full_text'));        
        $crud->display_as('id_nasabah','Nasabah')
             ->display_as('id_owner','Koperasi')
             ->display_as('id_kolektor','Kolektor')
             ->display_as('jumlah_pinjaman','Jumlah Pinjaman (Rp.)')
             ->display_as('persentase_bunga','Bunga (%)')
             ->display_as('biaya_admin','Biaya Admin (Rp.)')
             ->display_as('biaya_simpanan','Biaya Simpanan (Rp.)')
             ->display_as('jumlah_pinjaman_setelah_bunga','Total (Rp.)')
             ->display_as('angsuran','Angsuran')
             ->display_as('jumlah_terbayar','Jml Terbayar (Rp.)')
             ->display_as('status_pinjaman','STATUS')
             ->display_as('tgl_pinjaman','Tgl. Pinjaman');   
        $crud->callback_column('jumlah_pinjaman_setelah_bunga',array($this,'_callback'));
        $crud->callback_column('Kekurangan',array($this,'get_kekurangan'));        
        $crud->callback_column('Laporan',array($this,'link_laporan'));        
        $crud->callback_column('angsuran',array($this,'angsuran'));        
        $crud->callback_column('biaya_simpanan',array($this,'biaya_simpanan'));        
        $crud->callback_column('biaya_admin',array($this,'biaya_admin'));        
        $crud->callback_column('jumlah_pinjaman',array($this,'set_number_format_with_rp'));        
        $crud->callback_column('jumlah_diterima',array($this,'set_number_format_with_rp'));        
         $crud->set_rules('jumlah_pinjaman','Jumlah Pinjaman', array(                            
            'required',
                array(
                    'jumlah_pinjaman',
                    function ($str) {
                        $id_nasabah = $this->input->post('id_nasabah',true);
                        $jumlah_kurang = (float) $this->function_lib->get_one('jumlah_pinjaman_setelah_bunga-jumlah_terbayar','pinjaman','id_nasabah="'.$id_nasabah.'" AND jumlah_terbayar<jumlah_pinjaman_setelah_bunga');                        
                        // jumlah pinjaman baru harus lebih besar dari jumlah pinjaman lama yg belum lunas
                        if ( floatval($str) < $jumlah_kurang) {
                            $this->form_validation->set_message('jumlah_pinjaman', 'Jumlah pinjaman harus lebih besar dari kekurangan pinjaman yang belum lunas.');
                            return false;
                        }  else{
                            return true;
                        }                      
                    }
                )
            ));
        $crud->callback_insert(array($this,'insert_new_data'));
        // $crud->callback_after_insert(array($this, 'after_insert_baru'));
        $crud->callback_after_update(array($this, 'update_data'));        
        $crud->callback_delete(array($this,'delete_data'));
        //  set rule untuk cek pinjaman berjalan di nasabah yg sama

        $this->form_validation->set_rules('fieldname', 'fieldlabel', 'trim|required|min_length[5]|max_length[12]');

        
        $crud->unset_add_fields(array('input_oleh','input_oleh_id','id_pinjaman','jumlah_pinjaman_setelah_bunga','jumlah_diterima','last_update','tgl_terakhir_angsuran','angsuran_ke','tgl_pinjaman','jumlah_terbayar','persentase_bunga','status_pinjaman','persentase_biaya_admin','persentase_biaya_simpanan','jumlah_perangsuran'));
        $crud->unset_edit_fields(array('input_oleh','input_oleh_id','id_pinjaman','jumlah_pinjaman_setelah_bunga','jumlah_diterima','last_update','tgl_terakhir_angsuran','persentase_biaya_admin','persentase_biaya_simpanan','jumlah_perangsuran','angsuran_ke'));        
        if($crud->getState() == 'add' || $crud->getState() == 'insert_validation') {
            $crud->required_fields('id_nasabah','id_owner','id_kolektor','jumlah_pinjaman','persentase_bunga','status_pinjaman','periode_angsuran','lama_angsuran');
        }else{
            $crud->required_fields('jumlah_pinjaman','status_pinjaman','periode_angsuran','lama_angsuran');
            $crud->field_type('persentase_bunga','readonly');
            $crud->field_type('jumlah_terbayar','readonly');
            $crud->field_type('id_nasabah','readonly');
            $crud->field_type('id_owner','readonly');
            $crud->field_type('id_kolektor','readonly');
        }
        $data = $crud->render();
        $data->id_user = $id_user;
        $data->level = $level;
        $data->state_data = $crud->getState();
        if ($data->state_data == "list" OR $data->state_data == "success") {
            if ($level == "owner") {
                $data->total_modal_usaha = $this->function_lib->get_one('sum(jumlah_modal_usaha)','modal_usaha','status_modal_usaha="aktif" AND id_owner="'.$this->db->escape_str($id_user).'"');
                $data->total_modal_simpanan = $this->function_lib->get_one('sum(jumlah_simpanan)','simpanan','status_simpanan="aktif" AND id_owner="'.$this->db->escape_str($id_user).'"');
                $data->total_modal = floatval($data->total_modal_usaha) + floatval($data->total_modal_simpanan);
                $data->total_pinjaman = $this->function_lib->get_one('sum(jumlah_pinjaman)','pinjaman','status_pinjaman="aktif" AND id_owner="'.$this->db->escape_str($id_user).'"');
                $data->ratio_modal_pinjaman = floatval($data->total_pinjaman) / floatval($data->total_modal);
            }else{
                $data->total_modal_usaha = $this->function_lib->get_one('sum(jumlah_modal_usaha)','modal_usaha','status_modal_usaha="aktif"');
                $data->total_modal_simpanan = $this->function_lib->get_one('sum(jumlah_simpanan)','simpanan','status_simpanan="aktif"');
                $data->total_modal = floatval($data->total_modal_usaha) + floatval($data->total_modal_simpanan);
                $data->total_pinjaman = $this->function_lib->get_one('sum(jumlah_pinjaman)','pinjaman','status_pinjaman="aktif"');
                $data->ratio_modal_pinjaman = floatval($data->total_pinjaman) / floatval($data->total_modal);
            }
        }
 
        $this->load->view('pinjaman/index', $data, FALSE);

    }
    public function lunas() {
        $crud = new Ajax_grocery_CRUD();
        $crud->set_theme('adminlte');
        $crud->set_table('pinjaman');

        $user_sess = $this->function_lib->get_user_level();
        $level = isset($user_sess['level']) ? $user_sess['level'] : "";
        $id_user = isset($user_sess['id_user']) ? $user_sess['id_user'] : "";
        $crud->order_by('tgl_pinjaman','desc');
       if ($level == "owner") {            
            $crud->where("pinjaman.id_owner",$id_user);
            $crud->field_type('id_owner', 'hidden', $id_user);            
            if($crud->getState() != 'add' AND $crud->getState() != 'list') {
                $crud->set_relation('id_owner','owner','nama_koperasi');
                if ($crud->getState() == "read" OR $crud->getState() == "edit") {
                    $stateInfo = (array) $crud->getStateInfo();
                    $pk = isset($stateInfo['primary_key']) ? $stateInfo['primary_key'] : 0;
                    $id_pinjaman = $this->function_lib->get_one('id_pinjaman','pinjaman','id_pinjaman="'.$pk.'" AND id_owner="'.$id_user.'"');
                    if (empty($id_pinjaman)) {
                        redirect(base_url().'pinjaman/lunas/');
                        exit();
                    }
                }
                
            }
        }else if($level == "kasir"){
            $id_user = $this->function_lib->get_one('id_owner','kasir','id_kasir='.$this->db->escape($id_user).'');
            $crud->where("pinjaman.id_owner",$id_user);
            $crud->field_type('id_owner', 'hidden', $id_user);            
            if($crud->getState() != 'add' AND $crud->getState() != 'list') {
                $crud->set_relation('id_owner','owner','nama_koperasi');
                if ($crud->getState() == "read" OR $crud->getState() == "edit") {
                    $stateInfo = (array) $crud->getStateInfo();
                    $pk = isset($stateInfo['primary_key']) ? $stateInfo['primary_key'] : 0;
                    $id_pinjaman = $this->function_lib->get_one('id_pinjaman','pinjaman','id_pinjaman="'.$pk.'" AND id_owner="'.$id_user.'"');
                    if (empty($id_pinjaman)) {
                        redirect(base_url().'pinjaman/index/');
                        exit();
                    }
                }
                
            }
        }else{
            $crud->set_relation('id_owner','owner','nama_koperasi');
        }                
        $crud->where('status_pinjaman',"lunas");
        $crud->set_subject('Data Pinjaman');
        $crud->set_language('indonesian');
        $crud->columns('id_pinjaman','status_pinjaman','Laporan','id_nasabah','id_owner','id_kolektor','jumlah_pinjaman','jumlah_diterima','biaya_admin','biaya_simpanan','persentase_bunga','jumlah_pinjaman_setelah_bunga','angsuran','jumlah_terbayar','Kekurangan','input_oleh','tgl_pinjaman','tgl_terakhir_angsuran','angsuran_ke');        
        // set_relation( string $field_name , string  $related_table, string  $related_title_field  [, mixed $where [, string $order_by ] ] )
        // $crud->field_type('status_pinjaman','enum',array('aktif'=>"Aktif",'non_aktif'=>"non_aktif"));
        $crud->set_relation('id_nasabah','nasabah','username');
        $crud->set_relation_dependency('id_nasabah','id_kolektor','id_kolektor');        
        $crud->set_relation('id_kolektor','kolektor','username');                                
        $crud->set_relation_dependency('id_kolektor','id_owner','id_owner');
        $crud->field_type('jumlah_pinjaman','integer');
        $crud->field_type('persentase_bunga','integer');
        $crud->field_type('jumlah_pinjaman_setelah_bunga','integer');
        $crud->field_type('jumlah_terbayar','integer');
        $crud->unset_texteditor(array('note','full_text'));        
        $crud->display_as('id_nasabah','Nasabah')
             ->display_as('id_owner','Koperasi')
             ->display_as('id_kolektor','Kolektor')
             ->display_as('jumlah_pinjaman','Jumlah Pinjaman (Rp.)')
             ->display_as('persentase_bunga','Bunga (%)')
             ->display_as('biaya_admin','Biaya Admin (Rp.)')
             ->display_as('biaya_simpanan','Biaya Simpanan (Rp.)')
             ->display_as('jumlah_pinjaman_setelah_bunga','Total (Rp.)')
             ->display_as('angsuran','Angsuran')
             ->display_as('jumlah_terbayar','Jml Terbayar (Rp.)')
             ->display_as('status_pinjaman','STATUS')
             ->display_as('tgl_pinjaman','Tgl. Pinjaman');   
        $crud->callback_column('jumlah_pinjaman_setelah_bunga',array($this,'_callback'));
        $crud->callback_column('Kekurangan',array($this,'get_kekurangan'));        
        $crud->callback_column('Laporan',array($this,'link_laporan'));        
        $crud->callback_column('angsuran',array($this,'angsuran'));        
        $crud->callback_column('biaya_simpanan',array($this,'biaya_simpanan'));        
        $crud->callback_column('biaya_admin',array($this,'biaya_admin'));        
        $crud->callback_column('jumlah_pinjaman',array($this,'set_number_format_with_rp'));        
        $crud->callback_column('jumlah_diterima',array($this,'set_number_format_with_rp'));        
         $crud->set_rules('jumlah_pinjaman','Jumlah Pinjaman', array(                            
            'required',
                array(
                    'jumlah_pinjaman',
                    function ($str) {
                        $id_nasabah = $this->input->post('id_nasabah',true);
                        $jumlah_kurang = (float) $this->function_lib->get_one('jumlah_pinjaman_setelah_bunga-jumlah_terbayar','pinjaman','id_nasabah="'.$id_nasabah.'" AND jumlah_terbayar<jumlah_pinjaman_setelah_bunga');                        
                        // jumlah pinjaman baru harus lebih besar dari jumlah pinjaman lama yg belum lunas
                        if ( floatval($str) < $jumlah_kurang) {
                            $this->form_validation->set_message('jumlah_pinjaman', 'Jumlah pinjaman harus lebih besar dari kekurangan pinjaman yang belum lunas.');
                            return false;
                        }  else{
                            return true;
                        }                      
                    }
                )
            ));
        $crud->callback_insert(array($this,'insert_new_data'));
        // $crud->callback_after_insert(array($this, 'after_insert_baru'));
        $crud->callback_after_update(array($this, 'update_data'));        
        $crud->callback_delete(array($this,'delete_data'));
        //  set rule untuk cek pinjaman berjalan di nasabah yg sama

        $this->form_validation->set_rules('fieldname', 'fieldlabel', 'trim|required|min_length[5]|max_length[12]');

        
        $crud->unset_add_fields(array('input_oleh','input_oleh_id','id_pinjaman','jumlah_pinjaman_setelah_bunga','jumlah_diterima','last_update','tgl_terakhir_angsuran','angsuran_ke','tgl_pinjaman','jumlah_terbayar','persentase_bunga','status_pinjaman','persentase_biaya_admin','persentase_biaya_simpanan','jumlah_perangsuran'));
        $crud->unset_edit_fields(array('input_oleh','input_oleh_id','id_pinjaman','jumlah_pinjaman_setelah_bunga','last_update','tgl_terakhir_angsuran','persentase_biaya_admin','persentase_biaya_simpanan','jumlah_perangsuran','angsuran_ke'));        
        if($crud->getState() == 'add' || $crud->getState() == 'insert_validation') {
            $crud->required_fields('id_nasabah','id_owner','id_kolektor','jumlah_pinjaman','persentase_bunga','status_pinjaman','periode_angsuran','lama_angsuran');
        }else{
            $crud->required_fields('jumlah_pinjaman','status_pinjaman','periode_angsuran','lama_angsuran');
            $crud->field_type('persentase_bunga','readonly');
            $crud->field_type('jumlah_terbayar','readonly');
            $crud->field_type('id_nasabah','readonly');
            $crud->field_type('id_owner','readonly');
            $crud->field_type('id_kolektor','readonly');
        }
        $data = $crud->render();
        $data->id_user = $id_user;
        $data->level = $level;
        $data->state_data = $crud->getState();
        if ($data->state_data == "list" OR $data->state_data == "success") {
            if ($level == "owner") {
                $data->total_modal_usaha = $this->function_lib->get_one('sum(jumlah_modal_usaha)','modal_usaha','status_modal_usaha="aktif" AND id_owner="'.$this->db->escape_str($id_user).'"');
                $data->total_modal_simpanan = $this->function_lib->get_one('sum(jumlah_simpanan)','simpanan','status_simpanan="aktif" AND id_owner="'.$this->db->escape_str($id_user).'"');
                $data->total_modal = floatval($data->total_modal_usaha) + floatval($data->total_modal_simpanan);
                $data->total_pinjaman = $this->function_lib->get_one('sum(jumlah_pinjaman)','pinjaman','status_pinjaman="aktif" AND id_owner="'.$this->db->escape_str($id_user).'"');
                $data->ratio_modal_pinjaman = floatval($data->total_pinjaman) / floatval($data->total_modal);
            }else{
                $data->total_modal_usaha = $this->function_lib->get_one('sum(jumlah_modal_usaha)','modal_usaha','status_modal_usaha="aktif"');
                $data->total_modal_simpanan = $this->function_lib->get_one('sum(jumlah_simpanan)','simpanan','status_simpanan="aktif"');
                $data->total_modal = floatval($data->total_modal_usaha) + floatval($data->total_modal_simpanan);
                $data->total_pinjaman = $this->function_lib->get_one('sum(jumlah_pinjaman)','pinjaman','status_pinjaman="aktif"');
                $data->ratio_modal_pinjaman = floatval($data->total_pinjaman) / floatval($data->total_modal);
            }
        }
 
        $this->load->view('pinjaman/index', $data, FALSE);

    }
    public function insert_new_data($post_array){
        // cek apakah nasabah punya data pinjaman yg belum lunas. Jika masih, pinjaman baru akan dipotong untuk menutupi pinjaman lama
        $user_sess = $this->function_lib->get_user_level();
        $username_sess = isset($user_sess['username']) ? $user_sess['username'] : "";
        $level_sess = isset($user_sess['level']) ? $user_sess['level'] : "";
        $oleh = $username_sess. " - ". $level_sess;
        $oleh_id = isset($user_sess['id_user']) ? $user_sess['id_user'] : 0;        
        $tgl_sekarang = date("Y-m-d H:i:s");
        $id_nasabah = isset($post_array['id_nasabah']) ? $post_array['id_nasabah'] : "0";
        $jumlah_pinjaman = isset($post_array['jumlah_pinjaman']) ? $post_array['jumlah_pinjaman'] : "0";
        $id_pinjaman = (float) $this->function_lib->get_one('id_pinjaman','pinjaman','id_nasabah="'.$id_nasabah.'" AND jumlah_terbayar<jumlah_pinjaman_setelah_bunga');  
        $jumlah_kurang = (float) $this->function_lib->get_one('jumlah_pinjaman_setelah_bunga-jumlah_terbayar','pinjaman','id_pinjaman="'.$id_pinjaman.'"');         
        $post_array['jumlah_diterima'] = (float) ($jumlah_pinjaman - $jumlah_kurang);
        // insert data ke tabel pinjaman
        $insert_pinjaman = $this->db->insert('pinjaman', $post_array);
        $insert_id = $this->db->insert_id();

        if (!empty($id_pinjaman)) {
            $jumlah_angsuran_awal = $this->function_lib->get_one('count(id_riwayat)','riwayat_pinjaman','id_pinjaman="'.$id_pinjaman.'"');
            $jumlah_angsuran = $jumlah_angsuran_awal+1;
            // tambahkan riwayat angsuran yg diambil dari potongan pinjaman baru
            $jumlah_kurang = (float) $this->function_lib->get_one('jumlah_pinjaman_setelah_bunga-jumlah_terbayar','pinjaman','id_pinjaman="'.$id_pinjaman.'"');                                    
            $columnInsert = array(
                "id_pinjaman" => $id_pinjaman,
                "angsuran_ke" => $jumlah_angsuran_awal+1,
                "jumlah_riwayat_pembayaran" => $jumlah_kurang,
                "input_oleh" => $oleh,
                "input_oleh_id" => $oleh_id,
                "tgl_riwayat_pinjaman" => $tgl_sekarang,
                "keterangan_riwayat" => "Angsuran diambil dari potongan pinjaman baru dengan ID Pinjaman : ".$insert_id." Sejumlah : Rp.". number_format($jumlah_pinjaman,"2",",",".")
            );
            $this->db->insert('riwayat_pinjaman', $columnInsert);
            // update status dan data di pinjaman lama
            $columnUpdate = array(                
                "status_pinjaman" => "lunas",
                "angsuran_ke" => (int) $jumlah_angsuran,
                "last_update" => $tgl_sekarang,       
                "tgl_terakhir_angsuran" => $tgl_sekarang,
            );
            $this->db->set('jumlah_terbayar', 'jumlah_pinjaman_setelah_bunga', false);
            $this->db->where('id_pinjaman', $id_pinjaman);
            $this->db->update('pinjaman', $columnUpdate);  
        }

        // bug callback after insert
        $bunga_pinjaman = $this->function_lib->get_one('bunga_pinjaman','owner','id_owner IN (SELECT id_owner FROM pinjaman WHERE id_pinjaman ="'.$insert_id.'")');
        $id_nasabah = $this->function_lib->get_one('id_nasabah','pinjaman','id_pinjaman="'.$insert_id.'"');
        $id_owner = $this->function_lib->get_one('id_owner','pinjaman','id_pinjaman="'.$insert_id.'"');
        $id_kolektor = $this->function_lib->get_one('id_kolektor','pinjaman','id_pinjaman="'.$insert_id.'"');
        $biaya_simpanan = (float) $this->function_lib->get_one('biaya_simpanan','owner','id_owner="'.$id_owner.'"');
        $biaya_administrasi = (float) $this->function_lib->get_one('biaya_administrasi','owner','id_owner="'.$id_owner.'"');
        $this->db->query('UPDATE `pinjaman` SET 
            `jumlah_pinjaman_setelah_bunga` = ((`jumlah_pinjaman`*'.$bunga_pinjaman.')/100)+`jumlah_pinjaman`, 
            `persentase_bunga` = "'.$bunga_pinjaman.'",
            `jumlah_perangsuran` = (((`jumlah_pinjaman`*'.$bunga_pinjaman.')/100)+`jumlah_pinjaman`)/`lama_angsuran`,
            `tgl_pinjaman` = "'.date("Y-m-d H:i:s").'",
            `persentase_biaya_simpanan` = "'.$biaya_simpanan.'",
            `persentase_biaya_admin` = "'.$biaya_administrasi.'",
            `input_oleh` = "'.$oleh.'",
            `input_oleh_id` = "'.$oleh_id.'",
            `last_update` = "'.date("Y-m-d H:i:s").'" WHERE `id_pinjaman` = '.$insert_id.'
            ');        
        // update simpanan milik nasabah
        // cek apakah nasabah punya data simpanan aktif        
        $cek_id_simpanan = $this->function_lib->get_one('id_simpanan','simpanan','id_nasabah="'.$id_nasabah.'"');
                
         $jumlah_pinjaman = $this->function_lib->get_one('jumlah_pinjaman','pinjaman','id_pinjaman="'.$insert_id.'"');
        $jumlah_pinjaman = floatval($jumlah_pinjaman);
        $jumlah_simpanan_tambahan = (float) ($jumlah_pinjaman*$biaya_simpanan)/100;

        if (!empty($cek_id_simpanan)) {
            // jika nasabah ada data simpanan, maka update jumlahnya ditambah dengan total pinjaman* bunga simpanan            
           $this->db->query('UPDATE `simpanan` SET 
            `jumlah_simpanan` = `jumlah_simpanan`+'.$jumlah_simpanan_tambahan.',
            `last_update` = "'.date("Y-m-d H:i:s").'" ,
            `update_oleh_id` = "'.$oleh_id.'",
            `update_oleh` = "'.$oleh.'"
            WHERE id_simpanan = '.$cek_id_simpanan.'
            ');   
           // insert log riwayat simpanan dengan log type biaya_simpanan
           $columnInsertLog = array(
            "id_simpanan" => $cek_id_simpanan,
            "jumlah_riwayat_simpanan" => $jumlah_simpanan_tambahan,
            "tipe_riwayat" => "biaya_pinjaman",
            "input_oleh" => $oleh,
            "input_oleh_id" => $oleh_id,
            "tgl_riwayat_simpanan" => date("Y-m-d H:i:s"),
            "keterangan_riwayat" => "Biaya pinjaman dari koperasi sejumlah ".$jumlah_pinjaman
           );
           $this->db->insert('riwayat_simpanan', $columnInsertLog);
        }else{
            // jika nasabah belum ada data simpanan, maka insert data simpanan, dan insert riwayat simpanan
            $columnInsert = array(
                "id_nasabah" => $id_nasabah,
                "id_owner" => $id_owner,
                "id_kolektor" => $id_kolektor,
                "jumlah_simpanan" => $jumlah_simpanan_tambahan,
                "tgl_simpanan" => date("Y-m-d H:i:s"),                
                "status_simpanan" => "aktif",                
                "input_oleh" => $oleh,                
                "input_oleh_id" => $oleh_id,                
                "note" => "Biaya pinjaman dari koperasi sejumlah ".$jumlah_pinjaman
            );
            $this->db->insert('simpanan', $columnInsert);
            $id_simpanan = $this->db->insert_id();
            $columnInsertLog = array(
                "id_simpanan" => $id_simpanan,
                "jumlah_riwayat_simpanan" => $jumlah_simpanan_tambahan,
                "tipe_riwayat" => "biaya_pinjaman",
                "input_oleh" => $oleh,
                "input_oleh_id" => $oleh_id,
                "tgl_riwayat_simpanan" => date("Y-m-d H:i:s"),
                "keterangan_riwayat" => "Biaya pinjaman dari koperasi sejumlah ".$jumlah_pinjaman
            );
            $this->db->insert('riwayat_simpanan', $columnInsertLog);

        }        
        // end bug
        return $insert_pinjaman;
    }    
    public function get_detail_pinjaman_lama($id_nasabah="0"){
        header("Content-type: application/json");
        $status = 500;
        $msg = "";
        $this->db->select('pinjaman.*,nasabah.nama_nasabah');
        $this->db->where('pinjaman.id_nasabah', $id_nasabah);
        $this->db->where("jumlah_terbayar<jumlah_pinjaman_setelah_bunga");
        $this->db->join('nasabah', 'pinjaman.id_nasabah = nasabah.id_nasabah', 'left');
        $query = $this->db->get('pinjaman');
        $data = $query->row_array();
        if (!empty($data)) {
            $status = 200;
            $msg = "OK";
        }
        $data['status'] = $status;
        $data['msg'] = $msg;
        echo json_encode($data);
    }
    public function set_number_format_with_rp($value, $row){
        return "Rp. ".number_format($value,'2',',','.');
    }
    public function _callback($value, $row)
    {        
        $jumlah_pinjaman = floatval($this->function_lib->remove_string_and_substr2($row->jumlah_pinjaman));
        $persentase_bunga = floatval($this->function_lib->remove_string_and_substr2($row->persentase_bunga));              
      return number_format(floatval(($jumlah_pinjaman*$persentase_bunga/100)+$jumlah_pinjaman),'2',',','.');
    }
    public function biaya_simpanan($value, $row)
    {
        $jumlah_pinjaman = floatval($this->function_lib->remove_string_and_substr2($row->jumlah_pinjaman));        
        return "Rp. ".number_format((($row->persentase_biaya_simpanan)*($jumlah_pinjaman)/100),'2',',','.');
    }
    public function biaya_admin($value, $row)
    {
        $jumlah_pinjaman = floatval($this->function_lib->remove_string_and_substr2($row->jumlah_pinjaman));
        return "Rp. ".number_format((($row->persentase_biaya_admin)*($jumlah_pinjaman)/100),'2',',','.');
    }
    public function angsuran($value, $row)
    {
        $periode_angsuran = $row->periode_angsuran;
        $lama_angsuran = $row->lama_angsuran;
        $jumlah_perangsuran = $row->jumlah_perangsuran;
        return "@Rp. ".number_format($jumlah_perangsuran,'2',',','.')." X ".$lama_angsuran."/".$periode_angsuran;
    }
    public function link_laporan($value, $row){
        return '<a href="'.base_url("riwayat_pinjaman/index?id_pinjaman=".$row->id_pinjaman).'" class="btn btn-info btn-sm"><i class="fa fa-list-alt"></i></a>';
    }
    function get_kekurangan($value, $row){
        $jumlah_pinjaman_setelah_bunga = floatval($this->function_lib->remove_string_and_substr2($row->jumlah_pinjaman_setelah_bunga));        
        
        $terbayar = floatval($row->jumlah_terbayar);
        return "Rp. ".number_format(($jumlah_pinjaman_setelah_bunga-$terbayar),0,'.','.');
    }
    function update_data($post_array,$primary_key)
    {                
        $bunga_pinjaman = $this->function_lib->get_one('bunga_pinjaman','owner','id_owner IN (SELECT id_owner FROM pinjaman WHERE id_pinjaman ="'.$primary_key.'")');        
        $this->db->query('UPDATE `pinjaman` SET 
            `jumlah_pinjaman_setelah_bunga` = ((`jumlah_pinjaman`*'.$bunga_pinjaman.')/100)+`jumlah_pinjaman`,             
            `jumlah_perangsuran` = (((`jumlah_pinjaman`*'.$bunga_pinjaman.')/100)+`jumlah_pinjaman`)/`lama_angsuran`,            
            `last_update` = "'.date("Y-m-d H:i:s").'" WHERE `id_pinjaman` = '.$primary_key.''
        );

        return true;
    }   
    function delete_data($primary_key){        
        $user_sess = $this->function_lib->get_user_level();
        $level = isset($user_sess['level']) ? $user_sess['level'] : "";
        $id_user = isset($user_sess['id_user']) ? $user_sess['id_user'] : "";
        if ($level == "owner") {            
            $id_pinjaman = $this->function_lib->get_one('id_pinjaman','pinjaman','id_pinjaman="'.$primary_key.'" AND id_owner="'.$id_user.'"');
            
            if (empty($id_pinjaman)) {
                return false;
            }else{
                $columnUpdate = array(
                    'status_pinjaman' => 'non_aktif'
                );
                $this->db->where('id_pinjaman', $primary_key);
                return $this->db->update('pinjaman', $columnUpdate);            
            }
        }else{
            $columnUpdate = array(
                'status_pinjaman' => 'non_aktif'
            );
            $this->db->where('id_pinjaman', $primary_key);
            return $this->db->update('pinjaman', $columnUpdate);        
        }
    } 
}

/* End of file Pinjaman.php */
/* Location: ./application/controllers/Pinjaman.php */