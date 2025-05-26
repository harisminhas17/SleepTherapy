<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function getPackages()
    {
        $packages = Package::where('is_active', 1)->get();

        if ($packages->isEmpty()) {
            return response()->json([
                'error' => true,
                'message' => 'No packages found'
            ], 200);
        }

        return response()->json([
            'error' => false,
            'message' => 'Packages fetched successfully',
            'records' => $packages,

        ], 200);
    }
}
