<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	public function __construct()
	{
		parent::__construct();		
	}
	public function index()
	{
		$this->function_lib->cek_auth(array('koordinator'));		
		$data = array();
		$this->load->view('koordinator/dashboard/index',$data,false);	
	}


}

/* End of file Dashboard.php */
/* Location: ./application/controllers/koordinator/Dashboard.php */