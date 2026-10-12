@extends('layouts.app')

@section('content')

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
    </style>

    <div class="skp-page">

        {{-- =====================================================
         HEADER
    ====================================================== --}}

        <div class="skp-heading">

            <div>
                <h3>Monitoring SKP</h3>
                <p>Monitoring pengumpulan dan persetujuan SKP pegawai</p>
            </div>

            <div class="periode-badge">
                @if (($jenisAktif ?? 'bulanan') === 'bulanan')
                    @if (($bulan ?? 'all') === 'all')
                        Periode: Semua Bulan {{ $tahun }}
                    @else
                        Periode: {{ $daftarBulan[str_pad((string) ($bulan ?? 1), 2, '0', STR_PAD_LEFT)] ?? 'Januari' }} {{ $tahun }}
                    @endif
                @elseif (($jenisAktif ?? 'bulanan') === 'triwulan')
                    @if (($triwulan ?? 'all') === 'all')
                        Periode: Semua Triwulan {{ $tahun }}
                    @else
                        Periode: Triwulan {{ $triwulan ?? 1 }} ({{ $daftarTriwulan[(string) ($triwulan ?? 1)] ?? 'Jan - Mar' }}) {{ $tahun }}
                    @endif
                @else
                    Periode: Tahun {{ $tahun }}
                @endif
            </div>

        </div>

        {{-- =====================================================
         FILTER
    ====================================================== --}}

        <div class="card skp-filter-card">

            <div class="card-body">

                <div class="skp-filter-title">
                    <i class="bi bi-funnel"></i>
                    Filter Monitoring SKP Evaluasi
                </div>

                <div class="monitoring-type-wrapper">

                    <label class="form-label">Jenis Evaluasi</label>

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
                        <label class="form-label">Bulan</label>
                        <select class="form-select" id="filterBulan">
                            <option value="all" {{ ($bulan ?? 'all') === 'all' ? 'selected' : '' }}>
                                Semua Bulan
                            </option>
                            @foreach ($daftarBulan as $kode => $namaBln)
                                <option value="{{ $kode }}"
                                    {{ (string) ($bulan ?? 'all') === (string) $kode ? 'selected' : '' }}>
                                    {{ $namaBln }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4" id="wrapperTriwulan"
                        style="display: {{ ($jenisAktif ?? 'bulanan') === 'triwulan' ? 'block' : 'none' }};">
                        <label class="form-label">Triwulan</label>
                        <select class="form-select" id="filterTriwulan">
                            <option value="all" {{ ($triwulan ?? 'all') === 'all' ? 'selected' : '' }}>
                                Semua Triwulan
                            </option>
                            @foreach ($daftarTriwulan as $kode => $rentang)
                                <option value="{{ $kode }}"
                                    {{ (string) ($triwulan ?? 'all') === (string) $kode ? 'selected' : '' }}>
                                    Triwulan {{ $kode }} ({{ $rentang }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4" id="wrapperTahunan"
                        style="display: {{ ($jenisAktif ?? 'bulanan') === 'tahunan' ? 'block' : 'none' }};">
                        <div class="form-control monitoring-readonly">
                            <i class="bi bi-calendar-check"></i>
                            Satu Tahun
                        </div>
                    </div>

                    <div class="col-md-4">
                        <button type="button" class="btn btn-primary w-100 skp-filter-btn" id="btnTampilkan">
                            <i class="bi bi-search"></i>
                            Tampilkan Data
                        </button>
                    </div>

                </div>

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
                                <h6>Sudah Lengkap</h6>
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
                                <h6>Belum Lengkap</h6>
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
                                <i class="bi bi-clipboard-check"></i>
                                @if ($jenisAktif === 'bulanan')
                                    Monitoring Evaluasi SKP Bulanan
                                @elseif ($jenisAktif === 'triwulan')
                                    Monitoring Evaluasi SKP Triwulan
                                @else
                                    Monitoring Evaluasi SKP Tahunan
                                @endif
                            </h5>

                            <small>
                                @if ($jenisAktif === 'bulanan')
                                    @if (($bulan ?? 'all') === 'all')
                                        Semua Bulan {{ $tahun }}
                                    @else
                                        {{ $daftarBulan[str_pad((string) $bulan, 2, '0', STR_PAD_LEFT)] ?? 'Januari' }} {{ $tahun }}
                                    @endif
                                @elseif ($jenisAktif === 'triwulan')
                                    @if (($triwulan ?? 'all') === 'all')
                                        Semua Triwulan {{ $tahun }}
                                    @else
                                        Triwulan {{ $triwulan }} ({{ $daftarTriwulan[(string) $triwulan] ?? 'Jan - Mar' }}) {{ $tahun }}
                                    @endif
                                @else
                                    Tahun {{ $tahun }}
                                @endif
                            </small>
                        </div>

                        <div class="skp-section-actions">

                            <button type="button" class="btn btn-light btn-sm" id="btnDownloadAll">
                                <i class="bi bi-download"></i>
                                Download All
                            </button>

                            @if ($backupSudahDilakukan)
                                <button type="button" class="btn btn-danger btn-sm" id="btnResetEvaluasi">
                                    <i class="bi bi-arrow-counterclockwise"></i>
                                    Reset
                                </button>
                            @else
                                <button type="button" class="btn btn-secondary btn-sm" id="btnResetEvaluasi" disabled
                                    title="Download All terlebih dahulu">
                                    <i class="bi bi-lock-fill"></i>
                                    Reset
                                </button>
                            @endif

                        </div>

                    </div>

                    <div class="table-responsive">

                        @php
                            $periodeLabel = function ($jenis, $periode) use ($daftarBulan, $daftarTriwulan) {
                                if ($jenis === 'bulanan') {
                                    return $daftarBulan[str_pad((string) $periode, 2, '0', STR_PAD_LEFT)] ?? 'Bulan';
                                }
                                if ($jenis === 'triwulan') {
                                    return 'Triwulan ' . $periode;
                                }
                                return 'Tahunan';
                            };

                            $rekamanTerpilih = function ($item, $jenis) use ($periodeList) {
                                if ($jenis === 'bulanan') {
                                    return collect($periodeList)->map(fn($p) => $item['bulanan'][(int) $p] ?? null);
                                }
                                if ($jenis === 'triwulan') {
                                    return collect($periodeList)->map(fn($p) => $item['triwulan'][(int) $p] ?? null);
                                }
                                return collect([$item['tahunan'] ?? null]);
                            };
                        @endphp

                        <table class="table skp-table mb-0">

                            <thead>
                                <tr>
                                    <th style="width:50px;">No</th>
                                    <th style="min-width:200px;">Nama Pegawai</th>

                                    @foreach ($periodeList as $periode)
                                        <th class="text-center periode-column" style="min-width:330px;">
                                            {{ $periodeLabel($jenisAktif, $periode) }}
                                            @if ($jenisAktif === 'triwulan')
                                                <small>{{ $daftarTriwulan[(string) $periode] ?? '' }}</small>
                                            @elseif ($jenisAktif === 'tahunan')
                                                <small>{{ $tahun }}</small>
                                            @endif
                                        </th>
                                    @endforeach

                                    <th class="text-center" style="min-width:210px;">Keterangan</th>
                                </tr>
                            </thead>

                            <tbody>

                                @forelse ($dataEvaluasi as $index => $item)

                                    @php
                                        $records = $rekamanTerpilih($item, $jenisAktif);
                                        // Bulanan & Triwulan -> hanya dokumen Kualitatif.
                                        // Tahunan            -> dokumen Evaluasi Kinerja + Kualitatif.
                                        $dokumenDiperlukan = $jenisAktif === 'tahunan'
                                            ? ['evaluasi', 'kualitatif']
                                            : ['kualitatif'];

                                        $adaDitolak = $records->contains(fn($e) =>
                                            $e &&
                                            collect($dokumenDiperlukan)->contains(
                                                fn($d) => $e->{'status_dokumen_' . $d} === 'ditolak'
                                            )
                                        );
                                        $adaMenunggu = $records->contains(fn($e) =>
                                            $e &&
                                            collect($dokumenDiperlukan)->contains(
                                                fn($d) => $e->{'status_dokumen_' . $d} === 'menunggu'
                                            )
                                        );
                                        $lengkap = $records->every(fn($e) =>
                                            $e &&
                                            collect($dokumenDiperlukan)->every(
                                                fn($d) => !empty($e->{'dokumen_' . $d . '_path'}) &&
                                                    $e->{'status_dokumen_' . $d} === 'disetujui'
                                            )
                                        );
                                    @endphp

                                    <tr>

                                        <td class="text-center">{{ $index + 1 }}</td>

                                        <td class="nama-pegawai">{{ $item['pegawai']->nama }}</td>

                                        @foreach ($periodeList as $periode)

                                            @php
                                                if ($jenisAktif === 'bulanan') {
                                                    $evaluasi = $item['bulanan'][(int) $periode] ?? null;
                                                } elseif ($jenisAktif === 'triwulan') {
                                                    $evaluasi = $item['triwulan'][(int) $periode] ?? null;
                                                } else {
                                                    $evaluasi = $item['tahunan'] ?? null;
                                                }

                                                $statusDokumen1 = $evaluasi?->status_dokumen_evaluasi;
                                                $statusDokumen2 = $evaluasi?->status_dokumen_kualitatif;
                                            @endphp

                                            <td class="text-center">

                                                <div class="dokumen-review-status">

                                                    {{-- Dokumen Evaluasi Kinerja: hanya untuk evaluasi Tahunan --}}
                                                    @if ($jenisAktif === 'tahunan')
                                                    <div class="fw-semibold mb-1">
                                                        <i class="bi bi-file-earmark-text"></i>
                                                        Dokumen Evaluasi Kinerja
                                                    </div>

                                                    @if ($evaluasi?->dokumen_evaluasi_path)

                                                        <div class="dokumen-file-name">
                                                            <i class="bi bi-file-earmark-pdf text-danger"></i>
                                                            <span title="{{ $evaluasi->dokumen_evaluasi_file }}">
                                                                {{ $evaluasi->dokumen_evaluasi_file }}
                                                            </span>
                                                        </div>

                                                        <a href="{{ $evaluasi->dokumen_evaluasi_url }}" target="_blank"
                                                            class="status-mini sudah">
                                                            <i class="bi bi-eye"></i>
                                                            Lihat PDF
                                                        </a>

                                                        @if ($statusDokumen1 === 'menunggu')
                                                            <div class="d-flex gap-1 flex-wrap justify-content-center mt-1">
                                                                <button type="button"
                                                                    class="btn btn-sm btn-success btn-review-dokumen"
                                                                    data-id="{{ $evaluasi->id }}" data-jenis="evaluasi"
                                                                    data-aksi="setujui">
                                                                    <i class="bi bi-check-lg"></i> Setuju
                                                                </button>
                                                                <button type="button"
                                                                    class="btn btn-sm btn-danger btn-review-dokumen"
                                                                    data-id="{{ $evaluasi->id }}" data-jenis="evaluasi"
                                                                    data-aksi="tolak">
                                                                    <i class="bi bi-x-lg"></i> Tolak
                                                                </button>
                                                            </div>
                                                        @elseif ($statusDokumen1 === 'disetujui')
                                                            <span class="badge bg-success">
                                                                <i class="bi bi-check-circle"></i> Disetujui
                                                            </span>
                                                        @elseif ($statusDokumen1 === 'ditolak')
                                                            <span class="status-mini ditolak">
                                                                <i class="bi bi-x-circle"></i> Ditolak
                                                            </span>
                                                            @if ($evaluasi->keterangan_dokumen_evaluasi)
                                                                <button type="button"
                                                                    class="btn btn-sm btn-outline-danger btn-lihat-keterangan mt-1"
                                                                    data-jenis="Dokumen Evaluasi Kinerja"
                                                                    data-keterangan="{{ $evaluasi->keterangan_dokumen_evaluasi }}">
                                                                    <i class="bi bi-chat-left-text"></i> Lihat Alasan
                                                                </button>
                                                            @endif
                                                        @endif

                                                    @else
                                                        <span class="status-mini belum">
                                                            <i class="bi bi-clock"></i>
                                                            Belum Upload
                                                        </span>
                                                    @endif

                                                    @endif

                                                    {{-- Dokumen Pendekatan Hasil Kerja Kualitatif: semua jenis evaluasi --}}
                                                    <div class="{{ $jenisAktif === 'tahunan' ? 'border-top mt-3 pt-3 w-100' : 'w-100' }}">

                                                        <div class="fw-semibold mb-1">
                                                            <i class="bi bi-file-earmark-text"></i>
                                                           Dokumen Evaluasi Kinerja Pendekatan Hasil Kerja Kualitatif
                                                        </div>

                                                        @if ($evaluasi?->dokumen_kualitatif_path)

                                                            <div class="dokumen-file-name">
                                                                <i class="bi bi-file-earmark-pdf text-danger"></i>
                                                                <span title="{{ $evaluasi->dokumen_kualitatif_file }}">
                                                                    {{ $evaluasi->dokumen_kualitatif_file }}
                                                                </span>
                                                            </div>

                                                            <a href="{{ $evaluasi->dokumen_kualitatif_url }}" target="_blank"
                                                                class="status-mini sudah">
                                                                <i class="bi bi-eye"></i>
                                                                Lihat PDF
                                                            </a>

                                                            @if ($statusDokumen2 === 'menunggu')
                                                                <div class="d-flex gap-1 flex-wrap justify-content-center mt-1">
                                                                    <button type="button"
                                                                        class="btn btn-sm btn-success btn-review-dokumen"
                                                                        data-id="{{ $evaluasi->id }}" data-jenis="kualitatif"
                                                                        data-aksi="setujui">
                                                                        <i class="bi bi-check-lg"></i> Setuju
                                                                    </button>
                                                                    <button type="button"
                                                                        class="btn btn-sm btn-danger btn-review-dokumen"
                                                                        data-id="{{ $evaluasi->id }}" data-jenis="kualitatif"
                                                                        data-aksi="tolak">
                                                                        <i class="bi bi-x-lg"></i> Tolak
                                                                    </button>
                                                                </div>
                                                            @elseif ($statusDokumen2 === 'disetujui')
                                                                <span class="badge bg-success">
                                                                    <i class="bi bi-check-circle"></i> Disetujui
                                                                </span>
                                                            @elseif ($statusDokumen2 === 'ditolak')
                                                                <span class="status-mini ditolak">
                                                                    <i class="bi bi-x-circle"></i> Ditolak
                                                                </span>
                                                                @if ($evaluasi->keterangan_dokumen_kualitatif)
                                                                    <button type="button"
                                                                        class="btn btn-sm btn-outline-danger btn-lihat-keterangan mt-1"
                                                                        data-jenis="Pendekatan Hasil Kerja Kualitatif"
                                                                        data-keterangan="{{ $evaluasi->keterangan_dokumen_kualitatif }}">
                                                                        <i class="bi bi-chat-left-text"></i> Lihat Alasan
                                                                    </button>
                                                                @endif
                                                            @else
                                                                <span class="status-mini belum">
                                                                    <i class="bi bi-clock"></i>
                                                                    Belum Upload
                                                                </span>
                                                            @endif

                                                        @endif

                                                    </div>

                                                </div>

                                            </td>

                                        @endforeach

                                        <td class="text-center">

                                            @if ($lengkap)
                                                <div class="keterangan-status">
                                                    <span class="badge bg-success">
                                                        <i class="bi bi-check-circle"></i> Disetujui
                                                    </span>
                                                </div>
                                            @elseif ($adaDitolak)
                                                <div class="keterangan-status">
                                                    <span class="badge bg-danger">
                                                        <i class="bi bi-x-circle"></i> Perlu Perbaikan
                                                    </span>
                                                </div>
                                            @elseif ($adaMenunggu)
                                                <div class="keterangan-status">
                                                    <span class="badge bg-warning text-dark">
                                                        <i class="bi bi-clock"></i> Menunggu Persetujuan
                                                    </span>
                                                </div>
                                            @else
                                                <div class="keterangan-status">
                                                    <span class="badge bg-secondary">Belum Lengkap</span>
                                                </div>
                                            @endif

                                        </td>

                                    </tr>

                                @empty

                                    <tr>
                                        <td colspan="{{ 3 + count($periodeList) }}" class="text-center text-muted py-4">
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
                        Monitoring digunakan untuk memeriksa dokumen dan melakukan persetujuan atau penolakan evaluasi SKP.
                        <br>
                        Evaluasi <strong>Bulanan</strong> dan <strong>Triwulan</strong> hanya memerlukan Dokumen
                        Evaluasi Kinerja Pendekatan Hasil Kerja Kualitatif, sedangkan evaluasi
                        <strong>Tahunan</strong> memerlukan dua dokumen.
                    </span>
                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
     MODAL LIHAT KETERANGAN PENOLAKAN
========================================================= --}}

    <div class="modal fade" id="modalLihatKeterangan" tabindex="-1" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered modal-sm">

            <div class="modal-content modal-keterangan">

                <div class="modal-header modal-keterangan-header">

                    <div>

                        <div class="modal-icon-danger">

                            <i class="bi bi-exclamation-triangle-fill"></i>

                        </div>

                        <div>

                            <h5 class="modal-title">
                                Dokumen Ditolak
                            </h5>

                            <small id="judulJenisKeterangan">
                                Keterangan penolakan
                            </small>

                        </div>

                    </div>

                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                </div>


                <div class="modal-body">

                    <div class="keterangan-card">

                        <div class="keterangan-card-title">

                            <i class="bi bi-chat-left-text"></i>

                            Alasan penolakan

                        </div>

                        <div id="isiKeteranganPenolakan" class="keterangan-penolakan"></div>

                    </div>


                    <div class="keterangan-hint">

                        <i class="bi bi-arrow-repeat"></i>

                        <span>

                            Dokumen yang ditolak dapat
                            diupload ulang oleh pegawai.

                        </span>

                    </div>

                </div>


                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">

                        <i class="bi bi-check2"></i>

                        Mengerti

                    </button>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
     MODAL SETUJU
========================================================= --}}

    <div class="modal fade" id="modalSetujuiDokumen" tabindex="-1" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered modal-sm">

            <div class="modal-content modal-setuju">

                <div class="modal-header modal-setuju-header">

                    <div>

                        <div class="modal-icon-success">

                            <i class="bi bi-check-lg"></i>

                        </div>

                        <div>

                            <h5 class="modal-title">
                                Setujui Dokumen
                            </h5>

                            <small id="judulSetujuiDokumen">
                                Dokumen evaluasi
                            </small>

                        </div>

                    </div>

                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                </div>


                <div class="modal-body">

                    <div class="persetujuan-card">

                        <i class="bi bi-shield-check"></i>

                        <span>

                            Apakah kamu yakin ingin menyetujui
                            dokumen ini?

                            Dokumen yang sudah disetujui
                            tidak akan menampilkan tombol
                            review lagi.

                        </span>

                    </div>

                </div>


                <div class="modal-footer">

                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        Batal
                    </button>


                    <button type="button" class="btn btn-success btn-konfirmasi-setuju"
                        id="btnKonfirmasiSetujuiDokumen">

                        <i class="bi bi-check-circle"></i>

                        Setujui Dokumen

                    </button>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
     MODAL TOLAK
