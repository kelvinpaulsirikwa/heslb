<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ApplicationGuideline;

class ApplicationGuidelineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create sample application guidelines
        ApplicationGuideline::create([
            'title' => 'Application Guidelines and Criteria for Issuance of Loans and Grants',
            'academic_year' => '2024/2025',
            'description' => 'Comprehensive guidelines for student loan and grant applications for the 2024/2025 academic year.',
            'file_path' => null, // Will be set when actual file is uploaded
            'file_name' => null,
            'file_original_name' => null,
            'file_size' => null,
            'file_type' => null,
            'is_active' => true,
            'is_current' => true,
            'created_by' => 1, // Assuming admin user with ID 1
            'updated_by' => 1,
        ]);

        ApplicationGuideline::create([
            'title' => 'Application Guidelines and Criteria for Issuance of Loans and Grants',
            'academic_year' => '2023/2024',
            'description' => 'Previous year guidelines for reference purposes.',
            'file_path' => null,
            'file_name' => null,
            'file_original_name' => null,
            'file_size' => null,
            'file_type' => null,
            'is_active' => true,
            'is_current' => false,
            'created_by' => 1,
            'updated_by' => 1,
        ]);
    }
}
