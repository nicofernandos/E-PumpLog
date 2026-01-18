@extends('admin.layouts.app')

@section('title', 'Data Pompa')

@push('styles')
<style>
    .table td {
        vertical-align: middle;
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <h3 class="page-title mb-2">Daftar Pompa</h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Data Pompa</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h4 class="card-title mb-0">Data Pompa</h4>
                        <p class="card-description mb-0">Kelola data pompa E-PumpLog</p>
                    </div>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createPompaModal">
                        <i class="ti-plus me-1"></i> Tambah Pompa
                    </button>
                </div>

                {{-- Filter Section --}}
                <div class="row mb-3">
                    <div class="col-md-6 mb-2">
                        <input type="text" class="form-control" id="searchPompa" placeholder="Cari kode pompa, jenis pompa, atau kapasitas...">
                    </div>
                    <div class="col-md-3 mb-2">
                        <select class="form-select" id="filterLokasi">
                            <option value="">Semua Lokasi</option>
                            @foreach($lokasi as $lok)
                                <option value="{{ $lok->id }}">{{ $lok->namasp }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-2">
                        <select class="form-select" id="filterStatus">
                            <option value="">Semua Status</option>
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Non-Aktif</option>
                        </select>
                    </div>
                </div>

                {{-- Table --}}
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="12%">Kode Pompa</th>
                                <th width="20%">Jenis Pompa</th>
                                <th width="12%">Kapasitas</th>
                                <th width="20%">Lokasi SP</th>
                                <th width="10%" class="text-center">Status</th>
                                <th width="12%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pompa as $index => $item)
                                <tr data-lokasi="{{ $item->lokasi_id }}" data-status="{{ $item->status }}">
                                    <td>{{ $pompa->firstItem() + $index }}</td>
                                    <td>
                                        <span class="badge badge-info">{{ $item->kodepompa }}</span>
                                    </td>
                                    <td>{{ $item->jenispompa }}</td>
                                    <td>{{ $item->kapasitas ?? '-' }}</td>
                                    <td>
                                        <small class="text-muted">{{ $item->lokasi->kodesp ?? '-' }}</small><br>
                                        {{ $item->lokasi->namasp ?? '-' }}
                                    </td>
                                    <td class="text-center">
                                        @if($item->status == 'aktif')
                                            <span class="badge badge-success">Aktif</span>
                                        @else
                                            <span class="badge badge-danger">Non-Aktif</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-warning btn-icon btn-edit-pompa" 
                                            title="Edit" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editPompaModal" 
                                            data-id="{{ $item->id }}"
                                            data-kodepompa="{{ $item->kodepompa }}"
                                            data-jenispompa="{{ $item->jenispompa }}"
                                            data-kapasitas="{{ $item->kapasitas }}"
                                            data-lokasi="{{ $item->lokasi_id }}"
                                            data-status="{{ $item->status }}">
                                            <i class="ti-pencil"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger btn-icon" title="Hapus" onclick="confirmDelete('{{ $item->id }}')">
                                            <i class="ti-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">Tidak ada data pompa yang tersedia.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="d-flex justify-content-between align-items-center mt-3">
                    @if ($pompa instanceof \Illuminate\Pagination\LengthAwarePaginator && $pompa->total() > 0)
                        <p class="mb-0 text-muted">Menampilkan {{ $pompa->firstItem() }} - {{ $pompa->lastItem() }} dari {{ $pompa->total() }} data</p>
                        <nav aria-label="Page navigation">
                            {{ $pompa->links('pagination::bootstrap-4') }}
                        </nav>
                    @else
                        <p class="mb-0 text-muted">Menampilkan {{ count($pompa) }} data</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Create Pompa --}}
