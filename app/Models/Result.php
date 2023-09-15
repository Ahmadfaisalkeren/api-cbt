<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory;

    protected $table = 'results';
    protected $fillable = [
        'student_id',
        'exam_id',
        'score',
        'submitted_at'
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id', 'id');
    }

    public function examResult()
    {
        return $this->belongsTo(Exam::class, 'exam_id', 'id');
    }
}
