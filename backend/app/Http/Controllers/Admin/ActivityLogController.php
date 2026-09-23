<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\BuildsAdminDataTables;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    use BuildsAdminDataTables;

    public function index()
    {
        return view('admin.activity.index', [
            'users' => User::query()->orderBy('name')->get(['id', 'name']),
            'subjectTypes' => ActivityLog::query()->whereNotNull('subject_type')->distinct()->orderBy('subject_type')->pluck('subject_type'),
        ]);
    }

    public function data(Request $request)
    {
        $query = ActivityLog::query()
            ->with('user:id,name')
            ->select(['id', 'user_id', 'action', 'subject_type', 'subject_id', 'description', 'changes', 'ip_address', 'created_at'])
            ->when($request->filled('user_id'), fn ($builder) => $builder->where('user_id', $request->integer('user_id')))
            ->when($request->filled('action'), fn ($builder) => $builder->where('action', $request->string('action')->toString()))
            ->when($request->filled('subject_type'), fn ($builder) => $builder->where('subject_type', $request->string('subject_type')->toString()));

        return $this->adminDataTable($query)
            ->addColumn('who', fn (ActivityLog $log) => $log->user?->name ?? 'Deleted user')
            ->editColumn('action', fn (ActivityLog $log) => $this->renderStatusBadge(
                str($log->action)->headline(),
                match ($log->action) {
                    'created' => 'active',
                    'deleted', 'archived' => 'muted',
                    default => 'role',
                },
            ))
            ->editColumn('description', fn (ActivityLog $log) => $log->description)
            ->addColumn('details', fn (ActivityLog $log) => $this->renderAdminPartial('admin.activity.partials.changes', compact('log')))
            ->editColumn('created_at', fn (ActivityLog $log) => $this->formatAdminDate($log->created_at, 'd M Y, h:i:s A'))
            ->rawColumns(['action', 'details'])
            ->toJson();
    }
}
