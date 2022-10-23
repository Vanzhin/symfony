<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:article-statistics',
    description: 'Выводит статистику статьи',
)]
class ArticleStatisticsCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->addArgument('slug', InputArgument::REQUIRED, 'слаг статьи')
            ->addOption('format', null, InputOption::VALUE_REQUIRED, 'Формат выводв', 'text');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $slug = $input->getArgument('slug');
        $format = $input->getOption('format');

        $data = [
            'slug' => $slug,
            'title' => ucfirst(str_replace('-', ' ', $slug)),
            'likes' => rand(10, 50)
        ];
        switch ($format) {
            case 'text':
                $io->table(array_keys($data), [$data]);
                break;
            case 'json':
                $io->write(json_encode($data));
                break;
            default:
                throw new \Exception('Незнакомый формат');
        }

        return Command::SUCCESS;
    }
}
