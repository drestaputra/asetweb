<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Msite_configuration extends CI_Model {

	function edit($key,$value){
		$this->db->where('configuration_index', $key);
		$count = array(
			'configuration_value' => $value, 
		);
		$this->db->update('site_configuration', $count);
	}

	function getData()
	{
		return $this->db->query('SELECT * FROM site_configuration')->result_array();
	}
}

/* End of file Msite_configuration.php */
/* Location: ./application/models/Msite_configuration.php */