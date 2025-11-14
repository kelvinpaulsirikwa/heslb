@extends('aboutus.aboutus')

@section('aboutus-content')
@php
    use Carbon\Carbon;
@endphp

<div class="yt-player-container mt-4">

    @if(count($videos) > 0)
        <div class="yt-player-grid">
            @foreach ($videos as $index => $video)
                @php
                    // Extract YouTube video ID from URL (supports common formats)
                    preg_match('/(?:youtu\.be\/|youtube\.com\/(?:watch\?v=|embed\/|v\/))([\w-]+)/', $video['link'], $matches);
                    $videoId = $matches[1] ?? null;
                    $thumbnail = $videoId 
                        ? "https://img.youtube.com/vi/{$videoId}/maxresdefault.jpg" 
                        : null;
                @endphp

                <div class="yt-player-item">
                    <div class="yt-player-card" onclick="openVideoModal('{{$index}}')">
                        @if($thumbnail)
                            <div class="yt-player-thumb" style="background-image: url('{{ $thumbnail }}');">
                                <div class="yt-player-overlay">
                                    <div class="yt-player-play-wrapper">
                                        <div class="yt-player-play-btn">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M8 5v14l11-7z"/>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="yt-player-thumb yt-player-no-thumb">
                                <div class="yt-player-no-preview">
                                    <svg width="48" height="48" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M17,10.5V7A1,1 0 0,0 16,6H4A1,1 0 0,0 3,7V17A1,1 0 0,0 4,18H16A1,1 0 0,0 17,17V13.5L21,17.5V6.5L17,10.5Z"/>
                                    </svg>
                                    <p>No Preview Available</p>
                                </div>
                            </div>
                        @endif

                        <div class="yt-player-info">
                            <h5 class="yt-player-video-title" title="{{ $video['name'] }}">{{ $video['name'] }}</h5>
                            <small class="yt-player-date">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M19,3H18V1H16V3H8V1H6V3H5A2,2 0 0,0 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5A2,2 0 0,0 19,3M19,19H5V8H19V19Z"/>
                                </svg>
                                {{ Carbon::parse($video['date_posted'])->format('M d, Y') }}
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Custom Modal -->
                <div class="yt-player-modal" id="ytModal{{$index}}" style="display: none;">
                    <div class="yt-player-modal-backdrop" onclick="closeVideoModal('{{$index}}')"></div>
                    <div class="yt-player-modal-content">
                        <div class="yt-player-modal-header">
                            <h5 class="yt-player-modal-title">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12,20C7.59,20 4,16.41 4,12C4,7.59 7.59,4 12,4C16.41,4 20,7.59 20,12C20,16.41 16.41,20 12,20M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M10,16.5L16,12L10,7.5V16.5Z"/>
                                </svg>
                                {{ $video['name'] }}
                            </h5>
                            <div class="yt-player-modal-controls">
                                <button class="yt-player-btn yt-player-btn-outline" onclick="toggleVideoFullscreen('ytVideoContainer{{$index}}')" title="Toggle Fullscreen">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M5,5H10V7H7V10H5V5M14,5H19V10H17V7H14V5M17,14H19V19H14V17H17V14M10,17V19H5V14H7V17H10Z"/>
                                    </svg>
                                </button>
                                <button class="yt-player-btn yt-player-btn-close" onclick="closeVideoModal('{{$index}}')" title="Close">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="yt-player-modal-body" id="ytVideoContainer{{$index}}">
                            <div class="yt-player-video-wrapper">
                                <iframe id="ytVideoFrame{{$index}}" 
                                        data-video-src="https://www.youtube.com/embed/{{ $videoId }}?rel=0&showinfo=0&modestbranding=1&fs=1&iv_load_policy=3&enablejsapi=1" 
                                        src="" 
                                        frameborder="0" 
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                                        allowfullscreen>
                                </iframe>
                            </div>
                        </div>
                        <div class="yt-player-modal-footer">
                            <div class="yt-player-footer-info">
                                <small class="yt-player-post-date">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M19,3H18V1H16V3H8V1H6V3H5A2,2 0 0,0 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5A2,2 0 0,0 19,3M19,19H5V8H19V19Z"/>
                                    </svg>
                                    Posted: {{ Carbon::parse($video['date_posted'])->format('F d, Y') }}
                                </small>
                                <div class="yt-player-footer-actions">
                                    <button class="yt-player-btn yt-player-btn-primary" data-action="youtube" data-url="{{ $video['link'] }}" title="Open in YouTube">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M10,15L15.19,12L10,9V15M21.56,7.17C21.69,7.64 21.78,8.27 21.84,9.07C21.91,9.87 21.94,10.56 21.94,11.16L22,12C22,14.19 21.84,15.8 21.56,16.83C21.31,17.73 20.73,18.31 19.83,18.56C19.36,18.69 18.5,18.78 17.18,18.84C15.88,18.91 14.69,18.94 13.59,18.94L12,19C7.81,19 5.2,18.84 4.17,18.56C3.27,18.31 2.69,17.73 2.44,16.83C2.31,16.36 2.22,15.73 2.16,14.93C2.09,14.13 2.06,13.44 2.06,12.84L2,12C2,9.81 2.16,8.2 2.44,7.17C2.69,6.27 3.27,5.69 4.17,5.44C4.64,5.31 5.5,5.22 6.82,5.16C8.12,5.09 9.31,5.06 10.41,5.06L12,5C16.19,5 18.8,5.16 19.83,5.44C20.73,5.69 21.31,6.27 21.56,7.17Z"/>
                                        </svg>
                                        YouTube
                                    </button>
                                    <button class="yt-player-btn yt-player-btn-secondary" data-action="share" data-title="{{ $video['name'] }}" data-url="{{ $video['link'] }}" title="Share Video">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M18,16.08C17.24,16.08 16.56,16.38 16.04,16.85L8.91,12.7C8.96,12.47 9,12.24 9,12C9,11.76 8.96,11.53 8.91,11.3L15.96,7.19C16.5,7.69 17.21,8 18,8A3,3 0 0,0 21,5A3,3 0 0,0 18,2A3,3 0 0,0 15,5C15,5.24 15.04,5.47 15.09,5.7L8.04,9.81C7.5,9.31 6.79,9 6,9A3,3 0 0,0 3,12A3,3 0 0,0 6,15C6.79,15 7.5,14.69 8.04,14.19L15.16,18.34C15.11,18.55 15.08,18.77 15.08,19C15.08,20.61 16.39,21.91 18,21.91C19.61,21.91 20.92,20.61 20.92,19A2.92,2.92 0 0,0 18,16.08Z"/>
                                        </svg>
                                        Share
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            @endforeach
        </div>

    @else
        <div class="yt-player-empty">
            <div class="yt-player-empty-card">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M17,10.5V7A1,1 0 0,0 16,6H4A1,1 0 0,0 3,7V17A1,1 0 0,0 4,18H16A1,1 0 0,0 17,17V13.5L21,17.5V6.5L17,10.5Z"/>
                </svg>
                <h4 class="yt-player-empty-title">No Videos Found</h4>
                <p class="yt-player-empty-text">Check back later for new video content.</p>
            </div>
        </div>
    @endif
