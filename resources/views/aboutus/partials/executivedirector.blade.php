@extends('aboutus.aboutus')

@section('aboutus-content')

<!-- Executive Directors Timeline Section -->
<div class="container py-5" style="background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);">
    @if($executiveDirectors->count() > 0)
        <div class="row">
            <div class="col-12">
                @php
                    // Use the ordering from controller: Active first, then by end_year/start_year
                    $allDirectors = $executiveDirectors;
                    $totalDirectors = $allDirectors->count();
                @endphp

                <!-- Enhanced Smart Timeline -->
                <div class="smart-timeline-wrapper">
                    <div class="smart-timeline" id="mainTimeline">
                        @foreach($allDirectors as $index => $director)
                            @php
                                $isActive = $director->status === 'Active';
                                $isEven = $index % 2 === 0;
                                $yearSpan = $director->start_year . ' - ' . ($director->end_year ?? 'Present');
                            @endphp
                            
                            <div class="timeline-era" data-year="{{ $director->start_year }}" data-index="{{ $index }}">
                                <!-- Era Connector -->
                                @if($index < $totalDirectors - 1)
                                    <div class="era-connector {{ $isActive ? 'connector-active' : '' }}"></div>
                                @endif

                                <!-- Era Content -->
                                <div class="era-content {{ $isEven ? 'era-left' : 'era-right' }} {{ $isActive ? 'era-active' : '' }}">
                                    <!-- Director Card -->
                                    <div class="director-card {{ $isActive ? 'director-active' : '' }}">
                                        <!-- Director Image & Info -->
                                        <div class="director-main-info">
                                            <div class="director-avatar">
                                                @if($director->imagepath)
                                                    <img src="{{ asset('images/storage/' . $director->imagepath) }}" 
                                                         alt="{{ $director->full_name }}" 
                                                         class="avatar-image">
                                                @else
                                                    <div class="avatar-placeholder">
                                                        <i class="fas fa-user-tie"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            <div class="director-details">
                                                <h3 class="director-name">{{ $director->full_name }}</h3>
                                                <div class="director-tenure">
                                                    <span class="tenure-period">{{ $yearSpan }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-5">
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-users-slash"></i>
                </div>
                <h3 class="empty-title">No Executive Directors Found</h3>
                <p class="empty-subtitle">Check back later for updates on our leadership team.</p>
            </div>
        </div>
    @endif
</div>

<style>
    /* Enhanced Timeline Styles */
    .smart-timeline-wrapper {
        position: relative;
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem 0;
    }

    /* Main Timeline */
    .smart-timeline {
        position: relative;
        display: flex;
        flex-direction: column;
        gap: 4rem;
    }

    .smart-timeline::before {
        content: '';
        position: absolute;
        left: 50%;
        top: 0;
        bottom: 0;
        width: 4px;
        background: linear-gradient(to bottom, #0c1f38 0%, #0e9bd5 50%, #0c1f38 100%);
        transform: translateX(-50%);
        z-index: 1;
        border-radius: 2px;
    }

    /* Timeline Era */
    .timeline-era {
        position: relative;
        display: flex;
        align-items: center;
        min-height: 300px;
    }

    .era-connector {
        position: absolute;
        left: 50%;
        top: 100px;
        bottom: -4rem;
        width: 3px;
        background: #dee2e6;
        transform: translateX(-50%);
        z-index: 2;
    }

    .connector-active {
        background: linear-gradient(to bottom, #0e9bd5, #0c1f38);
    }

    .era-content {
        position: relative;
        width: 45%;
        z-index: 3;
    }

    .era-left {
        margin-right: auto;
    }

    .era-right {
        margin-left: auto;
    }

    /* Director Card */
    .director-card {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 6px 24px rgba(0,0,0,0.1);
        border: 2px solid #f1f3f4;
        position: relative;
        transition: all 0.4s ease;
        overflow: hidden;
        text-align: center;
    }

    .director-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 32px rgba(0,0,0,0.15);
        border-color: #0c1f38;
    }

    .director-active {
        border-color: #0e9bd5;
        background: linear-gradient(135deg, #f7fcff, #ffffff);
    }

    .director-active:hover {
        border-color: #0e9bd5;
        box-shadow: 0 12px 32px rgba(14, 155, 213, 0.25);
    }

    /* Director Main Info */
    .director-main-info {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1.5rem;
    }

    .director-avatar {
        position: relative;
        width: 200px;
        height: 200px;
        border-radius: 50%;
        overflow: hidden;
        border: 5px solid #f8f9fa;
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        transition: all 0.3s ease;
    }

    .director-avatar:hover {
        transform: scale(1.05);
        border-color: #0e9bd5;
    }

    .avatar-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .director-avatar:hover .avatar-image {
        transform: scale(1.1);
    }

    .avatar-placeholder {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #0c1f38, #0e9bd5);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 4rem;
    }

    .director-details {
        text-align: center;
    }

    .director-name {
        font-size: 1.8rem;
        font-weight: 800;
        color: #0c1f38;
        margin-bottom: 0.5rem;
        line-height: 1.2;
    }

    .director-tenure {
        display: flex;
        justify-content: center;
    }

    .tenure-period {
        font-size: 1.3rem;
        font-weight: 700;
        color: #0e9bd5;
        background: #f8f9fa;
        padding: 0.5rem 1.5rem;
        border-radius: 25px;
        border: 2px solid #e9ecef;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }

    .empty-icon {
        font-size: 4rem;
        color: #6c757d;
        margin-bottom: 1.5rem;
    }

    .empty-title {
        color: #495057;
        margin-bottom: 1rem;
    }

    .empty-subtitle {
        color: #6c757d;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .smart-timeline::before {
            left: 30px;
        }

        .era-content {
            width: calc(100% - 80px);
            margin-left: 80px !important;
        }

        .era-left, .era-right {
            margin-left: 80px;
            margin-right: 0;
        }

        .era-connector {
            left: 54px;
        }

        .director-avatar {
            width: 150px;
            height: 150px;
        }
        
        .director-name {
            font-size: 1.5rem;
        }
        
        .tenure-period {
            font-size: 1.1rem;
        }
    }

    @media (max-width: 480px) {
        .era-content {
            width: calc(100% - 60px);
            margin-left: 60px !important;
        }

        .era-left, .era-right {
            margin-left: 60px;
        }

        .era-connector {
            left: 44px;
        }

        .director-card {
            padding: 1.5rem;
        }

        .director-avatar {
            width: 120px;
            height: 120px;
        }
        
        .avatar-placeholder {
            font-size: 3rem;
        }
        
        .director-name {
            font-size: 1.3rem;
        }
        
        .tenure-period {
            font-size: 1rem;
        }
    }
</style>


<script>
    // Add intersection observer for animation effects
    document.addEventListener('DOMContentLoaded', function() {
        const timelineEras = document.querySelectorAll('.timeline-era');
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, { threshold: 0.1 });

        timelineEras.forEach(era => {
            era.style.opacity = '0';
            era.style.transform = 'translateY(50px)';
            era.style.transition = 'all 0.6s ease';
            observer.observe(era);
        });

        // Add image modal functionality
        const avatarImages = document.querySelectorAll('.avatar-image');
        avatarImages.forEach(img => {
            img.addEventListener('click', function() {
                // Create modal for image
                const modal = document.createElement('div');
                modal.style.position = 'fixed';
                modal.style.top = '0';
                modal.style.left = '0';
                modal.style.width = '100%';
                modal.style.height = '100%';
                modal.style.backgroundColor = 'rgba(0,0,0,0.9)';
                modal.style.display = 'flex';
                modal.style.alignItems = 'center';
                modal.style.justifyContent = 'center';
                modal.style.zIndex = '9999';
                modal.style.cursor = 'pointer';
                
                const modalImg = document.createElement('img');
                modalImg.src = this.src;
                modalImg.alt = this.alt;
                modalImg.style.maxWidth = '90%';
                modalImg.style.maxHeight = '90%';
                modalImg.style.objectFit = 'contain';
                modalImg.style.borderRadius = '8px';
                modalImg.style.boxShadow = '0 10px 30px rgba(0,0,0,0.5)';
                
                modal.appendChild(modalImg);
                document.body.appendChild(modal);
                
                // Close modal on click
                modal.addEventListener('click', function() {
                    document.body.removeChild(modal);
                });
            });
        });
    });
</script>

@endsection