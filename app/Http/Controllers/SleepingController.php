<?php

namespace App\Http\Controllers;

use App\Models\SleepRecommendation;
use App\Models\SleepingTip;
use App\Models\MeditationSound;
use App\Models\SleepChallenge;
use App\Models\UserChallenge;
use App\Models\Journal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SleepingController extends Controller
{

    public function getSleepRecommendations()
    {
        $recommendations = SleepRecommendation::select('icon_source as iconSource', 'title')->get();

        return response()->json($recommendations);
    }


    public function getSleepingTips()
    {
        $tips = SleepingTip::select('icon_source as iconSource', 'title', 'description')->get();

        return response()->json($tips);
    }

    public function getAllSleepChallenges()
    {
        $challenges = SleepChallenge::select('title', 'iconSrc as icon_src', 'description')->get();

        return response()->json($challenges);
    }

    public function getSleepQualityChallenges()
    {
        $challenges = SleepChallenge::where('type', 'sleep_quality')
            ->select('title', 'iconSrc as icon_src', 'description')
            ->get();

        return response()->json($challenges);
    }

    public function getMySleepChallenges()
    {
        $user = Auth::user();

        $myChallenges = UserChallenge::with(['challenge'])
            ->where('userId', $user->id)
            ->get()
            ->map(function ($userChallenge) {
                return [
                    'id' => $userChallenge->id,
                    'title' => $userChallenge->challenge->title,
                    'icon_src' => $userChallenge->challenge->iconSrc,
                    'description' => $userChallenge->challenge->description,
                    'total_days' => $userChallenge->challenge->totalDays,
                    'completed_days' => $userChallenge->completedDays
                ];
            });

        return response()->json($myChallenges);
    }


    public function getSleepChallenge($id)
    {
        $user = Auth::user();

        // Find the challenge first
        $challenge = SleepChallenge::findOrFail($id);

        // Check if user has started this challenge
        $userChallenge = UserChallenge::where('userId', $user->id)
            ->where('challengeId', $id)
            ->first();

        $response = [
            'title' => $challenge->title,
            'icon_src' => $challenge->iconSrc,
            'description' => $challenge->description,
            'benefits_why' => $challenge->benefitsWhy,
            'benefits_results' => $challenge->benefitsResults,
            'total_days' => $challenge->totalDays,
            'completed_days' => $userChallenge ? $userChallenge->completedDays : 0,
            'is_started' => $userChallenge ? $userChallenge->isStarted : false
        ];

        return response()->json($response);
    }


}
