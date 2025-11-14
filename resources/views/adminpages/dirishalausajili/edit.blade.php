@extends('adminpages.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h4 class="m-0 font-weight-bold text-primary">Edit Application Window</h4>
            </div>
            <div class="card-body">
                <x-admin-validation-errors />
                
                <form id="windowApplicationForm" action="{{ route('admin.window_applications.update', $application->id) }}" method="POST" data-admin-validation="windowApplication">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="font-weight-bold">Program Type <span class="text-danger">*</span></label>
                                <div class="checkbox-group mt-3">
                                    @php
                                        $programTypes = \App\Models\WindowApplication::getAvailableProgramTypes();
                                    @endphp
                                    <div class="row">
                                        @foreach($programTypes as $value => $label)
                                            <div class="col-md-6">
                                                <div class="form-check custom-checkbox">
                                                    <input type="checkbox" name="program_type[]" value="{{ $value }}" id="{{ $value }}" 
                                                        class="form-check-input" {{ in_array($value, explode(',', $application->program_type)) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="{{ $value }}">{{ $label }}</label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                @error('program_type')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                                <small class="form-text text-muted">Select one or more program types</small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <x-admin-form-field
                                type="select"
                                name="extension_type"
                                label="Extension Type"
                                :options="\App\Models\WindowApplication::getAvailableExtensionTypes()"
                                :selected="old('extension_type', $application->extension_type)"
                                help="Select the type of window"
                            />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <x-admin-form-field
                                type="select"
                                name="academic_year"
                                label="Academic Year"
                                :options="[
                                    (date('Y') - 1) . '/' . date('Y') => (date('Y') - 1) . '/' . date('Y'),
                                    date('Y') . '/' . (date('Y') + 1) => date('Y') . '/' . (date('Y') + 1),
                                    (date('Y') + 1) . '/' . (date('Y') + 2) => (date('Y') + 1) . '/' . (date('Y') + 2)
                                ]"
                                :selected="old('academic_year', $application->academic_year)"
                                required
                                help="Select the academic year for this application window"
                            />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="starting_date" class="font-weight-bold">Application Opening Date <span class="text-danger">*</span></label>
                                <input type="date" name="starting_date" id="starting_date" class="form-control" 
                                       value="{{ old('starting_date', $application->starting_date ? \Carbon\Carbon::parse($application->starting_date)->format('Y-m-d') : '') }}" 
                                       required 
                                       placeholder="YYYY-MM-DD">
                                @error('starting_date')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                                <small class="form-text text-muted">Date when applications will open</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="ending_date" class="font-weight-bold">Application Closing Date <span class="text-danger">*</span></label>
                                <input type="date" name="ending_date" id="ending_date" class="form-control" 
                                       value="{{ old('ending_date', $application->ending_date ? \Carbon\Carbon::parse($application->ending_date)->format('Y-m-d') : '') }}" 
                                       required 
                                       placeholder="YYYY-MM-DD">
                                @error('ending_date')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                                <small class="form-text text-muted">Date when applications will close</small>
                            </div>
                        </div>
                    </div>
                      <div class="row">
                        <div class="col-md-12">
                            <x-admin-form-field
                                type="textarea"
                                name="description"
                                label="Description"
                                :value="old('description', $application->description)"
                                placeholder="Enter additional details about this application window..."
                                :rows="3"
                                help="Provide any additional information about this application window (Optional)"
                            />
                        </div>
                    </div>

                    <div class="form-actions mt-4">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-save"></i> Update Application Window
                        </button>
                        <a href="{{ route('admin.window_applications.index') }}" class="btn btn-secondary btn-lg ml-2">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

<!-- JavaScript for Checkbox Selection -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAllBtn = document.getElementById('selectAll');
        const deselectAllBtn = document.getElementById('deselectAll');
        const checkboxes = document.querySelectorAll('.program-checkbox');

        selectAllBtn.addEventListener('click', function(e) {
            e.preventDefault();
            checkboxes.forEach(checkbox => checkbox.checked = true);
        });

        deselectAllBtn.addEventListener('click', function(e) {
            e.preventDefault();
            checkboxes.forEach(checkbox => checkbox.checked = false);
        });
    });
