<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use App\Models\Item;

class ActivityLogController extends Controller
{
    public function showActivity(Item $item)
    {
        // Mengambil data log untuk item tertentu
        $activities = Activity::where('subject_id', $item->id)->where('subject_type', get_class($item))->orderBy('created_at', 'desc')->get();

        // Mengembalikan partial view untuk modal
        return view('activity_logs.partial', compact('item', 'activities'));
    }
}
