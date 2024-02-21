<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BossSpellResource extends JsonResource
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
            'id' => $o->id,
            'name' => $o->name,
            'data' => $o->data,
        ];
    }
}
