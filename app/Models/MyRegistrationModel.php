<?php
namespace App\Models;
use CodeIgniter\Model;

class MyRegistrationModel extends Model
{

    protected $db;

    public function __construct(){
        parent::__construct();
        $this->session = session();
        $this->request = \Config\Services::request();
		$this->db = \Config\Database::connect();
		$this->cuser = $this->session->get('__xsys_myuserzicas__');
        
    }

	public function user_save() {

		$company_code = $this->request->getPostGet('company_code');
		$username = $this->request->getPostGet('username');
		$emailto = $this->request->getPostGet('email');
		$hash_value = $this->request->getPostGet('hash_value');

		$emailsend = \Config\Services::email();

		$hash_password = hash('sha512', $hash_value);

		// =========================
		// SAVE USER
		// =========================
		$query = $this->db->query("
			INSERT INTO `myua_user`(
				`username`,
				`hash_password`,
				`hash_value`,
				`company_code`,
				`email`,
				`added_by`
			)
			VALUES(
				'$username',
				'$hash_password',
				'$hash_value',
				'$company_code',
				'$emailto',
				'{$this->cuser}'
			)
		");

		// =========================
		// EMAIL CONFIGURATION
		// =========================
		$emailsend->setFrom(
			'largaphilippines.com',
			'LARGA INTERNATIONAL LOGISTICS INC.'
		);

		$emailsend->setTo($emailto);

		$emailsend->setSubject(
			'LARGA INTERNATIONAL LOGISTICS INC. | Client Portal Registration Successful'
		);

		$emailsend->setMailType('html');

		// =========================
		// PROFESSIONAL EMAIL TEMPLATE
		// =========================
		$message = '

		<div style="
			background:#f3f4f6;
			padding:40px 20px;
			font-family:Arial,sans-serif;
		">

			<div style="
				max-width:720px;
				margin:auto;
				background:#ffffff;
				border-radius:18px;
				overflow:hidden;
				box-shadow:0 10px 30px rgba(0,0,0,0.08);
			">

				<!-- HEADER -->
				<div style="
					background:linear-gradient(135deg,#003366,#0052cc);
					padding:50px 40px;
					text-align:center;
					color:#ffffff;
				">

					<div style="
						display:inline-block;
						padding:12px 22px;
						background:rgba(255,255,255,0.12);
						border-radius:12px;
						margin-bottom:20px;
						font-size:14px;
						letter-spacing:1px;
						font-weight:600;
					">
						LARGA CLIENT PORTAL
					</div>

					<h1 style="
						margin:0;
						font-size:34px;
						font-weight:700;
						letter-spacing:0.5px;
						line-height:1.3;
					">
						LARGA INTERNATIONAL LOGISTICS INC.
					</h1>

					<p style="
						margin-top:15px;
						font-size:16px;
						color:#dbeafe;
						line-height:1.7;
					">
						Client Portal Registration Successful
					</p>

				</div>

				<!-- BODY -->
				<div style="
					padding:45px 40px;
					color:#374151;
				">

					<h2 style="
						margin-top:0;
						color:#111827;
						font-size:26px;
					">
						Welcome to the Client Portal
					</h2>

					<p style="
						font-size:15px;
						line-height:1.9;
						margin-top:20px;
					">
						Dear <strong>'.$username.'</strong>,
					</p>

					<p style="
						font-size:15px;
						line-height:1.9;
					">
						Your registration to the 
						<strong>LARGA INTERNATIONAL LOGISTICS INC.</strong> 
						Client Portal has been successfully completed.
					</p>

					<p style="
						font-size:15px;
						line-height:1.9;
					">
						You may now access your client account and manage your logistics transactions, shipment monitoring, records, and portal activities securely through our online system.
					</p>

					<!-- ACCOUNT DETAILS -->
					<div style="
						margin-top:30px;
						background:#f9fafb;
						border:1px solid #e5e7eb;
						border-radius:12px;
						padding:25px;
					">

						<h3 style="
							margin-top:0;
							margin-bottom:20px;
							font-size:18px;
							color:#111827;
						">
							Client Portal Account Information
						</h3>

						<table width="100%" cellpadding="8" cellspacing="0">

							<tr>
								<td style="
									font-weight:bold;
									width:180px;
									color:#374151;
								">
									Company Code:
								</td>

								<td style="color:#111827;">
									'.$company_code.'
								</td>
							</tr>

							<tr>
								<td style="
									font-weight:bold;
									color:#374151;
								">
									Username:
								</td>

								<td style="color:#111827;">
									'.$username.'
								</td>
							</tr>

							<tr>
								<td style="
									font-weight:bold;
									color:#374151;
								">
									Temporary Password:
								</td>

								<td style="
									color:#dc2626;
									font-weight:bold;
									letter-spacing:1px;
								">
									'.$hash_value.'
								</td>
							</tr>

							<tr>
								<td style="
									font-weight:bold;
									color:#374151;
								">
									Registered Email:
								</td>

								<td style="color:#111827;">
									'.$emailto.'
								</td>
							</tr>

							<tr>
								<td style="
									font-weight:bold;
									color:#374151;
								">
									Account Status:
								</td>

								<td style="
									color:#10b981;
									font-weight:bold;
								">
									ACTIVE
								</td>
							</tr>

						</table>

					</div>

					<!-- SECURITY NOTICE -->
					<div style="
						margin-top:25px;
						padding:20px;
						background:#fef3c7;
						border-left:5px solid #f59e0b;
						border-radius:10px;
					">

						<p style="
							margin:0;
							font-size:14px;
							line-height:1.8;
							color:#92400e;
						">
							For security purposes, we strongly recommend changing your password immediately after your first login to the Client Portal.
						</p>

					</div>

					<!-- FOOTER MESSAGE -->
					<p style="
						margin-top:35px;
						font-size:15px;
						line-height:1.9;
					">
						Thank you for choosing 
						<strong>LARGA INTERNATIONAL LOGISTICS INC.</strong>.
						We look forward to providing you with reliable and efficient logistics services.
					</p>

					<p style="
						margin-top:40px;
						font-size:15px;
						line-height:1.8;
					">
						Sincerely,<br><br>

						<strong>
							LARGA INTERNATIONAL LOGISTICS INC.
						</strong><br>

						Client Portal Administration Team
					</p>

				</div>

				<!-- FOOTER -->
				<div style="
					background:#111827;
					color:#9ca3af;
					text-align:center;
					padding:22px;
					font-size:13px;
				">
					© '.date('Y').' LARGA INTERNATIONAL LOGISTICS INC.
					All Rights Reserved.
				</div>

			</div>

		</div>

		';

		$emailsend->setMessage($message);

		// =========================
		// SEND EMAIL
		// =========================
		$emailSent = $emailsend->send();

		// =========================
		// RETURN RESPONSE
		// =========================
		if($query){

			return [
				'status' => 'success',
				'message' => 'Registration Saved Successfully',
				'email_status' => $emailSent
			];

		} else {

			return [
				'status' => 'error',
				'message' => 'Registration Failed'
			];

		}
	}

	
} //end main class
?>