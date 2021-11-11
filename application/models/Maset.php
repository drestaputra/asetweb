<?php
defined('BASEPATH') OR exit('No direct script access allowed');



class Maset extends CI_Model {

	function upload(){
		$user_sess = $this->function_lib->get_user_level();
        $level = isset($user_sess['level']) ? $user_sess['level'] : "";
        $id_user = isset($user_sess['id_user']) ? $user_sess['id_user'] : "";

		$status = 500;
		$msg = "";
		$strTime = time();
		$config['upload_path'] = './assets/excel/aset';
		$config['allowed_types'] = 'xlsx|xls';
		$config['max_size']  = '5000';
		$config['remove_spaces'] = true;
		$config['encrypt_name'] = true;
		
		$this->load->library('upload', $config);
		
		if ( ! $this->upload->do_upload('file_aset')){
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
		$path = "./assets/excel/aset/";
		$user_sess = $this->function_lib->get_user_level();
        $level = isset($user_sess['level']) ? $user_sess['level'] : "";
        $id_user = isset($user_sess['id_user']) ? $user_sess['id_user'] : "";

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
    			
    			$luas_tanah = isset($value['E']) ? $value['E'] : "";
    			$luas_tanah = str_replace(",","",$luas_tanah);

    			$harga_perolehan = isset($value['M']) ? $value['M'] : "";
    			$harga_perolehan = str_replace(",","",$harga_perolehan);

    			$tanggal_sertifikat = isset($value['I']) ? $value['I'] : "";
    			$tanggal_sertifikat = date("Y-m-d", strtotime($tanggal_sertifikat));

	        	$insertData[$key]["nama_aset"] = isset($value['B']) ? $value['B'] : "";
	        	$insertData[$key]["kode_barang"] = isset($value['C']) ? $value['C'] : "";
	        	$insertData[$key]["register"] = isset($value['D']) ? $value['D'] : "";
	        	$insertData[$key]["luas_tanah"] = floatval($luas_tanah);
	        	$insertData[$key]["tahun_perolehan"] = isset($value['F']) ? intval($value['F']) : "";
	        	$insertData[$key]["alamat"] = isset($value['G']) ? $value['G'] : "";
	        	$insertData[$key]["jenis_hak"] = isset($value['H']) ? $value['H'] : "";
	        	$insertData[$key]["tanggal_sertifikat"] = $tanggal_sertifikat;
	        	$insertData[$key]["nomor_sertifikat"] = isset($value['J']) ? $value['J'] : "";
	        	$insertData[$key]["penggunaan"] = isset($value['K']) ? $value['K'] : "";
	        	$insertData[$key]["asal_perolehan"] = isset($value['L']) ? $value['L'] : "";
	        	$insertData[$key]["harga_perolehan"] = floatval($harga_perolehan);
	        	$insertData[$key]["keterangan"] = isset($value['N']) ? $value['N'] : "";
	        	// data default
	        	$insertData[$key]["status_aset"] = "idle";
	        	$insertData[$key]["status_verifikasi_aset"] = "sedang_diverifikasi";
	        	$insertData[$key]["created_by_id"] = $id_user;
	        	$insertData[$key]["created_by"] = "import_".$level;
        	}
        }
        
        $this->db->insert_batch('aset', $insertData); 
        if (file_exists($path.$filename)) {
        	unlink($path.$filename);
        }
        $status = 200;
        $msg = "Berhasil, ".count($insertData)." Data Berhasil Ditambahkan";
        return array("status" => $status, "msg" => $msg);
	}
	function cari_aset($data)
	{
		$nama_aset = isset($data['nama_aset']) ? $data['nama_aset'] : "";
		$id_opd = isset($data['id_opd']) ? $data['id_opd'] : "";
		$id_kecamatan = isset($data['id_kecamatan']) ? $data['id_kecamatan'] : "";
		$id_desa = isset($data['id_desa']) ? $data['id_desa'] : "";
		if (!empty($id_opd)) {
			$this->db->where('id_opd_aset', $id_opd);
		}if (!empty($nama_aset)) {
			$this->db->where('nama_aset LIKE "%'.$this->db->escape_str($nama_aset).'%" OR kode_barang LIKE "%'.$this->db->escape_str($nama_aset).'%"');
		}
		$this->db->where('status_aset!="deleted"');
		if (!empty($id_kecamatan)) {
			$this->db->where('id_kecamatan', $id_kecamatan);
		}
		if (!empty($id_desa)) {
			$this->db->where('id_desa', $id_desa);
		}
		$this->db->select('id_aset,nama_aset,kode_barang,register,luas_tanah,alamat,jenis_hak,tanggal_sertifikat,nomor_sertifikat,penggunaan,keterangan,latitude,longitude,status_aset,status_verifikasi_aset');
		$query = $this->db->get('aset',500,0);
		$data = $query->result_array();
		return $data;
	}
	function count_aset_by_status($status = "idle", $where =""){
		if (!empty($where) AND trim($where) != "") {
			$this->db->where($where);
		}
		// SELECT count(id_aset),status_aset FROM `aset` GROUP BY status_aset
		$this->db->select('count(id_aset) as total');
		$this->db->group_by("status_aset");
		$this->db->where('status_aset', $status);
		$query = $this->db->get('aset');
		$data = $query->row_array();
		$total = isset($data['total']) ?  floatval($data['total']) : 0;
		return $total;
	}
	function count_aset_by_status_verifikasi($status = "valid", $where = ""){
		if (!empty($where) AND trim($where) != "") {
			$this->db->where($where);
		}
		// SELECT count(id_aset),status_aset FROM `aset` GROUP BY status_aset
		$this->db->select('count(id_aset) as total');
		$this->db->group_by("status_verifikasi_aset");
		$this->db->where('status_aset!="deleted"');
		$this->db->where('status_verifikasi_aset', $status);
		$query = $this->db->get('aset');
		$data = $query->row_array();
		$total = isset($data['total']) ?  floatval($data['total']) : 0;
		return $total;
	}
	// SELECT (SELECt count(id_aset) as total FROM aset WHERE aset.id_opd_aset = opd.id_opd) as total, nama_opd,label_opd FROM `opd` GROUP by id_opd
	function count_aset_by_opd($where = ""){
		if (!empty($where) AND trim($where) != "") {
			$this->db->where($where);
		}
		$this->db->select('(SELECt count(id_aset) as total FROM aset WHERE aset.id_opd_aset = opd.id_opd) as total, nama_opd,label_opd');
		$this->db->group_by("id_opd");
		$this->db->order_by('label_opd', 'asc');
		$this->db->where('status_opd!="deleted"');
		$query = $this->db->get('opd');
		return $query->result_array();
		
	}

}

/* End of file Maset.php */
/* Location: ./application/models/Maset.php */