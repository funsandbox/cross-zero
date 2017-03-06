<?php

namespace Controller;

use Model\UserModel;

class UserController extends BaseController
{
    protected $name = 'User';

    public function profile()
    {
        $UserModel = new UserModel();
        if($_GET['id'])
        {
            $this->data['profile'] = $UserModel->profile($_GET['id']);
        }
        $this->render("profile");
    }
}