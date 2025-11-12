@extends('admin.layouts.app')

@section('title', 'Users')

@section('content')
<div class="page-header">
    <h3 class="page-title mb-2">Daftar Users</h3>
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
                    {{-- TOMBOL TAMBAH USER DIHAPUS DARI SINI --}}
                </div>

                {{-- Filter Section --}}
                <div class="row mb-3">
                    <div class="col-md-3">
                        <select class="form-select" id="filterRole">
                            <option value="">Semua Role</option>
                            <option value="Admin">Admin</option>
                            <option value="Supervisor">Supervisor</option>
                            <option value="Operator">Operator</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="filterStatus">
                            <option value="">Semua Status</option>
                            <option value="Active">Aktif</option>
                            <option value="Inactive">Nonaktif</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <input type="text" class="form-control" id="searchUser" placeholder="Cari nama, email, atau username...">
                    </div>
                </div>

                {{-- Table --}}
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="20%">Nama</th>
                                <th width="20%">Email</th>
                                <th width="15%">Username</th>
                                <th width="12%">Role</th>
                                <th width="12%">Status</th>
                                <th width="16%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Example Data - Replace with @foreach($users as $user) --}}
                            <tr>
                                <td>1</td>
                                <td>
                                    <img src="{{ asset('images/faces/face1.jpg') }}" class="me-2" alt="image" style="width: 30px; height: 30px; border-radius: 50%;">
                                    John Doe
                                </td>
                                <td>john.doe@epumplog.com</td>
                                <td>johndoe</td>
                                <td><label class="badge badge-danger">Admin</label></td>
                                <td><label class="badge badge-success">Aktif</label></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-info btn-icon" title="Detail">
                                        <i class="ti-eye"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-warning btn-icon" title="Edit" data-bs-toggle="modal" data-bs-target="#editUserModal">
                                        <i class="ti-pencil"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger btn-icon" title="Hapus" onclick="confirmDelete(1)">
                                        <i class="ti-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>
                                    <img src="{{ asset('images/faces/face2.jpg') }}" class="me-2" alt="image" style="width: 30px; height: 30px; border-radius: 50%;">
                                    Sarah Smith
                                </td>
                                <td>sarah.smith@epumplog.com</td>
                                <td>sarahsmith</td>
                                <td><label class="badge badge-primary">Supervisor</label></td>
                                <td><label class="badge badge-success">Aktif</label></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-info btn-icon" title="Detail">
                                        <i class="ti-eye"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-warning btn-icon" title="Edit">
                                        <i class="ti-pencil"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger btn-icon" title="Hapus" onclick="confirmDelete(2)">
                                        <i class="ti-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>
                                    <img src="{{ asset('images/faces/face3.jpg') }}" class="me-2" alt="image" style="width: 30px; height: 30px; border-radius: 50%;">
                                    Mike Johnson
                                </td>
                                <td>mike.johnson@epumplog.com</td>
                                <td>mikejohnson</td>
                                <td><label class="badge badge-info">Operator</label></td>
                                <td><label class="badge badge-success">Aktif</label></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-info btn-icon" title="Detail">
                                        <i class="ti-eye"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-warning btn-icon" title="Edit">
                                        <i class="ti-pencil"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger btn-icon" title="Hapus" onclick="confirmDelete(3)">
                                        <i class="ti-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>
                                    <img src="{{ asset('images/faces/face4.jpg') }}" class="me-2" alt="image" style="width: 30px; height: 30px; border-radius: 50%;">
                                    Emily Brown
                                </td>
                                <td>emily.brown@epumplog.com</td>
                                <td>emilybrown</td>
                                <td><label class="badge badge-info">Operator</label></td>
                                <td><label class="badge badge-warning">Nonaktif</label></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-info btn-icon" title="Detail">
                                        <i class="ti-eye"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-warning btn-icon" title="Edit">
                                        <i class="ti-pencil"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger btn-icon" title="Hapus" onclick="confirmDelete(4)">
                                        <i class="ti-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>
                                    <img src="{{ asset('images/faces/face5.jpg') }}" class="me-2" alt="image" style="width: 30px; height: 30px; border-radius: 50%;">
                                    David Wilson
                                </td>
                                <td>david.wilson@epumplog.com</td>
                                <td>davidwilson</td>
                                <td><label class="badge badge-primary">Supervisor</label></td>
                                <td><label class="badge badge-success">Aktif</label></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-info btn-icon" title="Detail">
                                        <i class="ti-eye"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-warning btn-icon" title="Edit">
                                        <i class="ti-pencil"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger btn-icon" title="Hapus" onclick="confirmDelete(5)">
                                        <i class="ti-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <p class="mb-0 text-muted">Menampilkan 1 - 5 dari 5 data</p>
                    <nav aria-label="Page navigation">
                        <ul class="pagination mb-0">
                            <li class="page-item disabled">
                                <a class="page-link" href="#" tabindex="-1">Previous</a>
                            </li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item">
                                <a class="page-link" href="#">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL ADD USER DIHAPUS DARI SINI --}}

