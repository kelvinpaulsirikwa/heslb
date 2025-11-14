@php
use App\Models\Contact;
@endphp


<div class="card mb-4">
    <div class="card-header bg-light">
        <h5 class="mb-0">Filters</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.user-stories.index') }}" method="GET">
            <div class="row">
                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Statuses</option>
                            <option value="seen" {{ request('status') === 'seen' ? 'selected' : '' }}>Seen</option>
                            <option value="not seen" {{ request('status') === 'not seen' ? 'selected' : '' }}>Not Seen</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Search</label>
                        <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('admin.user-stories.index') }}" class="btn btn-outline-secondary">Reset</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>