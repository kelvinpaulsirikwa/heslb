@extends('adminpages.layouts.app')

@section('content')
<div class="container-fluid bg-white p-4 mt-2">
    <!-- Header Section -->
    <div class="row mb-5 align-items-center">
        <div class="col-md-8">
            <h1 class="h2 mb-1 text-dark fw-bold">Dashboard</h1>
            <p class="text-muted mb-0">Welcome, {{ Auth::user()->username }}  administrative panel</p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-globe"></i> WEBSITE
            </a>
        </div>
    </div>

    <!-- General Statistics Cards -->
    <div class="row g-4 mb-5">
        @php
            $cards = [
                ['title' => 'News', 'count' => $stats['total_news'], 'icon' => 'fa-newspaper', 'color' => 'primary', 'route' => route('admin.news.index')],
                ['title' => 'Feedbacks', 'count' => $stats['total_feedbacks'], 'icon' => 'fa-comments', 'color' => 'warning', 'route' => route('admin.feedback')],
                ['title' => 'Publications', 'count' => $stats['total_publications'], 'icon' => 'fa-file-alt', 'color' => 'info', 'route' => route('admin.publications.index')],
                ['title' => 'Links', 'count' => $stats['total_links'], 'icon' => 'fa-link', 'color' => 'dark', 'route' => route('shortcut-links.index')],
                ['title' => 'Video Podcasts', 'count' => $stats['total_videos'], 'icon' => 'fa-video', 'color' => 'danger', 'route' => route('videopodcasts.index')],
                ['title' => 'Applications', 'count' => $stats['total_applications'], 'icon' => 'fa-clipboard-list', 'color' => 'success', 'route' => route('admin.window_applications.index')],
            ];
        @endphp

        @foreach ($cards as $card)
        <div class="col-xl-3 col-md-6">
            <a href="{{ $card['route'] }}" class="text-decoration-none">
            <div class="card border-0 shadow-sm h-100 position-relative overflow-hidden card-hover">
                <div class="card-body d-flex justify-content-between align-items-center p-4">
                    <div>
                        <h6 class="text-uppercase fw-semibold text-muted mb-2" style="font-size: 0.75rem;">{{ $card['title'] }}</h6>
                        <h2 class="fw-bold mb-0 text-{{ $card['color'] }}">{{ $card['count'] }}</h2>
                    </div>
                    <div class="icon-circle bg-{{ $card['color'] }} bg-opacity-10 text-{{ $card['color'] }}">
                        <i class="fas {{ $card['icon'] }} fa-2x"></i>
                    </div>
                </div>
                <div class="position-absolute bottom-0 start-0 w-100 bg-{{ $card['color'] }}" style="height: 4px;"></div>
            </div>
            </a>
        </div>
        @endforeach
    </div>

    <!-- Visitor Statistics Section -->
    <div class="row g-4 mb-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom-0 py-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1 fw-bold text-dark">
                                <i class="fas fa-chart-line text-primary me-2"></i>
                                Visitor Analytics
                            </h5>
                            <p class="text-muted mb-0 small">Website traffic statistics by time period</p>
                        </div>
                        <div class="d-flex gap-2">
                            <span class="badge bg-primary px-3 py-2">
                                <i class="fas fa-chart-line me-1"></i>Visitor Analytics
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <!-- Time Period Cards -->
                    <div class="row g-3 mb-4">
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <div class="stat-card bg-primary bg-opacity-10 border border-primary border-opacity-25 rounded-3 p-3 text-center">
                                <i class="fas fa-calendar-day text-primary mb-2" style="font-size: 1.5rem;"></i>
                                <h6 class="text-muted mb-1 small">Today</h6>
                                <h4 class="fw-bold text-primary mb-0">{{ $stats['visit_stats']['today'] }}</h4>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <div class="stat-card bg-info bg-opacity-10 border border-info border-opacity-25 rounded-3 p-3 text-center">
                                <i class="fas fa-calendar-minus text-info mb-2" style="font-size: 1.5rem;"></i>
                                <h6 class="text-muted mb-1 small">Yesterday</h6>
                                <h4 class="fw-bold text-info mb-0">{{ $stats['visit_stats']['yesterday'] }}</h4>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <div class="stat-card bg-warning bg-opacity-10 border border-warning border-opacity-25 rounded-3 p-3 text-center">
                                <i class="fas fa-calendar-week text-warning mb-2" style="font-size: 1.5rem;"></i>
                                <h6 class="text-muted mb-1 small">This Week</h6>
                                <h4 class="fw-bold text-warning mb-0">{{ $stats['visit_stats']['this_week'] }}</h4>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <div class="stat-card bg-success bg-opacity-10 border border-success border-opacity-25 rounded-3 p-3 text-center">
                                <i class="fas fa-calendar-alt text-success mb-2" style="font-size: 1.5rem;"></i>
                                <h6 class="text-muted mb-1 small">This Month</h6>
                                <h4 class="fw-bold text-success mb-0">{{ $stats['visit_stats']['this_month'] }}</h4>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <div class="stat-card bg-secondary bg-opacity-10 border border-secondary border-opacity-25 rounded-3 p-3 text-center">
                                <i class="fas fa-calendar text-secondary mb-2" style="font-size: 1.5rem;"></i>
                                <h6 class="text-muted mb-1 small">Last Month</h6>
                                <h4 class="fw-bold text-secondary mb-0">{{ $stats['visit_stats']['last_month'] }}</h4>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <div class="stat-card bg-dark bg-opacity-10 border border-dark border-opacity-25 rounded-3 p-3 text-center">
                                <i class="fas fa-chart-bar text-dark mb-2" style="font-size: 1.5rem;"></i>
                                <h6 class="text-muted mb-1 small">Total All Time</h6>
                                <h4 class="fw-bold text-dark mb-0">{{ $stats['visit_stats']['total_all'] }}</h4>
                            </div>
                        </div>
                    </div>

                    <!-- Monthly Chart -->
                    <div class="mt-4">
                        <h6 class="fw-bold text-dark mb-3">
                            <i class="fas fa-chart-area text-primary me-2"></i>
                            Monthly Visitor Trends (Last 12 Months)
                        </h6>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0 fw-semibold">Month</th>
                                        <th class="border-0 fw-semibold text-center">Total Visits</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($stats['monthly_stats'] as $monthData)
                                    <tr>
                                        <td class="fw-semibold">{{ $monthData['month'] }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2">
                                                {{ number_format($monthData['visits']) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
</div>

<style>
/* Card hover effect */
.card-hover {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.card-hover:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.15);
}

/* Icon circle */
.icon-circle {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 55px;
    height: 55px;
    border-radius: 50%;
}

/* Stat card hover effects */
.stat-card {
    transition: all 0.3s ease;
    cursor: pointer;
}

.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
}

/* Progress bar animations */
.progress-bar {
    transition: width 0.6s ease;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .container-fluid {
        padding-left: 15px;
        padding-right: 15px;
    }
    
    .stat-card {
        margin-bottom: 15px;
    }
    
    .btn-sm {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }
}

@media (max-width: 576px) {
    .col-lg-2 {
        margin-bottom: 15px;
    }
    
    .table-responsive {
        font-size: 0.875rem;
    }
}
</style>

@endsection
