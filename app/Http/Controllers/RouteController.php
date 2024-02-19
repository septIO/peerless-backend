<?php

namespace App\Http\Controllers;

use App\Models\Key;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RouteController extends Controller
{
    public static function key($name)
    {
        $key = \request()->user()->keys()->whereName($name)->first();
        if (!$key) {
            return response()->json(['message' => 'No key found'], 401);
        }
        return $key->key;
    }

    public function WoWAuditRaids(Request $request)
    {
        $response = Http::withHeaders([
            'Authorization' => static::key('wowaudit'),
        ])
            ->accept('application/json')
            ->get('https://wowaudit.com/v1/raids');

        return $response->json();
    }

    public function WoWAuditRaid(Request $request, $id)
    {
        $response = Http::withHeaders([
            'Authorization' => static::key('wowaudit'),
        ])
            ->accept('application/json')
            ->get('https://wowaudit.com/v1/raids/' . $id);

        return $response->json();
    }

    public function storeKey(Request $request)
    {
        $request->validate([
            'key' => 'required',
            'value' => 'required',
        ]);
        $user = $request->user();

        Key::updateOrCreate([
            'name' => $request->key,
            'user_id' => $user->id,
        ], [
            'user_id' => $user->id,
            'key' => $request->value,
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'Key saved'], 201);
    }

    public function getKeys(Request $request)
    {
        $user = $request->user();
        $keys = $user->keys()->get()->map(function ($key) {
            return [
                'name' => $key->name,
                'value' => $key->key,
            ];
        });
        return response()->json($keys);
    }

    private function send()
    {

    }
}
