<?php
namespace app\news\controller;
use think\Cache;
use app\index\model\News;
class Index
{
    public function index()
    {
        $cache = Cache::get('news');
        if(!$cache){
            $cache = collection(News::all())->toArray();
            Cache::set('news', $cache, 200);
        }
        return json_encode($cache);
    }
}
