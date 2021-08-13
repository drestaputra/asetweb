<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Riwayat_simpanan extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		//Do your magic here
		$this->function_lib->cek_auth(array("super_admin","admin","owner","kasir"));
		$this->load->library('grocery_CRUD');   
	}		
 
    public function index() {    	
        $crud = new grocery_CRUD();

        $user_sess = $this->function_lib->get_user_level();
        $level = isset($user_sess['level']) ? $user_sess['level'] : "";
        $id_user = isset($user_sess['id_user']) ? $user_sess['id_user'] : "";
        
        $crud->set_theme('adminlte');
        $crud->set_table('riwayat_simpanan');
        $crud->set_subject('Data Riwayat Simpanan');
        $crud->set_language('indonesian');
        $crud->order_by("tgl_riwayat_simpanan","DESC");
        $crud->columns('detail_simpanan','nama_nasabah','id_simpanan','jumlah_riwayat_simpanan','tipe_riwayat','input_oleh','tgl_riwayat_simpanan','keterangan_riwayat');                
        if ($level == "owner") {      
            $crud->where("id_riwayat_simpanan IN (SELECT id_riwayat_simpanan FROM riwayat_simpanan WHERE id_owner='".$id_user."')");      
            $crud->set_relation('id_simpanan','simpanan','id_simpanan','simpanan.id_simpanan IN (SELECT id_simpanan from simpanan WHERE id_owner="'.$id_user.'")');  
            if ($crud->getState() == "read" OR $crud->getState() == "edit") {
                $stateInfo = (array) $crud->getStateInfo();
                $pk = isset($stateInfo['primary_key']) ? $stateInfo['primary_key'] : 0;
                $id_riwayat_simpanan = $this->function_lib->get_one('id_riwayat_simpanan','riwayat_simpanan','id_riwayat_simpanan="'.$pk.'" AND riwayat_simpanan.id_simpanan IN (SELECT id_simpanan FROM simpanan WHERE id_owner="'.$id_user.'")');
                if (empty($id_riwayat_simpanan)) {
                    redirect(base_url().'riwayat_simpanan/index/');
                    exit();
                }
            }              
        }else if ($level == "kasir") {   
            $id_user = $this->function_lib->get_one('id_owner','kasir','id_kasir='.$this->db->escape($id_user).'');   
            $crud->where("id_riwayat_simpanan IN (SELECT id_riwayat_simpanan FROM riwayat_simpanan WHERE id_owner='".$id_user."')");      
            $crud->set_relation('id_simpanan','simpanan','id_simpanan','simpanan.id_simpanan IN (SELECT id_simpanan from simpanan WHERE id_owner="'.$id_user.'")');  
            if ($crud->getState() == "read" OR $crud->getState() == "edit") {
                $stateInfo = (array) $crud->getStateInfo();
                $pk = isset($stateInfo['primary_key']) ? $stateInfo['primary_key'] : 0;
                $id_riwayat_simpanan = $this->function_lib->get_one('id_riwayat_simpanan','riwayat_simpanan','id_riwayat_simpanan="'.$pk.'" AND riwayat_simpanan.id_simpanan IN (SELECT id_simpanan FROM simpanan WHERE id_owner="'.$id_user.'")');
                if (empty($id_riwayat_simpanan)) {
                    redirect(base_url().'riwayat_simpanan/index/');
                    exit();
                }
            }                      
        }else{
            $crud->set_relation('id_simpanan','simpanan','id_simpanan');                
        }        
        $crud->unset_texteditor(array('keterangan_riwayat','full_text'));        
         $crud->display_as('id_simpanan','ID Simpanan')        
             ->display_as('jumlah_riwayat_simpanan','Jumlah Simpanan (Rp.)')
             ->display_as('tipe_riwayat','Tipe')    
             ->display_as('detail_simpanan','Detail Simpanan')
             ->display_as('nama_nasabah',"Nasabah</hr>Username - Nama")
             ->display_as('tgl_riwayat_simpanan','Tgl. Riwayat');        
		$crud->callback_column('detail_simpanan',array($this,'link_detail'));        
        $crud->callback_column('nama_nasabah',array($this,'nama_nasabah'));        
        $crud->required_fields('id_simpanan','jumlah_riwayat_simpanan','keterangan_riwayat');
        $crud->unset_add_fields('tipe_simpanan','detail_simpanan','nama_nasabah','input_oleh','input_oleh_id','tgl_riwayat_simpanan','angsuran_ke');
        $crud->unset_edit_fields('detail_simpanan','nama_nasabah','input_oleh','input_oleh_id','tgl_riwayat_simpanan','id_simpanan');        
        $crud->callback_after_insert(array($this, 'insert_baru'));        
        $crud->callback_after_update(array($this, 'edit_data'));        
        $crud->callback_delete(array($this,'delete_data'));
        
        $data = $crud->render();
 
        $this->load->view('riwayat_simpanan/index', $data, FALSE);

    }
    public function get_detail_simpanan($id_simpanan){
        header("Content-type: application/json");
          header("Content-type: application/json");
        $status = 500;
        $msg = "";
        $this->db->select('simpanan.*,nasabah.nama_nasabah');
        $this->db->where('id_simpanan', $id_simpanan);
        $this->db->join('nasabah', 'simpanan.id_nasabah = nasabah.id_nasabah', 'left');
        $query = $this->db->get('simpanan');
        $data = $query->row_array();
        if (!empty($data)) {
            $status = 200;
            $msg = "OK";
        }
        $data['status'] = $status;
        $data['msg'] = $msg;
        echo json_encode($data);
                
    }
    public function link_detail($value, $row){
		return '<a href="'.base_url("simpanan/index/read/".$row->id_simpanan).'" class="btn btn-info btn-sm"><i class="fa fa-eye"></i> Detail</a>';
	}
    public function nama_nasabah($value,$row){
        $nasabah = $this->function_lib->get_one_by('`username`,`nama_nasabah`','nasabah','id_nasabah IN (SELECT id_nasabah FROM simpanan WHERE id_simpanan="'.$row->id_simpanan.'")','id_nasabah ASC');        
        $nama = (isset($nasabah['nama_nasabah']) AND !empty($nasabah['nama_nasabah'])!="") ? $nasabah['nama_nasabah'] : "";
        $username = (isset($nasabah['username']) AND !empty($nasabah['username'])!="") ? $nasabah['username'] : "";
        
        return "<b>".$username." - ".$nama."</b>";

    }
    function insert_baru($post_array,$primary_key){    	
    	$user_sess = $this->function_lib->get_user_level();
    	$username_sess = isset($user_sess['username']) ? $user_sess['username'] : "";
    	$level_sess = isset($user_sess['level']) ? $user_sess['level'] : "";
    	$input_oleh = $username_sess. " - ". $level_sess;
    	$input_oleh_id = isset($user_sess['id_user']) ? $user_sess['id_user'] : 0;    	
    	$this->db->query('UPDATE `riwayat_simpanan` SET                         
            `tgl_riwayat_simpanan` = "'.date("Y-m-d H:i:s").'",            
            `input_oleh` = "'.$input_oleh.'",
            `input_oleh_id` = "'.$input_oleh_id.'"
            WHERE `id_riwayat_simpanan` = '.$primary_key.'
            ');        
    	// update table simpanan
    	$jumlah_simpanan = $this->function_lib->get_one('sum(jumlah_riwayat_simpanan)','riwayat_simpanan','id_simpanan='.$this->security->sanitize_filename($post_array['id_simpanan']).' AND (tipe_riwayat="simpanan" OR tipe_riwayat="biaya_pinjaman") ORDER BY id_simpanan DESC');
    	$jumlah_simpanan = floatval($jumlah_simpanan);    	        
    	$columnUpdate = array(    		
    		"jumlah_simpanan" => $jumlah_simpanan,    		            
    		"last_update" => date("Y-m-d H:i:s"),
            "update_oleh" => $input_oleh,
            "update_oleh_id" => $input_oleh_id
    	);
    	$this->db->where('id_simpanan', $this->security->sanitize_filename($post_array['id_simpanan']));
    	$this->db->update('simpanan', $columnUpdate);
        return true;
    } 
    function edit_data($post_array,$primary_key = null){
        $user_sess = $this->function_lib->get_user_level();
        $username_sess = isset($user_sess['username']) ? $user_sess['username'] : "";
        $level_sess = isset($user_sess['level']) ? $user_sess['level'] : "";
        $input_oleh = $username_sess. " - ". $level_sess;
        $input_oleh_id = isset($user_sess['id_user']) ? $user_sess['id_user'] : 0;      
    	$id_simpanan = $this->function_lib->get_one('id_simpanan','riwayat_simpanan','id_riwayat_simpanan="'.$primary_key.'"');    	
    	$update_jumlah = $this->function_lib->get_one('sum(jumlah_riwayat_simpanan)','riwayat_simpanan','id_simpanan='.$this->security->sanitize_filename($id_simpanan).'');
        $jumlah_simpanan = $this->function_lib->get_one('sum(jumlah_riwayat_simpanan)','riwayat_simpanan','id_simpanan='.$this->security->sanitize_filename($id_simpanan).' AND (tipe_riwayat="simpanan" OR tipe_riwayat="biaya_pinjaman") ORDER BY id_simpanan DESC');
        $jumlah_simpanan = floatval($jumlah_simpanan);              

    	$columnUpdate = array(         
            "jumlah_simpanan" => $jumlah_simpanan,
            "last_update" => date("Y-m-d H:i:s"),
            "update_oleh" => $input_oleh,
            "update_oleh_id" => $input_oleh_id
        );
    	$this->db->where('id_simpanan', $this->security->sanitize_filename($id_simpanan));
    	$this->db->update('simpanan', $columnUpdate);
    }  
    function delete_data($primary_key){
        $user_sess = $this->function_lib->get_user_level();
        $username_sess = isset($user_sess['username']) ? $user_sess['username'] : "";
        $level_sess = isset($user_sess['level']) ? $user_sess['level'] : "";
        $input_oleh = $username_sess. " - ". $level_sess;
        $input_oleh_id = isset($user_sess['id_user']) ? $user_sess['id_user'] : 0;      
        $id_user = $input_oleh_id;    	
        if ($level_sess == "owner") {            
            $id_riwayat_simpanan = $this->function_lib->get_one('id_riwayat_simpanan','riwayat_simpanan','id_riwayat_simpanan="'.$primary_key.'" AND id_simpanan IN (SELECT id_simpanan FROM simpanan WHERE id_owner="'.$id_user.'")');

            if (empty($id_riwayat_simpanan)) {
                return false;
            }else{
                $id_simpanan = $this->function_lib->get_one('id_simpanan','riwayat_simpanan','id_riwayat_simpanan="'.$primary_key.'"');        
                $this->db->where('id_riwayat_simpanan', $primary_key);
                $this->db->delete('riwayat_simpanan');
                $update_jumlah = $this->function_lib->get_one('sum(jumlah_riwayat_simpanan)','riwayat_simpanan','id_simpanan='.$this->security->sanitize_filename($id_simpanan).'');
                $jumlah_simpanan = $this->function_lib->get_one('sum(jumlah_riwayat_simpanan)','riwayat_simpanan','id_simpanan='.$this->security->sanitize_filename($id_simpanan).' AND (tipe_riwayat="simpanan" OR tipe_riwayat="biaya_pinjaman") ORDER BY id_simpanan DESC');
                $jumlah_simpanan = floatval($jumlah_simpanan);      
                $columnUpdate = array(         
                    "jumlah_simpanan" => $jumlah_simpanan,            
                    "last_update" => date("Y-m-d H:i:s"),
                    "update_oleh" => $input_oleh,
                    "update_oleh_id" => $input_oleh_id
                );
                $this->db->where('id_simpanan', $this->security->sanitize_filename($id_simpanan));
                return $this->db->update('simpanan', $columnUpdate);  
            }
        }else{
            $id_simpanan = $this->function_lib->get_one('id_simpanan','riwayat_simpanan','id_riwayat_simpanan="'.$primary_key.'"');        
            $this->db->where('id_riwayat_simpanan', $primary_key);
            $this->db->delete('riwayat_simpanan');
            $update_jumlah = $this->function_lib->get_one('sum(jumlah_riwayat_simpanan)','riwayat_simpanan','id_simpanan='.$this->security->sanitize_filename($id_simpanan).'');
            $jumlah_simpanan = $this->function_lib->get_one('sum(jumlah_riwayat_simpanan)','riwayat_simpanan','id_simpanan='.$this->security->sanitize_filename($id_simpanan).' AND (tipe_riwayat="simpanan" OR tipe_riwayat="biaya_pinjaman") ORDER BY id_simpanan DESC');
            $jumlah_simpanan = floatval($jumlah_simpanan);      
            $columnUpdate = array(         
                "jumlah_simpanan" => $jumlah_simpanan,            
                "last_update" => date("Y-m-d H:i:s"),
                "update_oleh" => $input_oleh,
                "update_oleh_id" => $input_oleh_id
            );
            $this->db->where('id_simpanan', $this->security->sanitize_filename($id_simpanan));
            return $this->db->update('simpanan', $columnUpdate);         
        }          
    } 
}

/* End of file Riwayat Simpanan.php */
/* Location: ./application/controllers/Riwayat Simpanan.php */