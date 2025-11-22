@extends('user.layouts.app')

@section('title', 'Dashboard')

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
    .bg-primary-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    .bg-success-gradient {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    }
    .bg-warning-gradient {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }
    .bg-info-gradient {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }
    .bg-danger-gradient {
        background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
    }
    .stat-card {
        border: none;
        border-left: 4px solid;
    }
    .stat-card.primary {
        border-left-color: #667eea;
    }
    .stat-card.success {
        border-left-color: #38ef7d;
    }
    .stat-card.warning {
        border-left-color: #f5576c;
    }
    .stat-card.info {
        border-left-color: #00f2fe;
    }
    .stat-card.danger {
        border-left-color: #fa709a;
    }
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
                        <h4 class="text-white mb-2">Selamat Datang, {{ Auth::user()->name ?? 'User' }}!</h4>
                        <p class="mb-0 opacity-75">Sistem Monitoring Pompa Air - {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
                    </div>
                    <div>
                        <i class="ti-user" style="font-size: 48px; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Statistics Cards --}}
<div class="row">
    {{-- Total Pompa --}}
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
                        <i class="ti-settings"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Pompa Aktif --}}
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
                        <i class="ti-check-box"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Pompa Non-Aktif --}}
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
                        <i class="ti-close"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Total Lokasi SP --}}
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
                        <i class="ti-location-pin"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Log Activity Today --}}
<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card stat-card danger">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Log Hari Ini</h6>
                        <h2 class="mb-0 fw-bold">{{ $logHariIni ?? 0 }}</h2>
                        <small class="text-muted">Aktivitas Tercatat</small>
                    </div>
                    <div class="card-icon bg-danger-gradient">
                        <i class="ti-clipboard"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card stat-card primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Total Log</h6>
                        <h2 class="mb-0 fw-bold">{{ $totalLog ?? 0 }}</h2>
                        <small class="text-muted">Semua Aktivitas</small>
                    </div>
                    <div class="card-icon bg-primary-gradient">
                        <i class="ti-archive"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Quick Access Cards --}}
<div class="row">
    <div class="col-12 mb-3">
        <h4 class="mb-3">Menu Cepat</h4>
    </div>

    <div class="col-md-4 mb-4">
        <a href="" class="text-decoration-none">
            <div class="card">
                <div class="card-body text-center py-4">
                    <div class="card-icon bg-primary-gradient mx-auto mb-3">
                        <i class="ti-settings"></i>
                    </div>
                    <h5 class="card-title mb-2">Data Pompa</h5>
                    <p class="text-muted small mb-0">Lihat daftar semua pompa</p>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-4 mb-4">
        <a href="" class="text-decoration-none">
            <div class="card">
                <div class="card-body text-center py-4">
                    <div class="card-icon bg-success-gradient mx-auto mb-3">
                        <i class="ti-clipboard"></i>
                    </div>
                    <h5 class="card-title mb-2">Log Aktivitas</h5>
                    <p class="text-muted small mb-0">Catat aktivitas pompa</p>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-4 mb-4">
        <a href="" class="text-decoration-none">
            <div class="card">
                <div class="card-body text-center py-4">
                    <div class="card-icon bg-info-gradient mx-auto mb-3">
                        <i class="ti-location-pin"></i>
                    </div>
                    <h5 class="card-title mb-2">Lokasi SP</h5>
                    <p class="text-muted small mb-0">Lihat stasiun pompa</p>
                </div>
            </div>
        </a>
    </div>
</div>

{{-- Recent Activity --}}
@if(isset($recentLogs) && count($recentLogs) > 0)
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title mb-0">Aktivitas Terbaru</h4>
                    <a href="" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th width="15%">Tanggal</th>
                                <th width="15%">Kode Pompa</th>
                                <th width="20%">Lokasi</th>
                                <th width="15%">Status</th>
                                <th width="35%">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentLogs as $log)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($log->tanggal)->format('d/m/Y') }}</td>
                                <td>
                                    <span class="badge badge-info">{{ $log->pompa->kodepompa ?? '-' }}</span>
                                </td>
                                <td>{{ $log->pompa->lokasi->namasp ?? '-' }}</td>
                                <td>
                                    @if($log->status == 'berjalan')
                                        <span class="badge badge-success">Berjalan</span>
                                    @elseif($log->status == 'mati')
                                        <span class="badge badge-danger">Mati</span>
                                    @else
                                        <span class="badge badge-warning">Maintenance</span>
                                    @endif
                                </td>
                                <td>{{ \Illuminate\Support\Str::limit($log->keterangan ?? '-', 50) }}</td>
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