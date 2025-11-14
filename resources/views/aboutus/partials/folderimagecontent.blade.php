@extends('aboutus.aboutus')

@php($pageTitle = 'Strategic Partners')

<style>
    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 15px;
    }

    .gallery-item {
        overflow: hidden;
        position: relative;
        border-radius: 10px;
        transition: transform 0.3s ease-in-out;
        background: #fff;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }

    .gallery-item img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        display: block;
        transition: transform 0.4s ease-in-out;
        border-radius: 10px 10px 0 0;
    }

    .gallery-item:hover img {
        transform: scale(1.05);
        filter: brightness(0.7);
    }

    .view-button {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: rgba(0, 123, 255, 0.9);
        color: white;
        padding: 12px 30px;
        border-radius: 25px;
        font-size: 16px;
        font-weight: bold;
        border: 2px solid white;
        cursor: pointer;
        opacity: 0;
        transition: opacity 0.3s ease-in-out;
        pointer-events: none;
        z-index: 10;
    }

    .gallery-item:hover .view-button {
        opacity: 1;
        pointer-events: all;
    }

    .view-button:hover {
        background: rgba(0, 123, 255, 1);
        transform: translate(-50%, -50%) scale(1.1);
    }

    .gallery-caption {
        padding: 8px 10px;
        font-size: 14px;
        color: #555;
        text-align: center;
        background: #f8f9fa;
        border-radius: 0 0 10px 10px;
        white-space: nowrap !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
        display: block !important;
        max-width: 100%;
        min-height: 38px;
        line-height: 1.5;
    }
    
    .gallery-caption:empty {
        display: none !important;
    }
    
    .gallery-caption * {
        display: inline !important;
        white-space: nowrap !important;
    }

    /* Event Description Styling */
    .event-description {
        background: #f9f9f9;
        padding: 15px;
        border-left: 4px solid #007bff;
        border-radius: 6px;
        font-size: 1rem;
        color: #444;
        margin-top: 10px;
    }

    /* Fullscreen Modal Dialog */
    .fullscreen-modal {
        display: none;
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        width: 100vw !important;
        height: 100vh !important;
        background: rgba(0, 0, 0, 0.95) !important;
        z-index: 2147483647 !important;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        overflow: hidden;
        padding: 20px;
        box-sizing: border-box;
        margin: 0 !important;
    }

    .fullscreen-modal.active {
        display: flex !important;
    }

    .fullscreen-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        max-height: 90vh;
        width: 90vw;
        z-index: 2147483647;
        position: relative;
        gap: 20px;
    }

    .fullscreen-modal img {
        max-width: 90vw;
        max-height: 60vh;
        object-fit: contain;
        border-radius: 8px;
        display: block;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
    }

    .fullscreen-description {
        color: #fff;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        padding: 20px 30px;
        border-radius: 12px;
        max-width: 90vw;
        max-height: 25vh;
        overflow-y: auto;
        text-align: left;
        font-size: 16px;
        line-height: 1.8;
        box-shadow: 0 5px 30px rgba(0, 0, 0, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.2);
        word-wrap: break-word;
        white-space: normal;
    }

    .fullscreen-description::-webkit-scrollbar {
        width: 8px;
    }

    .fullscreen-description::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 4px;
    }

    .fullscreen-description::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.3);
        border-radius: 4px;
    }

    .fullscreen-description::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.5);
    }

    .close-fullscreen {
        position: fixed !important;
        top: 20px !important;
        right: 30px !important;
        color: #fff !important;
        font-size: 40px;
        font-weight: bold;
        cursor: pointer;
        background: rgba(255, 255, 255, 0.15) !important;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s;
        z-index: 2147483647 !important;
        border: none;
        outline: none;
    }

    .close-fullscreen:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: rotate(90deg);
    }

    /* Prevent body scroll when modal is open */
    body.modal-open {
        overflow: hidden !important;
        position: fixed;
        width: 100%;
        height: 100%;
    }

    /* Medium screens */
    @media (max-width: 768px) {
        .gallery-grid {
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        }
        .fullscreen-modal {
            padding: 10px;
        }
        .fullscreen-content {
            max-height: 85vh;
            width: 95vw;
            gap: 15px;
        }
        .fullscreen-modal img {
            max-width: 95vw;
            max-height: 50vh;
        }
        .fullscreen-description {
            font-size: 14px;
            padding: 15px 20px;
            max-width: 95vw;
            max-height: 30vh;
            line-height: 1.6;
        }
        .view-button {
            padding: 10px 20px;
            font-size: 14px;
        }
        .close-fullscreen {
            top: 10px;
            right: 10px;
            width: 45px;
            height: 45px;
            font-size: 35px;
        }
    }

    /* Small screens - mobile */
    @media (max-width: 480px) {
        .gallery-grid {
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: 10px;
        }
        .gallery-caption {
            font-size: 12px;
        }
        .fullscreen-modal {
            padding: 5px;
        }
        .fullscreen-content {
            max-height: 85vh;
            width: 98vw;
            gap: 10px;
        }
        .fullscreen-modal img {
            max-width: 98vw;
            max-height: 45vh;
        }
        .fullscreen-description {
            font-size: 13px;
            padding: 12px 15px;
            max-width: 98vw;
            max-height: 35vh;
            line-height: 1.5;
        }
        .close-fullscreen {
            top: 5px;
            right: 5px;
            width: 40px;
            height: 40px;
            font-size: 30px;
        }
        .view-button {
            padding: 8px 16px;
            font-size: 12px;
        }
        .fullscreen-description::-webkit-scrollbar {
            width: 5px;
        }
    }
