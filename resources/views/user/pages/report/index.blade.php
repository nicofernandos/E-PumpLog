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
    .btn-add-row {
        margin-top: 10px;
    }
    .header-info {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
        border-left: 4px solid #667eea;
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <h3 class="page-title mb-2">Laporan Harian</h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page"> Laporan Harian</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="card-title mb-1">Laporan Harian Injeksi Pompa Injeksi</h4>
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

                                <div class="form-group mb-0">
                                    <label class="form-label mb-1">Pompa No</label>
                                    <select class="form-control @error('pompa_id') is-invalid @enderror" name="pompa_id" required>
                                        <option value="">-- Pilih Pompa --</option>
                                        @foreach($pompa ?? [] as $p)
                                            <option value="{{ $p->id }}" {{ old('pompa_id') == $p->id ? 'selected' : '' }}>
                                                {{ $p->kodepompa }} - {{ $p->jenispompa }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('pompa_id')
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
                                           placeholder="Contoh: IKU 02" 
                                           value="{{ old('injector_well') }}" 
                                           required>
                                    @error('injector_well')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Flow Analysis Table Section --}}
                    <div class="section-title">
                        <i class="mdi mdi-chart-line me-2"></i>Flow Analysis
                    </div>

                    <div class="table-responsive mb-4">
                        <table class="table table-bordered table-flow-analysis">
                            <thead>
                                <tr>
                                    <th rowspan="2" style="width: 8%;">Waktu</th>
                                    <th colspan="4">Total</th>
                                    <th rowspan="2" style="width: 10%;">Keterangan BBM</th>
                                    <th colspan="4">Tekanan Engine Pompa No</th>
                                    <th rowspan="2" style="width: 8%;">Water Cut</th>
                                    <th rowspan="2" style="width: 8%;">Freq Hz</th>
                                </tr>
                                <tr>
                                    <th style="width: 7%;">INJEKSI</th>
                                    <th style="width: 7%;">RPM</th>
                                    <th style="width: 7%;">CUMU</th>
                                    <th style="width: 7%;">RATE</th>
                                    <th style="width: 8%;">Injeksi (PSI)</th>
                                    <th style="width: 7%;">RPM</th>
                                    <th style="width: 7%;">Oil (PSI)</th>
                                    <th style="width: 7%;">Press (PSI)</th>
                                </tr>
                            </thead>
                            <tbody id="flowTableBody">
                                @for($i = 1; $i <= 5; $i++)
                                <tr>
                                    <td>
                                        <input type="time" class="form-control form-control-sm" 
                                               name="flow[{{ $i }}][waktu]" 
                                               value="{{ old('flow.'.$i.'.waktu') }}">
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" class="form-control form-control-sm" 
                                               name="flow[{{ $i }}][total_injeksi]" 
                                               value="{{ old('flow.'.$i.'.total_injeksi') }}">
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" class="form-control form-control-sm" 
                                               name="flow[{{ $i }}][rpm]" 
                                               value="{{ old('flow.'.$i.'.rpm') }}">
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" class="form-control form-control-sm" 
                                               name="flow[{{ $i }}][cumu]" 
                                               value="{{ old('flow.'.$i.'.cumu') }}">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm" 
                                               name="flow[{{ $i }}][rate]" 
                                               value="{{ old('flow.'.$i.'.rate') }}">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm" 
                                               name="flow[{{ $i }}][keterangan_bbm]" 
                                               value="{{ old('flow.'.$i.'.keterangan_bbm') }}">
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" class="form-control form-control-sm" 
                                               name="flow[{{ $i }}][injeksi_psi]" 
                                               value="{{ old('flow.'.$i.'.injeksi_psi') }}">
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" class="form-control form-control-sm" 
                                               name="flow[{{ $i }}][engine_rpm]" 
                                               value="{{ old('flow.'.$i.'.engine_rpm') }}">
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" class="form-control form-control-sm" 
                                               name="flow[{{ $i }}][oil_psi]" 
                                               value="{{ old('flow.'.$i.'.oil_psi') }}">
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" class="form-control form-control-sm" 
                                               name="flow[{{ $i }}][press_psi]" 
                                               value="{{ old('flow.'.$i.'.press_psi') }}">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm" 
                                               name="flow[{{ $i }}][water_cut]" 
                                               value="{{ old('flow.'.$i.'.water_cut') }}">
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" class="form-control form-control-sm" 
                                               name="flow[{{ $i }}][freq_hz]" 
                                               value="{{ old('flow.'.$i.'.freq_hz') }}">
                                    </td>
                                </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>

                    {{-- Summary Section --}}
                    <div class="section-title">
                        <i class="mdi mdi-file-document me-2"></i>Keterangan
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Total Comulative Pompa No : (BBLS)</label>
                                <input type="number" step="0.01" class="form-control @error('total_comulative') is-invalid @enderror" 
                                       name="total_comulative" 
                                       placeholder="0.00" 
                                       value="{{ old('total_comulative') }}">
                                @error('total_comulative')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">Running Hours Engine Genset : (Jam)</label>
                                <input type="number" step="0.01" class="form-control @error('running_hours') is-invalid @enderror" 
                                       name="running_hours" 
                                       placeholder="0.00" 
                                       value="{{ old('running_hours') }}">
                                @error('running_hours')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Pemakaian BBM (Liter)</label>
                                <div class="row">
                                    <div class="col-6">
                                        <input type="number" step="0.01" class="form-control @error('bbm_b30') is-invalid @enderror" 
                                               name="bbm_b30" 
                                               placeholder="B/C" 
                                               value="{{ old('bbm_b30') }}">
                                        <small class="text-muted">B/C</small>
                                        @error('bbm_b30')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-6">
                                        <input type="number" step="0.01" class="form-control @error('bbm_b35') is-invalid @enderror" 
                                               name="bbm_b35" 
                                               placeholder="B/D" 
                                               value="{{ old('bbm_b35') }}">
                                        <small class="text-muted">B/D</small>
                                        @error('bbm_b35')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Keterangan Tambahan</label>
                                <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                                          name="keterangan" 
                                          rows="3" 
                                          placeholder="Catatan tambahan mengenai operasional pompa...">{{ old('keterangan') }}</textarea>
                                @error('keterangan')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Note Section --}}
                    <div class="alert alert-info mb-4">
                        <i class="mdi mdi-information-outline me-2"></i>
                        <strong>NB:</strong> Mohon laporan dibuiat dengan sebaik baiknya, sesuai dengan ketentuan pembuatan laporan
                    </div>

                    {{-- Submit Buttons --}}
                    <div class="d-flex justify-content-between">
                        <a href="" class="btn btn-light">
                            <i class="mdi mdi-arrow-left me-1"></i> Kembali
                        </a>
                        <div>
                            <button type="submit" name="action" value="draft" class="btn btn-secondary me-2">
                                <i class="mdi mdi-content-save me-1"></i> Batal
                            </button>
                            <button type="submit" name="action" value="submit" class="btn btn-primary">
                                <i class="mdi mdi-send me-1"></i> Simpan Draft Laporan
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
        // Auto format date
        const dateInput = document.querySelector('input[name="tanggal"]');
        if (dateInput && !dateInput.value) {
            dateInput.value = new Date().toISOString().split('T')[0];
        }
    });
</script>
@endpush