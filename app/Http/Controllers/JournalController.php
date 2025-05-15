<?php

namespace App\Http\Controllers;

use App\Models\Journal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JournalController extends Controller
{
    public function postJournal(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'mood' => 'required',
            'sleep_quality' => 'required',
            'notes' => 'nullable',
        ]);

        $journal = new Journal();
        $journal->user_id = $user->id;
        $journal->mood = $request->mood;
        $journal->sleep_quality = $request->sleep_quality;
        $journal->notes = $request->notes;
        $journal->save();

        return response()->json([
            'error' => false,
            'message' => 'Journal entry created successfully',
            'records' => $journal
        ]);
    }
    public function getJournalSummary()
    {
        $user = Auth::user();

        $summaries = Journal::where('user_id', $user->id)->get();

        if ($summaries->isEmpty()) {
            return response()->json([
                'error' => true,
                'message' => 'No journal record found'
            ]);
        }

        $summaries = $summaries->map(function ($summary) {
            return [
                'id' => $summary->id,
                'mood' => $summary->mood,
                'sleep_quality' => $summary->sleep_quality,
                'notes' => $summary->notes,
                'created_at' => $summary->created_at,
                'updated_at' => $summary->updated_at,
                'deleted_at' => $summary->deleted_at,
            ];
        });

        return response()->json(
            [
                'error' => false,
                'message' => 'Journal record found',
                'records' => $summaries
            ]
        );
    }
}
