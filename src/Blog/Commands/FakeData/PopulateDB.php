<?php

namespace Grimarina\Blog_Project\Blog\Commands\FakeData;

use Grimarina\Blog_Project\Blog\Post;
use Grimarina\Blog_Project\Blog\Repositories\PostsRepositories\PostsRepositoryInterface;
use Grimarina\Blog_Project\Blog\Repositories\UsersRepositories\UsersRepositoryInterface;
use Grimarina\Blog_Project\Blog\User;
use Grimarina\Blog_Project\Blog\UUID;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class PopulateDB extends Command
{
    public function __construct(
        private \Faker\Generator $faker,
        private UsersRepositoryInterface $usersRepository, 
        private PostsRepositoryInterface $postsRepository,
    ){ 
        parent::__construct();
    }
        
    protected function configure(): void 
    {
        $this
            ->setName('fake-data:populate-db') 
            ->setDescription('Populates DB with fake data')
            ->addOption(
                'users-number',
                'u',
                InputOption::VALUE_OPTIONAL,
                'Users number'
            )
            ->addOption(
                'posts-number',
                'p',
                InputOption::VALUE_OPTIONAL,
                'Posts number'
            );
    }
    
    protected function execute( 
        InputInterface $input, 
        OutputInterface $output,
    ): int 
    {
        $usersNum = $input->getOption('users-number') ?? 10;

        $users = [];
        for ($i = 0; $i < $usersNum & $i < 100; $i++) {
            $user = $this->createFakeUser();
            $users[] = $user;
            $output->writeln('User created: ' . $user->getUsername());
        }
        
        $postsNum = $input->getOption('posts-number') ?? 10;

        foreach ($users as $user) {
            for ($i = 0; $i < $postsNum & $i < 50; $i++) {
                $post = $this->createFakePost($user); 
                $output->writeln('Post created: ' . $post->getTitle()); 
            }
        }

        return Command::SUCCESS; 
    }

    private function createFakeUser(): User 
    {
        $user = User::createFrom(
            $this->faker->userName, 
            $this->faker->password, 
            $this->faker->firstName, 
            $this->faker->lastName
        );
    
        $this->usersRepository->save($user); 
        
        return $user;
    }

    private function createFakePost(User $author): Post 
    {
        $post = new Post( 
            UUID::random(),
            $author->getUuid(),
            $this->faker->sentence(6, true), 
            $this->faker->realText
        );
        
        $this->postsRepository->save($post); 
        
        return $post;
    }

}