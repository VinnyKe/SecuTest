<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\QuestionsController;


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
Route::resources([
    'questions' => QuestionsController::class,
]);

// Questionnaire management
Route::get('/questions/{question}/image', [QuestionsController::class,'showImage']);
Route::get('/get-questionnaire', [QuestionsController::class,'getQuestionnaire']);
Route::post('/submit-questionnaire', [QuestionsController::class,'submitQuestionnaire']);

// DB management
Route::post('/questions/import', [QuestionsController::class, 'importQuestions']);
Route::post('/questions/reset', [QuestionsController::class, 'resetQuestions']);
