<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

return function (App $app) {
    $container = $app->getContainer();

    $auth = function ($request, $response, $next) {
        $response->getBody()->write('BEFORE');
        $response = $next($request, $response);
        $response->getBody()->write('AFTER');
    
        return $response;
    };
    
    $app->get('/hello/[{name}]', function (Request $request, Response $response, array $args) use ($container) {
        // Sample log message
        $container->get('logger')->info("Slim-Skeleton '/' route");

        // Render index view
        return $container->get('renderer')->render($response, 'index.phtml', $args);
    })->add($auth);

    $app->get('/users', function (Request $request, Response $response, array $args) use ($container) {
        // Sample log message
        $db = $container->get('db');
        $users = $db->table('users')->get();
        // Render index view
        return $container->get('renderer')->render($response, 'users/index.phtml',  ['users'=>$users]);
    });

    $app->post('/users', function (Request $request, Response $response, array $args) use ($container) {
        
        $data = [
            'name'=> filter_input(INPUT_POST, 'name'),
            'email'=> filter_input(INPUT_POST, 'email'),
            'password'=> filter_input(INPUT_POST, 'password')
        ]; 
        $db = $container->get('db');
        $users = $db->table('users')->insert($data);
        // Render index view
        return $response->withStatus(302)->withHeader('Location','/users');
    });

    $app->get('/users/{id}', function (Request $request, Response $response, array $args) use ($container) {
        // Sample log message
        $db = $container->get('db');
        $users = $db->table('users')->where('id',$request->getAttribute(id))->delete();
        // Render index view
        return $response->withStatus(302)->withHeader('Location','/users');
    });


};
