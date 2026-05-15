
<?php
$this->request = \Config\Services::request();
$this->db = \Config\Database::connect();
$this->session = session();
$this->cuser = $this->session->get('__xsys_myuserzicas__');
$company_code = $this->request->getPostGet('company_code');

if(!empty($company_code) || !is_null($company_code)) { 

    $query = $this->db->query("
    SELECT 
        `email`,
        `otp_code`
    FROM 
        `tmp_myua_user` 
    WHERE 
        `company_code` = '$company_code'"
    );

    $data = $query->getRowArray();
    $email = $data['email'];
    $otp_code = $data['otp_code'];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
  <title>LARGA Enterprise | Email Verification</title>
  <link rel="shortcut icon" type="image/png" href="<?=base_url('assets/images/logos/largaicon.png')?>" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    :root {
      --bg-primary: #F0F4F8;
      --bg-white: #FFFFFF;
      --card-white: #FFFFFF;
      --blue-light: #E8F0FE;
      --blue-primary: #0052CC;
      --blue-dark: #003D99;
      --blue-soft: #D6E6F9;
      --blue-glow: rgba(0, 82, 204, 0.15);
      --gray-50: #F8F9FC;
      --gray-100: #F0F2F5;
      --gray-200: #E4E7EC;
      --gray-300: #D0D5DD;
      --gray-500: #667085;
      --gray-700: #344054;
      --gray-900: #1A2C3E;
      --success: #10B981;
      --warning: #F59E0B;
      --danger: #EF4444;
      --info: #3B82F6;
      --shadow-sm: 0 1px 2px rgba(0,0,0,0.03);
      --shadow-md: 0 4px 12px rgba(0,0,0,0.05);
      --shadow-lg: 0 12px 32px rgba(0,0,0,0.08);
      --shadow-xl: 0 24px 48px -12px rgba(0,0,0,0.15);
      --border: #E4E7EC;
    }

    body {
      font-family: 'Inter', 'Segoe UI', sans-serif;
      background: linear-gradient(135deg, #E8F0FE 0%, #F0F4F8 100%);
      min-height: 100vh;
      position: relative;
      overflow-x: hidden;
    }

    canvas {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      display: block;
      z-index: 1;
      opacity: 0.4;
    }

    .animated-bg {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: radial-gradient(circle at 20% 50%, rgba(0, 82, 204, 0.03) 0%, transparent 50%),
                  radial-gradient(circle at 80% 80%, rgba(0, 82, 204, 0.04) 0%, transparent 60%);
      z-index: 1;
      pointer-events: none;
    }

    .otp-wrapper {
      position: relative;
      z-index: 10;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem;
    }

    .otp-card {
      width: 100%;
      max-width: 500px;
      background: var(--card-white);
      border-radius: 32px;
      border: 1px solid rgba(0, 82, 204, 0.08);
      box-shadow: var(--shadow-xl);
      padding: 2.5rem 2.5rem 2.8rem;
      transition: all 0.4s cubic-bezier(0.2, 0.8, 0.2, 1);
      position: relative;
      overflow: hidden;
    }

    .otp-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: linear-gradient(90deg, var(--blue-primary), var(--blue-light), var(--blue-primary));
      opacity: 0.6;
    }

    .otp-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 28px 56px -12px rgba(0, 82, 204, 0.2);
      border-color: rgba(0, 82, 204, 0.15);
    }

    .brand {
      text-align: center;
      margin-bottom: 2rem;
    }

    .logo-icon {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(135deg, var(--blue-primary) 0%, var(--blue-dark) 100%);
      width: 70px;
      height: 70px;
      border-radius: 20px;
      margin-bottom: 1.2rem;
      box-shadow: 0 8px 20px rgba(0, 82, 204, 0.25);
    }

    .logo-icon i {
      font-size: 32px;
      color: white;
    }

    h1 {
      font-size: 1.8rem;
      font-weight: 700;
      letter-spacing: -0.5px;
      background: linear-gradient(135deg, var(--gray-900) 0%, var(--gray-700) 100%);
      -webkit-background-clip: text;
      background-clip: text;
      color: transparent;
      margin-bottom: 0.3rem;
    }

    .tagline {
      color: var(--blue-primary);
      font-size: 0.65rem;
      font-weight: 700;
      letter-spacing: 2px;
      text-transform: uppercase;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
    }

    .tagline span {
      background: var(--blue-light);
      padding: 2px 12px;
      border-radius: 30px;
    }

    .email-badge {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: var(--gray-50);
      padding: 8px 16px;
      border-radius: 40px;
      margin: 1rem 0;
      font-size: 0.85rem;
      color: var(--gray-700);
      border: 1px solid var(--gray-200);
    }

    .email-badge i {
      color: var(--blue-primary);
    }

    /* OTP Input Group */
    .otp-input-group {
      margin: 1.8rem 0 1rem;
    }

    .otp-label {
      font-size: 0.8rem;
      font-weight: 600;
      color: var(--gray-700);
      margin-bottom: 0.8rem;
      display: block;
    }

    .otp-input-container {
      display: flex;
      gap: 12px;
      justify-content: center;
      margin-bottom: 1.2rem;
    }

    .otp-digit {
      width: 60px;
      height: 70px;
      text-align: center;
      font-size: 1.8rem;
      font-weight: 700;
      font-family: 'Inter', monospace;
      border: 2px solid var(--gray-200);
      border-radius: 16px;
      background: var(--bg-white);
      color: var(--gray-900);
      transition: all 0.2s;
      outline: none;
    }

    .otp-digit:focus {
      border-color: var(--blue-primary);
      box-shadow: 0 0 0 4px var(--blue-glow);
    }

    .otp-digit.error {
      border-color: var(--danger);
      animation: shake 0.4s ease;
    }

    @keyframes shake {
      0%, 100% { transform: translateX(0); }
      25% { transform: translateX(-5px); }
      75% { transform: translateX(5px); }
    }

    /* Timer and Resend */
    .timer-section {
      text-align: center;
      margin: 1rem 0;
    }

    .timer-text {
      font-size: 0.75rem;
      color: var(--gray-500);
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
    }

    .resend-link {
      background: none;
      border: none;
      color: var(--blue-primary);
      font-weight: 600;
      cursor: pointer;
      font-size: 0.75rem;
      text-decoration: underline;
      transition: color 0.2s;
    }

    .resend-link:hover:not(:disabled) {
      color: var(--blue-dark);
    }

    .resend-link:disabled {
      color: var(--gray-300);
      cursor: not-allowed;
      text-decoration: none;
    }

    /* Verify Button */
    .verify-button {
      width: 100%;
      background: linear-gradient(135deg, var(--blue-primary) 0%, var(--blue-dark) 100%);
      border: none;
      padding: 0.95rem;
      border-radius: 14px;
      font-weight: 700;
      font-size: 0.95rem;
      letter-spacing: 0.5px;
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 12px;
      cursor: pointer;
      transition: all 0.3s cubic-bezier(0.2, 0.8, 0.2, 1);
      margin-top: 1rem;
      position: relative;
      overflow: hidden;
    }

    .verify-button::before {
      content: '';
      position: absolute;
      top: 50%;
      left: 50%;
      width: 0;
      height: 0;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.2);
      transform: translate(-50%, -50%);
      transition: width 0.6s, height 0.6s;
    }

    .verify-button:hover::before {
      width: 300px;
      height: 300px;
    }

    .verify-button:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 24px rgba(0, 82, 204, 0.3);
    }

    .verify-button.loading {
      pointer-events: none;
      opacity: 0.8;
    }

    .verify-button.loading i {
      animation: spin 1s linear infinite;
    }

    @keyframes spin {
      from { transform: rotate(0deg); }
      to { transform: rotate(360deg); }
    }

    /* Wrong OTP Message */
    .error-message {
      background: rgba(239, 68, 68, 0.08);
      border-left: 3px solid var(--danger);
      padding: 0.7rem 1rem;
      border-radius: 12px;
      margin-top: 1rem;
      font-size: 0.75rem;
      color: var(--danger);
      text-align: center;
      display: none;
      align-items: center;
      justify-content: center;
      gap: 8px;
    }

    .error-message.show {
      display: flex;
    }

    .success-message {
      background: rgba(16, 185, 129, 0.08);
      border-left: 3px solid var(--success);
      padding: 0.7rem 1rem;
      border-radius: 12px;
      margin-top: 1rem;
      font-size: 0.75rem;
      color: var(--success);
      text-align: center;
      display: none;
      align-items: center;
      justify-content: center;
      gap: 8px;
    }

    .success-message.show {
      display: flex;
    }

    /* Back to login link */
    .back-link {
      text-align: center;
      margin-top: 1.8rem;
      font-size: 0.8rem;
      color: var(--gray-500);
    }

    .back-link a {
      color: var(--blue-primary);
      text-decoration: none;
      font-weight: 600;
      transition: color 0.2s;
    }

    .back-link a:hover {
      color: var(--blue-dark);
      text-decoration: underline;
    }

    .footer-stats {
      margin-top: 2rem;
      display: flex;
      justify-content: space-between;
      gap: 1rem;
      border-top: 1px solid var(--gray-200);
      padding-top: 1.2rem;
      font-size: 0.65rem;
      color: var(--gray-500);
      flex-wrap: wrap;
    }

    .stat {
      display: flex;
      align-items: center;
      gap: 6px;
      transition: all 0.2s;
      cursor: default;
    }

    .stat:hover {
      color: var(--blue-primary);
    }

    .stat i {
      color: var(--blue-primary);
      font-size: 0.7rem;
      opacity: 0.7;
    }

    @media (max-width: 550px) {
      .otp-card {
        padding: 1.8rem 1.5rem 2rem;
        margin: 0 1rem;
      }
      h1 {
        font-size: 1.5rem;
      }
      .otp-digit {
        width: 50px;
        height: 60px;
        font-size: 1.5rem;
      }
      .otp-input-container {
        gap: 8px;
      }
    }
    .verify-button.loading {
    pointer-events: none;
    opacity: 0.8;
}

