<?php

use Grimarina\Blog_Project\http\Actions\Posts\{CreatePost, DeletePost, FindByUuid};
use Grimarina\Blog_Project\http\Actions\Users\{CreateUser, FindByUsername};
use Grimarina\Blog_Project\http\Actions\Comments\{CreateComment, FindCommentByUuid};
use Grimarina\Blog_Project\http\Actions\Likes\{CreateLike, FindByPostUuid};
use Grimarina\Blog_Project\http\{ErrorResponse, Request};
use Grimarina\Blog_Project\Exceptions\HttpException;
use Psr\Log\LoggerInterface;

$container = require __DIR__ . '/bootstrap.php';

$request = new Request(
    $_GET,
    $_SERVER,
    file_get_contents('php://input')
);

$logger = $container->get(LoggerInterface::class);

try {
    $path = $request->path();
} catch (HttpException $exception) {
    $logger->warning($exception->getMessage());
    (new ErrorResponse())->send();
    return;
}

try {
    $method = $request->method();
} catch (HttpException $exception) {
    $logger->warning($exception->getMessage());
    (new ErrorResponse())->send();
    return;
}

$routes = [
    'GET' => [
        '/users/show' => FindByUsername::class,
        '/posts/show' => FindByUuid::class,
        '/comments/show' => FindCommentByUuid::class,    
        '/likes/show' => FindByPostUuid::class,
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

    if (!array_key_exists($method, $routes) || !array_key_exists($path, $routes[$method])) {
        $message = "Route not found: $method $path";
        $logger->notice($message);
        (new ErrorResponse($message))->send();
        return;
    }

    $actionClassName = $routes[$method][$path];

try {
    $action = $container->get($actionClassName);
    $response = $action->handle($request);

} catch (Exception $exception) {
    $logger->error($exception->getMessage(), ['exception' => $exception]);
    (new ErrorResponse())->send();
    return;
}

$response->send();