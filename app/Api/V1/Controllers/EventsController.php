<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Models\Event;
use App\Api\V1\Transformers\EventTransformer;
use Dingo\Api\Exception\StoreResourceFailedException;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;

class EventsController extends Controller
{
    public function getEvents(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'count' => 'required|numeric',
            'start' => 'numeric',
            'end' => 'numeric',
        ]);

        if ($validator->fails()) {
            throw new StoreResourceFailedException('Could not get events.', $validator->errors());
        }
        
        $event = new Event();
        $event->start = new \DateTime();
        $event->end = new \DateTime();
        $event->hasEnd = false;
        $event->title = "test event";
        $event->location = "test location";
        $event->featured = true;
        $event->livestream = false;
        $event->save();
        
        $events = Event::take($request["count"]);
        
        if (isset($request["start"]))
        {
            $events = $events->where('start','>=',\DateTime::createFromFormat( 'U', $request["start"] ));
        }
        
        if (isset($request["end"]))
        {
            $events = $events->where('end','<=',\DateTime::createFromFormat( 'U', $request["end"] ));
        }

        $events = $events->get();

        return $this->response->collection(new Collection($events), new EventTransformer());
    }
}