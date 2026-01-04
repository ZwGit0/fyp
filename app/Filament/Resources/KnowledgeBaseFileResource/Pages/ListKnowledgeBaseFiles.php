<?php

namespace App\Filament\Resources\KnowledgeBaseFileResource\Pages;

use App\Filament\Resources\KnowledgeBaseFileResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKnowledgeBaseFiles extends ListRecords
{
    protected static string $resource = KnowledgeBaseFileResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
