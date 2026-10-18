<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use App\Models\Favorite;
use App\Http\Requests\FavoriteRequest;
use App\Http\Resources\FavoriteResource;
use OpenApi\Attributes as OA;

class FavoriteController extends Controller
{
    use ApiResponse;

    #[OA\Get(
        path: '/api/favorites',
        summary: 'Get Favorites',
        description: 'Retrieve the favorites list of the authenticated user, or of a guest identified by the Guest-Token header.',
        tags: ['Favorites'],
        parameters: [
            new OA\Parameter(name: 'Guest-Token', description: 'Guest identifier, required when the request is not authenticated', in: 'header', required: false, schema: new OA\Schema(type: 'string'))
        ],
        responses: [
            new OA\Response(response: 200, description: 'Favorites retrieved successfully'),
            new OA\Response(response: 400, description: 'Guest token is required')
        ]
    )]
    public function getFavorites(Request $request)
    {
        $query = Favorite::with(['product.category', 'product.images', 'product.variants.color']);

        if ($user = $request->user('sanctum')) {
            $query->where('user_id', $user->id);
        } else {
            $guestToken = $request->header('Guest-Token');

            if (! $guestToken) {
                return $this->errorResponse(__('messages.guest_token_required'), 400);
            }

            $query->where('guest_token', $guestToken);
        }

        $favorites = $query->latest()->get();

        return $this->successResponse(
            FavoriteResource::collection($favorites),
            __('messages.favorites_retrieved')
        );
    }

    #[OA\Post(
        path: '/api/favorites',
        summary: 'Add To Favorites',
        description: 'Add a product to the favorites list of the authenticated user, or of a guest identified by the Guest-Token header. Adding the same product twice does not duplicate it.',
        tags: ['Favorites'],
        parameters: [
            new OA\Parameter(name: 'Guest-Token', description: 'Guest identifier, required when the request is not authenticated', in: 'header', required: false, schema: new OA\Schema(type: 'string'))
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['product_id'],
                properties: [new OA\Property(property: 'product_id', description: 'Product ID', type: 'integer')]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Product added to favorites successfully'),
            new OA\Response(response: 400, description: 'Guest token is required'),
            new OA\Response(response: 422, description: 'Validation error')
        ]
    )]
    public function addToFavorites(FavoriteRequest $request)
    {
        $data = [
            'product_id' => $request->product_id,
        ];

        if ($user = $request->user('sanctum')) {
            $data['user_id'] = $user->id;
        } else {
            $guestToken = $request->header('Guest-Token');

            if (! $guestToken) {
                return $this->errorResponse(__('messages.guest_token_required'), 400);
            }

            $data['guest_token'] = $guestToken;
        }

        $favorite = Favorite::firstOrCreate($data);
        $favorite->load(['product.category', 'product.images', 'product.variants.color']);

        return $this->successResponse(
            new FavoriteResource($favorite),
            __('messages.favorite_added')
        );
    }

    #[OA\Delete(
        path: '/api/favorites/{productId}',
        summary: 'Remove From Favorites',
        description: 'Remove a product from the favorites list of the authenticated user, or of a guest identified by the Guest-Token header.',
        tags: ['Favorites'],
        parameters: [
            new OA\Parameter(name: 'productId', description: 'Product ID', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
            new OA\Parameter(name: 'Guest-Token', description: 'Guest identifier, required when the request is not authenticated', in: 'header', required: false, schema: new OA\Schema(type: 'string'))
        ],
        responses: [
            new OA\Response(response: 200, description: 'Product removed from favorites successfully'),
            new OA\Response(response: 400, description: 'Guest token is required')
        ]
    )]
    public function cancleFavorite(Request $request, $productId)
    {
        $query = Favorite::where('product_id', $productId);

        if ($user = $request->user('sanctum')) {
            $query->where('user_id', $user->id);
        } else {
            $guestToken = $request->header('Guest-Token');

            if (! $guestToken) {
                return $this->errorResponse(__('messages.guest_token_required'), 400);
            }

            $query->where('guest_token', $guestToken);
        }

        $query->delete();

        return $this->successMessage(__('messages.favorite_removed'));
    }
}
