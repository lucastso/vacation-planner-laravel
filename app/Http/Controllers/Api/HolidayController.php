<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Holiday;
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
            "participants" => "required|string",
        ]);
    
        if ($validator->fails()) {
            return response()->json(["error" => $validator->errors()], 422);
        }

        $holiday = new Holiday();
        $holiday->title = $request->input("title");
        $holiday->description = $request->input("description");
        $holiday->date = $request->input("date");
        $holiday->location = $request->input("location");
        $holiday->participants = $request->input("participants");
        $holiday->save();

        return response()->json(["holiday" => $holiday], 201);
    }

    public function read(Request $request, $id)
    {
        $holiday = Holiday::query()
            ->where('id', $id)
            ->first();

        if($holiday) {
            return response()->json(["holiday" => $holiday], 200);
        }

        return response()->json(["error" => "There's no holiday with this ID."], 422);
    }

    public function update(Request $request, $id)
    { 
        $holiday = Holiday::findOrFail($id);

        $validator = Validator::make($request->all(), [
            "title" => "required|string",
            "description" => "required|string",
            "date" => "required|date_format:Y-m-d",
            "location" => "required|string",
            "participants" => "required|string",
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
            return response()->json(["error" => "Holiday not found"], 404);
        }

        $holiday->delete();

        return response()->json(["message" => "Holiday deleted successfully"], 200);
    }
}