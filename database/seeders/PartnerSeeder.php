<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Partner;
use App\Models\Userstable;
use Illuminate\Support\Facades\File;

class PartnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get the first admin user for posted_by field
        $adminUser = Userstable::where('role', 'admin')->first();
        $postedBy = $adminUser ? $adminUser->id : 1; // Fallback to ID 1 if no admin found

        // Partner data with image mapping
        $partners = [
            [
                'acronym_name' => 'TCU',
                'name' => 'Tanzania Commission for Universities',
                'link' => 'https://www.tcu.go.tz/',
                'image_file' => 'tcu.png'
            ],
            [
                'acronym_name' => 'NECTA',
                'name' => 'National Examinations Council of Tanzania',
                'link' => 'https://www.necta.go.tz/',
                'image_file' => 'necta.png'
            ],
            [
                'acronym_name' => 'NACTVET',
                'name' => 'National Council for Technical Education',
                'link' => 'https://www.nacte.go.tz/',
                'image_file' => 'nacte.png'
            ],
            [
                'acronym_name' => 'MoE',
                'name' => 'Ministry of Education, Science and Technology',
                'link' => 'http://www.moe.go.tz/sw/',
                'image_file' => 'moe.png'
            ],
            [
                'acronym_name' => 'RITA',
                'name' => 'Registration, Insolvency and Trusteeship Agency',
                'link' => 'https://www.rita.go.tz/',
                'image_file' => 'rita.png'
            ],
            [
                'acronym_name' => 'ZHESLB',
                'name' => 'Zanzibar Higher Education Loans Board',
                'link' => 'http://www.zhelb.go.tz/',
                'image_file' => 'zhelb.png'
            ],
            [
                'acronym_name' => 'AAHEFA',
                'name' => 'Association of African Higher Education Financing Agencies',
                'link' => 'https://www.aahefa.org/',
                'image_file' => 'aahefa.png'
            ],
            [
                'acronym_name' => 'TRO',
                'name' => 'Tanzania Research Organization',
                'link' => 'https://www.tro.go.tz/#',
                'image_file' => 'tro.png'
            ],
            [
                'acronym_name' => 'TASAF',
                'name' => 'Tanzania Social Action Fund',
                'link' => 'https://www.tasaf.go.tz/',
                'image_file' => 'TASAF_logo.jpg'
            ],
            [
                'acronym_name' => 'SATF',
                'name' => 'Social Action Trust Fund',
                'link' => 'https://satf.or.tz/',
                'image_file' => 'SATF_Logo.png'
            ],
             [
                'acronym_name' => 'TRA',
                'name' => 'Tanzania Revenue Authority',
                'link' => 'https://www.tra.go.tz/',
                'image_file' => 'tra.png'
            ],
             [
                'acronym_name' => 'NIDA',
                'name' => 'National Identification Authority',
                'link' => 'https://nida.go.tz//',
                'image_file' => 'nida.png'
            ]
        ];

        // Create partners directory in storage if it doesn't exist
        $storagePartnerPath = storage_path('app/public/partner_image');
        if (!File::exists($storagePartnerPath)) {
            File::makeDirectory($storagePartnerPath, 0755, true);
        }

        // Seed each partner
        foreach ($partners as $partnerData) {
            $imagePath = null;
            
            // Check if image file exists in seed-files and copy it to storage
            $seedImagePath = database_path('seed-files/partners/' . $partnerData['image_file']);
            if (File::exists($seedImagePath)) {
                $imagePath = $partnerData['image_file'];
                
                // Copy image to storage
                $destinationPath = $storagePartnerPath . '/' . $partnerData['image_file'];
                if (!File::exists($destinationPath)) {
                    File::copy($seedImagePath, $destinationPath);
                }
            }

            // Create partner record
            Partner::create([
                'acronym_name' => $partnerData['acronym_name'],
                'name' => $partnerData['name'],
                'link' => $partnerData['link'],
                'image_path' => $imagePath,
                'posted_by' => $postedBy,
            ]);
        }

        $this->command->info('Partners seeded successfully!');
    }
}
