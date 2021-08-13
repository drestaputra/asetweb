<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mkolektor extends CI_Model {


    public function data_kolektor($params,$custom_select='',$count=false,$additional_where='', $order_by="id_kolektor DESC")
    {
        
        $where_detail=' ';
        $where=" ";        
        if($count==false)
        {
            $params['order_by'] =$order_by;
        }
        $order_by=$this->input->post('order_by');
        if (trim($order_by)!="") {
            $params['order_by'] = $order_by;
        }
        $pencarian=$this->input->post('pencarian',TRUE);        
        $username=$this->input->post('username',TRUE);        
        if (trim($pencarian)!="") {
            $where.=' AND (nama_kolektor like "%'.$this->db->escape_str($pencarian).'%" OR nama_usaha like "%'.$this->db->escape_str($pencarian).'%" OR username like "%'.$this->db->escape_str($pencarian).'%")';
        }
        if (trim($username)!="") {
            $where.=' AND username like "%'.$this->db->escape_str($username).'%"';
        }
              
        if(isset($_POST["sort"]["type"]) && isset($_POST["sort"]["field"]) && ($_POST["sort"]["type"]!="" && $_POST["sort"]["field"]!="")){
            $params["order_by"]=$_POST["sort"]["field"].' '.$_POST["sort"]["type"];
        }

        $where.=$additional_where;
        $params['table'] = 'kolektor';
        $params['select'] = '*';
        
        if(trim($custom_select)!='')
        {
            $params['select'] = $custom_select;
        }
        $params['where_detail'] =" 1
        ".$where_detail.' '.$where;
        
        return array(
            'status'=>200,
            'msg'=>"sukses",
            'query'=>$this->function_lib->db_query_execution($params,false),
            'total'=>$this->function_lib->db_query_execution($params, true),
        );
    }     
    public function get_all_kolektor_username($id_owner,$id_kolektor = "0"){
    	$this->db->select('id_kolektor,username,nama');
    	$this->db->where('id_owner', $this->security->sanitize_filename($id_owner));
    	$this->db->where('id_kolektor!="'.$id_kolektor.'"');
    	$this->db->where('status', 'aktif');
    	$query = $this->db->get('kolektor');
    	return $query->result_array();
    }  
    public function request_oper_berkas($id_kolektor){
    	$status = 500;
    	$msg = "";
    	$id_nasabah = $this->input->post('id_nasabah',TRUE);
    	$id_nasabah = $this->security->sanitize_filename($id_nasabah);
    	$username = $this->input->post('username',TRUE);
    	$username = $this->security->sanitize_filename($username);
    	$password = $this->input->post('password',TRUE);
    	$password = $this->security->sanitize_filename($password);
    	$password = hash('sha512',$password . config_item('encryption_key'));        
    	// cek password kolektor yg login
    	$id_kolektor = $this->function_lib->get_one('id_kolektor','kolektor','id_kolektor="'.$id_kolektor.'" AND password="'.$password.'" AND status="aktif"');
    	$id_owner = $this->function_lib->get_one('id_owner','kolektor','id_kolektor="'.$id_kolektor.'"');
    	if (!empty($id_kolektor)) {
    		// jika kolektor ada cek apakah kolektor penerima ada
    		$status_penerima = $this->function_lib->get_one('status','kolektor','username="'.$username.'" AND id_owner="'.$id_owner.'" AND id_kolektor!="'.$id_kolektor.'"');
    		if ($status_penerima == "aktif") {
    			// cek apakah pinjaman masih dipunyai kolektor request, jika ada lolos
    			$cek_ada = $this->function_lib->get_one('id_nasabah','nasabah','id_kolektor="'.$id_kolektor.'" AND status="aktif"');
    			// cek apakah pinjaman sekarang sedang dalam proses request, jika kosong lolos
    			$cek_request = $this->function_lib->get_one('id_oper_berkas','oper_berkas','id_nasabah="'.$id_nasabah.'" AND status="proses"');
    			if (!empty($cek_ada) AND empty($cek_request)) {
    				$id_kolektor_ke = $this->function_lib->get_one('id_kolektor','kolektor','username="'.$username.'"');
    				$columnInsert = array(
    					"id_nasabah" => $id_nasabah,
    					"id_kolektor_dari" => $id_kolektor,
    					"id_kolektor_ke" => $id_kolektor_ke,
    					"status" => "proses",
    					"tgl_oper_berkas" => date("Y-m-d H:i:s")
    				);
    				$this->db->insert('oper_berkas', $columnInsert);
    				$status = 200;
    				$msg = "Berhasil meminta oper berkas kepada kolektor : ".strtoupper($username);
    			}else{
    				$status = 500;
    				$msg = "Oper berkas sedang dalam proses";
    			}
    		}else{
    			$status = 500;
    			$msg = "Kolektor penerima tidak aktif";
    		}
    	}else{
    		$status = 500;
    		$msg = "Password yang Anda inputkan salah";
    	}
    	return array("status"=>$status,"msg"=>$msg);

    }
    public function edit_profil($id_kolektor){
        $validasi = $this->validasi($id_kolektor);
        $status = isset($validasi['status']) ? $validasi['status'] : 500;
        $msg = isset($validasi['msg']) ? $validasi['msg'] : "";
        if ($status == 200) {             
            $nama = $this->input->post('nama',true);
            $email = $this->input->post('email',true);
            $no_hp = $this->input->post('no_hp',true);
            $no_ktp = $this->input->post('no_ktp',true);
            $provinsi = $this->input->post('provinsi',true);
            $kabupaten = $this->input->post('kabupaten',true);
            $kecamatan = $this->input->post('kecamatan',true);
            $alamat = $this->input->post('alamat',true);
            $warga_negara = $this->input->post('warga_negara',true);                
            $columnUpdate = array(
                "nama" => $this->security->sanitize_filename($nama),
                "email" => $this->security->sanitize_filename($email),
                "no_hp" => $this->security->sanitize_filename($no_hp),
                "no_ktp" => $this->security->sanitize_filename($no_ktp),
                "provinsi" => $this->security->sanitize_filename($provinsi),
                "kabupaten" => $this->security->sanitize_filename($kabupaten),
                "kecamatan" => $this->security->sanitize_filename($kecamatan),
                "alamat" => $this->security->sanitize_filename($alamat),
                "warga_negara" => $this->security->sanitize_filename($warga_negara)
            );
            $this->db->where('id_kolektor', $id_kolektor);
            $this->db->update('kolektor', $columnUpdate);
        }

        return array("status"=>$status,"msg"=>$msg);
    }
    public function validasi($id_kolektor){
        $status=200;
        $msg="";        
        $this->load->library('form_validation');      
        $this->form_validation->set_rules('nama', 'Nama', 'trim|required|min_length[1]|max_length[250]',
             array(
                'required'      => '%s masih kosong',                
            )
        );           
        $this->form_validation->set_rules('email', 'Email', 'trim|required|min_length[1]|max_length[250]',
             array(
                'required'      => '%s masih kosong',                
            )
        );   
        $this->form_validation->set_rules('no_hp', 'Nomor HP', 'trim|required|numeric|min_length[1]|max_length[250]',
             array(
                'required'      => '%s masih kosong',                
            )
        );      
        // $this->form_validation->set_rules('no_ktp', 'Nomor KTP', 'trim|required|numeric|min_length[1]|max_length[250]',
        //      array(
        //         'required'      => '%s masih kosong',                
        //     )
        // );      
        $this->form_validation->set_rules('provinsi', 'Provinsi', 'trim|required|min_length[1]|max_length[250]',
             array(
                'required'      => '%s masih kosong',                
            )
        );      
        $this->form_validation->set_rules('kabupaten', 'Kabupaten', 'trim|required|min_length[1]|max_length[250]',
             array(
                'required'      => '%s masih kosong',                
            )
        );  
        $this->form_validation->set_rules('kecamatan', 'Kecamatan', 'trim|required|min_length[1]|max_length[250]',
             array(
                'required'      => '%s masih kosong',                
            )
        ); 
        $this->form_validation->set_rules('alamat', 'Alamat', 'trim|required|min_length[1]|max_length[250]',
             array(
                'required'      => '%s masih kosong',                
            )
        );  
        // $this->form_validation->set_rules('warga_negara', 'Warga negara', 'trim|required|min_length[1]|max_length[250]',
        //      array(
        //         'required'      => '%s masih kosong',                
        //     )
        // );      
        if ($this->form_validation->run() == TRUE) {            
            $email = $this->input->post('email',true);
            // validasi email unique
            $cek_email = $this->function_lib->get_one('email','kolektor','id_kolektor!='.$this->db->escape($id_kolektor).' AND email='.$this->db->escape($email).'');
            if (empty($cek_email)) {
                $status=200;
                $msg="Profil berhasil diperbarui";
            }else{
                $status = 500;
                $msg = "Mohon pilih email lain, email tersebut sudah dipakai";
            }
        } else {
            $status=500;
            $msg=validation_errors(' ',' ');
        }
        return array("status"=>$status,"msg"=>$msg);
    }
    public function ganti_password($id_kolektor){
        $validasi = $this->validasi_ganti_password($id_kolektor);
        $status = isset($validasi['status']) ? $validasi['status'] : 500;
        $msg = isset($validasi['msg']) ? $validasi['msg'] : "";
        $password_baru = $this->input->post('password_baru', true);
        $hashed_password_baru = hash('sha512',$password_baru . config_item('encryption_key'));        
        if ($status == 200) {
            $columnUpdate = array("password"=> $hashed_password_baru);
            $this->db->where('id_kolektor', $id_kolektor);
            $this->db->update('kolektor', $columnUpdate);
        }
        return array("status"=>$status,"msg"=>$msg);
    }
    public function validasi_ganti_password($id_kolektor){
          $status=200;
        $msg="";        
        $this->load->library('form_validation');      
        $this->form_validation->set_rules('password_lama', 'Password Lama', 'trim|required|min_length[1]|max_length[30]',
             array(
                'required'      => '%s masih kosong',                
            )
        );   
        $this->form_validation->set_rules('password_baru', 'Password Baru', 'trim|required|min_length[1]|max_length[30]',
             array(
                'required'      => '%s masih kosong',                
            )
        );                   
        $this->form_validation->set_rules('password_konfirmasi', 'Konfirmasi password', 'trim|required|min_length[1]|max_length[30]|matches[password_baru]',
             array(
                'required'      => '%s masih kosong',                
            )
        );                   
        if ($this->form_validation->run() == TRUE) {                       
            // cek password lama
            $password_lama = $this->input->post('password_lama', true);
            $hashed_password_lama = hash('sha512',$password_lama . config_item('encryption_key'));        
            $password_baru = $this->input->post('password_baru', true);
            $password_konfirmasi = $this->input->post('password_konfirmasi', true);            
            $cek_password_lama = $this->function_lib->get_one('id_kolektor','kolektor','id_kolektor='.$this->db->escape($id_kolektor).' AND password='.$this->db->escape($hashed_password_lama).'');
            if (!empty($cek_password_lama)) {
                $status=200;
                $msg="Berhasil mengubah password";           
            }else{
                $status = 500;
                $msg = "Password lama tidak sesuai";
            }
        } else {
            $status=500;
            $msg=validation_errors(' ',' ');
        }
        return array("status"=>$status,"msg"=>$msg);
    }
}

/* End of file Mkolektor.php */
/* Location: ./application/models/Mkolektor.php */