<?php

namespace Yemenpoint\FilamentCustomFields\Resources\CustomFieldResource\Pages;

use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Yemenpoint\FilamentCustomFields\Resources\CustomFieldResource;

class ListCustomFields extends ListRecords
{
    protected static string $resource = CustomFieldResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
