<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Msaran_pemanfaatan_aset extends CI_Model {

	public function deleteSaranPemanfaatanTanahByIdAset($id_aset = '')
	{
		$this->db->where('id_aset', $id_aset);
		$this->db->delete('saran_pemanfaatan_aset');
	}
	public function insertDataSaranPemanfaatanAset($saratPemanfaatanArr, $id_aset){
		foreach ($saratPemanfaatanArr as $key => $value) {
			$insertdata = array(
				"id_saran_pemanfaatan" => $value,
				"id_aset" => $id_aset
			);
			$this->db->insert('saran_pemanfaatan_aset', $insertdata);
		}
	}
	public function getSaranPemanfaatanByIdAset($id_aset){
		// SELECT GROUP_CONCAT((SELECT isi_pemanfaatan FROM pemanfaatan WHERE id_pemanfaatan=pemanfaatan_aset.id_pemanfaatan)) FROM pemanfaatan_aset
		$this->db->select('GROUP_CONCAT((SELECT isi_saran_pemanfaatan FROM saran_pemanfaatan WHERE id_saran_pemanfaatan=saran_pemanfaatan_aset.id_saran_pemanfaatan)) as saran_pemanfaatan');
		$this->db->where('id_aset', $id_aset);
		$query = $this->db->get('saran_pemanfaatan_aset');
		$dataSaranPemanfaatan = $query->row_array();
		$saran_pemanfaatan = isset($dataSaranPemanfaatan['saran_pemanfaatan']) ? $dataSaranPemanfaatan['saran_pemanfaatan'] : "";
		
		return $saran_pemanfaatan;
	}
	public function getAllSaranPemanfaatanAsetById($id_aset){
		$this->db->select('id_saran_pemanfaatan');
		$this->db->where('id_aset', $id_aset);
		$query = $this->db->get('saran_pemanfaatan_aset');
		$data = $query->result_array();
		return $data;
	}
}

/* End of file Mpemanfaatan_aset.php */
/* Location: ./application/models/Mpemanfaatan_aset.php */