.verify-button.loading i {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
/* Resend button loading state */
.resend-link.loading {
    pointer-events: none;
    opacity: 0.7;
}

.resend-link.loading i {
    animation: spin 1s linear infinite;
}

.resend-link:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
  </style>
</head>
<body>
<canvas id="otpCanvas"></canvas>
<div class="animated-bg"></div>
<input type="hidden" id="__siteurl" data-mesiteurl="<?=site_url();?>" />
<form action="<?=site_url();?>myotp?meaction=OTP-SAVE" method="post" id="otpForm" class="myotp-validation">
  <div class="otp-wrapper">
    <div class="otp-card">
      <div class="brand">
        <div class="logo-icon">
          <i class="fas fa-envelope"></i>
        </div>
        <h1>Verify Your Email</h1>
        <div class="tagline">
          <i class="fas fa-shield-alt"></i>
          <span>TWO-FACTOR AUTHENTICATION</span>
          <i class="fas fa-lock"></i>
        </div>
        <div class="email-badge" id="emailDisplay">
          <i class="fas fa-envelope"></i>
          <span id="userEmail"><?=$email;?></span>
        </div>
      </div>  

      <div class="otp-input-group">
        <div class="otp-label">
          <i class="fas fa-key"></i> Enter 6-digit verification code
        </div>
        <div class="otp-input-container" id="otpContainer">
          <input type="text" maxlength="1" class="otp-digit" id="otp1" name="otp1" autofocus>
          <input type="text" maxlength="1" class="otp-digit" id="otp2" name="otp2">
          <input type="text" maxlength="1" class="otp-digit" id="otp3" name="otp3">
          <input type="text" maxlength="1" class="otp-digit" id="otp4" name="otp4">
          <input type="text" maxlength="1" class="otp-digit" id="otp5" name="otp5">
          <input type="text" maxlength="1" class="otp-digit" id="otp6" name="otp6">
        </div>
      </div>
      <button type="submit" class="verify-button" id="verifyBtn">
        <i class="fas fa-check-double"></i> VERIFY & CONTINUE
      </button>
      </form>
      <form action="<?=site_url();?>myotp?meaction=OTP-RESEND" method="post" id="otpForm" class="myotp-resend-validation">
      
      <div class="timer-section">
        <div class="timer-text" id="timerDisplay">
          <i class="fas fa-hourglass-half"></i>
          <span id="timerCountdown">05:00</span>
          <span>remaining</span>
        </div>
        <button class="resend-link" id="resendBtn">
          <i class="fas fa-rotate-right"></i> Resend verification code
        </button>
      </div>
      </form>



      <div id="errorMsg" class="error-message">
        <i class="fas fa-exclamation-triangle"></i>
        <span id="errorText">Invalid verification code. Please try again.</span>
      </div>

      <div id="successMsg" class="success-message">
        <i class="fas fa-circle-check"></i>
        <span>Email verified! Redirecting to dashboard...</span>
      </div>

      <div class="back-link">
        <a href="<?=site_url();?>"><i class="fas fa-arrow-left"></i> Back to Sign In</a>
      </div>

      <div class="footer-stats">
        <div class="stat"><i class="fas fa-shield-alt"></i> Secure Verification</div>
        <div class="stat"><i class="fas fa-clock"></i> Code expires in 5 min</div>
        <div class="stat"><i class="fas fa-envelope"></i> Check your inbox</div>
      </div>
    </div>
  </div>



<!-- Hidden fields for dynamic data -->
<input type="hidden" id="company_code" name="company_code" class="company_code" value="<?=$company_code;?>">
<input type="hidden" id="otp_code" name="otp_code" class="otp_code" value="<?=$otp_code;?>">
<input type="hidden" id="userEmailHidden" value="">
<input type="hidden" id="redirectUrl" value="<?=site_url('dashboard');?>">
<input type="hidden" id="resendOtpUrl" value="<?=site_url('myregistration/resend_otp');?>">
<input type="hidden" id="verifyOtpUrl" value="<?=site_url('myregistration/verify_otp');?>">


<!-- ==================== CORRECT SCRIPT LOADING ORDER ==================== -->
<!-- 1. jQuery FIRST (required by toastr, DataTables, etc.) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- 2. jQuery UI (if needed) -->
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

<!-- 3. Toastr JS (after jQuery) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<!-- 4. Your custom JS files -->
<script src="<?=base_url('assets/js/myotp.js?v=1');?>"></script>
<script src="<?=base_url('assets/js/vendor.min.js')?>"></script>

<!-- 5. Bootstrap and other dependencies -->
<script src="<?=base_url('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js')?>"></script>
<script src="<?=base_url('assets/libs/simplebar/dist/simplebar.min.js')?>"></script>

<!-- 6. DataTables (requires jQuery) -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<!-- 7. Theme JS files -->
<script src="<?=base_url('assets/js/theme/app.init.js')?>"></script>
<script src="<?=base_url('assets/js/theme/theme.js')?>"></script>
<script src="<?=base_url('assets/js/theme/app.min.js')?>"></script>
<script src="<?=base_url('assets/js/theme/sidebarmenu.js')?>"></script>
<script src="<?=base_url('assets/libs/owl.carousel/dist/owl.carousel.min.js');?>"></script>

<script>
// ============================================
// OTP Email Confirmation Page
// Simulates the flow after registration
// ============================================

$(document).ready(function() {
    // Toastr configuration
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000"
    };

    // ========== GET EMAIL FROM URL PARAMETERS OR SESSION ==========
    // In real implementation, email would be passed via session or URL
    // For demo purposes, we simulate the email from registration

    // Try to get email from URL query string (e.g., ?email=user@company.com)

    // ========== OTP INPUT HANDLING (Auto-tab, numeric only) ==========
    const otpInputs = ['otp1', 'otp2', 'otp3', 'otp4', 'otp5', 'otp6'];
    
    // Handle input: only digits, auto focus next
    otpInputs.forEach((id, index) => {
        const input = document.getElementById(id);
        if (!input) return;
        
        input.addEventListener('input', function(e) {
            // Only allow digits
            this.value = this.value.replace(/[^0-9]/g, '');
            
            // If value entered and not last field, move to next
            if (this.value.length === 1 && index < otpInputs.length - 1) {
                document.getElementById(otpInputs[index + 1]).focus();
            }
            
            // Remove error styling when user starts typing
            document.getElementById('errorMsg').classList.remove('show');
            otpInputs.forEach(otpId => {
                document.getElementById(otpId).classList.remove('error');
            });
        });
        
        // Handle backspace: move to previous input
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Backspace' && this.value.length === 0 && index > 0) {
                document.getElementById(otpInputs[index - 1]).focus();
            }
        });
        
        // Handle paste event for full OTP
        input.addEventListener('paste', function(e) {
            e.preventDefault();
            const pastedData = (e.clipboardData || window.clipboardData).getData('text');
            const cleaned = pastedData.replace(/[^0-9]/g, '').slice(0, 6);
            const digits = cleaned.split('');
            
            otpInputs.forEach((otpId, idx) => {
                const field = document.getElementById(otpId);
                if (idx < digits.length) {
                    field.value = digits[idx];
                } else {
                    field.value = '';
                }
            });
            
            // Focus on next empty or last filled
            const lastFilledIndex = Math.min(digits.length, otpInputs.length - 1);
            if (lastFilledIndex < otpInputs.length - 1 && digits.length === otpInputs.length) {
                document.getElementById(otpInputs[otpInputs.length - 1]).focus();
            } else if (digits.length > 0 && digits.length < otpInputs.length) {
                document.getElementById(otpInputs[digits.length]).focus();
            }
        });
    });
    
    // ========== TIMER FUNCTIONALITY (5 minutes countdown) ==========
    let timeLeft = 3; // 5 minutes in seconds
    let timerInterval = null;
    let canResend = false;
    
    function updateTimerDisplay() {
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        const timeString = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        $('#timerCountdown').text(timeString);
        
        if (timeLeft <= 0) {
            clearInterval(timerInterval);
            $('#resendBtn').prop('disabled', false);
            $('#timerDisplay').html('<i class="fas fa-clock"></i> <span>Code expired</span>');
            canResend = true;
        } else {
            $('#resendBtn').prop('disabled', true);
            canResend = false;
        }
    }
    
    function startTimer() {
        if (timerInterval) clearInterval(timerInterval);
        timeLeft = 3;
        updateTimerDisplay();
        timerInterval = setInterval(() => {
            if (timeLeft > 0) {
                timeLeft--;
                updateTimerDisplay();
            } else {
                clearInterval(timerInterval);
            }
        }, 1000);
    }
    
    startTimer();
    
    // ========== RESEND OTP FUNCTION ==========
    // $('#resendBtn').on('click', function() {
    //     if (!canResend && timeLeft > 0) {
    //         toastr.warning(`Please wait ${Math.ceil(timeLeft / 60)} minutes before resending`, 'Cooldown');
    //         return;
    //     }
        
    //     const btn = $(this);
    //     btn.html('<i class="fas fa-spinner fa-spin"></i> Sending...');
    //     btn.prop('disabled', true);
        
    //     // Simulate AJAX request to resend OTP
    //     setTimeout(() => {
    //         // In production, this would be an AJAX call to your backend
    //         // $.post(resendUrl, { email: userEmail }, function(response) { ... });
            
    //         toastr.success(`A new verification code has been sent to ${userEmail}`, 'Code Resent');
    //         btn.html('<i class="fas fa-rotate-right"></i> Resend verification code');
            
    //         // Reset timer
    //         startTimer();
    //         canResend = false;
            
    //         // Clear OTP fields
    //         otpInputs.forEach(id => {
    //             document.getElementById(id).value = '';
    //         });
    //         document.getElementById('otp1').focus();
    //         document.getElementById('errorMsg').classList.remove('show');
            
    //     }, 1000);
    // });
    
    // ========== VERIFY OTP FUNCTION ==========
    $('#verifyBtn').on('click', function() {
        // Collect OTP digits
        let otpCode = '';
        let allFilled = true;
        
        // otpInputs.forEach(id => {
        //     const val = document.getElementById(id).value;
        //     if (val.length === 0) {
        //         allFilled = false;
        //     }
        //     otpCode += val;
        // });
        
        // if (!allFilled || otpCode.length !== 6) {
        //     toastr.error('Please enter all 6 digits', 'Incomplete Code');
        //     // Highlight empty fields
        //     otpInputs.forEach(id => {
        //         if (document.getElementById(id).value.length === 0) {
        //             document.getElementById(id).classList.add('error');
        //             setTimeout(() => document.getElementById(id).classList.remove('error'), 500);
        //         }
        //     });
        //     return;
        // }
        
        // const verifyBtn = $('#verifyBtn');
        // verifyBtn.addClass('loading');
        // verifyBtn.html('<i class="fas fa-spinner fa-spin"></i> VERIFYING...');
        
        // In production, this would be an AJAX call to your backend
        // For demo, we'll simulate verification with a fixed OTP: 123456
        // In real implementation, the OTP would be stored in session/database
        // const CORRECT_OTP = '123456'; // Demo OTP - in production this comes from server
        
        // setTimeout(() => {
        //     if (otpCode === CORRECT_OTP) {
        //         // SUCCESS - OTP verified
        //         $('#successMsg').addClass('show');
        //         $('#errorMsg').removeClass('show');
                
        //         toastr.success('Email verified successfully! Redirecting...', 'Verification Complete');
                
        //         // Store verification status in localStorage/session
        //         sessionStorage.setItem('email_verified', 'true');
        //         sessionStorage.setItem('verified_email', userEmail);
                
        //         // Redirect to dashboard after 2 seconds
        //         setTimeout(() => {
        //             const redirectTo = $('#redirectUrl').val();
        //             window.location.href = redirectTo;
        //         }, 2000);
        //     } else {
        //         // FAILURE - Invalid OTP
        //         $('#errorMsg').addClass('show');
        //         $('#errorText').text('Invalid verification code. Please check your email and try again.');
        //         toastr.error('The code you entered is incorrect. Please try again.', 'Verification Failed');
                
        //         // Shake animation effect on OTP container
        //         $('#otpContainer').addClass('shake-effect');
        //         setTimeout(() => $('#otpContainer').removeClass('shake-effect'), 500);
                
        //         // Clear OTP fields after error (optional)
        //         otpInputs.forEach(id => {
        //             document.getElementById(id).value = '';
        //             document.getElementById(id).classList.add('error');
        //             setTimeout(() => document.getElementById(id).classList.remove('error'), 800);
        //         });
        //         document.getElementById('otp1').focus();
        //     }
            
        //     verifyBtn.removeClass('loading');
        //     verifyBtn.html('<i class="fas fa-check-double"></i> VERIFY & CONTINUE');
        // }, 1200);
    });
    
    // Add shake animation CSS dynamically
    const style = document.createElement('style');
    style.textContent = `
        .shake-effect {
            animation: shakeEffect 0.5s cubic-bezier(0.36, 0.07, 0.19, 0.97) both;
        }
        @keyframes shakeEffect {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-4px); }
            20%, 40%, 60%, 80% { transform: translateX(4px); }
        }
    `;
    document.head.appendChild(style);
    
    // ========== CANVAS ANIMATION (Background) ==========
    const canvas = document.getElementById('otpCanvas');
    const ctx = canvas.getContext('2d');
    let width = window.innerWidth;
    let height = window.innerHeight;
    let particles = [];
    const PARTICLE_COUNT = 50;
    
    class Particle {
        constructor(x, y, vx, vy) {
            this.x = x;
            this.y = y;
            this.vx = vx;
            this.vy = vy;
            this.size = 1.5 + Math.random() * 2;
            this.alpha = 0.2 + Math.random() * 0.3;
        }
        update() {
            this.x += this.vx;
            this.y += this.vy;
            if (this.x < 0) { this.x = 0; this.vx *= -0.98; }
            if (this.x > width) { this.x = width; this.vx *= -0.98; }
            if (this.y < 0) { this.y = 0; this.vy *= -0.98; }
            if (this.y > height) { this.y = height; this.vy *= -0.98; }
        }
        draw() {
            ctx.beginPath();
            ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
            ctx.fillStyle = `rgba(0, 82, 204, ${this.alpha})`;
            ctx.fill();
        }
    }
    
    function initParticles() {
        particles = [];
        for (let i = 0; i < PARTICLE_COUNT; i++) {
            let x = Math.random() * width;
            let y = Math.random() * height;
            let vx = (Math.random() - 0.5) * 0.2;
            let vy = (Math.random() - 0.5) * 0.2;
            particles.push(new Particle(x, y, vx, vy));
        }
    }
    
    function drawConnections() {
        for (let i = 0; i < particles.length; i++) {
            for (let j = i + 1; j < particles.length; j++) {
                const dx = particles[i].x - particles[j].x;
                const dy = particles[i].y - particles[j].y;
                const dist = Math.sqrt(dx * dx + dy * dy);
                if (dist < 150) {
                    const intensity = 1 - (dist / 150);
                    ctx.beginPath();
                    ctx.moveTo(particles[i].x, particles[i].y);
                    ctx.lineTo(particles[j].x, particles[j].y);
                    ctx.strokeStyle = `rgba(0, 82, 204, ${0.03 + intensity * 0.05})`;
                    ctx.lineWidth = 0.6;
                    ctx.stroke();
                }
            }
        }
    }
    
    function animate() {
        if (!ctx) return;
        ctx.clearRect(0, 0, width, height);
        for (let p of particles) p.update();
        drawConnections();
        for (let p of particles) p.draw();
        requestAnimationFrame(animate);
    }
    
    function resizeCanvas() {
        width = window.innerWidth;
        height = window.innerHeight;
        canvas.width = width;
        canvas.height = height;
        initParticles();
    }
    
    window.addEventListener('resize', resizeCanvas);
    resizeCanvas();
    animate();
    
    // Pre-fill demo OTP for testing convenience (can be removed in production)
    // Uncomment below for easy testing:
    // setTimeout(() => {
    //     const testOtp = '123456';
    //     testOtp.split('').forEach((digit, idx) => {
    //         if (document.getElementById(otpInputs[idx])) {
    //             document.getElementById(otpInputs[idx]).value = digit;
    //         }
    //     });
    //     document.getElementById(otpInputs[5]).focus();
    // }, 500);
});
</script>
</body>
</html>