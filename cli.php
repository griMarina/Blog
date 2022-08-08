<?php

require_once __DIR__ . "/vendor/autoload.php";

use Grimarina\Blog_Project\Blog\{User, Post, Comment, UUID};
use Grimarina\Blog_Project\Blog\Commands\{Arguments, CreateUserCommand, CreatePostCommand};
use Grimarina\Blog_Project\Blog\Repositories\UsersRepositories\{InMemoryUsersRepository, UsersRepository};
use Grimarina\Blog_Project\Blog\Repositories\PostsRepositories\PostsRepository;


    $connection = new PDO('sqlite:' . __DIR__ . '/blog.sqlite');

    $faker = Faker\Factory::create();
    
    //$usersRepository = new UsersRepository($connection);
    $postsRepository = new PostsRepository($connection);

    
    //$command = new CreateUserCommand($usersRepository);
    $command = new CreatePostCommand($postsRepository);


try {

    $command->handle(Arguments::fromArgv($argv));


    // Проверка Users

    // $usersRepository->save(new User(
    //     UUID::random(),
    //     'admin',
    //     $faker->firstName(), 
    //     $faker->lastName(),
    //     )
    // );


    //$user = $usersRepository->getByUsername('marina');
    //print($user);
    
    
    // Проверка Posts

    // $postsRepository->save(new Post(
    //     UUID::random(),
    //     '9127e521-7ac0-4357-b6c5-b1bcc01ba613',
    //     $faker->title(), 
    //     $faker->text(),
    //     ));

    //php cli.php author_uuid=9127e521-7ac0-4357-b6c5-b1bcc01ba613 title=first post text=Hello everyone!

   // echo $postsRepository->get(New UUID('f440d768-3a0f-41fd-bafc-ed38c16252bc'));



    
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