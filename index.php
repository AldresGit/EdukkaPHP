<?php

require '/app/Slim/Slim.php'; //require '.././Slim/Slim.php';
require_once 'user-service.php';
require_once 'class-service.php';
require_once 'game-service.php';
require_once 'quiz-service.php';
require_once 'multiplayer-game-service.php';
\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();
$app->contentType('application/json');

$app->get('/', function () use ($app) {
    $app->response->redirect('index.html');
});

// User Service
$app->get('/users', 'getAllUsers');
$app->get('/user/:id', 'getUser');
$app->get('/user/activity/:id', 'getUserActivity');
$app->post('/login', 'logIn');
$app->post('/signup', 'signUp');
$app->post('/user/score', 'updateUserScore');
$app->post('/user/edit', 'updateUser');
$app->post('/user/delete', 'deleteUser');

// Class Service
$app->get('/classes', 'getAllClasses');
$app->get('/class/:id', 'getClass');
$app->get('/myclass/:id', 'getUserClass');
$app->get('/class/activity/:id', 'getClassActivity');
$app->post('/class/new', 'createClass');
$app->post('/class/edit', 'updateClass');
$app->post('/class/delete', 'deleteClass');
$app->post('/class/adduser', 'addUserClass');
$app->post('/class/remuser', 'removeUserClass');

// Game Service
$app->get('/games', 'getAllGames');
$app->get('/game/:id', 'getGame');
$app->get('/games/:sub', 'getSubjectGames');
$app->post('/game/new', 'createGame');
$app->post('/game/edit', 'updateGame');
$app->post('/game/delete', 'deleteGame');
$app->post('/game/finish', 'finishGame');
$app->post('/game/upvote', 'upvoteGame');
$app->post('/game/downvote', 'downvoteGame');

// Quiz Service
$app->get('/quizzes', 'getAllQuizzes');
$app->get('/quiz/:id', 'getQuiz');
$app->get('/play/:id', 'getGameQuiz');
$app->post('/quiz/new', 'createQuiz');
$app->post('/quiz/edit', 'updateQuiz');
$app->post('/quiz/delete', 'deleteQuiz');

// Multiplayer Game Service
$app->get('/room/:id', 'getRoom');
$app->get('/rooms/:id', 'searchRoom');
$app->get('/room/randomquizzes/:id', 'getRandomQuizzes');
$app->post('/room/new', 'createRoom');
$app->post('/room/join', 'joinRoom');
$app->post('/room/leave', 'leaveRoom');
$app->post('/room/adorn', 'adornRoom');
$app->post('/room/delete', 'deleteRoom');
$app->post('/room/updatequizzes', 'updateQuizzes');
$app->post('/room/getquizzesbyid', 'getQuizzesById');

$app->run();

function getDB() {
    $dbhost = 'bzi56eihrhec7ehmqmbt-mysql.services.clever-cloud.com';
    $dbuser = 'uup09mc4h8d4wecw3cnx';       //$dbuser = 'id5255892_root';
    $dbpass = 'LTZlyCv8R6Lhw05T70vC';           //$dbpass = 'k4zGDiZJ6EqCKnkDOhAH';
    $dbname = 'bzi56eihrhec7ehmqmbt';     //$dbname = 'id5255892_edukka';

    $mysql_conn_string = "mysql:host=$dbhost;dbname=$dbname;charset=utf8mb4";
    $dbConnection = new PDO($mysql_conn_string, $dbuser, $dbpass);
    $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $dbConnection;
}

?>