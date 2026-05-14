<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
  <title>LARGA Enterprise | Client Registration</title>
  <link rel="shortcut icon" type="image/png" href="<?=base_url('assets/images/logos/largaicon.png')?>" />
  <!-- Google Fonts + Font Awesome -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <!-- Toastr CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
  
  <style>
    /* Your existing styles remain the same */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    /* Premium Enterprise Color Palette - Matching Login */
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
      --shadow-sm: 0 1px 2px rgba(0,0,0,0.03), 0 1px 1px rgba(0,0,0,0.02);
      --shadow-md: 0 4px 12px rgba(0,0,0,0.05), 0 1px 2px rgba(0,0,0,0.03);
      --shadow-lg: 0 12px 32px rgba(0,0,0,0.08), 0 4px 8px rgba(0,0,0,0.02);
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

    /* Animated gradient background */
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

    /* Canvas animation */
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

    /* Main container */
    .register-wrapper {
      position: relative;
      z-index: 10;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem;
    }

    /* Premium Card Design */
    .register-card {
      width: 100%;
      max-width: 520px;
      background: var(--card-white);
      border-radius: 32px;
      border: 1px solid rgba(0, 82, 204, 0.08);
      box-shadow: var(--shadow-xl);
      padding: 2.5rem 2.5rem 2.8rem;
      transition: all 0.4s cubic-bezier(0.2, 0.8, 0.2, 1);
      position: relative;
      overflow: hidden;
    }

    .register-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: linear-gradient(90deg, var(--blue-primary), var(--blue-light), var(--blue-primary));
      opacity: 0.6;
    }

    .register-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 28px 56px -12px rgba(0, 82, 204, 0.2);
      border-color: rgba(0, 82, 204, 0.15);
    }

    /* Premium Brand Area */
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
      transition: transform 0.3s ease;
    }

    .logo-icon:hover {
      transform: scale(1.02);
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

    .sub-brand {
      text-align: center;
      font-size: 0.65rem;
      color: var(--gray-500);
      margin-top: 10px;
      font-weight: 500;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: var(--gray-50);
      padding: 5px 12px;
      border-radius: 40px;
    }

    /* Form styling */
    .input-container {
      margin-bottom: 1.2rem;
      position: relative;
    }

    .input-icon {
      position: absolute;
      left: 16px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--gray-500);
      font-size: 0.95rem;
      transition: all 0.2s;
      z-index: 2;
    }

    .form-input {
      width: 100%;
      background: var(--bg-white);
      border: 1.5px solid var(--gray-200);
      border-radius: 14px;
      padding: 0.9rem 1rem 0.9rem 2.8rem;
      font-size: 0.9rem;
      font-weight: 500;
      color: var(--gray-900);
      transition: all 0.2s;
      outline: none;
      font-family: 'Inter', monospace;
    }

    .form-input:focus {
      border-color: var(--blue-primary);
      box-shadow: 0 0 0 4px var(--blue-glow);
    }

    .form-input:focus + .input-icon {
      color: var(--blue-primary);
    }

    .form-input::placeholder {
      color: var(--gray-300);
      font-weight: 400;
      font-size: 0.85rem;
    }

    /* Password strength indicator - Integrated inline */
    .password-wrapper {
      position: relative;
    }

    .password-strength {
      position: absolute;
      right: 16px;
      top: 50%;
      transform: translateY(-50%);
      display: flex;
      align-items: center;
      gap: 6px;
      background: var(--bg-white);
      padding: 2px 8px;
      border-radius: 20px;
      font-size: 0.65rem;
      font-weight: 600;
      pointer-events: none;
      z-index: 3;
    }

    .strength-dot {
      width: 6px;
      height: 6px;
      border-radius: 50%;
      background: var(--gray-300);
      transition: all 0.2s;
    }

    .strength-text {
      color: var(--gray-500);
      font-size: 0.65rem;
      font-weight: 500;
    }

    /* Premium Register Button */
    .register-button {
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
      margin-top: 0.5rem;
      position: relative;
      overflow: hidden;
    }

    .register-button::before {
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

    .register-button:hover::before {
      width: 300px;
      height: 300px;
    }

    .register-button:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 24px rgba(0, 82, 204, 0.3);
    }

    .register-button:active {
      transform: translateY(0);
    }

    .register-button i {
      font-size: 0.95rem;
      transition: transform 0.2s;
    }

    .register-button:hover i {
      transform: translateX(4px);
    }

    /* Error & Success messages */
    .error-msg {
      background: rgba(239, 68, 68, 0.08);
      border-left: 3px solid var(--danger);
      padding: 0.7rem 1rem;
      border-radius: 12px;
      margin-top: 1rem;
      font-size: 0.75rem;
      color: var(--danger);
      text-align: center;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
    }

    .success-msg {
      background: rgba(16, 185, 129, 0.08);
      border-left: 3px solid var(--success);
      padding: 0.7rem 1rem;
      border-radius: 12px;
      margin-top: 1rem;
      font-size: 0.75rem;
      color: var(--success);
      text-align: center;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
    }

    /* Login link */
    .login-link {
      text-align: center;
      margin-top: 1.5rem;
      font-size: 0.8rem;
      color: var(--gray-500);
    }

    .login-link a {
      color: var(--blue-primary);
      text-decoration: none;
      font-weight: 600;
      transition: color 0.2s;
    }

    .login-link a:hover {
      color: var(--blue-dark);
      text-decoration: underline;
    }

    /* Footer Stats */
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

    .stat:hover i {
      opacity: 1;
    }

    /* Responsive */
    @media (max-width: 550px) {
      .register-card {
        padding: 1.8rem 1.5rem 2rem;
        margin: 0 1rem;
      }
      h1 {
        font-size: 1.5rem;
      }
      .logo-icon {
        width: 60px;
        height: 60px;
      }
      .logo-icon i {
        font-size: 28px;
      }
      .password-strength {
        position: relative;
        right: auto;
        top: auto;
        transform: none;
        margin-top: 0.5rem;
        justify-content: flex-end;
      }
    }

    /* Loading state */
    .register-button.loading {
      pointer-events: none;
      opacity: 0.8;
    }

    .register-button.loading i {
      animation: spin 1s linear infinite;
    }

    @keyframes spin {
      from { transform: rotate(0deg); }
      to { transform: rotate(360deg); }
    }

    /* Field validation styles */
    .form-input.error {
      border-color: var(--danger);
    }

    .form-input.valid {
      border-color: var(--success);
    }
    
    /* Toastr custom styling */
    .toast-success {
      background-color: var(--success) !important;
    }
    .toast-error {
      background-color: var(--danger) !important;
    }
    .toast-info {
      background-color: var(--blue-primary) !important;
    }
    .toast-warning {
      background-color: var(--warning) !important;
    }
  </style>
