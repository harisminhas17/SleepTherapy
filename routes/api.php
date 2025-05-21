<?php

use App\Http\Controllers\MeditationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SleepingController;
use App\Http\Controllers\AlarmController;
use App\Http\Controllers\JournalController;
use Illuminate\Support\Facades\Route;

Route::post('register', [UserController::class, 'register']); //working
Route::post('login', [UserController::class, 'login']); //working
Route::post('checkEmail', [UserController::class, 'checkEmail']); //working


Route::middleware('auth:sanctum')->group(function () {

    //User
    Route::get('getUserSummary', [UserController::class, 'getUserSummary']); //working
    Route::post('updateProfile', [UserController::class, 'updateProfile']); //working
    Route::put('changePassword', [UserController::class, 'changePassword']); //working

    // Journal
    Route::post('postJournal', [JournalController::class, 'postJournal']); //working
    Route::get('getJournalSummary', [JournalController::class, 'getJournalSummary']); //working

    // Alarms
    Route::post('setAlarm', [AlarmController::class, 'setAlarm']); //working
    Route::get('getAllAlarms', [AlarmController::class, 'getAllAlarms']); //working
    Route::get('getSingleAlarm', [AlarmController::class, 'getSingleAlarm']); //working
    Route::put('updateAlarm', [AlarmController::class, 'updateAlarm']); //working
    Route::delete('deleteAlarm', [AlarmController::class, 'deleteAlarm']); //working

    // Meditation

    Route::get('getMeditation', [MeditationController::class, 'getMeditation']); //working
    Route::post('addMeditation', [MeditationController::class, 'addMeditation']); //working

    // Sleep Data

    Route::get('getSleepData', [SleepingController::class, 'getSleepData']); //working
    
    // Sleep recommendations
    Route::get('/sleep-recommendations', [SleepingController::class, 'getSleepRecommendations']);

    // Sleeping tips
    Route::get('/sleeping-tips', [SleepingController::class, 'getSleepingTips']);


    // Sleep challenges
    Route::get('/all-sleep-challenges', [SleepingController::class, 'getAllSleepChallenges']);
    Route::get('/sleep-quality-challenges', [SleepingController::class, 'getSleepQualityChallenges']);
    Route::get('/my-sleep-challenges', [SleepingController::class, 'getMySleepChallenges']);
    Route::get('/sleep-challenge/{id}', [SleepingController::class, 'getSleepChallenge']);
});
