<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class MyRegistration extends BaseController
{
    public function __construct()
	{
		$this->request = \Config\Services::request();
        $this->myregistration = model('App\Models\MyRegistrationModel');
        $this->db = \Config\Database::connect();
        $this->session = session();
        $this->db = \Config\Database::connect();
        $this->cuser = $this->session->get('__xsys_myuserzicas__');
	}

    public function index() {
        
        $meaction = $this->request->getPostGet('meaction');
    
        switch ($meaction) {
            case 'MAIN': 
                return view('MyRegistration');
                break;

            case 'REGISTER-SAVE':
                $result = $this->myregistration->user_save();
                return $this->response->setJSON($result);
                break;
            
        }
    }
    
}
