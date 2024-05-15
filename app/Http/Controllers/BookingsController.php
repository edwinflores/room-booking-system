<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoomsController;
use Auth0\Laravel\Facade\Auth0;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookingsController extends Controller
{
    public function __construct(private AuthController $authController)
    {
        //
    }

    public function listByUser()
    {
        $user = $this->authController->getAuthenticatedUser();

        if (empty($user)) {
            return response()->json([
              'status' => 'failed',
            ], 404);
        }

        // Grab the bookings made by the user
        $bookings = $user->bookings()->with('room')->get();

        return response()->json([
            'status' => 'success', 
            'bookings' => $bookings
        ]);
    }

    public function add(Request $request, RoomsController $roomsController)
    {

    }
}
