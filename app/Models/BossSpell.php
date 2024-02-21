<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BossSpell extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'data' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if(!$model->icon){
                $url = 'https://www.wowhead.com/ptr-2/spell=' . $model->id . '/icon';
                $doc = new \DOMDocument();
                $doc->loadHTML(file_get_contents($url));
                $xpath = new \DOMXPath($doc);
                $element = $xpath->query('/html/body/div[4]/div/div/div[2]/div[2]/div[3]/div[2]/div/ins')->item(0);
            }
        });
    }


    public function boss()
    {
        return $this->belongsTo(Fight::class);
    }
}
