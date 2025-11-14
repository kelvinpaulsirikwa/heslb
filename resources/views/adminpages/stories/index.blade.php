@extends('adminpages.layouts.app')

@section('title', 'User Stories Management')

@section('content')
<div class="min-vh-100" style="background-color: #f8f9fa;">
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-12">
                <!-- Header Section -->
                <div class="bg-white shadow-sm border rounded-3 p-4 mb-4">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                        <div>
                            <h2 class="mb-1 text-dark fw-bold">
                                <i class="fas fa-book-open text-dark me-2"></i>
                                User Stories Management
                            </h2>
                            <p class="text-muted mb-0 small">Manage and review citizen success stories</p>
                        </div>
                    </div>
                </div>

                <!-- Filters Section -->
                @include('adminpages.stories.partials.filters')

                <!-- Stories Section -->
                <div class="bg-white shadow-sm border rounded-3 mb-4">
                    <div class="p-4">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0 fw-semibold text-dark">ID</th>
                                        <th class="border-0 fw-semibold text-dark">Title</th>
                                        <th class="border-0 fw-semibold text-dark">Author</th>
                                        <th class="border-0 fw-semibold text-dark">Submitted</th>
                                        <th class="border-0 fw-semibold text-dark text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($stories as $story)
                                        @include('adminpages.stories.partials.storyrow', ['story' => $story])
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-5">
                                                <div class="text-muted">
                                                    <i class="fas fa-book-open fa-3x mb-3"></i>
                                                    <h5>No Stories Found</h5>
                                                    <p>No user stories match your current filters.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $stories->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endsection