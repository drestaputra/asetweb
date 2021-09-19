<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
	

}

/* End of file Mopd.php */
/* Location: ./application/models/Mopd.php */