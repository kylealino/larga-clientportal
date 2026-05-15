<?php
namespace App\Models;
use CodeIgniter\Model;

class MyOTPModel extends Model
{

    protected $db;

    public function __construct(){
        parent::__construct();
        $this->session = session();
        $this->request = \Config\Services::request();
		$this->db = \Config\Database::connect();
		$this->cuser = $this->session->get('__xsys_myuserzicas__');
        
    }

	// public function user_save() {

	// 	$company_code = $this->request->getPostGet('company_code');
	// 	$otp_code = $this->request->getPostGet('otp_code');
	// 	$otp1 = $this->request->getPostGet('otp1');
	// 	$otp2 = $this->request->getPostGet('otp2');
	// 	$otp3 = $this->request->getPostGet('otp3');
	// 	$otp4 = $this->request->getPostGet('otp4');
	// 	$otp5 = $this->request->getPostGet('otp5');
	// 	$otp6 = $this->request->getPostGet('otp6');

	// 	$combinedotp = $otp1 . $otp2 . $otp3 . $otp4 . $otp5 . $otp6;

	// 	// =========================
	// 	// FETCH USER DATA FROM tmp_myua_user
	// 	// =========================
	// 	$tmpUser = $this->db->query("
	// 		SELECT * FROM tmp_myua_user 
	// 		WHERE company_code = '$company_code'
	// 	")->getRow();

	// 	if (!$tmpUser) {	
	// 		return [
	// 			'status' => 'error',
	// 			'message' => 'No pending registration found for this company code.'
	// 		];
	// 	}

	// 	// =========================
	// 	// CHECK ATTEMPTS (3 attempts per day)
	// 	// =========================
	// 	$today = date('Y-m-d');
	// 	$attempt_count = isset($tmpUser->attempt_count) ? $tmpUser->attempt_count : 0;
	// 	$last_attempt_date = isset($tmpUser->last_attempt_date) ? $tmpUser->last_attempt_date : null;
		
	// 	// Reset attempts if it's a new day
	// 	if ($last_attempt_date != $today) {
	// 		$attempt_count = 0;
	// 		$this->db->query("
	// 			UPDATE tmp_myua_user 
	// 			SET attempt_count = 0, last_attempt_date = '$today'
	// 			WHERE company_code = '$company_code'
	// 		");
	// 	}
		
	// 	// Check if max attempts reached (3 attempts)
	// 	if ($attempt_count >= 3) {
	// 		return [
	// 			'status' => 'error',
	// 			'message' => 'Maximum attempts reached (3/3). Please try again tomorrow.'
	// 		];
	// 	}

	// 	// Verify OTP
	// 	if ($tmpUser->otp_code !== $combinedotp) {
	// 		// Increment attempt counter
	// 		$new_attempts = $attempt_count + 1;
	// 		$remaining = 3 - $new_attempts;
			
	// 		$this->db->query("
	// 			UPDATE tmp_myua_user 
	// 			SET attempt_count = $new_attempts, last_attempt_date = '$today'
	// 			WHERE company_code = '$company_code'
	// 		");
			
	// 		$message = 'The code you entered is incorrect. ';
	// 		if ($remaining > 0) {
	// 			$message .= "$remaining attempt(s) remaining.";
	// 		} else {
	// 			$message .= "No attempts left for today. Please try again tomorrow.";
	// 		}
			
	// 		return [
	// 			'status' => 'error',
	// 			'message' => $message
	// 		];
	// 	}

	// 	// Check if OTP is expired
	// 	if (strtotime($tmpUser->otp_expiry) < time()) {
	// 		return [
	// 			'status' => 'error',
	// 			'message' => 'OTP code has expired. Please request a new one.'
	// 		];
	// 	}

	// 	$username = $tmpUser->username;
	// 	$emailto = $tmpUser->email;
	// 	$hash_value = $tmpUser->hash_value;
	// 	$hash_password = $tmpUser->hash_password;

