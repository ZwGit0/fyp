<?php
namespace App\Filament\Pages;

use Filament\Pages\Page;

class Chat extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chat';
    protected static string $view = 'filament.pages.chat';

    public function getTitle(): string
    {
        return 'Live Chat';
    }

    public function mount()
    {
        $this->receiverId = request()->query('receiverId');
    }
}