@extends('layouts.app')

@section('content')

    <div class="page-heading">

        {{-- =========================================================
             SUCCESS ALERT
        ========================================================== --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">

                <div class="d-flex align-items-center">

                    <i class="bi bi-check-circle-fill me-2"></i>

                    <div>
                        <strong>Berhasil!</strong><br>
                        {{ session('success') }}
                    </div>

                </div>

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                </button>

            </div>
        @endif


        {{-- =========================================================
             ERROR ALERT
        ========================================================== --}}
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">

                <div class="d-flex align-items-start">

                    <i class="bi bi-exclamation-triangle-fill me-2"></i>

                    <div>

                        <strong>Import gagal!</strong>

                        <ul class="mb-0 mt-1">

                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach

                        </ul>

                    </div>

                </div>

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                </button>

            </div>
        @endif


        {{-- =========================================================
             HEADER
        ========================================================== --}}
        <div class="page-title">

            <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">

                <div>

                    <h3 class="kehadiran-title">
                        Kehadiran Pegawai
                    </h3>

                    <p class="text-subtitle text-muted mb-0">
                        Monitoring kehadiran pegawai berdasarkan periode.
                    </p>

                </div>


                <a href="{{ route('admin.kehadiran.import') }}" class="btn btn-success">

                    <i class="bi bi-file-earmark-excel me-1"></i>

                    Import Excel

                </a>

            </div>

        </div>


        {{-- =========================================================
             FILTER
        ========================================================== --}}
        <div class="card filter-card mt-4">

            <div class="card-body">

                <form method="GET" action="{{ route('admin.kehadiran.index') }}">

                    <div class="row g-3 align-items-end">

                        {{-- TAHUN --}}
                        <div class="col-12 col-md-3">

                            <label class="form-label">
                                Tahun
                            </label>

                            <select name="tahun" class="form-select">

                                @for ($y = now()->year - 2; $y <= now()->year + 1; $y++)
                                    <option value="{{ $y }}" {{ (int) $tahun === $y ? 'selected' : '' }}>

                                        {{ $y }}

                                    </option>
                                @endfor

                            </select>

                        </div>


                        {{-- BULAN --}}
                        <div class="col-12 col-md-3">

                            <label class="form-label">
                                Bulan
                            </label>

                            <select name="bulan" class="form-select">

                                @foreach ($bulanList as $key => $nama)
                                    <option value="{{ $key }}" {{ (int) $bulan === (int) $key ? 'selected' : '' }}>

                                        {{ $nama }}

                                    </option>
                                @endforeach

                            </select>

                        </div>


                        {{-- =================================================
                             TRIWULAN
                             Aktifkan kembali jika diperlukan
                        ================================================== --}}
                        {{--
                        <div class="col-12 col-md-3">

                            <label class="form-label">
                                Triwulan
                            </label>

                            <select name="triwulan"
                                    class="form-select">

                                <option value="">
                                    Semua Triwulan
                                </option>

                                <option value="1"
                                    {{ ($triwulan ?? null) == 1 ? 'selected' : '' }}>
                                    Triwulan I
                                </option>

                                <option value="2"
                                    {{ ($triwulan ?? null) == 2 ? 'selected' : '' }}>
                                    Triwulan II
                                </option>

                                <option value="3"
                                    {{ ($triwulan ?? null) == 3 ? 'selected' : '' }}>
                                    Triwulan III
                                </option>

                                <option value="4"
                                    {{ ($triwulan ?? null) == 4 ? 'selected' : '' }}>
                                    Triwulan IV
                                </option>

                            </select>

                        </div>
                        --}}


                        {{-- BUTTON --}}
                        <div class="col-12 col-md-3">

                            <button type="submit" class="btn btn-primary w-100">

                                <i class="bi bi-funnel me-1"></i>

                                Terapkan Filter

                            </button>

                        </div>

                    </div>

                </form>

            </div>

        </div>


        {{-- =========================================================
             STATISTIC CARDS
        ========================================================== --}}
        <div class="row g-3 mt-2">


            {{-- TOTAL PEGAWAI --}}
            <div class="col-12 col-md-6 col-xl-3">

                <div class="card attendance-stat-card">

                    <div class="card-body">

                        <div class="stat-icon stat-blue">

                            <i class="bi bi-people"></i>

                        </div>

                        <div>

                            <span class="stat-label">
                                TOTAL PEGAWAI
                            </span>

                            <h4 class="stat-value">

                                {{ $stats['total_pegawai'] ?? 0 }}

                            </h4>

                            <span class="stat-description">
                                Pegawai terdata
                            </span>

                        </div>

                    </div>

                </div>

            </div>


            {{-- TEPAT WAKTU --}}
            <div class="col-12 col-md-6 col-xl-3">

                <button type="button" class="stat-card-button" data-bs-toggle="modal" data-bs-target="#modalTepatWaktu">

                    <div class="card attendance-stat-card stat-clickable">

                        <div class="card-body">

                            <div class="stat-icon stat-green">

                                <i class="bi bi-check-circle"></i>

                            </div>

                            <div>

                                <span class="stat-label">
                                    TEPAT WAKTU
                                </span>

                                <h4 class="stat-value">

                                    {{ $stats['tepat_waktu'] ?? 0 }}

                                </h4>

                                <span class="stat-description">
                                    Pegawai
                                </span>

                            </div>

                            <i class="bi bi-chevron-right stat-arrow"></i>

                        </div>

                    </div>

                </button>

            </div>


            {{-- TERLAMBAT --}}
            <div class="col-12 col-md-6 col-xl-3">

                <button type="button" class="stat-card-button" data-bs-toggle="modal" data-bs-target="#modalTerlambat">

                    <div class="card attendance-stat-card stat-clickable">

                        <div class="card-body">

                            <div class="stat-icon stat-orange">

                                <i class="bi bi-clock-history"></i>

                            </div>

                            <div>

                                <span class="stat-label">
                                    TERLAMBAT
                                </span>

                                <h4 class="stat-value">

                                    {{ $stats['terlambat'] ?? 0 }}

                                </h4>

                                <span class="stat-description">
                                    Pegawai
                                </span>

                            </div>

                            <i class="bi bi-chevron-right stat-arrow"></i>

                        </div>

                    </div>

                </button>

            </div>


            {{-- PULANG CEPAT --}}
            <div class="col-12 col-md-6 col-xl-3">

                <button type="button" class="stat-card-button" data-bs-toggle="modal" data-bs-target="#modalPulangCepat">

                    <div class="card attendance-stat-card stat-clickable">

                        <div class="card-body">

                            <div class="stat-icon stat-red">

                                <i class="bi bi-box-arrow-right"></i>

                            </div>

                            <div>

                                <span class="stat-label">
                                    PULANG CEPAT
                                </span>

                                <h4 class="stat-value">

                                    {{ $stats['pulang_cepat'] ?? 0 }}

                                </h4>

                                <span class="stat-description">
                                    Pegawai
                                </span>

                            </div>

                            <i class="bi bi-chevron-right stat-arrow"></i>

                        </div>

                    </div>

                </button>

            </div>

        </div>


        {{-- =========================================================
             TABEL PEGAWAI
        ========================================================== --}}
        <div class="card mt-4 attendance-main-card">

            <div class="card-header">

                <div>

                    <h4 class="card-title mb-1" id="tableCardTitle">
                        Daftar Pegawai
                    </h4>

                    <p class="text-muted mb-0" id="tableCardSubtitle">
                        {{ $bulanList[$bulan] ?? '' }} {{ $tahun }}
                    </p>

                </div>

                <div class="d-flex align-items-center gap-2 flex-wrap">

                    {{-- TOGGLE KEHADIRAN / KJK --}}
                    <div class="btn-group view-toggle-group" role="group">

                        <button type="button" class="btn btn-sm btn-primary view-toggle-btn active"
                            id="btnViewKehadiran" onclick="tampilkanView('kehadiran')">

                            <i class="bi bi-calendar-check me-1"></i>
                            Kehadiran

                        </button>

                        <button type="button" class="btn btn-sm btn-outline-primary view-toggle-btn" id="btnViewKjk"
                            onclick="tampilkanView('kjk')">

                            <i class="bi bi-hourglass-split me-1"></i>
                            KJK

                        </button>

                    </div>

                    {{-- EXPORT EXCEL (KJK) --}}
                    <a href="{{ route('admin.kehadiran.kjk.export', ['tahun' => $tahun, 'bulan' => $bulan]) }}"
                        id="btnExportKjk" class="btn btn-sm btn-success d-none">

                        <i class="bi bi-file-earmark-excel me-1"></i>
                        Export Excel

                    </a>

                    <div class="table-total">

                        <i class="bi bi-people me-1"></i>

                        {{ $employees->count() }} Pegawai

                    </div>

                </div>

            </div>


            <div class="card-body">

                {{-- =====================================================
                     VIEW: KEHADIRAN
                ====================================================== --}}
                <div id="viewKehadiran">

                    {{-- SEARCH --}}
                    <div class="attendance-toolbar mb-3">

                        <div class="attendance-search">

                            <i class="bi bi-search"></i>

                            <input type="text" id="searchPegawai" class="form-control"
                                placeholder="Cari nama atau NIP...">

                        </div>

                    </div>


                    {{-- TABLE --}}
                    <div class="table-responsive">

                        <table class="table attendance-table align-middle">

                            <thead>

                                <tr>

                                    <th width="60">
                                        No
                                    </th>

                                    <th>
                                        Pegawai
                                    </th>

                                    <th class="text-center">
                                        Hadir
                                    </th>

                                    <th class="text-center">
                                        Izin
                                    </th>

                                    <th class="text-center">
                                        Sakit
                                    </th>

                                    <th class="text-center">
                                        Tanpa Ket.
                                    </th>

                                    <th class="text-center">
                                        Aksi
                                    </th>

                                </tr>

                            </thead>


                            <tbody id="employeeTable">

                                @forelse ($employees as $index => $employee)
                                    <tr class="employee-row"
                                        data-search="{{ strtolower(($employee['nama'] ?? '') . ' ' . ($employee['nip'] ?? '')) }}">

                                        {{-- NO --}}
                                        <td class="text-muted">

                                            {{ $index + 1 }}

                                        </td>


                                        {{-- PEGAWAI --}}
                                        <td>

                                            <div class="employee-info">

                                                <div class="employee-avatar">

                                                    <i class="bi bi-person"></i>

                                                </div>

                                                <div>

                                                    <div class="employee-name">

                                                        {{ $employee['nama'] ?? '-' }}

                                                    </div>

                                                    <div class="employee-nip">

                                                        <i class="bi bi-person-vcard me-1"></i>

                                                        {{ $employee['nip'] ?? '-' }}

                                                    </div>

                                                </div>

                                            </div>

                                        </td>


                                        {{-- HADIR --}}
                                        <td class="text-center">

                                            <span class="count-badge badge-hadir">

                                                {{ $employee['hadir'] ?? 0 }}

                                            </span>

                                        </td>


                                        {{-- IZIN --}}
                                        <td class="text-center">

                                            <span class="count-badge badge-izin">

                                                {{ $employee['izin'] ?? 0 }}

                                            </span>

                                        </td>


                                        {{-- SAKIT --}}
                                        <td class="text-center">

                                            <span class="count-badge badge-sakit">

                                                {{ $employee['sakit'] ?? 0 }}

                                            </span>

                                        </td>


                                        {{-- TANPA KETERANGAN --}}
                                        <td class="text-center">

                                            <span class="count-badge badge-tanpa">

                                                {{ $employee['tanpa_keterangan'] ?? 0 }}

                                            </span>

                                        </td>


                                        {{-- DETAIL --}}
                                        <td class="text-center">

                                            <button type="button" class="btn btn-sm btn-outline-primary"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalDetailPegawai{{ $employee['user_id'] }}">

                                                <i class="bi bi-calendar3 me-1"></i>

                                                Detail

                                            </button>

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td colspan="7">

                                            <div class="empty-state">

                                                <i class="bi bi-calendar-x"></i>

                                                <h5>
                                                    Belum ada data kehadiran
                                                </h5>

                                                <p>

                                                    Belum ditemukan data pada periode
                                                    {{ $bulanList[$bulan] ?? '' }}
                                                    {{ $tahun }}.

                                                </p>

                                            </div>

                                        </td>

                                    </tr>
                                @endforelse

                            </tbody>

                        </table>

                    </div>


                    {{-- SEARCH EMPTY --}}
                    <div id="searchEmpty" class="empty-state d-none">

                        <i class="bi bi-search"></i>

                        <h5>
                            Pegawai tidak ditemukan
                        </h5>

                        <p>
                            Coba gunakan nama atau NIP yang berbeda.
                        </p>

                    </div>

                </div>
                {{-- /VIEW: KEHADIRAN --}}


                {{-- =====================================================
                     VIEW: KJK (KETERLAMBATAN JAM KERJA)
                ====================================================== --}}
                <div id="viewKjk" class="d-none">

                    {{-- LEGENDA WARNA --}}
                    <div class="kjk-legend">

                        <div class="kjk-legend-item">
                            <span class="kjk-legend-swatch" style="background:#fbfbfd; border:1px solid #e3e7ef;"></span>
                            Tidak ada keterlambatan
                        </div>

                        <div class="kjk-legend-item">
                            <span class="kjk-legend-swatch" style="background:#fff3cd;"></span>
                            1 - 30 menit
                        </div>

                        <div class="kjk-legend-item">
                            <span class="kjk-legend-swatch" style="background:#ffdca8;"></span>
                            31 - 60 menit
                        </div>

                        <div class="kjk-legend-item">
                            <span class="kjk-legend-swatch" style="background:#f8b4b4;"></span>
                            &gt; 60 menit
                        </div>

                    </div>

                    <div class="table-responsive">

                        <table class="table table-bordered table-sm align-middle mb-0 kjk-table">

                            <thead>

                                <tr>

                                    <th style="width: 40px;">No</th>
                                    <th>NIP</th>
                                    <th>Nama</th>

                                    @foreach ($bulanList as $kodeBulan => $namaBulanItem)
                                        <th class="text-center">
                                            {{ substr($namaBulanItem, 0, 3) }}
                                        </th>
                                    @endforeach

                                    <th class="text-center">
                                        Total KJK Per-Tahun
                                    </th>

                                </tr>

                            </thead>

                            <tbody>

                                @forelse ($kjkTahunan as $index => $item)
                                    <tr>

                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td class="kjk-nip">{{ $item['nip'] }}</td>
                                        <td class="kjk-nama">{{ $item['nama'] }}</td>

                                        @foreach ($bulanList as $kodeBulan => $namaBulanItem)
                                            @php
                                                $menit = $item['per_bulan'][$kodeBulan];

                                                $kelasMenit = 'kjk-kosong';

                                                if ($menit > 60) {
                                                    $kelasMenit = 'kjk-tinggi';
                                                } elseif ($menit > 30) {
                                                    $kelasMenit = 'kjk-sedang';
                                                } elseif ($menit > 0) {
                                                    $kelasMenit = 'kjk-rendah';
                                                }
                                            @endphp

                                            <td class="text-center {{ $kelasMenit }}">
                                                {{ $menit > 0 ? $menit : '-' }}
                                            </td>
                                        @endforeach

                                        @php
                                            $totalTahun = $item['total_tahun'];

                                            $kelasTotal = 'kjk-kosong';

                                            if ($totalTahun > 300) {
                                                $kelasTotal = 'kjk-total-tinggi';
                                            } elseif ($totalTahun > 120) {
                                                $kelasTotal = 'kjk-total-sedang';
                                            } elseif ($totalTahun > 0) {
                                                $kelasTotal = 'kjk-total-rendah';
                                            }
                                        @endphp

                                        <td class="text-center fw-bold {{ $kelasTotal }}">
                                            {{ $totalTahun }}
                                        </td>

                                    </tr>

                                @empty

                                    <tr>
                                        <td colspan="16" class="text-center text-muted py-4">
                                            Belum ada data kehadiran tahun {{ $tahun }}.
                                        </td>
                                    </tr>
                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>
                {{-- /VIEW: KJK --}}

            </div>

        </div>

    </div>


    {{-- ============================================================
         MODAL DETAIL SETIAP PEGAWAI
    ============================================================= --}}
    @foreach ($employees as $employee)
        <div class="modal fade" id="modalDetailPegawai{{ $employee['user_id'] }}" tabindex="-1" aria-hidden="true">

            <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">

                <div class="modal-content detail-modal">


                    {{-- HEADER --}}
                    <div class="modal-header">

                        <div>

                            <h5 class="modal-title">

                                <i class="bi bi-calendar3 me-2"></i>

                                Detail Kehadiran

                            </h5>

                            <div class="modal-employee-info">

                                {{ $employee['nama'] ?? '-' }}

                                <span>
                                    • {{ $employee['nip'] ?? '-' }}
                                </span>

                            </div>

                        </div>


                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                        </button>

                    </div>


                    {{-- BODY --}}
                    <div class="modal-body">


                        {{-- RINGKASAN --}}
                        <div class="detail-summary">

                            <div>

                                <span>
                                    Hadir
                                </span>

                                <strong>
                                    {{ $employee['hadir'] ?? 0 }}
                                </strong>

                            </div>


                            <div>

                                <span>
                                    Izin
                                </span>

                                <strong>
                                    {{ $employee['izin'] ?? 0 }}
                                </strong>

                            </div>


                            <div>

                                <span>
                                    Sakit
                                </span>

                                <strong>
                                    {{ $employee['sakit'] ?? 0 }}
                                </strong>

                            </div>


                            <div>

                                <span>
                                    Tanpa Ket.
                                </span>

                                <strong>
                                    {{ $employee['tanpa_keterangan'] ?? 0 }}
                                </strong>

                            </div>

                        </div>


                        {{-- =================================================
                             KALENDER KEHADIRAN
                        ================================================== --}}
                        <div class="attendance-calendar">

                            @php

                                $jumlahHari = \Carbon\Carbon::create($tahun, $bulan, 1)->daysInMonth;

                                $namaHariIndonesia = [
                                    1 => 'Senin',
                                    2 => 'Selasa',
                                    3 => 'Rabu',
                                    4 => 'Kamis',
                                    5 => 'Jumat',
                                    6 => 'Sabtu',
                                    7 => 'Minggu',
                                ];

                                $dataKehadiran = collect($employee['kehadiran'] ?? []);

                            @endphp


                            @for ($hari = 1; $hari <= $jumlahHari; $hari++)
                                @php

                                    $tanggal = \Carbon\Carbon::create($tahun, $bulan, $hari);

                                    $tanggalKey = $tanggal->format('Y-m-d');

                                    $dataHari = $dataKehadiran->firstWhere('tanggal', $tanggalKey);

                                    $hariKe = $tanggal->dayOfWeekIso;

                                    $hariLibur = in_array($hariKe, [6, 7]);

                                    $namaHari = $namaHariIndonesia[$hariKe];

                                    /*
                                     * Weekend selalu dianggap Libur,
                                     * meskipun tidak ada record database.
                                     */
                                    $statusLibur = $hariLibur || ($dataHari['status'] ?? null) === 'Libur';
                                @endphp


                                <div
                                    class="
                                    calendar-day
                                    {{ $hariLibur ? 'calendar-weekend' : '' }}
                                    {{ $statusLibur ? 'calendar-holiday' : '' }}
                                ">


                                    {{-- HEADER TANGGAL --}}
                                    <div class="calendar-day-header">

                                        <strong>
                                            {{ $hari }}
                                        </strong>

                                        <span>
                                            {{ $namaHari }}
                                        </span>

                                    </div>


                                    {{-- =================================================
                                         STATUS LIBUR
                                    ================================================== --}}
                                    @if ($statusLibur)
                                        <div class="calendar-status">

                                            <span class="calendar-badge badge-libur">

                                                <i class="bi bi-calendar-x me-1"></i>

                                                Libur

                                            </span>

                                        </div>

                                        <div class="calendar-holiday-text">
                                            Hari Libur
                                        </div>


                                        {{-- =================================================
                                         DATA KEHADIRAN
                                    ================================================== --}}
                                    @elseif ($dataHari)
                                        <div class="calendar-status">


                                            {{-- HADIR --}}
                                            @if (($dataHari['status'] ?? '') === 'Hadir')
                                                @if (($dataHari['status_waktu'] ?? '') === 'Terlambat')
                                                    <span class="calendar-badge badge-terlambat">
                                                        Terlambat
                                                    </span>
                                                @elseif (($dataHari['status_waktu'] ?? '') === 'Pulang Cepat')
                                                    <span class="calendar-badge badge-pulang">
                                                        Pulang Cepat
                                                    </span>
                                                @elseif (($dataHari['status_waktu'] ?? '') === 'Terlambat & Pulang Cepat')
                                                    <span class="calendar-badge badge-terlambat">
                                                        Terlambat
                                                    </span>

                                                    <span class="calendar-badge badge-pulang">
                                                        Pulang Cepat
                                                    </span>
                                                @elseif (($dataHari['status_waktu'] ?? '') === 'Data Tidak Lengkap')
                                                    <span class="calendar-badge badge-tidak-lengkap">
                                                        Tidak Lengkap
                                                    </span>
                                                @else
                                                    <span class="calendar-badge badge-hadir">
                                                        Tepat Waktu
                                                    </span>
                                                @endif


                                                {{-- IZIN --}}
                                            @elseif (($dataHari['status'] ?? '') === 'Izin')
                                                <span class="calendar-badge badge-izin">
                                                    Izin
                                                </span>


                                                {{-- SAKIT --}}
                                            @elseif (($dataHari['status'] ?? '') === 'Sakit')
                                                <span class="calendar-badge badge-sakit">
                                                    Sakit
                                                </span>

                                                {{-- LIBUR --}}
                                            @elseif (($dataHari['status'] ?? '') === 'Libur')
                                                <span class="calendar-badge badge-libur">

                                                    <i class="bi bi-calendar-x me-1"></i>

                                                    Libur

                                                </span>

                                                <div class="small text-muted mt-2">
                                                    Hari Libur
                                                </div>


                                                {{-- TANPA KETERANGAN --}}
                                            @elseif (($dataHari['status'] ?? '') === 'Tanpa Keterangan')
                                                <span class="calendar-badge badge-tanpa">
                                                    Tanpa Ket.
                                                </span>


                                                {{-- STATUS LAIN --}}
                                            @else
                                                <span class="calendar-badge badge-tanpa">

                                                    {{ $dataHari['status'] ?? 'Tanpa Ket.' }}

                                                </span>
                                            @endif

                                        </div>


                                        {{-- =================================================
                                             JAM MASUK / PULANG
                                        ================================================== --}}
                                        @if (!empty($dataHari['jam_masuk'] ?? null) || !empty($dataHari['jam_pulang'] ?? null))
                                            <div class="calendar-time">

                                                @if (!empty($dataHari['jam_masuk'] ?? null))
                                                    <span>

                                                        <i class="bi bi-box-arrow-in-right"></i>

                                                        {{ $dataHari['jam_masuk'] }}

                                                    </span>
                                                @endif


                                                @if (!empty($dataHari['jam_pulang'] ?? null))
                                                    <span>

                                                        <i class="bi bi-box-arrow-right"></i>

                                                        {{ $dataHari['jam_pulang'] }}

                                                    </span>
                                                @endif

                                            </div>
                                        @endif


                                        {{-- =================================================
                                             TERLAMBAT
                                        ================================================== --}}
                                        @if (($dataHari['menit_terlambat'] ?? 0) > 0)
                                            <small class="calendar-warning">

                                                <i class="bi bi-clock me-1"></i>

                                                +{{ $dataHari['menit_terlambat'] }}
                                                menit

                                            </small>
                                        @endif


                                        {{-- =================================================
                                             PULANG CEPAT
                                        ================================================== --}}
                                        @if (($dataHari['menit_pulang_cepat'] ?? 0) > 0)
                                            <small class="calendar-warning calendar-warning-danger">

                                                <i class="bi bi-clock-history me-1"></i>

                                                -{{ $dataHari['menit_pulang_cepat'] }}
                                                menit

                                            </small>
                                        @endif


                                        {{-- =================================================
                                             KETERANGAN
                                        ================================================== --}}
                                        @if (!empty($dataHari['keterangan'] ?? null))
                                            <div class="calendar-note">

                                                <i class="bi bi-info-circle me-1"></i>

                                                {{ $dataHari['keterangan'] }}

                                            </div>
                                        @endif


                                        {{-- =================================================
                                         TIDAK ADA DATA
                                    ================================================== --}}
                                    @else
                                        <span class="calendar-empty">
                                            -
                                        </span>
                                    @endif

                                </div>
                            @endfor

                        </div>

                    </div>

                </div>

            </div>

        </div>
    @endforeach


    {{-- ============================================================
         MODAL TEPAT WAKTU
    ============================================================= --}}
    <div class="modal fade" id="modalTepatWaktu" tabindex="-1" aria-hidden="true">

        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">

            <div class="modal-content">

                <div class="modal-header">

                    <div>

                        <h5 class="modal-title">

                            <i class="bi bi-check-circle me-2"></i>

                            Pegawai Tepat Waktu

                        </h5>

                        <small class="text-muted">

                            {{ $bulanList[$bulan] ?? '' }}
                            {{ $tahun }}

                        </small>

                    </div>

                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>

                </div>


                <div class="modal-body">

                    <div class="status-modal-summary success">

                        <strong>
                            {{ $tepatWaktuEmployees->count() }}
                        </strong>

                        pegawai memiliki setidaknya satu kehadiran tepat waktu.

                    </div>


                    <div class="table-responsive">

                        <table class="table modal-data-table align-middle">

                            <thead>

                                <tr>

                                    <th width="60">
                                        No
                                    </th>

                                    <th>
                                        Pegawai
                                    </th>

                                    <th class="text-center">
                                        Tepat Waktu
                                    </th>

                                    <th>
                                        Jam Kehadiran
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @forelse ($tepatWaktuEmployees as $index => $employee)
                                    <tr>

                                        <td>
                                            {{ $index + 1 }}
                                        </td>


                                        <td>

                                            <strong>
                                                {{ $employee['nama'] ?? '-' }}
                                            </strong>

                                            <small class="d-block text-muted">
                                                {{ $employee['nip'] ?? '-' }}
                                            </small>

                                        </td>


                                        {{-- FIX JUMLAH --}}
                                        <td class="text-center">

                                            <span class="count-badge badge-hadir">

                                                {{ count($employee['details'] ?? []) }}x

                                            </span>

                                        </td>


                                        <td>

                                            <div class="detail-time-list">

                                                @foreach ($employee['details'] ?? [] as $detail)
                                                    <div class="detail-time-item">

                                                        <span>

                                                            {{ \Carbon\Carbon::parse($detail['tanggal'])->format('d/m') }}

                                                        </span>

                                                        <strong>
                                                            {{ $detail['jam_masuk'] ?? '-' }}
                                                        </strong>

                                                        <span>
                                                            s/d
                                                        </span>

                                                        <strong>
                                                            {{ $detail['jam_pulang'] ?? '-' }}
                                                        </strong>

                                                    </div>
                                                @endforeach

                                            </div>

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td colspan="4" class="text-center text-muted py-4">

                                            Belum ada pegawai tepat waktu.

                                        </td>

                                    </tr>
                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
         MODAL TERLAMBAT
    ============================================================= --}}
    <div class="modal fade" id="modalTerlambat" tabindex="-1" aria-hidden="true">

        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">

            <div class="modal-content">

                <div class="modal-header">

                    <div>

                        <h5 class="modal-title">

                            <i class="bi bi-clock-history me-2"></i>

                            Pegawai Terlambat

                        </h5>

                        <small class="text-muted">

                            {{ $bulanList[$bulan] ?? '' }}
                            {{ $tahun }}

                        </small>

                    </div>

                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>

                </div>


                <div class="modal-body">

                    <div class="status-modal-summary warning">

                        <strong>
                            {{ $terlambatEmployees->count() }}
                        </strong>

                        pegawai memiliki keterlambatan.

                    </div>


                    <div class="table-responsive">

                        <table class="table modal-data-table align-middle">

                            <thead>

                                <tr>

                                    <th width="60">
                                        No
                                    </th>

                                    <th>
                                        Pegawai
                                    </th>

                                    <th class="text-center">
                                        Terlambat
                                    </th>

                                    <th>
                                        Detail Keterlambatan
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @forelse ($terlambatEmployees as $index => $employee)
                                    <tr>

                                        <td>
                                            {{ $index + 1 }}
                                        </td>


                                        <td>

                                            <strong>
                                                {{ $employee['nama'] ?? '-' }}
                                            </strong>

                                            <small class="d-block text-muted">
                                                {{ $employee['nip'] ?? '-' }}
                                            </small>

                                        </td>


                                        {{-- FIX JUMLAH --}}
                                        <td class="text-center">

                                            <span class="count-badge badge-terlambat">

                                                {{ count($employee['details'] ?? []) }}x

                                            </span>

                                        </td>


                                        <td>

                                            <div class="detail-time-list">

                                                @foreach ($employee['details'] ?? [] as $detail)
                                                    <div class="late-detail">

                                                        <div>

                                                            <strong>

                                                                {{ \Carbon\Carbon::parse($detail['tanggal'])->format('d/m') }}

                                                            </strong>

                                                            <span>

                                                                {{ $detail['hari'] ?? '-' }}

                                                            </span>

                                                        </div>


                                                        <div>

                                                            <span class="actual-time">

                                                                Masuk
                                                                {{ $detail['jam_masuk'] ?? '-' }}

                                                            </span>


                                                            <span class="late-duration">

                                                                +{{ $detail['menit'] ?? 0 }}
                                                                menit

                                                            </span>

                                                        </div>

                                                    </div>
                                                @endforeach

                                            </div>

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td colspan="4" class="text-center text-muted py-4">

                                            Tidak ada pegawai terlambat.

                                        </td>

                                    </tr>
                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
         MODAL PULANG CEPAT
    ============================================================= --}}
    <div class="modal fade" id="modalPulangCepat" tabindex="-1" aria-hidden="true">

        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">

            <div class="modal-content">

                <div class="modal-header">

                    <div>

                        <h5 class="modal-title">

                            <i class="bi bi-box-arrow-right me-2"></i>

                            Pegawai Pulang Cepat

                        </h5>

                        <small class="text-muted">

                            {{ $bulanList[$bulan] ?? '' }}
                            {{ $tahun }}

                        </small>

                    </div>

                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>

                </div>


                <div class="modal-body">

                    <div class="status-modal-summary danger">

                        <strong>
                            {{ $pulangCepatEmployees->count() }}
                        </strong>

                        pegawai memiliki catatan pulang cepat.

                    </div>


                    <div class="table-responsive">

                        <table class="table modal-data-table align-middle">

                            <thead>

                                <tr>

                                    <th width="60">
                                        No
                                    </th>

                                    <th>
                                        Pegawai
                                    </th>

                                    <th class="text-center">
                                        Pulang Cepat
                                    </th>

                                    <th>
                                        Detail
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @forelse ($pulangCepatEmployees as $index => $employee)
                                    <tr>

                                        <td>
                                            {{ $index + 1 }}
                                        </td>


                                        <td>

                                            <strong>
                                                {{ $employee['nama'] ?? '-' }}
                                            </strong>

                                            <small class="d-block text-muted">
                                                {{ $employee['nip'] ?? '-' }}
                                            </small>

                                        </td>


                                        {{-- FIX JUMLAH --}}
                                        <td class="text-center">

                                            <span class="count-badge badge-pulang">

                                                {{ count($employee['details'] ?? []) }}x

                                            </span>

                                        </td>


                                        <td>

                                            <div class="detail-time-list">

                                                @foreach ($employee['details'] ?? [] as $detail)
                                                    <div class="late-detail">

                                                        <div>

                                                            <strong>

                                                                {{ \Carbon\Carbon::parse($detail['tanggal'])->format('d/m') }}

                                                            </strong>

                                                            <span>

                                                                {{ $detail['hari'] ?? '-' }}

                                                            </span>

                                                        </div>


                                                        <div>

                                                            <span class="actual-time">

                                                                Pulang
                                                                {{ $detail['jam_pulang'] ?? '-' }}

                                                            </span>


                                                            <span class="early-duration">

                                                                -{{ $detail['menit'] ?? 0 }}
                                                                menit

                                                            </span>

                                                        </div>

                                                    </div>
                                                @endforeach

                                            </div>

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td colspan="4" class="text-center text-muted py-4">

                                            Tidak ada pegawai pulang cepat.

                                        </td>

                                    </tr>
                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
         STYLE
    ============================================================= --}}
    <style>
        /* =========================================================
                                   KJK (KETERLAMBATAN JAM KERJA)
                                ========================================================== */

        .kjk-table th,
        .kjk-table td {
            font-size: 12.5px;
            padding: 7px 8px;
        }

        .view-toggle-group .view-toggle-btn {
            font-weight: 600;
        }

        #viewKjk.d-none,
        #viewKehadiran.d-none {
            display: none !important;
        }

        /* ---- Header tabel KJK ---- */
        .kjk-table thead th {
            background: #25396f;
            color: #ffffff;
            font-weight: 600;
            border-color: #25396f;
            position: sticky;
            top: 0;
            z-index: 1;
        }

        /* ---- Zebra striping & border ---- */
        .kjk-table {
            border: 1px solid #d7dce6;
        }

        .kjk-table td {
            border-color: #e3e7ef;
        }

        .kjk-table tbody tr:nth-child(even) td {
            background-color: #f7f9fc;
        }

        .kjk-table tbody tr:hover td {
            background-color: #eef1fb;
        }

        /* ---- Kolom identitas pegawai ---- */
        .kjk-table .kjk-nip {
            color: #667085;
            white-space: nowrap;
        }

        .kjk-table .kjk-nama {
            color: #25396f;
            font-weight: 600;
            white-space: nowrap;
        }

        /* ---- Sel kosong (tidak ada keterlambatan) ---- */
        .kjk-table .kjk-kosong {
            color: #b4bac6;
            background-color: #fbfbfd;
        }

        /* ---- Tingkat keterlambatan per bulan ---- */
        .kjk-table .kjk-rendah {
            background-color: #fff3cd;
            color: #8a6d00;
            font-weight: 600;
        }

        .kjk-table .kjk-sedang {
            background-color: #ffdca8;
            color: #8a4b00;
            font-weight: 700;
        }

        .kjk-table .kjk-tinggi {
            background-color: #f8b4b4;
            color: #7a1212;
            font-weight: 700;
        }

        /* ---- Total tahunan ---- */
        .kjk-table .kjk-total-rendah {
            background-color: #ffe8b3;
            color: #7a4b00;
        }

        .kjk-table .kjk-total-sedang {
            background-color: #ffc999;
            color: #7a3d00;
        }

        .kjk-table .kjk-total-tinggi {
            background-color: #f28b82;
            color: #ffffff;
        }

        /* ---- Legenda warna KJK ---- */
        .kjk-legend {
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
            margin-bottom: 12px;
            font-size: 12.5px;
        }

        .kjk-legend-item {
            display: flex;
            align-items: center;
            gap: 6px;
            color: #667085;
        }

        .kjk-legend-swatch {
            width: 14px;
            height: 14px;
            border-radius: 4px;
            display: inline-block;
        }

        /* =========================================================
                                   TITLE
                                ========================================================== */

        .kehadiran-title {
            color: #25396f;
            font-weight: 700;
            margin-bottom: 6px;
        }


        /* =========================================================
                                   FILTER
                                ========================================================== */

        .filter-card {
            border: 1px solid #edf0f5;
            border-radius: 12px;
        }


        /* =========================================================
                                   STAT CARD
                                ========================================================== */

        .attendance-stat-card {
            border: 1px solid #edf0f5;
            border-radius: 12px;
            height: 100%;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .025);
        }

        .stat-card-button {
            border: 0;
            padding: 0;
            background: transparent;
            width: 100%;
            text-align: left;
        }

        .stat-card-button .card {
            cursor: pointer;
            transition: .2s ease;
        }

        .stat-card-button:hover .card {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(0, 0, 0, .07);
        }

        .attendance-stat-card .card-body {
            min-height: 105px;
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 18px;
            position: relative;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            min-width: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 21px;
        }

        .stat-blue {
            background: #eef4ff;
            color: #435ebe;
        }

        .stat-green {
            background: #eaf8f1;
            color: #198754;
        }

        .stat-orange {
            background: #fff5df;
            color: #b77900;
        }

        .stat-red {
            background: #fff0f0;
            color: #dc3545;
        }

        .stat-label {
            display: block;
            color: #8993a4;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: .5px;
        }

        .stat-value {
            color: #25396f;
            font-size: 23px;
            font-weight: 700;
            margin: 3px 0 0;
        }

        .stat-description {
            color: #8993a4;
            font-size: 11px;
        }

        .stat-arrow {
            position: absolute;
            right: 16px;
            color: #aab2c0;
        }


        /* =========================================================
                                   MAIN TABLE
                                ========================================================== */

        .attendance-main-card {
            border-radius: 14px;
            border: 1px solid #edf0f5;
            overflow: hidden;
        }

        .attendance-main-card .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            padding: 18px 20px;
        }

        .table-total {
            background: #eef4ff;
            color: #435ebe;
            padding: 7px 11px;
            border-radius: 7px;
            font-size: 12px;
            font-weight: 600;
            white-space: nowrap;
        }


        /* =========================================================
                                   SEARCH
                                ========================================================== */

        .attendance-toolbar {
            display: flex;
            justify-content: space-between;
        }

        .attendance-search {
            position: relative;
            width: 100%;
            max-width: 400px;
        }

        .attendance-search i {
            position: absolute;
            left: 13px;
            top: 50%;
            transform: translateY(-50%);
            color: #8993a4;
            z-index: 2;
        }

        .attendance-search input {
            height: 42px;
            padding-left: 38px;
            border-radius: 8px;
        }


        /* =========================================================
                                   TABLE
                                ========================================================== */

        .attendance-table {
            margin-bottom: 0;
        }

        .attendance-table thead th {
            background: #f8f9fb;
            color: #8993a4;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .3px;
            padding: 12px;
            border-bottom: 1px solid #edf0f4;
        }

        .attendance-table tbody td {
            padding: 13px 12px;
            font-size: 12px;
            border-color: #f0f2f5;
        }

        .attendance-table tbody tr {
            transition: .15s ease;
        }

        .attendance-table tbody tr:hover {
            background: #fafbfd;
        }


        /* =========================================================
                                   EMPLOYEE
                                ========================================================== */

        .employee-info {
            display: flex;
            align-items: center;
            gap: 11px;
        }

        .employee-avatar {
            width: 38px;
            height: 38px;
            min-width: 38px;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #eef4ff;
            color: #435ebe;
            font-size: 17px;
        }

        .employee-name {
            color: #25396f;
            font-weight: 700;
            font-size: 13px;
        }

        .employee-nip {
            color: #8993a4;
            font-size: 10px;
            margin-top: 3px;
        }


        /* =========================================================
                                   COUNT BADGE
                                ========================================================== */

        .count-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 34px;
            padding: 5px 8px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
        }

        .badge-hadir {
            background: #eaf8f1;
            color: #198754;
        }

        .badge-izin {
            background: #eaf4ff;
            color: #2672c9;
        }

        .badge-sakit {
            background: #fff5df;
            color: #b77900;
        }

        .badge-tanpa {
            background: #f0f1f3;
            color: #6c757d;
        }

        .badge-terlambat {
            background: #fff3dc;
            color: #b77900;
        }

        .badge-pulang {
            background: #fff0f0;
            color: #dc3545;
        }

        .badge-tidak-lengkap {
            background: #f0f1f3;
            color: #6c757d;
        }

        .badge-libur {
            background: #eef1f4;
            color: #68717a;
        }


        /* =========================================================
                                   MODAL
                                ========================================================== */

        .modal-content {
            border: 0;
            border-radius: 14px;
            overflow: hidden;
        }

        .modal-header {
            padding: 18px 20px;
            border-bottom: 1px solid #edf0f4;
        }

        .modal-title {
            color: #25396f;
            font-weight: 700;
        }

        .modal-employee-info {
            color: #8993a4;
            font-size: 12px;
            margin-top: 4px;
        }

        .modal-employee-info span {
            color: #a1a8b4;
        }


        /* =========================================================
                                   DETAIL SUMMARY
                                ========================================================== */

        .detail-summary {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-bottom: 20px;
        }

        .detail-summary>div {
            background: #f8f9fb;
            border-radius: 9px;
            padding: 12px;
            text-align: center;
        }

        .detail-summary span {
            display: block;
            color: #8993a4;
            font-size: 10px;
        }

        .detail-summary strong {
            display: block;
            margin-top: 3px;
            color: #25396f;
            font-size: 18px;
        }


        /* =========================================================
                                   CALENDAR
                                ========================================================== */

        .attendance-calendar {
            display: grid;
            grid-template-columns: repeat(7, minmax(130px, 1fr));
            gap: 8px;
        }

        .calendar-day {
            min-height: 125px;
            padding: 10px;
            border: 1px solid #e9edf3;
            border-radius: 9px;
            background: #fff;
        }

        .calendar-weekend {
            background: #fafafa;
            border-color: #e1e4e8;
        }

        .calendar-holiday {
            background: #f7f8fa;
        }

        .calendar-day-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .calendar-day-header strong {
            color: #435ebe;
            font-size: 17px;
        }

        .calendar-day-header span {
            color: #8993a4;
            font-size: 9px;
        }

        .calendar-status {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
        }

        .calendar-badge {
            display: inline-block;
            padding: 4px 6px;
            border-radius: 5px;
            font-size: 9px;
            font-weight: 700;
        }

        .calendar-time {
            display: flex;
            flex-direction: column;
            gap: 3px;
            margin-top: 9px;
            font-family: monospace;
            font-size: 10px;
            color: #667085;
        }

        .calendar-warning {
            display: block;
            margin-top: 5px;
            color: #b77900;
            font-size: 9px;
            font-weight: 600;
        }

        .calendar-warning-danger {
            color: #dc3545;
        }

        .calendar-empty {
            display: block;
            margin-top: 20px;
            text-align: center;
            color: #b7bec9;
            font-size: 11px;
        }

        .calendar-holiday-text {
            margin-top: 8px;
            text-align: center;
            color: #9aa2ad;
            font-size: 10px;
            font-weight: 600;
        }

        .calendar-note {
            margin-top: 7px;
            color: #8993a4;
            font-size: 9px;
            line-height: 1.4;
        }


        /* =========================================================
                                   STATUS MODAL
                                ========================================================== */

        .status-modal-summary {
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 18px;
            font-size: 12px;
        }

        .status-modal-summary strong {
            font-size: 16px;
            margin-right: 3px;
        }

        .status-modal-summary.success {
            background: #eaf8f1;
            color: #198754;
        }

        .status-modal-summary.warning {
            background: #fff5df;
            color: #8a6500;
        }

        .status-modal-summary.danger {
            background: #fff0f0;
            color: #c02d3e;
        }


        /* =========================================================
                                   MODAL TABLE
                                ========================================================== */

        .modal-data-table {
            margin-bottom: 0;
        }

        .modal-data-table th {
            background: #f8f9fb;
            color: #8993a4;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: .3px;
        }

        .modal-data-table td {
            font-size: 12px;
            border-color: #edf0f4;
        }

        .detail-time-list {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .detail-time-item {
            display: flex;
            align-items: center;
            gap: 7px;
            font-size: 11px;
        }

        .detail-time-item span:first-child {
            color: #8993a4;
        }

        .late-detail {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            padding: 7px 9px;
            border-radius: 7px;
            background: #fafbfd;
        }

        .late-detail>div {
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .late-detail strong {
            color: #435ebe;
        }

        .late-detail span {
            color: #8993a4;
        }

        .actual-time {
            color: #667085 !important;
            font-family: monospace;
        }

        .late-duration {
            color: #b77900 !important;
            font-weight: 700;
        }

        .early-duration {
            color: #dc3545 !important;
            font-weight: 700;
        }


        /* =========================================================
                                   EMPTY
                                ========================================================== */

        .empty-state {
            text-align: center;
            padding: 50px 20px;
            color: #8993a4;
        }

        .empty-state i {
            font-size: 38px;
            color: #cbd1da;
        }

        .empty-state h5 {
            margin-top: 12px;
            color: #435ebe;
        }

        .empty-state p {
            font-size: 12px;
        }


        /* =========================================================
                                   DARK MODE
                                ========================================================== */

        body.theme-dark .kehadiran-title,
        html.theme-dark .kehadiran-title,
        body.theme-dark .stat-value,
        html.theme-dark .stat-value,
        body.theme-dark .employee-name,
        html.theme-dark .employee-name,
        body.theme-dark .modal-title,
        html.theme-dark .modal-title,
        body.theme-dark .detail-summary strong,
        html.theme-dark .detail-summary strong {

            color: #e8ecf5;

        }


        body.theme-dark .filter-card,
        html.theme-dark .filter-card,
        body.theme-dark .attendance-stat-card,
        html.theme-dark .attendance-stat-card,
        body.theme-dark .attendance-main-card,
        html.theme-dark .attendance-main-card,
        body.theme-dark .calendar-day,
        html.theme-dark .calendar-day,
        body.theme-dark .modal-content,
        html.theme-dark .modal-content {

            background: #1e2433;
            border-color: #343b4f;

        }


        body.theme-dark .attendance-table thead th,
        html.theme-dark .attendance-table thead th,
        body.theme-dark .modal-data-table th,
        html.theme-dark .modal-data-table th,
        body.theme-dark .detail-summary>div,
        html.theme-dark .detail-summary>div {

            background: #252b3b;

        }


        body.theme-dark .attendance-table tbody tr:hover,
        html.theme-dark .attendance-table tbody tr:hover {

            background: #252b3b;

        }


        body.theme-dark .attendance-table td,
        html.theme-dark .attendance-table td,
        body.theme-dark .modal-data-table td,
        html.theme-dark .modal-data-table td {

            border-color: #343b4f;

        }


        body.theme-dark .kjk-table tbody tr:nth-child(even) td,
        html.theme-dark .kjk-table tbody tr:nth-child(even) td {

            background-color: #202635;

        }


        body.theme-dark .kjk-table tbody tr:hover td,
        html.theme-dark .kjk-table tbody tr:hover td {

            background-color: #2a3145;

        }


        body.theme-dark .kjk-table td,
        html.theme-dark .kjk-table td {

            border-color: #343b4f;

        }


        body.theme-dark .kjk-table .kjk-kosong,
        html.theme-dark .kjk-table .kjk-kosong {

            background-color: #1e2433;
            color: #6b7386;

        }


        body.theme-dark .kjk-table .kjk-nama,
        html.theme-dark .kjk-table .kjk-nama {

            color: #e8ecf5;

        }


        body.theme-dark .kjk-legend-item,
        html.theme-dark .kjk-legend-item {

            color: #a7afc2;

        }


        body.theme-dark .calendar-weekend,
        html.theme-dark .calendar-weekend,
        body.theme-dark .calendar-holiday,
        html.theme-dark .calendar-holiday {

            background: #202635;

        }


        body.theme-dark .late-detail,
        html.theme-dark .late-detail {

            background: #252b3b;

        }


        body.theme-dark .calendar-holiday-text,
        html.theme-dark .calendar-holiday-text {

            color: #8f98a8;

        }


        body.theme-dark .calendar-note,
        html.theme-dark .calendar-note {

            color: #8f98a8;

        }


        /* =========================================================
                                   RESPONSIVE
                                ========================================================== */

        @media (max-width: 992px) {

            .attendance-calendar {
                grid-template-columns: repeat(4,
                        minmax(130px, 1fr));
            }

        }


        @media (max-width: 768px) {

            .detail-summary {
                grid-template-columns: repeat(2, 1fr);
            }

            .attendance-calendar {
                grid-template-columns: repeat(2,
                        minmax(130px, 1fr));
            }

            .attendance-main-card .card-header {
                align-items: flex-start;
                flex-direction: column;
            }

        }


        @media (max-width: 576px) {

            .attendance-calendar {
                grid-template-columns: 1fr;
            }

            .detail-summary {
                grid-template-columns: repeat(2, 1fr);
            }

            .late-detail {
                align-items: flex-start;
                flex-direction: column;
                gap: 4px;
            }

        }
    </style>


    {{-- ============================================================
         JAVASCRIPT TOGGLE VIEW (KEHADIRAN / KJK)
    ============================================================= --}}
    <script>
        function tampilkanView(view) {

            const viewKehadiran = document.getElementById('viewKehadiran');
            const viewKjk = document.getElementById('viewKjk');

            const btnKehadiran = document.getElementById('btnViewKehadiran');
            const btnKjk = document.getElementById('btnViewKjk');

            const title = document.getElementById('tableCardTitle');
            const subtitle = document.getElementById('tableCardSubtitle');

            const btnExportKjk = document.getElementById('btnExportKjk');

            const bulanTahun = @json(($bulanList[$bulan] ?? '') . ' ' . $tahun);
            const tahunSaja = @json('Tahun ' . $tahun);


            if (view === 'kjk') {

                viewKehadiran.classList.add('d-none');
                viewKjk.classList.remove('d-none');

                btnKjk.classList.remove('btn-outline-primary');
                btnKjk.classList.add('btn-primary', 'active');

                btnKehadiran.classList.remove('btn-primary', 'active');
                btnKehadiran.classList.add('btn-outline-primary');

                btnExportKjk.classList.remove('d-none');

                title.textContent = 'KJK (Keterlambatan Jam Kerja)';
                subtitle.textContent = 'Rekap KJK, ' + tahunSaja;

            } else {

                viewKjk.classList.add('d-none');
                viewKehadiran.classList.remove('d-none');

                btnKehadiran.classList.remove('btn-outline-primary');
                btnKehadiran.classList.add('btn-primary', 'active');

                btnKjk.classList.remove('btn-primary', 'active');
                btnKjk.classList.add('btn-outline-primary');

                btnExportKjk.classList.add('d-none');

                title.textContent = 'Daftar Pegawai';
                subtitle.textContent = bulanTahun;

            }

        }
    </script>


    {{-- ============================================================
         JAVASCRIPT SEARCH
    ============================================================= --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const searchInput =
                document.getElementById('searchPegawai');

            const rows =
                document.querySelectorAll('.employee-row');

            const emptyState =
                document.getElementById('searchEmpty');


            if (!searchInput) {
                return;
            }


            searchInput.addEventListener('input', function() {

                const keyword =
                    this.value
                    .toLowerCase()
                    .trim();

                let visible = 0;


                rows.forEach(function(row) {

                    const searchText =
                        row.dataset.search || '';


                    if (searchText.includes(keyword)) {

                        row.style.display = '';

                        visible++;

                    } else {

                        row.style.display = 'none';

                    }

                });


                if (visible === 0 && keyword !== '') {

                    emptyState.classList.remove('d-none');

                } else {

                    emptyState.classList.add('d-none');

                }

            });

        });
    </script>

@endsection
