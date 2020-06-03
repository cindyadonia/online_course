<?php

namespace App\Http\Controllers\v1\Lecturer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\CourseMaterial;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\CourseMaterialRequest;
use Exception;
use App\Services\FileService;

class CourseMaterialController extends Controller
{
    private $fileService;
    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
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

    public function store(CourseMaterialRequest $request)
    {
        $data = $request->validated();
        DB::beginTransaction();
        try {
            $filename = null;
            $body = null;

            if($request->hasFile('file')) {
                $file = $request['file'];
    
                if(!$file->isValid()) {
                    return response()->api([], 400, 'error', 'Sorry invalid file upload');
                }
    
                $newName = trim(addslashes($file->getClientOriginalName()));
                $newName = str_replace(' ', '%20', $newName);
                $random_time = time();
                $dir = 'course_materials';
                $file->storeAs('public/file/'.$dir, $random_time.'_'.$newName);
                $filename = '/'.$dir.'/'.$random_time.'_'.$newName;
            }

            if(isset($data['body'])){
                $body = $data['body'];
            }

            $courseMaterial = new CourseMaterial([
                'course_id' => $data['course_id'],
                'title' => $data['title'],
                'body' => $body,
                'file' => $filename
            ]);
            $courseMaterial->save();
            DB::commit();
        }
        catch (Exception $e) {
            DB::Rollback();
            return response()->json([
                'status' => 'error',
                'code' => 400,
                'message' => 'Sorry something went wrong!',
                'data' => []
            ]);
        }
        return response()->json([
            'status' => 'ok',
            'code' => 200,
            'message' => 'Successfully add materials',
            'data' => []
        ]);
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $courseMaterial = CourseMaterial::find($id);
            if(!$courseMaterial){
                return response()->json([
                    'status' =>  'error',
                    'code' => 400,
                    'message' => 'Course material doesn\'t exist',
                    'data' => []
                ]);
            }
            if($courseMaterial['file'] != NULL){
                $path = $courseMaterial['file'];
    
                $url = $this->fileService->originalURL($path);
                if(is_file(storage_path('app/public/file/' . $path))){
                    unlink(storage_path('app/public/file/' . $path));
                }
                else{
                    return response()->json([
                        'status' =>  'error',
                        'code' => 400,
                        'message' => 'Failed to delete material. File doesn\'t exist!',
                        'data' => $this->fileService->fileURL($path)
                    ]);
                }
                Storage::delete($url);
            }
            
            $courseMaterial->delete();
            DB::commit();
        }
        catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'status' =>  'error',
                'code' => 400,
                'message' => 'Failed to delete course material',
                'data' => []
            ]);
        }
        return response()->json([
            'status' =>  'ok',
            'code' => 200,
            'message' => 'Successfully delete course material',
            'data' => []
        ]);
    }
}
