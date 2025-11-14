@extends('layouts.app')

@section('content')

<style>
.story-image {
    max-height: 300px;
    overflow: hidden;
    border-radius: 8px;
    margin-bottom: 1.5rem;
}

.story-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
</style>
 
<div class="container-fluid px-1 py-2">
    <div class="row">
        <!-- Main Content Area -->
        <div class="col-lg-9">
            <!-- Success Story Header -->
        
  

            <!-- Success Story Content -->
            <article class="story-content">
                <div class="story-card">
                    <div class="story-image">
                        @if($successStory->image)
                            <img src="{{ asset('images/storage/' . $successStory->image) }}" 
                                 alt="Success Story" class="img-fluid">
                        @else
                            <img src="{{ asset('images/static_files/noimagenews.png') }}" 
                                 alt="Success Story" class="img-fluid">
                        @endif
                    </div>
                    
                    <div class="story-body">
                        <div class="story-author">
                            <h4>{{ $successStory->first_name }} {{ $successStory->last_name }}</h4>
                            <p class="author-info">
                                <i class="fas fa-envelope"></i> {{ $successStory->email }}
                                @if($successStory->phone)
                                    <br><i class="fas fa-phone"></i> {{ $successStory->phone }}
                                @endif
                            </p>
                        </div>
                        
                        <div class="story-message">
                            <h5>Hadithi:</h5>
                            <div class="story-text">
                                {!! nl2br(e($successStory->message)) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </article>

            <!-- Related Stories -->
            @if(isset($relatedStories) && $relatedStories->count() > 0)
            <section class="related-stories mt-5">
                <h3 class="section-subtitle">
                    <i class="fas fa-link"></i>
                    Hadithi Zingine za Mafanikio
                </h3>
                <div class="row">
                    @foreach($relatedStories as $story)
                    <div class="col-md-4 mb-3">
                        <div class="related-story-card">
                            @if($story->image)
                                <img src="{{ asset('images/storage/' . $story->image) }}" 
                                     alt="Related Story" class="related-story-image">
                            @endif
                            <div class="related-story-content">
                                <h6>{{ Str::limit($story->message, 80) }}</h6>
                                <p class="related-story-author">
                                    {{ $story->first_name }} {{ $story->last_name }}
                                </p>
                                <a href="{{ route('story.showspecific', $story->id) }}" class="read-more">
                                    Soma Zaidi <i class="fas fa-chevron-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </section>
            @endif
        </div>

        <!-- Government Sidebar -->
        <div class="col-lg-3">
            @include('newspage.partial.sidebar')
        </div>
    </div>
</div>

@include('newspage.partial.script')
@endsection
