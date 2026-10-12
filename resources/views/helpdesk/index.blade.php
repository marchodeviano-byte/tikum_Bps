<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Help Desk</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --blue: #2271d1;
            --cyan: #0e56dc;
            --ink: #000;
            --muted: #4a4f5c;
        }

        * { box-sizing: border-box; }
        [hidden] { display: none !important; }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Raleway', system-ui, sans-serif;
            color: var(--ink);
            background: #fff;
        }

        /* Foto gedung: taruh file di public/images/helpdesk-bg.jpg */
        .bg {
            position: absolute;
            inset: 0 0 auto 0;
            height: 54vh;
            min-height: 380px;
            z-index: 0;
            background:
                linear-gradient(rgba(255,255,255,.62), rgba(255,255,255,.62)),
                url("{{ asset('images/helpdesk-bg.jpg') }}") center / cover no-repeat,
                linear-gradient(180deg, #e6ecf6, #f4f7fb);
        }

        .page {
            position: relative;
            z-index: 1;
            height: 100vh;
            height: 100dvh;
            display: flex;
            flex-direction: column;
            padding: clamp(14px, 3vh, 32px) 24px clamp(16px, 3vh, 32px);
        }

        .logo {
            display: inline-block;
            align-self: flex-start;
            margin-left: 28px;
            font-size: 1.8rem;
            font-weight: 800;
            letter-spacing: -.03em;
            text-decoration: none;
            color: var(--ink);
        }
        .logo span { color: var(--cyan); }

        .frame {
            flex: 1;
            min-height: 0;
            width: 100%;
            max-width: 1072px;
            margin: clamp(12px, 3vh, 32px) auto 0;
            padding: clamp(18px, 4vh, 48px) 150px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            overflow-y: auto;
            border: 4px solid var(--blue);
            border-radius: 26px;
            background: rgba(255, 255, 255, .55);
            box-shadow: 0 14px 30px rgba(34, 104, 209, .15);
        }

        h1, h2 { margin: 0; font-weight: 800; letter-spacing: -.03em; }
        .accent-cyan { color: var(--cyan); }
        .accent-blue { color: var(--blue); }

        /* ---------- Search ---------- */
        .search {
            display: flex;
            align-items: center;
            padding: 4px 6px 4px 28px;
            border: 4px solid var(--blue);
            border-radius: 999px;
            background: #fff;
        }
        .search input {
            flex: 1;
            min-width: 0;
            border: 0;
            outline: 0;
            background: transparent;
            font: inherit;
            font-size: 1.4rem;
            color: var(--ink);
        }
        .search input::placeholder { color: var(--ink); opacity: 1; }
        .search button {
            flex: none;
            width: 58px;
            height: 58px;
            border: 0;
            border-radius: 50%;
            background: var(--blue);
            color: #fff;
            font-size: 1.5rem;
            cursor: pointer;
        }
        .search:focus-within { box-shadow: 0 0 0 4px rgba(34, 104, 209, .2); }

        /* ---------- Halaman 1 ---------- */
        .home h1 { text-align: center; font-size: clamp(1.6rem, 4.6vh, 2.4rem); }
        .home .search { margin-top: clamp(16px, 4vh, 36px); }

        .cats {
            display: grid;
            grid-template-columns: repeat(2, 304px);
            justify-content: space-between;
            margin-top: clamp(18px, 5vh, 50px);
        }
        .cat {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: clamp(170px, 31vh, 240px);
            padding: 20px;
            text-align: center;
            text-decoration: none;
            color: var(--ink);
            background: #fff;
            border: 4px solid var(--blue);
            border-radius: 24px;
            box-shadow: 0 8px 14px rgba(34, 104, 209, .18);
        }
        .cat i { font-size: clamp(2.6rem, 6.5vh, 4rem); line-height: 1; color: var(--blue); }
        .cat h2 { margin: clamp(8px, 1.8vh, 14px) 0 clamp(6px, 1.5vh, 12px); font-size: clamp(1.3rem, 3vh, 1.7rem); }
        .cat p { margin: 0; font-size: .95rem; color: var(--muted); }

        /* ---------- Halaman 2 ---------- */
        .list {
            display: grid;
            grid-template-columns: 1fr 430px;
            gap: 0 60px;
            flex: 1;
            min-height: 0;
            margin: 0 -100px;
        }
        .list .col-right { display: flex; flex-direction: column; min-height: 0; }
        .list h1 { font-size: clamp(1.6rem, 4.4vh, 2.3rem); padding-top: 6px; }

        .picked {
            display: flex;
            align-items: center;
            gap: 28px;
            width: 304px;
            margin-top: clamp(16px, 4vh, 38px);
            padding: clamp(18px, 3.5vh, 30px) 34px;
            background: #fff;
            border: 4px solid var(--blue);
            border-radius: 22px;
            box-shadow: 0 8px 14px rgba(34, 104, 209, .18);
        }
        .picked i { font-size: 2.6rem; line-height: 1; color: var(--blue); }
        .picked strong { font-size: 1.5rem; font-weight: 800; letter-spacing: -.02em; }

        .list .search { border-width: 3px; padding: 3px 5px 3px 22px; }
        .list .search input { font-size: 1.2rem; }
        .list .search button { width: 54px; height: 54px; }

        .panel {
            flex: 1;
            min-height: 0;
            overflow-y: auto;
            margin-top: 14px;
            padding: 20px;
            background: rgba(255, 255, 255, .85);
            border: 2px solid var(--blue);
            border-radius: 22px;
        }
        .guide {
            display: flex;
            align-items: center;
            gap: 30px;
            min-height: clamp(84px, 15vh, 120px);
            margin-bottom: 16px;
            padding: 14px 28px;
            text-decoration: none;
            color: var(--blue);
            background: #fff;
            border: 3px solid var(--blue);
            border-radius: 20px;
            box-shadow: 0 6px 12px rgba(34, 104, 209, .18);
        }
        .guide:last-of-type { margin-bottom: 0; }
        .guide i { flex: none; font-size: 2.6rem; line-height: 1; }
        .guide span { font-size: 1.45rem; font-weight: 800; letter-spacing: -.02em; line-height: 1.1; }
        .guide:hover { background: #f1f6ff; }

        .cat:focus-visible, .guide:focus-visible, .logo:focus-visible {
            outline: 3px solid var(--cyan);
            outline-offset: 3px;
        }
        .empty { margin: 8px 4px; color: var(--muted); }

        /* ---------- Responsive ---------- */
        @media (max-width: 1000px) {
            .page { height: auto; min-height: 100dvh; }
            .frame { padding: 32px 24px; overflow: visible; }
            .list { margin: 0; grid-template-columns: 1fr; }
            .list .col-right { margin-top: 24px; }
            .panel { overflow: visible; }
        }
        @media (max-width: 700px) {
            .cats { grid-template-columns: 1fr; gap: 20px; }
            .home h1, .list h1 { font-size: 1.7rem; }
            .logo { margin-left: 0; }
            .frame { margin-top: 32px; }
            .picked { width: 100%; }
        }
    </style>
</head>
<body>
    <div class="bg"></div>

    <div class="page">
        <a href="#" class="logo">Help<span>Desk</span></a>

        <main class="frame">

            {{-- ===== Halaman 1: beranda ===== --}}
            <section class="home" id="view-home">
                <h1>Halo, Butuh <span class="accent-cyan">Bantuan</span>?</h1>

                <form class="search" id="homeSearch" role="search">
                    <input type="search" id="homeInput" placeholder="Cari" aria-label="Cari bantuan" autocomplete="off">
                    <button type="submit" aria-label="Cari"><i class="bi bi-search"></i></button>
                </form>

                <div class="cats">
                    @foreach ($sections as $key => $section)
                        <a href="#{{ $key }}" class="cat">
                            <i class="bi {{ $section['icon'] }}"></i>
                            <h2>{{ $section['label'] }}</h2>
                            <p>{{ $section['desc'] }}</p>
                        </a>
                    @endforeach
                </div>
            </section>

            {{-- ===== Halaman 2: daftar panduan / FAQ ===== --}}
            @foreach ($sections as $key => $section)
                <section class="list" data-section="{{ $key }}" hidden>
                    <div class="col-left">
                        <h1>Cari, <span class="accent-blue">{{ $section['label'] }}</span>?</h1>
                        <div class="picked">
                            <i class="bi {{ $section['icon'] }}"></i>
                            <strong>{{ $section['label'] }}</strong>
                        </div>
                    </div>

                    <div class="col-right">
                        <form class="search" role="search">
                            <input type="search" placeholder="Cari" aria-label="Cari {{ $section['label'] }}" autocomplete="off">
                            <button type="submit" aria-label="Cari"><i class="bi bi-search"></i></button>
                        </form>

                        <div class="panel">
                            @foreach ($section['items'] as $item)
                                <a href="{{ $item['url'] }}" class="guide" target="_blank" rel="noopener noreferrer"
                                    data-title="{{ strtolower($item['title']) }}">
                                    <i class="bi bi-file-earmark-text"></i>
                                    <span>{{ $item['title'] }}</span>
                                </a>
                            @endforeach
                            <p class="empty" hidden>Tidak ada hasil yang cocok.</p>
                        </div>
                    </div>
                </section>
            @endforeach

        </main>
    </div>

    <script>
        const home = document.getElementById('view-home');
        const lists = document.querySelectorAll('.list');

        // Tampilkan halaman sesuai hash: "" = beranda, "#panduan" / "#faq" = daftar
        function render() {
            const key = location.hash.slice(1);
            let found = false;
            lists.forEach(el => {
                const on = el.dataset.section === key;
                el.hidden = !on;
                found = found || on;
            });
            home.hidden = found;
            window.scrollTo(0, 0);
        }
        window.addEventListener('hashchange', render);
        render();

        // Pencarian di halaman daftar
        lists.forEach(sec => {
            const input = sec.querySelector('input');
            const items = sec.querySelectorAll('.guide');
            const empty = sec.querySelector('.empty');

            sec.applyFilter = () => {
                const q = input.value.trim().toLowerCase();
                let shown = 0;
                items.forEach(el => {
                    const ok = el.dataset.title.includes(q);
                    el.hidden = !ok;
                    if (ok) shown++;
                });
                empty.hidden = shown > 0;
            };
            input.addEventListener('input', sec.applyFilter);
            sec.querySelector('form').addEventListener('submit', e => e.preventDefault());
        });

        // Pencarian di beranda: pindah ke daftar Panduan dengan kata kunci terisi
        document.getElementById('homeSearch').addEventListener('submit', e => {
            e.preventDefault();
            const panduan = document.querySelector('.list[data-section="panduan"]');
            if (!panduan) return;
            panduan.querySelector('input').value = document.getElementById('homeInput').value;
            panduan.applyFilter();
            location.hash = 'panduan';
        });
    </script>
</body>
</html>