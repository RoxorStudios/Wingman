<?php

use DI\ContainerBuilder;
use Symfony\Component\Yaml\Yaml;

/*
|--------------------------------------------------------------------------
| Composer
|--------------------------------------------------------------------------
*/

require __DIR__.'/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Create the application
|--------------------------------------------------------------------------
*/

$app = new ContainerBuilder();
$app->addDefinitions([
	'Config' 								=> Yaml::parseFile(__DIR__.'/../config.yaml'),

	'Wingman\Interfaces\DatabaseInterface' 	=> DI\autowire('Wingman\Repositories\Database\JsonDatabase'),
	'Wingman\Interfaces\JobInterface' 		=> DI\autowire('Wingman\Repositories\Job\DatabaseJob'),
]);

/*
|--------------------------------------------------------------------------
| Build our application
|--------------------------------------------------------------------------
*/

return $app->build();