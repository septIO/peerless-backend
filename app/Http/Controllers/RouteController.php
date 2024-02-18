<?php

namespace App\Http\Controllers;

use App\Models\Key;
use Illuminate\Http\Request;

class RouteController extends Controller
{
    public function WoWAuditRaids(Request $request)
    {
        $client = new \GuzzleHttp\Client();

        $key = $request->user()->keys()->whereName('wowaudit')->first();
        if(!$key) {
            return response()->json(['message' => 'No key found'], 401);
        }

        $response = $client->request('GET', 'https://www.wowaudit.com/api/v1/raids', [
            'headers' => [
                'Authorization' => $key->key,
            ],
        ]);

        return $response->getBody();
    }

    public function WoWAuditCharacter($raidID)
    {

    }

    public function storeKey(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'key' => 'required',
        ]);
        $user = $request->user();

        Key::updateOrCreate([
            'name' => $request->name,
            'user_id' => $user->id,
        ], [
            'user_id' => $user->id,
            'key' => $request->key,
        ]);

        return response()->json(['message' => 'Key saved'], 201);
    }

    private function send()
    {

    }
}
