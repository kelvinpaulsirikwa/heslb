@props(['errors'])

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <div class="d-flex align-items-start">
            <div class="bg-danger rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                <i class="fas fa-exclamation-triangle text-white"></i>
            </div>
            <div class="flex-grow-1">
                <strong class="text-danger">Validation Errors Detected</strong>
                <div class="text-muted mt-1">Please review and correct the following issues:</div>
                <ul class="mb-0 mt-2 text-danger">
                    @foreach ($errors->all() as $error)
                        <li class="mb-1">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
