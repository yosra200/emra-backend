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
            'visibility' => 'الظهور والبادچات',
            'images' => 'الصور',
            'variants' => 'الألوان والمخزون',
        ],
        'fields' => [
            'name' => 'اسم المنتج',
            'name_ar' => 'اسم المنتج (عربي)',
            'name_en' => 'اسم المنتج (إنجليزي)',
            'description' => 'الوصف',
            'description_ar' => 'الوصف (عربي)',
            'description_en' => 'الوصف (إنجليزي)',
            'slug' => 'الرابط (Slug)',
            'category' => 'التصنيف',
            'price' => 'السعر',
            'sale_price' => 'سعر التخفيض',
            'is_active' => 'مُفعّل',
            'is_featured' => 'مميّز',
            'is_best_seller' => 'الأكثر مبيعاً',
            'is_new_arrival' => 'وصل حديثاً',
            'discount' => 'الخصم',
            'image' => 'الصورة',
            'images_count' => 'عدد الصور',
            'variants_count' => 'عدد الألوان',
            'created_at' => 'تاريخ الإضافة',
        ],
        'hints' => [
            'slug' => 'ده اللي بيظهر في لينك المنتج، بالإنجليزي وبشرطة بين الكلمات.',
            'sale_price' => 'اتركه فارغًا لو مفيش تخفيض.',
            'discount_preview' => 'الخصم اللي هيظهر للعميلة: :percent%',
            'is_active' => 'لو مقفول، المنتج مش هيظهر في الموقع.',
            'images' => 'أول صورة هي الصورة الرئيسية. استخدمي أزرار الترتيب لتغيير الترتيب.',
            'variants' => 'كل لون له كود ومخزون مستقل. اللون اللي مخزونه صفر هيظهر «غير متاح».',
        ],
        'actions' => [
            'add_image' => 'إضافة صورة',
            'add_variant' => 'إضافة لون',
        ],
        'filters' => [
            'active_only' => 'المُفعّل فقط',
            'on_sale' => 'عليه تخفيض',
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
        'sections' => [
            'details' => 'بيانات التصنيف',
        ],
        'fields' => [
            'name' => 'اسم التصنيف',
            'name_ar' => 'اسم التصنيف (عربي)',
            'name_en' => 'اسم التصنيف (إنجليزي)',
            'slug' => 'الرابط (Slug)',
            'products_count' => 'عدد المنتجات',
            'created_at' => 'تاريخ الإضافة',
        ],
        'hints' => [
            'slug' => 'ده اللي بيظهر في لينك التصنيف، بالإنجليزي وبشرطة بين الكلمات.',
        ],
    ],

    'color' => [
        'label' => 'لون',
        'plural_label' => 'الألوان',
        'sections' => [
            'details' => 'بيانات اللون',
        ],
        'fields' => [
            'name' => 'اسم اللون',
            'name_ar' => 'اسم اللون (عربي)',
            'name_en' => 'اسم اللون (إنجليزي)',
            'code' => 'كود اللون',
            'created_at' => 'تاريخ الإضافة',
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