</script>

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
        
        .form-group {
            margin-bottom: 2rem;
        }
        
        .form-group label {
            color: #2c3e50;
            margin-bottom: 0.75rem;
            font-size: 0.95rem;
            font-weight: 600;
            display: block;
        }
        
        .form-control {
            border: 1px solid #ced4da;
            border-radius: 4px;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            transition: all 0.15s ease-in-out;
            background-color: #ffffff;
            color: #495057;
            font-weight: 400;
        }
        
        .form-control:focus {
            border-color: #80bdff;
            background-color: white;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
            outline: none;
            color: #495057;
        }
        
        /* Specific styling for date inputs */
        input[type="date"] {
            color: #212529 !important;
            font-weight: 600 !important;
            background-color: #ffffff !important;
            border: 2px solid #ced4da !important;
            font-size: 1rem !important;
            padding: 0.75rem 1rem !important;
            min-height: 45px !important;
        }
        
        input[type="date"]:focus {
            color: #212529 !important;
            background-color: #ffffff !important;
            border-color: #80bdff !important;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25) !important;
        }
        
        input[type="date"]::-webkit-calendar-picker-indicator {
            color: #495057 !important;
            cursor: pointer;
            background-color: #f8f9fa;
            border-radius: 3px;
            padding: 4px;
            margin-left: 8px;
        }
        
        /* Make date input text more visible */
        input[type="date"]::-webkit-datetime-edit {
            color: #212529 !important;
            font-weight: 600 !important;
            font-size: 1rem !important;
            padding: 0 !important;
        }
        
        input[type="date"]::-webkit-datetime-edit-fields-wrapper {
            background-color: #ffffff !important;
            padding: 0 !important;
        }
        
        input[type="date"]::-webkit-datetime-edit-text {
            color: #212529 !important;
            font-weight: 600 !important;
            font-size: 1rem !important;
            padding: 0 2px !important;
        }
        
        input[type="date"]::-webkit-datetime-edit-month-field,
        input[type="date"]::-webkit-datetime-edit-day-field,
        input[type="date"]::-webkit-datetime-edit-year-field {
            color: #212529 !important;
            font-weight: 600 !important;
            background-color: #ffffff !important;
            font-size: 1rem !important;
            padding: 2px 4px !important;
            border-radius: 2px !important;
        }
        
        /* Firefox date input styling */
        input[type="date"]::-moz-placeholder {
            color: #6c757d !important;
            opacity: 1 !important;
        }
        
        /* Ensure date value is always visible */
        input[type="date"]:not(:placeholder-shown) {
            color: #212529 !important;
        }
        
        input[type="date"]:valid {
            color: #212529 !important;
        }
        
        /* Additional visual feedback for date inputs */
        input[type="date"].has-value {
            border-color: #28a745 !important;
            background-color: #f8fff9 !important;
        }
        
        input[type="date"].date-focused {
            border-color: #007bff !important;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25) !important;
            background-color: #ffffff !important;
        }
        
        /* Ensure date input text is always visible */
        input[type="date"]::-webkit-datetime-edit-month-field:focus,
        input[type="date"]::-webkit-datetime-edit-day-field:focus,
        input[type="date"]::-webkit-datetime-edit-year-field:focus {
            background-color: #e3f2fd !important;
            color: #1976d2 !important;
            outline: none !important;
        }
        
        .form-control:hover:not(:focus) {
            border-color: #ced4da;
        }
        
        .checkbox-group {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 4px;
            padding: 1.25rem;
            transition: all 0.15s ease-in-out;
        }
        
        .checkbox-group.is-invalid {
            border-color: #e74c3c;
            background-color: #fdf2f2;
        }
        
        .custom-checkbox {
            position: relative;
            margin-bottom: 1rem;
        }
        
        .form-check-input {
            width: 1.1rem;
            height: 1.1rem;
            margin-top: 0.1rem;
            cursor: pointer;
            border-radius: 3px;
            border: 1px solid #ced4da;
            background-color: #ffffff;
            transition: all 0.15s ease-in-out;
        }
        
        .form-check-input:checked {
            background-color: #495057;
            border-color: #495057;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3e%3cpath fill='none' stroke='%23fff' stroke-linecap='round' stroke-linejoin='round' stroke-width='3' d='m6 10 3 3 6-6'/%3e%3c/svg%3e");
        }
        
        .form-check-input:hover:not(:checked) {
            border-color: #495057;
        }
        
        .form-check-input:focus {
            box-shadow: 0 0 0 0.2rem rgba(73, 80, 87, 0.25);
            outline: none;
        }
        
        .form-check-label {
            color: #495057;
            font-weight: 500;
            font-size: 0.9rem;
            margin-left: 0.5rem;
            cursor: pointer;
            user-select: none;
            transition: color 0.2s ease-in-out;
        }
        
        .form-check-label:hover {
            color: #495057;
        }
        
        .custom-checkbox .form-check-input:checked + .form-check-label {
            color: #495057;
            font-weight: 500;
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
        
        .btn-success {
            background-color: #495057;
            border-color: #495057;
            color: white;
            font-weight: 500;
            transition: all 0.15s ease-in-out;
        }
        
        .btn-success:hover {
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
        
        .text-danger {
            color: #e74c3c !important;
            font-weight: 500;
        }
        
        .form-text {
            font-size: 0.825rem;
            color: #6c757d;
            font-style: italic;
            margin-top: 0.5rem;
        }
        
        .is-invalid {
            border-color: #e74c3c !important;
            box-shadow: 0 0 0 3px rgba(231, 76, 60, 0.1) !important;
        }
        
        /* Select All/Deselect All functionality */
        .checkbox-controls {
            margin-bottom: 1rem;
            text-align: right;
        }
        
        .checkbox-controls .btn-link {
            color: #2a5298;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            padding: 0;
            margin-left: 1rem;
        }
        
        .checkbox-controls .btn-link:hover {
            color: #1e3c72;
            text-decoration: underline;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .card-body {
                padding: 1.5rem;
            }
            
            .form-group {
                margin-bottom: 1.5rem;
            }
            
            .checkbox-group {
                padding: 1rem;
            }
            
            .custom-checkbox {
                margin-bottom: 0.75rem;
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
            
            .form-group label {
                font-size: 0.9rem;
            }
            
            .form-control {
                padding: 0.75rem;
                font-size: 0.9rem;
            }
            
            .checkbox-controls {
                text-align: left;
                margin-bottom: 0.75rem;
            }
            
            .checkbox-controls .btn-link {
                margin-left: 0;
                margin-right: 1rem;
            }
        }
        
        @media (max-width: 576px) {
            .card-header h4 {
                font-size: 1.1rem;
            }
            
            .card-body {
                padding: 1rem;
            }
            
            .form-group {
                margin-bottom: 1.25rem;
            }
            
            .custom-checkbox .col-md-4 {
                margin-bottom: 0.5rem;
            }
        }
    </style>
    <script>
        $(document).ready(function() {
            // Add select all/deselect all functionality
            $('.checkbox-group').prepend('<div class="checkbox-controls"><a href="#" class="btn-link" id="selectAll">Select All</a><a href="#" class="btn-link" id="deselectAll">Deselect All</a></div>');
            
            $('#selectAll').on('click', function(e) {
                e.preventDefault();
                $('input[name="program_type[]"]').prop('checked', true);
                $('.checkbox-group').removeClass('is-invalid');
            });
            
            $('#deselectAll').on('click', function(e) {
                e.preventDefault();
                $('input[name="program_type[]"]').prop('checked', false);
            });
            
            // Form validation
            $('form').on('submit', function(e) {
                let isValid = true;
                let errors = [];
                
                // Clear previous error states
                $('.form-control').removeClass('is-invalid');
                $('.checkbox-group').removeClass('is-invalid');
                
                // Convert checkbox values to comma-separated string
                let checkedPrograms = $('input[name="program_type[]"]:checked');
                if (checkedPrograms.length === 0) {
                    $('.checkbox-group').addClass('is-invalid');
                    errors.push('Please select at least one program type');
                    isValid = false;
                } else {
                    // Create a hidden input with comma-separated values
                    $('#program-type-hidden').remove(); // Remove existing hidden input if any
                    let programTypes = [];
                    checkedPrograms.each(function() {
                        programTypes.push($(this).val());
                    });
                    $('<input>').attr({
                        type: 'hidden',
                        id: 'program-type-hidden',
                        name: 'program_type',
                        value: programTypes.join(',')
                    }).appendTo('form');
                    
                    // Disable the checkbox inputs to prevent them from being submitted
                    $('input[name="program_type[]"]').prop('disabled', true);
                }
                
                // Validate window
                let window = $('#window').val();
                if (!window) {
                    $('#window').addClass('is-invalid');
                    errors.push('Please select an application window');
                    isValid = false;
                }
                
                // Validate academic year
                let academicYear = $('#academic_year').val();
                if (!academicYear) {
                    $('#academic_year').addClass('is-invalid');
                    errors.push('Please select an academic year');
                    isValid = false;
                }
                
                // Validate starting date
                let startDate = $('#starting_date').val();
                if (!startDate) {
                    $('#starting_date').addClass('is-invalid');
                    errors.push('Please select an application opening date');
                    isValid = false;
                }
                
                // Validate ending date
                let endDate = $('#ending_date').val();
                if (!endDate) {
                    $('#ending_date').addClass('is-invalid');
                    errors.push('Please select an application closing date');
                    isValid = false;
                }
                
                // Validate date range
                if (startDate && endDate) {
                    const startDateTime = new Date(startDate);
                    const endDateTime = new Date(endDate);
                    
                    if (endDateTime <= startDateTime) {
                        $('#ending_date').addClass('is-invalid');
                        errors.push('Application closing date must be after the opening date');
                        isValid = false;
                    }
                    
                    // Check if the application period is at least 7 days
                    const diffTime = Math.abs(endDateTime - startDateTime);
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                    if (diffDays < 7) {
                        $('#ending_date').addClass('is-invalid');
                        errors.push('Application period must be at least 7 days');
                        isValid = false;
                    }
                }
                
                if (!isValid) {
                    e.preventDefault();
                    // Re-enable checkboxes if form validation fails
                    $('input[name="program_type[]"]').prop('disabled', false);
                    $('#program-type-hidden').remove();
                    alert('Please correct the following errors:\n\n• ' + errors.join('\n• '));
                    // Scroll to first error
                    $('.is-invalid').first().focus();
                }
            });
            
            // Real-time date validation
            $('#starting_date, #ending_date').on('change', function() {
                let startDate = $('#starting_date').val();
                let endDate = $('#ending_date').val();
                
                if (startDate) {
                    const startDateTime = new Date(startDate);
                    // Set minimum end date to start date + 1 day
                    const minEndDate = new Date(startDateTime);
                    minEndDate.setDate(minEndDate.getDate() + 1);
                    $('#ending_date').attr('min', minEndDate.toISOString().split('T')[0]);
                }
                
                if (startDate && endDate) {
                    const startDateTime = new Date(startDate);
                    const endDateTime = new Date(endDate);
                    
                    if (endDateTime <= startDateTime) {
                        alert('Application closing date must be after the opening date.');
                        $('#ending_date').val('');
                        return;
                    }
                    
                    const diffTime = Math.abs(endDateTime - startDateTime);
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                    if (diffDays < 7) {
                        alert('Application period must be at least 7 days.');
                        $('#ending_date').val('');
                        return;
                    }
                }
            });
            
            // Remove invalid class on input change
            $('.form-control').on('input change', function() {
                $(this).removeClass('is-invalid');
            });
            
            // Remove invalid class when checkbox is checked
            $('input[name="program_type[]"]').on('change', function() {
                if ($('input[name="program_type[]"]:checked').length > 0) {
                    $('.checkbox-group').removeClass('is-invalid');
                }
            });
            
            // Ensure date inputs are properly formatted and visible
            function formatDateInputs() {
                $('input[type="date"]').each(function() {
                    const $input = $(this);
                    const value = $input.val();
                    
                    // If there's a value, ensure it's properly formatted
                    if (value) {
                        // Convert to YYYY-MM-DD format if needed
                        const date = new Date(value);
                        if (!isNaN(date.getTime())) {
                            const formattedDate = date.toISOString().split('T')[0];
                            $input.val(formattedDate);
                        }
                    }
                    
                    // Add a class to indicate if the input has a value
                    if (value) {
                        $input.addClass('has-value');
                    } else {
                        $input.removeClass('has-value');
                    }
                });
            }
            
            // Format date inputs on page load
            formatDateInputs();
            
            // Format date inputs when values change
            $('input[type="date"]').on('change input', function() {
                formatDateInputs();
            });
            
            // Add visual feedback for date inputs
            $('input[type="date"]').on('focus', function() {
                $(this).addClass('date-focused');
            }).on('blur', function() {
                $(this).removeClass('date-focused');
            });
        });
    </script>