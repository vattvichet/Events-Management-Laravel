<?php

namespace App\Http\Controllers;

use App\Http\Requests\Event\Store_EventRequest;
use App\Http\Requests\Event\Update_EventRequest;
use App\Models\Event;
use Exception;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::with('user')->with('attendees')->get();
        return $events;
    }

    public function show($id)
    {
        $event = Event::with('user')->with('attendees')->find($id);
        if ($event) {
            return response()->json([
                'message' => 'success',
                'data' => $event,
            ], 200);
        }
        return response()->json([
            'message' => "Event ID invalid",
        ], 201);
    }

    public function store(Store_EventRequest $request)
    {
        $validated = $request->validated();
        try {
            $event = Event::create($validated);
            return response()->json([
                'message' => 'success',
                'data' => $event,
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e,
            ]);
        }
    }

    public function update(Update_EventRequest $request, $id)
    {
        $event = Event::find($id);
        $validated = $request->validated();
        if ($event) {
            try {
                $event->update($validated);
                return response()->json([
                    'message' => 'success',
                    'data' => $event,
                ], 200);
            } catch (Exception $e) {
                return response()->json([
                    'status' => 'error',
                    'message' => $e,
                ]);
            }
        }
        return response()->json([
            'message' => "Event ID invalid",
        ], 201);
    }

    public function destroy($id)
    {
        $event = Event::find($id);
        if ($event) {
            $event->delete();
            return response()->json([], 204);
        }
        return response()->json([
            'message' => "Event ID invalid",
        ], 201);
    }
}
