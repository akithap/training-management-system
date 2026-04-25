<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TrainingProgram;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProgramController extends Controller
{
    public function index()
    {
        $programs = TrainingProgram::with('trainer')->latest()->get();
        $totalPrograms = TrainingProgram::count();
        $totalTrainees = User::where('role', 'trainee')->count();
        
        // 1. Trainer Performance Leaderboard
        $trainerPerformance = User::where('role', 'trainer')->with(['programsAsTrainer.feedbacks'])->get()->map(function($trainer) {
            $feedbacks = $trainer->programsAsTrainer->flatMap->feedbacks;
            $avg = $feedbacks->avg('rating');
            return [
                'name' => $trainer->name,
                'rating' => $avg ? round($avg, 2) : 0
            ];
        })->filter(fn($t) => $t['rating'] > 0)->sortByDesc('rating')->values();

        // 3. Program Satisfaction Scores
        $programSatisfaction = TrainingProgram::with('feedbacks')->get()->map(function($program) {
            $avg = $program->feedbacks->avg('rating');
            return [
                'title' => $program->title,
                'rating' => $avg ? round($avg, 2) : 0
            ];
        })->filter(fn($p) => $p['rating'] > 0)->sortByDesc('rating')->take(10)->values();

        // 4. 7-Day Attendance Rate (Engagement per Workshop)
        $sevenDaysAgo = now()->subDays(7);
        $engagementData = TrainingProgram::with('trainees')
            ->where('schedule_datetime', '>=', $sevenDaysAgo)
            ->where('schedule_datetime', '<=', now())
            ->get()
            ->map(function($program) {
                $enrolled = $program->trainees->count();
                $present = $program->trainees->filter(fn($t) => $t->pivot->is_present)->count();
                return [
                    'title' => $program->title,
                    'enrolled' => $enrolled,
                    'present' => $present,
                    'absent' => $enrolled - $present
                ];
            })->values();

        return view('admin.programs.index', compact('programs', 'totalPrograms', 'totalTrainees', 'trainerPerformance', 'programSatisfaction', 'engagementData'));
    }

    public function create()
    {
        $trainers = User::where('role', 'trainer')->get();
        $trainees = User::where('role', 'trainee')->get();
        return view('admin.programs.create', compact('trainers', 'trainees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'training_area' => 'required|string|max:255',
            'venue' => 'required|string|max:255',
            'schedule_datetime' => 'required|date',
            'trainer_id' => 'required|exists:users,id',
            'file_upload' => 'nullable|file|mimes:pdf,doc,docx,zip|max:5120',
            'trainees' => 'nullable|array',
            'trainees.*' => 'exists:users,id'
        ]);

        $filePath = null;
        if ($request->hasFile('file_upload')) {
            $filePath = $request->file('file_upload')->store('programs', 'public');
        }

        $program = TrainingProgram::create([
            'title' => $validated['title'],
            'training_area' => $validated['training_area'],
            'venue' => $validated['venue'],
            'schedule_datetime' => $validated['schedule_datetime'],
            'trainer_id' => $validated['trainer_id'],
            'file_path' => $filePath,
        ]);

        if (!empty($validated['trainees'])) {
            $program->trainees()->attach($validated['trainees']);
        }

        return redirect()->route('admin.programs.index')->with('success', 'Training Program created successfully!');
    }
}
