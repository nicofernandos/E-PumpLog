@extends('admin.layouts.app')

@section('title', 'Manajemen Users - Create Users')

@push('styles')
<style>
/* Style untuk tombol toggle password */
.input-group .toggle-password {
    border: 1px solid #ced4da;
    border-left: none;
    background-color: #fff;
    padding: 0 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    min-width: 45px;
}

.input-group .toggle-password:hover {
    background-color: #f8f9fa;
}

.input-group .toggle-password:focus,
.input-group .toggle-password:active {
    outline: none !important;
    box-shadow: none !important;
    border-color: #ced4da;
}

/* Style untuk ikon mata */
.toggle-password i {
    font-size: 18px;
    color: #6c757d;
    line-height: 1;
    display: inline-block;
    transition: color 0.2s ease;
}

.toggle-password:hover i {
    color: #495057;
}

/* Pastikan input dan button memiliki tinggi yang sama */
.input-group .form-control {
    border-right: none;
}

.input-group .form-control:focus {
    border-color: #ced4da;
    box-shadow: none;
}

.input-group .form-control:focus + .toggle-password {
    border-color: #80bdff;
}

/* Fix untuk invalid feedback */
.input-group .invalid-feedback {
    width: 100%;
    margin-top: 0.25rem;
}
</style>
@endpush

@section('content')
<div class="page-header">
    <h3 class="page-title mb-2">Tambah Users</h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.users') }}">Users</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tambah User</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h4 class="card-title mb-0">Buat Pengguna Baru</h4>
                        <p class="card-description mb-0">Kelola data pengguna sistem E-PumpLog</p>
                    </div>
                </div>

                <form class="form-sample" method="POST" action="{{ route('admin.users.store') }}">
                    @csrf 
                    <p class="card-description">Informasi Akun</p>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="name" class="col-sm-3 col-form-label">Nama Lengkap</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" 
                                           required autocomplete="name" autofocus 
                                           placeholder="Masukkan nama lengkap"/>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="email" class="col-sm-3 col-form-label">Email</label>
                                <div class="col-sm-9">
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email') }}" 
                                           required autocomplete="email"
                                           placeholder="contoh@email.com"/>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="password" class="col-sm-3 col-form-label">Password</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <input type="password" 
                                               class="form-control @error('password') is-invalid @enderror" 
                                               id="password" 
                                               name="password" 
                                               required 
                                               autocomplete="new-password"
                                               placeholder="Minimal 8 karakter"/>
                                        <button class="btn toggle-password" type="button" data-target="password" tabindex="-1">
                                            <i class="ti-eye"></i>
                                        </button>
                                    </div>
                                    @error('password')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="password-confirm" class="col-sm-3 col-form-label">Konfirmasi Password</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <input type="password" 
                                               class="form-control" 
                                               id="password-confirm" 
                                               name="password_confirmation" 
                                               required 
                                               autocomplete="new-password"
                                               placeholder="Ulangi password"/>
                                        <button class="btn toggle-password" type="button" data-target="password-confirm" tabindex="-1">
                                            <i class="ti-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="role" class="col-sm-3 col-form-label">Level Akses (Role)</label>
                                <div class="col-sm-9">
                                    <select class="form-control @error('role') is-invalid @enderror" 
                                            id="role" name="role" required>
                                        <option value="">Pilih Role</option>
                                        <option value="manager" {{ old('role') == 'manager' ? 'selected' : '' }}>Manager</option>
                                        <option value="supervisor" {{ old('role') == 'supervisor' ? 'selected' : '' }}>Supervisor</option>
                                        <option value="operator" {{ old('role') == 'operator' ? 'selected' : '' }}>Operator</option>
                                    </select>
                                    @error('role')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Status User</label>
                                <div class="col-sm-9">
                                    <div class="form-check" style="padding-top: 8px;">
                                        <label class="form-check-label">
                                            <input type="checkbox" 
                                                   class="form-check-input" 
                                                   name="is_active" 
                                                   id="is_active" 
                                                   value="1" 
                                                   {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                                            Aktif (Dapat Login)
                                            <i class="input-helper"></i>
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">
                                        User yang aktif dapat melakukan login ke sistem
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <a href="{{ route('admin.users') }}" class="btn btn-light btn-lg me-2">
                            <i class="ti-arrow-left me-1"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="ti-save me-1"></i> Simpan User
                        </button>
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
    const toggleButtons = document.querySelectorAll('.toggle-password');
    
    toggleButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const targetId = this.getAttribute('data-target');
            const passwordInput = document.getElementById(targetId);
            const icon = this.querySelector('i');

            console.log('Button clicked:', targetId); // Debug
            console.log('Icon before:', icon.className); // Debug

            if (passwordInput && icon) {
                // Toggle tipe input
                const isPassword = passwordInput.getAttribute('type') === 'password';
                
                if (isPassword) {
                    passwordInput.setAttribute('type', 'text');
                    icon.className = 'ti-eye-off'; // Ganti seluruh class
                } else {
                    passwordInput.setAttribute('type', 'password');
                    icon.className = 'ti-eye'; // Ganti seluruh class
                }
                
                console.log('Icon after:', icon.className); // Debug
                console.log('Input type:', passwordInput.type); // Debug
            }
        });

        // Prevent button dari submit form
        button.addEventListener('mousedown', function(e) {
            e.preventDefault();
        });
    });

    // Validasi konfirmasi password
    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('password-confirm');
    
    if (confirmInput && passwordInput) {
        confirmInput.addEventListener('input', function() {
            if (this.value !== passwordInput.value) {
                this.setCustomValidity('Password tidak cocok');
            } else {
                this.setCustomValidity('');
            }
        });
        
        passwordInput.addEventListener('input', function() {
            if (confirmInput.value !== '') {
                confirmInput.dispatchEvent(new Event('input'));
            }
        });
    }
});
</script>
@endpush