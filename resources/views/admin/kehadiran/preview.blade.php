@extends('layouts.app')

@section('content')

    <div class="page-heading">

        {{-- =========================================================
        HEADER
    ========================================================== --}}
        <div class="page-title mb-4">
            <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">

                <div>
                    <h3 class="preview-title mb-1">
                        Preview Import Kehadiran
                    </h3>

                    <p class="text-subtitle text-muted mb-0">
                        Periksa data dari file Excel sebelum dimasukkan ke sistem.
                    </p>
                </div>

                {{-- =================================================
                ACTION BUTTON
            ================================================== --}}
                <div class="preview-actions">

                    <a href="{{ route('admin.kehadiran.import') }}" class="btn btn-light-secondary">

                        <i class="bi bi-arrow-left me-1"></i>
                        Upload Ulang
                    </a>

                    {{-- Tombol membuka modal --}}
                    <button type="button" class="btn btn-primary btn-simpan-preview" data-bs-toggle="modal"
                        data-bs-target="#modalSimpanData">

                        <i class="bi bi-cloud-arrow-up me-1"></i>
                        Simpan Data
                    </button>

                </div>
            </div>
        </div>


        {{-- =========================================================
        SUMMARY CARD
    ========================================================== --}}
        <div class="row g-3">

            {{-- PERIODE --}}
            <div class="col-12 col-md-6 col-xl-3">

                <div class="card preview-stat-card">

                    <div class="card-body">

                        <div class="preview-stat-icon icon-blue">
                            <i class="bi bi-calendar3"></i>
                        </div>

                        <div class="preview-stat-content">

                            <span class="preview-stat-label">
                                PERIODE
                            </span>

                            <h5 class="preview-stat-value">
                                {{ $bulanNama }} {{ $tahun }}
                            </h5>

                        </div>

                    </div>

                </div>

            </div>


            {{-- PEGAWAI --}}
            <div class="col-12 col-md-6 col-xl-3">

                <div class="card preview-stat-card">

                    <div class="card-body">

                        <div class="preview-stat-icon icon-purple">
                            <i class="bi bi-people"></i>
                        </div>

                        <div class="preview-stat-content">

                            <span class="preview-stat-label">
                                PEGAWAI
                            </span>

                            <h5 class="preview-stat-value">

                                {{ $jumlahPegawai }}

                                <small>
                                    Pegawai
                                </small>

                            </h5>

                        </div>

                    </div>

                </div>

            </div>


            {{-- TOTAL DATA --}}
            <div class="col-12 col-md-6 col-xl-3">

                <div class="card preview-stat-card">

                    <div class="card-body">

                        <div class="preview-stat-icon icon-green">
                            <i class="bi bi-database"></i>
                        </div>

                        <div class="preview-stat-content">

                            <span class="preview-stat-label">
                                TOTAL DATA
                            </span>

                            <h5 class="preview-stat-value">

                                {{ $totalData }}

                                <small>
                                    Data
                                </small>

                            </h5>

                        </div>

                    </div>

                </div>

            </div>


            {{-- PEMETAAN --}}
            <div class="col-12 col-md-6 col-xl-3">

                <div class="card preview-stat-card">

                    <div class="card-body">

                        <div class="preview-stat-icon icon-orange">
                            <i class="bi bi-person-check"></i>
                        </div>

                        <div class="preview-stat-content">

                            <span class="preview-stat-label">
                                PEMETAAN
                            </span>

                            <h5 class="preview-stat-value">

                                {{ $mapped }}

                                <small>
                                    cocok
                                </small>

                            </h5>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =========================================================
        WARNING UNMAPPED
    ========================================================== --}}
        @if ($unmapped > 0)
            <div class="alert alert-warning mapping-warning mt-4">

                <div class="d-flex align-items-start gap-3">

                    <div class="warning-icon">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>

                    <div>

                        <strong>
                            {{ $unmapped }} pegawai belum terpetakan
                        </strong>

                        <p class="mb-0 mt-1">
                            Data tersebut tetap ditampilkan pada preview,
                            tetapi tidak akan dimasukkan ke database sampai
                            pegawainya tersedia di Manajemen User.
                        </p>

                    </div>

                </div>

            </div>
        @else
            <div class="alert alert-success mapping-success mt-4">

                <div class="d-flex align-items-start gap-3">

                    <div class="success-icon">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>

                    <div>

                        <strong>
                            Semua pegawai berhasil terpetakan
                        </strong>

                        <p class="mb-0 mt-1">
                            Data kehadiran siap dimasukkan ke dalam sistem.
                        </p>

                    </div>

                </div>

            </div>
        @endif


        {{-- =========================================================
        MAIN DATA CARD
    ========================================================== --}}
        <div class="card mt-4 preview-main-card">

            {{-- HEADER --}}
            <div class="card-header preview-card-header">

                <div>

                    <h4 class="card-title mb-1">
                        Data Kehadiran
                    </h4>

                    <p class="text-muted mb-0">
                        Data sudah disesuaikan dengan format Presensi Bulanan BPS.
                    </p>

                </div>

                <div class="preview-badge">

                    <i class="bi bi-file-earmark-excel"></i>

                    {{ $bulanNama }} {{ $tahun }}

                </div>

            </div>


            {{-- BODY --}}
            <div class="card-body">

                {{-- =================================================
                TOOLBAR
            ================================================== --}}
                <div class="preview-toolbar">

                    <div class="search-box">

                        <i class="bi bi-search"></i>

                        <input type="text" id="searchPegawai" class="form-control"
                            placeholder="Cari nama atau NIP pegawai...">

                    </div>

                    <div class="data-info">

                        <span id="jumlahTampil">
                            {{ $jumlahPegawai }}
                        </span>

                        pegawai

                    </div>

                </div>


                {{-- =================================================
                EMPLOYEE LIST
            ================================================== --}}
                <div id="employeeList">

                    @forelse ($employees as $index => $employee)
                        <div class="employee-card"
                            data-search="{{ strtolower(($employee['nip'] ?? '') . ' ' . ($employee['nama'] ?? '')) }}">

                            {{-- =================================================
                            EMPLOYEE HEADER
                        ================================================== --}}
                            <div class="employee-card-header" onclick="toggleEmployee({{ $index }})">

                                <div class="employee-main-info">

                                    <div class="employee-avatar">
                                        <i class="bi bi-person"></i>
                                    </div>

                                    <div class="employee-information">

                                        <div class="employee-name">
                                            {{ $employee['nama'] ?? 'Nama tidak tersedia' }}
                                        </div>

                                        <div class="employee-meta">

                                            <span>
                                                <i class="bi bi-person-vcard"></i>

                                                {{ $employee['nip'] ?: 'NIP tidak tersedia' }}
                                            </span>


                                            @if (!empty($employee['mapped']))
                                                <span class="mapping-status mapped">

                                                    <i class="bi bi-check-circle-fill"></i>

                                                    {{ $employee['mapping'] ?? 'Terpetakan' }}

                                                </span>
                                            @else
                                                <span class="mapping-status unmapped">

                                                    <i class="bi bi-exclamation-circle-fill"></i>

                                                    Belum terpetakan

                                                </span>
                                            @endif

                                        </div>

                                    </div>

                                </div>


                                {{-- RIGHT --}}
                                <div class="employee-right">

                                    <div class="employee-total">

                                        <strong>
                                            {{ count($employee['data'] ?? []) }}
                                        </strong>

                                        <span>
                                            Data
                                        </span>

                                    </div>

                                    <i id="arrow-{{ $index }}" class="bi bi-chevron-down employee-arrow">
                                    </i>

                                </div>

                            </div>


                            {{-- =================================================
                            EMPLOYEE BODY
                        ================================================== --}}
                            <div id="employee-body-{{ $index }}" class="employee-card-body">


                                {{-- TABLE HEADER --}}
                                <div class="attendance-table-header">

                                    <div>
                                        Tanggal
                                    </div>

                                    <div>
                                        Status
                                    </div>

                                    <div>
                                        Jam Kerja
                                    </div>

                                    <div>
                                        Keterangan
                                    </div>

                                </div>


                                {{-- ATTENDANCE DATA --}}
                                @foreach ($employee['data'] ?? [] as $item)
                                    <div class="attendance-item">


                                        {{-- TANGGAL --}}
                                        <div class="attendance-date">

                                            <div class="date-number">

                                                {{ \Carbon\Carbon::parse($item['tanggal'])->format('d') }}

                                            </div>

                                            <div>

                                                <strong>
                                                    {{ $item['hari'] ?? \Carbon\Carbon::parse($item['tanggal'])->translatedFormat('l') }}
                                                </strong>

                                                <small>
                                                    {{ \Carbon\Carbon::parse($item['tanggal'])->translatedFormat('d M Y') }}
                                                </small>

                                            </div>

                                        </div>


                                        {{-- STATUS --}}
                                        <div>

                                            @php
                                                $status = $item['status'] ?? 'Tanpa Keterangan';
                                            @endphp


                                            @if ($status === 'Hadir')
                                                <span class="attendance-status status-hadir">

                                                    <i class="bi bi-check-circle-fill"></i>

                                                    Hadir

                                                </span>
                                            @elseif ($status === 'Sakit')
                                                <span class="attendance-status status-sakit">

                                                    <i class="bi bi-heart-pulse-fill"></i>

                                                    Sakit

                                                </span>
                                            @elseif ($status === 'Izin')
                                                <span class="attendance-status status-izin">

                                                    <i class="bi bi-info-circle-fill"></i>

                                                    Izin

                                                </span>
                                            @elseif ($status === 'Dinas')
                                                <span class="attendance-status status-dinas">

                                                    <i class="bi bi-briefcase-fill"></i>

                                                    Dinas

                                                </span>
                                            @else
                                                <span class="attendance-status status-tanpa">

                                                    <i class="bi bi-dash-circle"></i>

                                                    {{ $status }}

                                                </span>
                                            @endif

                                        </div>


                                        {{-- JAM --}}
                                        <div class="attendance-time">


                                            @if (!empty($item['jam_masuk']))
                                                <span class="time-in">

                                                    <i class="bi bi-box-arrow-in-right"></i>

                                                    {{ $item['jam_masuk'] }}

                                                </span>
                                            @else
                                                <span class="time-empty">
                                                    -
                                                </span>
                                            @endif


                                            <span class="time-arrow">
                                                →
                                            </span>


                                            @if (!empty($item['jam_pulang']))
                                                <span class="time-out">

                                                    <i class="bi bi-box-arrow-right"></i>

                                                    {{ $item['jam_pulang'] }}

                                                </span>
                                            @else
                                                <span class="time-empty">
                                                    -
                                                </span>
                                            @endif

                                        </div>


                                        {{-- KETERANGAN --}}
                                        <div class="attendance-note">

                                            @if (!empty($item['keterangan']))
                                                <span>
                                                    {{ $item['keterangan'] }}
                                                </span>
                                            @else
                                                <span class="text-muted">
                                                    -
                                                </span>
                                            @endif

                                        </div>

                                    </div>
                                @endforeach

                            </div>

                        </div>

                    @empty

                        <div class="empty-data">

                            <i class="bi bi-database-x"></i>

                            <h5>
                                Tidak ada data
                            </h5>

                            <p>
                                Tidak ditemukan data kehadiran pada file Excel.
                            </p>

                        </div>
                    @endforelse

                </div>


                {{-- =================================================
                EMPTY SEARCH
            ================================================== --}}
                <div id="emptySearch" class="empty-search d-none">

                    <i class="bi bi-search"></i>

                    <h5>
                        Pegawai tidak ditemukan
                    </h5>

                    <p>
                        Coba gunakan nama atau NIP yang berbeda.
                    </p>

                </div>

            </div>

        </div>

    </div>


    {{-- =============================================================
    MODAL KONFIRMASI SIMPAN
============================================================= --}}
    <div class="modal fade" id="modalSimpanData" tabindex="-1" aria-labelledby="modalSimpanDataLabel"
        aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content save-modal">


                {{-- HEADER --}}
                <div class="modal-header">

                    <div class="modal-title-wrapper">

                        <div class="modal-save-icon">

                            <i class="bi bi-cloud-arrow-up-fill"></i>

                        </div>

                        <div>

                            <h5 class="modal-title" id="modalSimpanDataLabel">

                                Simpan Data Kehadiran

                            </h5>

                            <p class="modal-subtitle mb-0">
                                Konfirmasi sebelum data dimasukkan
                            </p>

                        </div>

                    </div>


                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>

                </div>


                {{-- BODY --}}
                <div class="modal-body">


                    <div class="save-confirmation">

                        <div class="confirmation-icon">
                            <i class="bi bi-question-circle"></i>
                        </div>

                        <div>

                            <h6>
                                Yakin ingin menyimpan data?
                            </h6>

                            <p>
                                Data kehadiran berikut akan dimasukkan
                                ke dalam database sistem.
                            </p>

                        </div>

                    </div>


                    {{-- SUMMARY --}}
                    <div class="save-summary">

                        <div class="save-summary-item">

                            <div class="summary-item-icon">
                                <i class="bi bi-calendar3"></i>
                            </div>

                            <div>

                                <span>
                                    Periode
                                </span>

                                <strong>
                                    {{ $bulanNama }} {{ $tahun }}
                                </strong>

                            </div>

                        </div>


                        <div class="save-summary-item">

                            <div class="summary-item-icon">
                                <i class="bi bi-people"></i>
                            </div>

                            <div>

                                <span>
                                    Pegawai
                                </span>

                                <strong>
                                    {{ $jumlahPegawai }} Pegawai
                                </strong>

                            </div>

                        </div>


                        <div class="save-summary-item">

                            <div class="summary-item-icon">
                                <i class="bi bi-database"></i>
                            </div>

                            <div>

                                <span>
                                    Total Data
                                </span>

                                <strong>
                                    {{ $totalData }} Data
                                </strong>

                            </div>

                        </div>


                        <div class="save-summary-item">

                            <div class="summary-item-icon">
                                <i class="bi bi-person-check"></i>
                            </div>

                            <div>

                                <span>
                                    Pemetaan
                                </span>

                                <strong class="text-success">
                                    {{ $mapped }} Cocok
                                </strong>

                            </div>

                        </div>

                    </div>


                    {{-- WARNING --}}
                    @if ($unmapped > 0)
                        <div class="save-modal-warning">

                            <i class="bi bi-exclamation-triangle-fill"></i>

                            <div>

                                <strong>
                                    Perhatian
                                </strong>

                                <p>
                                    Terdapat {{ $unmapped }} pegawai yang
                                    belum terpetakan. Data tersebut tidak
                                    akan disimpan sampai pegawai tersedia
                                    di Manajemen User.
                                </p>

                            </div>

                        </div>
                    @else
                        <div class="save-modal-success">

                            <i class="bi bi-check-circle-fill"></i>

                            <span>
                                Semua pegawai sudah berhasil terpetakan.
                            </span>

                        </div>
                    @endif

                </div>


                {{-- FOOTER --}}
                <div class="modal-footer">

                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">

                        <i class="bi bi-x-lg me-1"></i>

                        Batal

                    </button>


                    {{-- FORM SIMPAN --}}
                    <form action="{{ route('admin.kehadiran.import.store') }}" method="POST" id="formSimpanData">

                        @csrf

                        <button type="submit" class="btn btn-primary btn-confirm-save" id="btnConfirmSave">

                            <i class="bi bi-check2-circle me-1"></i>

                            Ya, Simpan Data

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>


    {{-- =============================================================
    STYLE
============================================================= --}}
    <style>
        /* =========================================================
               PAGE HEADER
            ========================================================== */

        .preview-title {
            color: #25396f;
            font-weight: 700;
            margin: 0;
        }

        .preview-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-simpan-preview {
            min-width: 145px;
            font-weight: 600;
        }


        /* =========================================================
               STAT CARD
            ========================================================== */

        .preview-stat-card {
            height: 100%;
            border: 1px solid #edf0f5;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .025);
        }

        .preview-stat-card .card-body {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 18px;
        }

        .preview-stat-icon {
            width: 48px;
            height: 48px;
            min-width: 48px;
            border-radius: 12px;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 21px;
        }

        .icon-blue {
            background: #eef4ff;
            color: #435ebe;
        }

        .icon-purple {
            background: #f2efff;
            color: #7267d8;
        }

        .icon-green {
            background: #eafaf2;
            color: #2ca87f;
        }

        .icon-orange {
            background: #fff5e8;
            color: #ed9b35;
        }

        .preview-stat-label {
            display: block;

            color: #8993a4;
            font-size: 10px;
            font-weight: 700;

            letter-spacing: .5px;
        }

        .preview-stat-value {
            margin: 3px 0 0;

            color: #25396f;

            font-size: 18px;
            font-weight: 700;
        }

        .preview-stat-value small {
            color: #8993a4;

            font-size: 12px;
            font-weight: 500;
        }


        /* =========================================================
               ALERT
            ========================================================== */

        .mapping-warning,
        .mapping-success {
            border: 0;
            border-radius: 10px;
            padding: 15px 18px;
        }

        .mapping-warning {
            background: #fff7df;
            color: #856404;
        }

        .mapping-success {
            background: #eaf8f1;
            color: #198754;
        }

        .warning-icon,
        .success-icon {
            font-size: 21px;
        }

        .mapping-warning p,
        .mapping-success p {
            font-size: 13px;
        }


        /* =========================================================
               MAIN CARD
            ========================================================== */

        .preview-main-card {
            overflow: hidden;

            border: 1px solid #edf0f5;
            border-radius: 14px;
        }

        .preview-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;

            gap: 15px;

            padding: 18px 20px;
        }

        .preview-badge {
            display: inline-flex;
            align-items: center;
            gap: 7px;

            padding: 7px 11px;

            border-radius: 7px;

            background: #eef4ff;
            color: #435ebe;

            font-size: 12px;
            font-weight: 600;

            white-space: nowrap;
        }


        /* =========================================================
               TOOLBAR
            ========================================================== */

        .preview-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;

            gap: 15px;

            margin-bottom: 20px;
        }

        .search-box {
            position: relative;

            width: 100%;
            max-width: 430px;
        }

        .search-box i {
            position: absolute;

            left: 14px;
            top: 50%;

            z-index: 2;

            transform: translateY(-50%);

            color: #8993a4;
        }

        .search-box input {
            height: 42px;

            padding-left: 40px;

            border-radius: 8px;
        }

        .data-info {
            color: #8993a4;

            font-size: 13px;

            white-space: nowrap;
        }

        .data-info span {
            color: #435ebe;
            font-weight: 700;
        }


        /* =========================================================
               EMPLOYEE CARD
            ========================================================== */

        .employee-card {
            overflow: hidden;

            margin-bottom: 12px;

            background: #fff;

            border: 1px solid #e9edf3;
            border-radius: 12px;

            transition: .2s ease;
        }

        .employee-card:hover {
            border-color: #d8dfeb;

            box-shadow: 0 4px 14px rgba(0, 0, 0, .04);
        }

        .employee-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;

            gap: 15px;

            padding: 15px 17px;

            background: #fafbfd;

            cursor: pointer;
        }

        .employee-main-info {
            display: flex;
            align-items: center;

            gap: 12px;

            min-width: 0;
        }

        .employee-avatar {
            display: flex;
            align-items: center;
            justify-content: center;

            width: 40px;
            height: 40px;
            min-width: 40px;

            border-radius: 10px;

            background: #eef4ff;
            color: #435ebe;

            font-size: 18px;
        }

        .employee-name {
            margin-bottom: 4px;

            color: #25396f;

            font-size: 14px;
            font-weight: 700;
        }

        .employee-meta {
            display: flex;
            align-items: center;
            flex-wrap: wrap;

            gap: 9px;

            color: #8993a4;

            font-size: 11px;
        }

        .employee-meta i {
            margin-right: 3px;
        }

        .mapping-status {
            display: inline-flex;
            align-items: center;

            gap: 4px;

            font-weight: 600;
        }

        .mapping-status.mapped {
            color: #198754;
        }

        .mapping-status.unmapped {
            color: #dc3545;
        }


        /* =========================================================
               EMPLOYEE RIGHT
            ========================================================== */

        .employee-right {
            display: flex;
            align-items: center;

            gap: 15px;
        }

        .employee-total {
            min-width: 55px;

            padding: 5px 8px;

            text-align: center;

            background: #fff;

            border: 1px solid #e4e8ef;
            border-radius: 7px;
        }

        .employee-total strong {
            display: block;

            color: #435ebe;

            font-size: 14px;
        }

        .employee-total span {
            color: #8993a4;

            font-size: 9px;
        }

        .employee-arrow {
            color: #8993a4;

            transition: .2s ease;
        }

        .employee-arrow.open {
            transform: rotate(180deg);
        }


        /* =========================================================
               EMPLOYEE BODY
            ========================================================== */

        .employee-card-body {
            display: none;

            border-top: 1px solid #edf0f4;
        }

        .employee-card-body.open {
            display: block;
        }


        /* =========================================================
               ATTENDANCE TABLE
            ========================================================== */

        .attendance-table-header {
            display: grid;

            grid-template-columns: 1.5fr 1fr 1.4fr 1.3fr;

            gap: 15px;

            padding: 10px 17px;

            background: #f8f9fb;
            color: #8993a4;

            font-size: 10px;
            font-weight: 700;

            text-transform: uppercase;

            letter-spacing: .3px;
        }

        .attendance-item {
            display: grid;

            grid-template-columns: 1.5fr 1fr 1.4fr 1.3fr;

            align-items: center;

            gap: 15px;

            padding: 12px 17px;

            border-top: 1px solid #f0f2f5;

            font-size: 12px;
        }

        .attendance-item:hover {
            background: #fbfcfe;
        }


        /* =========================================================
               DATE
            ========================================================== */

        .attendance-date {
            display: flex;
            align-items: center;

            gap: 10px;
        }

        .date-number {
            display: flex;
            align-items: center;
            justify-content: center;

            width: 35px;
            height: 35px;
            min-width: 35px;

            border-radius: 8px;

            background: #eef4ff;
            color: #435ebe;

            font-size: 13px;
            font-weight: 700;
        }

        .attendance-date strong {
            display: block;

            color: #435ebe;

            font-size: 12px;
        }

        .attendance-date small {
            display: block;

            margin-top: 2px;

            color: #8993a4;

            font-size: 10px;
        }


        /* =========================================================
               STATUS
            ========================================================== */

        .attendance-status {
            display: inline-flex;
            align-items: center;

            gap: 5px;

            padding: 5px 9px;

            border-radius: 6px;

            font-size: 10px;
            font-weight: 600;
        }

        .status-hadir {
            background: #eaf8f1;
            color: #198754;
        }

        .status-sakit {
            background: #fff5df;
            color: #b77900;
        }

        .status-izin {
            background: #eaf4ff;
            color: #2672c9;
        }

        .status-tanpa {
            background: #f0f1f3;
            color: #6c757d;
        }


        /* =========================================================
               TIME
            ========================================================== */

        .attendance-time {
            display: flex;
            align-items: center;

            gap: 7px;

            white-space: nowrap;

            font-family: monospace;

            font-size: 11px;
        }

        .time-in {
            color: #198754;
        }

        .time-out {
            color: #2672c9;
        }

        .time-arrow {
            color: #b2b7c0;
        }

        .time-empty {
            color: #aab0b8;
        }


        /* =========================================================
               NOTE
            ========================================================== */

        .attendance-note {
            overflow: hidden;

            color: #6c757d;

            text-overflow: ellipsis;

            white-space: nowrap;
        }


        /* =========================================================
               EMPTY
            ========================================================== */

        .empty-search,
        .empty-data {
            padding: 50px 20px;

            text-align: center;

            color: #8993a4;
        }

        .empty-search i,
        .empty-data i {
            color: #cbd1da;

            font-size: 38px;
        }

        .empty-search h5,
        .empty-data h5 {
            margin-top: 12px;

            color: #435ebe;
        }

        .empty-search p,
        .empty-data p {
            font-size: 13px;
        }


        /* =========================================================
               SAVE MODAL
            ========================================================== */

        .save-modal {
            overflow: hidden;

            border: 0;

            border-radius: 14px;

            box-shadow: 0 15px 50px rgba(0, 0, 0, .15);
        }

        .save-modal .modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;

            padding: 20px;

            border-bottom: 1px solid #edf0f5;
        }

        .modal-title-wrapper {
            display: flex;
            align-items: center;

            gap: 12px;
        }

        .modal-save-icon {
            display: flex;
            align-items: center;
            justify-content: center;

            width: 44px;
            height: 44px;

            border-radius: 10px;

            background: #eef4ff;
            color: #435ebe;

            font-size: 19px;
        }

        .save-modal .modal-title {
            color: #25396f;

            font-size: 16px;
            font-weight: 700;
        }

        .modal-subtitle {
            color: #8993a4;

            font-size: 11px;
        }

        .save-modal .modal-body {
            padding: 20px;
        }


        /* =========================================================
               CONFIRMATION
            ========================================================== */

        .save-confirmation {
            display: flex;
            align-items: flex-start;

            gap: 12px;

            margin-bottom: 18px;

            padding: 13px;

            border-radius: 9px;

            background: #f8f9fb;
        }

        .confirmation-icon {
            color: #435ebe;

            font-size: 20px;
        }

        .save-confirmation h6 {
            margin-bottom: 4px;

            color: #25396f;

            font-size: 13px;
            font-weight: 700;
        }

        .save-confirmation p {
            margin: 0;

            color: #8993a4;

            font-size: 11px;

            line-height: 1.5;
        }


        /* =========================================================
               SAVE SUMMARY
            ========================================================== */

        .save-summary {
            display: grid;

            grid-template-columns: 1fr 1fr;

            gap: 8px;

            margin-bottom: 15px;
        }

        .save-summary-item {
            display: flex;
            align-items: center;

            gap: 9px;

            padding: 10px;

            border: 1px solid #edf0f5;

            border-radius: 8px;
        }

        .summary-item-icon {
            display: flex;
            align-items: center;
            justify-content: center;

            width: 32px;
            height: 32px;
            min-width: 32px;

            border-radius: 7px;

            background: #f4f6fa;
            color: #435ebe;

            font-size: 13px;
        }

        .save-summary-item span {
            display: block;

            color: #8993a4;

            font-size: 9px;
        }

        .save-summary-item strong {
            display: block;

            margin-top: 2px;

            color: #25396f;

            font-size: 11px;
        }


        /* =========================================================
               MODAL WARNING / SUCCESS
            ========================================================== */

        .save-modal-warning,
        .save-modal-success {
            display: flex;
            align-items: flex-start;

            gap: 9px;

            padding: 11px;

            border-radius: 8px;

            font-size: 11px;
        }

        .save-modal-warning {
            background: #fff7df;
            color: #856404;
        }

        .save-modal-success {
            align-items: center;

            background: #eaf8f1;
            color: #198754;
        }

        .save-modal-warning i,
        .save-modal-success i {
            font-size: 15px;
        }

        .save-modal-warning strong {
            display: block;

            margin-bottom: 2px;
        }

        .save-modal-warning p {
            margin: 0;

            line-height: 1.5;
        }


        /* =========================================================
               MODAL FOOTER
            ========================================================== */

        .save-modal .modal-footer {
            padding: 15px 20px;

            border-top: 1px solid #edf0f5;
        }

        .btn-confirm-save {
            min-width: 145px;

            font-weight: 600;
        }


        /* =========================================================
               DARK MODE
            ========================================================== */

        body.theme-dark .preview-title,
        html.theme-dark .preview-title,

        body.theme-dark .preview-stat-value,
        html.theme-dark .preview-stat-value,

        body.theme-dark .employee-name,
        html.theme-dark .employee-name,

        body.theme-dark .save-modal .modal-title,
        html.theme-dark .save-modal .modal-title,

        body.theme-dark .save-confirmation h6,
        html.theme-dark .save-confirmation h6,

        body.theme-dark .save-summary-item strong,
        html.theme-dark .save-summary-item strong {
            color: #e8ecf5;
        }

        body.theme-dark .preview-stat-card,
        html.theme-dark .preview-stat-card,

        body.theme-dark .preview-main-card,
        html.theme-dark .preview-main-card,

        body.theme-dark .employee-card,
        html.theme-dark .employee-card {
            background: #1e2433;

            border-color: #343b4f;
        }

        body.theme-dark .employee-card-header,
        html.theme-dark .employee-card-header,

        body.theme-dark .attendance-table-header,
        html.theme-dark .attendance-table-header,

        body.theme-dark .save-confirmation,
        html.theme-dark .save-confirmation {
            background: #252b3b;
        }

        body.theme-dark .attendance-item:hover,
        html.theme-dark .attendance-item:hover {
            background: #252b3b;
        }

        body.theme-dark .attendance-item,
        html.theme-dark .attendance-item,

        body.theme-dark .employee-card-body,
        html.theme-dark .employee-card-body,

        body.theme-dark .preview-card-header,
        html.theme-dark .preview-card-header {
            border-color: #343b4f;
        }

        body.theme-dark .preview-badge,
        html.theme-dark .preview-badge {
            background: #252d43;
            color: #91b5ff;
        }

        body.theme-dark .employee-total,
        html.theme-dark .employee-total {
            background: #1e2433;
            border-color: #3a4255;
        }

        body.theme-dark .mapping-warning,
        html.theme-dark .mapping-warning {
            background: #3a321d;
            color: #e5c96b;
        }

        body.theme-dark .mapping-success,
        html.theme-dark .mapping-success {
            background: #19352b;
            color: #72d6ac;
        }

        body.theme-dark .save-modal,
        html.theme-dark .save-modal {
            background: #1e2433;
        }

        body.theme-dark .save-modal .modal-header,
        html.theme-dark .save-modal .modal-header,

        body.theme-dark .save-modal .modal-footer,
        html.theme-dark .save-modal .modal-footer {
            border-color: #343b4f;
        }

        body.theme-dark .save-summary-item,
        html.theme-dark .save-summary-item {
            border-color: #343b4f;
        }

        body.theme-dark .summary-item-icon,
        html.theme-dark .summary-item-icon {
            background: #252b3b;
        }

        body.theme-dark .save-modal-warning,
        html.theme-dark .save-modal-warning {
            background: #3a321d;
            color: #e5c96b;
        }

        body.theme-dark .save-modal-success,
        html.theme-dark .save-modal-success {
            background: #19352b;
            color: #72d6ac;
        }


        /* =========================================================
               RESPONSIVE
            ========================================================== */

        @media (max-width: 900px) {

            .attendance-table-header {
                display: none;
            }

            .attendance-item {
                grid-template-columns: 1fr 1fr;

                gap: 10px;
            }

            .attendance-note {
                grid-column: 1 / -1;
            }

        }


        @media (max-width: 600px) {

            .preview-actions {
                width: 100%;
            }

            .preview-actions>*,
            .preview-actions form {
                flex: 1;
            }

            .preview-actions .btn {
                width: 100%;
            }

            .preview-toolbar {
                align-items: stretch;

                flex-direction: column;
            }

            .search-box {
                max-width: none;
            }

            .employee-card-header {
                padding: 13px;
            }

            .employee-right {
                gap: 8px;
            }

            .employee-total {
                display: none;
            }

            .attendance-item {
                grid-template-columns: 1fr;
            }

            .attendance-note {
                grid-column: auto;
            }

            .save-summary {
                grid-template-columns: 1fr;
            }

            .save-modal .modal-footer {
                flex-direction: column;
            }

            .save-modal .modal-footer>*,
            .save-modal .modal-footer form,
            .save-modal .modal-footer button {
                width: 100%;
            }

            .btn-confirm-save {
                width: 100%;
            }

        }
    </style>


    {{-- =============================================================
    JAVASCRIPT
============================================================= --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {


            /* =====================================================
               TOGGLE PEGAWAI
            ====================================================== */

            window.toggleEmployee = function(index) {

                const body = document.getElementById(
                    'employee-body-' + index
                );

                const arrow = document.getElementById(
                    'arrow-' + index
                );


                if (!body) {
                    return;
                }


                body.classList.toggle('open');


                if (arrow) {

                    arrow.classList.toggle('open');

                }

            };


            /* =====================================================
               SEARCH PEGAWAI
            ====================================================== */

            const searchInput = document.getElementById(
                'searchPegawai'
            );


            if (searchInput) {

                searchInput.addEventListener('input', function() {

                    const keyword = this.value
                        .toLowerCase()
                        .trim();


                    const employees = document.querySelectorAll(
                        '.employee-card'
                    );


                    let visible = 0;


                    employees.forEach(function(employee) {

                        const text = (
                            employee.dataset.search || ''
                        ).toLowerCase();


                        if (text.includes(keyword)) {

                            employee.style.display = '';

                            visible++;

                        } else {

                            employee.style.display = 'none';

                        }

                    });


                    const counter = document.getElementById(
                        'jumlahTampil'
                    );


                    if (counter) {

                        counter.textContent = visible;

                    }


                    const emptySearch = document.getElementById(
                        'emptySearch'
                    );


                    if (emptySearch) {

                        if (visible === 0) {

                            emptySearch.classList.remove(
                                'd-none'
                            );

                        } else {

                            emptySearch.classList.add(
                                'd-none'
                            );

                        }

                    }

                });

            }


            /* =====================================================
               FORM SIMPAN
            ====================================================== */

            const formSimpan = document.getElementById(
                'formSimpanData'
            );


            const btnConfirmSave = document.getElementById(
                'btnConfirmSave'
            );


            if (formSimpan && btnConfirmSave) {

                formSimpan.addEventListener('submit', function() {

                    btnConfirmSave.disabled = true;


                    btnConfirmSave.innerHTML = `
                    <span class="spinner-border spinner-border-sm me-1"
                          role="status"
                          aria-hidden="true">
                    </span>
                    Menyimpan...
                `;

                });

            }

        });
    </script>

@endsection
