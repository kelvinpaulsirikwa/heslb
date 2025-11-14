@extends('adminpages.layouts.app')

@section('title', 'Partners Management')

@section('content')
<div class="container-fluid  bg-white px-4 py-5">
    <div class="row">
        <div class="col-12">
  <div class="bg-white rounded-3 p-4 shadow-sm border border-light">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h2 mb-2 fw-bold text-dark">
                            <i class="text-secondary"></i>
                            Partner Management
                        </h1>
                        <p class="mb-0 text-muted fs-6">Manage your strategic partner</p>
                    </div>
                    <div class="d-flex gap-2">
                     
                        <a href="{{ route('admin.partners.create') }}" class="btn btn-dark btn-lg shadow-sm">
                            <i class="fas fa-add me-2"></i>
                            <span class="fw-semibold">Add New Partner</span>
                        </a>
                    </div>
                </div>
          
<br>
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Strategic Partners</h3>
                  
                </div>
                
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Acronym</th>
                                    <th>Link</th>
                                    <th>Posted By</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($partners as $partner)
                                    <tr>
                                        <td>{{ $partner->id }}</td>
                                        <td>
                                            @if($partner->image_path)
                                                <img src="{{ asset('images/storage/partner_image/' . $partner->image_path) }}" 
                                                     alt="{{ $partner->name }}" 
                                                     class="img-thumbnail" 
                                                     style="max-width: 60px; max-height: 60px;">
                                            @else
                                                <span class="badge bg-secondary">No Image</span>
                                            @endif
                                        </td>
                                        <td>{{ $partner->name }}</td>
                                        <td>{{ $partner->acronym_name ?? 'N/A' }}</td>
                                        <td>
                                            @if($partner->link)
                                                <a href="{{ $partner->link }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-external-link-alt"></i>
                                                </a>
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>{{ $partner->user->username ?? 'Unknown' }}</td>
                                        <td>{{ $partner->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.partners.show', $partner) }}" 
                                                   class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.partners.edit', $partner) }}" 
                                                   class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" 
                                                        class="btn btn-sm btn-danger delete-partner-btn"
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#deletePartnerModal"
                                                        data-partner-id="{{ $partner->id }}"
                                                        data-partner-name="{{ $partner->name }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No partners found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $partners->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Partner Modal -->
<div class="modal fade" id="deletePartnerModal" tabindex="-1" aria-labelledby="deletePartnerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deletePartnerModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Confirm Partner Deletion
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <div class="mb-3">
                        <i class="fas fa-trash-alt text-danger" style="font-size: 3rem;"></i>
                    </div>
                    <h6 class="text-muted mb-2">Are you sure you want to delete this partner?</h6>
                    <p class="fw-bold text-dark mb-0" id="partnerNameToDelete"></p>
                </div>
                
                <div class="alert alert-warning" role="alert">
                    <div class="d-flex">
                        <i class="fas fa-exclamation-triangle me-2 mt-1"></i>
                        <div>
                            <strong>Warning:</strong> This action cannot be undone. The partner will be permanently removed from the system.
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>
                    Cancel
                </button>
                <form id="deletePartnerForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i>
                        Delete Partner
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle delete partner button clicks
    const deleteButtons = document.querySelectorAll('.delete-partner-btn');
    const deleteModal = document.getElementById('deletePartnerModal');
    const partnerNameElement = document.getElementById('partnerNameToDelete');
    const deleteForm = document.getElementById('deletePartnerForm');
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const partnerId = this.getAttribute('data-partner-id');
            const partnerName = this.getAttribute('data-partner-name');
            
            // Update modal content
            partnerNameElement.textContent = partnerName;
            
            // Update form action
            deleteForm.action = `/admin/partners/${partnerId}`;
        });
    });
    
    // Add loading state to delete button
    deleteForm.addEventListener('submit', function() {
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Deleting...';
        
        // Re-enable after 5 seconds if something goes wrong
        setTimeout(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }, 5000);
    });
    
    // Auto-dismiss success alerts after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert-success');
        alerts.forEach(alert => {
            if (alert.classList.contains('show')) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        });
    }, 5000);
});
</script>
@endsection