<?php

namespace App\Http\Controllers\v1\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentCourse;
use App\Models\Course;
use App\Models\CourseMaterial;
use Illuminate\Support\Facades\DB;
use App\Services\FileService;
use Exception;

class CourseController extends Controller
{
    private $fileService;
    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function getMyCourses()
    {
        $myCourses = StudentCourse::where('student_id', auth()->user()->id)->get();
        return response()->json([
            'status' => 'ok',
            'code' => 200,
            'message' => 'Successfully get my courses',
            'data' => $myCourses
        ]);
    }

    public function enrollMe(Request $request)
    {
        if(!isset($request['course_id'])){
            return response()-json([
                'status' => 'error',
                'code' => 422,
                'message' => 'Please at least choose 1 course',
                'data' => []
            ]);
        }
        
        $course = Course::find($request['course_id']);
        if(!$course){
            return response()-json([
                'status' => 'error',
                'code' => 400,
                'message' => 'Course doesn\'t exist',
                'data' => []
            ]);
        }

        $studentCourse = StudentCourse::where('student_id', auth()->user()->id)->where('course_id', $request['course_id'])->first();
        if(!$studentCourse){
            $enroll = new StudentCourse();
            $enroll->student_id = auth()->user()->id;
            $enroll->course_id = $request['course_id'];
            $enroll->save();

            return response()->json([
                'status' => 'ok',
                'code' => 200,
                'message' => 'Successfully enroll to the course',
                'data' => []
            ]);
        }
        else{
            return response()->json([
                'status' => 'error',
                'code' => 400,
                'message' => 'You have enrolled to this course',
                'data' => []
            ]);
        }
    }

    public function getCourseMaterials($id)
    {
        $course = Course::with('course_materials:id,course_id,title,body,file')->find($id);
        foreach($course['course_materials'] as $material){
            if($material->file != NULL){
                $material->file = $this->fileService->fileURL($material->file);
            }
        }

        return response()->json([
            'status' => 'ok',
            'code' => 200,
            'message' => 'Successfully get course materials',
            'data' => $course
        ]);
    }
}
