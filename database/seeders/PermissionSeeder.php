<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\RolePermission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            // User Management
            ['name' => 'manage_users', 'display_name' => 'Manage Users', 'description' => 'Create, edit, delete, and manage user accounts', 'category' => 'user_management'],
            ['name' => 'view_users', 'display_name' => 'View Users', 'description' => 'View user list and details', 'category' => 'user_management'],
            ['name' => 'create_users', 'display_name' => 'Create Users', 'description' => 'Create new user accounts', 'category' => 'user_management'],
            ['name' => 'edit_users', 'display_name' => 'Edit Users', 'description' => 'Edit existing user accounts', 'category' => 'user_management'],
            ['name' => 'delete_users', 'display_name' => 'Delete Users', 'description' => 'Delete user accounts', 'category' => 'user_management'],
            ['name' => 'reset_user_password', 'display_name' => 'Reset User Password', 'description' => 'Reset passwords for other users', 'category' => 'user_management'],
            
            // News Management
            ['name' => 'manage_news', 'display_name' => 'Manage News', 'description' => 'Create, edit, delete, and publish news articles', 'category' => 'content_management'],
            ['name' => 'view_news', 'display_name' => 'View News', 'description' => 'View news articles', 'category' => 'content_management'],
            
            // Publications Management
            ['name' => 'manage_publications', 'display_name' => 'Manage Publications', 'description' => 'Create, edit, delete, and manage publications', 'category' => 'content_management'],
            ['name' => 'manage_publication_categories', 'display_name' => 'Manage Publication Categories', 'description' => 'Create and manage publication categories', 'category' => 'content_management'],
            
            // Partners Management
            ['name' => 'manage_partners', 'display_name' => 'Manage Partners', 'description' => 'Create, edit, delete, and manage strategic partners', 'category' => 'content_management'],
            
            // FAQs Management
            ['name' => 'manage_faqs', 'display_name' => 'Manage FAQs', 'description' => 'Create, edit, delete, and manage FAQs', 'category' => 'content_management'],
            ['name' => 'manage_loan_application_faqs', 'display_name' => 'Manage Loan Application FAQs', 'description' => 'Manage loan application FAQs', 'category' => 'content_management'],
            ['name' => 'manage_loan_repayment_faqs', 'display_name' => 'Manage Loan Repayment FAQs', 'description' => 'Manage loan repayment FAQs', 'category' => 'content_management'],
            
            // Feedback Management
            ['name' => 'manage_feedback', 'display_name' => 'Manage Feedback', 'description' => 'View, respond to, and manage user feedback', 'category' => 'user_management'],
            ['name' => 'view_feedback', 'display_name' => 'View Feedback', 'description' => 'View user feedback', 'category' => 'user_management'],
            
            // Window Applications
            ['name' => 'manage_applications', 'display_name' => 'Manage Applications', 'description' => 'Create, edit, delete, and manage window applications', 'category' => 'content_management'],
            
            // Events/Photo Galleries
            ['name' => 'manage_events', 'display_name' => 'Manage Events', 'description' => 'Create, edit, delete, and manage events and photo galleries', 'category' => 'content_management'],
            
            // Video Podcasts
            ['name' => 'manage_video_podcasts', 'display_name' => 'Manage Video Podcasts', 'description' => 'Create, edit, delete, and manage video podcasts', 'category' => 'content_management'],
            
            // Shortcut Links
            ['name' => 'manage_shortcut_links', 'display_name' => 'Manage Shortcut Links', 'description' => 'Create, edit, delete, and manage shortcut links', 'category' => 'content_management'],
            
            // Application Guidelines
            ['name' => 'manage_application_guidelines', 'display_name' => 'Manage Application Guidelines', 'description' => 'Create, edit, delete, and manage application guidelines', 'category' => 'content_management'],
            
            // Board of Directors
            ['name' => 'manage_board_directors', 'display_name' => 'Manage Board of Directors', 'description' => 'Create, edit, delete, and manage board of directors', 'category' => 'content_management'],
            
            // Executive Directors
            ['name' => 'manage_executive_directors', 'display_name' => 'Manage Executive Directors', 'description' => 'Create, edit, delete, and manage executive directors', 'category' => 'content_management'],
            
            // Scholarships
            ['name' => 'manage_scholarships', 'display_name' => 'Manage Scholarships', 'description' => 'Create, edit, delete, and manage scholarships', 'category' => 'content_management'],
            
            // User Stories
            ['name' => 'manage_user_stories', 'display_name' => 'Manage User Stories', 'description' => 'Approve, reject, and manage user success stories', 'category' => 'content_management'],
            
            // Validation Documentation
            ['name' => 'view_validation_documentation', 'display_name' => 'View Validation Documentation', 'description' => 'View validation documentation', 'category' => 'content_management'],
            
            // Analytics & Dashboard
            ['name' => 'view_analytics', 'display_name' => 'View Analytics', 'description' => 'View dashboard and analytics', 'category' => 'system'],
            ['name' => 'view_audit_logs', 'display_name' => 'View Audit Logs', 'description' => 'View system audit logs', 'category' => 'system'],
            
            // System Settings (for future use)
            ['name' => 'manage_settings', 'display_name' => 'Manage Settings', 'description' => 'Manage system settings', 'category' => 'system'],
        ];

        // Create permissions (using Spatie's structure with guard_name)
        foreach ($permissions as $permissionData) {
            Permission::updateOrCreate(
                [
                    'name' => $permissionData['name'],
                    'guard_name' => 'web' // Default guard for web users
                ],
                array_merge($permissionData, ['guard_name' => 'web'])
            );
        }

        // Assign all permissions to admin role
        $adminPermissions = Permission::where('guard_name', 'web')->get();
        foreach ($adminPermissions as $permission) {
            RolePermission::updateOrCreate(
                [
                    'role' => 'admin',
                    'permission_id' => $permission->id
                ],
                [
                    'role' => 'admin',
                    'permission_id' => $permission->id
                ]
            );
        }

        $this->command->info('Permissions created and assigned to admin role successfully!');
    }
}
