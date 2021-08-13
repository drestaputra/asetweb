<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mpengaduan extends CI_Model {

	public function kirim($id_owner)
	{		
		$validasi = $this->validasi();
		$status = isset($validasi['status']) ? $validasi['status'] : 500;
		$msg = isset($validasi['msg']) ? $validasi['msg'] : "";
		if (isset($validasi['status']) AND $validasi['status'] == 200) {
			$nama_lengkap = $this->input->post('nama_lengkap',TRUE);
			$email = $this->input->post('email',TRUE);
			$isi_aduan = $this->input->post('isi_aduan',TRUE);		
			$columnInsert = array(
				"nama_lengkap_pengirim" => $this->security->sanitize_filename($nama_lengkap),
				"email_pengirim" => $this->security->sanitize_filename($email),
				"isi_pengaduan" => $this->security->sanitize_filename($isi_aduan),
				"tgl_pengaduan" => date("Y-m-d H:i:s"),
			);
			if (!empty($id_owner) OR $id_owner!="0") {
				$columnInsert['id_owner'] = $id_owner;
			}
			$this->db->insert('pengaduan', $columnInsert);
		}
		return array("status"=>$status,"msg"=>$msg);
	}
	 function validasi(){
        $status=200;
        $msg="";
        $this->load->library('form_validation');      
        $this->form_validation->set_rules('nama_lengkap', 'Nama Lengkap', 'trim|required',
             array(
                'required'      => '%s masih kosong',                
            )
        );   
        $this->form_validation->set_rules('email', 'Email', 'trim|required|min_length[1]|max_length[250]',
             array(
                'required'      => '%s masih kosong',                
            )
        );      
        $this->form_validation->set_rules('isi_aduan', 'Isi Aduan', 'trim|required',
             array(
                'required'      => '%s masih kosong',                
            )
        );      
        if ($this->form_validation->run() == TRUE) {
            $status=200;
            $msg="Terima kasih, keluhan Anda akan segera kami tanggapi melalui email";
        } else {
            $status=500;
            $msg=validation_errors(' ',' ');
        }
        return array("status"=>$status,"msg"=>$msg);
    }
       
}

/* End of file Mpengaduan.php */
/* Location: ./application/models/Mpengaduan.php */