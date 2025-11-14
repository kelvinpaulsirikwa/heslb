@extends('adminpages.layouts.app')

@section('content')
<style>
    * {
        box-sizing: border-box;
    }

    .admin-container {
        margin: 0 auto;
        padding: 30px;
        width: 100%;
        background: #f8fafc;
        min-height: 100vh;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    }

    .admin-card {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        margin-bottom: 24px;
        overflow: hidden;
    }

    .page-header {
        padding: 32px;
        border-bottom: 1px solid #e2e8f0;
        background: #ffffff;
    }

    .page-title {
        margin: 0 0 8px 0;
        font-size: 28px;
        font-weight: 600;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .page-subtitle {
        margin: 0;
        color: #64748b;
        font-size: 16px;
        font-weight: 400;
    }

    .breadcrumb {
        margin-top: 20px;
    }

    .breadcrumb-link {
        color: #4f46e5;
        text-decoration: none;
        font-weight: 500;
        padding: 8px 16px;
        border-radius: 6px;
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .breadcrumb-link:hover {
        background: #e2e8f0;
        text-decoration: none;
        color: #4338ca;
    }

    .alert {
        padding: 16px 24px;
        border-radius: 8px;
        margin-bottom: 24px;
        font-weight: 500;
        border-left: 4px solid;
    }

    .alert-success {
        background: #f0fdff;
        border-color: #06b6d4;
        color: #0e7490;
    }

    .alert-error {
        background: #fef2f2;
        border-color: #ef4444;
        color: #dc2626;
    }

    .upload-section {
        padding: 32px;
        border-bottom: 1px solid #e2e8f0;
    }

    .section-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 24px;
    }

    .section-title {
        font-size: 20px;
        font-weight: 600;
        color: #1e293b;
        margin: 0;
    }

    .upload-form {
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 24px;
        align-items: end;
    }

    .file-upload-container {
        position: relative;
    }

    .file-drop-zone {
        border: 2px dashed #cbd5e1;
        border-radius: 12px;
        padding: 48px 24px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease;
        background: #fafafa;
        position: relative;
    }

    .file-drop-zone:hover {
        border-color: #4f46e5;
        background: #f8fafc;
    }

    .file-drop-zone.dragover {
        border-color: #4f46e5;
        background: #f1f5f9;
        border-style: solid;
    }

    .file-input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
        width: 100%;
        height: 100%;
    }

    .drop-zone-content {
        pointer-events: none;
    }

    .drop-icon {
        width: 48px;
        height: 48px;
        background: #e2e8f0;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
        color: #64748b;
        font-size: 20px;
    }

    .drop-text {
        color: #334155;
        font-size: 16px;
        font-weight: 500;
        margin-bottom: 4px;
    }

    .drop-subtext {
        color: #64748b;
        font-size: 14px;
    }

    .selected-files-preview {
        margin-top: 20px;
        padding: 16px;
        background: #f1f5f9;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        display: none;
    }

    .selected-files-preview.show {
        display: block;
    }

    .preview-header {
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 12px;
        font-size: 14px;
    }

    .preview-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
        gap: 12px;
    }

    .preview-item {
        text-align: center;
    }

    .preview-image {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 6px;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
    }

    .preview-name {
        margin-top: 4px;
        font-size: 12px;
        color: #64748b;
        word-break: break-word;
        line-height: 1.2;
    }

    .btn-primary {
        background: #4f46e5;
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s ease;
        height: fit-content;
        white-space: nowrap;
    }

    .btn-primary:hover:not(:disabled) {
        background: #4338ca;
    }

    .btn-primary:disabled {
        background: #94a3b8;
        cursor: not-allowed;
    }

    .upload-progress {
        display: none;
        align-items: center;
        gap: 12px;
        color: #64748b;
        font-size: 14px;
        margin-top: 16px;
    }

    .upload-progress.show {
        display: flex;
    }

    .spinner {
        width: 16px;
        height: 16px;
        border: 2px solid #e5e7eb;
        border-top: 2px solid #4f46e5;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .gallery-section {
        padding: 32px;
    }

    .gallery-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }

    .gallery-count {
        font-size: 14px;
        color: #64748b;
        font-weight: 400;
    }

    .images-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 24px;
    }

    .image-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.2s ease;
    }

    .image-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        transform: translateY(-2px);
    }

    .image-container {
        position: relative;
        width: 100%;
        height: 200px;
        background: #f8fafc;
        overflow: hidden;
    }

    .image-preview {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.2s ease;
    }

    .image-card:hover .image-preview {
        transform: scale(1.05);
    }

    .image-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.2s ease;
        cursor: pointer;
    }

    .image-card:hover .image-overlay {
        opacity: 1;
    }

    .view-button {
        background: #ffffff;
        color: #1e293b;
        border: none;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: transform 0.2s ease;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }

    .view-button:hover {
        transform: scale(1.1);
    }

    .image-info {
        padding: 16px;
    }

    .image-name {
        font-size: 14px;
        font-weight: 500;
        color: #1e293b;
        margin-bottom: 12px;
        word-break: break-all;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .image-actions {
        display: flex;
        gap: 8px;
    }

    .btn-secondary {
        background: #f1f5f9;
        color: #475569;
        text-decoration: none;
        border: 1px solid #e2e8f0;
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 500;
        transition: all 0.2s ease;
        flex: 1;
        text-align: center;
    }

    .btn-secondary:hover {
        background: #e2e8f0;
        color: #334155;
        text-decoration: none;
    }

    .btn-danger {
        background: #fef2f2;
        color: #dc2626;
        border: 1px solid #fecaca;
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-danger:hover {
        background: #fee2e2;
        border-color: #fca5a5;
    }

    .empty-state {
        text-align: center;
        padding: 80px 32px;
        color: #64748b;
    }

    .empty-icon {
        width: 64px;
        height: 64px;
        background: #f1f5f9;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
        font-size: 24px;
        color: #94a3b8;
    }

    .empty-title {
        font-size: 18px;
        font-weight: 500;
        color: #334155;
        margin-bottom: 8px;
    }

    .empty-description {
        color: #64748b;
        line-height: 1.5;
    }

    /* Modal */
    .image-modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.9);
    }

    .image-modal.show {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px;
    }

    .modal-content {
        max-width: 90vw;
        max-height: 90vh;
        position: relative;
        background: #ffffff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
    }

    .modal-image {
        width: 100%;
        height: 100%;
        object-fit: contain;
        display: block;
    }

    .modal-close {
        position: absolute;
        top: 16px;
        right: 16px;
        background: rgba(255, 255, 255, 0.9);
        border: none;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        cursor: pointer;
        font-size: 18px;
        color: #1e293b;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 500;
    }

    .modal-close:hover {
        background: #ffffff;
        transform: scale(1.05);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .admin-container {
            padding: 16px;
        }

        .page-header {
            padding: 24px;
        }

        .page-title {
            font-size: 24px;
        }

        .upload-section, .gallery-section {
            padding: 24px;
        }

        .upload-form {
            grid-template-columns: 1fr;
            gap: 16px;
        }

        .images-grid {
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 16px;
        }

        .preview-grid {
            grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
        }

        .preview-image {
            width: 80px;
            height: 80px;
        }

        .modal-content {
            max-width: 95vw;
            max-height: 95vh;
        }
    }

    @media (max-width: 480px) {
        .page-title {
            font-size: 20px;
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
        }

        .images-grid {
            grid-template-columns: 1fr;
        }

        .image-actions {
            flex-direction: column;
            gap: 6px;
        }
    }
