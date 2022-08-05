<?php

require_once __DIR__ . "/vendor/autoload.php";

use Grimarina\Blog\{User, Post, Comment};

$faker = Faker\Factory::create();

$user = new User($faker->unique()->randomDigit, $faker->name);
print($user);

$post = new Post($faker->unique()->randomDigit, $faker->unique()->randomDigit, $faker->title(), $faker->text());
print($post);

$comment = new Comment($faker->unique()->randomDigit, $faker->unique()->randomDigit, $faker->unique()->randomDigit, $faker->text());
print($comment);