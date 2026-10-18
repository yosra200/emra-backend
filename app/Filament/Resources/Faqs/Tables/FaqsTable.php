<?php

namespace App\Filament\Resources\Faqs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class FaqsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('question')
                    ->label(__('panel.faq.fields.question'))
                    ->wrap()
                    ->limit(90)
                    // The column holds a JSON object of translations, so searching has to
                    // look inside both languages.
                    ->searchable(query: fn (Builder $query, string $search): Builder => $query
                        ->where('question->ar', 'like', "%{$search}%")
                        ->orWhere('question->en', 'like', "%{$search}%")),

                TextColumn::make('answer')
                    ->label(__('panel.faq.fields.answer'))
                    ->wrap()
                    ->limit(120)
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label(__('panel.faq.fields.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->defaultSort('id')
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
