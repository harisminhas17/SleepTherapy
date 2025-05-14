<?php

use Illuminate\Http\Request;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SleepingController;
use App\Http\Controllers\AlarmController;
use Illuminate\Support\Facades\Route;

Route::post('register', [UserController::class, 'register']); //working
Route::post('login', [UserController::class, 'login']); //working
Route::post('checkEmail', [UserController::class, 'checkEmail']); //working


Route::middleware('auth:sanctum')->group(function () {

    //Summary
    Route::get('getUserSummary', [UserController::class, 'getUserSummary']);

    // Sleep recommendations
    Route::get('/sleep-recommendations', [SleepingController::class, 'getSleepRecommendations']);

    // Sleeping tips
    Route::get('/sleeping-tips', [SleepingController::class, 'getSleepingTips']);

    // Meditation sounds
    Route::get('/meditation-sounds', [SleepingController::class, 'getMeditationSounds']);
    Route::get('/meditation-sound/{id}', [SleepingController::class, 'getMeditationSound']);

    // Journal
    Route::post('/post-journal', [SleepingController::class, 'postJournal']);
    Route::get('/journal-summary', [SleepingController::class, 'getJournalSummary']);

    // Sleep challenges
    Route::get('/all-sleep-challenges', [SleepingController::class, 'getAllSleepChallenges']);
    Route::get('/sleep-quality-challenges', [SleepingController::class, 'getSleepQualityChallenges']);
    Route::get('/my-sleep-challenges', [SleepingController::class, 'getMySleepChallenges']);
    Route::get('/sleep-challenge/{id}', [SleepingController::class, 'getSleepChallenge']);

    // Alarms
    Route::post('/set-alarm', [AlarmController::class, 'store']);
    Route::get('/get-alarms', [AlarmController::class, 'index']);
    Route::get('/alarm/{id}', [AlarmController::class, 'show']);
    Route::put('/alarm/{id}', [AlarmController::class, 'update']);
    Route::delete('/alarm/{id}', [AlarmController::class, 'destroy']);
});
