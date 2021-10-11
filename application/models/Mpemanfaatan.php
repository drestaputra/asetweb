<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mpemanfaatan extends CI_Model {

	function getAllPemanfaatan(){
		$this->db->where('status_pemanfaatan != "deleted"');
		$query = $this->db->get('pemanfaatan');
		return $query->result_array();
	}
	

}

/* End of file Mpemanfaatan.php */
/* Location: ./application/models/Mpemanfaatan.php */