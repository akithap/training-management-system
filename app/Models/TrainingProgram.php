<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingProgram extends Model
{
    protected $fillable = ['title', 'training_area', 'venue', 'schedule_datetime', 'end_datetime', 'trainer_id', 'file_path', 'is_completed'];

    protected $casts = [
        'schedule_datetime' => 'datetime',
        'end_datetime' => 'datetime',
        'is_completed' => 'boolean',
    ];

    public function trainer()
    {
        return $this->belongsTo(User::class, 'trainer_id');
    }

    public function trainees()
    {
        return $this->belongsToMany(User::class, 'program_user')->withPivot('is_present');
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }
}
