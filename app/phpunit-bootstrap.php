<?php

/**
 * Helper file to initialize test database for phpunit to run tests against.
 *
 * @author Atte Tarvainen <atte.tarvainen@pp1.inet.fi>
 */

require_once 'autoload.php';

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Input\ArrayInput;

use Doctrine\Bundle\DoctrineBundle\Command\DropDatabaseDoctrineCommand;
use Doctrine\Bundle\DoctrineBundle\Command\CreateDatabaseDoctrineCommand;
use Doctrine\Bundle\DoctrineBundle\Command\Proxy\UpdateSchemaDoctrineCommand;
use Doctrine\Bundle\FixturesBundle\Command\LoadDataFixturesDoctrineCommand;
use Doctrine\Bundle\MigrationsBundle\Command\MigrationsMigrateDoctrineCommand;

$kernel = new AppKernel('test', true);
$kernel->boot();

$application = new Application($kernel);

/** 1. Drop database */
$command = new DropDatabaseDoctrineCommand();
$application->add($command);

$input = new ArrayInput(array(
    'command' => 'doctrine:database:drop',
    '--force' => true,
));

$command->run($input, new ConsoleOutput());

/** 2. Create database */
$command = new CreateDatabaseDoctrineCommand();
$application->add($command);

$input = new ArrayInput(array(
    'command' => 'doctrine:database:create',
));

$command->run($input, new ConsoleOutput());

/** 3. Update schema */
$command = new UpdateSchemaDoctrineCommand();
$application->add($command);

$input = new ArrayInput(array(
    'command' => 'doctrine:schema:update',
    '--force' => true
));

$command->run($input, new ConsoleOutput());

/** 4. Load fixtures */
$command = new LoadDataFixturesDoctrineCommand();
$application->add($command);

$input = new ArrayInput(array(
    'command' => 'doctrine:fixtures:load'
));

$command->run($input, new ConsoleOutput());

/** 5. Migrate */
$command = new MigrationsMigrateDoctrineCommand();
$application->add($command);

$input = new ArrayInput(array(
    'command' => 'doctrine:migrations:migrate'
));

$command->run($input, new ConsoleOutput());

/** Yippee, we are done! */

