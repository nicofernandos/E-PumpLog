{{-- View File: resources/views/user/pages/report/edit.blade.php --}}
@extends('user.layouts.app')

@section('title', $title ?? 'Edit Draft Laporan')

@push('styles')
<style>
    .section-title {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 10px 15px;
        border-radius: 5px;
        margin-bottom: 20px;
        font-weight: 600;
    }
    .form-label {
        font-weight: 600;
        color: #333;
    }
    .table-flow-analysis th {
        background-color: #f8f9fa;
        font-weight: 600;
        text-align: center;
        vertical-align: middle;
        font-size: 12px;
        padding: 8px 4px;
    }
    .table-flow-analysis td {
        text-align: center;
        vertical-align: middle;
        padding: 6px 4px;
    }
    .table-flow-analysis input {
        text-align: center;
        font-size: 13px;
        padding: 4px;
    }
    .table-flow-analysis { font-size: 13px; }
    .header-info {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
        border-left: 4px solid #667eea;
    }
    .time-row { background-color: #f8f9fa; }
    .time-row.active { background-color: #fff; }
    .time-row.has-data { background-color: #f0fff4; }
    .time-row.locked { background-color: #e9ecef; }
    .locked-icon { color: #6c757d; }
    .has-data-icon { color: #28a745; }
</style>
@endpush

@section('content')
<div class="page-header">
    <h3 class="page-title mb-2">Edit Draft Laporan</h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('user.dashboard.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('user.dailyreport.index') }}">Draft Laporan</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Edit Draft</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="card-title mb-1">Edit Laporan Harian Injeksi Pompa</h4>
                        <p class="card-description mb-0">PT Pertamina EP Asset 2 Limau Field</p>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-warning text-dark px-3 py-2">
                            <i class="mdi mdi-file-edit me-1"></i> Draft
                        </span>
                        <img src="{{ asset('images/pertamina-logo.png') }}" 
                             alt="Pertamina Logo" style="height: 40px;" 
                             onerror="this.style.display='none'">
                    </div>
                </div>

                {{-- Alert --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="mdi mdi-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="mdi mdi-alert-circle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="mdi mdi-alert-circle me-2"></i>
                        <strong>Terdapat kesalahan:</strong>
                        <ul class="mb-0 mt-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ route('user.dailyreport.update', $laporan->id) }}" 
                      method="POST" class="forms-sample">
                    @csrf
                    @method('PUT')

                    {{-- Header Information --}}
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="header-info">
                                <div class="form-group mb-2">
                                    <label class="form-label mb-1">Fasilitas SP</label>
                                    <input type="text" class="form-control" 
                                           value="{{ $laporan->pompa->lokasi->kodesp ?? '-' }} - {{ $laporan->pompa->lokasi->namasp ?? '-' }}" 
                                           readonly>
                                    <input type="hidden" name="lokasi_id" value="{{ $laporan->pompa->lokasi_id ?? '' }}">
                                </div>

                                <div class="form-group mb-2">
                                    <label class="form-label mb-1">Pompa No</label>
                                    <input type="text" class="form-control" 
                                           value="{{ $laporan->pompa->kodepompa ?? '-' }}" 
                                           readonly>
                                    <input type="hidden" name="pompa_id" value="{{ $laporan->pompa_id }}">
                                </div>

                                <div class="form-group mb-0">
                                    <label class="form-label mb-1">Jenis Pompa</label>
                                    <input type="text" class="form-control" 
                                           value="{{ $laporan->pompa->jenispompa ?? '-' }}" 
                                           readonly>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="header-info">
                                <div class="form-group mb-2">
                                    <label class="form-label mb-1">Tanggal</label>
                                    <input type="text" class="form-control" 
                                           value="{{ \Carbon\Carbon::parse($laporan->tanggal)->format('d M Y') }}" 
                                           readonly>
                                    <input type="hidden" name="tanggal" value="{{ $laporan->tanggal }}">
                                </div>

                                <div class="form-group mb-0">
                                    <label class="form-label mb-1">Injektor Well</label>
                                    <input type="text" 
                                           class="form-control @error('injeksi_ke') is-invalid @enderror" 
                                           name="injeksi_ke" 
                                           placeholder="Contoh: SP 02" 
                                           value="{{ old('injeksi_ke', $laporan->injeksi_ke) }}" 
                                           required>
                                    @error('injeksi_ke')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="alert alert-warning mt-3 mb-0 py-2">
                                    <small>
                                        <i class="mdi mdi-information-outline me-1"></i>
                                        Waktu sekarang: <strong id="currentTime">{{ date('H:i') }}</strong> |
                                        Data terisi: <strong id="filledCount">0</strong> jam |
                                        Tersedia: <strong id="availableHours">0</strong> jam
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Flow Analysis Table --}}
                    <div class="section-title">
                        <i class="mdi mdi-chart-line me-2"></i>
                        Report Flow Analyzer & Tekanan Engine Pompa
                    </div>

                    <div class="table-responsive mb-4">
                        <table class="table table-bordered table-flow-analysis">
                            <thead>
                                <tr>
                                    <th rowspan="2" style="width: 8%;">WAKTU</th>
                                    <th colspan="4">FLOW ANALYZER</th>
                                    <th colspan="4">TEKANAN ENGINE POMPA NO</th>
                                    <th rowspan="2" style="width: 7%;">WATER C/F</th>
                                    <th rowspan="2" style="width: 7%;">FREQ HZ</th>
                                </tr>
                                <tr>
                                    <th style="width: 7%;">TOTAL BBLS</th>
                                    <th style="width: 7%;">RATE/JAM BBLS</th>
                                    <th style="width: 7%;">CUMM BBLS</th>
                                    <th style="width: 7%;">RATE/HARI BBLS</th>
                                    <th style="width: 7%;">INJEKSI PSI</th>
                                    <th style="width: 7%;">RPM</th>
                                    <th style="width: 7%;">OIL C/F</th>
                                    <th style="width: 7%;">PRESS C/F</th>
                                </tr>
                            </thead>
                            <tbody id="flowTableBody">
                                @php
                                    $currentHour = (int)date('H');
                                    $laporanDate = $laporan->tanggal;
                                    $isToday     = $laporanDate === date('Y-m-d');
                                @endphp

                                @for($hour = 0; $hour < 24; $hour++)
                                    @php
                                        $timeString  = sprintf('%02d:00', $hour);
                                        $isAvailable = !$isToday || $hour <= $currentHour;
                                        $hasData     = isset($flowData[$hour]) && 
                                                       !empty(array_filter($flowData[$hour]));

                                        if (!$isAvailable) {
                                            $rowClass = 'time-row locked';
                                        } elseif ($hasData) {
                                            $rowClass = 'time-row has-data';
                                        } else {
                                            $rowClass = 'time-row active';
                                        }

                                        $d = $flowData[$hour] ?? [];
                                    @endphp
                                    <tr class="{{ $rowClass }}" data-hour="{{ $hour }}">
                                        <td>
                                            <strong>{{ $timeString }}</strong>
                                            @if(!$isAvailable)
                                                <i class="mdi mdi-lock locked-icon ms-1" title="Belum waktunya"></i>
                                            @elseif($hasData)
                                                <i class="mdi mdi-check-circle has-data-icon ms-1" title="Data sudah diisi"></i>
                                            @endif
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" 
                                                   class="form-control form-control-sm" 
                                                   name="flow[{{ $hour }}][total_bbls]" 
                                                   value="{{ old('flow.'.$hour.'.total_bbls', $d['total_bbls'] ?? '') }}"
                                                   placeholder="BBLS"
                                                   {{ !$isAvailable ? 'readonly' : '' }}>
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" 
                                                   class="form-control form-control-sm" 
                                                   name="flow[{{ $hour }}][rate_jam]" 
                                                   value="{{ old('flow.'.$hour.'.rate_jam', $d['rate_jam'] ?? '') }}"
                                                   placeholder="BBLS"
                                                   {{ !$isAvailable ? 'readonly' : '' }}>
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" 
                                                   class="form-control form-control-sm" 
                                                   name="flow[{{ $hour }}][cumm]" 
                                                   value="{{ old('flow.'.$hour.'.cumm', $d['cumm'] ?? '') }}"
                                                   placeholder="BBLS"
                                                   {{ !$isAvailable ? 'readonly' : '' }}>
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" 
                                                   class="form-control form-control-sm" 
                                                   name="flow[{{ $hour }}][rate_hari]" 
                                                   value="{{ old('flow.'.$hour.'.rate_hari', $d['rate_hari'] ?? '') }}"
                                                   placeholder="BBLS"
                                                   {{ !$isAvailable ? 'readonly' : '' }}>
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" 
                                                   class="form-control form-control-sm" 
                                                   name="flow[{{ $hour }}][injeksi_psi]" 
                                                   value="{{ old('flow.'.$hour.'.injeksi_psi', $d['injeksi_psi'] ?? '') }}"
                                                   placeholder="PSI"
                                                   {{ !$isAvailable ? 'readonly' : '' }}>
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" 
                                                   class="form-control form-control-sm" 
                                                   name="flow[{{ $hour }}][rpm]" 
                                                   value="{{ old('flow.'.$hour.'.rpm', $d['rpm'] ?? '') }}"
                                                   {{ !$isAvailable ? 'readonly' : '' }}>
                                        </td>
                                        <td>
                                            <input type="text" 
                                                   class="form-control form-control-sm" 
                                                   name="flow[{{ $hour }}][oil_cf]" 
                                                   value="{{ old('flow.'.$hour.'.oil_cf', $d['oil_cf'] ?? '') }}"
                                                   placeholder="C/F"
                                                   {{ !$isAvailable ? 'readonly' : '' }}>
                                        </td>
                                        <td>
                                            <input type="text" 
                                                   class="form-control form-control-sm" 
                                                   name="flow[{{ $hour }}][press_cf]" 
                                                   value="{{ old('flow.'.$hour.'.press_cf', $d['press_cf'] ?? '') }}"
                                                   placeholder="C/F"
                                                   {{ !$isAvailable ? 'readonly' : '' }}>
                                        </td>
                                        <td>
                                            <input type="text" 
                                                   class="form-control form-control-sm" 
                                                   name="flow[{{ $hour }}][water_cf]" 
                                                   value="{{ old('flow.'.$hour.'.water_cf', $d['water_cf'] ?? '') }}"
                                                   placeholder="C/F"
                                                   {{ !$isAvailable ? 'readonly' : '' }}>
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" 
                                                   class="form-control form-control-sm" 
                                                   name="flow[{{ $hour }}][freq_hz]" 
                                                   value="{{ old('flow.'.$hour.'.freq_hz', $d['freq_hz'] ?? '') }}"
                                                   {{ !$isAvailable ? 'readonly' : '' }}>
                                        </td>
                                        <input type="hidden" name="flow[{{ $hour }}][waktu]" 
                                               value="{{ $timeString }}">
                                    </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>

                    {{-- Keterangan Section --}}
                    <div class="section-title">
                        <i class="mdi mdi-file-document me-2"></i>Keterangan
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th colspan="5" class="text-start bg-light">
                                                KETERANGAN : TOTAL CUMULATIVE POMPA NO :
                                                <strong>{{ $laporan->pompa->kodepompa ?? '-' }}</strong>
                                                ( <span class="ms-1">{{ number_format($laporan->total_cumulative ?? 0, 2) }} BBLS</span> )
                                            </th>
                                        </tr>
                                        <tr>
                                            <th style="width: 15%;">Dari Jam</th>
                                            <th style="width: 15%;">S/D Jam</th>
                                            <th style="width: 40%;">Keterangan</th>
                                            <th style="width: 15%;">Jumlah Jam</th>
                                            <th style="width: 15%;">Downtime (jam)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $runningHours = $laporan->runningHours ?? collect();
                                        @endphp
                                        @for($i = 0; $i < 6; $i++)
                                            @php $rh = $runningHours->get($i); @endphp
                                            <tr>
                                                <td>
                                                    <input type="time" 
                                                           class="form-control form-control-sm dari-jam" 
                                                           name="running_hours[{{ $i }}][dari_jam]"
                                                           data-index="{{ $i }}"
                                                           value="{{ old('running_hours.'.$i.'.dari_jam', $rh ? \Carbon\Carbon::parse($rh->dari_jam)->format('H:i') : '') }}">
                                                </td>
                                                <td>
                                                    <input type="time" 
                                                           class="form-control form-control-sm sd-jam" 
                                                           name="running_hours[{{ $i }}][sd_jam]"
                                                           data-index="{{ $i }}"
                                                           value="{{ old('running_hours.'.$i.'.sd_jam', $rh ? \Carbon\Carbon::parse($rh->sd_jam)->format('H:i') : '') }}">
                                                </td>
                                                <td>
                                                    <input type="text" 
                                                           class="form-control form-control-sm" 
                                                           name="running_hours[{{ $i }}][keterangan]"
                                                           placeholder="Keterangan..."
                                                           value="{{ old('running_hours.'.$i.'.keterangan', $rh->keterangan ?? '') }}">
                                                </td>
                                                <td>
                                                    <input type="text" 
                                                           class="form-control form-control-sm jumlah-jam" 
                                                           name="running_hours[{{ $i }}][jumlah_jam]"
                                                           id="jumlahJam{{ $i }}"
                                                           placeholder="0"
                                                           readonly
                                                           value="{{ old('running_hours.'.$i.'.jumlah_jam', $rh->jumlah_jam ?? '') }}">
                                                </td>
                                                <td>
                                                    <input type="number" step="0.01" 
                                                           class="form-control form-control-sm" 
                                                           name="running_hours[{{ $i }}][downtime_jam]"
                                                           placeholder="0"
                                                           value="{{ old('running_hours.'.$i.'.downtime_jam', $rh->downtime_jam ?? '') }}">
                                                </td>
                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- Note --}}
                    <div class="alert alert-info mb-4">
                        <i class="mdi mdi-information-outline me-2"></i>
                        <strong>NB:</strong> Mohon laporan dibuat dengan sebaik-baiknya sesuai ketentuan.
                        Baris berwarna <span class="badge" style="background:#f0fff4;color:#1a5c3a;border:1px solid #28a745;">hijau muda</span>
                        menandakan data sudah terisi.
                    </div>

                    {{-- Submit Buttons --}}
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('user.dailyreport.index') }}" class="btn btn-light">
                            <i class="mdi mdi-arrow-left me-1"></i> Kembali
                        </a>
                        <div class="d-flex gap-2">
                            {{-- Simpan Draft --}}
                            <button type="submit" name="action" value="draft" class="btn btn-warning">
                                <i class="mdi mdi-content-save me-1"></i> Simpan Draft
                            </button>
                            {{-- Submit Laporan --}}
                            <button type="submit" name="action" value="submit" class="btn btn-primary"
                                    onclick="return confirm('Submit laporan? Data tidak dapat diubah setelah disubmit.')">
                                <i class="mdi mdi-send me-1"></i> Submit Laporan
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Hitung jumlah jam otomatis ──────────────────────────────
    function hitungJumlahJam(index) {
        const dari = document.querySelector(`input[name="running_hours[${index}][dari_jam]"]`).value;
        const sd   = document.querySelector(`input[name="running_hours[${index}][sd_jam]"]`).value;
        const out  = document.getElementById(`jumlahJam${index}`);

        if (dari && sd) {
            const [dH, dM] = dari.split(':').map(Number);
            const [sH, sM] = sd.split(':').map(Number);
            let diff = (sH * 60 + sM) - (dH * 60 + dM);
            if (diff < 0) diff += 24 * 60; // lewat tengah malam
            out.value = (diff / 60).toFixed(2);
        } else {
            out.value = '';
        }
    }

    document.querySelectorAll('.dari-jam, .sd-jam').forEach(input => {
        input.addEventListener('change', function () {
            hitungJumlahJam(this.dataset.index);
        });
    });

    // Hitung semua saat load (data lama)
    for (let i = 0; i < 6; i++) hitungJumlahJam(i);

    // ── Update jam & lock/unlock baris tabel flow ───────────────
    const isToday = "{{ $laporanDate }}" === new Date().toISOString().split('T')[0];

    function updateUI() {
        const now         = new Date();
        const currentHour = now.getHours();
        document.getElementById('currentTime').textContent =
            String(now.getHours()).padStart(2,'0') + ':' + String(now.getMinutes()).padStart(2,'0');

        let available = 0, filled = 0;

        document.querySelectorAll('#flowTableBody tr').forEach(row => {
            const hour   = parseInt(row.dataset.hour);
            const inputs = row.querySelectorAll('input:not([type=hidden])');
            const locked = isToday && hour > currentHour;

            if (locked) {
                row.classList.remove('active','has-data');
                row.classList.add('locked');
                inputs.forEach(i => {
                    i.setAttribute('readonly','readonly');
                    i.style.backgroundColor = '#e9ecef';
                });
            } else {
                row.classList.remove('locked');
                available++;
                const anyFilled = [...inputs].some(i => i.value.trim() !== '');
                if (anyFilled) {
                    row.classList.add('has-data');
                    row.classList.remove('active');
                    filled++;
                } else {
                    row.classList.add('active');
                    row.classList.remove('has-data');
                }
            }
        });

        document.getElementById('availableHours').textContent = available;
        document.getElementById('filledCount').textContent    = filled;
    }

    updateUI();
    setInterval(updateUI, 60000);
});
</script>
@endpush