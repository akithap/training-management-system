<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\TrainingProgram;
use App\Models\User;

class ScheduleController extends Controller
{
    public function index()
    {
        $programs = Auth::user()->programsAsTrainer()->with(['trainees', 'feedbacks.user'])->get();
        
        $upcomingCount = $programs->where('schedule_datetime', '>=', now())->count();
        $totalFeedbacks = $programs->flatMap->feedbacks;
        $avgRating = $totalFeedbacks->avg('rating') ?: 0;
        
        return view('trainer.schedules.index', compact('programs', 'upcomingCount', 'avgRating'));
    }

    public function markAttendance(Request $request, TrainingProgram $program, User $trainee)
    {
        if ($program->trainer_id !== Auth::id()) abort(403);

        $isPresent = $request->boolean('is_present');
        $program->trainees()->updateExistingPivot($trainee->id, ['is_present' => $isPresent]);

        $status = $isPresent ? 'Present' : 'Absent';
        return back()->with('success', "Marked {$trainee->name} as {$status}.");
    }
}
