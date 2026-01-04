<?php
namespace App\Filament\Pages;

use Filament\Pages\Page;

class SellerChat extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chat';
    protected static string $view = 'filament.pages.seller-chat';

    public function getTitle(): string
    {
        return 'Seller Live Chat';
    }

    public function mount()
    {
        $this->receiverId = request()->query('receiverId');
    }
}