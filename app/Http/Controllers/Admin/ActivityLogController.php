<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\UserActivityService;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    protected UserActivityService $activityService;

    public function __construct(UserActivityService $activityService)
    {
        $this->middleware(['auth', 'role:admin']);
        $this->activityService = $activityService;
    }

    /**
     * Display activity log
     */
    public function index(Request $request)
    {
        $query = Activity::with(['causer', 'subject'])
            ->orderBy('created_at', 'desc');

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('causer_id', $request->user_id);
        }

        // Filter by activity type
        if ($request->filled('activity_type')) {
            $query->where('properties->activity_type', $request->activity_type);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search
        if ($request->filled('search')) {
            $query->where('description', 'like', '%' . $request->search . '%');
        }

        $activities = $query->paginate(50);

        $activityTypes = Activity::select('properties->activity_type as type')
            ->whereNotNull('properties->activity_type')
            ->distinct()
            ->pluck('type')
            ->filter()
            ->sort()
            ->values();

        return view('admin.activity-log.index', compact('activities', 'activityTypes'));
    }

    /**
     * Export activity log to CSV
     */
    public function export(Request $request)
    {
        $csv = $this->activityService->exportToCSV();

        $filename = 'activity_log_' . now()->format('Y-m-d_His') . '.csv';

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Get activity statistics
     */
    public function stats()
    {
        $stats = [
            'total_activities' => Activity::count(),
            'today' => Activity::whereDate('created_at', today())->count(),
            'this_week' => Activity::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'this_month' => Activity::whereMonth('created_at', now()->month)->count(),
            'by_type' => Activity::select('properties->activity_type as type')
                ->selectRaw('count(*) as count')
                ->whereNotNull('properties->activity_type')
                ->groupBy('properties->activity_type')
                ->get()
                ->pluck('count', 'type'),
        ];

        return response()->json($stats);
    }
}
