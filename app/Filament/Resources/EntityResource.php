<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EntityResource\Pages;
use App\Models\Entity;
use App\Models\Property;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class EntityResource extends Resource
{
    protected static ?string $model = Entity::class;

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('id_number')
                    ->label('ID Number')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('nickname')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(fn (Builder $query) => Entity::query()->withPropertiesCount()->withPortfolioValue())
            ->columns([
                Tables\Columns\TextColumn::make('nickname')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('properties_count')
                    ->label('Properties')
                    ->sortable(),
                Tables\Columns\TextColumn::make('properties_sum_current_value')
                    ->label('Portfolio Value')
                    ->money('ZAR')
                    ->sortable(),
            ])
            ->filters([
                // Add filters if needed
            ])
            ->persistFiltersInSession()
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            EntityResource\RelationManagers\PropertiesRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEntities::route('/'),
            'create' => Pages\CreateEntity::route('/create'),
            'edit' => Pages\EditEntity::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            EntityResource\Widgets\EntityOverview::class,
        ];
    }
}
