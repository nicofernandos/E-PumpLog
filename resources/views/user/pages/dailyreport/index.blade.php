@extends('user.layouts.app')

@section('title', 'Draft Laporan Pompa')

@push('styles')
<style>
    .table td {
        vertical-align: middle;
    }
    .badge-draft {
        background-color: #ffc107;
        color: #000;
    }
    .stats-card {
        border-left: 4px solid #ffc107;
        transition: transform 0.2s;
    }
    .stats-card:hover {
        transform: translateY(-5px);
    }
    .hourly-badge {
        font-size: 10px;
        padding: 2px 6px;
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <h3 class="page-title mb-2">Draft Laporan Harian</h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('user.dashboard.index') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Draft Laporan</li>
        </ol>
    </nav>
</div>

{{-- Statistics Cards --}}
<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Total Draft</h6>
                        <h3 class="mb-0 fw-bold text-warning">{{ $totalDraft ?? 0 }}</h3>
                        <small class="text-muted">Laporan Harian</small>
                    </div>
                    <div>
                        <i class="mdi mdi-file-document-edit-outline text-warning" style="font-size: 48px;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-3">
        <div class="card stats-card" style="border-left-color: #28a745;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Draft Hari Ini</h6>
                        <h3 class="mb-0 fw-bold text-success">{{ $draftToday ?? 0 }}</h3>
                        <small class="text-muted">{{ \Carbon\Carbon::now()->format('d/m/Y') }}</small>
                    </div>
                    <div>
                        <i class="mdi mdi-calendar-today text-success" style="font-size: 48px;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-3">
        <div class="card stats-card" style="border-left-color: #17a2b8;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Total Input Jam</h6>
                        <h3 class="mb-0 fw-bold text-info">{{ $totalHourlyEntries ?? 0 }}</h3>
                        <small class="text-muted">Entry Data</small>
                    </div>
                    <div>
                        <i class="mdi mdi-clock-outline text-info" style="font-size: 48px;"></i>
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
                        <h4 class="card-title mb-0">Daftar Draft Laporan Harian</h4>
                        <p class="card-description mb-0">Laporan harian injeksi pompa per jam yang masih draft</p>
                    </div>
                    <a href="{{ route('user.report.index') }}" class="btn btn-primary btn-sm">
                        <i class="mdi mdi-plus me-1"></i> Input Laporan Baru
                    </a>    
                </div>

                {{-- Filter Section --}}
                <div class="row mb-3">
                    <div class="col-md-4 mb-2">
                        <input type="text" class="form-control" id="searchReport" placeholder="Cari pompa, lokasi, injector well...">
                    </div>
                    <div class="col-md-4 mb-2">
                        <input type="date" class="form-control" id="filterDate" placeholder="Filter Tanggal">
                    </div>
                    <div class="col-md-4 mb-2">
                        <select class="form-select" id="filterLokasi">
                            <option value="">Semua Lokasi</option>
                            @foreach($lokasi ?? [] as $lok)
                                <option value="{{ $lok->id }}">{{ $lok->namasp }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Table --}}
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="10%">Tanggal</th>
                                <th width="12%">Kode Pompa</th>
                                <th width="18%">Lokasi SP</th>
                                <th width="12%">Injector Well</th>
                                <th width="10%">Entry Jam</th>
                                <th width="10%">Total BBLS</th>
                                <th width="8%" class="text-center">Status</th>
                                <th width="15%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($drafts ?? [] as $index => $draft)
                                <tr data-lokasi="{{ $draft->pompa->lokasi_id ?? '' }}"
                                    data-date="{{ $draft->tanggal }}">
                                    <td>{{ ($drafts->currentPage() - 1) * $drafts->perPage() + $index + 1 }}</td>
                                    <td>
                                        <strong>{{ \Carbon\Carbon::parse($draft->tanggal)->format('d/m/Y') }}</strong><br>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($draft->created_at)->format('H:i') }}</small>
                                    </td>
                                    <td>
                                        <span class="badge badge-info">{{ $draft->pompa->kodepompa ?? '-' }}</span>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $draft->pompa->lokasi->kodesp ?? '-' }}</small><br>
                                        {{ $draft->pompa->lokasi->namasp ?? '-' }}
                                    </td>
                                    <td>
                                        <strong>{{ $draft->injeksi_ke ?? '-' }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge badge-primary hourly-badge">
                                            {{ $draft->hourly_entries_count ?? 0 }} entry
                                        </span>
                                    </td>
                                    <td>
                                        <strong>{{ number_format($draft->total_cumulative ?? 0, 2) }}</strong>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-draft">Draft</span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('user.dailyreport.edit', $draft->id) }}" 
                                           class="btn btn-sm btn-warning btn-icon" 
                                           title="Lanjutkan Edit">
                                            <i class="mdi mdi-pencil"></i>
                                        </a>

                                        <button type="button" 
                                                class="btn btn-sm btn-success btn-icon" 
                                                title="Selesai & Submit" 
                                                onclick="confirmFinish('{{ $draft->id }}')">
                                            <i class="mdi mdi-check-circle"></i>
                                        </button>

                                        <button type="button" 
                                                class="btn btn-sm btn-danger btn-icon" 
                                                title="Hapus Draft" 
                                                onclick="confirmDelete('{{ $draft->id }}')">
                                            <i class="mdi mdi-delete"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">
                                        <div class="py-5">
                                            <i class="mdi mdi-file-document-edit-outline" style="font-size: 64px; color: #ccc;"></i>
                                            <h5 class="text-muted mt-3 mb-2">Belum Ada Draft Laporan</h5>
                                            <p class="text-muted">Mulai input laporan harian injeksi pompa per jam</p>
                                            <a href="{{ route('user.report.index') }}" class="btn btn-primary mt-2">
                                                <i class="mdi mdi-plus me-1"></i> Input Laporan Baru
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if(isset($drafts) && $drafts->total() > 0)
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <p class="mb-0 text-muted">
                        Menampilkan {{ $drafts->firstItem() }} - {{ $drafts->lastItem() }} dari {{ $drafts->total() }} draft
                    </p>
                    <nav aria-label="Page navigation">
                        {{ $drafts->links('pagination::bootstrap-4') }}
                    </nav>
                </div>
                @endif

                {{-- Info Box --}}
                <div class="alert alert-info mt-3">
                    <i class="mdi mdi-information-outline me-2"></i>
                    <strong>Catatan:</strong> Draft laporan akan tersimpan otomatis. Anda dapat melanjutkan input data kapan saja. 
                    Setelah selesai input semua data per jam, klik <strong>"Selesai & Submit"</strong> untuk mengirim laporan.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Search Function
    document.getElementById('searchReport').addEventListener('keyup', function() {
        filterTable();
    });

    // Filter by Date
    document.getElementById('filterDate').addEventListener('change', function() {
        filterTable();
    });

    // Filter by Lokasi
    document.getElementById('filterLokasi').addEventListener('change', function() {
        filterTable();
    });

    // Combined Filter Function
    function filterTable() {
        const searchValue = document.getElementById('searchReport').value.toLowerCase();
        const dateFilter = document.getElementById('filterDate').value;
        const lokasiFilter = document.getElementById('filterLokasi').value;
        const tableRows = document.querySelectorAll('tbody tr');
        
        let visibleCount = 0;
        
        tableRows.forEach(row => {
            if (row.querySelector('td[colspan]')) {
                row.style.display = visibleCount === 0 ? '' : 'none';
                return;
            }
            
            const kodepompa = row.querySelector('td:nth-child(3)').textContent.trim().toLowerCase();
            const lokasi = row.querySelector('td:nth-child(4)').textContent.trim().toLowerCase();
            const injector = row.querySelector('td:nth-child(5)').textContent.trim().toLowerCase();
            const lokasiId = row.getAttribute('data-lokasi');
            const date = row.getAttribute('data-date');

            const matchSearch = kodepompa.includes(searchValue) || 
                               lokasi.includes(searchValue) || 
                               injector.includes(searchValue);
            const matchDate = dateFilter === '' || date === dateFilter;
            const matchLokasi = lokasiFilter === '' || lokasiId === lokasiFilter;
            
            if (matchSearch && matchDate && matchLokasi) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });
    }

    // Finish and Submit Report
    function confirmFinish(draftId) {
        Swal.fire({
            title: 'Selesai Input Data?',
            html: `
                <p>Pastikan semua data per jam sudah lengkap.</p>
                <p class="text-muted">Laporan akan dikirim dan tidak dapat diubah lagi.</p>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Submit Laporan!',
            cancelButtonText: 'Cek Lagi'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.action = `/user/dailyreport/${draftId}/submit`;
                form.method = 'POST';
                form.style.display = 'none';

                const csrfInput = document.createElement('input');
                csrfInput.setAttribute('type', 'hidden');
                csrfInput.setAttribute('name', '_token');
                csrfInput.setAttribute('value', '{{ csrf_token() }}');
                form.appendChild(csrfInput);

                const methodInput = document.createElement('input');
                methodInput.setAttribute('type', 'hidden');
                methodInput.setAttribute('name', '_method');
                methodInput.setAttribute('value', 'PATCH');
                form.appendChild(methodInput);

                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    // Delete Draft
    function confirmDelete(draftId) {
        Swal.fire({
            title: 'Hapus Draft?',
            text: "Semua data input per jam akan hilang!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#ED4337',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.action = `/user/dailyreport/${draftId}`;
                form.method = 'POST';
                form.style.display = 'none';

                const csrfInput = document.createElement('input');
                csrfInput.setAttribute('type', 'hidden');
                csrfInput.setAttribute('name', '_token');
                csrfInput.setAttribute('value', '{{ csrf_token() }}');
                form.appendChild(csrfInput);

                const methodInput = document.createElement('input');
                methodInput.setAttribute('type', 'hidden');
                methodInput.setAttribute('name', '_method');
                methodInput.setAttribute('value', 'DELETE');
                form.appendChild(methodInput);

                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>
@endpush