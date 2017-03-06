<?php

namespace Controller;

use Model\UserModel;
use Model\GameModel;

class BaseController
{
    protected $name = 'Index';
    protected $layout = 'default';
    protected $data;
    protected $message;

    public function __construct()
    {
        $UserModel = new UserModel();
        $this->data['online'] = $UserModel->whoOnline();
        if (!empty($_SESSION['userId'])) {
        $this->data['in_game'] = $UserModel->inGame($_SESSION['userId']);}
        $GameModel = new GameModel();
        $this->data['open_room'] = $GameModel->openRoom();
    }

    protected function render($templateName)
    {
        $data = $this->data;
        $message = $this->message;
        ob_start();
        //var_dump(SITE_DIR . DS. "View" . DS . $this->name . DS . 'login' . '.php');exit();
        include SITE_DIR . DS. "View" . DS . $this->name . DS . $templateName . '.php';
        $content = ob_get_clean();
        include SITE_DIR . DS. "View" . DS . "Layout" . DS . $this->layout .'.php';
    }
}