<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KnowledgeBaseFileResource\Pages;
use App\Models\KnowledgeBaseFile;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Forms\Components;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class KnowledgeBaseFileResource extends Resource
{
    protected static ?string $model = KnowledgeBaseFile::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Components\FileUpload::make('file_path')
                    ->label('Knowledge Base File')
                    ->acceptedFileTypes(['application/pdf', 'text/plain', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                    ->directory('knowledge-base')
                    ->required()
                    ->afterStateUpdated(function ($state, callable $set, $get) {
                        if ($state) {
                            $file = $state;
                            $fileName = $file->getClientOriginalName();
                            $storedPath = $file->storeAs('knowledge-base', $fileName);
                            $filePath = Storage::path($storedPath);

                            if (!file_exists($filePath)) {
                                \Filament\Notifications\Notification::make()
                                    ->title('File Not Found')
                                    ->body('The uploaded file could not be found in the storage directory.')
                                    ->danger()
                                    ->send();
                                $set('status', 'failed');
                                return;
                            }

                            $fileContent = file_get_contents($filePath);
                            $fileSize = filesize($filePath);

                            $botpressApiToken = config('services.botpress.api_token');
                            $botpressKbId = config('services.botpress.kb_id');
                            $botpressBotId = config('services.botpress.bot_id');
                            $botpressWorkspaceId = config('services.botpress.workspace_id');
                            $botpressUrl = "https://api.botpress.cloud/v1/files";

                            $payload = [
                                'size' => $fileSize,
                                'key' => "kb-{$botpressKbId}/{$fileName}",
                                'tags' => [
                                    'source' => 'knowledge-base',
                                    'kbId' => $botpressKbId,
                                ],
                                'index' => true,
                            ];

                            $response = Http::withToken($botpressApiToken)
                                ->withHeaders([
                                    'x-workspace-id' => $botpressWorkspaceId,
                                    'x-bot-id' => $botpressBotId,
                                    'accept' => 'application/json',
                                    'content-type' => 'application/json',
                                ])
                                ->put($botpressUrl, $payload);

                            if (!$response->successful()) {
                                $set('status', 'failed');
                                \Filament\Notifications\Notification::make()
                                    ->title('Upload Failed')
                                    ->body('Failed to create file entry in Botpress. Error: ' . $response->body())
                                    ->danger()
                                    ->send();
                                return;
                            }

                            $responseData = $response->json();
                            $fileId = $responseData['file']['id'] ?? null;
                            $uploadUrl = $responseData['file']['uploadUrl'] ?? null;

                            if (!$uploadUrl) {
                                $set('status', 'failed');
                                \Filament\Notifications\Notification::make()
                                    ->title('Upload Failed')
                                    ->body('Upload URL not provided by Botpress.')
                                    ->danger()
                                    ->send();
                                return;
                            }

                            $s3Response = Http::withBody($fileContent, mime_content_type($filePath))
                                ->put($uploadUrl);

                            if ($s3Response->successful()) {
                                $set('botpress_kb_id', $fileId);
                                $set('status', 'uploaded');
                                $set('file_name', $fileName);
                            } else {
                                $set('status', 'failed');
                                \Filament\Notifications\Notification::make()
                                    ->title('Upload Failed')
                                    ->body('Failed to upload file content to S3. Error: ' . $s3Response->body())
                                    ->danger()
                                    ->send();
                            }
                        }
                    }),
                Components\TextInput::make('file_name')
                    ->disabled()
                    ->label('File Name'),
                Components\TextInput::make('botpress_kb_id')
                    ->disabled()
                    ->label('Botpress KB Document ID'),
                Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'uploaded' => 'Uploaded',
                        'failed' => 'Failed',
                    ])
                    ->disabled()
                    ->label('Status'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('file_name')->label('File Name'),
                \Filament\Tables\Columns\TextColumn::make('botpress_kb_id')->label('Botpress KB ID'),
                \Filament\Tables\Columns\TextColumn::make('status')->label('Status'),
                \Filament\Tables\Columns\TextColumn::make('created_at')->label('Uploaded At')->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                \Filament\Tables\Actions\EditAction::make(),
                \Filament\Tables\Actions\DeleteAction::make()
                    ->action(function (KnowledgeBaseFile $record) {
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

                        // Delete the record from the database
                        $record->delete();

                        \Filament\Notifications\Notification::make()
                            ->title('File Deleted')
                            ->body('The file has been successfully deleted.')
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                \Filament\Tables\Actions\DeleteBulkAction::make()
                    ->action(function ($records) {
                        foreach ($records as $record) {
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
                                        ->body('Failed to delete file ' . $record->file_name . ' from Botpress. Error: ' . $response->body())
                                        ->danger()
                                        ->send();
                                    continue;
                                }
                            }

                            // Delete the file from Laravel storage
                            if (Storage::exists($record->file_path)) {
                                Storage::delete($record->file_path);
                            }

                            // Delete the record from the database
                            $record->delete();
                        }

                        \Filament\Notifications\Notification::make()
                            ->title('Files Deleted')
                            ->body('The selected files have been successfully deleted.')
                            ->success()
                            ->send();
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKnowledgeBaseFiles::route('/'),
            'create' => Pages\CreateKnowledgeBaseFile::route('/create'),
            'edit' => Pages\EditKnowledgeBaseFile::route('/{record}/edit'),
        ];
    }
}