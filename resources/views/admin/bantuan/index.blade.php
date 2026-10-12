<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Pusat Bantuan | BPS Kota Bogor</title>

    <link rel="shortcut icon" href="{{ asset('assets/static/images/logo/logoputih.png') }}" type="image/png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        /* =========================================================
           PUSAT BANTUAN (halaman terpisah, tanpa menu aplikasi)
           Warna mengikuti website utama: biru BPS #07549b.
        ========================================================= */
        :root {
            --hc-blue: #07549b;
            --hc-blue-dark: #06457f;
            --hc-blue-mid: #0a6bc4;
            --hc-blue-soft: #eef5ff;
            --hc-ink: #161c22;
            --hc-text: #34404d;
            --hc-muted: #7c8a9a;
            --hc-border: #dce2e8;
            --hc-bg: #f4f7fb;
        }

        * {
            box-sizing: border-box;
        }

        [hidden] {
            display: none !important;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background: var(--hc-bg);
            color: var(--hc-text);
            font-family: 'Nunito', system-ui, -apple-system, 'Segoe UI', sans-serif;
            font-size: 15px;
            line-height: 1.6;
        }

        a {
            color: inherit;
        }

        .hc-container {
            width: 100%;
            max-width: 1120px;
            margin: 0 auto;
            padding: 0 24px;
        }

        /* ---------- TOPBAR ---------- */
        .hc-topbar {
            position: sticky;
            top: 0;
            z-index: 50;
            background: #ffffff;
            border-bottom: 1px solid var(--hc-border);
            box-shadow: 0 2px 10px rgba(22, 28, 34, .04);
        }

        .hc-topbar-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            min-height: 70px;
        }

        .hc-brand {
            display: flex;
            align-items: center;
            gap: 14px;
            text-decoration: none;
        }

        .hc-brand img {
            height: 40px;
            width: auto;
            max-width: 200px;
            object-fit: contain;
        }

        .hc-brand-divider {
            width: 1px;
            height: 28px;
            background: var(--hc-border);
        }

        .hc-brand-text {
            color: var(--hc-blue);
            font-size: 15px;
            font-weight: 800;
            letter-spacing: .3px;
            text-transform: uppercase;
            line-height: 1.2;
        }

        .hc-nav {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .hc-nav a {
            padding: 9px 16px;
            border-radius: 9px;
            color: #526172;
            font-size: 14.5px;
            font-weight: 700;
            text-decoration: none;
            transition: background-color .15s ease, color .15s ease;
        }

        .hc-nav a:hover {
            background: var(--hc-blue-soft);
            color: var(--hc-blue);
        }

        .hc-nav a.active {
            background: var(--hc-blue-soft);
            color: var(--hc-blue);
        }

        .hc-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            height: 40px;
            padding: 0 18px;
            border-radius: 9px;
            background: var(--hc-blue);
            color: #ffffff;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            white-space: nowrap;
            transition: background-color .15s ease, transform .15s ease;
        }

        .hc-back:hover {
            background: var(--hc-blue-dark);
            color: #ffffff;
            transform: translateY(-1px);
        }

        /* ---------- HERO ---------- */
        .hc-hero {
            position: relative;
            overflow: hidden;
            padding: 58px 0 92px;
            color: #ffffff;
            text-align: center;
            background: linear-gradient(135deg, var(--hc-blue) 0%, var(--hc-blue-mid) 58%, #2f8fdc 100%);
        }

        .hc-hero::before,
        .hc-hero::after {
            content: "";
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, .08);
            pointer-events: none;
        }

        .hc-hero::before {
            width: 420px;
            height: 420px;
            top: -200px;
            right: -90px;
        }

        .hc-hero::after {
            width: 280px;
            height: 280px;
            bottom: -160px;
            left: -60px;
        }

        .hc-hero .hc-container {
            position: relative;
            z-index: 1;
        }

        .hc-hero-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 14px;
            padding: 6px 14px;
            border-radius: 20px;
            background: rgba(255, 255, 255, .16);
            font-size: 13px;
            font-weight: 700;
            letter-spacing: .3px;
        }

        .hc-hero-title {
            margin: 0 0 10px;
            color: #ffffff;
            font-size: 36px;
            font-weight: 800;
            letter-spacing: -.4px;
            line-height: 1.2;
        }

        .hc-hero-desc {
            max-width: 600px;
            margin: 0 auto 30px;
            color: rgba(255, 255, 255, .9);
            font-size: 16px;
        }

        /* ---------- PENCARIAN ---------- */
        .hc-search {
            position: relative;
            max-width: 660px;
            margin: 0 auto;
        }

        .hc-search>i.bi-search {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--hc-blue);
            font-size: 18px;
            pointer-events: none;
        }

        .hc-search input {
            width: 100%;
            height: 58px;
            padding: 0 92px 0 54px;
            border: 0;
            border-radius: 14px;
            outline: none;
            background: #ffffff;
            color: var(--hc-ink);
            font-family: inherit;
            font-size: 16px;
            font-weight: 600;
            box-shadow: 0 12px 28px rgba(0, 0, 0, .16);
        }

        .hc-search input::placeholder {
            color: #8b97a5;
            font-weight: 500;
            opacity: 1;
        }

        .hc-search input:focus {
            box-shadow: 0 0 0 4px rgba(255, 255, 255, .35), 0 12px 28px rgba(0, 0, 0, .16);
        }

        .hc-search-clear {
            position: absolute;
            right: 52px;
            top: 50%;
            transform: translateY(-50%);
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 0;
            border-radius: 50%;
            background: #eef2f6;
            color: #5b6877;
            font-size: 14px;
            cursor: pointer;
        }

        .hc-search-clear:hover {
            background: #dfe6ee;
        }

        .hc-search-key {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            min-width: 26px;
            padding: 2px 8px;
            border: 1px solid #d4dbe3;
            border-radius: 6px;
            background: #f6f8fa;
            color: #8b97a5;
            font-size: 12px;
            font-weight: 800;
            text-align: center;
            pointer-events: none;
        }

        /* ---------- KONTEN ---------- */
        .hc-main {
            flex: 1;
            padding-bottom: 48px;
        }

        .hc-cats {
            position: relative;
            z-index: 2;
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 20px;
            margin-top: -46px;
        }

        .hc-cat {
            display: flex;
            align-items: center;
            gap: 18px;
            width: 100%;
            padding: 24px 26px;
            border: 1px solid var(--hc-border);
            border-radius: 14px;
            background: #ffffff;
            font-family: inherit;
            text-align: left;
            cursor: pointer;
            box-shadow: 0 10px 24px rgba(22, 28, 34, .08);
            transition: border-color .18s ease, box-shadow .18s ease, transform .18s ease;
        }

        .hc-cat:hover {
            border-color: var(--hc-blue);
            transform: translateY(-3px);
            box-shadow: 0 16px 30px rgba(7, 84, 155, .16);
        }

        .hc-cat.active {
            border-color: var(--hc-blue);
            box-shadow: 0 0 0 3px rgba(7, 84, 155, .14), 0 10px 24px rgba(22, 28, 34, .08);
        }

        .hc-cat-icon {
            width: 60px;
            height: 60px;
            min-width: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 16px;
            background: var(--hc-blue-soft);
            color: var(--hc-blue);
            font-size: 28px;
        }

        .hc-cat-body {
            flex: 1;
            min-width: 0;
        }

        .hc-cat-title {
            display: block;
            margin-bottom: 3px;
            color: var(--hc-ink);
            font-size: 19px;
            font-weight: 800;
        }

        .hc-cat-desc {
            display: block;
            color: var(--hc-muted);
            font-size: 13.5px;
            line-height: 1.45;
        }

        .hc-count {
            flex: none;
            min-width: 32px;
            padding: 4px 11px;
            border-radius: 20px;
            background: var(--hc-blue-soft);
            color: var(--hc-blue);
            font-size: 12.5px;
            font-weight: 800;
            text-align: center;
        }

        /* ---------- DAFTAR ARTIKEL ---------- */
        .hc-list-card {
            margin-top: 28px;
            border: 1px solid var(--hc-border);
            border-radius: 14px;
            background: #ffffff;
            overflow: hidden;
            box-shadow: 0 6px 18px rgba(22, 28, 34, .05);
            scroll-margin-top: 90px;
        }

        .hc-list-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            flex-wrap: wrap;
            padding: 18px 24px;
            border-bottom: 1px solid #e8edf2;
        }

        .hc-tabs {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .hc-tab {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            height: 40px;
            padding: 0 18px;
            border: 1px solid var(--hc-border);
            border-radius: 10px;
            background: #f7f9fb;
            color: #526172;
            font-family: inherit;
            font-size: 14.5px;
            font-weight: 700;
            cursor: pointer;
            transition: .18s ease;
        }

        .hc-tab:hover {
            border-color: var(--hc-blue);
            color: var(--hc-blue);
        }

        .hc-tab.active {
            border-color: var(--hc-blue);
            background: var(--hc-blue);
            color: #ffffff;
        }

        .hc-tab.active .hc-count {
            background: rgba(255, 255, 255, .22);
            color: #ffffff;
        }

        .hc-list-meta {
            color: var(--hc-muted);
            font-size: 13px;
            font-weight: 700;
        }

        .hc-pane {
            padding: 12px 16px 16px;
        }

        .hc-item {
            display: flex;
            align-items: center;
            gap: 18px;
            margin-top: 8px;
            padding: 16px 18px;
            border: 1px solid transparent;
            border-radius: 11px;
            text-decoration: none;
            transition: background-color .15s ease, border-color .15s ease;
        }

        .hc-item:hover {
            border-color: #cfe0f3;
            background: #f5f9ff;
        }

        .hc-item.is-pending {
            cursor: default;
        }

        .hc-item.is-pending:hover {
            border-color: transparent;
            background: transparent;
        }

        .hc-item-icon {
            width: 44px;
            height: 44px;
            min-width: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            background: var(--hc-blue-soft);
            color: var(--hc-blue);
            font-size: 20px;
        }

        .hc-item.is-pending .hc-item-icon {
            background: #f1f3f6;
            color: #98a4b3;
        }

        .hc-item-body {
            flex: 1;
            min-width: 0;
        }

        .hc-item-title {
            display: block;
            color: var(--hc-ink);
            font-size: 15.5px;
            font-weight: 700;
            line-height: 1.4;
        }

        .hc-item-desc {
            display: block;
            margin-top: 2px;
            color: var(--hc-muted);
            font-size: 13.5px;
            line-height: 1.45;
        }

        .hc-item-action {
            flex: none;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            color: var(--hc-blue);
            font-size: 13.5px;
            font-weight: 800;
        }

        .hc-pending-badge {
            flex: none;
            padding: 4px 12px;
            border-radius: 20px;
            background: #f1f3f6;
            color: var(--hc-muted);
            font-size: 12px;
            font-weight: 700;
        }

        .hc-empty {
            padding: 52px 20px;
            text-align: center;
            color: var(--hc-muted);
        }

        .hc-empty i {
            display: block;
            margin-bottom: 10px;
            color: #b5c0cc;
            font-size: 44px;
        }

        .hc-empty strong {
            display: block;
            margin-bottom: 4px;
            color: var(--hc-text);
            font-size: 17px;
        }

        /* ---------- KONTAK ---------- */
        .hc-contact {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            flex-wrap: wrap;
            margin-top: 28px;
            padding: 24px 28px;
            border: 1px solid #cfe0f3;
            border-radius: 14px;
            background: linear-gradient(135deg, #f3f8ff 0%, #eaf3ff 100%);
        }

        .hc-contact-title {
            margin: 0 0 4px;
            color: var(--hc-blue);
            font-size: 18px;
            font-weight: 800;
        }

        .hc-contact-desc {
            margin: 0;
            color: #5f6e7f;
            font-size: 14px;
        }

        .hc-contact-list {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .hc-contact-item {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            padding: 11px 17px;
            border: 1px solid #d3e3f5;
            border-radius: 10px;
            background: #ffffff;
            color: var(--hc-ink);
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
        }

        .hc-contact-item i {
            color: var(--hc-blue);
            font-size: 16px;
        }

        a.hc-contact-item:hover {
            border-color: var(--hc-blue);
        }

        /* ---------- FOOTER ---------- */
        .hc-footer {
            padding: 22px 0;
            border-top: 1px solid var(--hc-border);
            background: #ffffff;
            color: var(--hc-muted);
            font-size: 13.5px;
            text-align: center;
        }

        .hc-footer strong {
            color: var(--hc-text);
        }

        /* ---------- RESPONSIVE ---------- */
        @media (max-width: 860px) {

            .hc-topbar-inner {
                flex-wrap: wrap;
                padding-top: 10px;
                padding-bottom: 10px;
            }

            .hc-nav {
                order: 3;
                width: 100%;
                overflow-x: auto;
                padding-bottom: 2px;
            }

            .hc-brand-text {
                display: none;
            }

            .hc-brand-divider {
                display: none;
            }
        }

        @media (max-width: 720px) {

            .hc-container {
                padding: 0 16px;
            }

            .hc-hero {
                padding: 40px 0 80px;
            }

            .hc-hero-title {
                font-size: 27px;
            }

            .hc-hero-desc {
                font-size: 15px;
            }

            .hc-cats {
                grid-template-columns: 1fr;
                gap: 14px;
            }

            .hc-search-key {
                display: none;
            }

            .hc-search input {
                padding-right: 56px;
            }

            .hc-search-clear {
                right: 14px;
            }

            .hc-back span {
                display: none;
            }

            .hc-back {
                padding: 0 14px;
            }

            .hc-item {
                flex-wrap: wrap;
                gap: 14px;
            }

            .hc-item-action,
            .hc-pending-badge {
                margin-left: 62px;
            }
        }
    </style>
</head>

<body>

    @php
        $kontak = $kontak ?? [];
        $adaKontak = collect($kontak)->filter()->isNotEmpty();
        $totalArtikel = collect($sections)->sum(fn($section) => count($section['items']));
    @endphp

    {{-- =====================================================
         TOPBAR + NAVIGASI HALAMAN BANTUAN
    ====================================================== --}}
    <header class="hc-topbar">
        <div class="hc-container hc-topbar-inner">

            <a href="#" class="hc-brand" data-home>
                <img src="{{ asset('assets/static/images/logo/logo_black_teks.png') }}" alt="Logo BPS">

                <span class="hc-brand-divider"></span>

                <span class="hc-brand-text">Pusat Bantuan</span>
            </a>

            <nav class="hc-nav" aria-label="Navigasi bantuan">

                <a href="#" data-home class="active">
                    <i class="bi bi-house-door"></i> Beranda
                </a>

                @foreach ($sections as $key => $section)
                    <a href="#{{ $key }}" data-tab="{{ $key }}">
                        <i class="bi {{ $section['icon'] }}"></i> {{ $section['label'] }}
                    </a>
                @endforeach

            </nav>

            <a href="{{ route('dashboard') }}" class="hc-back">
                <i class="bi bi-arrow-left"></i>
                <span>Kembali ke Aplikasi</span>
            </a>

        </div>
    </header>


    {{-- =====================================================
         HERO + PENCARIAN
    ====================================================== --}}
    <section class="hc-hero" id="hcTop">
        <div class="hc-container">

            <span class="hc-hero-eyebrow">
                <i class="bi bi-life-preserver"></i>
                Pusat Bantuan BPS Kota Bogor
            </span>

            <h1 class="hc-hero-title">Halo, ada yang bisa kami bantu?</h1>

            <p class="hc-hero-desc">
                Temukan panduan penggunaan aplikasi dan jawaban atas pertanyaan yang sering diajukan.
                {{ $totalArtikel }} artikel tersedia.
            </p>

            <div class="hc-search" role="search">
                <i class="bi bi-search"></i>

                <input type="search" id="hcSearch" placeholder="Cari panduan atau pertanyaan..."
                    aria-label="Cari bantuan" autocomplete="off">

                <button type="button" class="hc-search-clear" id="hcClear" aria-label="Hapus pencarian" hidden>
                    <i class="bi bi-x-lg"></i>
                </button>

                <span class="hc-search-key" id="hcKey">/</span>
            </div>

        </div>
    </section>


    <main class="hc-main">
        <div class="hc-container">

            {{-- =================================================
                 KATEGORI
            ================================================== --}}
            <div class="hc-cats">
                @foreach ($sections as $key => $section)
                    <button type="button" class="hc-cat" data-tab="{{ $key }}" data-scroll>

                        <span class="hc-cat-icon">
                            <i class="bi {{ $section['icon'] }}"></i>
                        </span>

                        <span class="hc-cat-body">
                            <span class="hc-cat-title">{{ $section['label'] }}</span>
                            <span class="hc-cat-desc">{{ $section['desc'] }}</span>
                        </span>

                        <span class="hc-count" data-count-for="{{ $key }}">
                            {{ count($section['items']) }}
                        </span>

                    </button>
                @endforeach
            </div>


            {{-- =================================================
                 DAFTAR ARTIKEL
            ================================================== --}}
            <div class="hc-list-card" id="hcList">

                <div class="hc-list-header">

                    <div class="hc-tabs" role="tablist">
                        @foreach ($sections as $key => $section)
                            <button type="button" class="hc-tab" role="tab" data-tab="{{ $key }}">
                                <i class="bi {{ $section['icon'] }}"></i>
                                {{ $section['label'] }}
                                <span class="hc-count" data-count-for="{{ $key }}">
                                    {{ count($section['items']) }}
                                </span>
                            </button>
                        @endforeach
                    </div>

                    <span class="hc-list-meta" id="hcMeta"></span>

                </div>

                @foreach ($sections as $key => $section)
                    <div class="hc-pane" data-pane="{{ $key }}" role="tabpanel" hidden>

                        @foreach ($section['items'] as $item)
                            @php
                                $punyaLink = !empty($item['url']) && $item['url'] !== '#';
                                $googleDocs = $punyaLink && str_contains($item['url'], 'docs.google.com');
                            @endphp

                            <a href="{{ $punyaLink ? $item['url'] : 'javascript:void(0)' }}"
                                class="hc-item {{ $punyaLink ? '' : 'is-pending' }}"
                                data-title="{{ mb_strtolower($item['title']) }}"
                                data-desc="{{ mb_strtolower($item['desc'] ?? '') }}"
                                @if ($punyaLink) target="_blank" rel="noopener noreferrer"
                                @else aria-disabled="true" tabindex="-1" @endif>

                                <span class="hc-item-icon">
                                    <i class="bi bi-file-earmark-text"></i>
                                </span>

                                <span class="hc-item-body">
                                    <span class="hc-item-title">{{ $item['title'] }}</span>

                                    @if (!empty($item['desc']))
                                        <span class="hc-item-desc">{{ $item['desc'] }}</span>
                                    @endif
                                </span>

                                @if ($punyaLink)
                                    <span class="hc-item-action">
                                        {{ $googleDocs ? 'Google Docs' : 'Buka' }}
                                        <i class="bi {{ $googleDocs ? 'bi-google' : 'bi-box-arrow-up-right' }}"></i>
                                    </span>
                                @else
                                    <span class="hc-pending-badge">Segera hadir</span>
                                @endif

                            </a>
                        @endforeach

                    </div>
                @endforeach

                <div class="hc-empty" id="hcEmpty" hidden>
                    <i class="bi bi-search"></i>
                    <strong>Tidak ada hasil yang cocok</strong>
                    Coba kata kunci lain, atau periksa kembali penulisannya.
                </div>

            </div>


            {{-- =================================================
                 KONTAK (tampil hanya kalau diisi di controller)
            ================================================== --}}
            @if ($adaKontak)
                <div class="hc-contact">

                    <div>
                        <h5 class="hc-contact-title">Masih butuh bantuan?</h5>
                        <p class="hc-contact-desc">
                            Hubungi admin aplikasi jika jawaban yang Anda cari belum ditemukan.
                        </p>
                    </div>

                    <div class="hc-contact-list">

                        @if (!empty($kontak['email']))
                            <a href="mailto:{{ $kontak['email'] }}" class="hc-contact-item">
                                <i class="bi bi-envelope-fill"></i>
                                {{ $kontak['email'] }}
                            </a>
                        @endif

                        @if (!empty($kontak['telepon']))
                            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $kontak['telepon']) }}"
                                class="hc-contact-item">
                                <i class="bi bi-telephone-fill"></i>
                                {{ $kontak['telepon'] }}
                            </a>
                        @endif

                        @if (!empty($kontak['jam']))
                            <span class="hc-contact-item">
                                <i class="bi bi-clock-fill"></i>
                                {{ $kontak['jam'] }}
                            </span>
                        @endif

                    </div>

                </div>
            @endif

        </div>
    </main>


    <footer class="hc-footer">
        <div class="hc-container">
            &copy; {{ date('Y') }} <strong>BPS Kota Bogor</strong> &middot; Badan Pusat Statistik
        </div>
    </footer>


    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const tabButtons = document.querySelectorAll('[data-tab]');
            const homeLinks = document.querySelectorAll('[data-home]');
            const panes = document.querySelectorAll('[data-pane]');
            const input = document.getElementById('hcSearch');
            const clearBtn = document.getElementById('hcClear');
            const keyHint = document.getElementById('hcKey');
            const empty = document.getElementById('hcEmpty');
            const meta = document.getElementById('hcMeta');
            const listCard = document.getElementById('hcList');

            const kunciValid = Array.from(panes).map(p => p.dataset.pane);

            let aktif = null;

            /* ---------- Ganti tab (Panduan / FAQ) ---------- */
            function setTab(key, gulir, tandaiNav) {

                aktif = key;

                tabButtons.forEach(btn => {
                    btn.classList.toggle('active', btn.dataset.tab === key);
                });

                // Menu "Beranda" aktif hanya kalau belum memilih Panduan/FAQ
                document.querySelectorAll('.hc-nav a[data-home]').forEach(link => {
                    link.classList.toggle('active', !tandaiNav);
                });

                document.querySelectorAll('.hc-nav a[data-tab]').forEach(link => {
                    link.classList.toggle('active', tandaiNav && link.dataset.tab === key);
                });

                panes.forEach(pane => {
                    pane.hidden = pane.dataset.pane !== key;
                });

                perbaruiMeta();

                if (gulir) {
                    listCard.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }

            /* ---------- Pencarian ---------- */
            function hitungHasil() {

                const kata = input.value.trim().toLowerCase();
                const hasil = {};

                panes.forEach(pane => {

                    let cocok = 0;

                    pane.querySelectorAll('.hc-item').forEach(item => {

                        const tampil = (item.dataset.title + ' ' + item.dataset.desc).includes(kata);

                        item.hidden = !tampil;

                        if (tampil) {
                            cocok++;
                        }
                    });

                    hasil[pane.dataset.pane] = cocok;
                });

                return {
                    kata,
                    hasil
                };
            }

            function perbaruiMeta() {

                const pane = document.querySelector('[data-pane="' + aktif + '"]');

                if (!pane) {
                    return;
                }

                const tampil = pane.querySelectorAll('.hc-item:not([hidden])').length;
                const total = pane.querySelectorAll('.hc-item').length;

                meta.textContent = tampil === total ?
                    total + ' artikel' :
                    tampil + ' dari ' + total + ' artikel';
            }

            let navDipilih = false;

            function cari() {

                const {
                    kata,
                    hasil
                } = hitungHasil();

                clearBtn.hidden = kata === '';
                keyHint.hidden = kata !== '';

                // Angka di kategori & tab = jumlah yang cocok
                document.querySelectorAll('[data-count-for]').forEach(el => {

                    const pane = document.querySelector('[data-pane="' + el.dataset.countFor + '"]');

                    el.textContent = kata === '' ?
                        pane.querySelectorAll('.hc-item').length :
                        hasil[el.dataset.countFor];
                });

                // Pindah otomatis ke tab yang punya hasil
                if (kata !== '' && hasil[aktif] === 0) {

                    const lain = kunciValid.find(key => hasil[key] > 0);

                    if (lain) {
                        setTab(lain, false, navDipilih);
                    }
                }

                const adaHasil = Object.values(hasil).some(jumlah => jumlah > 0);

                empty.hidden = adaHasil || kata === '';

                panes.forEach(pane => {
                    pane.hidden = !adaHasil || pane.dataset.pane !== aktif;
                });

                perbaruiMeta();
            }

            /* ---------- Event ---------- */
            tabButtons.forEach(btn => {
                btn.addEventListener('click', function(e) {

                    e.preventDefault();

                    navDipilih = true;

                    setTab(this.dataset.tab, true, true);

                    history.replaceState(null, '', '#' + this.dataset.tab);
                });
            });

            homeLinks.forEach(link => {
                link.addEventListener('click', function(e) {

                    e.preventDefault();

                    navDipilih = false;

                    setTab(aktif || kunciValid[0], false, false);

                    history.replaceState(null, '', location.pathname + location.search);

                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                });
            });

            input.addEventListener('input', cari);

            input.addEventListener('keydown', function(e) {

                if (e.key === 'Enter') {
                    e.preventDefault();
                    listCard.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }

                if (e.key === 'Escape') {
                    input.value = '';
                    cari();
                }
            });

            clearBtn.addEventListener('click', function() {
                input.value = '';
                cari();
                input.focus();
            });

            // Tekan "/" untuk langsung menulis di kolom pencarian
            document.addEventListener('keydown', function(e) {

                const sedangMenulis = ['INPUT', 'TEXTAREA', 'SELECT'].includes(document.activeElement.tagName);

                if (e.key === '/' && !sedangMenulis) {
                    e.preventDefault();
                    input.focus();
                }
            });

            /* ---------- Awal ---------- */
            const dariHash = location.hash.slice(1);
            const hashValid = kunciValid.includes(dariHash);

            navDipilih = hashValid;

            setTab(hashValid ? dariHash : kunciValid[0], hashValid, hashValid);

            cari();
        });
    </script>

</body>

</html>