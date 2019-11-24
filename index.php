<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;

require 'vendor/autoload.php';

$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

$app = new \Slim\App(["settings" => $config]);

$container = $app->getContainer();

$container['dbal'] = function() {
    $dbconfig = require './config/database.php';
    $connection = DriverManager::getConnection(array(
        'dbname' => $dbconfig['name'],
        'user' => $dbconfig['username'],
        'password' => $dbconfig['password'],
        'host' => $dbconfig['host'],
        'driver' => 'pdo_mysql',
        'charset' => 'utf8',
        'driverOptions' => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
        )
    ), $config = new Configuration);
    return $connection;
};

$container['view'] = function ($c) {
    $view = new \Slim\Views\Twig('templates', [
        //'cache' => 'cache'
    ]);
    $basePath = rtrim(str_ireplace('index.php', '', $c['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new \Slim\Views\TwigExtension($c['router'], $basePath));

    return $view;
};

$container['baseUrl'] = 'http://' . $_SERVER['HTTP_HOST'] . '/rsp/';

$app->get('/', function (Request $request, Response $response, array $args) {
     
    $messages = null;
    if (isset($_SESSION['login']) && !empty($_SESSION['login'])) {
            if (isset($_SESSION['id']) && !empty($_SESSION['id'])) {
                $messages = \Entity\Message::getMessageById($this->dbal, $_SESSION['id']);
            }
        $user = array('isLogged' => 'true', 'login' => $_SESSION['login'], 'role' => $_SESSION['role']);
    } else {
        $user = array('isLogged' => 'false');
    } 

    if ($messages == null) {
        $messageCount = 0;
    } else {
        $messageCount = count($messages) - 1;
    }

    return $this->view->render($response, 'Main.html.twig', ['user' => $user, 'messageCount' => $messageCount, 'baseUrl' => $this->baseUrl]); 

});

$app->get('/article/{id}', function ($request, $response, $args) {

    $messages = null;
    if (isset($_SESSION['login']) && !empty($_SESSION['login'])) {
        if (isset($_SESSION['id']) && !empty($_SESSION['id'])) {
            $messages = \Entity\Message::getMessageById($this->dbal, $_SESSION['id']);
        }
        $user = array('isLogged' => 'true', 'login' => $_SESSION['login'], 'role' => $_SESSION['role']);
    } else {
        $user = array('isLogged' => 'false');
    } 

    if ($messages == null) {
        $messageCount = 0;
    } else {
        $messageCount = count($messages) - 1;
    }

    return $this->view->render($response, 'Article.html.twig', ["articleID" => $args["id"], 'user' => $user, 'messageCount' => $messageCount, 'baseUrl' => $this->baseUrl]); 

});

$app->get('/messages', function ($request, $response, $args) {

    $messages = null;
    if (isset($_SESSION['login']) && !empty($_SESSION['login'])) {
        if (isset($_SESSION['id']) && !empty($_SESSION['id'])) {
            $messages = \Entity\Message::getMessageById($this->dbal, $_SESSION['id']);
        }
        $user = array('isLogged' => 'true', 'login' => $_SESSION['login'], 'role' => $_SESSION['role']);
    } else {
        $user = array('isLogged' => 'false');
    } 

    if ($messages == null) {
        $messageCount = 0;
    } else {
        $messageCount = count($messages) - 1;
    }

    foreach ($messages as $message) {
        if ($message !== null) {
            $messageSender = \Entity\Login::load($this->dbal, $message->getSender());
            $messageSenders[] = (string)($messageSender->getLogin());
        }
    }

    return $this->view->render($response, 'Messages.html.twig', ['user' => $user, 'messages' => $messages, 'messageSenders' => $messageSenders, 'messageCount' => $messageCount, 'baseUrl' => $this->baseUrl]); 
  

});

$app->get('/helpdesk', function ($request, $response, $args) {

    $messages = null;
    $helpdeskMessages = null;
    if (isset($_SESSION['login']) && !empty($_SESSION['login'])) {
        if (isset($_SESSION['id']) && !empty($_SESSION['id'])) {
            $messages = \Entity\Message::getMessageById($this->dbal, $_SESSION['id']);
        }
        $user = array('isLogged' => 'true', 'login' => $_SESSION['login'], 'role' => $_SESSION['role']);
        if ($_SESSION['role'] == "Adminstrátor") {
            $helpdeskMessages = \Entity\Message::getAllHelpdeskMessages($this->dbal);
        }
    } else {
        $user = array('isLogged' => 'false');
    } 

    if ($messages == null) {
        $messageCount = 0;
    } else {
        $messageCount = count($messages) - 1;
    }

    foreach ($helpdeskMessages as $helpdeskMessage) {
        if ($helpdeskMessage !== null) {
            $messageSender = \Entity\Login::load($this->dbal, $helpdeskMessage['ID_recipient']);
            $messageSenders[] = (string)($messageSender->getLogin());
        }
    }

    return $this->view->render($response, 'Helpdesk.html.twig', ['user' => $user, 'messages' => $messages, 'helpdeskMessages' => $helpdeskMessages, 'messageSenders' => $messageSenders, 'messageCount' => $messageCount, 'baseUrl' => $this->baseUrl]); 

});

$app->post('/signup', function ($request, $response, $args) {
    
    $email = $_POST['email'];
    $pwd = $_POST['pwd'];
    $pwdconfirm = $_POST['pwdconfirm'];
    $role = $_POST['role'];

    if ($role == null) {
        $role = 'autor';
    }

    if ($pwd == $pwdconfirm) {
        $signUp = \Entity\Login::SignUp($this->dbal, $email , $pwd, $role);
        $result = 1;
    } else {
        $result = 0;
    }

    echo json_encode(array(
        'result' => $result
    ));

});

$app->post('/login', function ($request, $response, $args) {
    
    $email = $_POST['email'];
    $pwd = $_POST['pwd'];

    if ($login = \Entity\Login::Login($this->dbal, $email, $pwd)) {
       
        $_SESSION["login"] = $login['Login_name'];
        $_SESSION["id"] = $login['ID'];
        $_SESSION["role"] = $login['Role'];
        $result = 1;
    } else {
        $_SESSION["login"] = null;
        $_SESSION["id"] = null;
        $_SESSION["role"] = null;
        $result = 0;
    }

    echo json_encode(array(
        'result' => $result
    ));

});

$app->post('/logout', function ($request, $response, $args) {
    
    session_destroy();

    if (session_status() === PHP_SESSION_NONE) {
        $result = 1;
    } else {
        $result = 0;
    }

    echo json_encode(array(
        'result' => $result
    ));

});

$app->post('/sendmessage', function ($request, $response, $args) {
    
    $sendto = $_POST['sendto'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    $result = \Entity\Message::sendMessage($this->dbal, $sendto, $_SESSION['id'], $subject, $message);
      
    echo json_encode(array(
        'result' => $result
    ));

});

$app->post('/sendhelpdeskmessage', function ($request, $response, $args) {
    
    $sendto = $_POST['sendto'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    $result = \Entity\Message::sendHelpdeskMessage($this->dbal, $sendto, $subject, $message);
      
    echo json_encode(array(
        'result' => $result
    ));

});


$app->run();


