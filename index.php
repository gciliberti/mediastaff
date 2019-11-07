<?php

require 'vendor/autoload.php';
require 'src/mf/utils/ClassLoader.php';
session_start();

$loader = new \mf\utils\ClassLoader('src');
$loader->register();
$array = parse_ini_file("conf/config.ini");

//\app\view\AppView::addStyleSheet("html/css/style.css");
//\app\view\AppView::addStyleSheet("html/css/bootstrap.css");
//\app\view\AppView::addStyleSheet("html/css/bootstrap-grid.css");
\app\view\AppView::addStyleSheet("html/css/main.css");


$db = new \Illuminate\Database\Capsule\Manager();
$db->addConnection($array);
$db->setAsGlobal();
$db->bootEloquent();

$router = new \mf\router\Router();

$router->addRoute('home',
'/home/',
'\app\control\AppController',
'viewHome',\app\auth\AppAuthentification::ACCESS_LEVEL_NONE);



$router->addRoute('borrow',
'/borrow/',
'\app\control\AppController',
'viewBorrow',\app\auth\AppAuthentification::ACCESS_LEVEL_NONE);

$router->addRoute('return',
'/return/',
'\app\control\AppController',
'viewReturn',\app\auth\AppAuthentification::ACCESS_LEVEL_NONE);

$router->addRoute('addDoc',
'/addDoc/',
'\app\control\AppController',
'viewAddDoc',\app\auth\AppAuthentification::ACCESS_LEVEL_NONE);

$router->addRoute('users',
'/users/',
'\app\control\AppController',
'viewUserRegister',\app\auth\AppAuthentification::ACCESS_LEVEL_NONE);

$router->addRoute('userModify',
    '/userModify/',
    '\app\control\AppController',
    'viewUserModify',\app\auth\AppAuthentification::ACCESS_LEVEL_NONE);

$router->addRoute('viewUser',
'/viewUser/',
'\app\control\AppController',
'viewUserInfo',\app\auth\AppAuthentification::ACCESS_LEVEL_NONE);

$router->addRoute('borrowSummary',
'/borrowSummary/',
'\app\control\AppController',
'viewBorrowSummary',\app\auth\AppAuthentification::ACCESS_LEVEL_NONE);

$router->addRoute('returnSummary',
'/returnSummary/',
'\app\control\AppController',
'viewReturnSummary',\app\auth\AppAuthentification::ACCESS_LEVEL_NONE);

$router->addRoute('checkReturn',
'/checkreturn/',
'\app\control\AppController',
'checkReturn',\app\auth\AppAuthentification::ACCESS_LEVEL_NONE);

$router->addRoute('checkBorrow',
'/checkborrow/',
'\app\control\AppController',
'checkBorrow',\app\auth\AppAuthentification::ACCESS_LEVEL_NONE);

$router->addRoute('checkDoc',
'/checkdoc/',
'\app\control\AppController',
'checkDoc',\app\auth\AppAuthentification::ACCESS_LEVEL_NONE);


$router->setDefaultRoute('/home/');


$router->run();
