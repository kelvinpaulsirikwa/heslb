// contact-form.js

document.addEventListener('DOMContentLoaded', function () {
    
    const contactForm = document.getElementById('contactForm');
    const successAlert = document.getElementById('successAlert');
    const errorAlert = document.getElementById('errorAlert');
    const textarea = document.getElementById('message');
    const submitBtn = document.getElementById('submitBtn');
    
    if (!contactForm) return;

   

    // Hide alerts on page load

    hideAlerts();

    // Form submission handler
    contactForm.addEventListener('submit', function (e) {
        e.preventDefault();

        const form = this;

        // Prevent double submission
        if (submitBtn.disabled) {
            return;
        }

        // Clear previous validation and alerts
        clearValidation(form);
        hideAlerts();

        // Client-side validation
        if (!validateForm(form)) {
            showErrorAlert('Please correct the errors below and try again.');
            return;
        }

        // Show loading state
        showLoadingState();

        // Get form data
        const formData = new FormData(form);
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        // Add CSRF token if not present
        if (csrfToken && !formData.has('_token')) {
            formData.append('_token', csrfToken);
        }

        // Submit form via AJAX
        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => {
            return response.json().then(data => ({
                status: response.status,
                ok: response.ok,
                data: data
            }));
        })
        .then(result => {
            if (result.ok) {
                // Success
                showSuccessAlert(result.data.message || 'Your message has been sent successfully!');
                form.reset();
                scrollToTop();
            } else {
                // Handle different error types
                if (result.status === 422 && result.data.errors) {
                    // Validation errors
                    handleValidationErrors(result.data.errors);
                    // Human verification specific copy
                    if (result.data.errors.image_captcha_selection) {
                        showErrorAlert('Please complete the human verification (select the correct tiles).');
                    } else {
                        showErrorAlert(result.data.message || 'Please correct the errors below and try again.');
                    }
                } else if (result.status === 429) {
                    // Duplicate submission - show warning message
                    showErrorAlert(result.data.message || 'A similar message was recently submitted. Please wait a moment before submitting again.');
                } else {
                    // Other errors
                    showErrorAlert(result.data.message || 'An error occurred while submitting the form.');
                }
            }
        })
        .catch(error => {
            console.error('Form submission error:', error);
            showErrorAlert('There was a problem submitting your message. Please try again.');
            // Prevent form submission on error
            return false;
        })
        .finally(() => {
            hideLoadingState();
        });
    });

    // Client-side form validation
    function validateForm(form) {
        let isValid = true;
        const requiredFields = form.querySelectorAll('[required]');

        requiredFields.forEach(field => {
            const value = field.value.trim();
            
            if (!value) {
                showFieldError(field, 'This field is required');
                isValid = false;
            } else {
                // Specific validations
                if (field.type === 'email' && !isValidEmail(value)) {
                    showFieldError(field, 'Please enter a valid email address');
                    isValid = false;
                } else if (field.type === 'tel' && !isValidPhone(value)) {
                    showFieldError(field, 'Please enter a valid phone number (at least 10 digits)');
                    isValid = false;
                } else if (field.type === 'checkbox' && !field.checked) {
                    showFieldError(field, 'You must accept the terms and conditions');
                    isValid = false;
                }
            }
        });

        // Additional validations
        const ageField = form.querySelector('#age');
        if (ageField && ageField.value) {
            const age = parseInt(ageField.value);
            if (age < 1 || age > 120) {
                showFieldError(ageField, 'Please enter a valid age (1-120)');
                isValid = false;
            }
        }

        // Validate date of incident (cannot exceed today)
        const dateOfIncidentField = form.querySelector('#date_of_incident');
        if (dateOfIncidentField && dateOfIncidentField.value) {
            const selectedDate = new Date(dateOfIncidentField.value);
            const today = new Date();
            today.setHours(23, 59, 59, 999); // Set to end of today
            
            if (selectedDate > today) {
                showFieldError(dateOfIncidentField, 'Date of incident cannot be in the future');
                isValid = false;
            }
        }

        // Ensure image captcha selection is present
        const imageCaptcha = form.querySelector('#image_captcha_selection');
        if (imageCaptcha && (!imageCaptcha.value || imageCaptcha.value === '[]')) {
            const errorEl = document.getElementById('imageCaptchaError');
            if (errorEl) errorEl.style.display = 'block';
            isValid = false;
            showErrorAlert('Please complete the human verification (select the correct tiles).');
        }

        return isValid;
    }

    // Handle server-side validation errors
    function handleValidationErrors(errors) {
        Object.keys(errors).forEach(fieldName => {
            const field = document.querySelector(`[name="${fieldName}"]`);
            if (field) {
                const errorMessage = Array.isArray(errors[fieldName]) 
                    ? errors[fieldName][0] 
                    : errors[fieldName];
                showFieldError(field, errorMessage);
            }
        });

        // Scroll to first error
        const firstError = document.querySelector('.is-invalid');
        if (firstError) {
            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            firstError.focus();
        }
    }

    // Show field error
    function showFieldError(field, message) {
        field.classList.add('is-invalid');
        const feedback = field.closest('.mb-3').querySelector('.invalid-feedback');
        if (feedback) {
            feedback.textContent = message;
            feedback.style.display = 'block';
        }
    }

    // Clear all validation errors
    function clearValidation(form) {
        const invalidFields = form.querySelectorAll('.is-invalid');
        invalidFields.forEach(field => {
            field.classList.remove('is-invalid');
        });

        const feedbacks = form.querySelectorAll('.invalid-feedback');
        feedbacks.forEach(feedback => {
            feedback.textContent = '';
            feedback.style.display = 'none';
        });
    }

    // Show success alert
    function showSuccessAlert(message) {
        // Hide error alert first
        if (errorAlert) {
            errorAlert.style.display = 'none';
        }
        
        if (successAlert) {
            const messageSpan = successAlert.querySelector('#successMessage');
            if (messageSpan) {
                messageSpan.textContent = message;
            }
            successAlert.style.display = 'block';
            successAlert.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }

    // Show error alert
    function showErrorAlert(message) {
        // Hide success alert first
        if (successAlert) {
            successAlert.style.display = 'none';
        }
        
        if (errorAlert) {
            const messageSpan = errorAlert.querySelector('#errorMessage');
            if (messageSpan) {
                messageSpan.textContent = message;
            }
            errorAlert.style.display = 'block';
            errorAlert.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }

    // Hide all alerts
    function hideAlerts() {
        if (successAlert) {
            successAlert.style.display = 'none';
        }
        if (errorAlert) {
            errorAlert.style.display = 'none';
        }
    }

    // Show loading state
    function showLoadingState() {
        if (submitBtn) {
            submitBtn.classList.add('loading');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Submitting...';
        }
        contactForm.classList.add('form-loading');
    }

    // Hide loading state
    function hideLoadingState() {
        if (submitBtn) {
            submitBtn.classList.remove('loading');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-paper-plane me-1"></i>Tuma Ujumbe (Submit)';
        }
        contactForm.classList.remove('form-loading');
    }

    // Email validation
    function isValidEmail(email) {
        const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        return emailRegex.test(email);
    }

    // Phone validation
    function isValidPhone(phone) {
        // Remove all non-digit characters for validation
        const cleanPhone = phone.replace(/\D/g, '');
        return cleanPhone.length >= 10;
    }

    // Scroll to top
    function scrollToTop() {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // Remove error styling on input
    document.addEventListener('input', function (e) {
        if (e.target.classList.contains('is-invalid')) {
            e.target.classList.remove('is-invalid');
            const feedback = e.target.closest('.mb-3').querySelector('.invalid-feedback');
            if (feedback) {
                feedback.style.display = 'none';
            }
        }
    });

    // Remove error styling on change (for selects and checkboxes)
    document.addEventListener('change', function (e) {
        if (e.target.classList.contains('is-invalid')) {
            e.target.classList.remove('is-invalid');
            const feedback = e.target.closest('.mb-3, .form-check').querySelector('.invalid-feedback');
            if (feedback) {
                feedback.style.display = 'none';
            }
        }
    });

    // Auto-resize textarea
    if (textarea) {
        textarea.addEventListener('input', function () {
            this.style.height = 'auto';
            this.style.height = Math.max(120, this.scrollHeight) + 'px';
        });
    }

    // Clear form button handler
    const clearBtn = document.querySelector('button[type="reset"]');
    if (clearBtn) {
        clearBtn.addEventListener('click', function () {
            clearValidation(contactForm);
            hideAlerts();
            // Reset textarea height
            if (textarea) {
                textarea.style.height = '120px';
            }
        });
    }

    // Handle browser back/forward buttons to hide alerts
    window.addEventListener('pageshow', function(event) {
        if (event.persisted) {
            hideAlerts();
            clearValidation(contactForm);
        }
    });
});