@extends('layouts.app')

@section('content')

    @php
        // Format angka: 3 desimal, koma sebagai pemisah desimal (contoh 12,500)
        $fmt = fn($angka) => number_format((float) $angka, 3, ',', '');
    @endphp

    <style>
        /* =========================================================
                                                   HALAMAN INPUT NILAI KINERJA
                                                   DESIGN TETAP SEPERTI SEBELUMNYA
                                                ========================================================= */

        .penilaian-input-page {
            padding: 22px 5px 40px;
        }


        /* =========================================================
                                                   HEADER
                                                ========================================================= */

        .page-header-input {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-wrap: wrap;
            gap: 14px;
            margin-bottom: 26px;
        }

        .penilaian-title {
            color: #07549b !important;
            font-size: 25px;
            font-weight: 700;
            line-height: 1.35;
            margin: 0 0 8px 0;
            max-width: 720px;
        }

        .tahun-badge {
            display: inline-flex;
            align-items: center;
            gap: 7px;

            padding: 9px 15px;
            border-radius: 7px;

            background: #ffffff !important;
            color: #607080 !important;
            border: 1px solid #d9e0e7;

            font-size: 14px;
            font-weight: 500;
        }

        .tahun-badge i {
            color: #07549b !important;
        }


        /* =========================================================
                                                   SELECTOR TAHUN + TRIWULAN
                                                ========================================================= */

        .periode-selector-card {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;

            background: #ffffff !important;
            border: 1px solid #dce2e8;
            border-radius: 9px;

            padding: 15px 20px;

            margin-bottom: 24px;
        }

        .periode-selector-item {
            display: flex;
            align-items: center;
            gap: 9px;
        }

        .periode-selector-item i {
            color: #07549b;
            font-size: 16px;
        }

        .periode-selector-label {
            color: #465466 !important;
            font-size: 14px;
            font-weight: 600;
            white-space: nowrap;
        }

        .periode-selector {
            height: 39px;

            min-width: 135px;

            padding: 0 34px 0 12px;

            border: 1px solid #d7dee7 !important;
            border-radius: 6px;

            background-color: #ffffff !important;
            color: #29313a !important;

            font-size: 14px;
            font-weight: 600;

            cursor: pointer;
        }

        .periode-selector:focus {
            border-color: #07549b !important;
            box-shadow: 0 0 0 3px rgba(7, 84, 155, .12);
            outline: none;
        }

        .periode-divider {
            width: 1px;
            height: 27px;
            background: #dce2e8;
            margin: 0 4px;
        }


        /* =========================================================
                                                   CARD METODE INPUT
                                                ========================================================= */

        .mode-card {
            background: #ffffff !important;
            border: 1px solid #dce2e8;
            border-radius: 9px;

            overflow: hidden;
            margin-bottom: 24px;
        }

        .mode-card-header {
            display: flex;
            align-items: center;
            gap: 9px;

            padding: 16px 22px;

            color: #07549b !important;
            background: #ffffff !important;
            border-bottom: 1px solid #eef1f5;

            font-size: 15px;
            font-weight: 700;
        }

        .mode-card-header i {
            font-size: 16px;
        }

        .mode-card-body {
            padding: 18px 22px 20px;
        }

        .mode-options {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .mode-option {
            position: relative;

            flex: 1;
            min-width: 220px;

            display: flex;
            align-items: center;
            gap: 12px;

            padding: 13px 16px;

            background: #ffffff !important;
            border: 1px solid #dce3eb;
            border-radius: 8px;

            cursor: pointer;
            transition: all .18s ease;
        }

        .mode-option:hover {
            border-color: #9bbce0;
            background: #f8fbff !important;
        }

        .mode-option input {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        .mode-option .mode-dot {
            width: 20px;
            height: 20px;
            min-width: 20px;

            border-radius: 50%;
            border: 2px solid #c3cddb;

            display: flex;
            align-items: center;
            justify-content: center;

            transition: all .18s ease;
        }

        .mode-option .mode-dot::after {
            content: '';

            width: 10px;
            height: 10px;

            border-radius: 50%;

            background: #07549b;

            transform: scale(0);

            transition: transform .15s ease;
        }

        .mode-option>span:last-child {
            margin: 0;

            color: #465466 !important;

            font-size: 14.5px;
            font-weight: 600;

            cursor: pointer;
        }

        .mode-option:has(input:checked) {
            border-color: #07549b;
            background: #eef5ff !important;
        }

        .mode-option:has(input:checked) .mode-dot {
            border-color: #07549b;
        }

        .mode-option:has(input:checked) .mode-dot::after {
            transform: scale(1);
        }

        .mode-option:has(input:checked)>span:last-child {
            color: #07549b !important;
        }


        /* =========================================================
                                                   CARD TABEL
                                                ========================================================= */

        .input-table-card {
            background: #ffffff !important;
            border: 1px solid #d8e0e8;
            border-radius: 9px;

            overflow: hidden;
        }

        .input-table-header {
            display: flex;
            align-items: center;
            gap: 9px;

            background: #075a9f !important;
            color: #ffffff !important;

            padding: 16px 22px;

            font-size: 15.5px;
            font-weight: 700;
        }

        .input-table-header i {
            font-size: 16px;
        }


        /* =========================================================
                                                   TABLE
                                                ========================================================= */

        .table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        .table-penilaian {
            width: 100%;
            min-width: 1050px;

            margin: 0;

            border-collapse: collapse;
        }

        .table-penilaian th,
        .table-penilaian td {
            border: 1px solid #e2e7ed !important;

            padding: 12px 14px;

            vertical-align: middle;
        }

        .table-penilaian thead th {
            height: 54px;

            background: #f8fafc !important;
            color: #07549b !important;

            font-size: 13.5px;
            font-weight: 700;

            text-align: center;
        }

        .table-penilaian thead th.nama-header {
            text-align: left;
            padding-left: 18px;
        }

        .table-penilaian tbody td {
            height: 60px;

            background: #ffffff !important;
            color: #29313a !important;

            font-size: 14.5px;
        }

        .table-penilaian tbody tr:hover td {
            background: #f8fbff !important;
        }

        .table-penilaian tbody tr:hover td.total-column,
        .table-penilaian tbody tr:hover td.triwulan-column {
            background: #e3f0ff !important;
        }


        /* =========================================================
                                                   NAMA PEGAWAI
                                                ========================================================= */

        .nama-pegawai {
            color: #1d2733 !important;

            font-weight: 700;
            text-align: left;

            padding-left: 18px !important;
        }

        .role-column {
            text-align: center;
        }


        /* =========================================================
                                                   ROLE
                                                ========================================================= */

        .role-badge {
            display: inline-block;

            background: #eef1f4 !important;
            color: #344454 !important;

            padding: 5px 11px;

            border-radius: 5px;

            font-size: 12.5px;
            font-weight: 500;
        }


        /* =========================================================
                                                   INPUT NILAI
                                                ========================================================= */

        .nilai-column {
            text-align: center;
        }

        .input-nilai {
            width: 95px;
            height: 38px;

            margin: auto;

            border: 1px solid #d7dee7 !important;
            border-radius: 6px;

            background: #ffffff !important;
            color: #29313a !important;

            text-align: center;

            font-size: 15px;
            font-weight: 500;

            transition:
                border-color .15s ease,
                box-shadow .15s ease;
        }

        .input-nilai:focus {
            border-color: #07549b !important;

            box-shadow: 0 0 0 3px rgba(7, 84, 155, .12);

            outline: none;
        }

        .input-nilai[readonly] {
            background: #f8fafc !important;
            color: #7c8a9a !important;
            cursor: default;
        }


        /* =========================================================
                                                   JUMLAH NILAI
                                                ========================================================= */

        .total-column {
            background: #eef5ff !important;
            text-align: center;
        }

        .input-total {
            width: 108px;
            height: 38px;

            margin: auto;

            border: 1px solid #b9d3ee !important;
            border-radius: 6px;

            background: transparent !important;
            color: #07549b !important;

            text-align: center;

            font-size: 15px;
            font-weight: 700;
        }


        /* =========================================================
                                                   INPUT TRIWULAN
                                                ========================================================= */

        .triwulan-column {
            background: #eef5ff !important;
            text-align: center;
        }

        .input-triwulan {
            width: 108px;
            height: 38px;

            margin: auto;

            border: 1px solid #07549b !important;
            border-radius: 6px;

            background: #ffffff !important;
            color: #07549b !important;

            text-align: center;

            font-size: 15px;
            font-weight: 700;

            transition:
                border-color .15s ease,
                box-shadow .15s ease;
        }

        .input-triwulan:focus {
            outline: none;

            border-color: #07549b !important;

            box-shadow: 0 0 0 3px rgba(7, 84, 155, .12);
        }


        /* =========================================================
                                                   RESET PER BARIS
                                                ========================================================= */

        .btn-reset {
            min-width: 96px;
            height: 38px;

            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 5px;

            border-radius: 6px;
            border: none;

            background: #fdecee !important;
            color: #dc3545 !important;

            font-size: 13.5px;
            font-weight: 600;

            transition: all .15s ease;
        }

        .btn-reset:hover {
            background: #dc3545 !important;
            color: #ffffff !important;
        }


        /* =========================================================
                                                   BUTTON BAWAH
                                                ========================================================= */

        .bottom-actions {
            display: flex;

            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;

            margin-top: 22px;
            padding-top: 20px;

            border-top: 1px solid #e2e7ed;

            gap: 12px;
        }

        .bottom-left,
        .bottom-right {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .bottom-actions .btn {
            min-width: 132px;
            height: 44px;

            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;

            border-radius: 7px;

            font-size: 14.5px;
            font-weight: 600;
        }

        .bottom-actions .btn-success {
            background: #198754 !important;
            border-color: #198754 !important;
        }

        .bottom-actions .btn-success:hover {
            background: #157347 !important;
            border-color: #157347 !important;
        }

        .bottom-actions .btn-light {
            background: #ffffff !important;
            border: 1px solid #d9e0e7 !important;
            color: #465466 !important;
        }

        .bottom-actions .btn-light:hover {
            background: #f5f8fc !important;
        }


        /* =========================================================
                                                   CUSTOM RESET MODAL
                                                ========================================================= */

        .reset-modal-overlay {
            position: fixed;
            inset: 0;

            display: flex;
            align-items: center;
            justify-content: center;

            padding: 20px;

            background: rgba(0, 0, 0, .45);

            opacity: 0;
            visibility: hidden;
            pointer-events: none;

            z-index: 99999;

            transition:
                opacity .18s ease,
                visibility .18s ease;
        }

        .reset-modal-overlay.show {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
        }

        .reset-modal {
            width: 100%;
            max-width: 430px;

            background: #ffffff;

            border-radius: 9px;

            overflow: hidden;

            box-shadow: 0 18px 45px rgba(0, 0, 0, .20);

            transform: translateY(12px) scale(.97);

            transition: transform .18s ease;
        }

        .reset-modal-overlay.show .reset-modal {
            transform: translateY(0) scale(1);
        }

        .reset-modal-top {
            padding: 25px 25px 10px;

            text-align: center;
        }

        .reset-modal-icon {
            width: 58px;
            height: 58px;

            margin: 0 auto 14px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 50%;

            background: #fdecee;
            color: #dc3545;

            font-size: 25px;
        }

        .reset-modal-title {
            margin: 0;

            color: #29313a;

            font-size: 19px;
            font-weight: 700;
        }

        .reset-modal-body {
            padding: 8px 25px 20px;

            text-align: center;

            color: #607080;

            font-size: 14px;
            line-height: 1.6;
        }

        .reset-modal-body strong {
            color: #29313a;
        }

        .reset-modal-footer {
            display: flex;

            justify-content: center;

            gap: 9px;

            padding: 0 25px 22px;
        }

        .reset-modal-btn {
            min-width: 115px;
            height: 40px;

            border-radius: 6px;

            border: none;

            font-size: 13.5px;
            font-weight: 600;

            cursor: pointer;

            transition: all .15s ease;
        }

        .reset-modal-cancel {
            background: #f1f3f5;
            color: #465466;
        }

        .reset-modal-cancel:hover {
            background: #e2e6ea;
        }

        .reset-modal-confirm {
            background: #dc3545;
            color: #ffffff;
        }

        .reset-modal-confirm:hover {
            background: #bb2d3b;
        }


        /* =========================================================
                                                   DARK MODE
                                                ========================================================= */

        body.theme-dark .penilaian-input-page,
        html.theme-dark .penilaian-input-page {
            color: #c9c9d6;
        }

        body.theme-dark .penilaian-title,
        html.theme-dark .penilaian-title {
            color: #8db9ff !important;
        }

        body.theme-dark .tahun-badge,
        html.theme-dark .tahun-badge {
            background: #252436 !important;
            color: #c8ceda !important;
            border-color: #3a394e !important;
        }

        body.theme-dark .tahun-badge i,
        html.theme-dark .tahun-badge i {
            color: #8db9ff !important;
        }

        body.theme-dark .periode-selector-card,
        html.theme-dark .periode-selector-card {
            background: #1f1e2e !important;
            border-color: #353448 !important;
        }

        body.theme-dark .periode-selector-label,
        html.theme-dark .periode-selector-label {
            color: #c3c8d5 !important;
        }

        body.theme-dark .periode-selector,
        html.theme-dark .periode-selector {
            background: #242334 !important;
            color: #d4d8e2 !important;
            border-color: #3c3b50 !important;
        }

        body.theme-dark .periode-selector-item i,
        html.theme-dark .periode-selector-item i {
            color: #8db9ff;
        }

        body.theme-dark .periode-divider,
        html.theme-dark .periode-divider {
            background: #3a394e;
        }

        body.theme-dark .mode-card,
        html.theme-dark .mode-card {
            background: #1f1e2e !important;
            border-color: #353448 !important;
        }

        body.theme-dark .mode-card-header,
        html.theme-dark .mode-card-header {
            background: #1f1e2e !important;
            color: #8db9ff !important;
            border-bottom-color: #353448;
        }

        body.theme-dark .mode-option,
        html.theme-dark .mode-option {
            background: #242334 !important;
            border-color: #3c3b50;
        }

        body.theme-dark .mode-option>span:last-child,
        html.theme-dark .mode-option>span:last-child {
            color: #c3c8d5 !important;
        }

        body.theme-dark .mode-option:has(input:checked),
        html.theme-dark .mode-option:has(input:checked) {
            border-color: #8db9ff;
            background: #29283d !important;
        }

        body.theme-dark .mode-option:has(input:checked)>span:last-child {
            color: #8db9ff !important;
        }

        body.theme-dark .input-table-card,
        html.theme-dark .input-table-card {
            background: #1f1e2e !important;
            border-color: #353448 !important;
        }

        body.theme-dark .table-penilaian th,
        body.theme-dark .table-penilaian td,
        html.theme-dark .table-penilaian th,
        html.theme-dark .table-penilaian td {
            border-color: #3a394e !important;
        }

        body.theme-dark .table-penilaian thead th,
        html.theme-dark .table-penilaian thead th {
            background: #242334 !important;
            color: #a9c6ff !important;
        }

        body.theme-dark .table-penilaian tbody td,
        html.theme-dark .table-penilaian tbody td {
            background: #1c1b2b !important;
            color: #d4d8e2 !important;
        }

        body.theme-dark .nama-pegawai,
        html.theme-dark .nama-pegawai {
            color: #f0f2f7 !important;
        }

        body.theme-dark .role-badge,
        html.theme-dark .role-badge {
            background: #343746 !important;
            color: #d7dce5 !important;
        }

        body.theme-dark .input-nilai,
        html.theme-dark .input-nilai {
            background: #242334 !important;
            color: #d4d8e2 !important;
            border-color: #3c3b50 !important;
        }

        body.theme-dark .input-nilai[readonly],
        html.theme-dark .input-nilai[readonly] {
            background: #1c1b2b !important;
            color: #8a93a6 !important;
        }

        body.theme-dark .total-column,
        html.theme-dark .total-column {
            background: #16375c !important;
        }

        body.theme-dark .input-total,
        html.theme-dark .input-total {
            border-color: #3c6ea5 !important;
            color: #a9c6ff !important;
        }

        body.theme-dark .triwulan-column,
        html.theme-dark .triwulan-column {
            background: #16375c !important;
        }

        body.theme-dark .input-triwulan,
        html.theme-dark .input-triwulan {
            background: #242334 !important;
            color: #a9c6ff !important;
            border-color: #8db9ff !important;
        }

        body.theme-dark .btn-reset,
        html.theme-dark .btn-reset {
            background: #402029 !important;
            color: #ff8a95 !important;
        }

        body.theme-dark .btn-reset:hover,
        html.theme-dark .btn-reset:hover {
            background: #dc3545 !important;
            color: #ffffff !important;
        }

        body.theme-dark .bottom-actions,
        html.theme-dark .bottom-actions {
            border-top-color: #353448;
        }

        body.theme-dark .bottom-actions .btn-light,
        html.theme-dark .bottom-actions .btn-light {
            background: #242334 !important;
            border-color: #3c3b50 !important;
            color: #c3c8d5 !important;
        }

        body.theme-dark .reset-modal,
        html.theme-dark .reset-modal {
            background: #1f1e2e;
        }

        body.theme-dark .reset-modal-title,
        html.theme-dark .reset-modal-title {
            color: #f0f2f7;
        }

        body.theme-dark .reset-modal-body {
            color: #adb5c4;
        }

        body.theme-dark .reset-modal-body strong {
            color: #ffffff;
        }

        body.theme-dark .reset-modal-cancel {
            background: #343746;
            color: #d7dce5;
        }


        /* =========================================================
                                                   RESPONSIVE
                                                ========================================================= */

        @media (max-width: 768px) {

            .penilaian-input-page {
                padding: 15px 5px;
            }

            .penilaian-title {
                font-size: 20px;
            }

            .periode-selector-card {
                align-items: stretch;
                flex-direction: column;
            }

            .periode-selector-item {
                width: 100%;
            }

            .periode-selector {
                flex: 1;
            }

            .periode-divider {
                display: none;
            }

            .mode-options {
                flex-direction: column;
            }

            .mode-option {
                width: 100%;
            }

            .bottom-actions {
                flex-direction: column;
                align-items: stretch;
            }

            .bottom-left,
            .bottom-right {
                width: 100%;
            }

            .bottom-actions .btn {
                flex: 1;
            }
        }
    
        /* =========================================================
           TAMBAHAN KHUSUS ANGKA KREDIT
        ========================================================= */
        .input-ak {
            width: 125px;
        }

        .input-ak-total {
            width: 125px;
        }

        .ak-hint {
            display: block;
            margin-top: 4px;
            color: #8a96a3;
            font-size: 11px;
            line-height: 1.3;
        }

        .ak-hint.manual {
            color: #07549b;
            font-weight: 600;
        }

        .table-penilaian tbody td.ak-awal-column {
            text-align: center;
            vertical-align: middle;
        }

        .input-nilai::placeholder {
            color: #98a4b3;
            opacity: 1;
        }

        body.theme-dark .ak-hint,
        html.theme-dark .ak-hint {
            color: #969eb0;
        }

        body.theme-dark .ak-hint.manual,
        html.theme-dark .ak-hint.manual {
            color: #8db9ff;
        }

        body.theme-dark .input-nilai::placeholder,
        html.theme-dark .input-nilai::placeholder {
            color: #7d8598;
        }
    
    </style>

    {{-- =========================================================
     ALERT
========================================================= --}}
    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
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
            document.addEventListener('DOMContentLoaded', function() {
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

    <div class="penilaian-input-page">

        {{-- =====================================================
         HEADER
    ====================================================== --}}
        <div class="page-header-input">

            <div>
                <h3 class="penilaian-title">
                    Angka Kredit Pegawai
                </h3>
            </div>

            <span class="tahun-badge">
                <i class="bi bi-calendar-check"></i>
                Tahun: {{ $tahun }}
            </span>

        </div>


        {{-- =====================================================
         PILIH TAHUN
    ====================================================== --}}
        <form action="{{ route('admin.skp.angka-kredit.index') }}" method="GET" id="formPeriode">

            <div class="periode-selector-card">

                <div class="periode-selector-item">

                    <i class="bi bi-calendar3"></i>

                    <span class="periode-selector-label">
                        Tahun
                    </span>

                    <select name="tahun" class="periode-selector" onchange="this.form.submit()">
                        @foreach ($tahunOptions as $optionTahun)
                            <option value="{{ $optionTahun }}" @selected((int) $optionTahun === (int) $tahun)>
                                {{ $optionTahun }}
                            </option>
                        @endforeach
                    </select>

                </div>

            </div>

        </form>


        {{-- =====================================================
         FORM ANGKA KREDIT
    ====================================================== --}}
        <form action="{{ route('admin.skp.angka-kredit.simpan') }}" method="POST" id="formAngkaKredit">

            @csrf

            <input type="hidden" name="tahun" value="{{ $tahun }}">

            <div class="input-table-card">

                <div class="input-table-header">
                    <i class="bi bi-table"></i>
                    Angka Kredit Tahun {{ $tahun }}
                </div>

                <div class="table-wrapper">

                    <table class="table-penilaian">

                        <thead>
                            <tr>
                                <th class="nama-header" style="width: 26%;">
                                    Nama Pegawai
                                </th>

                                <th style="width: 16%;">
                                    Angka Kredit Awal
                                </th>

                                <th style="width: 15%;">
                                    Semester 1
                                </th>

                                <th style="width: 15%;">
                                    Semester 2
                                </th>

                                <th style="width: 16%;">
                                    Angka Kredit Akhir
                                </th>

                                <th style="width: 12%;">
                                    Aksi
                                </th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($pegawai as $user)
                                @php
                                    $ak = $dataAngkaKredit[$user->id] ?? null;

                                    $awalManual = $ak['awal_manual'] ?? null;
                                    $awalOtomatis = (float) ($ak['awal'] ?? 0);
                                    $sumber = $ak['sumber'] ?? 'kosong';
                                @endphp

                                <tr data-user-id="{{ $user->id }}" data-nama="{{ $user->nama }}">

                                    <td class="nama-pegawai">
                                        {{ $user->nama }}
                                    </td>

                                    {{-- ANGKA KREDIT AWAL --}}
                                    <td class="nilai-column ak-awal-column">

                                        <input type="text" class="form-control input-nilai input-ak input-awal"
                                            name="awal[{{ $user->id }}]"
                                            value="{{ $awalManual !== null ? $fmt($awalManual) : '' }}"
                                            placeholder="{{ $fmt($awalOtomatis) }}"
                                            data-otomatis="{{ $awalOtomatis }}" autocomplete="off"
                                            inputmode="decimal">

                                        @if ($awalManual !== null)
                                            <small class="ak-hint manual">Manual</small>
                                        @elseif ($sumber === 'otomatis')
                                            <small class="ak-hint">Otomatis dari {{ $tahun - 1 }}</small>
                                        @else
                                            <small class="ak-hint">Belum ada angka dasar</small>
                                        @endif

                                    </td>

                                    {{-- SEMESTER 1 --}}
                                    <td class="nilai-column">
                                        <input type="text" class="form-control input-nilai input-ak input-semester"
                                            name="semester_1[{{ $user->id }}]"
                                            value="{{ $fmt($ak['semester_1'] ?? 0) }}" autocomplete="off"
                                            inputmode="decimal">
                                    </td>

                                    {{-- SEMESTER 2 --}}
                                    <td class="nilai-column">
                                        <input type="text" class="form-control input-nilai input-ak input-semester"
                                            name="semester_2[{{ $user->id }}]"
                                            value="{{ $fmt($ak['semester_2'] ?? 0) }}" autocomplete="off"
                                            inputmode="decimal">
                                    </td>

                                    {{-- ANGKA KREDIT AKHIR (otomatis) --}}
                                    <td class="total-column">
                                        <input type="text" class="form-control input-total input-ak-total input-akhir"
                                            value="{{ $fmt($ak['akhir'] ?? 0) }}" readonly>
                                    </td>

                                    {{-- AKSI --}}
                                    <td class="text-center">
                                        <button type="button" class="btn btn-danger btn-reset" data-reset-row>
                                            <i class="bi bi-arrow-counterclockwise"></i>
                                            Reset
                                        </button>
                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        Belum ada pegawai aktif.
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>


            {{-- =================================================
             AKSI BAWAH
        ================================================== --}}
            <div class="bottom-actions">

                <div class="bottom-left">

                    <button type="button" class="btn btn-secondary" id="resetSemua">
                        <i class="bi bi-arrow-counterclockwise"></i>
                        Reset Semua
                    </button>

                </div>

                <div class="bottom-right">

                    <button type="submit" class="btn btn-success" id="simpanAngkaKredit">
                        <i class="bi bi-check-lg"></i>
                        Simpan
                    </button>

                </div>

            </div>

        </form>

    </div>


    {{-- FORM RESET (dikirim lewat popup konfirmasi) --}}
    <form action="{{ route('admin.skp.angka-kredit.reset') }}" method="POST" id="formReset" class="d-none">
        @csrf
        <input type="hidden" name="tahun" value="{{ $tahun }}">
        <input type="hidden" name="user_id" id="resetUserId" value="">
    </form>


    {{-- =========================================================
     CUSTOM POPUP RESET
========================================================= --}}
    <div id="resetModal" class="reset-modal-overlay" aria-hidden="true">

        <div class="reset-modal" role="dialog" aria-modal="true">

            <div class="reset-modal-top">

                <div class="reset-modal-icon">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </div>

                <h5 class="reset-modal-title">
                    Konfirmasi Reset
                </h5>

            </div>

            <div class="reset-modal-body" id="resetModalMessage">
                Apakah Anda yakin ingin mereset angka kredit?
            </div>

            <div class="reset-modal-footer">

                <button type="button" class="reset-modal-btn reset-modal-cancel" id="cancelReset">
                    Batal
                </button>

                <button type="button" class="reset-modal-btn reset-modal-confirm" id="confirmReset">
                    Ya, Reset
                </button>

            </div>

        </div>

    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const form = document.getElementById('formAngkaKredit');
            const tahun = @json($tahun);

            /* =========================================================
               FORMAT ANGKA
               Koma = desimal. Kalau ada koma, titik = pemisah ribuan.
            ========================================================= */
            function parseAngka(teks) {

                let nilai = String(teks ?? '').trim().replace(/\s/g, '');

                if (nilai === '') {
                    return null;
                }

                if (nilai.includes(',')) {
                    nilai = nilai.replace(/\./g, '').replace(',', '.');
                }

                const angka = parseFloat(nilai);

                return Number.isFinite(angka) ? angka : null;
            }

            function formatAngka(angka) {
                return (Math.round(angka * 1000) / 1000)
                    .toFixed(3)
                    .replace('.', ',');
            }

            /* =========================================================
               HITUNG ANGKA KREDIT AKHIR PER BARIS
               akhir = awal (manual, atau otomatis kalau kosong) + sem 1 + sem 2
            ========================================================= */
            function hitungBaris(tr) {

                const inputAwal = tr.querySelector('.input-awal');
                const [sem1, sem2] = tr.querySelectorAll('.input-semester');

                const awalManual = parseAngka(inputAwal.value);
                const awal = awalManual !== null ?
                    awalManual :
                    parseFloat(inputAwal.dataset.otomatis || 0);

                const total = awal +
                    (parseAngka(sem1.value) ?? 0) +
                    (parseAngka(sem2.value) ?? 0);

                tr.querySelector('.input-akhir').value = formatAngka(total);
            }

            // Hanya angka, koma, dan titik
            form.addEventListener('input', function(e) {

                if (!e.target.classList.contains('input-nilai')) {
                    return;
                }

                e.target.value = e.target.value.replace(/[^0-9.,]/g, '');

                hitungBaris(e.target.closest('tr'));
            });

            // Pilih seluruh isi kolom saat fokus supaya mudah diganti
            form.addEventListener('focusin', function(e) {

                if (e.target.classList.contains('input-nilai')) {
                    e.target.select();
                }
            });

            // Enter di kolom isian tidak langsung submit (cegah simpan tak sengaja)
            form.addEventListener('keydown', function(e) {

                if (e.key === 'Enter' && e.target.classList.contains('input-nilai')) {
                    e.preventDefault();
                }
            });

            // Tombol simpan: cegah klik ganda
            form.addEventListener('submit', function() {

                const tombol = document.getElementById('simpanAngkaKredit');

                tombol.disabled = true;
                tombol.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Menyimpan...';
            });


            /* =========================================================
               POPUP RESET
            ========================================================= */
            const resetModal = document.getElementById('resetModal');
            const resetMessage = document.getElementById('resetModalMessage');
            const resetUserId = document.getElementById('resetUserId');
            const formReset = document.getElementById('formReset');

            function bukaResetModal(pesan, userId) {

                resetMessage.innerHTML = pesan;
                resetUserId.value = userId || '';

                resetModal.classList.add('show');
                resetModal.setAttribute('aria-hidden', 'false');
                document.body.style.overflow = 'hidden';
            }

            function tutupResetModal() {

                resetModal.classList.remove('show');
                resetModal.setAttribute('aria-hidden', 'true');
                document.body.style.overflow = '';
            }

            function escapeHtml(teks) {
                const div = document.createElement('div');
                div.textContent = teks;
                return div.innerHTML;
            }

            // Reset satu pegawai
            document.querySelectorAll('[data-reset-row]').forEach(function(tombol) {

                tombol.addEventListener('click', function() {

                    const tr = this.closest('tr');

                    bukaResetModal(
                        'Apakah Anda yakin ingin mereset angka kredit <strong>' +
                        escapeHtml(tr.dataset.nama) + '</strong> tahun ' + tahun + '?',
                        tr.dataset.userId
                    );
                });
            });

            // Reset semua pegawai
            document.getElementById('resetSemua').addEventListener('click', function() {

                bukaResetModal(
                    'Apakah Anda yakin ingin mereset angka kredit <strong>semua pegawai</strong> tahun ' +
                    tahun + '?',
                    ''
                );
            });

            document.getElementById('cancelReset').addEventListener('click', tutupResetModal);

            resetModal.addEventListener('click', function(e) {

                if (e.target === resetModal) {
                    tutupResetModal();
                }
            });

            document.addEventListener('keydown', function(e) {

                if (e.key === 'Escape' && resetModal.classList.contains('show')) {
                    tutupResetModal();
                }
            });

            document.getElementById('confirmReset').addEventListener('click', function() {

                this.disabled = true;
                this.textContent = 'Mereset...';

                formReset.submit();
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection