@extends('layouts.app')

@section('content')

    @php
        /* Fallback agar Blade tidak error sebelum controller mengirim variabel hasil. */
        $penilaianAktif = $penilaianAktif ?? null;

        if (!$penilaianAktif && isset($riwayat) && $riwayat->isNotEmpty()) {
            $penilaianAktif = $riwayat->first(function ($item) use ($tahun, $triwulan, $suratKeputusanId) {
                return (int) $item->tahun === (int) $tahun &&
                    (int) $item->triwulan === (int) $triwulan &&
                    (!$suratKeputusanId || (int) $item->surat_keputusan_id === (int) $suratKeputusanId);
            });
        }

        /* Login belum dibuat, jadi default hak approval dibuat false. */
        $bolehMenyetujui = $bolehMenyetujui ?? false;
        $pegawaiTampil = $pegawaiTampil ?? collect();
    @endphp

    {{-- =============================================================
     MODAL KONFIRMASI PROSES TAHAP 3
 ============================================================== --}}
    <div id="processFinalModal"
        class="custom-modal-backdrop"
        onclick="closeProcessFinalModal(event)">

        <div class="custom-modal delete-modal"
            onclick="event.stopPropagation()">

            <div class="delete-modal-body">

                <div id="processFinalIcon" class="delete-icon"
                    style="background:#eef5ff; color:#07549b;">
                    <i class="bi bi-check2-circle"></i>
                </div>

                <h4 id="processFinalModalTitle"
                    class="delete-modal-title">
                    Proses Penilaian?
                </h4>

                <p id="processFinalModalText"
                    class="delete-modal-text">
                    Apakah Anda yakin ingin memproses Penilaian Tahap 1 ini?
                </p>

            </div>

            <div class="delete-modal-footer">

                <button type="button"
                    class="tahap1-btn tahap1-btn-secondary"
                    onclick="closeProcessFinalModal()">
                    Batal
                </button>

                <button id="processFinalConfirmButton"
                    type="button"
                    class="tahap1-btn tahap1-btn-primary"
                    onclick="submitProcessFinalForm()">
                    <i class="bi bi-check2-circle"></i>
                    Ya, Proses
                </button>

            </div>

        </div>

    </div>


    {{-- =========================================================
         PERSETUJUAN DI HALAMAN DEPAN
    ========================================================== --}}
    @if ($step == 1 && $bolehMenyetujui && isset($persetujuan) && $persetujuan->isNotEmpty())
        <div class="tahap1-card">

            <div class="tahap1-card-header history-header">

                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-shield-check"></i>
                    <span>
                        Persetujuan Penilaian Tahap 1
                    </span>
                </div>

                <span class="history-count">
                    {{ $persetujuan->count() }} Menunggu
                </span>

            </div>

            <div class="tahap1-card-body">

                <div class="tahap1-table-wrapper">
                    <table class="tahap1-table">
                        <thead>
                            <tr>
                                <th width="65">No</th>
                                <th width="130">Tahun</th>
                                <th width="140">Triwulan</th>
                                <th>Nomor SK</th>
                                <th>Dibuat Oleh</th>
                                <th width="180">Status</th>
                                <th width="190">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($persetujuan as $index => $item)
                                <tr class="table-center">
                                    <td class="table-center">
                                        {{ $index + 1 }}
                                    </td>

                                    <td class="table-center">
                                        {{ $item->tahun }}
                                    </td>

                                    <td class="table-center">
                                        Triwulan {{ $item->triwulan }}
                                    </td>

                                    <td>
                                        <span class="nama-pegawai">
                                            {{ $item->suratKeputusan?->no_sk ?? '-' }}
                                        </span>
                                    </td>

                                    <td>
                                        {{ $item->creator?->nama ?? '-' }}
                                    </td>

                                    <td class="table-center">
                                        <span class="status-badge status-menunggu">
                                            <i class="bi bi-hourglass-split"></i>
                                            Menunggu Persetujuan
                                        </span>
                                    </td>

                                    <td>
                                        <div class="history-actions">

                                            <form
                                                action="{{ route('admin.sipeter.penilaian.penilaian-tahap1.tolak', $item->id) }}"
                                                method="POST"
                                                >
                                                @csrf

                                                <button type="button"
                                                    class="tahap1-btn tahap1-btn-danger"
                                                    style="height:34px; padding:0 12px; font-size:12px;"
                                                    onclick="openApprovalModal('tolak', this.form)">
                                                    <i class="bi bi-x-lg"></i>
                                                    Tolak
                                                </button>
                                            </form>

                                            <form
                                                action="{{ route('admin.sipeter.penilaian.penilaian-tahap1.setujui', $item->id) }}"
                                                method="POST"
                                                >
                                                @csrf

                                                <button type="button"
                                                    class="tahap1-btn tahap1-btn-success"
                                                    style="height:34px; padding:0 12px; font-size:12px;"
                                                    onclick="openApprovalModal('setujui', this.form)">
                                                    <i class="bi bi-check-lg"></i>
                                                    Setujui
                                                </button>
                                            </form>

                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    @endif

    <style>
        /* =========================================================
               BASE
            ========================================================= */

        .tahap1-page {
            padding: 22px 5px 45px;
        }

        .tahap1-page * {
            box-sizing: border-box;
        }


        /* =========================================================
               HEADER
            ========================================================= */

        .tahap1-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 18px;
            flex-wrap: wrap;
            margin-bottom: 25px;
        }

        .tahap1-title {
            margin: 0 0 7px;
            color: #07549b !important;
            font-size: 25px;
            font-weight: 700;
        }

        .tahap1-subtitle {
            margin: 0;
            color: #7b8794 !important;
            font-size: 14px;
        }

        .tahap1-year-badge {
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

        .tahap1-year-badge i {
            color: #07549b;
        }


        /* =========================================================
               ALERT
            ========================================================= */

        .tahap1-alert {
            border: 0 !important;
            border-radius: 8px !important;
            padding: 13px 16px !important;
            margin-bottom: 20px;
            font-size: 14px;
        }


        /* =========================================================
               STEPPER
            ========================================================= */

        .tahap1-stepper {
            display: flex;
            align-items: center;
            width: 100%;
            margin-bottom: 24px;
            background: #ffffff;
            border: 1px solid #dce3eb;
            border-radius: 9px;
            padding: 18px 22px;
        }

        .tahap1-step {
            display: flex;
            align-items: center;
            gap: 10px;
            flex: 1;
            position: relative;
        }

        .tahap1-step:not(:last-child)::after {
            content: "";
            height: 1px;
            background: #dce3eb;
            flex: 1;
            margin: 0 18px;
        }

        .tahap1-step-number {
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

        .tahap1-step.active .tahap1-step-number,
        .tahap1-step.completed .tahap1-step-number {
            background: #07549b;
            border-color: #07549b;
            color: #ffffff;
        }

        .tahap1-step-title {
            color: #697687;
            font-size: 13px;
            font-weight: 600;
            white-space: nowrap;
        }

        .tahap1-step.active .tahap1-step-title,
        .tahap1-step.completed .tahap1-step-title {
            color: #07549b;
        }


        /* =========================================================
               CARD
            ========================================================= */

        .tahap1-card {
            background: #ffffff;
            border: 1px solid #dce3eb;
            border-radius: 9px;
            overflow: hidden;
            margin-bottom: 23px;
        }

        .tahap1-card-header {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 16px 21px;
            border-bottom: 1px solid #e8edf2;
            color: #07549b;
            font-size: 15px;
            font-weight: 700;
        }

        .tahap1-card-header i {
            font-size: 17px;
        }

        .tahap1-card-body {
            padding: 21px;
        }


        /* =========================================================
               FORM
            ========================================================= */

        .tahap1-form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 17px;
        }

        .tahap1-form-group label {
            display: block;
            margin-bottom: 7px;
            color: #465466;
            font-size: 13px;
            font-weight: 700;
        }

        .tahap1-form-control {
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

        .tahap1-form-control:focus {
            border-color: #07549b !important;
            box-shadow: 0 0 0 3px rgba(7, 84, 155, .10) !important;
        }


        /* =========================================================
               BUTTON
            ========================================================= */

        .tahap1-actions {
            display: flex;
            justify-content: flex-end;
            gap: 9px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .tahap1-btn {
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

        .tahap1-btn-primary {
            background: #07549b !important;
            color: #ffffff !important;
        }

        .tahap1-btn-primary:hover {
            background: #06457f !important;
            transform: translateY(-1px);
        }

        .tahap1-btn-secondary {
            background: #f1f4f7 !important;
            color: #526172 !important;
            border: 1px solid #dce3eb !important;
        }

        .tahap1-btn-secondary:hover {
            background: #e7edf3 !important;
        }

        .tahap1-btn-warning {
            background: #fff4db !important;
            color: #9b6800 !important;
            border: 1px solid #f1d99e !important;
        }

        .tahap1-btn-warning:hover {
            background: #ffeab8 !important;
        }

        .tahap1-btn-success {
            background: #198754 !important;
            color: #ffffff !important;
        }

        .tahap1-btn-success:hover {
            background: #157347 !important;
        }

        .tahap1-btn-danger {
            background: #dc3545 !important;
            color: #ffffff !important;
        }

        .tahap1-btn-danger:hover {
            background: #bb2d3b !important;
        }


        /* =========================================================
               INFO SK
            ========================================================= */

        .sk-info {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
            margin-bottom: 18px;
        }

        .sk-info-item {
            padding: 13px 15px;
            border: 1px solid #dfe6ed;
            border-radius: 8px;
            background: #f9fbfd;
        }

        .sk-info-label {
            color: #84909d;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .3px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .sk-info-value {
            color: #27323e;
            font-size: 13.5px;
            font-weight: 700;
            word-break: break-word;
        }


        /* =========================================================
               TABLE
            ========================================================= */

        .tahap1-table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        .tahap1-table {
            width: 100%;
            min-width: 700px;
            margin: 0;
            border-collapse: collapse;
        }

        .tahap1-table th,
        .tahap1-table td {
            border: 1px solid #e1e7ed !important;
            padding: 12px 14px;
            vertical-align: middle;
        }

        .tahap1-table thead th {
            background: #f7f9fb !important;
            color: #07549b !important;
            font-size: 12.5px;
            font-weight: 700;
            text-align: center;
        }

        .tahap1-table tbody td {
            height: 55px;
            background: #ffffff !important;
            color: #34404d !important;
            font-size: 13.5px;
        }

        .tahap1-table tbody tr:hover td {
            background: #f8fbff !important;
        }

        .nama-pegawai {
            color: #1d2733 !important;
            font-weight: 700;
        }

        .table-center {
            text-align: center;
        }


        /* =========================================================
               EXCLUSION
            ========================================================= */

        .exception-box {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            padding: 15px 17px;
            border: 1px solid #dce5ee;
            background: #f8fbff;
            border-radius: 8px;
            margin-bottom: 18px;
        }

        .exception-box-title {
            color: #273545;
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 3px;
        }

        .exception-box-text {
            color: #7c8997;
            font-size: 12.5px;
            margin: 0;
        }

        .btn-pengecualian {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            height: 40px;
            padding: 0 15px;
            border-radius: 7px;
            border: 1px solid #f1d99e;
            background: #fff4db;
            color: #9b6800;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: .18s ease;
        }

        .btn-pengecualian:hover {
            background: #ffeab8;
            transform: translateY(-1px);
        }


        /* =========================================================
               TOP 5 RESULT
            ========================================================= */

        .result-highlight {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 16px;
            background: #eef7ff;
            border: 1px solid #cde4f8;
            border-radius: 8px;
            margin-bottom: 18px;
        }

        .result-highlight-icon {
            width: 42px;
            height: 42px;
            min-width: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: #07549b;
            color: #ffffff;
            font-size: 18px;
        }

        .result-highlight-title {
            color: #07549b;
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 2px;
        }

        .result-highlight-text {
            color: #657586;
            font-size: 12.5px;
            margin: 0;
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

        .status-menunggu {
            background: #fff4d6;
            color: #996b00;
        }

        .status-disetujui {
            background: #e5f7ed;
            color: #16804d;
        }

        .status-ditolak {
            background: #fde9ec;
            color: #c52f42;
        }


        /* =========================================================
               HISTORY
            ========================================================= */

        .history-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .history-count {
            background: #edf5ff;
            color: #07549b;
            border: 1px solid #d1e5fa;
            padding: 5px 10px;
            border-radius: 6px;
            font-size: 11.5px;
            font-weight: 700;
        }

        .history-actions {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .history-action {
            width: 34px;
            height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 7px;
            border: 1px solid transparent;
            text-decoration: none;
            transition: .18s ease;
            cursor: pointer;
        }

        .history-action-view {
            background: #edf5ff;
            color: #07549b;
            border-color: #d4e6f8;
        }

        .history-action-view:hover {
            background: #07549b;
            color: #ffffff;
        }

        .history-action-delete {
            background: #fdecef;
            color: #dc3545;
            border-color: #f6d2d8;
        }

        .history-action-delete:hover {
            background: #dc3545;
            color: #ffffff;
        }


        /* =========================================================
               CUSTOM MODAL
            ========================================================= */

        .custom-modal-backdrop {
            position: fixed;
            inset: 0;
            z-index: 99990;
            background: rgba(13, 24, 38, .58);
            backdrop-filter: blur(3px);
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transition: .2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .custom-modal-backdrop.show {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
        }

        .custom-modal {
            width: 100%;
            max-width: 820px;
            max-height: calc(100vh - 40px);
            overflow: hidden;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 25px 80px rgba(0, 0, 0, .25);
            transform: translateY(18px) scale(.97);
            transition: .2s ease;
        }

        .custom-modal-backdrop.show .custom-modal {
            transform: translateY(0) scale(1);
        }

        .detail-modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            padding: 19px 22px;
            background: #07549b;
            color: #ffffff;
        }

        .detail-modal-title {
            display: flex;
            align-items: center;
            gap: 11px;
        }

        .detail-modal-title-icon {
            width: 38px;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 9px;
            background: rgba(255, 255, 255, .15);
            font-size: 17px;
        }

        .detail-modal-title h5 {
            margin: 0;
            font-size: 16px;
            font-weight: 700;
            color: #ffffff;
        }

        .detail-modal-title small {
            display: block;
            margin-top: 2px;
            color: rgba(255, 255, 255, .78);
            font-size: 11.5px;
        }

        .modal-close-custom {
            width: 34px;
            height: 34px;
            border: 0;
            background: rgba(255, 255, 255, .12);
            color: #ffffff;
            border-radius: 7px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-close-custom:hover {
            background: rgba(255, 255, 255, .22);
        }

        .detail-modal-body {
            max-height: calc(100vh - 160px);
            overflow-y: auto;
            padding: 21px;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
            margin-bottom: 20px;
        }

        .detail-item {
            border: 1px solid #e0e6ec;
            background: #f9fbfd;
            border-radius: 8px;
            padding: 12px 14px;
        }

        .detail-item-label {
            color: #8995a2;
            font-size: 10.5px;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: .35px;
            margin-bottom: 5px;
        }

        .detail-item-value {
            color: #273441;
            font-size: 13px;
            font-weight: 700;
        }

        .detail-section-title {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #07549b;
            font-size: 13.5px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .detail-result-table {
            width: 100%;
            border-collapse: collapse;
        }

        .detail-result-table th,
        .detail-result-table td {
            border: 1px solid #e1e7ed;
            padding: 10px 12px;
        }

        .detail-result-table th {
            background: #f6f9fc;
            color: #07549b;
            font-size: 11.5px;
            text-align: center;
        }

        .detail-result-table td {
            color: #3b4754;
            font-size: 12.5px;
        }

        .detail-result-table td:first-child {
            text-align: center;
            width: 80px;
            font-weight: 700;
        }

        /* =========================================================
               DELETE MODAL
            ========================================================= */

        .delete-modal {
            max-width: 430px;
        }

        .delete-modal-body {
            text-align: center;
            padding: 30px 27px 24px;
        }

        .delete-icon {
            width: 70px;
            height: 70px;
            margin: 0 auto 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: #fff0f2;
            color: #dc3545;
            font-size: 28px;
        }

        .delete-modal-title {
            margin: 0 0 8px;
            color: #27313b;
            font-size: 19px;
            font-weight: 700;
        }

        .delete-modal-text {
            margin: 0 auto;
            max-width: 340px;
            color: #7a8794;
            font-size: 13px;
            line-height: 1.65;
        }

        .delete-target {
            margin: 17px 0;
            padding: 12px 14px;
            background: #fff7f8;
            border: 1px solid #f5d5da;
            border-radius: 8px;
            color: #b02a37;
            font-size: 13px;
            font-weight: 700;
        }

        .delete-modal-footer {
            display: flex;
            justify-content: center;
            gap: 9px;
            padding: 0 24px 24px;
        }

        .delete-modal-footer .tahap1-btn {
            min-width: 120px;
        }


        /* =========================================================
               MODAL PROSES 5 PEGAWAI TERBAIK
            ========================================================= */

        #modalProsesTerbaik .modal-dialog {
            max-width: 600px;
        }

        #modalProsesTerbaik .modal-content {
            border: none;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 18px 55px rgba(15, 55, 95, .22);
            background: #ffffff;
        }

        #modalProsesTerbaik .proses-modal-header {
            position: relative;
            padding: 24px 26px 22px;
            background: linear-gradient(135deg, #07549b 0%, #0b6fbd 100%);
            color: #ffffff;
            border: none;
        }

        #modalProsesTerbaik .proses-icon-wrapper {
            width: 54px;
            height: 54px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 14px;
            background: rgba(255, 255, 255, .16);
            font-size: 25px;
            flex-shrink: 0;
        }

        #modalProsesTerbaik .proses-modal-title {
            margin: 0;
            font-size: 19px;
            font-weight: 700;
            color: #ffffff;
        }

        #modalProsesTerbaik .proses-modal-subtitle {
            margin: 4px 0 0;
            font-size: 13px;
            color: rgba(255, 255, 255, .82);
        }

        #modalProsesTerbaik .btn-close {
            position: absolute;
            right: 18px;
            top: 18px;
            opacity: .9;
        }

        #modalProsesTerbaik .modal-body {
            padding: 25px;
        }

        .proses-intro-box {
            display: flex;
            gap: 13px;
            padding: 15px 16px;
            margin-bottom: 20px;
            border-radius: 10px;
            background: #f4f8fd;
            border: 1px solid #dce8f5;
            color: #526171;
            font-size: 13.5px;
            line-height: 1.6;
        }

        .proses-intro-box i {
            color: #07549b;
            font-size: 20px;
            flex-shrink: 0;
        }

        .proses-info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            margin-bottom: 14px;
        }

        .proses-info-card {
            padding: 14px 15px;
            border-radius: 10px;
            background: #f8fafc;
            border: 1px solid #e3e9f0;
        }

        .proses-info-label {
            display: block;
            margin-bottom: 5px;
            font-size: 11.5px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .3px;
            color: #8a96a3;
        }

        .proses-info-value {
            display: block;
            font-size: 14px;
            font-weight: 700;
            color: #263746;
        }

        .proses-sk-card {
            padding: 15px 16px;
            margin-top: 12px;
            border-radius: 10px;
            background: #eef5ff;
            border: 1px solid #d3e4f8;
        }

        .proses-sk-card .sk-label {
            display: block;
            margin-bottom: 5px;
            font-size: 11.5px;
            font-weight: 600;
            text-transform: uppercase;
            color: #66809a;
        }

        .proses-sk-card .sk-value {
            display: block;
            font-size: 14px;
            font-weight: 700;
            color: #07549b;
        }

        .proses-sk-card .sk-perihal {
            display: block;
            margin-top: 4px;
            font-size: 12.5px;
            color: #667585;
        }

        .proses-warning {
            display: flex;
            gap: 11px;
            align-items: flex-start;
            padding: 14px 15px;
            margin-top: 15px;
            border-radius: 10px;
            background: #fff8e8;
            border: 1px solid #f3dfac;
            color: #7a6427;
            font-size: 13px;
            line-height: 1.55;
        }

        .proses-warning i {
            font-size: 18px;
            color: #d89c00;
            flex-shrink: 0;
        }

        #modalProsesTerbaik .modal-footer {
            padding: 17px 25px;
            border-top: 1px solid #e8edf2;
            background: #ffffff;
        }

        .btn-proses-batal {
            min-width: 100px;
            border-radius: 7px;
            font-weight: 600;
        }

        .btn-proses-confirm {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-width: 165px;
            height: 42px;
            border: none;
            border-radius: 7px;
            background: #198754;
            color: #ffffff;
            font-size: 13.5px;
            font-weight: 600;
            transition: all .2s ease;
        }

        .btn-proses-confirm:hover {
            background: #157347;
            color: #ffffff;
            transform: translateY(-1px);
            box-shadow: 0 5px 15px rgba(25, 135, 84, .2);
        }


        /* =========================================================
               BUTTON PROSES
            ========================================================= */

        .btn-proses-terbaik {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-width: 220px;
            height: 44px;
            padding: 0 18px;
            border: none;
            border-radius: 7px;
            background: #198754;
            color: #ffffff;
            font-size: 13.5px;
            font-weight: 600;
            cursor: pointer;
            transition: all .2s ease;
        }

        .btn-proses-terbaik:hover {
            background: #157347;
            color: #ffffff;
            transform: translateY(-1px);
            box-shadow: 0 5px 15px rgba(25, 135, 84, .18);
        }

        .btn-proses-terbaik i:first-child {
            font-size: 15px;
        }


        /* =========================================================
               RESPONSIVE
            ========================================================= */

        @media (max-width: 768px) {

            .tahap1-page {
                padding: 15px 0 35px;
            }

            .tahap1-form-grid {
                grid-template-columns: 1fr;
            }

            .sk-info {
                grid-template-columns: 1fr;
            }

            .tahap1-stepper {
                overflow-x: auto;
                gap: 5px;
            }

            .tahap1-step {
                min-width: 170px;
            }

            .tahap1-step:not(:last-child)::after {
                margin: 0 10px;
            }

            .exception-box {
                flex-direction: column;
                align-items: flex-start;
            }

            .detail-grid {
                grid-template-columns: 1fr;
            }

            .proses-info-grid {
                grid-template-columns: 1fr;
            }

            .custom-modal-backdrop {
                padding: 10px;
            }

            .custom-modal {
                max-height: calc(100vh - 20px);
            }
        }


        /* =========================================================
               DARK MODE
            ========================================================= */

        body.theme-dark .tahap1-title,
        html.theme-dark .tahap1-title {
            color: #8db9ff !important;
        }

        body.theme-dark .tahap1-subtitle,
        html.theme-dark .tahap1-subtitle {
            color: #929bad !important;
        }

        body.theme-dark .tahap1-year-badge,
        html.theme-dark .tahap1-year-badge,
        body.theme-dark .tahap1-stepper,
        html.theme-dark .tahap1-stepper,
        body.theme-dark .tahap1-card,
        html.theme-dark .tahap1-card {
            background: #1f1e2e !important;
            border-color: #353448 !important;
        }

        body.theme-dark .tahap1-card-header,
        html.theme-dark .tahap1-card-header {
            border-color: #353448 !important;
            color: #8db9ff !important;
        }

        body.theme-dark .tahap1-form-group label,
        html.theme-dark .tahap1-form-group label {
            color: #c3c8d5 !important;
        }

        body.theme-dark .tahap1-form-control,
        html.theme-dark .tahap1-form-control {
            background: #242334 !important;
            border-color: #3c3b50 !important;
            color: #d4d8e2 !important;
        }

        body.theme-dark .tahap1-form-control:focus,
        html.theme-dark .tahap1-form-control:focus {
            border-color: #8db9ff !important;
        }

        body.theme-dark .tahap1-step-title,
        html.theme-dark .tahap1-step-title {
            color: #929bad;
        }

        body.theme-dark .tahap1-step.active .tahap1-step-title,
        html.theme-dark .tahap1-step.active .tahap1-step-title,
        body.theme-dark .tahap1-step.completed .tahap1-step-title,
        html.theme-dark .tahap1-step.completed .tahap1-step-title {
            color: #8db9ff;
        }

        body.theme-dark .tahap1-step:not(:last-child)::after,
        html.theme-dark .tahap1-step:not(:last-child)::after {
            background: #353448;
        }

        body.theme-dark .tahap1-table th,
        body.theme-dark .tahap1-table td,
        html.theme-dark .tahap1-table th,
        html.theme-dark .tahap1-table td {
            border-color: #3a394e !important;
        }

        body.theme-dark .tahap1-table thead th,
        html.theme-dark .tahap1-table thead th {
            background: #242334 !important;
            color: #a9c6ff !important;
        }

        body.theme-dark .tahap1-table tbody td,
        html.theme-dark .tahap1-table tbody td {
            background: #1c1b2b !important;
            color: #d4d8e2 !important;
        }

        body.theme-dark .tahap1-table tbody tr:hover td,
        html.theme-dark .tahap1-table tbody tr:hover td {
            background: #23223a !important;
        }

        body.theme-dark .nama-pegawai,
        html.theme-dark .nama-pegawai {
            color: #f0f2f7 !important;
        }

        body.theme-dark .exception-box,
        html.theme-dark .exception-box,
        body.theme-dark .result-highlight,
        html.theme-dark .result-highlight,
        body.theme-dark .sk-info-item,
        html.theme-dark .sk-info-item {
            background: #242334 !important;
            border-color: #3c3b50 !important;
        }

        body.theme-dark .exception-box-title,
        html.theme-dark .exception-box-title,
        body.theme-dark .sk-info-value,
        html.theme-dark .sk-info-value {
            color: #e2e5ec !important;
        }

        body.theme-dark .exception-box-text,
        html.theme-dark .exception-box-text,
        body.theme-dark .result-highlight-text,
        html.theme-dark .result-highlight-text,
        body.theme-dark .sk-info-label,
        html.theme-dark .sk-info-label {
            color: #929bad !important;
        }

        body.theme-dark .result-highlight-title,
        html.theme-dark .result-highlight-title {
            color: #8db9ff !important;
        }

        body.theme-dark .history-count,
        html.theme-dark .history-count {
            background: #242334;
            border-color: #3c3b50;
            color: #8db9ff;
        }

        body.theme-dark .custom-modal,
        html.theme-dark .custom-modal,
        body.theme-dark #modalProsesTerbaik .modal-content,
        html.theme-dark #modalProsesTerbaik .modal-content {
            background: #1f1e2e !important;
        }

        body.theme-dark .detail-modal-body,
        html.theme-dark .detail-modal-body,
        body.theme-dark #modalProsesTerbaik .modal-body,
        html.theme-dark #modalProsesTerbaik .modal-body {
            background: #1f1e2e !important;
        }

        body.theme-dark .detail-item,
        html.theme-dark .detail-item,
        body.theme-dark .proses-info-card,
        html.theme-dark .proses-info-card {
            background: #242334 !important;
            border-color: #3c3b50 !important;
        }

        body.theme-dark .detail-item-label,
        html.theme-dark .detail-item-label,
        body.theme-dark .proses-info-label,
        html.theme-dark .proses-info-label {
            color: #929bad !important;
        }

        body.theme-dark .detail-item-value,
        html.theme-dark .detail-item-value,
        body.theme-dark .proses-info-value,
        html.theme-dark .proses-info-value {
            color: #e2e5ec !important;
        }

        body.theme-dark .detail-section-title,
        html.theme-dark .detail-section-title {
            color: #8db9ff !important;
        }

        body.theme-dark .detail-result-table th,
        html.theme-dark .detail-result-table th {
            background: #242334 !important;
            color: #a9c6ff !important;
            border-color: #3a394e;
        }

        body.theme-dark .detail-result-table td,
        html.theme-dark .detail-result-table td {
            background: #1c1b2b !important;
            color: #d4d8e2 !important;
            border-color: #3a394e;
        }

        body.theme-dark .delete-modal-body,
        html.theme-dark .delete-modal-body {
            background: #1f1e2e;
        }

        body.theme-dark .delete-modal-title,
        html.theme-dark .delete-modal-title {
            color: #f0f2f7;
        }

        body.theme-dark .delete-modal-text,
        html.theme-dark .delete-modal-text {
            color: #929bad;
        }

        body.theme-dark .delete-target,
        html.theme-dark .delete-target {
            background: #332329;
            border-color: #59333b;
            color: #ff8a95;
        }

        body.theme-dark #modalProsesTerbaik .modal-footer,
        html.theme-dark #modalProsesTerbaik .modal-footer {
            background: #1f1e2e !important;
            border-top-color: #353448 !important;
        }

        body.theme-dark .proses-intro-box,
        html.theme-dark .proses-intro-box {
            background: #242334 !important;
            border-color: #3c3b50 !important;
            color: #b8bfcc !important;
        }

        body.theme-dark .proses-intro-box i,
        html.theme-dark .proses-intro-box i {
            color: #8db9ff !important;
        }

        body.theme-dark .proses-sk-card,
        html.theme-dark .proses-sk-card {
            background: #182f4a !important;
            border-color: #284f78 !important;
        }

        body.theme-dark .proses-sk-card .sk-label {
            color: #91a6bb !important;
        }

        body.theme-dark .proses-sk-card .sk-value {
            color: #8db9ff !important;
        }

        body.theme-dark .proses-sk-card .sk-perihal {
            color: #aeb8c8 !important;
        }

        body.theme-dark .proses-warning,
        html.theme-dark .proses-warning {
            background: #3b321e !important;
            border-color: #63532a !important;
            color: #d7c37e !important;
        }

        body.theme-dark .proses-warning i,
        html.theme-dark .proses-warning i {
            color: #f1c75b !important;
        }

        /* =========================================================
               STEP 3 WORKFLOW
            ========================================================= */

        .tahap1-step3-actions {
            border-top: 1px solid #e8edf2;
            margin-top: 20px;
            padding-top: 20px;
        }

        .tahap1-approval-divider {
            margin: 24px 0 14px;
            padding-top: 18px;
            border-top: 1px solid #e8edf2;
        }

        .tahap1-approval-label {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            color: #07549b;
            font-size: 13px;
            font-weight: 700;
        }

        .tahap1-approval-actions {
            margin-top: 10px;
            padding-top: 0;
        }
    </style>


    <div class="tahap1-page">

        {{-- =========================================================
         HEADER
    ========================================================== --}}

        <div class="tahap1-header">

            <div>
                <h3 class="tahap1-title">
                    Penilaian Tahap 1
                </h3>

                <p class="tahap1-subtitle">
                    Proses pemeringkatan 5 pegawai terbaik berdasarkan
                    penilaian kinerja periode yang dipilih.
                </p>
            </div>

            <div class="tahap1-year-badge">
                <i class="bi bi-calendar3"></i>
                Tahun {{ $tahun }}
            </div>

        </div>


        {{-- =========================================================
         ALERT
    ========================================================== --}}

        @if (session('success'))
            <div class="alert alert-success tahap1-alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger tahap1-alert">
                <i class="bi bi-exclamation-circle-fill me-2"></i>
                {{ session('error') }}
            </div>
        @endif


        {{-- =========================================================
         STEPPER
    ========================================================== --}}

        <div class="tahap1-stepper">

            <div class="tahap1-step {{ $step >= 1 ? 'active' : '' }}">
                <div class="tahap1-step-number">1</div>

                <div class="tahap1-step-title">
                    Periode
                </div>
            </div>


            <div class="tahap1-step {{ $step >= 2 ? 'active' : '' }}">
                <div class="tahap1-step-number">2</div>

                <div class="tahap1-step-title">
                    Surat Keputusan & Pegawai
                </div>
            </div>


            <div class="tahap1-step {{ $step >= 3 ? 'active' : '' }}">
                <div class="tahap1-step-number">3</div>

                <div class="tahap1-step-title">
                    Hasil & Persetujuan
                </div>
            </div>

        </div>


        {{-- =========================================================
         STEP 1
    ========================================================== --}}

        @if ($step == 1)
            <div class="tahap1-card">

                <div class="tahap1-card-header">
                    <i class="bi bi-calendar-range"></i>

                    <span>
                        Pilih Periode Penilaian
                    </span>
                </div>


                <div class="tahap1-card-body">

                    <form action="{{ route('admin.sipeter.penilaian.penilaian-tahap1.proses') }}" method="POST">

                        @csrf

                        <div class="tahap1-form-grid">

                            <div class="tahap1-form-group">

                                <label for="tahun">
                                    Tahun
                                </label>

                                <select name="tahun" id="tahun" class="tahap1-form-control" required>

                                    @foreach ($tahunOptions as $itemTahun)
                                        <option value="{{ $itemTahun }}"
                                            {{ (int) $tahun === (int) $itemTahun ? 'selected' : '' }}>

                                            {{ $itemTahun }}

                                        </option>
                                    @endforeach

                                </select>

                            </div>


                            <div class="tahap1-form-group">

                                <label for="triwulan">
                                    Triwulan
                                </label>

                                <select name="triwulan" id="triwulan" class="tahap1-form-control" required>

                                    @for ($i = 1; $i <= 4; $i++)
                                        <option value="{{ $i }}"
                                            {{ (int) $triwulan === $i ? 'selected' : '' }}>

                                            Triwulan {{ $i }}

                                        </option>
                                    @endfor

                                </select>

                            </div>

                        </div>


                        <div class="tahap1-actions">

                            <button type="submit" class="tahap1-btn tahap1-btn-primary">

                                Lanjut

                                <i class="bi bi-arrow-right"></i>

                            </button>

                        </div>

                    </form>

                </div>

            </div>
        @endif


        {{-- =========================================================
         STEP 2
    ========================================================== --}}

        @if ($step == 2)

            @if (!$suratKeputusanId)

                <div class="tahap1-card">

                    <div class="tahap1-card-header">

                        <i class="bi bi-file-earmark-text"></i>

                        <span>
                            Pilih Surat Keputusan Panitia
                        </span>

                    </div>


                    <div class="tahap1-card-body">

                        <form action="{{ route('admin.sipeter.penilaian.penilaian-tahap1.proses') }}" method="POST">

                            @csrf

                            <input type="hidden" name="tahun" value="{{ $tahun }}">

                            <input type="hidden" name="triwulan" value="{{ $triwulan }}">


                            <div class="tahap1-form-group">

                                <label for="surat_keputusan_id">
                                    Surat Keputusan Panitia
                                </label>

                                <select name="surat_keputusan_id" id="surat_keputusan_id" class="tahap1-form-control"
                                    required>

                                    <option selected disabled>

                                        -- Pilih Surat Keputusan --

                                    </option>

                                    @foreach ($daftarSuratKeputusan as $sk)
                                        <option value="{{ $sk->id }}">

                                            {{ $sk->no_sk }}

                                            @if ($sk->perihal)
                                                — {{ $sk->perihal }}
                                            @endif

                                        </option>
                                    @endforeach

                                </select>

                            </div>


                            <div class="tahap1-actions">

                                <a href="{{ route('admin.sipeter.penilaian.penilaian-tahap1.index', [
                                    'step' => 1,
                                    'tahun' => $tahun,
                                    'triwulan' => $triwulan,
                                ]) }}"
                                    class="tahap1-btn tahap1-btn-secondary">

                                    <i class="bi bi-arrow-left"></i>

                                    Kembali

                                </a>


                                <button type="submit" class="tahap1-btn tahap1-btn-primary">

                                    Lanjut

                                    <i class="bi bi-arrow-right"></i>

                                </button>

                            </div>

                        </form>

                    </div>

                </div>
            @else
                @if ($suratKeputusan)
                    <div class="tahap1-card">

                        <div class="tahap1-card-header">

                            <i class="bi bi-file-earmark-check"></i>

                            <span>
                                Surat Keputusan Terpilih
                            </span>

                        </div>


                        <div class="tahap1-card-body">

                            <div class="sk-info">

                                <div class="sk-info-item">

                                    <div class="sk-info-label">
                                        Nomor SK
                                    </div>

                                    <div class="sk-info-value">
                                        {{ $suratKeputusan->no_sk }}
                                    </div>

                                </div>


                                <div class="sk-info-item">

                                    <div class="sk-info-label">
                                        Tanggal
                                    </div>

                                    <div class="sk-info-value">
                                        {{ optional($suratKeputusan->tanggal_sk)->format('d/m/Y') }}
                                    </div>

                                </div>


                                <div class="sk-info-item">

                                    <div class="sk-info-label">
                                        Perihal
                                    </div>

                                    <div class="sk-info-value">
                                        {{ $suratKeputusan->perihal }}
                                    </div>

                                </div>

                            </div>


                            {{-- PENGECUALIAN --}}

                            <div class="exception-box">

                                <div>

                                    <div class="exception-box-title">
                                        Pengaturan Pengecualian
                                    </div>

                                    <p class="exception-box-text">
                                        Pegawai yang dikecualikan tidak akan
                                        ikut dalam proses 5 pegawai terbaik.
                                    </p>

                                </div>


                                <button type="button" class="btn-pengecualian" data-bs-toggle="modal"
                                    data-bs-target="#modalPengecualian">

                                    <i class="bi bi-person-x"></i>

                                    Atur Pengecualian

                                </button>

                            </div>


                            {{-- TABLE PEGAWAI --}}

                            <div class="tahap1-table-wrapper">

                                <table class="tahap1-table">

                                    <thead>

                                        <tr>

                                            <th width="80">
                                                No
                                            </th>

                                            <th>
                                                Nama Pegawai
                                            </th>

                                            <th width="180">
                                                Status
                                            </th>

                                        </tr>

                                    </thead>


                                    <tbody>

                                        @forelse ($pegawaiTampil as $index => $user)
                                            <tr>

                                                <td class="table-center">
                                                    {{ $index + 1 }}
                                                </td>


                                                <td>

                                                    <span class="nama-pegawai">
                                                        {{ $user->nama }}
                                                    </span>

                                                </td>


                                                <td class="table-center">

                                                    @if ($pengecualianIds->contains((int) $user->id))
                                                        <span class="status-badge status-ditolak">

                                                            <i class="bi bi-x-circle"></i>

                                                            Dikecualikan

                                                        </span>
                                                    @else
                                                        <span class="status-badge status-disetujui">

                                                            <i class="bi bi-check-circle"></i>

                                                            Diikutsertakan

                                                        </span>
                                                    @endif

                                                </td>

                                            </tr>

                                        @empty

                                            <tr>

                                                <td colspan="3" class="text-center py-4">

                                                    Belum ada pegawai yang dikecualikan.

                                                    <div class="mt-1" style="font-size:12px;color:#a0a8b2;">
                                                        Atur pengecualian melalui tombol di atas jika diperlukan.
                                                    </div>

                                                </td>

                                            </tr>
                                        @endforelse

                                    </tbody>

                                </table>

                            </div>


                            <div class="tahap1-actions">

                                <a href="{{ route('admin.sipeter.penilaian.penilaian-tahap1.index', [
                                    'step' => 1,
                                    'tahun' => $tahun,
                                    'triwulan' => $triwulan,
                                ]) }}"
                                    class="tahap1-btn tahap1-btn-secondary">

                                    <i class="bi bi-arrow-left"></i>

                                    Ganti Periode

                                </a>


                                <button type="button" class="btn-proses-terbaik" data-bs-toggle="modal"
                                    data-bs-target="#modalProsesTerbaik">

                                    <i class="bi bi-trophy-fill"></i>

                                    Proses 5 Pegawai Terbaik

                                </button>

                            </div>

                        </div>

                    </div>
                @endif

            @endif

        @endif


        {{-- =========================================================
         STEP 3
    ========================================================== --}}

        @if ($step == 3 && $penilaianAktif)
            <div class="tahap1-card">

                <div class="tahap1-card-header">

                    <i class="bi bi-trophy"></i>

                    <span>
                        Hasil 5 Pegawai Terbaik
                    </span>

                </div>


                <div class="tahap1-card-body">

                    <div class="result-highlight">

                        <div class="result-highlight-icon">
                            <i class="bi bi-award"></i>
                        </div>

                        <div>

                            <div class="result-highlight-title">
                                Hasil Penilaian Tahap 1
                            </div>

                            <p class="result-highlight-text">

                                Hasil berdasarkan nilai terbaik pada
                                {{ $penilaianAktif->tahun }}
                                — Triwulan {{ $penilaianAktif->triwulan }}.

                            </p>

                        </div>

                    </div>


                    <div class="sk-info">

                        <div class="sk-info-item">

                            <div class="sk-info-label">
                                Nomor SK
                            </div>

                            <div class="sk-info-value">
                                {{ $penilaianAktif->suratKeputusan?->no_sk ?? '-' }}
                            </div>

                        </div>


                        <div class="sk-info-item">

                            <div class="sk-info-label">
                                Status
                            </div>

                            <div class="sk-info-value">

                                @if ($penilaianAktif->status === 'draft')
                                    <span class="status-badge status-menunggu">

                                        <i class="bi bi-pencil-square"></i>

                                        Belum Diproses

                                    </span>
                                @elseif ($penilaianAktif->status === 'menunggu_persetujuan')
                                    <span class="status-badge status-menunggu">

                                        <i class="bi bi-hourglass-split"></i>

                                        Menunggu Persetujuan

                                    </span>
                                @elseif ($penilaianAktif->status === 'disetujui')
                                    <span class="status-badge status-disetujui">

                                        <i class="bi bi-check-circle-fill"></i>

                                        Disetujui

                                    </span>
                                @else
                                    <span class="status-badge status-ditolak">

                                        <i class="bi bi-x-circle-fill"></i>

                                        Ditolak

                                    </span>
                                @endif

                            </div>

                        </div>


                        <div class="sk-info-item">

                            <div class="sk-info-label">
                                Dibuat Oleh
                            </div>

                            <div class="sk-info-value">
                                {{ $penilaianAktif->creator?->nama ?? '-' }}
                            </div>

                        </div>

                    </div>


                    <div class="tahap1-table-wrapper">

                        <table class="tahap1-table">

                            <thead>

                                <tr>

                                    <th width="100">
                                        No
                                    </th>

                                    <th>
                                        Nama Pegawai
                                    </th>

                                    </tr>

                            </thead>


                            <tbody>

                                @forelse ($penilaianAktif->details->sortBy(fn($d) => mb_strtolower($d->user?->nama ?? ''))->values() as $detail)
                                    <tr>

                                        <td class="table-center">

                                            {{ $loop->iteration }}

                                        </td>


                                        <td>

                                            <span class="nama-pegawai">

                                                {{ $detail->user?->nama ?? '-' }}

                                            </span>

                                        </td>


                                        </tr>

                                @empty

                                    <tr>

                                        <td colspan="2" class="text-center py-4">

                                            Belum ada hasil.

                                        </td>

                                    </tr>
                                @endforelse

                            </tbody>

                        </table>

                    </div>


                    {{-- AKSI TAHAP 3 --}}

                    @if ($penilaianAktif->status === 'draft')
                        <div class="tahap1-actions tahap1-step3-actions">

                            <a href="{{ route('admin.sipeter.penilaian.penilaian-tahap1.index', [
                                'step' => 2,
                                'tahun' => $tahun,
                                'triwulan' => $triwulan,
                                'surat_keputusan_id' => $suratKeputusanId,
                            ]) }}"
                                class="tahap1-btn tahap1-btn-secondary">

                                <i class="bi bi-arrow-left"></i>

                                Kembali ke Tahap 2

                            </a>


                            <form action="{{ route('admin.sipeter.penilaian.penilaian-tahap1.proses-final') }}"
                                method="POST" class="m-0">

                                @csrf

                                <input type="hidden" name="penilaian_tahap1_id" value="{{ $penilaianAktif->id }}">
                                <input type="hidden" name="tahun" value="{{ $tahun }}">
                                <input type="hidden" name="triwulan" value="{{ $triwulan }}">
                                <input type="hidden" name="surat_keputusan_id" value="{{ $suratKeputusanId }}">

                                <button type="button"
                                    class="tahap1-btn tahap1-btn-primary"
                                    onclick="openProcessFinalModal(this.form)">

                                    <i class="bi bi-check2-circle"></i>

                                    Proses

                                </button>

                            </form>

                        </div>
                    @endif


                    {{-- APPROVAL --}}

                    @if ($bolehMenyetujui && $penilaianAktif->status === 'menunggu_persetujuan')
                        <div class="tahap1-approval-divider">

                            <div class="tahap1-approval-label">
                                <i class="bi bi-shield-check"></i>
                                Persetujuan Penilaian Tahap 1
                            </div>

                        </div>

                        <div class="tahap1-actions tahap1-approval-actions">

                            <form
                                action="{{ route('admin.sipeter.penilaian.penilaian-tahap1.tolak', $penilaianAktif->id) }}"
                                method="POST" >

                                @csrf

                                <button type="button"
                                    class="tahap1-btn tahap1-btn-danger"
                                    onclick="openApprovalModal('tolak', this.form)">

                                    <i class="bi bi-x-lg"></i>

                                    Tolak

                                </button>

                            </form>


                            <form
                                action="{{ route('admin.sipeter.penilaian.penilaian-tahap1.setujui', $penilaianAktif->id) }}"
                                method="POST" >

                                @csrf

                                <button type="button"
                                    class="tahap1-btn tahap1-btn-success"
                                    onclick="openApprovalModal('setujui', this.form)">

                                    <i class="bi bi-check-lg"></i>

                                    Setujui

                                </button>

                            </form>

                        </div>
                    @endif

                </div>

            </div>
        @endif


        {{-- =========================================================
         RIWAYAT
    ========================================================== --}}

        <div class="tahap1-card">

            <div class="tahap1-card-header history-header">

                <div class="d-flex align-items-center gap-2">

                    <i class="bi bi-clock-history"></i>

                    <span>
                        Riwayat Penilaian Tahap 1
                    </span>

                </div>


                <span class="history-count">

                    {{ $riwayat->count() }}

                    Riwayat

                </span>

            </div>


            <div class="tahap1-card-body p-0">

                <div class="tahap1-table-wrapper">

                    <table class="tahap1-table">

                        <thead>

                            <tr>

                                <th width="65">
                                    No
                                </th>

                                <th>
                                    Tahun
                                </th>

                                <th>
                                    Triwulan
                                </th>

                                <th>
                                    Surat Keputusan
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
                                    Dibuat Oleh
                                </th>

                                <th width="130">
                                    Aksi
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse ($riwayat as $index => $history)
                                <tr class="table-center">

                                    <td class="table-center">
                                        {{ $index + 1 }}
                                    </td>


                                    <td class="table-center">
                                        {{ $history->tahun }}
                                    </td>


                                    <td class="table-center">
                                        Triwulan {{ $history->triwulan }}
                                    </td>


                                    <td>

                                        <strong>
                                            {{ $history->suratKeputusan?->no_sk ?? '-' }}
                                        </strong>

                                        @if ($history->suratKeputusan?->perihal)
                                            <div class="text-muted" style="font-size:11px;">

                                                {{ $history->suratKeputusan->perihal }}

                                            </div>
                                        @endif

                                    </td>


                                    <td class="table-center">

                                        @if ($history->status === 'menunggu_persetujuan')
                                            <span class="status-badge status-menunggu">

                                                <i class="bi bi-hourglass-split"></i>

                                                Menunggu

                                            </span>
                                        @elseif ($history->status === 'disetujui')
                                            <span class="status-badge status-disetujui">

                                                <i class="bi bi-check-circle-fill"></i>

                                                Disetujui

                                            </span>
                                        @elseif ($history->status === 'ditolak')
                                            <span class="status-badge status-ditolak">

                                                <i class="bi bi-x-circle-fill"></i>

                                                Ditolak

                                            </span>
                                        @else
                                            <span class="status-badge status-menunggu">

                                                {{ ucfirst($history->status) }}

                                            </span>
                                        @endif

                                    </td>


                                    <td>
                                        {{ $history->creator?->nama ?? '-' }}
                                    </td>


                                    <td>

                                        <div class="history-actions">

                                            {{-- LIHAT --}}

                                            <button type="button" class="history-action history-action-view"
                                                title="Lihat" onclick="openDetailModal({{ $history->id }})">

                                                <i class="bi bi-eye"></i>

                                            </button>


                                            {{-- HAPUS --}}

                                            <button type="button" class="history-action history-action-delete"
                                                title="Hapus"
                                                onclick="openDeleteModal(
                                                    {{ $history->id }},
                                                    @js('Penilaian Tahap 1 ' . $history->tahun . ' - Triwulan ' . $history->triwulan)
                                                )">

                                                <i class="bi bi-trash3"></i>

                                            </button>

                                            {{-- @if ($bolehMenyetujui)
                                                <button type="button" class="history-action history-action-delete"
                                                    title="Hapus"
                                                    onclick="openDeleteModal(
                                                    {{ $history->id }},
                                                    @js('Penilaian Tahap 1 ' . $history->tahun . ' - Triwulan ' . $history->triwulan)
                                                )">

                                                    <i class="bi bi-trash3"></i>

                                                </button>
                                            @endif --}}

                                        </div>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="7" class="text-center py-5">

                                        <div style="color:#8995a2;font-size:14px;">

                                            <i class="bi bi-clock-history"
                                                style="
                                                display:block;
                                                font-size:30px;
                                                margin-bottom:8px;
                                            ">
                                            </i>

                                            Belum ada riwayat Penilaian Tahap 1.

                                        </div>

                                    </td>

                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>


    {{-- =============================================================
     MODAL PENGECUALIAN
============================================================== --}}

    @if ($step == 2 && $suratKeputusanId)
        <div class="modal fade" id="modalPengecualian" tabindex="-1" aria-hidden="true">

            <div class="modal-dialog modal-dialog-centered modal-lg">

                <div class="modal-content">

                    <div class="modal-header">

                        <h5 class="modal-title">

                            <i class="bi bi-slash-circle me-2"></i>

                            Atur Pengecualian Pegawai

                        </h5>

                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                        </button>

                    </div>


                    <form action="{{ route('admin.sipeter.penilaian.penilaian-tahap1.pengecualian.store') }}"
                        method="POST">

                        @csrf

                        <input type="hidden" name="tahun" value="{{ $tahun }}">

                        <input type="hidden" name="triwulan" value="{{ $triwulan }}">

                        <input type="hidden" name="surat_keputusan_id" value="{{ $suratKeputusanId }}">


                        <div class="modal-body">

                            <p class="text-muted">

                                Centang pegawai yang tidak akan
                                diikutsertakan dalam proses 5 pegawai terbaik.

                            </p>


                            <div class="list-group">

                                @foreach ($pegawai as $user)
                                    <label class="list-group-item d-flex align-items-center">

                                        <input class="form-check-input me-3" type="checkbox" name="pengecualian[]"
                                            value="{{ $user->id }}"
                                            {{ $pengecualianIds->contains((int) $user->id) ? 'checked' : '' }}>


                                        <div>

                                            <strong>
                                                {{ $user->nama }}
                                            </strong>

                                        </div>

                                    </label>
                                @endforeach

                            </div>

                        </div>


                        <div class="modal-footer">

                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">

                                Batal

                            </button>


                            <button type="submit" class="btn btn-primary">

                                <i class="bi bi-check-lg me-1"></i>

                                Simpan Pengecualian

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>
    @endif


    {{-- =============================================================
     MODAL DETAIL / LIHAT
============================================================== --}}

    <div id="detailModal" class="custom-modal-backdrop" onclick="closeDetailModal(event)">

        <div class="custom-modal" onclick="event.stopPropagation()">

            <div class="detail-modal-header">

                <div class="detail-modal-title">

                    <div class="detail-modal-title-icon">

                        <i class="bi bi-eye"></i>

                    </div>

                    <div>

                        <h5>
                            Detail Penilaian Tahap 1
                        </h5>

                        <small>
                            Informasi lengkap hasil penilaian
                        </small>

                    </div>

                </div>


                <button type="button" class="modal-close-custom" onclick="closeDetailModal()">

                    <i class="bi bi-x-lg"></i>

                </button>

            </div>


            <div class="detail-modal-body">

                <div id="detailLoading" class="text-center py-5">

                    <div class="spinner-border text-primary" role="status">
                    </div>

                    <div class="mt-3 text-muted" style="font-size:13px;">

                        Memuat data...

                    </div>

                </div>


                <div id="detailContent" style="display:none;">

                    <div class="detail-grid">

                        <div class="detail-item">

                            <div class="detail-item-label">
                                Tahun
                            </div>

                            <div class="detail-item-value" id="detailTahun">
                                -
                            </div>

                        </div>


                        <div class="detail-item">

                            <div class="detail-item-label">
                                Triwulan
                            </div>

                            <div class="detail-item-value" id="detailTriwulan">
                                -
                            </div>

                        </div>


                        <div class="detail-item">

                            <div class="detail-item-label">
                                Nomor SK
                            </div>

                            <div class="detail-item-value" id="detailNoSk">
                                -
                            </div>

                        </div>


                        <div class="detail-item">

                            <div class="detail-item-label">
                                Tanggal SK
                            </div>

                            <div class="detail-item-value" id="detailTanggalSk">
                                -
                            </div>

                        </div>


                        <div class="detail-item">

                            <div class="detail-item-label">
                                Perihal
                            </div>

                            <div class="detail-item-value" id="detailPerihal">
                                -
                            </div>

                        </div>


                        <div class="detail-item">

                            <div class="detail-item-label">
                                Status
                            </div>

                            <div class="detail-item-value" id="detailStatus">
                                -
                            </div>

                        </div>


                        <div class="detail-item">

                            <div class="detail-item-label">
                                Dibuat Oleh
                            </div>

                            <div class="detail-item-value" id="detailCreatedBy">
                                -
                            </div>

                        </div>


                        <div class="detail-item">

                            <div class="detail-item-label">
                                Disetujui Oleh
                            </div>

                            <div class="detail-item-value" id="detailApprovedBy">
                                -
                            </div>

                        </div>

                    </div>


                    <div class="detail-section-title">

                        <i class="bi bi-trophy"></i>

                        5 Pegawai Terbaik

                    </div>


                    <div class="tahap1-table-wrapper">

                        <table class="detail-result-table">

                            <thead>

                                <tr>

                                    <th>
                                        No
                                    </th>

                                    <th>
                                        Nama Pegawai
                                    </th>

                                    </tr>

                            </thead>


                            <tbody id="detailResultsBody"></tbody>

                        </table>

                    </div>

                </div>


                <div id="detailError" class="text-center py-5" style="display:none;">

                    <i class="bi bi-exclamation-circle"
                        style="
                        font-size:35px;
                        color:#dc3545;
                    ">
                    </i>

                    <div class="mt-2"
                        style="
                        font-size:13px;
                        color:#7c8794;
                    ">

                        Data detail tidak dapat dimuat.

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =============================================================
     MODAL DELETE
