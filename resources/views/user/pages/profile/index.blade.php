@extends('user.layouts.app')

@section('title','Profile')

@push('styles')
<style>
    /* ===== PROFILE PAGE STYLES ===== */
    .profile-hero {
        background: linear-gradient(135deg, #0d47a1 0%, #1565c0 40%, #0288d1 100%);
        border-radius: 16px;
        padding: 2.5rem 2rem 4rem;
        position: relative;
        overflow: hidden;
        margin-bottom: 0;
    }

    .profile-hero::before {
        content: '';
        position: absolute;
        top: -60px;
        right: -60px;
        width: 220px;
        height: 220px;
        background: rgba(255, 255, 255, 0.07);
        border-radius: 50%;
    }

    .profile-hero::after {
        content: '';
        position: absolute;
        bottom: -80px;
        left: -40px;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 50%;
    }

    .profile-hero-text h4 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #ffffff;
        margin-bottom: 4px;
    }

    .profile-hero-text p {
        color: rgba(255, 255, 255, 0.75);
        font-size: 0.875rem;
        margin-bottom: 0;
    }

    .profile-badge {
        display: inline-block;
        background: rgba(255, 255, 255, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: #fff;
        font-size: 0.75rem;
        font-weight: 600;
        padding: 3px 12px;
        border-radius: 20px;
        letter-spacing: 0.5px;
        margin-top: 6px;
    }

    /* Avatar */
    .profile-avatar-wrapper {
        position: relative;
        display: inline-block;
    }

    .profile-avatar {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        border: 4px solid #fff;
        object-fit: cover;
        box-shadow: 0 4px 20px rgba(0,0,0,0.25);
    }

    .avatar-placeholder {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        border: 4px solid #fff;
        background: linear-gradient(135deg, #1976d2, #0288d1);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 20px rgba(0,0,0,0.25);
    }

    .avatar-placeholder span {
        font-size: 2rem;
        font-weight: 700;
        color: #fff;
        line-height: 1;
    }

    .avatar-online-dot {
        position: absolute;
        bottom: 5px;
        right: 5px;
        width: 14px;
        height: 14px;
        background: #4caf50;
        border: 2px solid #fff;
        border-radius: 50%;
    }

    /* Card style override */
    .profile-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 2px 20px rgba(0,0,0,0.07);
        margin-top: -2.5rem;
        position: relative;
        z-index: 10;
    }

    .profile-info-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 2px 20px rgba(0,0,0,0.07);
    }

    /* Info Item */
    .info-item {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        padding: 14px 0;
        border-bottom: 1px solid #f0f2f5;
    }

    .info-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .info-item:first-child {
        padding-top: 0;
    }

    .info-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 1rem;
    }

    .info-icon.blue   { background: #e3f2fd; color: #1565c0; }
    .info-icon.teal   { background: #e0f7f4; color: #00796b; }
    .info-icon.orange { background: #fff3e0; color: #e65100; }
    .info-icon.purple { background: #f3e5f5; color: #6a1b9a; }
    .info-icon.green  { background: #e8f5e9; color: #2e7d32; }
    .info-icon.red    { background: #fce4ec; color: #b71c1c; }

    .info-label {
        font-size: 0.72rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        color: #9aa0ac;
        margin-bottom: 2px;
    }

    .info-value {
        font-size: 0.92rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 0;
    }

    /* Stats cards */
    .stat-mini {
        background: #fff;
        border-radius: 12px;
        padding: 16px;
        text-align: center;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        transition: transform 0.2s;
    }

    .stat-mini:hover {
        transform: translateY(-3px);
    }

    .stat-mini .stat-num {
        font-size: 1.6rem;
        font-weight: 800;
        line-height: 1;
        margin-bottom: 4px;
    }

    .stat-mini .stat-label {
        font-size: 0.72rem;
        font-weight: 600;
        color: #9aa0ac;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Section title */
    .section-title {
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #9aa0ac;
        margin-bottom: 1.2rem;
        padding-bottom: 8px;
        border-bottom: 2px solid #f0f2f5;
    }

    /* Edit form styles */
    .form-label-custom {
        font-size: 0.8rem;
        font-weight: 600;
        color: #6c757d;
        margin-bottom: 5px;
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }

    .form-control-custom {
        border: 1.5px solid #e8edf2;
        border-radius: 10px;
        padding: 10px 14px;
        font-size: 0.9rem;
        color: #2c3e50;
        background: #fafbfc;
        transition: all 0.2s;
    }

    .form-control-custom:focus {
        border-color: #1565c0;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(21, 101, 192, 0.1);
    }

    .form-control-custom:disabled {
        background: #f5f7fa;
        color: #9aa0ac;
        cursor: not-allowed;
    }

    /* Btn custom */
    .btn-primary-custom {
        background: linear-gradient(135deg, #1565c0, #0288d1);
        border: none;
        color: #fff;
        border-radius: 10px;
        padding: 10px 28px;
        font-weight: 600;
        font-size: 0.88rem;
        letter-spacing: 0.3px;
        transition: all 0.25s;
        box-shadow: 0 4px 12px rgba(21, 101, 192, 0.3);
    }

    .btn-primary-custom:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(21, 101, 192, 0.4);
        color: #fff;
    }

    .btn-outline-custom {
        background: transparent;
        border: 1.5px solid #e8edf2;
        color: #6c757d;
        border-radius: 10px;
        padding: 10px 24px;
        font-weight: 600;
        font-size: 0.88rem;
        transition: all 0.2s;
    }

    .btn-outline-custom:hover {
        background: #f5f7fa;
        border-color: #cdd4dc;
        color: #2c3e50;
    }

    /* Password section */
    .password-strength {
        height: 4px;
        border-radius: 4px;
        background: #e8edf2;
        overflow: hidden;
        margin-top: 8px;
    }

    .password-strength-bar {
        height: 100%;
        border-radius: 4px;
        width: 0%;
        transition: width 0.4s, background 0.4s;
    }

    /* Tab nav custom */
    .nav-tabs-custom {
        border-bottom: 2px solid #f0f2f5;
        gap: 4px;
    }

    .nav-tabs-custom .nav-link {
        border: none;
        border-radius: 10px 10px 0 0;
        padding: 10px 20px;
        font-size: 0.85rem;
        font-weight: 600;
        color: #9aa0ac;
        background: transparent;
        transition: all 0.2s;
    }

    .nav-tabs-custom .nav-link:hover {
        color: #1565c0;
        background: #f0f6ff;
    }

    .nav-tabs-custom .nav-link.active {
        color: #1565c0;
        background: #fff;
        border-bottom: 2px solid #1565c0;
        margin-bottom: -2px;
    }

    .nav-tabs-custom .nav-link i {
        margin-right: 6px;
    }

    /* Alert custom */
    .alert-info-custom {
        background: linear-gradient(135deg, #e3f2fd, #e1f5fe);
        border: 1px solid #90caf9;
        border-radius: 12px;
        color: #1565c0;
        font-size: 0.85rem;
        padding: 12px 16px;
    }

    /* Responsive tweaks */
    @media (max-width: 576px) {
        .profile-hero {
            text-align: center;
        }
        .profile-hero .d-flex {
            flex-direction: column;
            align-items: center !important;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-3 px-md-4 py-4">

    {{-- Page Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1" style="color:#1a2340;">Profil Saya</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0" style="font-size:0.8rem;">
                    <li class="breadcrumb-item"><a href="{{ route('user.dashboard.index') }}" class="text-decoration-none" style="color:#1565c0;">Dashboard</a></li>
                    <li class="breadcrumb-item active text-muted">Profil Saya</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row g-4">

        {{-- ===== LEFT COLUMN ===== --}}
        <div class="col-12 col-lg-4">

            {{-- Profile Hero Card --}}
            <div class="profile-hero mb-0">
                <div class="d-flex align-items-center gap-3 position-relative" style="z-index:2;">
                    <div class="profile-avatar-wrapper">
                        {{-- Jika ada foto profil: --}}
                        {{-- <img src="{{ auth()->user()->avatar_url ?? asset('images/default-avatar.png') }}" class="profile-avatar" alt="Avatar"> --}}

                        {{-- Placeholder inisial --}}
                        <div class="avatar-placeholder">
                            <span>{{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}</span>
                        </div>
                        <div class="avatar-online-dot"></div>
                    </div>
                    <div class="profile-hero-text">
                        <h4>{{ auth()->user()->name ?? 'Nama Pengguna' }}</h4>
                        <p>{{ auth()->user()->email ?? 'email@example.com' }}</p>
                        <span class="profile-badge">
                            <i class="bi bi-shield-check me-1"></i>
                            {{ ucfirst(auth()->user()->role ?? 'Operator') }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Info Card (overlapping hero) --}}
            <div class="card profile-card p-4">

                {{-- Mini Stats --}}
                <div class="row g-3 mb-4">
                    <div class="col-4">
                        <div class="stat-mini">
                            <div class="stat-num" style="color:#1565c0;">{{ $totalLaporan ?? 0 }}</div>
                            <div class="stat-label">Laporan</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="stat-mini">
                            <div class="stat-num" style="color:#2e7d32;">{{ $laporanDisetujui ?? 0 }}</div>
                            <div class="stat-label">Disetujui</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="stat-mini">
                            <div class="stat-num" style="color:#e65100;">{{ $laporanDraft ?? 0 }}</div>
                            <div class="stat-label">Draft</div>
                        </div>
                    </div>
                </div>

                {{-- Info Detail --}}
                <p class="section-title mb-3">Informasi Akun</p>

                <div class="info-item">
                    <div class="info-icon blue"><i class="bi bi-person-fill"></i></div>
                    <div>
                        <div class="info-label">Nama Lengkap</div>
                        <div class="info-value">{{ auth()->user()->name ?? '-' }}</div>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-icon teal"><i class="bi bi-envelope-fill"></i></div>
                    <div>
                        <div class="info-label">Email</div>
                        <div class="info-value">{{ auth()->user()->email ?? '-' }}</div>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-icon orange"><i class="bi bi-building"></i></div>
                    <div>
                        <div class="info-label">Field / Lokasi</div>
                        <div class="info-value">{{ auth()->user()->field ?? 'Limau Field' }}</div>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-icon purple"><i class="bi bi-layers-fill"></i></div>
                    <div>
                        <div class="info-label">Jabatan / Role</div>
                        <div class="info-value">{{ ucfirst(auth()->user()->role ?? 'Operator') }}</div>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-icon green"><i class="bi bi-calendar3"></i></div>
                    <div>
                        <div class="info-label">Bergabung Sejak</div>
                        <div class="info-value">{{ auth()->user()->created_at ? auth()->user()->created_at->format('d M Y') : '-' }}</div>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-icon red"><i class="bi bi-clock-history"></i></div>
                    <div>
                        <div class="info-label">Login Terakhir</div>
                        <div class="info-value">{{ auth()->user()->last_login_at ? \Carbon\Carbon::parse(auth()->user()->last_login_at)->diffForHumans() : 'Sekarang' }}</div>
                    </div>
                </div>

            </div>
        </div>

        {{-- ===== RIGHT COLUMN ===== --}}
        <div class="col-12 col-lg-8">
            <div class="card profile-info-card">
                <div class="card-body p-4">

                    {{-- Tabs --}}
                    <ul class="nav nav-tabs-custom mb-4" id="profileTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="edit-tab" data-bs-toggle="tab" href="#edit" role="tab">
                                <i class="bi bi-person-gear"></i> Edit Profil
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="password-tab" data-bs-toggle="tab" href="#password" role="tab">
                                <i class="bi bi-shield-lock"></i> Ubah Password
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content" id="profileTabContent">

                        {{-- ===== TAB: EDIT PROFIL ===== --}}
                        <div class="tab-pane fade show active" id="edit" role="tabpanel">

                            @if(session('success'))
                            <div class="alert alert-info-custom mb-4">
                                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                            </div>
                            @endif

                            @if($errors->any())
                            <div class="alert alert-danger border-0 rounded-3 mb-4" style="font-size:0.85rem;">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                {{ $errors->first() }}
                            </div>
                            @endif

                            <form action="{{ route('user.profile.update') }}" method="POST">
                                @csrf
                                @method('PUT')

                                <p class="section-title">Data Pribadi</p>

                                <div class="row g-3 mb-4">
                                    <div class="col-12 col-md-6">
                                        <label class="form-label-custom">Nama Lengkap <span class="text-danger">*</span></label>
                                        <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}"
                                            class="form-control form-control-custom @error('name') is-invalid @enderror"
                                            placeholder="Masukkan nama lengkap">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label class="form-label-custom">Email <span class="text-danger">*</span></label>
                                        <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}"
                                            class="form-control form-control-custom @error('email') is-invalid @enderror"
                                            placeholder="email@pertamina.com">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label class="form-label-custom">Field / Lokasi</label>
                                        <input type="text" value="{{ auth()->user()->field ?? 'Limau Field' }}"
                                            class="form-control form-control-custom" disabled>
                                        <small class="text-muted" style="font-size:0.75rem;">
                                            <i class="bi bi-info-circle me-1"></i>Hubungi admin untuk mengubah field.
                                        </small>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label class="form-label-custom">Role / Jabatan</label>
                                        <input type="text" value="{{ ucfirst(auth()->user()->role ?? 'Operator') }}"
                                            class="form-control form-control-custom" disabled>
                                        <small class="text-muted" style="font-size:0.75rem;">
                                            <i class="bi bi-info-circle me-1"></i>Tidak dapat diubah sendiri.
                                        </small>
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label-custom">Nomor Telepon</label>
                                        <div class="input-group">
                                            <span class="input-group-text" style="border-radius:10px 0 0 10px; border:1.5px solid #e8edf2; background:#fafbfc; color:#6c757d; font-size:0.85rem;">+62</span>
                                            <input type="tel" name="phone" value="{{ old('phone', auth()->user()->phone ?? '') }}"
                                                class="form-control form-control-custom @error('phone') is-invalid @enderror"
                                                style="border-radius:0 10px 10px 0;"
                                                placeholder="8xx xxxx xxxx">
                                        </div>
                                        @error('phone')
                                            <div class="text-danger mt-1" style="font-size:0.8rem;">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="d-flex gap-2 justify-content-end">
                                    <button type="reset" class="btn btn-outline-custom">
                                        <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                                    </button>
                                    <button type="submit" class="btn btn-primary-custom">
                                        <i class="bi bi-check2-circle me-1"></i> Simpan Perubahan
                                    </button>
                                </div>
                            </form>
                        </div>

                        {{-- ===== TAB: UBAH PASSWORD ===== --}}
                        <div class="tab-pane fade" id="password" role="tabpanel">

                            @if(session('password_success'))
                            <div class="alert alert-info-custom mb-4">
                                <i class="bi bi-check-circle-fill me-2"></i>{{ session('password_success') }}
                            </div>
                            @endif

                            <div class="alert-info-custom mb-4">
                                <i class="bi bi-shield-check me-2"></i>
                                <strong>Tips Keamanan:</strong> Gunakan minimal 8 karakter, kombinasi huruf besar, kecil, angka, dan simbol.
                            </div>

                            <form action="{{ route('user.profile.updatePassword') }}" method="POST">
                                @csrf
                                @method('PUT')

                                <p class="section-title">Keamanan Akun</p>

                                <div class="row g-3 mb-4">
                                    <div class="col-12">
                                        <label class="form-label-custom">Password Saat Ini <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="password" name="current_password" id="currentPassword"
                                                class="form-control form-control-custom @error('current_password') is-invalid @enderror"
                                                style="border-radius:10px 0 0 10px;"
                                                placeholder="Masukkan password saat ini">
                                            <button class="btn" type="button" id="toggleCurrent"
                                                style="border:1.5px solid #e8edf2; border-left:none; border-radius:0 10px 10px 0; background:#fafbfc; color:#9aa0ac;">
                                                <i class="bi bi-eye" id="iconCurrent"></i>
                                            </button>
                                        </div>
                                        @error('current_password')
                                            <div class="text-danger mt-1" style="font-size:0.8rem;">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label class="form-label-custom">Password Baru <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="password" name="password" id="newPassword"
                                                class="form-control form-control-custom @error('password') is-invalid @enderror"
                                                style="border-radius:10px 0 0 10px;"
                                                placeholder="Min. 8 karakter">
                                            <button class="btn" type="button" id="toggleNew"
                                                style="border:1.5px solid #e8edf2; border-left:none; border-radius:0 10px 10px 0; background:#fafbfc; color:#9aa0ac;">
                                                <i class="bi bi-eye" id="iconNew"></i>
                                            </button>
                                        </div>
                                        <div class="password-strength mt-2">
                                            <div class="password-strength-bar" id="strengthBar"></div>
                                        </div>
                                        <small id="strengthLabel" class="text-muted" style="font-size:0.75rem;"></small>
                                        @error('password')
                                            <div class="text-danger mt-1" style="font-size:0.8rem;">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label class="form-label-custom">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="password" name="password_confirmation" id="confirmPassword"
                                                class="form-control form-control-custom"
                                                style="border-radius:10px 0 0 10px;"
                                                placeholder="Ulangi password baru">
                                            <button class="btn" type="button" id="toggleConfirm"
                                                style="border:1.5px solid #e8edf2; border-left:none; border-radius:0 10px 10px 0; background:#fafbfc; color:#9aa0ac;">
                                                <i class="bi bi-eye" id="iconConfirm"></i>
                                            </button>
                                        </div>
                                        <div id="matchIndicator" class="mt-1" style="font-size:0.75rem;"></div>
                                    </div>
                                </div>

                                <div class="d-flex gap-2 justify-content-end">
                                    <button type="reset" class="btn btn-outline-custom">
                                        <i class="bi bi-x-circle me-1"></i> Batal
                                    </button>
                                    <button type="submit" class="btn btn-primary-custom">
                                        <i class="bi bi-lock-fill me-1"></i> Ubah Password
                                    </button>
                                </div>
                            </form>
                        </div>

                    </div>{{-- end tab-content --}}
                </div>
            </div>
        </div>

    </div>{{-- end row --}}
</div>
@endsection


@push('scripts')
<script>
    // ===== TOGGLE PASSWORD VISIBILITY =====
    function togglePassword(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon  = document.getElementById(iconId);
        if (!input) return;
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('bi-eye', 'bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('bi-eye-slash', 'bi-eye');
        }
    }

    document.getElementById('toggleCurrent')?.addEventListener('click', () => togglePassword('currentPassword', 'iconCurrent'));
    document.getElementById('toggleNew')?.addEventListener('click',     () => togglePassword('newPassword', 'iconNew'));
    document.getElementById('toggleConfirm')?.addEventListener('click', () => togglePassword('confirmPassword', 'iconConfirm'));

    // ===== PASSWORD STRENGTH METER =====
    const newPasswordInput = document.getElementById('newPassword');
    const strengthBar      = document.getElementById('strengthBar');
    const strengthLabel    = document.getElementById('strengthLabel');

    if (newPasswordInput) {
        newPasswordInput.addEventListener('input', function () {
            const val   = this.value;
            let score   = 0;
            if (val.length >= 8)              score++;
            if (/[A-Z]/.test(val))            score++;
            if (/[0-9]/.test(val))            score++;
            if (/[^A-Za-z0-9]/.test(val))     score++;

            const levels = [
                { width: '0%',   color: '#e8edf2', label: '' },
                { width: '25%',  color: '#e53935', label: '🔴 Sangat lemah' },
                { width: '50%',  color: '#fb8c00', label: '🟠 Lemah' },
                { width: '75%',  color: '#fdd835', label: '🟡 Cukup kuat' },
                { width: '100%', color: '#43a047', label: '🟢 Kuat' },
            ];

            const lvl = levels[score] || levels[0];
            strengthBar.style.width    = lvl.width;
            strengthBar.style.background = lvl.color;
            strengthLabel.textContent  = lvl.label;
        });
    }

    // ===== PASSWORD MATCH INDICATOR =====
    const confirmInput    = document.getElementById('confirmPassword');
    const matchIndicator  = document.getElementById('matchIndicator');

    if (confirmInput) {
        confirmInput.addEventListener('input', function () {
            if (this.value === '') {
                matchIndicator.textContent = '';
                return;
            }
            if (this.value === newPasswordInput.value) {
                matchIndicator.innerHTML = '<span style="color:#43a047;"><i class="bi bi-check-circle-fill me-1"></i>Password cocok</span>';
            } else {
                matchIndicator.innerHTML = '<span style="color:#e53935;"><i class="bi bi-x-circle-fill me-1"></i>Password tidak cocok</span>';
            }
        });
    }
</script>
@endpush