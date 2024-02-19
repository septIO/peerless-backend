<?php

namespace App\Http\Controllers;

use App\Models\Key;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RouteController extends Controller
{
    public function WoWAuditRaids(Request $request)
    {
        $key = $request->user()->keys()->whereName('wowaudit')->first();
        if (!$key) {
            return response()->json(['message' => 'No key found'], 401);
        }

        $response = Http::withHeaders([
            'Authorization' => 'c9ad37b8c325688929606c96d925ba413a07307087737c7a0a76cc887f23e635',
        ])
            ->accept('application/json')
            ->get('https://wowaudit.com/v1/raids');

        return $response;
    }

    public function WoWAuditCharacter($raidID)
    {

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
