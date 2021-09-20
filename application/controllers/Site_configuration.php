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
    	$user_sess = $this->function_lib->get_user_level();
        $level = isset($user_sess['level']) ? $user_sess['level'] : "";
        $id_user = isset($user_sess['id_user']) ? $user_sess['id_user'] : "";
        $data['id_user'] = $id_user;
        $data['level'] = $level;
    	if ($this->input->post()) {
            foreach ($_POST as $key => $value) {
                $this->Msite_configuration->edit($key,$value);
                if ($key == "aset_jenis_hak") {
                	$jenisHakStr = "'" . str_replace(",", "','", $value) . "'";
                	$this->Msite_configuration->editJenisHak($jenisHakStr);
                }
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