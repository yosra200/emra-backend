<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use ApiResponse;

    /**
 * @OA\Post(
 *     path="/api/register",
 *     tags={"Authentication"},
 *     summary="Register",
 *     description="Create a new user account",
 *
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name","email","password","password_confirmation"},
 *             @OA\Property(property="name", type="string", example="Yosra"),
 *             @OA\Property(property="email", type="string", format="email", example="yosra@gmail.com"),
 *             @OA\Property(property="password", type="string", format="password", example="12345678"),
 *             @OA\Property(property="password_confirmation", type="string", format="password", example="12345678")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=201,
 *         description="Registered successfully"
 *     )
 * )
 */
    public function register(RegisterRequest $request)
    {
        $user = User::create($request->validated());

        $token = $user->createToken('react-app')->plainTextToken;

        return $this->successResponse(
            [
                'user' => $user,
                'token' => $token,
            ],
            __('messages.register_success'),
            201
        );
    }
/**
 * @OA\Post(
 *     path="/api/login",
 *     tags={"Authentication"},
 *     summary="Login",
 *     description="Login user and return access token",
 *
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email","password"},
 *             @OA\Property(property="email", type="string", format="email", example="yosra@gmail.com"),
 *             @OA\Property(property="password", type="string", format="password", example="12345678")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Login successful"
 *     ),
 *
 *     @OA\Response(
 *         response=401,
 *         description="Invalid credentials"
 *     )
 * )
 */
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (!Auth::attempt($credentials)) {
            return $this->errorResponse(
                __('messages.invalid_credentials'),
                401
            );
        }

        $user = Auth::user();

        // حذف التوكنات القديمة (اختياري)
        $user->tokens()->delete();

        // إنشاء توكن جديد
        $token = $user->createToken('react-app')->plainTextToken;

        return $this->successResponse(
            [
                'user' => $user,
                'token' => $token,
            ],
            __('messages.login_success'),
            200
        );
    }

    /**
 * @OA\Post(
 *     path="/api/forgot-password",
 *     tags={"Authentication"},
 *     summary="Forgot Password",
 *     description="Send password reset link to user's email",
 *
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email"},
 *             @OA\Property(
 *                 property="email",
 *                 type="string",
 *                 format="email",
 *                 example="yosra@gmail.com"
 *             )
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Reset link sent successfully"
 *     ),
 *
 *     @OA\Response(
 *         response=422,
 *         description="User not found"
 *     )
 * )
 */
public function forgotPassword(Request $request)
{
    $request->validate([
        'email' => ['required', 'email'],
    ]);

    $status = Password::sendResetLink(
        $request->only('email')
    );

    if ($status === Password::RESET_LINK_SENT) {
        return $this->successResponse(
            [],
            __('Password reset link sent successfully.'),
            200
        );
    }

    return $this->errorResponse(
        __('We could not find a user with that email address.'),
        422
    );
}

/**
 * @OA\Post(
 *     path="/api/logout",
 *     tags={"Authentication"},
 *     summary="Logout",
 *     description="Logout authenticated user",
 *
 *     security={{"sanctum":{}}},
 *
 *     @OA\Response(
 *         response=200,
 *         description="Logged out successfully"
 *     ),
 *
 *     @OA\Response(
 *         response=401,
 *         description="Unauthenticated"
 *     )
 * )
 */
    public function logout()
    {
        Auth::user()->currentAccessToken()->delete();

        return $this->successMessage(
            __('messages.logout_success'),
            200
        );
    }
}
