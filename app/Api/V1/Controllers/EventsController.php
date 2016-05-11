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
            'livestream' => 'boolean',
            'maxstart' => 'numeric',
            'minend' => 'numeric'
        ]);

        if ($validator->fails()) {
            throw new StoreResourceFailedException('Could not get events.', $validator->errors());
        }
        
        $events = Event::take($request["count"]);
        
        if (isset($request["start"]))
        {
            $events = $events->where('start','>=',\DateTime::createFromFormat( 'U', $request["start"] ));
        }
        
        if (isset($request["end"]))
        {
            $events = $events->where('end','<=',\DateTime::createFromFormat( 'U', $request["end"] ));
        }
        
        if (isset($request["livestream"]) && $request["livestream"])
        {
            $events = $events->where('livestream', true);
        }
        
        if (isset($request["minend"]))
        {
            $events = $events->where('end','>=',\DateTime::createFromFormat( 'U', $request["minend"] ));
        }
        
        if (isset($request["maxstart"]))
        {
            $events = $events->where('start','<',\DateTime::createFromFormat( 'U', $request["maxstart"] ));
        }
        
        if (isset($request["reverse"]))
        {
            $events = $events->orderBy('start','DESC');
        }

        $events = $events->get();

        return $this->response->collection(new Collection($events), new EventTransformer());
    }
}