<?php

namespace App\Http\Controllers\v1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\User;
use App\Http\Requests\CourseRequest;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::get();
        return response()->json([
            'status' => 'ok',
            'code' => 200,
            'message' => 'Successfully get courses',
            'data' => $courses
        ]);
    }

    public function store(CourseRequest $request)
    {
        $data = $request->validated();
        $user = User::find($data['lecturer_id']);
        if($user && $user->role_id == 2){
            $course = Course::create($data);
            return response()->json([
                'status' => 'ok',
                'code' => 200,
                'message' => 'Successfully add new course',
                'data' => []
            ]);
        }
        return response()->json([
            'status' => 'error',
            'code' => 400,
            'message' => 'Failed to add new course! User should be lecturer',
            'data' => []
        ]);
    }

    public function show(Course $course)
    {
        return response()->json([
            'status' => 'ok',
            'code' => 200,
            'message' => 'Successfully get course',
            'data' => $course->makeVisible('lecturer')
        ]);
    }

    public function update(CourseRequest $request, Course $course)
    {
        $data = $request->validated();
        $user = User::find($data['lecturer_id']);
        if($user && $user->role_id == 2){
            if($course->update($data)){
                return response()->json([
                    'status' => 'ok',
                    'code' => 200,
                    'message' => 'Successfully update course',
                    'data' => []
                ]);
            }
            else{
                return response()->json([
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'Failed to update course',
                    'data' => []
                ]);
            }
        }
        return response()->json([
            'status' => 'error',
            'code' => 400,
            'message' => 'Failed to update course! User should be lecturer',
            'data' => []
        ]);
    }

    public function destroy(Course $course)
    {
        if($course->delete()){
            return response()->json([
                'status' => 'ok',
                'code' => 200,
                'message' => 'Successfully delete course',
                'data' => []
            ]);
        }
        else{
            return response()->json([
                'status' => 'error',
                'code' => 400,
                'message' => 'Failed to to delete course!',
                'data' => []
            ]);
        }
    }
}
