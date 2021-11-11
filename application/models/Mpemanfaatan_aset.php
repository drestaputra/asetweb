<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mpemanfaatan_aset extends CI_Model {

	public function deletePemanfaatanTanahByIdAset($id_aset = '')
	{
		$this->db->where('id_aset', $id_aset);
		$this->db->delete('pemanfaatan_aset');
	}
	public function insertDataPemanfaatanAset($pemanfaatanArr, $id_aset){
		foreach ($pemanfaatanArr as $key => $value) {
			$insertdata = array(
				"id_pemanfaatan" => $value,
				"id_aset" => $id_aset
			);
			$this->db->insert('pemanfaatan_aset', $insertdata);
		}
	}
	public function getPemanfaatanByIdAset($id_aset){
		// SELECT GROUP_CONCAT(penggunaan) FROM `pemanfaatan` WHERE `id_aset_pemanfaatan` = '2'
		$this->db->select('GROUP_CONCAT(penggunaan) as pemanfaatan');
		$this->db->where('id_aset_pemanfaatan', $id_aset);
		$query = $this->db->get('pemanfaatan');
		$dataPemanfaatan = $query->row_array();
		$pemanfaatan = isset($dataPemanfaatan['pemanfaatan']) ? $dataPemanfaatan['pemanfaatan'] : "";
		
		return $pemanfaatan;
		
		
		// return $query->row_array();
	}
	public function getAllPemanfaatanAsetById($id_aset){
		$this->db->select('id_pemanfaatan');
		$this->db->where('id_aset', $id_aset);
		$query = $this->db->get('pemanfaatan_aset');
		$data = $query->result_array();
		return $data;

	}
}

/* End of file Mpemanfaatan_aset.php */
/* Location: ./application/models/Mpemanfaatan_aset.php */