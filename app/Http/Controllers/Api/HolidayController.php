<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Holiday;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class HolidayController extends Controller
{
    public function index(Request $request)
    {
        $holidays = Holiday::query()
            ->orderByDesc("created_at")
            ->get();

        return response()->json($holidays);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "title" => "required|string",
            "description" => "required|string",
            "date" => "required|date_format:Y-m-d",
            "location" => "required|string",
        ]);
    
        if ($validator->fails()) {
            return response()->json(["error" => $validator->errors()], 422);
        }

        $holiday = new Holiday();
        $holiday->title = $request->input("title");
        $holiday->description = $request->input("description");
        $holiday->date = $request->input("date");
        $holiday->location = $request->input("location");
        $holiday->participants = $request->user()->id;
        $holiday->save();

        return response()->json(["holiday" => $holiday], 201);
    }

    public function read(Request $request, $id)
    {
        $holiday = Holiday::query()
            ->where("id", $id)
            ->first();

        if($holiday) {
            return response()->json(["holiday" => $holiday], 200);
        }

        return response()->json(["error" => "Holiday not found"], 422);
    }

    public function update(Request $request, $id)
    { 
        $holiday = Holiday::findOrFail($id);

        $validator = Validator::make($request->all(), [
            "title" => "required|string",
            "description" => "required|string",
            "date" => "required|date_format:Y-m-d",
            "location" => "required|string",
        ]);

        if ($validator->fails()) {
            return response()->json(["error" => $validator->errors()], 422);
        }

        $holiday->update($request->all());

        return response()->json(["holiday" => $holiday], 200);
    }

    public function delete(Request $request, $id)
    { 
        $holiday = Holiday::find($id);

        if (!$holiday) {
            return response()->json(["error" => "Holiday not found"], 400);
        }

        $holiday->delete();

        return response()->json(["message" => "Holiday deleted successfully"], 200);
    }

    public function addNewParticipant(Request $request, $holidayId, $userId)
    {
        $holiday = Holiday::findOrFail($holidayId);
        $user = User::find($userId);
    
        if (!$user) {
            return response()->json(["error" => "User not found"], 400);
        }
    
        $participants = explode(",", $holiday->participants);
        if (in_array($userId, $participants)) {
            return response()->json(["error" => "User is already a participant of this holiday"], 400);
        }
    
        $participants[] = $userId;
        $holiday->participants = implode(",", $participants);
        $holiday->save();
    
        return response()->json(["message" => "User added as a participant successfully"], 200);
    }

    public function removeParticipant(Request $request, $holidayId, $userId)
    {
        $holiday = Holiday::findOrFail($holidayId);
        $user = User::find($userId);

        if (!$user) {
            return response()->json(["error" => "User not found"], 400);
        }

        $participants = explode(",", $holiday->participants);
        $participantKey = array_search($userId, $participants);
        if ($participantKey === false) {
            return response()->json(["error" => "User is not a participant of this holiday"], 400);
        }

        unset($participants[$participantKey]);
        $holiday->participants = implode(",", $participants);
        $holiday->save();

        return response()->json(["message" => "User removed from participants successfully"], 200);
    }
}