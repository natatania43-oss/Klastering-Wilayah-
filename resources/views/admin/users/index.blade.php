@extends('layouts.admin')

@section('title', 'Kelola Pengguna')

@section('content')
@php
    $totalUser    = \App\Models\User::count();
    $totalAdmin   = \App\Models\User::whereIn('role', ['admin', 'super_admin'])->count();
    $totalPimpin  = \App\Models\User::where('role', 'pimpinan')->count();
    $totalNonaktif = \App\Models\User::where('is_active', false)->count();

    $roleStyle = [
        'super_admin' => ['#dc3545', 'rgba(220,53,69,.12)'],
        'admin'       => ['#0d6efd', 'rgba(13,110,253,.12)'],
        'pimpinan'    => ['#198754', 'rgba(25,135,84,.12)'],
    ];
@endphp

<div class="container-fluid">

    {{-- Hero --}}
    <div class="usr-hero mb-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <h4 class="mb-1 fw-bold">Kelola Pengguna</h4>
                <div class="sub">Manajemen akun dan hak akses pengguna sistem</div>
            </div>
            <a href="{{ route('admin.users.create') }}" class="btn btn-light fw-semibold">
                <i class="fas fa-plus me-1"></i> Tambah Pengguna
            </a>
        </div>
    </div>

    {{-- Alert --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="fas fa-exclamation-circle me-1"></i> {{ $errors->first() }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- KPI --}}
    <div class="row g-3 mb-4">
        @foreach ([
            ['Total Pengguna', $totalUser,     'fa-users',       '#0d6efd', 'rgba(13,110,253,.12)'],
            ['Admin',          $totalAdmin,    'fa-user-shield', '#6f42c1', 'rgba(111,66,193,.12)'],
            ['Pimpinan',       $totalPimpin,   'fa-user-tie',    '#198754', 'rgba(25,135,84,.12)'],
            ['Nonaktif',       $totalNonaktif, 'fa-user-slash',  '#dc3545', 'rgba(220,53,69,.12)'],
        ] as [$lbl, $val, $ico, $clr, $bg])
            <div class="col-6 col-xl-3">
                <div class="card usr-card p-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="usr-kpi-icon" style="background:{{ $bg }};color:{{ $clr }}">
                            <i class="fas {{ $ico }}"></i>
                        </div>
                        <div>
                            <div class="usr-kpi-label">{{ $lbl }}</div>
                            <div class="usr-kpi-value">{{ number_format($val) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Filter --}}
    <div class="card usr-card mb-3">
        <div class="card-body">
            <form action="{{ route('admin.users.index') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0"
                               placeholder="Cari nama atau email..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="role" class="form-select">
                        <option value="">Semua Role</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="pimpinan" {{ request('role') == 'pimpinan' ? 'selected' : '' }}>Pimpinan</option>
                        <option value="super_admin" {{ request('role') == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1">Filter</button>
                    @if(request()->hasAny(['search', 'role', 'status']))
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary" title="Reset filter">
                            <i class="fas fa-times"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Tabel --}}
    <div class="card usr-card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle users-table mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Pengguna</th>
                            <th>Role</th>
                            <th>Jabatan</th>
                            <th>No. Telepon</th>
                            <th>Status</th>
                            <th>Dibuat</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($users as $index => $user)
                        @php
                            [$rc, $rbg] = $roleStyle[$user->role] ?? ['#6c757d', 'rgba(108,117,125,.12)'];
                            $isSelf = $user->id === auth()->id();
                        @endphp
                        <tr>
                            <td class="text-muted">{{ $users->firstItem() + $index }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar-circle" style="background:{{ $rbg }};color:{{ $rc }}">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-semibold">
                                            {{ $user->name }}
                                            @if($isSelf)
                                                <span class="badge rounded-pill bg-info ms-1">Anda</span>
                                            @endif
                                        </div>
                                        <div class="small text-muted">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="soft-badge" style="background:{{ $rbg }};color:{{ $rc }}">
                                    {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                                </span>
                            </td>
                            <td>{{ $user->jabatan ?? '-' }}</td>
                            <td>{{ $user->no_telepon ?? '-' }}</td>
                            <td>
                                @if($user->is_active)
                                    <span class="soft-badge" style="background:rgba(25,135,84,.12);color:#198754">
                                        <i class="fas fa-circle me-1" style="font-size:6px;"></i> Aktif
                                    </span>
                                @else
                                    <span class="soft-badge" style="background:rgba(220,53,69,.12);color:#dc3545">
                                        <i class="fas fa-circle me-1" style="font-size:6px;"></i> Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="text-muted">{{ $user->created_at?->format('d/m/Y') ?? '-' }}</td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-1">
                                    <a href="{{ route('admin.users.edit', $user) }}"
                                       class="btn-icon btn-icon-warning" title="Edit">
                                        <i class="fas fa-pen"></i>
                                    </a>

                                    @unless($isSelf)
                                        <button type="button" class="btn-icon btn-icon-danger" title="Hapus"
                                                data-url="{{ route('admin.users.destroy', $user) }}"
                                                data-name="{{ $user->name }}"
                                                onclick="confirmDelete(this)">
                                            <i class="fas fa-trash"></i>
                                        </button>

                                        <div class="dropdown">
                                            <button type="button" class="btn-icon btn-icon-muted"
                                                    data-bs-toggle="dropdown" aria-expanded="false" title="Aksi lain">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                                @if(in_array($user->role, ['admin', 'pimpinan']))
                                                    <li>
                                                        <form action="{{ route('admin.users.role', $user) }}" method="POST">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="role"
                                                                   value="{{ $user->role == 'admin' ? 'pimpinan' : 'admin' }}">
                                                            <button type="submit" class="dropdown-item">
                                                                <i class="fas fa-exchange-alt me-2 text-primary"></i>
                                                                Ubah ke {{ $user->role == 'admin' ? 'Pimpinan' : 'Admin' }}
                                                            </button>
                                                        </form>
                                                    </li>
                                                @endif
                                                <li>
                                                    <form action="{{ route('admin.users.toggle', $user) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="dropdown-item">
                                                            <i class="fas fa-{{ $user->is_active ? 'ban text-danger' : 'check text-success' }} me-2"></i>
                                                            {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                                        </button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <button type="button" class="dropdown-item"
                                                            data-url="{{ route('admin.users.reset-password', $user) }}"
                                                            data-name="{{ $user->name }}"
                                                            onclick="resetPassword(this)">
                                                        <i class="fas fa-key me-2 text-warning"></i> Reset Password
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    @endunless
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="fas fa-users fa-3x text-muted mb-3 d-block"></i>
                                <h6 class="text-muted">Belum ada pengguna terdaftar</h6>
                                <p class="text-muted small mb-0">Klik tombol "Tambah Pengguna" untuk menambahkan</p>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mt-3">
                <span class="text-muted small">
                    Menampilkan {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }}
                    dari {{ $users->total() }} data
                </span>
                <div class="users-pagination">
                    {{ $users->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Hapus --}}
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-body text-center p-4">
                <div class="modal-icon" style="background:rgba(220,53,69,.12);color:#dc3545">
                    <i class="fas fa-trash"></i>
                </div>
                <h5 class="mb-2">Hapus Pengguna?</h5>
                <p class="text-muted mb-1">Anda akan menghapus <strong id="deleteUserName"></strong>.</p>
                <p class="text-danger small mb-4">Tindakan ini tidak dapat dibatalkan.</p>
                <form id="deleteForm" method="POST" class="d-flex justify-content-center gap-2">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger px-4">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Modal Reset Password --}}
<div class="modal fade" id="resetPasswordModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form id="resetPasswordForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body p-4">
                    <div class="text-center">
                        <div class="modal-icon" style="background:rgba(255,193,7,.18);color:#c99700">
                            <i class="fas fa-key"></i>
                        </div>
                        <h5 class="mb-1">Reset Password</h5>
                        <p class="text-muted mb-4">Untuk <strong id="resetUserName"></strong></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password Baru</label>
                        <input type="password" name="password" class="form-control"
                               required minlength="8" autocomplete="new-password">
                        <div class="form-text">Minimal 8 karakter.</div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control"
                               required minlength="8" autocomplete="new-password">
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning px-4">Reset Password</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .usr-hero {
        background: linear-gradient(135deg, #0d6efd 0%, #0a4fb8 100%);
        color: #fff;
        border-radius: 1rem;
        padding: 1.5rem 1.75rem;
    }
    .usr-hero .sub { opacity: .85; }
    .usr-card {
        border: 0;
        border-radius: 1rem;
        box-shadow: 0 2px 12px rgba(20, 30, 60, .06);
    }
    .usr-kpi-icon {
        width: 46px; height: 46px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
    }
    .usr-kpi-label { font-size: .8rem; color: #6c757d; }
    .usr-kpi-value { font-size: 1.6rem; font-weight: 700; line-height: 1.2; }

    .users-table th, .users-table td { white-space: nowrap; }
    .users-table thead th {
        font-size: .75rem;
        text-transform: uppercase;
        letter-spacing: .04em;
        color: #6c757d;
        border-bottom-width: 1px;
    }
    .avatar-circle {
        width: 38px; height: 38px;
        flex-shrink: 0;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700;
    }
    .soft-badge {
        display: inline-block;
        padding: .3rem .65rem;
        border-radius: 999px;
        font-size: .75rem;
        font-weight: 600;
    }

    .btn-icon {
        width: 32px; height: 32px;
        border: 0;
        border-radius: 8px;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: .8rem;
        text-decoration: none;
        cursor: pointer;
        transition: filter .15s ease;
    }
    .btn-icon:hover { filter: brightness(.92); }
    .btn-icon-warning { background: rgba(255,193,7,.18); color: #c99700; }
    .btn-icon-danger  { background: rgba(220,53,69,.12); color: #dc3545; }
    .btn-icon-muted   { background: #eef0f4; color: #495057; }

    .modal-icon {
        width: 60px; height: 60px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem;
        margin: 0 auto 1rem;
    }
    .modal-content { border-radius: 1rem; }

    .users-pagination .pagination { margin-bottom: 0; gap: 2px; }
    .users-pagination .page-link {
        padding: .25rem .55rem;
        font-size: .8rem;
        line-height: 1.25;
        border-radius: .4rem;
    }
    .users-pagination .page-link svg { width: .75rem; height: .75rem; }
    .users-pagination p.small,
    .users-pagination nav > div:first-child > p { display: none; }
</style>
@endpush

@push('scripts')
<script>
    function confirmDelete(button) {
        document.getElementById('deleteUserName').textContent = button.dataset.name;
        document.getElementById('deleteForm').action = button.dataset.url;
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    }

    function resetPassword(button) {
        const form = document.getElementById('resetPasswordForm');
        form.reset();
        document.getElementById('resetUserName').textContent = button.dataset.name;
        form.action = button.dataset.url;
        new bootstrap.Modal(document.getElementById('resetPasswordModal')).show();
    }

    // Agar menu dropdown tidak terpotong oleh table-responsive
    document.querySelectorAll('.users-table [data-bs-toggle="dropdown"]').forEach(function (el) {
        new bootstrap.Dropdown(el, { popperConfig: { strategy: 'fixed' } });
    });
</script>
@endpush