<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Malamat extends CI_Model {

	function provinsi (){
		$this->db->order_by('nama', 'ASC');
		$query = $this->db->get('provinsi');
		return $query->result_array();
	}
	function kabupaten (){
		$id_provinsi = $this->input->post('id_provinsi',TRUE);
		$this->db->where('id_provinsi', $id_provinsi,TRUE);
		$this->db->order_by('nama', 'ASC');
		$query = $this->db->get('kabupaten');
		return $query->result_array();
	}
	function kecamatan (){
		$id_kabupaten = $this->input->post('id_kabupaten',TRUE);
		$this->db->where('id_kabupaten', $id_kabupaten, TRUE);
		$this->db->order_by('nama', 'ASC');
		$query = $this->db->get('kecamatan');
		return $query->result_array();
	}
	function kelurahan (){
		$id_kecamatan = $this->input->post('id_kecamatan',TRUE);
		$this->db->where('id_kecamatan', $id_kecamatan, TRUE);
		$this->db->order_by('nama', 'ASC');
		$query = $this->db->get('desa');
		return $query->result_array();
	}
	

}

/* End of file Malamat.php */
/* Location: ./application/models/Malamat.php */