	// 	$emailsend = \Config\Services::email();

	// 	// =========================
	// 	// CHECK IF USER ALREADY EXISTS IN myua_user
	// 	// =========================
	// 	$existingUser = $this->db->query("
	// 		SELECT * FROM myua_user 
	// 		WHERE company_code = '$company_code'
	// 	")->getRow();

	// 	if ($existingUser) {
	// 		return [
	// 			'status' => 'error',
	// 			'message' => 'User with this company code or email already exists.'
	// 		];
	// 	}

	// 	// =========================
	// 	// SAVE USER TO myua_user
	// 	// =========================
	// 	$query = $this->db->query("
	// 		INSERT INTO `myua_user`(
	// 			`username`,
	// 			`hash_password`,
	// 			`hash_value`,
	// 			`company_code`,
	// 			`email`,
	// 			`added_by`
	// 		)
	// 		VALUES(
	// 			'$username',
	// 			'$hash_password',
	// 			'$hash_value',
	// 			'$company_code',
	// 			'$emailto',
	// 			'{$this->cuser}'
	// 		)
	// 	");

	// 	// =========================
	// 	// EMAIL CONFIGURATION
	// 	// =========================
	// 	$emailsend->setFrom(
	// 		'noreply@largaphilippines.com',
	// 		'LARGA INTERNATIONAL LOGISTICS INC.'
	// 	);

	// 	$emailsend->setTo($emailto);

	// 	$emailsend->setSubject(
	// 		'LARGA INTERNATIONAL LOGISTICS INC. | Client Portal Registration Successful'
	// 	);

	// 	$emailsend->setMailType('html');

	// 	// =========================
	// 	// PROFESSIONAL EMAIL TEMPLATE
	// 	// =========================
	// 	$message = '

	// 	<div style="
	// 		background:#f3f4f6;
	// 		padding:40px 20px;
	// 		font-family:Arial,sans-serif;
	// 	">

	// 		<div style="
	// 			max-width:720px;
	// 			margin:auto;
	// 			background:#ffffff;
	// 			border-radius:18px;
	// 			overflow:hidden;
	// 			box-shadow:0 10px 30px rgba(0,0,0,0.08);
	// 		">

	// 			<!-- HEADER -->
	// 			<div style="
	// 				background:linear-gradient(135deg,#003366,#0052cc);
	// 				padding:50px 40px;
	// 				text-align:center;
	// 				color:#ffffff;
	// 			">

	// 				<div style="
	// 					display:inline-block;
	// 					padding:12px 22px;
	// 					background:rgba(255,255,255,0.12);
	// 					border-radius:12px;
	// 					margin-bottom:20px;
	// 					font-size:14px;
	// 					letter-spacing:1px;
	// 					font-weight:600;
	// 				">
	// 					LARGA CLIENT PORTAL
	// 				</div>

	// 				<h1 style="
	// 					margin:0;
	// 					font-size:34px;
	// 					font-weight:700;
	// 					letter-spacing:0.5px;
	// 					line-height:1.3;
	// 				">
	// 					LARGA INTERNATIONAL LOGISTICS INC.
	// 				</h1>

	// 				<p style="
	// 					margin-top:15px;
	// 					font-size:16px;
	// 					color:#dbeafe;
	// 					line-height:1.7;
	// 				">
	// 					Client Portal Registration Successful
	// 				</p>

	// 			</div>

	// 			<!-- BODY -->
	// 			<div style="
	// 				padding:45px 40px;
	// 				color:#374151;
	// 			">

	// 				<h2 style="
	// 					margin-top:0;
	// 					color:#111827;
	// 					font-size:26px;
	// 				">
	// 					Welcome to the Client Portal
	// 				</h2>

	// 				<p style="
	// 					font-size:15px;
	// 					line-height:1.9;
	// 					margin-top:20px;
	// 				">
	// 					Dear <strong>' . htmlspecialchars($username) . '</strong>,
	// 				</p>

