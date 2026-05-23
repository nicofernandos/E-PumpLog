@extends('user.layouts.app')

@section('title', $title)

@push('styles')
<style>
    .card {
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        transition: transform 0.2s;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    }
    .card-icon {
        width: 60px;
        height: 60px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        color: white;
    }
    .bg-primary-gradient   { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    .bg-success-gradient   { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); }
    .bg-warning-gradient   { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
    .bg-info-gradient      { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
    .bg-danger-gradient    { background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); }
    .bg-orange-gradient    { background: linear-gradient(135deg, #f6d365 0%, #fda085 100%); }

    .stat-card { border: none; border-left: 4px solid; }
    .stat-card.primary { border-left-color: #667eea; }
    .stat-card.success { border-left-color: #38ef7d; }
    .stat-card.warning { border-left-color: #f5576c; }
    .stat-card.info    { border-left-color: #00f2fe; }
    .stat-card.danger  { border-left-color: #fa709a; }
    .stat-card.orange  { border-left-color: #fda085; }

    .quick-card { cursor: pointer; }
    .quick-card .card-icon { margin: 0 auto; }
</style>
@endpush

@section('content')
<div class="page-header">
    <h3 class="page-title mb-2">Dashboard E-PumpLog</h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
        </ol>
    </nav>
</div>

{{-- Welcome Card --}}
<div class="row mb-4">
    <div class="col-12">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="text-white mb-2">
                            Selamat Datang, {{ Auth::user()->name ?? 'User' }}!
                        </h4>
                        <p class="mb-0 opacity-75">
                            Sistem Monitoring Injeksi Pompa &mdash;
                            {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                        </p>
                    </div>
                    <div>
                        <i class="mdi mdi-account-circle" style="font-size: 64px; opacity: 0.4;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ===== STATISTIK POMPA & LOKASI ===== --}}
<div class="row">
    <div class="col-md-6 col-xl-3 mb-4">
        <div class="card stat-card primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Total Pompa</h6>
                        <h2 class="mb-0 fw-bold">{{ $totalPompa ?? 0 }}</h2>
                        <small class="text-muted">Unit Pompa</small>
                    </div>
                    <div class="card-icon bg-primary-gradient">
                        <i class="mdi mdi-engine"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3 mb-4">
        <div class="card stat-card success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Pompa Aktif</h6>
                        <h2 class="mb-0 fw-bold text-success">{{ $pompaAktif ?? 0 }}</h2>
                        <small class="text-muted">Sedang Beroperasi</small>
                    </div>
                    <div class="card-icon bg-success-gradient">
                        <i class="mdi mdi-check-circle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3 mb-4">
        <div class="card stat-card warning">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Pompa Non-Aktif</h6>
                        <h2 class="mb-0 fw-bold text-danger">{{ $pompaNonAktif ?? 0 }}</h2>
                        <small class="text-muted">Tidak Beroperasi</small>
                    </div>
                    <div class="card-icon bg-warning-gradient">
                        <i class="mdi mdi-close-circle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3 mb-4">
        <div class="card stat-card info">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Total Lokasi SP</h6>
                        <h2 class="mb-0 fw-bold">{{ $totalLokasi ?? 0 }}</h2>
                        <small class="text-muted">Stasiun Pompa</small>
                    </div>
                    <div class="card-icon bg-info-gradient">
                        <i class="mdi mdi-map-marker"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ===== STATISTIK LAPORAN USER ===== --}}
<div class="row">
    <div class="col-md-6 col-xl-3 mb-4">
        <div class="card stat-card danger">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Laporan Hari Ini</h6>
                        <h2 class="mb-0 fw-bold">{{ $logHariIni ?? 0 }}</h2>
                        <small class="text-muted">{{ \Carbon\Carbon::now()->format('d/m/Y') }}</small>
                    </div>
                    <div class="card-icon bg-danger-gradient">
                        <i class="mdi mdi-clipboard-text"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3 mb-4">
        <div class="card stat-card primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Total Laporan</h6>
                        <h2 class="mb-0 fw-bold">{{ $totalLog ?? 0 }}</h2>
                        <small class="text-muted">Semua Laporan Saya</small>
                    </div>
                    <div class="card-icon bg-primary-gradient">
                        <i class="mdi mdi-document"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3 mb-4">
        <div class="card stat-card orange">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Menunggu Approval</h6>
                        <h2 class="mb-0 fw-bold text-warning">{{ $totalSubmitted ?? 0 }}</h2>
                        <small class="text-muted">Laporan Disubmit</small>
                    </div>
                    <div class="card-icon bg-orange-gradient">
                        <i class="mdi mdi-clock-outline"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3 mb-4">
        <div class="card stat-card success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Disetujui</h6>
                        <h2 class="mb-0 fw-bold text-success">{{ $totalApproved ?? 0 }}</h2>
                        <small class="text-muted">Laporan Approved</small>
                    </div>
                    <div class="card-icon bg-success-gradient">
                        <i class="mdi mdi-check-decagram"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ===== MENU CEPAT ===== --}}
<div class="row mb-2">
    <div class="col-12">
        <h5 class="mb-3 fw-semibold">Menu Cepat</h5>
    </div>

    <div class="col-md-4 mb-4">
        <a href="{{ route('user.report.index') }}" class="text-decoration-none">
            <div class="card quick-card">
                <div class="card-body text-center py-4">
                    <div class="card-icon bg-primary-gradient mb-3">
                        <i class="mdi mdi-plus-circle"></i>
                    </div>
                    <h5 class="card-title mb-1">Input Laporan</h5>
                    <p class="text-muted small mb-0">Buat laporan harian baru</p>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-4 mb-4">
        <a href="{{ route('user.dailyreport.index') }}" class="text-decoration-none">
            <div class="card quick-card">
                <div class="card-body text-center py-4">
                    <div class="card-icon bg-warning-gradient mb-3">
                        <i class="mdi mdi-book"></i>
                    </div>
                    <h5 class="card-title mb-1">Draft Laporan</h5>
                    <p class="text-muted small mb-0">Lanjutkan draft yang tersimpan</p>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-4 mb-4">
        <a href="{{ route('user.report.list') }}" class="text-decoration-none">
            <div class="card quick-card">
                <div class="card-body text-center py-4">
                    <div class="card-icon bg-success-gradient mb-3">
                        <i class="mdi mdi-format-list-bulleted"></i>
                    </div>
                    <h5 class="card-title mb-1">Semua Laporan</h5>
                    <p class="text-muted small mb-0">Lihat riwayat laporan saya</p>
                </div>
            </div>
        </a>
    </div>
</div>

{{-- ===== LAPORAN TERBARU ===== --}}
@if(isset($recentLogs) && count($recentLogs) > 0)
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title mb-0">Laporan Terbaru</h5>
                    <a href="{{ route('user.report.list') }}" 
                       class="btn btn-sm btn-outline-primary">
                        Lihat Semua
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Kode Pompa</th>
                                <th>Lokasi SP</th>
                                <th>Injeksi Ke</th>
                                <th>Status</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentLogs as $log)
                            <tr>
                                <td>
                                    {{ \Carbon\Carbon::parse($log->tanggal)->format('d/m/Y') }}
                                    <small class="d-block text-muted">
                                        {{ $log->created_at->diffForHumans() }}
                                    </small>
                                </td>
                                <td>
                                    <span class="badge bg-info text-white">
                                        {{ $log->pompa->kodepompa ?? '-' }}
                                    </span>
                                </td>
                                <td>{{ $log->pompa->lokasi->namasp ?? '-' }}</td>
                                <td>{{ $log->injeksi_ke ?? '-' }}</td>
                                <td>
                                    @php
                                        $statusMap = [
                                            0 => ['label' => 'Draft',     'class' => 'bg-secondary text-dark'],
                                            1 => ['label' => 'Submitted', 'class' => 'bg-warning text-dark'],
                                            2 => ['label' => 'Revisi',    'class' => 'bg-warning text-dark'],
                                            3 => ['label' => 'Approved',  'class' => 'bg-success'],
                                            4 => ['label' => 'Rejected',  'class' => 'bg-danger'],
                                        ];
                                        $st = $statusMap[$log->status_code] 
                                              ?? ['label' => '-', 'class' => 'bg-secondary'];
                                    @endphp
                                    <span class="badge {{ $st['class'] }}">
                                        {{ $st['label'] }}
                                    </span>
                                </td>
                                <td>
                                    {{ \Illuminate\Support\Str::limit($log->keterangan ?? '-', 40) }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@endsection