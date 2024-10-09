<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PropertyDocumentResource\Pages;
use App\Filament\Resources\PropertyDocumentResource\RelationManagers;
use App\Models\PropertyDocument;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PropertyDocumentResource extends Resource
{
    protected static ?string $model = PropertyDocument::class;

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('property_id')
                    ->label('Property')
                    ->relationship('property', 'name')
                    ->required(),
                FileUpload::make('document_path')
                    ->label('Document')
                    ->directory('property-documents')
                    ->openable()
                    ->downloadable()
                    ->acceptedFileTypes(['application/pdf']) // Restrict to PDF only
                    ->required(),
                TextInput::make('name')
                    ->label('Document Name')
                    ->required(),
                TextInput::make('description')
                    ->label('Description')
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Document Name')
                    ->sortable(),
                TextColumn::make('property.name')
                    ->label('Property')
                    ->sortable(),
                TextColumn::make('description')
                    ->label('Description')
                    ->sortable(),
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
            'index' => Pages\ListPropertyDocuments::route('/'),
            'create' => Pages\CreatePropertyDocument::route('/create'),
            'edit' => Pages\EditPropertyDocument::route('/{record}/edit'),
        ];
    }
}
