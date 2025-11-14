<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Userstable;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get the first user (seeder user) to use as posted_by
        $seederUser = Userstable::first();
        $postedBy = $seederUser ? $seederUser->id : 1;

        // Define the publication categories in the specified order
        $categories = [
            [
                'name' => 'HESLB Act & Amendments',
                'description' => 'Official HESLB Act and its amendments',
                'display_order' => 1,
            ],
            [
                'name' => 'National Higher Education Policy',
                'description' => 'National policies related to higher education',
                'display_order' => 2,
            ],
            [
                'name' => 'Education Policy',
                'description' => 'General education policies and guidelines',
                'display_order' => 3,
            ],
            [
                'name' => 'HESLB Strategic Plan',
                'description' => 'Strategic planning documents for HESLB',
                'display_order' => 4,
            ],
            [
                'name' => 'Client Service Charter',
                'description' => 'Service standards and commitments to clients',
                'display_order' => 5,
            ],
            [
                'name' => 'Complaints Handling Policy',
                'description' => 'Policies and procedures for handling complaints',
                'display_order' => 6,
            ],
            [
                'name' => 'HESLB Annual Reports and Financial Statements',
                'description' => 'Annual reports and financial statements',
                'display_order' => 7,
            ],
            [
                'name' => 'Guidelines',
                'description' => 'General guidelines and procedures',
                'display_order' => 8,
            ],
            [
                'name' => 'HESLB 20th Anniversary',
                'description' => 'Special publications for HESLB 20th anniversary',
                'display_order' => 9,
            ],
            [
                'name' => 'HESLB Newsletters',
                'description' => 'Regular newsletters and updates from HESLB',
                'display_order' => 10,
            ],
        ];

        // Create each category
        foreach ($categories as $categoryData) {
            Category::create([
                'name' => $categoryData['name'],
                'slug' => Str::slug($categoryData['name']),
                'description' => $categoryData['description'],
                'display_order' => $categoryData['display_order'],
                'posted_by' => $postedBy,
                'is_active' => true,
            ]);
        }

        $this->command->info('Publication categories seeded successfully!');
    }
}
