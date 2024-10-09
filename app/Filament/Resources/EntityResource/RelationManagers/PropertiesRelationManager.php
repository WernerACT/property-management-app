<?php

namespace App\Filament\Resources\EntityResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PropertiesRelationManager extends RelationManager
{
    protected static string $relationship = 'properties';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('entity_id')
                    ->label('Entity')
                    ->relationship('entity', 'nickname')
                    ->required(),
                Forms\Components\TextInput::make('nickname')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('purchase_value')
                    ->label('Purchase Value')
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('current_value')
                    ->label('Current Value')
                    ->numeric()
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('nickname')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('entity.nickname')
                    ->label('Entity')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('current_value')
                    ->label('Property Value')
                    ->money('ZAR')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
