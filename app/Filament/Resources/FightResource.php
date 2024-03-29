<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FightResource\Pages;
use App\Filament\Resources\FightResource\RelationManagers;
use App\Models\Fight;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FightResource extends Resource
{
    protected static ?string $model = Fight::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('encounter_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Repeater::make('data')
                    ->schema([
                        Forms\Components\Select::make('ability') // TODO: Add a select for abilities
                        ->required()
                            ->columns(1)
                            ->options(
                                \App\Models\BossSpell::all()->pluck('name', 'id')->toArray()
                            ),
                        Forms\Components\TextInput::make('time')
                            ->required()
                            ->columns(1)
                            ->hint('Add modifier as needed, e.g. SCS, SAA, etc. 12,SAA:12345. Time is in seconds.'),
                    ])->cloneable()->columns(2),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('encounter_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFights::route('/'),
            'create' => Pages\CreateFight::route('/create'),
            'edit' => Pages\EditFight::route('/{record}/edit'),
        ];
    }
}