</head>
<body>
<div class="row me-myregistration-outp-msg mx-0">
</div>
<input type="hidden" id="__siteurl" data-mesiteurl="<?=site_url();?>" />
<canvas id="registerCanvas"></canvas>
<div class="animated-bg"></div>

<div class="register-wrapper">
  <div class="register-card">
    
    <!-- Premium Brand Section -->
    <div class="brand">
      <div class="logo-icon">
        <i class="fas fa-user-plus"></i>
      </div>
      <h1>Create Account</h1>
      <div class="tagline">
        <i class="fas fa-truck-fast"></i>
        <span>CLIENT REGISTRATION</span>
        <i class="fas fa-box"></i>
      </div>
      <div class="sub-brand">
        <i class="fas fa-charging-station"></i> JOIN LARGA LOGISTICS NETWORK
      </div>
    </div>

    <!-- Registration Form -->
    <form action="<?=site_url();?>myregistration?meaction=REGISTER-SAVE" method="post" id="registerForm" class="myregistration-validation">
      <!-- Company Code -->
      <div class="input-container">
        <i class="fas fa-building input-icon"></i>
        <input type="text" name="company_code" id="company_code" class="form-input" placeholder="Company Code / Business ID" autocomplete="off" required>
      </div>
      
      <!-- Username -->
      <div class="input-container">
        <i class="fas fa-user input-icon"></i>
        <input type="text" name="username" id="username" class="form-input" placeholder="Username" autocomplete="off" required>
      </div>
      
      <!-- Email -->
      <div class="input-container">
        <i class="fas fa-envelope input-icon"></i>
        <input type="email" name="email" id="email" class="form-input" placeholder="Email Address" autocomplete="off" required>
      </div>
      
      <!-- Password with inline strength indicator -->
      <div class="input-container password-wrapper">
        <i class="fas fa-lock input-icon"></i>
        <input type="password" name="password" id="password" class="form-input" placeholder="Password" autocomplete="new-password" required>
        <div class="password-strength" id="passwordStrength">
          <span class="strength-dot" id="dot1"></span>
          <span class="strength-dot" id="dot2"></span>
          <span class="strength-dot" id="dot3"></span>
          <span class="strength-text" id="strengthText"></span>
        </div>
      </div>
      
      <!-- Confirm Password -->
      <div class="input-container">
        <i class="fas fa-check-circle input-icon"></i>
        <input type="password" name="confirm_password" id="confirm_password" class="form-input" placeholder="Confirm Password" autocomplete="off" required>
      </div>
      
      <button type="submit" class="register-button" id="registerButton">
        <i class="fas fa-arrow-right-to-bracket"></i> REGISTER ACCOUNT
      </button>

      <?php
      $msg = session()->getFlashdata('registration_error');
      if(!empty($msg)){
        echo "<div class='error-msg'><i class='fas fa-shield-exclamation'></i>" . htmlspecialchars($msg) . "</div>";
      }
      
      $success_msg = session()->getFlashdata('registration_success');
      if(!empty($success_msg)){
        echo "<div class='success-msg'><i class='fas fa-check-circle'></i>" . htmlspecialchars($success_msg) . "</div>";
      }
      ?>
    </form>

    <!-- Login Link -->
    <div class="login-link">
      Already have an account? <a href="<?=site_url();?>">Sign in to your portal</a>
    </div>

    <!-- Footer Stats -->
    <div class="footer-stats">
      <div class="stat"><i class="fas fa-shield-alt"></i> Secure Registration</div>
      <div class="stat"><i class="fas fa-headset"></i> 24/7 Support</div>
      <div class="stat"><i class="fas fa-chart-line"></i> Instant Access</div>
    </div>
  </div>
