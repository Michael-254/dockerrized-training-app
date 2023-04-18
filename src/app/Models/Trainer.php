<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trainer extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'type' => 'array',
    ];

    public function trainings()
    {
        return $this->belongsToJson(TrainingRequest::class, 'trainers->trainer_ids');
    }
}
