<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Dashboard'); ?> | Klastering Banjarmasin</title>

    <!-- Font & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --sidebar-width: 270px;
            --sidebar-collapsed-width: 80px;
            --header-height: 70px;
            --sidebar-bg-start: #0f172a;
            --sidebar-bg-end: #1e293b;
            --accent-color: #3b82f6;
            --accent-glow: rgba(59, 130, 246, 0.35);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
            color: #334155;
            overflow-x: hidden;
        }

        /* --- SIDEBAR STYLING --- */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(180deg, var(--sidebar-bg-start) 0%, var(--sidebar-bg-end) 100%);
            color: #f8fafc;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1050;
            display: flex;
            flex-direction: column;
            box-shadow: 4px 0 25px rgba(0, 0, 0, 0.15);
            white-space: nowrap;
        }

        /* Mode Collapsed (Desktop) */
        body.sidebar-collapsed .sidebar {
            width: var(--sidebar-collapsed-width);
        }

        body.sidebar-collapsed .main-content {
            margin-left: var(--sidebar-collapsed-width);
        }

        /* Hide Text & Headers in Collapsed Mode */
        body.sidebar-collapsed .sidebar .brand-text,
        body.sidebar-collapsed .sidebar .menu-header,
        body.sidebar-collapsed .sidebar .nav-text,
        body.sidebar-collapsed .sidebar .user-info {
            opacity: 0;
            visibility: hidden;
            width: 0;
            display: none;
        }

        body.sidebar-collapsed .sidebar-brand {
            justify-content: center;
            padding: 20px 0;
        }

        body.sidebar-collapsed .sidebar-menu .nav-link {
            justify-content: center;
            padding: 12px 0;
        }

        body.sidebar-collapsed .sidebar-footer {
            padding: 12px;
            justify-content: center;
        }

        body.sidebar-collapsed .user-card {
            justify-content: center;
            padding: 6px;
        }

        /* Brand Header */
        .sidebar-brand {
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 14px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            transition: all 0.3s ease;
        }

        .brand-icon {
            width: 42px;
            height: 42px;
            min-width: 42px;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: #fff;
            box-shadow: 0 4px 12px var(--accent-glow);
        }

        .brand-text h6 {
            font-weight: 700;
            font-size: 0.98rem;
            margin: 0;
            color: #fff;
        }

        .brand-text small {
            font-size: 0.7rem;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            font-weight: 600;
        }

        /* Navigation Menu */
        .sidebar-menu {
            padding: 16px 12px;
            flex-grow: 1;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .menu-header {
            font-size: 0.68rem;
            text-transform: uppercase;
            font-weight: 700;
            color: #64748b;
            letter-spacing: 1.2px;
            padding: 12px 12px 6px;
            transition: opacity 0.2s;
        }

        .sidebar-menu .nav-item {
            margin-bottom: 4px;
        }

        .sidebar-menu .nav-link {
            color: #94a3b8;
            padding: 11px 16px;
            border-radius: 10px;
            font-weight: 500;
            font-size: 0.9rem;
            transition: all 0.25s ease;
            display: flex;
            align-items: center;
            gap: 14px;
            text-decoration: none;
        }

        .sidebar-menu .nav-link i {
            font-size: 1.15rem;
            min-width: 22px;
            text-align: center;
            transition: color 0.25s ease;
        }

        /* Hover & Active States */
        .sidebar-menu .nav-link:hover {
            color: #f8fafc;
            background: rgba(255, 255, 255, 0.06);
        }

        .sidebar-menu .nav-link:hover i {
            color: var(--accent-color);
        }

        .sidebar-menu .nav-link.active {
            color: #fff;
            background: linear-gradient(90deg, var(--accent-color) 0%, #2563eb 100%);
            box-shadow: 0 4px 15px var(--accent-glow);
            font-weight: 600;
        }

        .sidebar-menu .nav-link.active i {
            color: #fff;
        }

        .sidebar-menu .nav-link.logout-link {
            color: #f87171;
            margin-top: 8px;
        }

        .sidebar-menu .nav-link.logout-link:hover {
            background: rgba(239, 68, 68, 0.12);
            color: #ef4444;
        }

        /* Footer User Profile */
        .sidebar-footer {
            padding: 14px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            background: rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
        }

        .user-card {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 6px;
            border-radius: 10px;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            min-width: 36px;
            border-radius: 50%;
            background: #334155;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #3b82f6;
            font-weight: 700;
            border: 2px solid var(--accent-color);
        }

        .user-info h6 {
            margin: 0;
            font-size: 0.85rem;
            font-weight: 600;
            color: #f8fafc;
        }

        .user-info span {
            font-size: 0.7rem;
            color: #64748b;
        }

        /* --- MAIN CONTENT & HEADER --- */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .header {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            padding: 0 28px;
            height: var(--header-height);
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #e2e8f0;
            position: sticky;
            top: 0;
            z-index: 1040;
        }

        .toggle-btn {
            background: #f1f5f9;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 10px;
            color: #475569;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .toggle-btn:hover {
            background: #e2e8f0;
            color: #0f172a;
        }

        .content {
            padding: 28px;
        }

        .card {
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
            transition: all 0.25s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05) !important;
        }

        /* Overlay Mobile */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(15, 23, 42, 0.5);
            backdrop-filter: blur(4px);
            z-index: 1045;
        }

        /* --- RESPONSIVE MOBILE --- */
        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
                width: var(--sidebar-width) !important;
            }

            body.sidebar-collapsed .sidebar {
                width: var(--sidebar-width) !important;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .sidebar-overlay.show {
                display: block;
            }

            .main-content,
            body.sidebar-collapsed .main-content {
                margin-left: 0 !important;
            }

            /* Selalu tampilkan teks menu saat terbuka di HP */
            body.sidebar-collapsed .sidebar .brand-text,
            body.sidebar-collapsed .sidebar .menu-header,
            body.sidebar-collapsed .sidebar .nav-text,
            body.sidebar-collapsed .sidebar .user-info {
                display: block !important;
                opacity: 1 !important;
                visibility: visible !important;
                width: auto !important;
            }

            body.sidebar-collapsed .sidebar-brand,
            body.sidebar-collapsed .sidebar-menu .nav-link,
            body.sidebar-collapsed .sidebar-footer,
            body.sidebar-collapsed .user-card {
                justify-content: flex-start !important;
            }
        }
    </style>

    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>

    <!-- Mobile Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    
    <aside class="sidebar" id="sidebar">
        <!-- Brand Header -->
        <div class="sidebar-brand">
            <div class="brand-icon">
                <i class="fas fa-chart-pie"></i>
            </div>
            <div class="brand-text">
                <h6>Klastering Wilayah</h6>
                <small>Banjarmasin</small>
            </div>
        </div>

        <!-- Navigation Links -->
        <div class="sidebar-menu">
            <div class="menu-header">Menu Utama</div>

            <div class="nav-item">
                <a href="<?php echo e(route('admin.dashboard')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.dashboard') ? 'active' : ''); ?>" title="Dashboard">
                    <i class="fas fa-chart-line"></i>
                    <span class="nav-text">Dashboard</span>
                </a>
            </div>

            <div class="menu-header">Kelola Data</div>

            <div class="nav-item">
                <a href="<?php echo e(route('admin.users.index')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.users*') ? 'active' : ''); ?>" title="Kelola User">
                    <i class="fas fa-users"></i>
                    <span class="nav-text">Kelola User</span>
                </a>
            </div>

            <div class="nav-item">
                <a href="<?php echo e(route('tps.index')); ?>" class="nav-link <?php echo e(request()->routeIs('tps*') ? 'active' : ''); ?>" title="Data TPS">
                    <i class="fas fa-trash-can"></i>
                    <span class="nav-text">Data TPS</span>
                </a>
            </div>

            <div class="nav-item">
                <a href="<?php echo e(route('clusters.index')); ?>" class="nav-link <?php echo e(request()->routeIs('clusters*') ? 'active' : ''); ?>" title="Hasil Klastering">
                    <i class="fas fa-layer-group"></i>
                    <span class="nav-text">Hasil Klastering</span>
                </a>
            </div>

            <div class="menu-header">Sistem</div>

            <div class="nav-item">
                <a href="#" class="nav-link logout-link" onclick="event.preventDefault(); confirmLogout();" title="Keluar">
                    <i class="fas fa-right-from-bracket"></i>
                    <span class="nav-text">Keluar</span>
                </a>
            </div>

            <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="d-none">
                <?php echo csrf_field(); ?>
            </form>
        </div>

        <!-- User Profile Card -->
        <div class="sidebar-footer">
            <div class="user-card">
                <div class="user-avatar">
                    <?php echo e(strtoupper(substr(Auth::user()->name ?? 'A', 0, 1))); ?>

                </div>
                <div class="user-info">
                    <h6><?php echo e(Auth::user()->name ?? 'Administrator'); ?></h6>
                    <span><?php echo e(Auth::user()->email ?? 'admin@banjarmasin.go.id'); ?></span>
                </div>
            </div>
        </div>
    </aside>

    
    <div class="main-content">
        
        <header class="header">
            <div class="d-flex align-items-center gap-3">
                <button class="toggle-btn" id="sidebarToggle" aria-label="Toggle Navigation">
                    <i class="fas fa-bars"></i>
                </button>
                <h5 class="m-0 font-weight-bold d-none d-md-block" style="font-size: 1.1rem;">
                    <?php echo $__env->yieldContent('title', 'Dashboard'); ?>
                </h5>
            </div>

            <div class="d-flex align-items-center gap-3">
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-decoration-none text-dark gap-2 dropdown-toggle" data-bs-toggle="dropdown">
                        <div class="user-avatar" style="width: 32px; height: 32px; font-size: 0.85rem;">
                            <?php echo e(strtoupper(substr(Auth::user()->name ?? 'A', 0, 1))); ?>

                        </div>
                        <span class="fw-semibold d-none d-sm-inline" style="font-size: 0.9rem;">
                            <?php echo e(Auth::user()->name); ?>

                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2">
                        <li>
                            <a class="dropdown-item" href="<?php echo e(route('profile.edit')); ?>">
                                <i class="fas fa-user-gear me-2 text-muted"></i> Pengaturan Profil
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-danger" href="#" onclick="event.preventDefault(); confirmLogout();">
                                <i class="fas fa-right-from-bracket me-2"></i> Keluar
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        
        <main class="content">
            <?php echo $__env->yieldContent('content'); ?>
        </main>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        // Restore status sidebar dari localStorage
        if (localStorage.getItem('sidebar-collapsed') === 'true' && window.innerWidth >= 992) {
            document.body.classList.add('sidebar-collapsed');
        }

        // Toggle Sidebar Function
        sidebarToggle.addEventListener('click', function() {
            if (window.innerWidth < 992) {
                sidebar.classList.toggle('show');
                sidebarOverlay.classList.toggle('show');
            } else {
                document.body.classList.toggle('sidebar-collapsed');
                const isCollapsed = document.body.classList.contains('sidebar-collapsed');
                localStorage.setItem('sidebar-collapsed', isCollapsed);
            }
        });

        // Tutup sidebar di HP saat overlay diklik
        sidebarOverlay.addEventListener('click', function() {
            sidebar.classList.remove('show');
            sidebarOverlay.classList.remove('show');
        });

        // SweetAlert Logout Confirmation
        function confirmLogout() {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Anda akan keluar dari sesi sistem ini.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3b82f6',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Keluar',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-4'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logout-form').submit();
                }
            });
        }
    </script>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH C:\laragon\www\Latihan\resources\views/layouts/admin.blade.php ENDPATH**/ ?>