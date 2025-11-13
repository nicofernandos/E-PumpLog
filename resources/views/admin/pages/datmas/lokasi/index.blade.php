@extends('admin.layouts.app')

@section('title', 'Lokasi Stasiun Pompa')

@push('styles')
<style>
    
</style>
@endpush

@section('content')
<div class="page-header">
    <h3 class="page-title">Daftar Lokasi Stasiun Pompa</h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Lokasi SP</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h4 class="card-title mb-0">Data Lokasi Stasiun Pompa</h4>
                        <p class="card-description mb-0">Kelola data lokasi stasiun pompa E-PumpLog</p>
                    </div>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createLokasiModal">
                        <i class="ti-plus me-1"></i> Tambah Lokasi SP
                    </button>
                </div>

                {{-- Filter Section --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                        <input type="text" class="form-control" id="searchLokasi" placeholder="Cari kode SP, nama SP, atau keterangan...">
                    </div>
                </div>

                {{-- Table --}}
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="15%">Kode SP</th>
                                <th width="25%">Nama Stasiun Pompa</th>
                                <th width="35%">Keterangan</th>
                                <th width="10%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($lokasi as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <span class="badge badge-info">{{ $item->kodesp }}</span>
                                    </td>
                                    <td>{{ $item->namasp }}</td>
                                    <td>{{ $item->keterangan ?? '-' }}</td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-warning btn-icon btn-edit-lokasi" 
                                            title="Edit" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editLokasiModal" 
                                            data-id="{{ $item->id }}"
                                            data-kodesp="{{ $item->kodesp }}"
                                            data-namasp="{{ $item->namasp }}"
                                            data-keterangan="{{ $item->keterangan }}">
                                            <i class="ti-pencil"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger btn-icon" title="Hapus" onclick="confirmDelete('{{ $item->id }}')">
                                            <i class="ti-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada data lokasi SP yang tersedia.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="d-flex justify-content-between align-items-center mt-3">
                    @if ($lokasi instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        <p class="mb-0 text-muted">Menampilkan {{ $lokasi->firstItem() }} - {{ $lokasi->lastItem() }} dari {{ $lokasi->total() }} data</p>
                        <nav aria-label="Page navigation">
                            {{ $lokasi->links('pagination::bootstrap-4') }}
                        </nav>
                    @else
                        <p class="mb-0 text-muted">Menampilkan {{ count($lokasi) }} data</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Create Lokasi SP --}}
<div class="modal fade" id="createLokasiModal" tabindex="-1" aria-labelledby="createLokasiModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createLokasiModalLabel">Tambah Lokasi Stasiun Pompa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createLokasiForm" method="POST" action="{{ route('admin.lokasi.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="kodesp" class="form-label">Kode SP <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('kodesp') is-invalid @enderror" 
                               id="kodesp" name="kodesp" 
                               placeholder="Contoh: SP1, SP2" 
                               value="{{ old('kodesp') }}" 
                               required>
                        @error('kodesp')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <small class="form-text text-muted">Maksimal 50 karakter</small>
                    </div>
                    <div class="mb-3">
                        <label for="namasp" class="form-label">Nama Stasiun Pompa <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('namasp') is-invalid @enderror" 
                               id="namasp" name="namasp" 
                               placeholder="Contoh: Stasiun Pompa 1" 
                               value="{{ old('namasp') }}" 
                               required>
                        @error('namasp')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <small class="form-text text-muted">Maksimal 100 karakter</small>
                    </div>
                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                                  id="keterangan" name="keterangan" 
                                  rows="3" 
                                  placeholder="Contoh: Wilayah Limau Field Area 1">{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <small class="form-text text-muted">Deskripsi lokasi atau area stasiun pompa</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ti-save me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Edit Lokasi SP --}}
<div class="modal fade" id="editLokasiModal" tabindex="-1" aria-labelledby="editLokasiModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editLokasiModalLabel">Edit Lokasi Stasiun Pompa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editLokasiForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_kodesp" class="form-label">Kode SP <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_kodesp" name="kodesp" placeholder="Contoh: SP1, SP2" required>
                        <small class="form-text text-muted">Maksimal 50 karakter</small>
                    </div>
                    <div class="mb-3">
                        <label for="edit_namasp" class="form-label">Nama Stasiun Pompa <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_namasp" name="namasp" placeholder="Contoh: Stasiun Pompa 1" required>
                        <small class="form-text text-muted">Maksimal 100 karakter</small>
                    </div>
                    <div class="mb-3">
                        <label for="edit_keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control" id="edit_keterangan" name="keterangan" rows="3" placeholder="Contoh: Wilayah Limau Field Area 1"></textarea>
                        <small class="form-text text-muted">Deskripsi lokasi atau area stasiun pompa</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ti-save me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // 1. Logic untuk modal Edit
        const editLokasiModal = document.getElementById('editLokasiModal');
        const editLokasiForm = document.getElementById('editLokasiForm');
        
        editLokasiModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            
            // Ambil data dari atribut data-*
            const lokasiId = button.getAttribute('data-id');
            const kodesp = button.getAttribute('data-kodesp');
            const namasp = button.getAttribute('data-namasp');
            const keterangan = button.getAttribute('data-keterangan');

            // Set Action Form dengan route yang benar
            const actionRoute = '{{ route("admin.lokasi.update", ":id") }}'.replace(':id', lokasiId);
            editLokasiForm.setAttribute('action', actionRoute);

            // Isi field di Modal
            document.getElementById('edit_kodesp').value = kodesp;
            document.getElementById('edit_namasp').value = namasp;
            document.getElementById('edit_keterangan').value = keterangan || '';
        });

        // 2. Reset form create saat modal dibuka
        const createLokasiModal = document.getElementById('createLokasiModal');
        createLokasiModal.addEventListener('hidden.bs.modal', function () {
            document.getElementById('createLokasiForm').reset();
        });

        // 3. Auto show modal create jika ada error validation
        @if ($errors->any() && !request()->has('_method'))
            var createModal = new bootstrap.Modal(document.getElementById('createLokasiModal'));
            createModal.show();
        @endif

        // 4. Auto show modal edit jika ada error validation pada update
        @if ($errors->any() && request()->has('_method'))
            var editModal = new bootstrap.Modal(document.getElementById('editLokasiModal'));
            editModal.show();
        @endif
    });

    // 5. Delete Lokasi SP
    function confirmDelete(lokasiId) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data lokasi SP akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.action = '{{ route("admin.lokasi.destroy", ":id") }}'.replace(':id', lokasiId);
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

    // 6. Search Function
    document.getElementById('searchLokasi').addEventListener('keyup', function() {
        const searchValue = this.value.toLowerCase();
        const tableRows = document.querySelectorAll('tbody tr');
        
        tableRows.forEach(row => {
            // Skip empty row
            if (row.querySelector('td[colspan]')) return;
            
            const kodesp = row.querySelector('td:nth-child(2)').textContent.trim().toLowerCase();
            const namasp = row.querySelector('td:nth-child(3)').textContent.trim().toLowerCase();
            const keterangan = row.querySelector('td:nth-child(4)').textContent.trim().toLowerCase();

            const match = kodesp.includes(searchValue) || 
                         namasp.includes(searchValue) || 
                         keterangan.includes(searchValue);
            
            row.style.display = match ? '' : 'none';
        });
    });
</script>
@endpush