@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row">
  <div class="col-sm-12">
    <div class="home-tab">
      <div class="d-sm-flex align-items-center justify-content-between border-bottom">
        <ul class="nav nav-tabs" role="tablist">
          <li class="nav-item">
            <a class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" 
               href="#overview" role="tab" aria-controls="overview" aria-selected="true">
               Overview
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="profile-tab" data-bs-toggle="tab" 
               href="#audiences" role="tab" aria-selected="false">Audiences</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="contact-tab" data-bs-toggle="tab" 
               href="#demographics" role="tab" aria-selected="false">Demographics</a>
          </li>
          <li class="nav-item">
            <a class="nav-link border-0" id="more-tab" data-bs-toggle="tab" 
               href="#more" role="tab" aria-selected="false">More</a>
          </li>
        </ul>
        <div>
          <div class="btn-wrapper">
            <a href="#" class="btn btn-otline-dark align-items-center">
                <i class="icon-share"></i> Share
            </a>
            <a href="#" class="btn btn-otline-dark">
                <i class="icon-printer"></i> Print
            </a>
            <a href="#" class="btn btn-primary text-white me-0">
                <i class="icon-download"></i> Export
            </a>
          </div>
        </div>
      </div>

      <div class="tab-content tab-content-basic">
        <div class="tab-pane fade show active" id="overview" role="tabpanel" 
             aria-labelledby="overview">

          {{-- ===== STATISTIK ATAS ===== --}}
          <div class="row">
            <div class="col-sm-12">
              <div class="statistics-details d-flex align-items-center justify-content-between">
                <div>
                  <p class="statistics-title">Total Laporan</p>
                  <h3 class="rate-percentage">{{ $totalLaporan }}</h3>
                  <p class="text-info d-flex">
                      <i class="mdi mdi-file-document"></i>
                      <span>Semua laporan</span>
                  </p>
                </div>
                <div>
                  <p class="statistics-title">Menunggu Approval</p>
                  <h3 class="rate-percentage">{{ $laporanPending }}</h3>
                  <p class="text-warning d-flex">
                      <i class="mdi mdi-clock-outline"></i>
                      <span>Pending</span>
                  </p>
                </div>
                <div>
                  <p class="statistics-title">Disetujui</p>
                  <h3 class="rate-percentage">{{ $laporanApproved }}</h3>
                  <p class="text-success d-flex">
                      <i class="mdi mdi-check-circle"></i>
                      <span>Approved</span>
                  </p>
                </div>
                <div class="d-none d-md-block">
                  <p class="statistics-title">Laporan Hari Ini</p>
                  <h3 class="rate-percentage">{{ $laporanHariIni }}</h3>
                  <p class="text-primary d-flex">
                      <i class="mdi mdi-calendar-today"></i>
                      <span>{{ now()->format('d/m/Y') }}</span>
                  </p>
                </div>
              </div>
            </div>
          </div>

          {{-- ===== CARD STATISTIK ===== --}}
          <div class="row mt-3 mb-3">
            <div class="col-md-3 mb-3">
              <div class="card border-left-primary">
                <div class="card-body py-3">
                  <div class="d-flex justify-content-between align-items-center">
                    <div>
                      <small class="text-muted">Total User</small>
                      <h4 class="mb-0 fw-bold">{{ $totalUser }}</h4>
                    </div>
                    <i class="mdi mdi-account-group text-primary" style="font-size:32px;"></i>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-3 mb-3">
              <div class="card border-left-success">
                <div class="card-body py-3">
                  <div class="d-flex justify-content-between align-items-center">
                    <div>
                      <small class="text-muted">Total Pompa</small>
                      <h4 class="mb-0 fw-bold">{{ $totalPompa }}</h4>
                    </div>
                    <i class="mdi mdi-engine text-success" style="font-size:32px;"></i>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-3 mb-3">
              <div class="card border-left-warning">
                <div class="card-body py-3">
                  <div class="d-flex justify-content-between align-items-center">
                    <div>
                      <small class="text-muted">Perlu Revisi</small>
                      <h4 class="mb-0 fw-bold text-warning">{{ $laporanRevisi }}</h4>
                    </div>
                    <i class="mdi mdi-pencil-circle text-warning" style="font-size:32px;"></i>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-3 mb-3">
              <div class="card border-left-danger">
                <div class="card-body py-3">
                  <div class="d-flex justify-content-between align-items-center">
                    <div>
                      <small class="text-muted">Ditolak</small>
                      <h4 class="mb-0 fw-bold text-danger">{{ $laporanRejected }}</h4>
                    </div>
                    <i class="mdi mdi-close-circle text-danger" style="font-size:32px;"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>

          {{-- ===== CHART & STATUS SUMMARY ===== --}}
          <div class="row">
            <div class="col-lg-8 d-flex flex-column">
              <div class="row flex-grow">
                <div class="col-12 grid-margin stretch-card">
                  <div class="card card-rounded">
                    <div class="card-body">
                      <div class="d-sm-flex justify-content-between align-items-start">
                        <div>
                          <h4 class="card-title card-title-dash">
                              Laporan 7 Hari Terakhir
                          </h4>
                          <h5 class="card-subtitle card-subtitle-dash">
                              Jumlah laporan masuk per hari
                          </h5>
                        </div>
                      </div>
                      <div class="chartjs-wrapper mt-5">
                        <canvas id="performaneLine"></canvas>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-lg-4 d-flex flex-column">
              <div class="row flex-grow">
                <div class="col-md-6 col-lg-12 grid-margin stretch-card">
                  <div class="card bg-primary card-rounded">
                    <div class="card-body pb-0">
                      <h4 class="card-title card-title-dash text-white mb-4">
                          Status Summary
                      </h4>
                      <div class="row">
                        <div class="col-sm-4">
                          <p class="status-summary-ight-white mb-1">Approved</p>
                          <h2 class="text-info">{{ $laporanApproved }}</h2>
                          <p class="status-summary-ight-white mb-1 mt-2">Pending</p>
                          <h2 class="text-warning">{{ $laporanPending }}</h2>
                        </div>
                        <div class="col-sm-8">
                          <div class="status-summary-chart-wrapper pb-4">
                            <canvas id="status-summary"></canvas>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          {{-- ===== TABEL LAPORAN TERBARU ===== --}}
          <div class="row mt-2">
            <div class="col-12">
              <div class="card card-rounded">
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title card-title-dash mb-0">Laporan Terbaru</h4>
                    <a href="{{ route('admin.reports.index') }}" 
                       class="btn btn-sm btn-outline-primary">
                        Lihat Semua
                    </a>
                  </div>
                  <div class="table-responsive">
                    <table class="table table-hover">
                      <thead>
                        <tr>
                          <th>Tanggal</th>
                          <th>User</th>
                          <th>Pompa</th>
                          <th>Lokasi SP</th>
                          <th>Status</th>
                          <th class="text-center">Aksi</th>
                        </tr>
                      </thead>
                      <tbody>
                        @forelse($recentLaporan as $laporan)
                        <tr>
                          <td>
                            {{ \Carbon\Carbon::parse($laporan->tanggal)->format('d/m/Y') }}
                            <small class="d-block text-muted">
                                {{ $laporan->created_at->diffForHumans() }}
                            </small>
                          </td>
                          <td>{{ $laporan->user->name ?? '-' }}</td>
                          <td>
                            <span class="badge bg-info text-white">
                                {{ $laporan->pompa->kodepompa ?? '-' }}
                            </span>
                          </td>
                          <td>{{ $laporan->pompa->lokasi->namasp ?? '-' }}</td>
                          <td>
                            @php
                              $statusMap = [
                                0 => ['label' => 'Draft',     'class' => 'bg-secondary'],
                                1 => ['label' => 'Pending',   'class' => 'bg-warning text-dark'],
                                2 => ['label' => 'Revisi',    'class' => 'bg-warning text-dark'],
                                3 => ['label' => 'Approved',  'class' => 'bg-success'],
                                4 => ['label' => 'Rejected',  'class' => 'bg-danger'],
                              ];
                              $st = $statusMap[$laporan->status_code]
                                    ?? ['label' => '-', 'class' => 'bg-secondary'];
                            @endphp
                            <span class="badge {{ $st['class'] }}">{{ $st['label'] }}</span>
                          </td>
                          <td class="text-center">
                            <a href="{{ route('admin.reports.show', $laporan->id) }}"
                               class="btn btn-sm btn-outline-info">
                                <i class="mdi mdi-eye"></i>
                            </a>
                          </td>
                        </tr>
                        @empty
                        <tr>
                          <td colspan="6" class="text-center text-muted py-4">
                            Belum ada laporan
                          </td>
                        </tr>
                        @endforelse
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
// Pastikan dashboard.js template tidak bentrok —
// jika dashboard.js sudah punya chart, nonaktifkan bagian itu dulu