========================================================= --}}

    <div class="modal fade" id="modalTolakDokumen" tabindex="-1" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content modal-tolak">

                <div class="modal-header modal-tolak-header">

                    <div>

                        <div class="modal-icon-danger">

                            <i class="bi bi-x-circle-fill"></i>

                        </div>

                        <div>

                            <h5 class="modal-title">
                                Tolak Dokumen
                            </h5>

                            <small id="judulTolakDokumen">
                                Dokumen evaluasi
                            </small>

                        </div>

                    </div>

                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                </div>


                <div class="modal-body">

                    <div class="review-warning">

                        <i class="bi bi-info-circle-fill"></i>

                        <span>

                            Dokumen yang ditolak akan
                            dihapus dari database dan storage.
                            Pegawai dapat mengupload ulang
                            dokumen tersebut.

                        </span>

                    </div>


                    <label for="keteranganTolakDokumen" class="form-label fw-semibold">

                        Alasan penolakan

                        <span class="text-danger">
                            *
                        </span>

                    </label>


                    <textarea id="keteranganTolakDokumen" class="form-control" rows="4" maxlength="2000"
                        placeholder="Tuliskan alasan penolakan secara jelas..."></textarea>


                    <div class="text-end small text-muted mt-1">

                        <span id="counterKeterangan">
                            0
                        </span>

                        /2000

                    </div>

                </div>


                <div class="modal-footer">

                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">

                        Batal

                    </button>


                    <button type="button" class="btn btn-danger" id="btnSimpanTolakDokumen">

                        <i class="bi bi-x-circle"></i>

                        Tolak Dokumen

                    </button>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
     MODAL DOWNLOAD ALL
