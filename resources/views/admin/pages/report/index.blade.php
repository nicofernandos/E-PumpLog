@extends('admin.layouts.app')

@section('title', $title)

@push('styles')
<style>
    .table td { vertical-align: middle; }

    .stats-card {
        border-left: 4px solid #667eea;
        transition: transform 0.2s;
    }
    .stats-card:hover { transform: translateY(-3px); }

    /* ── Badge status — sesuai enum DB ── */
    .badge-draft     { background-color: #6c757d; color: #fff; }
    .badge-finalized { background-color: #17a2b8; color: #fff; }
    .badge-verified  { background-color: #007bff; color: #fff; }
    .badge-approved  { background-color: #28a745; color: #fff; }

    .hourly-badge { font-size: 10px; padding: 2px 6px; }

    /* Filter row compact */
    .filter-row .form-control,
    .filter-row .form-select { font-size: 13px; }
</style>
@endpush

@section('content')
<div class="page-header">
    <h3 class="page-title mb-2">Laporan Harian Pompa</h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Laporan Harian</li>
        </ol>
    </nav>
</div>

{{-- ===== STATISTIK BARIS 1 ===== --}}
<div class="row mb-3">
    <div class="col-md-3 mb-3">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Total Laporan</h6>
                        <h3 class="mb-0 fw-bold text-primary">{{ $totalReports ?? 0 }}</h3>
                        <small class="text-muted">Semua Periode</small>
                    </div>
                    <i class="mdi mdi-file-document-outline text-primary" style="font-size:48px;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card stats-card" style="border-left-color:#28a745;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Laporan Hari Ini</h6>
                        <h3 class="mb-0 fw-bold text-success">{{ $reportsToday ?? 0 }}</h3>
                        <small class="text-muted">{{ \Carbon\Carbon::now()->format('d/m/Y') }}</small>
                    </div>
                    <i class="mdi mdi-calendar-check text-success" style="font-size:48px;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        {{-- Menunggu Approval = status 'verified' (sudah disubmit user) --}}
        <div class="card stats-card" style="border-left-color:#ffc107;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Menunggu Approval</h6>
                        <h3 class="mb-0 fw-bold text-warning">{{ $pendingApproval ?? 0 }}</h3>
                        <small class="text-muted">Status: Verified</small>
                    </div>
                    <i class="mdi mdi-clock-alert-outline text-warning" style="font-size:48px;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card stats-card" style="border-left-color:#17a2b8;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Bulan Ini</h6>
                        <h3 class="mb-0 fw-bold text-info">{{ $reportsThisMonth ?? 0 }}</h3>
                        <small class="text-muted">{{ \Carbon\Carbon::now()->isoFormat('MMMM Y') }}</small>
                    </div>
                    <i class="mdi mdi-chart-line text-info" style="font-size:48px;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ===== STATISTIK BARIS 2 ===== --}}
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card stats-card" style="border-left-color:#FFCE1B;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Draft Hari Ini</h6>
                        <h4 class="mb-0 fw-bold text-warning">{{ $reportsDraftToday ?? 0 }}</h4>
                        <small class="text-muted">{{ \Carbon\Carbon::now()->format('d/m/Y') }}</small>
                    </div>
                    <i class="mdi mdi-file-edit-outline text-warning" style="font-size:40px;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card stats-card" style="border-left-color:#014325;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Verified Hari Ini</h6>
                        <h4 class="mb-0 fw-bold text-success">{{ $reportsVerifiedToday ?? 0 }}</h4>
                        <small class="text-muted">{{ \Carbon\Carbon::now()->format('d/m/Y') }}</small>
                    </div>
                    <i class="mdi mdi-check-decagram text-success" style="font-size:40px;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ===== TABEL UTAMA ===== --}}
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">

                {{-- Header card --}}
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h4 class="card-title mb-0">Daftar Laporan Harian Pompa</h4>
                        <p class="card-description mb-0 text-muted">Monitoring laporan harian injeksi pompa dari user</p>
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

                {{-- ── Filter — server-side via GET ── --}}
                <form method="GET" action="{{ route('admin.reports.index') }}" id="filterForm">
                    <div class="row mb-3 filter-row g-2">
                        <div class="col-md-2">
                            <input type="text" class="form-control" name="search"
                                   value="{{ request('search') }}"
                                   placeholder="Cari pompa, lokasi, user...">
                        </div>
                        <div class="col-md-2">
                            <input type="date" class="form-control" name="date_from"
                                   value="{{ request('date_from') }}" placeholder="Dari Tanggal">
                        </div>
                        <div class="col-md-2">
                            <input type="date" class="form-control" name="date_to"
                                   value="{{ request('date_to') }}" placeholder="Sampai Tanggal">
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" name="lokasi_id">
                                <option value="">Semua Lokasi</option>
                                @foreach($lokasi ?? [] as $lok)
                                    <option value="{{ $lok->id }}"
                                        {{ request('lokasi_id') == $lok->id ? 'selected' : '' }}>
                                        {{ $lok->namasp }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            {{-- Fix: nilai sesuai enum DB --}}
                            <select class="form-select" name="status">
                                <option value="">Semua Status</option>
                                <option value="draft"     {{ request('status') == 'draft'     ? 'selected' : '' }}>Draft</option>
                                <option value="finalized" {{ request('status') == 'finalized' ? 'selected' : '' }}>Finalized</option>
                                <option value="verified"  {{ request('status') == 'verified'  ? 'selected' : '' }}>Verified</option>
                                <option value="approved"  {{ request('status') == 'approved'  ? 'selected' : '' }}>Approved</option>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex gap-1">
                            <button type="submit" class="btn btn-primary w-100" title="Cari">
                                <i class="mdi mdi-magnify"></i>
                            </button>
                            <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary w-100" title="Reset">
                                <i class="mdi mdi-refresh"></i>
                            </a>
                        </div>
                    </div>
                </form>

                {{-- ── Tabel ── --}}
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th width="4%">No</th>
                                <th width="9%">Tanggal</th>
                                <th width="10%">Kode Pompa</th>
                                <th width="15%">Lokasi SP</th>
                                <th width="11%">Injector Well</th>
                                <th width="11%">User Input</th>
                                <th width="7%">Entry Jam</th>
                                <th width="9%">Total BBLS</th>
                                <th width="9%" class="text-center">Status</th>
                                <th width="15%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($reports ?? [] as $index => $report)
                                @php
                                    // ── Konfigurasi badge status sesuai enum DB ──
                                    $statusConfig = [
                                        'draft'     => ['label' => 'Draft',     'class' => 'badge-draft'],
                                        'finalized' => ['label' => 'Finalized', 'class' => 'badge-finalized'],
                                        'verified'  => ['label' => 'Verified',  'class' => 'badge-verified'],
                                        'approved'  => ['label' => 'Approved',  'class' => 'badge-approved'],
                                    ];
                                    $st = $statusConfig[$report->status]
                                          ?? ['label' => ucfirst($report->status ?? 'Draft'), 'class' => 'badge-draft'];
                                @endphp
                                <tr>
                                    <td>{{ ($reports->currentPage() - 1) * $reports->perPage() + $index + 1 }}</td>

                                    <td>
                                        <strong>{{ \Carbon\Carbon::parse($report->tanggal)->format('d/m/Y') }}</strong>
                                        <small class="d-block text-muted">
                                            {{ \Carbon\Carbon::parse($report->created_at)->format('H:i') }}
                                        </small>
                                    </td>

                                    <td>
                                        {{-- Fix: pakai bg-info text-dark (Bootstrap 5) --}}
                                        <span class="badge bg-info text-dark">
                                            {{ $report->pompa->kodepompa ?? '-' }}
                                        </span>
                                        <small class="d-block text-muted">
                                            {{ $report->pompa->jenispompa ?? '-' }}
                                        </small>
                                    </td>

                                    <td>
                                        <small class="text-muted">{{ $report->pompa->lokasi->kodesp ?? '-' }}</small>
                                        <span class="d-block">{{ $report->pompa->lokasi->namasp ?? '-' }}</span>
                                    </td>

                                    <td><strong>{{ $report->injeksi_ke ?? '-' }}</strong></td>

                                    <td>
                                        <span class="d-block">{{ $report->user->name ?? '-' }}</span>
                                        <small class="text-muted">{{ $report->user->email ?? '-' }}</small>
                                    </td>

                                    <td>
                                        {{-- Fix: pakai bg-primary (Bootstrap 5) --}}
                                        <span class="badge bg-primary hourly-badge">
                                            {{ $report->hourly_entries_count ?? 0 }} entry
                                        </span>
                                    </td>

                                    <td>
                                        <strong>{{ number_format($report->total_cumulative ?? 0, 2) }}</strong>
                                    </td>

                                    <td class="text-center">
                                        <span class="badge {{ $st['class'] }}">{{ $st['label'] }}</span>
                                    </td>

                                    <td class="text-center">
                                        {{-- Lihat Detail --}}
                                        <button type="button"
                                                class="btn btn-sm btn-info btn-icon"
                                                title="Lihat Detail"
                                                data-bs-toggle="modal"
                                                data-bs-target="#detailReportModal"
                                                data-id="{{ $report->id }}">
                                            <i class="mdi mdi-eye"></i>
                                        </button>

                                        {{-- Fix: tombol approve hanya muncul jika status 'verified' --}}
                                        @if($report->status === 'verified')
                                            <button type="button"
                                                    class="btn btn-sm btn-success btn-icon"
                                                    title="Approve"
                                                    onclick="confirmApprove('{{ $report->id }}')">
                                                <i class="mdi mdi-check-circle"></i>
                                            </button>
                                            <button type="button"
                                                    class="btn btn-sm btn-warning btn-icon"
                                                    title="Kembalikan"
                                                    onclick="confirmReject('{{ $report->id }}')">
                                                <i class="mdi mdi-close-circle"></i>
                                            </button>
                                        @endif

                                        <button type="button"
                                                class="btn btn-sm btn-outline-primary btn-icon"
                                                title="Print PDF">
                                            <i class="mdi mdi-printer"></i>
                                        </button>

                                        <button type="button"
                                                class="btn btn-sm btn-danger btn-icon"
                                                title="Hapus"
                                                onclick="confirmDelete('{{ $report->id }}')">
                                            <i class="mdi mdi-delete"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center py-5">
                                        <i class="mdi mdi-file-document-outline" style="font-size:64px;color:#ccc;"></i>
                                        <h5 class="text-muted mt-3 mb-1">Belum Ada Laporan</h5>
                                        <p class="text-muted mb-0">Laporan harian dari user akan muncul di sini</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if(isset($reports) && $reports->total() > 0)
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <p class="mb-0 text-muted small">
                        Menampilkan {{ $reports->firstItem() }}–{{ $reports->lastItem() }}
                        dari {{ $reports->total() }} laporan
                    </p>
                    <nav>{{ $reports->links('pagination::bootstrap-4') }}</nav>
                </div>
                @endif

            </div>
        </div>
    </div>
</div>

{{-- ===== MODAL DETAIL ===== --}}
<div class="modal fade" id="detailReportModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="mdi mdi-file-document-outline me-2"></i>Detail Laporan Harian Injeksi Pompa
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                {{-- Info laporan & lokasi --}}
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-subtitle mb-3 text-muted">Informasi Laporan</h6>
                                <table class="table table-sm table-borderless mb-0">
                                    <tr><td width="40%"><strong>Tanggal</strong></td>
                                        <td>: <span id="detail_tanggal">-</span></td></tr>
                                    <tr><td><strong>Kode Pompa</strong></td>
                                        <td>: <span id="detail_kodepompa">-</span></td></tr>
                                    <tr><td><strong>Jenis Pompa</strong></td>
                                        <td>: <span id="detail_jenispompa">-</span></td></tr>
                                    <tr><td><strong>Injector Well</strong></td>
                                        <td>: <span id="detail_injector_well">-</span></td></tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-subtitle mb-3 text-muted">Lokasi & User</h6>
                                <table class="table table-sm table-borderless mb-0">
                                    <tr><td width="40%"><strong>Lokasi SP</strong></td>
                                        <td>: <span id="detail_lokasi">-</span></td></tr>
                                    <tr><td><strong>Kode SP</strong></td>
                                        <td>: <span id="detail_kodesp">-</span></td></tr>
                                    <tr><td><strong>Dibuat Oleh</strong></td>
                                        <td>: <span id="detail_user">-</span></td></tr>
                                    <tr><td><strong>Status</strong></td>
                                        <td>: <span id="detail_status" class="badge">-</span></td></tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tabel per jam --}}
                <div class="mb-4">
                    <h6 class="mb-3">
                        <i class="mdi mdi-chart-line me-2"></i>Data Flow Analyzer & Tekanan Engine Pompa Per Jam
                    </h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th rowspan="2" class="text-center" style="width:8%;">WAKTU</th>
                                    <th colspan="4" class="text-center">FLOW ANALYZER</th>
                                    <th colspan="4" class="text-center">TEKANAN ENGINE POMPA</th>
                                    <th rowspan="2" class="text-center" style="width:7%;">WATER C/F</th>
                                    <th rowspan="2" class="text-center" style="width:7%;">FREQ HZ</th>
                                </tr>
                                <tr>
                                    <th class="text-center" style="width:7%;">TOTAL BBLS</th>
                                    <th class="text-center" style="width:7%;">RATE/JAM</th>
                                    <th class="text-center" style="width:7%;">CUMM</th>
                                    <th class="text-center" style="width:7%;">RATE/HARI</th>
                                    <th class="text-center" style="width:8%;">INJEKSI PSI</th>
                                    <th class="text-center" style="width:7%;">RPM</th>
                                    <th class="text-center" style="width:7%;">OIL C/F</th>
                                    <th class="text-center" style="width:7%;">PRESS C/F</th>
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
                    <h6 class="mb-3"><i class="mdi mdi-file-document me-2"></i>Keterangan</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Dari Jam</th>
                                    <th>s/d Jam</th>
                                    <th>Keterangan</th>
                                    <th>DT=-/jam</th>
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

                {{-- Ringkasan --}}
                <div class="card bg-light">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="mb-3">Ringkasan Laporan</h6>
                                <table class="table table-sm table-borderless mb-0">
                                    <tr>
                                        <td width="60%"><strong>Total Cumulative BBLS</strong></td>
                                        <td>: <strong id="detail_total_cumulative" class="text-primary">0</strong></td>
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
                                    {{-- Fix: hilangkan @if($report->approved_at) karena $report tidak ada di scope ini --}}
                                    {{-- Baris ini dikontrol via JS berdasarkan data AJAX --}}
                                    <tr id="row_approved_at" style="display:none;">
                                        <td><strong>Approved At</strong></td>
                                        <td>: <span id="detail_approved_at">-</span></td>
                                    </tr>
                                    <tr id="row_approved_by" style="display:none;">
                                        <td><strong>Approved By</strong></td>
                                        <td>: <span id="detail_approved_by">-</span></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>{{-- /modal-body --}}

            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">
                    Tutup
                </button>
                <button type="button" class="btn btn-primary btn-sm">
                    Print PDF
                </button>
                <a href="{{ route('admin.reports.export.excel', request()->query()) }}"
                class="btn btn-success btn-sm me-2">
                   Export Excel
                </a>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Peta badge status — sesuai enum DB ──────────────────────────
    const STATUS_MAP = {
        draft:     { label: 'Draft',     cls: 'badge-draft' },
        finalized: { label: 'Finalized', cls: 'badge-finalized' },
        verified:  { label: 'Verified',  cls: 'badge-verified' },
        approved:  { label: 'Approved',  cls: 'badge-approved' },
    };

    // ── Auto-submit filter form on select change ─────────────────────
    ['[name="lokasi_id"]', '[name="status"]'].forEach(sel => {
        const el = document.querySelector(sel);
        if (el) el.addEventListener('change', () =>
            document.getElementById('filterForm').submit()
        );
    });

    // ── Modal Detail (AJAX) ──────────────────────────────────────────
    const detailModal = document.getElementById('detailReportModal');

    if (detailModal) {
        detailModal.addEventListener('show.bs.modal', function (event) {
            const reportId = event.relatedTarget.getAttribute('data-id');
            showLoadingState();

            fetch(`/admin/reports/${reportId}`, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => {
                if (!res.ok) throw new Error(`HTTP ${res.status}`);
                return res.json();
            })
            .then(data => populateModalData(data))
            .catch(err => {
                console.error('Error fetching report:', err);
                showErrorState();
            });
        });

        // Bersihkan modal saat ditutup
        detailModal.addEventListener('hidden.bs.modal', function () {
            resetModal();
        });
    }

    function showLoadingState() {
        const spin = '<i class="mdi mdi-loading mdi-spin"></i>';
        ['detail_tanggal','detail_kodepompa','detail_jenispompa','detail_injector_well',
         'detail_lokasi','detail_kodesp','detail_user'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.innerHTML = spin;
        });
        const body = document.getElementById('detail_hourly_data');
        if (body) body.innerHTML = `<tr><td colspan="11" class="text-center text-muted">${spin} Memuat data...</td></tr>`;
    }

    function showErrorState() {
        const err = `<tr><td colspan="11" class="text-center text-danger py-4">
            <i class="mdi mdi-alert-circle"></i> Gagal memuat data. Coba lagi.
        </td></tr>`;
        document.getElementById('detail_hourly_data').innerHTML = err;
    }

    function resetModal() {
        ['detail_tanggal','detail_kodepompa','detail_jenispompa','detail_injector_well',
         'detail_lokasi','detail_kodesp','detail_user','detail_total_cumulative',
         'detail_running_hours','detail_entries','detail_created_at','detail_updated_at',
         'detail_approved_at','detail_approved_by'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.textContent = '-';
        });
        document.getElementById('row_approved_at').style.display = 'none';
        document.getElementById('row_approved_by').style.display = 'none';
    }

    function populateModalData(data) {
        // Info dasar
        setText('detail_tanggal',       data.tanggal       ?? '-');
        setText('detail_kodepompa',     data.kodepompa     ?? '-');
        setText('detail_jenispompa',    data.jenispompa    ?? '-');
        setText('detail_injector_well', data.injeksi_ke    ?? '-');
        setText('detail_lokasi',        data.lokasi        ?? '-');
        setText('detail_kodesp',        data.kodesp        ?? '-');
        setText('detail_user',          data.user          ?? '-');

        // Fix: badge status dari STATUS_MAP, bukan asumsi pending/rejected
        const statusInfo = STATUS_MAP[data.status] ?? { label: data.status ?? '-', cls: 'badge-draft' };
        const badge = document.getElementById('detail_status');
        badge.textContent = statusInfo.label;
        badge.className   = `badge ${statusInfo.cls}`;

        // Tabel per jam
        const hourlyBody = document.getElementById('detail_hourly_data');
        if (data.hourly_data && data.hourly_data.length > 0) {
            hourlyBody.innerHTML = data.hourly_data.map(item => `
                <tr>
                    <td class="text-center fw-bold">${item.jam_ke}</td>
                    <td class="text-center">${item.total_bbls}</td>
                    <td class="text-center">${item.rate_jam}</td>
                    <td class="text-center">${item.cumm_bbls}</td>
                    <td class="text-center">${item.rate_hari}</td>
                    <td class="text-center">${item.inj_psi}</td>
                    <td class="text-center">${item.inj_rpm}</td>
                    <td class="text-center">${item.oil_cf}</td>
                    <td class="text-center">${item.press_cf}</td>
                    <td class="text-center">${item.water_cf}</td>
                    <td class="text-center">${item.freq_hz}</td>
                </tr>
            `).join('');
        } else {
            hourlyBody.innerHTML = `<tr><td colspan="11" class="text-center text-muted py-4">
                Tidak ada data per jam
            </td></tr>`;
        }

        // Keterangan
        const ketBody = document.getElementById('detail_keterangan');
        if (data.keterangan && data.keterangan.length > 0) {
            ketBody.innerHTML = data.keterangan.map(ket => `
                <tr>
                    <td>${ket.dari_jam  ?? '-'}</td>
                    <td>${ket.sd_jam    ?? '-'}</td>
                    <td>${ket.keterangan ?? '-'}</td>
                    <td>${ket.dt_jam    ?? '-'}</td>
                </tr>
            `).join('');
        } else {
            ketBody.innerHTML = `<tr><td colspan="4" class="text-center text-muted py-3">
                Tidak ada keterangan
            </td></tr>`;
        }

        // Ringkasan
        setText('detail_total_cumulative', data.total_cumulative ?? '0');
        setText('detail_running_hours',    data.running_hours    ?? '-');
        setText('detail_entries',          data.entries_count    ?? '0');
        setText('detail_created_at',       data.created_at       ?? '-');
        setText('detail_updated_at',       data.updated_at       ?? '-');

        if (data.approved_at) {
            setText('detail_approved_at', data.approved_at);
            setText('detail_approved_by', data.approved_by ?? '-');
            document.getElementById('row_approved_at').style.display = '';
            document.getElementById('row_approved_by').style.display = '';
        } else {
            document.getElementById('row_approved_at').style.display = 'none';
            document.getElementById('row_approved_by').style.display = 'none';
        }
    }

    function setText(id, val) {
        const el = document.getElementById(id);
        if (el) el.textContent = val;
    }

    // ── Approve ──────────────────────────────────────────────────────
    window.confirmApprove = function (reportId) {
        Swal.fire({
            title: 'Approve Laporan?',
            html: '<p class="mb-0">Laporan yang sudah di-approve tidak dapat diubah.</p>',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="mdi mdi-check-circle me-1"></i> Ya, Approve!',
            cancelButtonText: 'Batal'
        }).then(result => {
            if (result.isConfirmed) submitForm(`/admin/reports/${reportId}/approve`, 'PATCH');
        });
    };

    // ── Reject / Kembalikan ───────────────────────────────────────────
    window.confirmReject = function (reportId) {
        Swal.fire({
            title: 'Kembalikan Laporan?',
            html: '<p class="mb-2 text-muted">Laporan akan dikembalikan ke status <strong>Finalized</strong> untuk direvisi user.</p>'
                + '<textarea id="reject_reason" class="form-control" placeholder="Alasan (opsional)" rows="3"></textarea>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ffc107',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="mdi mdi-undo me-1"></i> Ya, Kembalikan!',
            cancelButtonText: 'Batal',
            preConfirm: () => ({ reason: document.getElementById('reject_reason').value })
        }).then(result => {
            if (result.isConfirmed) {
                submitForm(`/admin/reports/${reportId}/reject`, 'PATCH', {
                    reject_reason: result.value.reason
                });
            }
        });
    };

    // ── Delete ────────────────────────────────────────────────────────
    window.confirmDelete = function (reportId) {
        Swal.fire({
            title: 'Hapus Laporan?',
            text: 'Data laporan akan dihapus permanen dan tidak dapat dikembalikan!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: '<i class="mdi mdi-delete me-1"></i> Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then(result => {
            if (result.isConfirmed) submitForm(`/admin/reports/${reportId}`, 'DELETE');
        });
    };

    // ── Helper: buat & submit form ────────────────────────────────────
    function submitForm(action, method, extraFields = {}) {
        const form = document.createElement('form');
        form.action = action;
        form.method = 'POST';

        const fields = {
            _token:  '{{ csrf_token() }}',
            _method: method,
            ...extraFields
        };

        Object.entries(fields).forEach(([name, value]) => {
            const input = document.createElement('input');
            input.type  = 'hidden';
            input.name  = name;
            input.value = value;
            form.appendChild(input);
        });

        document.body.appendChild(form);
        form.submit();
    }

});
</script>
@endpush