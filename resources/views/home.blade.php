@extends('layouts.app')

@section('css')
<style>
    /* Skeleton Loading */
    .skeleton {
        display: inline-block;
        background: linear-gradient(
            90deg,
            #e0e0e0 25%,
            #f5f5f5 37%,
            #e0e0e0 63%
        );
        background-size: 400% 100%;
        animation: skeleton-loading 1.4s ease infinite;
        border-radius: 4px;
    }

    .skeleton-text {
        width: 140px;
        height: 1em;
        vertical-align: middle;
    }

    @keyframes skeleton-loading {
        0% {
            background-position: 100% 0;
        }
        100% {
            background-position: 0 0;
        }
    }

    /* Dashboard Modern Styling */
    .dashboard-header {
        background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
        color: white;
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        margin-bottom: 30px;
        animation: fadeInDown 0.6s ease;
    }

    .dashboard-header h2 {
        font-size: 2rem;
        font-weight: 700;
        margin: 0 0 10px 0;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
    }

    .dashboard-header p {
        font-size: 1.1rem;
        margin: 0;
        opacity: 0.95;
    }

    /* Enhanced Stats Cards */
    .stats-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        height: 100%;
        border: 1px solid rgba(0,0,0,0.05);
        position: relative;
        overflow: hidden;
        animation: fadeInUp 0.6s ease backwards;
    }

    .stats-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.15);
    }

    .stats-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, var(--card-color-start), var(--card-color-end));
    }

    .stats-card.hariini { --card-color-start: #dc3545; --card-color-end: #c82333; }
    .stats-card.mingguini { --card-color-start: #ffc107; --card-color-end: #ff9800; }
    .stats-card.bulanini { --card-color-start: #28a745; --card-color-end: #20c997; }
    .stats-card.tahunini { --card-color-start: #17a2b8; --card-color-end: #138496; }
    .stats-card.received { --card-color-start: #667eea; --card-color-end: #764ba2; }
    .stats-card.totalinvoice { --card-color-start: #6c757d; --card-color-end: #495057; }

    .stats-icon {
        width: 70px;
        height: 70px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        margin-bottom: 15px;
        background: linear-gradient(135deg, var(--card-color-start), var(--card-color-end));
        color: white;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        transition: transform 0.3s ease;
    }

    .stats-card:hover .stats-icon {
        transform: scale(1.1) rotate(5deg);
    }

    .stats-value {
        font-size: 2.5rem;
        font-weight: 700;
        color: #2c3e50;
        margin: 10px 0;
        line-height: 1;
    }

    .stats-label {
        font-size: 1rem;
        color: #6c757d;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .stats-trend {
        position: absolute;
        top: 20px;
        right: 20px;
        font-size: 0.85rem;
        color: #28a745;
        font-weight: 600;
    }

    /* Animations */
    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Delay for staggered animation */
    .col-lg-4:nth-child(1) .stats-card { animation-delay: 0.1s; }
    .col-lg-4:nth-child(2) .stats-card { animation-delay: 0.2s; }
    .col-lg-4:nth-child(3) .stats-card { animation-delay: 0.3s; }
    .col-lg-4:nth-child(4) .stats-card { animation-delay: 0.4s; }
    .col-lg-4:nth-child(5) .stats-card { animation-delay: 0.5s; }
    .col-lg-4:nth-child(6) .stats-card { animation-delay: 0.6s; }

    /* Loading spinner */
    .spinner-wrapper {
        display: inline-block;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    /* Responsive */
    @media (max-width: 768px) {
        .dashboard-header h2 {
            font-size: 1.5rem;
        }
        
        .stats-value {
            font-size: 2rem;
        }

        .stats-icon {
            width: 60px;
            height: 60px;
            font-size: 1.5rem;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Modern Dashboard Header -->
    <div class="dashboard-header">
        <h2>
            <i class="fas fa-warehouse mr-2"></i>
            Multi Prima Rasa
        </h2>
        <p>
            Selamat datang, 
            <strong>{{ auth()->user()->Nama }}</strong>
        </p>
    </div>

    <!-- Stats Cards -->
    <div class="row">
        <div class="col-lg-4 col-md-6 col-6 mb-4">
            <div class="stats-card hariini">
                <div class="stats-icon">
                    <i class="fas fa-calendar-day"></i>
                </div>
                <div class="stats-value">
                    {{ $hariini ?? 0 }}
                </div>
                <div class="stats-label">Invoice hari ini</div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-6 mb-4">
            <div class="stats-card mingguini">
                <div class="stats-icon">
                    <i class="fas fa-calendar-week"></i>
                </div>
                <div class="stats-value">
                    {{ $mingguini ?? 0 }}
                </div>
                <div class="stats-label">Invoice minggu ini</div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-6 mb-4">
            <div class="stats-card bulanini">
                <div class="stats-icon">
                    <i class="fas fa-calendar-days"></i>
                </div>
                <div class="stats-value">
                    {{ $bulanini ?? 0 }}
                </div>
                <div class="stats-label">Invoice bulan ini</div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-6 mb-4">
            <div class="stats-card tahunini">
                <div class="stats-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stats-value">
                    {{ $tahunini ?? 0 }}
                </div>
                <div class="stats-label">Invoice tahun ini</div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-6 mb-4">
            <div class="stats-card totalinvoice">
                <div class="stats-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stats-value">
                    {{ $totalform ?? 0 }}
                </div>
                <div class="stats-label">Total Invoice</div>
            </div>
        </div>
    </div>
</div>
@endsection
