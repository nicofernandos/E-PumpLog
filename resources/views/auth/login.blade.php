<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Login - EPumpLog</title>
  
  <!-- plugins:css -->
  <link rel="stylesheet" href="{{ asset('assets/vendors/feather/feather.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendors/ti-icons/css/themify-icons.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendors/typicons/typicons.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendors/simple-line-icons/css/simple-line-icons.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
  
  <!-- inject:css -->
  <link rel="stylesheet" href="{{ asset('assets/css/vertical-layout-light/style.css') }}">
  
  <!-- Favicon -->
  <link rel="shortcut icon" href="{{ asset('images/logo/av.png') }}" />
  
  <style>
    /* Custom Styles for Modern Login */
    .auth-page-wrapper {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      position: relative;
      overflow: hidden;
    }
    
    /* Animated Background Shapes */
    .auth-page-wrapper::before {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
      background-size: 50px 50px;
      animation: backgroundMove 20s linear infinite;
    }
    
    @keyframes backgroundMove {
      0% { transform: translate(0, 0); }
      100% { transform: translate(50px, 50px); }
    }
    
    /* Floating Shapes */
    .shape {
      position: absolute;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(10px);
    }
    
    .shape-1 {
      width: 300px;
      height: 300px;
      top: -100px;
      right: -100px;
      animation: float 6s ease-in-out infinite;
    }
    
    .shape-2 {
      width: 200px;
      height: 200px;
      bottom: -50px;
      left: -50px;
      animation: float 8s ease-in-out infinite reverse;
    }
    
    .shape-3 {
      width: 150px;
      height: 150px;
      top: 50%;
      left: 10%;
      animation: float 7s ease-in-out infinite;
    }
    
    @keyframes float {
      0%, 100% { transform: translateY(0px) rotate(0deg); }
      50% { transform: translateY(-20px) rotate(5deg); }
    }
    
    /* Login Card Styles */
    .login-card {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(20px);
      border-radius: 20px;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
      padding: 50px 40px;
      max-width: 450px;
      width: 100%;
      position: relative;
      z-index: 10;
      animation: slideUp 0.6s ease-out;
    }
    
    @keyframes slideUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    
    /* Logo Styles */
    .brand-logo {
      text-align: center;
      margin-bottom: 30px;
    }
    
    .brand-logo img {
      max-width: 180px;
      height: auto;
      filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.1));
      transition: transform 0.3s ease;
      border-radius: 10px;
    }
    
    .brand-logo img:hover {
      transform: scale(1.05);
    }
    
    /* Title Styles */
    .login-title {
      font-size: 28px;
      font-weight: 600;
      color: #2c2c54;
      margin-bottom: 10px;
      text-align: center;
    }
    
    .login-subtitle {
      font-size: 14px;
      color: #7c7c9a;
      text-align: center;
      margin-bottom: 35px;
    }
    
    /* Form Styles */
    .form-group {
      margin-bottom: 25px;
      position: relative;
    }
    
    .form-group label {
      font-weight: 500;
      color: #4a4a6a;
      margin-bottom: 8px;
      font-size: 14px;
    }
    
    .form-control {
      height: 50px;
      border-radius: 12px;
      border: 2px solid #e8e8f0;
      padding: 12px 20px 12px 50px;
      font-size: 14px;
      transition: all 0.3s ease;
      background: #f8f9fa;
    }
    
    .form-control:focus {
      border-color: #667eea;
      box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
      background: #fff;
    }
    
    /* Icon in Input */
    .input-icon {
      position: absolute;
      left: 18px;
      top: 50%;
      transform: translateY(-50%);
      color: #9b9bb4;
      font-size: 18px;
      transition: color 0.3s ease;
    }
    
    .form-control:focus + .input-icon {
      color: #667eea;
    }
    
    /* Button Styles */
    .btn-login {
      height: 50px;
      border-radius: 12px;
      font-size: 16px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      border: none;
      color: #fff;
      transition: all 0.3s ease;
      box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
    }
    
    .btn-login:hover {
      transform: translateY(-2px);
      box-shadow: 0 12px 30px rgba(102, 126, 234, 0.4);
      background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
    }
    
    .btn-login:active {
      transform: translateY(0);
    }
    
    /* Remember Me & Forgot Password */
    .form-check-label {
      font-size: 14px;
      color: #6c757d;
      cursor: pointer;
      user-select: none;
    }
    
    .forgot-link {
      font-size: 14px;
      color: #667eea;
      text-decoration: none;
      font-weight: 500;
      transition: color 0.3s ease;
    }
    
    .forgot-link:hover {
      color: #764ba2;
      text-decoration: underline;
    }
    
    /* Alert Styles */
    .alert {
      border-radius: 12px;
      border: none;
      font-size: 14px;
      margin-bottom: 25px;
      animation: slideDown 0.4s ease-out;
    }
    
    @keyframes slideDown {
      from {
        opacity: 0;
        transform: translateY(-10px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    
    .alert-danger {
      background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
      color: #fff;
    }
    
    .alert-success {
      background: linear-gradient(135deg, #51cf66 0%, #40c057 100%);
      color: #fff;
    }
    
    /* Loading Animation */
    .btn-login .spinner-border {
      width: 18px;
      height: 18px;
      border-width: 2px;
    }
    
    /* Responsive */
    @media (max-width: 576px) {
      .login-card {
        padding: 35px 25px;
        margin: 20px;
      }
      
      .login-title {
        font-size: 24px;
      }
      
      .shape-1, .shape-2, .shape-3 {
        display: none;
      }
    }
    
    /* Custom Checkbox */
    .form-check-input:checked {
      background-color: #667eea;
      border-color: #667eea;
    }
    
    .form-check-input:focus {
      box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
  </style>
</head>

<body>
  <div class="auth-page-wrapper">
    <!-- Animated Background Shapes -->
    <div class="shape shape-1"></div>
    <div class="shape shape-2"></div>
    <div class="shape shape-3"></div>
    
    <!-- Login Card -->
    <div class="login-card">
      <!-- Logo -->
      <div class="brand-logo">
        <img src="{{ asset('images/logo/logo.svg') }}" alt="Company Logo">
      </div>
      
      <!-- Title -->
      <h4 class="login-title">Selamat Datang!</h4>
      <p class="login-subtitle">Silakan masuk ke akun Anda</p>
      
      <!-- Error Alert -->
      @if ($errors->any())
        <div class="alert alert-danger" role="alert">
          <i class="mdi mdi-alert-circle me-2"></i>
          <strong>Oops!</strong> {{ $errors->first() }}
        </div>
      @endif
      
      <!-- Success Message -->
      @if (session('success'))
        <div class="alert alert-success" role="alert">
          <i class="mdi mdi-check-circle me-2"></i>
          {{ session('success') }}
        </div>
      @endif
      
      <form method="POST" action="{{ route('login') }}" id="loginForm">
        @csrf
        
        <!-- Email Field -->
        <div class="form-group">
          <label for="email">Email Address</label>
          <div class="position-relative">
            <input type="email" 
                   class="form-control @error('email') is-invalid @enderror" 
                   id="email" 
                   name="email" 
                   value="{{ old('email') }}"
                   placeholder="Enter your email" 
                   required 
                   autofocus>
            <i class="mdi mdi-email-outline input-icon"></i>
          </div>
          @error('email')
            <div class="text-danger mt-1" style="font-size: 13px;">{{ $message }}</div>
          @enderror
        </div>
        
        <!-- Password Field -->
        <div class="form-group">
          <label for="password">Password</label>
          <div class="position-relative">
            <input type="password" 
                   class="form-control @error('password') is-invalid @enderror" 
                   id="password" 
                   name="password" 
                   placeholder="Enter your password" 
                   required>
            <i class="mdi mdi-lock-outline input-icon"></i>
          </div>
          @error('password')
            <div class="text-danger mt-1" style="font-size: 13px;">{{ $message }}</div>
          @enderror
        </div>
        
        <!-- Remember Me & Forgot Password -->
        <div class="d-flex justify-content-between align-items-center mb-4">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
            <label class="form-check-label" for="remember">
              Remember me
            </label>
          </div>
          <a href="" class="forgot-link">Forgot Password?</a>
        </div>
        
        <!-- Submit Button -->
        <button type="submit" class="btn btn-login w-100" id="loginBtn">
          <span class="btn-text">Sign In</span>
          <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
        </button>
      </form>
      
      <!-- Footer Text -->
      <div class="text-center mt-4">
        <p class="text-muted" style="font-size: 13px;">
          © {{ date('Y') }} E-PumpLog. All rights reserved.
        </p>
      </div>
    </div>
  </div>

  <!-- plugins:js -->
  <script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
  <script src="{{ asset('assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
  <script src="{{ asset('assets/js/off-canvas.js') }}"></script>
  <script src="{{ asset('assets/js/hoverable-collapse.js') }}"></script>
  <script src="{{ asset('assets/js/template.js') }}"></script>
  <script src="{{ asset('assets/js/settings.js') }}"></script>
  <script src="{{ asset('assets/js/todolist.js') }}"></script>
  
  <!-- Custom Login Script -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const loginForm = document.getElementById('loginForm');
      const loginBtn = document.getElementById('loginBtn');
      const btnText = loginBtn.querySelector('.btn-text');
      const spinner = loginBtn.querySelector('.spinner-border');
      
      // Form Submit Handler with Loading State
      loginForm.addEventListener('submit', function(e) {
        // Show loading state
        btnText.textContent = 'Signing In...';
        spinner.classList.remove('d-none');
        loginBtn.disabled = true;
      });
      
      // Input Focus Animation
      const formControls = document.querySelectorAll('.form-control');
      formControls.forEach(input => {
        input.addEventListener('focus', function() {
          this.parentElement.classList.add('focused');
        });
        
        input.addEventListener('blur', function() {
          if (this.value === '') {
            this.parentElement.classList.remove('focused');
          }
        });
      });
      
      // Auto-hide alerts after 5 seconds
      const alerts = document.querySelectorAll('.alert');
      alerts.forEach(alert => {
        setTimeout(() => {
          alert.style.animation = 'slideUp 0.4s ease-out';
          setTimeout(() => {
            alert.remove();
          }, 400);
        }, 5000);
      });
      
      // Password Toggle (Optional Enhancement)
      const passwordInput = document.getElementById('password');
      const passwordIcon = passwordInput.nextElementSibling;
      
      // You can add a toggle button here if needed
      // Example: Click icon to show/hide password
      passwordIcon.style.cursor = 'pointer';
      passwordIcon.addEventListener('click', function() {
        if (passwordInput.type === 'password') {
          passwordInput.type = 'text';
          this.classList.remove('mdi-lock-outline');
          this.classList.add('mdi-lock-open-outline');
        } else {
          passwordInput.type = 'password';
          this.classList.remove('mdi-lock-open-outline');
          this.classList.add('mdi-lock-outline');
        }
      });
    });
  </script>
</body>

</html>