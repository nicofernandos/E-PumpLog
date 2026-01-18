@extends('admin.layouts.app')

@section('title', 'Laporan Disetujui')

@push('styles')
<style>
    .table td {
        vertical-align: middle;
    }
    .stats-card {
        border-left: 4px solid #28a745;
        transition: transform 0.2s;
    }
    .stats-card:hover {
        transform: translateY(-5px);
    }
    .badge-approved {
        background-color: #28a745;
        color: white;
    }
    .hourly-badge {
        font-size: 10px;
        padding: 2px 6px;
    }
    .approved-icon {
        color: #28a745;
        font-size: 20px;
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <h3 class="page-title mb-2">
        <i class="mdi mdi-check-circle-outline text-success me-2"></i>Laporan Disetujui
    </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.reports.index') }}">Laporan Harian</a></li>
            <li class="breadcrumb-item active" aria-current="page">Laporan Disetujui</li>
        </ol>
    </nav>
</div>

{{-- Statistics Cards --}}
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Total Disetujui</h6>
                        <h3 class="mb-0 fw-bold text-success">{{ $totalApproved ?? 0 }}</h3>
                        <small class="text-muted">Semua Periode</small>
                    </div>
                    <div>
                        <i class="mdi mdi-check-circle text-success" style="font-size: 48px;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card stats-card" style="border-left-color: #17a2b8;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Disetujui Hari Ini</h6>
                        <h3 class="mb-0 fw-bold text-info">{{ $approvedToday ?? 0 }}</h3>
                        <small class="text-muted">{{ \Carbon\Carbon::now()->format('d/m/Y') }}</small>
                    </div>
                    <div>
                        <i class="mdi mdi-calendar-check text-info" style="font-size: 48px;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card stats-card" style="border-left-color: #6f42c1;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Bulan Ini</h6>
                        <h3 class="mb-0 fw-bold text-purple">{{ $approvedThisMonth ?? 0 }}</h3>
                        <small class="text-muted">{{ \Carbon\Carbon::now()->format('F Y') }}</small>
                    </div>
                    <div>
                        <i class="mdi mdi-chart-line text-purple" style="font-size: 48px;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card stats-card" style="border-left-color: #fd7e14;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Total BBLS</h6>
                        <h3 class="mb-0 fw-bold text-orange">{{ number_format($totalCumulative ?? 0, 2) }}</h3>
                        <small class="text-muted">Kumulatif</small>
                    </div>
                    <div>
                        <i class="mdi mdi-gauge text-orange" style="font-size: 48px;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Main Content --}}
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h4 class="card-title mb-0">
                            <i class="mdi mdi-check-circle-outline text-success me-2"></i>Daftar Laporan Disetujui
                        </h4>
                        <p class="card-description mb-0">Laporan harian yang telah disetujui oleh admin</p>
                    </div>
                    <div>
                        <button type="button" class="btn btn-success btn-sm me-2">
                            <i class="mdi mdi-file-excel me-1"></i> Export Excel
                        </button>
                        <button type="button" class="btn btn-danger btn-sm">
                            <i class="mdi mdi-file-pdf me-1"></i> Export PDF
                        </button>
                    </div>
                </div>

                {{-- Info Alert --}}
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="mdi mdi-information-outline me-2"></i>
                    <strong>Info:</strong> Halaman ini menampilkan laporan yang telah disetujui. Laporan yang telah disetujui tidak dapat diubah atau dihapus.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>

                {{-- Filter Section --}}
                <div class="row mb-3">
                    <div class="col-md-3 mb-2">
                        <input type="text" class="form-control" id="searchReport" placeholder="Cari pompa, lokasi, user...">
                    </div>
                    <div class="col-md-2 mb-2">
                        <input type="date" class="form-control" id="filterDateFrom" placeholder="Dari Tanggal">
                    </div>
                    <div class="col-md-2 mb-2">
                        <input type="date" class="form-control" id="filterDateTo" placeholder="Sampai Tanggal">
                    </div>
                    <div class="col-md-2 mb-2">
                        <select class="form-select" id="filterLokasi">
                            <option value="">Semua Lokasi</option>
                            @foreach($lokasi ?? [] as $lok)
                                <option value="{{ $lok->id }}">{{ $lok->namasp }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <select class="form-select" id="filterApprovedBy">
                            <option value="">Semua Approver</option>
                            @foreach($approvers ?? [] as $approver)
                                <option value="{{ $approver->id }}">{{ $approver->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-1 mb-2">
                        <button type="button" class="btn btn-secondary w-100" id="resetFilter">
                            <i class="mdi mdi-refresh"></i>
                        </button>
                    </div>
                </div>

                {{-- Table --}}
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="10%">Tanggal</th>
                                <th width="10%">Kode Pompa</th>
                                <th width="15%">Lokasi SP</th>
                                <th width="10%">Injector Well</th>
                                <th width="10%">User Input</th>
                                <th width="8%">Total BBLS</th>
                                <th width="10%">Disetujui Oleh</th>
                                <th width="10%">Tanggal Approve</th>
                                <th width="12%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($reports ?? [] as $index => $report)
                                <tr data-lokasi="{{ $report->pompa->lokasi_id ?? '' }}"
                                    data-date="{{ $report->tanggal }}"
                                    data-approver="{{ $report->approved_by ?? '' }}">
                                    <td>{{ ($reports->currentPage() - 1) * $reports->perPage() + $index + 1 }}</td>
                                    <td>
                                        <strong>{{ \Carbon\Carbon::parse($report->tanggal)->format('d/m/Y') }}</strong><br>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($report->created_at)->format('H:i') }}</small>
                                    </td>
                                    <td>
                                        <span class="badge badge-info">{{ $report->pompa->kodepompa ?? '-' }}</span><br>
                                        <small class="text-muted">{{ $report->pompa->jenispompa ?? '-' }}</small>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $report->pompa->lokasi->kodesp ?? '-' }}</small><br>
                                        {{ $report->pompa->lokasi->namasp ?? '-' }}
                                    </td>
                                    <td>
                                        <strong>{{ $report->injeksi_ke ?? '-' }}</strong>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $report->user->name ?? '-' }}</small><br>
                                        <small class="text-muted">{{ $report->user->email ?? '-' }}</small>
                                    </td>
                                    <td>
                                        <strong class="text-success">{{ number_format($report->total_cumulative ?? 0, 2) }}</strong>
                                        <br>
                                        <span class="badge badge-primary hourly-badge">
                                            {{ $report->hourly_entries_count ?? 0 }} entry
                                        </span>
                                    </td>
                                    <td>
                                        <i class="mdi mdi-account-check approved-icon me-1"></i>
                                        <small>{{ $report->approvedBy->name ?? '-' }}</small>
                                    </td>
                                    <td>
                                        @if($report->approved_at)
                                            <small>{{ \Carbon\Carbon::parse($report->approved_at)->format('d/m/Y H:i') }}</small>
                                        @else
                                            <small class="text-muted">-</small>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <button type="button" 
                                                class="btn btn-sm btn-info btn-icon" 
                                                title="Lihat Detail"
                                                data-bs-toggle="modal"
                                                data-bs-target="#detailReportModal"
                                                data-id="{{ $report->id }}">
                                            <i class="mdi mdi-eye"></i>
                                        </button>

                                        <button type="button" 
                                                class="btn btn-sm btn-primary btn-icon" 
                                                title="Print PDF"
                                                onclick="printReport('{{ $report->id }}')">
                                            <i class="mdi mdi-printer"></i>
                                        </button>

                                        <button type="button" 
                                                class="btn btn-sm btn-success btn-icon" 
                                                title="Download Excel"
                                                onclick="downloadExcel('{{ $report->id }}')">
                                            <i class="mdi mdi-file-excel"></i>
                                        </button>

                                        {{-- Optional: Revoke approval (jika dibutuhkan) --}}
                                        {{-- <button type="button" 
                                                class="btn btn-sm btn-warning btn-icon" 
                                                title="Batalkan Approval"
                                                onclick="confirmRevoke('{{ $report->id }}')">
                                            <i class="mdi mdi-undo"></i>
                                        </button> --}}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center">
                                        <div class="py-5">
                                            <i class="mdi mdi-check-circle-outline text-success" style="font-size: 64px;"></i>
                                            <h5 class="text-muted mt-3 mb-2">Belum Ada Laporan Disetujui</h5>
                                            <p class="text-muted">Laporan yang telah disetujui akan muncul di sini</p>
                                            <a href="{{ route('admin.reports.index') }}" class="btn btn-primary btn-sm mt-2">
                                                <i class="mdi mdi-arrow-left me-1"></i> Kembali ke Daftar Laporan
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if(isset($reports) && $reports->total() > 0)
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <p class="mb-0 text-muted">
                        Menampilkan {{ $reports->firstItem() }} - {{ $reports->lastItem() }} dari {{ $reports->total() }} laporan disetujui
                    </p>
                    <nav aria-label="Page navigation">
                        {{ $reports->links('pagination::bootstrap-4') }}
                    </nav>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Modal Detail Report (Same as index) --}}
