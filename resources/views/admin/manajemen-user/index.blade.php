@extends('layouts.app')

@section('title', 'Manajemen User')

@section('content')

    <div class="page-heading">

        {{-- =========================
        HEADER
    ========================== --}}
        <div class="page-title">

            <div class="row align-items-center">

                <div class="col-md-8">

                    <h3 class="mb-1">
                        Manajemen User
                    </h3>

                    <p class="text-subtitle text-muted mb-0">
                        Kelola akun, data pegawai, dan hak akses pengguna sistem.
                    </p>

                </div>

                <div class="col-md-4 text-md-end mt-3 mt-md-0">

                    <a href="{{ route('admin.manajemen-user.create') }}" class="btn btn-primary">
                        <i class="bi bi-person-plus me-1"></i>
                        Tambah User
                    </a>

                </div>

            </div>

        </div>


        {{-- =========================
        FILTER
    ========================== --}}
        <section class="section mt-4">

            <div class="card">

                <div class="card-body">

                    <form method="GET" action="{{ route('admin.manajemen-user.index') }}">

                        <div class="row g-3 align-items-end">

                            {{-- SEARCH --}}
                            <div class="col-md-5">

                                <label class="form-label fw-semibold">
                                    Cari Pegawai
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        <i class="bi bi-search"></i>
                                    </span>

                                    <input type="text" name="search" class="form-control"
                                        placeholder="Cari berdasarkan NIP atau nama..." value="{{ request('search') }}">

                                </div>

                            </div>


                            {{-- ROLE --}}
                            <div class="col-md-3">

                                <label class="form-label fw-semibold">
                                    Role
                                </label>

                                <select name="role_id" class="form-select">

                                    <option selected disabled>
                                        -- Pilih Role --
                                    </option>

                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}"
                                            {{ request('role_id') == $role->id ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach

                                </select>

                            </div>


                            {{-- STATUS --}}
                            <div class="col-md-2">

                                <label class="form-label fw-semibold">
                                    Status
                                </label>

                                <select name="status" class="form-select">

                                    <option selected disabled>
                                        -- Pilih Status --
                                    </option>

                                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>
                                        Aktif
                                    </option>

                                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>
                                        Nonaktif
                                    </option>

                                </select>

                            </div>


                            {{-- BUTTON --}}
                            <div class="col-md-2">

                                <div class="d-flex gap-2">

                                    <button type="submit" class="btn btn-primary flex-fill">
                                        <i class="bi bi-funnel me-1"></i>
                                        Filter
                                    </button>

                                    <a href="{{ route('admin.manajemen-user.index') }}" class="btn btn-light"
                                        title="Reset Filter">
                                        <i class="bi bi-arrow-counterclockwise"></i>
                                    </a>

                                </div>

                            </div>

                        </div>

                    </form>

                </div>

            </div>

        </section>


        {{-- =========================
        DATA USER
    ========================== --}}
        <section class="section">

            <div class="card">

                <div class="card-header">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <h4 class="card-title mb-1">
                                Data Pegawai
                            </h4>

                            <small class="text-muted">
                                Menampilkan {{ $users->count() }}
                                dari {{ $users->total() }} user
                            </small>

                        </div>

                        <div class="text-muted">

                            <i class="bi bi-people me-1"></i>

                            {{ $users->total() }} User

                        </div>

                    </div>

                </div>


                <div class="card-body">


                    {{-- TABLE --}}
                    <div class="table-responsive">

                        <table class="table table-hover align-middle user-table">

                            <thead>

                                <tr>

                                    <th width="60">
                                        No
                                    </th>

                                    <th>
                                        Pegawai
                                    </th>

                                    <th>
                                        Jabatan
                                    </th>

                                    <th>
                                        Role
                                    </th>

                                    <th>
                                        Status
                                    </th>

                                    <th width="150">
                                        Aksi
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @forelse($users as $index => $user)
                                    <tr>

                                        {{-- NO --}}
                                        <td>

                                            <span class="text-muted">

                                                {{ $users->firstItem() + $index }}

                                            </span>

                                        </td>


                                        {{-- PEGAWAI --}}
                                        <td>

                                            <div class="d-flex align-items-center">

                                                {{-- Avatar --}}
                                                <div class="user-avatar">

                                                    {{ strtoupper(substr($user->nama, 0, 1)) }}

                                                </div>


                                                <div class="ms-3">

                                                    <div class="fw-semibold">

                                                        {{ $user->nama }}

                                                    </div>

                                                    <small class="text-muted">

                                                        {{ $user->nip }}

                                                    </small>

                                                </div>

                                            </div>

                                        </td>


                                        {{-- JABATAN --}}
                                        <td>

                                            @if ($user->jabatan)
                                                {{ $user->jabatan->nama_jabatan }}
                                            @else
                                                <span class="text-muted">
                                                    Belum ditentukan
                                                </span>
                                            @endif

                                        </td>


                                        {{-- ROLE --}}
                                        <td>

                                            @php

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


                                            <span class="badge {{ $roleClass }}">

                                                {{ $user->role->name ?? '-' }}

                                            </span>

                                        </td>


                                        {{-- STATUS --}}
                                        <td>

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

                                        </td>


                                        {{-- AKSI --}}
                                        <td>

                                            <div class="d-flex gap-1">

                                                {{-- DETAIL --}}
                                                <a href="{{ route('admin.manajemen-user.show', $user) }}"
                                                    class="btn btn-sm btn-info" title="Detail">

                                                    <i class="bi bi-eye"></i>

                                                </a>


                                                {{-- EDIT --}}
                                                <a href="{{ route('admin.manajemen-user.edit', $user) }}"
                                                    class="btn btn-sm btn-warning" title="Edit">

                                                    <i class="bi bi-pencil"></i>

                                                </a>


                                                {{-- STATUS --}}
                                                <button type="button"
                                                    class="btn btn-sm {{ $user->status ? 'btn-danger' : 'btn-success' }}"
                                                    title="{{ $user->status ? 'Nonaktifkan' : 'Aktifkan' }}"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#statusModal{{ $user->id }}">

                                                    <i
                                                        class="bi {{ $user->status ? 'bi-person-x' : 'bi-person-check' }}"></i>

                                                </button>

                                            </div>

                                        </td>

                                    </tr>


                                    {{-- ==================================
                                    MODAL STATUS USER
                                =================================== --}}
                                    <div class="modal fade" id="statusModal{{ $user->id }}" tabindex="-1"
                                        aria-hidden="true">

                                        <div class="modal-dialog modal-dialog-centered">

                                            <div class="modal-content status-modal">

                                                {{-- HEADER --}}
                                                <div class="modal-header border-0 pb-0">

                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>

                                                </div>


                                                <div class="modal-body text-center px-4 pb-4">


                                                    {{-- ICON --}}
                                                    <div
                                                        class="status-modal-icon {{ $user->status ? 'danger' : 'success' }}">

                                                        <i
                                                            class="bi {{ $user->status ? 'bi-person-x' : 'bi-person-check' }}"></i>

                                                    </div>


                                                    {{-- TITLE --}}
                                                    <h4 class="mt-3 mb-2">

                                                        {{ $user->status ? 'Nonaktifkan Akun?' : 'Aktifkan Kembali Akun?' }}

                                                    </h4>


                                                    {{-- DESCRIPTION --}}
                                                    <p class="text-muted mb-4">

                                                        {{ $user->status
                                                            ? 'Apakah Anda yakin ingin menonaktifkan akun pegawai ini?'
                                                            : 'Apakah Anda ingin mengaktifkan kembali akun pegawai ini?' }}

                                                    </p>


                                                    {{-- USER INFO --}}
                                                    <div class="status-user-info text-start">

                                                        <div class="d-flex align-items-center">

                                                            <div class="user-avatar">

                                                                {{ strtoupper(substr($user->nama, 0, 1)) }}

                                                            </div>


                                                            <div class="ms-3">

                                                                <div class="fw-semibold">

                                                                    {{ $user->nama }}

                                                                </div>

                                                                <small class="text-muted">

                                                                    {{ $user->nip }}

                                                                </small>

                                                            </div>

                                                        </div>


                                                        <div class="mt-3">

                                                            <div class="d-flex justify-content-between">

                                                                <span class="text-muted">
                                                                    Role
                                                                </span>

                                                                <span class="fw-semibold">

                                                                    {{ $user->role->name ?? '-' }}

                                                                </span>

                                                            </div>


                                                            <div class="d-flex justify-content-between mt-2">

                                                                <span class="text-muted">
                                                                    Status
                                                                </span>

                                                                @if ($user->status)
                                                                    <span class="badge bg-success">
                                                                        Aktif
                                                                    </span>
                                                                @else
                                                                    <span class="badge bg-secondary">
                                                                        Nonaktif
                                                                    </span>
                                                                @endif

                                                            </div>

                                                        </div>

                                                    </div>


                                                    {{-- WARNING --}}
                                                    @if ($user->status)
                                                        <div class="alert alert-warning text-start mt-3 mb-0">

                                                            <div class="d-flex">

                                                                <i class="bi bi-info-circle-fill me-2 mt-1"></i>

                                                                <small>

                                                                    Akun tidak dapat digunakan untuk login
                                                                    setelah dinonaktifkan.
                                                                    <strong>
                                                                        Data pegawai dan riwayatnya tetap tersimpan.
                                                                    </strong>

                                                                </small>

                                                            </div>

                                                        </div>
                                                    @else
                                                        <div class="alert alert-success text-start mt-3 mb-0">

                                                            <div class="d-flex">

                                                                <i class="bi bi-check-circle-fill me-2 mt-1"></i>

                                                                <small>

                                                                    Akun akan dapat digunakan kembali
                                                                    setelah status diaktifkan.

                                                                </small>

                                                            </div>

                                                        </div>
                                                    @endif


                                                </div>


                                                {{-- FOOTER --}}
                                                <div class="modal-footer border-0 justify-content-center pb-4">

                                                    <button type="button" class="btn btn-light px-4"
                                                        data-bs-dismiss="modal">
                                                        Batal
                                                    </button>


                                                    <form method="POST"
                                                        action="{{ route('admin.manajemen-user.destroy', $user) }}"
                                                        class="d-inline">

                                                        @csrf

                                                        @method('DELETE')


                                                        <button type="submit"
                                                            class="btn {{ $user->status ? 'btn-danger' : 'btn-success' }} px-4">

                                                            <i
                                                                class="bi {{ $user->status ? 'bi-person-x' : 'bi-person-check' }} me-1"></i>

                                                            {{ $user->status ? 'Ya, Nonaktifkan' : 'Ya, Aktifkan' }}

                                                        </button>

                                                    </form>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                @empty

                                    <tr>

                                        <td colspan="6" class="text-center py-5">

                                            <div class="empty-state">

                                                <i class="bi bi-people fs-1 text-muted"></i>

                                                <h5 class="mt-3">
                                                    Belum Ada Data User
                                                </h5>

                                                <p class="text-muted">
                                                    Belum ada pegawai yang terdaftar
                                                    dalam sistem.
                                                </p>

                                                <a href="{{ route('admin.manajemen-user.create') }}"
                                                    class="btn btn-primary">

                                                    <i class="bi bi-person-plus me-1"></i>

                                                    Tambah User

                                                </a>

                                            </div>

                                        </td>

                                    </tr>
                                @endforelse

                            </tbody>

                        </table>

                    </div>


                    {{-- =========================
    PAGINATION
========================== --}}
                    @if ($users->hasPages())

                        <div class="user-pagination-wrapper">

                            <div class="pagination-info">
                                Menampilkan
                                <strong>{{ $users->firstItem() }}</strong>
                                -
                                <strong>{{ $users->lastItem() }}</strong>
                                dari
                                <strong>{{ $users->total() }}</strong>
                                user
                            </div>

                            <nav aria-label="Navigasi halaman">

                                <ul class="pagination user-pagination mb-0">

                                    {{-- PREVIOUS --}}
                                    <li class="page-item {{ $users->onFirstPage() ? 'disabled' : '' }}">

                                        @if ($users->onFirstPage())
                                            <span class="page-link pagination-arrow">
                                                <i class="bi bi-chevron-left"></i>
                                            </span>
                                        @else
                                            <a class="page-link pagination-arrow" href="{{ $users->previousPageUrl() }}"
                                                aria-label="Halaman sebelumnya">
                                                <i class="bi bi-chevron-left"></i>
                                            </a>
                                        @endif

                                    </li>


                                    {{-- NOMOR HALAMAN --}}
                                    @foreach ($users->getUrlRange(max(1, $users->currentPage() - 2), min($users->lastPage(), $users->currentPage() + 2)) as $page => $url)
                                        <li class="page-item {{ $page == $users->currentPage() ? 'active' : '' }}">

                                            @if ($page == $users->currentPage())
                                                <span class="page-link">
                                                    {{ $page }}
                                                </span>
                                            @else
                                                <a class="page-link" href="{{ $url }}">
                                                    {{ $page }}
                                                </a>
                                            @endif

                                        </li>
                                    @endforeach


                                    {{-- NEXT --}}
                                    <li class="page-item {{ $users->hasMorePages() ? '' : 'disabled' }}">

                                        @if ($users->hasMorePages())
                                            <a class="page-link pagination-arrow" href="{{ $users->nextPageUrl() }}"
                                                aria-label="Halaman berikutnya">
                                                <i class="bi bi-chevron-right"></i>
                                            </a>
                                        @else
                                            <span class="page-link pagination-arrow">
                                                <i class="bi bi-chevron-right"></i>
                                            </span>
                                        @endif

                                    </li>

                                </ul>

                            </nav>

                        </div>

                    @endif

                </div>

            </div>

        </section>

    </div>


    {{-- =========================
    CUSTOM CSS
========================== --}}
    <style>
        /* =========================================
                   PAGINATION
                ========================================= */

        .user-pagination-wrapper {
            margin-top: 24px;
            padding-top: 18px;
            border-top: 1px solid var(--bs-border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .pagination-info {
            color: var(--bs-secondary-color);
            font-size: 0.85rem;
        }

        .pagination-info strong {
            color: var(--bs-body-color);
            font-weight: 600;
        }

        .user-pagination {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .user-pagination .page-item {
            margin: 0;
        }

        .user-pagination .page-link {
            width: 36px;
            height: 36px;
            padding: 0;

            display: flex;
            align-items: center;
            justify-content: center;

            border: 1px solid var(--bs-border-color);
            border-radius: 8px !important;

            background: var(--bs-body-bg);
            color: var(--bs-secondary-color);

            font-size: 0.85rem;
            font-weight: 500;

            transition:
                background-color 0.2s ease,
                color 0.2s ease,
                border-color 0.2s ease,
                transform 0.2s ease;
        }

        .user-pagination .page-link:hover {
            background: var(--bs-primary);
            border-color: var(--bs-primary);
            color: #fff;
        }

        .user-pagination .page-item.active .page-link {
            background: var(--bs-primary);
            border-color: var(--bs-primary);
            color: #fff;
            font-weight: 600;
        }

        .user-pagination .page-item.disabled .page-link {
            background: var(--bs-tertiary-bg);
            border-color: var(--bs-border-color);
            color: var(--bs-secondary-color);
            opacity: 0.55;
            cursor: not-allowed;
        }

        .user-pagination .pagination-arrow {
            width: 36px;
            height: 36px;
        }

        .user-pagination .pagination-arrow i {
            font-size: 13px;
            line-height: 1;
        }

        .user-pagination .page-link:focus {
            box-shadow: none;
        }


        /* =========================================
                   RESPONSIVE PAGINATION
                ========================================= */

        @media (max-width: 768px) {

            .user-pagination-wrapper {
                flex-direction: column;
                align-items: center;
                justify-content: center;
            }

        }

        .user-table thead th {
            white-space: nowrap;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

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

        .empty-state {
            padding: 20px;
        }

        @media (max-width: 768px) {

            .user-table {
                min-width: 850px;
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
