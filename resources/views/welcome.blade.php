<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Merry Cookies | Katalog Kue Kering Premium</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;9..144,700;9..144,800&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
            --ease-smooth: cubic-bezier(0.22, 1, 0.36, 1);
            --ease-pop: cubic-bezier(0.16, 1, 0.3, 1);
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
            background-size: 140% 140%;
            animation: bgShift 18s ease-in-out infinite;
            min-height: 100vh;
            overflow-x: hidden;
        }

        body.modal-open {
            overflow: hidden;
            touch-action: none;
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
            filter: blur(18px);
            opacity: 0.5;
            animation: float 11s var(--ease-smooth) infinite, blobPulse 7s ease-in-out infinite;
            will-change: transform, opacity;
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
            z-index: 30;
            isolation: isolate;
        }

        .nav::before {
            content: "";
            position: absolute;
            top: 0;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100vw;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(127, 16, 16, 0.08);
            z-index: -1;
            box-shadow: 0 6px 14px rgba(127, 16, 16, 0.12);
            animation: navGlow 10s ease-in-out infinite alternate;
        }

        .brand {
            font-family: "Fraunces", Georgia, serif;
            font-size: 1.65rem;
            font-weight: 800;
            color: var(--choco);
            letter-spacing: 0.4px;
            padding-left: 20px;
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
        }

        .brand-logo {
            width: 36px;
            height: 36px;
        }

        .nav-links {
            display: flex;
            gap: 1.2rem;
            align-items: center;
        }

        .menu-toggle {
            display: none;
            width: 40px;
            height: 40px;
            border: 1px solid var(--border);
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.82);
            color: var(--choco);
            cursor: pointer;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--text-soft);
            font-weight: 700;
            font-size: 0.94rem;
            transition: color 0.28s var(--ease-smooth), transform 0.28s var(--ease-smooth);
        }

        .nav-links a:hover {
            color: var(--choco);
            transform: translateY(-1px);
        }

        .btn {
            border: 0;
            border-radius: 999px;
            padding: 0.85rem 1.35rem;
            cursor: pointer;
            font-weight: 800;
            font-family: inherit;
            transition: transform 0.32s var(--ease-pop), box-shadow 0.32s var(--ease-pop), background 0.32s var(--ease-pop), filter 0.32s var(--ease-pop);
        }

        .btn:hover {
            transform: translateY(-4px) scale(1.02);
            filter: brightness(1.02) saturate(1.08);
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

        .hero-actions a {
            text-decoration: none;
        }

        .hero-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 1rem;
            box-shadow: var(--shadow);
            transform: translateY(10px);
            opacity: 0;
            animation: heroRise 1.05s 0.18s var(--ease-pop) forwards;
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

        .contact-wrap {
            background: linear-gradient(155deg, rgba(255, 248, 224, 0.88) 0%, rgba(255, 236, 186, 0.86) 100%);
            border: 1px solid rgba(127, 16, 16, 0.14);
            border-radius: var(--radius-lg);
            padding: clamp(1rem, 2.8vw, 1.6rem);
            box-shadow: 0 18px 36px rgba(127, 16, 16, 0.14);
            animation: ctaGlowIn 1s var(--ease-pop) both;
        }

        .contact-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.85rem;
        }

        .contact-card {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            background: rgba(255, 255, 255, 0.78);
            border: 1px solid rgba(127, 16, 16, 0.12);
            border-radius: 14px;
            padding: 0.8rem 0.85rem;
            text-decoration: none;
            color: var(--text-main);
            transition: transform 0.3s var(--ease-pop), box-shadow 0.3s var(--ease-pop), border-color 0.3s var(--ease-pop), background 0.3s var(--ease-pop);
        }

        .contact-card:hover {
            transform: translateY(-6px) scale(1.01);
            border-color: rgba(196, 18, 26, 0.35);
            box-shadow: 0 18px 30px rgba(127, 16, 16, 0.16);
        }

        .contact-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(145deg, rgba(255, 211, 65, 0.34) 0%, rgba(255, 81, 72, 0.24) 100%);
            color: #8d170f;
            flex-shrink: 0;
            animation: iconBob 2.8s ease-in-out infinite;
        }

        .contact-card b {
            display: block;
            color: #5f1212;
            font-size: 0.9rem;
            margin-bottom: 0.12rem;
        }

        .contact-card span {
            color: var(--text-soft);
            font-size: 0.84rem;
            line-height: 1.45;
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
            transition: transform 0.5s var(--ease-pop), box-shadow 0.5s var(--ease-pop), border-color 0.5s var(--ease-pop), filter 0.5s var(--ease-pop);
            animation: cardFloatIn 0.95s var(--ease-pop) both;
        }

        .category-card:hover {
            transform: translateY(-12px) scale(1.01);
            box-shadow: 0 34px 60px rgba(127, 16, 16, 0.24);
            filter: saturate(1.04);
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
            transition: transform 0.45s var(--ease-pop), box-shadow 0.45s var(--ease-pop), filter 0.45s var(--ease-pop);
            animation: productLiftIn 0.9s var(--ease-pop) both;
        }

        .product:hover {
            transform: translateY(-12px) scale(1.03) rotate(-0.3deg);
            box-shadow: 0 30px 52px rgba(127, 16, 16, 0.26);
            filter: saturate(1.06);
        }

        .product img {
            width: 100%;
            height: 185px;
            object-fit: cover;
            display: block;
        }

        .product-media {
            position: relative;
            overflow: hidden;
        }

        .product-carousel {
            position: relative;
            width: 100%;
            height: 185px;
            touch-action: pan-y;
        }

        .product-carousel-track {
            width: 100%;
            height: 100%;
            display: flex;
            transition: transform 0.38s ease;
        }

        .product-carousel-slide {
            width: 100%;
            flex: 0 0 100%;
        }

        .product-carousel-slide img {
            width: 100%;
            height: 185px;
            object-fit: cover;
            display: block;
        }

        .carousel-control {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 28px;
            height: 28px;
            border: 0;
            border-radius: 999px;
            background: rgba(53, 17, 17, 0.65);
            color: #fff;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            z-index: 2;
        }

        .carousel-control.prev {
            left: 8px;
        }

        .carousel-control.next {
            right: 8px;
        }

        .carousel-dots {
            position: absolute;
            left: 50%;
            bottom: 8px;
            transform: translateX(-50%);
            display: flex;
            gap: 5px;
            z-index: 2;
        }

        .carousel-dot {
            width: 5px;
            height: 5px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.55);
            border: 0;
            padding: 0;
            cursor: pointer;
        }

        .carousel-dot.active {
            background: #fff;
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
            animation: ctaGlowIn 1s var(--ease-pop) both;
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
            transform: translateY(28px) scale(0.985);
            transition: opacity 0.9s var(--ease-pop), transform 0.9s var(--ease-pop);
        }

        .reveal.show {
            opacity: 1;
            transform: translateY(0) scale(1);
        }

        .reveal-stagger > * {
            opacity: 0;
            transform: translateY(24px) scale(0.98);
            transition: opacity 0.85s var(--ease-pop), transform 0.85s var(--ease-pop);
        }

        .reveal-stagger.show > * {
            opacity: 1;
            transform: translateY(0) scale(1);
        }

        .reveal-stagger.show > *:nth-child(2) { transition-delay: 0.08s; }
        .reveal-stagger.show > *:nth-child(3) { transition-delay: 0.16s; }
        .reveal-stagger.show > *:nth-child(4) { transition-delay: 0.24s; }
        .reveal-stagger.show > *:nth-child(5) { transition-delay: 0.32s; }
        .reveal-stagger.show > *:nth-child(6) { transition-delay: 0.4s; }

        footer {
            padding: 2rem 0 2.5rem;
            color: #8a2b2b;
            font-size: 0.9rem;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            inset: 0;
            background: radial-gradient(circle at 20% 20%, rgba(255, 211, 65, 0.22) 0%, transparent 34%),
                        radial-gradient(circle at 82% 14%, rgba(255, 81, 72, 0.25) 0%, transparent 36%),
                        rgba(58, 12, 12, 0.52);
            z-index: 1000;
            justify-content: center;
            align-items: center;
            backdrop-filter: blur(8px);
            animation: fadeIn 0.28s ease;
            padding: 1rem;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: linear-gradient(160deg, rgba(255, 250, 234, 0.96) 0%, rgba(255, 241, 206, 0.94) 100%);
            border-radius: var(--radius-lg);
            border: 1px solid rgba(127, 16, 16, 0.16);
            max-width: 650px;
            width: min(650px, 95vw);
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 28px 70px rgba(85, 20, 20, 0.28);
            animation: modalPopIn 0.52s var(--ease-pop) both;
            position: relative;
            overflow-x: hidden;
            scrollbar-width: thin;
            scrollbar-color: rgba(196, 18, 26, 0.78) rgba(255, 236, 190, 0.72);
        }

        .modal-content::-webkit-scrollbar,
        .kemasan-modal-content::-webkit-scrollbar {
            width: 10px;
        }

        .modal-content::-webkit-scrollbar-track,
        .kemasan-modal-content::-webkit-scrollbar-track {
            background: linear-gradient(180deg, rgba(255, 241, 206, 0.86) 0%, rgba(255, 230, 168, 0.78) 100%);
            border-left: 1px solid rgba(127, 16, 16, 0.08);
        }

        .modal-content::-webkit-scrollbar-thumb,
        .kemasan-modal-content::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #d5252f 0%, #b3131b 100%);
            border-radius: 999px;
            border: 2px solid rgba(255, 239, 198, 0.9);
        }

        .modal-content::-webkit-scrollbar-thumb:hover,
        .kemasan-modal-content::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #e1333d 0%, #c0151e 100%);
        }

        .modal-content::before {
            content: "";
            position: absolute;
            top: -120px;
            right: -70px;
            width: 220px;
            height: 220px;
            border-radius: 999px;
            background: radial-gradient(circle, rgba(255, 179, 40, 0.34) 0%, rgba(255, 179, 40, 0) 70%);
            pointer-events: none;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.2rem 1.3rem;
            border-bottom: 1px solid rgba(127, 16, 16, 0.14);
            background: linear-gradient(135deg, rgba(255, 81, 72, 0.11) 0%, rgba(255, 211, 65, 0.2) 100%);
        }

        .modal-close {
            background: rgba(255, 255, 255, 0.62);
            border: 1px solid rgba(127, 16, 16, 0.14);
            border-radius: 999px;
            width: 34px;
            height: 34px;
            font-size: 1.35rem;
            color: var(--text-soft);
            cursor: pointer;
            transition: color 0.3s ease, transform 0.3s ease, background 0.3s ease;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-close:hover {
            color: var(--choco);
            background: rgba(255, 255, 255, 0.9);
            transform: translateY(-1px);
        }

        .modal-body {
            padding: 1.2rem 1.3rem;
        }

        .modal-image {
            width: 100%;
            height: 300px;
            object-fit: cover;
            border-radius: var(--radius-md);
            border: 1px solid rgba(127, 16, 16, 0.1);
            box-shadow: 0 16px 34px rgba(127, 16, 16, 0.16);
            margin-bottom: 1rem;
            display: block;
        }

        .modal-image-carousel {
            position: relative;
            width: 100%;
            height: 300px;
            margin-bottom: 1rem;
            border-radius: var(--radius-md);
            overflow: hidden;
            border: 1px solid rgba(127, 16, 16, 0.1);
            box-shadow: 0 16px 34px rgba(127, 16, 16, 0.16);
            touch-action: pan-y;
            background: rgba(255, 255, 255, 0.55);
        }

        .modal-image-track {
            width: 100%;
            height: 100%;
            display: flex;
            transition: transform 0.38s ease;
        }

        .modal-image-slide {
            width: 100%;
            flex: 0 0 100%;
        }

        .modal-image-slide .modal-image {
            margin-bottom: 0;
            border: 0;
            box-shadow: none;
            border-radius: 0;
            height: 300px;
        }

        .modal-image-carousel .carousel-control {
            width: 32px;
            height: 32px;
        }

        .modal-image-carousel .carousel-dots {
            bottom: 10px;
        }

        .modal-title {
            font-family: "Fraunces", Georgia, serif;
            font-size: clamp(1.35rem, 3vw, 1.8rem);
            color: #5c0f0f;
            margin-bottom: 0.75rem;
        }

        .modal-tag {
            display: inline-block;
            padding: 0.34rem 0.72rem;
            border-radius: 999px;
            background: linear-gradient(135deg, rgba(255, 211, 65, 0.44) 0%, rgba(255, 81, 72, 0.26) 100%);
            color: #8d170f;
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.04em;
            margin-bottom: 0.85rem;
            border: 1px solid rgba(127, 16, 16, 0.14);
        }

        .category-section {
            margin-bottom: 1.2rem;
            background: rgba(255, 255, 255, 0.6);
            border: 1px solid rgba(127, 16, 16, 0.1);
            border-radius: 16px;
            padding: 0.95rem;
        }

        .category-label {
            font-weight: 700;
            font-size: 0.9rem;
            color: var(--text-main);
            margin-bottom: 0.8rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .kemasan-info-btn {
            width: 24px;
            height: 24px;
            border-radius: 999px;
            border: 1.5px solid rgba(127, 16, 16, 0.22);
            background: rgba(255, 255, 255, 0.82);
            color: var(--choco);
            font-size: 0.78rem;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: background 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease;
            margin-left: 0.4rem;
        }

        .kemasan-info-btn:hover {
            background: rgba(255, 210, 40, 0.32);
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(127, 16, 16, 0.14);
        }

        .kemasan-modal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(58, 12, 12, 0.52);
            z-index: 1100;
            justify-content: center;
            align-items: center;
            backdrop-filter: blur(8px);
            animation: fadeIn 0.28s ease;
            padding: 1rem;
        }

        .kemasan-modal.active {
            display: flex;
        }

        .kemasan-modal-content {
            background: linear-gradient(160deg, rgba(255, 250, 234, 0.97) 0%, rgba(255, 241, 206, 0.95) 100%);
            border-radius: var(--radius-lg);
            border: 1px solid rgba(127, 16, 16, 0.16);
            max-width: 480px;
            width: min(480px, 92vw);
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 28px 70px rgba(85, 20, 20, 0.28);
            animation: modalPopIn 0.52s var(--ease-pop) both;
            overflow-x: hidden;
            scrollbar-width: thin;
            scrollbar-color: rgba(196, 18, 26, 0.78) rgba(255, 236, 190, 0.72);
        }

        .kemasan-modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.9rem 1.1rem;
            border-bottom: 1px solid rgba(127, 16, 16, 0.12);
            background: linear-gradient(135deg, rgba(255, 81, 72, 0.1) 0%, rgba(255, 211, 65, 0.18) 100%);
        }

        .kemasan-modal-header h4 {
            font-family: "Fraunces", Georgia, serif;
            font-size: 1.1rem;
            color: #5c0f0f;
            margin: 0;
        }

        .kemasan-modal-body {
            padding: 1rem;
        }

        .kemasan-modal-body img {
            width: 100%;
            border-radius: var(--radius-md);
            display: block;
            border: 1px solid rgba(127, 16, 16, 0.1);
            box-shadow: 0 10px 24px rgba(127, 16, 16, 0.12);
        }

        .category-list {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.7rem;
        }

        .category-btn {
            padding: 0.6rem 0.8rem;
            border: 1.5px solid rgba(127, 16, 16, 0.14);
            background: rgba(255, 255, 255, 0.82);
            border-radius: 12px;
            cursor: pointer;
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--text-soft);
            transition: all 0.22s ease;
        }

        .category-btn:hover {
            background: rgba(255, 210, 40, 0.24);
            border-color: rgba(255, 81, 72, 0.5);
            transform: translateY(-1px);
        }

        .category-btn.active {
            background: linear-gradient(135deg, #ff4b4b 0%, #c4121a 100%);
            color: #fff;
            border-color: #c4121a;
            box-shadow: 0 8px 18px rgba(196, 18, 26, 0.28);
        }

        .price-section {
            background: linear-gradient(140deg, rgba(255, 211, 65, 0.24) 0%, rgba(255, 81, 72, 0.14) 100%);
            border: 1px solid rgba(127, 16, 16, 0.14);
            padding: 1rem 1.1rem;
            border-radius: var(--radius-md);
            margin-bottom: 1.2rem;
        }

        .price-label {
            font-size: 0.85rem;
            color: var(--text-soft);
            margin-bottom: 0.3rem;
        }

        .price-display {
            font-family: "Fraunces", Georgia, serif;
            font-size: 1.8rem;
            font-weight: 800;
            color: #c4121a;
            line-height: 1.1;
        }

        .quantity-section {
            margin-bottom: 1.2rem;
            background: rgba(255, 255, 255, 0.6);
            border: 1px solid rgba(127, 16, 16, 0.1);
            border-radius: 16px;
            padding: 0.95rem;
        }

        .quantity-list {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.7rem;
        }

        .quantity-btn {
            padding: 0.6rem 0.8rem;
            border: 1.5px solid rgba(127, 16, 16, 0.14);
            background: rgba(255, 255, 255, 0.82);
            border-radius: 12px;
            cursor: pointer;
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--text-soft);
            transition: all 0.22s ease;
        }

        .quantity-btn:hover {
            background: rgba(255, 210, 40, 0.24);
            border-color: rgba(255, 81, 72, 0.5);
            transform: translateY(-1px);
        }

        .quantity-btn.active {
            background: linear-gradient(135deg, #ff4b4b 0%, #c4121a 100%);
            color: #fff;
            border-color: #c4121a;
            box-shadow: 0 8px 18px rgba(196, 18, 26, 0.28);
        }

        .modal-actions {
            display: flex;
            gap: 0.7rem;
            padding: 1.1rem 1.3rem 1.3rem;
            border-top: 1px solid rgba(127, 16, 16, 0.12);
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.12) 0%, rgba(255, 244, 216, 0.48) 100%);
        }

        .modal-actions button {
            flex: 1;
            padding: 0.85rem 1.2rem;
            border: 0;
            border-radius: 999px;
            cursor: pointer;
            font-weight: 800;
            font-family: inherit;
            transition: all 0.3s ease;
        }

        .modal-actions .btn-primary {
            background: linear-gradient(135deg, #ff4b4b 0%, #c4121a 100%);
            color: #fff;
        }

        .clickable-product {
            cursor: pointer;
        }

        .d-none {
            display: none !important;
        }

        .back-to-top {
            position: fixed;
            right: 1.1rem;
            bottom: 1.2rem;
            width: 48px;
            height: 48px;
            border: 0;
            border-radius: 999px;
            background: linear-gradient(135deg, #ff4b4b 0%, #c4121a 100%);
            color: #fff;
            font-size: 1.3rem;
            font-weight: 800;
            cursor: pointer;
            box-shadow: 0 12px 24px rgba(196, 18, 26, 0.32);
            opacity: 0;
            visibility: hidden;
            transform: translateY(12px);
            transition: opacity 0.28s var(--ease-pop), transform 0.28s var(--ease-pop), visibility 0.28s var(--ease-pop), box-shadow 0.28s var(--ease-pop);
            z-index: 90;
        }

        .back-to-top:hover {
            transform: translateY(4px) scale(1.05);
            box-shadow: 0 16px 30px rgba(196, 18, 26, 0.4);
        }

        .back-to-top.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes bgShift {
            0%, 100% { background-position: 0% 0%; }
            50% { background-position: 100% 100%; }
        }

        @keyframes navGlow {
            0% { opacity: 0.82; }
            100% { opacity: 1; }
        }

        @keyframes blobPulse {
            0%, 100% { opacity: 0.42; transform: scale(1); }
            50% { opacity: 0.62; transform: scale(1.08); }
        }

        @keyframes heroRise {
            from {
                opacity: 0;
                transform: translateY(34px) scale(0.94) rotate(-1deg);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1) rotate(0);
            }
        }

        @keyframes cardFloatIn {
            from {
                opacity: 0;
                transform: translateY(30px) scale(0.96);
                filter: blur(6px);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
                filter: blur(0);
            }
        }

        @keyframes productLiftIn {
            from {
                opacity: 0;
                transform: translateY(26px) scale(0.94);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes ctaGlowIn {
            from {
                opacity: 0;
                transform: translateY(24px) scale(0.975);
                box-shadow: 0 0 0 rgba(0, 0, 0, 0);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
                box-shadow: 0 25px 45px rgba(143, 18, 18, 0.34);
            }
        }

        @keyframes modalPopIn {
            0% {
                opacity: 0;
                transform: translateY(34px) scale(0.92);
            }
            70% {
                opacity: 1;
                transform: translateY(-4px) scale(1.01);
            }
            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes iconBob {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-3px) rotate(-4deg); }
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

        @media (max-width: 640px) {
            .modal-content {
                width: 96vw;
                border-radius: 20px;
            }

            .modal-image {
                height: 220px;
            }

            .modal-image-carousel {
                height: 220px;
            }

            .modal-image-slide .modal-image {
                height: 220px;
            }

            .modal-image-carousel .carousel-dots {
                display: none;
            }

            .category-list {
                grid-template-columns: 1fr;
            }

            .modal-actions {
                flex-direction: column;
            }
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
                position: relative;
            }

            .nav-links {
                position: absolute;
                top: calc(100% + 0.45rem);
                right: 0;
                width: min(320px, calc(100vw - 2.2rem));
                flex-direction: column;
                align-items: stretch;
                gap: 0.45rem;
                padding: 0.7rem;
                border-radius: 14px;
                border: 1px solid rgba(127, 16, 16, 0.12);
                background: rgba(255, 249, 230, 0.98);
                box-shadow: 0 16px 32px rgba(127, 16, 16, 0.2);
                opacity: 0;
                visibility: hidden;
                pointer-events: none;
                transform: translateY(-8px);
                transition: opacity 0.2s ease, transform 0.2s ease, visibility 0.2s ease;
                z-index: 31;
            }

            .nav-links.open {
                opacity: 1;
                visibility: visible;
                pointer-events: auto;
                transform: translateY(0);
            }

            .nav-links a {
                display: block;
                padding: 0.62rem 0.72rem;
                border-radius: 10px;
            }

            .nav-links .btn {
                width: 100%;
                text-align: center;
            }

            .menu-toggle {
                display: inline-flex;
            }

            .hero {
                padding-top: 1.2rem;
            }

            .category-grid,
            .stats {
                grid-template-columns: 1fr;
            }

            .products {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 0.7rem;
            }

            .product-carousel {
                height: 132px;
            }

            .product-carousel-slide img {
                height: 132px;
            }

            .product-carousel .carousel-dots {
                display: none;
            }

            .product img {
                height: 135px;
            }

            .product-body {
                padding: 0.72rem;
            }

            .product h4 {
                font-size: 0.88rem;
                margin-bottom: 0.38rem;
            }

            .product-tag {
                font-size: 0.62rem;
                margin-bottom: 0.35rem;
            }

            .product-meta {
                font-size: 0.74rem;
            }

            .category-card {
                grid-template-columns: 1fr;
            }

            .contact-grid {
                grid-template-columns: 1fr;
            }

            .hero-card img {
                height: 260px;
            }
        }

        @media (max-width: 420px) {
            .products {
                gap: 0.6rem;
            }

            .product img {
                height: 120px;
            }

            .product-body {
                padding: 0.62rem;
            }

            .product-meta {
                font-size: 0.7rem;
            }
        }

        #single, #kategori, #paket {
            scroll-margin-top: 80px;
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
            <div class="brand">
                <img class="brand-logo" src="{{ asset('images/logo.png') }}" alt="Logo Merry Cookies">
                <span>Merry Cookies</span>
            </div>
            <button class="menu-toggle" id="menuToggleBtn" type="button" aria-label="Toggle menu" aria-expanded="false">
                <i class="fas fa-bars" aria-hidden="true"></i>
            </button>
            <div class="nav-links">
                <a href="#kategori">Kategori</a>
                <a href="#paket">Produk</a>
                <a href="#hubungi">Hubungi Kami</a>
                <a target="_blank" href="https://wa.me/6281332879850?text=Haloo%2C%20saya%20tertarik%20untuk%20membeli%20kue%20Merry%20Cookies">Pesan Sekarang</a>
            </div>
        </nav>

        <header class="hero reveal">
            <div>
                <span class="kicker">Freshly Baked Daily</span><h1>Koleksi Kue Kering Premium yang Cantik, Renyah, dan Bikin Ketagihan</h1>
                <p>
                    Jelajahi beragam pilihan <strong>kue kering berkualitas tinggi</strong> yang dibuat dengan bahan pilihan dan 
                    perhatian pada setiap detail. Setiap varian menghadirkan perpaduan rasa yang <strong>pas</strong>, tekstur yang <strong>renyah</strong>, 
                    serta tampilan yang <strong>menggoda</strong> sejak pandangan pertama.
                </p>
                <div class="hero-actions">
                    <a href="#kategori" class="btn btn-primary">Lihat Katalog</a>
                    {{-- <button class="btn btn-secondary">Lihat Promo Minggu Ini</button> --}}
                </div>
                <div class="stats reveal-stagger">
                    <div class="stat">
                        <b>120+</b>
                        <span>Varian Rasa</span>
                    </div>
                    <div class="stat">
                        <b>20+</b>
                        <span>Varian Paket</span>
                    </div>
                    <div class="stat">
                        <b>24 Jam</b>
                        <span>Proses Cepat</span>
                    </div>
                </div>
            </div>

            <div class="hero-card">
                <img src="{{ asset('images/fotokue/gift.png') }}" alt="Produk Paket Set Kue Kering">
                <div class="hero-card-footer">
                    <div>
                        <strong>Signature Gift Box</strong>
                        <span>Mix cookies premium untuk hadiah</span>
                    </div>
                    {{-- <button class="btn btn-secondary">Detail</button> --}}
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
                <a href="#paket" style="text-decoration: none; color: inherit;">
                    <article class="category-card">
                        <div>
                            <span class="pill">BEST FOR GIFT</span>
                            <h3>Paket Set</h3>
                            <p>
                                Komposisi beberapa rasa dalam satu paket elegan. Cocok untuk hampers lebaran,
                                corporate gift, atau kejutan untuk orang tersayang.
                            </p>
                        </div>
                        <img src="{{ asset('images/fotokue/paket.png') }}" alt="Kategori Paket Set">
                    </article>
                </a>

                <a href="#single" style="text-decoration: none; color: inherit;">
                    <article class="category-card">
                        <div>
                            <span class="pill">FLEXIBLE CHOICE</span>
                            <h3>Single</h3>
                            <p>
                                Beli per varian favorit dalam kemasan single. Pas untuk stok harian,
                                tester rasa baru, atau custom kombinasi sesuai selera keluarga.
                            </p>
                        </div>
                        <img src="{{ asset('images/fotokue/single.png') }}" alt="Kategori Single">
                    </article>
                </a>
            </div>
        </section>

        {{-- <section id="produk" class="reveal">
            <h2 class="section-title">Preview Produk Terlaris</h2>
            <p class="section-subtitle">
                Contoh tampilan kartu produk untuk halaman katalog. Seluruh gambar saat ini menggunakan placeholder,
                jadi nanti tinggal ganti source image ke foto produk asli.
            </p>

            <div class="products reveal-stagger">
                <article class="product clickable-product" data-product-id="1" data-product-name="Nastar Premium" data-product-image="{{ asset('images/logo.png') }}" data-category-tag="Single" data-prices='{"bulat-250gr": 75000, "bulat-300gr": 95000, "bulat-500gr": 150000, "tabung-300ml": 85000, "tabung-750ml": 180000, "kiloan": 200000}'>
                    <img src="{{ asset('images/logo.png') }}" alt="Nastar Premium">
                    <div class="product-body">
                        <div class="product-tag">Single</div>
                        <h4>Nastar Premium</h4>
                        <div class="product-meta">
                            <span>250 gram</span>
                            <strong>Rp75.000</strong>
                        </div>
                    </div>
                </article>

                <article class="product clickable-product" data-product-id="2" data-product-name="Kastengel Butter" data-product-image="{{ asset('images/logo.png') }}" data-category-tag="Single" data-prices='{"bulat-250gr": 82000, "bulat-300gr": 98000, "bulat-500gr": 160000, "tabung-300ml": 92000, "tabung-750ml": 195000, "kiloan": 220000}'>
                    <img src="{{ asset('images/logo.png') }}" alt="Kastengel Butter">
                    <div class="product-body">
                        <div class="product-tag">Single</div>
                        <h4>Kastengel Butter</h4>
                        <div class="product-meta">
                            <span>250 gram</span>
                            <strong>Rp82.000</strong>
                        </div>
                    </div>
                </article>

                <article class="product clickable-product" data-product-id="3" data-product-name="Choco Crunch Set" data-product-image="{{ asset('images/logo.png') }}" data-category-tag="Paket Set" data-prices='{"bulat-250gr": 70000, "bulat-300gr": 88000, "bulat-500gr": 140000, "tabung-300ml": 80000, "tabung-750ml": 170000, "kiloan": 190000}'>
                    <img src="{{ asset('images/logo.png') }}" alt="Choco Crunch Set">
                    <div class="product-body">
                        <div class="product-tag">Paket Set</div>
                        <h4>Choco Crunch Set</h4>
                        <div class="product-meta">
                            <span>3 toples</span>
                            <strong>Rp199.000</strong>
                        </div>
                    </div>
                </article>

                <article class="product clickable-product" data-product-id="4" data-product-name="Classic Family Box" data-product-image="{{ asset('images/logo.png') }}" data-category-tag="Paket Set" data-prices='{"bulat-250gr": 90000, "bulat-300gr": 110000, "bulat-500gr": 180000, "tabung-300ml": 105000, "tabung-750ml": 225000, "kiloan": 250000}'>
                    <img src="{{ asset('images/logo.png') }}" alt="Classic Family Box">
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
        </section> --}}
        <section id="paket" class="reveal">
            <h2 class="section-title">Kue Paket</h2>
            <p class="section-subtitle">
                Koleksi lengkap kue kering dalam paket pilihan. Pilih sesuai kebutuhan dan selera.
            </p>

            <div class="products reveal-stagger">
                @foreach ($paketcookies ?? [] as $cookie)
                    @php
                        $rawImagePaths = $cookie->URL ?? 'placeholder.png';
                        $imagePaths = array_values(array_filter(array_map('trim', explode(';', $rawImagePaths))));
                        if (empty($imagePaths)) {
                            $imagePaths = ['placeholder.png'];
                        }
                        $imageUrls = array_map(function ($path) {
                            return asset('images/fotokue/' . $path);
                        }, $imagePaths);
                    @endphp
                    <article
                        class="product clickable-product"
                        data-product-id="{{ $cookie->id ?? $loop->iteration }}"
                        data-product-name="{{ $cookie->Name ?? 'Nama Produk' }}"
                        data-product-image="{{ asset('images/fotokue/' . ($imagePaths[0] ?? 'placeholder.png')) }}"
                        data-product-images='@json($imageUrls)'
                        data-category-tag="Paket"
                        data-base-price="{{ (int) ($cookie->price->PriceKonsumen ?? 0) }}"
                        data-prices='{{ $cookie->price->PriceKonsumen }}'
                    >
                        <div class="product-media">
                            @if (count($imagePaths) > 1)
                                <div class="product-carousel" data-carousel data-interval="3000">
                                    <div class="product-carousel-track">
                                        @foreach ($imagePaths as $imagePath)
                                            <div class="product-carousel-slide">
                                                <img src="{{ asset('images/fotokue/' . $imagePath) }}" alt="{{ $cookie->Name ?? 'Kue Paket' }}">
                                            </div>
                                        @endforeach
                                    </div>
                                    <button class="carousel-control prev" type="button" aria-label="Gambar sebelumnya">
                                        <i class="fas fa-chevron-left" aria-hidden="true"></i>
                                    </button>
                                    <button class="carousel-control next" type="button" aria-label="Gambar berikutnya">
                                        <i class="fas fa-chevron-right" aria-hidden="true"></i>
                                    </button>
                                    <div class="carousel-dots">
                                        @foreach ($imagePaths as $unused)
                                            <button class="carousel-dot {{ $loop->first ? 'active' : '' }}" type="button" aria-label="Pilih gambar {{ $loop->iteration }}"></button>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <img src="{{ asset('images/fotokue/' . ($imagePaths[0] ?? 'placeholder.png')) }}" alt="{{ $cookie->Name ?? 'Kue Paket' }}">
                            @endif
                        </div>
                        <div class="product-body">
                            <div class="product-tag">Paket</div>
                            <h4>{{ $cookie->Name ?? 'Nama Produk' }}</h4>
                            <div class="product-meta">
                                <span>{{ $cookie->Kode ?? '' }}</span>
                                <strong>Rp{{ number_format($cookie->price->PriceKonsumen ?? 0, 0, ',', '.') }}</strong>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            {{-- <div style="text-align: center; margin-top: 1.8rem;">
                <a href="{{ '#' }}" class="btn btn-primary">Lihat Semua Kue Single</a>
            </div> --}}
        </section>

        <section id="single" class="reveal">
            <h2 class="section-title">Kue Single</h2>
            <p class="section-subtitle">
                Koleksi lengkap kue kering satuan pilihan. Pilih sesuai rasa favorit dan jumlah yang sesuai kebutuhan.
            </p>

            <div class="products reveal-stagger">
                @foreach ($singlecookies ?? [] as $cookie)
                    @php
                        $rawImagePaths = $cookie->URL ?? 'placeholder.png';
                        $imagePaths = array_values(array_filter(array_map('trim', explode(';', $rawImagePaths))));
                        if (empty($imagePaths)) {
                            $imagePaths = ['placeholder.png'];
                        }
                        $imageUrls = array_map(function ($path) {
                            return asset('images/fotokue/' . $path);
                        }, $imagePaths);
                    @endphp
                    <article
                        class="product clickable-product"
                        data-product-id="{{ $cookie->id ?? $loop->iteration }}"
                        data-product-name="{{ $cookie->Name ?? 'Nama Produk' }}"
                        data-product-image="{{ asset('images/fotokue/' . ($imagePaths[0] ?? 'placeholder.png')) }}"
                        data-product-images='@json($imageUrls)'
                        data-category-tag="Single"
                        data-base-price="{{ (int) ($cookie->Price ?? 0) }}"
                        data-prices='{{ $cookie->pricesinglecustomer }}'
                    >
                        <div class="product-media">
                            @if (count($imagePaths) > 1)
                                <div class="product-carousel" data-carousel data-interval="3000">
                                    <div class="product-carousel-track">
                                        @foreach ($imagePaths as $imagePath)
                                            <div class="product-carousel-slide">
                                                <img src="{{ asset('images/fotokue/' . $imagePath) }}" alt="{{ $cookie->Name ?? 'Kue Single' }}">
                                            </div>
                                        @endforeach
                                    </div>
                                    <button class="carousel-control prev" type="button" aria-label="Gambar sebelumnya">
                                        <i class="fas fa-chevron-left" aria-hidden="true"></i>
                                    </button>
                                    <button class="carousel-control next" type="button" aria-label="Gambar berikutnya">
                                        <i class="fas fa-chevron-right" aria-hidden="true"></i>
                                    </button>
                                    <div class="carousel-dots">
                                        @foreach ($imagePaths as $unused)
                                            <button class="carousel-dot {{ $loop->first ? 'active' : '' }}" type="button" aria-label="Pilih gambar {{ $loop->iteration }}"></button>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <img src="{{ asset('images/fotokue/' . ($imagePaths[0] ?? 'placeholder.png')) }}" alt="{{ $cookie->Name ?? 'Kue Single' }}">
                            @endif
                        </div>
                        <div class="product-body">
                            <div class="product-tag">Single</div>
                            <h4>{{ $cookie->Name ?? 'Nama Produk' }}</h4>
                            <div class="product-meta">
                                <span>{{ $cookie->Kode ?? '' }}</span>
                                {{-- <strong>Rp{{ number_format($cookie->Price ?? 0, 0, ',', '.') }}</strong> --}}
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            {{-- <div style="text-align: center; margin-top: 1.8rem;">
                <a href="{{ '#' }}" class="btn btn-primary">Lihat Semua Kue Single</a>
            </div> --}}
        </section>

        <div id="order" class="cta reveal">
        <div>
            <h3>Lagi Cari Kue Kering yang Bikin Ngiler?</h3>
            <p>
                Katalog ini menampilkan berbagai pilihan kue kering dengan tampilan menarik dan mudah dijelajahi. 
                Cocok untuk melihat varian, detail produk, dan menentukan pilihan sebelum melakukan pemesanan.
            </p>
        </div>
        <a target="_blank" href="https://wa.me/6281332879850?text=Haloo%2C%20saya%20tertarik%20untuk%20membeli%20kue%20Merry%20Cookies" class="btn order-btn">Mulai Order Sekarang</a>
    </div>

            <section id="hubungi" class="reveal">
                <h2 class="section-title">Hubungi Kami</h2>
                <p class="section-subtitle">
                    Multi Prima Rasa siap membantu pemesanan, pertanyaan produk, dan kebutuhan kerja sama. Hubungi kami melalui kanal berikut.
                </p>
                <div class="contact-wrap">
                    <div class="contact-grid">
                        <a class="contact-card" target="_blank" href="https://wa.me/6281332879850?text=Halo%20Multi%20Prima%20Rasa%2C%20saya%20ingin%20bertanya%20tentang%20produk%20kue.">
                            <span class="contact-icon" aria-hidden="true"><i class="fab fa-whatsapp"></i></span>
                            <span>
                                <b>WhatsApp</b>
                                <span>+62 813-3287-9850</span>
                            </span>
                        </a>

                        <a class="contact-card" target="_blank" href="https://instagram.com/merrycookies">
                            <span class="contact-icon" aria-hidden="true"><i class="fab fa-instagram"></i></span>
                            <span>
                                <b>Instagram</b>
                                <span>@merrycookies</span>
                            </span>
                        </a>

                        <a class="contact-card" target="_blank" href="https://shopee.co.id/merrycookies">
                            <span class="contact-icon" aria-hidden="true"><i class="fas fa-store"></i></span>
                            <span>
                                <b>Shopee</b>
                                <span>merrycookies</span>
                            </span>
                        </a>

                        <a class="contact-card" target="_blank" href="https://www.tokopedia.com/merry-cookies---surabaya">
                            <span class="contact-icon" aria-hidden="true"><i class="fas fa-bag-shopping"></i></span>
                            <span>
                                <b>Tokopedia</b>
                                <span>MERRY COOKIES</span>
                            </span>
                        </a>

                        <a class="contact-card" target="_blank" href="https://share.google/nk65T7vSadW76ofuN">
                            <span class="contact-icon" aria-hidden="true"><i class="fas fa-location-dot"></i></span>
                            <span>
                                <b>Alamat</b>
                                <span>Wisma Lidah Kulon F21, Lidah Kulon, Lakarsantri, Surabaya, Jawa Timur</span>
                            </span>
                        </a>
                    </div>
                </div>
            </section>

        <footer>
            <p>Merry Cookies Catalog - Made for a sweet treat.</p>
        </footer>

        <!-- Kemasan Info Modal -->
        <div class="kemasan-modal" id="kemasanModal">
            <div class="kemasan-modal-content">
                <div class="kemasan-modal-header">
                    <h4>Referensi Kemasan</h4>
                    <button class="modal-close" id="kemasanModalClose" type="button">&times;</button>
                </div>
                <div class="kemasan-modal-body">
                    <img src="{{ asset('images/fotokue/kemasan.png') }}" alt="Referensi jenis kemasan kue">
                </div>
            </div>
        </div>

        <!-- Product Detail Modal -->
        <div class="modal" id="productModal">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h3 style="margin: 0; color: #5c0f0f; font-family: 'Fraunces', Georgia, serif;">Detail Produk</h3>
                    </div>
                    <button class="modal-close" data-close-modal>&times;</button>
                </div>
                <div class="modal-body">
                    <div class="modal-image-carousel" id="modalImageCarousel">
                        <div class="modal-image-track" id="modalImageTrack"></div>
                        <button class="carousel-control prev" id="modalImagePrev" type="button" aria-label="Gambar sebelumnya">
                            <i class="fas fa-chevron-left" aria-hidden="true"></i>
                        </button>
                        <button class="carousel-control next" id="modalImageNext" type="button" aria-label="Gambar berikutnya">
                            <i class="fas fa-chevron-right" aria-hidden="true"></i>
                        </button>
                        <div class="carousel-dots" id="modalImageDots"></div>
                    </div>
                    <div class="modal-tag" id="modalTag">Single</div>
                    <h2 class="modal-title" id="modalTitle">Nama Produk</h2>


                    <div class="price-section">
                        <div class="price-label">Harga:</div>
                        <div class="price-display" id="modalPrice">Rp75.000</div>
                    </div>
                    <div class="singledetail d-none">
                        <div class="category-section">
                            <label class="category-label">
                                Pilih Kemasan:
                                <button class="kemasan-info-btn" id="kemasanInfoBtn" type="button" title="Lihat referensi kemasan" aria-label="Lihat foto kemasan">
                                    <i class="fas fa-circle-info" aria-hidden="true"></i>
                                </button>
                            </label>
                            <div class="category-list" id="categoryList">
                                @foreach($singlecategories as $name => $slug)
                                    <button class="category-btn {{ $loop->first ? 'active' : '' }}" data-category="{{ $slug[1] }}">{{ $name }}</button>
                                @endforeach
                            </div>
                        </div>

                        <div class="quantity-section">
                            <label class="category-label">Pilih Jumlah:</label>
                            <div class="quantity-list" id="quantityList">
                                <button class="quantity-btn active" data-qty="satuan">Satuan</button>
                                <button class="quantity-btn" data-qty="grosir">Isi 12 Toples</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-actions">
                    <button class="btn-primary btn-cancel" data-close-modal>Tutup</button>
                    {{-- <button class="btn-primary">Tambah ke Keranjang</button> --}}
                    {{-- <button class="btn-cancel" data-close-modal>Batal</button> --}}
                </div>
            </div>
        </div>
    </div>

    <button id="backToTopBtn" class="back-to-top" type="button" aria-label="Kembali ke atas" title="Kembali ke atas"><i class="fas fa-arrow-up"></i></button>

    <script>
        const revealElements = document.querySelectorAll('.reveal, .reveal-stagger');

        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('show');
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.04,
                rootMargin: '0px 0px -8% 0px'
            });

            revealElements.forEach((element) => {
                observer.observe(element);
            });
        } else {
            // Fallback for older mobile browsers.
            revealElements.forEach((element) => {
                element.classList.add('show');
            });
        }

        // Product Modal Functionality
        const modal = document.getElementById('productModal');
        const modalImageCarousel = document.getElementById('modalImageCarousel');
        const modalImageTrack = document.getElementById('modalImageTrack');
        const modalImageDots = document.getElementById('modalImageDots');
        const modalImagePrev = document.getElementById('modalImagePrev');
        const modalImageNext = document.getElementById('modalImageNext');
        const singleDetail = document.querySelector('.singledetail');
        const categoryBtns = document.querySelectorAll('.category-btn');
        const quantityBtns = document.querySelectorAll('.quantity-btn');
        const productCards = document.querySelectorAll('.clickable-product');
        const backToTopBtn = document.getElementById('backToTopBtn');
        const menuToggleBtn = document.getElementById('menuToggleBtn');
        const navLinks = document.querySelector('.nav-links');
        let currentPrices = {};
        let currentCategory = null;
        let currentQuantity = null;
        let currentMultiplier = 1;
        let modalImageIndex = 0;
        let modalImageCount = 0;
        let modalImageAutoplayId = null;
        let modalTouchStartX = 0;

        function stopModalImageAutoplay() {
            if (modalImageAutoplayId) {
                clearInterval(modalImageAutoplayId);
                modalImageAutoplayId = null;
            }
        }

        function renderModalImagePosition() {
            if (!modalImageTrack) {
                return;
            }

            modalImageTrack.style.transform = `translateX(-${modalImageIndex * 100}%)`;
            if (modalImageDots) {
                modalImageDots.querySelectorAll('.carousel-dot').forEach((dot, dotIndex) => {
                    dot.classList.toggle('active', dotIndex === modalImageIndex);
                });
            }
        }

        function startModalImageAutoplay() {
            stopModalImageAutoplay();
            if (!modal.classList.contains('active') || modalImageCount <= 1) {
                return;
            }

            modalImageAutoplayId = setInterval(() => {
                modalImageIndex = (modalImageIndex + 1) % modalImageCount;
                renderModalImagePosition();
            }, 3500);
        }

        function setupModalImages(imageList, fallbackImage, productName) {
            const safeImages = (Array.isArray(imageList) ? imageList : []).filter(Boolean);
            const finalImages = safeImages.length ? safeImages : [fallbackImage];
            modalImageIndex = 0;
            modalImageCount = finalImages.length;

            if (modalImageTrack) {
                modalImageTrack.innerHTML = finalImages.map((src) => {
                    return `<div class="modal-image-slide"><img class="modal-image" src="${src}" alt="${productName}"></div>`;
                }).join('');
            }

            if (modalImageDots) {
                modalImageDots.innerHTML = finalImages.map((_, idx) => {
                    return `<button class="carousel-dot ${idx === 0 ? 'active' : ''}" type="button" aria-label="Pilih gambar ${idx + 1}"></button>`;
                }).join('');
            }

            const showControls = finalImages.length > 1;
            if (modalImagePrev) {
                modalImagePrev.style.display = showControls ? 'inline-flex' : 'none';
            }
            if (modalImageNext) {
                modalImageNext.style.display = showControls ? 'inline-flex' : 'none';
            }
            if (modalImageDots) {
                modalImageDots.style.display = showControls ? 'flex' : 'none';
            }

            renderModalImagePosition();

            if (modalImageDots) {
                modalImageDots.querySelectorAll('.carousel-dot').forEach((dot, dotIndex) => {
                    dot.addEventListener('click', (e) => {
                        e.preventDefault();
                        e.stopPropagation();
                        modalImageIndex = dotIndex;
                        renderModalImagePosition();
                        startModalImageAutoplay();
                    });
                });
            }
        }

        function openModal() {
            modal.classList.add('active');
            document.body.classList.add('modal-open');
        }

        function closeModal() {
            modal.classList.remove('active');
            document.body.classList.remove('modal-open');
            stopModalImageAutoplay();
        }

        // Open modal on product click
        productCards.forEach((card) => {
            card.addEventListener('click', () => {
                const productId = card.getAttribute('data-product-id');
                const productName = card.getAttribute('data-product-name');
                const productImage = card.getAttribute('data-product-image');
                const rawProductImages = card.getAttribute('data-product-images');
                const categoryTag = card.getAttribute('data-category-tag');
                const rawPrices = card.getAttribute('data-prices');
                const basePrice = parseInt(card.getAttribute('data-base-price') || '0', 10);
                let prices = null;
                let productImages = [];
                console.log('Raw Prices from Data Attribute:', rawPrices);

                if (rawProductImages) {
                    try {
                        productImages = JSON.parse(rawProductImages);
                    } catch (error) {
                        productImages = [];
                    }
                }

                if (rawPrices) {
                    try {
                        prices = JSON.parse(rawPrices);
                    } catch (error) {
                        prices = null;
                    }
                }

                // Set modal content
                setupModalImages(productImages, productImage, productName);
                document.getElementById('modalTitle').textContent = productName;
                document.getElementById('modalTag').textContent = categoryTag;
                currentPrices = prices;

                const isSingleProduct = String(categoryTag || '').toLowerCase() === 'single';
                if (singleDetail) {
                    singleDetail.classList.toggle('d-none', !isSingleProduct);
                }

                // Reset and set first category as active
                categoryBtns.forEach((btn) => btn.classList.remove('active'));
                const firstBtn = document.querySelector('.category-btn[data-category="bulat-250gr"]');
                if (firstBtn) {
                    firstBtn.classList.add('active');
                    currentCategory = '250gr';
                } else if (categoryBtns.length > 0) {
                    categoryBtns[0].classList.add('active');
                    currentCategory = categoryBtns[0].getAttribute('data-category');
                }
                console.log('Current Category on Modal Open:', currentCategory);
                console.log('prices on Modal Open:', currentPrices);

                // Reset quantity to satuan on each modal open
                quantityBtns.forEach((btn) => btn.classList.remove('active'));
                const singleQtyBtn = document.querySelector('.quantity-btn[data-qty="satuan"]');
                if (singleQtyBtn) {
                    singleQtyBtn.classList.add('active');
                }
                currentQuantity = 'satuan';
                console.log('Current Quantity on Modal Open:', currentQuantity, currentCategory);
                if (currentCategory) {
                    updatePrice(currentCategory, currentQuantity);
                }

                // Show modal
                openModal();
                startModalImageAutoplay();
            });
        });

        // Close modal
        document.querySelectorAll('[data-close-modal]').forEach((btn) => {
            btn.addEventListener('click', () => {
                closeModal();
            });
        });

        // Close modal when clicking outside
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                closeModal();
            }
        });

        modalImagePrev?.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            if (modalImageCount <= 1) {
                return;
            }
            modalImageIndex = (modalImageIndex - 1 + modalImageCount) % modalImageCount;
            renderModalImagePosition();
            startModalImageAutoplay();
        });

        modalImageNext?.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            if (modalImageCount <= 1) {
                return;
            }
            modalImageIndex = (modalImageIndex + 1) % modalImageCount;
            renderModalImagePosition();
            startModalImageAutoplay();
        });

        modalImageCarousel?.addEventListener('mouseenter', stopModalImageAutoplay);
        modalImageCarousel?.addEventListener('mouseleave', startModalImageAutoplay);

        modalImageCarousel?.addEventListener('touchstart', (e) => {
            modalTouchStartX = e.changedTouches[0].clientX;
        }, { passive: true });

        modalImageCarousel?.addEventListener('touchend', (e) => {
            const deltaX = e.changedTouches[0].clientX - modalTouchStartX;
            if (Math.abs(deltaX) <= 35 || modalImageCount <= 1) {
                return;
            }

            if (deltaX < 0) {
                modalImageIndex = (modalImageIndex + 1) % modalImageCount;
            } else {
                modalImageIndex = (modalImageIndex - 1 + modalImageCount) % modalImageCount;
            }

            renderModalImagePosition();
            startModalImageAutoplay();
        }, { passive: true });

        // Category button click handler
        categoryBtns.forEach((btn) => {
            btn.addEventListener('click', () => {
                categoryBtns.forEach((b) => b.classList.remove('active'));
                btn.classList.add('active');
                const category = btn.getAttribute('data-category');
                const qty = document.querySelector('.quantity-btn.active')?.getAttribute('data-qty');
                updatePrice(category, qty);
            });
        });

        // Quantity button click handler
        quantityBtns.forEach((btn) => {
            btn.addEventListener('click', () => {
                quantityBtns.forEach((b) => b.classList.remove('active'));
                btn.classList.add('active');
                const category = document.querySelector('.category-btn.active')?.getAttribute('data-category');
                const qty = btn.getAttribute('data-qty');
                updatePrice(category, qty);
            });
        });

        // Update price display
        function updatePrice(category, qty) {
            if (qty == 'grosir' && category != 'Kiloan') {
                category = category + 'G';
            }
            console.log('Updating price for category:', category);
            console.log('Current Prices:', currentPrices);
            const normalized = String(category || '').toLowerCase().trim();
            let price = currentPrices[category] ?? currentPrices;

            if (price) {
                const finalPrice = Number(price) * currentMultiplier;
                const formattedPrice = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0,
                }).format(finalPrice);
                document.getElementById('modalPrice').textContent = formattedPrice;
            }
        }

        // Kemasan info modal
        const kemasanModal = document.getElementById('kemasanModal');
        const kemasanInfoBtn = document.getElementById('kemasanInfoBtn');
        const kemasanModalClose = document.getElementById('kemasanModalClose');

        kemasanInfoBtn?.addEventListener('click', (e) => {
            e.stopPropagation();
            kemasanModal.classList.add('active');
        });

        kemasanModalClose?.addEventListener('click', () => {
            kemasanModal.classList.remove('active');
        });

        kemasanModal?.addEventListener('click', (e) => {
            if (e.target === kemasanModal) {
                kemasanModal.classList.remove('active');
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeModal();
                kemasanModal?.classList.remove('active');
            }
        });

        // Floating back-to-top button
        function toggleBackToTopVisibility() {
            if (!backToTopBtn) {
                return;
            }

            const shouldShow = window.scrollY > 280;
            backToTopBtn.classList.toggle('show', shouldShow);
        }

        if (backToTopBtn) {
            window.addEventListener('scroll', toggleBackToTopVisibility, { passive: true });
            toggleBackToTopVisibility();

            backToTopBtn.addEventListener('click', () => {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        }

        // Mobile burger menu
        if (menuToggleBtn && navLinks) {
            menuToggleBtn.addEventListener('click', () => {
                const isOpen = navLinks.classList.toggle('open');
                menuToggleBtn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                const icon = menuToggleBtn.querySelector('i');
                if (icon) {
                    icon.classList.toggle('fa-bars', !isOpen);
                    icon.classList.toggle('fa-xmark', isOpen);
                }
            });

            navLinks.querySelectorAll('a').forEach((link) => {
                link.addEventListener('click', () => {
                    if (window.innerWidth <= 768) {
                        navLinks.classList.remove('open');
                        menuToggleBtn.setAttribute('aria-expanded', 'false');
                        const icon = menuToggleBtn.querySelector('i');
                        if (icon) {
                            icon.classList.add('fa-bars');
                            icon.classList.remove('fa-xmark');
                        }
                    }
                });
            });

            window.addEventListener('resize', () => {
                if (window.innerWidth > 768) {
                    navLinks.classList.remove('open');
                    menuToggleBtn.setAttribute('aria-expanded', 'false');
                    const icon = menuToggleBtn.querySelector('i');
                    if (icon) {
                        icon.classList.add('fa-bars');
                        icon.classList.remove('fa-xmark');
                    }
                }
            });
        }

        // Product image carousel (autoplay + controls + swipe)
        document.querySelectorAll('[data-carousel]').forEach((carousel) => {
            const track = carousel.querySelector('.product-carousel-track');
            const slides = Array.from(carousel.querySelectorAll('.product-carousel-slide'));
            const prevBtn = carousel.querySelector('.carousel-control.prev');
            const nextBtn = carousel.querySelector('.carousel-control.next');
            const dots = Array.from(carousel.querySelectorAll('.carousel-dot'));
            const intervalMs = parseInt(carousel.getAttribute('data-interval') || '3500', 10);

            if (!track || slides.length <= 1) {
                return;
            }

            let index = 0;
            let autoplayId = null;
            let touchStartX = 0;
            let touchEndX = 0;

            function render() {
                track.style.transform = `translateX(-${index * 100}%)`;
                dots.forEach((dot, dotIndex) => {
                    dot.classList.toggle('active', dotIndex === index);
                });
            }

            function next() {
                index = (index + 1) % slides.length;
                render();
            }

            function prev() {
                index = (index - 1 + slides.length) % slides.length;
                render();
            }

            function stopAutoplay() {
                if (autoplayId) {
                    clearInterval(autoplayId);
                    autoplayId = null;
                }
            }

            function startAutoplay() {
                stopAutoplay();
                autoplayId = setInterval(next, intervalMs);
            }

            prevBtn?.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                prev();
                startAutoplay();
            });

            nextBtn?.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                next();
                startAutoplay();
            });

            dots.forEach((dot, dotIndex) => {
                dot.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    index = dotIndex;
                    render();
                    startAutoplay();
                });
            });

            carousel.addEventListener('mouseenter', stopAutoplay);
            carousel.addEventListener('mouseleave', startAutoplay);

            carousel.addEventListener('touchstart', (e) => {
                touchStartX = e.changedTouches[0].clientX;
            }, { passive: true });

            carousel.addEventListener('touchend', (e) => {
                touchEndX = e.changedTouches[0].clientX;
                const deltaX = touchEndX - touchStartX;
                if (Math.abs(deltaX) > 35) {
                    if (deltaX < 0) {
                        next();
                    } else {
                        prev();
                    }
                    startAutoplay();
                }
            }, { passive: true });

            render();
            startAutoplay();
        });
    </script>
</body>
</html>
