<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LocationController extends Controller
{
 public function update(Request $request)
 {
    $user = auth()->user();

    if (!$user) {
        return response()->json([
            'error' => 'Unauthenticated'
        ], 401);
    }

    $user->update([
        'lat' => $request->lat,
        'lng' => $request->lng
    ]);

    return response()->json(['success' => true]);
 }
}