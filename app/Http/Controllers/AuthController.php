<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Repositories\UserRepository;
use Auth0\Laravel\Facade\Auth0;

class AuthController extends Controller
{
    public function __construct(private UserRepository $userRepository)
    {
        //
    }

    /**
     * Get the current user from Auth0 using token; Do redirects otherwise
     *
     * @return App\Models\User
     */
    public function getAuthenticatedUser(): User
    {
        $authUser = auth()->user();

        if (empty($authUser)) {
            return response()->json([
                'status' => 'failed',
            ], 401);
        }

        $user = $this->userRepository->fromAccessToken($authUser->getAttributes());

        // User Auth0 record isn't updated or isn't in the system
        if (empty($user)) {
            return response()->json([
                'status' => 'failed',
            ], 404);
        }

        return $user;
    }
}
