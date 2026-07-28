<?php

return [

    'navigation' => [
        'catalog' => 'المتجر',
    ],

    'product' => [
        'label' => 'منتج',
        'plural_label' => 'المنتجات',
        'sections' => [
            'details' => 'بيانات المنتج',
            'pricing' => 'التسعير',
            'images' => 'الصور',
            'variants' => 'الألوان والمخزون',
        ],
        'fields' => [
            'name' => 'اسم المنتج',
            'description' => 'الوصف',
            'category' => 'التصنيف',
            'price' => 'السعر',
            'sale_price' => 'سعر التخفيض',
            'is_active' => 'مُفعّل',
            'image' => 'الصورة',
            'images_count' => 'عدد الصور',
            'created_at' => 'تاريخ الإضافة',
        ],
        'hints' => [
            'sale_price' => 'اتركه فارغًا لو مفيش تخفيض.',
        ],
        'filters' => [
            'active_only' => 'المُفعّل فقط',
        ],
    ],

    'variant' => [
        'label' => 'لون',
        'plural_label' => 'الألوان',
        'fields' => [
            'color' => 'اللون',
            'sku' => 'كود المنتج (SKU)',
            'stock' => 'المخزون',
        ],
    ],

    'category' => [
        'label' => 'تصنيف',
        'plural_label' => 'التصنيفات',
        'fields' => [
            'name' => 'اسم التصنيف',
            'products_count' => 'عدد المنتجات',
        ],
    ],

    'color' => [
        'label' => 'لون',
        'plural_label' => 'الألوان',
        'fields' => [
            'name' => 'اسم اللون',
            'code' => 'كود اللون',
        ],
        'hints' => [
            'code' => 'كود هيكس زي #000000.',
        ],
    ],

    'language' => [
        'label' => 'اللغة',
        'ar' => 'العربية',
        'en' => 'English',
    ],

];