============================================================== --}}

    <div id="deleteModal" class="custom-modal-backdrop" onclick="closeDeleteModal(event)">

        <div class="custom-modal delete-modal" onclick="event.stopPropagation()">

            <div class="delete-modal-body">

                <div class="delete-icon">

                    <i class="bi bi-trash3"></i>

                </div>


                <h4 class="delete-modal-title">
                    Hapus Riwayat?
                </h4>


                <p class="delete-modal-text">

                    Apakah kamu yakin ingin menghapus
                    riwayat Penilaian Tahap 1 ini?

                    <br>

                    Data hasil dan peringkat yang tersimpan
                    akan ikut dihapus.

                </p>


                <div id="deleteTarget" class="delete-target">

                    -

                </div>

            </div>


            <div class="delete-modal-footer">

                <button type="button" class="tahap1-btn tahap1-btn-secondary" onclick="closeDeleteModal()">

                    Batal

                </button>


                <form id="deleteForm" method="POST">

                    @csrf

                    @method('DELETE')

                    <button type="submit" class="tahap1-btn tahap1-btn-danger">

                        <i class="bi bi-trash3"></i>

                        Ya, Hapus

                    </button>

                </form>

            </div>

        </div>

    </div>


    {{-- =============================================================
     MODAL KONFIRMASI PERSETUJUAN / PENOLAKAN
============================================================== --}}

    <div id="approvalModal" class="custom-modal-backdrop" onclick="closeApprovalModal(event)">

        <div class="custom-modal delete-modal" onclick="event.stopPropagation()">

            <div class="delete-modal-body">

                <div id="approvalIcon" class="delete-icon">
                    <i id="approvalIconElement" class="bi bi-question-circle"></i>
                </div>

                <h4 id="approvalModalTitle" class="delete-modal-title">
                    Konfirmasi
                </h4>

                <p id="approvalModalText" class="delete-modal-text">
                    Apakah Anda yakin ingin melanjutkan tindakan ini?
                </p>

            </div>

            <div class="delete-modal-footer">

                <button type="button"
                    class="tahap1-btn tahap1-btn-secondary"
                    onclick="closeApprovalModal()">
                    Batal
                </button>

                <button id="approvalConfirmButton"
                    type="button"
                    class="tahap1-btn tahap1-btn-success"
                    onclick="submitApprovalForm()">
                    <i id="approvalConfirmIcon" class="bi bi-check-lg"></i>
                    <span id="approvalConfirmText">Setujui</span>
                </button>

            </div>

        </div>

    </div>


    {{-- =============================================================
     MODAL PROSES 5 PEGAWAI TERBAIK
============================================================== --}}

    @if ($step == 2 && $suratKeputusanId)
        <div class="modal fade" id="modalProsesTerbaik" tabindex="-1" aria-labelledby="modalProsesTerbaikLabel"
            aria-hidden="true">

            <div class="modal-dialog modal-dialog-centered">

                <div class="modal-content">

                    {{-- HEADER --}}

                    <div class="proses-modal-header">

                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close">
                        </button>


                        <div class="d-flex align-items-center gap-3">

                            <div class="proses-icon-wrapper">

                                <i class="bi bi-trophy-fill"></i>

                            </div>


                            <div>

                                <h5 class="proses-modal-title" id="modalProsesTerbaikLabel">

                                    Proses 5 Pegawai Terbaik

                                </h5>


                                <p class="proses-modal-subtitle">

                                    Konfirmasi proses penilaian Tahap 1

                                </p>

                            </div>

                        </div>

                    </div>


                    {{-- BODY --}}

                    <div class="modal-body">

                        <div class="proses-intro-box">

                            <i class="bi bi-info-circle-fill"></i>

                            <div>

                                Sistem akan mengambil

                                <strong>
                                    5 pegawai dengan nilai kinerja tertinggi
                                </strong>

                                berdasarkan periode yang telah dipilih.

                                Pegawai yang telah dikecualikan tidak akan
                                diikutsertakan dalam proses.

                            </div>

                        </div>


                        <div class="proses-info-grid">

                            <div class="proses-info-card">

                                <span class="proses-info-label">
                                    Tahun
                                </span>

                                <span class="proses-info-value">
                                    {{ $tahun }}
                                </span>

                            </div>


                            <div class="proses-info-card">

                                <span class="proses-info-label">
                                    Periode
                                </span>

                                <span class="proses-info-value">
                                    Triwulan {{ $triwulan }}
                                </span>

                            </div>

                        </div>


                        <div class="proses-sk-card">

                            <span class="sk-label">
                                Surat Keputusan Panitia
                            </span>


                            <span class="sk-value">

                                {{ $suratKeputusan->no_sk }}

                            </span>


                            @if ($suratKeputusan->perihal)
                                <span class="sk-perihal">

                                    {{ $suratKeputusan->perihal }}

                                </span>
                            @endif

                        </div>


                        <div class="proses-warning">

                            <i class="bi bi-shield-exclamation"></i>

                            <div>

                                <strong>Perhatian:</strong>

                                Setelah proses dijalankan, sistem akan
                                membaca nilai penilaian pada periode ini,
                                mengecualikan pegawai yang dipilih,
                                kemudian membuat hasil 5 pegawai terbaik.

                            </div>

                        </div>

                    </div>


                    {{-- FOOTER --}}

                    <div class="modal-footer">

                        <button type="button" class="btn btn-secondary btn-proses-batal" data-bs-dismiss="modal">

                            <i class="bi bi-x-lg me-1"></i>

                            Batal

                        </button>


                        <form action="{{ route('admin.sipeter.penilaian.penilaian-tahap1.proses-terbaik') }}"
                            method="POST" class="m-0">

                            @csrf


                            <input type="hidden" name="tahun" value="{{ $tahun }}">


                            <input type="hidden" name="triwulan" value="{{ $triwulan }}">


                            <input type="hidden" name="surat_keputusan_id" value="{{ $suratKeputusanId }}">


                            <button type="submit" class="btn-proses-confirm">

                                <i class="bi bi-trophy-fill"></i>

                                Proses Sekarang

                                <i class="bi bi-arrow-right"></i>

                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </div>
    @endif


    <script>
        /* =========================================================
               BASE URL
            ========================================================= */

        const tahap1BaseUrl =
            @json(route('admin.sipeter.penilaian.penilaian-tahap1.index'));


        /* =========================================================
           DETAIL MODAL
        ========================================================= */

        function formatTanggalIndonesia(value) {

            if (!value) {
                return '-';
            }

            const date = new Date(value);

            if (Number.isNaN(date.getTime())) {
                return '-';
            }

            return date.toLocaleDateString('id-ID', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            });
        }


        function openDetailModal(id) {

            const modal =
                document.getElementById('detailModal');

            const loading =
                document.getElementById('detailLoading');

            const content =
                document.getElementById('detailContent');

            const error =
                document.getElementById('detailError');


            modal.classList.add('show');

            document.body.style.overflow = 'hidden';

            loading.style.display = 'block';

            content.style.display = 'none';

            error.style.display = 'none';


            fetch(
                    tahap1BaseUrl + '/' + id + '/lihat', {
                        method: 'GET',

                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    }
                )

                .then(response => {

                    if (!response.ok) {
                        throw new Error('Gagal mengambil data.');
                    }

                    return response.json();

                })

                .then(responseData => {

                    const data = responseData.data ?? responseData;

                    document.getElementById(
                            'detailTahun'
                        ).textContent =
                        data.tahun ?? '-';


                    document.getElementById(
                            'detailTriwulan'
                        ).textContent =
                        'Triwulan ' +
                        (data.triwulan ?? '-');


                    document.getElementById(
                            'detailNoSk'
                        ).textContent =
                        data.surat_keputusan?.no_sk ?? '-';


                    document.getElementById(
                            'detailTanggalSk'
                        ).textContent =
                        formatTanggalIndonesia(
                            data.surat_keputusan?.tanggal_sk
                        );


                    document.getElementById(
                            'detailPerihal'
                        ).textContent =
                        data.surat_keputusan?.perihal ?? '-';


                    document.getElementById(
                            'detailCreatedBy'
                        ).textContent =
                        data.creator?.nama ?? '-';


                    document.getElementById(
                            'detailApprovedBy'
                        ).textContent =
                        data.approver?.nama ?? '-';


                    const statusElement =
                        document.getElementById(
                            'detailStatus'
                        );


                    let statusHtml = '';


                    if (
                        data.status ===
                        'menunggu_persetujuan'
                    ) {

                        statusHtml =
                            '<span class="status-badge status-menunggu">' +
                            '<i class="bi bi-hourglass-split"></i>' +
                            ' Menunggu Persetujuan' +
                            '</span>';

                    } else if (
                        data.status ===
                        'disetujui'
                    ) {

                        statusHtml =
                            '<span class="status-badge status-disetujui">' +
                            '<i class="bi bi-check-circle-fill"></i>' +
                            ' Disetujui' +
                            '</span>';

                    } else if (
                        data.status ===
                        'ditolak'
                    ) {

                        statusHtml =
                            '<span class="status-badge status-ditolak">' +
                            '<i class="bi bi-x-circle-fill"></i>' +
                            ' Ditolak' +
                            '</span>';

                    } else {

                        statusHtml =
                            '<span class="status-badge status-menunggu">' +
                            escapeHtml(data.status ?? '-') +
                            '</span>';

                    }


                    statusElement.innerHTML =
                        statusHtml;


                    const tbody =
                        document.getElementById(
                            'detailResultsBody'
                        );


                    tbody.innerHTML = '';


                    if (
                        data.details &&
                        data.details.length > 0
                    ) {

                        // Baris ditampilkan berurutan abjad nama pegawai,
                        // nomor peringkat mengikuti urutan tampil (1, 2, 3, ...).
                        const details = [...data.details]
                            .sort(function(a, b) {
                                const namaA = String(a.nama ?? a.user?.nama ?? '');
                                const namaB = String(b.nama ?? b.user?.nama ?? '');
                                return namaA.localeCompare(namaB, 'id', {
                                    sensitivity: 'base'
                                });
                            });
                        details.forEach(
                            function(item, index) {
                                const row =
                                    document.createElement('tr');
                                const rank = index + 1;

                                let rankHtml =
                                    escapeHtml(rank);


                                if (rank === 1) {

                                    rankHtml =
                                        '<span class="status-badge">' +
                                        ' 1' +
                                        '</span>';

                                }


                                row.innerHTML =

                                    '<td>' +
                                    rankHtml +
                                    '</td>' +

                                    '<td>' +
                                    escapeHtml(
                                        item.nama ??
                                        item.user?.nama ??
                                        '-'
                                    ) +
                                    '</td>';


                                tbody.appendChild(row);

                            }
                        );

                    } else {

                        tbody.innerHTML =
                            '<tr>' +
                            '<td colspan="2" style="text-align:center;">' +
                            'Belum ada data hasil.' +
                            '</td>' +
                            '</tr>';

                    }


                    loading.style.display =
                        'none';

                    content.style.display =
                        'block';

                })

                .catch(errorObject => {

                    console.error(errorObject);

                    loading.style.display =
                        'none';

                    content.style.display =
                        'none';

                    error.style.display =
                        'block';

                });

        }


        function closeDetailModal(event) {

            if (
                event &&
                event.target !==
                event.currentTarget
            ) {
                return;
            }


            const modal =
                document.getElementById(
                    'detailModal'
                );


            modal.classList.remove('show');

            document.body.style.overflow =
                '';

        }


        /* =========================================================
           DELETE MODAL
        ========================================================= */

        function openDeleteModal(id, target) {

            const modal =
                document.getElementById(
                    'deleteModal'
                );


            const targetElement =
                document.getElementById(
                    'deleteTarget'
                );


            const form =
                document.getElementById(
                    'deleteForm'
                );


            targetElement.textContent =
                target;


            form.action =
                tahap1BaseUrl +
                '/' +
                id;


            modal.classList.add('show');

            document.body.style.overflow =
                'hidden';

        }


        function closeDeleteModal(event) {

            if (
                event &&
                event.target !==
                event.currentTarget
            ) {
                return;
            }


            const modal =
                document.getElementById(
                    'deleteModal'
                );


            modal.classList.remove('show');

            document.body.style.overflow =
                '';

        }


        /* =========================================================
           KONFIRMASI PROSES TAHAP 3
        ========================================================= */

        let processFinalForm = null;


        function openProcessFinalModal(form) {

            processFinalForm = form;

            const modal =
                document.getElementById(
                    'processFinalModal'
                );

            modal.classList.add('show');

            document.body.style.overflow =
                'hidden';

        }


        function submitProcessFinalForm() {

            if (!processFinalForm) {
                return;
            }

            const form =
                processFinalForm;

            processFinalForm = null;

            closeProcessFinalModal();

            form.submit();

        }


        function closeProcessFinalModal(event) {

            if (
                event &&
                event.target !==
                event.currentTarget
            ) {
                return;
            }

            const modal =
                document.getElementById(
                    'processFinalModal'
                );

            modal.classList.remove('show');

            document.body.style.overflow =
                '';

            processFinalForm = null;

        }


        /* =========================================================
           APPROVAL
        ========================================================= */

        let approvalForm = null;


        function openApprovalModal(type, form) {

            approvalForm = form;

            const modal =
                document.getElementById('approvalModal');

            const icon =
                document.getElementById('approvalIcon');

            const iconElement =
                document.getElementById('approvalIconElement');

            const title =
                document.getElementById('approvalModalTitle');

            const text =
                document.getElementById('approvalModalText');

            const confirmButton =
                document.getElementById('approvalConfirmButton');

            const confirmIcon =
                document.getElementById('approvalConfirmIcon');

            const confirmText =
                document.getElementById('approvalConfirmText');


            if (type === 'tolak') {

                icon.style.background = '#fff0f2';
                icon.style.color = '#dc3545';

                iconElement.className =
                    'bi bi-x-circle';

                title.textContent =
                    'Tolak Penilaian?';

                text.textContent =
                    'Apakah Anda yakin ingin menolak Penilaian Tahap 1 ini?';

                confirmButton.className =
                    'tahap1-btn tahap1-btn-danger';

                confirmIcon.className =
                    'bi bi-x-lg';

                confirmText.textContent =
                    'Ya, Tolak';

            } else {

                icon.style.background = '#e5f7ed';
                icon.style.color = '#198754';

                iconElement.className =
                    'bi bi-check-circle';

                title.textContent =
                    'Setujui Penilaian?';

                text.textContent =
                    'Apakah Anda yakin ingin menyetujui Penilaian Tahap 1 ini?';

                confirmButton.className =
                    'tahap1-btn tahap1-btn-success';

                confirmIcon.className =
                    'bi bi-check-lg';

                confirmText.textContent =
                    'Ya, Setujui';

            }


            modal.classList.add('show');

            document.body.style.overflow =
                'hidden';

        }


        function submitApprovalForm() {

            if (!approvalForm) {
                return;
            }

            const form =
                approvalForm;

            approvalForm =
                null;

            closeApprovalModal();

            form.submit();

        }


        function closeApprovalModal(event) {

            if (
                event &&
                event.target !==
                event.currentTarget
            ) {
                return;
            }

            const modal =
                document.getElementById(
                    'approvalModal'
                );

            modal.classList.remove('show');

            document.body.style.overflow =
                '';

            approvalForm =
                null;

        }


        /* =========================================================
           ESCAPE HTML
        ========================================================= */

        function escapeHtml(value) {

            return String(value)

                .replace(/&/g, '&amp;')

                .replace(/</g, '&lt;')

                .replace(/>/g, '&gt;')

                .replace(/"/g, '&quot;')

                .replace(/'/g, '&#039;');

        }


        /* =========================================================
           ESC KEY
        ========================================================= */

        document.addEventListener(
            'keydown',
            function(event) {

                if (event.key !== 'Escape') {
                    return;
                }


                closeDetailModal();

                closeDeleteModal();

            }
        );
    </script>

@endsection