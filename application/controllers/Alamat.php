<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Alamat extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Malamat');
	}
	public function get_all_provinsi()
	{
		header("Content-type: application/json");
        $data = $this->Malamat->get_all_provinsi();
        echo(json_encode($data));
	}

	public function get_all_kabupaten($id_provinsi)
	{
		header("Content-type: application/json");
		$id_provinsi = $this->security->sanitize_filename($id_provinsi);
        $data = $this->Malamat->get_all_kabupaten($id_provinsi);
        echo(json_encode($data));
	}

	public function get_all_kecamatan($id_provinsi)
	{
		header("Content-type: application/json");
		$id_provinsi = $this->security->sanitize_filename($id_provinsi);
        $data = $this->Malamat->get_all_kecamatan($id_provinsi);
        echo(json_encode($data));
	}

}

/* End of file Alamat.php */
/* Location: ./application/controllers/Alamat.php */