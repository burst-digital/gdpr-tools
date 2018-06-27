<?php

namespace GdprTools\Command;

use GdprTools\Configuration\Configuration;
use GdprTools\Database\Database;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class AnonymiseCommand extends Command
{

  const ARGUMENT_FILE = 'file';

  protected function configure()
  {
    $this
      ->setName('db:anonymise')
      ->setDescription('Anonymises a database based on a yaml configuration.')
      ->setHelp('Anonymises a database based on a yaml configuration.')
      ->addArgument(
        self::ARGUMENT_FILE,
        InputArgument::REQUIRED,
        'Where is the yaml configuration located')
    ;
  }

  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $io = new SymfonyStyle($input, $output);

    $file = $input->getArgument(self::ARGUMENT_FILE);

    $configuration = new Configuration($file, $io);

    $database = new Database($configuration, $io);

    $connection = $database->getConnection();

    $io->success('Database connection succeeded.');

    $result = $connection->query('SELECT * FROM anonymise_test');

    $rows = [];
    while ($row = $result->fetch()) {
      array_push($rows, $row);
    }

    $io->table(array_keys($rows[0]), $rows);
  }
}
