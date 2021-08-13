<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mowner extends CI_Model {

	function cekLogin(){
		$pwd = $this->input->post('pwd',TRUE);
        $username = $this->input->post('username',TRUE);
		$password = hash('sha512',$pwd . config_item('encryption_key'));		
		$this->db->where('username', $username);
		$this->db->where('password', $password);
        // $this->db->where("tgl_jatuh_tempo_pembayaran_sistem > CURRENT_TIMESTAMP");
		$this->db->where('status="aktif"');
		$query=$this->db->get('owner');
		if ($query->num_rows()!=null) {			
            $data=$query->row_array();					
			$this->session->set_userdata("owner",$data);		
			return array("status"=>200,"msg"=>"Berhasil Login");
		}else{
			return array("status"=>500,"msg"=>"Data User tidak ditemukan");			
		}
	}
	function validasi($id_owner=0){
		$status=200;
        $msg="";
        $error = array();
        // $function_lib=$this->load->library('function_lib');        
        
        // exit();
        $username = $this->input->post('username',TRUE);        
        $email = $this->input->post('email',TRUE);        
        if ($id_owner==0) {            
        $id_owner = isset($this->session->userdata('owner')['id_owner']) ? $this->session->userdata('owner')['id_owner'] : null;
        }
        
        $usernameOri = $this->function_lib->get_one('username','owner','id_owner="'.$id_owner.'"');        
        $emailOri = $this->function_lib->get_one('email','owner','id_owner="'.$id_owner.'"');                
        $is_unique = ($username != $usernameOri)? '|is_unique[owner.username]':'';
        $is_uniqueEmail = ($email != $emailOri)? '|is_unique[owner.email]':'';

        $this->load->library('form_validation');
        $this->form_validation->set_rules('username', 'Username', 'required'.$is_unique,
             array(                
                'is_unique'     => 'Username sudah terpakai.'
            )
        );        
        $this->form_validation->set_rules('email', 'Email', 'required'.$is_uniqueEmail,
            array(                
                'is_unique'     => 'Email sudah terpakai.'
            )
        );  
        // validasi tambah
        if ($this->input->post('tambah')) {           
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[5]',
                array(                
                    'required'     => '%s masih kosong.',
                    'min_length'   => '%s harus lebih dari 5 karakter.'
                )
            );  
            $this->form_validation->set_rules('conf_password', 'Konfirmasi Password', 'required|matches[password]',
                array(                
                    'required'     => '%s masih kosong.',
                    'matches'      => '%s tidak cocok'
                )
            );  
           
        }
        $this->form_validation->set_rules('alamat', 'Alamat', 'required',array('required'     => '%s masih kosong.'));  
        $this->form_validation->set_rules('nama_owner', 'Nama Owner', 'required',array('required'     => '%s masih kosong.'));  
        $this->form_validation->set_rules('nama_koperasi', 'Nama Koperasi', 'required',array('required'     => '%s masih kosong.'));  
        $this->form_validation->set_rules('no_hp', 'no_hp', 'required',array('required'     => '%s masih kosong.'));  
        $this->form_validation->set_rules('kecamatan', 'Kecamatan', 'required',array('required'     => '%s masih kosong.'));  
        $this->form_validation->set_rules('kabupaten', 'Kabupaten', 'required',array('required'     => '%s masih kosong.'));  
        $this->form_validation->set_rules('provinsi', 'Provinsi', 'required',array('required'     => '%s masih kosong.'));  
        $this->form_validation->set_rules('no_badan_hukum', 'No. Badan Hukum', 'required',array('required'     => '%s masih kosong.'));  
        $this->form_validation->set_rules('biaya_administrasi', 'Biaya Administrasi', 'required|custom_decimal',array('required'     => '%s masih kosong.','custom_decimal'=>'Inputan %s tidak diijinkan'));  
        $this->form_validation->set_rules('biaya_simpanan', 'Biaya Tabungan', 'required|custom_decimal',array('required'     => '%s masih kosong.','custom_decimal'=>'Inputan %s tidak diijinkan'));  
        $this->form_validation->set_rules('bunga_pinjaman', 'Bunga Pinjaman', 'required|custom_decimal',array('required'     => '%s masih kosong.','custom_decimal'=>'Inputan %s tidak diijinkan'));  
        $user_sess = $this->function_lib->get_user_level();
        $level = isset($user_sess['level']) ? $user_sess['level'] : "";
        $id_user = isset($user_sess['id_user']) ? $user_sess['id_user'] : "";
        if ($level!="owner") {
            $this->form_validation->set_rules('biaya_sewa_aplikasi', 'Biaya Sewa Aplikasi', 'required|custom_decimal',array('required'     => '%s masih kosong.','custom_decimal'=>'Inputan %s tidak diijinkan'));  
            $this->form_validation->set_rules('tgl_jatuh_tempo_pembayaran_sistem', 'Tgl Jatuh Tempo', 'required',array('required'     => '%s masih kosong.'));  
        }
        if ($this->form_validation->run() == TRUE) {
            $status=200;
            $msg="Berhasil";
            $error = array(
                "username" => form_error('username'),
                "email" => form_error('email'),                
                "password" => form_error('password'),
                "conf_password" => form_error('conf_password'),
                "nama_owner" => form_error('nama_owner'),
                "nama_koperasi" => form_error('nama_koperasi'),
                "no_hp" => form_error('no_hp'),
                "alamat" => form_error('alamat'),
                "kecamatan" => form_error('kecamatan'),
                "kabupaten" => form_error('kabupaten'),
                "provinsi" => form_error('provinsi'),
                "no_badan_hukum" => form_error('no_badan_hukum'),
                "biaya_administrasi" => form_error('biaya_administrasi'),
                "biaya_simpanan" => form_error('biaya_simpanan'),
                "bunga_pinjaman" => form_error('bunga_pinjaman'),
                "biaya_sewa_aplikasi" => form_error('biaya_sewa_aplikasi'),
                "tgl_jatuh_tempo_pembayaran_sistem" => form_error('tgl_jatuh_tempo_pembayaran_sistem'),
            );
        } else {
            $status=500;
            $msg=validation_errors(' ',' ');
            $error = array(
                "username" => form_error('username'),
                "email" => form_error('email'),                
                "password" => form_error('password'),
                "conf_password" => form_error('conf_password'),
                "nama_owner" => form_error('nama_owner'),
                "nama_koperasi" => form_error('nama_koperasi'),
                "no_hp" => form_error('no_hp'),
                "alamat" => form_error('alamat'),
                "kecamatan" => form_error('kecamatan'),
                "kabupaten" => form_error('kabupaten'),
                "provinsi" => form_error('provinsi'),
                "no_badan_hukum" => form_error('no_badan_hukum'),
                "biaya_administrasi" => form_error('biaya_administrasi'),
                "biaya_simpanan" => form_error('biaya_simpanan'),
                "bunga_pinjaman" => form_error('bunga_pinjaman'),
                "biaya_sewa_aplikasi" => form_error('biaya_sewa_aplikasi'),
                "tgl_jatuh_tempo_pembayaran_sistem" => form_error('tgl_jatuh_tempo_pembayaran_sistem'),
            );
        }        
        return array("status"=>$status,"msg"=>$msg,"error"=>$error);
	}

	function editProfil(){
		$username = $this->input->post('username',TRUE);        
        $email = $this->input->post('email',TRUE);        
        $nama_owner = $this->input->post('nama_owner',TRUE);        
        $nama_koperasi = $this->input->post('nama_koperasi',TRUE);        
        $no_hp = $this->input->post('no_hp',TRUE);        
        $alamat = $this->input->post('alamat',TRUE);        
        $kecamatan = $this->input->post('kecamatan',TRUE);        
        $kabupaten = $this->input->post('kabupaten',TRUE);        
        $provinsi = $this->input->post('provinsi',TRUE);        
        $no_badan_hukum = $this->input->post('no_badan_hukum',TRUE);        
        $biaya_administrasi = $this->input->post('biaya_administrasi',TRUE);        
        $biaya_simpanan = $this->input->post('biaya_simpanan',TRUE);        
        $bunga_pinjaman = $this->input->post('bunga_pinjaman',TRUE);        
        $hari_kerja = $this->input->post('hari_kerja',TRUE);        
        $ratio_modal = $this->input->post('ratio_modal',TRUE);        
        $idAdmin = $this->session->userdata('owner')['id_owner'];                
		$validasi = $this->validasi();		
		$status = 500;
		$msg = "";
		if ($validasi['status']==200) {
			$columnUpdate = array(
				"email"=> $email,
				"username"=> $username,
                "nama_owner" => $nama_owner,
                "nama_koperasi" => $nama_koperasi,
                "no_hp" => $no_hp,
                "alamat" => $alamat,
                "kecamatan" => $kecamatan,
                "kabupaten" => $kabupaten,
                "provinsi" => $provinsi,
                "no_badan_hukum" => $no_badan_hukum,
                "biaya_administrasi" => $biaya_administrasi,
                "biaya_simpanan" => $biaya_simpanan,
                "bunga_pinjaman" => $bunga_pinjaman,                
                "hari_kerja" => $hari_kerja,                
                "ratio_modal" => $ratio_modal,                
			);
			$this->db->where('id_owner="'.$idAdmin.'"');
			$this->db->update('owner', $columnUpdate);
			$status = 200;
			$msg = "Berhasil Update";
		}else{
			$status = $validasi['status'];
			$msg = $validasi['msg'];
		}
		return array("status"=>$status,"msg"=>$msg);
	}

	function getData(){
		$params = isset($_POST) ? $_POST : array();
        $params['table'] = 'owner';

        $username = $this->input->get('username',TRUE);        
        $email = $this->input->get('email',TRUE);                
        $status = $this->input->get('status',TRUE);        
        $nama_owner = $this->input->get('nama_owner',TRUE);        
        $nama_koperasi = $this->input->get('nama_koperasi',TRUE);        
        $no_hp = $this->input->get('no_hp',TRUE);        
        $alamat = $this->input->get('alamat',TRUE);        
        $kecamatan = $this->input->get('kecamatan',TRUE);        
        $kabupaten = $this->input->get('kabupaten',TRUE);        
        $provinsi = $this->input->get('provinsi',TRUE);        
        $no_badan_hukum = $this->input->get('no_badan_hukum',TRUE);        
        $biaya_administrasi = $this->input->get('biaya_administrasi',TRUE);        
        $biaya_simpanan = $this->input->get('biaya_simpanan',TRUE);        
        $bunga_pinjaman = $this->input->get('bunga_pinjaman',TRUE);        
        $biaya_sewa_aplikasi = $this->input->get('biaya_sewa_aplikasi',TRUE);   
                
        $params['select'] = "
            *
        ";
        $params['join'] = "
        ";
        $params['where'] = "1";
      
        if(trim($username)!='')
        {
            $params['where'].=' AND username LIKE "%'.$username.'%"';
        }        
        if(trim($email)!='')
        {
            $params['where'].=' AND email LIKE "%'.$email.'%"';
        }  
        if(trim($status)!='')
        {
            $params['where'].=' AND status LIKE "%'.$status.'%"';
        }        
        if(trim($nama_owner)!='')
        {
            $params['where'].=' AND nama_owner = "'.$nama_owner.'"';
        }      
        if(trim($nama_koperasi)!='')
        {
            $params['where'].=' AND nama_koperasi = "'.$nama_koperasi.'"';
        }      
        if(trim($no_hp)!='')
        {
            $params['where'].=' AND no_hp = "'.$no_hp.'"';
        }      
        if(trim($alamat)!='')
        {
            $params['where'].=' AND alamat = "'.$alamat.'"';
        }      
        if(trim($kecamatan)!='')
        {
            $params['where'].=' AND kecamatan = "'.$kecamatan.'"';
        }      
        if(trim($kabupaten)!='')
        {
            $params['where'].=' AND kabupaten = "'.$kabupaten.'"';
        }      
        if(trim($provinsi)!='')
        {
            $params['where'].=' AND provinsi = "'.$provinsi.'"';
        }      
        if(trim($no_badan_hukum)!='')
        {
            $params['where'].=' AND no_badan_hukum = "'.$no_badan_hukum.'"';
        }      
        if(trim($biaya_administrasi)!='')
        {
            $params['where'].=' AND biaya_administrasi = "'.$biaya_administrasi.'"';
        }      
        if(trim($biaya_simpanan)!='')
        {
            $params['where'].=' AND biaya_simpanan = "'.$biaya_simpanan.'"';
        }      
        if(trim($bunga_pinjaman)!='')
        {
            $params['where'].=' AND bunga_pinjaman = "'.$bunga_pinjaman.'"';
        }      
        if(trim($biaya_sewa_aplikasi)!='')
        {
            $params['where'].=' AND biaya_sewa_aplikasi = "'.$biaya_sewa_aplikasi.'"';
        }      
          
		
        $params['order_by'] = "
            id_owner DESC
        ";
   
        
        $query = $this->function_lib->db_query_execution($params);
        $total = $this->function_lib->db_query_execution($params, true);        
        return array("query"=>$query,"total"=>$total);
	}
	function delete($id_owner){
		$cek = $this->function_lib->get_one('id_owner','owner','id_owner="'.$id_owner.'"');
		if (trim($cek)!="") {			
			$this->db->where('id_owner', $id_owner);
			$this->db->delete('owner');
		}
		return array("status"=>200,"msg"=>"Berhasil menghapus");
	}
	function edit($id_owner){
        $username = $this->input->post('username',TRUE);        
        $email = $this->input->post('email',TRUE);        
        $password= $this->input->post('password',TRUE);        
        $status_post = $this->input->post('status',TRUE);        
        $nama_owner = $this->input->post('nama_owner',TRUE);        
        $nama_koperasi = $this->input->post('nama_koperasi',TRUE);        
        $no_hp = $this->input->post('no_hp',TRUE);        
        $alamat = $this->input->post('alamat',TRUE);        
        $kecamatan = $this->input->post('kecamatan',TRUE);        
        $kabupaten = $this->input->post('kabupaten',TRUE);        
        $provinsi = $this->input->post('provinsi',TRUE);        
        $no_badan_hukum = $this->input->post('no_badan_hukum',TRUE);        
        $biaya_administrasi = $this->input->post('biaya_administrasi',TRUE);        
        $biaya_simpanan = $this->input->post('biaya_simpanan',TRUE);        
        $bunga_pinjaman = $this->input->post('bunga_pinjaman',TRUE);        
        $biaya_sewa_aplikasi = $this->input->post('biaya_sewa_aplikasi',TRUE);        
        $tgl_jatuh_tempo_pembayaran_sistem = $this->input->post('tgl_jatuh_tempo_pembayaran_sistem',TRUE);        
        $status_post = trim($status_post)!=""?$status_post:"aktif";        
        $validasi = $this->validasi($id_owner);      
        if ($validasi['status']==200) {
            $columnUpdate = array(
                "email"=> $this->security->sanitize_filename($email),
                "username"=> $this->security->sanitize_filename($username),                
                "status"=> $this->security->sanitize_filename($status_post),                
                "nama_owner"=> $this->security->sanitize_filename($nama_owner),                
                "nama_koperasi"=> $this->security->sanitize_filename($nama_koperasi),                
                "no_hp"=> $this->security->sanitize_filename($no_hp),                
                "alamat"=> $this->security->sanitize_filename($alamat),
                "kecamatan"=> $this->security->sanitize_filename($kecamatan),
                "kabupaten"=> $this->security->sanitize_filename($kabupaten),
                "provinsi"=> $this->security->sanitize_filename($provinsi),
                "no_badan_hukum"=> $this->security->sanitize_filename($no_badan_hukum),
                "biaya_administrasi"=> $this->security->sanitize_filename($biaya_administrasi),
                "biaya_simpanan"=> $this->security->sanitize_filename($biaya_simpanan),
                "bunga_pinjaman"=> $this->security->sanitize_filename($bunga_pinjaman),
                "biaya_sewa_aplikasi"=> $this->security->sanitize_filename($biaya_sewa_aplikasi),                                
                "tgl_jatuh_tempo_pembayaran_sistem"=> date("Y-m-d H:i:s", strtotime($tgl_jatuh_tempo_pembayaran_sistem)),
            );
            $this->db->where('id_owner="'.$id_owner.'"');
            $this->db->update('owner', $columnUpdate);
            $status = 200;
            $msg = "Berhasil Update";
        }else{
            $status = $validasi['status'];
            $msg = $validasi['msg'];
        }
        return array("status"=>$status,"msg"=>$msg);
	}
    function changePassword($id_owner=0){
        $status = 500;
        $msg = "";
        $old_password = $this->input->post('old_password',TRUE);        
        $new_password = $this->input->post('new_password',TRUE);        
        $repeat_password = $this->input->post('repeat_password',TRUE);        
        $error = array();

        $this->load->library('form_validation');
        $this->form_validation->set_rules('new_password', 'Password Baru', 'required|matches[repeat_password]');  
        if (!empty($this->session->userdata('owner'))) {
            $oldPasswordHash = hash('sha512',$old_password . config_item('encryption_key'));        
            $this->form_validation->set_rules('old_password', 'Password Lama', 'required');  
        }
        $this->form_validation->set_rules('repeat_password', 'Konfirmasi Password', 'required');  
        if ($this->form_validation->run() == TRUE) {            
                if (!empty($this->session->userdata('owner'))) {
                    $id_owner = $this->session->userdata('owner')['id_owner'];                    
                    $id_owner = $this->function_lib->get_one('id_owner','owner','password='.$this->db->escape($oldPasswordHash).'');                    
                }
                if (floatval($id_owner) != 0) {     
                    $columnUpdate = array(
                        "password" => hash('sha512',$new_password . config_item('encryption_key')),   
                    );                    
                    $this->db->where('id_owner', $id_owner);
                    $this->db->update('owner', $columnUpdate);
                    $status=200;
                    $msg="Berhasil mengubah password";
                }else{
                    $status = 500;
                    $msg = "Password lama tidak sesuai";
                }
            $error = array(
                "old_password" => form_error('old_password'),
                "new_password" => form_error('new_password'),
                "repeat_password" => form_error('repeat_password'),
            );
            

        } else {
            $status=500;
            $msg="Gagal, ".validation_errors(' ',' ');
            $error = array(
                "old_password" => form_error('old_password'),
                "new_password" => form_error('new_password'),
                "repeat_password" => form_error('repeat_password'),
            );
        }            
        return array("status"=>$status,"msg"=>$msg,"error"=>$error);            
    }
    function tambah(){
        $username = $this->input->post('username',TRUE);        
        $email = $this->input->post('email',TRUE);        
        $password= $this->input->post('password',TRUE);        
        $status_post = $this->input->post('status',TRUE);        
        $nama_owner = $this->input->post('nama_owner',TRUE);        
        $nama_koperasi = $this->input->post('nama_koperasi',TRUE);        
        $no_hp = $this->input->post('no_hp',TRUE);        
        $alamat = $this->input->post('alamat',TRUE);        
        $kecamatan = $this->input->post('kecamatan',TRUE);        
        $kabupaten = $this->input->post('kabupaten',TRUE);        
        $provinsi = $this->input->post('provinsi',TRUE);        
        $no_badan_hukum = $this->input->post('no_badan_hukum',TRUE);        
        $biaya_administrasi = $this->input->post('biaya_administrasi',TRUE);        
        $biaya_simpanan = $this->input->post('biaya_simpanan',TRUE);        
        $bunga_pinjaman = $this->input->post('bunga_pinjaman',TRUE);        
        $biaya_sewa_aplikasi = $this->input->post('biaya_sewa_aplikasi',TRUE);        
        $tgl_jatuh_tempo_pembayaran_sistem = $this->input->post('tgl_jatuh_tempo_pembayaran_sistem',TRUE);        
        $status_post = trim($status_post)!=""?$status_post:"aktif";        
        $validasi = $this->validasi();      
        $status = 500;
        $msg = "";
        if ($validasi['status']==200) {
            $hashPassword = hash('sha512', $password . config_item('encryption_key'));     
            // $masa_jatuh_tempo = $this->function_lib->periode_jatuh_tempo_pendaftaran();
            
            $columnInsert = array(
                "email"=> $this->security->sanitize_filename($email),
                "username"=> $this->security->sanitize_filename($username),
                "password"=> $this->security->sanitize_filename($hashPassword),                
                "status"=> $this->security->sanitize_filename($status_post),                
                "nama_owner"=> $this->security->sanitize_filename($nama_owner),                
                "nama_koperasi"=> $this->security->sanitize_filename($nama_koperasi),                
                "no_hp"=> $this->security->sanitize_filename($no_hp),                
                "alamat"=> $this->security->sanitize_filename($alamat),
                "kecamatan"=> $this->security->sanitize_filename($kecamatan),
                "kabupaten"=> $this->security->sanitize_filename($kabupaten),
                "provinsi"=> $this->security->sanitize_filename($provinsi),
                "no_badan_hukum"=> $this->security->sanitize_filename($no_badan_hukum),
                "biaya_administrasi"=> $this->security->sanitize_filename($biaya_administrasi),
                "biaya_simpanan"=> $this->security->sanitize_filename($biaya_simpanan),
                "bunga_pinjaman"=> $this->security->sanitize_filename($bunga_pinjaman),
                "biaya_sewa_aplikasi"=> $this->security->sanitize_filename($biaya_sewa_aplikasi),
                "tgl_pendaftaran_sistem"=> date("Y-m-d H:i:s"),
                "tgl_jatuh_tempo_pembayaran_sistem"=> date("Y-m-d H:i:s", strtotime($tgl_jatuh_tempo_pembayaran_sistem)),                
            );
            
            $this->db->insert('owner', $columnInsert);
            $last_id_owner = $this->db->insert_id();
            $this->db->query('UPDATE `owner` SET `kode_koperasi` = CONCAT("KOP",LPAD(`id_owner`,4, 0))  WHERE `owner`.`id_owner` = '.$last_id_owner.';');
            $status = 200;
            $msg = "Berhasil Menambah Owner";
        }else{
            $status = $validasi['status'];
            $msg = $validasi['msg'];
        }
        return array("status"=>$status,"msg"=>$msg);
    }
    public function get_grafik_user_owner(){    
        $query = $this->db->query('SELECT MONTH(tgl_pendaftaran_sistem) AS bulan,count(id_owner) AS total FROM owner WHERE YEAR(tgl_pendaftaran_sistem)='.date("Y").' group by year(tgl_pendaftaran_sistem),month(tgl_pendaftaran_sistem) order by year(tgl_pendaftaran_sistem),month(tgl_pendaftaran_sistem)');
        $data = $query->result_array();
        return $data;
    }
    public function lupass(){
        $this->load->library('form_validation');                    
        $data['status']=500;                                    
        $data['msg']="Gagal silahkan coba lagi";
        $this->form_validation->set_rules('email', 'email', 'trim|required');
        if ($this->form_validation->run() == TRUE) {                
            $email = $this->input->post('email', TRUE);
            $email = $this->security->sanitize_filename($email);
            $cek_email = $this->function_lib->get_one('email','owner','status="aktif" AND email='.$this->db->escape($email).'');
            if (!empty($cek_email)) {
                $exp_datetime = date("Y-m-d H:i:s",strtotime('+10 hours'));
                $jam_sekarang = date("Y-m-d H:i:s",strtotime($exp_datetime));
                $menit_lalu = date("Y-m-d H:i:s",strtotime('+590 minutes'));
                // BETWEEN '2016-01-23 00:00:00' AND '2016-01-24 00:00:00'
                // cek hitung batasan limit request forget password range 10 menit, limit 5 request
                $jumlah_request = $this->function_lib->get_one('count(id_forget_password)','forget_password','email='.$this->db->escape($email).' AND jenis_user="owner" AND exp_datetime BETWEEN '.$this->db->escape($menit_lalu).' AND '.$this->db->escape($jam_sekarang).'');                 
                if (intval($jumlah_request)<5) {
                    $this->insertKode($email);
                    $data['status']=200;                                    
                    $data['msg']="Email telah dikirim, silahkan cek email untuk mengubah password"; 
                }else{                  
                    $data['status'] = 500;                                  
                    $data['msg'] = "Anda terlalu banyak melakukan request perubahan password, silahkan tunggu 10 menit lagi.";  
                }
            }else{
                $data['status']=500;                                    
                $data['msg']="Pengguna dengan email tersebut tidak ditemukan."  ;
            }
        } else {
            $data['status']=500;                                    
            $data['msg']="Gagal silahkan coba lagi";
        }       
        return $data;       
    }
    function insertKode($email){
        $id_user = $this->function_lib->get_one('id_owner','owner','email='.$this->db->escape($email).'');
        $configKey = "3mai1f0rg3t";
        $exp_datetime = date("Y-m-d H:i:s",strtotime('+10 hours'));
        $token = hash('sha512', $email . $configKey . $exp_datetime);
        $this->db->set('is_active','0');
        $this->db->where('email', $email);
        $this->db->where('jenis_user', "kolektor");
        $this->db->update('forget_password');
        $columnInsert = array(
            "email" => $email,
            "jenis_user" => "owner",
            "id_user" => $id_user,
            "token" => $token,
            "exp_datetime" => $exp_datetime
        );
        $insert = $this->db->insert('forget_password', $columnInsert);
        if ($insert) {
            $this->load->model('Mmail');
            $data_email['token']=$token;
            $data_email['base_url'] = base_url();
            $message = $this->load->view('template_email_forget_password', $data_email, TRUE);          
            $this->Mmail->kirim_email($email,"Koperasi Artakita","Permintaan Perubahaan Password",$message);
        }        
    }
    public function get_grafik_user_nasabah($tahun = "2021",$id_owner){    
        $query = $this->db->query('SELECT MONTH(tgl_bergabung) AS bulan,count(id_nasabah) AS total FROM nasabah WHERE YEAR(tgl_bergabung)='.$tahun.' group by year(tgl_bergabung),month(tgl_bergabung) order by year(tgl_bergabung),month(tgl_bergabung) AND id_owner = '.$this->db->escape($id_owner).'');
        $data = $query->result_array();
        return $data;
    }
    public function get_grafik_riwayat_pinjaman($month = "1", $id_owner=""){
        $where_add = '';
        if (!empty($id_owner)) {
            $where_add = 'id_pinjaman IN (SELECT id_pinjaman FROM pinjaman where id_owner='.$this->db->escape($id_owner).') AND ';
        }
        $query = $this->db->query('SELECT DAY(tgl_riwayat_pinjaman) AS hari,sum(jumlah_riwayat_pembayaran) AS total FROM riwayat_pinjaman WHERE '.$where_add.' MONTH(tgl_riwayat_pinjaman)='.$month.' group by month(tgl_riwayat_pinjaman),day(tgl_riwayat_pinjaman) order by month(tgl_riwayat_pinjaman),month(tgl_riwayat_pinjaman) ASC');
        $data = $query->result_array();
        return $data;
    }
    public function get_grafik_riwayat_simpanan($month = "", $id_owner=""){
        $where_add = '';
        if (!empty($id_owner)) {
            $where_add = 'id_simpanan IN (SELECT id_simpanan FROM simpanan where id_owner='.$this->db->escape($id_owner).') AND ';
        }        
        $query = $this->db->query('SELECT DAY(tgl_riwayat_simpanan) AS hari,sum(jumlah_riwayat_simpanan) AS total FROM riwayat_simpanan WHERE '.$where_add.' MONTH(tgl_riwayat_simpanan)='.$month.' group by month(tgl_riwayat_simpanan),day(tgl_riwayat_simpanan) order by month(tgl_riwayat_simpanan),month(tgl_riwayat_simpanan) ASC');
        $data = $query->result_array();
        return $data;
    }
}

/* End of file Mowner.php */
/* Location: ./application/models/Mowner.php */