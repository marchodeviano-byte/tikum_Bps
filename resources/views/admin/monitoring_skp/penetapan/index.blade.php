@extends('layouts.app')

@section('content')

<style>
        /* =====================================================
                           MONITORING SKP PAGE (disamakan dengan desain Pelaporan)
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


        /* ================= RINGKASAN (STAT CARD) ================= */

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


        /* ================= BLUE HEADER ================= */

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

        .skp-section-info {
            display: flex;
            align-items: center;
            gap: 7px;
            color: #e7f1ff !important;
            font-size: 13px;
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

        .skp-table thead th small {
            display: block;
            font-weight: 500;
            color: #7c8a9a !important;
        }

        .skp-table tbody td {
            background: #ffffff !important;
            color: #29313a !important;
        }

        .skp-table .nama-pegawai {
            font-weight: 700;
            color: #1d2733 !important;
        }


        /* ================= ROLE ================= */

        .role-badge {
            display: inline-block;
            padding: 5px 10px;
            background: #eef1f4 !important;
            color: #344454 !important;
            border-radius: 5px;
            font-size: 13px;
        }


        /* ================= STATUS (BADGE PANJANG) ================= */

        .status-belum {
            display: inline-flex;
            align-items: center;
            gap: 5px;

            background: #fff3cd;
            color: #856404;

            padding: 6px 12px;
            border-radius: 20px;

            font-size: 12px;
            font-weight: 600;
            white-space: nowrap;
        }

        .status-sudah {
            display: inline-flex;
            align-items: center;
            gap: 5px;

            background: #d1e7dd;
            color: #0f5132;

            padding: 6px 12px;
            border-radius: 20px;

            font-size: 12px;
            font-weight: 600;
            white-space: nowrap;
        }


        /* ================= STATUS (DOT, UNTUK TABEL BANYAK KOLOM) ================= */

        .status-mini {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            font-size: 15px;
        }

        a.status-mini.sudah {
            cursor: pointer;
            text-decoration: none;
            transition: transform 0.1s ease;
        }

        a.status-mini.sudah:hover {
            transform: scale(1.1);
            filter: brightness(0.95);
        }

        .status-mini.sudah {
            background: #d1e7dd;
            color: #198754;
        }

        .status-mini.belum {
            background: #fff3cd;
            color: #b76d00;
        }


        /* ================= FILE ================= */

        .file-wrapper {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 3px;
        }

        .file-type {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            font-size: 13px;
            font-weight: 500;
        }

        .file-type.pdf {
            color: #dc3545 !important;
        }

        .file-type.pdf i {
            font-size: 19px;
        }

        .file-empty {
            color: #8995a1 !important;
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

        body.theme-dark .skp-section-header,
        html.theme-dark .skp-section-header {
            background: #075a9f !important;
            color: #ffffff !important;
        }

        body.theme-dark .skp-section-header h5,
        html.theme-dark .skp-section-header h5,
        body.theme-dark .skp-section-header small,
        html.theme-dark .skp-section-header small,
        body.theme-dark .skp-section-info,
        html.theme-dark .skp-section-info {
            color: #ffffff !important;
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

        body.theme-dark .skp-table thead th small,
        html.theme-dark .skp-table thead th small {
            color: #929bad !important;
        }

        body.theme-dark .skp-table tbody td,
        html.theme-dark .skp-table tbody td {
            background: #1c1b2b !important;
            color: #d4d8e2 !important;
        }

        body.theme-dark .skp-table .nama-pegawai,
        html.theme-dark .skp-table .nama-pegawai {
            color: #f0f2f7 !important;
        }

        body.theme-dark .role-badge,
        html.theme-dark .role-badge {
            background: #343746 !important;
            color: #d7dce5 !important;
        }

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

        body.theme-dark .status-mini.sudah,
        html.theme-dark .status-mini.sudah {
            background: #183c2d !important;
            color: #6ee7a8 !important;
        }

        body.theme-dark .status-mini.belum,
        html.theme-dark .status-mini.belum {
            background: #4a3d16 !important;
            color: #ffd970 !important;
        }

        body.theme-dark .file-empty,
        html.theme-dark .file-empty {
            color: #858da0 !important;
        }

        body.theme-dark .skp-note,
        html.theme-dark .skp-note {
            background: #242334 !important;
            color: #c3c8d5 !important;
        }


        /* ================= BACKUP & RESET ================= */

        .monitoring-backup-actions {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
            margin-bottom: 18px;
        }

        .monitoring-backup-actions .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            min-height: 38px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
        }

        .btn-reset-locked {
            cursor: not-allowed !important;
            opacity: .65;
        }

        .modal-backup,
        .modal-reset {
            border: none;
            border-radius: 12px;
            overflow: hidden;
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

        .modal-icon-backup { background: #e8f1ff; color: #07549b; }
        .modal-icon-reset { background: #fff0f1; color: #dc3545; }

        .backup-info,
        .reset-warning,
        .backup-warning,
        .reset-safe {
            display: flex;
            gap: 10px;
            padding: 13px 14px;
            border-radius: 8px;
        }

        .backup-info { background:#eef5ff; border:1px solid #d5e5ff; color:#34516f; }
        .backup-warning { background:#fff8e6; color:#765c16; font-size:13px; }
        .reset-warning { background:#fff1f2; border:1px solid #ffd4d8; color:#6b3036; }
        .reset-safe { background:#eaf7f0; color:#276044; }

        .backup-structure {
            background:#f7f9fc;
            border:1px solid #e0e6ed;
            padding:13px 14px;
            border-radius:8px;
        }

        .backup-tree {
            font-size:13px;
            line-height:1.8;
            color:#586777;
        }

        body.theme-dark .monitoring-backup-actions .btn-reset-locked,
        html.theme-dark .monitoring-backup-actions .btn-reset-locked {
            background:#343746 !important;
            border-color:#4a4c5d !important;
            color:#aeb5c5 !important;
        }

        body.theme-dark .modal-backup,
        html.theme-dark .modal-backup,
        body.theme-dark .modal-reset,
        html.theme-dark .modal-reset {
            background:#252436;
            color:#d4d8e2;
        }

        body.theme-dark .modal-backup .modal-header,
        html.theme-dark .modal-backup .modal-header,
        body.theme-dark .modal-reset .modal-header,
        html.theme-dark .modal-reset .modal-header {
            border-bottom-color:#3a394e;
        }

        body.theme-dark .modal-backup .modal-footer,
        html.theme-dark .modal-backup .modal-footer,
        body.theme-dark .modal-reset .modal-footer,
        html.theme-dark .modal-reset .modal-footer {
            border-top-color:#3a394e;
        }

        body.theme-dark .backup-info,
        html.theme-dark .backup-info { background:#242f42; border-color:#344b6b; color:#c6d3e5; }
        body.theme-dark .backup-structure,
        html.theme-dark .backup-structure { background:#1d1c2b; border-color:#3a394e; }
        body.theme-dark .backup-tree,
        html.theme-dark .backup-tree { color:#b8c0d0; }
        body.theme-dark .backup-warning,
        html.theme-dark .backup-warning { background:#403719; color:#eadb9b; }
        body.theme-dark .reset-warning,
        html.theme-dark .reset-warning { background:#42272c; border-color:#63353c; color:#f0c1c6; }
        body.theme-dark .reset-safe,
        html.theme-dark .reset-safe { background:#19382b; color:#a8dfc0; }

        /* ================= APPROVAL ================= */

        .approval-status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            white-space: nowrap;
        }

        .approval-status.menunggu { background:#fff3cd; color:#856404; }
        .approval-status.disetujui { background:#d1e7dd; color:#0f5132; }
        .approval-status.ditolak { background:#f8d7da; color:#842029; }

        .approval-reason-mini {
            display:block;
            margin-top:7px;
            color:#842029 !important;
            font-size:11px;
        }

        .approval-actions {
            display:flex;
            justify-content:center;
            gap:6px;
            flex-wrap:wrap;
        }

        .modal-approval,
        .modal-rejection {
            border:none;
            border-radius:12px;
            overflow:hidden;
        }

        .modal-approval-header,
        .modal-rejection-header {
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:12px;
            padding:16px 18px;
            border-bottom:1px solid #e7ebf0;
        }

        .modal-approval-icon,
        .modal-rejection-icon {
            width:42px;
            height:42px;
            border-radius:10px;
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:19px;
        }

        .modal-approval-icon { background:#e7f6ee; color:#198754; }
        .modal-rejection-icon { background:#fff0f1; color:#dc3545; }

        .modal-approval-title,
        .modal-rejection-title {
            margin:0;
            font-size:16px;
            font-weight:700;
            color:#263646;
        }

        .modal-approval-subtitle,
        .modal-rejection-subtitle {
            display:block;
            margin-top:2px;
            font-size:12px;
            color:#8a96a3;
        }

        .approval-confirm-box,
        .rejection-box {
            padding:13px 14px;
            border-radius:9px;
        }

        .approval-confirm-box { background:#eef8f3; color:#276044; }
        .rejection-box { background:#fff8f8; border:1px solid #f2d4d7; }

        .rejection-box textarea {
            min-height:130px;
            resize:vertical;
        }

        .rejection-counter {
            text-align:right;
            margin-top:5px;
            color:#8a96a3;
            font-size:11px;
        }

        body.theme-dark .approval-status.menunggu,
        html.theme-dark .approval-status.menunggu { background:#4a3d16; color:#ffd970; }
        body.theme-dark .approval-status.disetujui,
        html.theme-dark .approval-status.disetujui { background:#183c2d; color:#6ee7a8; }
        body.theme-dark .approval-status.ditolak,
        html.theme-dark .approval-status.ditolak { background:#49252b; color:#ff9da6; }
        body.theme-dark .approval-reason-mini,
        html.theme-dark .approval-reason-mini { color:#ff9da6 !important; }
        body.theme-dark .modal-approval,
        body.theme-dark .modal-rejection,
        html.theme-dark .modal-approval,
        html.theme-dark .modal-rejection { background:#252436; color:#d4d8e2; }
        body.theme-dark .modal-approval-header,
        body.theme-dark .modal-rejection-header,
        html.theme-dark .modal-approval-header,
        html.theme-dark .modal-rejection-header { border-bottom-color:#3a394e; }
        body.theme-dark .modal-approval-title,
        body.theme-dark .modal-rejection-title,
        html.theme-dark .modal-approval-title,
        html.theme-dark .modal-rejection-title { color:#f0f2f7; }
        body.theme-dark .modal-approval-subtitle,
        body.theme-dark .modal-rejection-subtitle,
        html.theme-dark .modal-approval-subtitle,
        html.theme-dark .modal-rejection-subtitle { color:#929bad; }
        body.theme-dark .approval-confirm-box,
        html.theme-dark .approval-confirm-box { background:#19382b; color:#a8dfc0; }
        body.theme-dark .rejection-box,
        html.theme-dark .rejection-box { background:#2b2530; border-color:#4b3740; }

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

            .skp-section-info {
                display: none;
            }

            .skp-table {
                min-width: 750px;
            }

            #skp-table-tahunan {
                min-width: 1150px;
            }
        }
    </style>

    <div class="skp-page">

        <div class="skp-heading">
            <div>
                <h3>Monitoring SKP</h3>
                <p>Monitoring pengumpulan SKP pegawai</p>
            </div>
            <div class="periode-badge" id="periodeBadge">
                Periode: Tahun {{ $tahun }}
            </div>
        </div>

        <div class="card skp-filter-card">
            <div class="card-body">
                <div class="skp-filter-title">
                    <i class="bi bi-funnel"></i>
                    Filter Monitoring SKP Penetapan
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

                    <div class="col-md-4">
                        <div class="form-control monitoring-readonly">
                            <i class="bi bi-calendar-check"></i>
                            <span>Satu Tahun</span>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <button type="button"
                            class="btn btn-primary w-100 skp-filter-btn"
                            id="btnTampilkan">
                            <i class="bi bi-search"></i>
                            Tampilkan Data
                        </button>
                    </div>

                </div>
            </div>
        </div>

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
                                <h6>Sudah Upload</h6>
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
                                <h6>Belum Upload</h6>
                                <h4 class="mb-0">{{ $belumUpload }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="card skp-main-card">

            <div class="card-body">

                <div class="skp-table-wrapper">

                    <div class="skp-section-header">

                        <div>
                            <h5>
                                <i class="bi bi-clipboard-check"></i>
                                Monitoring SKP Penetapan Tahunan
                            </h5>

                            <small>
                                Tahun {{ $tahun }}
                            </small>
                        </div>

                        <div class="skp-section-actions">

                            <button type="button"
                                class="btn btn-light btn-sm"
                                id="btnDownloadAll">
                                <i class="bi bi-download"></i>
                                Download All
                            </button>

                            @if ($backupSudahDilakukan)
                                <button type="button"
                                    class="btn btn-danger btn-sm"
                                    id="btnResetPenetapan">
                                    <i class="bi bi-arrow-counterclockwise"></i>
                                    Reset
                                </button>
                            @else
                                <button type="button"
                                    class="btn btn-secondary btn-sm"
                                    id="btnResetPenetapan"
                                    disabled
                                    title="Download All terlebih dahulu">
                                    <i class="bi bi-lock-fill"></i>
                                    Reset
                                </button>
                            @endif

                        </div>

                    </div>

                    <div class="table-responsive">

                        <table class="table skp-table mb-0"
                            id="skp-table-tahunan">

                            <thead>
                                <tr>
                                    <th style="width:50px;">No</th>
                                    <th style="min-width:220px;">Nama Pegawai</th>
                                    <th style="min-width:250px;">Status SKP</th>
                                    <th style="min-width:320px;">File SKP Penetapan Tahunan</th>
                                    <th style="min-width:180px;">Aksi</th>
                                </tr>
                            </thead>

                            <tbody>

                                @forelse ($dataTahunan as $index => $item)

                                    @php
                                        $skp = $item['skp'];
                                    @endphp

                                    <tr>

                                        <td class="text-center">
                                            {{ $index + 1 }}
                                        </td>

                                        <td class="nama-pegawai">
                                            {{ $item['pegawai']->nama }}
                                        </td>

                                        <td class="text-center">

                                            @if ($skp)
                                                @php
                                                    $statusPenetapan = $skp->status ?? 'menunggu';
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
                                                    @if ($skp->keterangan)
                                                        <span class="approval-reason-mini">Alasan tersedia</span>
                                                    @endif
                                                @else
                                                    <span class="approval-status menunggu">
                                                        <i class="bi bi-clock-fill"></i>
                                                        Menunggu Persetujuan
                                                    </span>
                                                @endif
                                            @else
                                                <span class="status-belum">
                                                    <i class="bi bi-clock"></i>
                                                    Belum Upload
                                                </span>
                                            @endif

                                        </td>

                                        <td>

                                            @if ($skp && $skp->path_file)

                                                <div class="file-wrapper">

                                                    <a href="{{ $skp->url_file }}"
                                                        target="_blank"
                                                        class="file-type pdf">
                                                        <i class="bi bi-file-earmark-pdf"></i>
                                                        {{ $skp->nama_file ?? 'Dokumen SKP Tahunan' }}
                                                    </a>

                                                    @if ($skp->updated_at)
                                                        <small class="text-muted">
                                                            Diupload:
                                                            {{ $skp->updated_at->timezone('Asia/Jakarta')->format('d M Y H:i') }}
                                                        </small>
                                                    @endif

                                                </div>

                                            @elseif ($skp && ($skp->status ?? '') === 'ditolak')

                                                <span class="file-empty">
                                                    <i class="bi bi-file-earmark-x"></i>
                                                    File ditolak — silakan upload ulang
                                                </span>

                                            @else

                                                <span class="file-empty">
                                                    <i class="bi bi-file-earmark"></i>
                                                    Belum upload
                                                </span>

                                            @endif

                                        </td>

                                        <td class="text-center">
                                            @if ($skp && $skp->path_file && ($skp->status ?? 'menunggu') === 'menunggu')
                                                <div class="approval-actions">
                                                    <button type="button"
                                                        class="btn btn-sm btn-success btn-review-penetapan"
                                                        data-id="{{ $skp->id }}"
                                                        data-aksi="setujui">
                                                        <i class="bi bi-check-lg"></i> Setuju
                                                    </button>

                                                    <button type="button"
                                                        class="btn btn-sm btn-danger btn-review-penetapan"
                                                        data-id="{{ $skp->id }}"
                                                        data-aksi="tolak">
                                                        <i class="bi bi-x-lg"></i> Tolak
                                                    </button>
                                                </div>
                                            @elseif ($skp && ($skp->status ?? '') === 'ditolak' && $skp->keterangan)
                                                <button type="button"
                                                    class="btn btn-sm btn-outline-danger btn-lihat-alasan-penetapan"
                                                    data-keterangan="{{ $skp->keterangan }}">
                                                    <i class="bi bi-chat-left-text"></i> Lihat Alasan
                                                </button>
                                            @elseif ($skp && ($skp->status ?? '') === 'disetujui')
                                                <span class="text-success small fw-semibold">
                                                    <i class="bi bi-check-circle-fill"></i> Selesai
                                                </span>
                                            @else
                                                <span class="text-muted small">-</span>
                                            @endif
                                        </td>

                                    </tr>

                                @empty

                                    <tr>
                                        <td colspan="5"
                                            class="text-center text-muted py-4">
                                            <i class="bi bi-inbox fs-4 d-block mb-2"></i>
                                            Belum ada data pegawai.
                                        </td>
                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

                <div class="skp-note">
                    <i class="bi bi-info-circle"></i>
                    <span>
                        Monitoring SKP Penetapan hanya menampilkan dokumen
                        dengan metode Tahunan yang telah diupload oleh pegawai.
                    </span>
                </div>

            </div>

        </div>

    </div>


    {{-- MODAL SETUJU PENETAPAN --}}
    <div class="modal fade" id="modalSetujuiPenetapan" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-approval">
                <div class="modal-approval-header">
                    <div class="d-flex align-items-center gap-3">
                        <div class="modal-approval-icon"><i class="bi bi-check-circle-fill"></i></div>
                        <div>
                            <h5 class="modal-approval-title">Setujui SKP Penetapan</h5>
                            <small class="modal-approval-subtitle">Persetujuan dokumen SKP Penetapan Tahunan</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="approval-confirm-box">
                        <i class="bi bi-shield-check"></i>
                        <strong>Apakah dokumen SKP Penetapan ini sudah sesuai?</strong>
                        <div class="small mt-1">Setelah disetujui, status dokumen akan menjadi Disetujui.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-success" id="btnKonfirmasiSetujuiPenetapan">
                        <i class="bi bi-check-circle"></i> Setujui Dokumen
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL TOLAK PENETAPAN --}}
    <div class="modal fade" id="modalTolakPenetapan" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-rejection">
                <div class="modal-rejection-header">
                    <div class="d-flex align-items-center gap-3">
                        <div class="modal-rejection-icon"><i class="bi bi-x-circle-fill"></i></div>
                        <div>
                            <h5 class="modal-rejection-title">Tolak SKP Penetapan</h5>
                            <small class="modal-rejection-subtitle">Alasan penolakan akan disimpan untuk pegawai</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="rejection-box">
                        <label for="keteranganTolakPenetapan" class="form-label fw-semibold">Alasan Penolakan</label>
                        <textarea id="keteranganTolakPenetapan" class="form-control" maxlength="2000" placeholder="Tuliskan alasan penolakan..."></textarea>
                        <div class="rejection-counter"><span id="counterKeteranganPenetapan">0</span>/2000</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" id="btnKonfirmasiTolakPenetapan">
                        <i class="bi bi-x-circle"></i> Tolak Dokumen
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL DOWNLOAD ALL --}}
    <div class="modal fade" id="modalDownloadAll" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-backup">
                <div class="modal-header">
                    <div class="d-flex align-items-center gap-3">
                        <div class="modal-icon-backup"><i class="bi bi-cloud-arrow-down-fill"></i></div>
                        <div>
                            <h5 class="modal-title mb-0">Download Semua File</h5>
                            <small class="text-muted">Backup SKP Penetapan Tahun {{ $tahun }}</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="backup-info">
                        <i class="bi bi-info-circle-fill"></i>
                        <div>
                            <strong>Backup seluruh dokumen?</strong>
                            <p class="mb-0 mt-1">Semua file SKP Penetapan tahun <strong>{{ $tahun }}</strong> akan dikumpulkan menjadi satu file ZIP.</p>
                        </div>
                    </div>

                    <div class="backup-structure mt-3">
                        <div class="fw-semibold mb-2">Struktur file ZIP:</div>
                        <div class="backup-tree">
                            <div><i class="bi bi-file-earmark-zip"></i> Backup_SKP_Penetapan_{{ $tahun }}.zip</div>
                            <div class="ms-3"><i class="bi bi-folder"></i> Nama Pegawai/</div>
                            <div class="ms-5"><i class="bi bi-file-earmark-pdf"></i> Januari - file.pdf</div>
                            <div class="ms-5"><i class="bi bi-file-earmark-pdf"></i> Februari - file.pdf</div>
                            <div class="ms-5"><i class="bi bi-three-dots"></i> File SKP bulan lainnya</div>
                        </div>
                    </div>

                    <div class="backup-warning mt-3">
                        <i class="bi bi-shield-check"></i>
                        <span>Setelah proses backup berhasil, tombol <strong>Reset</strong> akan terbuka.</span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="btnKonfirmasiDownloadAll">
                        <i class="bi bi-download"></i> Download All
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL RESET --}}
    <div class="modal fade" id="modalResetPenetapan" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-reset">
                <div class="modal-header">
                    <div class="d-flex align-items-center gap-3">
                        <div class="modal-icon-reset"><i class="bi bi-arrow-counterclockwise"></i></div>
                        <div>
                            <h5 class="modal-title mb-0">Reset Data SKP Penetapan</h5>
                            <small class="text-muted">Tahun {{ $tahun }}</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="reset-warning">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        <div>
                            <strong>Perhatian!</strong>
                            <p class="mb-0 mt-1">Seluruh data dan file SKP Penetapan tahun <strong>{{ $tahun }}</strong> pada monitoring akan dihapus.</p>
                        </div>
                    </div>

                    <div class="reset-safe mt-3">
                        <i class="bi bi-shield-check"></i>
                        <span>Backup sudah dilakukan. Data pegawai dan SKP Evaluasi tidak akan ikut dihapus.</span>
                    </div>

                    <p class="mt-3 mb-0">Apakah Anda yakin ingin melanjutkan reset?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" id="btnKonfirmasiResetPenetapan">
                        <i class="bi bi-trash"></i> Ya, Reset Data
                    </button>
                </div>
            </div>
        </div>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const tahun =
                document.getElementById('filterTahun');

            const btnTampilkan =
                document.getElementById('btnTampilkan');

            if (btnTampilkan) {

                btnTampilkan.addEventListener(
                    'click',
                    function () {

                        window.location.href =
                            '{{ route('admin.monitoring_skp.penetapan') }}?tahun=' +
                            encodeURIComponent(
                                tahun.value
                            );

                    }
                );

            }

            /* ================= REVIEW PENETAPAN ================= */
            let reviewPenetapanData = null;

            const modalSetujuiPenetapan = document.getElementById('modalSetujuiPenetapan');
            const modalTolakPenetapan = document.getElementById('modalTolakPenetapan');
            const textareaTolakPenetapan = document.getElementById('keteranganTolakPenetapan');
            const counterTolakPenetapan = document.getElementById('counterKeteranganPenetapan');

            document.querySelectorAll('.btn-review-penetapan').forEach(function (button) {
                button.addEventListener('click', function () {
                    reviewPenetapanData = {
                        id: this.dataset.id,
                        aksi: this.dataset.aksi
                    };

                    if (reviewPenetapanData.aksi === 'setujui') {
                        bootstrap.Modal.getOrCreateInstance(modalSetujuiPenetapan).show();
                    } else {
                        textareaTolakPenetapan.value = '';
                        counterTolakPenetapan.textContent = '0';
                        bootstrap.Modal.getOrCreateInstance(modalTolakPenetapan).show();
                    }
                });
            });

            if (textareaTolakPenetapan) {
                textareaTolakPenetapan.addEventListener('input', function () {
                    counterTolakPenetapan.textContent = this.value.length;
                });
            }

            function kirimReviewPenetapan(aksi, keterangan) {
                if (!reviewPenetapanData) return;

                return fetch('{{ url('/monitoring-skp/penetapan') }}/' + encodeURIComponent(reviewPenetapanData.id) + '/review', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        aksi: aksi,
                        keterangan: keterangan
                    })
                }).then(async response => {
                    const result = await response.json();
                    if (!response.ok) {
                        throw new Error(result.message || 'Proses review gagal.');
                    }
                    return result;
                });
            }

            document.getElementById('btnKonfirmasiSetujuiPenetapan').addEventListener('click', function () {
                const btn = this;
                btn.disabled = true;
                btn.innerHTML = '<i class="bi bi-arrow-repeat"></i> Memproses...';

                kirimReviewPenetapan('setujui', '')
                    .then(result => {
                        bootstrap.Modal.getOrCreateInstance(modalSetujuiPenetapan).hide();
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: result.message || 'SKP Penetapan berhasil disetujui.',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#198754',
                            timer: 2000,
                            timerProgressBar: true
                        }).then(() => window.location.reload());
                    })
                    .catch(error => {
                        Swal.fire({ icon:'error', title:'Gagal!', text:error.message || 'Proses review gagal.', confirmButtonColor:'#dc3545' });
                    })
                    .finally(() => {
                        btn.disabled = false;
                        btn.innerHTML = '<i class="bi bi-check-circle"></i> Setujui Dokumen';
                    });
            });

            document.getElementById('btnKonfirmasiTolakPenetapan').addEventListener('click', function () {
                const btn = this;
                const keterangan = textareaTolakPenetapan.value.trim();

                if (!keterangan) {
                    textareaTolakPenetapan.focus();
                    Swal.fire({ icon:'warning', title:'Alasan Belum Diisi', text:'Silakan masukkan alasan penolakan terlebih dahulu.', confirmButtonColor:'#f0ad4e' });
                    return;
                }

                if (keterangan.length < 3) {
                    textareaTolakPenetapan.focus();
                    Swal.fire({ icon:'warning', title:'Alasan Terlalu Singkat', text:'Silakan berikan alasan penolakan yang lebih jelas.', confirmButtonColor:'#f0ad4e' });
                    return;
                }

                btn.disabled = true;
                btn.innerHTML = '<i class="bi bi-arrow-repeat"></i> Memproses...';

                kirimReviewPenetapan('tolak', keterangan)
                    .then(result => {
                        bootstrap.Modal.getOrCreateInstance(modalTolakPenetapan).hide();
                        Swal.fire({
                            icon:'success',
                            title:'Dokumen Ditolak',
                            text:result.message || 'SKP Penetapan berhasil ditolak.',
                            confirmButtonText:'OK',
                            confirmButtonColor:'#198754',
                            timer:2500,
                            timerProgressBar:true
                        }).then(() => window.location.reload());
                    })
                    .catch(error => {
                        Swal.fire({ icon:'error', title:'Gagal!', text:error.message || 'Proses review gagal.', confirmButtonColor:'#dc3545' });
                    })
                    .finally(() => {
                        btn.disabled = false;
                        btn.innerHTML = '<i class="bi bi-x-circle"></i> Tolak Dokumen';
                    });
            });

            document.querySelectorAll('.btn-lihat-alasan-penetapan').forEach(function (button) {
                button.addEventListener('click', function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Alasan Penolakan',
                        text: this.dataset.keterangan || 'Tidak ada keterangan penolakan.',
                        confirmButtonText: 'Mengerti',
                        confirmButtonColor: '#dc3545'
                    });
                });
            });

            const btnDownloadAll = document.getElementById('btnDownloadAll');
            const btnKonfirmasiDownloadAll = document.getElementById('btnKonfirmasiDownloadAll');
            const modalDownloadAll = document.getElementById('modalDownloadAll');

            if (btnDownloadAll && modalDownloadAll) {
                btnDownloadAll.addEventListener('click', function () {
                    bootstrap.Modal.getOrCreateInstance(modalDownloadAll).show();
                });
            }

            if (btnKonfirmasiDownloadAll) {
                btnKonfirmasiDownloadAll.addEventListener('click', function () {
                    const btn = this;
                    btn.disabled = true;
                    btn.innerHTML = '<i class="bi bi-arrow-repeat"></i> Menyiapkan ZIP...';

                    bootstrap.Modal.getOrCreateInstance(modalDownloadAll).hide();

                    const iframe = document.createElement('iframe');
                    iframe.style.display = 'none';
                    iframe.src = '{{ route('admin.monitoring_skp.penetapan.download-all') }}?tahun=' + encodeURIComponent('{{ $tahun }}');
                    document.body.appendChild(iframe);

                    setTimeout(function () {
                        window.location.reload();
                    }, 4000);
                });
            }

            /* ================= MODAL RESET ================= */
            const btnResetPenetapan = document.getElementById('btnResetPenetapan');
            const btnKonfirmasiResetPenetapan = document.getElementById('btnKonfirmasiResetPenetapan');
            const modalResetPenetapan = document.getElementById('modalResetPenetapan');

            if (btnResetPenetapan && !btnResetPenetapan.disabled && modalResetPenetapan) {
                btnResetPenetapan.addEventListener('click', function () {
                    bootstrap.Modal.getOrCreateInstance(modalResetPenetapan).show();
                });
            }

            if (btnKonfirmasiResetPenetapan) {
                btnKonfirmasiResetPenetapan.addEventListener('click', function () {
                    const btn = this;
                    btn.disabled = true;
                    btn.innerHTML = '<i class="bi bi-arrow-repeat"></i> Mereset Data...';

                    fetch('{{ route('admin.monitoring_skp.penetapan.reset') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            tahun: '{{ $tahun }}'
                        })
                    })
                    .then(async response => {
                        const result = await response.json();
                        if (!response.ok) {
                            throw new Error(result.message || 'Reset gagal dilakukan.');
                        }
                        return result;
                    })
                    .then(result => {
                        bootstrap.Modal.getOrCreateInstance(modalResetPenetapan).hide();

                        Swal.fire({
                            icon: 'success',
                            title: 'Reset Berhasil!',
                            text: result.message || 'Seluruh data SKP Penetapan berhasil direset.',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#198754',
                            timer: 2500,
                            timerProgressBar: true
                        }).then(() => {
                            window.location.reload();
                        });
                    })
                    .catch(error => {
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

    @if (session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Backup Gagal!',
                    text: @json(session('error')),
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#dc3545'
                });
            });
        </script>
    @endif
@endsection
