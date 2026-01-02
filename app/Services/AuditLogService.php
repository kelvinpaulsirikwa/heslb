<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class AuditLogService
{
    /**
     * Log an administrative action
     *
     * @param string $action The action performed (create, update, delete, view, etc.)
     * @param string $resourceType The type of resource (User, News, Publication, etc.)
     * @param int|null $resourceId The ID of the resource
     * @param array|null $oldValues The old values before change
     * @param array|null $newValues The new values after change
     * @param string|null $description Custom description
     * @return AuditLog
     */
    public static function log(
        string $action,
        string $resourceType,
        ?int $resourceId = null,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?string $description = null
    ): AuditLog {
        $request = request();
        
        $logDescription = $description ?? self::generateDescription($action, $resourceType, $resourceId, $oldValues, $newValues);

        return AuditLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'resource_type' => $resourceType,
            'resource_id' => $resourceId,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'description' => $logDescription,
            'route' => $request->route()?->getName(),
            'method' => $request->method(),
        ]);
    }

    /**
     * Generate a description for the audit log
     */
    private static function generateDescription(
        string $action,
        string $resourceType,
        ?int $resourceId,
        ?array $oldValues,
        ?array $newValues
    ): string {
        $user = Auth::user();
        $username = $user ? $user->username : 'System';
        
        $actionMap = [
            'create' => 'created',
            'update' => 'updated',
            'delete' => 'deleted',
            'view' => 'viewed',
            'login' => 'logged in',
            'logout' => 'logged out',
            'block' => 'blocked',
            'unblock' => 'unblocked',
            'reset_password' => 'reset password for',
            'approve' => 'approved',
            'reject' => 'rejected',
        ];

        $actionVerb = $actionMap[$action] ?? $action;
        
        $description = "{$username} {$actionVerb} {$resourceType}";
        
        if ($resourceId) {
            $description .= " (ID: {$resourceId})";
        }

        if ($action === 'update' && $oldValues && $newValues) {
            $changes = [];
            foreach ($newValues as $key => $value) {
                if (isset($oldValues[$key]) && $oldValues[$key] !== $value) {
                    $changes[] = "{$key}: '{$oldValues[$key]}' → '{$value}'";
                }
            }
            if (!empty($changes)) {
                $description .= " - Changes: " . implode(', ', $changes);
            }
        }

        return $description;
    }
}

