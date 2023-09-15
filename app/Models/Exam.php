<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $table = 'exams';
    protected $fillable = [
        'title',
        'duration',
        'teacher_id',
        'class_id',
        'time_start',
        'time_end'
    ];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function studyClass()
    {
        return $this->belongsTo(StudyClass::class, 'class_id');
    }
}
