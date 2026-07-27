<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Traits\ApiResponse;
use App\Http\Resources\ProductResource;
use OpenApi\Attributes as OA;

class ProductController extends Controller
{
    use ApiResponse;

    #[OA\Get(
        path: '/api/products',
        summary: 'Get All Products',
        description: 'Retrieve all products with their categories and images',
        tags: ['Products'],
        responses: [
            new OA\Response(response: 200, description: 'Products retrieved successfully')
        ]
    )]
    public function index()
    {
        $products = Product::with(['category', 'images'])->get();
        return $this->successResponse(ProductResource::collection($products), __('messages.products_retrieved'));
    }

    #[OA\Get(
        path: '/api/products/{id}',
        summary: 'Get Product Details',
        description: 'Retrieve a specific product with its category and images',
        tags: ['Products'],
        parameters: [
            new OA\Parameter(name: 'id', description: 'Product ID', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(response: 200, description: 'Product retrieved successfully'),
            new OA\Response(response: 404, description: 'Product not found')
        ]
    )]
    public function show($id)
    {
        $product = Product::with(['category', 'images'])->find($id);
        return $this->successResponse(new ProductResource($product), __('messages.product_retrieved'));
    }
}
