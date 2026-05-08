<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Avian Cookies | Katalog Kue Kering Premium</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;9..144,700;9..144,800&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-cream: #fff7d8;
            --bg-soft: #ffe9a9;
            --sunset: #f9b516;
            --choco: #7f1010;
            --mint: #f4cf44;
            --text-main: #351111;
            --text-soft: #7b2d2d;
            --card: rgba(255, 255, 255, 0.76);
            --border: rgba(127, 16, 16, 0.16);
            --shadow: 0 22px 44px rgba(127, 16, 16, 0.18);
            --radius-lg: 28px;
            --radius-md: 18px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: "Manrope", "Segoe UI", sans-serif;
            color: var(--text-main);
            background: radial-gradient(circle at 15% 15%, #ffd54c 0%, transparent 36%),
                        radial-gradient(circle at 82% 12%, #ff7a59 0%, transparent 40%),
                        linear-gradient(160deg, var(--bg-cream) 0%, var(--bg-soft) 100%);
            min-height: 100vh;
            overflow-x: hidden;
        }

        .ambient {
            position: fixed;
            inset: 0;
            z-index: -1;
            pointer-events: none;
        }

        .blob {
            position: absolute;
            border-radius: 999px;
            filter: blur(12px);
            opacity: 0.4;
            animation: float 11s ease-in-out infinite;
        }

        .blob.one {
            width: 280px;
            height: 280px;
            top: -70px;
            left: -40px;
            background: #ffd341;
        }

        .blob.two {
            width: 240px;
            height: 240px;
            right: -80px;
            top: 28%;
            background: #ff5148;
            animation-delay: 1.2s;
        }

        .blob.three {
            width: 300px;
            height: 300px;
            left: 48%;
            bottom: -100px;
            background: #ffbf24;
            animation-delay: 2.1s;
        }

        .container {
            width: min(1160px, calc(100% - 2.2rem));
            margin: 0 auto;
        }

        .nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.2rem 0;
            position: sticky;
            top: 0;
            backdrop-filter: blur(10px);
            z-index: 30;
        }

        .brand {
            font-family: "Fraunces", Georgia, serif;
            font-size: 1.65rem;
            font-weight: 800;
            color: var(--choco);
            letter-spacing: 0.4px;
        }

        .nav-links {
            display: flex;
            gap: 1.2rem;
            align-items: center;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--text-soft);
            font-weight: 700;
            font-size: 0.94rem;
            transition: color 0.3s ease;
        }

        .nav-links a:hover {
            color: var(--choco);
        }

        .btn {
            border: 0;
            border-radius: 999px;
            padding: 0.85rem 1.35rem;
            cursor: pointer;
            font-weight: 800;
            font-family: inherit;
            transition: transform 0.3s ease, box-shadow 0.3s ease, background 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .btn-primary {
            background: linear-gradient(135deg, #ff4b4b 0%, #c4121a 100%);
            color: #fff;
            box-shadow: 0 10px 22px rgba(225, 27, 34, 0.34);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.6);
            color: var(--choco);
            border: 1px solid var(--border);
        }

        .hero {
            padding: 3.2rem 0 2rem;
            display: grid;
            grid-template-columns: 1.05fr 0.95fr;
            gap: 2rem;
            align-items: center;
        }

        .kicker {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(255, 255, 255, 0.58);
            border: 1px solid var(--border);
            border-radius: 999px;
            padding: 0.44rem 0.88rem;
            color: var(--text-soft);
            font-weight: 700;
            font-size: 0.82rem;
            margin-bottom: 1rem;
        }

        .hero h1 {
            font-family: "Fraunces", Georgia, serif;
            font-size: clamp(2.1rem, 4.4vw, 4rem);
            line-height: 1.08;
            margin-bottom: 1rem;
            color: #5c0f0f;
        }

        .hero p {
            color: var(--text-soft);
            font-size: clamp(0.97rem, 1.4vw, 1.08rem);
            line-height: 1.7;
            max-width: 560px;
            margin-bottom: 1.6rem;
        }

        .hero-actions {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .hero-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 1rem;
            box-shadow: var(--shadow);
            transform: translateY(10px);
            opacity: 0;
            animation: riseIn 1s 0.2s ease forwards;
        }

        .hero-card img {
            width: 100%;
            height: 330px;
            object-fit: cover;
            border-radius: 22px;
            display: block;
        }

        .hero-card-footer {
            margin-top: 0.85rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 0.7rem;
        }

        .hero-card-footer strong {
            display: block;
            color: var(--choco);
            font-size: 1rem;
        }

        .hero-card-footer span {
            color: var(--text-soft);
            font-size: 0.87rem;
        }

        .stats {
            margin-top: 1.6rem;
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 0.8rem;
        }

        .stat {
            border: 1px solid var(--border);
            background: rgba(255, 255, 255, 0.56);
            border-radius: var(--radius-md);
            padding: 0.9rem;
            text-align: center;
        }

        .stat b {
            font-family: "Fraunces", Georgia, serif;
            font-size: 1.45rem;
            color: var(--choco);
            display: block;
        }

        .stat span {
            color: var(--text-soft);
            font-weight: 700;
            font-size: 0.8rem;
        }

        section {
            padding: 2.6rem 0;
        }

        .section-title {
            font-family: "Fraunces", Georgia, serif;
            font-size: clamp(1.6rem, 3vw, 2.4rem);
            color: #690f0f;
            margin-bottom: 0.6rem;
        }

        .section-subtitle {
            color: var(--text-soft);
            max-width: 700px;
            line-height: 1.7;
            margin-bottom: 1.45rem;
        }

        .category-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1rem;
        }

        .category-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 1.2rem;
            display: grid;
            grid-template-columns: 1fr 0.8fr;
            gap: 0.9rem;
            align-items: center;
            box-shadow: var(--shadow);
            transition: transform 0.45s ease, box-shadow 0.45s ease;
        }

        .category-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 30px 52px rgba(127, 16, 16, 0.2);
        }

        .category-card img {
            width: 100%;
            height: 156px;
            border-radius: 16px;
            object-fit: cover;
        }

        .pill {
            display: inline-block;
            padding: 0.28rem 0.65rem;
            border-radius: 999px;
            background: rgba(255, 210, 40, 0.32);
            color: #8d170f;
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.04em;
            margin-bottom: 0.7rem;
        }

        .category-card h3 {
            font-family: "Fraunces", Georgia, serif;
            font-size: 1.4rem;
            margin-bottom: 0.45rem;
            color: #6a0e0e;
        }

        .category-card p {
            color: var(--text-soft);
            font-size: 0.92rem;
            line-height: 1.6;
        }

        .products {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 1rem;
        }

        .product {
            background: rgba(255, 255, 255, 0.75);
            border: 1px solid var(--border);
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 16px 30px rgba(127, 16, 16, 0.13);
            transition: transform 0.4s ease, box-shadow 0.4s ease;
        }

        .product:hover {
            transform: translateY(-9px) scale(1.01);
            box-shadow: 0 24px 40px rgba(127, 16, 16, 0.24);
        }

        .product img {
            width: 100%;
            height: 185px;
            object-fit: cover;
            display: block;
        }

        .product-body {
            padding: 1rem;
        }

        .product-tag {
            font-size: 0.68rem;
            font-weight: 800;
            color: #991515;
            letter-spacing: 0.06em;
            margin-bottom: 0.45rem;
            text-transform: uppercase;
        }

        .product h4 {
            font-size: 1rem;
            margin-bottom: 0.55rem;
            color: #4f1111;
        }

        .product-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: var(--text-soft);
            font-size: 0.84rem;
        }

        .cta {
            margin-top: 0.7rem;
            background: linear-gradient(140deg, #8f1212 0%, #b9151c 55%, #d2232a 100%);
            border-radius: var(--radius-lg);
            padding: clamp(1.4rem, 3vw, 2.4rem);
            color: #fff7dc;
            display: grid;
            grid-template-columns: 1.3fr 0.7fr;
            gap: 1rem;
            align-items: center;
            box-shadow: 0 25px 45px rgba(143, 18, 18, 0.34);
        }

        .cta h3 {
            font-family: "Fraunces", Georgia, serif;
            font-size: clamp(1.4rem, 2.4vw, 2.2rem);
            margin-bottom: 0.5rem;
        }

        .cta p {
            line-height: 1.75;
            color: #ffe7a6;
            max-width: 620px;
        }

        .cta .btn {
            justify-self: end;
            background: #ffe17a;
            color: #7f1010;
        }

        .reveal {
            opacity: 0;
            transform: translateY(18px);
            transition: opacity 0.7s ease, transform 0.7s ease;
        }

        .reveal.show {
            opacity: 1;
            transform: translateY(0);
        }

        .reveal-stagger > * {
            opacity: 0;
            transform: translateY(16px);
            transition: opacity 0.7s ease, transform 0.7s ease;
        }

        .reveal-stagger.show > * {
            opacity: 1;
            transform: translateY(0);
        }

        .reveal-stagger.show > *:nth-child(2) { transition-delay: 0.08s; }
        .reveal-stagger.show > *:nth-child(3) { transition-delay: 0.16s; }
        .reveal-stagger.show > *:nth-child(4) { transition-delay: 0.24s; }

        footer {
            padding: 2rem 0 2.5rem;
            color: #8a2b2b;
            font-size: 0.9rem;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-16px); }
        }

        @keyframes riseIn {
            from {
                transform: translateY(18px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @media (max-width: 1024px) {
            .hero,
            .cta {
                grid-template-columns: 1fr;
            }

            .products {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .cta .btn {
                justify-self: start;
            }
        }

        @media (max-width: 768px) {
            .nav {
                position: static;
                padding-top: 1rem;
            }

            .nav-links {
                display: none;
            }

            .hero {
                padding-top: 1.2rem;
            }

            .category-grid,
            .products,
            .stats {
                grid-template-columns: 1fr;
            }

            .category-card {
                grid-template-columns: 1fr;
            }

            .hero-card img {
                height: 260px;
            }
        }
    </style>
</head>
<body>
    <div class="ambient" aria-hidden="true">
        <span class="blob one"></span>
        <span class="blob two"></span>
        <span class="blob three"></span>
    </div>

    <div class="container">
        <nav class="nav">
            <div class="brand">Avian Cookies</div>
            <div class="nav-links">
                <a href="#kategori">Kategori</a>
                <a href="#produk">Produk</a>
                <a href="#order">Pemesanan</a>
                <button class="btn btn-primary">Pesan Sekarang</button>
            </div>
        </nav>

        <header class="hero reveal">
            <div>
                <span class="kicker">Freshly Baked Daily</span>
                <h1>Koleksi Kue Kering yang Cantik, Renyah, dan Bikin Rindu</h1>
                <p>
                    Jelajahi katalog kue kering premium dengan dua pilihan utama: <strong>Paket Set</strong> untuk hadiah spesial,
                    dan <strong>Single</strong> untuk dinikmati satuan sesuai favoritmu. Tampilan ini masih memakai placeholder image,
                    siap diganti foto produk asli kapan saja.
                </p>
                <div class="hero-actions">
                    <button class="btn btn-primary">Lihat Katalog</button>
                    <button class="btn btn-secondary">Lihat Promo Minggu Ini</button>
                </div>
                <div class="stats reveal-stagger">
                    <div class="stat">
                        <b>120+</b>
                        <span>Varian Rasa</span>
                    </div>
                    <div class="stat">
                        <b>4.9/5</b>
                        <span>Rating Pelanggan</span>
                    </div>
                    <div class="stat">
                        <b>24 Jam</b>
                        <span>Proses Cepat</span>
                    </div>
                </div>
            </div>

            <div class="hero-card">
                <img src="https://via.placeholder.com/900x620.png?text=Cookie+Gift+Set" alt="Produk Paket Set Kue Kering">
                <div class="hero-card-footer">
                    <div>
                        <strong>Signature Gift Box</strong>
                        <span>Mix cookies premium untuk hadiah</span>
                    </div>
                    <button class="btn btn-secondary">Detail</button>
                </div>
            </div>
        </header>

        <section id="kategori" class="reveal">
            <h2 class="section-title">Kategori Andalan</h2>
            <p class="section-subtitle">
                Pilih gaya belanja sesuai kebutuhan. Mau praktis untuk hampers dan event? Ambil Paket Set.
                Mau bebas pilih rasa favorit satuan? Cek koleksi Single.
            </p>
            <div class="category-grid">
                <article class="category-card">
                    <div>
                        <span class="pill">BEST FOR GIFT</span>
                        <h3>Paket Set</h3>
                        <p>
                            Komposisi beberapa rasa dalam satu paket elegan. Cocok untuk hampers lebaran,
                            corporate gift, atau kejutan untuk orang tersayang.
                        </p>
                    </div>
                    <img src="https://via.placeholder.com/640x420.png?text=Paket+Set" alt="Kategori Paket Set">
                </article>

                <article class="category-card">
                    <div>
                        <span class="pill">FLEXIBLE CHOICE</span>
                        <h3>Single</h3>
                        <p>
                            Beli per varian favorit dalam kemasan single. Pas untuk stok harian,
                            tester rasa baru, atau custom kombinasi sesuai selera keluarga.
                        </p>
                    </div>
                    <img src="https://via.placeholder.com/640x420.png?text=Single+Pack" alt="Kategori Single">
                </article>
            </div>
        </section>

        <section id="produk" class="reveal">
            <h2 class="section-title">Preview Produk Terlaris</h2>
            <p class="section-subtitle">
                Contoh tampilan kartu produk untuk halaman katalog. Seluruh gambar saat ini menggunakan placeholder,
                jadi nanti tinggal ganti source image ke foto produk asli.
            </p>

            <div class="products reveal-stagger">
                <article class="product">
                    <img src="https://via.placeholder.com/500x350.png?text=Nastar+Premium" alt="Nastar Premium">
                    <div class="product-body">
                        <div class="product-tag">Single</div>
                        <h4>Nastar Premium</h4>
                        <div class="product-meta">
                            <span>250 gram</span>
                            <strong>Rp75.000</strong>
                        </div>
                    </div>
                </article>

                <article class="product">
                    <img src="https://via.placeholder.com/500x350.png?text=Kastengel+Butter" alt="Kastengel Butter">
                    <div class="product-body">
                        <div class="product-tag">Single</div>
                        <h4>Kastengel Butter</h4>
                        <div class="product-meta">
                            <span>250 gram</span>
                            <strong>Rp82.000</strong>
                        </div>
                    </div>
                </article>

                <article class="product">
                    <img src="https://via.placeholder.com/500x350.png?text=Choco+Crunch+Set" alt="Choco Crunch Set">
                    <div class="product-body">
                        <div class="product-tag">Paket Set</div>
                        <h4>Choco Crunch Set</h4>
                        <div class="product-meta">
                            <span>3 toples</span>
                            <strong>Rp199.000</strong>
                        </div>
                    </div>
                </article>

                <article class="product">
                    <img src="https://via.placeholder.com/500x350.png?text=Classic+Family+Box" alt="Classic Family Box">
                    <div class="product-body">
                        <div class="product-tag">Paket Set</div>
                        <h4>Classic Family Box</h4>
                        <div class="product-meta">
                            <span>4 toples</span>
                            <strong>Rp259.000</strong>
                        </div>
                    </div>
                </article>
            </div>

            <div id="order" class="cta reveal">
                <div>
                    <h3>Siap Upgrade ke Toko Online yang Bikin Orang Betah Scroll?</h3>
                    <p>
                        Landing page ini sudah siap jadi fondasi branding toko kue kering kamu. Tinggal sambungkan ke data produk,
                        tambah tombol WhatsApp/checkout, dan website siap dipakai jualan.
                    </p>
                </div>
                <button class="btn">Mulai Order Sekarang</button>
            </div>
        </section>

        <footer>
            <p>Avian Cookies Catalog Landing Page - Made for a sweet first impression.</p>
        </footer>
    </div>

    <script>
        const revealElements = document.querySelectorAll('.reveal, .reveal-stagger');

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('show');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.16 });

        revealElements.forEach((element) => {
            observer.observe(element);
        });
    </script>
</body>
</html>