	// 				<p style="
	// 					font-size:15px;
	// 					line-height:1.9;
	// 				">
	// 					Your registration to the 
	// 					<strong>LARGA INTERNATIONAL LOGISTICS INC.</strong> 
	// 					Client Portal has been successfully completed.
	// 				</p>

	// 				<p style="
	// 					font-size:15px;
	// 					line-height:1.9;
	// 				">
	// 					You may now access your client account and manage your logistics transactions, shipment monitoring, records, and portal activities securely through our online system.
	// 				</p>

	// 				<!-- ACCOUNT DETAILS -->
	// 				<div style="
	// 					margin-top:30px;
	// 					background:#f9fafb;
	// 					border:1px solid #e5e7eb;
	// 					border-radius:12px;
	// 					padding:25px;
	// 				">

	// 					<h3 style="
	// 						margin-top:0;
	// 						margin-bottom:20px;
	// 						font-size:18px;
	// 						color:#111827;
	// 					">
	// 						Client Portal Account Information
	// 					</h3>

	// 					<table width="100%" cellpadding="8" cellspacing="0">

	// 						<tr>
	// 							<td style="
	// 								font-weight:bold;
	// 								width:180px;
	// 								color:#374151;
	// 							">
	// 								Company Code:
	// 							</td>
	// 							<td style="color:#111827;">
	// 								' . htmlspecialchars($company_code) . '
	// 							</td>
	// 						</tr>

	// 						<tr>
	// 							<td style="
	// 								font-weight:bold;
	// 								color:#374151;
	// 							">
	// 								Username:
	// 							</td>
	// 							<td style="color:#111827;">
	// 								' . htmlspecialchars($username) . '
	// 							</td>
	// 						</tr>

	// 						<tr>
	// 							<td style="
	// 								font-weight:bold;
	// 								color:#374151;
	// 							">
	// 								Temporary Password:
	// 							</td>
	// 							<td style="
	// 								color:#dc2626;
	// 								font-weight:bold;
	// 								letter-spacing:1px;
	// 							">
	// 								' . htmlspecialchars($hash_value) . '
	// 							</td>
	// 						</tr>

	// 						<tr>
	// 							<td style="
	// 								font-weight:bold;
	// 								color:#374151;
	// 							">
	// 								Registered Email:
	// 							</td>
	// 							<td style="color:#111827;">
	// 								' . htmlspecialchars($emailto) . '
	// 							</td>
	// 						</tr>

	// 						<tr>
	// 							<td style="
	// 								font-weight:bold;
	// 								color:#374151;
	// 							">
	// 								Account Status:
	// 							</td>
	// 							<td style="
	// 								color:#10b981;
	// 								font-weight:bold;
	// 							">
	// 								ACTIVE
	// 							</td>
	// 						</tr>

	// 					</table>

	// 				</div>

	// 				<!-- SECURITY NOTICE -->
	// 				<div style="
	// 					margin-top:25px;
	// 					padding:20px;
	// 					background:#fef3c7;
	// 					border-left:5px solid #f59e0b;
	// 					border-radius:10px;
	// 				">

	// 					<p style="
	// 						margin:0;
	// 						font-size:14px;
	// 						line-height:1.8;
	// 						color:#92400e;
	// 					">
	// 						For security purposes, we strongly recommend changing your password immediately after your first login to the Client Portal.
	// 					</p>

	// 				</div>

	// 				<!-- FOOTER MESSAGE -->
	// 				<p style="
	// 					margin-top:35px;
	// 					font-size:15px;
	// 					line-height:1.9;
	// 				">
	// 					Thank you for choosing 
	// 					<strong>LARGA INTERNATIONAL LOGISTICS INC.</strong>.
	// 					We look forward to providing you with reliable and efficient logistics services.
	// 				</p>

	// 				<p style="
	// 					margin-top:40px;
	// 					font-size:15px;
	// 					line-height:1.8;
	// 				">
	// 					Sincerely,<br><br>

