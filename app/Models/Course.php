<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = ['name', 'lecturer_id'];
    protected $appends = ['lecturer_name'];
    protected $hidden = ['lecturer'];

    public function getLecturerNameAttribute()
    {
        return $this->lecturer->name;
    }
    
    public function lecturer()
    {
        return $this->belongsTo('App\Models\User', 'lecturer_id');
    }

    public function students()
    {
        return $this->hasMany('App\Models\StudentCourse', 'course_id');
    }

    public function course_materials()
    {
        return $this->hasMany('App\Models\CourseMaterial');
    }

    public static function boot() {
        parent::boot();
        static::deleting(function($course){
            $course->course_materials()->delete();
            $course->students()->delete();
        });
    }
}
