@extends('publications.publications')

@section('publication-content')
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
                </div>
                <div class="heslb-content-body">
                    <div class="heslb-content-container">
                        <div class="no-publications text-center py-5">
                            <i class="fas fa-folder-open fa-3x mb-3 text-muted"></i>
                            <h5>{{ __('publications.noPublicationsTitle') }}</h5>
                            <p class="text-muted">{{ __('publications.noPublicationsText') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.no-publications {
    padding: 60px 20px;
}

.no-publications i {
    color: #6c757d;
    margin-bottom: 20px;
}

.no-publications h5 {
    color: #495057;
    font-weight: 600;
    margin-bottom: 15px;
}

.no-publications p {
    color: #6c757d;
    font-size: 1.1rem;
    max-width: 500px;
    margin: 0 auto;
}
</style>
@endsection
