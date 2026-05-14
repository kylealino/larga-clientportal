<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
  <title>LARGA Enterprise | Client Portal Login</title>
  <link rel="shortcut icon" type="image/png" href="<?=base_url('assets/images/logos/largaicon.png')?>" />
  <!-- Google Fonts + Font Awesome -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    /* Premium Enterprise Color Palette */
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
      height: 100vh;
      width: 100vw;
      overflow: hidden;
      position: relative;
    }

    /* Animated gradient background */
    .animated-bg {
      position: absolute;
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
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      display: block;
      z-index: 1;
      opacity: 0.5;
    }

    /* Main container */
    .login-wrapper {
      position: relative;
      z-index: 10;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem;
    }

    /* Premium Card Design */
    .login-card {
      width: 100%;
      max-width: 500px;
      background: var(--card-white);
      border-radius: 32px;
      border: 1px solid rgba(0, 82, 204, 0.08);
      box-shadow: var(--shadow-xl);
      padding: 2.8rem 2.5rem 3rem;
      transition: all 0.4s cubic-bezier(0.2, 0.8, 0.2, 1);
      position: relative;
      overflow: hidden;
      backdrop-filter: blur(0px);
    }

    .login-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: linear-gradient(90deg, var(--blue-primary), var(--blue-light), var(--blue-primary));
      opacity: 0.6;
    }

    .login-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 28px 56px -12px rgba(0, 82, 204, 0.2);
      border-color: rgba(0, 82, 204, 0.15);
    }

    /* Premium Brand Area */
    .brand {
      text-align: center;
      margin-bottom: 2.5rem;
    }

    .logo-icon {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(135deg, var(--blue-primary) 0%, var(--blue-dark) 100%);
      width: 80px;
      height: 80px;
      border-radius: 24px;
      margin-bottom: 1.5rem;
      box-shadow: 0 8px 20px rgba(0, 82, 204, 0.25);
      transition: transform 0.3s ease;
    }

    .logo-icon:hover {
      transform: scale(1.02);
    }

    .logo-icon i {
      font-size: 42px;
      color: white;
    }

    h1 {
      font-size: 2rem;
      font-weight: 700;
      letter-spacing: -0.5px;
      background: linear-gradient(135deg, var(--gray-900) 0%, var(--gray-700) 100%);
      -webkit-background-clip: text;
      background-clip: text;
      color: transparent;
      margin-bottom: 0.5rem;
    }

    .tagline {
      color: var(--blue-primary);
      font-size: 0.7rem;
      font-weight: 700;
      letter-spacing: 2px;
      text-transform: uppercase;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
    }

    .tagline i {
      font-size: 10px;
      color: var(--blue-primary);
    }

    .tagline span {
      background: var(--blue-light);
      padding: 2px 12px;
      border-radius: 30px;
    }

    .sub-brand {
      text-align: center;
      font-size: 0.7rem;
      color: var(--gray-500);
      margin-top: 12px;
      font-weight: 500;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: var(--gray-50);
      padding: 6px 14px;
      border-radius: 40px;
    }

    /* Form styling - Premium */
    .input-container {
      margin-bottom: 1.5rem;
      position: relative;
    }

    .input-icon {
      position: absolute;
      left: 18px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--gray-500);
      font-size: 1rem;
      transition: all 0.2s;
      z-index: 2;
    }

    .form-input {
      width: 100%;
      background: var(--bg-white);
      border: 1.5px solid var(--gray-200);
      border-radius: 16px;
      padding: 1rem 1rem 1rem 3rem;
      font-size: 0.95rem;
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
      font-size: 0.9rem;
    }

    /* Premium Login Button */
    .login-button {
      width: 100%;
      background: linear-gradient(135deg, var(--blue-primary) 0%, var(--blue-dark) 100%);
      border: none;
      padding: 1rem;
      border-radius: 16px;
      font-weight: 700;
      font-size: 1rem;
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

    .login-button::before {
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

    .login-button:hover::before {
      width: 300px;
      height: 300px;
    }

    .login-button:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 24px rgba(0, 82, 204, 0.3);
    }

    .login-button:active {
      transform: translateY(0);
    }

    .login-button i {
      font-size: 1rem;
      transition: transform 0.2s;
    }

    .login-button:hover i {
      transform: translateX(4px);
    }

    /* Register Link Section */
    .register-link-section {
      text-align: center;
      margin-top: 1.5rem;
      padding-top: 1rem;
      border-top: 1px solid var(--gray-200);
    }

    .register-link {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      color: var(--gray-500);
      font-size: 0.85rem;
      text-decoration: none;
      transition: all 0.2s;
    }

    .register-link i {
      color: var(--blue-primary);
      font-size: 0.8rem;
      transition: transform 0.2s;
    }

    .register-link:hover {
      color: var(--blue-primary);
    }

    .register-link:hover i {
      transform: translateX(3px);
    }

    .register-link strong {
      font-weight: 700;
    }

    /* Error message styling */
    .error-msg {
      background: rgba(239, 68, 68, 0.08);
      border-left: 3px solid var(--danger);
      padding: 0.8rem 1rem;
      border-radius: 14px;
      margin-top: 1.2rem;
      font-size: 0.75rem;
      color: var(--danger);
      text-align: center;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
    }

    /* Premium Footer Stats */
    .footer-stats {
      margin-top: 2rem;
      display: flex;
      justify-content: space-between;
      gap: 1rem;
      border-top: 1px solid var(--gray-200);
      padding-top: 1.5rem;
      font-size: 0.7rem;
      color: var(--gray-500);
      flex-wrap: wrap;
    }

    .stat {
      display: flex;
      align-items: center;
      gap: 8px;
      transition: all 0.2s;
      cursor: default;
    }

    .stat:hover {
      color: var(--blue-primary);
    }

    .stat i {
      color: var(--blue-primary);
      font-size: 0.8rem;
      opacity: 0.7;
      transition: opacity 0.2s;
    }

    .stat:hover i {
      opacity: 1;
    }

    /* Additional premium touches */
    .secure-badge {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      margin-top: 1.5rem;
      font-size: 0.65rem;
      color: var(--gray-500);
    }

    .secure-badge i {
      color: var(--success);
      font-size: 0.7rem;
    }

    /* Responsive */
    @media (max-width: 550px) {
      .login-card {
        padding: 2rem 1.5rem 2.2rem;
        margin: 0 1rem;
      }
      h1 {
        font-size: 1.6rem;
      }
      .logo-icon {
        width: 65px;
        height: 65px;
      }
      .logo-icon i {
        font-size: 32px;
      }
    }

    /* Loading state */
    .login-button.loading {
      pointer-events: none;
      opacity: 0.8;
    }

    .login-button.loading i {
      animation: spin 1s linear infinite;
    }

    @keyframes spin {
      from { transform: rotate(0deg); }
      to { transform: rotate(360deg); }
    }
  </style>
</head>
<body>

<canvas id="logisticsCanvas"></canvas>
<div class="animated-bg"></div>

<div class="login-wrapper">
  <div class="login-card">
    
    <!-- Premium Brand Section -->
    <div class="brand">
      <div class="logo-icon">
        <i class="fas fa-boxes"></i>
      </div>
      <h1>LARGA Enterprise</h1>
      <div class="tagline">
        <i class="fas fa-truck-fast"></i>
        <span>CLIENT PORTAL</span>
        <i class="fas fa-box"></i>
      </div>
      <div class="sub-brand">
        <i class="fas fa-charging-station"></i> POWERED BY LARGA LOGISTICS
      </div>
    </div>

    <!-- Login Form - IDs unchanged for backend compatibility -->
    <form action="<?=site_url();?>mylogin-auth" method="post" id="loginForm">
      <div class="input-container">
        <i class="fas fa-id-card input-icon"></i>
        <input type="text" name="MyUsername" class="form-input" placeholder="Client ID / Account Number" autocomplete="username" required>
      </div>
      
      <div class="input-container">
        <i class="fas fa-key input-icon"></i>
        <input type="password" name="MyPassword" class="form-input" placeholder="Portal Password" autocomplete="current-password" required>
      </div>
      
      <button type="submit" class="login-button" id="loginButton">
        <i class="fas fa-arrow-right-to-bracket"></i> ACCESS SECURE PORTAL
      </button>

      <?php
      $msg = session()->getFlashdata('mesyszicas_memsg_login');
      if(!empty($msg)){
        echo "<div class='error-msg'><i class='fas fa-shield-exclamation'></i>".htmlspecialchars($msg)."</div>";
      }
      ?>
    </form>

    <!-- Register Account Link -->
    <div class="register-link-section">
      <a href="<?=site_url();?>myregistration?meaction=MAIN" class="register-link">
        <span>Don't have an account?</span>
        <strong>Register New Account</strong>
        <i class="fas fa-arrow-right"></i>
      </a>
    </div>

    <!-- Security & Trust Badges -->
    <div class="secure-badge">
      <i class="fas fa-lock"></i>
      <span>256-bit SSL Encrypted</span>
      <i class="fas fa-circle" style="font-size: 3px; color: var(--gray-300);"></i>
      <i class="fas fa-shield-alt"></i>
      <span>ISO 27001 Certified</span>
    </div>

    <!-- Premium Footer Stats -->
    <div class="footer-stats">
      <div class="stat"><i class="fas fa-headset"></i> 24/7 Priority Support</div>
      <div class="stat"><i class="fas fa-chart-line"></i> Real-time Analytics</div>
      <div class="stat"><i class="fas fa-file-shield"></i> Enterprise Security</div>
    </div>
  </div>
</div>
<script>
  (function() {
    // PREMIUM ENTERPRISE ANIMATION - Professional blue theme
    const canvas = document.getElementById('logisticsCanvas');
    const ctx = canvas.getContext('2d');
    
    let width = window.innerWidth;
    let height = window.innerHeight;
    
    // Premium particle system
    let particles = [];
    let connections = [];
    
    const PARTICLE_COUNT = 60;
    const CONNECTION_DISTANCE = 180;
    
    class Particle {
      constructor(x, y, vx, vy) {
        this.x = x;
        this.y = y;
        this.vx = vx;
        this.vy = vy;
        this.size = 2 + Math.random() * 2;
        this.alpha = 0.3 + Math.random() * 0.4;
      }
      update() {
        this.x += this.vx;
        this.y += this.vy;
        
        // Boundary check with smooth bounce
        if (this.x < 0) { this.x = 0; this.vx *= -0.98; }
        if (this.x > width) { this.x = width; this.vx *= -0.98; }
        if (this.y < 0) { this.y = 0; this.vy *= -0.98; }
        if (this.y > height) { this.y = height; this.vy *= -0.98; }
      }
      draw() {
        ctx.beginPath();
        ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
        ctx.fillStyle = `rgba(0, 82, 204, ${this.alpha * 0.5})`;
        ctx.fill();
        
        // Inner glow
        ctx.beginPath();
        ctx.arc(this.x, this.y, this.size * 0.5, 0, Math.PI * 2);
        ctx.fillStyle = `rgba(0, 82, 204, ${this.alpha})`;
        ctx.fill();
      }
    }
    
    function initParticles() {
      particles = [];
      for (let i = 0; i < PARTICLE_COUNT; i++) {
        let x = Math.random() * width;
        let y = Math.random() * height;
        let vx = (Math.random() - 0.5) * 0.25;
        let vy = (Math.random() - 0.5) * 0.25;
        particles.push(new Particle(x, y, vx, vy));
      }
    }
    
    function drawConnections() {
      for (let i = 0; i < particles.length; i++) {
        for (let j = i + 1; j < particles.length; j++) {
          const dx = particles[i].x - particles[j].x;
          const dy = particles[i].y - particles[j].y;
          const dist = Math.sqrt(dx * dx + dy * dy);
          
          if (dist < CONNECTION_DISTANCE) {
            const intensity = 1 - (dist / CONNECTION_DISTANCE);
            ctx.beginPath();
            ctx.moveTo(particles[i].x, particles[i].y);
            ctx.lineTo(particles[j].x, particles[j].y);
            ctx.strokeStyle = `rgba(0, 82, 204, ${0.05 + intensity * 0.08})`;
            ctx.lineWidth = 0.8;
            ctx.stroke();
          }
        }
      }
    }
    
    // Draw subtle grid pattern
    let gridOffset = 0;
    function drawGrid() {
      gridOffset = (gridOffset + 0.2) % 50;
      ctx.beginPath();
      ctx.lineWidth = 0.5;
      const step = 50;
      
      for (let x = (gridOffset % step); x < width + step; x += step) {
        ctx.beginPath();
        ctx.moveTo(x, 0);
        ctx.lineTo(x, height);
        ctx.strokeStyle = 'rgba(0, 82, 204, 0.03)';
        ctx.stroke();
      }
      for (let y = (gridOffset % step); y < height + step; y += step) {
        ctx.beginPath();
        ctx.moveTo(0, y);
        ctx.lineTo(width, y);
        ctx.stroke();
      }
    }
    
    // Draw flowing particles (shipment simulation)
    let flowOffset = 0;
    function drawFlowLines() {
      flowOffset = (flowOffset + 0.5) % 100;
      
      for (let i = 0; i < 8; i++) {
        const yPos = height * (0.2 + i * 0.08);
        const startX = (flowOffset + i * 30) % (width + 200) - 100;
        
        ctx.beginPath();
        ctx.moveTo(startX, yPos);
        ctx.lineTo(startX + 60, yPos);
        ctx.strokeStyle = 'rgba(0, 82, 204, 0.04)';
        ctx.lineWidth = 1.5;
        ctx.stroke();
        
        // Add a moving dot on the line
        const dotX = (flowOffset + i * 30 + Date.now() * 0.02) % (width + 200) - 100;
        ctx.beginPath();
        ctx.arc(dotX, yPos, 2, 0, Math.PI * 2);
        ctx.fillStyle = 'rgba(0, 82, 204, 0.15)';
        ctx.fill();
      }
    }
    
    function animate() {
      if (!ctx) return;
      ctx.clearRect(0, 0, width, height);
      
      // Very subtle gradient overlay
      const grad = ctx.createLinearGradient(0, 0, width, height);
      grad.addColorStop(0, 'rgba(240, 244, 248, 0.1)');
      grad.addColorStop(1, 'rgba(255, 255, 255, 0.05)');
      ctx.fillStyle = grad;
      ctx.fillRect(0, 0, width, height);
      
      // Update and draw elements
      for (let p of particles) p.update();
      
      drawGrid();
      drawFlowLines();
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
    
    // Premium micro-interactions
    const inputs = document.querySelectorAll('.form-input');
    inputs.forEach(inp => {
      inp.addEventListener('focus', () => {
        inp.parentElement.style.transform = 'scale(1.01)';
      });
      inp.addEventListener('blur', () => {
        inp.parentElement.style.transform = 'scale(1)';
      });
    });
    
    // Loading state on form submit
    const loginForm = document.getElementById('loginForm');
    const loginButton = document.getElementById('loginButton');
    
    if (loginForm) {
      loginForm.addEventListener('submit', function(e) {
        const username = document.querySelector('input[name="MyUsername"]').value;
        const password = document.querySelector('input[name="MyPassword"]').value;
        
        if (username && password) {
          loginButton.classList.add('loading');
          loginButton.innerHTML = '<i class="fas fa-spinner"></i> AUTHENTICATING...';
        }
      });
    }
    
    // Hover effect for stats
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