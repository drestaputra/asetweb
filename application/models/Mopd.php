<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xls;

class Mopd extends CI_Model {

	function getAllOpd($where = ""){
		if (!empty($where) AND trim($where) != "") {
			$this->db->where($where);
		}
		$this->db->where('status_opd != "deleted"');
		$this->db->order_by('label_opd', 'ASC');
		$query = $this->db->get('opd');
		return $query->result_array();
	}
	function upload(){
		$user_sess = $this->function_lib->get_user_level();
        $level = isset($user_sess['level']) ? $user_sess['level'] : "";
        $id_user = isset($user_sess['id_user']) ? $user_sess['id_user'] : "";

		$status = 500;
		$msg = "";
		$strTime = time();
		$config['upload_path'] = './assets/excel/opd';
		$config['allowed_types'] = 'xlsx|xls';
		$config['max_size']  = '5000';
		$config['remove_spaces'] = true;
		$config['encrypt_name'] = true;
		
		$this->load->library('upload', $config);
		
		if ( ! $this->upload->do_upload('file_opd')){
			$msg = $this->upload->display_errors('','');
			$status = 500;
		}
		else{
			$status = 200;
			$msg = "Berhasil mengunggah file";
			$data = $this->upload->data();
			
		}
		return array(
			"status" => $status,
			"msg" => $msg,
			"data" => $data
		);
	}
	function save_import($filename = ""){
		$status = 500;
		$msg = "";
		$path = "./assets/excel/opd/";
        if (empty($filename)) {
        	$status = 500;
        	$msg = "File Excel Kosong";
            return array("status" => $status, "msg" => $msg);
        }else if(!file_exists($path.$filename)){
            $status = 500;
        	$msg = "File import tidak ditemukan, silahkan upload ulang";
            return array("status" => $status, "msg" => $msg);
        }
        $ext = pathinfo($filename, PATHINFO_EXTENSION);

        $reader = new PhpOffice\PhpSpreadsheet\Reader\Xlsx();

        if (!empty($ext) && $ext == "xls") {
        	$reader = new PhpOffice\PhpSpreadsheet\Reader\Xls();
        }
        $spreadsheet = $reader->load($path.$filename); // Load file yang tadi diupload ke folder tmp
        $sheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
        // hapus kolom header
        $sheet = array_splice($sheet, 1);
        $insertData = array();
        foreach ($sheet as $key => $value) {
        	if (isset($value['A']) && !empty($value['A']) && isset($value['B']) && !empty($value['B'])) {
	        	$insertData[$key]["nama_opd"] = isset($value['B']) ? $value['B'] : "";
	        	$insertData[$key]["label_opd"] = isset($value['C']) ? $value['C'] : "";
	        	$insertData[$key]["kode_opd"] = isset($value['D']) ? $value['D'] : "";
	        	$insertData[$key]["alamat_opd"] = isset($value['D']) ? $value['D'] : "";
	        	$insertData[$key]["status_opd"] = "aktif";
        	}
        }
        $this->db->insert_batch('opd', $insertData); 
        if (file_exists($path.$filename)) {
        	unlink($path.$filename);
        }
        $status = 200;
        $msg = "Berhasil, ".count($insertData)." Data Berhasil Ditambahkan";
        return array("status" => $status, "msg" => $msg);
	}

}

/* End of file Mopd.php */
/* Location: ./application/models/Mopd.php */