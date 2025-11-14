@extends('adminpages.layouts.app')

@section('title', 'Scholarships')

@section('content')
<div style="background:#ffffff; min-height:100vh; padding:40px 0;">
<div class="container px-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Scholarships</h3>
        <a href="{{ route('admin.scholarships.create') }}" class="btn btn-dark">Add Scholarship</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning">{{ session('warning') }}</div>
    @endif

    <div class="card">
        <div class="card-body p-0">
            <table class="table mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Published</th>
                        <th class="text-end">Actions<c1/th>
                    </tr>
                </thead>
                <tbody>
                @forelse($scholarships as $item)
                    <tr>
                        <td>{{ $item->title }}</td>
                        <td>
                            @if($item->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>{{ optional($item->published_at)->format('Y-m-d') }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.scholarships.edit', $item) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            <form class="d-inline" method="POST" action="{{ route('admin.scholarships.destroy', $item) }}" onsubmit="return confirm('Delete this scholarship?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center p-4">No scholarships found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">{{ $scholarships->links() }}</div>
    </div>
</div>
</div>
@endsection


