<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $fillable = ['training_program_id', 'user_id', 'rating', 'comments'];

    public function trainingProgram()
    {
        return $this->belongsTo(TrainingProgram::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
