<?php

namespace App\Http\Controllers;

use App\Http\Requests\Attendee\Store_AttendeeRequest;
use App\Models\Attendee;
use App\Models\Event;
use Illuminate\Http\Request;
use Exception;
use Spatie\QueryBuilder\QueryBuilder;

class AttendeeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum')->except([
            'index',
            'show',
        ]);
        $this->middleware('throttle:api')->only(['store', 'destroy']);
        $this->authorizeResource(Attendee::class, 'attendee');
    }
    public function index(Event $event, Request $request)
    {
        $attendees =  QueryBuilder::for($event->attendees())
            ->allowedIncludes(['user'])
            ->paginate($request['limit']);
        return response()->json(
            $attendees,
            200
        );
    }

    public function show(Event $event, Attendee $attendee)
    {
        return response()->json(
            ['data' => $attendee],
            200
        );
    }

    public function store(Store_AttendeeRequest $request, Event $event)
    {
        $validated = $request->validated();
        try {
            $attendee = $event->attendees()->create($validated);
            return response()->json([
                'message' => 'success',
                'data' => $attendee,
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e,
            ], 500);
        }
    }

    public function destroy(Event $event, Attendee $attendee)
    {
        $attendee->delete();
        return response(status: 204);
    }

    // public function update()


}
