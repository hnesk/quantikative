<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jk
 * Date: 02.07.13
 * Time: 19:56
 * To change this template use File | Settings | File Templates.
 */
error_reporting(E_ALL);
ini_set('display_errors',1);

define('BASE_DIR', realpath(__DIR__.'/..'));
require_once BASE_DIR.'/vendor/autoload.php';

$app = new Wahlomat\Application();
$app->run();
