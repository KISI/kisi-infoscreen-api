<?php
/**
 * Created by PhpStorm.
 * User: weber
 * Date: 22.02.2016
 * Time: 16:40
 */

namespace App\Api\V1\Transformers;


use App\Api\V1\Models\Event;
use League\Fractal\TransformerAbstract;

class EventBackendTransformer extends TransformerAbstract
{
    public function transform(Event $event)
    {
        return [
            'id' => $event->id,
            'start' => $event->start->getTimestamp(),
            'end' => $event->end->getTimestamp(),
            'hasEnd' => $event->hasEnd,
            'title' => $event->title,
            'location' => $event->location,
            'featured' => $event->featured,
            'livestream' => $event->livestream,
        ];
    }
}