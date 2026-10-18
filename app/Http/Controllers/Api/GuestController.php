<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Support\Str;
use OpenApi\Attributes as OA;

class GuestController extends Controller
{
    use ApiResponse;

    #[OA\Post(
        path: '/api/guest-token',
        summary: 'Generate Guest Token',
        description: 'Generate a unique guest identifier. The client stores it and sends it back in the Guest-Token header on the favorites and cart endpoints so a visitor keeps their data before signing up.',
        tags: ['Guest'],
        responses: [
            new OA\Response(response: 200, description: 'Guest token generated successfully')
        ]
    )]
    public function generateToken()
    {
        return $this->successResponse(
            ['guest_token' => (string) Str::uuid()],
            __('messages.guest_token_generated')
        );
    }
}
