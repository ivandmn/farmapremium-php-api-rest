<?php

declare(strict_types=1);

$kernel = require __DIR__ . '/Kernel.php';

return $kernel->getContainer()->get('doctrine')->getManager();
