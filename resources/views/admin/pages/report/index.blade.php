@extends('admin.layouts.app')

@section('title', 'Laporan Harian Pompa')

@push('styles')
<style>
    .table td {
        vertical-align: middle;
    }
    .stats-card {
        border-left: 4px solid #667eea;
        transition: transform 0.2s;
    }
    .stats-card:hover {
        transform: translateY(-5px);
    }
    .badge-approved {
        background-color: #28a745;
        color: white;
    }
    .badge-pending {
        background-color: #ffc107;
        color: #000;
    }
    .badge-rejected {
        background-color: #dc3545;
        color: white;
    }
    .hourly-badge {
        font-size: 10px;
        padding: 2px 6px;
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <h3 class="page-title mb-2">Laporan Harian Pompa</h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Laporan Harian</li>
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
                        <h6 class="text-muted mb-1">Total Laporan</h6>
                        <h3 class="mb-0 fw-bold text-primary">{{ $totalReports ?? 0 }}</h3>
                        <small class="text-muted">Semua Periode</small>
                    </div>
                    <div>
                        <i class="mdi mdi-file-document-outline text-primary" style="font-size: 48px;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card stats-card" style="border-left-color: #28a745;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Laporan Hari Ini</h6>
                        <h3 class="mb-0 fw-bold text-success">{{ $reportsToday ?? 0 }}</h3>
                        <small class="text-muted">{{ \Carbon\Carbon::now()->format('d/m/Y') }}</small>
                    </div>
                    <div>
                        <i class="mdi mdi-calendar-check text-success" style="font-size: 48px;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card stats-card" style="border-left-color: #ffc107;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Menunggu Approval</h6>
                        <h3 class="mb-0 fw-bold text-warning">{{ $pendingApproval ?? 0 }}</h3>
                        <small class="text-muted">Perlu Ditinjau</small>
                    </div>
                    <div>
                        <i class="mdi mdi-clock-alert-outline text-warning" style="font-size: 48px;"></i>
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
                        <h6 class="text-muted mb-1">Bulan Ini</h6>
                        <h3 class="mb-0 fw-bold text-info">{{ $reportsThisMonth ?? 0 }}</h3>
                        <small class="text-muted">{{ \Carbon\Carbon::now()->format('F Y') }}</small>
                    </div>
                    <div>
                        <i class="mdi mdi-chart-line text-info" style="font-size: 48px;"></i>
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
                        <h4 class="card-title mb-0">Daftar Laporan Harian Pompa</h4>
                        <p class="card-description mb-0">Monitoring laporan harian injeksi pompa dari user</p>
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
                        <select class="form-select" id="filterStatus">
                            <option value="">Semua Status</option>
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
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
                                <th width="12%">Injector Well</th>
                                <th width="10%">User Input</th>
                                <th width="8%">Entry Jam</th>
                                <th width="10%">Total BBLS</th>
                                <th width="8%" class="text-center">Status</th>
                                <th width="12%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($reports ?? [] as $index => $report)
                                <tr data-lokasi="{{ $report->pompa->lokasi_id ?? '' }}"
                                    data-date="{{ $report->tanggal }}"
                                    data-status="{{ $report->status ?? 'pending' }}">
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
                                        <span class="badge badge-primary hourly-badge">
                                            {{ $report->hourly_entries_count ?? 0 }} entry
                                        </span>
                                    </td>
                                    <td>
                                        <strong>{{ number_format($report->total_cumulative ?? 0, 2) }}</strong>
                                    </td>
                                    <td class="text-center">
                                        @if(($report->status ?? 'pending') == 'approved')
                                            <span class="badge badge-approved">Approved</span>
                                        @elseif(($report->status ?? 'pending') == 'rejected')
                                            <span class="badge badge-rejected">Rejected</span>
                                        @else
                                            <span class="badge badge-pending">Pending</span>
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

                                        @if(($report->status ?? 'pending') == 'pending')
                                        <button type="button" 
                                                class="btn btn-sm btn-success btn-icon" 
                                                title="Approve"
                                                onclick="confirmApprove('{{ $report->id }}')">
                                            <i class="mdi mdi-check-circle"></i>
                                        </button>

                                        <button type="button" 
                                                class="btn btn-sm btn-warning btn-icon" 
                                                title="Reject"
                                                onclick="confirmReject('{{ $report->id }}')">
                                            <i class="mdi mdi-close-circle"></i>
                                        </button>
                                        @endif

                                        <button type="button" 
                                                class="btn btn-sm btn-primary btn-icon" 
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
                                    <td colspan="10" class="text-center">
                                        <div class="py-5">
                                            <i class="mdi mdi-file-document-outline" style="font-size: 64px; color: #ccc;"></i>
                                            <h5 class="text-muted mt-3 mb-2">Belum Ada Laporan</h5>
                                            <p class="text-muted">Laporan harian dari user akan muncul di sini</p>
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
                        Menampilkan {{ $reports->firstItem() }} - {{ $reports->lastItem() }} dari {{ $reports->total() }} laporan
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

{{-- Modal Detail Report --}}
<div class="modal fade" id="detailReportModal" tabindex="-1" aria-labelledby="detailReportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="detailReportModalLabel">
                    <i class="mdi mdi-file-document-outline me-2"></i>Detail Laporan Harian Injeksi Pompa
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
                                <h6 class="card-subtitle mb-3 text-muted">Lokasi & User</h6>
                                <table class="table table-sm table-borderless mb-0">
                                    <tr>
                                        <td width="40%"><strong>Lokasi SP</strong></td>
                                        <td>: <span id="detail_lokasi">-</span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Kode SP</strong></td>
                                        <td>: <span id="detail_kodesp">-</span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Dibuat Oleh</strong></td>
                                        <td>: <span id="detail_user">-</span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Status</strong></td>
                                        <td>: <span id="detail_status" class="badge">-</span></td>
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
                                {{-- Data will be loaded via JavaScript --}}
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
                                {{-- Data will be loaded via JavaScript --}}
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
                                    @if(isset($report->approved_at))
                                    <tr>
                                        <td><strong>Approved At</strong></td>
                                        <td>: <span id="detail_approved_at">-</span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Approved By</strong></td>
                                        <td>: <span id="detail_approved_by">-</span></td>
                                    </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">
                    <i class="mdi mdi-close me-1"></i>Tutup
                </button>
                <button type="button" class="btn btn-primary btn-sm">
                    <i class="mdi mdi-printer me-1"></i>Print PDF
                </button>
                <button type="button" class="btn btn-success btn-sm">
                    <i class="mdi mdi-file-excel me-1"></i>Export Excel
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // FILTER & SEARCH FUNCTIONS
    const searchInput = document.getElementById('searchReport');
    const filterDateFrom = document.getElementById('filterDateFrom');
    const filterDateTo = document.getElementById('filterDateTo');
    const filterLokasi = document.getElementById('filterLokasi');
    const filterStatus = document.getElementById('filterStatus');
    const resetFilterBtn = document.getElementById('resetFilter');

    if (searchInput) searchInput.addEventListener('keyup', filterTable);
    if (filterDateFrom) filterDateFrom.addEventListener('change', filterTable);
    if (filterDateTo) filterDateTo.addEventListener('change', filterTable);
    if (filterLokasi) filterLokasi.addEventListener('change', filterTable);
    if (filterStatus) filterStatus.addEventListener('change', filterTable);

    if (resetFilterBtn) {
        resetFilterBtn.addEventListener('click', function() {
            searchInput.value = '';
            filterDateFrom.value = '';
            filterDateTo.value = '';
            filterLokasi.value = '';
            filterStatus.value = '';
            filterTable();
        });
    }

    function filterTable() {
        const searchValue = searchInput.value.toLowerCase();
        const dateFrom = filterDateFrom.value;
        const dateTo = filterDateTo.value;
        const lokasiValue = filterLokasi.value;
        const statusValue = filterStatus.value;
        const tableRows = document.querySelectorAll('tbody tr');
        
        let visibleCount = 0;
        
        tableRows.forEach(row => {
            if (row.querySelector('td[colspan]')) {
                row.style.display = visibleCount === 0 ? '' : 'none';
                return;
            }
            
            const kodepompa = row.querySelector('td:nth-child(3)')?.textContent.trim().toLowerCase() || '';
            const lokasi = row.querySelector('td:nth-child(4)')?.textContent.trim().toLowerCase() || '';
            const injector = row.querySelector('td:nth-child(5)')?.textContent.trim().toLowerCase() || '';
            const user = row.querySelector('td:nth-child(6)')?.textContent.trim().toLowerCase() || '';
            const lokasiId = row.getAttribute('data-lokasi');
            const rowDate = row.getAttribute('data-date');
            const rowStatus = row.getAttribute('data-status');

            const matchSearch = !searchValue || 
                               kodepompa.includes(searchValue) || 
                               lokasi.includes(searchValue) ||
                               injector.includes(searchValue) ||
                               user.includes(searchValue);
            
            const matchLokasi = !lokasiValue || lokasiId === lokasiValue;
            const matchStatus = !statusValue || rowStatus === statusValue;
            
            let matchDate = true;
            if (dateFrom && dateTo) {
                matchDate = rowDate >= dateFrom && rowDate <= dateTo;
            } else if (dateFrom) {
                matchDate = rowDate >= dateFrom;
            } else if (dateTo) {
                matchDate = rowDate <= dateTo;
            }
            
            if (matchSearch && matchLokasi && matchStatus && matchDate) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });
    }

    // MODAL DETAIL REPORT
    const detailModal = document.getElementById('detailReportModal');
    
    if (detailModal) {
        detailModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const reportId = button.getAttribute('data-id');
            
            showLoadingState();
            
            fetch(`/admin/reports/${reportId}/detail`)
                .then(response => response.json())
                .then(data => populateModalData(data))
                .catch(error => {
                    console.error('Error:', error);
                    showErrorState();
                });
        });
    }

    function showLoadingState() {
        document.getElementById('detail_tanggal').innerHTML = '<i class="mdi mdi-loading mdi-spin"></i>';
        document.getElementById('detail_kodepompa').innerHTML = '<i class="mdi mdi-loading mdi-spin"></i>';
        document.getElementById('detail_jenispompa').innerHTML = '<i class="mdi mdi-loading mdi-spin"></i>';
        document.getElementById('detail_injector_well').innerHTML = '<i class="mdi mdi-loading mdi-spin"></i>';
    }

    function showErrorState() {
        document.getElementById('detail_hourly_data').innerHTML = `
            <tr><td colspan="11" class="text-center text-danger py-4">
                <i class="mdi mdi-alert-circle"></i> Error loading data
            </td></tr>
        `;
    }

    function populateModalData(data) {
        document.getElementById('detail_tanggal').textContent = data.tanggal;
        document.getElementById('detail_kodepompa').textContent = data.kodepompa;
        document.getElementById('detail_jenispompa').textContent = data.jenispompa;
        document.getElementById('detail_injector_well').textContent = data.injeksi_ke;
        document.getElementById('detail_lokasi').textContent = data.lokasi;
        document.getElementById('detail_kodesp').textContent = data.kodesp;
        document.getElementById('detail_user').textContent = data.user;
        
        const statusBadge = document.getElementById('detail_status');
        statusBadge.textContent = data.status_label;
        statusBadge.className = 'badge';
        if (data.status === 'approved') statusBadge.classList.add('badge-approved');
        else if (data.status === 'rejected') statusBadge.classList.add('badge-rejected');
        else statusBadge.classList.add('badge-pending');

        const hourlyDataBody = document.getElementById('detail_hourly_data');
        if (data.hourly_data && data.hourly_data.length > 0) {
            hourlyDataBody.innerHTML = data.hourly_data.map(item => `
                <tr>
                    <td class="text-center"><strong>${item.jam_ke}</strong></td>
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
            hourlyDataBody.innerHTML = `<tr><td colspan="11" class="text-center text-muted py-4">Tidak ada data</td></tr>`;
        }

        const keteranganBody = document.getElementById('detail_keterangan');
        if (data.keterangan && data.keterangan.length > 0) {
            keteranganBody.innerHTML = data.keterangan.map(ket => `
                <tr>
                    <td>${ket.dari_jam || '-'}</td>
                    <td>${ket.sd_jam || '-'}</td>
                    <td>${ket.keterangan || '-'}</td>
                    <td>${ket.dt_jam || '-'}</td>
                </tr>
            `).join('');
        } else {
            keteranganBody.innerHTML = `<tr><td colspan="4" class="text-center text-muted py-3">Tidak ada keterangan</td></tr>`;
        }

        document.getElementById('detail_total_cumulative').textContent = data.total_cumulative;
        document.getElementById('detail_entries').textContent = data.entries_count;
        document.getElementById('detail_created_at').textContent = data.created_at;
        document.getElementById('detail_updated_at').textContent = data.updated_at;
    }

    // APPROVE REPORT
    window.confirmApprove = function(reportId) {
        Swal.fire({
            title: 'Approve Laporan?',
            html: '<p>Laporan yang sudah di-approve tidak dapat diubah.</p>',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="mdi mdi-check-circle me-1"></i> Ya, Approve!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.action = `/admin/reports/${reportId}/approve`;
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="PATCH">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        });
    };

    // REJECT REPORT
    window.confirmReject = function(reportId) {
        Swal.fire({
            title: 'Reject Laporan?',
            html: '<textarea id="reject_reason" class="form-control mt-2" placeholder="Alasan reject (opsional)" rows="3"></textarea>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ffc107',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="mdi mdi-close-circle me-1"></i> Ya, Reject!',
            cancelButtonText: 'Batal',
            preConfirm: () => ({ reason: document.getElementById('reject_reason').value })
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.action = `/admin/reports/${reportId}/reject`;
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="PATCH">
                    <input type="hidden" name="reject_reason" value="${result.value.reason}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        });
    };

    // DELETE REPORT
    window.confirmDelete = function(reportId) {
        Swal.fire({
            title: 'Hapus Laporan?',
            text: "Data laporan akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: '<i class="mdi mdi-delete me-1"></i> Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.action = `/admin/reports/${reportId}`;
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="DELETE">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        });
    };
});
</script>
<script const csrfToken = ' {{ csrf_token() }}';
<script src="{{ asset('assets/js/admin/report-admin.js') }}"></script>
@endpush