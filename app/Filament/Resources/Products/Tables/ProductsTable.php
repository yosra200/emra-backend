<?php

namespace App\Filament\Resources\Products\Tables;

use App\Models\ProductImage;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('images.image')
                    ->label(__('panel.product.fields.image'))
                    ->disk(ProductImage::FOLDER)
                    ->circular()
                    ->limit(1),

                TextColumn::make('name_ar')
                    ->label(__('panel.product.fields.name_ar'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('name_en')
                    ->label(__('panel.product.fields.name_en'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('category.name_ar')
                    ->label(__('panel.product.fields.category'))
                    ->badge()
                    ->sortable()
                    ->placeholder('—'),

                TextColumn::make('price')
                    ->label(__('panel.product.fields.price'))
                    ->money('EGP')
                    ->sortable(),

                TextColumn::make('sale_price')
                    ->label(__('panel.product.fields.sale_price'))
                    ->money('EGP')
                    ->sortable()
                    ->placeholder('—'),

                TextColumn::make('discount_percentage')
                    ->label(__('panel.product.fields.discount'))
                    ->badge()
                    ->color('danger')
                    ->formatStateUsing(fn (?int $state): ?string => $state !== null ? "{$state}%" : null)
                    ->placeholder('—'),

                TextColumn::make('variants_count')
                    ->label(__('panel.product.fields.variants_count'))
                    ->counts('variants')
                    ->badge(),

                TextColumn::make('variants_sum_stock')
                    ->label(__('panel.variant.fields.stock'))
                    ->sum('variants', 'stock')
                    ->badge()
                    ->color(fn ($state): string => (int) $state > 0 ? 'success' : 'danger'),

                IconColumn::make('is_active')
                    ->label(__('panel.product.fields.is_active'))
                    ->boolean()
                    ->sortable(),

                IconColumn::make('is_best_seller')
                    ->label(__('panel.product.fields.is_best_seller'))
                    ->boolean()
                    ->sortable()
                    ->toggleable(),

                IconColumn::make('is_new_arrival')
                    ->label(__('panel.product.fields.is_new_arrival'))
                    ->boolean()
                    ->sortable()
                    ->toggleable(),

                IconColumn::make('is_featured')
                    ->label(__('panel.product.fields.is_featured'))
                    ->boolean()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label(__('panel.product.fields.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->label(__('panel.product.fields.category'))
                    ->relationship('category', 'name_ar')
                    ->searchable()
                    ->preload(),

                TernaryFilter::make('is_active')
                    ->label(__('panel.product.fields.is_active')),

                TernaryFilter::make('is_best_seller')
                    ->label(__('panel.product.fields.is_best_seller')),

                TernaryFilter::make('is_new_arrival')
                    ->label(__('panel.product.fields.is_new_arrival')),

                Filter::make('on_sale')
                    ->label(__('panel.product.filters.on_sale'))
                    ->query(fn (Builder $query): Builder => $query
                        ->whereNotNull('sale_price')
                        ->whereColumn('sale_price', '<', 'price')),
            ])
            ->defaultSort('created_at', 'desc')
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
