<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompaniesController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\MarkController;
use App\Http\Controllers\ResumeController;
use App\Http\Controllers\TasksController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VacancyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/signin', [AuthController::class, 'signin']);
Route::middleware('auth:api')->post('logout', [AuthController::class, 'logout']);
Route::post('/signup', [AuthController::class, 'signup']);

// User
Route::middleware('auth:api')->get('user', [UserController::class, 'getSignedUser']);

Route::post('updateuserimage', [UserController::class, 'updateUserImage']);


Route::post('/createuser', [UserController::class, 'createUser']);

Route::get('/getusers', [UserController::class, 'getUsers']);

Route::get('/getuser', [UserController::class, 'getUser']);

Route::get('/getstudents', [UserController::class, 'getStudents']);

Route::delete('/deleteuser', [UserController::class, 'deleteUser']);

Route::put('/changeuseraboutme', [UserController::class, 'changeUserAboutMe']);

// Companies

Route::post('/company', [CompaniesController::class, 'getCompany']);
Route::get('/getcompanies', [CompaniesController::class, 'getCompanies']);

Route::delete('/deletecompany', [CompaniesController::class, 'deleteCompany']);

Route::post('/addcompany', [CompaniesController::class, 'addCompany']);

Route::post('/updatecompanyimage', [CompaniesController::class, 'updateCompanyImage']);

// Vacancies

Route::post('/addvacancy', [VacancyController::class, 'addVacancy']);
Route::post('/getvacancies', [VacancyController::class, 'getVacancies']);
Route::post('/getvacancy', [VacancyController::class, 'getVacancy']);

Route::delete('/deletevacancy', [VacancyController::class, 'deleteVacancy']);

// Permissions

Route::get('/getpermissions', function () {
    return \App\Models\Permission::all();
});

Route::get('/getpermission', function (Request $request) {
    return \App\Models\Permission::find($request['id']);
});

// Resumes

Route::post('/addresume', [ ResumeController::class, 'addResume']);

Route::get('/getresumes', [ ResumeController::class, 'getResumes']);

Route::get('getsentresumes', [ CompaniesController::class, 'resumesSent']);

// Marks

Route::get('/getmark', [ MarkController::class, 'getMark']);
Route::get('/getmarks', [ MarkController::class, 'getMarks']);
Route::post('/changemark', [UserController::class, 'changeUserMark']);



