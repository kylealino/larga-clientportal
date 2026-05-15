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

	public function tmp_user_save() {

		$company_code = $this->request->getPostGet('company_code');
		$username = $this->request->getPostGet('username');
		$emailto = $this->request->getPostGet('email');
		$hash_value = $this->request->getPostGet('hash_value');

		$emailsend = \Config\Services::email();

		$hash_password = hash('sha512', $hash_value);
		
		// =========================
		// GENERATE 6-DIGIT OTP CODE
		// =========================
		$otp_code = sprintf("%06d", mt_rand(1, 999999));
		
		// OTP expiry timestamp (15 minutes from now)
		$otp_expiry = date('Y-m-d H:i:s', strtotime('+15 minutes'));
		

		// =========================
		// SAVE USER WITH OTP CODE
		// =========================
		$query = $this->db->query("
			INSERT INTO `tmp_myua_user`(
				`username`,
				`hash_password`,
				`hash_value`,
				`company_code`,
				`email`,
				`otp_code`,
				`otp_expiry`
			)
			VALUES(
				'$username',
				'$hash_password',
				'$hash_value',
				'$company_code',
				'$emailto',
				'$otp_code',
				'$otp_expiry'
			)
		");

		// =========================
		// EMAIL CONFIGURATION
		// =========================
		$emailsend->setFrom(
			'noreply@largaphilippines.com',
			'LARGA INTERNATIONAL LOGISTICS INC.'
		);

		$emailsend->setTo($emailto);

		$emailsend->setSubject(
			'LARGA INTERNATIONAL LOGISTICS INC. | Email Verification Required'
		);

		$emailsend->setMailType('html');

		// =========================
		// PROFESSIONAL EMAIL TEMPLATE WITH OTP
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
						EMAIL VERIFICATION REQUIRED
					</div>

					<h1 style="
						margin:0;
						font-size:34px;
						font-weight:700;
						letter-spacing:0.5px;
						line-height:1.3;
					">
						Verify Your Email Address
					</h1>

					<p style="
						margin-top:15px;
						font-size:16px;
						color:#dbeafe;
						line-height:1.7;
					">
						Complete your registration to access the Client Portal
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
						One-Time Password (OTP) Verification
					</h2>

					<p style="
						font-size:15px;
						line-height:1.9;
						margin-top:20px;
					">
						Dear <strong>' . htmlspecialchars($username) . '</strong>,
					</p>

					<p style="
						font-size:15px;
						line-height:1.9;
					">
						Thank you for registering with 
						<strong>LARGA INTERNATIONAL LOGISTICS INC.</strong> 
						Client Portal.
					</p>

					<p style="
						font-size:15px;
						line-height:1.9;
					">
						To complete your registration and activate your account,
						please use the One-Time Password (OTP) below:
					</p>

					<!-- OTP CODE - HIGHLIGHTED -->
					<div style="
						margin:30px 0;
						text-align:center;
					">
						<div style="
							display:inline-block;
							background:#f0f9ff;
							border:2px solid #0052cc;
							border-radius:16px;
							padding:20px 40px;
						">
							<span style="
								font-size:42px;
								font-weight:800;
								letter-spacing:8px;
								color:#0052cc;
								font-family:monospace;
							">' . $otp_code . '</span>
						</div>
					</div>

					<div style="
						background:#fef3c7;
						border-left:5px solid #f59e0b;
						border-radius:10px;
						padding:15px 20px;
						margin:20px 0;
					">
						<p style="
							margin:0;
							font-size:14px;
							color:#92400e;
							line-height:1.6;
						">
							<strong>⚠️ Important:</strong> This OTP is valid for 
							<strong>15 minutes</strong> from the time of this email.
							For security reasons, do not share this code with anyone.
						</p>
					</div>

					<p style="
						font-size:15px;
						line-height:1.9;
					">
						<strong>How to verify:</strong>
					</p>

					<ol style="
						margin:15px 0 15px 20px;
						line-height:1.8;
						font-size:14px;
					">
						<li>Return to the LARGA Client Portal registration page</li>
						<li>Enter the 6-digit OTP code shown above</li>
						<li>Click "Verify Email" to activate your account</li>
						<li>You will be redirected to the login page upon successful verification</li>
					</ol>

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
							Registration Summary
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
									' . htmlspecialchars($company_code) . '
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
									' . htmlspecialchars($username) . '
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
									' . htmlspecialchars($emailto) . '
								</td>
							</tr>
							<tr>
								<td style="
									font-weight:bold;
									color:#374151;
								">
									OTP Expiry:
								</td>
								<td style="color:#dc2626;">
									' . date('F d, Y h:i A', strtotime($otp_expiry)) . '
								</td>
							</tr>
						</table>

					</div>

					<!-- TROUBLESHOOTING -->
					<div style="
						margin-top:25px;
						padding:20px;
						background:#eef2ff;
						border-radius:10px;
					">
						<p style="
							margin:0 0 10px 0;
							font-size:13px;
							color:#1e3a8a;
							line-height:1.6;
						">
							<strong>❓ Didn\'t receive the OTP or having issues?</strong>
						</p>
						<p style="
							margin:0;
							font-size:13px;
							color:#1e3a8a;
							line-height:1.6;
						">
							• Check your spam/junk folder<br>
							• Ensure you entered the correct email address<br>
							• Contact our support team at support@largaphilippines.com
						</p>
					</div>

					<p style="
						margin-top:35px;
						font-size:15px;
						line-height:1.9;
					">
						Thank you for choosing 
						<strong>LARGA INTERNATIONAL LOGISTICS INC.</strong>
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
					© ' . date('Y') . ' LARGA INTERNATIONAL LOGISTICS INC.
					All Rights Reserved.
				</div>

			</div>

		</div>

		';

		$emailsend->setMessage($message);

		// =========================
		// SEND EMAIL WITH OTP
		// =========================
		$emailSent = $emailsend->send();

		// =========================
		// RETURN RESPONSE (Matches your frontend expectation)
		// =========================
		if ($query) {

			return [
				'status' => 'success',
				'message' => 'Registration successful! Please check your email for the OTP verification code.',
				'email_status' => $emailSent,
				'company_code' => $company_code
			];

		} else {

			return [
				'status' => 'error',
				'message' => 'Registration Failed. Please try again.'
			];

		}
	}

} //end main class
?>