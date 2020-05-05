<?php
declare (strict_types = 1);

namespace app\admin\controller;

use app\BaseController;
use think\facade\View;

class Login extends BaseController
{
    public function index()
    {
        return View::fetch();
    }

    public function login()
    {
        return 'success';
    }
}