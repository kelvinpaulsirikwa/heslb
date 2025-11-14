@extends('adminpages.layouts.app')

@section('title', 'Add Scholarship')

@section('content')
<div style="background:#ffffff; min-height:100vh; padding:40px 0;">
    <div class="container">
    <div class="card">
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form method="POST" action="{{ route('admin.scholarships.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Title</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Donor Organization</label>
                    <input type="text" name="donor_organization" class="form-control" value="{{ old('donor_organization') }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Application Deadline</label>
                    <input type="date" name="application_deadline" class="form-control" value="{{ old('application_deadline') }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Eligible Applicants</label>
                    <textarea name="eligible_applicants" class="form-control" rows="3">{{ old('eligible_applicants') }}</textarea>
                    <small class="text-muted">Who can apply for this scholarship?</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Fields of Study</label>
                    <textarea name="fields_of_study" class="form-control" rows="3">{{ old('fields_of_study') }}</textarea>
                    <small class="text-muted">What fields/areas of study are eligible?</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Level of Study</label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="level_of_study[]" value="certificate" id="level_certificate" {{ is_array(old('level_of_study')) && in_array('certificate', old('level_of_study')) ? 'checked' : '' }}>
                        <label class="form-check-label" for="level_certificate">Certificate</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="level_of_study[]" value="diploma" id="level_diploma" {{ is_array(old('level_of_study')) && in_array('diploma', old('level_of_study')) ? 'checked' : '' }}>
                        <label class="form-check-label" for="level_diploma">Diploma</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="level_of_study[]" value="undergraduate" id="level_undergraduate" {{ is_array(old('level_of_study')) && in_array('undergraduate', old('level_of_study')) ? 'checked' : '' }}>
                        <label class="form-check-label" for="level_undergraduate">Undergraduate</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="level_of_study[]" value="masters" id="level_masters" {{ is_array(old('level_of_study')) && in_array('masters', old('level_of_study')) ? 'checked' : '' }}>
                        <label class="form-check-label" for="level_masters">Masters</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="level_of_study[]" value="phd" id="level_phd" {{ is_array(old('level_of_study')) && in_array('phd', old('level_of_study')) ? 'checked' : '' }}>
                        <label class="form-check-label" for="level_phd">PhD</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="level_of_study[]" value="postdoctoral" id="level_postdoctoral" {{ is_array(old('level_of_study')) && in_array('postdoctoral', old('level_of_study')) ? 'checked' : '' }}>
                        <label class="form-check-label" for="level_postdoctoral">Postdoctoral</label>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Benefits Summary</label>
                    <textarea name="benefits_summary" class="form-control" rows="4">{{ old('benefits_summary') }}</textarea>
                    <small class="text-muted">What benefits does this scholarship provide?</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">External Link</label>
                    <input type="url" name="external_link" class="form-control" value="{{ old('external_link') }}" placeholder="https://example.com">
                    <small class="text-muted">Link to external scholarship application or more information</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Content</label>
                    <textarea id="ckeditor_content" name="content_html" class="ckeditor form-control" rows="8">{{ old('content_html') }}</textarea>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Cover Image</label>
                    <input type="file" name="cover_image" class="form-control" accept="image/*">
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Published At</label>
                        <input type="text" class="form-control" value="{{ now()->format('d/m/Y H:i') }}" disabled>
                        <small class="text-muted">dd/mm/yyyy --:-- (auto)</small>
                    </div>
                    <div class="col-md-6 d-flex align-items-end">
                        <div>
                            <label class="form-label d-block">Status</label>
                            <span class="badge bg-success">Active</span>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('admin.scholarships.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-dark">Save</button>
                </div>
            </form>
        </div>
    </div>
    </div>
</div>

@push('scripts')
<script>
if (window.CKEDITOR) {
    CKEDITOR.replace('ckeditor_content');
}

// Prevent double form submission
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const submitBtn = document.querySelector('button[type="submit"]');
    let isSubmitting = false;

    form.addEventListener('submit', function(e) {
        if (isSubmitting) {
            e.preventDefault();
            return false;
        }

        isSubmitting = true;
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Saving...';
        submitBtn.disabled = true;
        submitBtn.classList.add('btn-loading');

        // Re-enable after 10 seconds as fallback
        setTimeout(() => {
            isSubmitting = false;
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
            submitBtn.classList.remove('btn-loading');
        }, 10000);
    });
});
</script>
@endpush
@endsection


