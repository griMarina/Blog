<?php

require_once __DIR__ . "/vendor/autoload.php";

use Grimarina\Blog_Project\Blog\Commands\{Arguments, CreateUserCommand, CreatePostCommand, CreateCommentCommand};

$container = require __DIR__ . '/bootstrap.php';

$command = $container->get(CreateUserCommand::class);

try {
    $command->handle(Arguments::fromArgv($argv));
} catch (Exception $exception) {
    echo $exception->getMessage();
}