<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContactUsController extends Controller
{public function showContactByRegion()
{
    $contacts = [
        'dar es salaam' => [
            'address' => "HESLB House, 1 Kilimo Street, TAZARA Area, Mandela Road,",
            'phone1' => '+255 736 665 533',
            'phone2' => '+255 736 665 533',
            'email' => 'info@heslb.go.tz',
            'website' => null,
            'latitude' => -6.8516,
            'longitude' => 39.2894,
            'postal_address' => 'P.O. Box 76068 Dar es Salaam ',
        ],
        'zanzibar' => [
            'address' => "Michenzani Mall Building, Michenzani Area, Wing A ,5th Floor, Hall 501",
            'phone1' => '+255 779 321 414',
            'phone2' => '+255 779 321 414',
            'email' => 'zanzibar@heslb.go.tz',
            'website' => 'www.heslb.go.tz',
            'latitude' => -6.16483,
            'longitude' => 39.19963,
            'postal_address' => 'P.O. Box 900 Zanzibar',
        ],
        
        'mtwara' => [
            'address' => "NHC Raha Leo Complex 2nd Floor Plot No. 217 Block H, ",
            'phone1' => '+255 733 068 171',
            'phone2' => '+255 628 068 171',
            'email' => 'mtwara@heslb.go.tz',
            'website' => 'www.heslb.go.tz',
            'latitude' => -10.2679,
            'longitude' => 40.1834,
            'postal_address' => 'P.O. Box 969 Mtwara ',
        ],
        'mbeya' => [
            'address' => "CAG Building, Mbalizi Road, ",
            'phone1' => '+255 738 131311',
            'phone2' => '+255 738 131310',
            'email' => 'mbeya@heslb.go.tz',
            'website' => 'www.heslb.go.tz',
            'latitude' => -8.9090,
            'longitude' => 33.4590,
            'postal_address' => 'P.O. Box 319 Mbeya',
        ],
        'dodoma' => [
            'address' => "PSSSF House 4th Floor, Makole Road Plot No 4 & 5, Block W Uhindini",
            'phone1' => '+255 758 067577',
            'phone2' => '+255 739 067577',
            'email' => 'Dodoma@heslb.go.tz',
            'website' => 'www.heslb.go.tz',
            'latitude' => -6.1630,
            'longitude' => 35.7516,
            'postal_address' => 'P.O. Box 984 Dodoma ',
        ],
        'arusha' => [
            'address' => "NSSF Mafao House, First Floor Corridor Area, Old Moshi Road",
            'phone1' => '+255 27 2520128',
            'phone2' => '+255 624 100011 & +255 739 102016',
            'email' => 'arusha@heslb.go.tz',
            'website' => 'www.heslb.go.tz',
            'latitude' => -3.3869,
            'longitude' => 36.6822,
            'postal_address' => 'P.O. Box 2712 Arusha',
        ],
        'mwanza' => [
            'address' => "PSSSF Plaza Building, 2nd Floor, Front Wing, Kenyatta Road",
            'phone1' => '+255 738 153 661', 
            'email' => 'mwanza@heslb.go.tz',
            'website' => 'www.heslb.go.tz',
            'latitude' => -2.5164,
            'longitude' => 32.9178,
            'postal_address' => 'P.O. Box 3051 Mwanza',
        ],
    ];

    return view('contactus.contactus', [
        'contacts' => $contacts,
    ]);
}


//upload complains

public function complainsform(Request $r){
    $preSelectedType = $r->get('type', '');
    return view('contactus.contactusform', compact('preSelectedType'));
}


//posting data 
public function store(Request $request)
{
    $validated = $request->validate([
        'first_name'       => 'nullable|string|max:255',
        'last_name'        => 'nullable|string|max:255',
        'email'            => 'nullable|email|max:255',
        'phone'            => 'nullable|string|max:20',
        'gender'           => 'nullable|string',
        'contact_type'     => 'required|string',
        'message'          => 'required|string|min:10',
        'date_of_incident' => 'nullable|date|before_or_equal:today',
        'location'         => 'nullable|string|max:255',
        'image'            => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        'consent'          => 'accepted',
        'image_captcha_selection' => 'required|string',
    ], [
        'message.min' => 'Message must be at least 10 characters long.',
    ]);

    // Validate image captcha selection
    $expectedCats = collect((array) session('image_captcha_cats', []))->map(fn($v)=>(int)$v)->sort()->values();
    $selected = collect(json_decode($request->input('image_captcha_selection'), true) ?: [])->map(fn($v)=>(int)$v)->sort()->values();
    
    // Check if arrays are equal by comparing their values
    $arraysEqual = $expectedCats->count() === $selected->count() && 
                   $expectedCats->diff($selected)->isEmpty() && 
                   $selected->diff($expectedCats)->isEmpty();
    
    if ($expectedCats->count() !== 3 || !$selected->count() || !$arraysEqual) {
        return response()->json([
            'message' => 'Human verification failed. Please try again.',
            'errors' => ['image_captcha_selection' => ['Please select the correct tiles.']]
        ], 422);
    }

    // Convert checkbox to boolean
    $validated['consent'] = $request->has('consent');

    // Handle image upload
    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
        $imagePath = $image->storeAs('contact_images', $imageName, 'public');
        $validated['image'] = $imagePath;
    }

    // Use database transaction to prevent race conditions
    return DB::transaction(function () use ($validated) {
        // Check for recent duplicate submissions (within last 30 seconds) - after validation
        $recentSubmission = Contact::where('email', $validated['email'])
            ->where('contact_type', $validated['contact_type'])
            ->where('message', $validated['message'])
            ->where('created_at', '>=', now()->subSeconds(30))
            ->first();

        if ($recentSubmission) {
            return response()->json([
                'message' => 'A similar message was recently submitted. Please wait a moment before submitting again.',
            ], 429);
        }

        // If it's a success story, redirect to the user stories system
        if ($validated['contact_type'] === 'success_stories') {
            // Save to DB
            Contact::create($validated);
            
            // Return JSON with redirect information
            return response()->json([
                'message' => 'Your success story has been submitted! It will be reviewed and may be featured in our user stories section.',
                'redirect' => route('story.getallstories')
            ]);
        }

        // Save to DB for other contact types
        Contact::create($validated);

        // Return JSON instead of redirect
        return response()->json([
            'message' => 'Your message has been sent successfully!',
        ]);
    });
}

}
