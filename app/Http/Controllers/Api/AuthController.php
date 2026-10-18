<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\forgotPasswordRequest;
use App\Http\Requests\resetPasswordRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OA;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    use ApiResponse;

    #[OA\Post(
        path: '/api/register',
        summary: 'Register',
        description: 'Create a new user account',
        tags: ['Authentication'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['first_name', 'last_name', 'phone', 'email', 'password', 'password_confirmation'],
                properties: [
                    new OA\Property(property: 'first_name', type: 'string', example: 'Yosra'),
                    new OA\Property(property: 'last_name', type: 'string', example: 'Khattab'),
                    new OA\Property(property: 'phone', type: 'string', example: '01000000000'),
                    new OA\Property(property: 'second_phone', type: 'string', example: '01100000000', nullable: true),
                    new OA\Property(property: 'address', type: 'string', example: 'Mansoura', nullable: true),
                    new OA\Property(property: 'governorate', type: 'string', example: 'Dakahlia', nullable: true),
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'yosra@gmail.com'),
                    new OA\Property(property: 'password', type: 'string', format: 'password', example: '12345678'),
                    new OA\Property(property: 'password_confirmation', type: 'string', format: 'password', example: '12345678'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Registered successfully')
        ]
    )]
    public function register(RegisterRequest $request)
    {
        $user = User::create($request->validated());

        $token = $user->createToken('react-app')->plainTextToken;

        return $this->successResponse(
            [
                'user' => new UserResource($user),
                'token' => $token,
            ],
            __('messages.register_success'),
            201
        );
    }

    #[OA\Post(
        path: '/api/login',
        summary: 'Login',
        description: 'Login user and return access token',
        tags: ['Authentication'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['email', 'password'],
                properties: [
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'yosra@gmail.com'),
                    new OA\Property(property: 'password', type: 'string', format: 'password', example: '12345678'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Login successful'),
            new OA\Response(response: 401, description: 'Invalid credentials')
        ]
    )]
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

        $user->tokens()->delete();

        $token = $user->createToken('react-app')->plainTextToken;

        return $this->successResponse(
            [
                'user' => new UserResource($user),
                'token' => $token,
            ],
            __('messages.login_success'),
            200
        );
    }

    #[OA\Post(
        path: '/api/forgot-password',
        summary: 'Send Password Reset Code',
        description: "Send a six-digit password reset code to the user's email",
        tags: ['Authentication'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['email'],
                properties: [
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'yosra@gmail.com')
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Reset code sent successfully'),
            new OA\Response(response: 422, description: 'User not found')
        ]
    )]

    public function forgotPassword(forgotPasswordRequest $request)
    {
        $user = User::where('email', $request->validated('email'))->firstOrFail();
        $broker = Password::broker();
        $repository = $broker->getRepository();

        if ($repository->recentlyCreatedToken($user)) {
            return $this->errorResponse(__('messages.password_reset_throttled'), 429);
        }

        $code = (string) random_int(100000, 999999);
        $table = config('auth.passwords.'.config('auth.defaults.passwords').'.table');

        DB::table($table)->updateOrInsert(
            ['email' => $user->getEmailForPasswordReset()],
            ['token' => Hash::make($code), 'created_at' => now()]
        );

        $user->notify(new ResetPasswordNotification($code));

        return $this->successResponse([], __('messages.password_reset_code_sent'), 200);
    }

    #[OA\Post(
        path: '/api/reset-password',
        summary: 'Reset Password',
        description: 'Reset user password using the six-digit code sent by email',
        tags: ['Authentication'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['email', 'password', 'code'],
                properties: [
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'yosra@gmail.com'),
                    new OA\Property(property: 'password', type: 'string', format: 'password', example: '12345678'),
                    new OA\Property(property: 'code', type: 'string', example: '123456', minLength: 6, maxLength: 6)
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Password reset successfully'),
            new OA\Response(response: 422, description: 'Reset failed')
        ]
    )]

    public function resetPassword(resetPasswordRequest $request)
    {
        $status = Password::reset(
            [
                ...$request->safe()->except('code'),
                'token' => $request->validated('code'),
            ],
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );
        if ($status === Password::PASSWORD_RESET) {
            return $this->successResponse(
                [],
                __('messages.password_reset_success'),
                200
            );
        }

        return $this->errorResponse(
            __($status),
            422
        );
    }
    #[OA\Get(
        path: '/api/profile',
        summary: 'Show Profile',
        description: 'Get the authenticated user profile',
        tags: ['Profile'],
        security: [['sanctum' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Profile retrieved successfully'),
            new OA\Response(response: 401, description: 'Unauthenticated')
        ]
    )]
    public function profile(Request $request)
    {
        return $this->successResponse(
            new UserResource($request->user()),
            __('messages.profile_retrieved'),
            200
        );
    }

    #[OA\Put(
        path: '/api/profile',
        summary: 'Update Profile',
        description: 'Update the authenticated user profile',
        tags: ['Profile'],
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'first_name', type: 'string', example: 'Yosra'),
                    new OA\Property(property: 'last_name', type: 'string', example: 'Khattab'),
                    new OA\Property(property: 'phone', type: 'string', example: '01000000000'),
                    new OA\Property(property: 'second_phone', type: 'string', example: '01100000000', nullable: true),
                    new OA\Property(property: 'address', type: 'string', example: 'Mansoura', nullable: true),
                    new OA\Property(property: 'governorate', type: 'string', example: 'Dakahlia', nullable: true),
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'yosra@gmail.com'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Profile updated successfully'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 422, description: 'Validation error')
        ]
    )]
    public function updateProfile(UpdateProfileRequest $request)
    {
        $user = $request->user();

        $user->update($request->validated());

        return $this->successResponse(
            new UserResource($user->fresh()),
            __('messages.profile_updated'),
            200
        );
    }

    #[OA\Post(
        path: '/api/logout',
        summary: 'Logout',
        description: 'Logout authenticated user',
        tags: ['Authentication'],
        security: [['sanctum' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Logged out successfully'),
            new OA\Response(response: 401, description: 'Unauthenticated')
        ]
    )]
    public function logout(Request $request)
    {
        if (!Auth::check()) {
            return $this->errorResponse(
                __('messages.invalid_credentials'),
                401
            );
        }

        $request->user()->currentAccessToken()?->delete();

        return $this->successMessage(
            __('messages.logout_success'),
            200
        );
    }
}
