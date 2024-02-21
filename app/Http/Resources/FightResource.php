<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FightResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $o = $this->resource;
        return [
            'encounter_id' => $o->encounter_id,
            'name' => $o->name,
            'abilities' => BossSpellResource::collection($o->bossSpells),
            'data' => collect($o->data)->map(function ($value, $key) {
                $value['ability'] = (int) $value['ability'];
                return $value;
            })->values()
        ];
    }
}
