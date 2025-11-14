@extends('adminpages.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h4 class="m-0 font-weight-bold text-primary">Application Window Details</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <tbody>
                            <tr>
                                <th class="font-weight-bold text-primary bg-light">ID</th>
                                <td>{{ $application->id }}</td>
                            </tr>
                            <tr>
                                <th class="font-weight-bold text-primary bg-light">Posted By</th>
                                <td>{{ $application->user->username }} ({{ $application->user->email }})</td>
                            </tr>
                            <tr>
                                <th class="font-weight-bold text-primary bg-light">Program Type</th>
                                <td>
                                    @php
                                        $availableTypes = \App\Models\WindowApplication::getAvailableProgramTypes();
                                    @endphp
                                    @foreach(explode(',', $application->program_type) as $program)
                                        <span class="badge badge-program-type" style="background-color: #e9ecef !important; color: #495057 !important; font-weight: 600 !important; border: 1px solid #ced4da !important; padding: 0.4em 0.75em !important; border-radius: 6px !important; box-shadow: 0 1px 3px rgba(0,0,0,0.1) !important;">{{ $availableTypes[trim($program)] ?? ucfirst(trim($program)) }}</span>
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <th class="font-weight-bold text-primary bg-light">Extension Type</th>
                                <td>
                                    @if($application->extension_type)
                                        <span class="badge badge-light" style="background-color: #e9ecef !important; color: #495057 !important; font-weight: 500; border: 1px solid #dee2e6;">{{ ucfirst(str_replace('_', ' ', $application->extension_type)) }}</span>
                                    @else
                                        <span class="badge badge-light" style="background-color: #f8f9fa !important; color: #6c757d !important; font-weight: 500; border: 1px solid #dee2e6;">Not Set</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="font-weight-bold text-primary bg-light">Academic Year</th>
                                <td>{{ $application->academic_year }}</td>
                            </tr><tr>
                                <th class="font-weight-bold text-primary bg-light">Description</th>
                                <td>{{ $application->description }}</td>
                            </tr>
                            <tr>
                                <th class="font-weight-bold text-primary bg-light">Starting Date</th>
                                <td>{{ \Carbon\Carbon::parse($application->starting_date)->format('M d, Y') }}</td>
                            </tr>
                            <tr>
                                <th class="font-weight-bold text-primary bg-light">Ending Date</th>
                                <td>{{ \Carbon\Carbon::parse($application->ending_date)->format('M d, Y') }}</td>
                            </tr>
                            <tr>
                                <th class="font-weight-bold text-primary bg-light">Submitted At</th>
                                <td>{{ $application->submitted_at ? \Carbon\Carbon::parse($application->submitted_at)->format('M d, Y H:i:s') : 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th class="font-weight-bold text-primary bg-light">Created At</th>
                                <td>{{ \Carbon\Carbon::parse($application->created_at)->format('M d, Y H:i:s') }}</td>
                            </tr>
                            <tr>
                                <th class="font-weight-bold text-primary bg-light">Updated At</th>
                                <td>{{ \Carbon\Carbon::parse($application->updated_at)->format('M d, Y H:i:s') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="form-actions mt-4">
                    <a href="{{ route('admin.window_applications.index') }}" class="btn btn-secondary btn-lg">
                        <i class="fas fa-arrow-left"></i> Back to List
                    </a>
                    <a href="{{ route('admin.window_applications.edit', $application->id) }}" class="btn btn-primary btn-lg ml-2">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

<style>
    .card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.07);
    }

    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
        color: #495057;
        border-radius: 6px 6px 0 0;
    }

    .card-header h4 {
        color: #495057 !important;
        font-weight: 600;
        font-size: 1.25rem;
    }

    .card-body {
        padding: 2.5rem;
    }

    .table {
        width: 100%;
        margin-bottom: 1rem;
        color: #495057;
        border-collapse: collapse;
    }

    .table th {
        background-color: #f8f9fa;
        padding: 1rem;
        text-align: left;
        width: 30%;
        color: #495057;
        font-weight: 600;
    }

    .table td {
        padding: 1rem;
        vertical-align: middle;
        border-top: 1px solid #e9ecef;
        color: #495057;
        font-weight: 500;
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(0, 0, 0, 0.02);
    }

    .table-hover tbody tr:hover {
        background-color: rgba(42, 82, 152, 0.05);
    }

    /* Updated badge for program type */
    .badge-program-type {
        font-size: 0.85em;
        font-weight: 600;
        padding: 0.4em 0.75em;
        margin-right: 0.5rem;
        margin-bottom: 0.25rem;
        border-radius: 6px;
        background-color: #e9ecef !important;
        color: #495057 !important;
        border: 1px solid #ced4da !important;
        display: inline-block;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        transition: all 0.2s ease-in-out;
    }
    
    .badge-program-type:hover {
        background-color: #dee2e6 !important;
        color: #343a40 !important;
        border-color: #adb5bd !important;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.15);
    }

    .form-actions {
        border-top: 2px solid #f1f3f4;
        padding-top: 2rem;
        margin-top: 2rem;
        text-align: center;
    }

    .btn-lg {
        padding: 0.875rem 2rem;
        font-size: 1rem;
        font-weight: 600;
        border-radius: 8px;
        min-width: 180px;
        letter-spacing: 0.3px;
    }

    .btn-primary {
        background-color: #495057;
        border-color: #495057;
        color: white;
        font-weight: 500;
        transition: all 0.15s ease-in-out;
    }

    .btn-primary:hover {
        background-color: #343a40;
        border-color: #343a40;
        color: white;
    }

    .btn-secondary {
        background-color: #e9ecef;
        border-color: #e9ecef;
        color: #495057;
        font-weight: 500;
        transition: all 0.15s ease-in-out;
    }

    .btn-secondary:hover {
        background-color: #dee2e6;
        border-color: #d1d3d4;
        color: #495057;
    }

    .bg-light {
        background-color: #f8f9fa !important;
    }

    .text-primary {
        color: #495057 !important;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .card-body {
            padding: 1.5rem;
        }

        .table th, 
        .table td {
            padding: 0.75rem;
        }

        .form-actions {
            text-align: center;
        }

        .btn-lg {
            width: 100%;
            margin-bottom: 0.75rem;
            min-width: auto;
        }

        .ml-2 {
            margin-left: 0 !important;
        }
    }

    @media (max-width: 576px) {
        .card-header h4 {
            font-size: 1.1rem;
        }

        .card-body {
            padding: 1rem;
        }

        .table th, 
        .table td {
            display: block;
            width: 100%;
        }

        .table th {
            border-bottom: none;
            padding-bottom: 0.25rem;
        }

        .table td {
            padding-top: 0.25rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #e9ecef;
        }

        .table tr:last-child td {
            border-bottom: none;
        }
    }
</style>
