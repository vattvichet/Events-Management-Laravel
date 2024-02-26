<?php

namespace App\Http\Controllers;

use App\Http\Requests\Event\Store_EventRequest;
use App\Http\Requests\Event\Update_EventRequest;
use App\Models\Event;
use Exception;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class EventController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum')->except([
            'index',
            'show',
        ]);
        $this->authorizeResource(Event::class, 'event');
    }
    public function index(Request $request)
    {
        $events = QueryBuilder::for(Event::class)
            ->allowedIncludes(['user', 'attendees', 'attendees.user'])
            ->paginate($request['limit']);
        return response()->json(
            $events,
            200
        );
    }

    public function show(Event $event)
    {
        $event->load('user', 'attendees');
        return response()->json([
            'message' => 'success',
            'data' => $event,
        ], 200);
    }

    public function store(Store_EventRequest $request)
    {

        $validated = $request->validated();
        $validated['user_id'] = $request->user()->id;
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

    public function update(Update_EventRequest $request, Event $event)
    {
        $validated = $request->validated();

        $event->update($validated);
        return response()->json([
            'message' => 'success',
            'data' => $event,
        ]);
    }


    public function destroy(Event $event)
    {
        $event->delete();
        return response(status: 204);
    }
}
