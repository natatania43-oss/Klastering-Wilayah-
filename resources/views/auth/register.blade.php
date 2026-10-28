<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi | Klastering Partisipasi Pemilih Banjarmasin</title>
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

        .register-container {
            width: 100%;
            max-width: 520px;
        }

        .register-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 40px 35px;
            position: relative;
            overflow: hidden;
            animation: fadeInUp 0.6s ease;
        }

        .register-card::before {
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

        .register-brand {
            text-align: center;
            margin-bottom: 25px;
        }

        .register-brand .icon {
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

        .register-brand .icon i {
            font-size: 32px;
            color: #fff;
        }

        .register-brand h4 {
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 5px;
        }

        .register-brand p {
            color: #6c757d;
            font-size: 0.9rem;
            margin-bottom: 0;
        }

        .register-brand .subtitle {
            color: #0d6efd;
            font-weight: 600;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        .form-group {
            margin-bottom: 18px;
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

        .input-group-custom .form-control,
        .input-group-custom .form-select {
            padding: 12px 15px 12px 45px;
            border-radius: 12px;
            border: 2px solid #e9ecef;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            height: 52px;
        }

        .input-group-custom .form-control:focus,
        .input-group-custom .form-select:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1);
        }

        .input-group-custom .form-control:focus + .input-icon,
        .input-group-custom .form-select:focus + .input-icon {
            color: #0d6efd;
        }

        .input-group-custom .form-control.is-invalid,
        .input-group-custom .form-select.is-invalid {
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

        .form-text-custom {
            font-size: 0.75rem;
            color: #6c757d;
            margin-top: 4px;
        }

        .btn-register {
            width: 100%;
            padding: 14px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            background: linear-gradient(135deg, #0d6efd, #0a3d91);
            border: none;
            color: #fff;
            transition: all 0.3s ease;
            height: 52px;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(13, 110, 253, 0.4);
        }

        .btn-register:active {
            transform: translateY(0);
        }

        .btn-register:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        .btn-register .spinner {
            display: none;
        }

        .btn-register.loading .spinner {
            display: inline-block;
        }

        .btn-register.loading .btn-text {
            display: none;
        }

        .btn-login {
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

        .btn-login:hover {
            background: #0d6efd;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(13, 110, 253, 0.3);
            text-decoration: none;
        }

        .register-footer {
            text-align: center;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
        }

        .register-footer p {
            color: #6c757d;
            font-size: 0.9rem;
            margin-bottom: 0;
        }

        .register-footer a {
            color: #0d6efd;
            text-decoration: none;
            font-weight: 600;
        }

        .register-footer a:hover {
            text-decoration: underline;
        }

        .register-footer .version {
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

        .role-card {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
        }

        .role-card:hover {
            border-color: #0d6efd;
            background: #f8f9ff;
        }

        .role-card.active {
            border-color: #0d6efd;
            background: #f0f5ff;
            box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1);
        }

        .role-card .role-icon {
            font-size: 28px;
            margin-bottom: 8px;
        }

        .role-card .role-name {
            font-weight: 600;
            font-size: 0.9rem;
        }

        .role-card .role-desc {
            font-size: 0.75rem;
            color: #6c757d;
        }

        .password-strength {
            height: 4px;
            border-radius: 2px;
            margin-top: 6px;
            transition: all 0.3s ease;
            background: #e9ecef;
        }

        .password-strength .bar {
            height: 100%;
            border-radius: 2px;
            transition: all 0.3s ease;
            width: 0%;
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

        @media (max-width: 576px) {
            .register-card {
                padding: 30px 20px;
            }

            .register-brand .icon {
                width: 60px;
                height: 60px;
            }

            .register-brand .icon i {
                font-size: 26px;
            }

            .register-brand h4 {
                font-size: 1.3rem;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-card">
            {{-- Brand --}}
            <div class="register-brand">
                <div class="icon">
                    <i class="fas fa-user-plus"></i>
                </div>
                <h4>Daftar Akun</h4>
                <p class="subtitle">Klastering Partisipasi Pemilih</p>
                <p style="font-size:0.8rem; color:#6c757d; margin-top:5px;">
                    <i class="fas fa-map-marker-alt me-1" style="color:#0d6efd;"></i> 
                    Kota Banjarmasin
                </p>
            </div>

            {{-- Error Messages --}}
            @if ($errors->any())
                <div class="alert alert-custom alert-danger mb-4">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Form Registrasi --}}
            <form method="POST" action="{{ route('register') }}" id="registerForm">
                @csrf

                {{-- Nama Lengkap --}}
                <div class="form-group">
                    <label for="name">
                        <i class="fas fa-user me-1" style="color:#0d6efd;"></i>
                        Nama Lengkap
                        <span class="text-danger">*</span>
                    </label>
                    <div class="input-group-custom">
                        <input 
                            id="name" 
                            type="text" 
                            class="form-control @error('name') is-invalid @enderror" 
                            name="name" 
                            value="{{ old('name') }}" 
                            required 
                            autofocus
                            placeholder="Masukkan nama lengkap"
                        >
                        <i class="fas fa-user input-icon"></i>
                    </div>
                </div>

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
                            placeholder="Masukkan alamat email"
                        >
                        <i class="fas fa-envelope input-icon"></i>
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
                            placeholder="Minimal 8 karakter"
                        >
                        <i class="fas fa-lock input-icon"></i>
                        <button type="button" class="toggle-password" onclick="togglePassword('password')">
                            <i class="fas fa-eye" id="passwordIcon"></i>
                        </button>
                    </div>
                    <div class="password-strength" id="passwordStrength">
                        <div class="bar" id="strengthBar"></div>
                    </div>
                    <div class="form-text-custom">
                        <i class="fas fa-info-circle me-1"></i>
                        Password minimal 8 karakter
                    </div>
                </div>

                {{-- Konfirmasi Password --}}
                <div class="form-group">
                    <label for="password_confirmation">
                        <i class="fas fa-check-circle me-1" style="color:#0d6efd;"></i>
                        Konfirmasi Password
                        <span class="text-danger">*</span>
                    </label>
                    <div class="input-group-custom">
                        <input 
                            id="password_confirmation" 
                            type="password" 
                            class="form-control" 
                            name="password_confirmation" 
                            required
                            placeholder="Masukkan ulang password"
                        >
                        <i class="fas fa-check-circle input-icon"></i>
                    </div>
                </div>

                {{-- Role --}}
                <div class="form-group">
                    <label>
                        <i class="fas fa-user-tag me-1" style="color:#0d6efd;"></i>
                        Pilih Role
                        <span class="text-danger">*</span>
                    </label>
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="role-card" onclick="selectRole('admin')" id="role-admin">
                                <div class="role-icon text-primary">
                                    <i class="fas fa-user-shield"></i>
                                </div>
                                <div class="role-name">Admin</div>
                                <div class="role-desc">Kelola data & sistem</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="role-card" onclick="selectRole('pimpinan')" id="role-pimpinan">
                                <div class="role-icon text-success">
                                    <i class="fas fa-user-tie"></i>
                                </div>
                                <div class="role-name">Pimpinan</div>
                                <div class="role-desc">Lihat analisis & laporan</div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="role" id="selectedRole" value="{{ old('role', 'admin') }}">
                    @error('role')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Jabatan --}}
                <div class="form-group">
                    <label for="jabatan">
                        <i class="fas fa-briefcase me-1" style="color:#0d6efd;"></i>
                        Jabatan
                        <span class="text-danger">*</span>
                    </label>
                    <div class="input-group-custom">
                        <input 
                            id="jabatan" 
                            type="text" 
                            class="form-control @error('jabatan') is-invalid @enderror" 
                            name="jabatan" 
                            value="{{ old('jabatan') }}" 
                            required
                            placeholder="Contoh: Administrator Sistem"
                        >
                        <i class="fas fa-briefcase input-icon"></i>
                    </div>
                </div>

                {{-- No Telepon --}}
                <div class="form-group">
                    <label for="no_telepon">
                        <i class="fas fa-phone me-1" style="color:#0d6efd;"></i>
                        No. Telepon
                    </label>
                    <div class="input-group-custom">
                        <input 
                            id="no_telepon" 
                            type="text" 
                            class="form-control @error('no_telepon') is-invalid @enderror" 
                            name="no_telepon" 
                            value="{{ old('no_telepon') }}"
                            placeholder="Contoh: 081234567890"
                        >
                        <i class="fas fa-phone input-icon"></i>
                    </div>
                </div>

                {{-- Tombol Register --}}
                <button type="submit" class="btn-register" id="registerButton">
                    <span class="spinner">
                        <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                    </span>
                    <span class="btn-text">
                        <i class="fas fa-user-plus me-2"></i>
                        Daftar Sekarang
                    </span>
                </button>
            </form>

            {{-- Divider --}}
            <div class="divider">
                <span>atau</span>
            </div>

            {{-- Tombol Login --}}
            <a href="{{ route('login') }}" class="btn-login">
                <i class="fas fa-sign-in-alt me-2"></i>
                Masuk ke Akun
            </a>

            {{-- Footer --}}
            <div class="register-footer">
                <p>
                    Sudah punya akun? 
                    <a href="{{ route('login') }}">
                        <i class="fas fa-sign-in-alt me-1"></i>
                        Masuk di sini
                    </a>
                </p>
                <p class="version mt-2">
                    <i class="far fa-copyright me-1"></i>
                    {{ date('Y') }} Dinas Pemberdayaan Masyarakat Kota Banjarmasin
                </p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle Password Visibility
        function togglePassword(fieldId) {
            const passwordInput = document.getElementById(fieldId);
            const passwordIcon = document.getElementById('passwordIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.className = 'fas fa-eye-slash';
            } else {
                passwordInput.type = 'password';
                passwordIcon.className = 'fas fa-eye';
            }
        }

        // Select Role
        function selectRole(role) {
            // Hapus active dari semua role
            document.querySelectorAll('.role-card').forEach(card => {
                card.classList.remove('active');
            });
            
            // Tambahkan active ke role yang dipilih
            document.getElementById('role-' + role).classList.add('active');
            
            // Set value hidden
            document.getElementById('selectedRole').value = role;
        }

        // Set default role
        document.addEventListener('DOMContentLoaded', function() {
            const defaultRole = document.getElementById('selectedRole').value || 'admin';
            selectRole(defaultRole);
        });

        // Password Strength Indicator
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const strengthBar = document.getElementById('strengthBar');
            
            if (password.length === 0) {
                strengthBar.style.width = '0%';
                strengthBar.style.background = '#e9ecef';
                return;
            }
            
            let score = 0;
            if (password.length >= 8) score++;
            if (password.match(/[a-z]/)) score++;
            if (password.match(/[A-Z]/)) score++;
            if (password.match(/[0-9]/)) score++;
            if (password.match(/[^a-zA-Z0-9]/)) score++;
            
            const percentage = (score / 5) * 100;
            strengthBar.style.width = percentage + '%';
            
            if (score <= 2) {
                strengthBar.style.background = '#dc3545';
            } else if (score <= 3) {
                strengthBar.style.background = '#ffc107';
            } else if (score <= 4) {
                strengthBar.style.background = '#0d6efd';
            } else {
                strengthBar.style.background = '#198754';
            }
        });

        // Confirm password match
        document.getElementById('password_confirmation').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirm = this.value;
            
            if (confirm.length > 0 && password !== confirm) {
                this.style.borderColor = '#dc3545';
            } else {
                this.style.borderColor = '#e9ecef';
            }
        });

        // Loading State on Submit
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const button = document.getElementById('registerButton');
            button.classList.add('loading');
            button.disabled = true;
        });
    </script>
</body>
</html>