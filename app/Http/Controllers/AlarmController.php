<?php

namespace App\Http\Controllers;

use App\Models\Alarm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AlarmController extends Controller
{

    public function setAlarm(Request $request)
    {

        $user = Auth::user();

        $request->validate([
            'time' => 'required',
            'repeat_days' => 'nullable',
            'alarm_name' => 'required',
            'alarm_sound' => 'nullable',
            'label' => 'nullable',
            'alarm_volume' => 'nullable|integer|min:0|max:100',
            'vibration' => 'nullable',
            'wake_up_challenge' => 'nullable',
            'math_problem' => 'required',
        ]);

        $alarm = Alarm::create([
            'user_id' => $user->id,
            'time' => $request->time,
            'repeat_days' => $request->repeat_days,
            'alarm_name' => $request->alarm_name,
            'alarm_sound' => $request->alarm_sound,
            'label' => $request->label,
            'alarm_volume' => $request->alarm_volume,
            'vibration' => $request->vibration,
            'wake_up_challenge' => $request->wake_up_challenge,
            'math_problem' => $request->math_problem,
        ]);

        return response()->json([
            'error' => false,
            'message' => 'Alarm created successfully',
            'records' => [
                'user_id' => $user->id,
                'id' => $alarm->id,
                'time' => $alarm->time,
                'repeat_days' => $alarm->repeat_days,
                'alarm_name' => $alarm->alarm_name,
                'alarm_sound' => $alarm->alarm_sound,
                'label' => $alarm->label,
                'alarm_volume' => $alarm->alarm_volume,
                'vibration' => $alarm->vibration,
                'wake_up_challenge' => $alarm->wake_up_challenge,
                'math_problem' => $alarm->math_problem,
                'is_active' => $alarm->is_active,
                'time_zone' => $alarm->time_zone,
                'created_at' => $alarm->created_at,
                'updated_at' => $alarm->updated_at
            ]
        ], 200);
    }

    public function getAllAlarms()
    {
        $user = Auth::user();

        $alarms = Alarm::where('user_id', $user->id)
            ->orderBy('id', 'desc')
            ->get();

        if ($alarms->isEmpty()) {
            return response()->json([
                'error' => true,
                'message' => 'No alarms records found'
            ], 200);
        }

        $alarms = $alarms->map(function ($alarm) {
            return [
                'user_id' => $alarm->user_id,
                'id' => $alarm->id,
                'time' => $alarm->time,
                'repeat_days' => $alarm->repeat_days,
                'alarm_name' => $alarm->alarm_name,
                'alarm_sound' => $alarm->alarm_sound,
                'label' => $alarm->label,
                'alarm_volume' => $alarm->alarm_volume,
                'vibration' => $alarm->vibration,
                'wake_up_challenge' => $alarm->wake_up_challenge,
                'math_problem' => $alarm->math_problem,
                'is_active' => $alarm->is_active,
                'time_zone' => $alarm->time_zone,
                'created_at' => $alarm->created_at,
                'updated_at' => $alarm->updated_at
            ];
        });

        return response()->json([
            'error' => false,
            'message' => 'All Alarms fetched successfully',
            'records' => $alarms
        ], 200);
    }

    public function getSingleAlarm(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'alarm_id' => 'required|integer'
        ]);

        $alarm = Alarm::where('user_id', $user->id)
            ->where('id', $request->alarm_id)
            ->first();

        if (!$alarm) {
            return response()->json([
                'error' => true,
                'message' => 'Alarm ID not found'
            ], 200);
        }

        return response()->json([
            'error' => false,
            'message' => 'Alarm fetched successfully',
            'records' => [
                'user_id' => $alarm->user_id,
                'id' => $alarm->id,
                'time' => $alarm->time,
                'repeat_days' => $alarm->repeat_days,
                'alarm_name' => $alarm->alarm_name,
                'alarm_sound' => $alarm->alarm_sound,
                'label' => $alarm->label,
                'alarm_volume' => $alarm->alarm_volume,
                'vibration' => $alarm->vibration,
                'wake_up_challenge' => $alarm->wake_up_challenge,
                'math_problem' => $alarm->math_problem,
                'is_active' => $alarm->is_active,
                'created_at' => $alarm->created_at,
                'updated_at' => $alarm->updated_at
            ]
        ], 200);
    }

    public function updateAlarm(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'alarm_id' => 'required|integer',
            'time' => 'nullable',
            'repeat_days' => 'nullable',
            'alarm_name' => 'nullable',
            'alarm_sound' => 'nullable',
            'label' => 'nullable',
            'alarm_volume' => 'nullable|integer|min:0|max:100',
            'vibration' => 'nullable',
            'wake_up_challenge' => 'nullable',
            'math_problem' => 'nullable',
            'is_active' => 'nullable',
        ]);

        $alarm = Alarm::where('user_id', $user->id)
            ->where('id', $request->alarm_id)
            ->first();

        if (!$alarm) {
            return response()->json([
                'error' => true,
                'message' => 'Alarm ID not found'
            ], 200);
        }

        // Remove alarm_id from the update data
        $updateData = $request->except('alarm_id');
        $alarm->update($updateData);

        return response()->json([
            'error' => false,
            'message' => 'Alarm updated successfully',
            'records' => [
                'user_id' => $alarm->user_id,
                'id' => $alarm->id,
                'time' => $alarm->time,
                'repeat_days' => $alarm->repeat_days,
                'alarm_name' => $alarm->alarm_name,
                'alarm_sound' => $alarm->alarm_sound,
                'label' => $alarm->label,
                'alarm_volume' => $alarm->alarm_volume,
                'vibration' => $alarm->vibration,
                'wake_up_challenge' => $alarm->wake_up_challenge,
                'math_problem' => $alarm->math_problem,
                'is_active' => $alarm->is_active,
                'time_zone' => $alarm->time_zone,
                'created_at' => $alarm->created_at,
                'updated_at' => $alarm->updated_at
            ]
        ], 200);
    }

    public function deleteAlarm(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'alarm_id' => 'required|integer'
        ]);

        $alarm = Alarm::where('user_id', $user->id)
            ->where('id', $request->alarm_id)
            ->first();

        if (!$alarm) {
            return response()->json([
                'error' => true,
                'message' => 'Alarm ID not found'
            ], 200);
        }

        $alarm->delete();

        return response()->json([
            'error' => false,
            'message' => 'Alarm deleted successfully'
        ], 200);
    }
}
