@extends('layouts.admin')

@section('title', 'Pengaturan Profil')

@section('content')
<style>
    .panel { border: 0; border-radius: 1rem; box-shadow: 0 2px 12px rgba(20,30,60,.06); }
    .pf-hero {
        background: linear-gradient(135deg, #0d6efd 0%, #0a4fb8 100%);
        color: #fff; border-radius: 1rem; padding: 1.5rem 1.75rem;
    }
    .pf-hero .sub { opacity: .85; }
    .pf-avatar {
        width: 96px; height: 96px; border-radius: 50%;
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        color: #fff; font-size: 2.4rem; font-weight: 700;
        display: flex; align-items: center; justify-content: center;
        border: 4px solid #fff; box-shadow: 0 8px 20px rgba(59,130,246,.3);
        margin: -48px auto 0;
    }
    .pf-banner { height: 84px; border-radius: 1rem 1rem 0 0; background: linear-gradient(135deg, #0d6efd, #6ea8fe); }
    .info-row { display: flex; justify-content: space-between; align-items: center; padding: .65rem 0; border-bottom: 1px solid #f0f2f5; font-size: .9rem; }
    .info-row:last-child { border-bottom: 0; }
    .sec-icon { width: 40px; height: 40px; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .form-label { font-size: .82rem; font-weight: 600; color: #495057; }
    .form-control, .input-group-text { border-radius: .6rem; }
    .form-control:focus { border-color: #3b82f6; box-shadow: 0 0 0 .2rem rgba(59,130,246,.15); }
    .input-group .form-control { border-radius: .6rem 0 0 .6rem; }
    .input-group .btn { border-radius: 0 .6rem .6rem 0; }
    .strength { height: 5px; border-radius: 5px; background: #e9ecef; overflow: hidden; }
    .strength > div { height: 100%; width: 0; transition: width .25s, background .25s; }
    .danger-zone { border: 1px solid rgba(220,53,69,.35); background: rgba(220,53,69,.04); border-radius: 1rem; }
</style>

@php
    $user     = Auth::user();
    $initial  = strtoupper(mb_substr($user->name ?? 'A', 0, 1));
    $peran    = $user->role ?? 'Administrator';
    $verified = ! method_exists($user, 'hasVerifiedEmail') || $user->hasVerifiedEmail();
@endphp

{{-- Hero --}}
<div class="pf-hero mb-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
        <div>
            <h4 class="mb-1 fw-bold">Pengaturan Profil</h4>
            <div class="sub">Pengelompokan Wilayah Berdasarkan Tingkat Partisipasi Pemilih di Kota Banjarmasin · K-Means</div>
        </div>
        <span class="badge rounded-pill bg-light text-primary px-3 py-2">
            <i class="fas fa-shield-alt me-1"></i> Akun {{ $verified ? 'Terverifikasi' : 'Belum Terverifikasi' }}
        </span>
    </div>
</div>

{{-- Notifikasi --}}
@if (session('status') === 'profile-updated')
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-1"></i> Informasi profil berhasil diperbarui.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if (session('status') === 'password-updated')
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-1"></i> Kata sandi berhasil diperbarui.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if (session('status') === 'verification-link-sent')
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        Tautan verifikasi baru telah dikirim ke email Anda.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row g-4">
    {{-- Kartu ringkasan --}}
    <div class="col-12 col-xl-4">
        <div class="card panel overflow-hidden">
            <div class="pf-banner"></div>
            <div class="card-body text-center pt-0">
                <div class="pf-avatar">{{ $initial }}</div>
                <h5 class="fw-bold mt-3 mb-0">{{ $user->name }}</h5>
                <div class="text-muted small mb-3">{{ $user->email }}</div>

                <div class="text-start mt-3">
                    <div class="info-row">
                        <span class="text-muted"><i class="fas fa-user-tag me-2"></i>Peran</span>
                        <span class="fw-semibold">{{ $peran }}</span>
                    </div>
                    <div class="info-row">
                        <span class="text-muted"><i class="fas fa-circle-check me-2"></i>Status</span>
                        <span class="badge bg-success-subtle text-success">Aktif</span>
                    </div>
                    <div class="info-row">
                        <span class="text-muted"><i class="fas fa-envelope me-2"></i>Email</span>
                        @if($verified)
                            <span class="badge bg-success-subtle text-success">Terverifikasi</span>
                        @else
                            <span class="badge bg-warning-subtle text-warning">Belum verifikasi</span>
                        @endif
                    </div>
                    @if($user->created_at)
                    <div class="info-row">
                        <span class="text-muted"><i class="fas fa-calendar me-2"></i>Bergabung</span>
                        <span class="fw-semibold">{{ $user->created_at->translatedFormat('d M Y') }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-xl-8">

        {{-- 1. Informasi profil --}}
        <div class="card panel mb-4">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="sec-icon bg-primary-subtle text-primary"><i class="fas fa-user-pen"></i></div>
                    <div>
                        <h6 class="fw-bold m-0">Informasi Profil</h6>
                        <small class="text-muted">Perbarui nama dan alamat email akun Anda.</small>
                    </div>
                </div>

                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PATCH')

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Nama</label>
                            <input id="name" name="name" type="text"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $user->name) }}" required autocomplete="name">
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input id="email" name="email" type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $user->email) }}" required autocomplete="username">
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    @if(! $verified && Route::has('verification.send'))
                        <div class="alert alert-warning small mt-3 mb-0">
                            Email Anda belum diverifikasi.
                            <button form="kirim-verifikasi" class="btn btn-link btn-sm p-0 align-baseline">Kirim ulang email verifikasi</button>
                        </div>
                    @endif

                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-1"></i> Simpan Perubahan</button>
                    </div>
                </form>

                @if(! $verified && Route::has('verification.send'))
                    <form id="kirim-verifikasi" method="POST" action="{{ route('verification.send') }}">@csrf</form>
                @endif
            </div>
        </div>

        {{-- 2. Kata sandi --}}
        <div class="card panel mb-4">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="sec-icon bg-warning-subtle text-warning"><i class="fas fa-key"></i></div>
                    <div>
                        <h6 class="fw-bold m-0">Perbarui Kata Sandi</h6>
                        <small class="text-muted">Gunakan kata sandi yang panjang, kuat, dan unik.</small>
                    </div>
                </div>

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="current_password" class="form-label">Kata Sandi Saat Ini</label>
                        <div class="input-group has-validation">
                            <input id="current_password" name="current_password" type="password"
                                   class="form-control @if($errors->updatePassword->has('current_password')) is-invalid @endif"
                                   autocomplete="current-password">
                            <button type="button" class="btn btn-outline-secondary toggle-pw" data-target="current_password"><i class="fas fa-eye"></i></button>
                            @if($errors->updatePassword->has('current_password'))
                                <div class="invalid-feedback">{{ $errors->updatePassword->first('current_password') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="password" class="form-label">Kata Sandi Baru</label>
                            <div class="input-group has-validation">
                                <input id="password" name="password" type="password"
                                       class="form-control @if($errors->updatePassword->has('password')) is-invalid @endif"
                                       autocomplete="new-password">
                                <button type="button" class="btn btn-outline-secondary toggle-pw" data-target="password"><i class="fas fa-eye"></i></button>
                                @if($errors->updatePassword->has('password'))
                                    <div class="invalid-feedback">{{ $errors->updatePassword->first('password') }}</div>
                                @endif
                            </div>
                            <div class="strength mt-2"><div id="strengthBar"></div></div>
                            <div class="small text-muted mt-1" id="strengthText">Minimal 8 karakter.</div>
                        </div>
                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi</label>
                            <div class="input-group has-validation">
                                <input id="password_confirmation" name="password_confirmation" type="password"
                                       class="form-control @if($errors->updatePassword->has('password_confirmation')) is-invalid @endif"
                                       autocomplete="new-password">
                                <button type="button" class="btn btn-outline-secondary toggle-pw" data-target="password_confirmation"><i class="fas fa-eye"></i></button>
                                @if($errors->updatePassword->has('password_confirmation'))
                                    <div class="invalid-feedback">{{ $errors->updatePassword->first('password_confirmation') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-warning px-4"><i class="fas fa-lock me-1"></i> Perbarui Kata Sandi</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- 3. Hapus akun --}}
        <div class="danger-zone p-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="sec-icon bg-danger-subtle text-danger"><i class="fas fa-trash-alt"></i></div>
                    <div>
                        <h6 class="fw-bold m-0 text-danger">Hapus Akun</h6>
                        <small class="text-muted">Tindakan ini permanen. Semua data akun akan dihapus dan tidak bisa dikembalikan.</small>
                    </div>
                </div>
                <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#hapusAkunModal">
                    Hapus Akun
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Modal konfirmasi hapus akun --}}
<div class="modal fade" id="hapusAkunModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="{{ route('profile.destroy') }}" class="modal-content border-0" style="border-radius:1rem">
            @csrf
            @method('DELETE')
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-danger"><i class="fas fa-exclamation-triangle me-2"></i>Yakin ingin menghapus akun?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small">Setelah dihapus, seluruh data akun akan hilang permanen. Masukkan kata sandi Anda untuk mengonfirmasi.</p>
                <label for="password_hapus" class="form-label">Kata Sandi</label>
                <div class="input-group has-validation">
                    <input id="password_hapus" name="password" type="password"
                           class="form-control @if($errors->userDeletion->has('password')) is-invalid @endif"
                           placeholder="Kata sandi Anda">
                    <button type="button" class="btn btn-outline-secondary toggle-pw" data-target="password_hapus"><i class="fas fa-eye"></i></button>
                    @if($errors->userDeletion->has('password'))
                        <div class="invalid-feedback">{{ $errors->userDeletion->first('password') }}</div>
                    @endif
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-danger">Ya, Hapus Akun</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
(function () {
    // Tampilkan / sembunyikan kata sandi
    document.querySelectorAll('.toggle-pw').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var input = document.getElementById(btn.dataset.target);
            var show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            btn.innerHTML = show ? '<i class="fas fa-eye-slash"></i>' : '<i class="fas fa-eye"></i>';
        });
    });

    // Indikator kekuatan kata sandi
    var pw = document.getElementById('password');
    var bar = document.getElementById('strengthBar');
    var txt = document.getElementById('strengthText');
    if (pw) {
        pw.addEventListener('input', function () {
            var v = pw.value, s = 0;
            if (v.length >= 8) s++;
            if (/[A-Z]/.test(v) && /[a-z]/.test(v)) s++;
            if (/\d/.test(v)) s++;
            if (/[^A-Za-z0-9]/.test(v)) s++;
            var lv = [
                ['0%',   '#e9ecef', 'Minimal 8 karakter.'],
                ['25%',  '#dc3545', 'Lemah'],
                ['50%',  '#fd7e14', 'Cukup'],
                ['75%',  '#ffc107', 'Baik'],
                ['100%', '#198754', 'Kuat']
            ][v.length ? Math.max(s, 1) : 0];
            bar.style.width = lv[0]; bar.style.background = lv[1]; txt.textContent = lv[2];
        });
    }

    // Buka ulang modal jika kata sandi salah
    @if($errors->userDeletion->isNotEmpty())
        window.addEventListener('load', function () {
            if (window.bootstrap) new bootstrap.Modal(document.getElementById('hapusAkunModal')).show();
        });
    @endif
})();
</script>
@endpush
@endsection