<?php

namespace App\Filament\Resources\BossSpellResource\Pages;

use App\Filament\Resources\BossSpellResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBossSpell extends EditRecord
{
    protected static string $resource = BossSpellResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
