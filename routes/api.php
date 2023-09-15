<?php

use App\Events\TestMessage;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ChosenOptionController;
use App\Http\Controllers\API\ExamController;
use App\Http\Controllers\API\ExamResultController;
use App\Http\Controllers\API\NotificationController;
use App\Http\Controllers\API\OptionController;
use App\Http\Controllers\API\QuestionController;
use App\Http\Controllers\API\ResultController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\StudentExamController;
use App\Http\Controllers\API\StudyClassController;
use App\Http\Controllers\API\UsersController;
use App\Models\StudyClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware(['auth:sanctum'])->group(function () {

    Route::apiResource('exams', ExamController::class);
    Route::apiResource('options', OptionController::class);
    Route::apiResource('questions', QuestionController::class);
    Route::apiResource('result', ResultController::class);
    Route::apiResource('roles', RoleController::class);
    Route::apiResource('student_exam', StudentExamController::class);
    Route::apiResource('users', UsersController::class);
    Route::apiResource('class', StudyClassController::class);

    Route::get('students', [UsersController::class, 'getStudents']);
    Route::get('teachers', [UsersController::class, 'getTeachers']);
    Route::get('teachers/{teacherId}/name', [UsersController::class, 'getTeacherName']);
    Route::get('students/{studentId}/name', [UsersController::class, 'getStudentName']);
    Route::get('currentTeacher', [UsersController::class, 'getCurrentTeacher']);
    Route::get('options/question/{questionId}', [OptionController::class, 'getOptionsByQuestionId']);
    Route::get('questions/exam/{exam_id}', [QuestionController::class, 'fetchQuestionsByExamId']);
    Route::post('chosen-option-store', [QuestionController::class, 'storeChosenOption']);
    Route::get('student/exams', [ExamController::class, 'getUserExams']);
    Route::get('exam/{examId}/examTitle', [ExamController::class, 'getExamName']);
    Route::get('chosen-option', [ChosenOptionController::class, 'show']);
    Route::post('chosen-option', [ChosenOptionController::class, 'store']);
    Route::put('chosen-option/{id}', [ChosenOptionController::class, 'update']);
    Route::post('exam-results', [ExamResultController::class, 'store']);
    Route::get('exam-results/submission-status', [ExamResultController::class, 'getSubmissionStatus']);
    Route::get('exam-results/{examId}', [ExamResultController::class, 'showExamResults']);
    Route::get('chosen-option/correct-count', [ChosenOptionController::class, 'getCorrectOptionsCount']);
    Route::get('exams/by-class/{class_id}', [ExamController::class, 'getExamsByClass']);
    Route::get('exams/{teacherId}/teacher', [ExamController::class, 'getExamsByTeacher']);
    Route::get('students/by-class/{classId}', [UsersController::class, 'getStudentsByClass']);
    Route::get('study_class/{classId}/class_name', [StudyClassController::class, 'getClassName']);
    Route::put('users/updateStudent/{id}', [UsersController::class, 'updateStudent']);
    // Route::post('notifications', [NotificationController::class, 'store']);
    // Route::get('notifications', [NotificationController::class, 'index']);
    // Route::put('notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead']);
});

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout']);
Route::post('updateUser', [AuthController::class, 'updateUser']);
Route::middleware('auth:sanctum')->get('user', [AuthController::class, 'getUser']);


