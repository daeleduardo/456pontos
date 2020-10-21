<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

$router->get('/', function () use ($router) {
    return view("index");
});

$router->group(['middleware' => 'auth'], function () use ($router) {
    $router->get('/buscarGruposVoos', ['uses' => 'GrupoVooController@buscarGruposVoos']);
    $router->option('/buscarGruposVoos', ['uses' => 'GrupoVooController@buscarGruposVoos']);
    $router->get('/buscarLocais', ['uses' => 'Mock@buscarLocais']);

});
