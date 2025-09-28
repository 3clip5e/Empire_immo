<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GeoController extends Controller
{
    /**
     * Rechercher une adresse au Cameroun.
     */
    public function search(Request $request)
    {
        $query = $request->input('q');
        if (!$query) return response()->json([]);

        $res = Http::withHeaders([
            'User-Agent' => 'MonApp/1.0 (+http://mon-site.com)'
        ])->get('https://nominatim.openstreetmap.org/search', [
            'q' => $query . ', Cameroon',
            'format' => 'json',
            'addressdetails' => 1,
            'limit' => 10,
            'countrycodes' => 'cm',
        ]);

        $data = collect($res->json())->filter(function($item) {
            return in_array($item['type'] ?? '', [
                'city','town','village','suburb','quarter','neighbourhood','hamlet','locality','residential'
            ]);
        })->values();

        return response()->json($data);
    }

    /**
     * Reverse geocoding pour lat/lng.
     */
    public function reverse(Request $request)
    {
        $lat = $request->input('lat');
        $lng = $request->input('lng');

        if (!$lat || !$lng) return response()->json(['error'=>'Lat et Lng requis'], 400);

        $res = Http::withHeaders([
            'User-Agent' => 'MonApp/1.0 (+http://mon-site.com)'
        ])->get('https://nominatim.openstreetmap.org/reverse', [
            'lat' => $lat,
            'lon' => $lng,
            'format' => 'json',
            'addressdetails' => 1,
        ]);

        return response()->json($res->json());
    }
}
