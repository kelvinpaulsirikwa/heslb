<?php

namespace App\Http\Controllers\AdminPages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class AuditLogController extends Controller
{
    /**
     * Display a listing of audit logs.
     */
    public function index(Request $request)
    {
        // Check permission
        if (!Auth::user()->hasPermission('view_audit_logs')) {
            abort(403, 'You do not have permission to view audit logs.');
        }

        $query = AuditLog::with('user')->orderBy('created_at', 'desc');

        // Filter by action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filter by resource type
        if ($request->filled('resource_type')) {
            $query->where('resource_type', $request->resource_type);
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search in description
        if ($request->filled('search')) {
            $query->where('description', 'like', '%' . $request->search . '%');
        }

        $auditLogs = $query->paginate(50);

        // Get unique values for filters
        $actions = AuditLog::distinct()->pluck('action')->sort();
        $resourceTypes = AuditLog::distinct()->pluck('resource_type')->sort();
        $users = \App\Models\Userstable::whereIn('id', AuditLog::distinct()->pluck('user_id'))->get();

        return view('adminpages.auditlogs.index', compact('auditLogs', 'actions', 'resourceTypes', 'users'));
    }

    /**
     * Display the specified audit log.
     */
    public function show(AuditLog $auditLog)
    {
        // Check permission
        if (!Auth::user()->hasPermission('view_audit_logs')) {
            abort(403, 'You do not have permission to view audit logs.');
        }

        $auditLog->load('user');

        return view('adminpages.auditlogs.show', compact('auditLog'));
    }
}
