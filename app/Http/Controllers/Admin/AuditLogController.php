<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::with('user')
            ->when($request->action, fn($q) => $q->where('action', $request->action))
            ->latest();

        $logs = $query->paginate(20);

        return view('admin.audit-logs.index', compact('logs'));
    }
}