</style>

@section('aboutus-content')
<div class="container mt-4">

    <!-- Event Info -->
    <div class="mb-2">
        
        <div class="event-description">
            {!! nl2br(e($event->description)) !!}
        </div>

    </div>

    <!-- Gallery -->
    @if($event->images->count() > 0)
        <div class="gallery-grid">
            @foreach ($event->images as $image)
                <div class="gallery-item" 
                     data-image="{{ asset('images/storage/' . $image->image_link) }}"
                     data-description="{{ strip_tags($image->description) }}">
                    <img src="{{ asset('images/storage/' . $image->image_link) }}" alt="Image">
                    <button class="view-button">View</button>
                    @if(!empty(trim(strip_tags($image->description))))
                    <div class="gallery-caption">
                        {!! $image->description !!}
                    </div>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <p class="text-muted">No images uploaded for this event.</p>
    @endif

    <a href="{{ route('admin.taasisevents.index') }}" class="btn btn-primary mt-4">Back</a>
</div>

<!-- Fullscreen Modal Dialog -->
<div class="fullscreen-modal" id="fullscreenModal" onclick="closeFullscreen()">
    <button class="close-fullscreen" onclick="closeFullscreen(); event.stopPropagation();">&times;</button>
    <div class="fullscreen-content" onclick="event.stopPropagation()">
        <img id="fullscreenImage" src="" alt="Fullscreen Image">
        <div class="fullscreen-description" id="fullscreenDescription"></div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Move modal to body to avoid z-index stacking context issues
        const modal = document.getElementById('fullscreenModal');
        if (modal && !modal.dataset.moved) {
            document.body.appendChild(modal);
            modal.dataset.moved = 'true';
        }

        // Add click handlers to all view buttons
        const viewButtons = document.querySelectorAll('.view-button');
        viewButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.stopPropagation();
                const galleryItem = this.closest('.gallery-item');
                const imageSrc = galleryItem.getAttribute('data-image');
                // Get description from data attribute or caption
                let description = galleryItem.getAttribute('data-description') || '';
                const caption = galleryItem.querySelector('.gallery-caption');
                if (caption) {
                    description = caption.innerHTML;
                }
                openFullscreen(imageSrc, description);
            });
        });
    });

    function openFullscreen(imageSrc, description) {
        const modal = document.getElementById('fullscreenModal');
        const img = document.getElementById('fullscreenImage');
        const desc = document.getElementById('fullscreenDescription');
        
        img.src = imageSrc;
        desc.innerHTML = description;
        modal.classList.add('active');
        
        // Prevent body scroll and fix position
        document.body.classList.add('modal-open');
        document.documentElement.style.overflow = 'hidden';
    }

    function closeFullscreen() {
        const modal = document.getElementById('fullscreenModal');
        modal.classList.remove('active');
        
        // Restore body scroll
        document.body.classList.remove('modal-open');
        document.documentElement.style.overflow = '';
    }

    // Close modal with Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeFullscreen();
        }
    });
</script>
@endsection