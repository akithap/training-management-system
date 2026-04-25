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
        
        $upcomingCount = $programs->where('is_completed', false)->count();
        $totalFeedbacks = $programs->flatMap->feedbacks;
        $avgRating = $totalFeedbacks->avg('rating') ?: 0;
        
        return view('trainer.schedules.index', compact('programs', 'upcomingCount', 'avgRating'));
    }

    public function markAttendance(Request $request, TrainingProgram $program, User $trainee)
    {
        if ($program->trainer_id !== Auth::id()) abort(403);
        
        if ($program->schedule_datetime > now()) {
            return back()->with('error', 'Attendance locked until the scheduled start time.');
        }

        $isPresent = $request->boolean('is_present');
        $program->trainees()->updateExistingPivot($trainee->id, ['is_present' => $isPresent]);

        $status = $isPresent ? 'Present' : 'Absent';
        return back()->with('success', "Marked {$trainee->name} as {$status}.");
    }

    public function markCompleted(Request $request, TrainingProgram $program)
    {
        if ($program->trainer_id !== Auth::id()) abort(403);

        if ($program->schedule_datetime > now()) {
            return back()->with('error', 'You cannot mark a program as completed before its scheduled start time.');
        }

        $program->update(['is_completed' => true]);
        
        return back()->with('success', "Program '{$program->title}' has been permanently completed.");
    }
}
