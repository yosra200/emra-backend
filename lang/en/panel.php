<?php

return [

    'navigation' => [
        'catalog' => 'Catalog',
    ],

    'product' => [
        'label' => 'Product',
        'plural_label' => 'Products',
        'sections' => [
            'details' => 'Product details',
            'pricing' => 'Pricing',
            'images' => 'Images',
            'variants' => 'Colours & stock',
        ],
        'fields' => [
            'name' => 'Name',
            'description' => 'Description',
            'category' => 'Category',
            'price' => 'Price',
            'sale_price' => 'Sale price',
            'is_active' => 'Active',
            'image' => 'Image',
            'images_count' => 'Images',
            'created_at' => 'Created at',
        ],
        'hints' => [
            'sale_price' => 'Leave empty when the product is not on sale.',
        ],
        'filters' => [
            'active_only' => 'Active only',
        ],
    ],

    'variant' => [
        'label' => 'Colour',
        'plural_label' => 'Colours',
        'fields' => [
            'color' => 'Colour',
            'sku' => 'SKU',
            'stock' => 'Stock',
        ],
    ],

    'category' => [
        'label' => 'Category',
        'plural_label' => 'Categories',
        'fields' => [
            'name' => 'Name',
            'products_count' => 'Products',
        ],
    ],

    'color' => [
        'label' => 'Colour',
        'plural_label' => 'Colours',
        'fields' => [
            'name' => 'Name',
            'code' => 'Hex code',
        ],
        'hints' => [
            'code' => 'A hex code such as #000000.',
        ],
    ],

    'language' => [
        'label' => 'Language',
        'ar' => 'العربية',
        'en' => 'English',
    ],

];
