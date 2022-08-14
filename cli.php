<?php

require_once __DIR__ . "/vendor/autoload.php";

use Grimarina\Blog_Project\Blog\{User, Post, Comment, UUID};
use Grimarina\Blog_Project\Blog\Commands\{Arguments, CreateUserCommand, CreatePostCommand, CreateCommentCommand};
use Grimarina\Blog_Project\Blog\Repositories\UsersRepositories\{InMemoryUsersRepository, UsersRepository};
use Grimarina\Blog_Project\Blog\Repositories\PostsRepositories\PostsRepository;
use Grimarina\Blog_Project\Blog\Repositories\CommentsRepositories\CommentsRepository;


    $connection = new PDO('sqlite:' . __DIR__ . '/blog.sqlite');

    //$faker = Faker\Factory::create();
    
    //$usersRepository = new UsersRepository($connection);
    $postsRepository = new PostsRepository($connection);
    //$commentsRepository = new CommentsRepository($connection);


    
    //$command = new CreateUserCommand($usersRepository);
    //$command = new CreatePostCommand($postsRepository);
    //$command = new CreateCommentCommand($commentsRepository);



try {

    //$command->handle(Arguments::fromArgv($argv));


    // Проверка Users

    // $usersRepository->save(new User(
    //     UUID::random(),
    //     'admin',
    //     $faker->firstName(), 
    //     $faker->lastName(),
    //     )
    // );

    //echo $user = $usersRepository->getByUsername('marina');
    
    
    // Проверка Posts

    // $postsRepository->save(new Post(
    //     UUID::random(),
    //     UUID::random(),
    //     $faker->title(), 
    //     $faker->text(),
    //     ));

    //php cli.php author_uuid=9127e521-7ac0-4357-b6c5-b1bcc01ba613 title=My_first_post text=Hello_everyone!_How_are_you?

   //echo $postsRepository->get(New UUID('3e00843d-02e5-4837-bba5-a5eb9d33697d'));

   //$postsRepository->deletePost(new UUID('8bb92fbb-ae7f-4d77-8908-dc2e9ac047d8'));

   // Проверка Comments

    // $commentsRepository->save(new Comment(
    //     UUID::random(),
    //     UUID::random(),
    //     UUID::random(),
    //     $faker->text(),
    //     ));


    //php cli.php post_uuid=f440d768-3a0f-41fd-bafc-ed38c16252bc author_uuid=9127e521-7ac0-4357-b6c5-b1bcc01ba613 text=I_like_this_post!

    //echo $commentsRepository->get(New UUID('aec6e3bc-9e35-4b89-bc26-063fa351bd2f'));

    
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