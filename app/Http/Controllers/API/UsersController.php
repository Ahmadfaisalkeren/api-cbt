<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::where('role_id', '!=', '1')->orderBy('id', 'desc')->get();

        if ($users->count() > 0) {
            return response()->json(
                [
                    'status' => 200,
                    'users' => $users,
                ],
                200,
            );
        } else {
            return response()->json(
                [
                    'status' => 404,
                    'users' => 'No records found',
                ],
                404,
            );
        }
    }

    // public function getStudents(Request $request)
    // {
    //     $classId = $request->input('class_id');

    //     $students = User::where('role_id', '=', '3')
    //                 // ->where('class_id', '=', $classId)
    //                 ->with('studyClass')
    //                 ->get();

    //     if ($students->count() > 0) {
    //         return response()->json([
    //             'status' => 200,
    //             'students' => $students,
    //         ], 200,);
    //     } else {
    //         return response()->json([
    //             'status' => 404,
    //             'students' => 'No Records Found',
    //         ], 404);
    //     }
    // }

    public function getStudents(Request $request)
    {
        $classId = $request->input('class_id');

        $students = User::where('role_id', 3)
                        ->whereHas('studyClass', function ($query) use ($classId) {
                            $query->where('id', $classId);
                        })
                        ->with('studyClass')
                        ->get();

        if ($students->count() > 0) {
            return response()->json([
                'status' => 200,
                'students' => $students,
            ], 200);
        } else {
            // Instead of returning a 404 status, return an empty array
            return response()->json([
                'status' => 200,
                'students' => [],
            ], 200);
        }
    }

    public function getStudentsByClass($classId)
    {
        $students = User::where('role_id', 3)
                        ->where('class_id', $classId)
                        ->get();

        return response()->json([
            'status' => 200,
            'students' => $students,
        ], 200);
    }

    public function getTeachers()
    {
        $teachers = User::where('role_id', '=', '2')->with('studyClass')->get();

        if ($teachers->count() > 0) {
            return response()->json([
                'status' => 200,
                'teachers' => $teachers,
            ], 200,);
        } else {
            return response()->json([
                'status' => 404,
                'teachers' => 'No Records Found',
            ], 404);
        }
    }

    public function getTeacherName($teacherId)
    {
        $teacher = User::find($teacherId);

        if ($teacher) {
            return response()->json([
                'status' => 200,
                'teacher_name' => $teacher->name,
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Teacher not found',
            ], 404);
        }
    }

    public function getStudentName($studentId)
    {
        $student = User::find($studentId);

        if ($student) {
            return response()->json([
                'status' => 200,
                'student_name' => $student->name,
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Student not found',
            ], 404);
        }
    }

    public function getCurrentTeacher()
    {
        $user = auth()->user();

        if ($user && $user->role_id === 2) {
            return response()->json([
                'status' => 200,
                'teacher' => $user,
            ], 200);
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Unauthorized',
            ], 401);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'password' => 'required|confirmed',
            'role_id' => 'required',
            'class_id' => 'nullable'
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'status' => 422,
                    'errors' => $validator->messages(),
                ],
                422,
            );
        } else {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => $request->role_id,
                'class_id' => $request->class_id,
            ]);

            if ($user) {
                return response()->json(
                    [
                        'status' => 200,
                        'message' => 'user created successfully',
                    ],
                    200,
                );
            } else {
                return response()->json(
                    [
                        'status' => 500,
                        'message' => 'Something went wrong',
                    ],
                    500,
                );
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = User::find($id);
        if ($user) {
            return response()->json(
                [
                    'status' => 200,
                    '$user' => $user,
                ],
                200,
            );
        } else {
            return response()->json(
                [
                    'status' => 404,
                    'message' => 'No such user found',
                ],
                404,
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = User::find($id);
        if ($user) {
            return response()->json(
                [
                    'status' => 200,
                    'user' => $user,
                ],
                200,
            );
        } else {
            return response()->json(
                [
                    'status' => 404,
                    'message' => 'No such user found',
                ],
                404,
            );
        }
    }

        /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'status' => 404,
                'message' => 'User not found',
            ], 404);
        }

        // Ensure the authenticated user matches the user being updated
        if ($user && auth()->user() && $user->id !== auth()->user()->id) {
            return response()->json([
                'status' => 403,
                'message' => 'Access denied',
            ], 403);
        }

        $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'role_id' => 'nullable|exists:roles,id',
            'class_id' => 'nullable|exists:study_classes,id',
        ]);

        $user->name = $request->input('name', $user->name);
        $user->email = $request->input('email', $user->email);
        $user->role_id = $request->input('role_id', $user->role_id);
        $user->class_id = $request->input('class_id', $user->class_id);

        $user->save();

        return response()->json([
            'status' => 200,
            'message' => 'User updated successfully',
            'user' => $user,
        ], 200);
    }

    public function updateStudent(Request $request, int $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'status' => 404,
                'message' => 'User not found',
            ], 404);
        }

        // Check if the authenticated user has a role_id of 2 (teacher)
        if (auth()->user()->role_id === 2) {
            // Teacher can update student data
            $request->validate([
                'name' => 'nullable|string|max:255',
                'email' => 'nullable|email|unique:users,email,' . $user->id,
                'class_id' => 'nullable|exists:study_classes,id',
            ]);
        } else {
            // Unauthorized user
            return response()->json([
                'status' => 403,
                'message' => 'Access denied',
            ], 403);
        }

        // Update user data
        $user->name = $request->input('name', $user->name);
        $user->email = $request->input('email', $user->email);
        $user->class_id = $request->input('class_id', $user->class_id);

        $user->save();

        return response()->json([
            'status' => 200,
            'message' => 'User updated successfully',
            'user' => $user,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->delete();

            return response()->json(
                [
                    'status' => 200,
                    'message' => 'User deleted successfully',
                ],
                200,
            );
        } else {
            return response()->json(
                [
                    'status' => 404,
                    'message' => 'No such user found!',
                ],
                404,
            );
        }
    }
}
