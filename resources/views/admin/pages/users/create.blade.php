@extends('admin.layouts.app')

@section('title', 'Manajemen Users - Create Users')

@section('content')
<div class="page-header">
    <h3 class="page-title mb-2">Tambah Users</h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Users</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h4 class="card-title mb-0">Data Users</h4>
                        <p class="card-description mb-0">Kelola data pengguna sistem E-PumpLog</p>
                    </div>
                </div>

            <div class="row">
                <div class="col-12 grid-margin">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Buat Pengguna Baru</h4>
                            <form class="form-sample" method="POST" action="">
                                @csrf 

                                <p class="card-description">
                                    Informasi Akun
                                </p>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label for="name" class="col-sm-3 col-form-label">Nama Lengkap</label>
                                            <div class="col-sm-9">
                                                {{-- Input Nama Lengkap --}}
                                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus />
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
                                                {{-- Input Email (harus unik untuk login) --}}
                                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autocomplete="email" />
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
                                                {{-- Input Password --}}
                                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required autocomplete="new-password"/>
                                                @error('password')
                                                    <span class="invalid-feedback" role="alert">
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
                                                {{-- Input Konfirmasi Password --}}
                                                <input type="password" class="form-control" id="password-confirm" name="password_confirmation" required autocomplete="new-password"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label for="role" class="col-sm-3 col-form-label">Level Akses (Role)</label>
                                            <div class="col-sm-9">
                                                {{-- Pilih Role/Level Akses --}}
                                                <select class="form-control @error('role') is-invalid @enderror" id="role" name="role" required>
                                                    <option value="">Pilih Role</option>
                                                    {{-- Ganti nilai di bawah dengan role yang sebenarnya ada di sistem Anda --}}
                                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                                    <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>Operator</option>
                                                    <option value="manager" {{ old('role') == 'manager' ? 'selected' : '' }}>Manager</option>
                                                </select>
                                                @error('role')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    {{-- Kolom Kosong/Tambahan Info --}}
                                    <div class="col-md-6">
                                        <p class="card-description">
                                            Status User
                                        </p>
                                        <div class="form-group row">
                                            <div class="col-sm-9 offset-sm-3">
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        <input type="checkbox" class="form-check-input" name="is_active" id="is_active" value="1" checked>
                                                        Aktif (Dapat Login)
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end mt-4">
                                    <button type="submit" class="btn btn-primary btn-lg mr-2">Simpan User</button>
                                    {{-- Tambahkan tombol batal jika perlu --}}
                                    <a href="{{ url('admin/users') }}" class="btn btn-light btn-lg">Batal</a>
                                </div>
                            </form>
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

</script>
@endpush