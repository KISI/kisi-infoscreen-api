<?php

namespace App\Api\V1\Controllers;


use App\Api\V1\Models\Event;
use App\Api\V1\Requests\FormRequest;
use App\Api\V1\Transformers\EventTransformer;
use App\Api\V1\Transformers\EventBackendTransformer;
use App\Http\Controllers\Controller;
use Dingo\Api\Exception\StoreResourceFailedException;
use Illuminate\Support\Collection;
use Validator;
use Illuminate\Http\Request;

class BackendApiController extends Controller
{
    public function getEvents(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'date'
        ]);

        if ($validator->fails()) {
            throw new StoreResourceFailedException('Could not get events.', $validator->errors());
        }
        
        if (isset($request["date"]))
        {
            $date = new \DateTime($request["date"]);
            $events = Event::whereDate('start', '=', $date->format('Y-m-d'))->get();
        }
        else {
            $events = Event::all();
        }
        
        return $this->response->collection($events, new EventBackendTransformer());
    }

    public function getEvent($eventid)
    {
        return $this->response->item(Event::findOrFail($eventid), new EventBackendTransformer());
    }
    
    public function deleteEvent($eventid)
    {
        $event = Event::findOrFail($eventid);
        
        $event->delete();
        
        return $this->response->item($event, new EventBackendTransformer());
    }
    
    public function updateEvent($eventid, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start' => 'numeric',
            'end' => 'numeric',
            'hasEnd' => 'boolean',
            'title' => 'string',
            'location' => 'string',
            'featured' => 'boolean',
            'livestream' => 'boolean'
        ]);

        if ($validator->fails()) {
            throw new StoreResourceFailedException('Could not update event.', $validator->errors());
        }
        
        $event = Event::findOrFail($eventid);
        
        if (isset($request["start"]))
        {
            $event->start = \DateTime::createFromFormat( 'U', $request["start"] );
        }
        
        if (isset($request["end"]))
        {
            $event->end = \DateTime::createFromFormat( 'U', $request["end"] );
        }
        
        if (isset($request["hasEnd"]))
        {
            $event->hasEnd = $request["hasEnd"];
        }
        
        if (isset($request["title"]))
        {
            $event->title = $request["title"];
        }
        
        if (isset($request["location"]))
        {
            $event->location = $request["location"];
        }
        
        if (isset($request["featured"]))
        {
            $event->featured = $request["featured"];
        }
        
        if (isset($request["livestream"]))
        {
            $event->livestream = $request["livestream"];
        }
        
        $event->save();
        
        return $this->response->item($event, new EventBackendTransformer());
    }
    
    public function createEvent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start' => 'required|numeric',
            'end' => 'required|numeric',
            'hasEnd' => 'boolean',
            'title' => 'required|string',
            'location' => 'string',
            'featured' => 'boolean',
            'livestream' => 'boolean'
        ]);

        if ($validator->fails()) {
            throw new StoreResourceFailedException('Could not create event.', $validator->errors());
        }
        
        $event = new Event();
        
        if (isset($request["start"]))
        {
            $event->start = \DateTime::createFromFormat( 'U', $request["start"] );
        }
        
        if (isset($request["end"]))
        {
            $event->end = \DateTime::createFromFormat( 'U', $request["end"] );
        }
        
        if (isset($request["hasEnd"]))
        {
            $event->hasEnd = $request["hasEnd"];
        } else {
            $event->hasEnd = false;
        }
        
        if (isset($request["title"]))
        {
            $event->title = $request["title"];
        }
        
        if (isset($request["location"]))
        {
            $event->location = $request["location"];
        } else {
            $event->location = "";
        }
        
        if (isset($request["featured"]))
        {
            $event->featured = $request["featured"];
        } else {
            $event->featured = false;
        }
        
        if (isset($request["livestream"]))
        {
            $event->livestream = $request["livestream"];
        } else {
            $event->livestream = false;
        }
        
        $event->save();
        
        return $this->response->item($event, new EventBackendTransformer());
    }

    public function cloneEvents(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'newdate' => 'required|date',
        ]);

        if ($validator->fails()) {
            throw new StoreResourceFailedException('Could not get events.', $validator->errors());
        }

        $date = new \DateTime($request["date"]);
        $newdate = new \DateTime($request["newdate"]);
        $events = Event::whereDate('start', '=', $date->format('Y-m-d'))->get();

        foreach ($events as $event) {
            $newevent = $event->replicate();
            $newevent->start->setDate((int)$newdate->format("Y"),(int)$newdate->format("n"),(int)$newdate->format("j"));
            $newevent->save();
        }
        
        return;
    }
}