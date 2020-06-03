<?php

namespace App\Http\Controllers\v1\Lecturer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::select('id', 'name')->where('lecturer_id', auth()->user()->id)->get();
        $courses->makeHidden('lecturer_name');

        return response()->json([
            'status' => 'ok',
            'code' => 200,
            'message' => 'Successfully get courses',
            'data' => $courses
        ]);
    }
}
