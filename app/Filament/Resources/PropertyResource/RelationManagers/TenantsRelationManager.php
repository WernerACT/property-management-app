<?php

namespace App\Filament\Resources\PropertyResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TenantsRelationManager extends RelationManager
{
    protected static string $relationship = 'tenants';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('tenant_type_id')
                    ->relationship('tenantType', 'name')
                    ->label('Tenant Type')
                    ->required(),

                TextInput::make('display_name')
                    ->required(),

                TextInput::make('name')
                    ->required(),

                TextInput::make('surname')
                    ->required(),

                TextInput::make('email')
                    ->required(),

                TextInput::make('mobile_number')
                    ->required(),

                TextInput::make('office_number'),

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('display_name')
            ->columns([
                TextColumn::make('tenantType.name'),

                TextColumn::make('display_name'),

                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('surname'),

                TextColumn::make('email')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('mobile_number'),

                TextColumn::make('office_number'),
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
