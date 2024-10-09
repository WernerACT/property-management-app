<?php

namespace App\Filament\Resources\BankAccountTypeResource\Pages;

use App\Filament\Resources\BankAccountTypeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBankAccountType extends CreateRecord
{
    protected static string $resource = BankAccountTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
