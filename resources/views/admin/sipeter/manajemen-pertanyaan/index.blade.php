@extends('layouts.app')

@section('content')

    <div class="page-heading">

        {{-- HEADER --}}
        <div class="page-title mb-3">

            <div class="row">

                <div class="col-12 col-md-6 order-md-1 order-last">

                    <h3>Manajemen Pertanyaan</h3>

                    <p class="text-subtitle text-muted">
                        Kelola pertanyaan yang digunakan dalam proses penilaian SIPETER.
                    </p>

                </div>

            </div>

        </div>


        {{-- VALIDATION ERROR --}}
        @if ($errors->any())

            <div class="alert alert-danger alert-dismissible fade show"
                role="alert">

                <div class="d-flex align-items-start">

                    <i class="bi bi-exclamation-triangle-fill me-2 mt-1"></i>

                    <div>

                        <strong>
                            Terjadi kesalahan:
                        </strong>

                        <ul class="mb-0 mt-1">

                            @foreach ($errors->all() as $error)

                                <li>
                                    {{ $error }}
                                </li>

                            @endforeach

                        </ul>

                    </div>

                </div>

                <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close">
                </button>

            </div>

        @endif


        {{-- =====================================================
             DAFTAR PERTANYAAN
        ====================================================== --}}

        <section class="section">

            <div class="card">

                {{-- CARD HEADER --}}
                <div class="card-header">

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                        <div>

                            <h4 class="card-title mb-1">
                                Daftar Pertanyaan
                            </h4>

                            <p class="text-muted mb-0">
                                Kelola pertanyaan untuk kebutuhan Penilaian Tahap 2.
                            </p>

                        </div>


                        {{-- TAMBAH --}}
                        <button type="button"
                            class="btn btn-primary"
                            data-bs-toggle="modal"
                            data-bs-target="#modalTambahPertanyaan">

                            <i class="bi bi-plus-lg me-1"></i>

                            Tambah Pertanyaan

                        </button>

                    </div>

                </div>


                {{-- CARD BODY --}}
                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table table-hover align-middle">

                            <thead>

                                <tr>

                                    <th style="width: 70px;"
                                        class="text-center">
                                        No
                                    </th>

                                    <th>
                                        Pertanyaan
                                    </th>

                                    <th style="width: 150px;"
                                        class="text-center">
                                        Aksi
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @forelse ($pertanyaanList as $index => $pertanyaan)

                                    <tr>

                                        <td class="text-center">

                                            {{ $index + 1 }}

                                        </td>


                                        <td>

                                            <div style="
                                                white-space: normal;
                                                word-break: break-word;
                                                line-height: 1.6;
                                            ">

                                                {{ $pertanyaan->pertanyaan }}

                                            </div>

                                        </td>


                                        <td class="text-center">

                                            <div class="d-flex justify-content-center gap-1">

                                                {{-- EDIT --}}
                                                <button type="button"
                                                    class="btn btn-sm btn-warning"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalEditPertanyaan{{ $pertanyaan->id }}"
                                                    title="Edit">

                                                    <i class="bi bi-pencil-square"></i>

                                                </button>


                                                {{-- HAPUS --}}
                                                <button type="button"
                                                    class="btn btn-sm btn-danger"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#hapusModal{{ $pertanyaan->id }}"
                                                    title="Hapus">

                                                    <i class="bi bi-trash"></i>

                                                </button>

                                            </div>

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td colspan="3"
                                            class="text-center py-5">

                                            <div class="text-muted">

                                                <i class="bi bi-question-circle"
                                                    style="font-size: 42px;">
                                                </i>

                                                <p class="mt-3 mb-1 fw-semibold">
                                                    Belum ada pertanyaan
                                                </p>

                                                <small>
                                                    Silakan tambahkan pertanyaan
                                                    menggunakan tombol
                                                    <strong>Tambah Pertanyaan</strong>.
                                                </small>

                                            </div>

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </section>

    </div>


    {{-- =====================================================
         MODAL TAMBAH
    ====================================================== --}}

    <div class="modal fade"
        id="modalTambahPertanyaan"
        tabindex="-1"
        aria-labelledby="modalTambahPertanyaanLabel"
        aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title"
                        id="modalTambahPertanyaanLabel">

                        <i class="bi bi-plus-circle me-2"></i>

                        Tambah Pertanyaan

                    </h5>

                    <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close">
                    </button>

                </div>


                <form
                    action="{{ route('admin.sipeter.manajemen-pertanyaan.store') }}"
                    method="POST">

                    @csrf

                    <div class="modal-body">

                        <div class="form-group">

                            <label for="pertanyaan"
                                class="form-label">

                                Pertanyaan

                                <span class="text-danger">
                                    *
                                </span>

                            </label>

                            <textarea
                                name="pertanyaan"
                                id="pertanyaan"
                                class="form-control"
                                rows="5"
                                placeholder="Masukkan pertanyaan..."
                                required>{{ old('pertanyaan') }}</textarea>

                            <small class="text-muted">
                                Masukkan pertanyaan yang akan digunakan
                                dalam proses penilaian.
                            </small>

                        </div>

                    </div>


                    <div class="modal-footer">

                        <button type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">

                            <i class="bi bi-x-lg me-1"></i>

                            Batal

                        </button>

                        <button type="submit"
                            class="btn btn-primary">

                            <i class="bi bi-save me-1"></i>

                            Simpan

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>


    {{-- =====================================================
         MODAL EDIT
    ====================================================== --}}

    @foreach ($pertanyaanList as $pertanyaan)

        <div class="modal fade"
            id="modalEditPertanyaan{{ $pertanyaan->id }}"
            tabindex="-1"
            aria-labelledby="modalEditPertanyaanLabel{{ $pertanyaan->id }}"
            aria-hidden="true">

            <div class="modal-dialog modal-dialog-centered">

                <div class="modal-content">

                    <div class="modal-header">

                        <h5 class="modal-title"
                            id="modalEditPertanyaanLabel{{ $pertanyaan->id }}">

                            <i class="bi bi-pencil-square me-2"></i>

                            Edit Pertanyaan

                        </h5>

                        <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close">
                        </button>

                    </div>


                    <form
                        action="{{ route('admin.sipeter.manajemen-pertanyaan.update', $pertanyaan->id) }}"
                        method="POST">

                        @csrf

                        @method('PUT')


                        <div class="modal-body">

                            <div class="form-group">

                                <label
                                    for="pertanyaan{{ $pertanyaan->id }}"
                                    class="form-label">

                                    Pertanyaan

                                    <span class="text-danger">
                                        *
                                    </span>

                                </label>

                                <textarea
                                    name="pertanyaan"
                                    id="pertanyaan{{ $pertanyaan->id }}"
                                    class="form-control"
                                    rows="5"
                                    required>{{ $pertanyaan->pertanyaan }}</textarea>

                                <small class="text-muted">
                                    Perubahan akan diterapkan pada pertanyaan
                                    yang dipilih.
                                </small>

                            </div>

                        </div>


                        <div class="modal-footer">

                            <button type="button"
                                class="btn btn-secondary"
                                data-bs-dismiss="modal">

                                <i class="bi bi-x-lg me-1"></i>

                                Batal

                            </button>

                            <button type="submit"
                                class="btn btn-primary">

                                <i class="bi bi-save me-1"></i>

                                Simpan Perubahan

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    @endforeach


    {{-- =====================================================
         MODAL HAPUS
    ====================================================== --}}
    @foreach ($pertanyaanList as $pertanyaan)
        <div class="modal fade" id="hapusModal{{ $pertanyaan->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content status-modal">

                    {{-- HEADER --}}
                    <div class="modal-header border-0 pb-0">
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body text-center px-4 pb-4">

                        {{-- ICON --}}
                        <div class="status-modal-icon danger">
                            <i class="bi bi-trash"></i>
                        </div>

                        {{-- TITLE --}}
                        <h4 class="mt-3 mb-2">Hapus Pertanyaan?</h4>

                        {{-- DESCRIPTION --}}
                        <p class="text-muted mb-4">
                            Apakah Anda yakin ingin menghapus pertanyaan ini?
                        </p>

                        {{-- INFO PERTANYAAN --}}
                        <div class="status-user-info text-start">
                            <small class="text-muted d-block mb-1">Pertanyaan</small>
                            <div class="fw-semibold" style="white-space: normal; word-break: break-word;">
                                {{ \Illuminate\Support\Str::limit($pertanyaan->pertanyaan, 200) }}
                            </div>
                        </div>

                        {{-- WARNING --}}
                        <div class="alert alert-warning text-start mt-3 mb-0">
                            <div class="d-flex">
                                <i class="bi bi-info-circle-fill me-2 mt-1"></i>
                                <small>
                                    <strong>Pertanyaan yang dihapus tidak dapat dikembalikan.</strong>
                                </small>
                            </div>
                        </div>

                    </div>

                    {{-- FOOTER --}}
                    <div class="modal-footer border-0 justify-content-center pb-4">
                        <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">
                            Batal
                        </button>

                        <form method="POST"
                            action="{{ route('admin.sipeter.manajemen-pertanyaan.destroy', $pertanyaan->id) }}"
                            class="d-inline">
                            @csrf
                            @method('DELETE')

                            <button type="submit" class="btn btn-danger px-4">
                                <i class="bi bi-trash me-1"></i>
                                Ya, Hapus
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    @endforeach


    {{-- =========================
         CUSTOM CSS
    ========================== --}}
    <style>
        .status-modal {
            border: 0;
            border-radius: 16px;
            overflow: hidden;
        }

        .status-modal-icon {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
        }

        .status-modal-icon.danger {
            background: rgba(220, 53, 69, 0.12);
            color: #dc3545;
        }

        .status-modal-icon.success {
            background: rgba(25, 135, 84, 0.12);
            color: #198754;
        }

        .status-user-info {
            padding: 16px;
            border-radius: 12px;
            background: var(--bs-light);
        }
    </style>


    {{-- =========================
         SWEETALERT2
    ========================== --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {

                // Sembunyikan alert Bootstrap lama agar tidak muncul dua kali
                document.querySelectorAll('.alert.alert-success').forEach(function (alert) {
                    alert.style.display = 'none';
                });

                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: @json(session('success')),
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#198754',
                    timer: 2500,
                    timerProgressBar: true
                });

            });
        </script>
    @endif

    @if (session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {

                // Sembunyikan alert Bootstrap lama agar tidak muncul dua kali
                document.querySelectorAll('.alert.alert-danger').forEach(function (alert) {
                    alert.style.display = 'none';
                });

                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan!',
                    text: @json(session('error')),
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#dc3545'
                });

            });
        </script>
    @endif
@endsection