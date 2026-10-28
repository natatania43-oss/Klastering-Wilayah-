<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Dashboard Pimpinan'); ?> | Klastering Banjarmasin</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        :root {
            --sidebar-width: 260px;
            --header-height: 60px;
            --primary-color: #0d6efd;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f2f5;
        }
        
        /* Sidebar Pimpinan (Warna Berbeda) */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(180deg, #1a1a2e 0%, #16213e 100%);
            color: #fff;
            transition: all 0.3s ease;
            z-index: 1050;
            overflow-y: auto;
        }
        
        .sidebar::-webkit-scrollbar {
            width: 4px;
        }
        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.3);
            border-radius: 4px;
        }
        
        .sidebar-brand {
            padding: 20px 24px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .sidebar-brand i {
            font-size: 28px;
            color: #ffd700;
        }
        .sidebar-brand span {
            font-weight: 700;
            font-size: 1.1rem;
        }
        .sidebar-brand small {
            font-weight: 400;
            font-size: 0.65rem;
            opacity: 0.7;
        }
        
        .sidebar-menu {
            padding: 20px 0;
        }
        .sidebar-menu .menu-label {
            padding: 8px 24px;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.5;
        }
        .sidebar-menu .nav-link {
            color: rgba(255,255,255,0.6);
            padding: 10px 24px;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }
        .sidebar-menu .nav-link:hover {
            color: #fff;
            background: rgba(255,255,255,0.05);
            border-left-color: #ffd700;
        }
        .sidebar-menu .nav-link.active {
            color: #fff;
            background: rgba(255,215,0,0.1);
            border-left-color: #ffd700;
        }
        .sidebar-menu .nav-link i {
            width: 20px;
            text-align: center;
        }
        
        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
        }
        
        /* Header Pimpinan (Berbeda) */
        .header {
            background: #fff;
            padding: 0 24px;
            height: var(--header-height);
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 1040;
        }
        
        .header-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .header-toggle {
            background: none;
            border: none;
            font-size: 1.2rem;
            color: #6c757d;
            cursor: pointer;
            padding: 4px 8px;
            border-radius: 6px;
            transition: all 0.3s ease;
        }
        .header-toggle:hover {
            background: #f0f2f5;
        }
        
        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .header-profile {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            padding: 6px 12px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        .header-profile:hover {
            background: #f0f2f5;
        }
        .header-profile .avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, #0d6efd, #0a3d91);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
        }
        .header-profile .name {
            font-weight: 600;
            font-size: 0.9rem;
            color: #212529;
        }
        .header-profile .role {
            font-size: 0.75rem;
            color: #6c757d;
        }
        
        /* Content */
        .content {
            padding: 24px;
        }
        
        /* Cards */
        .card {
            border: none;
            border-radius: 12px;
            transition: all 0.3s ease;
        }
        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.08) !important;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
            }
        }
        
        /* Print */
        @media print {
            .sidebar, .header, .no-print {
                display: none !important;
            }
            .main-content {
                margin-left: 0 !important;
            }
            .content {
                padding: 0 !important;
            }
        }
   </style>
</head>

<body>

    
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <i class="fas fa-chart-pie"></i>

            <div>
                <span>Klastering Banjarmasin</span>
                <small class="d-block">Panel Pimpinan</small>
            </div>
        </div>

        <div class="sidebar-menu">
            <div class="menu-label">Menu Utama</div>

            <a
                href="<?php echo e(route('pimpinan.dashboard')); ?>"
                class="nav-link <?php echo e(request()->routeIs('pimpinan.dashboard') ? 'active' : ''); ?>"
            >
                <i class="fas fa-home"></i>
                Dashboard
            </a>

            <div class="menu-label mt-3">Akun</div>

            <a
                href="<?php echo e(route('logout')); ?>"
                class="nav-link"
                onclick="
                    event.preventDefault();
                    document.getElementById('logout-form').submit();
                "
            >
                <i class="fas fa-sign-out-alt"></i>
                Keluar
            </a>

            <form
                id="logout-form"
                action="<?php echo e(route('logout')); ?>"
                method="POST"
                class="d-none"
            >
                <?php echo csrf_field(); ?>
            </form>
        </div>
    </aside>

    
    <div class="main-content">

        
        <header class="header">
            <div class="header-left">
                <button
                    type="button"
                    class="header-toggle"
                    id="sidebarToggle"
                >
                    <i class="fas fa-bars"></i>
                </button>

                <div>
                    <div class="fw-semibold">
                        <?php echo $__env->yieldContent('page-title', 'Dashboard Pimpinan'); ?>
                    </div>

                    <div class="small text-muted">
                        Sistem Klastering Partisipasi Pemilih
                    </div>
                </div>
            </div>

            <div class="header-right">
                <div class="header-profile">
                    <div class="avatar">
                        <?php echo e(strtoupper(
                            substr(Auth::user()->name ?? 'P', 0, 1)
                        )); ?>

                    </div>

                    <div class="d-none d-md-block">
                        <div class="name">
                            <?php echo e(Auth::user()->name ?? 'Pimpinan'); ?>

                        </div>

                        <div class="role">
                            Pimpinan
                        </div>
                    </div>
                </div>
            </div>
        </header>

        
        <main class="content">
            <?php echo $__env->yieldContent('content'); ?>
        </main>

    </div>

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    </script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');

        sidebarToggle?.addEventListener('click', function () {
            sidebar?.classList.toggle('show');
        });
    </script>

    <?php echo $__env->yieldPushContent('scripts'); ?>

</body>
</html><?php /**PATH C:\laragon\www\Latihan\resources\views/layouts/pimpinan.blade.php ENDPATH**/ ?>