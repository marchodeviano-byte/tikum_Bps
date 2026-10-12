<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>BPS KOTA BOGOR | BADAN PUSAT STATISTIK</title>

    {{-- Favicon --}}
    <link rel="shortcut icon" href="{{ asset('assets/static/images/logo/logoputih.png') }}" type="image/png">

    {{-- CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app-dark.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/compiled/css/iconly.css') }}">

    <style>
        /* =========================================
   SIDEBAR MENU - RAPAPI & SEJAJAR
========================================= */

        .sidebar-menu {
            padding: 0 16px;
        }

        .sidebar-menu .menu {
            padding: 0;
            margin: 0;
            list-style: none;
        }


        /* Menu utama */
        .sidebar-menu .sidebar-item {
            width: 100%;
            margin-bottom: 8px;
        }


        /* Link menu utama */
        .sidebar-menu .sidebar-link {
            width: 100%;
            min-height: 52px;

            display: flex;
            align-items: center;

            padding: 12px 18px;

            border-radius: 10px;

            gap: 16px;
        }


        /* Icon menu utama */
        .sidebar-menu .sidebar-link>i {
            width: 22px;
            min-width: 22px;

            display: flex;
            justify-content: center;
            align-items: center;

            font-size: 18px;
        }


        /* Tulisan menu utama */
        .sidebar-menu .sidebar-link>span {
            flex: 1;
            display: block;
        }


        /* =========================================
   SUBMENU
========================================= */

        .sidebar-menu .submenu {
            margin: 4px 0 8px 0;
            padding: 0 0 0 38px;

            list-style: none;
        }


        /* Item submenu */
        .sidebar-menu .submenu-item {
            margin: 3px 0;
        }


        /* Link submenu */
        .sidebar-menu .submenu-link {
            display: flex;
            align-items: center;

            min-height: 44px;

            padding: 9px 16px;

            border-radius: 8px;

            white-space: nowrap;
        }


        /* =========================================
   ACTIVE SUBMENU
========================================= */

        .sidebar-menu .submenu-item.active .submenu-link {
            font-weight: 600;
        }


        /* =========================================
   PANAH MENU HAS-SUB
========================================= */

        .sidebar-menu .has-sub>.sidebar-link {
            position: relative;
        }


        /* =========================================
   SUBMENU DISABLED (COMING SOON)
========================================= */

        .sidebar-menu .submenu-item.disabled>.submenu-link {
            opacity: .55;
            cursor: not-allowed;
        }

        .sidebar-menu .submenu-item.disabled>.submenu-link:hover {
            background: transparent !important;
        }

        .badge-coming-soon {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: .3px;
            text-transform: uppercase;
            padding: 3px 8px;
            border-radius: 20px;
            background: #eef1f5;
            color: #8a94a6;
            white-space: nowrap;
        }


        /* =========================================
   SIPETER - SUBMENU BERTINGKAT
   IMPORTANT: nested submenu TIDAK memakai class .submenu
   agar tidak dikontrol oleh Mazer app.js.
========================================= */

        .sidebar-menu .submenu-item.has-sub>.submenu-link {
            position: relative;
            padding-right: 34px;
        }

        .sidebar-menu .submenu-item.has-sub>.submenu-link::after,
        .sidebar-menu .submenu-item.has-sub>.submenu-link::before {
            content: none !important;
            display: none !important;
        }

        .sidebar-menu .submenu-item.has-sub>.submenu-link .submenu-caret {
            position: absolute;
            right: 12px;
            top: 50%;
            font-size: 11px;
            transform: translateY(-50%);
            transition: transform .2s ease;
        }

        .sidebar-menu .submenu-item.has-sub.active>.submenu-link .submenu-caret {
            transform: translateY(-50%) rotate(180deg);
        }

        /* Default tertutup */
        .sidebar-menu .nested-submenu {
            display: none !important;
            width: auto !important;
            height: auto !important;
            max-height: none !important;
            margin: 4px 0 5px 18px !important;
            padding: 0 !important;
            list-style: none !important;
            overflow: visible !important;
            opacity: 1 !important;
            visibility: visible !important;
        }

        /* Saat parent aktif, tampilkan seluruh isi */
        .sidebar-menu .submenu-item.has-sub.active>.nested-submenu,
        .sidebar-menu .nested-submenu.is-open {
            display: block !important;
        }

        /* Level nested berikutnya */
        .sidebar-menu .nested-submenu .submenu-item {
            margin: 3px 0 !important;
            padding-left: 12px !important;
            border-left: 2px solid #e4e7ee;
        }

        .sidebar-menu .nested-submenu .submenu-item .submenu-link {
            display: flex;
            align-items: center;
            min-height: 36px;
            padding: 8px 8px !important;
            font-size: 13px;
            line-height: 1.4;
            color: #5b6478 !important;
            opacity: 1 !important;
            visibility: visible !important;
            white-space: nowrap;
            transition: none !important;
        }

        .sidebar-menu .nested-submenu .submenu-item.active>.submenu-link {
            color: #4054b2 !important;
            font-weight: 600;
        }

        /* Nested level berikutnya dibuat sedikit lebih masuk */
        .sidebar-menu .nested-submenu .submenu-item.has-sub>.nested-submenu {
            margin-left: 14px !important;
        }

        /* =========================================
   LOGO
========================================= */

        .sidebar-header .logo {
            width: 100% !important;

            display: flex;
            justify-content: center;
            align-items: center;
        }

        .sidebar-header .logo a {
            display: flex;
            justify-content: center;
            align-items: center;

            width: 100%;
        }

        .sidebar-header .logo img.logo-bps {
            width: 155px !important;
            height: auto !important;
            max-width: 100% !important;

            object-fit: contain;
        }

        /* =====================================================
           SIDEBAR LOGO
        ===================================================== */

        .sidebar-header .logo {
            width: 100% !important;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .sidebar-header .logo a {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
        }

        .sidebar-header .logo img.logo-bps {
            width: 170px !important;
            height: auto !important;
            max-width: 90% !important;
            object-fit: contain;
            display: block;
        }

        /* Logo Light */
        .sidebar-header .logo .logo-light {
            display: block;
        }

        /* Logo Dark */
        .sidebar-header .logo .logo-dark {
            display: none;
        }


        /* =====================================================
           SIDEBAR HEADER
        ===================================================== */

        .sidebar-header {
            padding: 25px 20px 30px !important;
        }


        /* =====================================================
           MENU
        ===================================================== */

        .sidebar-menu {
            padding-top: 5px;
        }

        .sidebar-menu .menu {
            padding-left: 0;
            margin-bottom: 0;
        }


        /* =====================================================
           SUBMENU
        ===================================================== */

        .sidebar-menu .submenu {
            margin-top: 5px;
        }


        /* =====================================================
           MONITORING ICON
        ===================================================== */

        .monitoring-icon {
            font-size: 18px;
        }


        /* =====================================================
           RESPONSIVE LOGO
        ===================================================== */

        @media (max-width: 1199px) {

            .sidebar-header .logo img.logo-bps {
                width: 150px !important;
            }

        }

        @media (max-width: 768px) {

            .sidebar-header .logo img.logo-bps {
                width: 140px !important;
            }

        }
    </style>

</head>


<body>

    {{-- Theme --}}
    <script src="{{ asset('assets/static/js/initTheme.js') }}"></script>


    <div id="app">

        {{-- =====================================================
             SIDEBAR
        ===================================================== --}}

        <div id="sidebar">

            <div class="sidebar-wrapper active">


                {{-- =================================================
                     SIDEBAR HEADER
                ================================================== --}}

                <div class="sidebar-header position-relative">

                    <div class="d-flex justify-content-between align-items-center">

                        {{-- LOGO --}}
                        <div class="logo">

                            <a href="{{ route('dashboard') }}">

                                {{-- LIGHT MODE --}}
                                <img src="{{ asset('assets/static/images/logo/logo_black_teks.png') }}" alt="Logo BPS"
                                    class="logo-bps logo-light">

                                {{-- DARK MODE --}}
                                <img src="{{ asset('assets/static/images/logo/logoputih.png') }}" alt="Logo BPS"
                                    class="logo-bps logo-dark">

                            </a>

                        </div>


                        {{-- SIDEBAR TOGGLER MOBILE --}}
                        <div class="sidebar-toggler x">

                            <a href="#" class="sidebar-hide d-xl-none d-block">

                                <i class="bi bi-x bi-middle"></i>

                            </a>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     SIDEBAR MENU
                ================================================== --}}

                <div class="sidebar-menu">
                    <ul class="menu">

                        {{-- DASHBOARD --}}
                        <li class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <a href="{{ route('dashboard') }}" class="sidebar-link">
                                <i class="bi bi-grid-fill"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>


                        {{-- KINERJA --}}
                        @php
                            $kinerjaKehadiranActive = request()->routeIs('admin.kehadiran.*');
                            $kinerjaPenilaianActive = request()->routeIs('admin.penilaian.*');
                            $kinerjaPelaporanActive = request()->routeIs('admin.pelaporan.*');
                            $kinerjaSkpActive = request()->routeIs(
                                'admin.skp.*',
                                'admin.skp.penetapan.*',
                                'admin.skp.evaluasi.*',
                                'admin.skp.angka-kredit.*',
                            );

                            $kinerjaActive =
                                $kinerjaKehadiranActive ||
                                $kinerjaPenilaianActive ||
                                $kinerjaPelaporanActive ||
                                $kinerjaSkpActive;
                        @endphp

                        <li class="sidebar-item has-sub {{ $kinerjaActive ? 'active' : '' }}">

                            <a href="#" class="sidebar-link">
                                <i class="bi bi-stack"></i>
                                <span>Kinerja Pegawai</span>
                            </a>

                            <ul class="submenu {{ $kinerjaActive ? 'active' : '' }}">

                                {{-- KEHADIRAN --}}
                                <li class="submenu-item {{ $kinerjaKehadiranActive ? 'active' : '' }}">
                                    <a href="{{ route('admin.kehadiran.index') }}" class="submenu-link">
                                        Kehadiran
                                    </a>
                                </li>

                                {{-- PENILAIAN --}}
                                <li class="submenu-item {{ $kinerjaPenilaianActive ? 'active' : '' }}">
                                    <a href="{{ route('admin.penilaian.index') }}" class="submenu-link">
                                        Penilaian Skp
                                    </a>
                                </li>

                                {{-- PELAPORAN --}}
                                <li class="submenu-item {{ $kinerjaPelaporanActive ? 'active' : '' }}">
                                    <a href="{{ route('admin.pelaporan.index') }}" class="submenu-link">
                                        Pelaporan
                                    </a>
                                </li>

                                {{-- SKP --}}
                                <li class="submenu-item has-sub {{ $kinerjaSkpActive ? 'active' : '' }}">
                                    <a href="#" class="submenu-link">
                                        <span>Skp</span>
                                        <i class="bi bi-chevron-down submenu-caret"></i>
                                    </a>

                                    <ul class="nested-submenu {{ $kinerjaSkpActive ? 'is-open' : '' }}">
                                        <li
                                            class="submenu-item {{ request()->routeIs('admin.skp.penetapan.*') ? 'active' : '' }}">
                                            <a href="{{ route('admin.skp.penetapan') }}" class="submenu-link">
                                                Penetapan
                                            </a>
                                        </li>

                                        <li
                                            class="submenu-item {{ request()->routeIs('admin.skp.evaluasi.*') ? 'active' : '' }}">
                                            <a href="{{ route('admin.skp.evaluasi') }}" class="submenu-link">
                                                Evaluasi
                                            </a>
                                        </li>

                                        {{-- ANGKA KREDIT --}}
                                        <li
                                            class="submenu-item {{ request()->routeIs('admin.skp.angka-kredit.*') ? 'active' : '' }}">
                                            <a href="{{ Route::has('admin.skp.angka-kredit.index') ? route('admin.skp.angka-kredit.index') : 'javascript:void(0)' }}"
                                                class="submenu-link">
                                                Angka Kredit
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                            </ul>
                        </li>


                        {{-- KINERJA MITRA --}}
                        <li class="sidebar-item has-sub">

                            <a href="#" class="sidebar-link">
                                <i class="bi bi-people-fill"></i>
                                <span>Kinerja Mitra</span>
                            </a>

                            <ul class="submenu">

                                {{-- Penilaian (COMING SOON) --}}
                                <li class="submenu-item disabled">
                                    <a href="javascript:void(0)" class="submenu-link" onclick="return false;">
                                        <span>Penilaian</span>
                                        <span class="badge-coming-soon ms-auto">Coming Soon</span>
                                    </a>
                                </li>

                                {{-- Blacklist (COMING SOON) --}}
                                <li class="submenu-item disabled">
                                    <a href="javascript:void(0)" class="submenu-link" onclick="return false;">
                                        <span>Blacklist</span>
                                        <span class="badge-coming-soon ms-auto">Coming Soon</span>
                                    </a>
                                </li>

                            </ul>
                        </li>

                        {{-- KINERJA INSTANSI --}}
                        <li class="sidebar-item has-sub">

                            <a href="#" class="sidebar-link">
                                <i class="bi bi-building"></i>
                                <span>Kinerja Instansi</span>
                            </a>

                            <ul class="submenu">

                                {{-- SASARAN (COMING SOON) --}}
                                <li class="submenu-item disabled">
                                    <a href="javascript:void(0)" class="submenu-link" onclick="return false;">
                                        <span>Sasaran</span>
                                        <span class="badge-coming-soon ms-auto">Coming Soon</span>
                                    </a>
                                </li>

                                {{-- ENTRI SASARAN (COMING SOON) --}}
                                <li class="submenu-item disabled">
                                    <a href="javascript:void(0)" class="submenu-link" onclick="return false;">
                                        <span>Entri Sasaran</span>
                                        <span class="badge-coming-soon ms-auto">Coming Soon</span>
                                    </a>
                                </li>

                                {{-- INOVASI (COMING SOON) --}}
                                <li class="submenu-item disabled">
                                    <a href="javascript:void(0)" class="submenu-link" onclick="return false;">
                                        <span>Inovasi</span>
                                        <span class="badge-coming-soon ms-auto">Coming Soon</span>
                                    </a>
                                </li>

                                {{-- PENGHARGAAN (COMING SOON) --}}
                                <li class="submenu-item disabled">
                                    <a href="javascript:void(0)" class="submenu-link" onclick="return false;">
                                        <span>Penghargaan</span>
                                        <span class="badge-coming-soon ms-auto">Coming Soon</span>
                                    </a>
                                </li>

                            </ul>
                        </li>


                        {{-- SIPETER --}}
                        @php
                            $suratKeputusanActive = request()->routeIs(
                                'admin.sipeter.surat-keputusan.panitia.*',
                                'admin.sipeter.surat-keputusan.hasil.*',
                            );

                            $pertanyaanActive = request()->routeIs('admin.sipeter.manajemen-pertanyaan.*');

                            $penilaianTahap1Active = request()->routeIs(
                                'admin.sipeter.penilaian.penilaian-tahap1.*',
                                'admin.sipeter.penilaian.penilaian-tahap1.*',
                            );

                            $tahap2PanitiaActive = request()->routeIs(
                                'admin.sipeter.penilaian.penilaian-tahap2.panitia.*',
                            );

                            $tahap2PegawaiActive = request()->routeIs(
                                'admin.sipeter.penilaian.penilaian-tahap2.pegawai.*',
                            );

                            $penilaianTahap2Active = $tahap2PanitiaActive || $tahap2PegawaiActive;

                            // Link submenu Tahap 2. Selama route-nya belum dibuat,
                            // link diarahkan ke "#" supaya sidebar tidak error.
                            $tahap2PanitiaUrl = Route::has('admin.sipeter.penilaian.penilaian-tahap2.panitia.index')
                                ? route('admin.sipeter.penilaian.penilaian-tahap2.panitia.index')
                                : 'javascript:void(0)';

                            $tahap2PegawaiUrl = Route::has('admin.sipeter.penilaian.penilaian-tahap2.pegawai.index')
                                ? route('admin.sipeter.penilaian.penilaian-tahap2.pegawai.index')
                                : 'javascript:void(0)';

                            $penilaianActive = $penilaianTahap1Active || $penilaianTahap2Active;

                            $sipeterActive = $suratKeputusanActive || $pertanyaanActive || $penilaianActive;
                        @endphp

                        <li class="sidebar-item has-sub {{ $sipeterActive ? 'active' : '' }}">

                            <a href="#" class="sidebar-link">
                                <i class="bi bi-clipboard2-check"></i>
                                <span>SIPETER</span>
                            </a>

                            {{-- ROOT SUBMENU: tetap .submenu karena dikelola Mazer --}}
                            <ul class="submenu {{ $sipeterActive ? 'active' : '' }}">

                                {{-- 1. SURAT KEPUTUSAN --}}
                                <li class="submenu-item has-sub {{ $suratKeputusanActive ? 'active' : '' }}">

                                    <a href="#" class="submenu-link">
                                        <span>Surat Keputusan</span>
                                        <i class="bi bi-chevron-down submenu-caret"></i>
                                    </a>

                                    {{-- NESTED SUBMENU --}}
                                    <ul class="nested-submenu {{ $suratKeputusanActive ? 'is-open' : '' }}">

                                        {{-- PANITIA --}}
                                        <li
                                            class="submenu-item {{ request()->routeIs('admin.sipeter.surat-keputusan.panitia.*') ? 'active' : '' }}">
                                            <a href="{{ route('admin.sipeter.surat-keputusan.panitia.index') }}"
                                                class="submenu-link">
                                                Panitia
                                            </a>
                                        </li>

                                        {{-- HASIL SURAT KEPUTUSAN --}}
                                        <li
                                            class="submenu-item {{ request()->routeIs('admin.sipeter.surat-keputusan.hasil.*') ? 'active' : '' }}">
                                            <a href="{{ route('admin.sipeter.surat-keputusan.hasil.index') }}"
                                                class="submenu-link">
                                                Hasil
                                            </a>
                                        </li>

                                    </ul>
                                </li>


                                {{-- 2. MANAJEMEN PERTANYAAN --}}
                                <li class="submenu-item {{ $pertanyaanActive ? 'active' : '' }}">
                                    <a href="{{ route('admin.sipeter.manajemen-pertanyaan.index') }}"
                                        class="submenu-link">
                                        Manajemen Pertanyaan
                                    </a>
                                </li>


                                {{-- 3. PENILAIAN --}}
                                <li class="submenu-item has-sub {{ $penilaianActive ? 'active' : '' }}">

                                    <a href="#" class="submenu-link">
                                        <span>Penilaian</span>
                                        <i class="bi bi-chevron-down submenu-caret"></i>
                                    </a>

                                    {{-- NESTED SUBMENU --}}
                                    <ul class="nested-submenu {{ $penilaianActive ? 'is-open' : '' }}">

                                        {{-- PENILAIAN TAHAP 1 --}}
                                        <li class="submenu-item {{ $penilaianTahap1Active ? 'active' : '' }}">
                                            <a href="{{ route('admin.sipeter.penilaian.penilaian-tahap1.index') }}"
                                                class="submenu-link">
                                                Penilaian Tahap 1
                                            </a>
                                        </li>

                                        {{-- PENILAIAN TAHAP 2 --}}
                                        <li class="submenu-item has-sub {{ $penilaianTahap2Active ? 'active' : '' }}">
                                            <a href="#" class="submenu-link">
                                                <span>Penilaian Tahap 2</span>
                                                <i class="bi bi-chevron-down submenu-caret"></i>
                                            </a>

                                            {{-- NESTED SUBMENU LEVEL 2 --}}
                                            <ul class="nested-submenu {{ $penilaianTahap2Active ? 'is-open' : '' }}">

                                                {{-- PANITIA --}}
                                                <li class="submenu-item {{ $tahap2PanitiaActive ? 'active' : '' }}">
                                                    <a href="{{ route('admin.sipeter.penilaian.penilaian-tahap2.panitia.index') }}"
                                                        class="submenu-link">
                                                        Panitia
                                                    </a>
                                                </li>

                                                {{-- PEGAWAI --}}
                                                <li class="submenu-item {{ $tahap2PegawaiActive ? 'active' : '' }}">
                                                    <a href="{{ $tahap2PegawaiUrl }}" class="submenu-link">
                                                        Pegawai
                                                    </a>
                                                </li>

                                            </ul>
                                        </li>

                                    </ul>
                                </li>


                                {{-- 4. HASIL --}}
                                {{-- Route belum tersedia, jadi sementara dibuat sebagai menu biasa. --}}
                                <li class="submenu-item">
                                    <a href="javascript:void(0)" class="submenu-link" onclick="return false;">
                                        Hasil
                                    </a>
                                </li>

                            </ul>
                        </li>


                        {{-- MONITORING --}}
                        @php
                            $monitoringPelaporanActive = request()->routeIs('admin.monitoring_pelaporan.*');

                            $monitoringSkpActive = request()->routeIs(
                                'admin.monitoring_skp.*',
                                'admin.monitoring_skp.penetapan.*',
                                'admin.monitoring_skp.evaluasi.*',
                            );

                            $monitoringActive = $monitoringPelaporanActive || $monitoringSkpActive;
                        @endphp <li class="sidebar-item has-sub {{ $monitoringActive ? 'active' : '' }}">

                            <a href="#" class="sidebar-link">
                                <i class="bi bi-bar-chart-fill"></i>
                                <span>Monitoring</span>
                            </a>

                            <ul class="submenu {{ $monitoringActive ? 'active' : '' }}">

                                {{-- MONITORING PELAPORAN --}}
                                <li class="submenu-item {{ $monitoringPelaporanActive ? 'active' : '' }}">

                                    <a href="{{ route('admin.monitoring_pelaporan.index') }}" class="submenu-link">

                                        Pelaporan

                                    </a>

                                </li>

                                {{-- MONITORING SKP --}}
                                <li class="submenu-item has-sub {{ $monitoringSkpActive ? 'active' : '' }}">

                                    <a href="#" class="submenu-link">
                                        <span>Skp</span>
                                        <i class="bi bi-chevron-down submenu-caret"></i>
                                    </a>

                                    <ul class="nested-submenu {{ $monitoringSkpActive ? 'is-open' : '' }}">
                                        <li
                                            class="submenu-item {{ request()->routeIs('admin.monitoring_skp.penetapan.*') ? 'active' : '' }}">
                                            <a href="{{ route('admin.monitoring_skp.penetapan') }}"
                                                class="submenu-link">
                                                Penetapan
                                            </a>
                                        </li>

                                        <li
                                            class="submenu-item {{ request()->routeIs('admin.monitoring_skp.evaluasi.*') ? 'active' : '' }}">
                                            <a href="{{ route('admin.monitoring_skp.evaluasi') }}"
                                                class="submenu-link">
                                                Evaluasi
                                            </a>
                                        </li>
                                    </ul>

                                </li>

                            </ul>
                        </li>


                        {{-- MANAJEMEN USER --}}
                        <li class="sidebar-item {{ request()->routeIs('admin.manajemen-user.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.manajemen-user.index') }}" class="sidebar-link">
                                <i class="bi bi-pen-fill"></i>
                                <span>Manajemen User</span>
                            </a>
                        </li> 

                        {{-- HELP DESK --}}
                        <li class="sidebar-item sidebar-item-bottom">
                            <a href="{{ route('helpdesk') }}" class="sidebar-link" target="_blank" rel="noopener noreferrer">
                                <i class="bi bi-headset"></i>
                                <span>Help Desk</span>
                            </a>
                        </li>

                    </ul>
                </div>

            </div>

        </div>


        {{-- =====================================================
             MAIN CONTENT
        ===================================================== --}}

        <div id="main">

            @yield('content')


            {{-- FOOTER --}}
            <footer>

                <div class="footer clearfix mb-0 text-muted">

                    <div class="float-start">

                        <p>
                            2026 &copy; Copyright
                            <strong>
                                <span>BPS KOTA BOGOR</span>
                            </strong>.
                            All Rights Reserved
                        </p>

                    </div>

                </div>

            </footer>

        </div>

    </div>


    {{-- =====================================================
         JAVASCRIPT
    ===================================================== --}}

    <script src="{{ asset('assets/static/js/components/dark.js') }}"></script>

    <script src="{{ asset('assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>

    <script src="{{ asset('assets/compiled/js/app.js') }}"></script>

    <script src="{{ asset('assets/extensions/apexcharts/apexcharts.min.js') }}"></script>

    <script src="{{ asset('assets/static/js/pages/dashboard.js') }}"></script>


    {{-- =====================================================
         LOGO LIGHT / DARK MODE
    ===================================================== --}}

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            function updateLogo() {

                const isDark =
                    document.documentElement.classList.contains('theme-dark') ||
                    document.body.classList.contains('theme-dark') ||
                    document.documentElement.getAttribute('data-bs-theme') === 'dark' ||
                    document.body.getAttribute('data-bs-theme') === 'dark';

                const logoLight =
                    document.querySelector('.logo-light');

                const logoDark =
                    document.querySelector('.logo-dark');


                if (!logoLight || !logoDark) {
                    return;
                }


                if (isDark) {

                    logoLight.style.display = 'none';

                    logoDark.style.display = 'block';

                } else {

                    logoLight.style.display = 'block';

                    logoDark.style.display = 'none';

                }

            }


            // Jalankan ketika halaman pertama dibuka
            updateLogo();


            // Pantau perubahan theme Mazer
            const observer = new MutationObserver(function() {

                updateLogo();

            });


            observer.observe(
                document.documentElement, {
                    attributes: true,
                    attributeFilter: [
                        'class',
                        'data-bs-theme'
                    ]
                }
            );


            observer.observe(
                document.body, {
                    attributes: true,
                    attributeFilter: [
                        'class',
                        'data-bs-theme'
                    ]
                }
            );

        });
    </script>


    {{-- =====================================================
         TOGGLE SUBMENU SIPETER
         Nested menu memakai .nested-submenu agar tidak bentrok
         dengan accordion/collapse bawaan Mazer app.js.
    ===================================================== --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            document.addEventListener('click', function(event) {
                const link = event.target.closest(
                    '.sidebar-menu .submenu-item.has-sub > .submenu-link'
                );

                if (!link) return;

                const parent = link.parentElement;
                const child = link.nextElementSibling;

                if (!child || !child.classList.contains('nested-submenu')) {
                    return;
                }

                // Hentikan event agar tidak ditangkap ulang oleh Mazer app.js.
                event.preventDefault();
                event.stopPropagation();

                const willOpen = !parent.classList.contains('active');

                parent.classList.toggle('active', willOpen);
                child.classList.toggle('is-open', willOpen);
            }, true);

        });
    </script>


</body>

</html>
