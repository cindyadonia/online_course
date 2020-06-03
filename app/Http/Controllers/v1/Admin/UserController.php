<?php

namespace App\Http\Controllers\v1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Http\Requests\NewUserRequest;
use Illuminate\Support\Facades\DB;
use Exception;

class UserController extends Controller
{
    public function getLecturers()
    {
        $lecturers = User::select('id', 'name', 'email')->where('role_id', 2)->get();
        return response()->json([
            'status' => 'ok',
            'code' => 200,
            'message' => 'Successfully get lecturers',
            'data' => $lecturers
        ]);
    }

    public function getStudents()
    {
        $students = User::select('id', 'name', 'email')->where('role_id', 3)->get();
        return response()->json([
            'status' => 'ok',
            'code' => 200,
            'message' => 'Successfully get students',
            'data' => $students
        ]);
    }

    public function show($id)
    {
        $user = User::find($id);
        if(!$user){
            return response()->json([
                'status' => 'error',
                'code' => 400,
                'message' => 'Sorry! The user you are looking for doesn\'t exist!',
                'data' => []
            ]);
        }
        return response()->json([
            'status' => 'ok',
            'code' => 200,
            'message' => 'Successfully get user',
            'data' => $user
        ]);
    }

    public function store(NewUserRequest $request)
    {
        $data = $request->validated();
        DB::beginTransaction();
        try {
            $data['password'] = bcrypt($data['password']);
            User::create($data);
            DB::commit();
           
        }
        catch (Exception $e) {
            DB::Rollback();
            return response()->json([
                'status' => 'error',
                'code' => 400,
                'message' => 'Failed to add new user!',
                'data' => []
            ]);
        }
        return response()->json([
            'status' => 'ok',
            'code' => 200,
            'message' => 'Successfully add new users',
            'data' => []
        ]);
    }

    public function destroy($id)
    {
        $user = User::find($id);
        if(!$user){
            return response()->json([
                'status' => 'error',
                'code' => 400,
                'message' => 'Failed to delete user! User doesn\'t exist!',
                'data' => []
            ]);
        }
        $user->delete();
        return response()->json([
            'status' => 'ok',
            'code' => 200,
            'message' => 'Successfully delete user',
            'data' => []
        ]);

    }
}
