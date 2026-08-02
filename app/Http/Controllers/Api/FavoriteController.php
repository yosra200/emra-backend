<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use App\Models\Favorite;
use App\Models\Product;
use App\Http\Requests\FavoriteRequest;

class FavoriteController extends Controller
{
    use ApiResponse;
    public function addToFavorites(FavoriteRequest $request)
    {
        $data = [
            'product_id' => $request->product_id,
        ];

        if ($user = $request->user()) {
            $data['user_id'] = $user->id;
        } else {
            $guestToken = $request->header('Guest-Token');

            if (!$guestToken) {
                return $this->errorResponse('Guest token is required.', 400);
            }

            $data['guest_token'] = $guestToken;
        }

        Favorite::firstOrCreate($data);

        return $this->successResponse('Product added to favorites successfully.');
    }

    public function cancleFavorite(Request $request, $productId)
    {
        if ($user = $request->user()) {
            Favorite::where('user_id', $user->id)
                ->where('product_id', $productId)
                ->delete();
        } else {
            $guestToken = $request->header('Guest-Token');

            if (!$guestToken) {
                return $this->errorResponse('Guest token is required.', 400);
            }

            Favorite::where('guest_token', $guestToken)
                ->where('product_id', $productId)
                ->delete();
        }

        return $this->successResponse('Product removed from favorites successfully.');
    }
}
