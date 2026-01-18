@extends('admin.layouts.app')

@section('title', 'Users')

@push('styles')
<style>
.modal .input-group .toggle-password {
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

.modal .input-group .toggle-password:hover {
    background-color: #f8f9fa;
}

.modal .input-group .toggle-password:focus,
.modal .input-group .toggle-password:active {
    outline: none !important;
    box-shadow: none !important;
    border-color: #ced4da;
}

/* Style untuk ikon mata di modal */
.modal .toggle-password i {
    font-size: 18px;
    color: #6c757d;
    line-height: 1;
    display: inline-block;
    transition: color 0.2s ease;
}

.modal .toggle-password:hover i {
    color: #495057;
}

/* Pastikan input dan button memiliki tinggi yang sama */
.modal .input-group .form-control {
    border-right: none;
}

.modal .input-group .form-control:focus {
    border-color: #ced4da;
    box-shadow: none;
}

.modal .input-group .form-control:focus + .toggle-password {
    border-color: #80bdff;
}
</style>
@endpush

@section('content')
<div class="page-header">
    <h3 class="page-title">Daftar Users</h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Users</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card px-3 py-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h4 class="card-title mb-0">Data Users</h4>
                        <p class="card-description mb-0">Kelola data pengguna sistem E-PumpLog</p>
                    </div>
                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
                        <i class="ti-plus me-1"></i> Tambah User Baru
                    </a>
                </div>

                {{-- Filter Section --}}
                <div class="row mb-3">
                    <div class="col-md-3">
                        <select class="form-select" id="filterRole">
                            <option value="">Semua Role</option>
                            <option value="manager">Manager</option>
                            <option value="supervisor">Supervisor</option>
                            <option value="operator">Operator</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="filterStatus">
                            <option value="">Semua Status</option>
                            <option value="1">Aktif</option>
                            <option value="0">Nonaktif</option>
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
                            @forelse ($user as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <img src="{{ asset($item->avatar ?? 'images/faces/default.jpg') }}" 
                                             class="me-2" alt="image" 
                                             style="width: 30px; height: 30px; border-radius: 50%;">
                                        {{ $item->name }}
                                    </td>
                                    <td>{{ $item->email }}</td>
                                    <td>{{ explode('@', $item->email)[0] }}</td>
                                    <td>
                                        @php
                                            $roleClass = match ($item->role) {
                                                'admin' => 'badge-danger',
                                                'supervisor' => 'badge-primary',
                                                'operator' => 'badge-info',
                                                'manager' => 'badge-warning',
                                                default => 'badge-secondary',
                                            };
                                        @endphp
                                        <label class="badge {{ $roleClass }}">{{ ucfirst($item->role) }}</label>
                                    </td>
                                    <td>
                                        @if ($item->is_active)
                                            <label class="badge badge-success">Aktif</label>
                                        @else
                                            <label class="badge badge-warning">Nonaktif</label>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="" class="btn btn-sm btn-info btn-icon" title="Detail">
                                            <i class="ti-eye"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-warning btn-icon btn-edit-user" 
                                            title="Edit" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editUserModal" 
                                            data-id="{{ $item->id }}"
                                            data-name="{{ $item->name }}"
                                            data-email="{{ $item->email }}"
                                            data-is-active="{{ $item->is_active }}"
                                            data-role="{{ $item->role }}">
                                            <i class="ti-pencil"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger btn-icon" title="Hapus" onclick="confirmDelete('{{ $item->id }}')">
                                            <i class="ti-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">Tidak ada data user yang tersedia.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="d-flex justify-content-between align-items-center mt-3">
                    @if ($user instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        <p class="mb-0 text-muted">Menampilkan {{ $user->firstItem() }} - {{ $user->lastItem() }} dari {{ $user->total() }} data</p>
                        <nav aria-label="Page navigation">
                            {{ $user->links('pagination::bootstrap-4') }}
                        </nav>
                    @else
                        <p class="mb-0 text-muted">Menampilkan {{ count($user) }} data</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Edit User --}}
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editUserForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="edit_email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_role" class="form-label">Role</label>
                        <select class="form-select" id="edit_role" name="role" required>
                            <option value="">Pilih Role</option>
                            <option value="manager">Manager</option>
                            <option value="supervisor">Supervisor</option>
                            <option value="operator">Operator</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_status" class="form-label">Status</label>
                        <select class="form-select" id="edit_status" name="is_active" required>
                            <option value="1">Aktif</option>
                            <option value="0">Nonaktif</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_password" class="form-label">Password Baru (Opsional)</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="edit_password" name="password" placeholder="Kosongkan jika tidak ingin mengubah password">
                            <button class="btn toggle-password" type="button" data-target="edit_password" tabindex="-1">
                                <i class="ti-eye"></i>
                            </button>
                        </div>
                        <small class="form-text text-muted">Minimal 8 karakter</small>
                    </div>
                    <div class="mb-3">
                        <label for="edit_password_confirmation" class="form-label">Konfirmasi Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="edit_password_confirmation" name="password_confirmation" placeholder="Ulangi password baru">
                            <button class="btn toggle-password" type="button" data-target="edit_password_confirmation" tabindex="-1">
                                <i class="ti-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // 1. Logic untuk mengisi data di Modal Edit
    document.addEventListener('DOMContentLoaded', function() {
        const editUserModal = document.getElementById('editUserModal');
        const editUserForm = document.getElementById('editUserForm');
        
        editUserModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            
            // Ambil data dari atribut data-*
            const userId = button.getAttribute('data-id');
            const name = button.getAttribute('data-name');
            const email = button.getAttribute('data-email');
            const role = button.getAttribute('data-role');
            const isActive = button.getAttribute('data-is-active');

            // Set Action Form dengan route yang benar
            const actionRoute = '{{ route("admin.users.update", ":id") }}'.replace(':id', userId);
            editUserForm.setAttribute('action', actionRoute);

            // Isi field di Modal
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_email').value = email;
            document.getElementById('edit_role').value = role;
            document.getElementById('edit_status').value = isActive;
            
            // Reset password fields dan icon
            const passwordInput = document.getElementById('edit_password');
            const confirmInput = document.getElementById('edit_password_confirmation');
            passwordInput.value = '';
            confirmInput.value = '';
            passwordInput.setAttribute('type', 'password');
            confirmInput.setAttribute('type', 'password');
            
            // Reset icon ke ti-eye
            document.querySelectorAll('#editUserModal .toggle-password i').forEach(icon => {
                icon.className = 'ti-eye';
            });
        });

        // 2. Toggle Password Visibility (untuk semua toggle button)
        const toggleButtons = document.querySelectorAll('.toggle-password');
        
        toggleButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const targetId = this.getAttribute('data-target');
                const passwordInput = document.getElementById(targetId);
                const icon = this.querySelector('i');

                if (passwordInput && icon) {
                    // Toggle tipe input
                    const isPassword = passwordInput.getAttribute('type') === 'password';
                    
                    if (isPassword) {
                        passwordInput.setAttribute('type', 'text');
                        icon.className = 'ti-eye-off';
                    } else {
                        passwordInput.setAttribute('type', 'password');
                        icon.className = 'ti-eye';
                    }
                }
            });

            // Prevent button dari submit form
            button.addEventListener('mousedown', function(e) {
                e.preventDefault();
            });
        });

        // 3. Validasi konfirmasi password di modal
        const editPassword = document.getElementById('edit_password');
        const editPasswordConfirm = document.getElementById('edit_password_confirmation');
        
        if (editPasswordConfirm && editPassword) {
            editPasswordConfirm.addEventListener('input', function() {
                if (editPassword.value !== '' && this.value !== editPassword.value) {
                    this.setCustomValidity('Password tidak cocok');
                } else {
                    this.setCustomValidity('');
                }
            });
            
            editPassword.addEventListener('input', function() {
                if (editPasswordConfirm.value !== '') {
                    editPasswordConfirm.dispatchEvent(new Event('input'));
                }
            });
        }
    });

    // 4. Delete User
    function confirmDelete(userId) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data user akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#ED4337',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.action = '{{ route("admin.users.destroy", ":id") }}'.replace(':id', userId);
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

    // 5. Search and Filter
    document.getElementById('searchUser').addEventListener('keyup', searchTable);
    document.getElementById('filterRole').addEventListener('change', searchTable);
    document.getElementById('filterStatus').addEventListener('change', searchTable);

    function searchTable() {
        const searchValue = document.getElementById('searchUser').value.toLowerCase();
        const roleFilter = document.getElementById('filterRole').value.toLowerCase();
        const statusFilter = document.getElementById('filterStatus').value;
        
        const tableRows = document.querySelectorAll('tbody tr');
        
        tableRows.forEach(row => {
            // Skip empty row
            if (row.querySelector('td[colspan]')) return;
            
            const name = row.querySelector('td:nth-child(2)').textContent.trim().toLowerCase();
            const email = row.querySelector('td:nth-child(3)').textContent.trim().toLowerCase();
            const username = row.querySelector('td:nth-child(4)').textContent.trim().toLowerCase();
            const rowRoleText = row.querySelector('td:nth-child(5) label').textContent.trim().toLowerCase(); 
            const rowStatusText = row.querySelector('td:nth-child(6) label').textContent.trim();
            const rowStatusValue = (rowStatusText === 'Aktif') ? '1' : '0';

            const textMatch = name.includes(searchValue) || email.includes(searchValue) || username.includes(searchValue);
            const roleMatch = !roleFilter || rowRoleText === roleFilter;
            const statusMatch = !statusFilter || rowStatusValue === statusFilter;
            
            row.style.display = (textMatch && roleMatch && statusMatch) ? '' : 'none';
        });
    }
</script>
@endpush