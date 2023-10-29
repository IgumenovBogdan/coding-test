<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserTasksCountResource;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class StatiscticsController extends Controller
{
    public function statistics()
    {
        return view('tasks.statistics');
    }

    public function index(): JsonResponse
    {
        $lastWeekCompleted = Task::whereDate('completed_at', '>=', now()->startOfWeek())
            ->whereDate('completed_at', '<', now()->startOfWeek()->addWeek())
            ->count();
        $lastMonthCreated = Task::whereDate('created_at', '>=', now()->firstOfMonth())
            ->whereDate('created_at', '<', now()->firstOfMonth()->addMonth())
            ->count();

        //todo: need pagination
        $users = User::withCount('tasks')->get();

        return response()->json([
            'lastWeekCompleted' => $lastWeekCompleted,
            'lastMonthCreated' => $lastMonthCreated,
            'usersTasks' => UserTasksCountResource::collection($users)
        ]);
    }
}
