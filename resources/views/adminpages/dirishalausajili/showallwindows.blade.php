@extends('adminpages.layouts.app')


@section('content')
    <div class="container-fluid p-4 bg-white mt-2">
        <div class="card shadow ">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h4 class="m-0 font-weight-bold text-primary"> Window's Applications</h4>
                <a href="{{ route('admin.window_applications.create') }}" class="btn btn-primary">Create New Window</a>
            </div>
            <div class="card-body">
                <!-- Filter Section -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="filter-section">
                            <form method="GET" action="{{ route('admin.window_applications.index') }}" class="filter-form">
                                <div class="filter-content">
                                    <div class="filter-label-group">
                                        <label for="extension_type" class="filter-label">Extension Type:</label>
                                    </div>
                                    <div class="filter-controls">
                                        <select name="extension_type" id="extension_type" class="form-control filter-select">
                                            <option value="">All Types</option>
                                            @foreach(\App\Models\WindowApplication::getAvailableExtensionTypes() as $value => $label)
                                                <option value="{{ $value }}" {{ $extensionType == $value ? 'selected' : '' }}>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="filter-buttons">
                                            <button type="submit" class="btn btn-primary filter-btn">
                                                <i class="fas fa-filter"></i> Filter
                                            </button>
                                            <a href="{{ route('admin.window_applications.index') }}" class="btn btn-secondary filter-btn">
                                                <i class="fas fa-times"></i> Clear
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                @if($applications->count())
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover" id="dataTable" width="100%" cellspacing="0">
                            <thead class="thead-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>User</th>
                                    <th>Program Type</th>
                                    <th>Extension Type</th>
                                    <th>Starting Date</th>
                                    <th>Ending Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $sortedApps = $applications->sortByDesc('submitted_at');
                                    $latestDate = $sortedApps->first() ? \Carbon\Carbon::parse($sortedApps->first()->submitted_at)->format('Y-m-d') : null;
                                @endphp
                                @foreach($sortedApps as $app)
                                    @php
                                        $submissionDate = \Carbon\Carbon::parse($app->submitted_at)->format('Y-m-d');
                                        $isLatestDate = $latestDate && $submissionDate === $latestDate;
                                    @endphp
                                    <tr class="{{ $isLatestDate ? 'latest-submission' : '' }}">
                                        <td>{{ $app->id }}</td>
                                        <td>{{ $app->user->username }}</td>
                                        <td>
                                            @php
                                                $availableTypes = \App\Models\WindowApplication::getAvailableProgramTypes();
                                                $programTypes = explode(',', $app->program_type);
                                            @endphp
                                            @foreach($programTypes as $type)
                                                <span class="badge badge-light mr-1 mb-1" style="background-color: #f8f9fa !important; color: #495057 !important; font-weight: 500; border: 1px solid #dee2e6;">{{ $availableTypes[trim($type)] ?? ucfirst(trim($type)) }}</span>
                                            @endforeach
                                        </td>
                                        <td>
                                            @if($app->extension_type)
                                                <span class="badge badge-light" style="background-color: #e9ecef !important; color: #495057 !important; font-weight: 500; border: 1px solid #dee2e6;">{{ ucfirst(str_replace('_', ' ', $app->extension_type)) }}</span>
                                            @else
                                                <span class="badge badge-light" style="background-color: #f8f9fa !important; color: #6c757d !important; font-weight: 500; border: 1px solid #dee2e6;">Not Set</span>
                                            @endif
                                        </td>
                                        <td>{{ $app->starting_date ? \Carbon\Carbon::parse($app->starting_date)->format('M d') : 'N/A' }}</td>
                                        <td>{{ $app->ending_date ? \Carbon\Carbon::parse($app->ending_date)->format('M d') : 'N/A' }}</td>
                                        <td class="d-flex">
                                            <a href="{{ route('admin.window_applications.show', $app->id) }}" class="btn btn-info btn-sm mr-1">View</a>
                                            <a href="{{ route('admin.window_applications.edit', $app->id) }}" class="btn btn-warning btn-sm mr-1">Edit</a>
                                            <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $app->id }}">Delete</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Extra space to indicate more data could be loaded -->
                    <div class="table-footer-space">
                        <div class="load-indicator">
                            <small class="text-muted">• • •</small>
                        </div>
                    </div>
                    
                @else
                    <div class="alert alert-info">No applications found.</div>
                @endif
            </div>
        </div>
    </div>

    <!-- Delete Modals -->
    @foreach($applications as $app)
    <div class="modal fade" id="deleteModal{{ $app->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $app->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel{{ $app->id }}">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this application window?</p>
                    <div class="alert alert-warning">
                        <strong>Application Details:</strong><br>
                        <strong>ID:</strong> {{ $app->id }}<br>
                        <strong>Program Type:</strong> {{ $app->program_type }}<br>
                        <strong>Extension Type:</strong> {{ $app->extension_type ? ucfirst(str_replace('_', ' ', $app->extension_type)) : 'Not Set' }}<br>
                        <strong>Academic Year:</strong> {{ $app->academic_year }}
                    </div>
                    <p class="text-danger"><strong>This action cannot be undone.</strong></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('admin.window_applications.destroy', $app->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete Application</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
@endsection

 <style>
        .card {
            border: 1px solid #e9ecef;
            border-radius: 6px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.08);
            background-color: #ffffff;
        }
        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
            color: #495057;
        }
        .card-header h4 {
            color: #495057 !important;
            font-weight: 600;
            font-size: 1.25rem;
        }
        .table th {
            background-color: #f8f9fa;
            color: #495057;
            position: sticky;
            top: 0;
            font-weight: 600;
            font-size: 0.875rem;
            border: 1px solid #dee2e6;
        }
        .table td {
            border: 1px solid #e9ecef;
            vertical-align: middle;
            padding: 0.75rem;
        }
        .table tbody tr:hover {
            background-color: #f8f9fa;
        }
        
        /* Filter Section Styling */
        .filter-section {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1rem;
        }
        
        .filter-form {
            width: 100%;
        }
        
        .filter-content {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
        }
        
        .filter-label-group {
            flex-shrink: 0;
        }
        
        .filter-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0;
            font-size: 0.95rem;
        }
        
        .filter-controls {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
            flex: 1;
        }
        
        .filter-select {
            min-width: 200px;
            max-width: 300px;
            background-color: #ffffff !important;
            color: #495057 !important;
            border: 1px solid #ced4da !important;
            border-radius: 6px;
            padding: 0.5rem 0.75rem;
            font-size: 0.9rem;
            transition: all 0.15s ease-in-out;
        }
        
        .filter-select:focus {
            background-color: #ffffff !important;
            color: #495057 !important;
            border-color: #80bdff !important;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25) !important;
            outline: none;
        }
        
        .filter-select option {
            background-color: #ffffff !important;
            color: #495057 !important;
            padding: 0.5rem;
        }
        
        .filter-buttons {
            display: flex;
            gap: 0.5rem;
            flex-shrink: 0;
        }
        
        .filter-btn {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
            font-weight: 500;
            border-radius: 6px;
            transition: all 0.15s ease-in-out;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .filter-btn i {
            font-size: 0.8rem;
        }
        
        /* Latest submission highlighting */
        .latest-submission {
            background-color: #f8f9fa !important;
            border-left: 3px solid #6c757d;
        }
        .latest-submission:hover {
            background-color: #e9ecef !important;
        }
        
        /* Table footer space styling */
        .table-footer-space {
            height: 150px;
            display: flex;
            align-items: flex-end;
            justify-content: center;
            padding-bottom: 20px;
        }
        .load-indicator {
            opacity: 0.6;
            font-size: 18px;
            letter-spacing: 8px;
        }
        
        .btn-sm {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
            line-height: 1.5;
            font-weight: 500;
            border-radius: 4px;
            transition: all 0.15s ease-in-out;
            border: 1px solid transparent;
        }
        .btn-info {
            background-color: #6c757d;
            border-color: #6c757d;
            color: white;
        }
        .btn-info:hover {
            background-color: #5a6268;
            border-color: #545b62;
        }
        .btn-warning {
            background-color: #495057;
            border-color: #495057;
            color: white;
        }
        .btn-warning:hover {
            background-color: #343a40;
            border-color: #343a40;
        }
        .btn-danger {
            background-color: #6c757d;
            border-color: #6c757d;
            color: white;
        }
        .btn-danger:hover {
            background-color: #5a6268;
            border-color: #545b62;
        }
        .btn-primary {
            background-color: #495057;
            border-color: #495057;
            color: white;
            font-weight: 500;
        }
        .btn-primary:hover {
            background-color: #343a40;
            border-color: #343a40;
        }
        .btn-secondary {
            background-color: #e9ecef;
            border-color: #e9ecef;
            color: #495057;
        }
        .btn-secondary:hover {
            background-color: #dee2e6;
            border-color: #d1d3d4;
            color: #495057;
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
        }
        .alert-info {
            color: #31708f;
            background-color: #d9edf7;
            border-color: #bce8f1;
        }
        
        /* Modal Styling */
        .modal-content {
            border: 1px solid #e9ecef;
            border-radius: 6px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        
        .modal-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
            border-radius: 6px 6px 0 0;
        }
        
        .modal-title {
            color: #495057;
            font-weight: 600;
        }
        
        .modal-body {
            padding: 1.5rem;
        }
        
        .modal-footer {
            background-color: #f8f9fa;
            border-top: 1px solid #e9ecef;
            border-radius: 0 0 6px 6px;
        }
        
        .btn-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #6c757d;
        }
        
        .btn-close:hover {
            color: #495057;
        }
        @media (max-width: 768px) {
            .filter-section {
                padding: 1rem;
            }
            
            .filter-content {
                flex-direction: column;
                align-items: stretch;
                gap: 1rem;
            }
            
            .filter-label-group {
                text-align: center;
            }
            
            .filter-controls {
                flex-direction: column;
                align-items: stretch;
                gap: 1rem;
            }
            
            .filter-select {
                min-width: auto;
                max-width: none;
                width: 100%;
            }
            
            .filter-buttons {
                justify-content: center;
                flex-wrap: wrap;
            }
            
            .filter-btn {
                flex: 1;
                min-width: 120px;
                justify-content: center;
            }
            
            .table-responsive {
                display: block;
                width: 100%;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                border: 1px solid #ddd;
                border-radius: 0.375rem;
            }
            .table {
                margin-bottom: 0;
                min-width: 800px;
            }
            .d-flex {
                display: flex;
                flex-wrap: wrap;
                gap: 5px;
                justify-content: center;
            }
            .btn-sm {
                white-space: nowrap;
                font-size: 0.75rem;
                padding: 0.2rem 0.4rem;
            }
            .table-footer-space {
                height: 100px;
            }
            .card-header {
                flex-direction: column;
                gap: 10px;
                text-align: center;
            }
            .card-header h4 {
                font-size: 1.1rem;
            }
            .table th, .table td {
                padding: 0.5rem;
                font-size: 0.85rem;
            }
            .latest-submission {
                border-left-width: 3px;
            }
        }
        
        @media (max-width: 576px) {
            .table th, .table td {
                padding: 0.4rem;
                font-size: 0.8rem;
            }
            .btn-sm {
                font-size: 0.7rem;
                padding: 0.15rem 0.3rem;
            }
            .card-header h4 {
                font-size: 1rem;
            }
        }
    </style>