</div>

<!-- Custom JavaScript for Video Player -->
<script>
    // Video player functionality
    function openVideoModal(index) {
        const modal = document.getElementById('ytModal' + index);
        const iframe = document.getElementById('ytVideoFrame' + index);
        const videoSrc = iframe.dataset.videoSrc;
        
        // Show modal
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
        
        // Load video with autoplay
        iframe.src = videoSrc + '&autoplay=1';
        
        // Add animation
        setTimeout(() => {
            modal.classList.add('yt-player-modal-show');
        }, 10);
    }

    function closeVideoModal(index) {
        const modal = document.getElementById('ytModal' + index);
        const iframe = document.getElementById('ytVideoFrame' + index);
        
        // Stop video completely
        iframe.src = 'about:blank';
        
        // Hide modal with animation
        modal.classList.remove('yt-player-modal-show');
        
        setTimeout(() => {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }, 300);
    }

    // Fullscreen functionality
    function toggleVideoFullscreen(elementId) {
        const element = document.getElementById(elementId);
        
        if (!document.fullscreenElement) {
            // Enter fullscreen
            if (element.requestFullscreen) {
                element.requestFullscreen();
            } else if (element.mozRequestFullScreen) {
                element.mozRequestFullScreen();
            } else if (element.webkitRequestFullscreen) {
                element.webkitRequestFullscreen();
            } else if (element.msRequestFullscreen) {
                element.msRequestFullscreen();
            }
        } else {
            // Exit fullscreen
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.mozCancelFullScreen) {
                document.mozCancelFullScreen();
            } else if (document.webkitExitFullscreen) {
                document.webkitExitFullscreen();
            } else if (document.msExitFullscreen) {
                document.msExitFullscreen();
            }
        }
    }

    // Open video in YouTube
    function openVideoInYoutube(url) {
        window.open(url, '_blank');
    }

    // Share video functionality
    function shareVideoLink(title, url) {
        if (navigator.share) {
            navigator.share({
                title: title,
                url: url
            }).catch(console.error);
        } else {
            // Fallback: copy to clipboard
            navigator.clipboard.writeText(url).then(function() {
                showNotification('Video URL copied to clipboard!');
            }).catch(function() {
                // Fallback for older browsers
                const textArea = document.createElement('textarea');
                textArea.value = url;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                showNotification('Video URL copied to clipboard!');
            });
        }
    }

    // Show notification
    function showNotification(message) {
        const notification = document.createElement('div');
        notification.className = 'yt-player-notification';
        notification.textContent = message;
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.classList.add('yt-player-notification-show');
        }, 10);
        
        setTimeout(() => {
            notification.classList.remove('yt-player-notification-show');
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        }, 3000);
    }

    // Handle fullscreen changes
    document.addEventListener('fullscreenchange', function() {
        const fullscreenButtons = document.querySelectorAll('[onclick*="toggleVideoFullscreen"]');
        fullscreenButtons.forEach(button => {
            const svg = button.querySelector('svg path');
            if (document.fullscreenElement) {
                svg.setAttribute('d', 'M14,14H19V16H16V19H14V14M5,14H10V19H8V16H5V14M8,5H10V10H5V8H8V5M19,8V10H14V5H16V8H19Z');
                button.title = 'Exit Fullscreen';
            } else {
                svg.setAttribute('d', 'M5,5H10V7H7V10H5V5M14,5H19V10H17V7H14V5M17,14H19V19H14V17H17V14M10,17V19H5V14H7V17H10Z');
                button.title = 'Enter Fullscreen';
            }
        });
    });

    // Keyboard shortcuts
    document.addEventListener('keydown', function(event) {
        const openModal = document.querySelector('.yt-player-modal[style*="flex"]');
        
        if (openModal) {
            // Press 'F' for fullscreen
            if (event.key === 'f' || event.key === 'F') {
                const container = openModal.querySelector('[id^="ytVideoContainer"]');
                if (container) {
                    event.preventDefault();
                    toggleVideoFullscreen(container.id);
                }
            }
            
            // Press 'Escape' to close modal
            if (event.key === 'Escape') {
                event.preventDefault();
                const modalId = openModal.id.replace('ytModal', '');
                closeVideoModal(modalId);
            }
        }
    });

    // Close modal when clicking outside
    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('yt-player-modal-backdrop')) {
            const modal = event.target.parentElement;
            const modalId = modal.id.replace('ytModal', '');
            closeVideoModal(modalId);
        }
        
        // Handle data-action buttons
        const button = event.target.closest('[data-action]');
        if (button) {
            const action = button.dataset.action;
            if (action === 'youtube') {
                openVideoInYoutube(button.dataset.url);
            } else if (action === 'share') {
                shareVideoLink(button.dataset.title, button.dataset.url);
            }
        }
    });
</script>

<!-- Custom Styles - Completely isolated -->
<style>
    /* Container and Layout */
    .yt-player-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 15px;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }

    .yt-player-title {
        font-size: 2rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 2rem;
    }

    .yt-player-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
        margin-bottom: 2rem;
    }

    /* Video Cards - Reduced Size */
    .yt-player-card {
        background: #fff;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        cursor: pointer;
        border: 1px solid #e5e7eb;
    }

    .yt-player-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        border-color: #3b82f6;
    }

    .yt-player-thumb {
        position: relative;
        height: 160px; /* Reduced height */
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        overflow: hidden;
    }

    .yt-player-no-thumb {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }

    .yt-player-no-preview {
        text-align: center;
    }

    .yt-player-no-preview p {
        margin: 0.5rem 0 0 0;
        font-size: 0.8rem;
        opacity: 0.9;
    }

    .yt-player-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: all 0.3s ease;
    }

    .yt-player-card:hover .yt-player-overlay {
        opacity: 1;
    }

    .yt-player-play-wrapper {
        transition: transform 0.3s ease;
    }

    .yt-player-card:hover .yt-player-play-wrapper {
        transform: scale(1.1);
    }

    .yt-player-play-btn {
        width: 50px; /* Reduced size */
        height: 50px; /* Reduced size */
        background: #dc2626;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        box-shadow: 0 4px 20px rgba(220, 38, 38, 0.4);
        transition: all 0.3s ease;
    }

    .yt-player-card:hover .yt-player-play-btn {
        background: #b91c1c;
        box-shadow: 0 6px 25px rgba(220, 38, 38, 0.6);
    }

    .yt-player-info {
        padding: 0.75rem; /* Reduced padding */
        border-top: 1px solid #f3f4f6;
    }

    .yt-player-video-title {
        font-size: 0.95rem; /* Reduced font size */
        font-weight: 600;
        color: #111827;
        margin: 0 0 0.5rem 0; /* Reduced margin */
        line-height: 1.3;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .yt-player-date {
        color: #6b7280;
        font-size: 0.8rem; /* Reduced font size */
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }

    /* Custom Modal */
    .yt-player-modal {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.8);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .yt-player-modal-show {
        opacity: 1;
    }

    .yt-player-modal-backdrop {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
    }

    .yt-player-modal-content {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        width: 90vw;
        max-width: 1000px;
        max-height: 90vh;
        position: relative;
        transform: scale(0.9);
        transition: transform 0.3s ease;
    }

    .yt-player-modal-show .yt-player-modal-content {
        transform: scale(1);
    }

    .yt-player-modal-header {
        background: #1f2937;
        color: white;
        padding: 1rem 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .yt-player-modal-title {
        font-size: 1.125rem;
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .yt-player-modal-controls {
        display: flex;
        gap: 0.5rem;
    }

    .yt-player-btn {
        background: none;
        border: 1px solid currentColor;
        color: currentColor;
        padding: 0.5rem;
        border-radius: 6px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        font-size: 0.875rem;
        gap: 0.5rem;
    }

    .yt-player-btn:hover {
        background: currentColor;
        color: #1f2937;
    }

    .yt-player-btn-close {
        color: #ef4444;
    }

    .yt-player-btn-close:hover {
        background: #ef4444;
        color: white;
    }

    .yt-player-modal-body {
        padding: 0;
        position: relative;
        background: #000;
    }

    .yt-player-video-wrapper {
        position: relative;
        padding-bottom: 56.25%;
        height: 0;
    }

    .yt-player-video-wrapper iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }

    .yt-player-modal-footer {
        background: #f9fafb;
        padding: 1rem 1.5rem;
        border-top: 1px solid #e5e7eb;
    }

    .yt-player-footer-info {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .yt-player-post-date {
        color: #6b7280;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .yt-player-footer-actions {
        display: flex;
        gap: 0.75rem;
    }

    .yt-player-btn-primary {
        background: #dc2626;
        color: white;
        border-color: #dc2626;
    }

    .yt-player-btn-primary:hover {
        background: #b91c1c;
        border-color: #b91c1c;
        color: white;
    }

    .yt-player-btn-secondary {
        background: #6b7280;
        color: white;
        border-color: #6b7280;
    }

    .yt-player-btn-secondary:hover {
        background: #4b5563;
        border-color: #4b5563;
        color: white;
    }

    /* Empty State */
    .yt-player-empty {
        text-align: center;
        padding: 4rem 0;
    }

    .yt-player-empty-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 3rem 2rem;
        max-width: 400px;
        margin: 0 auto;
    }

    .yt-player-empty svg {
        color: #9ca3af;
        margin-bottom: 1.5rem;
    }

    .yt-player-empty-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #6b7280;
        margin: 0 0 1rem 0;
    }

    .yt-player-empty-text {
        color: #9ca3af;
        margin: 0;
    }

    /* Notification */
    .yt-player-notification {
        position: fixed;
        top: 20px;
        right: 20px;
        background: #10b981;
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 8px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        z-index: 10000;
        transform: translateX(100%);
        transition: transform 0.3s ease;
    }

    .yt-player-notification-show {
        transform: translateX(0);
    }

    /* Responsive adjustments */
    @media (max-width: 992px) {
        .yt-player-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .yt-player-title {
            font-size: 1.5rem;
        }

        .yt-player-card {
            border-radius: 8px;
        }

        .yt-player-thumb {
            height: 140px;
        }

        .yt-player-play-btn {
            width: 40px;
            height: 40px;
        }

        .yt-player-modal-content {
            width: 95vw;
        }
    }

    @media (max-width: 576px) {
        .yt-player-grid {
            grid-template-columns: 1fr;
        }

        .yt-player-thumb {
            height: 180px;
        }

        .yt-player-footer-info {
            flex-direction: column;
            align-items: flex-start;
        }

        .yt-player-modal-title {
            font-size: 1rem;
        }

        .yt-player-btn {
            font-size: 0.75rem;
            padding: 0.4rem;
        }

        .yt-player-footer-actions {
            width: 100%;
            justify-content: flex-start;
            flex-wrap: wrap;
            gap: 0.5rem;
        }
    }
</style>
@endsection