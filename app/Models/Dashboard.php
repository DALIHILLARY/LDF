<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AnimalHealthRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class Dashboard extends Model
{
    use HasFactory;
    

    
    //user activity chart
    public static function userMetrics(Request $request)
    {
        //user activity chart
        $filter = $request->input('filter', 'day'); // Default filter is day
        
        $startDate = now()->sub($filter, 1); // Adjust the start date based on the selected filter

        $userCounts = DB::table('admin_operation_log')
            ->where('created_at', '>=', $startDate)
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))
            ->select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d') as time"),
                DB::raw('COUNT(DISTINCT user_id) as user_count') // Count distinct user_ids for each day
            )
            ->get();

        return view('user_activity_chart', compact('userCounts', 'filter'));
    }

   
    //calendar events
    public function getCalendarEvents(Request $request)
    {
        // Fetch activities from the database
        $activities = FarmActivity::all();

        // Transform activities into the required format for FullCalendar
        $events = [];
        foreach ($activities as $activity) {
            $events[] = [
                'title' => $activity->name,
                'start' => $activity->scheduled_at->format('Y-m-d'),

            ];
        }

        return response()->json($events);
    }

    //function to get the totals
    public static function cards()
    {
        $data = [
            'total_farmers' => Farmer::count(),
            'pending_farmers' => Farmer::where('status', 'pending')->orWhere('status', null)->count(),
            'total_input_providers' => ServiceProvider::count(),
            'total_farms' => Farm::count(),
            'pending_input_providers' => ServiceProvider::where('status', 'pending')->orWhere('status', null)->count(),
            'pending_vets' => Vet::where('status', 'pending')->orWhere('status', null)->count(),
            'total_vets' => Vet::count(),
        ];

        return view('user_cards', ['data' => $data]);
    }

    
}
