<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mabout extends CI_Model {

	function get_about(){
		// SELECT * FROM `site_configuration` where configuration_index in ("app_dev","app_dev_web")
		$where='configuration_index in ("app_name","app_description","app_dev","app_dev_web","app_contact_ig","app_contact_fb","app_contact_twitter","app_contact_mail","app_contact_wa","app_contact_phone","app_contact_address")';
		$this->db->where($where);
		$this->db->order_by('configuration_index', 'ASC');
		$query=$this->db->get("site_configuration");
		$data = $query->result_array();
		$output = array();
		foreach ($data as $key => $value) {
			$output[$value['configuration_index']] = $value['configuration_value'];
		}
		return($output);
	}

}

/* End of file Mabout.php */
/* Location: ./application/models/Mabout.php */