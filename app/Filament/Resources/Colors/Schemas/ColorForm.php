<?php

namespace App\Filament\Resources\Colors\Schemas;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ColorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('panel.color.sections.details'))
                    ->schema([
                        TextInput::make('name_ar')
                            ->label(__('panel.color.fields.name_ar'))
                            ->required()
                            ->maxLength(255),

                        TextInput::make('name_en')
                            ->label(__('panel.color.fields.name_en'))
                            ->required()
                            ->maxLength(255),

                        ColorPicker::make('code')
                            ->label(__('panel.color.fields.code'))
                            ->hex()
                            ->helperText(__('panel.color.hints.code'))
                            ->required()
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}
