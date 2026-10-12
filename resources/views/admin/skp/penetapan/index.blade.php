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

        .upload-status {
            display: block;
            margin-bottom: 3px;
            color: #198754 !important;
            font-size: 11px;
            font-weight: 700;
        }

        .upload-status .bi {
            margin-right: 3px;
        }

        .upload-file-name {
            display: block;
            color: #dc3545 !important;
            font-size: 11px;
            font-weight: 600;
            word-break: break-word;
        }

        .approval-status {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .approval-status.menunggu { background: #fff3cd; color: #856404; }
        .approval-status.disetujui { background: #d1e7dd; color: #0f5132; }
        .approval-status.ditolak { background: #f8d7da; color: #842029; }

        .approval-reason {
            display: block;
            margin-top: 5px;
            color: #842029 !important;
            font-size: 11px;
            line-height: 1.45;
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

        body.theme-dark .upload-status,
        html.theme-dark .upload-status {
            color: #6ee7a8 !important;
        }

        body.theme-dark .upload-file-name,
        html.theme-dark .upload-file-name {
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

        {{-- ================= HEADER ================= --}}
        <div class="skp-heading">
            <div>
                <h3>SKP</h3>
                <p>Pengelolaan Sasaran Kinerja Pegawai</p>
            </div>

            <div class="periode-badge" id="periodeBadge">
                Periode: Tahun {{ $tahun }}
            </div>
        </div>


        {{-- ================= FILTER PENETAPAN ================= --}}
        <div class="card skp-filter-card">

            <div class="card-body">

                <div class="skp-filter-title">
                    <i class="bi bi-funnel"></i>
                    Periode SKP Penetapan
                </div>

                <div class="row g-3 align-items-end">

                    <div class="col-md-4">
                        <label class="form-label">Tahun</label>

                        <select class="form-select" id="filterTahun">
                            @foreach ([2024, 2025, 2026, 2027] as $th)
                                <option value="{{ $th }}"
                                    {{ $tahun == $th ? 'selected' : '' }}>
                                    {{ $th }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <div class="form-control monitoring-readonly">
                            <i class="bi bi-calendar-check"></i>
                            SKP Penetapan Tahunan
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

                    <div class="monitoring-type-content" id="content-tahunan">

                        <div class="skp-table-wrapper">

                            <div class="skp-section-header">

                                <h5>
                                    <i class="bi bi-clipboard-check"></i>
                                    Upload SKP Penetapan Tahunan
                                </h5>

                                <small id="subtitleTahunan">
                                    Tahun {{ $tahun }}
                                </small>

                            </div>

                            <div class="table-responsive">

                                <table class="table skp-table mb-0">

                                    <thead>
                                        <tr>
                                            <th style="width: 60px;">No</th>
                                            <th>Nama Pegawai</th>
                                            <th class="text-center" style="min-width: 260px;">
                                                Dokumen SKP Penetapan Tahunan
                                            </th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @forelse ($dataTahunan as $index => $item)
                                            @php
                                                $skpTahunan = $item['skp'];
                                            @endphp

                                            <tr>
                                                <td class="text-center">
                                                    {{ $index + 1 }}
                                                </td>

                                                <td class="nama-pegawai">
                                                    {{ $item['pegawai']->nama }}
                                                </td>

                                                <td class="upload-cell text-center {{ $skpTahunan ? 'sudah-upload' : '' }}">

                                                    <label class="btn-upload-pdf mb-0">
                                                        <i class="bi bi-upload"></i>
                                                        {{ $skpTahunan ? 'Ganti' : 'Upload' }}

                                                        <input type="file"
                                                            class="input-upload-skp"
                                                            accept=".pdf,application/pdf"
                                                            data-jenis="Tahunan"
                                                            data-user-id="{{ $item['pegawai']->id }}"
                                                            data-tahun="{{ $tahun }}"
                                                            hidden>
                                                    </label>

                                                    <div class="upload-info small text-muted">
                                                        @if ($skpTahunan)
                                                            @php
                                                                $statusPenetapan = $skpTahunan->status ?? 'menunggu';
                                                            @endphp

                                                            @if ($statusPenetapan === 'disetujui')
                                                                <span class="approval-status disetujui">
                                                                    <i class="bi bi-check-circle-fill"></i>
                                                                    Disetujui
                                                                </span>
                                                            @elseif ($statusPenetapan === 'ditolak')
                                                                <span class="approval-status ditolak">
                                                                    <i class="bi bi-x-circle-fill"></i>
                                                                    Ditolak
                                                                </span>

                                                                @if ($skpTahunan->keterangan)
                                                                    <span class="approval-reason">
                                                                        {{ $skpTahunan->keterangan }}
                                                                    </span>
                                                                @endif
                                                            @else
                                                                <span class="approval-status menunggu">
                                                                    <i class="bi bi-clock-fill"></i>
                                                                    Menunggu persetujuan
                                                                </span>
                                                            @endif

                                                            @if ($skpTahunan->nama_file)
                                                                <span class="upload-file-name">
                                                                    {{ $skpTahunan->nama_file }}
                                                                </span>
                                                            @endif
                                                        @else
                                                            -
                                                        @endif
                                                    </div>

                                                </td>
                                            </tr>

                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center text-muted py-4">
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
                            Upload dokumen SKP Penetapan Tahunan dalam format PDF
                            untuk masing-masing pegawai pada tahun yang dipilih.
                        </div>

                    </div>

                </div>

            </div>

        </div>

    {{-- SWEETALERT2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tahun = document.getElementById('filterTahun');
            const btn = document.getElementById('btnTampilkan');
            const csrfToken = '{{ csrf_token() }}';
            const urlUploadSkp = '{{ route('admin.skp.upload') }}';

            btn.addEventListener('click', function () {
                const params = new URLSearchParams({
                    tahun: tahun.value
                });

                window.location.href =
                    '{{ route('admin.skp.penetapan') }}?' +
                    params.toString();
            });

            document.addEventListener('change', function (e) {
                if (!e.target.classList.contains('input-upload-skp')) {
                    return;
                }

                const input = e.target;
                const file = input.files[0];

                if (!file) {
                    return;
                }

                if (file.type !== 'application/pdf') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Format File Tidak Sesuai!',
                        text: 'Dokumen SKP harus berformat PDF.',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#dc3545'
                    });

                    input.value = '';
                    return;
                }

                const cell = input.closest('.upload-cell');
                const infoDiv = cell.querySelector('.upload-info');
                const label = cell.querySelector('.btn-upload-pdf');
                const infoAsli = infoDiv.innerHTML;
                const labelAsli = label.innerHTML;

                const formData = new FormData();
                formData.append('file', file);
                formData.append('jenis', 'Tahunan');
                formData.append('user_id', input.dataset.userId);
                formData.append('tahun', input.dataset.tahun);

                infoDiv.textContent = 'Mengupload...';
                label.classList.add('disabled');

                fetch(urlUploadSkp, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(async response => {
                    const result = await response.json();

                    if (!response.ok) {
                        throw new Error(
                            result.message ||
                            Object.values(result.errors || {})[0]?.[0] ||
                            'Upload gagal, silakan coba lagi.'
                        );
                    }

                    return result;
                })
                .then(result => {
                    infoDiv.innerHTML = '';

                    const statusSpan = document.createElement('span');
                    statusSpan.className = 'approval-status menunggu';
                    statusSpan.innerHTML =
                        '<i class="bi bi-clock-fill"></i> Menunggu persetujuan';

                    const fileNameSpan = document.createElement('span');
                    fileNameSpan.className = 'upload-file-name';
                    fileNameSpan.textContent = result.data.nama_file;

                    infoDiv.appendChild(statusSpan);
                    infoDiv.appendChild(fileNameSpan);

                    cell.classList.add('sudah-upload');
                    label.innerHTML =
                        '<i class="bi bi-upload"></i> Ganti' +
                        '<input type="file" class="input-upload-skp" accept=".pdf,application/pdf"' +
                        ' data-jenis="Tahunan" data-user-id="' + input.dataset.userId +
                        '" data-tahun="' + input.dataset.tahun + '" hidden>';
                    label.classList.remove('disabled');

                    Swal.fire({
                        icon: 'success',
                        title: 'Upload Berhasil!',
                        text: result.message || 'Dokumen SKP Tahunan berhasil diupload.',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#198754',
                        timer: 2500,
                        timerProgressBar: true
                    });
                })
                .catch(err => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Upload Gagal!',
                        text: err.message || 'Terjadi kesalahan saat mengupload dokumen SKP.',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#dc3545'
                    });

                    infoDiv.innerHTML = infoAsli;
                    label.innerHTML = labelAsli;
                    label.classList.remove('disabled');
                })
                .finally(() => {
                    input.value = '';
                });
            });
        });
    </script>

@endsection