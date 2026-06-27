<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminAuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuditLogController extends Controller
{
    public function index(Request $request): View
    {
        $query = AdminAuditLog::with('admin')->recent();

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }
        if ($request->filled('admin_id')) {
            $query->where('admin_id', $request->admin_id);
        }
        if ($request->filled('target_type')) {
            $query->where('target_type', $request->target_type);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs   = $query->paginate(30)->withQueryString();
        $admins = User::where('is_admin', true)->get();
        $actions = [
            'user_activated', 'user_deactivated', 'score_override',
            'prediction_edited', 'prediction_points_override',
            'scoring_rule_created', 'scoring_rule_updated',
            'bulk_activate', 'bulk_deactivate', 'bulk_score_adjust',
            'recalculate_scores',
        ];

        return view('admin.audit-log.index', compact('logs', 'admins', 'actions'));
    }
}
