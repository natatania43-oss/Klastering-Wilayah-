@extends('layouts.admin')

@section('title', 'Edit Data TPS')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1">Edit Data TPS</h4>
                    <p class="text-muted mb-0">Perbarui data {{ $tps->id_tps }}</p>
                </div>
                <a href="{{ route('tps.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-1"></i> Terdapat kesalahan pada input:
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.tps.update', $tps->id_tps) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <h6 class="text-primary mb-3"><i class="fas fa-map-marker-alt me-1"></i> Identitas TPS</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-3">
                                <label class="form-label">ID TPS <span class="text-danger">*</span></label>
                                <input type="text" name="id_tps" class="form-control @error('id_tps') is-invalid @enderror"
                                       value="{{ old('id_tps', $tps->id_tps) }}" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">No. TPS</label>
                                <input type="number" name="no_tps" class="form-control" min="0" value="{{ old('no_tps', $tps->no_tps) }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Kecamatan <span class="text-danger">*</span></label>
                                <input type="text" name="kecamatan" class="form-control @error('kecamatan') is-invalid @enderror"
                                       value="{{ old('kecamatan', $tps->kecamatan) }}" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Kelurahan <span class="text-danger">*</span></label>
                                <input type="text" name="kelurahan" class="form-control @error('kelurahan') is-invalid @enderror"
                                       value="{{ old('kelurahan', $tps->kelurahan) }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Dapil</label>
                                <input type="text" name="dapil" class="form-control" value="{{ old('dapil', $tps->dapil) }}">
                            </div>
                        </div>

                        <h6 class="text-primary mb-3"><i class="fas fa-users me-1"></i> Data DPT (Daftar Pemilih Tetap)</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-3">
                                <label class="form-label">DPT Laki-laki <span class="text-danger">*</span></label>
                                <input type="number" name="dpt_lk" class="form-control @error('dpt_lk') is-invalid @enderror"
                                       min="0" value="{{ old('dpt_lk', $tps->dpt_lk) }}" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">DPT Perempuan <span class="text-danger">*</span></label>
                                <input type="number" name="dpt_pr" class="form-control @error('dpt_pr') is-invalid @enderror"
                                       min="0" value="{{ old('dpt_pr', $tps->dpt_pr) }}" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">DPTb (Pindahan)</label>
                                <input type="number" name="dptb" class="form-control @error('dptb') is-invalid @enderror"
                                       min="0" value="{{ old('dptb', $tps->dptb) }}" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">DPK (Khusus)</label>
                                <input type="number" name="dpk" class="form-control @error('dpk') is-invalid @enderror"
                                       min="0" value="{{ old('dpk', $tps->dpk) }}" required>
                            </div>
                            <div class="col-12">
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Saat ini: total DPT {{ $tps->dpt_jml }}, total pengguna hak pilih {{ $tps->total_pengguna }},
                                    partisipasi {{ number_format($tps->partisipasi * 100, 2) }}%.
                                    Nilai ini akan dihitung ulang otomatis setelah disimpan.
                                </div>
                            </div>
                        </div>

                        <h6 class="text-primary mb-3"><i class="fas fa-vote-yea me-1"></i> Pengguna Hak Pilih</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-3">
                                <label class="form-label">Pengguna DPT Laki-laki <span class="text-danger">*</span></label>
                                <input type="number" name="pdpt_lk" class="form-control @error('pdpt_lk') is-invalid @enderror"
                                       min="0" value="{{ old('pdpt_lk', $tps->pdpt_lk) }}" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Pengguna DPT Perempuan <span class="text-danger">*</span></label>
                                <input type="number" name="pdpt_pr" class="form-control @error('pdpt_pr') is-invalid @enderror"
                                       min="0" value="{{ old('pdpt_pr', $tps->pdpt_pr) }}" required>
                            </div>
                        </div>

                        <h6 class="text-primary mb-3"><i class="fas fa-file-alt me-1"></i> Surat Suara & Disabilitas</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label class="form-label">Surat Suara Diterima <span class="text-danger">*</span></label>
                                <input type="number" name="ss_diterima" class="form-control @error('ss_diterima') is-invalid @enderror"
                                       min="0" value="{{ old('ss_diterima', $tps->ss_diterima) }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Surat Suara Digunakan <span class="text-danger">*</span></label>
                                <input type="number" name="ss_digunakan" class="form-control @error('ss_digunakan') is-invalid @enderror"
                                       min="0" value="{{ old('ss_digunakan', $tps->ss_digunakan) }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Jumlah Disabilitas <span class="text-danger">*</span></label>
                                <input type="number" name="disabilitas" class="form-control @error('disabilitas') is-invalid @enderror"
                                       min="0" value="{{ old('disabilitas', $tps->disabilitas) }}" required>
                            </div>
                        </div>

                        <div class="alert alert-secondary small">
                            <i class="fas fa-info-circle me-1"></i>
                            Hasil clustering (cluster ke-{{ $tps->cluster ?? '-' }}) tidak diubah di sini.
                            Jalankan ulang <code>php artisan kmeans:run</code> setelah mengubah data untuk memperbarui hasil klaster.
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Simpan Perubahan
                            </button>
                            <a href="{{ route('tps.index') }}" class="btn btn-outline-secondary">Batal</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection