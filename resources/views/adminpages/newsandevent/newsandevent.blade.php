<!-- {{-- STEP 1: Update your NewsPage Blade file --}}
{{-- resources/views/adminpages/newspage.blade.php --}}

@extends('adminpages.layouts.app')

@section('content')
<div class="container mt-5">
    <h2>Welcome to NewsPage</h2>
    
    {{-- Your existing content here --}}
    
</div>

{{-- Floating Action Button (FAB) --}}
<div class="fab-container">
    <button type="button" class="fab" data-bs-toggle="modal" data-bs-target="#editorModal">
        <svg width="24" height="24" fill="white" viewBox="0 0 24 24">
            <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
        </svg>
    </button>
</div>

{{-- Modal with Rich Text Editor --}}
<div class="modal fade" id="editorModal" tabindex="-1" aria-labelledby="editorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editorModalLabel">Create News Article</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form id="newsForm" method="POST" action="#" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    {{-- Title Input --}}
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    
                    {{-- Rich Text Editor Component --}}
                    <div class="mb-3">
                        <label class="form-label">Content</label>
                        <x-rich-text-editor 
                            name="content"
                            placeholder="Write your news article here..."
                            height="400px"
                            :image-upload="true"
                            max-width="100%"
                        />
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Article</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* FAB Styles */
.fab-container {
    position: fixed;
    bottom: 30px;
    right: 30px;
    z-index: 1000;
}

.fab {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background-color: #007bff;
    border: none;
    box-shadow: 0 4px 12px rgba(0, 123, 255, 0.4);
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.fab:hover {
    background-color: #0056b3;
    transform: scale(1.1);
    box-shadow: 0 6px 20px rgba(0, 123, 255, 0.6);
}

.fab:active {
    transform: scale(0.95);
}

/* Modal adjustments */
.modal-xl {
    max-width: 95%;
}

@media (max-width: 768px) {
    .fab-container {
        bottom: 20px;
        right: 20px;
    }
    
    .fab {
        width: 50px;
        height: 50px;
    }
    
    .modal-xl {
        max-width: 100%;
        margin: 0;
    }
}
</style>

@endsection -->
