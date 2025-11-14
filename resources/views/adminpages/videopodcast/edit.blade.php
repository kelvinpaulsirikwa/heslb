@extends('adminpages.layouts.app')

@section('content')
<div class="container-fluid bg-white p-4 mt-2">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">Edit Video Podcast</h3>
                        <a href="{{ route('videopodcasts.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to Video Podcasts
                        </a>
        </div>
    </div>
                <div class="card-body">
                    <x-admin-validation-errors />

                    <form id="videoPodcastsEditForm" action="{{ route('videopodcasts.update', $video->id) }}" method="POST" data-admin-validation="videoPodcasts">
            @csrf
            @method('PUT')
            
                        <!-- Video Name -->
                        <x-admin-form-field
                            type="text"
                           name="name" 
                            label="Video Podcast Name"
                            :value="old('name', $video->name)"
                            placeholder="Enter video podcast name"
                           required
                            help="Give your video podcast a descriptive name"
                        />

                        <!-- YouTube Link -->
                        <div class="mb-3">
                            <label for="link" class="form-label">
                                <strong>YouTube Video Link</strong> <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                    <input type="url" 
                           id="link" 
                           name="link" 
                                       class="form-control @error('link') is-invalid @enderror" 
                           value="{{ old('link', $video->link) }}"
                           placeholder="https://www.youtube.com/watch?v=..."
                                       required
                                       onblur="validateYouTubeLink(this)">
                            </div>
                    @error('link')
                                <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle"></i> Supported formats: YouTube video URLs (youtube.com or youtu.be)
                            </small>
                            <div id="link-validation" class="validation-message"></div>
                    </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label for="description" class="form-label">
                                <strong>Description</strong> <span class="text-muted">(Optional)</span>
                            </label>
                            <textarea name="description" 
                                      id="description"
                                      class="ckeditor form-control @error('description') is-invalid @enderror" 
                                      rows="6"
                                      placeholder="Enter video description...">{{ old('description', $video->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle"></i> Optional description with rich text formatting support
                            </small>
                </div>

                        <!-- Current Video Preview -->
                    @if($video->link)
                        @php
                            $videoId = null;
                            if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $video->link, $matches)) {
                                $videoId = $matches[1];
                            }
                        @endphp
                        
                        @if($videoId)
                                <div class="mb-3">
                                    <label class="form-label">
                                        <strong>Current Video</strong>
                                    </label>
                                    <div class="card">
                                        <div class="card-body text-center">
                                        <img src="https://img.youtube.com/vi/{{ $videoId }}/maxresdefault.jpg" 
                                             alt="{{ $video->name }}"
                                                 class="img-fluid rounded"
                                                 style="max-height: 300px;"
                                             onerror="this.src='https://img.youtube.com/vi/{{ $videoId }}/hqdefault.jpg'">
                                            <p class="mt-2 mb-0 text-muted">Current Video ID: {{ $videoId }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif

                    <!-- New Video Preview (for changes) -->
                        <div class="mb-3" id="video-preview-section" style="display: none;">
                            <label class="form-label">
                                <strong>New Video Preview</strong>
                            </label>
                            <div class="card">
                                <div class="card-body">
                                    <div id="video-preview" class="text-center">
                                <!-- YouTube thumbnail will be inserted here -->
                            </div>
                        </div>
                    </div>
                </div>

                        <!-- Video Details -->
                @if($video->user || $video->date_posted)
                        <div class="mb-3">
                            <label class="form-label">
                                <strong>Video Details</strong>
                            </label>
                            <div class="card bg-light">
                                <div class="card-body">
                        @if($video->user)
                                        <p class="mb-1"><strong>Posted by:</strong> {{ $video->user->username }}</p>
                        @endif
                        @if($video->date_posted)
                                        <p class="mb-0"><strong>Posted on:</strong> {{ $video->date_posted->format('M d, Y \a\t h:i A') }}</p>
                        @endif
                        </div>
                    </div>
                </div>
                @endif

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Update Video Podcast
                </button>
                            <a href="{{ route('videopodcasts.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('resources/js/admin-validation.js') }}"></script>
<script src="{{ asset('public/ckeditor/ckeditor.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize CKEditor for description field
    if (typeof CKEDITOR !== 'undefined') {
        CKEDITOR.replace('description', {
            height: 200,
            toolbar: [
                { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike'] },
                { name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent'] },
                { name: 'links', items: ['Link', 'Unlink'] },
                { name: 'insert', items: ['Table', 'HorizontalRule'] },
                { name: 'styles', items: ['Format'] },
                { name: 'colors', items: ['TextColor', 'BGColor'] },
                { name: 'tools', items: ['Maximize'] },
                { name: 'document', items: ['Source'] }
            ],
            removePlugins: 'elementspath,image,forms,iframe',
            resize_enabled: false,
            extraPlugins: 'justify',
            justifyClasses: ['text-left', 'text-center', 'text-right', 'text-justify'],
            format_tags: 'p;h3;h4;h5;h6;pre;address;div'
        });
    }
    
    // Handle form submission with CKEditor
    const form = document.getElementById('videoPodcastsEditForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            // Update CKEditor instances before form submission
            if (typeof CKEDITOR !== 'undefined') {
                for (instance in CKEDITOR.instances) {
                    CKEDITOR.instances[instance].updateElement();
                }
            }
        });
    }
    
    // YouTube URL validation function
    window.validateYouTubeLink = function(input) {
    const url = input.value.trim();
        const validationDiv = document.getElementById('link-validation');
        const previewSection = document.getElementById('video-preview-section');
        const previewDiv = document.getElementById('video-preview');
        
        // Clear previous validation
        validationDiv.className = 'validation-message';
    validationDiv.style.display = 'none';
    previewSection.style.display = 'none';
        input.classList.remove('is-valid', 'is-invalid');
    
    if (!url) {
            return; // Empty is allowed
    }
    
    // YouTube URL patterns
    const youtubePatterns = [
        /^https?:\/\/(?:www\.)?youtube\.com\/watch\?v=([a-zA-Z0-9_-]{11})(?:\S+)?$/,
        /^https?:\/\/(?:www\.)?youtube\.com\/embed\/([a-zA-Z0-9_-]{11})(?:\S+)?$/,
        /^https?:\/\/youtu\.be\/([a-zA-Z0-9_-]{11})(?:\S+)?$/,
        /^https?:\/\/(?:www\.)?youtube\.com\/v\/([a-zA-Z0-9_-]{11})(?:\S+)?$/
    ];
    
    let videoId = null;
    let isValid = false;
    
    for (let pattern of youtubePatterns) {
        const match = url.match(pattern);
        if (match) {
            videoId = match[1];
            isValid = true;
            break;
        }
    }
    
    if (isValid && videoId) {
            // Valid YouTube URL
            input.classList.add('is-valid');
            validationDiv.innerHTML = '<i class="fas fa-check-circle text-success"></i> Valid YouTube URL detected';
            validationDiv.className = 'validation-message text-success';
        validationDiv.style.display = 'block';
        
            // Show video preview
        previewDiv.innerHTML = `
            <img src="https://img.youtube.com/vi/${videoId}/maxresdefault.jpg"
                 alt="YouTube Video Preview"
                     class="img-fluid rounded"
                     style="max-height: 300px;"
                 onerror="this.src='https://img.youtube.com/vi/${videoId}/hqdefault.jpg'">
                <p class="mt-2 mb-0 text-muted">New Video ID: ${videoId}</p>
        `;
        previewSection.style.display = 'block';
    } else {
            // Invalid YouTube URL
            input.classList.add('is-invalid');
            validationDiv.innerHTML = '<i class="fas fa-exclamation-triangle text-danger"></i> Please enter a valid YouTube URL';
            validationDiv.className = 'validation-message text-danger';
        validationDiv.style.display = 'block';
    }
    };
    
    // Admin validation is automatically initialized by the data-admin-validation attribute
});
</script>
@endpush
@endsection