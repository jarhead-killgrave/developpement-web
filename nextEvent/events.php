<?php
// Root of the project
set_include_path(__DIR__ . '/src/');
// Set the root path
require_once 'src/utils/AutoLoader.php';
require_once '../../private/dev/mysql_config.php';

use model\event\EventStorageMySQL;
use model\user\UserStorageMysql;
use utils\AutoLoader;
use App\Router;

AutoLoader::init();


// Create the event and user storage
$dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
$pdo = new PDO($dsn, DB_USER, DB_PASSWORD);
$eventsStorage = new EventStorageMySQL($pdo);
//$eventsStorage = new \model\event\EventStorageFile('../../tmp/events.db');
$usersStorage = new UserStorageMysql($pdo);
//$usersStorage = new \model\user\UserStorageFile('../../tmp/users.db');


$router = new Router($eventsStorage, $usersStorage, true);
$router->route();
