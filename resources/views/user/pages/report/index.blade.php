{{-- View File: resources/views/user/laporan/create.blade.php --}}
@extends('user.layouts.app')

@section('title', 'Laporan Harian Injeksi Pompa')

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
    .table-flow-analysis {
        font-size: 13px;
    }
    .header-info {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
        border-left: 4px solid #667eea;
    }
    .time-row {
        background-color: #f8f9fa;
    }
    .time-row.active {
        background-color: #fff;
    }
    .time-row.locked {
        background-color: #e9ecef;
    }
    .locked-icon {
        color: #6c757d;
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <h3 class="page-title mb-2">Laporan Harian</h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Laporan Harian</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="card-title mb-1">Laporan Harian Injeksi Pompa</h4>
                        <p class="card-description mb-0">PT Pertamina EP Asset 2 Limau Field</p>
                    </div>
                    <div>
                        <img src="{{ asset('images/pertamina-logo.png') }}" alt="Pertamina Logo" style="height: 40px;" onerror="this.style.display='none'">
                    </div>
                </div>

                <form action="" method="POST" class="forms-sample">
                    @csrf

                    {{-- Header Information --}}
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="header-info">
                                <div class="form-group mb-2">
                                    <label class="form-label mb-1">Fasilitas SP</label>
                                    <select class="form-control @error('lokasi_id') is-invalid @enderror" name="lokasi_id" required>
                                        <option value="">-- Pilih Lokasi SP --</option>
                                        @foreach($lokasi ?? [] as $lok)
                                            <option value="{{ $lok->id }}" {{ old('lokasi_id') == $lok->id ? 'selected' : '' }}>
                                                {{ $lok->kodesp }} - {{ $lok->namasp }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('lokasi_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group mb-2">
                                    <label class="form-label mb-1">Pompa No</label>
                                    <select class="form-control @error('pompa_id') is-invalid @enderror" name="pompa_id" required>
                                        <option value="">-- Pilih Pompa --</option>
                                        @foreach($pompa ?? [] as $p)
                                            <option value="{{ $p->id }}" {{ old('pompa_id') == $p->id ? 'selected' : '' }}>
                                                {{ $p->kodepompa }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('pompa_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group mb-0">
                                    <label class="form-label mb-1">Jenis Pompa</label>
                                    <select class="form-control @error('jenis_pompa_id') is-invalid @enderror" name="jenis_pompa_id" required>
                                        <option value="">-- Pilih Jenis Pompa --</option>
                                        @foreach($pompa ?? [] as $p)
                                            <option value="{{ $p->id }}" {{ old('jenis_pompa_id') == $p->id ? 'selected' : '' }}>
                                                {{ $p->jenispompa }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('jenis_pompa_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="header-info">
                                <div class="form-group mb-2">
                                    <label class="form-label mb-1">Tanggal</label>
                                    <input type="date" class="form-control @error('tanggal') is-invalid @enderror" 
                                           id="tanggalInput"
                                           name="tanggal" 
                                           value="{{ old('tanggal', date('Y-m-d')) }}" 
                                           required>
                                    @error('tanggal')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group mb-0">
                                    <label class="form-label mb-1">Injektor Well</label>
                                    <input type="text" class="form-control @error('injector_well') is-invalid @enderror" 
                                           name="injector_well" 
                                           placeholder="Contoh: SP 02" 
                                           value="{{ old('injector_well') }}" 
                                           required>
                                    @error('injector_well')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="alert alert-info mt-3 mb-0 py-2">
                                    <small><i class="mdi mdi-information-outline me-1"></i>
                                    Waktu sekarang: <strong id="currentTime">{{ date('H:i') }}</strong> | 
                                    Data yang dapat diinput: <strong id="availableHours">0</strong> jam
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Flow Analysis Table Section --}}
                    <div class="section-title">
                        <i class="mdi mdi-chart-line me-2"></i>Report Flow Analyzer & Tekanan Engine Pompa
                    </div>

                    <div class="table-responsive mb-4">
                        <table class="table table-bordered table-flow-analysis">
                            <thead>
                                <tr>
                                    <th rowspan="2" style="width: 8%;">WAKTU</th>
                                    <th colspan="4">FLOW ANALYZER</th>
                                    <th colspan="4">TEKANAN ENGINE POMPA NO</th>
                                    <th rowspan="2" style="width: 8%;">WATER C/F</th>
                                    <th rowspan="2" style="width: 8%;">FREQ HZ</th>
                                </tr>
                                <tr>
                                    <th style="width: 7%;">TOTAL BBLS</th>
                                    <th style="width: 7%;">RATE/JAM BBLS</th>
                                    <th style="width: 7%;">CUMM BBLS</th>
                                    <th style="width: 7%;">RATE/HARI BBLS</th>
                                    <th style="width: 8%;">INJEKSI PSI</th>
                                    <th style="width: 7%;">RPM</th>
                                    <th style="width: 7%;">OIL C/F</th>
                                    <th style="width: 7%;">PRESS C/F</th>
                                </tr>
                            </thead>
                            <tbody id="flowTableBody">
                                @php
                                    $currentHour = (int)date('H');
                                    $selectedDate = old('tanggal', date('Y-m-d'));
                                    $isToday = $selectedDate === date('Y-m-d');
                                @endphp

                                @for($hour = 0; $hour < 24; $hour++)
                                    @php
                                        $timeString = sprintf('%02d:00', $hour);
                                        $isAvailable = !$isToday || $hour <= $currentHour;
                                        $rowClass = $isAvailable ? 'time-row active' : 'time-row locked';
                                    @endphp
                                    <tr class="{{ $rowClass }}" data-hour="{{ $hour }}">
                                        <td>
                                            <strong>{{ $timeString }}</strong>
                                            @if(!$isAvailable)
                                                <i class="mdi mdi-lock locked-icon ms-1"></i>
                                            @endif
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" class="form-control form-control-sm" 
                                                   name="flow[{{ $hour }}][total_bbls]" 
                                                   value="{{ old('flow.'.$hour.'.total_bbls') }}"
                                                   placeholder="BBLS"
                                                   {{ !$isAvailable ? 'readonly' : '' }}>
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" class="form-control form-control-sm" 
                                                   name="flow[{{ $hour }}][rate_jam]" 
                                                   value="{{ old('flow.'.$hour.'.rate_jam') }}"
                                                   placeholder="BBLS"
                                                   {{ !$isAvailable ? 'readonly' : '' }}>
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" class="form-control form-control-sm" 
                                                   name="flow[{{ $hour }}][cumm]" 
                                                   value="{{ old('flow.'.$hour.'.cumm') }}"
                                                   placeholder="BBLS"
                                                   {{ !$isAvailable ? 'readonly' : '' }}>
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" class="form-control form-control-sm" 
                                                   name="flow[{{ $hour }}][rate_hari]" 
                                                   value="{{ old('flow.'.$hour.'.rate_hari') }}"
                                                   placeholder="BBLS"
                                                   {{ !$isAvailable ? 'readonly' : '' }}>
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" class="form-control form-control-sm" 
                                                   name="flow[{{ $hour }}][injeksi_psi]" 
                                                   value="{{ old('flow.'.$hour.'.injeksi_psi') }}"
                                                   placeholder="PSI"
                                                   {{ !$isAvailable ? 'readonly' : '' }}>
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" class="form-control form-control-sm" 
                                                   name="flow[{{ $hour }}][rpm]" 
                                                   value="{{ old('flow.'.$hour.'.rpm') }}"
                                                   {{ !$isAvailable ? 'readonly' : '' }}>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control form-control-sm" 
                                                   name="flow[{{ $hour }}][oil_cf]" 
                                                   value="{{ old('flow.'.$hour.'.oil_cf') }}"
                                                   placeholder="C/F"
                                                   {{ !$isAvailable ? 'readonly' : '' }}>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control form-control-sm" 
                                                   name="flow[{{ $hour }}][press_cf]" 
                                                   value="{{ old('flow.'.$hour.'.press_cf') }}"
                                                   placeholder="C/F"
                                                   {{ !$isAvailable ? 'readonly' : '' }}>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control form-control-sm" 
                                                   name="flow[{{ $hour }}][water_cf]" 
                                                   value="{{ old('flow.'.$hour.'.water_cf') }}"
                                                   placeholder="C/F"
                                                   {{ !$isAvailable ? 'readonly' : '' }}>
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" class="form-control form-control-sm" 
                                                   name="flow[{{ $hour }}][freq_hz]" 
                                                   value="{{ old('flow.'.$hour.'.freq_hz') }}"
                                                   {{ !$isAvailable ? 'readonly' : '' }}>
                                        </td>
                                        <input type="hidden" name="flow[{{ $hour }}][waktu]" value="{{ $timeString }}">
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
                                <table class="table table-bordered table-keterangan">
                                    <thead>
                                        <tr>
                                            <th colspan="5" class="text-start bg-light">
                                                KETERANGAN : TOTAL COMULATIVE POMPA NO : ( <span class="ms-2">BBLS)</span>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th colspan="5" class="text-start bg-light">
                                                RUNNING HOURS ENGINGE GENSET : ( <span class="ms-2">JAM)</span>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th style="width: 15%;">Dari jam</th>
                                            <th style="width: 15%;">s/d jam</th>
                                            <th style="width: 40%;">KETERANGAN</th>
                                            <th style="width: 15%;"></th>
                                            <th style="width: 15%;">DT=-/jam</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for($i = 1; $i <= 6; $i++)
                                        <tr>
                                            <td>
                                                @if($i == 1)
                                                    <input type="time" class="form-control form-control-sm" 
                                                           name="keterangan[{{ $i }}][dari_jam]" 
                                                           value="{{ old('keterangan.'.$i.'.dari_jam') }}">
                                                @else
                                                    <input type="text" class="form-control form-control-sm text-center" 
                                                           name="keterangan[{{ $i }}][dari_jam]" 
                                                           value="{{ old('keterangan.'.$i.'.dari_jam', 's/d') }}"
                                                           readonly>
                                                @endif
                                            </td>
                                            <td>
                                                @if($i == 1)
                                                    <input type="time" class="form-control form-control-sm" 
                                                           name="keterangan[{{ $i }}][sd_jam]" 
                                                           value="{{ old('keterangan.'.$i.'.sd_jam') }}">
                                                @else
                                                    <input type="text" class="form-control form-control-sm text-center" 
                                                           name="keterangan[{{ $i }}][sd_jam]" 
                                                           value="{{ old('keterangan.'.$i.'.sd_jam', 's/d') }}"
                                                           readonly>
                                                @endif
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-control-sm" 
                                                       name="keterangan[{{ $i }}][keterangan]" 
                                                       value="{{ old('keterangan.'.$i.'.keterangan') }}"
                                                       placeholder="Masukkan keterangan...">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-control-sm" 
                                                       name="keterangan[{{ $i }}][col4]" 
                                                       value="{{ old('keterangan.'.$i.'.col4') }}">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-control-sm" 
                                                       name="keterangan[{{ $i }}][dt_jam]" 
                                                       value="{{ old('keterangan.'.$i.'.dt_jam') }}"
                                                       placeholder="DT=-/jam">
                                            </td>
                                        </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- Note Section --}}
                    <div class="alert alert-info mb-4">
                        <i class="mdi mdi-information-outline me-2"></i>
                        <strong>NB :</strong> Mohon laporan dibuat dengan sebaik baiknya, sesuai dengan ketentuan pembuatan laporan
                    </div>

                    {{-- Submit Buttons --}}
                    <div class="d-flex justify-content-between">
                        <a href="" class="btn btn-light">
                            <i class="mdi mdi-arrow-left me-1"></i> Kembali
                        </a>
                        <div>
                            <button type="button" onclick="window.location.href=''" class="btn btn-secondary me-2">
                                <i class="mdi mdi-close me-1"></i> Batal
                            </button>
                            <button type="submit" name="action" value="submit" class="btn btn-primary">
                                <i class="mdi mdi-send me-1"></i> Simpan Laporan
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
    document.addEventListener('DOMContentLoaded', function() {
        const tanggalInput = document.getElementById('tanggalInput');
        const currentTimeSpan = document.getElementById('currentTime');
        const availableHoursSpan = document.getElementById('availableHours');

        // Function to update available hours
        function updateAvailableRows() {
            const selectedDate = tanggalInput.value;
            const today = new Date().toISOString().split('T')[0];
            const isToday = selectedDate === today;
            const currentHour = new Date().getHours();

            let availableCount = 0;

            document.querySelectorAll('#flowTableBody tr').forEach((row, index) => {
                const hour = parseInt(row.dataset.hour);
                const inputs = row.querySelectorAll('input[type="number"], input[type="text"]:not([type="hidden"])');
                
                if (isToday && hour > currentHour) {
                    // Lock future hours for today
                    row.classList.remove('active');
                    row.classList.add('locked');
                    inputs.forEach(input => {
                        input.setAttribute('readonly', 'readonly');
                        input.style.backgroundColor = '#e9ecef';
                    });
                } else {
                    // Unlock available hours
                    row.classList.remove('locked');
                    row.classList.add('active');
                    inputs.forEach(input => {
                        input.removeAttribute('readonly');
                        input.style.backgroundColor = '';
                    });
                    availableCount++;
                }
            });

            availableHoursSpan.textContent = availableCount;
        }

        // Update current time display
        function updateCurrentTime() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            currentTimeSpan.textContent = `${hours}:${minutes}`;
        }

        // Event listener for date change
        tanggalInput.addEventListener('change', updateAvailableRows);

        // Update every minute
        updateCurrentTime();
        updateAvailableRows();
        
        setInterval(() => {
            updateCurrentTime();
            updateAvailableRows();
        }, 60000); // Update every minute

        // Auto format date
        if (!tanggalInput.value) {
            tanggalInput.value = new Date().toISOString().split('T')[0];
        }
    });
</script>
@endpush