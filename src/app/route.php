<?php

include_once 'routes/Request.php';
include_once 'routes/Router.php';

$router = new Router(new Request);

$router->get('/', function() {
  return <<<HTML
  <h1>Hello world</h1>
HTML;
});


$router->get('/profile', function($request) {
  return <<<HTML
  <h1>Profile</h1>
HTML;
});
$router->get('/test', "RouteController@index");
$router->get('/d/:id/:p', "RouteController@indexPar");
$router->get("/test/:id/:p", function($request, $id, $p){
  return "Id ".$id." p ".$p;
});
$router->get('/d', "RouteController@index");

$router->post('/data', function($request) {

  return json_decode(json_encode($request->getBody()));
});