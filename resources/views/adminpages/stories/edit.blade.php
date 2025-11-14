@extends('adminpages.layouts.app')

@section('content')
<div class="container-fluid bg-white p-4 mt-2">
    <div class="row mb-4">
        <div class="col-12">
            <div class="bg-white rounded-3 p-4 shadow-sm border border-light">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h2 mb-2 fw-bold text-dark">
                            <i class="fas fa-edit text-secondary"></i>
                            Edit Story
                        </h1>
                        <p class="mb-0 text-muted fs-6">Modify story details and publication status</p>
                    </div>
                    <a href="{{ route('admin.user-stories.index') }}" class="btn btn-dark btn-lg shadow-sm border-0">
                        <i class="fas fa-arrow-left me-2"></i>
                        <span class="fw-semibold">Back to Stories</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Edit Story: {{ $story->title }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.user-stories.update', $story->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <!-- Story Information (Read-only) -->
                        <div class="mb-4">
                            <h5 class="text-muted">Story Information (Read-only)</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Title:</label>
                                        <input type="text" class="form-control" value="{{ $story->title }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Author:</label>
                                        <input type="text" class="form-control" value="{{ $story->author }}" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Email:</label>
                                        <input type="email" class="form-control" value="{{ $story->email }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">University:</label>
                                        <input type="text" class="form-control" value="{{ $story->university }}" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Content:</label>
                                <textarea class="form-control" rows="6" readonly>{{ $story->content }}</textarea>
                            </div>
                        </div>

                        <!-- Editable Fields -->
                        <div class="mb-4">
                            <h5 class="text-primary">Editable Fields</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="category" class="form-label fw-bold">Category <span class="text-danger">*</span></label>
                                        <select name="category" id="category" class="form-select @error('category') is-invalid @enderror" required>
                                            <option value="">Select Category</option>
                                            @foreach($categories as $key => $name)
                                                <option value="{{ $key }}" {{ $story->category == $key ? 'selected' : '' }}>
                                                    {{ $name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('category')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="publication_status" class="form-label fw-bold">Publication Status <span class="text-danger">*</span></label>
                                        <select name="publication_status" id="publication_status" class="form-select @error('publication_status') is-invalid @enderror" required>
                                            <option value="">Select Status</option>
                                            @foreach($statuses as $key => $name)
                                                <option value="{{ $key }}" {{ $story->publication_status == $key ? 'selected' : '' }}>
                                                    {{ $name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('publication_status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="admin_notes" class="form-label fw-bold">Admin Notes</label>
                                <textarea name="admin_notes" id="admin_notes" class="form-control @error('admin_notes') is-invalid @enderror" rows="3" placeholder="Add any notes about this story...">{{ old('admin_notes') }}</textarea>
                                @error('admin_notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Optional notes for internal reference</div>
                            </div>
                        </div>

                        <!-- Story Media -->
                        @if(!empty($story->images) || !empty($story->videos))
                        <div class="mb-4">
                            <h5 class="text-muted">Story Media</h5>
                            
                            @if(!empty($story->images))
                            <div class="mb-3">
                                <label class="form-label fw-bold">Images:</label>
                                <div class="row">
                                    @foreach(json_decode($story->images) as $image)
                                    <div class="col-md-3 mb-2">
                                        <img src="{{ asset('images/storage/' . $image) }}" 
                                             alt="Story Image" 
                                             class="img-fluid rounded border"
                                             style="max-height: 150px; object-fit: cover;">
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            @if(!empty($story->videos))
                            <div class="mb-3">
                                <label class="form-label fw-bold">Videos:</label>
                                <div class="row">
                                    @foreach(json_decode($story->videos) as $video)
                                    <div class="col-md-6 mb-2">
                                        <video controls class="w-100 rounded border" style="max-height: 200px;">
                                            <source src="{{ asset('images/storage/' . $video) }}" type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.user-stories.show', $story->id) }}" class="btn btn-secondary">
                                <i class="fas fa-eye me-2"></i>View Story
                            </a>
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Update Story
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <!-- Story Statistics -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Story Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h4 class="text-primary mb-0">{{ $story->views ?? 0 }}</h4>
                                <small class="text-muted">Views</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h4 class="text-success mb-0">{{ $story->reading_time ?? '5' }}</h4>
                            <small class="text-muted">Min Read</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Story Metadata -->
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Story Metadata</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <strong>Submitted:</strong><br>
                            <small class="text-muted">{{ $story->created_at->format('M j, Y H:i') }}</small>
                        </li>
                        @if($story->published_at)
                        <li class="mb-2">
                            <strong>Published:</strong><br>
                            <small class="text-muted">{{ $story->published_at->format('M j, Y H:i') }}</small>
                        </li>
                        @endif
                        @if($story->updated_at != $story->created_at)
                        <li class="mb-2">
                            <strong>Last Updated:</strong><br>
                            <small class="text-muted">{{ $story->updated_at->format('M j, Y H:i') }}</small>
                        </li>
                        @endif
                        @if($story->approved_by && $story->approver)
                        <li class="mb-2">
                            <strong>Approved By:</strong><br>
                            <small class="text-muted">{{ $story->approver->displayName }}</small>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.form-control[readonly] {
    background-color: #f8f9fa;
    opacity: 1;
}

.card-header {
    border-bottom: 2px solid #dee2e6;
}
</style>
@endsection
