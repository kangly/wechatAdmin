<?php
declare (strict_types = 1);

namespace app\admin\controller;

use app\Request;
use think\facade\View;
use think\facade\Config;
use EasyWeChat\Factory;
use app\common\Bootstrap;

class Index extends Base
{
    /**
     * 加载微信公众号图文消息
     * @param Request $request
     * @return string
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function index(Request $request)
    {
        $page = (int) $request->param('page');
        $type = 'news';
        $count = 10;
        $config = Config::get('wx');

        try{
            $app = Factory::officialAccount($config);
            $list = $app->material->list($type, ($page-1)*$count, $count*$page);
        }catch (\Exception $e){
            dump($e->getMessage());
            exit();
        }

        //数组分页
        $pages_data = [];
        if($list){
            $current_page = $page ? $page : 1;
            $p = Bootstrap::make($list, $count, $current_page, $list['total_count']);
            $p->appends($_GET);
            $pages_data = $p->render();
        }

        View::assign('list',$list['item']);
        View::assign('pages',$pages_data);

        return View::fetch();
    }
}
