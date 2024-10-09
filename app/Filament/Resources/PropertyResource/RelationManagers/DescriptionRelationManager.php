<?php

namespace App\Filament\Resources\PropertyResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DescriptionRelationManager extends RelationManager
{
    protected static string $relationship = 'description';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('bedroom')
                    ->label('Bedroom')
                    ->numeric()
                    ->default(0)
                    ->required(),
                TextInput::make('bathroom')
                    ->label('Bathroom')
                    ->numeric()
                    ->default(0)
                    ->required(),
                TextInput::make('garage')
                    ->label('Garage')
                    ->numeric()
                    ->default(0)
                    ->required(),
                TextInput::make('floor_size')
                    ->label('Floor Size')
                    ->numeric()
                    ->default(0)
                    ->required(),
                TextInput::make('property_size')
                    ->label('Property Size')
                    ->numeric()
                    ->default(0)
                    ->required(),
                TextInput::make('parking')
                    ->label('Parking')
                    ->numeric()
                    ->default(0)
                    ->required(),

                Grid::make(1)
                    ->schema([
                        Checkbox::make('pet_friendly')
                            ->label('Pet Friendly'),
                        Checkbox::make('garden')
                            ->label('Garden'),
                        TextInput::make('description')
                            ->label('Description')
                            ->maxLength(255)
                            ->nullable(),
                        Section::make('External Features')->description('Features outside the property for example a swimming pool or electric fencing')
                            ->schema([
                                Forms\Components\Repeater::make('external_features')
                                    ->schema([
                                        TextInput::make('feature')
                                            ->label('External Feature'),
                                    ])
                                    ->nullable(),
                            ]),
                        Section::make('Other Features')->description('Other features of the property such as 24/7 security, alarm system and CCTV')
                            ->schema([
                                Forms\Components\Repeater::make('other_features')
                                    ->schema([
                                        TextInput::make('feature')
                                            ->label('Other Feature'),
                                    ])
                                    ->nullable(),
                            ]),
                        Section::make('Points of Interest')->description('Points of interest surrounding the property')
                            ->schema([
                                Forms\Components\Repeater::make('points_of_interest')
                                    ->schema([
                                        TextInput::make('point')
                                            ->label('Point of Interest'),
                                    ])
                                    ->nullable(),
                            ]),
                    ]),

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('property.name')
            ->columns([
                Tables\Columns\TextColumn::make('property.name')
                    ->label('Property')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('bedroom')
                    ->label('Bedroom')
                    ->sortable(),
                Tables\Columns\TextColumn::make('bathroom')
                    ->label('Bathroom')
                    ->sortable(),
                Tables\Columns\TextColumn::make('garage')
                    ->label('Garage')
                    ->sortable(),
                Tables\Columns\TextColumn::make('floor_size')
                    ->label('Floor Size')
                    ->sortable(),
                Tables\Columns\TextColumn::make('property_size')
                    ->label('Property Size')
                    ->sortable(),
                Tables\Columns\BooleanColumn::make('pet_friendly')
                    ->label('Pet Friendly'),
                Tables\Columns\BooleanColumn::make('garden')
                    ->label('Garden'),
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
