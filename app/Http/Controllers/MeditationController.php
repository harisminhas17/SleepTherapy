<?php

namespace App\Http\Controllers;

use App\Models\Meditation;
use Exception;
use Illuminate\Http\Request;

class MeditationController extends Controller
{
    public function getMeditation(Request $request)
    {
        try {
            $request->validate([
                'meditation_type' => 'required',
            ]);

            $meditation = Meditation::where('type', $request->meditation_type)->get();

            if ($meditation->isEmpty()) {
                return response()->json([
                    'error' => true,
                    'message' => 'No meditation records found',
                ], 200);
            }

            return response()->json([
                'error' => false,
                'message' => 'Meditation fetched successfully',
                'records' => $meditation,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Something went wrong: ' . $e->getMessage(),
            ], 200);
        }
    }

    public function addMeditation(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required',
                'type' => 'required',
                'description' => 'nullable',
                'image' => 'required',
                'audio' => 'required',
                'duration' => 'nullable',
            ]);

            // Store image
            $imagePath = null;
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $imageName = time() . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
                $imagePath = base_path('media/images');
                $file->move(base_path($imagePath), $imageName);
                $imagePath = $imageName;
            }

            // Store audio
            $audioPath = null;
            if ($request->hasFile('audio')) {
                $audio = $request->file('audio');
                $audioName = time() . '_' . uniqid() . '.' . $audio->getClientOriginalExtension();
                $audioPath = base_path('media/audios');
                $audio->move(base_path($audioPath), $audioName);
                $audioPath = $audioName;
            }

            $meditation = Meditation::create([
                'title' => $request->title,
                'type' => $request->type,
                'description' => $request->description,
                'image' => $imagePath,
                'audio' => $audioPath,
                'duration' => $request->duration,
            ]);

            return response()->json([
                'error' => false,
                'message' => 'Meditation added successfully',
                'records' => $meditation,
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Something went wrong: ' . $e->getMessage(),
            ], 200);
        }
    }
}
