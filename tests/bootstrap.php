<?php

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';
require_once getenv('COMPOSER_HOME') . '/vendor/autoload.php';

if (file_exists(dirname(__DIR__).'/config/bootstrap.php')) {
    require dirname(__DIR__).'/config/bootstrap.php';
} elseif (method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->bootEnv(dirname(__DIR__).'/.env');
}

if (!getenv('INIT_DB')) {
    return;
}

$kernel = new App\Kernel('test', true);
$kernel->boot();

$application = new Symfony\Bundle\FrameworkBundle\Console\Application($kernel);
$application->setAutoExit(false);

/*
 * terminate all connections
 */
try {
    $sql = <<<EOT
    SELECT pid, pg_terminate_backend(pid) 
    FROM pg_stat_activity 
    WHERE datname = current_database() AND pid <> pg_backend_pid();
EOT;
    $kernel->getContainer()->get('doctrine')->getConnection()->exec($sql);
} catch (Exception $e) {
    printf('Warning test DB failed: "%s"', $e->getMessage());
}

$application->run(new Symfony\Component\Console\Input\ArrayInput([
    'command' => 'doctrine:database:drop',
    '--if-exists' => '1',
    '--force' => '1',
]));

$application->run(new Symfony\Component\Console\Input\ArrayInput([
    'command' => 'doctrine:database:create',
]));

$application->run(new Symfony\Component\Console\Input\ArrayInput([
    'command' => 'doctrine:migration:migrate',
    '--no-interaction' => '1',
]));

$application->run(new Symfony\Component\Console\Input\ArrayInput([
    'command' => 'doctrine:fixtures:load',
    '--append' => 1,
    '--no-interaction' => '1',
]));

$kernel->shutdown();