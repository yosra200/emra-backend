<?php

namespace App\Filament\Resources\Faqs\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;

class FaqForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('panel.faq.sections.details'))
                    ->schema([
                        // `question` and `answer` are Spatie translatable columns, so the
                        // fields write straight into the per-locale keys of the JSON value.
                        Tabs::make('translations')
                            ->tabs([
                                Tabs\Tab::make(__('panel.language.ar'))
                                    ->schema([
                                        TextInput::make('question.ar')
                                            ->label(__('panel.faq.fields.question_ar'))
                                            ->required()
                                            ->maxLength(255),

                                        Textarea::make('answer.ar')
                                            ->label(__('panel.faq.fields.answer_ar'))
                                            ->required()
                                            ->rows(6),
                                    ]),

                                Tabs\Tab::make(__('panel.language.en'))
                                    ->schema([
                                        TextInput::make('question.en')
                                            ->label(__('panel.faq.fields.question_en'))
                                            ->maxLength(255),

                                        Textarea::make('answer.en')
                                            ->label(__('panel.faq.fields.answer_en'))
                                            ->rows(6),
                                    ]),
                            ])
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
