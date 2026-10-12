@extends('layouts.app')

@section('content')
    <style>
        /* =====================================================
                                               PELAPORAN - LIGHT & DARK MODE
                                            ===================================================== */

        .pelaporan-page {
            padding: 0 5px 30px;
        }

        /* ================= HEADER ================= */

        .pelaporan-heading {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 25px;
        }

        .pelaporan-heading h3 {
            color: #07549b !important;
            font-size: 26px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .pelaporan-heading p {
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

        .filter-card {
            background: #ffffff !important;
            border: 1px solid #dce2e8;
            border-radius: 9px;
            margin-bottom: 28px;
        }

        .filter-card .card-body {
            padding: 22px;
        }

        .filter-title {
            color: #07549b !important;
            font-size: 17px;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .filter-card .form-label {
            color: #465466 !important;
            font-weight: 500;
        }

        .filter-card .form-select {
            color: #526170 !important;
            background-color: #ffffff !important;
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


        /* ================= TAB CARD ================= */

        .pelaporan-page>.card {
            background: #ffffff !important;
            border: none;
            border-radius: 12px;
            box-shadow: 0 3px 15px rgba(0, 0, 0, .04);
        }

        .pelaporan-page>.card>.card-body {
            padding: 26px;
        }


        /* ================= TABLE CARD ================= */

        .pelaporan-table-card {
            background: #ffffff !important;
            border: 1px solid #d8e0e8;
            border-radius: 9px;
            overflow: hidden;
        }


        /* ================= BLUE HEADER ================= */

        .pelaporan-card-header {
            background: #075a9f !important;
            color: #ffffff !important;
            padding: 17px 22px;
        }

        .pelaporan-card-header h5 {
            color: #ffffff !important;
            margin: 0;
            font-size: 17px;
            font-weight: 700;
        }

        .pelaporan-card-header small {
            display: block;
            margin-top: 5px;
            color: #ffffff !important;
            opacity: .95;
            font-size: 13px;
        }


        /* ================= TABLE ================= */

        .pelaporan-table {
            width: 100%;
            margin-bottom: 0;
            border-collapse: collapse;
        }

        .pelaporan-table th,
        .pelaporan-table td {
            border: 1px solid #d9e0e7 !important;
            vertical-align: middle;
            padding: 15px 13px;
        }

        .pelaporan-table thead th {
            background: #f8fafc !important;
            color: #07549b !important;
            text-align: center;
            font-weight: 700;
            white-space: nowrap;
        }

        .pelaporan-table thead th small {
            color: #315b88 !important;
            font-weight: 600;
        }

        .pelaporan-table tbody td {
            background: #ffffff !important;
            color: #29313a !important;
            text-align: center;
        }

        .pelaporan-table .nama-pegawai {
            text-align: center !important;
            font-weight: 700;
            color: #161c22 !important;
            min-width: 280px;
        }


        /* ================= UPLOAD ================= */

        .upload-cell {
            min-width: 150px;
        }

        .btn-upload {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;

            min-width: 125px;
            padding: 9px 14px;

            border-radius: 6px;
            border: none;

            background: #198754 !important;
            color: #ffffff !important;

            font-size: 14px;
            font-weight: 600;

            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-upload:hover {
            background: #157347 !important;
            color: #ffffff !important;
            transform: translateY(-1px);
        }

        .upload-info {
            display: block;
            margin-top: 6px;

            color: #198754 !important;

            font-size: 11px;
            font-weight: 600;
        }


        /* ================= PETUNJUK ================= */

        .petunjuk {
            margin-top: 18px;
            color: #687789 !important;
            font-size: 14px;
        }

        .petunjuk strong {
            color: #343d48 !important;
        }


        /* =====================================================
                                               DARK MODE
                                            ===================================================== */

        body.theme-dark .pelaporan-heading h3,
        html.theme-dark .pelaporan-heading h3 {
            color: #8db9ff !important;
        }

        body.theme-dark .pelaporan-heading p,
        html.theme-dark .pelaporan-heading p {
            color: #aeb5c5 !important;
        }

        body.theme-dark .periode-badge,
        html.theme-dark .periode-badge {
            background: #252436 !important;
            color: #c8ceda !important;
            border-color: #3a394e !important;
        }


        /* FILTER DARK */

        body.theme-dark .filter-card,
        html.theme-dark .filter-card {
            background: #1f1e2e !important;
            border-color: #353448 !important;
        }

        body.theme-dark .filter-title,
        html.theme-dark .filter-title {
            color: #8db9ff !important;
        }

        body.theme-dark .filter-card .form-label,
        html.theme-dark .filter-card .form-label {
            color: #c3c8d5 !important;
        }

        body.theme-dark .filter-card .form-select,
        html.theme-dark .filter-card .form-select {
            background-color: #242334 !important;
            color: #d4d8e2 !important;
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


        /* MAIN CARD DARK */

        body.theme-dark .pelaporan-page>.card,
        html.theme-dark .pelaporan-page>.card {
            background: #1f1e2e !important;
            box-shadow: none;
        }


        /* TABLE CARD DARK */

        body.theme-dark .pelaporan-table-card,
        html.theme-dark .pelaporan-table-card {
            background: #1c1b2b !important;
            border-color: #3a394e !important;
        }


        /* BLUE HEADER DARK
                                               Tetap biru agar seirama dengan index
                                            */

        body.theme-dark .pelaporan-card-header,
        html.theme-dark .pelaporan-card-header {
            background: #075a9f !important;
            color: #ffffff !important;
        }

        body.theme-dark .pelaporan-card-header h5,
        html.theme-dark .pelaporan-card-header h5 {
            color: #ffffff !important;
        }

        body.theme-dark .pelaporan-card-header small,
        html.theme-dark .pelaporan-card-header small {
            color: #ffffff !important;
        }


        /* TABLE DARK */

        body.theme-dark .pelaporan-table th,
        body.theme-dark .pelaporan-table td,
        html.theme-dark .pelaporan-table th,
        html.theme-dark .pelaporan-table td {
            border-color: #3a394e !important;
        }

        body.theme-dark .pelaporan-table thead th,
        html.theme-dark .pelaporan-table thead th {
            background: #242334 !important;
            color: #a9c6ff !important;
        }

        body.theme-dark .pelaporan-table thead th small,
        html.theme-dark .pelaporan-table thead th small {
            color: #8fa8d0 !important;
        }

        body.theme-dark .pelaporan-table tbody td,
        html.theme-dark .pelaporan-table tbody td {
            background: #1c1b2b !important;
            color: #d4d8e2 !important;
        }

        body.theme-dark .pelaporan-table .nama-pegawai,
        html.theme-dark .pelaporan-table .nama-pegawai {
            color: #f0f2f7 !important;
        }


        /* UPLOAD DARK */

        body.theme-dark .btn-upload,
        html.theme-dark .btn-upload {
            background: #176cf0 !important;
            color: #ffffff !important;
        }

        body.theme-dark .btn-upload:hover,
        html.theme-dark .btn-upload:hover {
            background: #287df7 !important;
        }

        body.theme-dark .upload-info,
        html.theme-dark .upload-info {
            color: #969eb0 !important;
        }


        /* PETUNJUK DARK */

        body.theme-dark .petunjuk,
        html.theme-dark .petunjuk {
            color: #aeb5c5 !important;
        }

        body.theme-dark .petunjuk strong,
        html.theme-dark .petunjuk strong {
            color: #e2e5ec !important;
        }


        /* ================= RESPONSIVE ================= */

        @media (max-width: 768px) {

            .pelaporan-heading {
                flex-direction: column;
                gap: 12px;
            }

            .pelaporan-heading h3 {
                font-size: 22px;
            }

            .pelaporan-page>.card>.card-body {
                padding: 15px;
            }

            .pelaporan-table {
                min-width: 900px;
            }

            #content-bulanan .pelaporan-table {
                min-width: 1750px;
            }

        }
    </style>


    <div class="pelaporan-page">

        {{-- ================================================
         HEADER
    ================================================= --}}
        <div class="pelaporan-heading">

            <div>
                <h3>Pelaporan</h3>

                <p class="text-muted">
                    Pengelolaan laporan kinerja pegawai
                </p>
            </div>

            <span class="periode-badge" id="periodeBadge">
                Periode: {{ $jenis === 'bulanan' ? 'Tahun ' . $tahun : $namaBulan[$bulan] . ' ' . $tahun }}
            </span>

        </div>


        {{-- ================================================
         FILTER (disamakan dengan Monitoring Pelaporan)
    ================================================= --}}
        <div class="filter-card">
            <div class="card-body">

                <div class="filter-title">
                    <i class="bi bi-funnel"></i>
                    Periode Pelaporan
                </div>

                <form method="GET" action="{{ route('admin.pelaporan.index') }}" id="formFilter">

                    <input type="hidden" name="jenis" id="inputJenis" value="{{ $jenis }}">

                    {{-- PILIH JENIS UPLOAD --}}
                    <div class="monitoring-type-wrapper">

                        <label class="form-label">
                            Jenis Upload
                        </label>

                        <div class="monitoring-type-buttons">

                            <button type="button" class="monitoring-type-btn {{ $jenis === 'mingguan' ? 'active' : '' }}"
                                data-type="mingguan">
                                <i class="bi bi-calendar-week"></i>
                                <span>
                                    <strong>Mingguan</strong>
                                    <small>Pelaporan setiap minggu</small>
                                </span>
                            </button>

                            <button type="button" class="monitoring-type-btn {{ $jenis === 'bulanan' ? 'active' : '' }}"
                                data-type="bulanan">
                                <i class="bi bi-calendar-month"></i>
                                <span>
                                    <strong>Bulanan</strong>
                                    <small>Pelaporan setiap bulan</small>
                                </span>
                            </button>

                        </div>

                    </div>

                    <div class="row g-3 align-items-end">

                        {{-- TAHUN --}}
                        <div class="col-md-3">
                            <label class="form-label">
                                Tahun
                            </label>

                            <select class="form-select" name="tahun" id="filterTahun">
                                @foreach ($tahunOptions as $itemTahun)
                                    <option value="{{ $itemTahun }}" {{ $itemTahun == $tahun ? 'selected' : '' }}>
                                        {{ $itemTahun }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- BULAN (hanya relevan untuk jenis Mingguan) --}}
                        <div class="col-md-3 {{ $jenis === 'bulanan' ? 'd-none' : '' }}" id="filterBulanWrapper">
                            <label class="form-label">
                                Bulan
                            </label>

                            <select class="form-select" name="bulan" id="filterBulan">
                                @foreach ($namaBulan as $noBulan => $labelBulan)
                                    <option value="{{ $noBulan }}" {{ $noBulan == $bulan ? 'selected' : '' }}>
                                        {{ $labelBulan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- MINGGU (filter kolom, hanya untuk jenis Mingguan) --}}
                        <div class="col-md-3 {{ $jenis === 'bulanan' ? 'd-none' : '' }}" id="filterMingguWrapper">
                            <label class="form-label">
                                Minggu
                            </label>

                            <select class="form-select" id="filterMinggu">
                                <option value="semua" selected>Semua Minggu</option>
                                @foreach ($mingguDalamBulan as $noMinggu => $rentang)
                                    <option value="{{ $noMinggu }}">Minggu {{ $noMinggu }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- BUTTON --}}
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary w-100" id="btnTampilkan">
                                <i class="bi bi-search"></i>
                                Tampilkan Data
                            </button>
                        </div>

                    </div>

                </form>

            </div>
        </div>



        {{-- ================================================
         KONTEN (dikontrol oleh tombol Jenis Monitoring di atas)
    ================================================= --}}
        <div class="card">

            <div class="card-body">

                <div class="tab-content">

                    {{-- ==================================================
                     MINGGUAN
                =================================================== --}}
                    <div class="monitoring-type-content {{ $jenis === 'bulanan' ? 'd-none' : '' }}" id="content-mingguan">

                        <div class="pelaporan-table-card">

                            <div class="pelaporan-card-header">

                                <h5>
                                    Pelaporan Mingguan
                                </h5>

                                <small id="subtitleMingguan">
                                    {{ $namaBulan[$bulan] }} {{ $tahun }}
                                </small>

                            </div>


                            <div class="table-responsive">

                                <table class="pelaporan-table">

                                    <thead>

                                        <tr>

                                            <th>
                                                Nama Pegawai
                                            </th>

                                            @foreach ($mingguDalamBulan as $noMinggu => $rentang)
                                                <th data-minggu="{{ $noMinggu }}">
                                                    Minggu {{ $noMinggu }}
                                                    <br>
                                                    <small>
                                                        {{ $rentang['mulai']->format('j') }}
                                                        -
                                                        {{ $rentang['selesai']->format('j M') }}
                                                    </small>
                                                </th>
                                            @endforeach

                                        </tr>

                                    </thead>


                                    <tbody>

                                        @foreach ($pegawaiList as $pegawai)
                                            <tr>

                                                <td class="nama-pegawai">
                                                    {{ $pegawai->nama }}
                                                </td>

                                                @foreach ($mingguDalamBulan as $noMinggu => $rentang)
                                                    @php
                                                        $sudahAda = optional(
                                                            $pelaporanPeriode->get($pegawai->id),
                                                        )->firstWhere(function ($item) use ($noMinggu) {
                                                            return $item->jenis === 'Mingguan' &&
                                                                (int) $item->minggu === (int) $noMinggu;
                                                        });
                                                    @endphp

                                                    <td class="upload-cell {{ $sudahAda ? 'sudah-upload' : '' }}"
                                                        data-minggu="{{ $noMinggu }}">

                                                        <label class="btn-upload">

                                                            <i class="bi bi-upload"></i>
                                                            {{ $sudahAda ? 'Ganti' : 'Upload' }}

                                                            <input type="file" class="input-upload-pelaporan"
                                                                accept=".xls,.xlsx" data-jenis="Mingguan"
                                                                data-user-id="{{ $pegawai->id }}"
                                                                data-tahun="{{ $tahun }}"
                                                                data-bulan="{{ $bulan }}"
                                                                data-minggu="{{ $noMinggu }}" hidden>

                                                        </label>

                                                        <span class="upload-info">
                                                            {{ $sudahAda->nama_file ?? 'Excel' }}
                                                        </span>

                                                    </td>
                                                @endforeach

                                            </tr>
                                        @endforeach

                                    </tbody>

                                </table>

                            </div>

                        </div>


                        <div class="petunjuk">

                            <strong>Petunjuk:</strong>

                            Setiap minggu memiliki file laporan Excel
                            masing-masing. Silakan upload laporan sesuai
                            dengan minggu yang sedang berjalan.

                        </div>

                    </div>


                    {{-- ==================================================
                     BULANAN (12 BULAN DALAM 1 TAHUN)
                =================================================== --}}
                    <div class="monitoring-type-content {{ $jenis === 'mingguan' ? 'd-none' : '' }}" id="content-bulanan">

                        <div class="pelaporan-table-card">

                            <div class="pelaporan-card-header">

                                <h5>
                                    Pelaporan Bulanan
                                </h5>

                                <small id="subtitleBulanan">
                                    Tahun {{ $tahun }}
                                </small>

                            </div>


                            <div class="table-responsive">

                                <table class="pelaporan-table">

                                    <thead>

                                        <tr>

                                            <th>
                                                Nama Pegawai
                                            </th>

                                            <th data-bulan="01">Januari</th>
                                            <th data-bulan="02">Februari</th>
                                            <th data-bulan="03">Maret</th>
                                            <th data-bulan="04">April</th>
                                            <th data-bulan="05">Mei</th>
                                            <th data-bulan="06">Juni</th>
                                            <th data-bulan="07">Juli</th>
                                            <th data-bulan="08">Agustus</th>
                                            <th data-bulan="09">September</th>
                                            <th data-bulan="10">Oktober</th>
                                            <th data-bulan="11">November</th>
                                            <th data-bulan="12">Desember</th>

                                        </tr>

                                    </thead>


                                    <tbody>

                                        @foreach ($pegawaiList as $pegawai)
                                            <tr>

                                                <td class="nama-pegawai">
                                                    {{ $pegawai->nama }}
                                                </td>

                                                @foreach (['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'] as $bln)
                                                    @php
                                                        $sudahAda = optional(
                                                            $pelaporanTahun->get($pegawai->id),
                                                        )->firstWhere(function ($item) use ($bln) {
                                                            return (int) $item->bulan === (int) $bln;
                                                        });
                                                    @endphp

                                                    <td class="upload-cell {{ $sudahAda ? 'sudah-upload' : '' }}"
                                                        data-bulan="{{ $bln }}">

                                                        <label class="btn-upload">

                                                            <i class="bi bi-upload"></i>
                                                            {{ $sudahAda ? 'Ganti' : 'Upload' }}

                                                            <input type="file" class="input-upload-pelaporan"
                                                                accept=".xls,.xlsx" data-jenis="Bulanan"
                                                                data-user-id="{{ $pegawai->id }}"
                                                                data-tahun="{{ $tahun }}"
                                                                data-bulan="{{ (int) $bln }}" hidden>

                                                        </label>

                                                        <span class="upload-info">
                                                            {{ $sudahAda->nama_file ?? 'Excel' }}
                                                        </span>

                                                    </td>
                                                @endforeach

                                            </tr>
                                        @endforeach

                                    </tbody>

                                </table>

                            </div>

                        </div>


                        <div class="petunjuk">

                            <strong>Petunjuk:</strong>

                            Setiap bulan (Januari - Desember) memiliki file
                            laporan Excel masing-masing. Silakan upload
                            laporan bulanan sesuai dengan bulan yang
                            bersangkutan untuk tahun yang dipilih.

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================= --}}
    {{-- JAVASCRIPT --}}
    {{-- ============================================================= --}}

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const typeButtons = document.querySelectorAll('.monitoring-type-btn');
            const inputJenis = document.getElementById('inputJenis');
            const contentMingguan = document.getElementById('content-mingguan');
            const contentBulanan = document.getElementById('content-bulanan');
            const filterBulanWrapper = document.getElementById('filterBulanWrapper');
            const filterMingguWrapper = document.getElementById('filterMingguWrapper');
            const filterMinggu = document.getElementById('filterMinggu');
            const periodeBadge = document.getElementById('periodeBadge');

            const periodeMingguan = @json($namaBulan[$bulan] . ' ' . $tahun);
            const periodeBulanan = @json('Tahun ' . $tahun);

            let currentType = @json($jenis);

            /* =========================================================
               POPUP ALERT (seragam dengan halaman Manajemen User)
               ========================================================= */
            function popupSukses(pesan) {
                return Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: pesan,
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#198754',
                    timer: 2500,
                    timerProgressBar: true
                });
            }

            function popupError(pesan) {
                return Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan!',
                    text: pesan,
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#dc3545'
                });
            }

            /* =========================================================
               EVENT JENIS UPLOAD (Mingguan / Bulanan)
               ========================================================= */
            typeButtons.forEach(button => {
                button.addEventListener('click', function() {

                    typeButtons.forEach(btn => btn.classList.remove('active'));
                    this.classList.add('active');

                    currentType = this.dataset.type;
                    inputJenis.value = currentType;

                    // Filter "Bulan" & "Minggu" hanya relevan untuk jenis Mingguan
                    // (tabel Bulanan menampilkan 12 bulan sekaligus)
                    if (currentType === 'mingguan') {
                        filterBulanWrapper.classList.remove('d-none');
                        filterMingguWrapper.classList.remove('d-none');
                    } else {
                        filterBulanWrapper.classList.add('d-none');
                        filterMingguWrapper.classList.add('d-none');
                        filterMinggu.value = 'semua';
                        filterMingguTabel('semua');
                    }

                    updateView();
                });
            });

            function updateView() {
                const mingguan = currentType === 'mingguan';

                contentMingguan.classList.toggle('d-none', !mingguan);
                contentBulanan.classList.toggle('d-none', mingguan);

                periodeBadge.textContent = 'Periode: ' + (mingguan ? periodeMingguan : periodeBulanan);
            }

            /* =========================================================
               FILTER KOLOM MINGGU PADA TABEL MINGGUAN
               value: 'semua' | '1' | '2' | ... | '6'
               ========================================================= */
            function filterMingguTabel(value) {
                const table = contentMingguan.querySelector('table');

                table.querySelectorAll('thead th[data-minggu]').forEach(th => {
                    th.classList.toggle('d-none', !(value === 'semua' || th.dataset.minggu === value));
                });

                table.querySelectorAll('tbody td[data-minggu]').forEach(td => {
                    td.classList.toggle('d-none', !(value === 'semua' || td.dataset.minggu === value));
                });
            }

            filterMinggu.addEventListener('change', function() {
                filterMingguTabel(this.value);
            });

            /* =========================================================
               UPLOAD LAPORAN (AJAX)
               ========================================================= */
            const csrfToken = '{{ csrf_token() }}';
            const urlUpload = '{{ route('pelaporan.upload') }}';
            const MAKS_UKURAN = 10 * 1024 * 1024; // 10 MB (sama dengan validasi server)

            function formatUkuranFile(bytes) {
                if (!bytes) return '';
                const kb = bytes / 1024;
                if (kb < 1024) return kb.toFixed(0) + ' KB';
                return (kb / 1024).toFixed(1) + ' MB';
            }

            document.addEventListener('change', async function(e) {

                if (!e.target.classList.contains('input-upload-pelaporan')) {
                    return;
                }

                const input = e.target;
                const file = input.files[0];

                if (!file) {
                    return;
                }

                const cell = input.closest('.upload-cell');
                const infoSpan = cell.querySelector('.upload-info');
                const label = cell.querySelector('.btn-upload');
                const infoAsli = infoSpan.textContent;
                const jenis = input.dataset.jenis;

                /* ---------- Validasi di browser ---------- */
                const ekstensi = file.name.split('.').pop().toLowerCase();

                if (!['xls', 'xlsx'].includes(ekstensi)) {
                    input.value = '';
                    popupError('File laporan harus berformat Excel (.xls / .xlsx).');
                    return;
                }

                if (file.size > MAKS_UKURAN) {
                    input.value = '';
                    popupError('Ukuran file laporan maksimal 10 MB.');
                    return;
                }

                /* ---------- Konfirmasi kalau mengganti file lama ---------- */
                if (cell.classList.contains('sudah-upload')) {

                    const konfirmasi = await Swal.fire({
                        icon: 'question',
                        title: 'Ganti file laporan?',
                        text: 'File lama pada periode ini akan diganti dengan "' + file.name + '".',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Ganti',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: '#198754',
                        cancelButtonColor: '#6c757d'
                    });

                    if (!konfirmasi.isConfirmed) {
                        input.value = '';
                        return;
                    }
                }

                const formData = new FormData();

                formData.append('file', file);
                formData.append('jenis', jenis);
                formData.append('user_id', input.dataset.userId);
                formData.append('tahun', input.dataset.tahun);
                formData.append('bulan', input.dataset.bulan);

                if (jenis === 'Mingguan') {
                    formData.append('minggu', input.dataset.minggu);
                }

                // Status loading
                infoSpan.textContent = 'Mengupload...';
                label.classList.add('disabled');

                fetch(urlUpload, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        },
                        body: formData,
                    })
                    .then(async (response) => {

                        const result = await response.json().catch(() => ({}));

                        if (!response.ok) {
                            throw new Error(
                                Object.values(result.errors || {})[0]?.[0] ||
                                result.message ||
                                'Upload gagal, silakan coba lagi.'
                            );
                        }

                        return result;
                    })
                    .then((result) => {

                        infoSpan.textContent = result.data.nama_file +
                            (result.data.ukuran_file ?
                                ' (' + formatUkuranFile(result.data.ukuran_file) + ')' :
                                '');

                        cell.classList.add('sudah-upload');

                        // Ganti label tombol "Upload" -> "Ganti"
                        label.childNodes.forEach((node) => {
                            if (node.nodeType === Node.TEXT_NODE && node.textContent.trim() === 'Upload') {
                                node.textContent = ' Ganti ';
                            }
                        });

                        popupSukses(result.message || 'Laporan berhasil diupload.');
                    })
                    .catch((err) => {
                        infoSpan.textContent = infoAsli;
                        popupError(err.message);
                    })
                    .finally(() => {
                        label.classList.remove('disabled');

                        // Supaya bisa upload file yang sama 2x berturut-turut
                        input.value = '';
                    });
            });

            /* =========================================================
               INITIAL
               ========================================================= */
            updateView();
            filterMingguTabel(filterMinggu.value);
        });
    </script>

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
@endsection