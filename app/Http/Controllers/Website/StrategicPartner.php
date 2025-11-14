<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;

class StrategicPartner extends Controller
{
   
    public function strategicPartners()
    {
        // Fetch all partners from database (without user relationship)
        $strategicPartners = Partner::orderBy('name')
            ->get()
            ->map(function ($partner) {
                return [
                    'id' => $partner->id,
                    'name' => $partner->name,
                    'acronym_name' => $partner->acronym_name,
                    'logo' => $partner->image_path 
                        ? asset('images/storage/partner_image/' . $partner->image_path) 
                        : null,
                    'url' => $partner->link,
                    'created_at' => $partner->created_at,
                ];
            });

        return view('aboutus.partials.startegicpartner', [
            'pageTitle' => 'Strategic Partners',
            'strategicPartners' => $strategicPartners
        ]);
    }
}
