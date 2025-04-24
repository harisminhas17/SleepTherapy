<?php

namespace App\Http\Controllers;

use App\Models\Alarm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AlarmController extends Controller
{
    /**
     * Get all alarms for the authenticated user.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $alarms = Alarm::where('userId', $user->id)->get()->map(function ($alarm) {
            return [
                'time' => $alarm->time,
                'date' => $alarm->date,
                'timezone' => $alarm->timezone,
                'repeat' => ['days' => $alarm->repeatDays],
                'label' => $alarm->label,
                'sound' => $alarm->sound,
                'vibration' => $alarm->vibration,
                'volume' => $alarm->volume,
                'wake_up_challenge' => $alarm->wakeUpChallenge
            ];
        });

        return response()->json($alarms);
    }

    /**
     * Store a newly created alarm.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'time' => 'required|string',
            'date' => 'nullable|string',
            'timezone' => 'nullable|string',
            'repeat' => 'nullable|array',
            'repeat.days' => 'nullable|array',
            'repeat.days.*' => 'string',
            'label' => 'nullable|string',
            'sound' => 'nullable|string',
            'vibration' => 'nullable|boolean',
            'volume' => 'nullable|integer|min:0|max:100',
            'wake_up_challenge' => 'nullable|boolean',
        ]);

        $user = Auth::user();

        $alarm = new Alarm();
        $alarm->userId = $user->id;
        $alarm->time = $request->time;
        $alarm->date = $request->date;
        $alarm->timezone = $request->timezone ?? 'UTC';

        if ($request->has('repeat') && isset($request->repeat['days'])) {
            $alarm->repeatDays = $request->repeat['days'];
        }

        $alarm->label = $request->label;
        $alarm->sound = $request->sound;
        $alarm->vibration = $request->vibration ?? true;
        $alarm->volume = $request->volume ?? 70;
        $alarm->wakeUpChallenge = $request->wake_up_challenge ?? false;
        $alarm->isActive = true;

        $alarm->save();

        return response()->json([
            'message' => 'Alarm created successfully',
            'alarm' => [
                'time' => $alarm->time,
                'date' => $alarm->date,
                'timezone' => $alarm->timezone,
                'repeat' => ['days' => $alarm->repeatDays],
                'label' => $alarm->label,
                'sound' => $alarm->sound,
                'vibration' => $alarm->vibration,
                'volume' => $alarm->volume,
                'wake_up_challenge' => $alarm->wakeUpChallenge
            ]
        ], 201);
    }

    /**
     * Display the specified alarm.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = Auth::user();
        $alarm = Alarm::where('userId', $user->id)->findOrFail($id);

        return response()->json([
            'time' => $alarm->time,
            'date' => $alarm->date,
            'timezone' => $alarm->timezone,
            'repeat' => ['days' => $alarm->repeatDays],
            'label' => $alarm->label,
            'sound' => $alarm->sound,
            'vibration' => $alarm->vibration,
            'volume' => $alarm->volume,
            'wake_up_challenge' => $alarm->wakeUpChallenge
        ]);
    }

    /**
     * Update the specified alarm.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'time' => 'nullable|string',
            'date' => 'nullable|string',
            'timezone' => 'nullable|string',
            'repeat' => 'nullable|array',
            'repeat.days' => 'nullable|array',
            'repeat.days.*' => 'string',
            'label' => 'nullable|string',
            'sound' => 'nullable|string',
            'vibration' => 'nullable|boolean',
            'volume' => 'nullable|integer|min:0|max:100',
            'wake_up_challenge' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        $user = Auth::user();
        $alarm = Alarm::where('userId', $user->id)->findOrFail($id);

        if ($request->has('time')) {
            $alarm->time = $request->time;
        }

        if ($request->has('date')) {
            $alarm->date = $request->date;
        }

        if ($request->has('timezone')) {
            $alarm->timezone = $request->timezone;
        }

        if ($request->has('repeat') && isset($request->repeat['days'])) {
            $alarm->repeatDays = $request->repeat['days'];
        }

        if ($request->has('label')) {
            $alarm->label = $request->label;
        }

        if ($request->has('sound')) {
            $alarm->sound = $request->sound;
        }

        if ($request->has('vibration')) {
            $alarm->vibration = $request->vibration;
        }

        if ($request->has('volume')) {
            $alarm->volume = $request->volume;
        }

        if ($request->has('wake_up_challenge')) {
            $alarm->wakeUpChallenge = $request->wake_up_challenge;
        }

        if ($request->has('is_active')) {
            $alarm->isActive = $request->is_active;
        }

        $alarm->save();

        return response()->json([
            'message' => 'Alarm updated successfully',
            'alarm' => [
                'time' => $alarm->time,
                'date' => $alarm->date,
                'timezone' => $alarm->timezone,
                'repeat' => ['days' => $alarm->repeatDays],
                'label' => $alarm->label,
                'sound' => $alarm->sound,
                'vibration' => $alarm->vibration,
                'volume' => $alarm->volume,
                'wake_up_challenge' => $alarm->wakeUpChallenge,
                'is_active' => $alarm->isActive
            ]
        ]);
    }

    /**
     * Remove the specified alarm.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $alarm = Alarm::where('userId', $user->id)->findOrFail($id);

        $alarm->delete();

        return response()->json([
            'message' => 'Alarm deleted successfully'
        ]);
    }
}
