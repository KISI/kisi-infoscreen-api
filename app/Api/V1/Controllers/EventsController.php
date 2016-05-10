<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Models\Event;
use App\Api\V1\Transformers\EventTransformer;
use Dingo\Api\Exception\StoreResourceFailedException;
//use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;

class EventsController extends Controller
{
    public function getEvents(Request $request)
    {

        /*$validator = Validator::make($request->all(), []);

        if ($validator->fails()) {
            throw new StoreResourceFailedException('Could not register.', $validator->errors());
        }*/
        
        $event = new Event();
        $event->start = time();
        $event->end = time();
        $event->hasEnd = false;
        $event->title = "test event";
        $event->dest = "test destination";
        $event->featured = true;
        $event->livestream = false;
        $event->save();
        
        $events = Event::all();

        return $this->response->collection(new Collection($events), new EventTransformer());
    }
}