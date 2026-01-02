@extends('adminpages.layouts.app')

@section('content')
<div class="min-vh-100" style="background-color: #ffffff;">
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                <!-- Header Section -->
                <div class="bg-white shadow-sm border rounded-3 p-4 mb-4">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                        <div>
                            <h2 class="mb-1 text-dark fw-bold">
                                <i class="fas fa-clipboard-list me-2 text-primary"></i>Audit Log Details
                            </h2>
                            <p class="text-muted mb-0 small">Detailed information about this administrative action</p>
                        </div>
                        <a href="{{ route('admin.audit-logs.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Audit Logs
                        </a>
                    </div>
                </div>

                <!-- Audit Log Details -->
                <div class="bg-white shadow-sm border rounded-3 p-4 mb-4">
                    <h5 class="mb-3 text-dark fw-semibold border-bottom pb-2">
                        <i class="fas fa-info-circle me-2"></i>Basic Information
                    </h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Log ID</label>
                            <div class="fw-semibold text-dark">#{{ $auditLog->id }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">User</label>
                            <div class="fw-semibold text-dark">{{ $auditLog->user->username ?? 'System' }}</div>
                            <small class="text-muted">{{ $auditLog->user->email ?? 'N/A' }}</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Action</label>
                            <div>
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
                                    $color = $actionColors[$auditLog->action] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $color }}">{{ ucfirst(str_replace('_', ' ', $auditLog->action)) }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Resource Type</label>
                            <div class="fw-semibold text-dark">{{ $auditLog->resource_type }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Resource ID</label>
                            <div class="fw-semibold text-dark">{{ $auditLog->resource_id ?? 'N/A' }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Date & Time</label>
                            <div class="fw-semibold text-dark">{{ $auditLog->created_at->format('Y-m-d H:i:s') }}</div>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="bg-white shadow-sm border rounded-3 p-4 mb-4">
                    <h5 class="mb-3 text-dark fw-semibold border-bottom pb-2">
                        <i class="fas fa-file-alt me-2"></i>Description
                    </h5>
                    <p class="text-dark mb-0">{{ $auditLog->description }}</p>
                </div>

                <!-- Request Information -->
                <div class="bg-white shadow-sm border rounded-3 p-4 mb-4">
                    <h5 class="mb-3 text-dark fw-semibold border-bottom pb-2">
                        <i class="fas fa-network-wired me-2"></i>Request Information
                    </h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted small">IP Address</label>
                            <div class="font-monospace text-dark">{{ $auditLog->ip_address }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">HTTP Method</label>
                            <div>
                                <span class="badge bg-secondary">{{ $auditLog->method ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label text-muted small">Route</label>
                            <div class="font-monospace text-dark">{{ $auditLog->route ?? 'N/A' }}</div>
                        </div>
                        <div class="col-12">
                            <label class="form-label text-muted small">User Agent</label>
                            <div class="font-monospace text-dark small">{{ $auditLog->user_agent ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>

                <!-- Changes (if update action) -->
                @if($auditLog->action === 'update' && ($auditLog->old_values || $auditLog->new_values))
                <div class="bg-white shadow-sm border rounded-3 p-4 mb-4">
                    <h5 class="mb-3 text-dark fw-semibold border-bottom pb-2">
                        <i class="fas fa-exchange-alt me-2"></i>Changes
                    </h5>
                    <div class="row">
                        @if($auditLog->old_values)
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Old Values</label>
                            <pre class="bg-light p-3 rounded border small">{{ json_encode($auditLog->old_values, JSON_PRETTY_PRINT) }}</pre>
                        </div>
                        @endif
                        @if($auditLog->new_values)
                        <div class="col-md-6">
                            <label class="form-label text-muted small">New Values</label>
                            <pre class="bg-light p-3 rounded border small">{{ json_encode($auditLog->new_values, JSON_PRETTY_PRINT) }}</pre>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

