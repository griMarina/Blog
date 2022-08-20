<?php

use Grimarina\Blog_Project\http\Actions\Posts\{CreatePost, DeletePost, FindByUuid};
use Grimarina\Blog_Project\http\Actions\Users\{CreateUser, FindByUsername};
use Grimarina\Blog_Project\http\Actions\Comments\CreateComment;
use Grimarina\Blog_Project\http\Actions\Likes\CreateLike;
use Grimarina\Blog_Project\http\{ErrorResponse, Request};
use Grimarina\Blog_Project\Exceptions\HttpException;
use Grimarina\Blog_Project\http\Actions\Likes\FindByPostUuid;

$container = require __DIR__ . '/bootstrap.php';

$request = new Request(
    $_GET,
    $_SERVER,
    file_get_contents('php://input')
);

try {
    $path = $request->path();
} catch (HttpException) {
    (new ErrorResponse())->send();
    return;
}

try {
    $method = $request->method();
} catch (HttpException) {
    (new ErrorResponse())->send();
    return;
}

$routes = [
    'GET' => [
        '/users/show' => FindByUsername::class,
        '/posts/show' => FindByUuid::class,
        '/likes/show' => FindByPostUuid::class  
        ],
    'POST' => [
        '/users/create' => CreateUser::class,
        '/posts/create' => CreatePost::class,
        '/comments/create' => CreateComment::class,
        '/likes/create' => CreateLike::class  
        ],
    'DELETE' => [
        '/posts' => DeletePost::class
        ]
    ];

    if (!array_key_exists($method, $routes)) {
        (new ErrorResponse("Route not found: $method $path"))->send();
        return;
    }

    $actionClassName = $routes[$method][$path];

    $action = $container->get($actionClassName);

try {
    
    $response = $action->handle($request);

    $response->send();

} catch (Exception $exception) {
    echo $exception->getMessage();
}


