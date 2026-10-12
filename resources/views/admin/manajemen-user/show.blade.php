@extends('layouts.app')


@section('content')

    @php
        $user = $manajemen_user;

        $roleName = strtolower($user->role->name ?? '');

        $roleClass = match ($roleName) {
            'admin' => 'bg-danger',
            'operator' => 'bg-warning text-dark',
            'kepala bps' => 'bg-primary',
            'pegawai' => 'bg-info text-dark',
            'panitia' => 'bg-success',
            default => 'bg-secondary',
        };
    @endphp

    <div class="page-heading">

        {{-- =========================
             HEADER
        ========================== --}}
        <div class="page-title">

            <div class="row align-items-center">

                <div class="col-md-8">

                    <h3 class="mb-1">
                        Detail User
                    </h3>

                    <p class="text-subtitle text-muted mb-0">
                        Informasi lengkap akun dan data pegawai.
                    </p>

                </div>

            </div>

        </div>


        <section class="section mt-4">

            <div class="row">

                {{-- =========================
                     PROFIL RINGKAS
                ========================== --}}
                <div class="col-12 col-lg-4">

                    <div class="card">

                        <div class="card-body text-center py-4">

                            <div class="user-avatar user-avatar-lg mx-auto">
                                {{ strtoupper(substr($user->nama, 0, 1)) }}
                            </div>

                            <h4 class="mt-3 mb-1">
                                {{ $user->nama }}
                            </h4>

                            <p class="text-muted mb-3">
                                {{ $user->nip }}
                            </p>

                            <div class="d-flex justify-content-center align-items-center gap-2 flex-wrap">

                                <span class="badge {{ $roleClass }}">
                                    {{ $user->role->name ?? '-' }}
                                </span>

                                @if ($user->status)
                                    <span class="status-badge status-active">
                                        <span class="status-dot"></span>
                                        Aktif
                                    </span>
                                @else
                                    <span class="status-badge status-inactive">
                                        <span class="status-dot"></span>
                                        Nonaktif
                                    </span>
                                @endif

                            </div>

                        </div>

                    </div>

                </div>


                {{-- =========================
                     DATA DETAIL
                ========================== --}}
                <div class="col-12 col-lg-8">

                    <div class="card">

                        <div class="card-header">

                            <h4 class="card-title mb-1">
                                Data Pegawai
                            </h4>

                            <small class="text-muted">
                                Data yang tersimpan untuk akun ini.
                            </small>

                        </div>

                        <div class="card-body">

                            <div class="detail-list">

                                <div class="detail-item">
                                    <span class="detail-label">NIP</span>
                                    <span class="detail-value">{{ $user->nip }}</span>
                                </div>

                                <div class="detail-item">
                                    <span class="detail-label">Nama Lengkap</span>
                                    <span class="detail-value">{{ $user->nama }}</span>
                                </div>

                                <div class="detail-item">
                                    <span class="detail-label">Email</span>
                                    <span class="detail-value">{{ $user->email }}</span>
                                </div>

                                <div class="detail-item">
                                    <span class="detail-label">Jabatan</span>
                                    <span class="detail-value">
                                        @if ($user->jabatan)
                                            {{ $user->jabatan->nama_jabatan }}
                                        @else
                                            <span class="text-muted">Belum ditentukan</span>
                                        @endif
                                    </span>
                                </div>

                                <div class="detail-item">
                                    <span class="detail-label">Role</span>
                                    <span class="detail-value">
                                        <span class="badge {{ $roleClass }}">
                                            {{ $user->role->name ?? '-' }}
                                        </span>
                                    </span>
                                </div>

                                <div class="detail-item">
                                    <span class="detail-label">Status Akun</span>
                                    <span class="detail-value">
                                        @if ($user->status)
                                            <span class="status-badge status-active">
                                                <span class="status-dot"></span>
                                                Aktif
                                            </span>
                                        @else
                                            <span class="status-badge status-inactive">
                                                <span class="status-dot"></span>
                                                Nonaktif
                                            </span>
                                        @endif
                                    </span>
                                </div>

                                <div class="detail-item">
                                    <span class="detail-label">Dibuat Pada</span>
                                    <span class="detail-value">
                                        {{ $user->created_at ? $user->created_at->copy()->timezone('Asia/Jakarta')->format('d-m-Y H:i') . ' WIB' : '-' }}
                                    </span>
                                </div>

                                <div class="detail-item">
                                    <span class="detail-label">Terakhir Diperbarui</span>
                                    <span class="detail-value">
                                        {{ $user->updated_at ? $user->updated_at->copy()->timezone('Asia/Jakarta')->format('d-m-Y H:i') . ' WIB' : '-' }}
                                    </span>
                                </div>

                            </div>

                        </div>

                        <div class="card-footer bg-transparent">

                            <div class="d-flex flex-wrap gap-2 justify-content-between">

                                <a href="{{ route('admin.manajemen-user.index') }}" class="btn btn-light">
                                    <i class="bi bi-arrow-left me-1"></i>
                                    Kembali
                                </a>

                                <div class="d-flex gap-2">

                                    <a href="{{ route('admin.manajemen-user.edit', $user) }}" class="btn btn-warning">
                                        <i class="bi bi-pencil me-1"></i>
                                        Edit
                                    </a>

                                    <button type="button" class="btn {{ $user->status ? 'btn-danger' : 'btn-success' }}"
                                        data-bs-toggle="modal" data-bs-target="#statusModal">

                                        <i class="bi {{ $user->status ? 'bi-person-x' : 'bi-person-check' }} me-1"></i>

                                        {{ $user->status ? 'Nonaktifkan' : 'Aktifkan' }}

                                    </button>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </section>

    </div>


    {{-- ==================================
         MODAL STATUS USER
    =================================== --}}
    <div class="modal fade" id="statusModal" tabindex="-1" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content status-modal">

                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body text-center px-4 pb-4">

                    <div class="status-modal-icon {{ $user->status ? 'danger' : 'success' }}">
                        <i class="bi {{ $user->status ? 'bi-person-x' : 'bi-person-check' }}"></i>
                    </div>

                    <h4 class="mt-3 mb-2">
                        {{ $user->status ? 'Nonaktifkan Akun?' : 'Aktifkan Kembali Akun?' }}
                    </h4>

                    <p class="text-muted mb-4">
                        {{ $user->status
                            ? 'Apakah Anda yakin ingin menonaktifkan akun pegawai ini?'
                            : 'Apakah Anda ingin mengaktifkan kembali akun pegawai ini?' }}
                    </p>

                    <div class="status-user-info text-start">

                        <div class="d-flex align-items-center">

                            <div class="user-avatar">
                                {{ strtoupper(substr($user->nama, 0, 1)) }}
                            </div>

                            <div class="ms-3">
                                <div class="fw-semibold">{{ $user->nama }}</div>
                                <small class="text-muted">{{ $user->nip }}</small>
                            </div>

                        </div>

                    </div>

                    @if ($user->status)
                        <div class="alert alert-warning text-start mt-3 mb-0">
                            <div class="d-flex">
                                <i class="bi bi-info-circle-fill me-2 mt-1"></i>
                                <small>
                                    Akun tidak dapat digunakan untuk login setelah dinonaktifkan.
                                    <strong>Data pegawai dan riwayatnya tetap tersimpan.</strong>
                                </small>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-success text-start mt-3 mb-0">
                            <div class="d-flex">
                                <i class="bi bi-check-circle-fill me-2 mt-1"></i>
                                <small>
                                    Akun akan dapat digunakan kembali setelah status diaktifkan.
                                </small>
                            </div>
                        </div>
                    @endif

                </div>

                <div class="modal-footer border-0 justify-content-center pb-4">

                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">
                        Batal
                    </button>

                    <form method="POST" action="{{ route('admin.manajemen-user.destroy', $user) }}" class="d-inline">

                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn {{ $user->status ? 'btn-danger' : 'btn-success' }} px-4">
                            <i class="bi {{ $user->status ? 'bi-person-x' : 'bi-person-check' }} me-1"></i>
                            {{ $user->status ? 'Ya, Nonaktifkan' : 'Ya, Aktifkan' }}
                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================
         CUSTOM CSS
    ========================== --}}
    <style>
        .user-avatar {
            width: 42px;
            height: 42px;
            min-width: 42px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--bs-primary);
            color: #fff;
            font-weight: 700;
            font-size: 16px;
        }

        .user-avatar-lg {
            width: 88px;
            height: 88px;
            min-width: 88px;
            font-size: 34px;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.78rem;
            font-weight: 600;
        }

        .status-active {
            background: rgba(25, 135, 84, 0.12);
            color: #198754;
        }

        .status-inactive {
            background: rgba(108, 117, 125, 0.12);
            color: #6c757d;
        }

        .status-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: currentColor;
        }

        .detail-list {
            display: flex;
            flex-direction: column;
        }

        .detail-item {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 14px 0;
            border-bottom: 1px solid var(--bs-border-color);
        }

        .detail-item:first-child {
            padding-top: 0;
        }

        .detail-item:last-child {
            border-bottom: 0;
            padding-bottom: 0;
        }

        .detail-label {
            flex: 0 0 200px;
            color: var(--bs-secondary-color);
            font-size: 0.9rem;
        }

        .detail-value {
            flex: 1;
            font-weight: 600;
            word-break: break-word;
        }

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

        @media (max-width: 576px) {

            .detail-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 4px;
            }

            .detail-label {
                flex: none;
            }

        }
    </style>

    {{-- =========================
         SWEETALERT2
    ========================== --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {

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