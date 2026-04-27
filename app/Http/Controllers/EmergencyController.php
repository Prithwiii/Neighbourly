<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\EmergencyNotification;


class EmergencyController extends Controller
{
    public function trigger(Request $request)
    { 
        \Log::info('EMERGENCY HIT', [
          'user_id' => auth()->id(),
          'request' => $request->all()
]       );
        
        $user = auth()->user();

        $lat = $request->lat;
        $lng = $request->lng;

        // update sender location
        $user->update([
            'lat' => $lat,
            'lng' => $lng
        ]);

        $users = User::where('id', '!=', $user->id)->get();

        foreach ($users as $u) {

            if (!$u->lat || !$u->lng) continue;

            $distance = $this->distance($lat, $lng, $u->lat, $u->lng);

            if ($distance <= 1) { // 1 km radius

                $u->notify(new EmergencyNotification($user));
            }
        }

        return response()->json(['success' => true]);
    }

    private function distance($lat1, $lon1, $lat2, $lon2)
    {
        $earth = 6371;

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a =
            sin($dLat/2) * sin($dLat/2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon/2) * sin($dLon/2);

        $c = 2 * atan2(sqrt($a), sqrt(1-$a));

        return $earth * $c;
    }
}