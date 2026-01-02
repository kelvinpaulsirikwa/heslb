<?php

namespace App\Http\Controllers\AdminPages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\Userstable;
use App\Models\WindowApplication;
use Illuminate\Support\Facades\Auth;

class WindowApplicationController extends Controller
{
    // Show all applications, optionally filtered by extension type
    public function index(Request $request)
    {
        // Server-side permission check
        /** @var Userstable $user */
        $user = Auth::user();
        if (!$user || !$user->hasPermission('manage_applications')) {
            abort(403, 'You do not have permission to manage applications.');
        }
        
        $extensionType = $request->input('extension_type');
        
        $query = WindowApplication::with('user');

        if ($extensionType) {
            $query->where('extension_type', $extensionType);
        }
        
        // Filter by user if provided
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $applications = $query->latest()->get();

        return view('adminpages.dirishalausajili.showallwindows', compact('applications', 'extensionType'));
    }

    // Show form to create a new application
    public function create()
    {
        // Server-side permission check
        /** @var Userstable $user */
        $user = Auth::user();
        if (!$user || !$user->hasPermission('manage_applications')) {
            abort(403, 'You do not have permission to manage applications.');
        }
        
        $users = Userstable::all();
        return view('adminpages.dirishalausajili.create', compact('users'));
    }

    // Store a new application
public function store(Request $request)
{
    // Server-side permission check
    /** @var Userstable $user */
    $user = Auth::user();
    if (!$user || !$user->hasPermission('manage_applications')) {
        abort(403, 'You do not have permission to manage applications.');
    }
    
    try {
        $validatedData = \App\Services\AdminValidationService::validate($request, 'window_application');
    } catch (\Illuminate\Validation\ValidationException $e) {
        return redirect()->back()
            ->withErrors($e->errors())
            ->withInput();
    }

    $validatedData['user_id'] = auth()->id();
    $validatedData['submitted_at'] = now();

    // Convert array to string for program_type
    if (isset($validatedData['program_type']) && is_array($validatedData['program_type'])) {
        $validatedData['program_type'] = implode(',', $validatedData['program_type']);
    }


    WindowApplication::create($validatedData);

    return redirect()->route('admin.window_applications.index')
                     ->with('success', 'Application window created successfully.');
}


    // Show one application
    public function show($id)
    {
        // Server-side permission check
        /** @var Userstable $user */
        $user = Auth::user();
        if (!$user || !$user->hasPermission('manage_applications')) {
            abort(403, 'You do not have permission to manage applications.');
        }
        
        $application = WindowApplication::with('user')->findOrFail($id);
        return view('adminpages.dirishalausajili.show', compact('application'));
    }

    // Show form to edit an application
    public function edit($id)
    {
        // Server-side permission check
        /** @var Userstable $user */
        $user = Auth::user();
        if (!$user || !$user->hasPermission('manage_applications')) {
            abort(403, 'You do not have permission to manage applications.');
        }
        
        $application = WindowApplication::findOrFail($id);
        $users = Userstable::all();

        return view('adminpages.dirishalausajili.edit', compact('application', 'users'));
    }

    // Update an application
    public function update(Request $request, $id)
    {
        // Server-side permission check
        /** @var Userstable $user */
        $user = Auth::user();
        if (!$user || !$user->hasPermission('manage_applications')) {
            abort(403, 'You do not have permission to manage applications.');
        }
        
        $application = WindowApplication::findOrFail($id);

        try {
            $validatedData = \App\Services\AdminValidationService::validate($request, 'window_application');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        }

        $validatedData['user_id'] = auth()->id();
        
        // Convert array to string for program_type
        if (isset($validatedData['program_type']) && is_array($validatedData['program_type'])) {
            $validatedData['program_type'] = implode(',', $validatedData['program_type']);
        }

        $application->update($validatedData);

        return redirect()->route('admin.window_applications.index')->with('success', 'Application window updated successfully.');
    }

    // Delete an application
    public function destroy($id)
    {
        // Server-side permission check
        /** @var Userstable $user */
        $user = Auth::user();
        if (!$user || !$user->hasPermission('manage_applications')) {
            abort(403, 'You do not have permission to manage applications.');
        }
        
        $application = WindowApplication::findOrFail($id);
        $application->delete();

        return redirect()->route('admin.window_applications.index')->with('success', 'Application deleted.');
    }
}
