<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	public function __construct()
	{
		parent::__construct();		
	}
	public function index()
	{
		redirect(base_url('aset'));
	}


}

/* End of file Dashboard.php */
/* Location: ./application/controllers/pengurus_barang/Dashboard.php */