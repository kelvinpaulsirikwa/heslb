@extends('layouts.app')

@section('content')
<div class="heslb-hero">
    <div class="container">
        <h1 class="heslb-hero-title">Scholarships</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb text-white">
                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}" class="text-white">Home</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Scholarships</li>
            </ol>
        </nav>
    </div>
</div>

<style>
    .heslb-sidebar a {
        display: block;
        padding: 15px 20px;
        margin-bottom: 10px;
        background-color: #f1f1f1;
        color: #222;
        text-decoration: none;
        border-radius: 4px;
        font-weight: 600;
        transition: all 0.2s ease-in-out;
    }

    .heslb-sidebar a:hover,
    .heslb-sidebar a.active {
        background-color: #0e9bd5;
        color: #fff;
    }

    .heslb-content-box {
        background: white;
        border-radius: 0;
        box-shadow: none;
        overflow: visible;
        padding: 0;
    }

    .heslb-content-body {
        padding: 0;
        color: #333;
        font-size: 1.05rem;
        line-height: 1.7;
    }

    .scholarship-info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .info-card {
        background: #ffffff;
        padding: 0;
        border: none;
    }

    .info-card-icon {
        color: #0e9bd5;
        font-size: 1.2rem;
        margin-right: 10px;
    }

    .info-card-title {
        color: #0c1f38;
        font-size: 0.95rem;
        font-weight: 600;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
    }

    .info-card-value {
        color: #6c757d;
        font-size: 0.9rem;
        line-height: 1.6;
        margin-left: 32px;
        word-wrap: break-word;
        word-break: break-word;
        overflow-wrap: break-word;
    }

    .apply-btn-container {
        margin-top: 30px;
        margin-bottom: 30px;
    }

    .content-section {
        background: #f8f9fa;
        padding: 25px;
        border-radius: 8px;
        margin-top: 30px;
    }

    .content-section h5 {
        color: #0c1f38;
        font-weight: 600;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #0e9bd5;
    }

    @media (max-width: 768px) {
        .scholarship-info-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="container my-5">
    <div class="row">
        <!-- Sidebar Navigation -->
        <div class="col-lg-3 mb-4">
            <ul class="heslb-sidebar list-unstyled">
                @foreach($scholarships as $s)
                    <li>
                        <a href="{{ route('scholarships.show', $s->slug) }}" 
                           class="{{ $current->id === $s->id ? 'active' : '' }}">
                            {{ $s->title }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

        <!-- Right Hand Content -->
        <div class="col-lg-9">
            <div class="heslb-content-box">
                <div class="heslb-content-body">
                    <div class="heslb-content-container">
                    <div class="heslb-content-header">
                    <span title="{{ $current->title }}">
{{ $current->title }}                    </span>
                  
                </div>
 <br>                        
                        @php $hasImage = $current->cover_image && \Illuminate\Support\Facades\Storage::disk('public')->exists($current->cover_image); @endphp
                        @if($hasImage)
                            <img src="{{ $current->cover_image_url }}" alt="cover" class="img-fluid w-100 rounded mb-4" style="width: 100%; height: auto;" onerror="this.style.display='none'">
                        @endif

                        <!-- Scholarship Details Grid - Two Columns -->
                        <div class="scholarship-info-grid">
                            @if($current->donor_organization)
                                <div class="info-card">
                                    <div class="info-card-title">
                                        <i class="fas fa-award info-card-icon"></i>
                                        Donor Organization
                                    </div>
                                    <div class="info-card-value">{{ $current->donor_organization }}</div>
                                </div>
                            @endif

                            @if($current->application_deadline)
                                <div class="info-card">
                                    <div class="info-card-title">
                                        <i class="fas fa-calendar-alt info-card-icon"></i>
                                        Application Deadline
                                    </div>
                                    <div class="info-card-value">{{ $current->application_deadline->format('F d, Y') }}</div>
                                </div>
                            @endif

                            @if($current->eligible_applicants)
                                <div class="info-card">
                                    <div class="info-card-title">
                                        <i class="fas fa-users info-card-icon"></i>
                                        Eligible Applicants
                                    </div>
                                    <div class="info-card-value">{{ $current->eligible_applicants }}</div>
                                </div>
                            @endif

                            @if($current->fields_of_study)
                                <div class="info-card">
                                    <div class="info-card-title">
                                        <i class="fas fa-graduation-cap info-card-icon"></i>
                                        Fields of Study
                                    </div>
                                    <div class="info-card-value" style="white-space: pre-line;">{{ $current->fields_of_study }}</div>
                                </div>
                            @endif

                            @if($current->benefits_summary)
                                <div class="info-card">
                                    <div class="info-card-title">
                                        <i class="fas fa-file-alt info-card-icon"></i>
                                        Benefits Summary
                                    </div>
                                    <div class="info-card-value" style="white-space: pre-line;">{{ $current->benefits_summary }}</div>
                                </div>
                            @endif

                            @if($current->level_of_study && count($current->level_of_study) > 0)
                                <div class="info-card">
                                    <div class="info-card-title">
                                        <i class="fas fa-graduation-cap info-card-icon"></i>
                                        Level of Study
                                    </div>
                                    <div class="info-card-value">
                                        @foreach($current->level_of_study as $level)
                                            <span class="badge me-1" style="background-color: #0e9bd5; color: white;">{{ ucfirst($level) }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Additional Content Section -->
                        @if($current->content_html)
                            <div class="content-section">
                                <h5>Scholarship Overview</h5>
                                <div class="content-html">
                                    {!! $current->content_html !!}
                                </div>
                            </div>
                        @endif

                        <!-- Apply Now Button - At the Bottom -->
                        @if($current->external_link)
                            <div class="apply-btn-container text-center">
                                <a href="{{ $current->external_link }}" target="_blank" rel="noopener noreferrer" class="btn btn-lg px-5 py-3" style="background-color: #0e9bd5; border-color: #0e9bd5; color: white;">
                                    <i class="fas fa-paper-plane me-2"></i> Apply Now
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