</div>

<!-- ==================== CORRECT SCRIPT LOADING ORDER ==================== -->
<!-- 1. jQuery FIRST (required by toastr, DataTables, etc.) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- 2. jQuery UI (if needed) -->
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

<!-- 3. Toastr JS (after jQuery) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<!-- 4. Your custom JS files -->
<script src="<?=base_url('assets/js/myregistration.js?v=1');?>"></script>
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

<!-- 8. Toastr initialization (AFTER jQuery and toastr.js are loaded) -->
<script>
// Toastr configuration
toastr.options = {
    "closeButton": true,
    "debug": false,
    "newestOnTop": false,
    "progressBar": true,
    "positionClass": "toast-top-right",
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
};

// Function to show toastr notifications
function showToast(type, title, message) {
    switch(type) {
        case 'success':
            toastr.success(message, title);
            break;
        case 'error':
            toastr.error(message, title);
            break;
        case 'warning':
            toastr.warning(message, title);
            break;
        case 'info':
            toastr.info(message, title);
            break;
        default:
            toastr.info(message, title);
    }
}

// Example: Show welcome message when page loads
$(document).ready(function() {
    console.log('Toastr initialized successfully');
    
    // Check for flash messages from PHP and show as toastr
    <?php if(!empty($msg)): ?>
    showToast('error', 'Registration Error', '<?= addslashes($msg) ?>');
    <?php endif; ?>
    
    <?php if(!empty($success_msg)): ?>
    showToast('success', 'Registration Successful', '<?= addslashes($success_msg) ?>');
    <?php endif; ?>
});
</script>

