@extends('layouts.app')

@section('content')

    @php
        $daftarBulan = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember',
        ];

        $daftarTriwulan = [
            '1' => 'Jan - Mar',
            '2' => 'Apr - Jun',
            '3' => 'Jul - Sep',
            '4' => 'Okt - Des',
        ];

        // Aturan dokumen per jenis evaluasi:
        // - Bulanan & Triwulan -> hanya Dokumen Evaluasi Kinerja Pegawai
        //                         Pendekatan Hasil Kerja Kualitatif
        // - Tahunan            -> kedua dokumen
        $pakaiDokumenEvaluasi = ($jenisAktif ?? 'bulanan') === 'tahunan';
    @endphp

    <style>
        /* =====================================================
                                       SKP PAGE
                                    ===================================================== */
        .skp-page {
            padding-bottom: 30px;
        }

        /* ================= HEADER ================= */
        .skp-heading {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 25px;
        }

        .skp-heading h3 {
            color: #07549b !important;
            font-size: 26px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .skp-heading p {
            color: #7c8a9a !important;
            margin-bottom: 0;
        }

        .periode-badge {
            background: #ffffff !important;
            color: #607080 !important;
            border: 1px solid #d9e0e7;
            padding: 10px 16px;
            border-radius: 7px;
            font-size: 14px;
        }

        /* ================= FILTER ================= */
        .skp-filter-card {
            background: #ffffff !important;
            border: 1px solid #dce2e8;
            border-radius: 9px;
            margin-bottom: 28px;
        }

        .skp-filter-card .card-body {
            padding: 22px;
        }

        .skp-filter-title {
            color: #07549b !important;
            font-size: 17px;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .skp-filter-card .form-label {
            color: #465466 !important;
            font-weight: 500;
        }

        .skp-filter-card .form-select {
            color: #526170 !important;
            background-color: #ffffff !important;
            border-color: #d7dee7 !important;
        }

        .monitoring-readonly {
            height: 42px;
            display: flex;
            align-items: center;
            gap: 8px;
            background: #f7f9fb !important;
            color: #667585 !important;
            border-color: #d7dee7 !important;
        }

        /* ================= JENIS MONITORING (TYPE BUTTONS) ================= */
        .monitoring-type-wrapper {
            border-bottom: 1px solid #e7ebf0;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }

        .monitoring-type-buttons {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .monitoring-type-btn {
            flex: 1;
            min-width: 180px;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            background: #ffffff !important;
            border: 1px solid #dce3eb;
            border-radius: 8px;
            color: #526170 !important;
            text-align: left;
            transition: all .2s ease;
        }

        .monitoring-type-btn>i {
            width: 38px;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            background: #eef5ff;
            color: #07549b !important;
            font-size: 18px;
            flex-shrink: 0;
        }

        .monitoring-type-btn span {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .monitoring-type-btn strong {
            font-size: 14px;
            color: #263646 !important;
        }

        .monitoring-type-btn small {
            font-size: 12px;
            color: #8a96a3 !important;
        }

        .monitoring-type-btn:hover {
            border-color: #9bbce0;
            background: #f8fbff !important;
        }

        .monitoring-type-btn.active {
            border-color: #07549b;
            background: #eef5ff !important;
        }

        .monitoring-type-btn.active>i {
            background: #07549b !important;
            color: #ffffff !important;
        }

        .monitoring-type-btn.active strong {
            color: #07549b !important;
        }

        /* ================= MAIN CARD ================= */
        .skp-main-card {
            background: #ffffff !important;
            border: none;
            border-radius: 12px;
            box-shadow: 0 3px 15px rgba(0, 0, 0, .04);
        }

        .skp-main-card .card-body {
            padding: 26px;
        }

        /* ================= BLUE HEADER ================= */
        .skp-section-header {
            background: #075a9f !important;
            color: #ffffff !important;
            padding: 18px 22px;
            border-radius: 8px 8px 0 0;
        }

        .skp-section-header h5 {
            color: #ffffff !important;
            margin: 0;
            font-size: 17px;
            font-weight: 700;
        }

        .skp-section-header small {
            display: block;
            color: #ffffff !important;
            margin-top: 5px;
            opacity: .95;
        }

        /* ================= EMPLOYEE INFO ================= */
        .pegawai-info {
            background: #f7f9fc !important;
            border: 1px solid #dce2e8;
            border-radius: 8px;
            padding: 17px 20px;
            margin-bottom: 20px;
        }

        .pegawai-info-label {
            color: #7a8794 !important;
            font-size: 12px;
            margin-bottom: 4px;
        }

        .pegawai-info-name {
            color: #1d2733 !important;
            font-size: 16px;
            font-weight: 700;
        }

        /* ================= TABLE ================= */
        .skp-table-wrapper {
            border: 1px solid #d8e0e8;
            border-radius: 8px;
            overflow: hidden;
        }

        .skp-table {
            width: 100%;
            margin-bottom: 0;
        }

        .skp-table th,
        .skp-table td {
            border: 1px solid #d9e0e7 !important;
            vertical-align: middle;
            padding: 15px;
        }

        .skp-table thead th {
            background: #f8fafc !important;
            color: #07549b !important;
            text-align: center;
            font-weight: 700;
        }

        .skp-table tbody td {
            background: #ffffff !important;
            color: #29313a !important;
        }

        .skp-table .periode {
            text-align: center;
            font-weight: 600;
            color: #1f2933 !important;
        }

        /* ================= STATUS ================= */
        .status-belum {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: #fff3cd;
            color: #856404;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-sudah {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: #d1e7dd;
            color: #0f5132;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
        }

        /* ================= UPLOAD BUTTON ================= */
        .btn-upload-pdf {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            min-width: 125px;
            padding: 9px 15px;
            border: none;
            border-radius: 6px;
            background: #dc3545 !important;
            color: #ffffff !important;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all .2s ease;
        }

        .btn-upload-pdf:hover {
            background: #bb2d3b !important;
            color: #ffffff !important;
            transform: translateY(-1px);
        }

        .file-info {
            display: block;
            margin-top: 6px;
            color: #dc3545 !important;
            font-size: 11px;
            font-weight: 600;
        }

        /* ================= PETUNJUK ================= */
        .skp-petunjuk {
            margin-top: 18px;
            color: #687789 !important;
            font-size: 14px;
        }

        .skp-petunjuk strong {
            color: #343d48 !important;
        }

        /* =====================================================
                                       DARK MODE
                                    ===================================================== */
        body.theme-dark .skp-heading h3,
        html.theme-dark .skp-heading h3 {
            color: #8db9ff !important;
        }

        body.theme-dark .skp-heading p,
        html.theme-dark .skp-heading p {
            color: #aeb5c5 !important;
        }

        body.theme-dark .periode-badge,
        html.theme-dark .periode-badge {
            background: #252436 !important;
            color: #c8ceda !important;
            border-color: #3a394e !important;
        }

        /* FILTER */
        body.theme-dark .skp-filter-card,
        html.theme-dark .skp-filter-card {
            background: #1f1e2e !important;
            border-color: #353448 !important;
        }

        body.theme-dark .skp-filter-title,
        html.theme-dark .skp-filter-title {
            color: #8db9ff !important;
        }

        body.theme-dark .skp-filter-card .form-label,
        html.theme-dark .skp-filter-card .form-label {
            color: #c3c8d5 !important;
        }

        body.theme-dark .skp-filter-card .form-select,
        html.theme-dark .skp-filter-card .form-select {
            background: #242334 !important;
            color: #d4d8e2 !important;
            border-color: #3c3b50 !important;
        }

        body.theme-dark .monitoring-readonly,
        html.theme-dark .monitoring-readonly {
            background: #242334 !important;
            color: #aeb7c7 !important;
            border-color: #3c3b50 !important;
        }

        /* JENIS MONITORING DARK */
        body.theme-dark .monitoring-type-wrapper,
        html.theme-dark .monitoring-type-wrapper {
            border-bottom-color: #353448;
        }

        body.theme-dark .monitoring-type-btn,
        html.theme-dark .monitoring-type-btn {
            background: #242334 !important;
            border-color: #3c3b50;
            color: #c3c8d5 !important;
        }

        body.theme-dark .monitoring-type-btn>i,
        html.theme-dark .monitoring-type-btn>i {
            background: #29283d;
            color: #8db9ff !important;
        }

        body.theme-dark .monitoring-type-btn strong,
        html.theme-dark .monitoring-type-btn strong {
            color: #e2e5ec !important;
        }

        body.theme-dark .monitoring-type-btn small,
        html.theme-dark .monitoring-type-btn small {
            color: #929bad !important;
        }

        body.theme-dark .monitoring-type-btn:hover,
        html.theme-dark .monitoring-type-btn:hover {
            border-color: #6288ff;
            background: #29283d !important;
        }

        body.theme-dark .monitoring-type-btn.active,
        html.theme-dark .monitoring-type-btn.active {
            border-color: #8db9ff;
            background: #29283d !important;
        }

        body.theme-dark .monitoring-type-btn.active>i,
        html.theme-dark .monitoring-type-btn.active>i {
            background: #8db9ff !important;
            color: #1f1e2e !important;
        }

        body.theme-dark .monitoring-type-btn.active strong,
        html.theme-dark .monitoring-type-btn.active strong {
            color: #8db9ff !important;
        }

        /* MAIN CARD */
        body.theme-dark .skp-main-card,
        html.theme-dark .skp-main-card {
            background: #1f1e2e !important;
            box-shadow: none;
        }

        /* BLUE HEADER */
        body.theme-dark .skp-section-header,
        html.theme-dark .skp-section-header {
            background: #075a9f !important;
            color: #ffffff !important;
        }

        body.theme-dark .skp-section-header h5,
        html.theme-dark .skp-section-header h5,
        body.theme-dark .skp-section-header small,
        html.theme-dark .skp-section-header small {
            color: #ffffff !important;
        }

        /* EMPLOYEE INFO */
        body.theme-dark .pegawai-info,
        html.theme-dark .pegawai-info {
            background: #242334 !important;
            border-color: #3a394e !important;
        }

        body.theme-dark .pegawai-info-label,
        html.theme-dark .pegawai-info-label {
            color: #929bad !important;
        }

        body.theme-dark .pegawai-info-name,
        html.theme-dark .pegawai-info-name {
            color: #f0f2f7 !important;
        }

        /* TABLE */
        body.theme-dark .skp-table-wrapper,
        html.theme-dark .skp-table-wrapper {
            border-color: #3a394e !important;
        }

        body.theme-dark .skp-table th,
        body.theme-dark .skp-table td,
        html.theme-dark .skp-table th,
        html.theme-dark .skp-table td {
            border-color: #3a394e !important;
        }

        body.theme-dark .skp-table thead th,
        html.theme-dark .skp-table thead th {
            background: #242334 !important;
            color: #a9c6ff !important;
        }

        body.theme-dark .skp-table tbody td,
        html.theme-dark .skp-table tbody td {
            background: #1c1b2b !important;
            color: #d4d8e2 !important;
        }

        body.theme-dark .skp-table .periode,
        html.theme-dark .skp-table .periode {
            color: #f0f2f7 !important;
        }

        /* STATUS DARK */
        body.theme-dark .status-belum,
        html.theme-dark .status-belum {
            background: #4a3d16 !important;
            color: #ffd970 !important;
        }

        body.theme-dark .status-sudah,
        html.theme-dark .status-sudah {
            background: #183c2d !important;
            color: #6ee7a8 !important;
        }

        /* PDF DARK */
        body.theme-dark .btn-upload-pdf,
        html.theme-dark .btn-upload-pdf {
            background: #dc3545 !important;
            color: #ffffff !important;
        }

        body.theme-dark .btn-upload-pdf:hover,
        html.theme-dark .btn-upload-pdf:hover {
            background: #e04b59 !important;
        }

        body.theme-dark .file-info,
        html.theme-dark .file-info {
            color: #ff7b87 !important;
        }

        /* PETUNJUK DARK */
        body.theme-dark .skp-petunjuk,
        html.theme-dark .skp-petunjuk {
            color: #aeb5c5 !important;
        }

        body.theme-dark .skp-petunjuk strong,
        html.theme-dark .skp-petunjuk strong {
            color: #e2e5ec !important;
        }

        .btn-lihat-keterangan {
            font-size: 11px;
            padding: 4px 8px;
            border-radius: 5px;
        }

        .keterangan-penolakan {
            white-space: pre-line;
            text-align: left;
            line-height: 1.6;
            color: #596575;
        }

        body.theme-dark .keterangan-penolakan,
        html.theme-dark .keterangan-penolakan {
            color: #c9ced8;
        }

        /* ================= DOKUMEN STATUS ================= */
        .dokumen-status-box {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 5px;
        }

        .dokumen-file-name {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            color: #4d5a68;
            font-size: 12px;
            font-weight: 600;
        }

        .dokumen-file-name span {
            max-width: 220px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .dokumen-badge {
            font-size: 11px;
            padding: 5px 9px;
        }

        .btn-lihat-pdf {
            font-size: 11px;
            padding: 4px 9px;
        }

        .contoh-dokumen {
            font-size: 11px !important;
        }

        .keterangan-status {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
        }

        .keterangan-status small {
            font-size: 11px;
        }

        .modal-keterangan {
            border: 0;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 15px 45px rgba(31, 41, 55, .18);
        }

        .modal-keterangan-header {
            background: #fff7f7;
            border-bottom: 1px solid #f3d6d9;
            padding: 18px 20px;
        }

        .modal-keterangan-header>div:first-child {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .modal-keterangan-header .modal-title {
            color: #2f3742;
            font-size: 16px;
            font-weight: 700;
            margin: 0;
        }

        .modal-keterangan-header small {
            color: #9a6b70;
            font-size: 12px;
        }

        .modal-icon-danger {
            width: 40px;
            height: 40px;
            border-radius: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #ffe3e5;
            color: #dc3545;
            font-size: 18px;
        }

        .keterangan-card {
            background: #fff8f8;
            border: 1px solid #f2d4d7;
            border-radius: 10px;
            padding: 14px;
        }

        .keterangan-card-title {
            color: #b02a37;
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .keterangan-penolakan {
            white-space: pre-line;
            text-align: left;
            line-height: 1.65;
            color: #4d5968;
            font-size: 14px;
        }

        .keterangan-hint {
            display: flex;
            gap: 8px;
            margin-top: 12px;
            padding: 10px 12px;
            border-radius: 9px;
            background: #f4f8ff;
            color: #5c6f85;
            font-size: 11px;
            line-height: 1.5;
        }

        .keterangan-hint i {
            color: #07549b;
            font-size: 14px;
            margin-top: 1px;
        }

        body.theme-dark .dokumen-file-name,
        html.theme-dark .dokumen-file-name {
            color: #d4d8e2;
        }

        body.theme-dark .modal-keterangan,
        html.theme-dark .modal-keterangan {
            background: #242334;
            color: #d4d8e2;
        }

        body.theme-dark .modal-keterangan-header,
        html.theme-dark .modal-keterangan-header {
            background: #2a2632;
            border-color: #463642;
        }

        body.theme-dark .modal-keterangan-header .modal-title,
        html.theme-dark .modal-keterangan-header .modal-title {
            color: #f0f2f7;
        }

        body.theme-dark .modal-keterangan-header small,
        html.theme-dark .modal-keterangan-header small {
            color: #d99ba2;
        }

        body.theme-dark .keterangan-card,
        html.theme-dark .keterangan-card {
            background: #2b2530;
            border-color: #4b3740;
        }

        body.theme-dark .keterangan-penolakan,
        html.theme-dark .keterangan-penolakan {
            color: #d4d8e2;
        }

        body.theme-dark .keterangan-hint,
        html.theme-dark .keterangan-hint {
            background: #242b3b;
            color: #b8c4d8;
        }

        /* ================= RESPONSIVE ================= */
        @media (max-width: 768px) {
            .skp-heading {
                flex-direction: column;
                gap: 12px;
            }

            .skp-heading h3 {
                font-size: 22px;
            }

            .skp-main-card .card-body {
                padding: 15px;
            }

            .skp-table {
                min-width: 750px;
            }
        }
    </style>

    <div class="skp-page">

        <div class="skp-heading">

            <div>
                <h3>SKP</h3>
                <p>Pengelolaan Sasaran Kinerja Pegawai</p>
            </div>

            <div class="periode-badge" id="periodeBadge">
                @if (($jenisAktif ?? 'bulanan') === 'bulanan')
                    Periode:
                    {{ $daftarBulan[str_pad((string) ($bulan ?? now()->month), 2, '0', STR_PAD_LEFT)] ?? 'Januari' }}
                    {{ $tahun }}
                @elseif (($jenisAktif ?? 'bulanan') === 'triwulan')
                    Periode: Triwulan {{ $triwulan ?? 1 }} ({{ $daftarTriwulan[(string) ($triwulan ?? 1)] ?? 'Jan - Mar' }})
                    {{ $tahun }}
                @else
                    Periode: Tahun {{ $tahun }}
                @endif
            </div>

        </div>


        <div class="card skp-filter-card">

            <div class="card-body">

                <div class="skp-filter-title">
                    <i class="bi bi-funnel"></i> Periode SKP Evaluasi
                </div>

                <div class="monitoring-type-wrapper">

                    <label class="form-label">
                        Jenis Evaluasi
                    </label>

                    <div class="monitoring-type-buttons">

                        <button type="button"
                            class="monitoring-type-btn {{ ($jenisAktif ?? 'bulanan') === 'bulanan' ? 'active' : '' }}"
                            data-type="bulanan">

                            <i class="bi bi-calendar-month"></i>

                            <span>
                                <strong>Bulanan</strong>
                                <small>Evaluasi per bulan</small>
                            </span>

                        </button>

                        <button type="button"
                            class="monitoring-type-btn {{ ($jenisAktif ?? 'bulanan') === 'triwulan' ? 'active' : '' }}"
                            data-type="triwulan">

                            <i class="bi bi-calendar3"></i>

                            <span>
                                <strong>Triwulan</strong>
                                <small>Evaluasi per triwulan</small>
                            </span>

                        </button>

                        <button type="button"
                            class="monitoring-type-btn {{ ($jenisAktif ?? 'bulanan') === 'tahunan' ? 'active' : '' }}"
                            data-type="tahunan">

                            <i class="bi bi-calendar-check"></i>

                            <span>
                                <strong>Tahunan</strong>
                                <small>Evaluasi per tahun</small>
                            </span>

                        </button>

                    </div>

                </div>


                <div class="row g-3 align-items-end">

                    <div class="col-md-4">

                        <label class="form-label">Tahun</label>

                        <select class="form-select" id="filterTahun">

                            @foreach ([2024, 2025, 2026, 2027] as $th)
                                <option value="{{ $th }}" {{ $tahun == $th ? 'selected' : '' }}>

                                    {{ $th }}

                                </option>
                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-4" id="wrapperBulan"
                        style="display: {{ ($jenisAktif ?? 'bulanan') === 'bulanan' ? 'block' : 'none' }};">

                        <label class="form-label">
                            Bulan
                        </label>

                        <select class="form-select" id="filterBulan">

                            @foreach ($daftarBulan as $kode => $namaBln)
                                <option value="{{ $kode }}"
                                    {{ (int) ($bulan ?? now()->month) === (int) $kode ? 'selected' : '' }}>

                                    {{ $namaBln }}

                                </option>
                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-4" id="wrapperTriwulan"
                        style="display: {{ ($jenisAktif ?? 'bulanan') === 'triwulan' ? 'block' : 'none' }};">

                        <label class="form-label">
                            Triwulan
                        </label>

                        <select class="form-select" id="filterTriwulan">

                            @foreach ($daftarTriwulan as $kode => $rentang)
                                <option value="{{ $kode }}"
                                    {{ (int) ($triwulan ?? 1) === (int) $kode ? 'selected' : '' }}>

                                    Triwulan {{ $kode }}
                                    ({{ $rentang }})
                                </option>
                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-4" id="wrapperPeriodeInfo">

                        <div class="form-control monitoring-readonly">

                            <i class="bi bi-calendar-range"></i>

                            <span id="periodeInfo">

                                @if (($jenisAktif ?? 'bulanan') === 'bulanan')
                                    {{ $daftarBulan[str_pad((string) ($bulan ?? now()->month), 2, '0', STR_PAD_LEFT)] ?? 'Januari' }}
                                @elseif (($jenisAktif ?? 'bulanan') === 'triwulan')
                                    Triwulan {{ $triwulan ?? 1 }}
                                    ({{ $daftarTriwulan[(string) ($triwulan ?? 1)] ?? 'Jan - Mar' }})
                                @else
                                    Satu Tahun
                                @endif

                            </span>

                        </div>

                    </div>


                    <div class="col-md-4">

                        <button type="button" class="btn btn-primary w-100" id="btnTampilkan">

                            <i class="bi bi-search"></i>
                            Tampilkan Data

                        </button>

                    </div>

                </div>

            </div>

        </div>


        <div class="card skp-main-card">

            <div class="card-body">

                <div class="tab-content">

                    <div class="monitoring-type-content">

                        <div class="skp-table-wrapper">

                            <div class="skp-section-header">

                                <div>

                                    <h5 id="judulEvaluasi">
                                        <i class="bi bi-clipboard-check"></i>
                                        Evaluasi SKP {{ ucfirst($jenisAktif ?? 'bulanan') }}
                                    </h5>

                                    <small id="subtitleEvaluasi">
                                        @if (($jenisAktif ?? 'bulanan') === 'bulanan')
                                            {{ $daftarBulan[str_pad((string) ($bulan ?? now()->month), 2, '0', STR_PAD_LEFT)] ?? 'Januari' }}
                                            {{ $tahun }}
                                        @elseif (($jenisAktif ?? 'bulanan') === 'triwulan')
                                            Triwulan {{ $triwulan ?? 1 }}
                                            ({{ $daftarTriwulan[(string) ($triwulan ?? 1)] ?? 'Jan - Mar' }})
                                            {{ $tahun }}
                                        @else
                                            Tahun {{ $tahun }}
                                        @endif
                                    </small>

                                </div>

                                <div class="skp-section-info">

                                    <i class="bi bi-info-circle"></i>

                                    <span>
                                        @if ($pakaiDokumenEvaluasi)
                                            Upload 2 dokumen evaluasi untuk setiap pegawai
                                        @else
                                            Upload dokumen evaluasi pendekatan hasil kerja kualitatif untuk setiap pegawai
                                        @endif
                                    </span>

                                </div>

                            </div>


                            <div class="table-responsive">

                                <table class="table skp-table mb-0" id="skp-table-evaluasi">

                                    <thead>

                                        <tr>

                                            <th style="width: 50px;">No</th>

                                            <th style="min-width: 200px;">Nama Pegawai</th>

                                            @if ($pakaiDokumenEvaluasi)
                                                <th class="text-center" style="min-width: 260px;">
                                                Dokumen Evaluasi Kinerja Pegawai
                                            </th>
                                            @endif

                                            <th class="text-center" style="min-width: 300px;">
                                                Dokumen Evaluasi Kinerja Pegawai<br>
                                                <span class="small fw-normal">
                                                    Pendekatan Hasil Kerja Kualitatif
                                                </span>
                                            </th>

                                            <th class="text-center" style="min-width: 190px;">
                                                Keterangan
                                            </th>

                                        </tr>

                                    </thead>


                                    <tbody>

                                        @forelse ($dataEvaluasi as $index => $item)
                                            @php

                                                $evaluasi = $item['evaluasi'] ?? null;

                                                // Bulanan & Triwulan: ambil data pada periode yang sedang dipilih
                                                if (!$evaluasi && !$pakaiDokumenEvaluasi && isset($item['evaluasiPeriode'])) {
                                                    $kodePeriode = ($jenisAktif ?? 'bulanan') === 'bulanan'
                                                        ? (int) ($bulan ?? now()->month)
                                                        : (int) ($triwulan ?? 1);

                                                    $evaluasi = $item['evaluasiPeriode']->get($kodePeriode);
                                                }

                                                $statusDokumen1 = $evaluasi?->status_dokumen_evaluasi;

                                                $statusDokumen2 = $evaluasi?->status_dokumen_kualitatif;

                                                // Hanya dokumen yang dipakai pada jenis evaluasi ini yang dihitung
                                                $dokumenAktif = $pakaiDokumenEvaluasi
                                                    ? ['evaluasi', 'kualitatif']
                                                    : ['kualitatif'];

                                                $adaDitolak = $evaluasi && collect($dokumenAktif)->contains(
                                                    fn($d) => $evaluasi->{'status_dokumen_' . $d} === 'ditolak'
                                                );

                                                $semuaDisetujui = $evaluasi && collect($dokumenAktif)->every(
                                                    fn($d) => !empty($evaluasi->{'dokumen_' . $d . '_path'}) &&
                                                        $evaluasi->{'status_dokumen_' . $d} === 'disetujui'
                                                );

                                                $semuaTerkirim = $evaluasi && collect($dokumenAktif)->every(
                                                    fn($d) => !empty($evaluasi->{'dokumen_' . $d . '_path'}) &&
                                                        in_array($evaluasi->{'status_dokumen_' . $d}, ['menunggu', 'disetujui'], true)
                                                );

                                                $status = $semuaDisetujui
                                                    ? 'disetujui'
                                                    : ($semuaTerkirim ? 'menunggu_persetujuan' : 'belum_lengkap');

                                            @endphp


                                            <tr>

                                                <td class="text-center">
                                                    {{ $index + 1 }}
                                                </td>

                                                <td class="nama-pegawai">
                                                    {{ $item['pegawai']->nama }}
                                                </td>

                                                {{-- Dokumen Evaluasi Kinerja: hanya untuk evaluasi Tahunan --}}
                                                @if ($pakaiDokumenEvaluasi)
                                                <td
                                                    class="upload-cell text-center {{ $evaluasi?->dokumen_evaluasi_path ? 'sudah-upload' : '' }}">

                                                    <div class="dokumen-status-box">

                                                        @if ($evaluasi?->dokumen_evaluasi_path)
                                                            <div class="dokumen-file-name">

                                                                <i class="bi bi-file-earmark-pdf text-danger"></i>

                                                                <span title="{{ $evaluasi->dokumen_evaluasi_file }}">
                                                                    {{ $evaluasi->dokumen_evaluasi_file }}
                                                                </span>

                                                            </div>

                                                            @if ($statusDokumen1 === 'disetujui')
                                                                <span class="badge bg-success dokumen-badge">
                                                                    <i class="bi bi-check-circle"></i> Disetujui
                                                                </span>
                                                            @elseif ($statusDokumen1 === 'menunggu')
                                                                <span class="badge bg-warning text-dark dokumen-badge">
                                                                    <i class="bi bi-clock"></i> Menunggu review
                                                                </span>
                                                            @else
                                                                <span class="badge bg-secondary dokumen-badge">
                                                                    Dokumen tersedia
                                                                </span>
                                                            @endif

                                                            <a href="{{ $evaluasi->dokumen_evaluasi_url }}" target="_blank"
                                                                class="btn btn-sm btn-outline-primary btn-lihat-pdf">

                                                                <i class="bi bi-eye"></i> Lihat PDF

                                                            </a>
                                                        @elseif ($statusDokumen1 === 'ditolak')
                                                            <span class="badge bg-danger dokumen-badge">
                                                                <i class="bi bi-x-circle"></i> Ditolak
                                                            </span>

                                                            <button type="button"
                                                                class="btn btn-sm btn-outline-danger btn-lihat-keterangan"
                                                                data-jenis="Dokumen Evaluasi Kinerja Pegawai"
                                                                data-keterangan="{{ $evaluasi?->keterangan_dokumen_evaluasi }}">

                                                                <i class="bi bi-chat-left-text"></i> Lihat alasan

                                                            </button>
                                                        @else
                                                            <span
                                                                class="badge bg-light text-secondary border dokumen-badge">
                                                                <i class="bi bi-file-earmark"></i> Belum upload
                                                            </span>
                                                        @endif


                                                        <label class="btn-upload-pdf mt-2 mb-1">

                                                            <i class="bi bi-upload"></i>

                                                            {{ $evaluasi?->dokumen_evaluasi_path ? 'Ganti Dokumen' : 'Upload PDF' }}

                                                            <input type="file" class="input-upload-evaluasi"
                                                                accept=".pdf,application/pdf"
                                                                data-jenis-dokumen="evaluasi"
                                                                data-jenis-evaluasi="{{ $jenisAktif ?? 'bulanan' }}"
                                                                data-user-id="{{ $item['pegawai']->id }}"
                                                                data-tahun="{{ $tahun }}"
                                                                data-bulan="{{ $bulan ?? '' }}"
                                                                data-triwulan="{{ $triwulan ?? '' }}" hidden>

                                                        </label>


                                                        <a href="{{ asset('storage/contoh-skp-evaluasi/contoh-evaluasi-kinerja-pegawai.pdf') }}"
                                                            target="_blank" class="small text-primary contoh-dokumen">

                                                            <i class="bi bi-file-earmark-pdf"></i> Contoh dokumen evaluasi
                                                            kinerja pegawai

                                                        </a>

                                                    </div>

                                                </td>
                                                @endif


                                                <td
                                                    class="upload-cell text-center {{ $evaluasi?->dokumen_kualitatif_path ? 'sudah-upload' : '' }}">

                                                    <div class="dokumen-status-box">

                                                        @if ($evaluasi?->dokumen_kualitatif_path)
                                                            <div class="dokumen-file-name">

                                                                <i class="bi bi-file-earmark-pdf text-danger"></i>

                                                                <span title="{{ $evaluasi->dokumen_kualitatif_file }}">
                                                                    {{ $evaluasi->dokumen_kualitatif_file }}
                                                                </span>

                                                            </div>

                                                            @if ($statusDokumen2 === 'disetujui')
                                                                <span class="badge bg-success dokumen-badge">
                                                                    <i class="bi bi-check-circle"></i> Disetujui
                                                                </span>
                                                            @elseif ($statusDokumen2 === 'menunggu')
                                                                <span class="badge bg-warning text-dark dokumen-badge">
                                                                    <i class="bi bi-clock"></i> Menunggu review
                                                                </span>
                                                            @else
                                                                <span class="badge bg-secondary dokumen-badge">
                                                                    Dokumen tersedia
                                                                </span>
                                                            @endif

                                                            <a href="{{ $evaluasi->dokumen_kualitatif_url }}"
                                                                target="_blank"
                                                                class="btn btn-sm btn-outline-primary btn-lihat-pdf">

                                                                <i class="bi bi-eye"></i> Lihat PDF

                                                            </a>
                                                        @elseif ($statusDokumen2 === 'ditolak')
                                                            <span class="badge bg-danger dokumen-badge">
                                                                <i class="bi bi-x-circle"></i> Ditolak
                                                            </span>

                                                            <button type="button"
                                                                class="btn btn-sm btn-outline-danger btn-lihat-keterangan"
                                                                data-jenis="Pendekatan Hasil Kerja Kualitatif"
                                                                data-keterangan="{{ $evaluasi?->keterangan_dokumen_kualitatif }}">

                                                                <i class="bi bi-chat-left-text"></i> Lihat alasan

                                                            </button>
                                                        @else
                                                            <span
                                                                class="badge bg-light text-secondary border dokumen-badge">
                                                                <i class="bi bi-file-earmark"></i> Belum upload
                                                            </span>
                                                        @endif


                                                        <label class="btn-upload-pdf mt-2 mb-1">

                                                            <i class="bi bi-upload"></i>

                                                            {{ $evaluasi?->dokumen_kualitatif_path ? 'Ganti Dokumen' : 'Upload PDF' }}

                                                            <input type="file" class="input-upload-evaluasi"
                                                                accept=".pdf,application/pdf"
                                                                data-jenis-dokumen="kualitatif"
                                                                data-jenis-evaluasi="{{ $jenisAktif ?? 'bulanan' }}"
                                                                data-user-id="{{ $item['pegawai']->id }}"
                                                                data-tahun="{{ $tahun }}"
                                                                data-bulan="{{ $bulan ?? '' }}"
                                                                data-triwulan="{{ $triwulan ?? '' }}" hidden>

                                                        </label>


                                                        <a href="{{ asset('storage/contoh-skp-evaluasi/contoh-evaluasi-kinerja-pegawai-kualitatif.pdf') }}"
                                                            target="_blank" class="small text-primary contoh-dokumen">

                                                            <i class="bi bi-file-earmark-pdf"></i> Contoh dokumen dokumen
                                                            evaluasi kinerja pegawai
                                                            pendekatan hasil kerja kualitatif

                                                        </a>

                                                    </div>

                                                </td>


                                                <td class="text-center">

                                                    @if ($status === 'disetujui')
                                                        <div class="keterangan-status status-ok">

                                                            <span class="badge bg-success">
                                                                <i class="bi bi-check-circle"></i> Lengkap & Disetujui
                                                            </span>

                                                        </div>
                                                    @elseif ($adaDitolak)
                                                        <div class="keterangan-status">

                                                            <span class="badge bg-danger">
                                                                <i class="bi bi-x-circle"></i> Perlu Perbaikan
                                                            </span>

                                                            <small class="text-muted">
                                                                Lihat alasan pada dokumen terkait.
                                                            </small>

                                                        </div>
                                                    @elseif ($status === 'menunggu_persetujuan')
                                                        <div class="keterangan-status">

                                                            <span class="badge bg-warning text-dark">
                                                                <i class="bi bi-clock"></i> Menunggu persetujuan
                                                            </span>

                                                        </div>
                                                    @else
                                                        <div class="keterangan-status">

                                                            <span class="badge bg-secondary">
                                                                <i class="bi bi-hourglass-split"></i> Belum lengkap
                                                            </span>

                                                        </div>
                                                    @endif

                                                </td>

                                            </tr>

                                        @empty

                                            <tr>

                                                <td colspan="{{ $pakaiDokumenEvaluasi ? 5 : 4 }}" class="text-center text-muted py-4">

                                                    Belum ada data pegawai.

                                                </td>

                                            </tr>
                                        @endforelse

                                    </tbody>

                                </table>

                            </div>

                        </div>


                        <div class="skp-petunjuk">

                            <strong>Petunjuk:</strong>

                            @if ($pakaiDokumenEvaluasi)
                                Upload kedua dokumen evaluasi untuk masing-masing pegawai
                                pada periode yang dipilih.
                            @else
                                Upload Dokumen Evaluasi Kinerja Pegawai Pendekatan Hasil Kerja
                                Kualitatif untuk masing-masing pegawai pada periode yang dipilih.
                                Evaluasi Bulanan dan Triwulan tidak memerlukan Dokumen Evaluasi
                                Kinerja Pegawai.
                            @endif

                            Gunakan link <strong>Contoh dokumen</strong> sebagai panduan
                            sebelum melakukan upload.

                            Setiap metode evaluasi menyimpan dokumennya sendiri dan
                            tidak mempengaruhi metode Bulanan, Triwulan, atau Tahunan lainnya.

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="modal fade" id="modalLihatKeterangan" tabindex="-1" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered modal-sm">

            <div class="modal-content modal-keterangan">

                <div class="modal-header modal-keterangan-header">

                    <div>

                        <div class="modal-icon-danger">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                        </div>

                        <div>

                            <h5 class="modal-title">Dokumen Perlu Perbaikan</h5>

                            <small id="judulJenisKeterangan">Keterangan penolakan</small>

                        </div>

                    </div>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                </div>

                <div class="modal-body">

                    <div class="keterangan-card">

                        <div class="keterangan-card-title">
                            <i class="bi bi-chat-left-text"></i> Alasan penolakan
                        </div>

                        <div id="isiKeteranganPenolakan" class="keterangan-penolakan"></div>

                    </div>

                    <div class="keterangan-hint">
                        <i class="bi bi-lightbulb"></i>
                        <span>
                            Silakan upload ulang dokumen yang sudah diperbaiki.
                            Dokumen lainnya tetap tersimpan.
                        </span>
                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">

                        <i class="bi bi-check2"></i> Mengerti

                    </button>

                </div>

            </div>

        </div>

    </div>

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const buttons = document.querySelectorAll('.monitoring-type-btn');

            const tahun = document.getElementById('filterTahun');
            const bulan = document.getElementById('filterBulan');
            const triwulan = document.getElementById('filterTriwulan');

            const wrapperBulan = document.getElementById('wrapperBulan');
            const wrapperTriwulan = document.getElementById('wrapperTriwulan');

            const btn = document.getElementById('btnTampilkan');
            const info = document.getElementById('periodeInfo');
            const periodeBadge = document.getElementById('periodeBadge');
            const judulEvaluasi = document.getElementById('judulEvaluasi');
            const subtitleEvaluasi = document.getElementById('subtitleEvaluasi');

            const bulanLabels = @json($daftarBulan);
            const triwulanLabels = @json($daftarTriwulan);

            let currentType = @json($jenisAktif ?? 'bulanan');

            function updateType() {

                buttons.forEach(button => {
                    button.classList.toggle(
                        'active',
                        button.dataset.type === currentType
                    );
                });

                const isBulanan = currentType === 'bulanan';
                const isTriwulan = currentType === 'triwulan';

                wrapperBulan.style.display =
                    isBulanan ? 'block' : 'none';

                wrapperTriwulan.style.display =
                    isTriwulan ? 'block' : 'none';

                if (isBulanan) {

                    const kode = bulan.value || '01';
                    const nama = bulanLabels[kode] || 'Januari';

                    info.textContent = nama;
                    periodeBadge.textContent = `Periode: ${nama} ${tahun.value}`;
                    judulEvaluasi.innerHTML = '<i class="bi bi-clipboard-check"></i> Evaluasi SKP Bulanan';
                    subtitleEvaluasi.textContent = `${nama} ${tahun.value}`;

                } else if (isTriwulan) {

                    const kode = triwulan.value || '1';
                    const rentang = triwulanLabels[kode] || 'Jan - Mar';

                    info.textContent = `Triwulan ${kode} (${rentang})`;
                    periodeBadge.textContent = `Periode: Triwulan ${kode} (${rentang}) ${tahun.value}`;
                    judulEvaluasi.innerHTML = '<i class="bi bi-clipboard-check"></i> Evaluasi SKP Triwulan';
                    subtitleEvaluasi.textContent = `Triwulan ${kode} (${rentang}) ${tahun.value}`;

                } else {

                    info.textContent = 'Satu Tahun';
                    periodeBadge.textContent = `Periode: Tahun ${tahun.value}`;
                    judulEvaluasi.innerHTML = '<i class="bi bi-clipboard-check"></i> Evaluasi SKP Tahunan';
                    subtitleEvaluasi.textContent = `Tahun ${tahun.value}`;

                }

            }

            buttons.forEach(button => {

                button.addEventListener('click', function() {

                    const selectedType = this.dataset.type;

                    /*
                     * Jenis evaluasi harus langsung diterapkan ke server.
                     * Sebelumnya tombol hanya mengganti tampilan JavaScript,
                     * sedangkan tabel dan data upload masih menggunakan data
                     * dari jenis evaluasi yang sedang dirender oleh Laravel.
                     */
                    const params = new URLSearchParams({
                        jenis: selectedType,
                        tahun: tahun.value
                    });

                    if (selectedType === 'bulanan') {
                        params.set('bulan', bulan.value || '{{ $bulan ?? now()->month }}');
                    }

                    if (selectedType === 'triwulan') {
                        params.set('triwulan', triwulan.value || '{{ $triwulan ?? 1 }}');
                    }

                    window.location.href =
                        '{{ route('admin.skp.evaluasi') }}?' +
                        params.toString();

                });

            });

            bulan.addEventListener('change', function() {

                if (currentType !== 'bulanan') return;

                const kode = this.value;

                updateType();

            });

            triwulan.addEventListener('change', function() {

                if (currentType !== 'triwulan') return;

                const kode = this.value;

                const rentang =
                    triwulanLabels[kode] || 'Jan - Mar';

                updateType();

            });

            tahun.addEventListener('change', function() {
                updateType();
            });

            btn.addEventListener('click', function() {

                const params = new URLSearchParams({

                    jenis: currentType,

                    tahun: tahun.value

                });

                if (currentType === 'bulanan') {

                    params.set(
                        'bulan',
                        bulan.value
                    );

                }

                if (currentType === 'triwulan') {

                    params.set(
                        'triwulan',
                        triwulan.value
                    );

                }

                window.location.href =
                    '{{ route('admin.skp.evaluasi') }}?' +
                    params.toString();

            });

            updateType();


            const csrfToken = '{{ csrf_token() }}';

            const urlUpload =
                '{{ route('admin.skp.evaluasi.upload') }}';


            document.addEventListener('click', function(e) {

                const btnKeterangan =
                    e.target.closest('.btn-lihat-keterangan');

                if (!btnKeterangan) return;

                document.getElementById(
                        'judulJenisKeterangan'
                    ).textContent =
                    btnKeterangan.dataset.jenis ||
                    'Keterangan penolakan';

                document.getElementById(
                        'isiKeteranganPenolakan'
                    ).textContent =
                    btnKeterangan.dataset.keterangan ||
                    'Tidak ada keterangan penolakan.';

                bootstrap.Modal.getOrCreateInstance(
                    document.getElementById(
                        'modalLihatKeterangan'
                    )
                ).show();

            });


            document.addEventListener('change', function(e) {

                if (
                    !e.target.classList.contains(
                        'input-upload-evaluasi'
                    )
                ) {
                    return;
                }

                const input = e.target;
                const file = input.files[0];

                if (!file) return;


                if (file.type !== 'application/pdf') {

                    Swal.fire({

                        icon: 'error',

                        title: 'Format File Tidak Valid!',

                        text: 'Dokumen evaluasi harus berformat PDF.',

                        confirmButtonText: 'OK',

                        confirmButtonColor: '#dc3545'

                    });

                    input.value = '';

                    return;

                }


                const cell =
                    input.closest('.upload-cell');

                const label =
                    cell.querySelector(
                        '.btn-upload-pdf'
                    );

                const oldLabel =
                    label.innerHTML;


                const formData =
                    new FormData();

                formData.append(
                    'file',
                    file
                );

                formData.append(
                    'jenis_dokumen',
                    input.dataset.jenisDokumen
                );

                formData.append(
                    'jenis_evaluasi',
                    input.dataset.jenisEvaluasi
                );

                formData.append(
                    'user_id',
                    input.dataset.userId
                );

                formData.append(
                    'tahun',
                    input.dataset.tahun
                );

                if (
                    input.dataset.jenisEvaluasi ===
                    'bulanan'
                ) {

                    formData.append(
                        'bulan',
                        input.dataset.bulan
                    );

                }

                if (
                    input.dataset.jenisEvaluasi ===
                    'triwulan'
                ) {

                    formData.append(
                        'triwulan',
                        input.dataset.triwulan
                    );

                }


                label.innerHTML =
                    '<i class="bi bi-arrow-repeat"></i> Mengupload...';

                label.classList.add(
                    'disabled'
                );


                fetch(
                        urlUpload, {

                            method: 'POST',

                            headers: {

                                'X-CSRF-TOKEN': csrfToken,

                                'Accept': 'application/json'

                            },

                            body: formData

                        }
                    )

                    .then(async response => {

                        const result =
                            await response.json();

                        if (!response.ok) {

                            throw new Error(

                                result.message ||

                                Object.values(
                                    result.errors || {}
                                )[0]?.[0] ||

                                'Upload gagal, silakan coba lagi.'

                            );

                        }

                        return result;

                    })

                    .then(result => {

                        Swal.fire({

                            icon: 'success',

                            title: 'Upload Berhasil!',

                            text: result.message ||
                                'Dokumen berhasil diupload.',

                            confirmButtonText: 'OK',

                            confirmButtonColor: '#198754',

                            timer: 2500,

                            timerProgressBar: true

                        }).then(() => {

                            window.location.reload();

                        });

                    })

                    .catch(err => {

                        Swal.fire({

                            icon: 'error',

                            title: 'Upload Gagal!',

                            text: err.message ||
                                'Terjadi kesalahan saat mengupload dokumen.',

                            confirmButtonText: 'OK',

                            confirmButtonColor: '#dc3545'

                        });

                        label.innerHTML =
                            oldLabel;

                        label.classList.remove(
                            'disabled'
                        );

                    })

                    .finally(() => {

                        input.value = '';

                    });

            });

        });
    </script>

@endsection