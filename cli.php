<?php

require_once __DIR__ . "/vendor/autoload.php";

use Grimarina\Blog_Project\Blog\Commands\FakeData\PopulateDB;
use Grimarina\Blog_Project\Blog\Commands\Posts\DeletePost;
use Grimarina\Blog_Project\Blog\Commands\Users\CreateUser;
use Grimarina\Blog_Project\Blog\Commands\Users\UpdateUser;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Application;

$container = require __DIR__ . '/bootstrap.php';

$logger = $container->get(LoggerInterface::class);

$application = new Application();

$commandClasses = [
    CreateUser::class,
    DeletePost::class,
    UpdateUser::class,
    PopulateDB::class
];

foreach ($commandClasses as $commandClass) {
    $command = $container->get($commandClass);

    $application->add($command);
}

try {
    $application->run();
} catch (Exception $exception) {
    $logger->error($exception->getMessage(), ['exception' => $exception]);
}