<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudyClass extends Model
{
    use HasFactory;

    protected $primaryKey ='id';
    protected $table = 'study_classes';
    protected $fillable = [
        'class_name'
    ];

    public function students()
    {
        return $this->hasMany(User::class, 'class_id', 'id');
    }
}
