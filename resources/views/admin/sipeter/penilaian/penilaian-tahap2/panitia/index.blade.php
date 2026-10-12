@extends('layouts.app')

@section('content')

    @php
        $statusSesi = $penilaian->status ?? null;
        $sudahBerjalan = $statusSesi === 'berjalan';
        $jumlahTerpilih = count($terpilihIds);
    @endphp

    <style>
        /* =========================================================
           BASE
        ========================================================= */
        .tahap2-page {
            padding: 22px 5px 45px;
        }

        .tahap2-page * {
            box-sizing: border-box;
        }

        /* =========================================================
           HEADER
        ========================================================= */
        .tahap2-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 18px;
            flex-wrap: wrap;
            margin-bottom: 25px;
        }

        .tahap2-title {
            margin: 0 0 7px;
            color: #07549b !important;
            font-size: 25px;
            font-weight: 700;
        }

        .tahap2-subtitle {
            margin: 0;
            color: #7b8794 !important;
            font-size: 14px;
        }

        .tahap2-year-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 15px;
            border-radius: 7px;
            background: #ffffff;
            border: 1px solid #dce3eb;
            color: #536171;
            font-size: 13px;
            font-weight: 600;
        }

        .tahap2-year-badge i {
            color: #07549b;
        }

        /* =========================================================
           ALERT
        ========================================================= */
        .tahap2-alert {
            border: 0 !important;
            border-radius: 8px !important;
            padding: 13px 16px !important;
            margin-bottom: 20px;
            font-size: 14px;
        }

        /* =========================================================
           STEPPER
        ========================================================= */
        .tahap2-stepper {
            display: flex;
            align-items: center;
            width: 100%;
            margin-bottom: 24px;
            background: #ffffff;
            border: 1px solid #dce3eb;
            border-radius: 9px;
            padding: 18px 22px;
        }

        .tahap2-step {
            display: flex;
            align-items: center;
            gap: 10px;
            flex: 1;
            position: relative;
        }

        .tahap2-step:not(:last-child)::after {
            content: "";
            height: 1px;
            background: #dce3eb;
            flex: 1;
            margin: 0 18px;
        }

        .tahap2-step-number {
            width: 35px;
            height: 35px;
            min-width: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #edf1f5;
            color: #7b8794;
            font-size: 13px;
            font-weight: 700;
            border: 1px solid #dce3eb;
        }

        .tahap2-step.active .tahap2-step-number {
            background: #07549b;
            border-color: #07549b;
            color: #ffffff;
        }

        .tahap2-step-title {
            color: #697687;
            font-size: 13px;
            font-weight: 600;
            white-space: nowrap;
        }

        .tahap2-step.active .tahap2-step-title {
            color: #07549b;
        }

        /* =========================================================
           CARD
        ========================================================= */
        .tahap2-card {
            background: #ffffff;
            border: 1px solid #dce3eb;
            border-radius: 9px;
            overflow: hidden;
            margin-bottom: 23px;
        }

        .tahap2-card-header {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 16px 21px;
            border-bottom: 1px solid #e8edf2;
            color: #07549b;
            font-size: 15px;
            font-weight: 700;
        }

        .tahap2-card-header i {
            font-size: 17px;
        }

        .tahap2-card-header .tahap2-card-meta {
            margin-left: auto;
            color: #7b8794;
            font-size: 12.5px;
            font-weight: 600;
        }

        .tahap2-card-body {
            padding: 21px;
        }

        /* =========================================================
           FORM
        ========================================================= */
        .tahap2-form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 17px;
        }

        .tahap2-form-group label {
            display: block;
            margin-bottom: 7px;
            color: #465466;
            font-size: 13px;
            font-weight: 700;
        }

        .tahap2-form-control {
            width: 100%;
            height: 42px;
            border: 1px solid #d6dee7 !important;
            border-radius: 7px !important;
            background: #ffffff !important;
            color: #29313a !important;
            padding: 0 13px;
            font-size: 14px;
            font-weight: 500;
            outline: none;
        }

        .tahap2-form-control:focus {
            border-color: #07549b !important;
            box-shadow: 0 0 0 3px rgba(7, 84, 155, .10) !important;
        }

        /* =========================================================
           BUTTON
        ========================================================= */
        .tahap2-actions {
            display: flex;
            justify-content: flex-end;
            gap: 9px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .tahap2-actions.between {
            justify-content: space-between;
        }

        .tahap2-btn {
            height: 42px;
            padding: 0 17px;
            border-radius: 7px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            font-size: 13.5px;
            font-weight: 600;
            border: 0;
            text-decoration: none;
            cursor: pointer;
            transition: .18s ease;
        }

        .tahap2-btn:disabled {
            opacity: .55;
            cursor: not-allowed;
            transform: none !important;
        }

        .tahap2-btn-primary {
            background: #07549b !important;
            color: #ffffff !important;
        }

        .tahap2-btn-primary:hover:not(:disabled) {
            background: #06457f !important;
            transform: translateY(-1px);
        }

        .tahap2-btn-secondary {
            background: #f1f4f7 !important;
            color: #526172 !important;
            border: 1px solid #dce3eb !important;
        }

        .tahap2-btn-secondary:hover {
            background: #e7edf3 !important;
        }

        /* =========================================================
           INFO GRID
        ========================================================= */
        .tahap2-info {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 12px;
            margin-bottom: 18px;
        }

        .tahap2-info-item {
            padding: 13px 15px;
            border: 1px solid #dfe6ed;
            border-radius: 8px;
            background: #f9fbfd;
        }

        .tahap2-info-label {
            color: #84909d;
            font-size: 11px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .tahap2-info-value {
            color: #27323e;
            font-size: 13.5px;
            font-weight: 700;
            word-break: break-word;
        }

        /* =========================================================
           TOOLBAR PILIH PERTANYAAN
        ========================================================= */
        .pilih-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            flex-wrap: wrap;
            padding: 14px 16px;
            border: 1px solid #dce5ee;
            background: #f8fbff;
            border-radius: 8px;
            margin-bottom: 16px;
        }

        .pilih-toolbar-left {
            display: flex;
            align-items: center;
            gap: 14px;
            flex-wrap: wrap;
        }

        .pilih-search {
            position: relative;
            width: 280px;
            max-width: 100%;
        }

        .pilih-search i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #84909d;
            font-size: 13px;
        }

        .pilih-search input {
            width: 100%;
            height: 40px;
            padding: 0 13px 0 34px;
            border: 1px solid #d6dee7;
            border-radius: 7px;
            background: #ffffff;
            color: #29313a;
            font-size: 13.5px;
            outline: none;
        }

        .pilih-search input:focus {
            border-color: #07549b;
            box-shadow: 0 0 0 3px rgba(7, 84, 155, .10);
        }

        .pilih-semua {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin: 0;
            color: #34404d;
            font-size: 13.5px;
            font-weight: 700;
            cursor: pointer;
            user-select: none;
        }

        .pilih-semua input,
        .pilih-check {
            width: 18px;
            height: 18px;
            accent-color: #07549b;
            cursor: pointer;
        }

        .pilih-counter {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 7px 12px;
            border-radius: 20px;
            background: #eef7ff;
            border: 1px solid #cde4f8;
            color: #07549b;
            font-size: 12.5px;
            font-weight: 700;
        }

        /* =========================================================
           TABLE
        ========================================================= */
        .tahap2-table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        .tahap2-table {
            width: 100%;
            min-width: 560px;
            margin: 0;
            border-collapse: collapse;
        }

        .tahap2-table th,
        .tahap2-table td {
            border: 1px solid #e1e7ed !important;
            padding: 12px 14px;
            vertical-align: middle;
        }

        .tahap2-table thead th {
            background: #f7f9fb !important;
            color: #07549b !important;
            font-size: 12.5px;
            font-weight: 700;
            text-align: center;
        }

        .tahap2-table tbody td {
            background: #ffffff !important;
            color: #34404d !important;
            font-size: 13.5px;
        }

        .tahap2-table tbody tr:hover td {
            background: #f8fbff !important;
        }

        .tahap2-table tbody tr.pilih-row {
            cursor: pointer;
        }

        .tahap2-table tbody tr.pilih-row.terpilih td {
            background: #eef7ff !important;
        }

        .table-center {
            text-align: center;
        }

        .pertanyaan-teks {
            white-space: normal;
            word-break: break-word;
            line-height: 1.6;
        }

        .nama-pegawai {
            color: #1d2733 !important;
            font-weight: 700;
        }

        .peringkat-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #07549b;
            color: #ffffff;
            font-size: 12.5px;
            font-weight: 700;
        }

        /* =========================================================
           STATUS
        ========================================================= */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 9px;
            border-radius: 6px;
            font-size: 11.5px;
            font-weight: 700;
        }

        .status-draft {
            background: #fff4d6;
            color: #996b00;
        }

        .status-berjalan {
            background: #e5f7ed;
            color: #16804d;
        }

        /* =========================================================
           NOTICE
        ========================================================= */
        .tahap2-notice {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 14px 16px;
            border-radius: 8px;
            margin-bottom: 18px;
            font-size: 13.5px;
        }

        .tahap2-notice i {
            font-size: 18px;
            margin-top: 1px;
        }

        .tahap2-notice-title {
            font-weight: 700;
            margin-bottom: 2px;
        }

        .tahap2-notice p {
            margin: 0;
        }

        .tahap2-notice.warning {
            background: #fff8e6;
            border: 1px solid #f1d99e;
            color: #8a5d00;
        }

        .tahap2-notice.success {
            background: #e9f8f0;
            border: 1px solid #b9e6cd;
            color: #16804d;
        }

        .tahap2-notice.info {
            background: #eef7ff;
            border: 1px solid #cde4f8;
            color: #07549b;
        }

        .tahap2-empty {
            padding: 34px 20px;
            text-align: center;
            color: #7b8794;
        }

        .tahap2-empty i {
            font-size: 42px;
        }

        /* =========================================================
           MODAL MULAI
        ========================================================= */
        .mulai-modal {
            border: 0;
            border-radius: 16px;
            overflow: hidden;
        }

        .mulai-modal-icon {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            background: #eef5ff;
            color: #07549b;
        }

        /* =========================================================
           RESPONSIVE
        ========================================================= */
        @media (max-width: 768px) {

            .tahap2-form-grid {
                grid-template-columns: 1fr;
            }

            .tahap2-info {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .tahap2-stepper {
                overflow-x: auto;
                gap: 5px;
            }

            .tahap2-step {
                min-width: 170px;
            }

            .tahap2-step:not(:last-child)::after {
                margin: 0 10px;
            }

            .pilih-search {
                width: 100%;
            }
        }

        /* =========================================================
           DARK MODE
        ========================================================= */
        body.theme-dark .tahap2-title,
        html.theme-dark .tahap2-title {
            color: #8db9ff !important;
        }

        body.theme-dark .tahap2-subtitle,
        html.theme-dark .tahap2-subtitle {
            color: #929bad !important;
        }

        body.theme-dark .tahap2-year-badge,
        html.theme-dark .tahap2-year-badge,
        body.theme-dark .tahap2-stepper,
        html.theme-dark .tahap2-stepper,
        body.theme-dark .tahap2-card,
        html.theme-dark .tahap2-card {
            background: #1f1e2e !important;
            border-color: #353448 !important;
        }

        body.theme-dark .tahap2-card-header,
        html.theme-dark .tahap2-card-header {
            border-color: #353448 !important;
            color: #8db9ff !important;
        }

        body.theme-dark .tahap2-form-group label,
        html.theme-dark .tahap2-form-group label {
            color: #c3c8d5 !important;
        }

        body.theme-dark .tahap2-form-control,
        html.theme-dark .tahap2-form-control,
        body.theme-dark .pilih-search input,
        html.theme-dark .pilih-search input {
            background: #242334 !important;
            border-color: #3c3b50 !important;
            color: #d4d8e2 !important;
        }

        body.theme-dark .tahap2-step-title,
        html.theme-dark .tahap2-step-title {
            color: #929bad;
        }

        body.theme-dark .tahap2-step.active .tahap2-step-title,
        html.theme-dark .tahap2-step.active .tahap2-step-title {
            color: #8db9ff;
        }

        body.theme-dark .tahap2-step-number,
        html.theme-dark .tahap2-step-number {
            background: #2a2940;
            border-color: #3c3b50;
            color: #929bad;
        }

        body.theme-dark .tahap2-step.active .tahap2-step-number,
        html.theme-dark .tahap2-step.active .tahap2-step-number {
            background: #07549b;
            border-color: #07549b;
            color: #ffffff;
        }

        body.theme-dark .tahap2-info-item,
        html.theme-dark .tahap2-info-item,
        body.theme-dark .pilih-toolbar,
        html.theme-dark .pilih-toolbar {
            background: #242334 !important;
            border-color: #353448 !important;
        }

        body.theme-dark .tahap2-info-value,
        html.theme-dark .tahap2-info-value,
        body.theme-dark .pilih-semua,
        html.theme-dark .pilih-semua,
        body.theme-dark .nama-pegawai,
        html.theme-dark .nama-pegawai {
            color: #e3e6ee !important;
        }

        body.theme-dark .tahap2-table th,
        html.theme-dark .tahap2-table th,
        body.theme-dark .tahap2-table td,
        html.theme-dark .tahap2-table td {
            border-color: #353448 !important;
        }

        body.theme-dark .tahap2-table thead th,
        html.theme-dark .tahap2-table thead th {
            background: #242334 !important;
            color: #8db9ff !important;
        }

        body.theme-dark .tahap2-table tbody td,
        html.theme-dark .tahap2-table tbody td {
            background: #1f1e2e !important;
            color: #cfd4e0 !important;
        }

        body.theme-dark .tahap2-table tbody tr:hover td,
        html.theme-dark .tahap2-table tbody tr:hover td,
        body.theme-dark .tahap2-table tbody tr.pilih-row.terpilih td,
        html.theme-dark .tahap2-table tbody tr.pilih-row.terpilih td {
            background: #27314a !important;
        }

        body.theme-dark .tahap2-btn-secondary,
        html.theme-dark .tahap2-btn-secondary {
            background: #2a2940 !important;
            border-color: #3c3b50 !important;
            color: #c3c8d5 !important;
        }
    </style>


    <div class="tahap2-page">

        {{-- =========================================================
             HEADER
        ========================================================== --}}
        <div class="tahap2-header">

            <div>
                <h3 class="tahap2-title">
                    Penilaian Tahap 2 - Panitia
                </h3>

                <p class="tahap2-subtitle">
                    Pilih pertanyaan yang akan dinilai pegawai untuk 5 pegawai terbaik
                    pada periode yang dipilih.
                </p>
            </div>

            <div class="tahap2-year-badge">
                <i class="bi bi-calendar3"></i>
                Tahun {{ $tahun }}
            </div>

        </div>


        {{-- =========================================================
             ALERT
        ========================================================== --}}
        @if (session('success'))
            <div class="alert alert-success tahap2-alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger tahap2-alert">
                <i class="bi bi-exclamation-circle-fill me-2"></i>
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger tahap2-alert">
                <div class="d-flex align-items-start">
                    <i class="bi bi-exclamation-triangle-fill me-2 mt-1"></i>
                    <div>
                        <strong>Terjadi kesalahan:</strong>
                        <ul class="mb-0 mt-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif


        {{-- =========================================================
             STEPPER
        ========================================================== --}}
        <div class="tahap2-stepper">

            <div class="tahap2-step {{ $step >= 1 ? 'active' : '' }}">
                <div class="tahap2-step-number">1</div>
                <div class="tahap2-step-title">Periode</div>
            </div>

            <div class="tahap2-step {{ $step >= 2 ? 'active' : '' }}">
                <div class="tahap2-step-number">2</div>
                <div class="tahap2-step-title">Pilih Pertanyaan</div>
            </div>

            <div class="tahap2-step {{ $step >= 3 ? 'active' : '' }}">
                <div class="tahap2-step-number">3</div>
                <div class="tahap2-step-title">Konfirmasi & Mulai</div>
            </div>

        </div>


        {{-- =========================================================
             STEP 1 - PERIODE
        ========================================================== --}}
        @if ($step == 1)

            <div class="tahap2-card">

                <div class="tahap2-card-header">
                    <i class="bi bi-calendar-range"></i>
                    <span>Pilih Periode Penilaian</span>
                </div>

                <div class="tahap2-card-body">

                    <form action="{{ route('admin.sipeter.penilaian.penilaian-tahap2.panitia.proses') }}" method="POST">
                        @csrf

                        <div class="tahap2-form-grid">

                            <div class="tahap2-form-group">
                                <label for="tahun">Tahun</label>

                                <select name="tahun" id="tahun" class="tahap2-form-control" required>
                                    @foreach ($tahunOptions as $itemTahun)
                                        <option value="{{ $itemTahun }}"
                                            {{ (int) $tahun === (int) $itemTahun ? 'selected' : '' }}>
                                            {{ $itemTahun }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="tahap2-form-group">
                                <label for="triwulan">Triwulan</label>

                                <select name="triwulan" id="triwulan" class="tahap2-form-control" required>
                                    @for ($i = 1; $i <= 4; $i++)
                                        <option value="{{ $i }}" {{ (int) $triwulan === $i ? 'selected' : '' }}>
                                            Triwulan {{ $i }}
                                        </option>
                                    @endfor
                                </select>
                            </div>

                        </div>

                        <div class="tahap2-actions">
                            <button type="submit" class="tahap2-btn tahap2-btn-primary">
                                Lanjut
                                <i class="bi bi-arrow-right"></i>
                            </button>
                        </div>

                    </form>

                </div>

            </div>

        @endif


        {{-- =========================================================
             STEP 2 - PILIH PERTANYAAN
        ========================================================== --}}
        @if ($step == 2)

            <div class="tahap2-card">

                <div class="tahap2-card-header">
                    <i class="bi bi-ui-checks"></i>
                    <span>Pilih Pertanyaan</span>

                    <span class="tahap2-card-meta">
                        Triwulan {{ $triwulan }} - {{ $tahun }}
                    </span>
                </div>

                <div class="tahap2-card-body">

                    @if ($pertanyaanList->isEmpty())

                        <div class="tahap2-empty">
                            <i class="bi bi-question-circle"></i>
                            <p class="mt-3 mb-1 fw-semibold">Belum ada pertanyaan</p>
                            <small>
                                Tambahkan pertanyaan terlebih dahulu di menu
                                <a href="{{ route('admin.sipeter.manajemen-pertanyaan.index') }}">
                                    Manajemen Pertanyaan
                                </a>.
                            </small>
                        </div>

                        <div class="tahap2-actions between">
                            <a href="{{ route('admin.sipeter.penilaian.penilaian-tahap2.panitia.index', ['step' => 1, 'tahun' => $tahun, 'triwulan' => $triwulan]) }}"
                                class="tahap2-btn tahap2-btn-secondary">
                                <i class="bi bi-arrow-left"></i>
                                Kembali
                            </a>
                        </div>
                    @else
                        <form id="formPilihPertanyaan"
                            action="{{ route('admin.sipeter.penilaian.penilaian-tahap2.panitia.pertanyaan.store') }}"
                            method="POST">
                            @csrf

                            <input type="hidden" name="tahun" value="{{ $tahun }}">
                            <input type="hidden" name="triwulan" value="{{ $triwulan }}">

                            {{-- TOOLBAR --}}
                            <div class="pilih-toolbar">

                                <div class="pilih-toolbar-left">

                                    <label class="pilih-semua" for="pilihSemua">
                                        <input type="checkbox" id="pilihSemua">
                                        Pilih semua
                                    </label>

                                    <div class="pilih-search">
                                        <i class="bi bi-search"></i>
                                        <input type="text" id="cariPertanyaan" placeholder="Cari pertanyaan..."
                                            autocomplete="off">
                                    </div>

                                </div>

                                <div class="pilih-counter">
                                    <i class="bi bi-check2-square"></i>
                                    <span id="jumlahTerpilih">{{ $jumlahTerpilih }}</span>
                                    dari {{ $pertanyaanList->count() }} dipilih
                                </div>

                            </div>

                            {{-- TABEL --}}
                            <div class="tahap2-table-wrapper">

                                <table class="tahap2-table">

                                    <thead>
                                        <tr>
                                            <th style="width: 60px;">Pilih</th>
                                            <th style="width: 70px;">No</th>
                                            <th>Pertanyaan</th>
                                        </tr>
                                    </thead>

                                    <tbody id="daftarPertanyaan">

                                        @foreach ($pertanyaanList as $index => $item)
                                            @php $dipilih = in_array((int) $item->id, $terpilihIds, true); @endphp

                                            <tr class="pilih-row {{ $dipilih ? 'terpilih' : '' }}">

                                                <td class="table-center">
                                                    <input type="checkbox" class="pilih-check" name="pertanyaan_ids[]"
                                                        value="{{ $item->id }}" {{ $dipilih ? 'checked' : '' }}>
                                                </td>

                                                <td class="table-center">{{ $index + 1 }}</td>

                                                <td>
                                                    <div class="pertanyaan-teks">{{ $item->pertanyaan }}</div>
                                                </td>

                                            </tr>
                                        @endforeach

                                        <tr id="barisKosong" style="display: none;">
                                            <td colspan="3" class="table-center text-muted py-4">
                                                Tidak ada pertanyaan yang cocok dengan pencarian.
                                            </td>
                                        </tr>

                                    </tbody>

                                </table>

                            </div>

                            <div class="tahap2-actions between">

                                <a href="{{ route('admin.sipeter.penilaian.penilaian-tahap2.panitia.index', ['step' => 1, 'tahun' => $tahun, 'triwulan' => $triwulan]) }}"
                                    class="tahap2-btn tahap2-btn-secondary">
                                    <i class="bi bi-arrow-left"></i>
                                    Kembali
                                </a>

                                <button type="submit" id="tombolLanjut" class="tahap2-btn tahap2-btn-primary"
                                    {{ $jumlahTerpilih === 0 ? 'disabled' : '' }}>
                                    Lanjut
                                    <i class="bi bi-arrow-right"></i>
                                </button>

                            </div>

                        </form>
                    @endif

                </div>

            </div>

        @endif


        {{-- =========================================================
             STEP 3 - KONFIRMASI & MULAI
        ========================================================== --}}
        @if ($step == 3 && $penilaian)

            {{-- RINGKASAN --}}
            <div class="tahap2-card">

                <div class="tahap2-card-header">
                    <i class="bi bi-clipboard2-check"></i>
                    <span>Ringkasan Penilaian</span>
                </div>

                <div class="tahap2-card-body">

                    <div class="tahap2-info">

                        <div class="tahap2-info-item">
                            <div class="tahap2-info-label">Tahun</div>
                            <div class="tahap2-info-value">{{ $tahun }}</div>
                        </div>

                        <div class="tahap2-info-item">
                            <div class="tahap2-info-label">Triwulan</div>
                            <div class="tahap2-info-value">Triwulan {{ $triwulan }}</div>
                        </div>

                        <div class="tahap2-info-item">
                            <div class="tahap2-info-label">Jumlah Pertanyaan</div>
                            <div class="tahap2-info-value">{{ $penilaian->pertanyaan->count() }} pertanyaan</div>
                        </div>

                        <div class="tahap2-info-item">
                            <div class="tahap2-info-label">Status</div>
                            <div class="tahap2-info-value">
                                @if ($sudahBerjalan)
                                    <span class="status-badge status-berjalan">
                                        <i class="bi bi-play-circle-fill"></i>
                                        Berjalan
                                    </span>
                                @else
                                    <span class="status-badge status-draft">
                                        <i class="bi bi-pencil-square"></i>
                                        Draft
                                    </span>
                                @endif
                            </div>
                        </div>

                    </div>

                    @if ($sudahBerjalan)
                        <div class="tahap2-notice success mb-0">
                            <i class="bi bi-check-circle-fill"></i>
                            <div>
                                <div class="tahap2-notice-title">Penilaian sudah dimulai</div>
                                <p>
                                    Dimulai pada {{ $penilaian->started_at?->format('d-m-Y H:i') ?? '-' }}.
                                    Pertanyaan di bawah ini sekarang tersedia untuk diisi pegawai dan
                                    tidak dapat diubah lagi.
                                </p>
                            </div>
                        </div>
                    @else
                        <div class="tahap2-notice info mb-0">
                            <i class="bi bi-info-circle-fill"></i>
                            <div>
                                <div class="tahap2-notice-title">Periksa kembali sebelum memulai</div>
                                <p>
                                    Setelah tombol <strong>Mulai Penilaian</strong> ditekan, pertanyaan akan
                                    diteruskan ke menu Pegawai dan tidak dapat diubah lagi.
                                </p>
                            </div>
                        </div>
                    @endif

                </div>

            </div>


            {{-- PERTANYAAN TERPILIH --}}
            <div class="tahap2-card">

                <div class="tahap2-card-header">
                    <i class="bi bi-list-check"></i>
                    <span>Pertanyaan yang Dipilih</span>

                    <span class="tahap2-card-meta">
                        {{ $penilaian->pertanyaan->count() }} pertanyaan
                    </span>
                </div>

                <div class="tahap2-card-body">

                    <div class="tahap2-table-wrapper">

                        <table class="tahap2-table">

                            <thead>
                                <tr>
                                    <th style="width: 70px;">No</th>
                                    <th>Pertanyaan</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($penilaian->pertanyaan as $item)
                                    <tr>
                                        <td class="table-center">{{ $item->urutan }}</td>
                                        <td>
                                            <div class="pertanyaan-teks">{{ $item->pertanyaan }}</div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>

                    </div>

                </div>

            </div>


            {{-- 5 PEGAWAI TERBAIK --}}
            <div class="tahap2-card">

                <div class="tahap2-card-header">
                    <i class="bi bi-trophy"></i>
                    <span>5 Pegawai Terbaik (Hasil Tahap 1)</span>
                </div>

                <div class="tahap2-card-body">

                    @if ($pegawaiTerbaik->isEmpty())

                        <div class="tahap2-notice warning mb-0">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            <div>
                                <div class="tahap2-notice-title">Belum ada 5 pegawai terbaik</div>
                                <p>
                                    Penilaian Tahap 1 untuk Triwulan {{ $triwulan }} {{ $tahun }}
                                    belum disetujui. Penilaian Tahap 2 baru bisa dimulai setelah hasil
                                    Tahap 1 disetujui.
                                </p>
                            </div>
                        </div>
                    @else
                        <div class="tahap2-table-wrapper">

                            <table class="tahap2-table" style="min-width: 420px;">

                                <thead>
                                    <tr>
                                        <th style="width: 90px;">No</th>
                                        <th>Nama Pegawai</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($pegawaiTerbaik->sortBy(fn($d) => mb_strtolower($d->user?->nama ?? ''))->values() as $detail)
                                        <tr>
                                            <td class="table-center">{{ $loop->iteration }}</td>
                                            <td class="nama-pegawai">{{ $detail->user->nama ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>

                            </table>

                        </div>
                    @endif

                </div>

            </div>


            {{-- AKSI --}}
            <div class="tahap2-actions between">

                @if ($sudahBerjalan)
                    <a href="{{ route('admin.sipeter.penilaian.penilaian-tahap2.panitia.index', ['step' => 1]) }}"
                        class="tahap2-btn tahap2-btn-secondary">
                        <i class="bi bi-arrow-left"></i>
                        Pilih Periode Lain
                    </a>
                @else
                    <a href="{{ route('admin.sipeter.penilaian.penilaian-tahap2.panitia.index', ['step' => 2, 'tahun' => $tahun, 'triwulan' => $triwulan]) }}"
                        class="tahap2-btn tahap2-btn-secondary">
                        <i class="bi bi-arrow-left"></i>
                        Ubah Pertanyaan
                    </a>

                    <button type="button" class="tahap2-btn tahap2-btn-primary" data-bs-toggle="modal"
                        data-bs-target="#mulaiModal" {{ $pegawaiTerbaik->isEmpty() ? 'disabled' : '' }}>
                        <i class="bi bi-play-circle"></i>
                        Mulai Penilaian
                    </button>
                @endif

            </div>


            {{-- MODAL KONFIRMASI MULAI --}}
            @unless ($sudahBerjalan)
                <div class="modal fade" id="mulaiModal" tabindex="-1" aria-hidden="true">

                    <div class="modal-dialog modal-dialog-centered">

                        <div class="modal-content mulai-modal">

                            <div class="modal-header border-0 pb-0">
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body text-center px-4 pb-4">

                                <div class="mulai-modal-icon">
                                    <i class="bi bi-play-circle"></i>
                                </div>

                                <h4 class="mt-3 mb-2">Mulai Penilaian?</h4>

                                <p class="text-muted mb-0">
                                    {{ $penilaian->pertanyaan->count() }} pertanyaan untuk
                                    Triwulan {{ $triwulan }} {{ $tahun }} akan diteruskan ke
                                    menu Pegawai. Pertanyaan tidak dapat diubah setelah ini.
                                </p>

                            </div>

                            <div class="modal-footer border-0 justify-content-center pb-4">

                                <button type="button" class="tahap2-btn tahap2-btn-secondary" data-bs-dismiss="modal">
                                    Batal
                                </button>

                                <form action="{{ route('admin.sipeter.penilaian.penilaian-tahap2.panitia.mulai') }}"
                                    method="POST" class="d-inline">
                                    @csrf

                                    <input type="hidden" name="penilaian_tahap2_id" value="{{ $penilaian->id }}">
                                    <input type="hidden" name="tahun" value="{{ $tahun }}">
                                    <input type="hidden" name="triwulan" value="{{ $triwulan }}">

                                    <button type="submit" class="tahap2-btn tahap2-btn-primary">
                                        <i class="bi bi-play-circle"></i>
                                        Ya, Mulai
                                    </button>
                                </form>

                            </div>

                        </div>

                    </div>

                </div>
            @endunless

        @endif

    </div>


    {{-- =========================================================
         JAVASCRIPT STEP 2
    ========================================================== --}}
    @if ($step == 2 && $pertanyaanList->isNotEmpty())
        <script>
            document.addEventListener('DOMContentLoaded', function() {

                const tbody = document.getElementById('daftarPertanyaan');
                const pilihSemua = document.getElementById('pilihSemua');
                const cari = document.getElementById('cariPertanyaan');
                const counter = document.getElementById('jumlahTerpilih');
                const tombolLanjut = document.getElementById('tombolLanjut');
                const barisKosong = document.getElementById('barisKosong');

                const semuaBaris = () => Array.from(tbody.querySelectorAll('tr.pilih-row'));
                const barisTampil = () => semuaBaris().filter(tr => tr.style.display !== 'none');
                const checkbox = tr => tr.querySelector('.pilih-check');

                function segarkan() {
                    // Tandai baris terpilih
                    semuaBaris().forEach(tr => {
                        tr.classList.toggle('terpilih', checkbox(tr).checked);
                    });

                    // Counter dan tombol lanjut
                    const total = semuaBaris().filter(tr => checkbox(tr).checked).length;
                    counter.textContent = total;
                    tombolLanjut.disabled = total === 0;

                    // Status "Pilih semua" mengikuti baris yang sedang tampil
                    const tampil = barisTampil();
                    const dipilih = tampil.filter(tr => checkbox(tr).checked).length;

                    pilihSemua.checked = tampil.length > 0 && dipilih === tampil.length;
                    pilihSemua.indeterminate = dipilih > 0 && dipilih < tampil.length;
                }

                // Pilih semua (hanya baris yang tampil, jadi aman dipakai bersama pencarian)
                pilihSemua.addEventListener('change', function() {
                    barisTampil().forEach(tr => {
                        checkbox(tr).checked = pilihSemua.checked;
                    });
                    segarkan();
                });

                // Klik di mana saja pada baris untuk mencentang
                tbody.addEventListener('click', function(e) {
                    const tr = e.target.closest('tr.pilih-row');
                    if (!tr) return;

                    if (!e.target.classList.contains('pilih-check')) {
                        checkbox(tr).checked = !checkbox(tr).checked;
                    }

                    segarkan();
                });

                // Pencarian
                cari.addEventListener('input', function() {
                    const kata = cari.value.trim().toLowerCase();

                    semuaBaris().forEach(tr => {
                        const teks = tr.querySelector('.pertanyaan-teks').textContent.toLowerCase();
                        tr.style.display = teks.includes(kata) ? '' : 'none';
                    });

                    barisKosong.style.display = barisTampil().length === 0 ? '' : 'none';

                    segarkan();
                });

                segarkan();
            });
        </script>
    @endif

@endsection