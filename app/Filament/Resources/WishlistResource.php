<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WishlistResource\Pages;
use App\Models\Priority;
use App\Models\Status;
use App\Models\Wishlist;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class WishlistResource extends Resource
{
    protected static ?string $model = Wishlist::class;

    protected static ?string $slug = 'wishlists';

    protected static ?string $navigationGroup = 'Development';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([//
                TextInput::make('name')
                    ->required(),

                Textarea::make('description')
                    ->required()
                ->rows(8),

                Select::make('status_id')
                    ->label('Status')
                    ->relationship('status', 'name')
                    ->required(),

                Select::make('priority_id')
                    ->label('Priority')
                    ->relationship('priority', 'name')
                    ->required()
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                SelectColumn::make('status_id')
                    ->label('Status')
                    ->options(Status::all()->pluck('name', 'id')->toArray())
                    ->sortable()->searchable(),

                SelectColumn::make('priority_id')
                    ->label('Priority')
                    ->options(Priority::all()->pluck('name', 'id')->toArray())
                    ->sortable()->searchable(),

                TextColumn::make('created_at')
                ->label('Created')
                ->dateTime(),
                TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime()
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->multiple()
                    ->relationship('status', 'name')->searchable()->preload(),
                SelectFilter::make('priority')
                    ->label('Priority')
                    ->multiple()
                    ->relationship('priority', 'name')->searchable()->preload(),
            ])
            ->persistFiltersInSession()
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWishlists::route('/'),
            'create' => Pages\CreateWishlist::route('/create'),
            'edit' => Pages\EditWishlist::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name'];
    }
}
