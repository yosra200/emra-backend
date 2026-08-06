<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use Filament\Resources\Pages\CreateRecord;
use App\Traits\Uploadable;

class CreateProduct extends CreateRecord
{
    use Uploadable;

    protected static string $resource = ProductResource::class;


    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (! empty($this->data['image'])) {
            $data['image'] = $this->uploadFile(
                $this->data['image'],
                'products'
            );
        }

        return $data;
    }
}
