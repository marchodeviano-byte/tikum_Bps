@extends('layouts.app')

@section('content')

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
    </style>

    {{-- =========================================================
 ALERT
========================================================== --}}

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
                    Input Nilai Kinerja -
                    {{ $periode['nama'] }}
                    ({{ implode(' - ', array_values($periode['bulan'])) }})
                </h3>

            </div>

            <span class="tahun-badge">

                <i class="bi bi-calendar-check"></i>

                Tahun: {{ $tahun }}

            </span>

        </div>


        {{-- =====================================================
         PILIH TAHUN + TRIWULAN
    ====================================================== --}}

        <form action="{{ route('admin.penilaian.index') }}" method="GET" id="formPeriode">

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


                <div class="periode-divider"></div>


                <div class="periode-selector-item">

                    <i class="bi bi-calendar-range"></i>

                    <span class="periode-selector-label">
                        Triwulan
                    </span>

                    <select name="triwulan" class="periode-selector"
                        onchange="document.getElementById('formPeriode').submit();">

                        <option value="1" @selected((int) $triwulan === 1)>
                            Triwulan 1
                        </option>

                        <option value="2" @selected((int) $triwulan === 2)>
                            Triwulan 2
                        </option>

                        <option value="3" @selected((int) $triwulan === 3)>
                            Triwulan 3
                        </option>

                        <option value="4" @selected((int) $triwulan === 4)>
                            Triwulan 4
                        </option>

                    </select>

                </div>

            </div>

        </form>


        {{-- =====================================================
         METODE INPUT
    ====================================================== --}}

        <div class="mode-card">

            <div class="mode-card-header">

                <i class="bi bi-sliders"></i>

                Metode Penginputan Nilai

            </div>


            <div class="mode-card-body">

                <div class="mode-options">

                    <label class="mode-option" for="modeBulanan">

                        <input type="radio" name="mode_display" id="modeBulanan" value="bulanan"
                            @checked(($modeDefault ?? 'bulanan') === 'bulanan')>

                        <span class="mode-dot"></span>

                        <span>
                            Per Bulan (3x Input)
                        </span>

                    </label>


                    <label class="mode-option" for="modeTriwulan">

                        <input type="radio" name="mode_display" id="modeTriwulan" value="triwulan"
                            @checked(($modeDefault ?? 'bulanan') === 'triwulan')>

                        <span class="mode-dot"></span>

                        <span>
                            Per Triwulan (1x Input)
                        </span>

                    </label>

                </div>

            </div>

        </div>


        {{-- =====================================================
         FORM PENILAIAN
    ====================================================== --}}

        <form action="{{ route('admin.penilaian.store') }}" method="POST" id="formPenilaian">

            @csrf

            <input type="hidden" name="tahun" value="{{ $tahun }}">

            <input type="hidden" name="triwulan" value="{{ $triwulan }}">

            <input type="hidden" name="mode_input" id="modeInput" value="{{ $modeDefault ?? 'bulanan' }}">

            <input type="hidden" name="mode_edit" id="modeEdit" value="0">


            {{-- =================================================
             MODE BULANAN
        ================================================== --}}

            <div id="tableBulanan" @if (($modeDefault ?? 'bulanan') === 'triwulan') style="display:none;" @endif>

                <div class="input-table-card">

                    <div class="input-table-header">

                        <i class="bi bi-table"></i>

                        Penginputan Nilai Kinerja Per Bulan

                    </div>


                    <div class="table-wrapper">

                        <table class="table-penilaian">

                            <thead>

                                <tr>

                                    <th class="nama-header" style="width: 31%;">
                                        Nama Pegawai
                                    </th>

                                    @foreach ($periode['bulan'] as $nomorBulan => $namaBulan)
                                        <th style="width: 11%;">
                                            {{ $namaBulan }}
                                        </th>
                                    @endforeach

                                    <th style="width: 14%;">
                                        Jumlah Nilai Kinerja
                                    </th>

                                    <th style="width: 12%;">
                                        Aksi
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @forelse($pegawai as $user)
                                    @php

                                        $data = $dataPenilaian[$user->id] ?? null;

                                        $nilai1 = $data?->nilai_bulan_1;

                                        $nilai2 = $data?->nilai_bulan_2;

                                        $nilai3 = $data?->nilai_bulan_3;

                                        $nilai1Display =
                                            $nilai1 !== null ? number_format((float) $nilai1, 2, ',', '') : '0,00';

                                        $nilai2Display =
                                            $nilai2 !== null ? number_format((float) $nilai2, 2, ',', '') : '0,00';

                                        $nilai3Display =
                                            $nilai3 !== null ? number_format((float) $nilai3, 2, ',', '') : '0,00';

                                        $adaData = $data !== null;

                                        $rataRata =
                                            ((float) ($nilai1 ?? 0) + (float) ($nilai2 ?? 0) + (float) ($nilai3 ?? 0)) /
                                            3;
                                    @endphp


                                    <tr data-user-id="{{ $user->id }}" data-nama="{{ $user->nama }}">

                                        <td class="nama-pegawai">

                                            {{ $user->nama }}

                                        </td>


                                        <td class="nilai-column">

                                            <input type="text" class="form-control input-nilai"
                                                name="nilai_bulan_1[{{ $user->id }}]" value="{{ $nilai1Display }}"
                                                autocomplete="off" @readonly($adaData)>

                                        </td>


                                        <td class="nilai-column">

                                            <input type="text" class="form-control input-nilai"
                                                name="nilai_bulan_2[{{ $user->id }}]" value="{{ $nilai2Display }}"
                                                autocomplete="off" @readonly($adaData)>

                                        </td>


                                        <td class="nilai-column">

                                            <input type="text" class="form-control input-nilai"
                                                name="nilai_bulan_3[{{ $user->id }}]" value="{{ $nilai3Display }}"
                                                autocomplete="off" @readonly($adaData)>

                                        </td>


                                        <td class="total-column">

                                            <input type="text" class="form-control input-total"
                                                value="{{ number_format($rataRata, 2, ',', '') }}" readonly>

                                        </td>


                                        <td class="text-center">

                                            <button type="button" class="btn btn-danger btn-reset" data-reset-row>

                                                <i class="bi bi-arrow-counterclockwise"></i>

                                                Reset

                                            </button>

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td colspan="7" class="text-center text-muted py-4">
                                            Belum ada pegawai aktif.
                                        </td>

                                    </tr>
                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>


            {{-- =================================================
             MODE TRIWULAN
        ================================================== --}}

            <div id="tableTriwulan" @if (($modeDefault ?? 'bulanan') !== 'triwulan') style="display:none;" @endif>

                <div class="input-table-card">

                    <div class="input-table-header">

                        <i class="bi bi-table"></i>

                        Penginputan Nilai Kinerja {{ $periode['nama'] }}

                    </div>


                    <div class="table-wrapper">

                        <table class="table-penilaian">

                            <thead>

                                <tr>

                                    <th class="nama-header" style="width: 45%;">
                                        Nama Pegawai
                                    </th>

                                    <th style="width: 25%;">
                                        Nilai {{ $periode['nama'] }}
                                    </th>

                                    <th style="width: 15%;">
                                        Aksi
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @forelse($pegawai as $user)
                                    @php

                                        $data = $dataPenilaian[$user->id] ?? null;

                                        $nilaiTriwulan = $data?->nilai_triwulan;

                                        $nilaiTriwulanDisplay =
                                            $nilaiTriwulan !== null
                                                ? number_format((float) $nilaiTriwulan, 2, ',', '')
                                                : '0,00';

                                        $adaDataTriwulan = $data !== null;
                                    @endphp


                                    <tr data-user-id="{{ $user->id }}" data-nama="{{ $user->nama }}">

                                        <td class="nama-pegawai">

                                            {{ $user->nama }}

                                        </td>


                                        <td class="triwulan-column">

                                            <input type="text" class="form-control input-triwulan"
                                                name="nilai_triwulan[{{ $user->id }}]"
                                                value="{{ $nilaiTriwulanDisplay }}" autocomplete="off" @readonly($adaDataTriwulan)>

                                        </td>


                                        <td class="text-center">

                                            <button type="button" class="btn btn-danger btn-reset" data-reset-row>

                                                <i class="bi bi-arrow-counterclockwise"></i>

                                                Reset

                                            </button>

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td colspan="4" class="text-center text-muted py-4">
                                            Belum ada pegawai aktif.
                                        </td>

                                    </tr>
                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>


            {{-- =================================================
             BUTTON BAWAH
        ================================================== --}}

            <div class="bottom-actions">

                <div class="bottom-left">

                    <button type="button" class="btn btn-warning" id="editSemua">

                        <i class="bi bi-pencil-square"></i>

                        Edit Semua

                    </button>


                    <button type="button" class="btn btn-secondary" id="resetSemua">

                        <i class="bi bi-arrow-counterclockwise"></i>

                        Reset Semua

                    </button>

                </div>


                <div class="bottom-right">

                    <button type="submit" class="btn btn-success" id="simpanNilai">

                        <i class="bi bi-check-lg"></i>

                        Simpan Nilai

                    </button>

                </div>

            </div>

        </form>

    </div>


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
                Apakah Anda yakin ingin mereset nilai?
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

            /* =========================================================
               ELEMENT
            ========================================================= */

            const modeBulanan =
                document.getElementById('modeBulanan');

            const modeTriwulan =
                document.getElementById('modeTriwulan');

            const tableBulanan =
                document.getElementById('tableBulanan');

            const tableTriwulan =
                document.getElementById('tableTriwulan');

            const modeInput =
                document.getElementById('modeInput');

            const modeEdit =
                document.getElementById('modeEdit');

            const editSemua =
                document.getElementById('editSemua');

            const resetSemua =
                document.getElementById('resetSemua');

            const formPenilaian =
                document.getElementById('formPenilaian');

            const simpanNilai =
                document.getElementById('simpanNilai');


            /* =========================================================
               MODAL
            ========================================================= */

            const resetModal =
                document.getElementById('resetModal');

            const resetModalMessage =
                document.getElementById('resetModalMessage');

            const cancelReset =
                document.getElementById('cancelReset');

            const confirmReset =
                document.getElementById('confirmReset');

            let resetCallback = null;


            /* =========================================================
               OPEN MODAL
            ========================================================= */

            function openResetModal(message, callback) {

                resetModalMessage.innerHTML =
                    message;

                resetCallback =
                    callback;

                resetModal.classList.add('show');

                resetModal.setAttribute(
                    'aria-hidden',
                    'false'
                );

                document.body.style.overflow =
                    'hidden';
            }


            /* =========================================================
               CLOSE MODAL
            ========================================================= */

            function closeResetModal() {

                resetModal.classList.remove(
                    'show'
                );

                resetModal.setAttribute(
                    'aria-hidden',
                    'true'
                );

                document.body.style.overflow =
                    '';

                resetCallback = null;
            }


            /* =========================================================
               BATAL
            ========================================================= */

            cancelReset.addEventListener(
                'click',
                function() {

                    closeResetModal();

                }
            );


            /* =========================================================
               KONFIRMASI RESET
            ========================================================= */

            confirmReset.addEventListener(
                'click',
                function() {

                    if (
                        typeof resetCallback ===
                        'function'
                    ) {

                        resetCallback();

                    }

                    closeResetModal();

                }
            );


            /* =========================================================
               KLIK LUAR MODAL
            ========================================================= */

            resetModal.addEventListener(
                'click',
                function(event) {

                    if (
                        event.target ===
                        resetModal
                    ) {

                        closeResetModal();

                    }

                }
            );


            /* =========================================================
               ESC
            ========================================================= */

            document.addEventListener(
                'keydown',
                function(event) {

                    if (
                        event.key === 'Escape' &&
                        resetModal.classList.contains('show')
                    ) {

                        closeResetModal();

                    }

                }
            );


            /* =========================================================
               MODE BULANAN
            ========================================================= */

            modeBulanan.addEventListener(
                'change',
                function() {

                    if (!this.checked) {
                        return;
                    }

                    tableBulanan.style.display =
                        'block';

                    tableTriwulan.style.display =
                        'none';

                    modeInput.value =
                        'bulanan';

                }
            );


            /* =========================================================
               MODE TRIWULAN
            ========================================================= */

            modeTriwulan.addEventListener(
                'change',
                function() {

                    if (!this.checked) {
                        return;
                    }

                    tableBulanan.style.display =
                        'none';

                    tableTriwulan.style.display =
                        'block';

                    modeInput.value =
                        'triwulan';

                }
            );


            /* =========================================================
               KONVERSI ANGKA
            ========================================================= */

            function parseNilai(value) {

                if (
                    value === null ||
                    value === undefined ||
                    value === ''
                ) {

                    return 0;

                }

                const parsed =
                    parseFloat(
                        String(value)
                        .replace(',', '.')
                    );

                return isNaN(parsed) ?
                    0 :
                    parsed;
            }


            /* =========================================================
               FORMAT NILAI
            ========================================================= */

            function formatNilai(value) {

                return Number(value)
                    .toFixed(2)
                    .replace('.', ',');
            }


            /* =========================================================
               HITUNG RATA-RATA
            ========================================================= */

            function hitungTotal(row) {

                if (!row) {
                    return;
                }

                const inputs =
                    row.querySelectorAll(
                        '.input-nilai'
                    );

                const totalInput =
                    row.querySelector(
                        '.input-total'
                    );

                if (!totalInput) {
                    return;
                }

                let total = 0;

                let jumlah = 0;

                inputs.forEach(
                    function(input) {

                        total +=
                            parseNilai(
                                input.value
                            );

                        jumlah++;

                    }
                );

                if (jumlah === 0) {

                    totalInput.value =
                        '0,00';

                    return;

                }

                const rataRata =
                    total / jumlah;

                totalInput.value =
                    formatNilai(
                        rataRata
                    );

            }


            /* =========================================================
               HITUNG SEMUA
            ========================================================= */

            function hitungSemuaTotal() {

                document
                    .querySelectorAll(
                        '#tableBulanan tbody tr'
                    )
                    .forEach(
                        function(row) {

                            hitungTotal(row);

                        }
                    );

            }


            /* =========================================================
               UPDATE TOTAL SAAT INPUT
            ========================================================= */

            document
                .querySelectorAll(
                    '.input-nilai'
                )
                .forEach(
                    function(input) {

                        input.addEventListener(
                            'input',
                            function() {

                                hitungTotal(
                                    this.closest('tr')
                                );

                            }
                        );

                    }
                );


            /* =========================================================
               RESET SATU PEGAWAI
               NILAI -> 0
               INPUT -> AKTIF
            ========================================================= */

            document
                .querySelectorAll(
                    '[data-reset-row]'
                )
                .forEach(
                    function(button) {

                        button.addEventListener(
                            'click',
                            function() {

                                const row =
                                    this.closest('tr');

                                if (!row) {
                                    return;
                                }

                                const nama =
                                    row.dataset.nama ||
                                    row.querySelector(
                                        '.nama-pegawai'
                                    )?.textContent.trim() ||
                                    'pegawai ini';


                                openResetModal(

                                    `
                                Apakah Anda yakin ingin
                                mereset nilai
                                <strong>${nama}</strong>?
                                <br>
                                <small>
                                    Nilai akan menjadi
                                    <strong>0</strong>
                                    dan dapat diinput ulang.
                                </small>
                            `,

                                    function() {

                                        /*
                                         * ==================================
                                         * UBAH SEMUA INPUT BARIS MENJADI 0
                                         * ==================================
                                         */

                                        row
                                            .querySelectorAll(
                                                '.input-nilai, .input-triwulan'
                                            )
                                            .forEach(
                                                function(input) {

                                                    input.value =
                                                        '0,00';

                                                    /*
                                                     * BUKA INPUT
                                                     */
                                                    input.removeAttribute(
                                                        'readonly'
                                                    );

                                                }
                                            );


                                        /*
                                         * Tandai mode edit.
                                         */
                                        modeEdit.value =
                                            '1';


                                        /*
                                         * Hitung ulang
                                         * jika mode bulanan.
                                         */
                                        hitungTotal(row);

                                    }

                                );

                            }
                        );

                    }
                );


            /* =========================================================
               RESET SEMUA
               SEMUA NILAI -> 0
            ========================================================= */

            resetSemua.addEventListener(
                'click',
                function() {

                    openResetModal(

                        `
                    Apakah Anda yakin ingin
                    <strong>mereset semua nilai</strong>?
                    <br>
                    <small>
                        Semua nilai akan menjadi
                        <strong>0</strong>
                        dan seluruh pegawai dapat
                        diinput ulang.
                    </small>
                `,

                        function() {

                            /*
                             * ==================================
                             * RESET BULANAN
                             * ==================================
                             */

                            document
                                .querySelectorAll(
                                    '#tableBulanan .input-nilai'
                                )
                                .forEach(
                                    function(input) {

                                        input.value =
                                            '0,00';

                                        input.removeAttribute(
                                            'readonly'
                                        );

                                    }
                                );


                            /*
                             * ==================================
                             * RESET TRIWULAN
                             * ==================================
                             */

                            document
                                .querySelectorAll(
                                    '#tableTriwulan .input-triwulan'
                                )
                                .forEach(
                                    function(input) {

                                        input.value =
                                            '0,00';

                                        input.removeAttribute(
                                            'readonly'
                                        );

                                    }
                                );


                            /*
                             * Mode edit aktif.
                             */
                            modeEdit.value =
                                '1';


                            /*
                             * Hitung ulang rata-rata.
                             */
                            hitungSemuaTotal();

                        }

                    );

                }
            );


            /* =========================================================
               EDIT SEMUA
            ========================================================= */

            editSemua.addEventListener(
                'click',
                function() {

                    document
                        .querySelectorAll(
                            '#tableBulanan .input-nilai, #tableTriwulan .input-triwulan'
                        )
                        .forEach(
                            function(input) {

                                input.removeAttribute(
                                    'readonly'
                                );

                            }
                        );


                    modeEdit.value =
                        '1';


                    editSemua.innerHTML =
                        '<i class="bi bi-check-lg"></i> Mode Edit Aktif';

                }
            );


            /* =========================================================
               SUBMIT
            ========================================================= */

            formPenilaian.addEventListener(
                'submit',
                function() {

                    simpanNilai.disabled =
                        true;

                    simpanNilai.innerHTML =
                        '<i class="bi bi-hourglass-split"></i> Menyimpan...';

                }
            );


            /* =========================================================
               INITIAL
            ========================================================= */

            hitungSemuaTotal();

        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
