<?php

require_once __DIR__ . "/vendor/autoload.php";

use Grimarina\Blog\{User, Post, Comment};

$faker = Faker\Factory::create();

switch ($argv[1]) {
    case "user":
        $user = new User($faker->unique()->randomDigit, $faker->name);
        print($user);
        break;

    case "post":
        $post = new Post($faker->unique()->randomDigit, $faker->unique()->randomDigit, $faker->text(), $faker->text());
        print($post);
        break;

    case "comment":
        $comment = new Comment($faker->unique()->randomDigit, $faker->unique()->randomDigit, $faker->unique()->randomDigit, $faker->text());
        print($comment);
        break;

    default:
        echo "Not found argument";
}