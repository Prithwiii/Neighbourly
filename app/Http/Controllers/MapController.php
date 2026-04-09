<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MapLocation; // optional, if you want DB points later

class MapController extends Controller
{
    
    public function homeRadius()
    {
       
        $user = Auth::user();

       
        $lat = $user->lat ?? 23.804;
        $lng = $user->lng ?? 90.362; 

        $radius = 5000; // 5 km in meters

        return response()->json([
         'lat' => $lat,
         'lng' => $lng,
         'radius' => $radius
        ]);

        
        
    }
}