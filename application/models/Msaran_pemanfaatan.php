<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Msaran_pemanfaatan extends CI_Model {

	function getAllSaranPemanfaatan(){
		$this->db->where('status_saran_pemanfaatan != "deleted"');
		$query = $this->db->get('saran_pemanfaatan');
		return $query->result_array();
	}
	

}

/* End of file Mpemanfaatan.php */
/* Location: ./application/models/Mpemanfaatan.php */