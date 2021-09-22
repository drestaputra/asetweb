<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Malamat extends CI_Model {

	function get_all_provinsi(){		
	      $this->db->select('id, nama as text');
	      $this->db->order_by('nama', 'ASC');	      
	      $query = $this->db->get('provinsi');      
	      $data = $query->result();            
	      return $data;
	}

	function get_all_kabupaten($id_provinsi){
		$data = array();
		if (floatval($id_provinsi != 0 )) {
	      $this->db->select('id, nama as text');
	      $this->db->order_by('nama', 'ASC');	      	      
	      $this->db->where('id_provinsi', $this->security->sanitize_filename($id_provinsi));
	      $query = $this->db->get('kabupaten');      
	      $data = $query->result();            
	  	}
	     return $data;
	}

	function get_all_kecamatan($id_kabupaten){
		$data = array();
		if (floatval($id_kabupaten != 0 )) {
	      $this->db->select('id, nama as text');
	      $this->db->order_by('nama', 'ASC');	      	      	      
	      $this->db->where('id_kabupaten', $this->security->sanitize_filename($id_kabupaten));
	      $query = $this->db->get('kecamatan');      
	      $data = $query->result();            
		}
	     return $data;
	}
	function getAllProvinsi(){		
	      $this->db->select('id, nama as text');
	      $this->db->order_by('nama', 'ASC');	      
	      $query = $this->db->get('provinsi');      
	      $data = $query->result_array();            
	      return $data;
	}

	function getAllKabupaten($id_provinsi){
		$data = array();
		if (floatval($id_provinsi != 0 )) {
	      $this->db->select('id, nama as text');
	      $this->db->order_by('nama', 'ASC');	      	      
	      $this->db->where('id_provinsi', $this->security->sanitize_filename($id_provinsi));
	      $query = $this->db->get('kabupaten');      
	      $data = $query->result_array();            
	  	}
	     return $data;
	}

	function getAllKecamatan($id_kabupaten){
		$data = array();
		if (floatval($id_kabupaten != 0 )) {
	      $this->db->select('id, nama as text');
	      $this->db->order_by('nama', 'ASC');	      	      	      
	      $this->db->where('id_kabupaten', $this->security->sanitize_filename($id_kabupaten));
	      $query = $this->db->get('kecamatan');      
	      $data = $query->result_array();            
		}
	     return $data;
	}
	function getAllDsea($id_kecamatan){
		$data = array();
		if (floatval($id_kecamatan != 0 )) {
	      $this->db->select('id, nama as text');
	      $this->db->order_by('nama', 'ASC');	      	      	      
	      $this->db->where('id_kecamatan', $this->security->sanitize_filename($id_kecamatan));
	      $query = $this->db->get('desa');      
	      $data = $query->result_array();            
		}
	    return $data;
	}
	

}

/* End of file Malamat.php */
/* Location: ./application/models/Malamat.php */