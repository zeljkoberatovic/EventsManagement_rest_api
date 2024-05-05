<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Http\Traits\CanLoadRelationships;
use Illuminate\Support\Facades\Gate;

class EventController extends Controller
{

    use CanLoadRelationships;

    private array $relations = ['user', 'attendees', 'attendees.user'];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //return \App\Models\Event::all();
        //return \App\Models\User::all();
        //return Event::all();
        //return EventResource::collection(Event::all());
        // $query = Event::query();
        // $relations = ['user', 'attendees', 'attendees.user'];

        // foreach ($relations as $relation) {
        //     $query->when(
        //         $this->shouldIncludeRelation($relation),
        //         fn($q) => $q->with($relation)
        //     );
        // }
        $query = $this->loadRelationships(Event::query());

        return EventResource::collection(
            //Event::with('user')->paginate()
            $query->latest()->paginate()
        );
    }

    //VRACA NAM PODATKE U NIZU
    protected function shouldIncludeRelation(String $relation):bool
    {
        $include = request()->query('include');

        if (!$include) {
            return false;
        }

        $relations = array_map('trim', explode(',', $include));

        return in_array($relation, $relations);
    }
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $event = Event::create([
            ...$request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'start_time' => 'required|date',
                'end_time' => 'required|date|after:start_time'
            ]),
            'user_id' => 1
        ]);

        return new EventResource($this->loadRelationships($event));
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        $event->load('user', 'attendees');
        return new EventResource($this->loadRelationships($event));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        $event->update(
            $request->validate([
                'name' => 'sometimes|string|max:255',
                'description' => 'nullable|string',
                'start_time' => 'sometimes|date',
                'end_time' => 'sometimes|date|after:start_time'
            ])
        );

        return new EventResource($this->loadRelationships($event));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $event->delete();
                
        return response(status: 204);
    }
}
