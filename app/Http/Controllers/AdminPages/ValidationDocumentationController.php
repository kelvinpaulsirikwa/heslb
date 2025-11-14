<?php

namespace App\Http\Controllers\AdminPages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ValidationDocumentationController extends Controller
{
    /**
     * Display the validation documentation page
     */
    public function index()
    {
        $validationRules = [
            'user_management' => [
                'title' => 'User Management',
                'rules' => [
                    'username' => [
                        'required' => 'Required field',
                        'string' => 'Must be text',
                        'max:255' => 'Maximum 255 characters'
                    ],
                    'email' => [
                        'required' => 'Required field',
                        'email' => 'Must be valid email format',
                        'unique:userstable,email' => 'Email must be unique'
                    ],
                    'profile_image' => [
                        'nullable' => 'Optional field',
                        'image' => 'Must be an image file',
                        'max:102400' => 'Maximum 100MB file size'
                    ],
                    'telephone' => [
                        'nullable' => 'Optional field',
                        'string' => 'Must be text',
                        'max:20' => 'Maximum 20 characters'
                    ],
                    'nida' => [
                        'nullable' => 'Optional field',
                        'integer' => 'Must be a number'
                    ],
                    'role' => [
                        'required' => 'Required field',
                        'string' => 'Must be text'
                    ],
                    'password' => [
                        'nullable' => 'Optional field',
                        'min:6' => 'Minimum 6 characters',
                        'confirmed' => 'Must match password confirmation'
                    ]
                ]
            ],
            'success_stories' => [
                'title' => 'Success Stories',
                'rules' => [
                    'title' => [
                        'required' => 'Required field',
                        'string' => 'Must be text',
                        'max:255' => 'Maximum 255 characters'
                    ],
                    'content' => [
                        'required' => 'Required field',
                        'string' => 'Must be text'
                    ],
                    'author' => [
                        'required' => 'Required field',
                        'string' => 'Must be text',
                        'max:255' => 'Maximum 255 characters'
                    ],
                    'university' => [
                        'required' => 'Required field',
                        'string' => 'Must be text',
                        'max:255' => 'Maximum 255 characters'
                    ],
                    'category' => [
                        'required' => 'Required field',
                        'in:tuition,meals,books,research,special_faculty,field_work,special_needs,postgraduate' => 'Must be one of: Tuition Fee Loans, Meals & Accommodation, Books & Stationery, Research, Special Faculty, Field Work, Special Needs Support, Postgraduate Studies'
                    ],
                    'images' => [
                        'nullable' => 'Optional field',
                        'array' => 'Must be an array of files',
                        'max:5' => 'Maximum 5 images'
                    ],
                    'images.*' => [
                        'image' => 'Each file must be an image',
                        'mimes:jpeg,png,jpg,gif' => 'Supported formats: JPEG, PNG, JPG, GIF',
                        'max:102400' => 'Each image maximum 100MB'
                    ],
                    'videos' => [
                        'nullable' => 'Optional field',
                        'array' => 'Must be an array of files',
                        'max:3' => 'Maximum 3 videos'
                    ],
                    'videos.*' => [
                        'file' => 'Each file must be a valid file',
                        'mimes:mp4,avi,mov,wmv' => 'Supported formats: MP4, AVI, MOV, WMV',
                        'max:10240' => 'Each video maximum 10MB'
                    ]
                ]
            ],
            'partners' => [
                'title' => 'Strategic Partners',
                'rules' => [
                    'name' => [
                        'required' => 'Required field',
                        'string' => 'Must be text',
                        'max:255' => 'Maximum 255 characters'
                    ],
                    'acronym_name' => [
                        'nullable' => 'Optional field',
                        'string' => 'Must be text',
                        'max:100' => 'Maximum 100 characters'
                    ],
                    'link' => [
                        'nullable' => 'Optional field',
                        'url' => 'Must be valid URL format'
                    ],
                    'image' => [
                        'nullable' => 'Optional field',
                        'image' => 'Must be an image file',
                        'mimes:jpeg,png,jpg,gif' => 'Supported formats: JPEG, PNG, JPG, GIF',
                        'max:102400' => 'Maximum 100MB file size'
                    ]
                ]
            ],
            'shortcut_links' => [
                'title' => 'Shortcut Links',
                'rules' => [
                    'link_name' => [
                        'required' => 'Required field',
                        'string' => 'Must be text',
                        'max:255' => 'Maximum 255 characters'
                    ],
                    'link_type' => [
                        'required' => 'Required field',
                        'in:link,file' => 'Must be either "link" or "file"'
                    ],
                    'link' => [
                        'required' => 'Required when link_type is "link"',
                        'url' => 'Must be valid URL format'
                    ],
                    'file' => [
                        'required' => 'Required when link_type is "file"',
                        'file' => 'Must be a valid file',
                        'max:102400' => 'Maximum 100MB file size'
                    ]
                ]
            ],
            'faqs' => [
                'title' => 'Frequently Asked Questions',
                'rules' => [
                    'question' => [
                        'required' => 'Required field',
                        'string' => 'Must be text',
                        'max:255' => 'Maximum 255 characters'
                    ],
                    'steps' => [
                        'required' => 'Required field',
                        'array' => 'Must be an array',
                        'min:1' => 'At least 1 step required'
                    ],
                    'steps.*' => [
                        'required' => 'Each step is required',
                        'string' => 'Each step must be text'
                    ],
                    'type' => [
                        'required' => 'Required field',
                        'in:loan_application,loan_repayment' => 'Must be either "loan_application" or "loan_repayment"'
                    ],
                    'qnstype' => [
                        'required' => 'Required field',
                        'in:popular_questions,general_questions' => 'Must be either "popular_questions" or "general_questions"'
                    ]
                ]
            ],
            'publications' => [
                'title' => 'Publications',
                'rules' => [
                    'title' => [
                        'required' => 'Required field',
                        'string' => 'Must be text',
                        'max:255' => 'Maximum 255 characters'
                    ],
                    'content' => [
                        'required' => 'Required field',
                        'string' => 'Must be text'
                    ],
                    'category_id' => [
                        'required' => 'Required field',
                        'exists:categories,id' => 'Category must exist'
                    ],
                    'image' => [
                        'nullable' => 'Optional field',
                        'image' => 'Must be an image file',
                        'mimes:jpeg,png,jpg,gif' => 'Supported formats: JPEG, PNG, JPG, GIF',
                        'max:102400' => 'Maximum 100MB file size'
                    ],
                    'file' => [
                        'nullable' => 'Optional field',
                        'file' => 'Must be a valid file',
                        'mimes:pdf,doc,docx' => 'Supported formats: PDF, DOC, DOCX',
                        'max:102400' => 'Maximum 100MB file size'
                    ]
                ]
            ],
            'categories' => [
                'title' => 'Publication Categories',
                'rules' => [
                    'name' => [
                        'required' => 'Required field',
                        'string' => 'Must be text',
                        'max:255' => 'Maximum 255 characters',
                        'unique:categories,name' => 'Category name must be unique'
                    ],
                    'description' => [
                        'nullable' => 'Optional field',
                        'string' => 'Must be text'
                    ]
                ]
            ],
            'events' => [
                'title' => 'Events',
                'rules' => [
                    'event_name' => [
                        'required' => 'Required field',
                        'string' => 'Must be text',
                        'max:255' => 'Maximum 255 characters',
                        'unique:events,event_name' => 'Event name must be unique'
                    ],
                    'event_date' => [
                        'required' => 'Required field',
                        'date' => 'Must be valid date format'
                    ],
                    'description' => [
                        'required' => 'Required field',
                        'string' => 'Must be text'
                    ],
                    'image' => [
                        'nullable' => 'Optional field',
                        'image' => 'Must be an image file',
                        'mimes:jpeg,png,jpg,gif' => 'Supported formats: JPEG, PNG, JPG, GIF',
                        'max:102400' => 'Maximum 100MB file size'
                    ]
                ]
            ],
            'photo_gallery' => [
                'title' => 'Photo Gallery',
                'rules' => [
                    'folder_name' => [
                        'required' => 'Required field',
                        'string' => 'Must be text',
                        'max:255' => 'Maximum 255 characters',
                        'unique:photo_folders,folder_name' => 'Folder name must be unique'
                    ],
                    'description' => [
                        'nullable' => 'Optional field',
                        'string' => 'Must be text'
                    ],
                    'images' => [
                        'required' => 'Required field',
                        'array' => 'Must be an array of files',
                        'min:1' => 'At least 1 image required'
                    ],
                    'images.*' => [
                        'image' => 'Each file must be an image',
                        'mimes:jpeg,png,jpg,gif' => 'Supported formats: JPEG, PNG, JPG, GIF',
                        'max:102400' => 'Each image maximum 100MB'
                    ]
                ]
            ],
            'video_podcasts' => [
                'title' => 'Video Podcasts',
                'rules' => [
                    'title' => [
                        'required' => 'Required field',
                        'string' => 'Must be text',
                        'max:255' => 'Maximum 255 characters'
                    ],
                    'description' => [
                        'required' => 'Required field',
                        'string' => 'Must be text'
                    ],
                    'video_url' => [
                        'required' => 'Required field',
                        'url' => 'Must be valid URL format'
                    ],
                    'thumbnail' => [
                        'nullable' => 'Optional field',
                        'image' => 'Must be an image file',
                        'mimes:jpeg,png,jpg,gif' => 'Supported formats: JPEG, PNG, JPG, GIF',
                        'max:102400' => 'Maximum 100MB file size'
                    ]
                ]
            ],
            'window_applications' => [
                'title' => 'Window Applications',
                'rules' => [
                    'program_type' => [
                        'required' => 'Required field',
                        'array' => 'Must be an array',
                        'min:1' => 'At least 1 program type required'
                    ],
                    'program_type.*' => [
                        'string' => 'Each program type must be text',
                        'max:255' => 'Each program type maximum 255 characters'
                    ],
                    'window' => [
                        'required' => 'Required field',
                        'string' => 'Must be text',
                        'max:255' => 'Maximum 255 characters'
                    ],
                    'academic_year' => [
                        'required' => 'Required field',
                        'string' => 'Must be text',
                        'max:9' => 'Maximum 9 characters'
                    ],
                    'starting_date' => [
                        'nullable' => 'Optional field',
                        'date' => 'Must be valid date format'
                    ],
                    'ending_date' => [
                        'nullable' => 'Optional field',
                        'date' => 'Must be valid date format'
                    ],
                    'description' => [
                        'nullable' => 'Optional field',
                        'string' => 'Must be text'
                    ]
                ]
            ],
            'contact_feedback' => [
                'title' => 'Contact & Feedback',
                'rules' => [
                    'name' => [
                        'required' => 'Required field',
                        'string' => 'Must be text',
                        'max:255' => 'Maximum 255 characters'
                    ],
                    'email' => [
                        'required' => 'Required field',
                        'email' => 'Must be valid email format'
                    ],
                    'subject' => [
                        'required' => 'Required field',
                        'string' => 'Must be text',
                        'max:255' => 'Maximum 255 characters'
                    ],
                    'message' => [
                        'required' => 'Required field',
                        'string' => 'Must be text'
                    ],
                    'phone' => [
                        'nullable' => 'Optional field',
                        'string' => 'Must be text',
                        'max:20' => 'Maximum 20 characters'
                    ]
                ]
            ]
        ];

        return view('adminpages.validation-documentation.index', compact('validationRules'));
    }
}