// ── Chart Laporan 7 Hari Terakhir (Chart.js v2 syntax) ─────
(function() {
    var canvas = document.getElementById('performaneLine');
    if (!canvas) return;
    
    // Hancurkan chart sebelumnya jika ada (cegah konflik dashboard.js)
    if (window.chartLaporan) { window.chartLaporan.destroy(); }
    
    var ctx = canvas.getContext('2d');
    window.chartLaporan = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! $chartLabels !!},
            datasets: [{
                label: 'Jumlah Laporan',
                data: {!! $chartData !!},
                borderColor: '#667eea',
                backgroundColor: 'rgba(102,126,234,0.1)',
                borderWidth: 2,
                pointRadius: 4,
                lineTension: 0.4,   // v2: lineTension, bukan tension
                fill: true,
            }]
        },
        options: {
            responsive: true,
            legend: { display: false },           // v2: di luar plugins
            scales: {
                yAxes: [{                         // v2: yAxes array
                    ticks: { 
                        beginAtZero: true,
                        stepSize: 1
                    }
                }]
            }
        }
    });
})();

// ── Donut Status Summary (Chart.js v2 syntax) ──────────────
(function() {
    var canvas2 = document.getElementById('status-summary');
    if (!canvas2) return;
    
    if (window.chartStatus) { window.chartStatus.destroy(); }
    
    var ctx2 = canvas2.getContext('2d');
    window.chartStatus = new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: ['Approved', 'Pending', 'Revisi', 'Rejected', 'Draft'],
            datasets: [{
                data: [
                    {{ $laporanApproved }},
                    {{ $laporanPending }},
                    {{ $laporanRevisi }},
                    {{ $laporanRejected }},
                    {{ max(0, $totalLaporan - $laporanApproved - $laporanPending - $laporanRevisi - $laporanRejected) }}
                ],
                backgroundColor: ['#38ef7d','#fda085','#f6d365','#fa709a','#a8c0ff'],
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            legend: { display: false },           // v2: di luar plugins
            cutoutPercentage: 70,                 // v2: cutoutPercentage, bukan cutout
        }
    });
})();
</script>
@endpush