</style>

<div class="admin-container">
    <!-- Page Header -->
    <div class="admin-card">
        <div class="page-header">
            <h1 class="page-title">
                📁 {{ $folderName }}
            </h1>
            <p class="page-subtitle">Manage and organize your image gallery</p>
            
            <div class="breadcrumb">
                <a href="{{ route('admin.gallery.index') }}" class="breadcrumb-link">
                    ← Back to All Folders
                </a>
            </div>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="alert alert-success">
            ✓ {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            ✗ {{ session('error') }}
        </div>
    @endif

    <!-- Upload Section -->
    <div class="admin-card">
        <div class="upload-section">
            <div class="section-header">
                <h2 class="section-title">Upload New Images</h2>
            </div>
            
            <form method="POST" 
                  action="{{ route('admin.gallery.upload-images', $folderName) }}" 
                  enctype="multipart/form-data" 
                  id="uploadForm" 
                  class="upload-form">
                @csrf
                
                <div class="file-upload-container">
                    <div class="file-drop-zone" id="dropZone">
                        <input type="file" 
                               name="images[]" 
                               id="fileInput"
                               class="file-input"
                               multiple 
                               accept="image/*"
                               required>
                        
                        <div class="drop-zone-content">
                            <div class="drop-icon">📤</div>
                            <div class="drop-text">Click to select images or drag and drop</div>
                            <div class="drop-subtext">JPG, PNG, GIF up to 100MB each</div>
                        </div>
                    </div>
                    
                    <div class="selected-files-preview" id="selectedPreview">
                        <div class="preview-header">Selected Images:</div>
                        <div class="preview-grid" id="previewGrid"></div>
                    </div>
                    
                    <div class="upload-progress" id="uploadProgress">
                        <div class="spinner"></div>
                        <span>Uploading images, please wait...</span>
                    </div>
                </div>
                
                <button type="submit" class="btn-primary" id="uploadBtn" disabled>
                    Upload Images
                </button>
            </form>
        </div>
    </div>

    <!-- Gallery Section -->
    <div class="admin-card">
        <div class="gallery-section">
            <div class="gallery-header">
                <div class="section-header">
                    <h2 class="section-title">Gallery Images</h2>
                </div>
                @if(count($images) > 0)
                    <div class="gallery-count">{{ count($images) }} images</div>
                @endif
            </div>

            @if(count($images) > 0)
                <div class="images-grid">
                    @foreach ($images as $image)
                        <div class="image-card">
                            <div class="image-container">
                                <img src="{{ $image['url'] }}" 
                                     alt="{{ $image['name'] }}" 
                                     class="image-preview"
                                     loading="lazy">
                                
                                <div class="image-overlay" onclick="openImageModal('{{ $image['url'] }}', '{{ $image['name'] }}')">
                                    <button class="view-button" type="button">
                                        👁️
                                    </button>
                                </div>
                            </div>
                            
                            <div class="image-info">
                                <div class="image-name" title="{{ $image['name'] }}">
                                    {{ $image['name'] }}
                                </div>
                                
                                <div class="image-actions">
                                    <a href="{{ $image['url'] }}" 
                                       target="_blank" 
                                       class="btn-secondary">
                                       View
                                    </a>
                                    
                                    <form method="POST" 
                                          action="{{ route('admin.gallery.delete-image', [$folderName, $image['name']]) }}"
                                          style="display: inline;"
                                          onsubmit="return confirmDelete('{{ $image['name'] }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-danger">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-icon">📷</div>
                    <div class="empty-title">No images uploaded yet</div>
                    <div class="empty-description">Upload some images above to get started with your gallery</div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Image Modal -->
<div class="image-modal" id="imageModal">
    <div class="modal-content">
        <button class="modal-close" onclick="closeImageModal()">×</button>
        <img src="" alt="" class="modal-image" id="modalImage">
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('fileInput');
    const dropZone = document.getElementById('dropZone');
    const selectedPreview = document.getElementById('selectedPreview');
    const previewGrid = document.getElementById('previewGrid');
    const uploadForm = document.getElementById('uploadForm');
    const uploadBtn = document.getElementById('uploadBtn');
    const uploadProgress = document.getElementById('uploadProgress');
    
    // Click handler for drop zone
    dropZone.addEventListener('click', function() {
        fileInput.click();
    });
    
    // File selection handler
    fileInput.addEventListener('change', function() {
        updateFilePreview();
    });
    
    // Drag and drop handlers
    dropZone.addEventListener('dragover', function(e) {
        e.preventDefault();
        dropZone.classList.add('dragover');
    });
    
    dropZone.addEventListener('dragleave', function(e) {
        e.preventDefault();
        dropZone.classList.remove('dragover');
    });
    
    dropZone.addEventListener('drop', function(e) {
        e.preventDefault();
        dropZone.classList.remove('dragover');
        
        const files = e.dataTransfer.files;
        fileInput.files = files;
        updateFilePreview();
    });
    
    // Form submission handler
    uploadForm.addEventListener('submit', function() {
        uploadBtn.style.display = 'none';
        uploadProgress.classList.add('show');
    });
    
    function updateFilePreview() {
        const files = fileInput.files;
        previewGrid.innerHTML = '';
        
        if (files.length > 0) {
            selectedPreview.classList.add('show');
            uploadBtn.disabled = false;
            
            Array.from(files).forEach(file => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const previewItem = document.createElement('div');
                        previewItem.className = 'preview-item';
                        
                        previewItem.innerHTML = `
                            <img src="${e.target.result}" alt="${file.name}" class="preview-image">
                            <div class="preview-name">${file.name}</div>
                        `;
                        
                        previewGrid.appendChild(previewItem);
                    };
                    reader.readAsDataURL(file);
                }
            });
        } else {
            selectedPreview.classList.remove('show');
            uploadBtn.disabled = true;
        }
    }
    
    // Initialize
    updateFilePreview();
});

// Modal functions
function openImageModal(imageUrl, imageName) {
    const modal = document.getElementById('imageModal');
    const modalImage = document.getElementById('modalImage');
    
    modalImage.src = imageUrl;
    modalImage.alt = imageName;
    modal.classList.add('show');
    
    document.body.style.overflow = 'hidden';
}

function closeImageModal() {
    const modal = document.getElementById('imageModal');
    modal.classList.remove('show');
    document.body.style.overflow = 'auto';
}

// Close modal on outside click
document.getElementById('imageModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeImageModal();
    }
});

// Close modal on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeImageModal();
    }
});

// Delete confirmation
function confirmDelete(imageName) {
    return confirm(`Are you sure you want to delete "${imageName}"?\n\nThis action cannot be undone.`);
}
</script>
@endsection