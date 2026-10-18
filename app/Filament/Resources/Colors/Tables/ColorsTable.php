<?php

namespace App\Filament\Resources\Colors\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ColorsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ColorColumn::make('code')
                    ->label(__('panel.color.fields.code'))
                    ->copyable(),

                TextColumn::make('name_ar')
                    ->label(__('panel.color.fields.name_ar'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('name_en')
                    ->label(__('panel.color.fields.name_en'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('variants_count')
                    ->label(__('panel.product.plural_label'))
                    ->counts('variants')
                    ->badge(),

                TextColumn::make('created_at')
                    ->label(__('panel.color.fields.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->defaultSort('name_ar')
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
