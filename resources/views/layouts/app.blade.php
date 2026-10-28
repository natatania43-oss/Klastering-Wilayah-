<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') | Klastering Partisipasi Pemilih Banjarmasin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        body { background:#f4f6f9; font-family:'Segoe UI',sans-serif; }
        .navbar-brand { font-weight:700; }
        .card { border:none; border-radius:14px; box-shadow:0 2px 10px rgba(0,0,0,.06); }
        .stat-card { border-left:5px solid #0d6efd; }
        .badge-cluster { font-size:.8rem; }
        .table thead th { white-space:nowrap; }
        footer { color:#888; font-size:.85rem; }
        .badge-role-admin { background:#dc3545; }
        .badge-role-pimpinan { background:#6f42c1; }
    </style>
    @stack('styles')
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="{{ route('dashboard') }}">
            <i class="fa-solid fa-chart-pie"></i> KMeans Banjarmasin
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="nav">
            <ul class="navbar-nav me-auto">
                @auth
                    @if(auth()->user()->isAdmin())
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">Dashboard Admin</a></li>
                    @else
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('pimpinan.dashboard') ? 'active' : '' }}" href="{{ route('pimpinan.dashboard') }}">Dashboard Pimpinan</a></li>
                    @endif
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('clusters.*') ? 'active' : '' }}" href="{{ route('clusters.index') }}">Klaster</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('tps.*') ? 'active' : '' }}" href="{{ route('tps.index') }}">Data TPS</a></li>
                @endauth
            </ul>

            @auth
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fa-solid fa-user-circle"></i> {{ auth()->user()->name }}
                        <span class="badge badge-role-{{ auth()->user()->role }} ms-1">{{ auth()->user()->roleLabel() }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="fa-solid fa-id-badge me-2"></i>Profil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger"><i class="fa-solid fa-right-from-bracket me-2"></i>Keluar</button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
            @endauth
        </div>
    </div>
</nav>

<main class="container py-4">
    @if(session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @yield('content')
</main>

<footer class="text-center py-4">
    Pengelompokan Wilayah Berdasarkan Tingkat Partisipasi Pemilih di Kota Banjarmasin — K-Means Clustering
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
@stack('scripts')
</body>
</html>