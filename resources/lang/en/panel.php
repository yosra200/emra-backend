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
            'visibility' => 'Visibility & badges',
            'images' => 'Images',
            'variants' => 'Colours & stock',
        ],
        'fields' => [
            'name' => 'Name',
            'name_ar' => 'Name (Arabic)',
            'name_en' => 'Name (English)',
            'description' => 'Description',
            'description_ar' => 'Description (Arabic)',
            'description_en' => 'Description (English)',
            'slug' => 'Slug',
            'category' => 'Category',
            'price' => 'Price',
            'sale_price' => 'Sale price',
            'is_active' => 'Active',
            'is_featured' => 'Featured',
            'is_best_seller' => 'Best seller',
            'is_new_arrival' => 'New arrival',
            'discount' => 'Discount',
            'image' => 'Image',
            'images_count' => 'Images',
            'variants_count' => 'Colours',
            'created_at' => 'Created at',
        ],
        'hints' => [
            'slug' => 'Used in the product URL. Lowercase English words separated by dashes.',
            'sale_price' => 'Leave empty when the product is not on sale.',
            'discount_preview' => 'Customers will see: :percent% off',
            'is_active' => 'When off, the product is hidden from the storefront.',
            'images' => 'The first image is the main one. Use the reorder buttons to change the order.',
            'variants' => 'Each colour has its own SKU and stock. A colour with zero stock shows as unavailable.',
        ],
        'actions' => [
            'add_image' => 'Add image',
            'add_variant' => 'Add colour',
        ],
        'filters' => [
            'active_only' => 'Active only',
            'on_sale' => 'On sale',
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
        'sections' => [
            'details' => 'Category details',
        ],
        'fields' => [
            'name' => 'Name',
            'name_ar' => 'Name (Arabic)',
            'name_en' => 'Name (English)',
            'slug' => 'Slug',
            'products_count' => 'Products',
            'created_at' => 'Created at',
        ],
        'hints' => [
            'slug' => 'Used in the category URL. Lowercase English words separated by dashes.',
        ],
    ],

    'color' => [
        'label' => 'Colour',
        'plural_label' => 'Colours',
        'sections' => [
            'details' => 'Colour details',
        ],
        'fields' => [
            'name' => 'Name',
            'name_ar' => 'Name (Arabic)',
            'name_en' => 'Name (English)',
            'code' => 'Hex code',
            'created_at' => 'Created at',
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
