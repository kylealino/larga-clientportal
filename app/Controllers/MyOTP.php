<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class MyOTP extends BaseController
{
    public function __construct()
	{
		$this->request = \Config\Services::request();
        $this->myotp = model('App\Models\MyOTPModel');
        $this->db = \Config\Database::connect();
        $this->session = session();
        $this->db = \Config\Database::connect();
        $this->cuser = $this->session->get('__xsys_myuserzicas__');
	}

    public function index() {
        
        $meaction = $this->request->getPostGet('meaction');
    
        switch ($meaction) {
            case 'MAIN': 
                return view('MyOTP');
                break;

            case 'OTP-SAVE':
                $result = $this->myotp->user_save();
                return $this->response->setJSON($result);
                break;

            case 'OTP-RESEND':
                $result = $this->myotp->resend_otp();
                return $this->response->setJSON($result);
                break;
            
        }
    }
    
}
