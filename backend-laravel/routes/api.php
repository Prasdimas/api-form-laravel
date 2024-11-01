<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\SkillSetController;
use App\Http\Controllers\CandidateController;
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


Route::apiResource('candidates', CandidateController::class);
Route::apiResource('jobs', JobController::class);
Route::apiResource('skill-sets', SkillSetController::class);
Route::apiResource('skills', SkillController::class);
Route::get('skill-sets/{candidateId}/{skillId}', [SkillSetController::class, 'show']);
Route::post('/candidates', [CandidateController::class, 'store']);
Route::post('/jobs', [JobController::class, 'store']);
Route::post('/skills', [SkillController::class, 'store']);