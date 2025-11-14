<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AdminValidationService
{
    /**
     * Validation rules for different admin forms
     */
    private static $validationRules = [
        'user_management' => [
            'username' => 'required|string|max:255|unique:userstable,username',
            'email' => 'required|email|max:255|unique:userstable,email',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'telephone' => 'nullable|string|max:20',
            'nida' => 'nullable|integer',
            'role' => 'required|string',
            'password' => 'nullable|min:6|confirmed',
        ],
        'user_management_update' => [
            'username' => 'required|string|max:255|unique:userstable,username',
            'email' => 'required|email|max:255|unique:userstable,email',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'telephone' => 'nullable|string|max:20',
            'nida' => 'nullable|integer',
            'role' => 'required|string',
            'password' => 'nullable|min:6|confirmed',
        ],
        'success_stories' => [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'author' => 'required|string|max:255',
            'university' => 'required|string|max:255',
            'category' => 'required|in:tuition,meals,books,research,special_faculty,field_work,special_needs,postgraduate',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120',
            'videos' => 'nullable|array|max:3',
            'videos.*' => 'file|mimes:mp4,avi,mov,wmv|max:10240',
        ],
        'partners' => [
            'name' => 'required|string|max:255',
            'acronym_name' => 'nullable|string|max:100',
            'link' => 'nullable|url',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ],
        'partners_update' => [
            'name' => 'required|string|max:255',
            'acronym_name' => 'nullable|string|max:100',
            'link' => 'nullable|url',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ],
        'shortcut_links' => [
            'link_name' => 'required|string|max:255',
            'link_type' => 'required|in:link,file',
            'link' => 'required_if:link_type,link|nullable|url',
            'file' => 'required_if:link_type,file|file|max:10240',
        ],
        'shortcut_links_update' => [
            'link_name' => 'required|string|max:255',
            'link_type' => 'required|in:link,file',
            'link' => 'required_if:link_type,link|nullable|url',
            'file' => 'nullable|file|max:10240',
        ],
        'faqs' => [
            'question' => 'required|string|max:255',
            'steps' => 'required|array|min:1',
            'steps.*' => 'required|string',
            'type' => 'required|in:loan_application,loan_repayment',
            'qnstype' => 'required|in:popular_questions,general_questions',
        ],
        'publications' => [
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ],
        'publications_update' => [
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
            'description' => 'nullable|string|max:1000',
            'publication_date' => 'nullable|date',
            'version' => 'nullable|string|max:50',
            'is_active' => 'boolean',
        ],
        'categories' => [
            'name' => 'required|string|max:100|unique:categories,name',
            'description' => 'nullable|string',
            'display_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ],
        'categories_update' => [
            'name' => 'required|string|max:100|unique:categories,name',
            'description' => 'nullable|string',
            'display_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ],
        'events' => [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'location' => 'nullable|string|max:255',
            'start_datetime' => 'required|date|after_or_equal:today',
            'all_day' => 'sometimes|boolean',
            'timezone' => 'nullable|string|max:50',
            'status' => 'sometimes|string|in:active,inactive,cancelled',
        ],
        'events_update' => [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'location' => 'nullable|string|max:255',
            'start_datetime' => 'required|date',
            'all_day' => 'sometimes|boolean',
            'timezone' => 'nullable|string|max:50',
            'status' => 'sometimes|string|in:active,inactive,cancelled',
        ],

        'photo_gallery' => [
            'name_of_event' => 'required|string|max:255|unique:taasisevent,name_of_event',
            'description' => 'required|string',
        ],
        'photo_gallery_update' => [
            'name_of_event' => 'required|string|max:255|unique:taasisevent,name_of_event',
            'description' => 'required|string',
        ],
        'photo_gallery_images' => [
            'images' => 'required|array|min:1',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
            'descriptions.*' => 'nullable|string',
        ],
        'photo_gallery_image_update' => [
            'description' => 'nullable|string',
        ],
        'contact_feedback' => [
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'phone' => 'nullable|string|max:20',
        ],
        'profile_update' => [
            'username' => 'required|string|max:255',
            'telephone' => 'nullable|string|max:20',
            'old_password' => 'nullable|string',
            'password' => 'nullable|string|min:6|confirmed',
            'password_confirmation' => 'nullable|string',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ],
        'auth_login' => [
            'email' => 'required|email',
            'password' => 'required',
        ],
        'user_stories_approve' => [
            'admin_notes' => 'nullable|string|max:1000',
        ],
        'user_stories_reject' => [
            'admin_notes' => 'required|string|max:1000',
        ],
        'loan_category' => [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'interest_rate' => 'required|numeric|min:0|max:100',
            'max_amount' => 'required|numeric|min:0',
            'min_amount' => 'required|numeric|min:0',
            'repayment_period' => 'required|integer|min:1',
        ],
        'window_application' => [
            'program_type' => 'required|array|min:1',
            'program_type.*' => 'required|string|max:255',
            'extension_type' => 'required|string|max:255',
            'academic_year' => 'required|string|max:9',
            'description' => 'nullable|string',
            'starting_date' => 'nullable|date',
            'ending_date' => 'nullable|date',
        ],
        'video_podcasts' => [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'link' => 'required|url',
        ],
        'news_publish' => [
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'category' => 'required|in:general news,successful stories',
            'date_expire' => 'required|date',
            'front_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ],
    ];

    /**
     * Custom error messages for validation
     */
    private static $customMessages = [
        'username.required' => 'Username is required',
        'username.max' => 'Username cannot exceed 255 characters',
        'username.unique' => 'This username is already taken. Please choose a different one.',
        'email.required' => 'Email address is required',
        'email.email' => 'Please enter a valid email address',
        'email.unique' => 'This email address is already taken. Please use a different email.',
        'email.max' => 'Email address cannot exceed 255 characters',
        'password.required' => 'Password is required',
        'password.min' => 'Password must be at least 6 characters',
        'password.confirmed' => 'Password confirmation does not match',
        'role.required' => 'User role is required',
        'telephone.max' => 'Telephone number cannot exceed 20 characters',
        'profile_image.image' => 'Profile image must be an image file',
        'profile_image.mimes' => 'Profile image must be JPEG, PNG, JPG, or GIF format',
        'profile_image.max' => 'Profile image must be less than 5MB',
        'title.required' => 'Title is required',
        'title.max' => 'Title cannot exceed 255 characters',
        'content.required' => 'Content is required',
        'author.required' => 'Author name is required',
        'author.max' => 'Author name cannot exceed 255 characters',
        'university.required' => 'University name is required',
        'university.max' => 'University name cannot exceed 255 characters',
        'category.required' => 'Category is required',
        'category.in' => 'Please select a valid category',
        'images.array' => 'Images must be selected as a group',
        'images.max' => 'Maximum 5 images allowed',
        'images.*.image' => 'Each file must be an image',
        'images.*.mimes' => 'Images must be JPEG, PNG, JPG, or GIF format',
        'images.*.max' => 'Each image must be less than 5MB',
        'videos.array' => 'Videos must be selected as a group',
        'videos.max' => 'Maximum 3 videos allowed',
        'videos.*.file' => 'Each file must be a valid video file',
        'videos.*.mimes' => 'Videos must be MP4, AVI, MOV, or WMV format',
        'videos.*.max' => 'Each video must be less than 10MB',
        'name.required' => 'Name is required',
        'name.max' => 'Name cannot exceed 255 characters',
        'acronym_name.max' => 'Acronym name cannot exceed 100 characters',
        'link.url' => 'Please enter a valid URL',
        'image.image' => 'File must be an image',
        'image.mimes' => 'Image must be JPEG, PNG, JPG, or GIF format',
        'image.max' => 'Image must be less than 5MB',
        'link_name.required' => 'Link name is required',
        'link_name.max' => 'Link name cannot exceed 255 characters',
        'link_type.required' => 'Link type is required',
        'link_type.in' => 'Link type must be either "link" or "file"',
        'shortcut_links.link.required_if' => 'Web URL is required when link type is "link"',
        'shortcut_links.link.url' => 'Please enter a valid web URL',
        'shortcut_links.file.required_if' => 'File is required when link type is "file"',
        'shortcut_links.file.file' => 'Must be a valid file',
        'shortcut_links.file.max' => 'File must be less than 10MB',
        'question.required' => 'Question is required',
        'question.max' => 'Question cannot exceed 255 characters',
        'faqs.steps.required' => 'At least one step is required',
        'steps.array' => 'Steps must be provided as a list',
        'steps.min' => 'At least one step is required',
        'steps.*.required' => 'Each step is required',
        'type.required' => 'Type is required',
        'type.in' => 'Type must be either "loan_application" or "loan_repayment"',
        'qnstype.required' => 'Question type is required',
        'qnstype.in' => 'Question type must be either "popular_questions" or "general_questions"',
        'category_id.required' => 'Category is required',
        'category_id.exists' => 'Selected category does not exist',
        'publications.file.mimes' => 'File must be PDF, DOC, or DOCX format',
        'publications.file.max' => 'File must be less than 10MB',
        'name.unique' => 'A category with this name already exists',
        'categories.name.max' => 'Category name cannot exceed 100 characters',
        'display_order.integer' => 'Display order must be a number',
        'display_order.min' => 'Display order cannot be negative',
        'events.title.required' => 'Event title is required',
        'events.title.max' => 'Event title cannot exceed 255 characters',
        'events.location.max' => 'Event location cannot exceed 255 characters',
        'events.start_datetime.required' => 'Event start date and time is required',
        'events.start_datetime.date' => 'Event start date and time must be a valid date',
        'events.start_datetime.after_or_equal' => 'Event start date and time must be today or in the future',
        'event_name.required' => 'Event name is required',
        'event_name.max' => 'Event name cannot exceed 255 characters',
        'event_name.unique' => 'An event with this name already exists',
        'event_date.required' => 'Event date is required',
        'event_date.date' => 'Event date must be a valid date',
        'gallery.description.required' => 'Description is required',
        'gallery.description.max' => 'Description cannot exceed 255 characters',
        'name_of_event.required' => 'Event name is required',
        'name_of_event.max' => 'Event name cannot exceed 255 characters',
        'name_of_event.unique' => 'An event with this name already exists',
        'gallery.images.required' => 'At least one image is required',
        'gallery.images.array' => 'Images must be selected as a group',
        'gallery.images.min' => 'At least one image is required',
        'gallery.images.*.required' => 'Each image is required',
        'gallery.images.*.image' => 'Each file must be an image',
        'gallery.images.*.mimes' => 'Images must be JPEG, PNG, JPG, or GIF format',
        'gallery.images.*.max' => 'Each image must be less than 5MB',
        'descriptions.*.max' => 'Image description cannot exceed 255 characters',
        'contact.subject.required' => 'Subject is required',
        'contact.subject.max' => 'Subject cannot exceed 255 characters',
        'contact.message.required' => 'Message is required',
        'phone.max' => 'Phone number cannot exceed 20 characters',
        'opening_date.required' => 'Application opening date is required',
        'opening_date.after' => 'Application opening date must be in the future',
        'closing_date.required' => 'Application closing date is required',
        'closing_date.after' => 'Application closing date must be after opening date',
        'requirements.required' => 'At least one requirement is needed',
        'requirements.array' => 'Requirements must be provided as a list',
        'requirements.min' => 'At least one requirement is needed',
        'program_type.required' => 'Program type is required',
        'program_type.array' => 'Program type must be selected as a list',
        'program_type.min' => 'At least one program type must be selected',
        'program_type.*.required' => 'Each program type is required',
        'program_type.*.string' => 'Program type must be text',
        'program_type.*.max' => 'Program type cannot exceed 255 characters',
        'extension_type.required' => 'Extension type is required',
        'extension_type.max' => 'Extension type cannot exceed 255 characters',
        'academic_year.required' => 'Academic year is required',
        'academic_year.max' => 'Academic year cannot exceed 9 characters',
        'starting_date.date' => 'Starting date must be a valid date',
        'ending_date.date' => 'Ending date must be a valid date',
        'requirements.*.required' => 'Each requirement is required',
        'documents.*.file' => 'Each document must be a valid file',
        'documents.*.mimes' => 'Documents must be PDF, DOC, or DOCX format',
        'documents.*.max' => 'Each document must be less than 10MB',
        'video_url.required' => 'Video URL is required',
        'video_url.url' => 'Please enter a valid video URL',
        'thumbnail.image' => 'Thumbnail must be an image file',
        'thumbnail.mimes' => 'Thumbnail must be JPEG, PNG, JPG, or GIF format',
        'thumbnail.max' => 'Thumbnail must be less than 5MB',
        'duration.max' => 'Duration cannot exceed 50 characters',
        'video_podcasts.category.required' => 'Category is required',
        'video_podcasts.category.max' => 'Category cannot exceed 100 characters',
        'date_expire.required' => 'Expiry date is required',
        'date_expire.date' => 'Expiry date must be a valid date',
        'front_image.image' => 'Front image must be an image file',
        'front_image.mimes' => 'Front image must be JPEG, PNG, JPG, or GIF format',
        'front_image.max' => 'Front image must be less than 5MB',
        'publish_date.required' => 'Publish date is required',
        'publish_date.date' => 'Publish date must be a valid date',
        'publication.is_featured.boolean' => 'Featured status must be true or false',
        'admin_notes.required' => 'Admin notes are required for rejection',
        'admin_notes.max' => 'Admin notes cannot exceed 1000 characters',
        'interest_rate.required' => 'Interest rate is required',
        'interest_rate.numeric' => 'Interest rate must be a number',
        'interest_rate.min' => 'Interest rate cannot be negative',
        'interest_rate.max' => 'Interest rate cannot exceed 100%',
        'max_amount.required' => 'Maximum amount is required',
        'max_amount.numeric' => 'Maximum amount must be a number',
        'max_amount.min' => 'Maximum amount cannot be negative',
        'min_amount.required' => 'Minimum amount is required',
        'min_amount.numeric' => 'Minimum amount must be a number',
        'min_amount.min' => 'Minimum amount cannot be negative',
        'repayment_period.required' => 'Repayment period is required',
        'repayment_period.integer' => 'Repayment period must be a whole number',
        'repayment_period.min' => 'Repayment period must be at least 1 month',
        'profile_update.username.required' => 'Username is required.',
        'profile_update.username.unique' => 'This username is already taken.',
        'profile_update.telephone.required' => 'Telephone number is required.',
        'old_password.required_with' => 'Current password is required when changing password. All three password fields must be filled.',
        'password.required_with' => 'New password is required when changing password. All three password fields must be filled.',
        'password_confirmation.required_with' => 'Password confirmation is required when changing password. All three password fields must be filled.',
        'old_password.string' => 'Current password must be text.',
        'password_confirmation.string' => 'Password confirmation must be text.',
        'profile_update.password.min' => 'Password must be at least 6 characters.',
        'profile_update.password.confirmed' => 'Password confirmation does not match.',
        'profile_update.profile_image.image' => 'The file must be an image.',
        'profile_update.profile_image.mimes' => 'Profile image must be a JPEG, PNG, JPG, or GIF file.',
        'profile_update.profile_image.max' => 'Profile image must not exceed 5MB.',

    ];

    /**
     * Validate request data based on form type
     */
    public static function validate(Request $request, string $formType, array $additionalRules = [])
    {
        if (!isset(self::$validationRules[$formType])) {
            throw new \InvalidArgumentException("Unknown form type: {$formType}");
        }

        $rules = self::$validationRules[$formType];
        
        // Merge additional rules
        if (!empty($additionalRules)) {
            $rules = array_merge($rules, $additionalRules);
        }

        // Handle unique validation with ID exclusion for updates
        $rules = self::handleUniqueValidation($rules, $request, $formType);

        $validator = Validator::make($request->all(), $rules, self::$customMessages);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    /**
     * Handle unique validation with ID exclusion for updates
     */
    private static function handleUniqueValidation(array $rules, Request $request, string $formType)
    {
        $updateTypes = [
            'user_management_update' => 'userstable',
            'partners_update' => 'partners',
            'categories_update' => 'categories',
            'publications_update' => 'publications',
            'events_update' => 'events',
            'photo_gallery_update' => 'taasisevent',
        ];

        if (isset($updateTypes[$formType])) {
            $table = $updateTypes[$formType];
            $id = $request->route('user') ?? $request->route('partner') ?? $request->route('category') ?? $request->route('publication') ?? $request->route('event') ?? $request->route('taasisevent') ?? $request->route('calenderevent');
            
            if ($id) {
                foreach ($rules as $field => $rule) {
                    if (strpos($rule, 'unique:') === 0) {
                        $rules[$field] = str_replace('unique:' . $table, 'unique:' . $table . ',' . $field . ',' . $id, $rule);
                    }
                }
            }
        }

        return $rules;
    }

    /**
     * Get validation rules for a specific form type
     */
    public static function getRules(string $formType): array
    {
        return self::$validationRules[$formType] ?? [];
    }

    /**
     * Get custom error messages
     */
    public static function getCustomMessages(): array
    {
        return self::$customMessages;
    }

    /**
     * Validate file uploads with additional checks
     */
    public static function validateFileUploads(Request $request, array $fileFields): array
    {
        $errors = [];

        foreach ($fileFields as $field => $config) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                
                if (is_array($file)) {
                    // Handle multiple files
                    foreach ($file as $index => $singleFile) {
                        $fileErrors = self::validateSingleFile($singleFile, $config, $field . '.' . $index);
                        if (!empty($fileErrors)) {
                            $errors = array_merge($errors, $fileErrors);
                        }
                    }
                } else {
                    // Handle single file
                    $fileErrors = self::validateSingleFile($file, $config, $field);
                    if (!empty($fileErrors)) {
                        $errors = array_merge($errors, $fileErrors);
                    }
                }
            }
        }

        return $errors;
    }

    /**
     * Validate a single file upload
     */
    private static function validateSingleFile($file, array $config, string $field): array
    {
        $errors = [];

        if (!$file->isValid()) {
            $errors[$field] = ['File upload failed. Please try again.'];
            return $errors;
        }

        // Check file size
        if (isset($config['max_size']) && $file->getSize() > $config['max_size']) {
            $errors[$field] = ['File size must be less than ' . self::formatFileSize($config['max_size'])];
        }

        // Check file type
        if (isset($config['mimes'])) {
            $mimeType = $file->getMimeType();
            if (!in_array($mimeType, $config['mimes'])) {
                $errors[$field] = ['File type not allowed. Allowed types: ' . implode(', ', $config['mimes'])];
            }
        }

        // Check file extension
        if (isset($config['extensions'])) {
            $extension = strtolower($file->getClientOriginalExtension());
            if (!in_array($extension, $config['extensions'])) {
                $errors[$field] = ['File extension not allowed. Allowed extensions: ' . implode(', ', $config['extensions'])];
            }
        }

        return $errors;
    }

    /**
     * Format file size for display
     */
    private static function formatFileSize(int $bytes): string
    {
        if ($bytes === 0) return '0 Bytes';
        
        $k = 1024;
        $sizes = ['Bytes', 'KB', 'MB', 'GB'];
        $i = floor(log($bytes) / log($k));
        
        return round($bytes / pow($k, $i), 2) . ' ' . $sizes[$i];
    }

    /**
     * Custom validation rules
     */
    public static function customRules(): array
    {
        return [
            'phone' => 'regex:/^[\+]?[1-9][\d]{0,15}$/',
            'nida' => 'regex:/^[0-9]{20}$/',
            'url' => 'regex:/^https?:\/\/.+/',
            'date_future' => 'after:today',
            'date_range' => 'after:start_date',
            'file_count' => 'max:5',
            'image_dimensions' => 'dimensions:min_width=100,min_height=100',
        ];
    }

    /**
     * Get validation documentation for frontend
     */
    public static function getValidationDocumentation(): array
    {
        $documentation = [];
        
        foreach (self::$validationRules as $formType => $rules) {
            $documentation[$formType] = [
                'title' => ucwords(str_replace('_', ' ', $formType)),
                'rules' => []
            ];
            
            foreach ($rules as $field => $rule) {
                $documentation[$formType]['rules'][$field] = self::parseValidationRule($rule);
            }
        }
        
        return $documentation;
    }

    /**
     * Parse validation rule for documentation
     */
    private static function parseValidationRule(string $rule): array
    {
        $parsed = [];
        $parts = explode('|', $rule);
        
        foreach ($parts as $part) {
            if (strpos($part, ':') !== false) {
                [$ruleName, $ruleValue] = explode(':', $part, 2);
                $parsed[$ruleName] = $ruleValue;
            } else {
                $parsed[$part] = true;
            }
        }
        
        return $parsed;
    }
}
