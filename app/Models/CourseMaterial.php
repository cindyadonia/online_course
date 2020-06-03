<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseMaterial extends Model
{
    protected $fillable = ['course_id', 'title', 'body', 'file'];

    public function course()
    {
        return $this->belongsTo('App\Models\Course');
    }
}
