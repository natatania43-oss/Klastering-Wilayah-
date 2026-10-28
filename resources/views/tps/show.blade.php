@extends('layouts.app')
@section('title', 'Detail TPS')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1">Detail TPS {{ $tps->id_tps }}</h3>
        <p class="text-muted mb-0">{{ $tps->kecamatan }}, {{ $tps->kelurahan }}</p>
    </div>
    <div class="d-flex gap-2">
        @if(auth()->user()->isAdmin())
            <a href="{{ route('admin.tps.edit', $tps->id_tps) }}" class="btn btn-warning">
                <i class="fas fa-edit me-1"></i> Edit
            </a>
        @endif
        <a href="{{ route('tps.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

<div class="row">
    {{-- Identitas TPS --}}
    <div class="col-lg-4 mb-3">
        <div class="card p-3 h-100">
            <h6 class="text-muted mb-3">Identitas TPS</h6>
            <table class="table table-sm table-borderless mb-0">
                <tr>
                    <td class="text-muted" style="width:45%">ID TPS</td>
                    <td><strong>{{ $tps->id_tps }}</strong></td>
                </tr>
                <tr>
                    <td class="text-muted">No. TPS</td>
                    <td>{{ $tps->no_tps ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="text-muted">Kecamatan</td>
                    <td>{{ $tps->kecamatan }}</td>
                </tr>
                <tr>
                    <td class="text-muted">Kelurahan</td>
                    <td>{{ $tps->kelurahan }}</td>
                </tr>
                <tr>
                    <td class="text-muted">Dapil</td>
                    <td>{{ $tps->dapil ?? '-' }}</td>
                </tr>
            </table>
        </div>
    </div>

    {{-- Ringkasan Partisipasi --}}
    <div class="col-lg-4 mb-3">
        <div class="card p-3 h-100">
            <h6 class="text-muted mb-3">Ringkasan Partisipasi</h6>
            <div class="mb-3">
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-muted small">Partisipasi Pemilih</span>
                    <span class="fw-bold">{{ number_format($tps->partisipasi * 100, 2) }}%</span>
                </div>
                <div class="progress" style="height:8px;">
                    <div class="progress-bar"
                         style="width: {{ $tps->partisipasi * 100 }}%;
                                background-color: {{ $tps->partisipasi >= 0.7 ? '#198754' : ($tps->partisipasi >= 0.5 ? '#ffc107' : '#dc3545') }};">
                    </div>
                </div>
            </div>
            <table class="table table-sm table-borderless mb-0">
                <tr>
                    <td class="text-muted" style="width:60%">Total DPT</td>
                    <td><strong>{{ number_format($tps->dpt_jml) }}</strong></td>
                </tr>
                <tr>
                    <td class="text-muted">Total Pengguna Hak Pilih</td>
                    <td><strong>{{ number_format($tps->total_pengguna) }}</strong></td>
                </tr>
                <tr>
                    <td class="text-muted">Klaster</td>
                    <td>
                        <span class="badge bg-secondary">
                            Cluster {{ $tps->cluster ?? '-' }}
                        </span>
                        @if($clusterResult && $clusterResult->label)
                            <span class="text-muted small ms-1">({{ $clusterResult->label }})</span>
                        @endif
                    </td>
                </tr>
            </table>
        </div>
    </div>

    {{-- Data Disabilitas & Surat Suara --}}
    <div class="col-lg-4 mb-3">
        <div class="card p-3 h-100">
            <h6 class="text-muted mb-3">Surat Suara & Disabilitas</h6>
            <table class="table table-sm table-borderless mb-0">
                <tr>
                    <td class="text-muted" style="width:60%">Surat Suara Diterima</td>
                    <td>{{ number_format($tps->ss_diterima) }}</td>
                </tr>
                <tr>
                    <td class="text-muted">Surat Suara Digunakan</td>
                    <td>{{ number_format($tps->ss_digunakan) }}</td>
                </tr>
                <tr>
                    <td class="text-muted">Pemilih Disabilitas</td>
                    <td>{{ number_format($tps->disabilitas) }}</td>
                </tr>
            </table>
        </div>
    </div>
</div>

<div class="row">
    {{-- Rincian DPT --}}
    <div class="col-lg-6 mb-3">
        <div class="card p-3 h-100">
            <h6 class="text-muted mb-3">Rincian Daftar Pemilih Tetap (DPT)</h6>
            <table class="table table-sm">
                <thead class="table-light">
                    <tr>
                        <th>Jenis Kelamin</th>
                        <th class="text-end">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Laki-laki</td>
                        <td class="text-end">{{ number_format($tps->dpt_lk) }}</td>
                    </tr>
                    <tr>
                        <td>Perempuan</td>
                        <td class="text-end">{{ number_format($tps->dpt_pr) }}</td>
                    </tr>
                    <tr class="table-light">
                        <td><strong>Total DPT</strong></td>
                        <td class="text-end"><strong>{{ number_format($tps->dpt_jml) }}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Rincian Pengguna Hak Pilih --}}
    <div class="col-lg-6 mb-3">
        <div class="card p-3 h-100">
            <h6 class="text-muted mb-3">Rincian Pengguna Hak Pilih</h6>
            <table class="table table-sm">
                <thead class="table-light">
                    <tr>
                        <th>Kategori</th>
                        <th class="text-end">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Pengguna DPT Laki-laki</td>
                        <td class="text-end">{{ number_format($tps->pdpt_lk) }}</td>
                    </tr>
                    <tr>
                        <td>Pengguna DPT Perempuan</td>
                        <td class="text-end">{{ number_format($tps->pdpt_pr) }}</td>
                    </tr>
                    <tr>
                        <td>DPTb (Pindahan)</td>
                        <td class="text-end">{{ number_format($tps->dptb) }}</td>
                    </tr>
                    <tr>
                        <td>DPK (Khusus)</td>
                        <td class="text-end">{{ number_format($tps->dpk) }}</td>
                    </tr>
                    <tr class="table-light">
                        <td><strong>Total Pengguna</strong></td>
                        <td class="text-end"><strong>{{ number_format($tps->total_pengguna) }}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection