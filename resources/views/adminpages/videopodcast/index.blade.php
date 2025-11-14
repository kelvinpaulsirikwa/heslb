@extends('adminpages.layouts.app')

@section('content')
<div class="vp-admin-container">
    <div class="vp-header">
        <h2 class="vp-title">Video Podcasts Management</h2>
        <a href="{{ route('videopodcasts.create') }}" class="vp-btn vp-btn-primary">
            <span class="vp-icon-plus">+</span>
            Add New Video
        </a>
    </div>

    @if(session('success'))
        <div class="vp-alert vp-alert-success">
            <span class="vp-icon-check">✓</span>
            {{ session('success') }}
        </div>
    @endif

    <div class="vp-grid">
        @forelse($videos as $video)
            <div class="vp-card">
                <div class="vp-video-container">
                    @if($video->link)
                        @php
                            // Extract video ID from different video platforms
                            $videoId = null;
                            $platform = 'unknown';
                            
                            if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $video->link, $matches)) {
                                $videoId = $matches[1];
                                $platform = 'youtube';
                            } elseif (preg_match('/vimeo\.com\/(\d+)/', $video->link, $matches)) {
                                $videoId = $matches[1];
                                $platform = 'vimeo';
                            }
                        @endphp

                        @if($platform === 'youtube' && $videoId)
                            <div class="vp-video-preview" data-video-id="{{ $videoId }}" data-platform="youtube">
                                <img src="https://img.youtube.com/vi/{{ $videoId }}/maxresdefault.jpg" 
                                     alt="{{ $video->name }}" 
                                     class="vp-thumbnail">
                                <div class="vp-play-overlay">
                                    <button class="vp-play-btn" onclick="playVideo('{{ $videoId }}', 'youtube', this)">
                                        <svg class="vp-play-icon" viewBox="0 0 24 24">
                                            <path d="M8 5v14l11-7z"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @elseif($platform === 'vimeo' && $videoId)
                            <div class="vp-video-preview" data-video-id="{{ $videoId }}" data-platform="vimeo">
                                <div class="vp-vimeo-placeholder">
                                    <div class="vp-play-overlay">
                                        <button class="vp-play-btn" onclick="playVideo('{{ $videoId }}', 'vimeo', this)">
                                            <svg class="vp-play-icon" viewBox="0 0 24 24">
                                                <path d="M8 5v14l11-7z"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="vp-video-placeholder">
                                <span class="vp-icon-video">📺</span>
                                <p class="vp-placeholder-text">Video Preview</p>
                            </div>
                        @endif
                    @else
                        <div class="vp-video-placeholder">
                            <span class="vp-icon-video">📺</span>
                            <p class="vp-placeholder-text">No Video Link</p>
                        </div>
                    @endif
                </div>

                <div class="vp-card-content">
                    <h3 class="vp-video-title">{{ $video->name }}</h3>
                    
                    <div class="vp-video-meta">
                        <div class="vp-meta-item">
                            <span class="vp-meta-label">ID:</span>
                            <span class="vp-meta-value">#{{ $video->id }}</span>
                        </div>
                        <div class="vp-meta-item">
                            <span class="vp-meta-label">Posted by:</span>
                            <span class="vp-meta-value">{{ $video->user->username ?? 'Unknown' }}</span>
                        </div>
                        <div class="vp-meta-item">
                            <span class="vp-meta-label">Date:</span>
                            <span class="vp-meta-value">{{ \Carbon\Carbon::parse($video->date_posted)->format('M d, Y') }}</span>
                        </div>
                    </div>

                    @if($video->link)
                        <div class="vp-link-section">
                            <label class="vp-link-label">Video URL:</label>
                            <div class="vp-link-container">
                                <input type="text" 
                                       class="vp-link-input" 
                                       value="{{ $video->link }}" 
                                       readonly>
                                <button class="vp-copy-btn" onclick="copyToClipboard('{{ $video->link }}', this)">
                                    <span class="vp-icon-copy">⧉</span>
                                </button>
                            </div>
                        </div>
                    @endif

                    <div class="vp-actions">
                        <a href="{{ route('videopodcasts.edit', $video->id) }}" 
                           class="vp-btn vp-btn-warning">
                            <span class="vp-icon-edit">✎</span>
                            Edit
                        </a>
                        <form action="{{ route('videopodcasts.destroy', $video->id) }}" 
                              method="POST" 
                              class="vp-delete-form" 
                              id="delete-form-{{ $video->id }}">
                            @csrf
                            @method('DELETE')
                            <button type="button" 
                                    class="vp-btn vp-btn-danger"
                                    onclick="openDeleteModal('{{ $video->id }}', '{{ addslashes($video->name) }}')">
                                <span class="vp-icon-delete">×</span>
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="vp-empty-state">
                <div class="vp-empty-icon">
                    <span class="vp-icon-video-off">📺</span>
                </div>
                <h3 class="vp-empty-title">No Video Podcasts Found</h3>
                <p class="vp-empty-description">Start by adding your first video podcast</p>
                <a href="{{ route('videopodcasts.create') }}" class="vp-btn vp-btn-primary">
                    <span class="vp-icon-plus">+</span>
                    Add New Video
                </a>
            </div>
        @endforelse
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="vp-delete-modal" class="vp-modal-overlay">
    <div class="vp-modal">
        <div class="vp-modal-header">
            <div class="vp-modal-icon-danger">
                <svg class="vp-modal-warning-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
            </div>
            <div class="vp-modal-title-section">
                <h3 class="vp-modal-title">Delete Video Podcast</h3>
                <p class="vp-modal-subtitle">This action cannot be undone</p>
            </div>
        </div>
        
        <div class="vp-modal-body">
            <p class="vp-modal-message">
                Are you sure you want to delete "<span id="vp-video-name" class="vp-video-name-highlight"></span>"? 
                This will permanently remove the video podcast and all its associated data.
            </p>
        </div>
        
        <div class="vp-modal-footer">
            <button type="button" class="vp-btn vp-btn-secondary" onclick="closeDeleteModal()">
                Cancel
            </button>
            <button type="button" class="vp-btn vp-btn-danger-solid" onclick="confirmDelete()" id="vp-confirm-delete">
                <span class="vp-icon-delete">×</span>
                Delete Video
            </button>
        </div>
    </div>
</div>

<style>
/* Video Podcasts Admin Styles - Unique prefix: vp- */
* {
    box-sizing: border-box;
}

.vp-admin-container {
    width: 100%;
    margin: 0 auto;
    padding: 30px;
    background: #f8fafc;
    min-height: 100vh;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
}

.vp-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    background: #ffffff;
    padding: 32px;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
}

.vp-title {
    margin: 0;
    color: #0f172a;
    font-size: 28px;
    font-weight: 600;
}

.vp-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    border: none;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
    white-space: nowrap;
    border: 1px solid;
}

.vp-btn-primary {
    background: #4f46e5;
    color: white;
    border-color: #4f46e5;
    padding: 12px 20px;
}

.vp-btn-primary:hover {
    background: #4338ca;
    border-color: #4338ca;
    color: white;
    text-decoration: none;
}

.vp-btn-warning {
    background: #fef3c7;
    color: #92400e;
    border-color: #fcd34d;
    padding: 8px 12px;
}

.vp-btn-warning:hover {
    background: #fde68a;
    color: #78350f;
    border-color: #f59e0b;
    text-decoration: none;
}

.vp-btn-danger {
    background: #fee2e2;
    color: #dc2626;
    border-color: #fca5a5;
    padding: 8px 12px;
}

.vp-btn-danger:hover {
    background: #fecaca;
    color: #b91c1c;
    border-color: #f87171;
}

.vp-btn-danger-solid {
    background: #dc2626;
    color: white;
    border-color: #dc2626;
    padding: 10px 16px;
}

.vp-btn-danger-solid:hover {
    background: #b91c1c;
    border-color: #b91c1c;
}

.vp-btn-secondary {
    background: #f8fafc;
    color: #374151;
    border-color: #e2e8f0;
    padding: 10px 16px;
}

.vp-btn-secondary:hover {
    background: #f1f5f9;
    color: #1f2937;
    border-color: #d1d5db;
    text-decoration: none;
}

.vp-alert {
    padding: 16px 20px;
    border-radius: 6px;
    margin-bottom: 24px;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 8px;
    border: 1px solid;
}

.vp-alert-success {
    background: #f0fdf4;
    border-color: #bbf7d0;
    color: #166534;
}

.vp-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
    gap: 20px;
    width: 100%;
}

.vp-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    overflow: hidden;
    transition: all 0.2s ease;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    animation: vpFadeIn 0.2s ease-out;
}

.vp-card:hover {
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    border-color: #d1d5db;
}

.vp-video-container {
    position: relative;
    width: 100%;
    height: 200px;
    overflow: hidden;
    background: #f1f5f9;
}

.vp-video-preview {
    width: 100%;
    height: 100%;
    position: relative;
    background: #000;
}

.vp-thumbnail {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.vp-video-placeholder,
.vp-vimeo-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: #6b7280;
    color: white;
}

.vp-placeholder-text {
    margin: 10px 0 0 0;
    font-size: 14px;
    color: #d1d5db;
}

.vp-icon-video {
    font-size: 24px;
    display: block;
    margin-bottom: 4px;
}

.vp-play-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(0,0,0,0.4);
    opacity: 0;
    transition: opacity 0.2s ease;
}

.vp-video-container:hover .vp-play-overlay {
    opacity: 1;
}

.vp-play-btn {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: rgba(255,255,255,0.95);
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}

.vp-play-btn:hover {
    background: white;
    transform: scale(1.05);
}

.vp-play-icon {
    width: 20px;
    height: 20px;
    fill: #374151;
    margin-left: 2px;
}

.vp-card-content {
    padding: 20px;
}

.vp-video-title {
    margin: 0 0 16px 0;
    font-size: 16px;
    font-weight: 600;
    color: #0f172a;
    line-height: 1.4;
    word-break: break-word;
}

.vp-video-meta {
    margin-bottom: 16px;
}

.vp-meta-item {
    display: flex;
    justify-content: space-between;
    margin-bottom: 6px;
}

.vp-meta-label {
    color: #64748b;
    font-size: 13px;
    font-weight: 400;
}

.vp-meta-value {
    color: #374151;
    font-weight: 500;
    font-size: 13px;
}

.vp-link-section {
    margin-bottom: 20px;
}

.vp-link-label {
    display: block;
    margin-bottom: 8px;
    color: #64748b;
    font-size: 13px;
    font-weight: 500;
}

.vp-link-container {
    display: flex;
    gap: 8px;
}

.vp-link-input {
    flex: 1;
    padding: 8px 12px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 12px;
    background: #f9fafb;
    color: #374151;
    transition: all 0.2s ease;
    min-width: 0;
}

.vp-link-input:focus {
    outline: none;
    border-color: #6366f1;
    background: white;
    box-shadow: 0 0 0 1px #6366f1;
}

.vp-copy-btn {
    padding: 8px 12px;
    background: #f8fafc;
    color: #374151;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 12px;
}

.vp-copy-btn:hover {
    background: #f1f5f9;
    color: #1f2937;
}

.vp-actions {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 8px;
    align-items: center;
}

.vp-delete-form {
    display: contents;
}

.vp-empty-state {
    grid-column: 1 / -1;
    text-align: center;
    padding: 60px 32px;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
}

.vp-empty-icon {
    width: 48px;
    height: 48px;
    background: #f1f5f9;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 16px;
    font-size: 20px;
    color: #9ca3af;
}

.vp-empty-title {
    font-size: 16px;
    font-weight: 500;
    color: #374151;
    margin: 0 0 8px 0;
}

.vp-empty-description {
    color: #6b7280;
    line-height: 1.5;
    font-size: 14px;
    margin-bottom: 24px;
}

/* Video player styles */
.vp-video-player {
    width: 100%;
    height: 200px;
    border: none;
}

/* Modal Styles */
.vp-modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 10000;
    padding: 16px;
    backdrop-filter: blur(4px);
}

.vp-modal-overlay.vp-modal-open {
    display: flex;
    animation: vpModalFadeIn 0.2s ease-out;
}

.vp-modal {
    background: white;
    border-radius: 12px;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    max-width: 480px;
    width: 100%;
    max-height: 90vh;
    overflow: hidden;
    animation: vpModalSlideIn 0.2s ease-out;
}

.vp-modal-header {
    padding: 24px 24px 0;
    display: flex;
    gap: 16px;
    align-items: flex-start;
}

.vp-modal-icon-danger {
    flex-shrink: 0;
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: #fee2e2;
    display: flex;
    align-items: center;
    justify-content: center;
}

.vp-modal-warning-icon {
    width: 24px;
    height: 24px;
    color: #dc2626;
}

.vp-modal-title-section {
    flex: 1;
    min-width: 0;
}

.vp-modal-title {
    font-size: 18px;
    font-weight: 600;
    color: #111827;
    margin: 0 0 4px 0;
    line-height: 1.4;
}

.vp-modal-subtitle {
    font-size: 14px;
    color: #6b7280;
    margin: 0;
    line-height: 1.4;
}

.vp-modal-body {
    padding: 16px 24px 24px;
}

.vp-modal-message {
    color: #374151;
    line-height: 1.6;
    font-size: 14px;
    margin: 0;
}

.vp-video-name-highlight {
    font-weight: 600;
    color: #111827;
}

.vp-modal-footer {
    padding: 20px 24px 24px;
    display: flex;
    gap: 12px;
    justify-content: flex-end;
    background: #f9fafb;
    border-top: 1px solid #e5e7eb;
}

.vp-modal-footer .vp-btn {
    min-width: 80px;
}

/* Animations */
@keyframes vpFadeIn {
    from {
        opacity: 0;
        transform: translateY(4px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes vpModalFadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

@keyframes vpModalSlideIn {
    from {
        opacity: 0;
        transform: scale(0.95) translateY(-8px);
    }
    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

/* Responsive Design */
@media (max-width: 768px) {
    .vp-admin-container {
        padding: 16px;
    }
    
    .vp-header {
        padding: 24px;
        flex-direction: column;
        gap: 16px;
        text-align: center;
    }
    
    .vp-title {
        font-size: 24px;
    }
    
    .vp-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }
    
    .vp-actions {
        grid-template-columns: 1fr;
        gap: 8px;
    }
    
    .vp-empty-state {
        padding: 40px 20px;
    }
    
    .vp-modal {
        margin: 16px;
    }
    
    .vp-modal-header {
        padding: 20px 20px 0;
        gap: 12px;
    }
    
    .vp-modal-icon-danger {
        width: 40px;
        height: 40px;
    }
    
    .vp-modal-warning-icon {
        width: 20px;
        height: 20px;
    }
    
    .vp-modal-title {
        font-size: 16px;
    }
    
    .vp-modal-body {
        padding: 12px 20px 20px;
    }
    
    .vp-modal-footer {
        padding: 16px 20px 20px;
        flex-direction: column-reverse;
    }
    
    .vp-modal-footer .vp-btn {
        width: 100%;
        justify-content: center;
    }
}

@media (max-width: 480px) {
    .vp-card-content {
        padding: 16px;
    }
    
    .vp-title {
        font-size: 20px;
    }
}
</style>

<script>
let currentDeleteVideoId = null;

function playVideo(videoId, platform, button) {
    const container = button.closest('.vp-video-preview');
    let embedUrl = '';
    
    if (platform === 'youtube') {
        embedUrl = `https://www.youtube.com/embed/${videoId}?autoplay=1`;
    } else if (platform === 'vimeo') {
        embedUrl = `https://player.vimeo.com/video/${videoId}?autoplay=1`;
    }
    
    if (embedUrl) {
        container.innerHTML = `<iframe class="vp-video-player" src="${embedUrl}" frameborder="0" allowfullscreen></iframe>`;
    }
}

function copyToClipboard(text, button) {
    navigator.clipboard.writeText(text).then(function() {
        const originalContent = button.innerHTML;
        button.innerHTML = '<span class="vp-icon-check">✓</span>';
        button.style.background = '#f0fdf4';
        button.style.borderColor = '#bbf7d0';
        button.style.color = '#166534';
        
        setTimeout(function() {
            button.innerHTML = originalContent;
            button.style.background = '#f8fafc';
            button.style.borderColor = '#e2e8f0';
            button.style.color = '#374151';
        }, 2000);
    });
}

function openDeleteModal(videoId, videoName) {
    currentDeleteVideoId = videoId;
    document.getElementById('vp-video-name').textContent = videoName;
    document.getElementById('vp-delete-modal').classList.add('vp-modal-open');
    document.body.style.overflow = 'hidden';
}

function closeDeleteModal() {
    document.getElementById('vp-delete-modal').classList.remove('vp-modal-open');
    document.body.style.overflow = '';
    currentDeleteVideoId = null;
}

function confirmDelete() {
    if (currentDeleteVideoId) {
        document.getElementById('delete-form-' + currentDeleteVideoId).submit();
    }
}

// Close modal when clicking on overlay
document.getElementById('vp-delete-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && document.getElementById('vp-delete-modal').classList.contains('vp-modal-open')) {
        closeDeleteModal();
    }
});
</script>

@endsection