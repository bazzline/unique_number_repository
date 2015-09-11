<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2015-09-07
 */

require __DIR__ . '/../vendor/autoload.php';

use flight\Engine;
use Net\Bazzline\UniqueNumberRepository\Application\Application;
use Net\Bazzline\UniqueNumberRepository\Application\Service\ApplicationLocator;

const VERSION = '1.0.0';

$engine     = new Engine();
$locator    = new ApplicationLocator();

Application::create($engine, $locator)
    ->andRun();
