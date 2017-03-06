<?php

namespace Controller;


class IndexController extends BaseController
{
    protected $name = 'Index';

    public function index()
    {
        $this->render("main");
    }
}