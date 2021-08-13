<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Riwayat_pinjaman extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		//Do your magic here
		$this->function_lib->cek_auth(array("admin","super_admin","owner","kasir"));
		$this->load->library('grocery_CRUD');   
	}		
 
    public function index() {    	
        $crud = new grocery_CRUD();
 
        $crud->set_theme('adminlte');
        $crud->set_table('riwayat_pinjaman');
        $crud->set_subject('Angsuran Pinjaman');
        $crud->set_language('indonesian');
        $crud->columns('id_pinjaman','detail_pinjaman','angsuran_ke','jumlah_riwayat_pembayaran','input_oleh','tgl_riwayat_pinjaman','keterangan_riwayat');        
        $crud->order_by('tgl_riwayat_pinjaman','desc');
        $user_sess = $this->function_lib->get_user_level();
        $level = isset($user_sess['level']) ? $user_sess['level'] : "";
        $id_user = isset($user_sess['id_user']) ? $user_sess['id_user'] : "";

        if ($level == "owner") {            
            $crud->where("riwayat_pinjaman.id_pinjaman IN (SELECT id_pinjaman FROM pinjaman WHERE id_owner='".$id_user."')");            
            $crud->set_relation('id_pinjaman','pinjaman','id_pinjaman','id_pinjaman IN (SELECT id_pinjaman FROM pinjaman WHERE id_owner="'.$id_user.'")');                
             if ($crud->getState() == "read" OR $crud->getState() == "edit") {
                $stateInfo = (array) $crud->getStateInfo();
                $pk = isset($stateInfo['primary_key']) ? $stateInfo['primary_key'] : 0;
                $id_riwayat = $this->function_lib->get_one('id_riwayat','riwayat_pinjaman','id_riwayat="'.$pk.'" AND riwayat_pinjaman.id_pinjaman IN (SELECT id_pinjaman FROM pinjaman WHERE id_owner="'.$id_user.'")');
                if (empty($id_riwayat)) {
                    redirect(base_url().'riwayat_pinjaman/index/');
                    exit();
                }
            }
        } else if ($level == "kasir") { 
            $id_user = $this->function_lib->get_one('id_owner','kasir','id_kasir='.$this->db->escape($id_user).'');           
            $crud->where("riwayat_pinjaman.id_pinjaman IN (SELECT id_pinjaman FROM pinjaman WHERE id_owner='".$id_user."')");            
            $crud->set_relation('id_pinjaman','pinjaman','id_pinjaman','id_pinjaman IN (SELECT id_pinjaman FROM pinjaman WHERE id_owner="'.$id_user.'")');                
             if ($crud->getState() == "read" OR $crud->getState() == "edit") {
                $stateInfo = (array) $crud->getStateInfo();
                $pk = isset($stateInfo['primary_key']) ? $stateInfo['primary_key'] : 0;
                $id_riwayat = $this->function_lib->get_one('id_riwayat','riwayat_pinjaman','id_riwayat="'.$pk.'" AND riwayat_pinjaman.id_pinjaman IN (SELECT id_pinjaman FROM pinjaman WHERE id_owner="'.$id_user.'")');
                if (empty($id_riwayat)) {
                    redirect(base_url().'riwayat_pinjaman/index/');
                    exit();
                }
            }
        }else{
            $crud->set_relation('id_pinjaman','pinjaman','id_pinjaman');                
        }
        $crud->unset_texteditor(array('keterangan_riwayat','full_text'));        
         $crud->display_as('id_pinjaman','ID Pinjaman')        
             ->display_as('jumlah_riwayat_pembayaran','Jumlah Pembayaran (Rp.)')
             ->display_as('detail_pinjaman','Detail Pinjaman')
             ->display_as('tgl_riwayat_pinjaman','Tgl. Riwayat');        
		$crud->callback_column('detail_pinjaman',array($this,'link_detail'));        
        $crud->required_fields('id_pinjaman','angsuran_ke','jumlah_pembayaran','keterangan_riwayat');
        $crud->unset_add_fields('detail_pinjaman','input_oleh','input_oleh_id','tgl_riwayat_pinjaman','angsuran_ke');
        $crud->unset_edit_fields('detail_pinjaman','input_oleh','input_oleh_id','tgl_riwayat_pinjaman','id_pinjaman');        
        // $crud->callback_insert(array($this,'insert_new_data'));
        $crud->callback_after_insert(array($this, 'after_insert_baru'));        
        $crud->callback_after_update(array($this, 'edit_data'));        
        $crud->callback_delete(array($this,'delete_data'));
        $crud->callback_column('jumlah_riwayat_pembayaran',array($this,'set_number_format_with_rp'));        

        $data = $crud->render();
 
        $this->load->view('riwayat_pinjaman/index', $data, FALSE);

    }
    public function get_detail_pinjaman($id_pinjaman){
        header("Content-type: application/json");
        $status = 500;
        $msg = "";
        $this->db->select('pinjaman.*,nasabah.nama_nasabah');
        $this->db->where('id_pinjaman', $id_pinjaman);
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
    public function get_detail_pinjaman_by_id_riwayat($id_riwayat){
        header("Content-type: application/json");
        $status = 500;
        $msg = "";
        $this->db->select('pinjaman.*,nasabah.nama_nasabah');
        $this->db->where('id_pinjaman IN (SELECT id_pinjaman from riwayat_pinjaman where id_riwayat="'.$id_riwayat.'")');
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
    
    public function link_detail($value, $row){
		return '<a href="'.base_url("pinjaman/index/read/".$row->id_pinjaman).'" class="btn btn-info btn-sm"><i class="fa fa-eye"></i> Detail</a>';
	}
    // function insert_new_data($post_array){
    //     $user_sess = $this->function_lib->get_user_level();
    //     $username_sess = isset($user_sess['username']) ? $user_sess['username'] : "";
    //     $level_sess = isset($user_sess['level']) ? $user_sess['level'] : "";
    //     $oleh = $username_sess. " - ". $level_sess;
    //     $oleh_id = isset($user_sess['id_user']) ? $user_sess['id_user'] : 0;

    //     $id_pinjaman = isset($post_array['id_pinjaman']) ? $post_array['id_pinjaman'] : "0";
    //     $jumlah_riwayat_pembayaran = isset($post_array['jumlah_riwayat_pembayaran']) ? $post_array['jumlah_riwayat_pembayaran'] : "0";
    //     $jumlah_perangsuran = (float) $this->function_lib->get_one('jumlah_perangsuran','pinjaman','id_pinjaman="'.$id_pinjaman.'"');
    //     $jumlah_angsuran = floor($jumlah_riwayat_pembayaran/$jumlah_perangsuran);
    //     $angsuran_ke = $this->function_lib->get_one('count(id_riwayat)','riwayat_pinjaman','id_pinjaman='.$id_pinjaman.'');
    //     $angsuran_ke = $angsuran_ke+1;
    //     for ($i=0; $i < $jumlah_angsuran; $i++) { 
    //         $columnInsert = array(
    //             "id_pinjaman" =>$id_pinjaman,
    //             "jumlah_riwayat_pembayaran" => $jumlah_perangsuran,
    //             "keterangan_riwayat" =>isset($post_array['keterangan_riwayat']) ? $post_array['keterangan_riwayat'] :"",
    //             "input_oleh" => $oleh,
    //             "input_oleh_id" => $oleh_id,
    //             "tgl_riwayat_pinjaman" => date("Y-m-d H:i:s"),
    //             "angsuran_ke" => $angsuran_ke,
    //         );
    //         $insert = $this->db->insert('riwayat_pinjaman', $columnInsert);
    //         $angsuran_ke++;
    //     }        
    //     return $insert;
    // }
    function after_insert_baru($post_array,$primary_key){    	
    	$user_sess = $this->function_lib->get_user_level();
    	$username_sess = isset($user_sess['username']) ? $user_sess['username'] : "";
    	$level_sess = isset($user_sess['level']) ? $user_sess['level'] : "kolektor";
    	$input_oleh = $level_sess;
    	$input_oleh_id = isset($user_sess['id_user']) ? $user_sess['id_user'] : 0;
    	$angsuran_ke = $this->function_lib->get_one('count(id_riwayat)','riwayat_pinjaman','id_pinjaman='.$this->security->sanitize_filename($post_array['id_pinjaman']).'');
    	$jumlah_pembayaran = $this->function_lib->get_one('sum(jumlah_riwayat_pembayaran)','riwayat_pinjaman','id_pinjaman='.$this->security->sanitize_filename($post_array['id_pinjaman']).' ORDER BY angsuran_ke DESC');
    	$jumlah_pembayaran = floatval($jumlah_pembayaran);
    	$angsuran_ke = floatval($angsuran_ke);
    	$this->db->query('UPDATE `riwayat_pinjaman` SET                         
            `tgl_riwayat_pinjaman` = "'.date("Y-m-d H:i:s").'",
            `angsuran_ke` = '.$angsuran_ke.',
            `input_oleh` = "'.$input_oleh.'",
            `input_oleh_id` = "'.$input_oleh_id.'"
            WHERE `id_riwayat` = '.$primary_key.'
            ');        
    	// insert table pinjaman
    	$columnUpdate = array(
    		"tgl_terakhir_angsuran" => date("Y-m-d H:i:s"),
    		"angsuran_ke" => $angsuran_ke,
    		"jumlah_terbayar" => $jumlah_pembayaran,
    		"last_update" => date("Y-m-d H:i:s")

    	);
    	$this->db->where('id_pinjaman', $this->security->sanitize_filename($post_array['id_pinjaman']));
    	$this->db->update('pinjaman', $columnUpdate);
        return true;
    } 
    function edit_data($post_array,$primary_key = null){
    	$id_pinjaman = $this->function_lib->get_one('id_pinjaman','riwayat_pinjaman','id_riwayat="'.$primary_key.'"');
    	$angsuran_ke = $this->function_lib->get_one('count(id_pinjaman)','riwayat_pinjaman','id_pinjaman="'.$id_pinjaman.'" ORDER BY angsuran_ke DESC');
    	$update_jumlah = $this->function_lib->get_one('sum(jumlah_riwayat_pembayaran)','riwayat_pinjaman','id_pinjaman='.$this->security->sanitize_filename($id_pinjaman).'');
    	$columnUpdate = array(
    		'angsuran_ke' => floatval($angsuran_ke),
    		"jumlah_terbayar" => floatval($update_jumlah),
    		"last_update" => date("Y-m-d H:i:s")
    	);
    	$this->db->where('id_pinjaman', $this->security->sanitize_filename($id_pinjaman));
    	$this->db->update('pinjaman', $columnUpdate);
    }  
    function delete_data($primary_key){
         $user_sess = $this->function_lib->get_user_level();
        $username_sess = isset($user_sess['username']) ? $user_sess['username'] : "";
        $level_sess = isset($user_sess['level']) ? $user_sess['level'] : "kolektor";
        $input_oleh = $level_sess;
        $input_oleh_id = isset($user_sess['id_user']) ? $user_sess['id_user'] : 0;      
        $id_user = $input_oleh_id;      

        if ($level_sess == "owner") {            
            $id_riwayat = $this->function_lib->get_one('id_riwayat','riwayat_pinjaman','id_riwayat="'.$primary_key.'" AND id_pinjaman IN (SELECT id_pinjaman FROM pinjaman WHERE id_owner="'.$id_user.'")');          
            if (empty($id_riwayat)) {
                return false;
            }else{
               $id_pinjaman = $this->function_lib->get_one('id_pinjaman','riwayat_pinjaman','id_riwayat="'.$primary_key.'"');
                $angsuran_ke = $this->function_lib->get_one('count(id_pinjaman)','riwayat_pinjaman','id_pinjaman="'.$id_pinjaman.'" AND id_riwayat !="'.$primary_key.'" ');
                $update_jumlah = $this->function_lib->get_one('sum(jumlah_riwayat_pembayaran)','riwayat_pinjaman','id_pinjaman='.$this->security->sanitize_filename($id_pinjaman).' AND id_riwayat !="'.$primary_key.'" ');
                $columnUpdate = array(
                    'angsuran_ke' => floatval($angsuran_ke),
                    "jumlah_terbayar" => floatval($update_jumlah),
                    "last_update" => date("Y-m-d H:i:s")
                );
                $this->db->where('id_pinjaman', $this->security->sanitize_filename($id_pinjaman));
                $this->db->update('pinjaman', $columnUpdate);
                $this->db->where('id_riwayat', $primary_key);
                return $this->db->delete('riwayat_pinjaman');
            }
        }else{
        	$id_pinjaman = $this->function_lib->get_one('id_pinjaman','riwayat_pinjaman','id_riwayat="'.$primary_key.'"');
        	$angsuran_ke = $this->function_lib->get_one('count(id_pinjaman)','riwayat_pinjaman','id_pinjaman="'.$id_pinjaman.'" AND id_riwayat !="'.$primary_key.'" ');
        	$update_jumlah = $this->function_lib->get_one('sum(jumlah_riwayat_pembayaran)','riwayat_pinjaman','id_pinjaman='.$this->security->sanitize_filename($id_pinjaman).' AND id_riwayat !="'.$primary_key.'" ');
        	$columnUpdate = array(
        		'angsuran_ke' => floatval($angsuran_ke),
        		"jumlah_terbayar" => floatval($update_jumlah),
        		"last_update" => date("Y-m-d H:i:s")
        	);
        	$this->db->where('id_pinjaman', $this->security->sanitize_filename($id_pinjaman));
        	$this->db->update('pinjaman', $columnUpdate);
        	$this->db->where('id_riwayat', $primary_key);
        	$this->db->delete('riwayat_pinjaman');
        }
    } 
}

/* End of file Riwayat Pinjaman.php */
/* Location: ./application/controllers/Riwayat Pinjaman.php */