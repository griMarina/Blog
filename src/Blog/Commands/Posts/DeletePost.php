<?php

namespace Grimarina\Blog_Project\Blog\Commands\Posts;

use Grimarina\Blog_Project\Blog\Repositories\PostsRepositories\PostsRepositoryInterface;
use Grimarina\Blog_Project\Blog\UUID;
use Grimarina\Blog_Project\Exceptions\PostNotFoundException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class DeletePost extends Command
{
    public function __construct(
        private PostsRepositoryInterface $postRepository,
    )
    {
        parent::__construct();
    }

    protected function configure():void
    {
        $this
            ->setName('posts:delete')
            ->setDescription('Delete a post')
            ->addArgument('uuid', InputArgument::REQUIRED, 'UUID of a post to delete')
            ->addOption(
                'check-existence',
                'c',
                InputOption::VALUE_NONE,
                'Check if post actually exists',
            );
    }

    protected function execute(
        InputInterface $input, 
        OutputInterface $output
    ): int
    {
       $question = new ConfirmationQuestion('Delete post [Y/n]? ', false);
       
       if (!$this->getHelper('question')->ask($input, $output, $question)) {
        return Command::SUCCESS;
       }

       $uuid = new UUID($input->getArgument('uuid'));

       if ($input->getOption('check-existence')) {
            try {
                $this->postRepository->get($uuid);
            } catch (PostNotFoundException $error) {
                $output->writeln($error->getMessage());
                return Command::FAILURE;
            }
       }

       $this->postRepository->delete($uuid);

       $output->writeln("Post $uuid deleted");

       return Command::SUCCESS;
    }
}