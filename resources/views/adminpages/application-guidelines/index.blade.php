@extends('adminpages.layouts.app')

@section('title', 'Application Guidelines Management')

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
                                <i class="fas fa-file-contract text-dark me-2"></i>
                                Application Guidelines Management
                            </h2>
                            <p class="text-muted mb-0 small">Manage and organize government application guidelines</p>
                        </div>
                        <a href="{{ route('admin.application-guidelines.create') }}" class="btn btn-dark px-4 py-2">
                            <i class="fas fa-plus me-2"></i>Add New Guideline
                        </a>
                    </div>
                </div>

                <!-- Success Alert -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Error Alert -->
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Guidelines Section -->
                @if($guidelines->count() > 0)
                    <div class="bg-white shadow-sm border rounded-3 mb-4">
                        <div class="p-4">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="border-0 fw-semibold text-dark">Title</th>
                                            <th class="border-0 fw-semibold text-dark">Academic Year</th>
                                            <th class="border-0 fw-semibold text-dark">File Information</th>
                                            <th class="border-0 fw-semibold text-dark">Status</th>
                                            <th class="border-0 fw-semibold text-dark">Created By</th>
                                            <th class="border-0 fw-semibold text-dark">Date Created</th>
                                            <th class="border-0 fw-semibold text-dark text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($guidelines as $guideline)
                                            <tr class="border-bottom">
                                                <td class="py-3">
                                                    <div class="d-flex flex-column">
                                                        <strong class="text-dark mb-1">{{ $guideline->title }}</strong>
                                                        @if($guideline->is_current)
                                                            <span class="badge bg-success text-white" style="width: fit-content;">Current</span>
                                                            @if($guideline->publication_id)
                                                                <span class="badge bg-info text-white mt-1" style="width: fit-content; font-size: 0.7em;">
                                                                    <i class="fas fa-link"></i> Deletable
                                                                </span>
                                                            @endif
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="py-3">
                                                    <span class="text-dark fw-medium">{{ $guideline->academic_year }}</span>
                                                </td>
                                                <td class="py-3">
                                                    @if($guideline->publication)
                                                        <div class="d-flex flex-column">
                                                            <small class="text-dark fw-medium mb-1">
                                                                <i class="fas fa-book me-1"></i>
                                                                {{ $guideline->publication->title }}
                                                            </small>
                                                            <small class="text-muted">
                                                                <i class="fas fa-weight me-1"></i>
                                                                {{ $guideline->publication->formatted_file_size }}
                                                            </small>
                                                            <small class="text-info">
                                                                <i class="fas fa-link me-1"></i>
                                                                From Publications
                                                            </small>
                                                        </div>
                                                    @elseif($guideline->file_original_name)
                                                        <div class="d-flex flex-column">
                                                            <small class="text-dark fw-medium mb-1">
                                                                <i class="fas fa-file-alt me-1"></i>
                                                                {{ $guideline->file_original_name }}
                                                            </small>
                                                            <small class="text-muted">
                                                                <i class="fas fa-weight me-1"></i>
                                                                {{ $guideline->formatted_file_size }}
                                                            </small>
                                                        </div>
                                                    @else
                                                        <span class="text-muted">
                                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                                            No file attached
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="py-3">
                                                    @if($guideline->is_active)
                                                        <span class="badge bg-success text-white">Active</span>
                                                    @else
                                                        <span class="badge bg-secondary text-white">Inactive</span>
                                                    @endif
                                                </td>
                                                <td class="py-3">
                                                    <span class="text-dark">{{ $guideline->creator_name }}</span>
                                                </td>
                                                <td class="py-3">
                                                    <span class="text-dark">{{ $guideline->created_at->format('M d, Y') }}</span>
                                                </td>
                                                <td class="py-3 text-center">
                                                    <div class="btn-group" role="group">
                                                      
                                                        @if(!$guideline->is_current)
                                                            <form action="{{ route('admin.application-guidelines.set-current', $guideline->id) }}" 
                                                                  method="POST" 
                                                                  class="d-inline set-current-form"
                                                                  id="set-current-form-{{ $guideline->id }}">
                                                                @csrf
                                                                <button type="button" 
                                                                        class="btn btn-sm btn-set-current" 
                                                                        style="border: 2px solid #6f42c1; color: #6f42c1; background: white;" 
                                                                        title="Set as Current"
                                                                        data-bs-toggle="modal" 
                                                                        data-bs-target="#setCurrentModal"
                                                                        data-guideline-id="{{ $guideline->id }}"
                                                                        data-guideline-title="{{ $guideline->title }}">
                                                                    <i class="fas fa-star"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                        @if($guideline->can_delete)
                                                            <form action="{{ route('admin.application-guidelines.destroy', $guideline->id) }}" 
                                                                  method="POST" 
                                                                  class="d-inline"
                                                                  onsubmit="return confirm('Are you sure you want to delete this guideline: {{ addslashes($guideline->title) }}? This action cannot be undone.')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" 
                                                                        class="btn btn-sm btn-delete" 
                                                                        style="border: 2px solid #dc3545; color: #dc3545; background: white;" 
                                                                        title="{{ $guideline->is_current ? 'Delete current guideline (file will be preserved if used in publications)' : 'Delete guideline' }}">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        @else
                                                            <button class="btn btn-sm" 
                                                                    style="border: 2px solid #6c757d; color: #6c757d; background: white;" 
                                                                    title="Cannot delete the last active guideline" 
                                                                    disabled>
                                                                <i class="fas fa-lock"></i>
                                                            </button>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $guidelines->links() }}
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="bg-white shadow-sm border rounded-3">
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-file-contract fa-4x text-muted"></i>
                            </div>
                            <h4 class="text-dark mb-3">No Application Guidelines Found</h4>
                            <p class="text-muted mb-4">Start by creating your first application guideline to help citizens understand the application process.</p>
                            <a href="{{ route('admin.application-guidelines.create') }}" class="btn btn-dark px-4 py-2">
                                <i class="fas fa-plus me-2"></i>Create First Guideline
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Set as Current Modal -->
<div class="modal fade" id="setCurrentModal" tabindex="-1" aria-labelledby="setCurrentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title text-dark fw-bold" id="setCurrentModalLabel">
                    <i class="fas fa-star text-warning me-2"></i>Set as Current Guideline
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-dark mb-2">Are you sure you want to set this guideline as current?</p>
                <div class="bg-light p-3 rounded mb-3">
                    <strong class="text-dark d-block mb-1">Guideline:</strong>
                    <span class="text-muted" id="setCurrentGuidelineTitle"></span>
                </div>
                <p class="text-muted small mb-0">
                    <i class="fas fa-info-circle me-1"></i>
                    This will make this guideline the active one for applications.
                </p>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cancel
                </button>
                <button type="button" class="btn btn-primary" id="confirmSetCurrent" style="background-color: #6f42c1; border-color: #6f42c1;">
                    <i class="fas fa-check me-1"></i>Yes, Set as Current
                </button>
            </div>
        </div>
    </div>
</div>


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentSetGuidelineId = null;
    
    // Handle Set as Current Modal
    const setCurrentModal = document.getElementById('setCurrentModal');
    if (setCurrentModal) {
        setCurrentModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const guidelineId = button.getAttribute('data-guideline-id');
            const guidelineTitle = button.getAttribute('data-guideline-title');
            
            currentSetGuidelineId = guidelineId;
            document.getElementById('setCurrentGuidelineTitle').textContent = guidelineTitle;
        });
    }
    
    // Confirm Set as Current
    const confirmSetCurrentBtn = document.getElementById('confirmSetCurrent');
    if (confirmSetCurrentBtn) {
        confirmSetCurrentBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            if (currentSetGuidelineId) {
                const form = document.getElementById('set-current-form-' + currentSetGuidelineId);
                
                if (form) {
                    // Close modal using Bootstrap's method
                    const modalElement = document.getElementById('setCurrentModal');
                    const modal = bootstrap.Modal.getInstance(modalElement);
                    if (modal) {
                        modal.hide();
                    }
                    
                    // Submit form after a brief delay
                    setTimeout(function() {
                        form.submit();
                    }, 200);
                }
            }
        });
    }
});
</script>
@endpush
@endsection
