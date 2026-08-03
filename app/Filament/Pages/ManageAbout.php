<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ManageAbout extends SettingsPage
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedInformationCircle;

    protected static ?int $navigationSort = 1;

    protected function settingsKeys(): array
    {
        return ['about_us_ar', 'about_us_en'];
    }

    public static function getNavigationLabel(): string
    {
        return __('panel.about.title');
    }

    public function getTitle(): string
    {
        return __('panel.about.title');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('panel.about.section'))
                    ->description(__('panel.about.hint'))
                    ->schema([
                        Tabs::make('translations')
                            ->tabs([
                                Tabs\Tab::make(__('panel.language.ar'))
                                    ->schema([
                                        Textarea::make('about_us_ar')
                                            ->label(__('panel.about.fields.about_us_ar'))
                                            ->rows(12),
                                    ]),

                                Tabs\Tab::make(__('panel.language.en'))
                                    ->schema([
                                        Textarea::make('about_us_en')
                                            ->label(__('panel.about.fields.about_us_en'))
                                            ->rows(12),
                                    ]),
                            ])
                            ->columnSpanFull(),
                    ]),
            ])
            ->statePath('data');
    }
}
