<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserSleepingDisorder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
            'age' => 'required',
            'gender' => 'required',
            // 'sleep_routine' => 'required|string',
            // 'bed_time' => 'required|string',
            // 'wake_up_time' => 'required|string',
            // 'difficulty_sleeping' => 'required',
            // 'wake_up_rested' => 'required',
            // 'working_hours' => 'required',
            // 'daily_commuting_hours' => 'required',
            // 'travel_frequently' => 'required',
            // 'is_bedroom_noisy' => 'required',
            // 'is_bedroom_dark' => 'required',
            // 'use_electronics' => 'required',
            // 'stressed_regularly' => 'required',
            // 'alcohol_or_caffeine' => 'required',
            // 'sleeping_disorders' => 'nullable',
            // 'exercise_regularly' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 200);
        }


        if ($request->has('email')) {
            $user = User::where('email', $request->email)->first();

            if ($user) {
                return response()->json(['message' => 'User already exists'], 200);
            }
        }

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'age' => $request->age,
            'gender' => $request->gender,
            'createdAt' => now(),
            'updatedAt' => now(),
            // 'sleepRoutine' => $request->sleep_routine,
            // 'bedTime' => $request->bed_time,
            // 'wakeUpTime' => $request->wake_up_time,
            // 'difficultySleeping' => $request->difficulty_sleeping,
            // 'wakeUpRested' => $request->wake_up_rested,
            // 'workingHours' => $request->working_hours,
            // 'dailyCommutingHours' => $request->daily_commuting_hours,
            // 'travelFrequently' => $request->travel_frequently,
            // 'isBedroomNoisy' => $request->is_bedroom_noisy,
            // 'isBedroomDark' => $request->is_bedroom_dark,
            // 'useElectronics' => $request->use_electronics,
            // 'stressedRegularly' => $request->stressed_regularly,
            // 'alcoholOrCaffeine' => $request->alcohol_or_caffeine,
            // 'exerciseRegularly' => $request->exercise_regularly,
        ]);

        // // Process sleeping disorders if provided
        // if ($request->has('sleeping_disorders') && is_array($request->sleeping_disorders)) {
        //     foreach ($request->sleeping_disorders as $disorderName) {
        //         // Find or create the sleeping disorder
        //         $disorder = \App\Models\SleepingDisorder::firstOrCreate(['name' => $disorderName]);

        //         // Associate the disorder with the user
        //         UserSleepingDisorder::create([
        //             'userId' => $user->id,
        //             'disorderId' => $disorder->id
        //         ]);
        //     }
        // }

        // Create token for the user
        $token = $user->createToken('auth_token');

        return response()->json([
            'message' => 'User registered successfully',
            'records' => $user,
            'token' => $token
        ], 200);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 200);
        }

        // Check email
        $user = User::where('email', $request->email)->first();

        // Check password
        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Create new token
        $token = $user->createToken('auth_token');

        return response()->json([
            'message' => 'Login successful',
            'user' => $user,
            'token' => $token
        ]);
    }
}
