@extends('layouts.guest-layout')

@section('title', 'Daily Meals - TapEats')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;1,9..40,400&display=swap" rel="stylesheet">

<style>
    :root {
        --primary: #FF6B35;
        --primary-light: #FF8C5A;
        --primary-dark: #E5531A;
        --secondary: #2D2A26;
        --accent: #FFD166;
        --success: #06D6A0;
        --info: #118AB2;
        --surface: #FFFBF7;
        --card-bg: #FFFFFF;
        --border: #F0EBE3;
        --text-primary: #1A1714;
        --text-muted: #8B7D72;
        --sidebar-width: 280px;
        --radius: 16px;
        --radius-sm: 10px;
        --shadow: 0 4px 24px rgba(255,107,53,0.08);
        --shadow-hover: 0 12px 40px rgba(255,107,53,0.18);
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
        font-family: 'DM Sans', sans-serif;
        background: var(--surface);
        color: var(--text-primary);
    }

    /* ── HERO ── */
    .hero {
        background: var(--secondary);
        position: relative;
        overflow: hidden;
        padding: 3.5rem 0 2.5rem;
    }
    .hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(ellipse at 80% 50%, rgba(255,107,53,0.25) 0%, transparent 65%),
                    radial-gradient(ellipse at 10% 80%, rgba(255,209,102,0.12) 0%, transparent 50%);
    }
    .hero .blob {
        position: absolute;
        border-radius: 50%;
        filter: blur(60px);
        opacity: 0.15;
    }
    .hero .blob-1 { width: 380px; height: 380px; background: var(--primary); top: -100px; right: -80px; }
    .hero .blob-2 { width: 260px; height: 260px; background: var(--accent); bottom: -80px; left: 10%; }
    .hero-inner { position: relative; z-index: 1; }
    .hero-badge {
        display: inline-flex; align-items: center; gap: 0.4rem;
        background: rgba(255,107,53,0.2); border: 1px solid rgba(255,107,53,0.35);
        color: #FFAA80; font-size: 0.75rem; font-weight: 600; letter-spacing: 0.06em;
        padding: 0.3rem 0.75rem; border-radius: 20px; margin-bottom: 1rem; text-transform: uppercase;
    }
    .hero-title {
        font-family: 'Sora', sans-serif;
        font-size: clamp(2rem, 5vw, 3.2rem);
        font-weight: 800;
        color: #fff;
        line-height: 1.1;
        margin-bottom: 0.75rem;
    }
    .hero-title span { color: var(--primary); }
    .hero-subtitle { color: rgba(255,255,255,0.6); font-size: 1rem; margin-bottom: 2rem; max-width: 480px; }

    .hero-stats {
        display: flex; gap: 2rem; flex-wrap: wrap;
    }
    .hero-stat {
        display: flex; align-items: center; gap: 0.6rem;
    }
    .hero-stat-num {
        font-family: 'Sora', sans-serif; font-size: 1.6rem; font-weight: 800; color: #fff;
    }
    .hero-stat-label { color: rgba(255,255,255,0.5); font-size: 0.78rem; line-height: 1.2; }
    .hero-stat-icon {
        width: 42px; height: 42px; border-radius: 12px;
        background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.12);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem; flex-shrink: 0;
    }

    /* ── LAYOUT ── */
    .page-layout {
        display: flex; gap: 1.5rem;
        max-width: 1400px; margin: 0 auto;
        padding: 2rem 1.5rem;
        align-items: flex-start;
    }

    /* ── SIDEBAR FILTER ── */
    .filter-panel {
        width: var(--sidebar-width);
        flex-shrink: 0;
        background: var(--card-bg);
        border-radius: var(--radius);
        border: 1px solid var(--border);
        padding: 1.5rem;
        position: sticky;
        top: 1.5rem;
        box-shadow: var(--shadow);
    }
    .filter-panel-title {
        font-family: 'Sora', sans-serif;
        font-size: 0.9rem; font-weight: 700; letter-spacing: 0.05em;
        text-transform: uppercase; color: var(--text-muted);
        margin-bottom: 1.25rem;
        display: flex; align-items: center; gap: 0.5rem;
    }

    /* Search input */
    .search-wrap { position: relative; margin-bottom: 1.25rem; }
    .search-wrap input {
        width: 100%; padding: 0.65rem 0.9rem 0.65rem 2.5rem;
        border: 1.5px solid var(--border); border-radius: var(--radius-sm);
        font-family: 'DM Sans', sans-serif; font-size: 0.9rem; color: var(--text-primary);
        background: var(--surface); outline: none; transition: border-color 0.2s;
    }
    .search-wrap input:focus { border-color: var(--primary); }
    .search-wrap input::placeholder { color: var(--text-muted); }
    .search-icon {
        position: absolute; left: 0.8rem; top: 50%; transform: translateY(-50%);
        color: var(--text-muted); font-size: 0.9rem; pointer-events: none;
    }

    /* Filter section */
    .filter-section { margin-bottom: 1.25rem; }
    .filter-label {
        font-size: 0.78rem; font-weight: 600; color: var(--text-muted);
        text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.6rem;
        display: block;
    }

    /* Pill toggles */
    .pill-group { display: flex; flex-direction: column; gap: 0.4rem; }
    .pill-option { display: none; }
    .pill-label {
        display: flex; align-items: center; justify-content: space-between;
        padding: 0.5rem 0.75rem; border-radius: 9px; cursor: pointer;
        font-size: 0.88rem; color: var(--text-primary);
        border: 1.5px solid var(--border); transition: all 0.2s;
        user-select: none;
    }
    .pill-label:hover { border-color: var(--primary); background: #FFF5F0; }
    .pill-option:checked + .pill-label {
        background: var(--primary); color: #fff; border-color: var(--primary);
    }
    .pill-count {
        font-size: 0.72rem; background: rgba(255,255,255,0.25);
        padding: 0.1rem 0.45rem; border-radius: 20px;
    }
    .pill-option:not(:checked) + .pill-label .pill-count {
        background: var(--border); color: var(--text-muted);
    }

    /* Select dropdown */
    .styled-select {
        width: 100%; padding: 0.6rem 0.85rem;
        border: 1.5px solid var(--border); border-radius: var(--radius-sm);
        font-family: 'DM Sans', sans-serif; font-size: 0.88rem;
        color: var(--text-primary); background: var(--surface);
        outline: none; cursor: pointer; transition: border-color 0.2s;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%238B7D72' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
        background-repeat: no-repeat; background-position: right 0.85rem center;
        padding-right: 2rem;
    }
    .styled-select:focus { border-color: var(--primary); }

    /* Apply / Clear buttons */
    .btn-apply {
        width: 100%; padding: 0.7rem; border-radius: var(--radius-sm);
        background: var(--primary); color: #fff; font-family: 'Sora', sans-serif;
        font-size: 0.88rem; font-weight: 700; border: none; cursor: pointer;
        transition: background 0.2s, transform 0.15s; margin-bottom: 0.5rem;
    }
    .btn-apply:hover { background: var(--primary-dark); transform: translateY(-1px); }
    .btn-clear {
        width: 100%; padding: 0.65rem; border-radius: var(--radius-sm);
        background: transparent; color: var(--text-muted);
        font-family: 'DM Sans', sans-serif; font-size: 0.85rem;
        border: 1.5px solid var(--border); cursor: pointer;
        transition: all 0.2s;
    }
    .btn-clear:hover { border-color: var(--primary); color: var(--primary); }

    /* Quick info */
    .quick-info { margin-top: 1.25rem; padding-top: 1.25rem; border-top: 1px solid var(--border); }
    .quick-info-item {
        display: flex; align-items: center; gap: 0.6rem;
        font-size: 0.82rem; color: var(--text-muted); margin-bottom: 0.6rem;
    }
    .quick-info-icon {
        width: 28px; height: 28px; border-radius: 8px; display: flex;
        align-items: center; justify-content: center; font-size: 0.85rem; flex-shrink: 0;
    }

    /* ── MAIN CONTENT ── */
    .content-area { flex: 1; min-width: 0; }

    /* Active filters bar */
    .active-filters {
        display: flex; align-items: center; flex-wrap: wrap; gap: 0.5rem;
        margin-bottom: 1.25rem;
    }
    .active-filter-tag {
        display: inline-flex; align-items: center; gap: 0.4rem;
        background: #FFF0E8; border: 1px solid #FFD0B5; color: var(--primary);
        border-radius: 20px; padding: 0.3rem 0.75rem; font-size: 0.8rem; font-weight: 600;
    }
    .active-filter-tag a { color: inherit; text-decoration: none; font-size: 1rem; line-height: 1; }

    /* Category section */
    .category-block { margin-bottom: 2.5rem; }
    .category-header {
        display: flex; align-items: center; gap: 0.75rem;
        margin-bottom: 1.25rem;
    }
    .category-icon {
        width: 44px; height: 44px; border-radius: 12px;
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        display: flex; align-items: center; justify-content: center;
        font-size: 1.25rem; flex-shrink: 0;
    }
    .category-name {
        font-family: 'Sora', sans-serif; font-size: 1.25rem; font-weight: 700; color: var(--text-primary);
    }
    .category-count {
        font-size: 0.8rem; color: var(--text-muted); background: var(--border);
        padding: 0.2rem 0.6rem; border-radius: 20px; margin-left: auto;
    }
    .category-divider {
        flex: 1; height: 1px; background: var(--border);
    }

    /* Meal card grid */
    .meals-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 1.25rem;
    }

    /* ── MEAL CARD ── */
    .meal-card {
        background: var(--card-bg);
        border-radius: var(--radius);
        border: 1px solid var(--border);
        overflow: hidden;
        display: flex; flex-direction: column;
        transition: box-shadow 0.25s, transform 0.25s;
        cursor: pointer;
    }
    .meal-card:hover {
        box-shadow: var(--shadow-hover);
        transform: translateY(-4px);
    }
    .meal-card:hover .meal-img { transform: scale(1.05); }

    .meal-img-wrap {
        position: relative; overflow: hidden; aspect-ratio: 4/3; flex-shrink: 0;
    }
    .meal-img {
        width: 100%; height: 100%; object-fit: cover; transition: transform 0.4s ease;
    }
    .meal-img-overlay {
        position: absolute; inset: 0;
        background: linear-gradient(to top, rgba(26,23,20,0.5) 0%, transparent 50%);
    }

    /* Badges on image */
    .img-badge-tl {
        position: absolute; top: 10px; left: 10px;
        display: flex; flex-direction: column; gap: 0.3rem; z-index: 2;
    }
    .img-badge-tr {
        position: absolute; top: 10px; right: 10px; z-index: 2;
    }
    .badge-pill {
        display: inline-flex; align-items: center; gap: 0.3rem;
        padding: 0.3rem 0.6rem; border-radius: 20px;
        font-size: 0.7rem; font-weight: 700; white-space: nowrap;
        backdrop-filter: blur(6px);
    }
    .badge-featured { background: rgba(255,209,102,0.95); color: #7A5C00; }
    .badge-discount { background: rgba(255,107,53,0.95); color: #fff; }
    .badge-spicy { background: rgba(220,38,38,0.9); color: #fff; }

    /* Card body */
    .meal-body { padding: 1rem; flex: 1; display: flex; flex-direction: column; }
    .meal-supplier {
        font-size: 0.72rem; color: var(--text-muted); margin-bottom: 0.3rem;
        display: flex; align-items: center; gap: 0.3rem;
    }
    .meal-name {
        font-family: 'Sora', sans-serif; font-size: 1rem; font-weight: 700;
        color: var(--text-primary); margin-bottom: 0.35rem; line-height: 1.25;
    }
    .meal-desc {
        font-size: 0.8rem; color: var(--text-muted); line-height: 1.5;
        margin-bottom: 0.6rem; flex: 1;
    }

    /* Dietary tags */
    .dietary-tags { display: flex; flex-wrap: wrap; gap: 0.3rem; margin-bottom: 0.75rem; }
    .dtag {
        display: inline-flex; align-items: center; gap: 0.2rem;
        padding: 0.2rem 0.5rem; border-radius: 6px;
        font-size: 0.68rem; font-weight: 600;
    }
    .dtag-veg { background: #ECFDF5; color: #059669; }
    .dtag-vegan { background: #F0FDF4; color: #16A34A; }
    .dtag-gluten { background: #EFF6FF; color: #2563EB; }
    .dtag-halal { background: #F5F3FF; color: #7C3AED; }

    /* Meta row */
    .meal-meta {
        display: flex; gap: 0.75rem; flex-wrap: wrap;
        font-size: 0.72rem; color: var(--text-muted); margin-bottom: 0.85rem;
    }
    .meal-meta span { display: flex; align-items: center; gap: 0.25rem; }

    /* Price + Cart row */
    .meal-footer {
        display: flex; align-items: center; justify-content: space-between; gap: 0.5rem;
    }
    .meal-price { display: flex; flex-direction: column; }
    .price-original {
        font-size: 0.72rem; color: var(--text-muted); text-decoration: line-through;
    }
    .price-main {
        font-family: 'Sora', sans-serif; font-size: 1.1rem; font-weight: 800;
        color: var(--primary);
    }

    .btn-cart {
        display: flex; align-items: center; gap: 0.4rem;
        padding: 0.55rem 1rem; border-radius: var(--radius-sm);
        background: var(--primary); color: #fff;
        font-family: 'Sora', sans-serif; font-size: 0.82rem; font-weight: 700;
        border: none; cursor: pointer; transition: background 0.2s, transform 0.15s;
        white-space: nowrap; flex-shrink: 0;
    }
    .btn-cart:hover { background: var(--primary-dark); transform: scale(1.04); }
    .btn-cart.added { background: var(--success); }

    /* Stock pill */
    .stock-indicator {
        font-size: 0.72rem; margin-top: 0.6rem;
        display: flex; align-items: center; gap: 0.3rem;
    }
    .stock-dot { width: 6px; height: 6px; border-radius: 50%; flex-shrink: 0; }

    /* ── EMPTY STATE ── */
    .empty-state {
        text-align: center; padding: 5rem 2rem;
        background: var(--card-bg); border-radius: var(--radius);
        border: 1px solid var(--border);
    }
    .empty-icon { font-size: 4rem; margin-bottom: 1rem; opacity: 0.3; }
    .empty-title { font-family: 'Sora', sans-serif; font-size: 1.4rem; font-weight: 700; margin-bottom: 0.5rem; }
    .empty-sub { color: var(--text-muted); margin-bottom: 1.5rem; }
    .btn-reset {
        display: inline-flex; align-items: center; gap: 0.5rem;
        padding: 0.65rem 1.5rem; border-radius: var(--radius-sm);
        background: var(--primary); color: #fff; text-decoration: none;
        font-family: 'Sora', sans-serif; font-size: 0.88rem; font-weight: 700;
        transition: background 0.2s;
    }
    .btn-reset:hover { background: var(--primary-dark); color: #fff; }

    /* ── CART SIDEBAR ── */
    .cart-sidebar {
        position: fixed; right: 0; top: 0; height: 100vh; width: 390px;
        background: var(--card-bg); box-shadow: -4px 0 30px rgba(0,0,0,0.12);
        transform: translateX(100%); transition: transform 0.35s cubic-bezier(0.4,0,0.2,1);
        z-index: 1060; display: flex; flex-direction: column;
        font-family: 'DM Sans', sans-serif;
    }
    .cart-sidebar.active { transform: translateX(0); }
    .cart-overlay {
        position: fixed; inset: 0; background: rgba(26,23,20,0.55);
        backdrop-filter: blur(3px); display: none; z-index: 1050;
    }
    .cart-overlay.active { display: block; }

    .cart-header {
        display: flex; align-items: center; justify-content: space-between;
        padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border);
    }
    .cart-title { font-family: 'Sora', sans-serif; font-size: 1.1rem; font-weight: 700; }
    .cart-close {
        width: 34px; height: 34px; border-radius: 10px; border: 1.5px solid var(--border);
        background: none; cursor: pointer; display: flex; align-items: center; justify-content: center;
        font-size: 1rem; color: var(--text-muted); transition: all 0.2s;
    }
    .cart-close:hover { border-color: var(--primary); color: var(--primary); }

    .cart-items { flex: 1; overflow-y: auto; padding: 1.25rem 1.5rem; }
    .cart-empty { text-align: center; padding: 3rem 1rem; color: var(--text-muted); }
    .cart-empty-icon { font-size: 3.5rem; opacity: 0.3; margin-bottom: 0.75rem; }

    .cart-item {
        display: flex; align-items: center; gap: 0.75rem;
        padding: 0.85rem; border-radius: var(--radius-sm);
        border: 1px solid var(--border); margin-bottom: 0.75rem;
        transition: box-shadow 0.2s;
    }
    .cart-item:hover { box-shadow: 0 2px 10px rgba(0,0,0,0.06); }
    .cart-item-img {
        width: 56px; height: 56px; border-radius: 10px; object-fit: cover; flex-shrink: 0;
    }
    .cart-item-info { flex: 1; min-width: 0; }
    .cart-item-name {
        font-family: 'Sora', sans-serif; font-size: 0.85rem; font-weight: 700;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-bottom: 0.2rem;
    }
    .cart-item-price { font-size: 0.8rem; color: var(--primary); font-weight: 600; }
    .cart-item-actions { display: flex; align-items: center; gap: 0.4rem; margin-top: 0.4rem; }
    .qty-btn {
        width: 26px; height: 26px; border-radius: 8px; border: 1.5px solid var(--border);
        background: none; cursor: pointer; font-size: 0.9rem; display: flex;
        align-items: center; justify-content: center; transition: all 0.15s;
    }
    .qty-btn:hover { border-color: var(--primary); color: var(--primary); }
    .qty-num { font-size: 0.85rem; font-weight: 700; min-width: 20px; text-align: center; }
    .cart-item-del {
        width: 28px; height: 28px; border-radius: 8px; border: none;
        background: #FFF0EE; color: #EF4444; cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.85rem; margin-left: auto; transition: background 0.2s;
    }
    .cart-item-del:hover { background: #FEE2E2; }

    .cart-footer { padding: 1.25rem 1.5rem; border-top: 1px solid var(--border); }
    .cart-total-row {
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 1rem;
    }
    .cart-total-label { font-size: 0.9rem; color: var(--text-muted); }
    .cart-total-amount { font-family: 'Sora', sans-serif; font-size: 1.4rem; font-weight: 800; color: var(--primary); }
    .btn-checkout {
        width: 100%; padding: 0.9rem; border-radius: var(--radius-sm);
        background: var(--primary); color: #fff; font-family: 'Sora', sans-serif;
        font-size: 0.95rem; font-weight: 700; border: none; cursor: pointer;
        transition: background 0.2s, transform 0.15s; display: flex;
        align-items: center; justify-content: center; gap: 0.5rem;
    }
    .btn-checkout:hover:not(:disabled) { background: var(--primary-dark); transform: translateY(-1px); }
    .btn-checkout:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }

    /* ── FAB ── */
    .fab-cart {
        position: fixed; bottom: 1.5rem; right: 1.5rem; z-index: 1040;
        width: 58px; height: 58px; border-radius: 50%;
        background: var(--primary); color: #fff; border: none; cursor: pointer;
        box-shadow: 0 6px 20px rgba(255,107,53,0.45);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.3rem; transition: transform 0.2s, box-shadow 0.2s;
    }
    .fab-cart:hover { transform: scale(1.1); box-shadow: 0 10px 28px rgba(255,107,53,0.55); }
    .fab-badge {
        position: absolute; top: -4px; right: -4px;
        min-width: 20px; height: 20px; border-radius: 10px;
        background: var(--secondary); color: #fff;
        font-size: 0.7rem; font-weight: 700;
        display: none; align-items: center; justify-content: center;
        padding: 0 4px; border: 2px solid var(--surface);
    }
    .fab-badge.show { display: flex; }

    /* ── TOAST ── */
    .toast-container {
        position: fixed; bottom: 5rem; right: 1.5rem; z-index: 2000;
        display: flex; flex-direction: column; gap: 0.5rem; pointer-events: none;
    }
    .toast-msg {
        background: var(--secondary); color: #fff;
        padding: 0.65rem 1rem; border-radius: 10px;
        font-size: 0.85rem; font-weight: 500;
        display: flex; align-items: center; gap: 0.5rem;
        box-shadow: 0 4px 16px rgba(0,0,0,0.2);
        animation: toastIn 0.3s ease forwards;
        border-left: 3px solid var(--primary);
    }
    .toast-msg.out { animation: toastOut 0.3s ease forwards; }
    @keyframes toastIn { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes toastOut { from { opacity: 1; transform: translateY(0); } to { opacity: 0; transform: translateY(12px); } }

    /* ── RESPONSIVE ── */
    @media (max-width: 900px) {
        .page-layout { flex-direction: column; padding: 1rem; }
        .filter-panel { width: 100%; position: static; }
        .pill-group { flex-direction: row; flex-wrap: wrap; }
        .pill-label { padding: 0.4rem 0.7rem; }
        .cart-sidebar { width: 100%; }
    }
    @media (max-width: 560px) {
        .meals-grid { grid-template-columns: 1fr 1fr; }
        .hero { padding: 2rem 0 1.5rem; }
    }
    @media (max-width: 420px) {
        .meals-grid { grid-template-columns: 1fr; }
    }
</style>

<!-- ══ HERO ══ -->
<section class="hero">
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
    <div class="container hero-inner">
        <div class="hero-badge">🍽️ &nbsp;Fresh Today</div>
        <h1 class="hero-title">
            Meals You'll <span>Love</span><br>Delivered Daily
        </h1>
        <p class="hero-subtitle">
            Browse fresh breakfast, lunch & dinner options from top local kitchens.
        </p>
        <div class="hero-stats">
            <div class="hero-stat">
                <div class="hero-stat-icon">🥘</div>
                <div>
                    <div class="hero-stat-num">{{ $totalItems }}</div>
                    <div class="hero-stat-label">Available<br>Meals</div>
                </div>
            </div>
            <div class="hero-stat">
                <div class="hero-stat-icon">🗂️</div>
                <div>
                    <div class="hero-stat-num">{{ count($menuByCategory) }}</div>
                    <div class="hero-stat-label">Menu<br>Categories</div>
                </div>
            </div>
            <div class="hero-stat">
                <div class="hero-stat-icon">⭐</div>
                <div>
                    <div class="hero-stat-num">{{ $featuredItems->count() }}</div>
                    <div class="hero-stat-label">Featured<br>Items</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ══ PAGE LAYOUT ══ -->
<div class="page-layout">

    <!-- ── FILTER SIDEBAR ── -->
    <aside class="filter-panel">
        <div class="filter-panel-title">
            <i class="bi bi-sliders2"></i> Filters
        </div>

        <form action="{{ route('dailymenuitems') }}" method="GET" id="filterForm">

            <!-- Search -->
            <div class="search-wrap">
                <i class="bi bi-search search-icon"></i>
                <input type="text" name="search" id="searchInput"
                       placeholder="Search meals…"
                       value="{{ $search }}"
                       autocomplete="off">
            </div>

            <!-- Meal Type -->
            <div class="filter-section">
                <span class="filter-label">Meal Type</span>
                <div class="pill-group">
                    <input type="radio" name="meal_type" id="mt_all" value="" class="pill-option" {{ !$mealType ? 'checked' : '' }}>
                    <label for="mt_all" class="pill-label">
                        🍽️ All Meals
                    </label>

                    <input type="radio" name="meal_type" id="mt_breakfast" value="breakfast" class="pill-option" {{ $mealType == 'breakfast' ? 'checked' : '' }}>
                    <label for="mt_breakfast" class="pill-label">
                        🌅 Breakfast <span class="pill-count">{{ $mealTypeCounts['breakfast'] }}</span>
                    </label>

                    <input type="radio" name="meal_type" id="mt_lunch" value="lunch" class="pill-option" {{ $mealType == 'lunch' ? 'checked' : '' }}>
                    <label for="mt_lunch" class="pill-label">
                        ☀️ Lunch <span class="pill-count">{{ $mealTypeCounts['lunch'] }}</span>
                    </label>

                    <input type="radio" name="meal_type" id="mt_dinner" value="dinner" class="pill-option" {{ $mealType == 'dinner' ? 'checked' : '' }}>
                    <label for="mt_dinner" class="pill-label">
                        🌙 Dinner <span class="pill-count">{{ $mealTypeCounts['dinner'] }}</span>
                    </label>
                </div>
            </div>

            <!-- Dietary -->
            <div class="filter-section">
                <span class="filter-label">Dietary Preference</span>
                <select name="dietary" class="styled-select">
                    <option value="">🍴 All Options</option>
                    <option value="vegetarian" {{ $dietaryFilter == 'vegetarian' ? 'selected' : '' }}>🥦 Vegetarian</option>
                    <option value="vegan" {{ $dietaryFilter == 'vegan' ? 'selected' : '' }}>🌱 Vegan</option>
                    <option value="gluten_free" {{ $dietaryFilter == 'gluten_free' ? 'selected' : '' }}>🌾 Gluten Free</option>
                    <option value="halal" {{ $dietaryFilter == 'halal' ? 'selected' : '' }}>☪️ Halal</option>
                </select>
            </div>

            <!-- Price Range -->
            <div class="filter-section">
                <span class="filter-label">Price Range (Tsh)</span>
                <select name="price_range" class="styled-select">
                    <option value="">All Prices</option>
                    <option value="under_10000" {{ $priceRange == 'under_10000' ? 'selected' : '' }}>Under 10,000</option>
                    <option value="10000_20000" {{ $priceRange == '10000_20000' ? 'selected' : '' }}>10,000 – 20,000</option>
                    <option value="20000_30000" {{ $priceRange == '20000_30000' ? 'selected' : '' }}>20,000 – 30,000</option>
                    <option value="above_30000" {{ $priceRange == 'above_30000' ? 'selected' : '' }}>Above 30,000</option>
                </select>
            </div>

            <button type="submit" class="btn-apply">
                <i class="bi bi-search"></i> Apply Filters
            </button>

            @if($search || $mealType || $dietaryFilter || $priceRange)
                <a href="{{ route('dailymenuitems') }}" class="btn-clear" style="display:block; text-align:center; text-decoration:none;">
                    <i class="bi bi-x-circle"></i> Clear All
                </a>
            @endif
        </form>

        <!-- Quick Info -->
        <div class="quick-info">
            <div class="quick-info-item">
                <div class="quick-info-icon" style="background:#FFF0E8; color:var(--primary);">🕐</div>
                Fresh meals updated daily
            </div>
            <div class="quick-info-item">
                <div class="quick-info-icon" style="background:#ECFDF5; color:#059669;">🚚</div>
                Free delivery over 10,000 Tsh
            </div>
            <div class="quick-info-item">
                <div class="quick-info-icon" style="background:#EFF6FF; color:#2563EB;">✅</div>
                Quality guaranteed
            </div>
        </div>
    </aside>

    <!-- ── MAIN MEALS AREA ── -->
    <main class="content-area">

        <!-- Active filter tags -->
        @if($search || $mealType || $dietaryFilter || $priceRange)
        <div class="active-filters">
            <span style="font-size:0.8rem; color:var(--text-muted); font-weight:600;">Active:</span>
            @if($search)
                <span class="active-filter-tag">🔍 "{{ $search }}"</span>
            @endif
            @if($mealType)
                <span class="active-filter-tag">{{ ucfirst($mealType) }}</span>
            @endif
            @if($dietaryFilter)
                <span class="active-filter-tag">{{ ucfirst(str_replace('_',' ',$dietaryFilter)) }}</span>
            @endif
            @if($priceRange)
                <span class="active-filter-tag">{{ str_replace(['under_','above_','_'], ['Under ','Above ','–'], $priceRange) }}</span>
            @endif
        </div>
        @endif

        @if($menuByCategory->isEmpty())
            <!-- Empty state -->
            <div class="empty-state">
                <div class="empty-icon">🍽️</div>
                <div class="empty-title">No meals found</div>
                <p class="empty-sub">Try adjusting your filters or search terms.</p>
                <a href="{{ route('dailymenuitems') }}" class="btn-reset">
                    <i class="bi bi-arrow-clockwise"></i> Reset Filters
                </a>
            </div>
        @else
            @foreach($menuByCategory as $categoryName => $items)
                <div class="category-block">
                    <div class="category-header">
                        <div class="category-icon">
                            @php
                                $icons = ['breakfast'=>'🌅','lunch'=>'☀️','dinner'=>'🌙','snack'=>'🍿','dessert'=>'🍰','drinks'=>'🥤','soup'=>'🍲'];
                                $lower = strtolower($categoryName);
                                $icon = '🍽️';
                                foreach($icons as $k=>$v) { if(str_contains($lower,$k)) { $icon=$v; break; } }
                            @endphp
                            {{ $icon }}
                        </div>
                        <div>
                            <div class="category-name">{{ $categoryName }}</div>
                        </div>
                        <div class="category-divider"></div>
                        <span class="category-count">{{ $items->count() }} items</span>
                    </div>

                    <div class="meals-grid">
                        @foreach($items as $item)
                            <div class="meal-card">
                                <!-- Image -->
                                <div class="meal-img-wrap">
                                    <img src="{{ $item->image_url ? asset('storage/' . $item->image_url) : asset('images/default-food.jpg') }}"
                                         class="meal-img" alt="{{ $item->name }}" loading="lazy">
                                    <div class="meal-img-overlay"></div>

                                    <div class="img-badge-tl">
                                        @if($item->is_featured)
                                            <span class="badge-pill badge-featured">⭐ Featured</span>
                                        @endif
                                        @if($item->is_spicy)
                                            <span class="badge-pill badge-spicy">🌶️ Spicy</span>
                                        @endif
                                    </div>

                                    @if($item->has_discount)
                                        <div class="img-badge-tr">
                                            <span class="badge-pill badge-discount">{{ $item->discount_percentage }}% OFF</span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Body -->
                                <div class="meal-body">
                                    <div class="meal-supplier">
                                        <i class="bi bi-shop"></i>
                                        {{ $item->supplier->business_name ?? 'N/A' }}
                                    </div>
                                    <div class="meal-name">{{ $item->name }}</div>
                                    <div class="meal-desc">{{ Str::limit($item->description, 80) }}</div>

                                    <!-- Dietary -->
                                    <div class="dietary-tags">
                                        @if($item->is_vegetarian)
                                            <span class="dtag dtag-veg">🥦 Vegetarian</span>
                                        @endif
                                        @if($item->is_vegan)
                                            <span class="dtag dtag-vegan">🌱 Vegan</span>
                                        @endif
                                        @if($item->is_gluten_free)
                                            <span class="dtag dtag-gluten">🌾 Gluten Free</span>
                                        @endif
                                        @if($item->is_halal)
                                            <span class="dtag dtag-halal">☪️ Halal</span>
                                        @endif
                                    </div>

                                    <!-- Meta -->
                                    <div class="meal-meta">
                                        @if($item->preparation_time)
                                            <span>⏱ {{ $item->preparation_time }} min</span>
                                        @endif
                                        @if($item->calories)
                                            <span>🔥 {{ $item->calories }} cal</span>
                                        @endif
                                        @if($item->serves)
                                            <span>👥 Serves {{ $item->serves }}</span>
                                        @endif
                                    </div>

                                    <!-- Price & Cart -->
                                    <div class="meal-footer">
                                        <div class="meal-price">
                                            @if($item->has_discount)
                                                <div class="price-original">{{ number_format($item->price, 0) }} Tsh</div>
                                            @endif
                                            <div class="price-main">
                                                {{ number_format($item->has_discount ? $item->discounted_price : $item->price, 0) }} Tsh
                                            </div>
                                        </div>

                                        <button class="btn-cart"
                                                onclick="addToCart({{ $item->id }}, '{{ addslashes($item->name) }}', {{ $item->current_price }}, '{{ $item->image_url ? asset('storage/' . $item->image_url) : asset('images/default-food.jpg') }}', {{ $item->supplier_id }}, this)">
                                            <i class="bi bi-cart-plus"></i> Add
                                        </button>
                                    </div>

                                    <!-- Stock -->
                                    @if($item->stock_quantity !== null)
                                        <div class="stock-indicator">
                                            @if($item->stock_quantity > 10)
                                                <div class="stock-dot" style="background:var(--success);"></div>
                                                <span style="color:var(--success);">In Stock</span>
                                            @elseif($item->stock_quantity > 0)
                                                <div class="stock-dot" style="background:#F59E0B;"></div>
                                                <span style="color:#F59E0B;">Only {{ $item->stock_quantity }} left</span>
                                            @else
                                                <div class="stock-dot" style="background:#EF4444;"></div>
                                                <span style="color:#EF4444;">Out of Stock</span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        @endif
    </main>
</div>

<!-- ══ CART SIDEBAR ══ -->
<div class="cart-overlay" id="cartOverlay" onclick="toggleCart()"></div>
<div class="cart-sidebar" id="cartSidebar">
    <div class="cart-header">
        <div class="cart-title">🛒 Your Cart</div>
        <button class="cart-close" onclick="toggleCart()"><i class="bi bi-x-lg"></i></button>
    </div>
    <div class="cart-items" id="cartItems"></div>
    <div class="cart-footer">
        <div class="cart-total-row">
            <span class="cart-total-label">Total</span>
            <span class="cart-total-amount" id="cartTotal">0 Tsh</span>
        </div>
        <button class="btn-checkout" id="checkoutBtn" onclick="proceedToCheckout()">
            <i class="bi bi-bag-check"></i> Proceed to Checkout
        </button>
    </div>
</div>

<!-- FAB -->
<button class="fab-cart" onclick="toggleCart()">
    <i class="bi bi-cart3"></i>
    <span class="fab-badge" id="fabBadge">0</span>
</button>

<!-- Toast container -->
<div class="toast-container" id="toastContainer"></div>

<script>
    const MIN_ORDER = 10.00;
    let cart = JSON.parse(localStorage.getItem('tapEatsCart')) || [];

    // ── CART ACTIONS ──
    function addToCart(id, name, price, image, supplier_id, btn) {
        const existing = cart.find(i => i.id === id);
        if (existing) { existing.quantity++; }
        else { cart.push({ id, name, price: parseFloat(price), image, quantity: 1, supplier_id }); }
        saveCart();
        renderCart();
        animateBtn(btn, name);
    }

    function updateQty(id, delta) {
        const item = cart.find(i => i.id === id);
        if (!item) return;
        item.quantity += delta;
        if (item.quantity <= 0) cart = cart.filter(i => i.id !== id);
        saveCart(); renderCart();
    }

    function removeItem(id) {
        cart = cart.filter(i => i.id !== id);
        saveCart(); renderCart();
    }

    function saveCart() { localStorage.setItem('tapEatsCart', JSON.stringify(cart)); }

    // ── RENDER ──
    function renderCart() {
        const itemsEl = document.getElementById('cartItems');
        const totalEl = document.getElementById('cartTotal');
        const badgeEl = document.getElementById('fabBadge');
        const checkoutBtn = document.getElementById('checkoutBtn');

        const totalQty = cart.reduce((s, i) => s + i.quantity, 0);
        const totalAmt = cart.reduce((s, i) => s + i.price * i.quantity, 0);

        // Badge
        badgeEl.textContent = totalQty;
        badgeEl.classList.toggle('show', totalQty > 0);

        // Total
        totalEl.textContent = totalAmt.toLocaleString() + ' Tsh';

        // Checkout button
        checkoutBtn.disabled = totalAmt < MIN_ORDER;

        // Items
        if (cart.length === 0) {
            itemsEl.innerHTML = `
                <div class="cart-empty">
                    <div class="cart-empty-icon">🛒</div>
                    <p style="font-weight:600; margin-bottom:0.3rem;">Your cart is empty</p>
                    <p style="font-size:0.82rem;">Add some delicious meals!</p>
                </div>`;
            return;
        }

        itemsEl.innerHTML = cart.map(item => `
            <div class="cart-item">
                <img src="${item.image}" alt="${item.name}" class="cart-item-img">
                <div class="cart-item-info">
                    <div class="cart-item-name">${item.name}</div>
                    <div class="cart-item-price">${(item.price * item.quantity).toLocaleString()} Tsh</div>
                    <div class="cart-item-actions">
                        <button class="qty-btn" onclick="updateQty(${item.id}, -1)">−</button>
                        <span class="qty-num">${item.quantity}</span>
                        <button class="qty-btn" onclick="updateQty(${item.id}, 1)">+</button>
                    </div>
                </div>
                <button class="cart-item-del" onclick="removeItem(${item.id})" title="Remove">
                    <i class="bi bi-trash3"></i>
                </button>
            </div>
        `).join('');
    }

    // ── TOGGLE ──
    function toggleCart() {
        document.getElementById('cartSidebar').classList.toggle('active');
        document.getElementById('cartOverlay').classList.toggle('active');
    }

    // ── ANIMATE BTN ──
    function animateBtn(btn, name) {
        if (!btn) return;
        const orig = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-check-lg"></i> Added!';
        btn.classList.add('added');
        showToast('🛒 ' + name + ' added to cart');
        setTimeout(() => {
            btn.innerHTML = orig;
            btn.classList.remove('added');
        }, 1200);
    }

    // ── TOAST ──
    function showToast(msg) {
        const container = document.getElementById('toastContainer');
        const el = document.createElement('div');
        el.className = 'toast-msg';
        el.textContent = msg;
        container.appendChild(el);
        setTimeout(() => {
            el.classList.add('out');
            setTimeout(() => el.remove(), 300);
        }, 2500);
    }

    // ── CHECKOUT ──
    async function proceedToCheckout() {
        if (cart.length === 0) { alert('Your cart is empty!'); return; }
        try {
            const res = await fetch('{{ route("cart.sync") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ cart_data: JSON.stringify(cart) })
            });
            if (res.ok) { window.location.href = '{{ route("checkoutindex") }}'; }
            else { throw new Error(); }
        } catch {
            alert('Failed to proceed to checkout. Please try again.');
        }
    }

    // ── LIVE SEARCH (debounced) ──
    let searchTimeout;
    document.getElementById('searchInput').addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            document.getElementById('filterForm').submit();
        }, 500);
    });

    // ── ESC to close cart ──
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            document.getElementById('cartSidebar').classList.remove('active');
            document.getElementById('cartOverlay').classList.remove('active');
        }
    });

    // ── INIT ──
    document.addEventListener('DOMContentLoaded', renderCart);
</script>

@endsection