<div class="modal fade" id="createPompaModal" tabindex="-1" aria-labelledby="createPompaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createPompaModalLabel">Tambah Data Pompa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createPompaForm" method="POST" action="{{ route('admin.pompa.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="kodepompa" class="form-label">Kode Pompa <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('kodepompa') is-invalid @enderror" 
                               id="kodepompa" name="kodepompa" 
                               placeholder="Contoh: P001, P002" 
                               value="{{ old('kodepompa') }}" 
                               required>
                        @error('kodepompa')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <small class="form-text text-muted">Maksimal 50 karakter</small>
                    </div>
                    <div class="mb-3">
                        <label for="jenispompa" class="form-label">Jenis Pompa <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('jenispompa') is-invalid @enderror" 
                               id="jenispompa" name="jenispompa" 
                               placeholder="Contoh: Centrifugal Pump, Submersible Pump" 
                               value="{{ old('jenispompa') }}" 
                               required>
                        @error('jenispompa')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <small class="form-text text-muted">Maksimal 100 karakter</small>
                    </div>
                    <div class="mb-3">
                        <label for="kapasitas" class="form-label">Kapasitas</label>
                        <input type="text" class="form-control @error('kapasitas') is-invalid @enderror" 
                               id="kapasitas" name="kapasitas" 
                               placeholder="Contoh: 500 L/min, 30 m³/h" 
                               value="{{ old('kapasitas') }}">
                        @error('kapasitas')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <small class="form-text text-muted">Kapasitas pompa (opsional)</small>
                    </div>
                    <div class="mb-3">
                        <label for="lokasi_id" class="form-label">Lokasi Stasiun Pompa <span class="text-danger">*</span></label>
                        <select class="form-select @error('lokasi_id') is-invalid @enderror" 
                                id="lokasi_id" name="lokasi_id" required>
                            <option value="">-- Pilih Lokasi SP --</option>
                            @foreach($lokasi as $lok)
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
                    <div class="mb-3">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" 
                                id="status" name="status" required>
                            <option value="aktif" {{ old('status', 'aktif') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>Non-Aktif</option>
                        </select>
                        @error('status')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ti-save me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Edit Pompa --}}
<div class="modal fade" id="editPompaModal" tabindex="-1" aria-labelledby="editPompaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPompaModalLabel">Edit Data Pompa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editPompaForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_kodepompa" class="form-label">Kode Pompa <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('kodepompa') is-invalid @enderror" 
                               id="edit_kodepompa" name="kodepompa" 
                               placeholder="Contoh: P001, P002" 
                               value="{{ old('kodepompa') }}"
                               required>
                        @error('kodepompa')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <small class="form-text text-muted">Maksimal 50 karakter</small>
                    </div>
                    <div class="mb-3">
                        <label for="edit_jenispompa" class="form-label">Jenis Pompa <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('jenispompa') is-invalid @enderror" 
                               id="edit_jenispompa" name="jenispompa" 
                               placeholder="Contoh: Centrifugal Pump" 
                               value="{{ old('jenispompa') }}"
                               required>
                        @error('jenispompa')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <small class="form-text text-muted">Maksimal 100 karakter</small>
                    </div>
                    <div class="mb-3">
                        <label for="edit_kapasitas" class="form-label">Kapasitas</label>
                        <input type="text" class="form-control @error('kapasitas') is-invalid @enderror" 
                               id="edit_kapasitas" name="kapasitas" 
                               placeholder="Contoh: 500 L/min"
                               value="{{ old('kapasitas') }}">
                        @error('kapasitas')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <small class="form-text text-muted">Kapasitas pompa (opsional)</small>
                    </div>
                    <div class="mb-3">
                        <label for="edit_lokasi_id" class="form-label">Lokasi Stasiun Pompa <span class="text-danger">*</span></label>
                        <select class="form-select @error('lokasi_id') is-invalid @enderror" 
                                id="edit_lokasi_id" name="lokasi_id" required>
                            <option value="">-- Pilih Lokasi SP --</option>
                            @foreach($lokasi as $lok)
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
                    <div class="mb-3">
                        <label for="edit_status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" 
                                id="edit_status" name="status" required>
                            <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>Non-Aktif</option>
                        </select>
                        @error('status')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
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
        const editPompaModal = document.getElementById('editPompaModal');
        const editPompaForm = document.getElementById('editPompaForm');
        
        editPompaModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            
            // Ambil data dari atribut data-*
            const pompaId = button.getAttribute('data-id');
            const kodepompa = button.getAttribute('data-kodepompa');
            const jenispompa = button.getAttribute('data-jenispompa');
            const kapasitas = button.getAttribute('data-kapasitas');
            const lokasiId = button.getAttribute('data-lokasi');
            const status = button.getAttribute('data-status');

            // Set Action Form dengan route yang benar
            const actionRoute = '{{ route("admin.pompa.update", ":id") }}'.replace(':id', pompaId);
            editPompaForm.setAttribute('action', actionRoute);

            // Isi field di Modal
            document.getElementById('edit_kodepompa').value = kodepompa || '';
            document.getElementById('edit_jenispompa').value = jenispompa || '';
            document.getElementById('edit_kapasitas').value = kapasitas === 'null' ? '' : (kapasitas || '');
            document.getElementById('edit_lokasi_id').value = lokasiId || '';
            document.getElementById('edit_status').value = status || 'aktif';
        });

        // 2. Reset form create saat modal ditutup
        const createPompaModal = document.getElementById('createPompaModal');
        createPompaModal.addEventListener('hidden.bs.modal', function () {
            document.getElementById('createPompaForm').reset();
            // Hapus semua class is-invalid
            document.querySelectorAll('#createPompaForm .is-invalid').forEach(el => {
                el.classList.remove('is-invalid');
            });
            // Hapus semua invalid-feedback
            document.querySelectorAll('#createPompaForm .invalid-feedback').forEach(el => {
                el.remove();
            });
        });

        // 3. Reset form edit saat modal ditutup
        editPompaModal.addEventListener('hidden.bs.modal', function () {
            // Hapus semua class is-invalid
            document.querySelectorAll('#editPompaForm .is-invalid').forEach(el => {
                el.classList.remove('is-invalid');
            });
            // Hapus semua invalid-feedback
            document.querySelectorAll('#editPompaForm .invalid-feedback').forEach(el => {
                el.remove();
            });
        });

        // 4. Auto show modal create jika ada error validation (tanpa _method = POST)
        @if ($errors->any() && !session('edited_pompa_id'))
            var createModal = new bootstrap.Modal(document.getElementById('createPompaModal'));
            createModal.show();
        @endif

        // 5. Auto show modal edit jika ada error validation pada update
        @if ($errors->any() && session('edited_pompa_id'))
            var editModal = new bootstrap.Modal(document.getElementById('editPompaModal'));
            
            // Populate data dari session
            const editedPompa = @json(session('edited_pompa'));
            if (editedPompa) {
                const actionRoute = '{{ route("admin.pompa.update", ":id") }}'.replace(':id', editedPompa.id);
                editPompaForm.setAttribute('action', actionRoute);
                
                document.getElementById('edit_kodepompa').value = '{{ old("kodepompa") }}' || editedPompa.kodepompa;
                document.getElementById('edit_jenispompa').value = '{{ old("jenispompa") }}' || editedPompa.jenispompa;
                document.getElementById('edit_kapasitas').value = '{{ old("kapasitas") }}' || editedPompa.kapasitas || '';
                document.getElementById('edit_lokasi_id').value = '{{ old("lokasi_id") }}' || editedPompa.lokasi_id;
                document.getElementById('edit_status').value = '{{ old("status") }}' || editedPompa.status;
            }
            
            editModal.show();
        @endif
    });

    // 6. Delete Pompa
    function confirmDelete(pompaId) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data pompa akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#ED4337',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.action = '{{ route("admin.pompa.destroy", ":id") }}'.replace(':id', pompaId);
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

    // 7. Search Function
    document.getElementById('searchPompa').addEventListener('keyup', function() {
        filterTable();
    });

    // 8. Filter by Lokasi
    document.getElementById('filterLokasi').addEventListener('change', function() {
        filterTable();
    });

    // 9. Filter by Status
    document.getElementById('filterStatus').addEventListener('change', function() {
        filterTable();
    });

    // 10. Combined Filter Function
    function filterTable() {
        const searchValue = document.getElementById('searchPompa').value.toLowerCase();
        const lokasiFilter = document.getElementById('filterLokasi').value;
        const statusFilter = document.getElementById('filterStatus').value;
        const tableRows = document.querySelectorAll('tbody tr');
        
        let visibleCount = 0;
        
        tableRows.forEach(row => {
            // Skip empty row
            if (row.querySelector('td[colspan]')) {
                row.style.display = visibleCount === 0 ? '' : 'none';
                return;
            }
            
            const kodepompa = row.querySelector('td:nth-child(2)').textContent.trim().toLowerCase();
            const jenispompa = row.querySelector('td:nth-child(3)').textContent.trim().toLowerCase();
            const kapasitas = row.querySelector('td:nth-child(4)').textContent.trim().toLowerCase();
            const lokasiId = row.getAttribute('data-lokasi');
            const status = row.getAttribute('data-status');

            const matchSearch = kodepompa.includes(searchValue) || 
                               jenispompa.includes(searchValue) || 
                               kapasitas.includes(searchValue);
            
            const matchLokasi = lokasiFilter === '' || lokasiId === lokasiFilter;
            const matchStatus = statusFilter === '' || status === statusFilter;
            
            if (matchSearch && matchLokasi && matchStatus) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });
    }
</script>
@endpush