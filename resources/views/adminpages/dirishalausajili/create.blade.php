@extends('adminpages.layouts.app')

@section('content')
    <div class="container-fluid p-4 bg-white mt-2">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h4 class="m-0 font-weight-bold text-primary">Create New Application Window</h4>
            </div>
            <div class="card-body">
                <x-admin-validation-errors />
                
                <form id="windowApplicationForm" action="{{ route('admin.window_applications.store') }}" method="POST" data-admin-validation="windowApplication">
                    @csrf

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
                                                        class="form-check-input" {{ in_array($value, old('program_type', [])) ? 'checked' : '' }}>
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
                                :selected="old('extension_type')"
                                help="Select the type of window"
                            />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="academic_year" class="font-weight-bold">Academic Year <span class="text-danger">*</span></label>
                                <select name="academic_year" id="academic_year" class="form-control" required>
                                    <option value="">Select Academic Year</option>
                                    @php
                                        $currentYear = date('Y');
                                        $academicYears = [];
                                        
                                        // Generate academic years (current-1 to current+2)
                                        for ($i = -1; $i <= 2; $i++) {
                                            $year1 = $currentYear + $i;
                                            $year2 = $year1 + 1;
                                            $academicYears[$year1 . '/' . $year2] = $year1 . '/' . $year2;
                                        }
                                    @endphp
                                    @foreach($academicYears as $value => $display)
                                        <option value="{{ $value }}" {{ old('academic_year') == $value ? 'selected' : '' }}>{{ $display }}</option>
                                    @endforeach
                                </select>
                                @error('academic_year')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="starting_date" class="font-weight-bold">Application Opening Date <span class="text-danger">*</span></label>
                                <input type="date" name="starting_date" id="starting_date" class="form-control" value="{{ old('starting_date') }}" required>
                                @error('starting_date')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                                <small class="form-text text-muted">Date when applications will open</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="ending_date" class="font-weight-bold">Application Closing Date <span class="text-danger">*</span></label>
                                <input type="date" name="ending_date" id="ending_date" class="form-control" value="{{ old('ending_date') }}" required>
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
                                :value="old('description')"
                                placeholder="Enter additional details about this application window..."
                                :rows="3"
                                help="Provide any additional information about this application window (Optional)"
                            />
                        </div>
                    </div>

                    <div class="form-actions mt-4">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-save"></i> Create Application Window
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
        /* ========================================
           CORE STYLES
        ======================================== */
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
        
        /* ========================================
           FORM ELEMENTS
        ======================================== */
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
        }
        
        input[type="date"]:focus {
            color: #212529 !important;
            background-color: #ffffff !important;
            border-color: #80bdff !important;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25) !important;
        }
        
        input[type="date"]::-webkit-calendar-picker-indicator {
            color: #495057;
            cursor: pointer;
            background-color: #f8f9fa;
            border-radius: 3px;
            padding: 2px;
        }
        
        /* Make date input text more visible */
        input[type="date"]::-webkit-datetime-edit {
            color: #212529 !important;
            font-weight: 600 !important;
        }
        
        input[type="date"]::-webkit-datetime-edit-fields-wrapper {
            background-color: #ffffff;
        }
        
        input[type="date"]::-webkit-datetime-edit-text {
            color: #212529 !important;
            font-weight: 600 !important;
        }
        
        input[type="date"]::-webkit-datetime-edit-month-field,
        input[type="date"]::-webkit-datetime-edit-day-field,
        input[type="date"]::-webkit-datetime-edit-year-field {
            color: #212529 !important;
            font-weight: 600 !important;
            background-color: #ffffff !important;
        }
        
        .form-control:hover:not(:focus) {
            border-color: #ced4da;
        }
        
        .form-control.is-invalid {
            border-color: #e74c3c !important;
            box-shadow: 0 0 0 3px rgba(231, 76, 60, 0.1) !important;
        }
        
        /* ========================================
           CHECKBOX STYLING
        ======================================== */
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
            border: none;
            background: none;
        }
        
        .checkbox-controls .btn-link:hover {
            color: #1e3c72;
            text-decoration: underline;
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
        
        /* ========================================
           BUTTON STYLING
        ======================================== */
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
            transition: all 0.3s ease-in-out;
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
        
        /* ========================================
           UTILITY CLASSES
        ======================================== */
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
        
        /* ========================================
           RESPONSIVE DESIGN
        ======================================== */
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
        document.addEventListener('DOMContentLoaded', function() {
            // Set minimum date to today for both date fields
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('starting_date').setAttribute('min', today);
            
            // Select All/Deselect All functionality
            document.getElementById('selectAll').addEventListener('click', function(e) {
                e.preventDefault();
                const checkboxes = document.querySelectorAll('input[name="program_type[]"]');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = true;
                });
                document.querySelector('.checkbox-group').classList.remove('is-invalid');
            });
            
            document.getElementById('deselectAll').addEventListener('click', function(e) {
                e.preventDefault();
                const checkboxes = document.querySelectorAll('input[name="program_type[]"]');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = false;
                });
            });
            
            // Date validation
            const startDateInput = document.getElementById('starting_date');
            const endDateInput = document.getElementById('ending_date');
            
            function updateMinEndDate() {
                const startDate = startDateInput.value;
                if (startDate) {
                    const startDateTime = new Date(startDate);
                    const minEndDate = new Date(startDateTime);
                    minEndDate.setDate(minEndDate.getDate() + 1);
                    endDateInput.setAttribute('min', minEndDate.toISOString().split('T')[0]);
                    
                    // Clear end date if it's now invalid
                    if (endDateInput.value && new Date(endDateInput.value) <= startDateTime) {
                        endDateInput.value = '';
                    }
                }
            }
            
            function validateDates() {
                const startDate = startDateInput.value;
                const endDate = endDateInput.value;
                
                if (startDate && endDate) {
                    const startDateTime = new Date(startDate);
                    const endDateTime = new Date(endDate);
                    
                    if (endDateTime <= startDateTime) {
                        showError('Application closing date must be after the opening date.');
                        endDateInput.value = '';
                        return false;
                    }
                    
                    const diffTime = Math.abs(endDateTime - startDateTime);
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                    if (diffDays < 7) {
                        showError('Application period must be at least 7 days.');
                        endDateInput.value = '';
                        return false;
                    }
                }
                return true;
            }
            
            startDateInput.addEventListener('change', function() {
                updateMinEndDate();
                this.classList.remove('is-invalid');
            });
            
            endDateInput.addEventListener('change', function() {
                if (validateDates()) {
                    this.classList.remove('is-invalid');
                }
            });
            
            // Remove invalid class on input change
            const formControls = document.querySelectorAll('.form-control');
            formControls.forEach(control => {
                control.addEventListener('input', function() {
                    this.classList.remove('is-invalid');
                });
                control.addEventListener('change', function() {
                    this.classList.remove('is-invalid');
                });
            });
            
            // Remove invalid class when checkbox is checked
            const programCheckboxes = document.querySelectorAll('input[name="program_type[]"]');
            programCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const checkedBoxes = document.querySelectorAll('input[name="program_type[]"]:checked');
                    if (checkedBoxes.length > 0) {
                        document.querySelector('.checkbox-group').classList.remove('is-invalid');
                    }
                });
            });
            
            // Form validation
            document.getElementById('applicationForm').addEventListener('submit', function(e) {
                let isValid = true;
                const errors = [];
                
                // Clear previous error states
                document.querySelectorAll('.form-control').forEach(el => el.classList.remove('is-invalid'));
                document.querySelector('.checkbox-group').classList.remove('is-invalid');
                
                // Validate program types
                const checkedPrograms = document.querySelectorAll('input[name="program_type[]"]:checked');
                if (checkedPrograms.length === 0) {
                    document.querySelector('.checkbox-group').classList.add('is-invalid');
                    errors.push('Please select at least one program type');
                    isValid = false;
                }
                
                // Validate required fields
                const requiredFields = [
                    { id: 'window', message: 'Please select an application window' },
                    { id: 'academic_year', message: 'Please select an academic year' },
                    { id: 'starting_date', message: 'Please select an application opening date' },
                    { id: 'ending_date', message: 'Please select an application closing date' }
                ];
                
                requiredFields.forEach(field => {
                    const element = document.getElementById(field.id);
                    if (!element.value.trim()) {
                        element.classList.add('is-invalid');
                        errors.push(field.message);
                        isValid = false;
                    }
                });
                
                // Validate dates
                const startDate = startDateInput.value;
                const endDate = endDateInput.value;
                
                if (startDate && endDate) {
                    const startDateTime = new Date(startDate);
                    const endDateTime = new Date(endDate);
                    const todayDate = new Date();
                    todayDate.setHours(0, 0, 0, 0);
                    
                    if (startDateTime < todayDate) {
                        startDateInput.classList.add('is-invalid');
                        errors.push('Application opening date cannot be in the past');
                        isValid = false;
                    }
                    
                    if (endDateTime <= startDateTime) {
                        endDateInput.classList.add('is-invalid');
                        errors.push('Application closing date must be after the opening date');
                        isValid = false;
                    }
                    
                    const diffTime = Math.abs(endDateTime - startDateTime);
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                    if (diffDays < 7) {
                        endDateInput.classList.add('is-invalid');
                        errors.push('Application period must be at least 7 days');
                        isValid = false;
                    }
                }
                
                if (!isValid) {
                    e.preventDefault();
                    showError('Please correct the following errors:\n\n• ' + errors.join('\n• '));
                    // Scroll to first error
                    const firstError = document.querySelector('.is-invalid');
                    if (firstError) {
                        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        firstError.focus();
                    }
                }
            });
            
            function showError(message) {
                alert(message);
            }
        });
    </script>
