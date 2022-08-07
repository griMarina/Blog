<?php

require_once __DIR__ . "/vendor/autoload.php";

use Grimarina\Blog_Project\Blog\{User, Post, Comment, UUID};
use Grimarina\Blog_Project\Blog\Commands\Arguments;
use Grimarina\Blog_Project\Blog\Commands\CreateUserCommand;
use Grimarina\Blog_Project\Blog\Repositories\InMemoryUsersRepository;
use Grimarina\Blog_Project\Blog\Repositories\UsersRepository;


    $connection = new PDO('sqlite:' . __DIR__ . '/blog.sqlite');

    //$faker = Faker\Factory::create();
    
    //$usersRepository = new UsersRepository($connection);

    $usersRepository = new InMemoryUsersRepository();
    
    $command = new CreateUserCommand($usersRepository);

try {

    $command->handle(Arguments::fromArgv($argv));

    $user = $usersRepository->getByUsername('marina');
    print($user);
    
    // $usersRepository->save(new User(
    //     UUID::random(),
    //     'admin',
    //     $faker->firstName(), 
    //     $faker->lastName(),
    //     )
    // );

    //echo $usersRepository->getByUsername('admin');
    
} catch (Exception $exception) {
    echo $exception->getMessage();
}




// switch ($argv[1]) {
//     case "user":
//         $user = new User(
//             $faker->unique()->randomDigit, 
//             $faker->firstName(),
//             $faker->lastName(),
//         );
//         print($user);
//         break;

//     case "post":
//         $post = new Post(
//             $faker->unique()->randomDigit, 
//             $faker->unique()->randomDigit, 
//             $faker->text(), 
//             $faker->text()
//         );
//         print($post);
//         break;

//     case "comment":
//         $comment = new Comment(
//             $faker->unique()->randomDigit, 
//             $faker->unique()->randomDigit, 
//             $faker->unique()->randomDigit, 
//             $faker->text()
//         );
//         print($comment);
//         break;

//     default:
//         echo "The argument not found";
// }