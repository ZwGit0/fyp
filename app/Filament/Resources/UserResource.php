<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('email')->sortable()->searchable(),
                BadgeColumn::make('is_deleted')
                    ->label('Status')
                    ->getStateUsing(function ($record) {
                        return $record->trashed() ? 'Deleted' : 'Active';
                    })
                    ->colors([
                        'danger' => function ($state) {
                            return $state === 'Deleted';
                        },
                        'success' => function ($state) {
                            return $state === 'Active';
                        },
                    ])
                    ]);
    }
    
    public static function getEloquentQuery(): Builder
    {
        return User::withoutGlobalScopes()->withTrashed(); 
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
        ];
    }
}
