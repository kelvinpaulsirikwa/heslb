@extends('adminpages.layouts.app')

@section('content')
<div class="min-vh-100" style="background-color: #ffffff;">
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-12">
                <!-- Header Section -->
                <div class="bg-white shadow-sm border rounded-3 p-4 mb-4">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                        <div>
                            <h2 class="mb-1 text-dark fw-bold">
                                <i class="fas fa-clipboard-list me-2 text-primary"></i>Audit Logs
                            </h2>
                            <p class="text-muted mb-0 small">Monitor and track all administrative actions in the system</p>
                        </div>
                    </div>
                </div>

                <!-- Filters Section -->
                <div class="bg-white shadow-sm border rounded-3 p-4 mb-4">
                    <form method="GET" action="{{ route('admin.audit-logs.index') }}" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label small text-muted">Search</label>
                            <input type="text" name="search" class="form-control" placeholder="Search in descriptions..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small text-muted">Action</label>
                            <select name="action" class="form-select">
                                <option value="">All Actions</option>
                                @foreach($actions as $action)
                                    <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('_', ' ', $action)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small text-muted">Resource Type</label>
                            <select name="resource_type" class="form-select">
                                <option value="">All Types</option>
                                @foreach($resourceTypes as $type)
                                    <option value="{{ $type }}" {{ request('resource_type') == $type ? 'selected' : '' }}>
                                        {{ $type }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small text-muted">User</label>
                            <select name="user_id" class="form-select">
                                <option value="">All Users</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->username }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small text-muted">Date From</label>
                            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small text-muted">Date To</label>
                            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter me-2"></i>Apply Filters
                            </button>
                            <a href="{{ route('admin.audit-logs.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Clear
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Audit Logs Table -->
                <div class="bg-white shadow-sm border rounded-3 overflow-hidden">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="fw-semibold text-dark py-3 px-4 border-0" style="width: 80px;">ID</th>
                                    <th class="fw-semibold text-dark py-3 px-4 border-0" style="min-width: 150px;">User</th>
                                    <th class="fw-semibold text-dark py-3 px-4 border-0" style="min-width: 120px;">Action</th>
                                    <th class="fw-semibold text-dark py-3 px-4 border-0" style="min-width: 120px;">Resource</th>
                                    <th class="fw-semibold text-dark py-3 px-4 border-0" style="min-width: 300px;">Description</th>
                                    <th class="fw-semibold text-dark py-3 px-4 border-0" style="min-width: 120px;">IP Address</th>
                                    <th class="fw-semibold text-dark py-3 px-4 border-0" style="min-width: 150px;">Date & Time</th>
                                    <th class="fw-semibold text-dark py-3 px-4 border-0" style="width: 100px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($auditLogs as $log)
                                <tr class="border-bottom">
                                    <td class="py-3 px-4 align-middle">
                                        <span class="badge bg-light text-dark border">#{{ $log->id }}</span>
                                    </td>
                                    <td class="py-3 px-4 align-middle">
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <div class="fw-semibold text-dark">{{ $log->user->username ?? 'System' }}</div>
                                                <small class="text-muted">{{ $log->user->email ?? 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 align-middle">
                                        @php
                                            $actionColors = [
                                                'create' => 'success',
                                                'update' => 'info',
                                                'delete' => 'danger',
                                                'unauthorized_access' => 'warning',
                                                'block' => 'warning',
                                                'unblock' => 'success',
                                                'reset_password' => 'primary',
                                            ];
                                            $color = $actionColors[$log->action] ?? 'secondary';
                                        @endphp
                                        <span class="badge bg-{{ $color }}">{{ ucfirst(str_replace('_', ' ', $log->action)) }}</span>
                                    </td>
                                    <td class="py-3 px-4 align-middle">
                                        <div>
                                            <div class="fw-semibold text-dark">{{ $log->resource_type }}</div>
                                            @if($log->resource_id)
                                                <small class="text-muted">ID: {{ $log->resource_id }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 align-middle">
                                        <div class="text-truncate" style="max-width: 300px;" title="{{ $log->description }}">
                                            {{ $log->description }}
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 align-middle">
                                        <small class="text-muted font-monospace">{{ $log->ip_address }}</small>
                                    </td>
                                    <td class="py-3 px-4 align-middle">
                                        <div>
                                            <div class="small text-dark">{{ $log->created_at->format('Y-m-d') }}</div>
                                            <div class="small text-muted">{{ $log->created_at->format('H:i:s') }}</div>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 align-middle">
                                        <a href="{{ route('admin.audit-logs.show', $log) }}" class="btn btn-outline-info btn-sm">
                                            <i class="fas fa-eye me-1"></i>View
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">No audit logs found</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($auditLogs->hasPages())
                    <div class="p-4 border-top">
                        {{ $auditLogs->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

