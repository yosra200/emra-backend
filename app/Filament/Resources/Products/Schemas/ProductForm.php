<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('panel.product.sections.details'))
                    ->schema([
                        Tabs::make('translations')
                            ->tabs([
                                Tabs\Tab::make(__('panel.language.ar'))
                                    ->schema([
                                        TextInput::make('name_ar')
                                            ->label(__('panel.product.fields.name_ar'))
                                            ->required()
                                            ->maxLength(255),

                                        Textarea::make('description_ar')
                                            ->label(__('panel.product.fields.description_ar'))
                                            ->rows(5),
                                    ]),

                                Tabs\Tab::make(__('panel.language.en'))
                                    ->schema([
                                        TextInput::make('name_en')
                                            ->label(__('panel.product.fields.name_en'))
                                            ->required()
                                            ->maxLength(255)
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(function (Get $get, Set $set, ?string $state): void {
                                                if (blank($get('slug')) && filled($state)) {
                                                    $set('slug', Str::slug($state));
                                                }
                                            }),

                                        Textarea::make('description_en')
                                            ->label(__('panel.product.fields.description_en'))
                                            ->rows(5),
                                    ]),
                            ])
                            ->columnSpanFull(),

                        TextInput::make('slug')
                            ->label(__('panel.product.fields.slug'))
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->helperText(__('panel.product.hints.slug'))
                            ->columnSpanFull(),

                        Select::make('category_id')
                            ->label(__('panel.product.fields.category'))
                            ->relationship('category', 'name_ar')
                            ->searchable(['name_ar', 'name_en'])
                            ->preload()
                            ->createOptionForm([
                                TextInput::make('name_ar')
                                    ->label(__('panel.category.fields.name_ar'))
                                    ->required()
                                    ->maxLength(255),

                                TextInput::make('name_en')
                                    ->label(__('panel.category.fields.name_en'))
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (Get $get, Set $set, ?string $state): void {
                                        if (blank($get('slug')) && filled($state)) {
                                            $set('slug', Str::slug($state));
                                        }
                                    }),

                                TextInput::make('slug')
                                    ->label(__('panel.category.fields.slug'))
                                    ->required()
                                    ->maxLength(255)
                                    ->unique('categories', 'slug'),
                            ])
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make(__('panel.product.sections.pricing'))
                    ->schema([
                        TextInput::make('price')
                            ->label(__('panel.product.fields.price'))
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->step(0.01)
                            ->prefix('EGP')
                            ->live(onBlur: true),

                        TextInput::make('sale_price')
                            ->label(__('panel.product.fields.sale_price'))
                            ->numeric()
                            ->minValue(0)
                            ->step(0.01)
                            ->prefix('EGP')
                            ->lt('price')
                            ->live(onBlur: true)
                            ->helperText(function (Get $get): string {
                                $price = (float) $get('price');
                                $salePrice = (float) $get('sale_price');

                                if ($price <= 0 || $salePrice <= 0 || $salePrice >= $price) {
                                    return __('panel.product.hints.sale_price');
                                }

                                return __('panel.product.hints.discount_preview', [
                                    'percent' => (int) round((($price - $salePrice) / $price) * 100),
                                ]);
                            }),
                    ])
                    ->columns(2),

                Section::make(__('panel.product.sections.visibility'))
                    ->schema([
                        Toggle::make('is_active')
                            ->label(__('panel.product.fields.is_active'))
                            ->helperText(__('panel.product.hints.is_active'))
                            ->default(true),

                        Toggle::make('is_best_seller')
                            ->label(__('panel.product.fields.is_best_seller')),

                        Toggle::make('is_new_arrival')
                            ->label(__('panel.product.fields.is_new_arrival')),

                        Toggle::make('is_featured')
                            ->label(__('panel.product.fields.is_featured')),
                    ])
                    ->columns(2),

                Section::make(__('panel.product.sections.images'))
                    ->description(__('panel.product.hints.images'))
                    ->schema([
                        Repeater::make('images')
                            ->relationship()
                            ->hiddenLabel()
                            ->schema([
                                FileUpload::make('image')
                                    ->label(__('panel.product.fields.image'))
                                    ->image()
                                    ->disk('public')
                                    ->directory('products')
                                    ->imageEditor()
                                    ->maxSize(5120)
                                    ->required(),
                            ])
                            ->addActionLabel(__('panel.product.actions.add_image'))
                            ->defaultItems(0)
                            ->orderColumn('sort_order')
                            ->reorderableWithButtons()
                            ->collapsible(),
                    ]),

                Section::make(__('panel.product.sections.variants'))
                    ->description(__('panel.product.hints.variants'))
                    ->schema([
                        Repeater::make('variants')
                            ->relationship()
                            ->hiddenLabel()
                            ->schema([
                                Select::make('color_id')
                                    ->label(__('panel.variant.fields.color'))
                                    ->relationship('color', 'name_ar')
                                    ->searchable(['name_ar', 'name_en'])
                                    ->preload()
                                    ->required()
                                    ->distinct()
                                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                    ->createOptionForm([
                                        TextInput::make('name_ar')
                                            ->label(__('panel.color.fields.name_ar'))
                                            ->required()
                                            ->maxLength(255),

                                        TextInput::make('name_en')
                                            ->label(__('panel.color.fields.name_en'))
                                            ->required()
                                            ->maxLength(255),

                                        TextInput::make('code')
                                            ->label(__('panel.color.fields.code'))
                                            ->helperText(__('panel.color.hints.code'))
                                            ->maxLength(255),
                                    ]),

                                TextInput::make('sku')
                                    ->label(__('panel.variant.fields.sku'))
                                    ->required()
                                    ->maxLength(255)
                                    ->unique('product_variants', 'sku', ignoreRecord: true)
                                    ->default(fn (): string => Str::upper(Str::random(8))),

                                TextInput::make('stock')
                                    ->label(__('panel.variant.fields.stock'))
                                    ->required()
                                    ->integer()
                                    ->minValue(0)
                                    ->default(0),
                            ])
                            ->columns(3)
                            ->addActionLabel(__('panel.product.actions.add_variant'))
                            ->defaultItems(0)
                            ->reorderable(false)
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['sku'] ?? null),
                    ]),
            ]);
    }
}
