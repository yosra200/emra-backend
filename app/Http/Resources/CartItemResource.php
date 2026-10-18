<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'quantity' => $this->quantity,
            'unit_price' => $this->unit_price,
            'total' => $this->total,
            'product' => $this->whenLoaded('productVariant', fn () => new ProductResource($this->productVariant->product)),
            'variant' => new ProductVariantResource($this->whenLoaded('productVariant')),
        ];
    }
}
