<?php

namespace App\Filament\Resources\BankAccountTypeResource\Pages;

use App\Filament\Resources\BankAccountTypeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBankAccountType extends EditRecord
{
    protected static string $resource = BankAccountTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