{{-- Modal Edit User (Tetap dipertahankan) --}}
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="editUserModalLabel">
                    <i class="ti-pencil me-2"></i>Edit User
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editUserForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" value="John Doe" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Username <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="username" value="johndoe" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="email" value="john.doe@epumplog.com" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">No. Telepon</label>
                            <input type="text" class="form-control" name="phone" value="08123456789">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Password Baru</label>
                            <input type="password" class="form-control" name="password" placeholder="Kosongkan jika tidak ingin mengubah">
                            <small class="text-muted">Minimal 8 karakter</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Konfirmasi Password</label>
                            <input type="password" class="form-control" name="password_confirmation" placeholder="Ulangi password baru">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Role <span class="text-danger">*</span></label>
                            <select class="form-select" name="role" required>
                                <option value="Admin" selected>Admin</option>
                                <option value="Supervisor">Supervisor</option>
                                <option value="Operator">Operator</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select" name="status" required>
                                <option value="Active" selected>Aktif</option>
                                <option value="Inactive">Nonaktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea class="form-control" name="address" rows="3">Jl. Contoh No. 123, Jakarta</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Foto Profile</label>
                        <input type="file" class="form-control" name="photo" accept="image/*">
                        <small class="text-muted">Format: JPG, PNG. Max: 2MB. Kosongkan jika tidak ingin mengubah</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="ti-close me-1"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-warning">
                        <i class="ti-save me-1"></i>Update User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Edit User Form Submit
    document.getElementById('editUserForm').addEventListener('submit', function(e) {
        e.preventDefault();

        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Data user berhasil diupdate',
            showConfirmButton: false,
            timer: 1500
        }).then(() => {
            const editModalEl = document.getElementById('editUserModal');
            if (editModalEl) {
                const modalInstance = bootstrap.Modal.getInstance(editModalEl);
                if (modalInstance) {
                    modalInstance.hide();
                } else if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                    new bootstrap.Modal(editModalEl).hide();
                }
            }
        });
    });

    // Delete User
    function confirmDelete(userId) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data user akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    icon: 'success',
                    title: 'Terhapus!',
                    text: 'User berhasil dihapus',
                    showConfirmButton: false,
                    timer: 1500
                });
                // Tambahkan logika penghapusan data di sini
            }
        });
    }

    // Search functionality
    document.getElementById('searchUser').addEventListener('keyup', function() {
        const searchValue = this.value.toLowerCase();
        const tableRows = document.querySelectorAll('tbody tr');
        
        tableRows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchValue) ? '' : 'none';
        });
    });

    // Filter by Role & Status
    document.getElementById('filterRole').addEventListener('change', filterTable);
    document.getElementById('filterStatus').addEventListener('change', filterTable);

    function filterTable() {
        const roleFilter = document.getElementById('filterRole').value;
        const statusFilter = document.getElementById('filterStatus').value;
        const tableRows = document.querySelectorAll('tbody tr');
        
        tableRows.forEach(row => {
            const role = row.querySelector('td:nth-child(5)').textContent.trim();
            const status = row.querySelector('td:nth-child(6)').textContent.trim();
            
            const roleMatch = !roleFilter || role.toLowerCase() === roleFilter.toLowerCase();
            const statusMatch = !statusFilter || status.toLowerCase() === statusFilter.toLowerCase();
            
            row.style.display = (roleMatch && statusMatch) ? '' : 'none';
        });
    }
</script>
@endpush
