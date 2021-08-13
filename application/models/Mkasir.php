<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mkasir extends CI_Model {

	function cekLogin(){
		$pwd = $this->input->post('pwd',TRUE);
        $username = $this->input->post('username',TRUE);
		$password = hash('sha512',$pwd . config_item('encryption_key'));		
		$this->db->where('username', $username);
		$this->db->where('password', $password);
		$this->db->where('status="aktif"');
		$query=$this->db->get('kasir');
		if ($query->num_rows()!=null) {			
            $data=$query->row_array();					
			$this->session->set_userdata("kasir",$data);		
			return array("status"=>200,"msg"=>"Berhasil Login");
		}else{
			return array("status"=>500,"msg"=>"Data User tidak ditemukan");			
		}
	}
	function validasi($id_kasir=0){
		$status=200;
        $msg="";
        // $function_lib=$this->load->library('function_lib');        
        
        // exit();
        $username = $this->input->post('username',TRUE);        
        $email = $this->input->post('email',TRUE);        
        if ($id_kasir==0) {            
        $id_kasir = isset($this->session->userdata('kasir')['id_kasir']) ? $this->session->userdata('kasir')['id_kasir'] : null;
        }
        // dapatkan data untuk edit
        $usernameOri = $this->function_lib->get_one('username','kasir','id_kasir="'.$id_kasir.'"');        
        $emailOri = $this->function_lib->get_one('email','kasir','id_kasir="'.$id_kasir.'"');                
        $is_unique = ($username != $usernameOri)? '|is_unique[kasir.username]':'';
        $is_uniqueEmail = ($email != $emailOri)? '|is_unique[kasir.email]':'';
        
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
            $this->form_validation->set_rules('status', 'Status', 'required',
                array(                
                    'required'     => '%s masih kosong.'
                )
            );  
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
        if ($this->form_validation->run() == TRUE) {
            $status=200;
            $msg="Berhasil";
            $error = array(
            	"username" => form_error('username'),
            	"email" => form_error('email'),
            	"status" => form_error('status'),
            	"password" => form_error('password'),
            	"conf_password" => form_error('conf_password'),
            );
        } else {
            $status=500;
            $msg=validation_errors(' ',' ');
            $error = array(
            	"username" => form_error('username'),
            	"email" => form_error('email'),
            	"status" => form_error('status'),
            	"password" => form_error('password'),
            	"conf_password" => form_error('conf_password'),
            );
        }
        return array("status"=>$status,"msg"=>$msg,"error"=>$error);        
	}
	function changePassword($id_kasir=0){
        $status = 500;
        $msg = "";
        $old_password = $this->input->post('old_password',TRUE);        
        $new_password = $this->input->post('new_password',TRUE);        
        $repeat_password = $this->input->post('repeat_password',TRUE);        
        $error = array();

        $this->load->library('form_validation');
        $this->form_validation->set_rules('new_password', 'Password Baru', 'required|matches[repeat_password]');  
        if (!empty($this->session->userdata('kasir'))) {
	        $oldPasswordHash = hash('sha512',$old_password . config_item('encryption_key'));        
	        $this->form_validation->set_rules('old_password', 'Password Lama', 'required');  
        }
        $this->form_validation->set_rules('repeat_password', 'Konfirmasi Password', 'required');  
        if ($this->form_validation->run() == TRUE) {            
		    	if (!empty($this->session->userdata('kasir'))) {
                	$id_kasir = $this->session->userdata('kasir')['id_kasir'];                    
                    $id_kasir = $this->function_lib->get_one('id_kasir','kasir','password='.$this->db->escape($oldPasswordHash).'');                    
		    	}
                if (floatval($id_kasir) != 0) {     
                    $columnUpdate = array(
                        "password" => hash('sha512',$new_password . config_item('encryption_key')),   
                    );                    
                    $this->db->where('id_kasir', $id_kasir);
                    $this->db->update('kasir', $columnUpdate);
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

	function editProfil(){
		$username = $this->input->post('username',TRUE);        
        $email = $this->input->post('email',TRUE);        
        $idAdmin = $this->session->userdata('kasir')['id_kasir'];                
		$validasi = $this->validasi();		
		$status = 500;
		$msg = "";
		if ($validasi['status']==200) {
			$columnUpdate = array(
				"email"=> $email,
				"username"=> $username,
				"nama"=> $nama,
				"no_hp"=> $no_hp,
				"no_ktp"=> $no_ktp,
				"status"=> $status,
			);
			$this->db->where('id_kasir="'.$idAdmin.'"');
			$this->db->update('kasir', $columnUpdate);
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
        $params['table'] = 'kasir';

        $username=$this->input->get('username',true);
        $email=$this->input->get('email',true);        
        $no_hp=$this->input->get('no_hp',true);        
        $nama=$this->input->get('nama',true);        
        $no_ktp=$this->input->get('no_ktp',true);        
        $status=$this->input->get('status',true);        
                
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
        if(trim($nama)!='')
        {
            $params['where'].=' AND nama LIKE "%'.$nama.'%"';
        } 
        if(trim($no_hp)!='')
        {
            $params['where'].=' AND no_hp LIKE "%'.$no_hp.'%"';
        } 
        if(trim($no_ktp)!='')
        {
            $params['where'].=' AND no_ktp LIKE "%'.$no_ktp.'%"';
        }      
        if(trim($status)!='')
        {
            $params['where'].=' AND status LIKE "%'.$status.'%"';
        }        
          
		
        $params['order_by'] = "
            id_kasir DESC
        ";
   
        
        $query = $this->function_lib->db_query_execution($params);
        $total = $this->function_lib->db_query_execution($params, true);        
        return array("query"=>$query,"total"=>$total);
	}
	function delete($id_kasir){
		$cek = $this->function_lib->get_one('id_kasir','kasir','id_kasir="'.$id_kasir.'"');
		if (trim($cek)!="") {			
			$this->db->where('id_kasir', $id_kasir);
			$this->db->delete('kasir');
		}
		return array("status"=>200,"msg"=>"Berhasil menghapus");
	}
	function edit($id_kasir){
        $id_owner = $this->input->post('id_owner',TRUE);      
        $username = $this->input->post('username',TRUE);        
        $email = $this->input->post('email',TRUE);        
        $status_post = $this->input->post('status',TRUE);        
        $nama = $this->input->post('nama',TRUE);        
        $no_hp = $this->input->post('no_hp',TRUE);        
        $no_ktp = $this->input->post('no_ktp',TRUE);        
        $status = $this->input->post('status',TRUE);        
        $status_post = trim($status)!=""?$status:"pending";        
        $validasi = $this->validasi($id_kasir);      
        $status = 500;
        $msg = "";
        $error = isset($validasi['error']) ? $validasi['error'] : array();
        if ($validasi['status']==200) {
           	$columnUpdate = array(
                "id_owner"=> $id_owner,
				"email"=> $email,
				"username"=> $username,
				"nama"=> $nama,
				"no_hp"=> $no_hp,
				"no_ktp"=> $no_ktp,
				"status"=> $status_post,
			);
            $this->db->where('id_kasir="'.$id_kasir.'"');
            $this->db->update('kasir', $columnUpdate);
            $status = 200;
            $msg = "Berhasil Update";
        }else{
            $status = $validasi['status'];
            $msg = $validasi['msg'];
        }
        return array("status"=>$status,"msg"=>$msg,"error"=>$error);
	}
    function tambah(){
        $id_owner = $this->input->post('id_owner',TRUE);        
        $username = $this->input->post('username',TRUE);        
        $email = $this->input->post('email',TRUE);        
        $password= $this->input->post('password',TRUE);        
        $status_post = $this->input->post('status',TRUE);        
        $no_hp = $this->input->post('no_hp',TRUE);        
        $no_ktp = $this->input->post('no_ktp',TRUE);        
        $nama = $this->input->post('nama',TRUE);        
        $status_post = trim($status_post)!=""?$status_post:"pending";        
        $validasi = $this->validasi();      
        $status = 500;
        $msg = "";
        $error = isset($validasi['error']) ? $validasi['error'] : array();
        if ($validasi['status']==200) {
            $hashPassword = hash('sha512', $password . config_item('encryption_key'));     
            $columnInsert = array(
                "id_owner"=> $id_owner,
                "email"=> $email,
                "username"=> $username,
                "password"=> $hashPassword,
                "status"=> $status_post,
                "nama"=> $nama,
                "no_ktp"=> $no_ktp,
                "no_hp"=> $no_hp,
            );
            
            $this->db->insert('kasir', $columnInsert);
            $status = 200;
            $msg = "Berhasil Menambah Kasir";
        }else{
            $status = $validasi['status'];
            $msg = $validasi['msg'];
        }
        return array("status"=>$status,"msg"=>$msg,"error"=>$error);
    }
    public function lupass(){
        $this->load->library('form_validation');                    
        $data['status']=500;                                    
        $data['msg']="Gagal silahkan coba lagi";
        $this->form_validation->set_rules('email', 'email', 'trim|required');
        if ($this->form_validation->run() == TRUE) {                
            $email = $this->input->post('email', TRUE);
            $email = $this->security->sanitize_filename($email);
            $cek_email = $this->function_lib->get_one('email','kasir','status="aktif" AND email='.$this->db->escape($email).'');
            if (!empty($cek_email)) {
                $exp_datetime = date("Y-m-d H:i:s",strtotime('+10 hours'));
                $jam_sekarang = date("Y-m-d H:i:s",strtotime($exp_datetime));
                $menit_lalu = date("Y-m-d H:i:s",strtotime('+590 minutes'));
                // BETWEEN '2016-01-23 00:00:00' AND '2016-01-24 00:00:00'
                // cek hitung batasan limit request forget password range 10 menit, limit 5 request
                $jumlah_request = $this->function_lib->get_one('count(id_forget_password)','forget_password','email='.$this->db->escape($email).' AND jenis_user="kasir" AND exp_datetime BETWEEN '.$this->db->escape($menit_lalu).' AND '.$this->db->escape($jam_sekarang).'');                 
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
        $id_user = $this->function_lib->get_one('id_kasir','kasir','email='.$this->db->escape($email).'');
        $configKey = "3mai1f0rg3t";
        $exp_datetime = date("Y-m-d H:i:s",strtotime('+10 hours'));
        $token = hash('sha512', $email . $configKey . $exp_datetime);
        $this->db->set('is_active','0');
        $this->db->where('email', $email);
        $this->db->where('jenis_user', "kolektor");
        $this->db->update('forget_password');
        $columnInsert = array(
            "email" => $email,
            "jenis_user" => "kasir",
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

/* End of file Mkasir.php */
/* Location: ./application/models/Mkasir.php */