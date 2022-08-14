<?php

use Grimarina\Blog_Project\Blog\Repositories\CommentsRepositories\CommentsRepository;
use Grimarina\Blog_Project\Blog\Repositories\PostsRepositories\PostsRepository;
use Grimarina\Blog_Project\Blog\Repositories\UsersRepositories\UsersRepository;
use Grimarina\Blog_Project\Exceptions\HttpException;
use Grimarina\Blog_Project\http\Actions\Posts\{CreatePost, DeletePost, FindByUuid};
use Grimarina\Blog_Project\http\Actions\Users\{CreateUser, FindByUsername};
use Grimarina\Blog_Project\http\{ErrorResponse, Request, SuccessfulResponse};
use Grimarina\Blog_Project\http\Actions\Comments\CreateComment;

require_once __DIR__ . "/vendor/autoload.php";

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
        '/users/show' => new FindByUsername(
            new UsersRepository(
                new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
            )
            ),
        '/posts/show' => new FindByUuid(
            new PostsRepository(
                new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
            )
        ),  
        ],
    'POST' => [
        '/users/create' => new CreateUser(
            new UsersRepository(
                new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
            )
        ),
        '/posts/create' => new CreatePost(
            new PostsRepository(
                new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
            ),
            new UsersRepository(
                new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
            ), 
        ),
        '/comments/create' => new CreateComment(
            new CommentsRepository(
                new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
            ),
            new PostsRepository(
                new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
            ), 
            new UsersRepository(
                new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
            ),
        )    
        ],
    'DELETE' => [
        '/posts' => new DeletePost(
            new PostsRepository(
                new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
            )
            ),
        ]
    ];

    

    if (!array_key_exists($path, $routes[$method])) {
        (new ErrorResponse('Not found'))->send();
        return;
    }

    $action = $routes[$method][$path];

try {
    
    $response = $action->handle($request);

    $response->send();

} catch (Exception $exception) {
    echo $exception->getMessage();
}


