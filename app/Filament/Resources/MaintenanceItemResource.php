<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MaintenanceItemResource\Pages;
use App\Filament\Resources\MaintenanceItemResource\RelationManagers;
use App\Models\MaintenanceItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MaintenanceItemResource extends Resource
{
    protected static ?string $model = MaintenanceItem::class;

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
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
            'index' => Pages\ListMaintenanceItems::route('/'),
            'create' => Pages\CreateMaintenanceItem::route('/create'),
            'edit' => Pages\EditMaintenanceItem::route('/{record}/edit'),
        ];
    }
}