	// 					<strong>
	// 						LARGA INTERNATIONAL LOGISTICS INC.
	// 					</strong><br>

	// 					Client Portal Administration Team
	// 				</p>

	// 			</div>

	// 			<!-- FOOTER -->
	// 			<div style="
	// 				background:#111827;
	// 				color:#9ca3af;
	// 				text-align:center;
	// 				padding:22px;
	// 				font-size:13px;
	// 			">
	// 				© ' . date('Y') . ' LARGA INTERNATIONAL LOGISTICS INC.
	// 				All Rights Reserved.
	// 			</div>

	// 		</div>

	// 	</div>

	// 	';

	// 	$emailsend->setMessage($message);

	// 	// =========================
	// 	// SEND EMAIL
	// 	// =========================
	// 	$emailSent = $emailsend->send();

	// 	// =========================
	// 	// RESET ATTEMPTS ON SUCCESS
	// 	// =========================
	// 	if($query){
	// 		// Reset attempts on successful registration
	// 		$this->db->query("
	// 			UPDATE tmp_myua_user 
	// 			SET attempt_count = 0, last_attempt_date = NULL
	// 			WHERE company_code = '$company_code'
	// 		");
			
	// 		return [
	// 			'status' => 'success',
	// 			'message' => 'Registration Saved Successfully',
	// 			'email_status' => $emailSent
	// 		];

	// 	} else {

	// 		return [
	// 			'status' => 'error',
	// 			'message' => 'Registration Failed'
	// 		];

	// 	}
	// }

