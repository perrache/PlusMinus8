<?php

namespace App\Maker;

use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;

final class MakePmController extends AbstractMaker
{
    public static function getCommandName(): string
    {
        return 'make:pmcontroller';
    }

    public static function getCommandDescription(): string
    {
        return 'Create a new PlusMinus controller class';
    }

    public function configureCommand(Command $command, InputConfiguration $inputConfig): void
    {
        $command
            ->addArgument('tableName', InputArgument::REQUIRED, 'Name of the table')
            ->setHelp(file_get_contents(__DIR__ . '/MakePmController.txt'))
        ;
    }

    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator): void
    {
        $generator->generateClass('App\Controller\CRUD', __DIR__ . '/MakePmController.tpl.php');
        $generator->writeChanges();
        $this->writeSuccessMessage($io);
    }

    public function configureDependencies(DependencyBuilder $dependencies): void
    {
    }
}
