@extends('adminpages.layouts.app')
@php($pageTitle = 'Photo Gallery')

@section('content')
<style>
    * {
        box-sizing: border-box;
    }

    .admin-gallery-container {
        width: 100%;
        margin: 0 auto;
        padding: 30px;
        background: #f8fafc;
        min-height: 100vh;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    }

    .admin-card {
        background: #ffffff;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        margin-bottom: 24px;
        overflow: hidden;
    }

    .admin-page-header {
        padding: 32px;
        text-align: center;
        background: #ffffff;
        border-bottom: 1px solid #f1f5f9;
    }

    .admin-page-title {
        margin: 0 0 8px 0;
        font-size: 28px;
        font-weight: 600;
        color: #0f172a;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
    }

    .admin-page-subtitle {
        margin: 0;
        color: #64748b;
        font-size: 16px;
        font-weight: 400;
    }

    .admin-alert {
        padding: 16px 20px;
        border-radius: 6px;
        margin-bottom: 24px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 8px;
        border: 1px solid;
    }

    .admin-alert-success {
        background: #f0fdf4;
        border-color: #bbf7d0;
        color: #166534;
    }

    .admin-alert-error {
        background: #fef2f2;
        border-color: #fecaca;
        color: #991b1b;
    }

    .admin-section {
        padding: 32px;
    }

    .admin-section:not(:last-child) {
        border-bottom: 1px solid #f1f5f9;
    }

    .admin-section-header {
        margin-bottom: 20px;
    }

    .admin-section-title {
        font-size: 18px;
        font-weight: 600;
        color: #0f172a;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .admin-form-row {
        display: flex;
        gap: 16px;
        align-items: stretch;
    }

    .admin-input-group {
        flex: 1;
    }

    .admin-input {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 14px;
        transition: border-color 0.2s ease;
        background: #ffffff;
        font-family: inherit;
        color: #374151;
    }

    .admin-input:focus {
        outline: none;
        border-color: #6366f1;
        box-shadow: 0 0 0 1px #6366f1;
    }

    .admin-input::placeholder {
        color: #9ca3af;
    }

    .admin-btn {
        border: none;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        text-decoration: none;
        white-space: nowrap;
    }

    .admin-btn-primary {
        background: #4f46e5;
        color: white;
        padding: 12px 20px;
    }

    .admin-btn-primary:hover {
        background: #4338ca;
    }

    .admin-btn-secondary {
        background: #f8fafc;
        color: #374151;
        border: 1px solid #e2e8f0;
        padding: 8px 16px;
    }

    .admin-btn-secondary:hover {
        background: #f1f5f9;
        color: #1f2937;
        text-decoration: none;
    }

    .admin-btn-warning {
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fcd34d;
        padding: 8px 12px;
    }

    .admin-btn-warning:hover {
        background: #fde68a;
        color: #78350f;
    }

    .admin-btn-danger {
        background: #fee2e2;
        color: #dc2626;
        border: 1px solid #fca5a5;
        padding: 8px 12px;
    }

    .admin-btn-danger:hover {
        background: #fecaca;
        color: #b91c1c;
    }

    .admin-btn-sm {
        font-size: 12px;
        padding: 6px 10px;
    }

    .admin-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 20px;
    }

    .admin-folder-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        overflow: hidden;
        transition: all 0.2s ease;
    }

    .admin-folder-card:hover {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        border-color: #d1d5db;
    }

    .admin-folder-header {
        padding: 20px;
        background: #fafbfc;
        border-bottom: 1px solid #f1f5f9;
    }

    .admin-folder-icon {
        font-size: 24px;
        margin-bottom: 8px;
        display: block;
        color: #6b7280;
    }

    .admin-folder-name {
        font-size: 16px;
        font-weight: 600;
        color: #0f172a;
        margin: 0;
        word-break: break-word;
        line-height: 1.4;
    }

    .admin-folder-body {
        padding: 16px;
    }

    .admin-folder-actions {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr;
        gap: 8px;
        align-items: center;
        margin-bottom: 12px;
    }

    .admin-rename-form {
        display: flex;
        gap: 6px;
        align-items: center;
    }

    .admin-rename-input {
        flex: 1;
        padding: 6px 10px;
        border: 1px solid #d1d5db;
        border-radius: 4px;
        font-size: 13px;
        background: #f9fafb;
        transition: all 0.2s ease;
        min-width: 0;
    }

    .admin-rename-input:focus {
        outline: none;
        border-color: #6366f1;
        background: white;
        box-shadow: 0 0 0 1px #6366f1;
    }

    .admin-empty-state {
        text-align: center;
        padding: 60px 32px;
        color: #6b7280;
    }

    .admin-empty-icon {
        width: 48px;
        height: 48px;
        background: #f1f5f9;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
        font-size: 20px;
        color: #9ca3af;
    }

    .admin-empty-title {
        font-size: 16px;
        font-weight: 500;
        color: #374151;
        margin-bottom: 8px;
    }

    .admin-empty-description {
        color: #6b7280;
        line-height: 1.5;
        font-size: 14px;
    }

    .admin-stats {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .admin-count {
        font-size: 14px;
        color: #6b7280;
        font-weight: 500;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .admin-gallery-container {
            padding: 16px;
        }

        .admin-page-header {
            padding: 24px;
        }

        .admin-page-title {
            font-size: 24px;
            flex-direction: column;
            gap: 8px;
        }

        .admin-section {
            padding: 20px;
        }

        .admin-form-row {
            flex-direction: column;
            gap: 12px;
        }

        .admin-grid {
            grid-template-columns: 1fr;
            gap: 16px;
        }

        .admin-folder-actions {
            grid-template-columns: 1fr;
            gap: 8px;
        }

        .admin-btn-secondary {
            justify-self: stretch;
        }

        .admin-rename-form {
            margin-top: 8px;
        }
    }

    @media (max-width: 480px) {
        .admin-page-title {
            font-size: 20px;
        }

        .admin-folder-header, .admin-folder-body {
            padding: 16px;
        }

        .admin-empty-state {
            padding: 40px 20px;
        }
    }

    /* Subtle animation */
    .admin-folder-card {
        animation: adminFadeIn 0.2s ease-out;
    }

    @keyframes adminFadeIn {
        from {
            opacity: 0;
            transform: translateY(4px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Hide/show rename forms */
    .admin-rename-form {
        display: none;
    }

    .admin-rename-form.active {
        display: flex;
    }
</style>

<div class="admin-gallery-container">
    <!-- Page Header -->
 

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="admin-alert admin-alert-success">
            <span>✓</span>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="admin-alert admin-alert-error">
            <span>✗</span>
            {{ session('error') }}
        </div>
    @endif

    <!-- Create Folder Section -->
    <div class="admin-card">
        <div class="admin-section">
            <div class="admin-section-header">
                <h2 class="admin-section-title">
                    <span>+</span>
                    Create New Folder
                </h2>
            </div>
            
            <form method="POST" action="{{ route('admin.gallery.createfolder') }}" class="admin-form-row">
                @csrf
                <div class="admin-input-group">
                    <input type="text" 
                           name="folder_name" 
                           class="admin-input"
                           placeholder="Enter folder name (e.g., Events, Products, Team Photos)" 
                           required>
                </div>
                <button type="submit" class="admin-btn admin-btn-primary">Create Folder</button>
            </form>
        </div>
    </div>

    <!-- Folders Section -->
    <div class="admin-card">
        <div class="admin-section">
            <div class="admin-stats">
                <div class="admin-section-header">
                    <h2 class="admin-section-title">
                        <span>📂</span>
                         PhotoGallery: 
                    </h2>
                </div>
                @if(count($folderNames) > 0)
                    <div class="admin-count">{{ count($folderNames) }} folders</div>
                @endif
            </div>

            @if(count($folderNames) > 0)
                <div class="admin-grid">
                    @foreach ($folderNames as $folder)
                        <div class="admin-folder-card">
                            <div class="admin-folder-header">
                                <span class="admin-folder-icon">📁</span>
                                <h3 class="admin-folder-name">{{ $folder }}</h3>
                            </div>
                            
                            <div class="admin-folder-body">
                                <!-- Action Buttons -->
                                <div class="admin-folder-actions">
                                    <a href="{{ route('admin.gallery.view-folder', $folder) }}" 
                                       class="admin-btn admin-btn-secondary">
                                        View Images
                                    </a>
                                    
                                    <button type="button" 
                                            class="admin-btn admin-btn-warning admin-btn-sm" 
                                            onclick="toggleAdminRenameForm('admin-rename-{{ $loop->index }}')">
                                        Rename
                                    </button>
                                    
                                    <form method="POST" 
                                          action="{{ route('admin.gallery.delete-folder', $folder) }}" 
                                          style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="admin-btn admin-btn-danger admin-btn-sm"
                                                onclick="return confirmAdminDelete('{{ $folder }}')">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                                
                                <!-- Rename Form -->
                                <form method="POST" 
                                      action="{{ route('admin.gallery.rename-folder', $folder) }}" 
                                      class="admin-rename-form" 
                                      id="admin-rename-{{ $loop->index }}">
                                    @csrf
                                    <input type="text" 
                                           name="new_name" 
                                           class="admin-rename-input"
                                           placeholder="New folder name" 
                                           required>
                                    <button type="submit" class="admin-btn admin-btn-warning admin-btn-sm">Save</button>
                                    <button type="button" 
                                            class="admin-btn admin-btn-secondary admin-btn-sm" 
                                            onclick="toggleAdminRenameForm('admin-rename-{{ $loop->index }}')">
                                        Cancel
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="admin-empty-state">
                    <div class="admin-empty-icon">📂</div>
                    <div class="admin-empty-title">No folders found</div>
                    <div class="admin-empty-description">
                        Create your first folder to start organizing your images
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function toggleAdminRenameForm(formId) {
    const form = document.getElementById(formId);
    const isActive = form.classList.contains('active');
    
    // Hide all rename forms
    document.querySelectorAll('.admin-rename-form').forEach(f => {
        f.classList.remove('active');
    });
    
    if (!isActive) {
        form.classList.add('active');
        const input = form.querySelector('.admin-rename-input');
        if (input) {
            input.focus();
            input.select();
        }
    }
}

function confirmAdminDelete(folderName) {
    return confirm(`Delete folder "${folderName}"?\n\nThis action cannot be undone. Ensure the folder is empty before deleting.`);
}

// Close rename forms when clicking outside
document.addEventListener('click', function(event) {
    const renameForms = document.querySelectorAll('.admin-rename-form');
    const renameButtons = document.querySelectorAll('.admin-btn-warning');
    
    let clickedRenameButton = false;
    renameButtons.forEach(button => {
        if (button.contains(event.target) && button.textContent.trim() === 'Rename') {
            clickedRenameButton = true;
        }
    });
    
    if (!clickedRenameButton) {
        renameForms.forEach(form => {
            if (!form.contains(event.target)) {
                form.classList.remove('active');
            }
        });
    }
});

// Handle Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        document.querySelectorAll('.admin-rename-form').forEach(form => {
            form.classList.remove('active');
        });
    }
});

// Handle Enter key in rename forms
document.addEventListener('keydown', function(event) {
    if (event.key === 'Enter' && event.target.classList.contains('admin-rename-input')) {
        event.target.closest('form').submit();
    }
});
</script>
@endsection