========================================================= --}}

    <div class="modal fade" id="modalDownloadAll" tabindex="-1" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content modal-backup">

                <div class="modal-header">

                    <div>

                        <div class="modal-icon-backup">

                            <i class="bi bi-cloud-arrow-down-fill"></i>

                        </div>

                        <div>

                            <h5 class="modal-title">
                                Download Semua File
                            </h5>

                            <small>
                                Backup SKP Evaluasi Tahun {{ $tahun }}
                            </small>

                        </div>

                    </div>

                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                </div>


                <div class="modal-body">

                    <div class="backup-info">

                        <i class="bi bi-info-circle-fill"></i>

                        <div>

                            <strong>
                                Backup seluruh dokumen?
                            </strong>

                            <p class="mb-0">

                                Semua file SKP Evaluasi
                                tahun {{ $tahun }}
                                akan dikumpulkan ke dalam
                                satu file ZIP.

                            </p>

                        </div>

                    </div>


                    <div class="backup-structure mt-3">

                        <div class="fw-semibold mb-2">
                            Struktur file:
                        </div>

                        <div class="backup-tree">

                            <div>

                                <i class="bi bi-file-earmark-zip"></i>

                                Backup_SKP_Evaluasi_{{ $tahun }}.zip

                            </div>

                            <div class="ms-3">
                                <i class="bi bi-folder"></i>
                                Nama Pegawai/
                            </div>
                            <div class="ms-4">
                                <i class="bi bi-folder"></i>
                                Bulanan/ dan Triwulan/
                            </div>
                            <div class="ms-5">
                                <i class="bi bi-file-earmark-pdf"></i>
                                Kualitatif - file.pdf
                            </div>
                            <div class="ms-4">
                                <i class="bi bi-folder"></i>
                                Tahunan/
                            </div>
                            <div class="ms-5">
                                <i class="bi bi-file-earmark-pdf"></i>
                                Evaluasi Kinerja - file.pdf
                            </div>
                            <div class="ms-5">
                                <i class="bi bi-file-earmark-pdf"></i>
                                Kualitatif - file.pdf
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

    <div class="modal fade" id="modalResetEvaluasi" tabindex="-1" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content modal-reset">

                <div class="modal-header">

                    <div>

                        <div class="modal-icon-reset">

                            <i class="bi bi-arrow-counterclockwise"></i>

                        </div>

                        <div>

                            <h5 class="modal-title">
                                Reset Data SKP Evaluasi
                            </h5>

                            <small>
                                Tahun {{ $tahun }}
                            </small>

                        </div>

                    </div>

                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                </div>


                <div class="modal-body">

                    <div class="reset-warning">

                        <i class="bi bi-exclamation-triangle-fill"></i>

                        <div>

                            <strong>
                                Perhatian!
                            </strong>

                            <p class="mb-0 mt-1">

                                Seluruh data dan file
                                SKP Evaluasi tahun
                                {{ $tahun }}
                                akan dihapus.

                            </p>

                        </div>

                    </div>


                    <div class="reset-safe mt-3">

                        <i class="bi bi-shield-check"></i>

                        <span>

                            Backup sudah dilakukan.
                            Data pegawai dan SKP Penetapan
                            tidak akan ikut dihapus.

                        </span>

                    </div>


                    <p class="mt-3 mb-0">

                        Apakah Anda yakin ingin
                        melakukan reset?

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




    {{-- =========================================================
     JAVASCRIPT
========================================================= --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const csrfToken = '{{ csrf_token() }}';

            let reviewData = null;
            /* =========================================================
               FILTER MONITORING SKP EVALUASI
            ========================================================= */

            const buttons =
                document.querySelectorAll('.monitoring-type-btn');

            const tahun =
                document.getElementById('filterTahun');

            const bulan =
                document.getElementById('filterBulan');

            const triwulan =
                document.getElementById('filterTriwulan');

            const wrapperBulan =
                document.getElementById('wrapperBulan');

            const wrapperTriwulan =
                document.getElementById('wrapperTriwulan');

            const wrapperTahunan =
                document.getElementById('wrapperTahunan');

            const btn =
                document.getElementById('btnTampilkan');

            let currentType =
                @json($jenisAktif ?? 'bulanan');

            function updateType() {

                buttons.forEach(button => {
                    button.classList.toggle(
                        'active',
                        button.dataset.type === currentType
                    );
                });

                const isBulanan = currentType === 'bulanan';
                const isTriwulan = currentType === 'triwulan';
                const isTahunan = currentType === 'tahunan';

                if (wrapperBulan) {
                    wrapperBulan.style.display =
                        isBulanan ? 'block' : 'none';
                }

                if (wrapperTriwulan) {
                    wrapperTriwulan.style.display =
                        isTriwulan ? 'block' : 'none';
                }

                if (wrapperTahunan) {
                    wrapperTahunan.style.display =
                        isTahunan ? 'block' : 'none';
                }

                if (isBulanan && bulan && bulan.value === '') {
                    bulan.value = 'all';
                }

                if (isTriwulan && triwulan && triwulan.value === '') {
                    triwulan.value = 'all';
                }
            }

            buttons.forEach(button => {

                button.addEventListener('click', function() {
                    currentType = this.dataset.type;
                    updateType();
                });

            });

            if (btn) {

                btn.addEventListener('click', function() {

                    const params = new URLSearchParams({
                        jenis: currentType,
                        tahun: tahun?.value || '{{ $tahun }}'
                    });

                    if (currentType === 'bulanan') {
                        params.set(
                            'bulan',
                            bulan?.value || 'all'
                        );
                    }

                    if (currentType === 'triwulan') {
                        params.set(
                            'triwulan',
                            triwulan?.value || 'all'
                        );
                    }

                    window.location.href =
                        '{{ route('admin.monitoring_skp.evaluasi') }}?' +
                        params.toString();

                });

            }

            updateType();

            /* =========================================================
               CLICK HANDLER
            ========================================================= */

            document.addEventListener('click', function(e) {


                /* =====================================================
                   LIHAT ALASAN PENOLAKAN
                ===================================================== */

                const lihat =
                    e.target.closest('.btn-lihat-keterangan');

                if (lihat) {

                    document.getElementById(
                            'judulJenisKeterangan'
                        ).textContent =
                        lihat.dataset.jenis ||
                        'Keterangan penolakan';


                    document.getElementById(
                            'isiKeteranganPenolakan'
                        ).textContent =
                        lihat.dataset.keterangan ||
                        'Tidak ada keterangan penolakan.';


                    bootstrap.Modal
                        .getOrCreateInstance(
                            document.getElementById(
                                'modalLihatKeterangan'
                            )
                        )
                        .show();

                    return;
                }


                /* =====================================================
                   REVIEW DOKUMEN
                ===================================================== */

                const review =
                    e.target.closest('.btn-review-dokumen');

                if (review) {

                    reviewData = {

                        id: review.dataset.id,

                        jenis: review.dataset.jenis,

                        aksi: review.dataset.aksi

                    };


                    /* =============================================
                       SETUJU
                    ============================================== */

                    if (reviewData.aksi === 'setujui') {

                        document.getElementById(
                                'judulSetujuiDokumen'
                            ).textContent =
                            reviewData.jenis === 'evaluasi' ?
                            'Dokumen Evaluasi Kinerja Pegawai' :
                            'Pendekatan Hasil Kerja Kualitatif';


                        bootstrap.Modal
                            .getOrCreateInstance(
                                document.getElementById(
                                    'modalSetujuiDokumen'
                                )
                            )
                            .show();

                        return;
                    }


                    /* =============================================
                       TOLAK
                    ============================================== */

                    document.getElementById(
                            'judulTolakDokumen'
                        ).textContent =
                        reviewData.jenis === 'evaluasi' ?
                        'Dokumen Evaluasi Kinerja Pegawai' :
                        'Pendekatan Hasil Kerja Kualitatif';


                    document.getElementById(
                        'keteranganTolakDokumen'
                    ).value = '';


                    document.getElementById(
                        'counterKeterangan'
                    ).textContent = '0';


                    bootstrap.Modal
                        .getOrCreateInstance(
                            document.getElementById(
                                'modalTolakDokumen'
                            )
                        )
                        .show();

                }

            });


            /* =========================================================
               COUNTER ALASAN
            ========================================================= */

            const textarea =
                document.getElementById(
                    'keteranganTolakDokumen'
                );

            if (textarea) {

                textarea.addEventListener(
                    'input',
                    function() {

                        document.getElementById(
                                'counterKeterangan'
                            ).textContent =
                            this.value.length;

                    }
                );

            }


            /* =========================================================
               KONFIRMASI SETUJU
            ========================================================= */

            const btnSetuju =
                document.getElementById(
                    'btnKonfirmasiSetujuiDokumen'
                );

            if (btnSetuju) {

                btnSetuju.addEventListener(
                    'click',
                    function() {

                        if (
                            !reviewData ||
                            reviewData.aksi !== 'setujui'
                        ) {
                            return;
                        }


                        this.disabled = true;

                        this.innerHTML =
                            '<i class="bi bi-arrow-repeat"></i> Memproses...';


                        kirimReview(
                            'setujui',
                            ''
                        );

                    }
                );

            }


            /* =========================================================
               KONFIRMASI TOLAK
            ========================================================= */

            const btnTolak =
                document.getElementById(
                    'btnSimpanTolakDokumen'
                );

            if (btnTolak) {

                btnTolak.addEventListener(
                    'click',
                    function() {

                        if (!reviewData) {
                            return;
                        }


                        const keterangan =
                            textarea.value.trim();


                        if (!keterangan) {

                            textarea.focus();

                            Swal.fire({
                                icon: 'warning',
                                title: 'Alasan Belum Diisi',
                                text: 'Silakan masukkan alasan penolakan terlebih dahulu.',
                                confirmButtonText: 'Mengerti',
                                confirmButtonColor: '#f0ad4e'
                            });

                            return;

                        }


                        if (
                            keterangan.length < 3
                        ) {

                            textarea.focus();

                            Swal.fire({
                                icon: 'warning',
                                title: 'Alasan Terlalu Singkat',
                                text: 'Silakan berikan alasan penolakan yang lebih jelas.',
                                confirmButtonText: 'Mengerti',
                                confirmButtonColor: '#f0ad4e'
                            });

                            return;

                        }


                        kirimReview(
                            'tolak',
                            keterangan
                        );

                    }
                );

            }


            /* =========================================================
               FUNGSI REVIEW
            ========================================================= */

            function kirimReview(
                aksi,
                keterangan
            ) {

                if (!reviewData) {
                    return;
                }


                const btnTolak =
                    document.getElementById(
                        'btnSimpanTolakDokumen'
                    );

                const btnSetuju =
                    document.getElementById(
                        'btnKonfirmasiSetujuiDokumen'
                    );


                if (aksi === 'tolak') {

                    btnTolak.disabled = true;

                    btnTolak.innerHTML =
                        '<i class="bi bi-arrow-repeat"></i> Memproses...';

                }


                if (aksi === 'setujui') {

                    btnSetuju.disabled = true;

                    btnSetuju.innerHTML =
                        '<i class="bi bi-arrow-repeat"></i> Memproses...';

                }


                /*
                |--------------------------------------------------------------------------
                | ENDPOINT APPROVAL YANG SUDAH DIPAKAI SISTEM LU
                |--------------------------------------------------------------------------
                */

                fetch(
                        `{{ url('/kinerjapegawai-skp/evaluasi') }}/${reviewData.id}/dokumen-review`, {
                            method: 'POST',

                            headers: {

                                'X-CSRF-TOKEN': csrfToken,

                                'Accept': 'application/json',

                                'Content-Type': 'application/json'

                            },

                            body: JSON.stringify({

                                jenis_dokumen: reviewData.jenis,

                                aksi: aksi,

                                keterangan: keterangan

                            })

                        }
                    )

                    .then(async response => {

                        let result = {};

                        try {

                            result =
                                await response.json();

                        } catch (error) {

                            result = {};

                        }


                        if (!response.ok) {

                            throw new Error(
                                result.message ||
                                'Proses review gagal.'
                            );

                        }


                        return result;

                    })

                    .then(function(result) {

                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: result.message || 'Dokumen berhasil diproses.',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#198754',
                            timer: 2500,
                            timerProgressBar: true
                        }).then(() => {

                            window.location.reload();

                        });

                    })

                    .catch(function(error) {

                        alert(
                            error.message ||
                            'Terjadi kesalahan.'
                        );


                        if (aksi === 'tolak') {

                            btnTolak.disabled = false;

                            btnTolak.innerHTML =
                                '<i class="bi bi-x-circle"></i> Tolak Dokumen';

                        }


                        if (aksi === 'setujui') {

                            btnSetuju.disabled = false;

                            btnSetuju.innerHTML =
                                '<i class="bi bi-check-circle"></i> Setujui Dokumen';

                        }

                    });

            }


            /* =========================================================
               DOWNLOAD ALL
            ========================================================= */

            const btnDownloadAll =
                document.getElementById(
                    'btnDownloadAll'
                );

            if (btnDownloadAll) {

                btnDownloadAll.addEventListener(
                    'click',
                    function() {

                        bootstrap.Modal
                            .getOrCreateInstance(
                                document.getElementById(
                                    'modalDownloadAll'
                                )
                            )
                            .show();

                    }
                );

            }


            /* =========================================================
               KONFIRMASI DOWNLOAD ALL
            ========================================================= */

            const btnKonfirmasiDownload =
                document.getElementById(
                    'btnKonfirmasiDownloadAll'
                );

            if (btnKonfirmasiDownload) {

                btnKonfirmasiDownload.addEventListener(
                    'click',
                    function() {

                        const btn =
                            this;


                        btn.disabled = true;

                        btn.innerHTML =
                            '<i class="bi bi-arrow-repeat"></i> Menyiapkan ZIP...';


                        const tahun =
                            {{ (int) $tahun }};


                        /*
                        |--------------------------------------------------------------------------
                        | Gunakan iframe agar response ZIP langsung menjadi download
                        |--------------------------------------------------------------------------
                        */

                        const iframe =
                            document.createElement(
                                'iframe'
                            );


                        iframe.style.display =
                            'none';


                        iframe.src =
                            '{{ route('admin.monitoring_skp.evaluasi.download-all') }}' +
                            '?tahun=' +
                            encodeURIComponent(tahun);


                        document.body.appendChild(
                            iframe
                        );


                        /*
                        |--------------------------------------------------------------------------
                        | Beri waktu controller menyelesaikan ZIP.
                        |--------------------------------------------------------------------------
                        */

                        setTimeout(
                            function() {

                                bootstrap.Modal
                                    .getOrCreateInstance(
                                        document.getElementById(
                                            'modalDownloadAll'
                                        )
                                    )
                                    .hide();


                                Swal.fire({
                                    icon: 'success',
                                    title: 'Backup Berhasil!',
                                    text: 'Seluruh dokumen SKP Evaluasi berhasil dikemas ke dalam file ZIP.',
                                    confirmButtonText: 'OK',
                                    confirmButtonColor: '#07549b',
                                    timer: 3000,
                                    timerProgressBar: true
                                }).then(() => {

                                    window.location.reload();

                                });

                            },
                            4000
                        );

                    }
                );

            }


            /* =========================================================
               RESET
            ========================================================= */

            const btnReset =
                document.getElementById(
                    'btnResetEvaluasi'
                );


            if (
                btnReset &&
                !btnReset.disabled
            ) {

                btnReset.addEventListener(
                    'click',
                    function() {

                        bootstrap.Modal
                            .getOrCreateInstance(
                                document.getElementById(
                                    'modalResetEvaluasi'
                                )
                            )
                            .show();

                    }
                );

            }


            /* =========================================================
               KONFIRMASI RESET
            ========================================================= */

            const btnKonfirmasiReset =
                document.getElementById(
                    'btnKonfirmasiReset'
                );


            if (btnKonfirmasiReset) {

                btnKonfirmasiReset.addEventListener(
                    'click',
                    function() {

                        const btn =
                            this;


                        btn.disabled = true;

                        btn.innerHTML =
                            '<i class="bi bi-arrow-repeat"></i> Mereset...';


                        fetch(
                                '{{ route('admin.monitoring_skp.evaluasi.reset') }}', {
                                    method: 'POST',

                                    headers: {

                                        'X-CSRF-TOKEN': csrfToken,

                                        'Accept': 'application/json',

                                        'Content-Type': 'application/json'

                                    },

                                    body: JSON.stringify({

                                        tahun: {{ (int) $tahun }}

                                    })

                                }
                            )

                            .then(async response => {

                                let result = {};

                                try {

                                    result =
                                        await response.json();

                                } catch (error) {

                                    result = {};

                                }


                                if (!response.ok) {

                                    throw new Error(
                                        result.message ||
                                        'Reset gagal dilakukan.'
                                    );

                                }


                                return result;

                            })

                            .then(function(result) {

                                Swal.fire({
                                    icon: 'success',
                                    title: 'Reset Berhasil!',
                                    text: result.message ||
                                        'Seluruh data SKP Evaluasi berhasil direset.',
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
                                    text: error.message ||
                                        'Terjadi kesalahan saat melakukan reset.',
                                    confirmButtonText: 'OK',
                                    confirmButtonColor: '#dc3545'
                                });

                                btn.disabled = false;

                                btn.innerHTML =
                                    '<i class="bi bi-trash"></i> Ya, Reset Data';

                            });

                    }
                );

            }

        });
    </script>
@endsection