{{-- View File: resources/views/user/pages/report/index.blade.php --}}
@extends('user.layouts.app')

@section('title', $title ?? 'Laporan Saya')

@push('styles')
<style>
    .badge-status {
        font-size: 12px;
        padding: 5px 10px;
        border-radius: 20px;
        font-weight: 500;
    }
    .badge-draft     { background-color: #e9ecef; color: #6c757d; }
    .badge-submitted { background-color: #fff3cd; color: #856404; }
    .badge-approved  { background-color: #d1e7dd; color: #0a3622; }
    .badge-revision  { background-color: #fff3cd; color: #664d03; }
    .badge-rejected  { background-color: #f8d7da; color: #58151c; }

    .stat-card {
        border-radius: 10px;
        padding: 20px;
        color: white;
        position: relative;
        overflow: hidden;
        transition: transform 0.2s;
        cursor: pointer;
        text-decoration: none;
        display: block;
    }
    .stat-card:hover {
        transform: translateY(-3px);
        color: white;
        text-decoration: none;
    }
    .stat-card .stat-icon {
        font-size: 40px;
        opacity: 0.3;
        position: absolute;
        right: 15px;
        bottom: 10px;
    }
    .stat-card .stat-number {
        font-size: 32px;
        font-weight: 700;
        line-height: 1;
        margin-bottom: 6px;
    }
    .stat-card .stat-label {
        font-size: 13px;
        opacity: 0.85;
        font-weight: 500;
    }
    .stat-card.card-total      { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    .stat-card.card-submitted  { background: linear-gradient(135deg, #f6d365 0%, #fda085 100%); }
    .stat-card.card-approved   { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: #1a5c3a; }
    .stat-card.card-approved .stat-label { color: #1a5c3a; }
    .stat-card.card-revision   { background: linear-gradient(135deg, #fa8231 0%, #f7b731 100%); }
    .stat-card.card-rejected   { background: linear-gradient(135deg, #ff5f6d 0%, #ffc371 100%); }
    .stat-card.card-draft      { background: linear-gradient(135deg, #a8c0ff 0%, #3f2b96 100%); }

    .filter-bar {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 12px 16px;
        margin-bottom: 20px;
        border-left: 4px solid #667eea;
    }
    .table th {
        background-color: #f8f9fa;
        font-weight: 600;
        font-size: 13px;
        vertical-align: middle;
    }
    .table td {
        vertical-align: middle;
        font-size: 13px;
    }
    .btn-action {
        padding: 4px 10px;
        font-size: 12px;
    }
    .empty-state {
        padding: 60px 20px;
        text-align: center;
        color: #6c757d;
    }
    .empty-state i {
        font-size: 48px;
        margin-bottom: 16px;
        display: block;
        color: #dee2e6;
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <h3 class="page-title mb-2">Laporan Saya</h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('user.dashboard.index') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Laporan Saya</li>
        </ol>
    </nav>
</div>

{{-- ===== STAT CARDS ===== --}}
<div class="row mb-4">

    {{-- Total --}}
    <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
        <a href="{{ route('user.report.list') }}"
           class="stat-card card-total {{ request()->missing('status') ? 'ring' : '' }}">
            <div class="stat-number">{{ $totalAll }}</div>
            <div class="stat-label">Total Laporan</div>
            <i class="mdi mdi-file-document-multiple stat-icon"></i>
        </a>
    </div>

    {{-- Draft --}}
    <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
        <a href="{{ route('user.report.index', ['status' => 'draft']) }}"
           class="stat-card card-draft">
            <div class="stat-number">{{ $totalDraft }}</div>
            <div class="stat-label">Draft</div>
            <i class="mdi mdi-file-edit stat-icon"></i>
        </a>
    </div>

    {{-- Menunggu Approval --}}
    <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
        <a href="{{ route('user.report.list', ['status' => 'submitted']) }}"
           class="stat-card card-submitted">
            <div class="stat-number">{{ $totalSubmitted }}</div>
            <div class="stat-label" style="font-size: 12px;" >Menunggu Approval</div>
            <i class="mdi mdi-clock-outline stat-icon"></i>
        </a>
    </div>

    {{-- Disetujui --}}
    <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
        <a href="{{ route('user.report.list', ['status' => 'approved']) }}"
           class="stat-card card-approved">
            <div class="stat-number">{{ $totalApproved }}</div>
            <div class="stat-label">Disetujui</div>
            <i class="mdi mdi-check-circle stat-icon"></i>
        </a>
    </div>

    {{-- Perlu Revisi --}}
    <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
        <a href="{{ route('user.report.list', ['status' => 'revision']) }}"
           class="stat-card card-revision">
            <div class="stat-number">{{ $totalRevision }}</div>
            <div class="stat-label">Perlu Revisi</div>
            <i class="mdi mdi-pencil-circle stat-icon"></i>
        </a>
    </div>

    {{-- Ditolak --}}
    <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
        <a href="{{ route('user.report.list', ['status' => 'rejected']) }}"
           class="stat-card card-rejected">
            <div class="stat-number">{{ $totalRejected }}</div>
            <div class="stat-label">Ditolak</div>
            <i class="mdi mdi-close-circle stat-icon"></i>
        </a>
    </div>

</div>

{{-- ===== TABEL ===== --}}
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">

                {{-- Header Card --}}
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="card-title mb-1">Daftar Laporan Harian</h4>
                        <p class="card-description mb-0">PT Pertamina EP Asset 2 Limau Field</p>
                    </div>
                    <div>
                        <a href="{{ route('user.report.index') }}" class="btn btn-primary btn-sm">
                            <i class="mdi mdi-plus me-1"></i> Buat Laporan
                        </a>
                    </div>
                </div>

                {{-- Filter Bar --}}
                <div class="filter-bar d-flex align-items-center flex-wrap gap-2">
                    <span class="me-2 fw-semibold text-muted" style="font-size:13px;">
                        <i class="mdi mdi-filter-outline me-1"></i>Filter:
                    </span>
                    <a href="{{ route('user.report.list') }}"
                       class="btn btn-sm {{ request()->missing('status') ? 'btn-primary' : 'btn-outline-secondary' }}">
                        Semua
                    </a>
                    <a href="{{ route('user.report.list', ['status' => 'draft']) }}"
                       class="btn btn-sm {{ request('status') == 'draft' ? 'btn-secondary' : 'btn-outline-secondary' }}">
                        Draft
                    </a>
                    <a href="{{ route('user.report.list', ['status' => 'submitted']) }}"
                       class="btn btn-sm {{ request('status') == 'submitted' ? 'btn-warning' : 'btn-outline-secondary' }}">
                        Menunggu Approval
                    </a>
                    <a href="{{ route('user.report.list', ['status' => 'approved']) }}"
                       class="btn btn-sm {{ request('status') == 'approved' ? 'btn-success' : 'btn-outline-secondary' }}">
                        Disetujui
                    </a>
                    <a href="{{ route('user.report.list', ['status' => 'revision']) }}"
                       class="btn btn-sm {{ request('status') == 'revision' ? 'btn-warning' : 'btn-outline-secondary' }}">
                        Perlu Revisi
                    </a>
                    <a href="{{ route('user.report.list', ['status' => 'rejected']) }}"
                       class="btn btn-sm {{ request('status') == 'rejected' ? 'btn-danger' : 'btn-outline-secondary' }}">
                        Ditolak
                    </a>
                    <span class="ms-auto text-muted" style="font-size:12px;">
                        Total: <strong>{{ $reports->total() }}</strong> laporan
                    </span>
                </div>

                {{-- Alert --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="mdi mdi-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="mdi mdi-alert-circle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- Tabel --}}
                @if($reports->isEmpty())
                    <div class="empty-state">
                        <i class="mdi mdi-file-document-outline"></i>
                        <h5>Belum ada laporan</h5>
                        <p class="mb-3">
                            {{ request('status') ? 'Tidak ada laporan dengan status ini.' : 'Anda belum membuat laporan apapun.' }}
                        </p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">#</th>
                                    <th>Tanggal</th>
                                    <th>Lokasi SP</th>
                                    <th>Pompa</th>
                                    <th>Injektor Well</th>
                                    <th style="width: 14%;">Status</th>
                                    <th>Dibuat</th>
                                    <th style="width: 12%;" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reports as $index => $report)
                                <tr>
                                    <td>{{ $reports->firstItem() + $index }}</td>
                                    <td>{{ \Carbon\Carbon::parse($report->tanggal)->format('d M Y') }}</td>
                                    <td>
                                        @if($report->lokasi)
                                            <span class="badge bg-light text-dark border">{{ $report->lokasi->kodesp }}</span>
                                            <small class="d-block text-muted mt-1">{{ $report->lokasi->namasp }}</small>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($report->pompa)
                                            {{ $report->pompa->kodepompa }}
                                            <small class="d-block text-muted">{{ $report->pompa->jenispompa }}</small>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>{{ $report->injector_well ?? '-' }}</td>
                                    <td>
                                        @php
                                            $statusMap = [
                                                0 => ['label' => 'Draft',             'class' => 'badge-draft'],
                                                1 => ['label' => 'Menunggu Approval', 'class' => 'badge-submitted'],
                                                2 => ['label' => 'Perlu Revisi',      'class' => 'badge-revision'],
                                                3 => ['label' => 'Disetujui',         'class' => 'badge-approved'],
                                                4 => ['label' => 'Ditolak',           'class' => 'badge-rejected'],
                                            ];
                                            $status = $statusMap[$report->status_code] ?? ['label' => 'Unknown', 'class' => 'badge-draft'];
                                        @endphp
                                        <span class="badge-status {{ $status['class'] }}">
                                            {{ $status['label'] }}
                                        </span>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $report->created_at->diffForHumans() }}</small>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-1">
                                            <a href="{{ route('user.dailyreport.edit', $report->id) }}"
                                               class="btn btn-sm btn-outline-info btn-action" title="Lihat / Edit">
                                                <i class="mdi mdi-eye"></i>
                                            </a>
                                            @if($report->status_code == 0)
                                                <form action="{{ route('user.dailyreport.submit', $report->id) }}"
                                                      method="POST" class="d-inline"
                                                      onsubmit="return confirm('Submit laporan ini?')">
                                                    @csrf @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-outline-primary btn-action" title="Submit">
                                                        <i class="mdi mdi-send"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            @if(in_array($report->status_code, [0, 2]))
                                                <form action="{{ route('user.dailyreport.destroy', $report->id) }}"
                                                      method="POST" class="d-inline"
                                                      onsubmit="return confirm('Hapus laporan ini?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger btn-action" title="Hapus">
                                                        <i class="mdi mdi-trash-can"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <small class="text-muted">
                            Menampilkan {{ $reports->firstItem() }}–{{ $reports->lastItem() }}
                            dari {{ $reports->total() }} laporan
                        </small>
                        {{ $reports->appends(request()->query())->links() }}
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection