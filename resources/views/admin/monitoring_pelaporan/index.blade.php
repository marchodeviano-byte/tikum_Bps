@extends('layouts.app')

@section('content')

    @php
        $jenisDb = $jenisAktif === 'bulanan' ? 'Bulanan' : 'Mingguan';

        // Parameter filter yang sedang aktif (dipakai tombol download & reset)
        $paramFilter = [
            'jenis' => $jenisAktif,
            'tahun' => $tahun,
            'bulan' => $bulan === 0 ? 'semua' : $bulan,
        ];

        if ($jenisAktif === 'mingguan') {
            $paramFilter['minggu'] = $mingguDipilih;
        }

        $periodeBadge = $labelBulan . ' ' . $tahun;

        if ($jenisAktif === 'mingguan' && $mingguDipilih !== 'semua') {
            $periodeBadge .= ' - Minggu ' . $mingguDipilih;
        }

        $labelRingkasan = $jenisAktif === 'bulanan'
            ? 'Bulan ' . $namaBulan[$bulanRingkasan]
            : 'Minggu ' . $mingguRingkasan;

        $namaZip = 'Laporan_Semua_Pegawai_' . $jenisDb . '_' . $tahun . '_' . ($bulan === 0 ? 'SemuaBulan' : str_pad((string) $bulan, 2, '0', STR_PAD_LEFT))
            . ($jenisAktif === 'mingguan' && $mingguDipilih !== 'semua' ? '_Minggu-' . $mingguDipilih : '')
            . '.zip';
    @endphp

    <style>
        /* =========================================================
                           MONITORING SKP EVALUASI
                        ========================================================= */

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
            font-weight: 500;
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

        .skp-filter-title i {
            margin-right: 7px;
        }

        .skp-filter-card .form-label {
            color: #465466 !important;
            font-weight: 600;
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

        .skp-filter-btn {
            height: 42px;
            font-weight: 600;
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

        /* ================= STATISTIK ================= */

        .pelaporan-stat-card {
            background: #ffffff !important;
            border: 1px solid #dce2e8;
            border-radius: 9px;
            margin-bottom: 28px;
        }

        .pelaporan-stat-card .card-body {
            padding: 20px 22px;
        }

        .pelaporan-stat-icon {
            width: 48px;
            height: 48px;
            min-width: 48px;
            border-radius: 10px;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 20px;
            color: #ffffff !important;

            margin-right: 16px;
        }

        .pelaporan-stat-icon.total {
            background: #3d6df2 !important;
        }

        .pelaporan-stat-icon.uploaded {
            background: #198754 !important;
        }

        .pelaporan-stat-icon.pending {
            background: #dc3545 !important;
        }

        .pelaporan-stat-card h6 {
            color: #7a8794 !important;
            font-size: 12px;
            margin-bottom: 4px;
        }

        .pelaporan-stat-card h4 {
            color: #1d2733 !important;
            font-size: 22px;
            font-weight: 700;
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

        /* ================= TABLE HEADER ================= */

        .skp-section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;

            background: #075a9f !important;
            color: #ffffff !important;

            padding: 18px 22px;
            border-radius: 8px 8px 0 0;
        }

        .skp-section-header h5 {
            color: #ffffff !important;
            margin: 0 0 4px;
            font-size: 17px;
            font-weight: 700;
        }

        .skp-section-header h5 i {
            margin-right: 7px;
        }

        .skp-section-header small {
            display: block;
            color: #e7f1ff !important;
            opacity: .95;
        }

        /* ================= ACTION BUTTON ================= */

        .skp-section-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 8px;
            flex-wrap: wrap;
        }

        .skp-section-actions .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;

            font-weight: 600;
            border-radius: 6px;
            min-height: 36px;
        }

        .skp-section-actions .btn:disabled {
            cursor: not-allowed;
            opacity: .65;
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
            padding: 14px 12px;
        }

        .skp-table thead th {
            background: #f8fafc !important;
            color: #07549b !important;
            text-align: center;
            font-weight: 700;
            white-space: nowrap;
        }

        .skp-table tbody td {
            background: #ffffff !important;
            color: #29313a !important;
        }

        .nama-pegawai {
            font-weight: 700;
            color: #1d2733 !important;
        }

        /* ================= DOKUMEN ================= */

        .dokumen-review-status {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 7px;
        }

        .dokumen-file-name {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;

            width: 100%;

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

        .status-mini {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 5px;

            min-height: 30px;
            padding: 6px 10px;

            border-radius: 6px;

            font-size: 11px;
            font-weight: 600;

            text-decoration: none;
        }

        .status-mini.sudah {
            background: #d1e7dd;
            color: #198754;
        }

        .status-mini.belum {
            background: #fff3cd;
            color: #856404;
        }

        .status-mini.ditolak {
            background: #f8d7da;
            color: #842029;
        }

        .status-mini:hover {
            filter: brightness(.96);
        }

        .btn-review-dokumen {
            font-size: 11px;
            padding: 4px 8px;
        }

        .btn-lihat-keterangan {
            font-size: 11px;
            padding: 4px 8px;
        }

        /* ================= KETERANGAN ================= */

        .keterangan-status {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
        }

        .keterangan-status small {
            font-size: 11px;
        }

        .keterangan-penolakan {
            white-space: pre-line;
            text-align: left;
            line-height: 1.65;
            color: #4d5968;
            font-size: 14px;
        }

        /* ================= NOTE ================= */

        .skp-note {
            display: flex;
            align-items: center;
            gap: 8px;

            margin-top: 15px;
            padding: 12px 15px;

            background: #f5f8fc !important;
            border-radius: 7px;

            color: #667585 !important;
            font-size: 13px;
        }

        .skp-note i {
            color: #1769aa !important;
            font-size: 16px;
        }

        /* =========================================================
                           MODAL APPROVAL
                        ========================================================= */

        .modal-setuju,
        .modal-tolak,
        .modal-keterangan,
        .modal-backup,
        .modal-reset {
            border: 0;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 18px 55px rgba(15, 23, 42, .18);
        }

        /* ================= MODAL SETUJU ================= */

        .modal-setuju-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;

            background: #f0fff7;
            border-bottom: 1px solid #d6f1e2;

            padding: 18px 20px;
        }

        .modal-setuju-header>div:first-child {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .modal-setuju-header .modal-title {
            color: #146c43;
            font-size: 18px;
            font-weight: 700;
            margin: 0;
        }

        .modal-setuju-header small {
            color: #5f7d6d;
        }

        .modal-icon-success {
            width: 44px;
            height: 44px;
            border-radius: 50%;

            display: flex;
            align-items: center;
            justify-content: center;

            background: #d1e7dd;
            color: #198754;

            font-size: 20px;
            flex-shrink: 0;
        }

        .persetujuan-card {
            display: flex;
            align-items: flex-start;
            gap: 12px;

            padding: 14px 15px;

            border-radius: 9px;

            background: #f8fafc;
            border: 1px solid #e1e7ee;

            color: #526170;
            line-height: 1.55;
        }

        .persetujuan-card i {
            color: #198754;
            font-size: 18px;
        }

        .btn-konfirmasi-setuju {
            min-width: 125px;
            font-weight: 600;
        }

        /* ================= MODAL TOLAK ================= */

        .modal-tolak-header,
        .modal-keterangan-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;

            background: #fff7f7;
            border-bottom: 1px solid #f3d6d9;

            padding: 18px 20px;
        }

        .modal-tolak-header>div:first-child,
        .modal-keterangan-header>div:first-child {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .modal-tolak-header .modal-title,
        .modal-keterangan-header .modal-title {
            color: #2f3742;
            font-size: 16px;
            font-weight: 700;
            margin: 0;
        }

        .modal-tolak-header small,
        .modal-keterangan-header small {
            color: #9a6b70;
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

        .review-warning {
            display: flex;
            gap: 8px;

            margin-bottom: 16px;
            padding: 10px 12px;

            border-radius: 9px;

            background: #fff1f2;
            color: #6b3036;

            font-size: 12px;
            line-height: 1.5;
        }

        .review-warning i {
            color: #dc3545;
        }

        /* ================= MODAL KETERANGAN ================= */

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
        }

        /* =========================================================
                           MODAL DOWNLOAD
                        ========================================================= */

        .modal-backup .modal-header,
        .modal-reset .modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .modal-backup .modal-header>div:first-child,
        .modal-reset .modal-header>div:first-child {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .modal-backup .modal-title,
        .modal-reset .modal-title {
            margin: 0;
            font-weight: 700;
        }

        .modal-backup .modal-header small,
        .modal-reset .modal-header small {
            color: #7c8a9a;
        }

        .modal-icon-backup,
        .modal-icon-reset {
            width: 42px;
            height: 42px;
            min-width: 42px;

            border-radius: 9px;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 19px;
        }

        .modal-icon-backup {
            background: #e8f1ff;
            color: #07549b;
        }

        .modal-icon-reset {
            background: #fff0f1;
            color: #dc3545;
        }

        .backup-info {
            display: flex;
            gap: 12px;

            padding: 14px;

            background: #eef5ff;
            border: 1px solid #d5e5ff;
            border-radius: 8px;

            color: #34516f;
        }

        .backup-info>i {
            color: #07549b;
            font-size: 18px;
        }

        .backup-structure {
            padding: 14px;

            border: 1px solid #e0e5eb;
            border-radius: 8px;

            background: #fafbfc;
        }

        .backup-tree {
            font-size: 13px;
            line-height: 1.9;
            color: #5f6c7b;
        }

        .backup-tree i {
            margin-right: 5px;
            color: #07549b;
        }

        .backup-warning {
            display: flex;
            gap: 9px;

            padding: 11px 13px;

            border-radius: 7px;

            background: #fff8e6;
            color: #765c16;

            font-size: 13px;
        }

        .backup-warning i {
            color: #c58b00;
        }

        /* ================= RESET ================= */

        .reset-warning {
            display: flex;
            gap: 12px;

            padding: 15px;

            border-radius: 8px;

            background: #fff1f2;
            border: 1px solid #ffd4d8;

            color: #6b3036;
        }

        .reset-warning>i {
            color: #dc3545;
            font-size: 20px;
        }

        .reset-safe {
            display: flex;
            gap: 9px;

            padding: 11px 13px;

            border-radius: 7px;

            background: #eaf7f0;
            color: #27623f;

            font-size: 13px;
        }

        .reset-safe i {
            color: #198754;
        }

        /* ================= DARK MODE ================= */

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

        body.theme-dark .pelaporan-stat-card,
        html.theme-dark .pelaporan-stat-card {
            background: #1f1e2e !important;
            border-color: #353448 !important;
        }

        body.theme-dark .pelaporan-stat-card h6,
        html.theme-dark .pelaporan-stat-card h6 {
            color: #929bad !important;
        }

        body.theme-dark .pelaporan-stat-card h4,
        html.theme-dark .pelaporan-stat-card h4 {
            color: #f0f2f7 !important;
        }

        body.theme-dark .skp-main-card,
        html.theme-dark .skp-main-card {
            background: #1f1e2e !important;
            box-shadow: none;
        }

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

        body.theme-dark .nama-pegawai,
        html.theme-dark .nama-pegawai {
            color: #f0f2f7 !important;
        }

        body.theme-dark .dokumen-file-name,
        html.theme-dark .dokumen-file-name {
            color: #d4d8e2;
        }

        body.theme-dark .status-mini.sudah,
        html.theme-dark .status-mini.sudah {
            background: #183c2d;
            color: #6ee7a8;
        }

        body.theme-dark .status-mini.belum,
        html.theme-dark .status-mini.belum {
            background: #4a3d16;
            color: #ffd970;
        }

        body.theme-dark .status-mini.ditolak,
        html.theme-dark .status-mini.ditolak {
            background: #49252b;
            color: #ff9da6;
        }

        body.theme-dark .skp-note,
        html.theme-dark .skp-note {
            background: #242334 !important;
            color: #c3c8d5 !important;
        }

        body.theme-dark .modal-setuju,
        body.theme-dark .modal-tolak,
        body.theme-dark .modal-keterangan,
        body.theme-dark .modal-backup,
        body.theme-dark .modal-reset,
        html.theme-dark .modal-setuju,
        html.theme-dark .modal-tolak,
        html.theme-dark .modal-keterangan,
        html.theme-dark .modal-backup,
        html.theme-dark .modal-reset {
            background: #242334;
            color: #d4d8e2;
        }

        body.theme-dark .modal-setuju-header,
        html.theme-dark .modal-setuju-header {
            background: #193a2b;
            border-color: #2f5a45;
        }

        body.theme-dark .modal-setuju-header .modal-title,
        html.theme-dark .modal-setuju-header .modal-title {
            color: #8be0b1;
        }

        body.theme-dark .modal-tolak-header,
        body.theme-dark .modal-keterangan-header,
        html.theme-dark .modal-tolak-header,
        html.theme-dark .modal-keterangan-header {
            background: #2a2632;
            border-color: #463642;
        }

        body.theme-dark .modal-tolak-header .modal-title,
        body.theme-dark .modal-keterangan-header .modal-title,
        html.theme-dark .modal-tolak-header .modal-title,
        html.theme-dark .modal-keterangan-header .modal-title {
            color: #f0f2f7;
        }

        body.theme-dark .persetujuan-card,
        html.theme-dark .persetujuan-card {
            background: #2b2a3b;
            border-color: #414055;
            color: #d4d8e2;
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

        body.theme-dark .backup-info,
        html.theme-dark .backup-info {
            background: #242f42;
            border-color: #344b6b;
            color: #c6d3e5;
        }

        body.theme-dark .backup-structure,
        html.theme-dark .backup-structure {
            background: #1d1c2b;
            border-color: #3a394e;
        }

        body.theme-dark .backup-tree,
        html.theme-dark .backup-tree {
            color: #b8c0d0;
        }

        body.theme-dark .backup-warning,
        html.theme-dark .backup-warning {
            background: #403719;
            color: #eadb9b;
        }

        body.theme-dark .reset-warning,
        html.theme-dark .reset-warning {
            background: #42272c;
            border-color: #63353c;
            color: #f0c1c6;
        }

        body.theme-dark .reset-safe,
        html.theme-dark .reset-safe {
            background: #19382b;
            color: #a8dfc0;
        }

        /* ================= RESPONSIVE ================= */

        @media (max-width: 768px) {

            .skp-heading {
                flex-direction: column;
            }

            .skp-heading h3 {
                font-size: 22px;
            }

            .skp-main-card .card-body {
                padding: 15px;
            }

            .skp-section-actions {
                width: 100%;
                justify-content: flex-start;
            }

            .skp-section-actions .btn {
                flex: 1;
                min-width: 130px;
            }

            .skp-table {
                min-width: 1100px;
            }
        }
    
        /* =========================================================
           TAMBAHAN KHUSUS MONITORING PELAPORAN
        ========================================================= */
        .dokumen-file-meta {
            color: #8a96a3;
            font-size: 11px;
        }

        body.theme-dark .dokumen-file-meta,
        html.theme-dark .dokumen-file-meta {
            color: #969eb0;
        }
    
    </style>

    <div class="skp-page">

        {{-- =====================================================
         HEADER
    ====================================================== --}}
        <div class="skp-heading">

            <div>
                <h3>Monitoring Pelaporan</h3>
                <p>Monitoring laporan Excel yang sudah diupload pegawai</p>
            </div>

            <div class="periode-badge">
                Periode: {{ $periodeBadge }}
            </div>

        </div>


        {{-- =====================================================
         FILTER
    ====================================================== --}}
        <div class="card skp-filter-card">

            <div class="card-body">

                <div class="skp-filter-title">
                    <i class="bi bi-funnel"></i>
                    Filter Monitoring Pelaporan
                </div>

                <form method="GET" action="{{ route('admin.monitoring_pelaporan.index') }}" id="formFilter">

                    <input type="hidden" name="jenis" id="inputJenis" value="{{ $jenisAktif }}">

                    <div class="monitoring-type-wrapper">

                        <label class="form-label">Jenis Pelaporan</label>

                        <div class="monitoring-type-buttons">

                            <button type="button"
                                class="monitoring-type-btn {{ $jenisAktif === 'mingguan' ? 'active' : '' }}"
                                data-type="mingguan">
                                <i class="bi bi-calendar-week"></i>
                                <span>
                                    <strong>Mingguan</strong>
                                    <small>Pelaporan setiap minggu</small>
                                </span>
                            </button>

                            <button type="button"
                                class="monitoring-type-btn {{ $jenisAktif === 'bulanan' ? 'active' : '' }}"
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

                        <div class="col-md-3">
                            <label class="form-label">Tahun</label>

                            <select class="form-select" name="tahun">
                                @foreach ($tahunOptions as $itemTahun)
                                    <option value="{{ $itemTahun }}" {{ $itemTahun == $tahun ? 'selected' : '' }}>
                                        {{ $itemTahun }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Bulan</label>

                            <select class="form-select" name="bulan">
                                @if ($jenisAktif === 'bulanan')
                                    <option value="semua" {{ $bulan === 0 ? 'selected' : '' }}>
                                        Semua Bulan
                                    </option>
                                @endif

                                @foreach ($namaBulan as $noBulan => $namaBulanItem)
                                    <option value="{{ $noBulan }}" {{ $noBulan == $bulan ? 'selected' : '' }}>
                                        {{ $namaBulanItem }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3" id="wrapperMinggu"
                            style="display: {{ $jenisAktif === 'mingguan' ? 'block' : 'none' }};">
                            <label class="form-label">Minggu</label>

                            <select class="form-select" name="minggu" id="filterMinggu">
                                <option value="semua" {{ $mingguDipilih === 'semua' ? 'selected' : '' }}>
                                    Semua Minggu
                                </option>

                                @foreach ($mingguDalamBulan as $noMinggu => $rentang)
                                    <option value="{{ $noMinggu }}"
                                        {{ (string) $noMinggu === (string) $mingguDipilih ? 'selected' : '' }}>
                                        Minggu {{ $noMinggu }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary w-100 skp-filter-btn">
                                <i class="bi bi-search"></i>
                                Tampilkan Data
                            </button>
                        </div>

                    </div>

                </form>

            </div>

        </div>


        {{-- =====================================================
         STATISTIK
    ====================================================== --}}
        <div class="row">

            <div class="col-md-4">
                <div class="card pelaporan-stat-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="pelaporan-stat-icon total">
                                <i class="bi bi-people-fill"></i>
                            </div>
                            <div>
                                <h6>Total Pegawai</h6>
                                <h4 class="mb-0">{{ $totalPegawai }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card pelaporan-stat-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="pelaporan-stat-icon uploaded">
                                <i class="bi bi-check-circle-fill"></i>
                            </div>
                            <div>
                                <h6>Sudah Upload ({{ $labelRingkasan }})</h6>
                                <h4 class="mb-0">{{ $sudahUpload }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card pelaporan-stat-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="pelaporan-stat-icon pending">
                                <i class="bi bi-clock-fill"></i>
                            </div>
                            <div>
                                <h6>Belum Upload ({{ $labelRingkasan }})</h6>
                                <h4 class="mb-0">{{ $belumUpload }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>


        {{-- =====================================================
         MAIN TABLE
    ====================================================== --}}
        <div class="card skp-main-card">

            <div class="card-body">

                <div class="skp-table-wrapper">

                    <div class="skp-section-header">

                        <div>
                            <h5>
                                <i class="bi bi-file-earmark-excel"></i>
                                @if ($jenisAktif === 'mingguan')
                                    Monitoring Pelaporan Mingguan
                                @else
                                    Monitoring Pelaporan Bulanan
                                @endif
                            </h5>

                            <small>{{ $labelPeriode }}</small>
                        </div>

                        <div class="skp-section-actions">

                            @if ($jumlahDataReset > 0)
                                <button type="button" class="btn btn-light btn-sm" id="btnDownloadAll"
                                    data-bs-toggle="modal" data-bs-target="#modalDownloadAll">
                                    <i class="bi bi-download"></i>
                                    Download All
                                </button>
                            @else
                                <button type="button" class="btn btn-light btn-sm" id="btnDownloadAll" disabled
                                    title="Belum ada file untuk periode ini">
                                    <i class="bi bi-download"></i>
                                    Download All
                                </button>
                            @endif

                            @if ($backupSudahDilakukan)
                                <button type="button" class="btn btn-danger btn-sm" id="btnResetPelaporan">
                                    <i class="bi bi-arrow-counterclockwise"></i>
                                    Reset
                                </button>
                            @else
                                <button type="button" class="btn btn-secondary btn-sm" id="btnResetPelaporan" disabled
                                    title="{{ $jumlahDataReset > 0 ? 'Download All terlebih dahulu' : 'Belum ada data untuk di-reset' }}">
                                    <i class="bi bi-lock-fill"></i>
                                    Reset
                                </button>
                            @endif

                        </div>

                    </div>


                    <div class="table-responsive">

                        <table class="table skp-table mb-0">

                            <thead>
                                <tr>
                                    <th style="width:50px;">No</th>
                                    <th style="min-width:200px;">Nama Pegawai</th>

                                    @if ($jenisAktif === 'mingguan')
                                        @foreach ($mingguTampil as $noMinggu)
                                            <th class="text-center periode-column" style="min-width:230px;">
                                                Minggu {{ $noMinggu }}
                                                <small class="d-block">
                                                    {{ $mingguDalamBulan[$noMinggu]['mulai']->format('j') }}
                                                    -
                                                    {{ $mingguDalamBulan[$noMinggu]['selesai']->format('j') }}
                                                    {{ \Illuminate\Support\Str::substr($namaBulan[$bulan], 0, 3) }}
                                                </small>
                                            </th>
                                        @endforeach
                                    @else
                                        @foreach ($bulanTampil as $noBulan)
                                            <th class="text-center periode-column" style="min-width:230px;">
                                                {{ $namaBulan[$noBulan] }}
                                                <small class="d-block">{{ $tahun }}</small>
                                            </th>
                                        @endforeach
                                    @endif

                                    <th class="text-center" style="min-width:190px;">Keterangan</th>
                                </tr>
                            </thead>

                            <tbody>

                                {{-- ======================= MINGGUAN ======================= --}}
                                @if ($jenisAktif === 'mingguan')

                                    @forelse ($dataMingguan as $index => $item)
                                        @php
                                            $jumlahTarget = count($mingguTampil);
                                            $jumlahSudah = collect($mingguTampil)
                                                ->filter(fn($no) => !empty($item['status'][$no]))
                                                ->count();
                                        @endphp

                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>

                                            <td class="nama-pegawai">{{ $item['pegawai']->nama }}</td>

                                            @foreach ($mingguTampil as $noMinggu)
                                                @php $lap = $item['status'][$noMinggu] ?? null; @endphp

                                                <td class="text-center">
                                                    <div class="dokumen-review-status">

                                                        @if ($lap)
                                                            <div class="dokumen-file-name">
                                                                <i class="bi bi-file-earmark-excel text-success"></i>
                                                                <span title="{{ $lap->nama_file }}">
                                                                    {{ $lap->nama_file }}
                                                                </span>
                                                            </div>

                                                            <a href="{{ $lap->url_file }}" target="_blank"
                                                                class="status-mini sudah">
                                                                <i class="bi bi-download"></i>
                                                                Unduh File
                                                            </a>

                                                            <small class="dokumen-file-meta">
                                                                {{ $lap->updated_at->copy()->timezone('Asia/Jakarta')->format('d-m-Y H:i') }}
                                                                WIB
                                                            </small>
                                                        @else
                                                            <span class="status-mini belum">
                                                                <i class="bi bi-clock"></i>
                                                                Belum Upload
                                                            </span>
                                                        @endif

                                                    </div>
                                                </td>
                                            @endforeach

                                            <td class="text-center">
                                                <div class="keterangan-status">

                                                    @if ($jumlahSudah === $jumlahTarget && $jumlahTarget > 0)
                                                        <span class="badge bg-success">
                                                            <i class="bi bi-check-circle"></i> Sudah Lengkap
                                                        </span>
                                                    @elseif ($jumlahSudah > 0)
                                                        <span class="badge bg-warning text-dark">
                                                            <i class="bi bi-clock"></i> Sebagian
                                                        </span>
                                                    @else
                                                        <span class="badge bg-secondary">Belum Upload</span>
                                                    @endif

                                                    <small class="text-muted">
                                                        {{ $jumlahSudah }} / {{ $jumlahTarget }} minggu
                                                    </small>

                                                </div>
                                            </td>
                                        </tr>

                                    @empty
                                        <tr>
                                            <td colspan="{{ 3 + count($mingguTampil) }}"
                                                class="text-center text-muted py-4">
                                                <i class="bi bi-inbox fs-4 d-block mb-2"></i>
                                                Belum ada data pegawai.
                                            </td>
                                        </tr>
                                    @endforelse

                                {{-- ======================= BULANAN ======================= --}}
                                @else

                                    @forelse ($dataBulanan as $index => $item)
                                        @php
                                            $jumlahTarget = count($bulanTampil);
                                            $jumlahSudah = collect($bulanTampil)
                                                ->filter(fn($no) => !empty($item['status'][$no]))
                                                ->count();
                                        @endphp

                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>

                                            <td class="nama-pegawai">{{ $item['pegawai']->nama }}</td>

                                            @foreach ($bulanTampil as $noBulan)
                                                @php $lap = $item['status'][$noBulan] ?? null; @endphp

                                                <td class="text-center">
                                                    <div class="dokumen-review-status">

                                                        @if ($lap)
                                                            <div class="dokumen-file-name">
                                                                <i class="bi bi-file-earmark-excel text-success"></i>
                                                                <span title="{{ $lap->nama_file }}">
                                                                    {{ $lap->nama_file }}
                                                                </span>
                                                            </div>

                                                            <a href="{{ $lap->url_file }}" target="_blank"
                                                                class="status-mini sudah">
                                                                <i class="bi bi-download"></i>
                                                                Unduh File
                                                            </a>

                                                            <small class="dokumen-file-meta">
                                                                {{ $lap->updated_at->copy()->timezone('Asia/Jakarta')->format('d-m-Y H:i') }}
                                                                WIB
                                                            </small>
                                                        @else
                                                            <span class="status-mini belum">
                                                                <i class="bi bi-clock"></i>
                                                                Belum Upload
                                                            </span>
                                                        @endif

                                                    </div>
                                                </td>
                                            @endforeach

                                            <td class="text-center">
                                                <div class="keterangan-status">

                                                    @if ($jumlahTarget === 1)
                                                        @if ($jumlahSudah === 1)
                                                            <span class="badge bg-success">
                                                                <i class="bi bi-check-circle"></i> Sudah Upload
                                                            </span>
                                                        @else
                                                            <span class="badge bg-secondary">Belum Upload</span>
                                                        @endif
                                                    @else
                                                        @if ($jumlahSudah === $jumlahTarget)
                                                            <span class="badge bg-success">
                                                                <i class="bi bi-check-circle"></i> Sudah Lengkap
                                                            </span>
                                                        @elseif ($jumlahSudah > 0)
                                                            <span class="badge bg-warning text-dark">
                                                                <i class="bi bi-clock"></i> Sebagian
                                                            </span>
                                                        @else
                                                            <span class="badge bg-secondary">Belum Upload</span>
                                                        @endif

                                                        <small class="text-muted">
                                                            {{ $jumlahSudah }} / {{ $jumlahTarget }} bulan
                                                        </small>
                                                    @endif

                                                </div>
                                            </td>
                                        </tr>

                                    @empty
                                        <tr>
                                            <td colspan="{{ 3 + count($bulanTampil) }}"
                                                class="text-center text-muted py-4">
                                                <i class="bi bi-inbox fs-4 d-block mb-2"></i>
                                                Belum ada data pegawai.
                                            </td>
                                        </tr>
                                    @endforelse

                                @endif

                            </tbody>

                        </table>

                    </div>

                </div>


                <div class="skp-note">
                    <i class="bi bi-info-circle"></i>
                    <span>
                        @if ($jenisAktif === 'mingguan')
                            Pilih <strong>Semua Minggu</strong> untuk melihat seluruh minggu pada bulan ini, atau
                            satu minggu saja. Download All dan Reset mengikuti pilihan minggu yang sedang tampil.
                        @else
                            Pilih <strong>Semua Bulan</strong> untuk melihat Januari - Desember, atau satu bulan saja.
                            Download All dan Reset mengikuti pilihan bulan yang sedang tampil.
                        @endif
                    </span>
                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
     MODAL DOWNLOAD ALL
========================================================= --}}
    @if ($jumlahDataReset > 0)
        <div class="modal fade" id="modalDownloadAll" tabindex="-1" aria-hidden="true">

            <div class="modal-dialog modal-dialog-centered">

                <div class="modal-content modal-backup">

                    <div class="modal-header">

                        <div>
                            <div class="modal-icon-backup">
                                <i class="bi bi-cloud-arrow-down-fill"></i>
                            </div>

                            <div>
                                <h5 class="modal-title">Download Semua File</h5>
                                <small>Backup Pelaporan {{ $jenisDb }} - {{ $labelPeriode }}</small>
                            </div>
                        </div>

                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                    </div>

                    <div class="modal-body">

                        <div class="backup-info">
                            <i class="bi bi-info-circle-fill"></i>
                            <div>
                                <strong>Backup seluruh laporan?</strong>
                                <p class="mb-0">
                                    {{ $jumlahDataReset }} file laporan {{ $jenisDb }}
                                    ({{ $labelPeriode }}) akan dikumpulkan ke dalam satu file ZIP.
                                </p>
                            </div>
                        </div>

                        <div class="backup-structure mt-3">

                            <div class="fw-semibold mb-2">Struktur file:</div>

                            <div class="backup-tree">

                                <div>
                                    <i class="bi bi-file-earmark-zip"></i>
                                    {{ $namaZip }}
                                </div>

                                <div class="ms-3">
                                    <i class="bi bi-folder"></i>
                                    Nama Pegawai/
                                </div>

                                <div class="ms-5">
                                    <i class="bi bi-file-earmark-excel"></i>
                                    {{ $jenisAktif === 'mingguan' ? 'Minggu-1' : 'Bulan-' . str_pad((string) ($bulan ?: 1), 2, '0', STR_PAD_LEFT) }}_file.xlsx
                                </div>

                            </div>

                        </div>

                        <div class="backup-warning mt-3">
                            <i class="bi bi-shield-check"></i>
                            <span>
                                Setelah backup berhasil,
                                tombol <strong>Reset</strong>
                                akan dibuka.
                            </span>
                        </div>

                    </div>

                    <div class="modal-footer">

                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                            Batal
                        </button>

                        <button type="button" class="btn btn-primary" id="btnKonfirmasiDownloadAll">
                            <i class="bi bi-download"></i>
                            Download All
                        </button>

                    </div>

                </div>

            </div>

        </div>


        {{-- =========================================================
     MODAL RESET
========================================================= --}}
        <div class="modal fade" id="modalResetPelaporan" tabindex="-1" aria-hidden="true">

            <div class="modal-dialog modal-dialog-centered">

                <div class="modal-content modal-reset">

                    <div class="modal-header">

                        <div>
                            <div class="modal-icon-reset">
                                <i class="bi bi-arrow-counterclockwise"></i>
                            </div>

                            <div>
                                <h5 class="modal-title">Reset Data Pelaporan</h5>
                                <small>{{ $jenisDb }} - {{ $labelPeriode }}</small>
                            </div>
                        </div>

                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                    </div>

                    <div class="modal-body">

                        <div class="reset-warning">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            <div>
                                <strong>Perhatian!</strong>
                                <p class="mb-0 mt-1">
                                    {{ $jumlahDataReset }} data dan file laporan {{ $jenisDb }}
                                    ({{ $labelPeriode }}) akan dihapus permanen dari server.
                                </p>
                            </div>
                        </div>

                        <div class="reset-safe mt-3">
                            <i class="bi bi-shield-check"></i>
                            <span>
                                Backup sudah dilakukan.
                                Data pegawai dan laporan pada periode lain
                                tidak akan ikut dihapus.
                            </span>
                        </div>

                        <p class="mt-3 mb-0">
                            Apakah Anda yakin ingin melakukan reset?
                        </p>

                    </div>

                    <div class="modal-footer">

                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                            Batal
                        </button>

                        <button type="button" class="btn btn-danger" id="btnKonfirmasiReset">
                            <i class="bi bi-trash"></i>
                            Ya, Reset Data
                        </button>

                    </div>

                </div>

            </div>

        </div>
    @endif


    {{-- =========================================================
     SWEETALERT2 + JAVASCRIPT
========================================================= --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const csrfToken = '{{ csrf_token() }}';
            const urlDownloadAll = @json(route('monitoring-pelaporan.download-semua'));
            const urlReset = @json(route('monitoring-pelaporan.reset'));
            const paramFilter = @json($paramFilter);
            const namaZipDefault = @json($namaZip);

            const form = document.getElementById('formFilter');
            const inputJenis = document.getElementById('inputJenis');
            const typeButtons = document.querySelectorAll('.monitoring-type-btn');
            const filterMinggu = document.getElementById('filterMinggu');

            /* =========================================================
               GANTI JENIS -> kirim ulang filter (data dirender server)
            ========================================================= */
            typeButtons.forEach(button => {
                button.addEventListener('click', function() {

                    if (this.classList.contains('active')) {
                        return;
                    }

                    const jenisBaru = this.dataset.type;

                    const params = new URLSearchParams(new FormData(form));

                    params.set('jenis', jenisBaru);

                    if (jenisBaru === 'bulanan') {

                        // Jenis Bulanan: default tampilkan Semua Bulan
                        params.set('bulan', 'semua');
                        params.delete('minggu');
                    } else {

                        // Jenis Mingguan butuh bulan spesifik
                        if (params.get('bulan') === 'semua') {
                            params.delete('bulan');
                        }

                        params.set('minggu', 'semua');
                    }

                    window.location.href = form.getAttribute('action') + '?' + params.toString();
                });
            });

            /* =========================================================
               KONFIRMASI DOWNLOAD ALL
               Setelah ZIP berhasil diterima, halaman di-reload sehingga
               tombol Reset terbuka (status backup dibaca dari server).
            ========================================================= */
            const btnKonfirmasiDownload = document.getElementById('btnKonfirmasiDownloadAll');

            if (btnKonfirmasiDownload) {
                btnKonfirmasiDownload.addEventListener('click', async function() {

                    const btn = this;
                    const htmlAsli = btn.innerHTML;

                    btn.disabled = true;
                    btn.innerHTML = '<i class="bi bi-arrow-repeat"></i> Menyiapkan ZIP...';

                    try {

                        const query = new URLSearchParams(paramFilter).toString();

                        const response = await fetch(urlDownloadAll + '?' + query, {
                            headers: {
                                'Accept': 'application/json'
                            }
                        });

                        const tipe = response.headers.get('Content-Type') || '';

                        if (!response.ok || !tipe.includes('zip')) {

                            let pesan = 'Download gagal dilakukan.';

                            try {
                                const hasil = await response.json();
                                pesan = hasil.message || pesan;
                            } catch (e) {}

                            throw new Error(pesan);
                        }

                        const blob = await response.blob();

                        const disposition = response.headers.get('Content-Disposition') || '';
                        const cocok = disposition.match(/filename\*?=(?:UTF-8\'\')?"?([^";]+)"?/i);
                        const namaFile = cocok ? decodeURIComponent(cocok[1]) : namaZipDefault;

                        const urlBlob = URL.createObjectURL(blob);
                        const tautan = document.createElement('a');

                        tautan.href = urlBlob;
                        tautan.download = namaFile;
                        document.body.appendChild(tautan);
                        tautan.click();
                        tautan.remove();

                        setTimeout(() => URL.revokeObjectURL(urlBlob), 10000);

                        bootstrap.Modal
                            .getOrCreateInstance(document.getElementById('modalDownloadAll'))
                            .hide();

                        Swal.fire({
                            icon: 'success',
                            title: 'Backup Berhasil!',
                            text: 'Seluruh file laporan berhasil dikemas ke dalam file ZIP.',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#07549b',
                            timer: 3000,
                            timerProgressBar: true
                        }).then(() => {
                            window.location.reload();
                        });

                    } catch (error) {

                        Swal.fire({
                            icon: 'error',
                            title: 'Download Gagal!',
                            text: error.message || 'Terjadi kesalahan saat membuat file ZIP.',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#dc3545'
                        });

                        btn.disabled = false;
                        btn.innerHTML = htmlAsli;
                    }
                });
            }

            /* =========================================================
               RESET (hanya aktif kalau backup sudah dilakukan)
            ========================================================= */
            const btnReset = document.getElementById('btnResetPelaporan');

            if (btnReset && !btnReset.disabled) {
                btnReset.addEventListener('click', function() {
                    bootstrap.Modal
                        .getOrCreateInstance(document.getElementById('modalResetPelaporan'))
                        .show();
                });
            }

            /* =========================================================
               KONFIRMASI RESET
            ========================================================= */
            const btnKonfirmasiReset = document.getElementById('btnKonfirmasiReset');

            if (btnKonfirmasiReset) {
                btnKonfirmasiReset.addEventListener('click', function() {

                    const btn = this;

                    btn.disabled = true;
                    btn.innerHTML = '<i class="bi bi-arrow-repeat"></i> Mereset...';

                    fetch(urlReset, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(paramFilter)
                        })
                        .then(async response => {

                            let hasil = {};

                            try {
                                hasil = await response.json();
                            } catch (error) {
                                hasil = {};
                            }

                            if (!response.ok) {
                                throw new Error(hasil.message || 'Reset gagal dilakukan.');
                            }

                            return hasil;
                        })
                        .then(function(hasil) {

                            Swal.fire({
                                icon: 'success',
                                title: 'Reset Berhasil!',
                                text: hasil.message || 'Data pelaporan berhasil direset.',
                                confirmButtonText: 'OK',
                                confirmButtonColor: '#198754',
                                timer: 3000,
                                timerProgressBar: true
                            }).then(() => {
                                window.location.reload();
                            });
                        })
                        .catch(function(error) {

                            Swal.fire({
                                icon: 'error',
                                title: 'Reset Gagal!',
                                text: error.message || 'Terjadi kesalahan saat melakukan reset.',
                                confirmButtonText: 'OK',
                                confirmButtonColor: '#dc3545'
                            });

                            btn.disabled = false;
                            btn.innerHTML = '<i class="bi bi-trash"></i> Ya, Reset Data';
                        });
                });
            }
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