<?php

namespace Controller;

use Model\LoginModel;
use Model\GameModel;

class LoginController extends BaseController // вход в на сайт
{
    protected $name = 'Login';
    private $users = array();

    public function login()
    {
        if ($_POST)
        {
            $loginModel = new LoginModel();
            $users = $loginModel->getUsers($_POST['login'], $_POST['password']);
            if (sizeof($users)) {
                $_SESSION['userId'] = $users[0]['id'];
                $_SESSION['user'] = $_POST['login'];
                $_SESSION['isLogged'] = TRUE;
                $loginModel->online($users[0]['id']);
            } else {
                $this->message = "Введены неверные данные";
            }
        }

        if (!isset($_SESSION['isLogged']))
        {
            $this->render("login");
        } else
        {
            return header("Location: http://".SITE_HOST."/");
        }
    }

    public function signup() // регистрация
    {
        if ($_POST)
        {
            $loginModel = new LoginModel();
            $users = $loginModel->getUser($_POST['login'],$_POST['email']);
            if (sizeof($users))
            {
                $this->message = "Пользователь с такими логином или электронной почтой уже зарегистрирован";
            } elseif ($loginModel->save($_POST))
            {
                $this->message = "Регистрация прошла успешно! Можете залогинится!";
                header("Location: http://".SITE_HOST."/login/login/");
            }
        }
        $this->render("signup");
    }

    public function logout() // выход
    {
        $loginModel = new LoginModel();
        $loginModel->offline($_SESSION['userId']);
        $GameModel = new GameModel();
        $GameModel->outputGame($_SESSION['user']);
        $GameModel->destroyRoom($_SESSION['user']);
        session_unset();
        $this->render("logout");
    }
}



