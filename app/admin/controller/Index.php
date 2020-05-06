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
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function index(Request $request)
    {
        $page = (int) $request->param('page',1);
        $type = 'news';
        $count = 10;
        $config = Config::get('wx');

        try{
            $app = Factory::officialAccount($config);
            $list = $app->material->list($type, ($page-1)*$count, $count);
            //dump($list['item']);
        }catch (\Exception $e){
            return $e->getMessage();
        }

        //数组分页
        $pages_data = [];
        if($list){
            $p = Bootstrap::make($list, $count, $page, $list['total_count']);
            $p->appends($_GET);
            $pages_data = $p->render();
        }

        View::assign('list',$list['item']);
        View::assign('pages',$pages_data);

        return View::fetch();
    }

    /**
     * @param Request $request
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function update(Request $request)
    {
        $mediaId = $request->param('id');
        if($mediaId){
            $config = Config::get('wx');
            try{
                $app = Factory::officialAccount($config);
                $resource = $app->material->get($mediaId);
                View::assign('resource',$resource);
            }catch (\Exception $e){
                return $e->getMessage();
            }
        }

        return View::fetch();
    }

    /**
     * @param Request $request
     * @return string
     */
    public function save(Request $request)
    {
        $mediaId = $request->param('id');
        $index = (int) $request->param('i');
        if($mediaId){
            $config = Config::get('wx');
            try{
                $app = Factory::officialAccount($config);
                /*$result = $app->material->updateArticle($mediaId, [
                    'title' => '测试内容！',
                    'thumb_media_id' => '', // 封面图片 mediaId
                    'author' => 'kangly',
                    'show_cover' => 1, // 是否在文章内容显示封面图片
                    'digest' => '这里是文章摘要',
                    'content' => '这里是文章内容，你可以放很长的内容',
                    'source_url' => 'https://www.baidu.com',
                ],$index);*/
                return 'success';
            }catch (\Exception $e){
                return $e->getMessage();
            }
        }else{
            return 'error';
        }
    }

    /**
     * @param Request $request
     */
    public function delete(Request $request)
    {
        $mediaId = $request->param('id');
        $index = (int) $request->param('i');
    }
}