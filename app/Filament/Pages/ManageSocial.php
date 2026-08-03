<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ManageSocial extends SettingsPage
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShare;

    protected static ?int $navigationSort = 2;

    protected function settingsKeys(): array
    {
        return ['instagram_url', 'facebook_url', 'tiktok_url'];
    }

    public static function getNavigationLabel(): string
    {
        return __('panel.social.title');
    }

    public function getTitle(): string
    {
        return __('panel.social.title');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('panel.social.section'))
                    ->description(__('panel.social.hint'))
                    ->schema([
                        TextInput::make('instagram_url')
                            ->label(__('panel.social.fields.instagram'))
                            ->placeholder('https://instagram.com/emra')
                            ->url()
                            ->maxLength(255),

                        TextInput::make('facebook_url')
                            ->label(__('panel.social.fields.facebook'))
                            ->placeholder('https://facebook.com/emra')
                            ->url()
                            ->maxLength(255),

                        TextInput::make('tiktok_url')
                            ->label(__('panel.social.fields.tiktok'))
                            ->placeholder('https://tiktok.com/@emra')
                            ->url()
                            ->maxLength(255),
                    ]),
            ])
            ->statePath('data');
    }
}