<div class="modal fade" id="detailReportModal" tabindex="-1" aria-labelledby="detailReportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="detailReportModalLabel">
                    <i class="mdi mdi-file-document-outline me-2"></i>Detail Laporan Disetujui
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{-- Header Information --}}
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-subtitle mb-3 text-muted">Informasi Laporan</h6>
                                <table class="table table-sm table-borderless mb-0">
                                    <tr>
                                        <td width="40%"><strong>Tanggal</strong></td>
                                        <td>: <span id="detail_tanggal">-</span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Kode Pompa</strong></td>
                                        <td>: <span id="detail_kodepompa">-</span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Jenis Pompa</strong></td>
                                        <td>: <span id="detail_jenispompa">-</span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Injector Well</strong></td>
                                        <td>: <span id="detail_injector_well">-</span></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-subtitle mb-3 text-muted">Lokasi & Approval</h6>
                                <table class="table table-sm table-borderless mb-0">
                                    <tr>
                                        <td width="40%"><strong>Lokasi SP</strong></td>
                                        <td>: <span id="detail_lokasi">-</span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Dibuat Oleh</strong></td>
                                        <td>: <span id="detail_user">-</span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Disetujui Oleh</strong></td>
                                        <td>: <span id="detail_approved_by" class="text-success">-</span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tanggal Approval</strong></td>
                                        <td>: <span id="detail_approved_at" class="text-success">-</span></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Data Per Jam --}}
                <div class="mb-4">
                    <h6 class="mb-3">
                        <i class="mdi mdi-chart-line me-2"></i>Data Flow Analyzer & Tekanan Engine Pompa Per Jam
                    </h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th rowspan="2" class="text-center" style="width: 8%;">WAKTU</th>
                                    <th colspan="4" class="text-center">FLOW ANALYZER</th>
                                    <th colspan="4" class="text-center">TEKANAN ENGINE POMPA</th>
                                    <th rowspan="2" class="text-center" style="width: 8%;">WATER C/F</th>
                                    <th rowspan="2" class="text-center" style="width: 8%;">FREQ HZ</th>
                                </tr>
                                <tr>
                                    <th class="text-center" style="width: 7%;">TOTAL BBLS</th>
                                    <th class="text-center" style="width: 7%;">RATE/JAM</th>
                                    <th class="text-center" style="width: 7%;">CUMM</th>
                                    <th class="text-center" style="width: 7%;">RATE/HARI</th>
                                    <th class="text-center" style="width: 8%;">INJEKSI PSI</th>
                                    <th class="text-center" style="width: 7%;">RPM</th>
                                    <th class="text-center" style="width: 7%;">OIL C/F</th>
                                    <th class="text-center" style="width: 7%;">PRESS C/F</th>
                                </tr>
                            </thead>
                            <tbody id="detail_hourly_data">
                                <tr>
                                    <td colspan="11" class="text-center text-muted">
                                        <i class="mdi mdi-loading mdi-spin me-2"></i>Memuat data...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Keterangan --}}
                <div class="mb-4">
                    <h6 class="mb-3">
                        <i class="mdi mdi-file-document me-2"></i>Keterangan
                    </h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-start">Dari Jam</th>
                                    <th class="text-start">s/d Jam</th>
                                    <th class="text-start">Keterangan</th>
                                    <th class="text-start">DT=-/jam</th>
                                </tr>
                            </thead>
                            <tbody id="detail_keterangan">
                                <tr>
                                    <td colspan="4" class="text-center text-muted">
                                        <i class="mdi mdi-loading mdi-spin me-2"></i>Memuat data...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Summary --}}
                <div class="card bg-light">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="mb-3">Ringkasan Laporan</h6>
                                <table class="table table-sm table-borderless mb-0">
                                    <tr>
                                        <td width="60%"><strong>Total Cumulative BBLS</strong></td>
                                        <td>: <strong id="detail_total_cumulative" class="text-success">0</strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Running Hours</strong></td>
                                        <td>: <span id="detail_running_hours">-</span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Jumlah Entry Data</strong></td>
                                        <td>: <span id="detail_entries">0</span> jam</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h6 class="mb-3">Timeline</h6>
                                <table class="table table-sm table-borderless mb-0">
                                    <tr>
                                        <td width="40%"><strong>Dibuat</strong></td>
                                        <td>: <span id="detail_created_at">-</span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Terakhir Update</strong></td>
                                        <td>: <span id="detail_updated_at">-</span></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                    <i class="mdi mdi-close me-1"></i>Tutup
                </button>
                <button type="button" class="btn btn-primary btn-sm" onclick="printCurrentReport()">
                    <i class="mdi mdi-printer me-1"></i>Print PDF
                </button>
                <button type="button" class="btn btn-success btn-sm" onclick="downloadCurrentReport()">
                    <i class="mdi mdi-file-excel me-1"></i>Download Excel
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('assets/js/admin/report-approved.js') }}"></script>
@endpush