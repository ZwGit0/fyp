<?php

namespace App\Filament\Resources\KnowledgeBaseFileResource\Pages;

use App\Filament\Resources\KnowledgeBaseFileResource;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class EditKnowledgeBaseFile extends EditRecord
{
    protected static string $resource = KnowledgeBaseFileResource::class;

    protected function afterDelete(): void
    {
        $record = $this->record;

        // Botpress API details
        $botpressApiToken = config('services.botpress.api_token');
        $botpressBotId = config('services.botpress.bot_id');
        $botpressWorkspaceId = config('services.botpress.workspace_id');
        $fileId = $record->botpress_kb_id;

        // Delete the file from Botpress
        if ($fileId) {
            $botpressUrl = "https://api.botpress.cloud/v1/files/{$fileId}";
            $response = Http::withToken($botpressApiToken)
                ->withHeaders([
                    'x-workspace-id' => $botpressWorkspaceId,
                    'x-bot-id' => $botpressBotId,
                ])
                ->delete($botpressUrl);

            if (!$response->successful()) {
                \Filament\Notifications\Notification::make()
                    ->title('Delete Failed')
                    ->body('Failed to delete file from Botpress. Error: ' . $response->body())
                    ->danger()
                    ->send();
                return;
            }
        }

        // Delete the file from Laravel storage
        if (Storage::exists($record->file_path)) {
            Storage::delete($record->file_path);
        }

        \Filament\Notifications\Notification::make()
            ->title('File Deleted')
            ->body('The file has been successfully deleted.')
            ->success()
            ->send();
    }
}