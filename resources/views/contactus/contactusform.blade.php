@extends('layouts.app')

@section('title', 'Mawasiliano - Contact Us')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">


 <style>
        :root {
            --primary-color: #0e9bd5;
            --primary-dark: #0e9bd5;
            --secondary-color: #f8fafc;
            --accent-color: #0e9bd5;
            --text-dark: #1f2937;
            --text-muted: #6b7280;
            --border-color: #e5e7eb;
            --success-color: #10b981;
            --shadow-sm: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: var(--text-dark);
            background-color: #f9fafb;
        }

        /* Hero Section */
     
        .breadcrumb {
            background: transparent;
            padding: 0;
            margin: 0;
            position: relative;
            z-index: 2;
        }

        .breadcrumb-item + .breadcrumb-item::before {
            color: rgba(255, 255, 255, 0.7);
        }

        .breadcrumb a {
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .breadcrumb a:hover {
            color: white;
        }

        /* Main Content */
        .main-content {
            padding: 2rem 0;
            min-height: calc(100vh - 200px);
        }

        .page-title {
            color: var(--text-dark);
            font-weight: 600;
            margin-bottom: 2rem;
            padding-bottom: 0.5rem;
            border-bottom: 3px solid var(--accent-color);
            display: inline-block;
        }

        /* Cards */
        .form-card {
            background: white;
            border: none;
            border-radius: 12px;
            box-shadow: var(--shadow-md);
            margin-bottom: 2rem;
            overflow: hidden;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .form-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--accent-color) 100%) !important;
            border: none;
            padding: 1.25rem 1.5rem;
            font-weight: 600;
            font-size: 1.1rem;
            position: relative;
        }

        .card-header::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background: rgba(255, 255, 255, 0.3);
        }

        .card-body {
            padding: 2rem 1.5rem;
        }

        /* Form Controls */
        .form-label {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }

        .form-control, .form-select {
            border: 2px solid var(--border-color);
            border-radius: 8px;
            padding: 0.75rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background-color: #fafbfc;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
            background-color: white;
        }

        .form-control.is-invalid, .form-select.is-invalid {
            border-color: #dc3545;
        }

        .invalid-feedback {
            display: block;
            font-size: 0.875rem;
            margin-top: 0.25rem;
            color: #dc3545;
        }

        /* Textarea */
        textarea.form-control {
            resize: vertical;
            min-height: 120px;
        }

        /* Checkbox */
        .form-check {
            padding: 1rem;
            background-color: #f8f9fa;
            border-radius: 8px;
            border: 2px solid var(--border-color);
        }

        .form-check-input {
            width: 1.2em;
            height: 1.2em;
            margin-top: 0.125em;
        }

        .form-check-input:checked {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
        }

        .form-check-label {
            font-size: 0.95rem;
            line-height: 1.5;
            margin-left: 0.5rem;
        }

        /* Buttons */
        .btn {
            padding: 0.75rem 2rem;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-size: 1rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--accent-color) 100%);
            border: none;
            color: white;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-color) 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(59, 130, 246, 0.3);
        }

        .btn-secondary {
            background-color: #6c757d;
            border: none;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            transform: translateY(-1px);
        }

        /* Success Alert */
        .alert-success {
            background-color: #ecfdf5;
            border: 2px solid #10b981;
            color: #065f46;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 2rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .heslb-hero {
                padding: 2rem 0 1.5rem;
            }
            
            .heslb-hero-title {
                font-size: 1.8rem;
            }
            
            .card-body {
                padding: 1.5rem 1rem;
            }
            
            .btn {
                width: 100%;
                margin-bottom: 0.5rem;
            }
            
            .d-md-flex .btn {
                width: auto;
            }
        }

        @media (max-width: 576px) {
            .container {
                padding-left: 1rem;
                padding-right: 1rem;
            }
            
            .heslb-hero-title {
                font-size: 1.5rem;
            }
            
            .card-header {
                padding: 1rem;
                font-size: 1rem;
            }
            
            .card-body {
                padding: 1rem;
            }
        }

        /* Loading Animation */
        .form-loading {
            opacity: 0.7;
            pointer-events: none;
        }

        .btn.loading {
            position: relative;
            color: transparent;
        }

        .btn.loading::after {
            content: "";
            position: absolute;
            width: 20px;
            height: 20px;
            top: 50%;
            left: 50%;
            margin-left: -10px;
            margin-top: -10px;
            border: 2px solid transparent;
            border-top-color: #ffffff;
            border-radius: 50%;
            animation: spin 1s ease infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>

    <!-- Hero Section -->
    <div class="heslb-hero">
        <div class="container">
            <h1 class="heslb-hero-title" id="page-title">{{ strtoupper(__('contactservice.get_in_touch')) }}</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb text-white">
                    <li class="breadcrumb-item">
                        <a href="#" class="text-white">
                            <i class="fas fa-home me-1"></i>{{ __('contactservice.contact') }}
                        </a>
                    </li>
                    <li class="breadcrumb-item active" id="breadcrumb-title" aria-current="page">{{ __('contactservice.get_in_touch') }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container main-content">
        <h2 class="page-title">
            <i class="fas fa-envelope me-2"></i>{{ __('contactservice.contact_page_title') }}
        </h2>

        <!-- Success Alert (hidden by default) -->
        <div class="alert alert-success" id="successAlert" style="display: none;">
            <i class="fas fa-check-circle me-2"></i>
            <span id="successMessage">{{ __('contactservice.success_message') }}</span>
        </div>

        <!-- Error Alert (hidden by default) -->
        <div class="alert alert-danger" id="errorAlert" style="display: none;">
            <i class="fas fa-exclamation-circle me-2"></i>
            <span id="errorMessage">{{ __('contactservice.error_message') }}</span>
        </div>

        <form id="contactForm" action="{{ route('contact.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <!-- Personal Information Section -->
            <div class="card form-card">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-user me-2"></i>{{ __('contactservice.personal_information') }}
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="first_name" class="form-label">
                                {{ __('contactservice.first_name') }}
                            </label>
                            <input type="text" class="form-control @error('first_name') is-invalid @enderror" 
                                   id="first_name" name="first_name" value="{{ old('first_name') }}">
                            <div class="invalid-feedback">
                                @error('first_name')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="last_name" class="form-label">
                                {{ __('contactservice.last_name') }}
                            </label>
                            <input type="text" class="form-control @error('last_name') is-invalid @enderror" 
                                   id="last_name" name="last_name" value="{{ old('last_name') }}">
                            <div class="invalid-feedback">
                                @error('last_name')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">
                                {{ __('contactservice.email_address') }}
                            </label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}">
                            <div class="invalid-feedback">
                                @error('email')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">
                                {{ __('contactservice.phone_number') }}
                            </label>
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone') }}">
                            <div class="invalid-feedback">
                                @error('phone')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="gender" class="form-label">
                                {{ __('contactservice.gender') }}
                            </label>
                            <select class="form-select @error('gender') is-invalid @enderror" 
                                    id="gender" name="gender">
                                <option value="">{{ __('contactservice.select') }}</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>{{ __('contactservice.male') }}</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>{{ __('contactservice.female') }}</option>
                                <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>{{ __('contactservice.other') }}</option>
                            </select>
                            <div class="invalid-feedback">
                                @error('gender')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                       
                    </div>
                </div>
            </div>

            <!-- Contact Purpose Section -->
           

            <!-- Complaint/Message Details Section -->
            <div class="card form-card">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-comment-dots me-2"></i>{{ __('contactservice.complaint_message_details') }}
                </div>
                <div class="card-body">
                     <label for="contact_type" class="form-label">
                            {{ __('contactservice.contact_type') }} <span class="text-danger">*</span>
                        </label>
                        <select class="form-select @error('contact_type') is-invalid @enderror" 
                                id="contact_type" name="contact_type" required>
                            <option value="suggestions" {{ (old('contact_type') == 'suggestions' || (isset($preSelectedType) && $preSelectedType == 'suggestions')) ? 'selected' : '' }}>Suggestions</option>
                            <option value="inquiries" {{ (old('contact_type') == 'inquiries' || (isset($preSelectedType) && $preSelectedType == 'inquiries')) ? 'selected' : '' }}>Inquiries</option>
                            <option value="success_stories" {{ (old('contact_type') == 'success_stories' || (isset($preSelectedType) && $preSelectedType == 'success_stories')) ? 'selected' : '' }}>Success Stories</option>
                            <option value="complaint" {{ old('contact_type') == 'complaint' ? 'selected' : '' }}>{{ __('contactservice.complaint') }}</option>
                            <option value="other" {{ old('contact_type') == 'other' ? 'selected' : '' }}>{{ __('contactservice.other') }}</option>
                        </select>
                        <div class="invalid-feedback">
                            @error('contact_type')
                                {{ $message }}
                            @enderror
                        </div>
                        <br>
                    <div class="mb-3">
                        <label for="message" class="form-label">
                            {{ __('contactservice.message') }} <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control @error('message') is-invalid @enderror" 
                                  id="message" name="message" rows="5" required 
                                  placeholder="{{ __('contactservice.message_placeholder') }}">{{ old('message') }}</textarea>
                        <div class="invalid-feedback">
                            @error('message')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="date_of_incident" class="form-label">{{ __('contactservice.date_of_incident') }}</label>
                            <input type="date" class="form-control @error('date_of_incident') is-invalid @enderror" 
                                   id="date_of_incident" name="date_of_incident" value="{{ old('date_of_incident') }}" 
                                   max="{{ date('Y-m-d') }}">
                            <div class="invalid-feedback">
                                @error('date_of_incident')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                           
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="location" class="form-label">{{ __('contactservice.location') }}</label>
                        <input type="text" class="form-control @error('location') is-invalid @enderror" 
                               id="location" name="location" 
                               placeholder="{{ __('contactservice.location_placeholder') }}" value="{{ old('location') }}">
                        <div class="invalid-feedback">
                            @error('location')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>

                    <!-- Image Upload Field -->
                    <div class="mb-3">
                        <label for="image" class="form-label">{{ __('contactservice.upload_image') }}</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" 
                               id="image" name="image" accept="image/*">
                        <div class="form-text">{{ __('contactservice.image_help_text') }}</div>
                        <div class="invalid-feedback">
                            @error('image')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

           

            <!-- Consent Section -->
            <div class="card form-card">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-check-square me-2"></i>{{ __('contactservice.consent') }}
                </div>
                <div class="card-body">
                    <div class="form-check">
                        <input class="form-check-input @error('consent') is-invalid @enderror" 
                               type="checkbox" id="consent" name="consent" 
                               {{ old('consent') ? 'checked' : '' }} required>
                        <label class="form-check-label" for="consent">
                            <strong>{{ __('contactservice.consent_text') }}</strong><br>
                         </label>
                        <div class="invalid-feedback">
                            @error('consent')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Human verification captcha (image grid) --}}
            @include('components.human-captcha')

            <!-- Submit Buttons -->
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button type="reset" class="btn btn-secondary me-md-2">
                    <i class="fas fa-undo me-1"></i>{{ __('contactservice.clear_form') }}
                </button>
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <i class="fas fa-paper-plane me-1"></i>{{ __('contactservice.send_message') }}
                </button>
            </div>
        </form>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/contactus.js') }}"></script>


@endsection