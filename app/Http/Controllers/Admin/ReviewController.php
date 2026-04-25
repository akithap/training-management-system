<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TrainingProgram;

class ReviewController extends Controller
{
    public function index()
    {
        $programs = TrainingProgram::with(['trainer', 'feedbacks.user'])
            ->where('is_completed', true)
            ->orderBy('schedule_datetime', 'desc')
            ->get();
            
        return view('admin.reviews.index', compact('programs'));
    }
}
