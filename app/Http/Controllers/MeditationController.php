<?php

namespace App\Http\Controllers;

use App\Models\Meditation;
use Exception;
use Illuminate\Http\Request;
use getID3;

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

            // 🔁 Format duration
            $meditation->transform(function ($item) {
                $item->duration = gmdate("i:s", $item->duration);
                return $item;
            });

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
            ]);

            // Store image
            $imagePath = null;
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $imageName = time() . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
                $uploadPath = base_path('media/images');

                if (!file_exists($uploadPath)) {
                    throw new Exception('Images directory does not exist');
                }

                $file->move($uploadPath, $imageName);
                $imagePath = $imageName;
            }

            // Store audio
            $audioPath = null;
            $durationInSeconds = null;

            if ($request->hasFile('audio')) {
                $audio = $request->file('audio');
                $audioName = time() . '_' . uniqid() . '.' . $audio->getClientOriginalExtension();
                $uploadPath = base_path('media/audios');

                if (!file_exists($uploadPath)) {
                    throw new Exception('Audios directory does not exist');
                }

                $audio->move($uploadPath, $audioName);
                $audioPath = $audioName;

                // Calculate duration using getID3
                $audioFullPath = $uploadPath . '/' . $audioName;
                $getID3 = new getID3();
                $fileInfo = $getID3->analyze($audioFullPath);

                if (isset($fileInfo['playtime_seconds'])) {
                    $durationInSeconds = round($fileInfo['playtime_seconds']); // rounded seconds

                }
            }


            $meditation = Meditation::create([
                'title' => $request->title,
                'type' => $request->type,
                'description' => $request->description,
                'image' => $imagePath,
                'audio' => $audioPath,
                'duration' => $durationInSeconds, // Calculated duration
            ]);

            return response()->json([
                'error' => false,
                'message' => 'Meditation added successfully',
                'records' => $meditation,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Something went wrong: ' . $e->getMessage(),
            ], 200);
        }
    }
}
