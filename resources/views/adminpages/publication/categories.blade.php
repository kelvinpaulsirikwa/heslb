{{-- resources/views/admin/publications/categories/index.blade.php --}}
@extends('adminpages.layouts.app')

@section('title', 'Categories Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Categories Management</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.publications.categories.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add New Category
                        </a>
                        <a href="{{ route('admin.publications.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Publications
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    {{-- Success/Error Messages --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            @foreach($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                            <button type="button" class="close" data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                        </div>
                    @endif

                    {{-- Categories Table --}}
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="categories-table">
                            <thead>
                                <tr>
                                    <th width="50">Order</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Publications</th>
                                    <th>Posted by</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th width="150">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="sortable-categories">
                                @forelse($categories as $category)
                                    <tr data-id="{{ $category->id }}">
                                        <td class="text-center">
                                            <span class="drag-handle" style="cursor: move;">
                                                <i class="fas fa-grip-vertical"></i>
                                            </span>
                                            <span class="display-order">{{ $category->display_order }}</span>
                                        </td>
                                        <td>
                                            <strong>{{ $category->name }}</strong>
                                            <br><small class="text-muted">Slug: {{ $category->slug }}</small>
                                        </td>
                                        <td>
                                            {{ $category->description ? Str::limit($category->description, 50) : '-' }}
                                        </td>
                                        <td>
{{ $category->publications_count }}                                        </td>
                                        
                                        <td>
                                        {{ $category->user?->username ?? 'Unknown' }}                                        </td>

                                        
                                        <td>
                                            <button type="button" 
                                                    class="btn btn-sm toggle-category-status {{ $category->is_active ? 'btn-success' : 'btn-secondary' }}" 
                                                    data-id="{{ $category->id }}">
                                                {{ $category->is_active ? 'Active' : 'Inactive' }}
                                            </button>
                                        </td>
                                        <td>{{ $category->created_at }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.publications.categories.edit', $category) }}" 
                                                   class="btn btn-sm btn-warning" 
                                                   title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @if($category->publications_count == 0)
                                                    <form action="{{ route('admin.publications.categories.destroy', $category) }}" 
                                                          method="POST" 
                                                          class="d-inline delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="btn btn-sm btn-danger" 
                                                                title="Delete">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @else
                                                    <button type="button" 
                                                            class="btn btn-sm btn-secondary" 
                                                            title="Cannot delete category with publications"
                                                            disabled>
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No categories found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="d-flex justify-content-center">
                        {{ $categories->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Include jQuery UI for sorting --}}
@push('styles')
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/ui-lightness/jquery-ui.css">
<style>
.ui-sortable-helper {
    background: #f8f9fa;
    border: 2px dashed #007bff;
}
.drag-handle {
    color: #6c757d;
}
.drag-handle:hover {
    color: #007bff;
}
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<script>
$(document).ready(function() {
    // Make table sortable
    $("#sortable-categories").sortable({
        handle: '.drag-handle',
        helper: 'clone',
        placeholder: 'ui-state-highlight',
        update: function(event, ui) {
            updateCategoryOrder();
        }
    });

    // Update category order
    function updateCategoryOrder() {
        const categories = [];
        $('#sortable-categories tr').each(function(index) {
            const categoryId = $(this).data('id');
            if (categoryId) {
                categories.push({
                    id: categoryId,
                    order: index
                });
                // Update display order in the UI
                $(this).find('.display-order').text(index);
            }
        });

        // Send AJAX request to update order
        $.ajax({
            url: '{{ route("admin.publications.categories.update-order") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                categories: categories
            },
            success: function(response) {
                if (response.success) {
                    // Show success message briefly
                    const successMsg = $('<div class="alert alert-success">Order updated successfully!</div>');
                    $('.card-body').prepend(successMsg);
                    setTimeout(function() {
                        successMsg.fadeOut();
                    }, 2000);
                }
            },
            error: function() {
                alert('Failed to update category order. Please try again.');
                // Reload page to reset order
                location.reload();
            }
        });
    }

    // Toggle category status
    $('.toggle-category-status').click(function() {
        const button = $(this);
        const categoryId = button.data('id');

        $.ajax({
            url: '{{ route("admin.publications.categories.toggle-status", ":id") }}'.replace(':id', categoryId),
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    if (response.status) {
                        button.removeClass('btn-secondary').addClass('btn-success').text('Active');
                    } else {
                        button.removeClass('btn-success').addClass('btn-secondary').text('Inactive');
                    }
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function() {
                alert('An error occurred while updating status.');
            }
        });
    });

    // Delete confirmation
    $('.delete-form').on('submit', function(e) {
        e.preventDefault();
        const categoryName = $(this).closest('tr').find('strong').text();
        if (confirm('Are you sure you want to delete the category "' + categoryName + '"? This action cannot be undone.')) {
            this.submit();
        }
    });
});
</script>
@endpush
@endsection