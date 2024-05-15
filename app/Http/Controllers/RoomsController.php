<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class RoomsController extends Controller
{
    public function list(): JsonResponse
    {
        $rooms = Room::all();

        return response()->json([
            'status' => 'success',
            'rooms' => $rooms
        ]);
    }

    public function listByAvailability(Request $request): JsonResponse
    {
        $rules = [
            'available_from' => 'required|date_format:Y-m-d H:i|after:now',
            'available_to' => 'required|date_format:Y-m-d H:i|after:avaiable_from'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
              'status' => 'failed',
              'message' => $validator->errors(),
            ], 400);
        }

        $input = $request->all();
        $availableFrom = Carbon::parse($input['available_from']);
        $availableTo = Carbon::parse($input['available_to']);

        $availableRooms = Room::whereDoesntHave('bookings', function ($query) use ($availableFrom, $availableTo) {
            $this->buildAvailableFromAndToQuery($query, $availableFrom, $availableTo);
        })->get();

        return response()->json([
              'status' => 'success',
              'availableRooms' => $availableRooms,
        ], 200);
    }

    protected function isRoomAvailable(Room $room, string $availableFrom, string $availableTo): boolean
    {
        $availableFrom = Carbon::parse($availableFrom);
        $availableTo = Carbon::parse($availableTo);

        $booking = Booking::where('room_id', $room->id)
            ->where(function ($query) use ($availableFrom, $availableTo) {
                $this->buildAvailableFromAndToQuery($query, $availableFrom, $availableTo);
            })
        ->exists();

        return !$booking;
    }

    protected function buildAvailableFromAndToQuery(Builder &$query, string $availableFrom, string $availableTo): void
    {
        $availableFrom = Carbon::parse($availableFrom);
        $availableTo = Carbon::parse($availableTo);

        $query->whereBetween('reserved_from', [$availableFrom, $availableTo])
            ->orWhereBetween('reserved_to', [$availableFrom, $availableTo])
            ->orWhere(function ($query) use ($availableFrom, $availableTo) {
                $query->where('reserved_from', '<=', $availableFrom)
                    ->where('reserved_to', '>=', $availableTo);
            });
    }
}
