<?php

namespace Controllers;

use Models\Model;
use Twig\Environment;

class Controller
{
    private Environment $twig;
    private $url = 'http://fefu.ml:1615';

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function showUserInfo($login)
    {
        $model = new Model();
        $result = $model->getUserInfo($login);
        $users = $model->getAllUsers();
        foreach ($result as $curUser) {
            echo $this->twig->render('userinfo.html.twig', ['curUser' => $curUser, 'users' => $users]);
        }
    }

    public function unauthorized()
    {
        $uri = $_SERVER['REQUEST_URI'];
        if (strpos($uri, '/reg?') === 0) {
            echo $this->twig->render('reg.html.twig');
        } else if (strpos($uri, '/run_reg?') === 0) {
            $this->reg();
        } else if (strpos($uri, '/login?') === 0) {
            $this->auth();
            echo $this->twig->render('main.html.twig');
        } else {
            echo $this->twig->render('main.html.twig');
        }

    }

    public function authorized()
    {
        $model = new Model();
        if ($_COOKIE['lHash'] == $model->getLoginHash($_COOKIE['login'])) {
            $this->showUserInfo($_COOKIE['login']);
        } else {
            echo '<h2>Так нельзя!</h2>';
            setcookie('login', '');
            setcookie('lHash', '');
        }
        $uri = $_SERVER['REQUEST_URI'];
        if (strpos($uri, '/logout?') === 0) {
            setcookie('login', '');
            setcookie('lHash', '');
            header('Location: ' . $this->url);
        }
    }

    public function auth()
    {
        $model = new Model();
        $model->setLP($_GET['login'], $_GET['password']);
        $exists = $model->searchByLoginPassword();
        if ($exists) {
            setcookie('login', $model->getLogin(), time()+120);
            setcookie('lHash', $model->getLoginHash($model->getLogin()), time()+120);
            header('Location: ' . $this->url);
        } else {
            echo '<br><br><br><h2 style="text-align: center; font-family: Arial;">Неверный логин или пароль!</h2>';
        }
    }

    public function reg()
    {
        $model = new Model();
        $model->setLP($_GET['login'], $_GET['password']);
        $notExisted = $model->newUser();
        if ($notExisted == true) {
            echo '<br><br><br><h2 style="text-align: center; font-family: Arial;">Регистрация прошла успешно!</h2>';
            echo $this->twig->render('main.html.twig');
        } else {
            echo '<br><br><br><h2 style="text-align: center; font-family: Arial;">Регистрация не удалась!</h2>';
            echo $this->twig->render('reg.html.twig');
        }
    }
}

