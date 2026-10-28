<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Masuk') | Klastering Partisipasi Pemilih Banjarmasin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #0d6efd 0%, #0a3d91 100%);
            font-family:'Segoe UI',sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .auth-card {
            border:none;
            border-radius:16px;
            box-shadow:0 10px 30px rgba(0,0,0,.25);
            overflow:hidden;
        }
        .auth-brand {
            font-weight:700;
            font-size:1.1rem;
        }
        .auth-side {
            background: #0d6efd;
            color:#fff;
        }
        .btn-primary {
            border-radius:8px;
        }
        .form-control {
            border-radius:8px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card auth-card">
                <div class="row g-0">
                    <div class="col-md-5 auth-side p-4 d-none d-md-flex flex-column justify-content-center">
                        <div class="auth-brand mb-3"><i class="fa-solid fa-chart-pie"></i> KMeans Banjarmasin</div>
                        <h4 class="fw-bold">Klastering Partisipasi Pemilih</h4>
                        <p class="mb-0 opacity-75">Dashboard analitik partisipasi pemilih Kota Banjarmasin berbasis K-Means Clustering.</p>
                    </div>
                    <div class="col-md-7 p-4 p-md-5">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>