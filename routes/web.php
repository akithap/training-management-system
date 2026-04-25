<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ProgramController as AdminProgramController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Trainer\ScheduleController as TrainerScheduleController;
use App\Http\Controllers\Trainee\ProgramController as TraineeProgramController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $role = auth()->user()->role;
    if ($role === 'admin') return redirect()->route('admin.programs.index');
    if ($role === 'trainer') return redirect()->route('trainer.schedules.index');
    return redirect()->route('trainee.programs.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('programs', AdminProgramController::class);
    Route::resource('users', AdminUserController::class)->except(['show']);
    Route::get('reviews', [App\Http\Controllers\Admin\ReviewController::class, 'index'])->name('admin.reviews.index');
});

Route::middleware(['auth', 'role:trainer'])->prefix('trainer')->name('trainer.')->group(function () {
    Route::get('schedules', [TrainerScheduleController::class, 'index'])->name('schedules.index');
    Route::post('schedules/{program}/attendance/{trainee}', [TrainerScheduleController::class, 'markAttendance'])->name('schedules.attendance');
    Route::post('schedules/{program}/complete', [TrainerScheduleController::class, 'markCompleted'])->name('schedules.complete');
});

Route::middleware(['auth', 'role:trainee'])->prefix('trainee')->name('trainee.')->group(function () {
    Route::get('programs', [TraineeProgramController::class, 'index'])->name('programs.index');
    Route::post('programs/{program}/feedback', [TraineeProgramController::class, 'submitFeedback'])->name('programs.feedback');
    Route::get('programs/{program}/certificate', [TraineeProgramController::class, 'certificate'])->name('programs.certificate');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('/downloads/programs/{filename}', function ($filename) {
    $path = 'programs/' . $filename;
    if (Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
        return Illuminate\Support\Facades\Storage::disk('public')->download($path);
    }
    abort(404);
})->name('downloads.programs')->middleware('auth');
