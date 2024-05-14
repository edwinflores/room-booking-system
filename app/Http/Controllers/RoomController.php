<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RoomsController extends Controller
{
    public function list()
    {
        $rooms = Room::all();

        return response()->json($rooms);
    }
}
