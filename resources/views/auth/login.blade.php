<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Klastering Partisipasi Pemilih Banjarmasin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #0d6efd 0%, #0a3d91 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            width: 100%;
            max-width: 430px;
        }

        .login-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 40px 35px;
            position: relative;
            overflow: hidden;
            animation: fadeInUp 0.6s ease;
        }

        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #0d6efd, #0a3d91, #0d6efd);
            background-size: 200% 100%;
            animation: gradientMove 3s ease infinite;
        }

        @keyframes gradientMove {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-brand {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-brand .icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #0d6efd, #0a3d91);
            border-radius: 18px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            box-shadow: 0 8px 25px rgba(13, 110, 253, 0.3);
        }

        .login-brand .icon i {
            font-size: 32px;
            color: #fff;
        }

        .login-brand h4 {
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 5px;
        }

        .login-brand p {
            color: #6c757d;
            font-size: 0.9rem;
            margin-bottom: 0;
        }

        .login-brand .subtitle {
            color: #0d6efd;
            font-weight: 600;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            font-weight: 600;
            color: #1a1a2e;
            font-size: 0.9rem;
            margin-bottom: 6px;
        }

        .form-group label .text-danger {
            color: #dc3545;
        }

        .input-group-custom {
            position: relative;
        }

        .input-group-custom .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #adb5bd;
            font-size: 16px;
            transition: all 0.3s ease;
            z-index: 4;
        }

        .input-group-custom .form-control {
            padding: 12px 15px 12px 45px;
            border-radius: 12px;
            border: 2px solid #e9ecef;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            height: 52px;
        }

        .input-group-custom .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1);
        }

        .input-group-custom .form-control:focus + .input-icon {
            color: #0d6efd;
        }

        .input-group-custom .form-control.is-invalid {
            border-color: #dc3545;
        }

        .input-group-custom .form-control.is-invalid + .input-icon {
            color: #dc3545;
        }

        .input-group-custom .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #adb5bd;
            cursor: pointer;
            padding: 5px;
            transition: all 0.3s ease;
            z-index: 4;
        }

        .input-group-custom .toggle-password:hover {
            color: #0d6efd;
        }

        .form-check {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-check input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: #0d6efd;
            cursor: pointer;
        }

        .form-check label {
            color: #495057;
            font-size: 0.9rem;
            cursor: pointer;
            margin-bottom: 0;
        }

        .btn-login {
            width: 100%;
            padding: 14px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            background: linear-gradient(135deg, #0d6efd, #0a3d91);
            border: none;
            color: #fff;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            height: 52px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(13, 110, 253, 0.4);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .btn-login:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        .btn-login .spinner {
            display: none;
        }

        .btn-login.loading .spinner {
            display: inline-block;
        }

        .btn-login.loading .btn-text {
            display: none;
        }

        .btn-register {
            width: 100%;
            padding: 14px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            background: transparent;
            border: 2px solid #0d6efd;
            color: #0d6efd;
            transition: all 0.3s ease;
            height: 52px;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-register:hover {
            background: #0d6efd;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(13, 110, 253, 0.3);
            text-decoration: none;
        }

        .login-footer {
            text-align: center;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
        }

        .login-footer p {
            color: #6c757d;
            font-size: 0.85rem;
            margin-bottom: 0;
        }

        .login-footer .version {
            color: #adb5bd;
            font-size: 0.75rem;
        }

        .alert-custom {
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 0.9rem;
            border: none;
        }

        .alert-custom.alert-danger {
            background: #fff5f5;
            color: #dc3545;
            border-left: 4px solid #dc3545;
        }

        .alert-custom.alert-success {
            background: #f0fff4;
            color: #198754;
            border-left: 4px solid #198754;
        }

        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 20px 0;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #e9ecef;
        }

        .divider span {
            padding: 0 15px;
            color: #6c757d;
            font-size: 0.85rem;
        }

        /* Responsive */
        @media (max-width: 576px) {
            .login-card {
                padding: 30px 20px;
            }

            .login-brand .icon {
                width: 60px;
                height: 60px;
            }

            .login-brand .icon i {
                font-size: 26px;
            }

            .login-brand h4 {
                font-size: 1.3rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            {{-- Brand --}}
           <div class="login-brand">
    <div class="icon">
        <i class="fas fa-chart-pie"></i>
    </div>

    <h4>Sistem Informasi Pengelompokan Wilayah</h4>

    <p class="subtitle">
        Tingkat Partisipasi Pemilih
    </p>

    <p style="font-size:0.8rem; color:#6c757d; margin-top:5px;">
        <i class="fas fa-map-marker-alt me-1" style="color:#0d6efd;"></i>
        Kota Banjarmasin • K-Means Clustering
    </p>
</div>

            {{-- Session Status --}}
            @if (session('status'))
                <div class="alert alert-custom alert-success mb-4">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-custom alert-danger mb-4">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            {{-- Form Login --}}
            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf

                {{-- Email --}}
                <div class="form-group">
                    <label for="email">
                        <i class="fas fa-envelope me-1" style="color:#0d6efd;"></i>
                        Alamat Email
                        <span class="text-danger">*</span>
                    </label>
                    <div class="input-group-custom">
                        <input 
                            id="email" 
                            type="email" 
                            class="form-control @error('email') is-invalid @enderror" 
                            name="email" 
                            value="{{ old('email') }}" 
                            required 
                            autofocus 
                            autocomplete="username"
                            placeholder="Masukkan alamat email"
                        >
                        <i class="fas fa-envelope input-icon"></i>
                        @error('email')
                            <div class="invalid-feedback d-block mt-1">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                {{-- Password --}}
                <div class="form-group">
                    <label for="password">
                        <i class="fas fa-lock me-1" style="color:#0d6efd;"></i>
                        Password
                        <span class="text-danger">*</span>
                    </label>
                    <div class="input-group-custom">
                        <input 
                            id="password" 
                            type="password" 
                            class="form-control @error('password') is-invalid @enderror" 
                            name="password" 
                            required 
                            autocomplete="current-password"
                            placeholder="Masukkan password"
                        >
                        <i class="fas fa-lock input-icon"></i>
                        <button type="button" class="toggle-password" onclick="togglePassword()">
                            <i class="fas fa-eye" id="passwordIcon"></i>
                        </button>
                        @error('password')
                            <div class="invalid-feedback d-block mt-1">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                {{-- Remember Me & Forgot Password --}}
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input 
                            type="checkbox" 
                            name="remember" 
                            id="remember_me" 
                            {{ old('remember') ? 'checked' : '' }}
                        >
                        <label for="remember_me">Ingat saya</label>
                    </div>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-decoration-none" style="font-size:0.9rem; color:#0d6efd;">
                            <i class="fas fa-key me-1"></i>
                            Lupa Password?
                        </a>
                    @endif
                </div>

                {{-- Login Button --}}
                <button type="submit" class="btn-login" id="loginButton">
                    <span class="spinner">
                        <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                    </span>
                    <span class="btn-text">
                        <i class="fas fa-sign-in-alt me-2"></i>
                        Masuk
                    </span>
                </button>
            </form>

            {{-- Divider --}}
            <div class="divider">
                <span>atau</span>
            </div>

            {{-- Register Button --}}
            <a href="{{ route('register') }}" class="btn-register">
                <i class="fas fa-user-plus me-2"></i>
                Daftar Akun Baru
            </a>

            {{-- Footer --}}
            <div class="login-footer">
                <p>
                    <i class="fas fa-shield-alt me-1" style="color:#0d6efd;"></i>
                    Sistem Informasi Pengelompokan Wilayah Partisipasi Pemilih
                </p>
                <p class="version">
                    <i class="far fa-copyright me-1"></i>
                    {{ date('Y') }} Badan Kesatuan Bangsa daN Politik 
                </p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle Password Visibility
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const passwordIcon = document.getElementById('passwordIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.className = 'fas fa-eye-slash';
            } else {
                passwordInput.type = 'password';
                passwordIcon.className = 'fas fa-eye';
            }
        }

        // Loading State on Submit
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const button = document.getElementById('loginButton');
            button.classList.add('loading');
            button.disabled = true;
        });

        // Auto focus on email if exists
        document.addEventListener('DOMContentLoaded', function() {
            const emailInput = document.getElementById('email');
            if (emailInput.value) {
                const passwordInput = document.getElementById('password');
                passwordInput.focus();
            }
        });

        // Enter key to submit
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                const form = document.getElementById('loginForm');
                const activeElement = document.activeElement;
                if (activeElement && (activeElement.id === 'email' || activeElement.id === 'password')) {
                    form.submit();
                }
            }
        });

        // Demo credentials helper
        console.log('🔑 Demo Credentials:');
        console.log('📧 admin@klastering.com');
        console.log('🔒 password123');
        console.log('📧 pimpinan@klastering.com');
        console.log('🔒 password123');
        console.log('📧 superadmin@klastering.com');
        console.log('🔒 password123');
    </script>
</body>
</html>