@extends('aboutus.aboutus')

<style>
    .folder-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 20px;
    }

    .folder-item {
        background: #fff;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        cursor: pointer;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        aspect-ratio: 1 / 1; /* Makes it a square */
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .folder-item:hover {
        transform: translateY(-6px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
    }

    .folder-icon {
        font-size: 60px;
        color: #f0ad4e; /* Folder yellow */
    }

    .folder-name {
        margin-top: 10px;
        font-weight: bold;
        font-size: 1rem;
        color: #333;
    }

    .folder-info {
        font-size: 0.85rem;
        color: #777;
    }
</style>

@section('aboutus-content')
<div class="container mt-4">

    @if($events->count() > 0)
        <div class="folder-grid">
            @foreach ($events as $event)
                <a href="{{ route('folder.viewimage', $event->id) }}" class="text-decoration-none">
                    <div class="folder-item">
                        <!-- Folder Icon -->
                        <div class="folder-icon">&#128193;</div>
                        
                        <!-- Folder Name -->
                        <div class="folder-name">
                            {{ $event->name_of_event }}
                        </div>

                        <!-- Folder Info -->
                        <div class="folder-info">
                            {{ $event->images_count }} {{ __('gallery.images') }}
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @else
        <p class="text-muted">{{ __('gallery.no_events_found') }}</p>
    @endif

</div>
@endsection
