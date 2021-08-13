<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class site_configuration extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Msite_configuration');		
		$this->function_lib->cek_auth(array("super_admin","admin"));
	}

    public function edit(){

    	if ($this->input->post()) {
            foreach ($_POST as $key => $value) {
                $this->Msite_configuration->edit($key,$value);
            }
			redirect(base_url('pengaturan/edit?status=200&msg='.base64_encode("Berhasil edit").''));
    	}
        foreach ($this->Msite_configuration->getData() as $arrData) {
            $data[$arrData['configuration_index']]=$arrData['configuration_value'];
        }
    	$this->load->view('site_configuration/edit', $data, FALSE);
    }

}

/* End of file site_configuration.php */
/* Location: ./application/controllers/site_configuration.php */