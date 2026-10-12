@extends('layouts.app')

@section('title', 'Panitia')

@section('content')

    <style>
        /* =========================================================
           PANITIA PAGE
        ========================================================= */

        .panitia-page {
            width: 100%;
        }

        .panitia-card {
            background: #ffffff;
            border-radius: 10px;
            border: 1px solid #e8e8e8;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
        }


        /* =========================================================
           BLUE HEADER
        ========================================================= */

        .panitia-card-header {
            background: #075a9f !important;
            color: #ffffff !important;

            padding: 16px 20px;

            display: flex;
            align-items: center;
            justify-content: space-between;

            gap: 15px;
        }

        .panitia-card-header-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .panitia-card-header-left i {
            font-size: 20px;
            color: #ffffff !important;
        }

        .panitia-card-header-title {
            margin: 0;
            font-size: 16px;
            font-weight: 600;

            color: #ffffff !important;
        }

        .panitia-card-header-subtitle {
            margin: 3px 0 0;
            font-size: 11px;

            color: rgba(255, 255, 255, 0.85) !important;
        }


        /* =========================================================
           UPLOAD BUTTON
        ========================================================= */

        .btn-panitia-upload {
            border: 0;

            background: #ffffff !important;
            color: #075a9f !important;

            padding: 8px 13px;

            border-radius: 6px;

            font-size: 12px;
            font-weight: 600;

            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;

            transition: all 0.2s;

            cursor: pointer;
        }

        .btn-panitia-upload i {
            color: #075a9f !important;
        }

        .btn-panitia-upload:hover {
            background: #f1f7fc !important;
            color: #064d87 !important;
        }

        .btn-panitia-upload:hover i {
            color: #064d87 !important;
        }


        /* =========================================================
           TOOLBAR
        ========================================================= */

        .panitia-toolbar {
            padding: 15px 18px;

            border-bottom: 1px solid #eeeeee;

            display: flex;
            justify-content: space-between;
            align-items: center;

            gap: 10px;
        }

        .panitia-search-wrapper {
            width: 330px;
            max-width: 100%;

            position: relative;
        }

        .panitia-search-wrapper i {
            position: absolute;

            left: 12px;
            top: 50%;

            transform: translateY(-50%);

            color: #8b95a1;

            font-size: 14px;
        }

        .panitia-search {
            width: 100%;

            height: 38px;

            border: 1px solid #dfe3e8;

            border-radius: 6px;

            padding: 0 12px 0 35px;

            font-size: 12px;

            outline: none;

            transition: 0.2s;
        }

        .panitia-search:focus {
            border-color: #075a9f;

            box-shadow:
                0 0 0 2px rgba(7, 90, 159, 0.08);
        }


        /* =========================================================
           TABLE
        ========================================================= */

        .panitia-table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        .panitia-table {
            width: 100%;

            border-collapse: collapse;

            min-width: 850px;
        }

        .panitia-table thead th {
            background: #f8f9fa;

            color: #495057;

            font-size: 12px;
            font-weight: 600;

            padding: 12px 14px;

            border-bottom: 1px solid #e5e7eb;

            white-space: nowrap;
        }

        .panitia-table tbody td {
            padding: 13px 14px;

            border-bottom: 1px solid #eeeeee;

            color: #495057;

            font-size: 12px;

            vertical-align: middle;
        }

        .panitia-table tbody tr:last-child td {
            border-bottom: 0;
        }

        .panitia-table tbody tr:hover {
            background: #fafcff;
        }


        /* =========================================================
           NUMBER
        ========================================================= */

        .panitia-number {
            width: 55px;

            text-align: center;

            color: #6c757d;

            font-weight: 500;
        }


        /* =========================================================
           NO SK
        ========================================================= */

        .panitia-no-sk {
            color: #075a9f;

            font-weight: 600;
        }


        /* =========================================================
           PEGAWAI
        ========================================================= */

        .panitia-pegawai {
            display: flex;

            align-items: center;

            gap: 8px;
        }

        .panitia-avatar {
            width: 30px;
            height: 30px;

            border-radius: 50%;

            background: #e9f3fb;

            color: #075a9f;

            display: flex;

            align-items: center;
            justify-content: center;

            font-size: 12px;
            font-weight: 600;

            flex-shrink: 0;
        }

        .panitia-pegawai-name {
            font-size: 12px;

            font-weight: 500;

            color: #495057;
        }


        /* =========================================================
           ACTION
        ========================================================= */

        .panitia-actions {
            display: flex;

            align-items: center;

            gap: 5px;
        }

        .panitia-action {
            width: 30px;
            height: 30px;

            border-radius: 5px;

            border: 0;

            display: inline-flex;

            align-items: center;
            justify-content: center;

            text-decoration: none;

            cursor: pointer;

            transition: all 0.2s;
        }

        .panitia-action i {
            font-size: 13px;
        }


        .panitia-action-view {
            background: #eaf7ef;

            color: #198754;
        }

        .panitia-action-view:hover {
            background: #198754;

            color: #ffffff;
        }


        .panitia-action-edit {
            background: #fff4df;

            color: #d99000;
        }

        .panitia-action-edit:hover {
            background: #d99000;

            color: #ffffff;
        }


        .panitia-action-delete {
            background: #fdebec;

            color: #dc3545;
        }

        .panitia-action-delete:hover {
            background: #dc3545;

            color: #ffffff;
        }


        /* =========================================================
           EMPTY
        ========================================================= */

        .panitia-empty {
            padding: 50px 20px !important;

            text-align: center;

            color: #8c96a3 !important;
        }

        .panitia-empty i {
            font-size: 38px;

            color: #cbd1d8;

            display: block;

            margin-bottom: 10px;
        }

        .panitia-empty-title {
            font-size: 13px;

            font-weight: 600;

            color: #6c757d;

            margin-bottom: 3px;
        }

        .panitia-empty-text {
            font-size: 11px;
        }


        /* =========================================================
           PAGINATION
        ========================================================= */

        .panitia-pagination {
            padding: 15px 18px;

            border-top: 1px solid #eeeeee;

            display: flex;

            justify-content: flex-end;
        }


        /* =========================================================
           MODAL
        ========================================================= */

        .panitia-modal .modal-content {
            border: 0;

            border-radius: 10px;

            overflow: hidden;

            box-shadow:
                0 10px 40px rgba(0, 0, 0, 0.15);
        }


        /* =========================================================
           MODAL HEADER
        ========================================================= */

        .panitia-modal-header {
            background: #075a9f !important;

            color: #ffffff !important;

            padding: 15px 18px;

            display: flex;

            align-items: center;

            justify-content: space-between;
        }

        .panitia-modal-header h5 {
            margin: 0;

            font-size: 15px;

            font-weight: 600;

            display: flex;

            align-items: center;

            gap: 8px;

            color: #ffffff !important;
        }

        .panitia-modal-header h5 i {
            color: #ffffff !important;

            font-size: 17px;
        }

        .panitia-modal-header .btn-close {
            filter: brightness(0) invert(1);

            opacity: 0.9;
        }

        .panitia-modal-header .btn-close:hover {
            opacity: 1;
        }


        /* =========================================================
           MODAL BODY
        ========================================================= */

        .panitia-modal-body {
            padding: 20px;
        }

        .panitia-form-group {
            margin-bottom: 15px;
        }

        .panitia-form-label {
            display: block;

            margin-bottom: 6px;

            font-size: 12px;

            font-weight: 600;

            color: #495057;
        }

        .panitia-required {
            color: #dc3545;
        }

        .panitia-form-control {
            width: 100%;

            height: 38px;

            border: 1px solid #dfe3e8;

            border-radius: 6px;

            padding: 0 10px;

            font-size: 12px;

            outline: none;

            transition: 0.2s;
        }

        textarea.panitia-form-control {
            height: 80px;

            padding-top: 9px;

            resize: vertical;
        }

        .panitia-form-control:focus {
            border-color: #075a9f;

            box-shadow:
                0 0 0 2px rgba(7, 90, 159, 0.08);
        }


        /* =========================================================
           FILE INPUT
        ========================================================= */

        .panitia-file-wrapper {
            border: 1px dashed #cdd4dc;

            background: #fafbfc;

            border-radius: 7px;

            padding: 11px;
        }

        .panitia-file-wrapper input {
            width: 100%;

            font-size: 11px;
        }

        .panitia-file-info {
            margin-top: 6px;

            font-size: 10px;

            color: #8b95a1;
        }


        /* =========================================================
           CURRENT PDF
        ========================================================= */

        .panitia-current-file {
            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 10px;

            background: #f1f8fd;

            border: 1px solid #d9ebf8;

            border-radius: 7px;

            padding: 9px 10px;

            margin-bottom: 15px;
        }

        .panitia-current-file-info {
            display: flex;

            align-items: center;

            gap: 8px;

            min-width: 0;
        }

        .panitia-current-file-info i {
            color: #dc3545;

            font-size: 18px;
        }

        .panitia-current-file-name {
            font-size: 11px;

            color: #495057;

            overflow: hidden;

            white-space: nowrap;

            text-overflow: ellipsis;
        }

        .panitia-current-file-link {
            flex-shrink: 0;

            font-size: 10px;

            color: #075a9f;

            text-decoration: none;

            border: 1px solid #cfe0ef;

            background: #ffffff;

            border-radius: 5px;

            padding: 5px 8px;
        }

        .panitia-current-file-link:hover {
            background: #075a9f;

            color: #ffffff;
        }


        /* =========================================================
           MODAL FOOTER
        ========================================================= */

        .panitia-modal-footer {
            padding: 13px 20px;

            border-top: 1px solid #eeeeee;

            display: flex;

            justify-content: center !important;

            align-items: center;

            gap: 8px;

            width: 100%;
        }

        .btn-panitia-cancel {
            border: 0;

            background: #f1f3f5;

            color: #495057;

            border-radius: 5px;

            padding: 8px 15px;

            font-size: 11px;

            font-weight: 600;

            min-width: 75px;

            text-align: center;

            cursor: pointer;
        }

        .btn-panitia-cancel:hover {
            background: #e2e6ea;

            color: #343a40;
        }

        .btn-panitia-save {
            border: 0;

            background: #075a9f;

            color: #ffffff;

            border-radius: 5px;

            padding: 8px 15px;

            font-size: 11px;

            font-weight: 600;

            min-width: 115px;

            text-align: center;

            cursor: pointer;
        }

        .btn-panitia-save:hover {
            background: #064d87;

            color: #ffffff;
        }


        /* =========================================================
           DELETE MODAL
        ========================================================= */

        .delete-content {
            padding: 25px 20px 10px;

            text-align: center;
        }

        .delete-icon {
            width: 50px;
            height: 50px;

            margin: 0 auto 12px;

            border-radius: 50%;

            background: #fdebec;

            color: #dc3545;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 22px;
        }

        .delete-content h5 {
            font-size: 15px;

            font-weight: 600;

            margin-bottom: 7px;

            color: #343a40;
        }

        .delete-content p {
            font-size: 11px;

            line-height: 1.6;

            color: #7b8591;

            margin: 0;
        }

        .delete-no-sk {
            font-weight: 600;

            color: #dc3545;
        }


        /*
        |--------------------------------------------------------------------------
        | DELETE MODAL FOOTER
        |--------------------------------------------------------------------------
        */

        #modalHapusPanitia .panitia-modal-footer {
            justify-content: center !important;

            align-items: center;

            padding-top: 12px;
            padding-bottom: 18px;
        }

        #deleteFormPanitia {
            margin: 0;

            display: flex;

            align-items: center;

            justify-content: center;
        }

        .btn-delete-confirm {
            border: 0;

            background: #dc3545;

            color: #ffffff;

            border-radius: 5px;

            padding: 8px 15px;

            min-width: 85px;

            font-size: 11px;

            font-weight: 600;

            text-align: center;

            display: inline-flex;

            align-items: center;

            justify-content: center;

            cursor: pointer;
        }

        .btn-delete-confirm:hover {
            background: #bb2d3b;

            color: #ffffff;
        }


        /* =========================================================
           RESPONSIVE
        ========================================================= */

        @media (max-width: 768px) {

            .panitia-card-header {
                align-items: flex-start;

                flex-direction: column;
            }

            .btn-panitia-upload {
                width: 100%;

                justify-content: center;
            }

            .panitia-toolbar {
                padding: 12px;
            }

            .panitia-search-wrapper {
                width: 100%;
            }

        }


        /* =========================================================
           DARK MODE
        ========================================================= */

        body.theme-dark .panitia-card,
        body.dark .panitia-card {
            background: #1e2633;

            border-color: #303a4b;
        }

        body.theme-dark .panitia-toolbar,
        body.dark .panitia-toolbar {
            border-color: #303a4b;
        }

        body.theme-dark .panitia-table thead th,
        body.dark .panitia-table thead th {
            background: #252e3d;

            color: #dce2e8;

            border-color: #303a4b;
        }

        body.theme-dark .panitia-table tbody td,
        body.dark .panitia-table tbody td {
            color: #cbd3dd;

            border-color: #303a4b;
        }

        body.theme-dark .panitia-table tbody tr:hover,
        body.dark .panitia-table tbody tr:hover {
            background: #252e3d;
        }

        body.theme-dark .panitia-search,
        body.dark .panitia-search,
        body.theme-dark .panitia-form-control,
        body.dark .panitia-form-control {
            background: #252e3d;

            color: #e4e9ef;

            border-color: #3a4658;
        }

        body.theme-dark .panitia-form-label,
        body.dark .panitia-form-label {
            color: #dce2e8;
        }

        body.theme-dark .panitia-file-wrapper,
        body.dark .panitia-file-wrapper {
            background: #252e3d;

            border-color: #3a4658;
        }

        body.theme-dark .panitia-modal .modal-content,
        body.dark .panitia-modal .modal-content {
            background: #1e2633;

            color: #dce2e8;
        }

        body.theme-dark .panitia-modal-footer,
        body.dark .panitia-modal-footer {
            border-color: #303a4b;
        }

        body.theme-dark .panitia-current-file,
        body.dark .panitia-current-file {
            background: #252e3d;

            border-color: #3a4658;
        }

        body.theme-dark .panitia-current-file-name,
        body.dark .panitia-current-file-name {
            color: #dce2e8;
        }

        body.theme-dark .delete-content h5,
        body.dark .delete-content h5 {
            color: #ffffff;
        }
    </style>


    <div class="page-heading">


        {{-- =====================================================
         HEADER HALAMAN
    ====================================================== --}}

        <div class="page-title">

            <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">

                <div>

                    <h3 class="mb-1">
                        Panitia
                    </h3>

                    <p class="text-subtitle text-muted mb-0">
                        Surat Keputusan &raquo; Panitia
                    </p>

                </div>

            </div>

        </div>


        {{-- =====================================================
         ALERT SUCCESS
    ====================================================== --}}

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mt-4" role="alert">

                <i class="bi bi-check-circle me-1"></i>

                {{ session('success') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert">
                </button>

            </div>
        @endif


        {{-- =====================================================
         ALERT ERROR
    ====================================================== --}}

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show mt-4" role="alert">

                <i class="bi bi-exclamation-circle me-1"></i>

                {{ session('error') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert">
                </button>

            </div>
        @endif


        {{-- =====================================================
         VALIDATION ERROR
    ====================================================== --}}

        @if ($errors->any())

            <div class="alert alert-danger alert-dismissible fade show mt-4" role="alert">

                <div class="fw-semibold mb-1">

                    <i class="bi bi-exclamation-triangle me-1"></i>

                    Terdapat kesalahan:

                </div>

                <ul class="mb-0 ps-4">

                    @foreach ($errors->all() as $error)
                        <li>
                            {{ $error }}
                        </li>
                    @endforeach

                </ul>

                <button type="button" class="btn-close" data-bs-dismiss="alert">
                </button>

            </div>

        @endif


        {{-- =====================================================
         CARD DATA PANITIA
    ====================================================== --}}

        <div class="panitia-page mt-4">

            <div class="panitia-card">


                {{-- =================================================
                 HEADER CARD
            ================================================== --}}

                <div class="panitia-card-header">

                    <div class="panitia-card-header-left">

                        <i class="bi bi-file-earmark-text"></i>

                        <div>

                            <h5 class="panitia-card-header-title">
                                Surat Keputusan Panitia
                            </h5>

                            <p class="panitia-card-header-subtitle">
                                Kelola dokumen Surat Keputusan Panitia
                            </p>

                        </div>

                    </div>


                    <button type="button" class="btn-panitia-upload" data-bs-toggle="modal"
                        data-bs-target="#modalUploadPanitia">

                        <i class="bi bi-cloud-arrow-up"></i>

                        Upload SK

                    </button>

                </div>


                {{-- =================================================
                 TOOLBAR
            ================================================== --}}

                <div class="panitia-toolbar">

                    <form
                        action="{{ route('admin.sipeter.surat-keputusan.panitia.index') }}"
                        method="GET">

                        <div class="panitia-search-wrapper">

                            <i class="bi bi-search"></i>

                            <input type="text" name="search" class="panitia-search" value="{{ $search ?? '' }}"
                                placeholder="Cari nomor SK, perihal, atau nama pegawai...">

                        </div>

                    </form>

                </div>


                {{-- =================================================
                 TABLE
            ================================================== --}}

                <div class="panitia-table-wrapper">

                    <table class="panitia-table">

                        <thead>

                            <tr>

                                <th class="panitia-number">
                                    No
                                </th>

                                <th>
                                    No. SK
                                </th>

                                <th>
                                    Tanggal SK
                                </th>

                                <th>
                                    Perihal
                                </th>

                                <th>
                                    Nama Pegawai
                                </th>

                                <th style="width: 125px;">
                                    Aksi
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse($data as $index => $item)
                                <tr>

                                    <td class="panitia-number">

                                        {{ $data->firstItem() + $index }}

                                    </td>


                                    <td>

                                        <span class="panitia-no-sk">

                                            {{ $item->no_sk }}

                                        </span>

                                    </td>


                                    <td>

                                        {{ $item->tanggal_sk ? $item->tanggal_sk->format('d/m/Y') : '-' }}

                                    </td>


                                    <td>

                                        {{ $item->perihal }}

                                    </td>


                                    <td>

                                        <div class="panitia-pegawai">

                                            @php

                                                $nama = $item->user->nama ?? '-';

                                                $initial = strtoupper(substr($nama, 0, 2));

                                            @endphp

                                            <div class="panitia-avatar">

                                                {{ $initial }}

                                            </div>

                                            <span class="panitia-pegawai-name">

                                                {{ $nama }}

                                            </span>

                                        </div>

                                    </td>


                                    <td>

                                        <div class="panitia-actions">


                                            {{-- LIHAT --}}

                                            <a href="{{ route('admin.sipeter.surat-keputusan.panitia.show', $item->id) }}"
                                                target="_blank" class="panitia-action panitia-action-view"
                                                title="Lihat PDF">

                                                <i class="bi bi-eye"></i>

                                            </a>


                                            {{-- EDIT --}}

                                            <button type="button" class="panitia-action panitia-action-edit" title="Edit"
                                                onclick="openEditPanitiaModal(
                                                    {{ $item->id }},
                                                    @js($item->no_sk),
                                                    @js($item->tanggal_sk ? $item->tanggal_sk->format('Y-m-d') : ''),
                                                    @js($item->perihal),
                                                    @js($item->file_pdf ? basename($item->file_pdf) : '')
                                                )">

                                                <i class="bi bi-pencil-square"></i>

                                            </button>


                                            {{-- HAPUS --}}

                                            <button type="button" class="panitia-action panitia-action-delete"
                                                title="Hapus"
                                                onclick="openDeletePanitiaModal(
                                                    {{ $item->id }},
                                                    @js($item->no_sk)
                                                )">

                                                <i class="bi bi-trash"></i>

                                            </button>

                                        </div>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="6" class="panitia-empty">

                                        <i class="bi bi-file-earmark-text"></i>

                                        <div class="panitia-empty-title">

                                            Belum ada Surat Keputusan Panitia

                                        </div>

                                        <div class="panitia-empty-text">

                                            Silakan upload Surat Keputusan terlebih dahulu.

                                        </div>

                                    </td>

                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>


                {{-- PAGINATION --}}

                @if ($data->hasPages())
                    <div class="panitia-pagination">

                        {{ $data->links() }}

                    </div>
                @endif

            </div>

        </div>

    </div>



    {{-- =========================================================
     MODAL UPLOAD
========================================================= --}}

    <div class="modal fade panitia-modal" id="modalUploadPanitia" tabindex="-1" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content">


                <div class="panitia-modal-header">

                    <h5>

                        <i class="bi bi-cloud-arrow-up"></i>

                        Upload Surat Keputusan

                    </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>

                </div>


                <form action="{{ route('admin.sipeter.surat-keputusan.panitia.store') }}"
                    method="POST" enctype="multipart/form-data">

                    @csrf


                    <div class="panitia-modal-body">


                        <div class="panitia-form-group">

                            <label class="panitia-form-label">

                                No. SK

                                <span class="panitia-required">
                                    *
                                </span>

                            </label>

                            <input type="text" name="no_sk" class="panitia-form-control"
                                placeholder="Masukkan nomor SK" value="{{ old('no_sk') }}" required>

                        </div>


                        <div class="panitia-form-group">

                            <label class="panitia-form-label">

                                Tanggal SK

                                <span class="panitia-required">
                                    *
                                </span>

                            </label>

                            <input type="date" name="tanggal_sk" class="panitia-form-control"
                                value="{{ old('tanggal_sk') }}" required>

                        </div>


                        <div class="panitia-form-group">

                            <label class="panitia-form-label">

                                Perihal

                                <span class="panitia-required">
                                    *
                                </span>

                            </label>

                            <textarea name="perihal" class="panitia-form-control" placeholder="Masukkan perihal SK" required>{{ old('perihal') }}</textarea>

                        </div>


                        <div class="panitia-form-group mb-0">

                            <label class="panitia-form-label">

                                File PDF

                                <span class="panitia-required">
                                    *
                                </span>

                            </label>

                            <div class="panitia-file-wrapper">

                                <input type="file" name="file_pdf" accept=".pdf,application/pdf" required>

                                <div class="panitia-file-info">

                                    <i class="bi bi-info-circle"></i>

                                    Format PDF, maksimal 10 MB.

                                </div>

                            </div>

                        </div>

                    </div>


                    <div class="panitia-modal-footer">

                        <button type="button" class="btn-panitia-cancel" data-bs-dismiss="modal">

                            Batal

                        </button>

                        <button type="submit" class="btn-panitia-save">

                            <i class="bi bi-cloud-arrow-up me-1"></i>

                            Upload SK

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>



    {{-- =========================================================
     MODAL EDIT
========================================================= --}}

    <div class="modal fade panitia-modal" id="modalEditPanitia" tabindex="-1" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content">


                <div class="panitia-modal-header">

                    <h5>

                        <i class="bi bi-pencil-square"></i>

                        Edit Surat Keputusan

                    </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>

                </div>


                <form method="POST" id="formEditPanitia" enctype="multipart/form-data">

                    @csrf

                    @method('PUT')


                    <div class="panitia-modal-body">


                        <div class="panitia-form-group">

                            <label class="panitia-form-label">

                                No. SK

                                <span class="panitia-required">
                                    *
                                </span>

                            </label>

                            <input type="text" name="no_sk" id="editNoSk" class="panitia-form-control" required>

                        </div>


                        <div class="panitia-form-group">

                            <label class="panitia-form-label">

                                Tanggal SK

                                <span class="panitia-required">
                                    *
                                </span>

                            </label>

                            <input type="date" name="tanggal_sk" id="editTanggalSk" class="panitia-form-control"
                                required>

                        </div>


                        <div class="panitia-form-group">

                            <label class="panitia-form-label">

                                Perihal

                                <span class="panitia-required">
                                    *
                                </span>

                            </label>

                            <textarea name="perihal" id="editPerihal" class="panitia-form-control" required></textarea>

                        </div>


                        <div class="panitia-current-file">

                            <div class="panitia-current-file-info">

                                <i class="bi bi-file-earmark-pdf"></i>

                                <span class="panitia-current-file-name" id="editCurrentFileName">

                                    -

                                </span>

                            </div>


                            <a href="#" id="editCurrentFileLink" target="_blank"
                                class="panitia-current-file-link">

                                <i class="bi bi-eye me-1"></i>

                                Lihat

                            </a>

                        </div>


                        <div class="panitia-form-group mb-0">

                            <label class="panitia-form-label">

                                Ganti File PDF

                            </label>

                            <div class="panitia-file-wrapper">

                                <input type="file" name="file_pdf" accept=".pdf,application/pdf">

                                <div class="panitia-file-info">

                                    <i class="bi bi-info-circle"></i>

                                    Kosongkan jika PDF tidak ingin diganti.
                                    Maksimal 10 MB.

                                </div>

                            </div>

                        </div>

                    </div>


                    <div class="panitia-modal-footer">

                        <button type="button" class="btn-panitia-cancel" data-bs-dismiss="modal">

                            Batal

                        </button>

                        <button type="submit" class="btn-panitia-save">

                            <i class="bi bi-check-lg me-1"></i>

                            Simpan Perubahan

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>



    {{-- =========================================================
     MODAL HAPUS
========================================================= --}}

    <div class="modal fade panitia-modal" id="modalHapusPanitia" tabindex="-1" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered modal-sm">

            <div class="modal-content">


                <div class="delete-content">

                    <div class="delete-icon">

                        <i class="bi bi-trash"></i>

                    </div>


                    <h5>
                        Hapus Surat Keputusan?
                    </h5>


                    <p>

                        Apakah Anda yakin ingin menghapus
                        SK

                        <span class="delete-no-sk" id="deleteNoSk">

                            -

                        </span>

                        ?

                        <br>

                        File PDF juga akan dihapus secara permanen.

                    </p>

                </div>


                {{-- FOOTER HAPUS DI TENGAH --}}

                <div class="panitia-modal-footer">

                    <button type="button" class="btn-panitia-cancel" data-bs-dismiss="modal">

                        Batal

                    </button>


                    <form method="POST" id="deleteFormPanitia">

                        @csrf

                        @method('DELETE')

                        <button type="submit" class="btn-delete-confirm">

                            <i class="bi bi-trash me-1"></i>

                            Ya, Hapus

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>



    <script>
        /*
        |--------------------------------------------------------------------------
        | BASE URL
        |--------------------------------------------------------------------------
        */

        const panitiaBaseUrl = @json(route('admin.sipeter.surat-keputusan.panitia.index'));


        /*
        |--------------------------------------------------------------------------
        | EDIT MODAL
        |--------------------------------------------------------------------------
        */

        function openEditPanitiaModal(
            id,
            noSk,
            tanggalSk,
            perihal,
            fileName
        ) {

            document.getElementById(
                'editNoSk'
            ).value = noSk || '';


            document.getElementById(
                'editTanggalSk'
            ).value = tanggalSk || '';


            document.getElementById(
                'editPerihal'
            ).value = perihal || '';


            /*
            |--------------------------------------------------------------------------
            | ACTION FORM
            |--------------------------------------------------------------------------
            */

            const editForm =
                document.getElementById(
                    'formEditPanitia'
                );


            editForm.action =
                panitiaBaseUrl.replace(/\/$/, '') +
                '/' +
                id;


            /*
            |--------------------------------------------------------------------------
            | FILE LAMA
            |--------------------------------------------------------------------------
            */

            const currentFileName =
                document.getElementById(
                    'editCurrentFileName'
                );


            const currentFileLink =
                document.getElementById(
                    'editCurrentFileLink'
                );


            if (fileName) {

                currentFileName.textContent =
                    fileName;


                currentFileLink.href =
                    panitiaBaseUrl.replace(/\/$/, '') +
                    '/' +
                    id +
                    '/lihat';

            } else {

                currentFileName.textContent =
                    'Tidak ada file PDF';

                currentFileLink.removeAttribute(
                    'href'
                );

            }


            /*
            |--------------------------------------------------------------------------
            | SHOW MODAL
            |--------------------------------------------------------------------------
            */

            const modalElement =
                document.getElementById(
                    'modalEditPanitia'
                );


            const modal =
                bootstrap.Modal.getOrCreateInstance(
                    modalElement
                );


            modal.show();
        }


        /*
        |--------------------------------------------------------------------------
        | DELETE MODAL
        |--------------------------------------------------------------------------
        */

        function openDeletePanitiaModal(
            id,
            noSk
        ) {

            document.getElementById(
                    'deleteNoSk'
                ).textContent =
                noSk || '-';


            const deleteForm =
                document.getElementById(
                    'deleteFormPanitia'
                );


            deleteForm.action =
                panitiaBaseUrl.replace(/\/$/, '') +
                '/' +
                id;


            const modalElement =
                document.getElementById(
                    'modalHapusPanitia'
                );


            const modal =
                bootstrap.Modal.getOrCreateInstance(
                    modalElement
                );


            modal.show();
        }
    </script>

@endsection
