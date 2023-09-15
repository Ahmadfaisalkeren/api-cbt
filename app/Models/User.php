<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'password_confirmation',
        'role_id',
        'class_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function exams()
    {
        return $this->hasMany(Exam::class, 'teacher_id', 'id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function studyClass()
    {
        return $this->belongsTo(StudyClass::class, 'class_id');
    }

    // public function classmates()
    // {
    //     return $this->hasMany(User::class, 'class_id', 'class_id')->where('id', '!=', $this->id);
    // }

    public function studentExams()
    {
        return $this->hasMany(Exam::class, 'class_id', 'class_id');
    }

}