	public function user_save() {

		$company_code = $this->request->getPostGet('company_code');
		$otp_code = $this->request->getPostGet('otp_code');
		$otp1 = $this->request->getPostGet('otp1');
		$otp2 = $this->request->getPostGet('otp2');
		$otp3 = $this->request->getPostGet('otp3');
		$otp4 = $this->request->getPostGet('otp4');
		$otp5 = $this->request->getPostGet('otp5');
		$otp6 = $this->request->getPostGet('otp6');

		$combinedotp = $otp1 . $otp2 . $otp3 . $otp4 . $otp5 . $otp6;

		// =========================
		// FETCH USER DATA FROM tmp_myua_user
		// =========================
		$tmpUser = $this->db->query("
			SELECT * FROM tmp_myua_user 
			WHERE company_code = '$company_code'
		")->getRow();

		if (!$tmpUser) {
			return [
				'status' => 'error',
				'message' => 'No pending registration found for this company code.'
			];
		}

		// =========================
		// CHECK ATTEMPTS FROM DATABASE (3 attempts per day)
		// =========================
		$today = date('Y-m-d');
		$attempt_count = isset($tmpUser->attempt_count) ? $tmpUser->attempt_count : 0;
		$last_attempt_date = isset($tmpUser->last_attempt_date) ? $tmpUser->last_attempt_date : null;
		
		// Reset attempts if it's a new day
		if ($last_attempt_date != $today) {
			$attempt_count = 0;
			$this->db->query("
				UPDATE tmp_myua_user 
				SET attempt_count = 0, last_attempt_date = '$today'
				WHERE company_code = '$company_code'
			");
		}
		
		// Check if max attempts reached (3 attempts)
		if ($attempt_count >= 3) {
			return [
				'status' => 'error',
				'message' => 'Maximum attempts reached (3/3). Please try again tomorrow.'
			];
		}

		// Verify OTP
		if ($tmpUser->otp_code !== $combinedotp) {
			// Increment attempt counter in database
			$new_attempts = $attempt_count + 1;
			$remaining = 3 - $new_attempts;
			
			$this->db->query("
				UPDATE tmp_myua_user 
				SET attempt_count = $new_attempts, last_attempt_date = '$today'
				WHERE company_code = '$company_code'
			");
			
			$message = 'The code you entered is incorrect. ';
			if ($remaining > 0) {
				$message .= "$remaining attempt(s) remaining.";
			} else {
				$message .= "No attempts left for today. Please try again tomorrow.";
			}
			
			return [
				'status' => 'error',
				'message' => $message,
				'attempts_remaining' => $remaining,
				'max_attempts_reached' => ($remaining == 0)
			];
		}

		// Check if OTP is expired
		if (strtotime($tmpUser->otp_expiry) < time()) {
			return [
				'status' => 'error',
				'message' => 'OTP code has expired. Please request a new one.'
			];
		}

		$username = $tmpUser->username;
		$emailto = $tmpUser->email;
		$hash_value = $tmpUser->hash_value;
		$hash_password = $tmpUser->hash_password;

		$emailsend = \Config\Services::email();

		// =========================
		// CHECK IF USER ALREADY EXISTS IN myua_user
		// =========================
		$existingUser = $this->db->query("
			SELECT * FROM myua_user 
			WHERE company_code = '$company_code'
		")->getRow();

		if ($existingUser) {
			return [
				'status' => 'error',
				'message' => 'User with this company code or email already exists.'
			];
		}

		// =========================
		// SAVE USER TO myua_user
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
			'noreply@largaphilippines.com',
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
						Dear <strong>' . htmlspecialchars($username) . '</strong>,
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
									Temporary Password:
								</td>
								<td style="
									color:#dc2626;
									font-weight:bold;
									letter-spacing:1px;
								">
									' . htmlspecialchars($hash_value) . '
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
					© ' . date('Y') . ' LARGA INTERNATIONAL LOGISTICS INC.
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
		// RESET ATTEMPTS ON SUCCESS
		// =========================
		if($query){
			// Reset attempts on successful registration
			$this->db->query("
				UPDATE tmp_myua_user 
				SET attempt_count = 0, last_attempt_date = NULL
				WHERE company_code = '$company_code'
			");
			
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

	// public function resend_otp() {

	// 	$company_code = $this->request->getPostGet('company_code');

	// 	$otp_code = sprintf("%06d", mt_rand(1, 999999));
	// 	// OTP expiry timestamp (15 minutes from now)
	// 	$otp_expiry = date('Y-m-d H:i:s', strtotime('+15 minutes'));

	// 	$query = $this->db->query("
	// 		SELECT 
	// 			`username`,
	// 			`hash_password`,
	// 			`hash_value`,
	// 			`company_code`,
	// 			`email`,
	// 			`otp_code`,
	// 			`otp_expiry`
	// 		FROM
	// 			`tmp_myua_user`
	// 		WHERE company_code = '$company_code'
	// 	");
	// 	$rw = $query->getRowArray();
	// 	$username = $rw['username'];
	// 	$emailto = $rw['email'];
	// 	$hash_value = $rw['hash_value'];
	// 	$hash_password = hash('sha512', $hash_value);

	// 	$query = $this->db->query("
	// 		UPDATE `tmp_myua_user` SET `otp_code` = '$otp_code', `otp_expiry` = '$otp_expiry' WHERE company_code = '$company_code'
	// 	");

	// 	$emailsend = \Config\Services::email();

	// 	// =========================
	// 	// EMAIL CONFIGURATION
	// 	// =========================
	// 	$emailsend->setFrom(
	// 		'noreply@largaphilippines.com',
	// 		'LARGA INTERNATIONAL LOGISTICS INC.'
	// 	);

	// 	$emailsend->setTo($emailto);

	// 	$emailsend->setSubject(
	// 		'LARGA INTERNATIONAL LOGISTICS INC. | Email Verification Required'
	// 	);

	// 	$emailsend->setMailType('html');

	// 	// =========================
	// 	// PROFESSIONAL EMAIL TEMPLATE WITH OTP
	// 	// =========================
	// 	$message = '

	// 	<div style="
	// 		background:#f3f4f6;
	// 		padding:40px 20px;
	// 		font-family:Arial,sans-serif;
	// 	">

	// 		<div style="
	// 			max-width:720px;
	// 			margin:auto;
	// 			background:#ffffff;
	// 			border-radius:18px;
	// 			overflow:hidden;
	// 			box-shadow:0 10px 30px rgba(0,0,0,0.08);
	// 		">

	// 			<!-- HEADER -->
	// 			<div style="
	// 				background:linear-gradient(135deg,#003366,#0052cc);
	// 				padding:50px 40px;
	// 				text-align:center;
	// 				color:#ffffff;
	// 			">

	// 				<div style="
	// 					display:inline-block;
	// 					padding:12px 22px;
	// 					background:rgba(255,255,255,0.12);
	// 					border-radius:12px;
	// 					margin-bottom:20px;
	// 					font-size:14px;
	// 					letter-spacing:1px;
	// 					font-weight:600;
	// 				">
	// 					EMAIL VERIFICATION REQUIRED
	// 				</div>

	// 				<h1 style="
	// 					margin:0;
	// 					font-size:34px;
	// 					font-weight:700;
	// 					letter-spacing:0.5px;
	// 					line-height:1.3;
	// 				">
	// 					Verify Your Email Address
	// 				</h1>

	// 				<p style="
	// 					margin-top:15px;
	// 					font-size:16px;
	// 					color:#dbeafe;
	// 					line-height:1.7;
	// 				">
	// 					Complete your registration to access the Client Portal
	// 				</p>

	// 			</div>

	// 			<!-- BODY -->
	// 			<div style="
	// 				padding:45px 40px;
	// 				color:#374151;
	// 			">

	// 				<h2 style="
	// 					margin-top:0;
	// 					color:#111827;
	// 					font-size:26px;
	// 				">
	// 					One-Time Password (OTP) Verification
	// 				</h2>

	// 				<p style="
	// 					font-size:15px;
	// 					line-height:1.9;
	// 					margin-top:20px;
	// 				">
	// 					Dear <strong>' . htmlspecialchars($username) . '</strong>,
	// 				</p>

	// 				<p style="
	// 					font-size:15px;
	// 					line-height:1.9;
	// 				">
	// 					Thank you for registering with 
	// 					<strong>LARGA INTERNATIONAL LOGISTICS INC.</strong> 
	// 					Client Portal.
	// 				</p>

	// 				<p style="
	// 					font-size:15px;
	// 					line-height:1.9;
	// 				">
	// 					To complete your registration and activate your account,
	// 					please use the One-Time Password (OTP) below:
	// 				</p>

	// 				<!-- OTP CODE - HIGHLIGHTED -->
	// 				<div style="
	// 					margin:30px 0;
	// 					text-align:center;
	// 				">
	// 					<div style="
	// 						display:inline-block;
	// 						background:#f0f9ff;
	// 						border:2px solid #0052cc;
	// 						border-radius:16px;
	// 						padding:20px 40px;
	// 					">
	// 						<span style="
	// 							font-size:42px;
	// 							font-weight:800;
	// 							letter-spacing:8px;
	// 							color:#0052cc;
	// 							font-family:monospace;
	// 						">' . $otp_code . '</span>
	// 					</div>
	// 				</div>

	// 				<div style="
	// 					background:#fef3c7;
	// 					border-left:5px solid #f59e0b;
	// 					border-radius:10px;
	// 					padding:15px 20px;
	// 					margin:20px 0;
	// 				">
	// 					<p style="
	// 						margin:0;
	// 						font-size:14px;
	// 						color:#92400e;
	// 						line-height:1.6;
	// 					">
	// 						<strong>⚠️ Important:</strong> This OTP is valid for 
	// 						<strong>15 minutes</strong> from the time of this email.
	// 						For security reasons, do not share this code with anyone.
	// 					</p>
	// 				</div>

	// 				<p style="
	// 					font-size:15px;
	// 					line-height:1.9;
	// 				">
	// 					<strong>How to verify:</strong>
	// 				</p>

	// 				<ol style="
	// 					margin:15px 0 15px 20px;
	// 					line-height:1.8;
	// 					font-size:14px;
	// 				">
	// 					<li>Return to the LARGA Client Portal registration page</li>
	// 					<li>Enter the 6-digit OTP code shown above</li>
	// 					<li>Click "Verify Email" to activate your account</li>
	// 					<li>You will be redirected to the login page upon successful verification</li>
	// 				</ol>

	// 				<!-- ACCOUNT DETAILS -->
	// 				<div style="
	// 					margin-top:30px;
	// 					background:#f9fafb;
	// 					border:1px solid #e5e7eb;
	// 					border-radius:12px;
	// 					padding:25px;
	// 				">

	// 					<h3 style="
	// 						margin-top:0;
	// 						margin-bottom:20px;
	// 						font-size:18px;
	// 						color:#111827;
	// 					">
	// 						Registration Summary
	// 					</h3>

	// 					<table width="100%" cellpadding="8" cellspacing="0">
	// 						<tr>
	// 							<td style="
	// 								font-weight:bold;
	// 								width:180px;
	// 								color:#374151;
	// 							">
	// 								Company Code:
	// 							</td>
	// 							<td style="color:#111827;">
	// 								' . htmlspecialchars($company_code) . '
	// 							</td>
	// 						</tr>
	// 						<tr>
	// 							<td style="
	// 								font-weight:bold;
	// 								color:#374151;
	// 							">
	// 								Username:
	// 							</td>
	// 							<td style="color:#111827;">
	// 								' . htmlspecialchars($username) . '
	// 							</td>
	// 						</tr>
	// 						<tr>
	// 							<td style="
	// 								font-weight:bold;
	// 								color:#374151;
	// 							">
	// 								Registered Email:
	// 							</td>
	// 							<td style="color:#111827;">
	// 								' . htmlspecialchars($emailto) . '
	// 							</td>
	// 						</tr>
	// 						<tr>
	// 							<td style="
	// 								font-weight:bold;
	// 								color:#374151;
	// 							">
	// 								OTP Expiry:
	// 							</td>
	// 							<td style="color:#dc2626;">
	// 								' . date('F d, Y h:i A', strtotime($otp_expiry)) . '
	// 							</td>
	// 						</tr>
	// 					</table>

	// 				</div>

	// 				<!-- TROUBLESHOOTING -->
	// 				<div style="
	// 					margin-top:25px;
	// 					padding:20px;
	// 					background:#eef2ff;
	// 					border-radius:10px;
	// 				">
	// 					<p style="
	// 						margin:0 0 10px 0;
	// 						font-size:13px;
	// 						color:#1e3a8a;
	// 						line-height:1.6;
	// 					">
	// 						<strong>❓ Didn\'t receive the OTP or having issues?</strong>
	// 					</p>
	// 					<p style="
	// 						margin:0;
	// 						font-size:13px;
	// 						color:#1e3a8a;
	// 						line-height:1.6;
	// 					">
	// 						• Check your spam/junk folder<br>
	// 						• Ensure you entered the correct email address<br>
	// 						• Contact our support team at support@largaphilippines.com
	// 					</p>
	// 				</div>

	// 				<p style="
	// 					margin-top:35px;
	// 					font-size:15px;
	// 					line-height:1.9;
	// 				">
	// 					Thank you for choosing 
	// 					<strong>LARGA INTERNATIONAL LOGISTICS INC.</strong>
	// 				</p>

	// 				<p style="
	// 					margin-top:40px;
	// 					font-size:15px;
	// 					line-height:1.8;
	// 				">
	// 					Sincerely,<br><br>

	// 					<strong>
	// 						LARGA INTERNATIONAL LOGISTICS INC.
	// 					</strong><br>

	// 					Client Portal Administration Team
	// 				</p>

	// 			</div>

	// 			<!-- FOOTER -->
	// 			<div style="
	// 				background:#111827;
	// 				color:#9ca3af;
	// 				text-align:center;
	// 				padding:22px;
	// 				font-size:13px;
	// 			">
	// 				© ' . date('Y') . ' LARGA INTERNATIONAL LOGISTICS INC.
	// 				All Rights Reserved.
	// 			</div>

	// 		</div>

	// 	</div>

	// 	';

	// 	$emailsend->setMessage($message);

	// 	// =========================
	// 	// SEND EMAIL WITH OTP
	// 	// =========================
	// 	$emailSent = $emailsend->send();

	// 	// =========================
	// 	// RETURN RESPONSE
	// 	// =========================
	// 	if($query){

	// 		return [
	// 			'status' => 'success',
	// 			'message' => 'OTP Resend Successfully',
	// 			'email_status' => $emailSent
	// 		];

	// 	} else {

	// 		return [
	// 			'status' => 'error',
	// 			'message' => 'Registration Failed'
	// 		];

	// 	}
	// }

	public function resend_otp() {
		
		$company_code = $this->request->getPostGet('company_code');
		$otp_code = sprintf("%06d", mt_rand(1, 999999));
		// OTP expiry timestamp (15 minutes from now)
		$otp_expiry = date('Y-m-d H:i:s', strtotime('+15 minutes'));
		
		// =========================
		// CHECK ATTEMPTS FROM DATABASE
		// =========================
		$checkUser = $this->db->query("
			SELECT attempt_count, last_attempt_date FROM tmp_myua_user 
			WHERE company_code = '$company_code'
		")->getRow();
		
		$today = date('Y-m-d');
		$attempt_count = isset($checkUser->attempt_count) ? $checkUser->attempt_count : 0;
		$last_attempt_date = isset($checkUser->last_attempt_date) ? $checkUser->last_attempt_date : null;
		
		if ($last_attempt_date == $today && $attempt_count >= 3) {
			return $this->response->setJSON([
				'status' => 'error',
				'message' => 'Maximum attempts reached (3/3). Please try again tomorrow.'
			]);
		}
		// =========================
		// END OF ATTEMPT CHECK
		// =========================
		
		$query = $this->db->query("
			SELECT 
				`username`,
				`hash_password`,
				`hash_value`,
				`company_code`,
				`email`,
				`otp_code`,
				`otp_expiry`
			FROM
				`tmp_myua_user`
			WHERE company_code = '$company_code'
		");
		$rw = $query->getRowArray();
		$username = $rw['username'];
		$emailto = $rw['email'];
		$hash_value = $rw['hash_value'];
		$hash_password = hash('sha512', $hash_value);

		if (empty($company_code)) {
			return $this->response->setJSON([
				'status' => 'error',
				'message' => 'Company code is required.'
			]);
		}
		
		$db = \Config\Database::connect();
		
		// Check if user exists and not yet verified
		$query = $this->db->query("
			SELECT * FROM tmp_myua_user 
			WHERE company_code = '$company_code' 
		");
		
		$user = $query->getRow();
		
		if (!$user) {
			return $this->response->setJSON([
				'status' => 'error',
				'message' => 'No pending registration found for this company code.'
			]);
		}
		
		// Generate new OTP
		$new_otp = sprintf("%06d", mt_rand(1, 999999));
		$new_expiry = date('Y-m-d H:i:s', strtotime('+15 minutes'));
		
		// Update OTP in database
		$this->db->query("
			UPDATE tmp_myua_user 
			SET otp_code = '$new_otp', 
				otp_expiry = '$new_expiry' 
			WHERE company_code = '$company_code'
		");
		
		$emailsend = \Config\Services::email();

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
						You requested a new One-Time Password (OTP) to verify your email address.
					</p>

					<p style="
						font-size:15px;
						line-height:1.9;
					">
						Please use the OTP code below to complete your registration:
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
							">' . $new_otp . '</span>
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
									' . date('F d, Y h:i A', strtotime($new_expiry)) . '
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

		
		if($query){

			return [
				'status' => 'success',
				'message' => 'OTP Resend Successfully',
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