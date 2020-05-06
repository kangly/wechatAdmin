<?php
declare (strict_types = 1);

namespace app\admin\controller;

use app\admin\model\Admin;
use app\BaseController;
use app\Request;
use think\facade\Config;
use think\facade\Session;
use think\facade\View;

class Login extends BaseController
{
    /**
     * @return string|\think\response\Redirect
     * @throws \Exception
     */
    public function index()
    {
        if(is_login()){
            return redirect((string) url('/index'));
        }

        return View::fetch();
    }

    /**
     * @param Request $request
     * @return string|\think\response\Redirect
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function login(Request $request)
    {
        if(request()->isPost())
        {
            $username = $request->param('username');
            $password = $request->param('password');
            $admin = new Admin();
            $admin_data = $admin->login($username,$password);
            if(!$admin_data){
                return $admin->getError();
            }else{
                $admin_data = $admin_data->toArray();
            }

            $admin->autoLogin($admin_data);

            return redirect((string) url('/index'));
        }
        else
        {
            return '非法操作！';
        }
    }

    /**
     * 退出登录
     */
    public function logout()
    {
        $this->clearCache();
        return redirect((string) url('/login'));
    }

    /**
     * 清空登录session
     */
    public function clearCache()
    {
        if(is_login()){
            Session::clear(Config::get('session.prefix'));
        }
    }
}