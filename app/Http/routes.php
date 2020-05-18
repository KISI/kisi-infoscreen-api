<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

/*$app->get('/', function () use ($app) {
    return $app->version();
});*/

$api = app('Dingo\Api\Routing\Router');

$api->group([
    'version' => 'v1',
    'namespace' => 'App\Api\V1\Controllers',
], function($api)
{
    $api->get('/api', function(){
        return "PONG";
    });
    
    $api->get('/events', 'EventsController@getEvents');
    $api->post('/backend/authenticate', 'AuthenticateController@backend');
});

$api->group([
    'version' => 'v1',
    'namespace' => 'App\Api\V1\Controllers',
    'middleware' => 'api.auth',
    'providers' => 'jwt',
], function($api)
{
    $api->get('/backend/events', 'BackendApiController@getEvents');
    $api->post('/backend/events/clone', 'BackendApiController@cloneEvents');
    $api->get('/backend/event/{eventid}', 'BackendApiController@getEvent');
    $api->put('/backend/event/{eventid}', 'BackendApiController@updateEvent');
    $api->post('/backend/event', 'BackendApiController@createEvent');
    $api->delete('/backend/event/{eventid}', 'BackendApiController@deleteEvent');
});