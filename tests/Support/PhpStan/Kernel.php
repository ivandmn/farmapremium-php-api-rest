<?php

declare(strict_types=1);

use App\Infrastructure\Symfony\Kernel;
use Symfony\Component\Dotenv\Dotenv;

require_once dirname(__DIR__ . '/../../../vendor/autoload.php');

if (!isset($GLOBALS['phpstanKernel'])) {
    new Dotenv()->bootEnv(__DIR__ . '/../../../.env');
    $kernel = new Kernel($_SERVER['APP_ENV'], (bool) $_SERVER['APP_DEBUG']);
    $kernel->boot();

    $GLOBALS['phpstanKernel'] = $kernel;
}

return $GLOBALS['phpstanKernel'];
