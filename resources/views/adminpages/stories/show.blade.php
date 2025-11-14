@extends('adminpages.layouts.app')

@section('content')
<div class="container-fluid bg-white p-4 mt-2">
    {{-- Display Success/Error Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="row mb-4">
        <div class="col-12">
            <div class="bg-white rounded-3 p-4 shadow-sm border border-light">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h2 mb-2 fw-bold text-dark">
                            <i class="text-secondary"></i>
                            Story Details
                        </h1>
                        <p class="mb-0 text-muted fs-6">View and manage success story</p>
                    </div>
                    <a href="{{ route('admin.user-stories.index') }}" class="btn btn-dark btn-lg shadow-sm border-0">
                        <i class="fas fa-arrow-left me-2"></i>
                        <span class="fw-semibold">Back</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Success Story</h4>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h5>Message:</h5>
                        <div class="border p-3 bg-light rounded">
                            {!! nl2br(e($story->message)) !!}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <h5>Contact Details:</h5>
                            <ul class="list-group">
                                <li class="list-group-item">
                                    <strong>Name:</strong> {{ trim(($story->first_name ?? '') . ' ' . ($story->last_name ?? '')) ?: 'Anonymous' }}
                                </li>
                                <li class="list-group-item">
                                    <strong>Email:</strong> {{ $story->email ?: 'Not provided' }}
                                </li>
                                <li class="list-group-item">
                                    <strong>Phone:</strong> {{ $story->phone ?: 'Not provided' }}
                                </li>
                                <li class="list-group-item">
                                    <strong>Gender:</strong> {{ $story->gender ?: 'Not specified' }}
                                </li>
                                @if($story->date_of_incident)
                                <li class="list-group-item">
                                    <strong>Date of Incident:</strong> {{ $story->date_of_incident }}
                                </li>
                                @endif
                                @if($story->location)
                                <li class="list-group-item">
                                    <strong>Location:</strong> {{ $story->location }}
                                </li>
                                @endif
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h5>Status Information:</h5>
                            <ul class="list-group">
                                <li class="list-group-item">
                                    <strong>Status:</strong> 
                                    <span class="badge bg-{{ $story->status === 'seen' ? 'success' : 'warning' }}">
                                        {{ $story->status === 'seen' ? 'Seen' : 'Not Seen' }}
                                    </span>
                                </li>
                                <li class="list-group-item">
                                    <strong>Published:</strong> 
                                    <span class="badge bg-{{ $story->published ? 'success' : 'secondary' }}">
                                        {{ $story->published ? 'Yes' : 'No' }}
                                    </span>
                                </li>
                                <li class="list-group-item">
                                    <strong>Submitted:</strong> {{ $story->created_at->format('M j, Y H:i') }}
                                </li>
                                @if($story->seen_by)
                                <li class="list-group-item">
                                    <strong>Last Action By:</strong> Admin
                                    <br>
                                    <small class="text-muted">
                                        {{ $story->status === 'seen' ? 'Seen' : 'Not Seen' }} on {{ $story->updated_at->format('M j, Y H:i') }}
                                    </small>
                                </li>
                                @endif
                            </ul>
                        </div>
                    </div>

                    @if($story->image)
                    <div class="mt-4">
                        <h5>Uploaded Image:</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <img src="{{ asset('images/storage/' . $story->image) }}" class="img-fluid img-thumbnail" alt="Uploaded Image" style="max-height: 300px; object-fit: cover;">
                                <div class="mt-2">
                                    <small class="text-muted">{{ basename($story->image) }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="mt-4">
                        <h5>Images:</h5>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            No images were uploaded with this story.
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">Admin Actions</h5>
                </div>
                <div class="card-body">
                    <!-- Approve Button -->
                    @if($story->status === 'not seen')
                        @php
                            $authorName = trim(($story->first_name ?? '') . ' ' . ($story->last_name ?? ''));
                            $hasAuthorName = !empty($authorName);
                        @endphp
                        
                        @if(!$hasAuthorName)
                            <div class="alert alert-warning mb-3">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Warning:</strong> This story cannot be approved because the author name is missing. Please contact the user to provide their name.
                            </div>
                        @endif
                        
                        <form action="{{ route('admin.user-stories.approve', $story->id) }}" method="POST" class="mb-3">
                            @csrf
                            <button type="submit" 
                                    class="btn {{ $hasAuthorName ? 'btn-success' : 'btn-secondary' }} w-100" 
                                    {{ !$hasAuthorName ? 'disabled' : '' }}
                                    onclick="return confirm('Are you sure you want to approve this story?')">
                                <i class="fas fa-check me-2"></i> 
                                {{ $hasAuthorName ? 'Approve Story' : 'Cannot Approve (No Author Name)' }}
                            </button>
                        </form>
                    @endif

                    <!-- Reject Button -->
                    @if($story->status === 'not seen')
                    <form action="{{ route('admin.user-stories.reject', $story->id) }}" method="POST" class="mb-3">
                        @csrf
                        <input type="hidden" name="admin_notes" value="Rejected by admin">
                        <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Are you sure you want to reject this story?')">
                            <i class="fas fa-times me-2"></i> Reject Story
                        </button>
                    </form>
                    @endif

                    <!-- Post Button -->
                    @if($story->status === 'seen' && !$story->published)
                        @php
                            $authorName = trim(($story->first_name ?? '') . ' ' . ($story->last_name ?? ''));
                            $hasAuthorName = !empty($authorName);
                        @endphp
                        
                        @if(!$hasAuthorName)
                            <div class="alert alert-warning mb-3">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Warning:</strong> This story cannot be posted because the author name is missing. Please contact the user to provide their name.
                            </div>
                        @endif
                        
                        <form action="{{ route('admin.user-stories.post', $story->id) }}" method="POST" class="mb-3">
                            @csrf
                            <button type="submit" 
                                    class="btn {{ $hasAuthorName ? 'btn-success' : 'btn-secondary' }} w-100" 
                                    {{ !$hasAuthorName ? 'disabled' : '' }}
                                    onclick="return confirm('Are you sure you want to post this story to the website?')">
                                <i class="fas fa-globe me-2"></i> 
                                {{ $hasAuthorName ? 'Post to Website' : 'Cannot Post (No Author Name)' }}
                            </button>
                        </form>
                    @endif

                    <!-- Unpost Button -->
                    @if($story->status === 'seen' && $story->published)
                    <form action="{{ route('admin.user-stories.unpost', $story->id) }}" method="POST" class="mb-3">
                        @csrf
                        <button type="submit" class="btn btn-warning w-100" onclick="return confirm('Are you sure you want to remove this story from the website?')">
                            <i class="fas fa-eye-slash me-2"></i> Remove from Website
                        </button>
                    </form>
                    @endif
                    
                    <hr>
                    
                    <!-- Delete Button -->
                    <form action="{{ route('admin.user-stories.destroy', $story->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Are you sure you want to delete this story?')">
                            <i class="fas fa-trash me-2"></i> Delete Story
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection