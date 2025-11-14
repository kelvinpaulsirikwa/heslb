/**
 * Admin Form Validation System
 * Provides comprehensive frontend validation for all admin forms
 */

class AdminFormValidator {
    constructor(formSelector, validationRules) {
        this.form = document.querySelector(formSelector);
        this.rules = validationRules;
        this.errors = {};
        this.init();
    }

    init() {
        if (!this.form) return;
        
        this.setupValidation();
        this.setupRealTimeValidation();
        this.setupFormSubmission();
    }

    setupValidation() {
        // Add validation attributes to form
        this.form.setAttribute('novalidate', true);
        this.form.classList.add('needs-validation');
    }

    setupRealTimeValidation() {
        // Real-time validation on input change
        this.form.querySelectorAll('input, select, textarea').forEach(field => {
            field.addEventListener('blur', () => this.validateField(field));
            field.addEventListener('input', () => {
                this.clearFieldError(field);
                // Trigger validation of dependent fields for password fields
                if (field.name === 'old_password' || field.name === 'password' || field.name === 'password_confirmation') {
                    this.validateDependentFields(field.name);
                }
            });
            
            // Special handling for radio buttons to trigger validation of dependent fields
            if (field.type === 'radio') {
                field.addEventListener('change', () => {
                    this.validateDependentFields(field.name);
                });
            }
        });
    }

    validateDependentFields(dependentFieldName) {
        // Find all fields that depend on this field and re-validate them
        this.form.querySelectorAll('input, select, textarea').forEach(field => {
            const rules = this.rules[field.name];
            if (rules && rules.required_if) {
                const [dependentField] = rules.required_if.split(',');
                if (dependentField === dependentFieldName) {
                    this.validateField(field);
                }
            }
            // Also handle required_with validation
            if (rules && rules.required_with) {
                const dependentFields = Array.isArray(rules.required_with) ? rules.required_with : [rules.required_with];
                if (dependentFields.includes(dependentFieldName)) {
                    this.validateField(field);
                }
            }
        });
    }

    setupFormSubmission() {
        this.form.addEventListener('submit', (e) => {
            if (!this.validateForm()) {
                e.preventDefault();
                this.showErrors();
                this.scrollToFirstError();
            }
        });
    }

    validateField(field) {
        const fieldName = field.name;
        const value = field.value.trim();
        const rules = this.rules[fieldName];

        if (!rules) return true;

        let isValid = true;
        let errorMessage = '';

        // Handle conditional validation (e.g., required_if for shortcut links)
        if (rules.required_if) {
            const [dependentField, dependentValue] = rules.required_if.split(',');
            const dependentFieldElement = this.form.querySelector(`[name="${dependentField}"]`);
            let dependentFieldValue = '';
            
            if (dependentFieldElement) {
                if (dependentFieldElement.type === 'radio') {
                    const checkedRadio = this.form.querySelector(`[name="${dependentField}"]:checked`);
                    dependentFieldValue = checkedRadio ? checkedRadio.value : '';
                } else {
                    dependentFieldValue = dependentFieldElement.value;
                }
            }
            
            // Only validate if the dependent field matches the required value
            if (dependentFieldValue === dependentValue && !value) {
                isValid = false;
                errorMessage = rules.required_if_error || rules.required || 'This field is required';
            }
            // If dependent field doesn't match, field is not required, so skip other validations
            else if (dependentFieldValue !== dependentValue) {
                // Clear any previous errors and return as valid
                this.clearFieldError(field);
                delete this.errors[fieldName];
                return true;
            }
        }
        // Handle required_with validation (field required when any of the specified fields have values)
        else if (rules.required_with) {
            const dependentFields = Array.isArray(rules.required_with) ? rules.required_with : [rules.required_with];
            let anyDependentFieldHasValue = false;
            
            dependentFields.forEach(dependentField => {
                const dependentFieldElement = this.form.querySelector(`[name="${dependentField}"]`);
                if (dependentFieldElement && dependentFieldElement.value.trim()) {
                    anyDependentFieldHasValue = true;
                }
            });
            
            if (anyDependentFieldHasValue && !value) {
                isValid = false;
                errorMessage = rules.required_with_error || 'This field is required when other related fields are filled';
            }
        }
        // Regular required validation (only if not handled by required_if or required_with)
        else if (rules.required && !value) {
            isValid = false;
            errorMessage = rules.required;
        }
        
        // Continue with other validations only if field has value or is required
        if (isValid && value) {
            // String validation
            if (rules.string && typeof value !== 'string') {
                isValid = false;
                errorMessage = rules.string;
            }
            // Email validation
            else if (rules.email && !this.isValidEmail(value)) {
                isValid = false;
                errorMessage = rules.email;
            }
            // URL validation
            else if (rules.url && !this.isValidUrl(value)) {
                isValid = false;
                errorMessage = rules.url;
            }
            // Min length validation
            else if (rules.min && value.length < rules.min) {
                isValid = false;
                errorMessage = rules.min_error || `Minimum ${rules.min} characters required`;
            }
            // Max length validation
            else if (rules.max && value.length > rules.max) {
                isValid = false;
                errorMessage = rules.max_error || `Maximum ${rules.max} characters allowed`;
            }
        }
        
        // File validation
        if (isValid && rules.file && field.files && field.files.length > 0) {
            const file = field.files[0];
            if (rules.mimes && !rules.mimes.includes(file.type)) {
                isValid = false;
                errorMessage = rules.mimes_error || `File must be one of: ${rules.mimes.join(', ')}`;
            }
            if (rules.max_size && file.size > rules.max_size) {
                isValid = false;
                errorMessage = rules.max_size_error || `File size must be less than ${this.formatFileSize(rules.max_size)}`;
            }
        }
        
        // Array validation
        if (isValid && rules.array && value) {
            try {
                const arrayValue = JSON.parse(value);
                if (!Array.isArray(arrayValue)) {
                    isValid = false;
                    errorMessage = rules.array;
                } else if (rules.min_items && arrayValue.length < rules.min_items) {
                    isValid = false;
                    errorMessage = rules.min_items_error || `Minimum ${rules.min_items} items required`;
                }
            } catch (e) {
                isValid = false;
                errorMessage = rules.array;
            }
        }
        
        // Custom validation
        if (isValid && rules.custom && typeof rules.custom === 'function') {
            const customResult = rules.custom(value, field);
            if (customResult !== true) {
                isValid = false;
                errorMessage = customResult;
            }
        }

        if (!isValid) {
            this.showFieldError(field, errorMessage);
            this.errors[fieldName] = errorMessage;
        } else {
            this.clearFieldError(field);
            delete this.errors[fieldName];
        }

        return isValid;
    }

    validateForm() {
        this.errors = {};
        let isValid = true;

        this.form.querySelectorAll('input, select, textarea').forEach(field => {
            if (!this.validateField(field)) {
                isValid = false;
            }
        });

        return isValid;
    }

    showFieldError(field, message) {
        field.classList.add('is-invalid');
        field.classList.remove('is-valid');

        // Create or update error message
        let errorDiv = field.parentNode.querySelector('.invalid-feedback');
        if (!errorDiv) {
            errorDiv = document.createElement('div');
            errorDiv.className = 'invalid-feedback';
            field.parentNode.appendChild(errorDiv);
        }
        errorDiv.textContent = message;
        errorDiv.style.display = 'block';
    }

    clearFieldError(field) {
        field.classList.remove('is-invalid');
        field.classList.add('is-valid');

        const errorDiv = field.parentNode.querySelector('.invalid-feedback');
        if (errorDiv) {
            errorDiv.style.display = 'none';
        }
    }

    showErrors() {
        // Show general error alert
        this.showErrorAlert();
        
        // Highlight all invalid fields
        Object.keys(this.errors).forEach(fieldName => {
            const field = this.form.querySelector(`[name="${fieldName}"]`);
            if (field) {
                field.classList.add('is-invalid');
            }
        });
    }

    showErrorAlert() {
        // Remove existing error alerts
        const existingAlert = this.form.parentNode.querySelector('.alert-danger');
        if (existingAlert) {
            existingAlert.remove();
        }

        // Create error alert
        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4';
        alertDiv.innerHTML = `
            <div class="d-flex align-items-start">
                <div class="bg-danger rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                    <i class="fas fa-exclamation-triangle text-white"></i>
                </div>
                <div class="flex-grow-1">
                    <strong class="text-danger">Validation Errors Detected</strong>
                    <div class="text-muted mt-1">Please review and correct the following issues:</div>
                    <ul class="mb-0 mt-2 text-danger">
                        ${Object.values(this.errors).map(error => `<li class="mb-1">${error}</li>`).join('')}
                    </ul>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;

        this.form.parentNode.insertBefore(alertDiv, this.form);
    }

    scrollToFirstError() {
        const firstError = this.form.querySelector('.is-invalid');
        if (firstError) {
            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            firstError.focus();
        }
    }

    // Utility methods
    isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    isValidUrl(url) {
        try {
            new URL(url);
            return true;
        } catch {
            return false;
        }
    }

    formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    // Static method to handle server-side validation errors
    static handleServerErrors(errors) {
        Object.keys(errors).forEach(fieldName => {
            const field = document.querySelector(`[name="${fieldName}"]`);
            if (field) {
                const validator = new AdminFormValidator('form');
                validator.showFieldError(field, errors[fieldName][0]);
            }
        });
    }
}

// Validation rules for different admin forms
const AdminValidationRules = {
    // User Management
    userManagement: {
        username: {
            required: 'Username is required',
            string: 'Username must be text',
            max: 255,
            max_error: 'Username cannot exceed 255 characters'
        },
        email: {
            required: 'Email is required',
            email: 'Please enter a valid email address',
            max: 255,
            max_error: 'Email cannot exceed 255 characters'
        },
        password: {
            min: 6,
            min_error: 'Password must be at least 6 characters',
            confirmed: 'Password confirmation does not match'
        },
        telephone: {
            max: 20,
            max_error: 'Telephone number cannot exceed 20 characters'
        },
        role: {
            required: 'Role is required',
            string: 'Role must be text'
        }
    },

    // Success Stories
    successStories: {
        title: {
            required: 'Title is required',
            string: 'Title must be text',
            max: 255,
            max_error: 'Title cannot exceed 255 characters'
        },
        content: {
            required: 'Content is required',
            string: 'Content must be text'
        },
        author: {
            required: 'Author is required',
            string: 'Author must be text',
            max: 255,
            max_error: 'Author name cannot exceed 255 characters'
        },
        university: {
            required: 'University is required',
            string: 'University must be text',
            max: 255,
            max_error: 'University name cannot exceed 255 characters'
        },
        category: {
            required: 'Category is required',
            string: 'Category must be text'
        },
        images: {
            array: 'Images must be an array',
            min_items: 1,
            min_items_error: 'At least one image is required'
        },
        'images.*': {
            file: 'Each item must be a file',
            mimes: ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'],
            mimes_error: 'Images must be JPEG, PNG, JPG, or GIF format',
            max_size: 100 * 1024 * 1024, // 100MB
            max_size_error: 'Each image must be less than 100MB'
        }
    },

    // Partners
    partners: {
        name: {
            required: 'Partner name is required',
            string: 'Partner name must be text',
            max: 255,
            max_error: 'Partner name cannot exceed 255 characters'
        },
        acronym_name: {
            max: 100,
            max_error: 'Acronym name cannot exceed 100 characters'
        },
        link: {
            url: 'Please enter a valid URL'
        },
        image: {
            file: 'File must be an image',
            mimes: ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'],
            mimes_error: 'Image must be JPEG, PNG, JPG, or GIF format',
            max_size: 100 * 1024 * 1024, // 100MB
            max_size_error: 'Image must be less than 100MB'
        }
    },

    // Shortcut Links
    shortcutLinks: {
        link_name: {
            required: 'Link name is required',
            string: 'Link name must be text',
            max: 255,
            max_error: 'Link name cannot exceed 255 characters'
        },
        link_type: {
            required: 'Link type is required',
            string: 'Link type must be text'
        },
        link: {
            required_if: 'link_type,link',
            required_if_error: 'Web URL is required when link type is "link"',
            url: 'Please enter a valid web URL'
        },
        file: {
            required_if: 'link_type,file',
            required_if_error: 'File is required when link type is "file"',
            file: 'Must be a valid file',
            max_size: 100 * 1024 * 1024, // 100MB
            max_size_error: 'File must be less than 100MB'
        }
    },

    // FAQs
    faqs: {
        question: {
            required: 'Question is required',
            string: 'Question must be text',
            max: 255,
            max_error: 'Question cannot exceed 255 characters'
        },
        steps: {
            required: 'At least one step is required',
            array: 'Steps must be an array',
            min_items: 1,
            min_items_error: 'At least one step is required'
        },
        type: {
            required: 'Type is required',
            string: 'Type must be text'
        },
        qnstype: {
            required: 'Question type is required',
            string: 'Question type must be text'
        }
    },

    // Publications
    publications: {
        title: {
            required: 'Publication title is required',
            string: 'Title must be text',
            max: 255,
            max_error: 'Title cannot exceed 255 characters'
        },
        category_id: {
            required: 'Category is required',
            string: 'Category must be selected'
        },
        file: {
            required: 'Publication file is required',
            file: 'Must be a valid file',
            mimes: ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'],
            mimes_error: 'File must be PDF, DOC, DOCX, XLS, or XLSX format',
            max_size: 100 * 1024 * 1024, // 100MB
            max_size_error: 'File must be less than 100MB'
        },
        description: {
            string: 'Description must be text',
            max: 1000,
            max_error: 'Description cannot exceed 1000 characters'
        },
        is_active: {
            boolean: 'Active status must be true or false'
        }
    },

    // Categories
    categories: {
        name: {
            required: 'Category name is required',
            string: 'Category name must be text',
            max: 100,
            max_error: 'Category name cannot exceed 100 characters'
        },
        description: {
            string: 'Description must be text'
        },
        display_order: {
            string: 'Display order must be a number',
            min: 0,
            min_error: 'Display order cannot be negative'
        },
        is_active: {
            boolean: 'Active status must be true or false'
        }
    },

    // Events
    events: {
        title: {
            required: 'Event title is required',
            string: 'Event title must be text',
            max: 255,
            max_error: 'Event title cannot exceed 255 characters'
        },
        start_datetime: {
            required: 'Event start date and time is required',
            string: 'Event start date and time must be a valid date'
        },
        description: {
            string: 'Description must be text'
        },
        location: {
            string: 'Location must be text',
            max: 255,
            max_error: 'Location cannot exceed 255 characters'
        },
        all_day: {
            boolean: 'All day must be true or false'
        }
    },

    // Photo Gallery
    photoGallery: {
        name_of_event: {
            required: 'Event name is required',
            string: 'Event name must be text',
            max: 255,
            max_error: 'Event name cannot exceed 255 characters'
        },
        description: {
            required: 'Description is required',
            string: 'Description must be text',
            max: 255,
            max_error: 'Description cannot exceed 255 characters'
        }
    },

    // Photo Gallery Images
    galleryImages: {
        images: {
            required: 'At least one image is required',
            array: 'Images must be selected as a group',
            min_items: 1,
            min_items_error: 'At least one image is required'
        },
        'images.*': {
            required: 'Each image is required',
            file: 'Each file must be an image',
            mimes: ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'],
            mimes_error: 'Images must be JPEG, PNG, JPG, or GIF format',
            max_size: 100 * 1024 * 1024, // 100MB
            max_size_error: 'Each image must be less than 100MB'
        },
        'descriptions.*': {
            string: 'Description must be text',
            max: 1000,
            max_error: 'Description cannot exceed 1000 characters'
        }
    },

    // Photo Gallery Image Edit
    galleryImageEdit: {
        description: {
            string: 'Description must be text',
            max: 1000,
            max_error: 'Description cannot exceed 1000 characters'
        }
    },

    // Contact & Feedback
    contactFeedback: {
        name: {
            required: 'Name is required',
            string: 'Name must be text',
            max: 255,
            max_error: 'Name cannot exceed 255 characters'
        },
        email: {
            required: 'Email is required',
            email: 'Please enter a valid email address'
        },
        subject: {
            required: 'Subject is required',
            string: 'Subject must be text',
            max: 255,
            max_error: 'Subject cannot exceed 255 characters'
        },
        message: {
            required: 'Message is required',
            string: 'Message must be text'
        },
        phone: {
            max: 20,
            max_error: 'Phone number cannot exceed 20 characters'
        }
    },

    // News Publish
    newsPublish: {
        title: {
            required: 'News title is required',
            string: 'News title must be text',
            max: 255,
            max_error: 'News title cannot exceed 255 characters'
        },
        content: {
            string: 'News content must be text'
        },
        category: {
            required: 'News category is required',
            string: 'News category must be selected'
        },
        date_expire: {
            required: 'Expiry date is required',
            string: 'Expiry date must be a valid date'
        },
        front_image: {
            file: 'File must be an image',
            mimes: ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'],
            mimes_error: 'Image must be JPEG, PNG, JPG, or GIF format',
            max_size: 100 * 1024 * 1024, // 100MB
            max_size_error: 'Image must be less than 100MB'
        }
    },

    // Video Podcasts
    videoPodcasts: {
        name: {
            required: 'Video podcast name is required',
            string: 'Video name must be text',
            max: 255,
            max_error: 'Video name cannot exceed 255 characters'
        },
        link: {
            required: 'YouTube video link is required',
            url: 'Please enter a valid YouTube URL'
        },
        description: {
            string: 'Description must be text',
            max: 1000,
            max_error: 'Description cannot exceed 1000 characters'
        }
    },

    // Window Application
    windowApplication: {
        program_type: {
            required: 'Program type is required',
            array: 'Program type must be selected as a list',
            min_items: 1,
            min_items_error: 'At least one program type must be selected'
        },
        'program_type.*': {
            required: 'Each program type is required',
            string: 'Program type must be text',
            max: 255,
            max_error: 'Program type cannot exceed 255 characters'
        },
        window: {
            required: 'Application window is required',
            string: 'Application window must be text',
            max: 255,
            max_error: 'Application window cannot exceed 255 characters'
        },
        academic_year: {
            required: 'Academic year is required',
            string: 'Academic year must be text',
            max: 9,
            max_error: 'Academic year cannot exceed 9 characters'
        },
        description: {
            string: 'Description must be text'
        },
        starting_date: {
            date: 'Starting date must be a valid date'
        },
        ending_date: {
            date: 'Ending date must be a valid date'
        }
    },

            // Profile Update
        profileUpdate: {
            username: {
                required: 'Username is required',
                string: 'Username must be text',
                max: 255,
                max_error: 'Username cannot exceed 255 characters'
            },
            telephone: {
                required: false,
                string: 'Telephone number must be text',
                max: 20,
                max_error: 'Telephone number cannot exceed 20 characters'
            },
            old_password: {
                required: false,
                string: 'Current password must be text',
                required_with: ['password', 'password_confirmation'],
                required_with_error: 'Current password is required when changing password. All three password fields must be filled.'
            },
            password: {
                required: false,
                string: 'Password must be text',
                min: 6,
                min_error: 'Password must be at least 6 characters',
                required_with: ['old_password', 'password_confirmation'],
                required_with_error: 'New password is required when changing password. All three password fields must be filled.'
            },
            password_confirmation: {
                required: false,
                string: 'Password confirmation must be text',
                match: 'password',
                match_error: 'Password confirmation does not match',
                required_with: ['old_password', 'password'],
                required_with_error: 'Password confirmation is required when changing password. All three password fields must be filled.'
            },
            profile_image: {
                required: false,
                file: 'File must be an image',
                mimes: ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'],
                mimes_error: 'Profile image must be JPEG, PNG, JPG, or GIF format',
                max_size: 100 * 1024 * 1024, // 100MB
                max_size_error: 'Profile image must not exceed 100MB'
            }
        },
        
        // Calendar Events
        events: {
            title: {
                required: 'Event title is required',
                string: 'Event title must be text',
                max: 255,
                max_error: 'Event title cannot exceed 255 characters'
            },
            description: {
                required: false,
                string: 'Description must be text',
                max: 1000,
                max_error: 'Description cannot exceed 1000 characters'
            },
            location: {
                required: false,
                string: 'Location must be text',
                max: 255,
                max_error: 'Location cannot exceed 255 characters'
            },
            start_datetime: {
                required: 'Event start date and time is required',
                date: 'Start date must be a valid date',
                future: true,
                future_error: 'Event start date must be today or in the future'
            },
            all_day: {
                required: false,
                boolean: 'All day must be true or false'
            },
            timezone: {
                required: false,
                string: 'Timezone must be text',
                max: 50,
                max_error: 'Timezone cannot exceed 50 characters'
            },
            status: {
                required: false,
                in: ['active', 'inactive', 'cancelled'],
                in_error: 'Status must be active, inactive, or cancelled'
            }
        },
        
        // Calendar Events Update
        events_update: {
            title: {
                required: 'Event title is required',
                string: 'Event title must be text',
                max: 255,
                max_error: 'Event title cannot exceed 255 characters'
            },
            description: {
                required: false,
                string: 'Description must be text',
                max: 1000,
                max_error: 'Description cannot exceed 1000 characters'
            },
            location: {
                required: false,
                string: 'Location must be text',
                max: 255,
                max_error: 'Location cannot exceed 255 characters'
            },
            start_datetime: {
                required: 'Event start date and time is required',
                date: 'Start date must be a valid date'
            },
            all_day: {
                required: false,
                boolean: 'All day must be true or false'
            },
            timezone: {
                required: false,
                string: 'Timezone must be text',
                max: 50,
                max_error: 'Timezone cannot exceed 50 characters'
            },
            status: {
                required: false,
                in: ['active', 'inactive', 'cancelled'],
                in_error: 'Status must be active, inactive, or cancelled'
            }
        }
};

// Initialize validation for different admin forms
document.addEventListener('DOMContentLoaded', function() {
    // User Management Form
    if (document.querySelector('#userManagementForm')) {
        new AdminFormValidator('#userManagementForm', AdminValidationRules.userManagement);
    }

    // Success Stories Form
    if (document.querySelector('#successStoriesForm')) {
        new AdminFormValidator('#successStoriesForm', AdminValidationRules.successStories);
    }

    // Partners Form
    if (document.querySelector('#partnersForm')) {
        new AdminFormValidator('#partnersForm', AdminValidationRules.partners);
    }

    // Shortcut Links Form
    if (document.querySelector('#shortcutLinksForm')) {
        new AdminFormValidator('#shortcutLinksForm', AdminValidationRules.shortcutLinks);
    }

    // FAQs Form
    if (document.querySelector('#faqsForm')) {
        new AdminFormValidator('#faqsForm', AdminValidationRules.faqs);
    }

    // Publications Form
    if (document.querySelector('#publicationsForm')) {
        new AdminFormValidator('#publicationsForm', AdminValidationRules.publications);
    }

    // Categories Form
    if (document.querySelector('#categoriesForm')) {
        new AdminFormValidator('#categoriesForm', AdminValidationRules.categories);
    }

    // Events Form
    if (document.querySelector('#eventsForm')) {
        new AdminFormValidator('#eventsForm', AdminValidationRules.events);
    }

    // Photo Gallery Form
    if (document.querySelector('#photoGalleryForm')) {
        new AdminFormValidator('#photoGalleryForm', AdminValidationRules.photoGallery);
    }

    // Contact & Feedback Form
    if (document.querySelector('#contactFeedbackForm')) {
        new AdminFormValidator('#contactFeedbackForm', AdminValidationRules.contactFeedback);
    }

    // News Publish Form
    if (document.querySelector('#newsPublishForm')) {
        new AdminFormValidator('#newsPublishForm', AdminValidationRules.newsPublish);
    }

    // Video Podcasts Form
    if (document.querySelector('#videoPodcastsForm')) {
        new AdminFormValidator('#videoPodcastsForm', AdminValidationRules.videoPodcasts);
    }

    // Window Application Form
    if (document.querySelector('#windowApplicationForm')) {
        new AdminFormValidator('#windowApplicationForm', AdminValidationRules.windowApplication);
    }

            // Profile Update Form
        if (document.querySelector('#profileUpdateForm')) {
            new AdminFormValidator('#profileUpdateForm', AdminValidationRules.profileUpdate);
        }
        
        // Calendar Event Create Form
        if (document.querySelector('#calendarEventForm')) {
            new AdminFormValidator('#calendarEventForm', AdminValidationRules.events);
        }
        
        // Calendar Event Edit Form
        if (document.querySelector('#calendarEventEditForm')) {
            new AdminFormValidator('#calendarEventEditForm', AdminValidationRules.events_update);
        }

    // Generic admin form validation
    document.querySelectorAll('form[data-admin-validation]').forEach(form => {
        const formType = form.getAttribute('data-admin-validation');
        if (AdminValidationRules[formType]) {
            new AdminFormValidator(`#${form.id}`, AdminValidationRules[formType]);
        }
    });
});

// Export for use in other modules
window.AdminFormValidator = AdminFormValidator;
window.AdminValidationRules = AdminValidationRules;
