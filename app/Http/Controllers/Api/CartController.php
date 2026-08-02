<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CartRequest;
use App\Http\Requests\UpdateCartItemRequest;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Models\ProductVariant;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class CartController extends Controller
{
    use ApiResponse;

    /**
     * Relations needed to build a full cart response.
     */
    private const ITEM_RELATIONS = [
        'items.productVariant.color',
        'items.productVariant.product.category',
        'items.productVariant.product.images',
    ];

    #[OA\Get(
        path: '/api/cart',
        summary: 'Get Cart',
        description: 'Retrieve the cart of the authenticated user, or of a guest identified by the Guest-Token header. Returns an empty cart when none exists yet.',
        tags: ['Cart'],
        parameters: [
            new OA\Parameter(name: 'Guest-Token', description: 'Guest identifier, required when the request is not authenticated', in: 'header', required: false, schema: new OA\Schema(type: 'string'))
        ],
        responses: [
            new OA\Response(response: 200, description: 'Cart retrieved successfully'),
            new OA\Response(response: 400, description: 'Guest token is required')
        ]
    )]
    public function getCart(Request $request)
    {
        $owner = $this->owner($request);

        if (! $owner) {
            return $this->errorResponse(__('messages.guest_token_required'), 400);
        }

        $cart = Cart::where($owner)->with(self::ITEM_RELATIONS)->first();

        if (! $cart) {
            $cart = (new Cart($owner))->setRelation('items', collect());
        }

        return $this->successResponse(new CartResource($cart), __('messages.cart_retrieved'));
    }

    #[OA\Post(
        path: '/api/cart',
        summary: 'Add To Cart',
        description: 'Add a product variant to the cart. When the variant is already in the cart its quantity is increased instead of adding a second line. The cart is created on the fly if it does not exist.',
        tags: ['Cart'],
        parameters: [
            new OA\Parameter(name: 'Guest-Token', description: 'Guest identifier, required when the request is not authenticated', in: 'header', required: false, schema: new OA\Schema(type: 'string'))
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['product_variant_id'],
                properties: [
                    new OA\Property(property: 'product_variant_id', description: 'Product variant ID', type: 'integer'),
                    new OA\Property(property: 'quantity', description: 'Quantity to add, defaults to 1', type: 'integer', default: 1),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Product added to cart successfully'),
            new OA\Response(response: 400, description: 'Guest token is required'),
            new OA\Response(response: 422, description: 'Validation error or not enough stock')
        ]
    )]
    public function addToCart(CartRequest $request)
    {
        $owner = $this->owner($request);

        if (! $owner) {
            return $this->errorResponse(__('messages.guest_token_required'), 400);
        }

        $variant = ProductVariant::findOrFail($request->product_variant_id);
        $quantity = (int) ($request->quantity ?? 1);

        $cart = Cart::firstOrCreate($owner);

        $item = $cart->items()->firstOrNew(['product_variant_id' => $variant->id]);
        $newQuantity = (int) $item->quantity + $quantity;

        if ($newQuantity > $variant->stock) {
            return $this->errorResponse(__('messages.insufficient_stock', ['stock' => $variant->stock]), 422);
        }

        $item->quantity = $newQuantity;
        $item->save();

        return $this->successResponse($this->cartData($cart), __('messages.cart_item_added'));
    }

    #[OA\Put(
        path: '/api/cart/{itemId}',
        summary: 'Update Cart Item',
        description: 'Set the quantity of a cart item. The item must belong to the cart of the authenticated user or of the guest identified by the Guest-Token header.',
        tags: ['Cart'],
        parameters: [
            new OA\Parameter(name: 'itemId', description: 'Cart item ID', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
            new OA\Parameter(name: 'Guest-Token', description: 'Guest identifier, required when the request is not authenticated', in: 'header', required: false, schema: new OA\Schema(type: 'string'))
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['quantity'],
                properties: [new OA\Property(property: 'quantity', description: 'New quantity', type: 'integer', minimum: 1)]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Cart updated successfully'),
            new OA\Response(response: 400, description: 'Guest token is required'),
            new OA\Response(response: 404, description: 'Cart item not found'),
            new OA\Response(response: 422, description: 'Validation error or not enough stock')
        ]
    )]
    public function updateCartItem(UpdateCartItemRequest $request, $itemId)
    {
        $owner = $this->owner($request);

        if (! $owner) {
            return $this->errorResponse(__('messages.guest_token_required'), 400);
        }

        $cart = Cart::where($owner)->first();
        $item = $cart?->items()->with('productVariant')->find($itemId);

        if (! $item) {
            return $this->errorResponse(__('messages.cart_item_not_found'), 404);
        }

        $quantity = (int) $request->quantity;
        $stock = (int) $item->productVariant->stock;

        if ($quantity > $stock) {
            return $this->errorResponse(__('messages.insufficient_stock', ['stock' => $stock]), 422);
        }

        $item->update(['quantity' => $quantity]);

        return $this->successResponse($this->cartData($cart), __('messages.cart_item_updated'));
    }

    #[OA\Delete(
        path: '/api/cart/{itemId}',
        summary: 'Remove Cart Item',
        description: 'Remove a single item from the cart of the authenticated user, or of the guest identified by the Guest-Token header.',
        tags: ['Cart'],
        parameters: [
            new OA\Parameter(name: 'itemId', description: 'Cart item ID', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
            new OA\Parameter(name: 'Guest-Token', description: 'Guest identifier, required when the request is not authenticated', in: 'header', required: false, schema: new OA\Schema(type: 'string'))
        ],
        responses: [
            new OA\Response(response: 200, description: 'Product removed from cart successfully'),
            new OA\Response(response: 400, description: 'Guest token is required'),
            new OA\Response(response: 404, description: 'Cart item not found')
        ]
    )]
    public function removeCartItem(Request $request, $itemId)
    {
        $owner = $this->owner($request);

        if (! $owner) {
            return $this->errorResponse(__('messages.guest_token_required'), 400);
        }

        $cart = Cart::where($owner)->first();
        $item = $cart?->items()->find($itemId);

        if (! $item) {
            return $this->errorResponse(__('messages.cart_item_not_found'), 404);
        }

        $item->delete();

        return $this->successResponse($this->cartData($cart), __('messages.cart_item_removed'));
    }

    #[OA\Delete(
        path: '/api/cart',
        summary: 'Clear Cart',
        description: 'Remove every item from the cart of the authenticated user, or of the guest identified by the Guest-Token header.',
        tags: ['Cart'],
        parameters: [
            new OA\Parameter(name: 'Guest-Token', description: 'Guest identifier, required when the request is not authenticated', in: 'header', required: false, schema: new OA\Schema(type: 'string'))
        ],
        responses: [
            new OA\Response(response: 200, description: 'Cart cleared successfully'),
            new OA\Response(response: 400, description: 'Guest token is required')
        ]
    )]
    public function clearCart(Request $request)
    {
        $owner = $this->owner($request);

        if (! $owner) {
            return $this->errorResponse(__('messages.guest_token_required'), 400);
        }

        Cart::where($owner)->first()?->items()->delete();

        return $this->successMessage(__('messages.cart_cleared'));
    }

    /**
     * The column/value pair identifying the cart owner, or null when a guest
     * request arrives without a Guest-Token header.
     *
     * @return array<string, mixed>|null
     */
    private function owner(Request $request): ?array
    {
        if ($user = $request->user('sanctum')) {
            return ['user_id' => $user->id];
        }

        $guestToken = $request->header('Guest-Token');

        return $guestToken ? ['guest_token' => $guestToken] : null;
    }

    /**
     * Reload the cart with everything the response needs.
     */
    private function cartData(Cart $cart): CartResource
    {
        return new CartResource($cart->load(self::ITEM_RELATIONS));
    }
}
