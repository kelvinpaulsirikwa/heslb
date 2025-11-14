@extends('layouts.app')

@section('content')
<div class="heslb-hero">
    <div class="container">
        <h1 class="heslb-hero-title">{{ __('publications.pageTitle') }}</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb text-white">
                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}" class="text-white">{{ __('publications.home') }}</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page" 
                    title="{{ $category->name ?? ($pageTitle ?? __('publications.pageTitle')) }}">
                    {{ $pageTitle ?? __('publications.pageTitle') }}
                </li>
            </ol>
        </nav>
    </div>
</div>

<div class="container my-5">
    <div class="row">
        <!-- Sidebar Navigation -->
        <div class="col-lg-3 mb-4">
            <ul class="heslb-sidebar list-unstyled">
                @forelse($sidebarCategories ?? $categories ?? [] as $sidebarCategory)
                    <li>
                        <a href="{{ route('publications.category', $sidebarCategory->slug) }}"
                           class="{{ (isset($category) && $category->id == $sidebarCategory->id) ? 'active' : '' }}"
                           title="{{ $sidebarCategory->name }}">
                            {{ $sidebarCategory->name }}
                        </a>
                    </li>
                @empty
                    <li><div class="alert alert-info">{{ __('publications.noCategories') }}</div></li>
                @endforelse
            </ul>
        </div>

        <!-- Right Hand Content -->
        <div class="col-lg-9">
            <div class="heslb-content-box">
                <div class="heslb-content-header">
                    <span title="{{ $category->name ?? ($pageTitle ?? __('publications.pageTitle')) }}">
                        {{ $pageTitle ?? __('publications.pageTitle') }}
                    </span>
                    @if(isset($category) && $category->publications_count > 0)
                        <small class="ms-2 opacity-75">
                            ({{ $category->publications_count }} 
                             {{ trans_choice('publications.documents', $category->publications_count) }})
                        </small>
                    @endif
                </div>
                <div class="heslb-content-body">
                    <div class="heslb-content-container">
                        @if(isset($items) && count($items) > 0)
                            @foreach($items as $item)
                                <div class="publication-item">
                                    <div class="publication-title">{{ $item['title'] }}</div>
                                    
                                    <div class="publication-meta">
                                        @if($item['formatted_date'])
                                            <span><i class="fas fa-calendar"></i> {{ $item['formatted_date'] }}</span>
                                        @endif
                                        @if($item['version'])
                                            <span class="ms-3">
                                                <i class="fas fa-tag"></i> {{ __('publications.version') }}: {{ $item['version'] }}
                                            </span>
                                        @endif
                                        @if($item['file_size'])
                                            <span class="ms-3"><i class="fas fa-file"></i> {{ $item['file_size'] }}</span>
                                        @endif
                                        @if($item['download_count'] > 0)
                                            <span class="ms-3">
                                                <i class="fas fa-download"></i> {{ $item['download_count'] }} {{ __('publications.downloads') }}
                                            </span>
                                        @endif
                                    </div>
                                    
                                    @if($item['description'])
                                        <div class="publication-description mb-3">
                                            {{ Str::limit($item['description'], 150) }}
                                        </div>
                                    @endif
                                    
                                    <div class="publication-actions">
                                        <a href="{{ $item['link'] }}" class="btn-download" download>
                                            <i class="fas fa-download"></i> {{ __('publications.download') }}
                                        </a>
                                        <a href="{{ $item['link'] }}" class="btn-read" target="_blank">
                                            <i class="fas fa-eye"></i> {{ __('publications.read') }}
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="no-publications">
                                <i class="fas fa-folder-open fa-3x mb-3 text-muted"></i>
                                <h5>{{ __('publications.noPublicationsTitle') }}</h5>
                                <p>{{ __('publications.noPublicationsText') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
