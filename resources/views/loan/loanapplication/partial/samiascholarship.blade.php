@extends('layouts.app')

@section('content')
<div class="heslb-hero">
    <div class="container">
        <h1 class="heslb-hero-title">{{ __('menu.samiascholarship') }}</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb text-white">
                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}" class="text-white">Home</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('menu.samiascholarship') }}</li>
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
        background-color: #009fe3;
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
</style>

<div class="container my-5">
    <div class="row">
        <!-- Sidebar Navigation -->
        <div class="col-lg-3 mb-4">
            <ul class="heslb-sidebar list-unstyled">
                @php
                    $scholarships = \App\Models\Scholarship::where('is_active', true)->orderByDesc('published_at')->orderBy('title')->get();
                @endphp
                @forelse($scholarships as $index => $s)
                    <li>
                        <a href="{{ route('scholarships.show', $s->slug) }}" 
                           class="scholarship-link" 
                           data-scholarship-id="{{ $s->id }}">
                            {{ $s->title }}
                        </a>
                    </li>
                @empty
                    <li><div class="text-muted p-3">No scholarships available</div></li>
                @endforelse
            </ul>
        </div>

        <!-- Right Hand Content -->
        <div class="col-lg-9">
            <div class="heslb-content-box">
                <div class="heslb-content-body">
                    <div class="heslb-content-container">
                        @if($scholarships->isEmpty())
                            <div class="text-center bg-white p-5 rounded shadow-sm">
                                <i class="fas fa-graduation-cap fa-3x text-muted mb-3"></i>
                                <h4 class="mb-2">No Scholarships Available</h4>
                                <p class="text-muted mb-0">Please check back later.</p>
                            </div>
                        @else
                            @php $current = $scholarships->first(); @endphp
                            <div id="scholarship-content">
                                <h3 class="mb-3" id="scholarship-title">{{ $current->title }}</h3>
                                @php $hasImage = $current->cover_image && \Illuminate\Support\Facades\Storage::disk('public')->exists($current->cover_image); @endphp
                                @if($hasImage)
                                    <img src="{{ $current->cover_image_url }}" alt="cover" class="img-fluid w-100 rounded mb-3" style="width: 100%; height: auto;" onerror="this.style.display='none'" id="scholarship-image">
                                @else
                                    <div class="text-muted small mb-3" id="scholarship-image">No cover image</div>
                                @endif
                                <div class="mt-3" id="scholarship-body">
                                    {!! $current->content_html !!}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Script loaded');
    
    // Set first scholarship as active on page load
    const firstLink = document.querySelector('.scholarship-link');
    if (firstLink) {
        firstLink.classList.add('active');
        console.log('First link set as active');
    }

    // Handle scholarship link clicks
    const scholarshipLinks = document.querySelectorAll('.scholarship-link');
    console.log('Found scholarship links:', scholarshipLinks.length);
    
    scholarshipLinks.forEach((link, index) => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Link clicked:', this.textContent);
            
            // Remove active class from all links
            scholarshipLinks.forEach(l => l.classList.remove('active'));
            
            // Add active class to clicked link
            this.classList.add('active');
            console.log('Active class added to:', this.textContent);
            
            // Get scholarship data
            const scholarshipId = this.getAttribute('data-scholarship-id');
            const scholarshipTitle = this.textContent.trim();
            
            // Update content area
            const titleElement = document.getElementById('scholarship-title');
            if (titleElement) {
                titleElement.textContent = scholarshipTitle;
                console.log('Title updated to:', scholarshipTitle);
            }
        });
    });
});
</script>
@endsection
