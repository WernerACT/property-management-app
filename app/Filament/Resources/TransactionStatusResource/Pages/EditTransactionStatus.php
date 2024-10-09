<?php

namespace App\Filament\Resources\TransactionStatusResource\Pages;

use App\Filament\Resources\TransactionStatusResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTransactionStatus extends EditRecord
{
    protected static string $resource = TransactionStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