<!-- 9. Your animation script (doesn't need jQuery) -->
<script>
  (function() {
    // PREMIUM ENTERPRISE ANIMATION - Professional blue theme
    const canvas = document.getElementById('registerCanvas');
    const ctx = canvas.getContext('2d');
    
    let width = window.innerWidth;
    let height = window.innerHeight;
    
    // Premium particle system
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
    
    window.addEventListener('resize', () => {
      resizeCanvas();
    });
    
    resizeCanvas();
    animate();
    
    // ==================== PASSWORD STRENGTH (Inline Dots) ====================
    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('confirm_password');
    const registerButton = document.getElementById('registerButton');
    const registerForm = document.getElementById('registerForm');
    const dot1 = document.getElementById('dot1');
    const dot2 = document.getElementById('dot2');
    const dot3 = document.getElementById('dot3');
    const strengthText = document.getElementById('strengthText');
    
    function updatePasswordStrength(password) {
      let strength = 0;
      let message = '';
      let color1 = '#E4E7EC';
      let color2 = '#E4E7EC';
      let color3 = '#E4E7EC';
      
      if (password.length === 0) {
        message = '';
        strength = 0;
      } else if (password.length < 6) {
        message = 'Too short';
        color1 = '#EF4444';
        strength = 1;
      } else {
        if (password.length >= 8) strength++;
        if (/[a-z]/.test(password)) strength++;
        if (/[A-Z]/.test(password)) strength++;
        if (/[0-9]/.test(password)) strength++;
        if (/[^a-zA-Z0-9]/.test(password)) strength++;
        
        strength = Math.min(strength, 3);
        
        if (strength === 1) {
          message = 'Weak';
          color1 = '#EF4444';
        } else if (strength === 2) {
          message = 'Medium';
          color1 = '#F59E0B';
          color2 = '#F59E0B';
        } else if (strength >= 3) {
          message = 'Strong';
          color1 = '#10B981';
          color2 = '#10B981';
          color3 = '#10B981';
        }
      }
      
      dot1.style.background = color1;
      dot2.style.background = color2;
      dot3.style.background = color3;
      strengthText.textContent = message;
      strengthText.style.color = strength === 1 ? '#EF4444' : (strength === 2 ? '#F59E0B' : (strength >= 3 ? '#10B981' : '#667085'));
      
      return strength >= 2;
    }
    
    function validateEmail(email) {
      const re = /^[^\s@]+@([^\s@]+\.)+[^\s@]+$/;
      return re.test(email);
    }
    
    function validateForm() {
      const companyCode = document.getElementById('company_code').value.trim();
      const username = document.getElementById('username').value.trim();
      const email = document.getElementById('email').value.trim();
      const password = passwordInput.value;
      const confirm = confirmInput.value;
      
      let isValid = true;
      let errorMessage = '';
      
      if (!companyCode) {
        errorMessage = 'Please enter your company code.';
        isValid = false;
      } else if (!username) {
        errorMessage = 'Please choose a username.';
        isValid = false;
      } else if (!email) {
        errorMessage = 'Please enter your email address.';
        isValid = false;
      } else if (!validateEmail(email)) {
        errorMessage = 'Please enter a valid email address.';
        isValid = false;
      } else if (!password) {
        errorMessage = 'Please create a password.';
        isValid = false;
      } else if (password.length < 6) {
        errorMessage = 'Password must be at least 6 characters.';
        isValid = false;
      } else if (password !== confirm) {
        errorMessage = 'Passwords do not match.';
        isValid = false;
      }
      
      if (!isValid && errorMessage) {
        showTemporaryError(errorMessage);
        // Also show toastr error
        if (typeof toastr !== 'undefined') {
          toastr.error(errorMessage, 'Validation Error');
        }
      }
      
      return isValid;
    }
    
    function showTemporaryError(message) {
      const existingError = document.querySelector('.error-msg:not(.flash-error)');
      if (existingError && !existingError.classList.contains('flash-error')) {
        existingError.remove();
      }
      
      const errorDiv = document.createElement('div');
      errorDiv.className = 'error-msg flash-error';
      errorDiv.innerHTML = '<i class="fas fa-shield-exclamation"></i>' + message;
      registerForm.appendChild(errorDiv);
      
      setTimeout(() => {
        errorDiv.style.opacity = '0';
        setTimeout(() => errorDiv.remove(), 300);
      }, 3000);
    }
    
    // Real-time password strength check
    if (passwordInput) {
      passwordInput.addEventListener('input', function() {
        updatePasswordStrength(this.value);
        
        if (confirmInput.value && this.value !== confirmInput.value) {
          confirmInput.style.borderColor = '#EF4444';
        } else if (confirmInput.value && this.value === confirmInput.value) {
          confirmInput.style.borderColor = '#10B981';
        } else {
          confirmInput.style.borderColor = '#E4E7EC';
        }
      });
    }
    
    // Confirm password validation
    if (confirmInput) {
      confirmInput.addEventListener('input', function() {
        if (passwordInput.value === this.value && this.value.length > 0) {
          this.style.borderColor = '#10B981';
        } else if (this.value.length > 0) {
          this.style.borderColor = '#EF4444';
        } else {
          this.style.borderColor = '#E4E7EC';
        }
      });
    }
    
    // Form submit with loading state
    if (registerForm) {
      registerForm.addEventListener('submit', function(e) {
        if (!validateForm()) {
          e.preventDefault();
          return false;
        }
        
        registerButton.classList.add('loading');
        registerButton.innerHTML = '<i class="fas fa-spinner"></i> CREATING ACCOUNT...';
      });
    }
    
    // Input focus effects
    const inputs = document.querySelectorAll('.form-input');
    inputs.forEach(inp => {
      inp.addEventListener('focus', () => {
        inp.parentElement.style.transform = 'scale(1.01)';
      });
      inp.addEventListener('blur', () => {
        inp.parentElement.style.transform = 'scale(1)';
      });
    });
    
    // Hover effects for stats
    const stats = document.querySelectorAll('.stat');
    stats.forEach(stat => {
      stat.addEventListener('mouseenter', () => {
        stat.style.transform = 'translateX(2px)';
      });
      stat.addEventListener('mouseleave', () => {
        stat.style.transform = 'translateX(0)';
      });
    });
  })();
</script>
</body>
</html>