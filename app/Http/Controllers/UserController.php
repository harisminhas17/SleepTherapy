<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
            'name' => 'required',
            'age' => 'required',
            'gender' => 'required|in:male,female,other',
            'identify_as' => 'required|in:night_owl,early_bird',
            'bed_time' => 'required|date_format:g:ia',
            'wake_up_time' => 'required|date_format:g:ia',
            "difficulty_sleeping" => "nullable",
            "wake_up_rested" => "nullable",
            "working_hours" => "nullable",
            "daily_commuting_hours" => "nullable",
            "travel_frequently" => "nullable",
            "is_bedroom_noisy" => "nullable",
            "is_bedroom_dark" => "nullable",
            "use_electronics" => "nullable",
            "wake_up_feeling" => "nullable",
            "feel_stressed" => "nullable",
            "consumption" => "nullable",
            "exercise_regularly" => "nullable",
            "sleep_disorders" => "nullable",
            "package_id" => "nullable",
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 200);
        }


        if ($request->has('email')) {
            $user = User::where('email', $request->email)->first();

            if ($user) {
                return response()->json([
                    'error' => true,
                    'message' => 'User already exists'
                ], 200);
            }
        }

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'name' => $request->name,
            'age' => $request->age,
            'gender' => $request->gender,
            'identify_as' => $request->identify_as,
            'bed_time' => $request->bed_time,
            'wake_up_time' => $request->wake_up_time,
            'difficulty_sleeping' => $request->difficulty_sleeping,
            'wake_up_rested' => $request->wake_up_rested,
            'working_hours' => $request->working_hours,
            'daily_commuting_hours' => $request->daily_commuting_hours,
            'travel_frequently' => $request->travel_frequently,
            'is_bedroom_noisy' => $request->is_bedroom_noisy,
            'is_bedroom_dark' => $request->is_bedroom_dark,
            'use_electronics' => $request->use_electronics,
            'wake_up_feeling' => $request->wake_up_feeling,
            'feel_stressed' => $request->feel_stressed,
            'consumption' => $request->consumption,
            'exercise_regularly' => $request->exercise_regularly,
            'sleep_disorders' => $request->sleep_disorders,
            'package_id' => $request->package_id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create token for the user
        $token = $user->createToken('auth_token');

        return response()->json([
            'error' => false,
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
                'error' => true,
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Create new token
        $token = $user->createToken('SleepRoutine')->plainTextToken;

        return response()->json([
            'token' => $token,
            'error' => false,
            'message' => 'Login successful',
            'records' => $user
        ]);
    }

    public function checkEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 200);
        }

        $user = User::where('email', $request->email)->first();

        if ($user) {
            return response()->json([
                'error' => true,
                'message' => 'Email already exists'
            ], 200);
        }

        return response()->json([
            'error' => false,
            'message' => 'Email is available'
        ], 200);
    }

    public function getUserSummary()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'error' => true,
                'message' => 'User not found'
            ], 200);
        }

        $user_summary = User::where('id', $user->id)->first();

        if (!$user_summary) {
            return response()->json([
                'error' => true,
                'message' => 'User summary not found'
            ], 200);
        }

        return response()->json([
            'error' => false,
            'message' => 'User summary retrieved successfully',
            'records' => $user_summary
        ], 200);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'error' => true,
                'message' => 'User not found'
            ], 200);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'age' => 'nullable',
            'gender' => 'nullable|in:male,female,other',
            'image' => 'nullable',
            'password' => 'nullable|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 200);
        }

        $data = $request->except(['email']); // Exclude email from update data

        // Handle password update securely
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $imageName = time() . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
            $imagePath = base_path('media/images');
            $file->move($imagePath, $imageName);
            $data['image'] = $imageName;
        }

        // Update user
        User::where('id', $user->id)->update($data);

        // Get updated user
        $updatedUser = User::find($user->id);

        return response()->json([
            'error' => false,
            'message' => 'Profile updated successfully',
            'records' => $updatedUser
        ], 200);
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation failed', $validator->errors(), 200);
        }

        $authUser = Auth::user();

        if (!Hash::check($request->current_password, $authUser->password)) {
            return response()->json([
                'error' => true,
                'message' => 'Current password is incorrect.'
            ]);
        }

        User::where('id', $authUser->id)
            ->update(['password' => Hash::make($request->new_password)]);

        return response()->json([
            'error' => false,
            'message' => 'Password updated successfully.'
        ], 200);
    }

    public function updateNotificationToken(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'error' => true,
                'message' => 'User not found'
            ], 200);
        }

        $validator = Validator::make($request->all(), [
            'notification_token' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 200);
        }

        User::where('id', $user->id)->update(['notification_token' => $request->notification_token]);

        return response()->json([
            'error' => false,
            'message' => 'Notification token updated successfully'
        ], 200);
    }
}
