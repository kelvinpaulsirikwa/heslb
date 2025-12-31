@extends('adminpages.layouts.app')

@section('content')
<div class="min-vh-100" style="background-color: #f8f9fa;">
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-12">
                <!-- Header Section -->
                <div class="bg-white shadow-sm border rounded-3 p-4 mb-4">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                        <div>
                            <h2 class="mb-1 text-dark fw-bold">
                                <i class="fas fa-ban text-danger me-2"></i>
                                Blocked Emails
                            </h2>
                            <p class="text-muted mb-0 small">View and manage blocked email addresses due to failed login attempts</p>
                        </div>
                        <a href="{{ route('admin.login-attempts.index') }}" class="btn btn-outline-secondary px-4 py-2">
                            <i class="fas fa-arrow-left me-2"></i>Back to Login Attempts
                        </a>
                    </div>
                </div>

                <!-- Success/Error Messages -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('info'))
                    <div class="alert alert-info alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                        <i class="fas fa-info-circle me-2"></i>{{ session('info') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Blocked Emails Table -->
                <div class="bg-white shadow-sm border rounded-3 overflow-hidden">
                    @if(count($blockedEmails) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="fw-semibold text-dark py-3 px-4 border-0" style="width: 50px;">#</th>
                                        <th class="fw-semibold text-dark py-3 px-4 border-0">Email Address</th>
                                        <th class="fw-semibold text-dark py-3 px-4 border-0 text-center">Failed Attempts</th>
                                        <th class="fw-semibold text-dark py-3 px-4 border-0">Last Attempt</th>
                                        <th class="fw-semibold text-dark py-3 px-4 border-0 text-center">Remaining Lockout</th>
                                        <th class="fw-semibold text-dark py-3 px-4 border-0">IP Addresses</th>
                                        <th class="fw-semibold text-dark py-3 px-4 border-0 text-center" style="min-width: 150px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($blockedEmails as $index => $blocked)
                                    <tr class="border-bottom">
                                        <td class="py-3 px-4 align-middle">
                                            <span class="badge bg-light text-dark border">{{ $index + 1 }}</span>
                                        </td>
                                        <td class="py-3 px-4 align-middle">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-envelope text-danger me-2"></i>
                                                <span class="fw-medium text-dark">{{ $blocked['email'] }}</span>
                                            </div>
                                        </td>
                                        <td class="py-3 px-4 align-middle text-center">
                                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25">
                                                {{ $blocked['attempt_count'] }} attempt(s)
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 align-middle">
                                            <div class="text-dark small">
                                                <i class="fas fa-clock text-muted me-1"></i>
                                                {{ \Carbon\Carbon::parse($blocked['last_attempt'])->format('M d, Y H:i:s') }}
                                            </div>
                                            <div class="text-muted" style="font-size: 0.75rem;">
                                                {{ \Carbon\Carbon::parse($blocked['last_attempt'])->diffForHumans() }}
                                            </div>
                                        </td>
                                        <td class="py-3 px-4 align-middle text-center">
                                            <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25">
                                                <i class="fas fa-hourglass-half me-1"></i>
                                                {{ $blocked['remaining_minutes'] }} min
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 align-middle">
                                            <div class="d-flex flex-wrap gap-1">
                                                @foreach($blocked['ip_addresses'] as $ip)
                                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 small">
                                                        {{ $ip }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="py-3 px-4 align-middle text-center">
                                            <form action="{{ route('admin.blocked-emails.unblock') }}" method="POST" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="email" value="{{ $blocked['email'] }}">
                                                <button type="submit" 
                                                        class="btn btn-sm btn-success" 
                                                        onclick="return confirm('Are you sure you want to unblock {{ $blocked['email'] }}?')"
                                                        title="Unblock this email">
                                                    <i class="fas fa-unlock me-1"></i>Unblock
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="text-muted">
                                <i class="fas fa-check-circle fa-3x mb-3 text-success"></i>
                                <h5>No Blocked Emails</h5>
                                <p>There are currently no blocked email addresses.</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Info Card -->
                <div class="bg-info bg-opacity-10 border border-info border-opacity-25 rounded-3 p-4 mt-4">
                    <div class="d-flex align-items-start">
                        <i class="fas fa-info-circle text-info me-3 mt-1"></i>
                        <div>
                            <h6 class="text-info fw-bold mb-2">About Blocked Emails</h6>
                            <p class="text-muted mb-0 small">
                                Emails are automatically blocked after 5 failed login attempts within 15 minutes. 
                                The lockout expires after 15 minutes from the first failed attempt. 
                                You can manually unblock an email by clicking the "Unblock" button, which will clear all failed login attempts for that email address.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

