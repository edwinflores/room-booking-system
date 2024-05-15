<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookingsController extends Controller
{
    public function __construct(private AuthController $authController)
    {
        //
    }

    /**
     * What bookings has the authenticated user made?
     */
    public function listByUser(): JsonResponse
    {
        $user = $this->authController->getAuthenticatedUser();

        // Grab the bookings made by the user
        $bookings = $user->bookings()->with('room')->get();

        return response()->json([
            'status' => 'success',
            'bookings' => $bookings,
        ]);
    }

    /**
     * Reserve a room for the authenticated user
     *
     * @param  Illuminate\Http\Request  $request
     * @param  App\Http\Controllers\RoomsController  $roomsController
     */
    public function add(Request $request, RoomsController $roomsController): JsonResponse
    {
        $user = $this->authController->getAuthenticatedUser();

        $rules = [
            'room_id' => 'required|integer|min:1|exists:App\Models\Room,id',
            'reserve_from' => 'required|date_format:Y-m-d H:i|after:now',
            'reserve_to' => 'required|date_format:Y-m-d H:i|after:reserve_from',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'message' => $validator->errors(),
            ], 400);
        }

        $input = $request->all();

        // Find the target Room
        $room = Room::find($input['room_id']);

        if (empty($room)) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Room not found.',
            ], 404);
        }

        // Check availability. Should also cover if they try to double book the same timeslots.
        // If there was a cancel functionality, then the User should make use of that first before double booking.
        if (! $roomsController->isRoomAvailable($room, $input['reserve_from'], $input['reserve_to'])) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Room is not available for the given timeslots.',
            ], 200);
        }

        // As long as the timeslots aren't available, the room isn't "available".
        // So logically, there isn't a need to check on a User level for:
        // "Ensure that no two users can book the same room at the same time."

        // Make the reservation
        $booking = Booking::create([
            'user_id' => $user->id,
            'room_id' => $room->id,
            'reserved_from' => Carbon::parse($input['reserve_from']),
            'reserved_to' => Carbon::parse($input['reserve_to']),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Room has been reserved.',
            'booking' => $booking,
        ], 200);
    }
}
