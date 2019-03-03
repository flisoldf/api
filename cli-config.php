<?php
/**
 * Created by PhpStorm.
 * User: francisco
 * Date: 2019-03-03
 * Time: 14:20
 */

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Slim\Container;

/** @var Container $container */
$container = require_once __DIR__ . '/bootstrap.php';

ConsoleRunner::run(
    ConsoleRunner::createHelperSet($container[EntityManager::class])
);