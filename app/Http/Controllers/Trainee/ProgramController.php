<?php

namespace App\Http\Controllers\Trainee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Feedback;
use App\Models\TrainingProgram;

class ProgramController extends Controller
{
    public function index()
    {
        $programs = Auth::user()->programsAsTrainee()->with('trainer')->with(['feedbacks' => function($q) {
            $q->where('user_id', Auth::id());
        }])->get();

        return view('trainee.programs.index', compact('programs'));
    }

    public function submitFeedback(Request $request, $programId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comments' => 'nullable|string'
        ]);

        Feedback::updateOrCreate(
            ['training_program_id' => $programId, 'user_id' => Auth::id()],
            ['rating' => $request->rating, 'comments' => $request->comments]
        );

        return back()->with('success', 'Feedback submitted successfully!');
    }

    public function certificate(TrainingProgram $program)
    {
        $trainee = Auth::user();
        $pivot = $program->trainees()->where('user_id', $trainee->id)->first()?->pivot;
        
        if (!$pivot || !$pivot->is_present) {
            abort(403, 'You must be marked as present to receive a certificate.');
        }

        return view('trainee.programs.certificate', compact('program', 'trainee'));
    }
}
