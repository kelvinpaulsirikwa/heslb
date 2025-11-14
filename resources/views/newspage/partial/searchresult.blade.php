@extends('layouts.app')

@section('content')
@include('newspage.partial.headers')

<div class="container-fluid px-4 py-4">
    <div class="row">
        <!-- Main Content Area -->
        <div class="col-lg-9">
            <!-- Search Results Header -->
            <section class="search-results-header mb-4">
                <div class="search-header-content">
                    <h2 class="section-title">
                        <i class="fas fa-search"></i>
                        MATOKEO YA UTAFUTAJI
                    </h2>
                    @if(request('search'))
                        <div class="search-query-display mt-2">
                            <p class="search-query-text">
                                Umetafuta: <strong>"{{ request('search') }}"</strong>
                            </p>
                            <p class="search-results-count">
                                @if(isset($newsArticles))
                                    @if($newsArticles->total() > 0)
                                        Matokeo {{ $newsArticles->total() }} yamepatikana
                                    @else
                                        Hakuna matokeo yaliyopatikana
                                    @endif
                                @endif
                            </p>
                        </div>
                    @endif
                </div>
            </section>

            <!-- Search Results List -->
            @if(isset($newsArticles) && $newsArticles->count() > 0)
                <div class="row">
                    @foreach($newsArticles as $item)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100 shadow-sm">
                                <img src="{{ ($item['image'] && file_exists(public_path('images/storage/' . $item['image']))) ? asset('images/storage/' . $item['image']) : asset('images/static_files/noimagenews.png') }}" 
                                     class="card-img-top" 
                                     alt="{{ $item['title'] }}"
                                     style="height: 200px; object-fit: cover;">
                                <div class="card-body">
                                    <div class="mb-2">
                                        <span class="badge badge-{{ $item['type'] === 'news' ? 'primary' : 'success' }}">
                                            {{ strtoupper($item['category']) }}
                                        </span>
                                    </div>
                                    <h5 class="card-title">
                                        <a href="{{ route($item['route'], $item['id']) }}">
                                            {{ Str::limit($item['title'], 60) }}
                                        </a>
                                    </h5>
                                    <p class="card-text">
                                        {{ Str::limit(strip_tags($item['content']), 100) }}
                                    </p>
                                </div>
                                <div class="card-footer text-muted">
                                    {{ \Carbon\Carbon::parse($item['created_at'])->format('d M Y') }} 
                                    • {{ $item['type'] === 'news' ? 'Habari' : 'Hadithi ya Mafanikio' }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $newsArticles->appends(['search' => request('search')])->links() }}
                </div>
            @else
                <div class="alert alert-warning mt-3">
                    <i class="fas fa-info-circle"></i> Hakuna matokeo yaliyopatikana kwa utafutaji huu.
                </div>
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
