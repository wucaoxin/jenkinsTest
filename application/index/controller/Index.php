<?php
namespace app\index\controller;
use QL\QueryList;

class Index
{
    public function index()
    {
        // 采集该页面文章列表中所有[文章]的超链接和超链接文本内容
//        $data = QueryList::get('http://cms.querylist.cc/google/list_1.html')->rules([
//            'link' => ['h2>a','href','',function($content){
//                //利用回调函数补全相对链接
//                $baseUrl = 'http://cms.querylist.cc';
//                return $baseUrl.$content;
//            }],
//            'text' => ['h2>a','text'],
//        ])->range('.cate_list li')->query()->getData();
//        print_r($data->all());

        // 采集该页面文章列表中所有[文章]的超链接和超链接文本内容
//        $data = QueryList::get('http://cms.querylist.cc/google/list_1.html')->rules([
//            'link' => ['a','href','',function($content){
//                //利用回调函数补全相对链接
//                $baseUrl = 'http://cms.querylist.cc';
//                return $baseUrl.$content;
//            }],
//            'text' => ['a','text'],
//        ])->range('.hotnews_list li')->query()->getData();
//        print_r($data->all());
        //需要采集的目标页面
        $page = 'http://cms.querylist.cc/news/566.html';
//采集规则
        $reg = [
            //采集文章标题
            'title' => ['h1','text'],
            //采集文章发布日期,这里用到了QueryList的过滤功能，过滤掉span标签和a标签
            'date' => ['.pt_info','text','-span -a',function($content){
                //用回调函数进一步过滤出日期
                $arr = explode(' ',$content);
                return $arr[0];
            }],
            //采集文章正文内容,利用过滤功能去掉文章中的超链接，但保留超链接的文字，并去掉版权、JS代码等无用信息
            'content' => ['.post_content','html','a -.content_copyright -script']
        ];
        $rang = '.content';
        $ql = QueryList::get($page)->rules($reg)->range($rang)->query();

        $data = $ql->getData(function($item){
            //利用回调函数下载文章中的图片并替换图片路径为本地路径
            //使用本例请确保当前目录下有image文件夹，并有写入权限
            $content = QueryList::html($item['content']);
            $content->find('img')->map(function($img){
                $src = 'http://cms.querylist.cc'.$img->src;
                $localSrc = 'static/images/'.md5($src).'.jpg';
                $stream = file_get_contents($src);
                file_put_contents($localSrc,$stream);
                $img->attr('src',$localSrc);
            });
            $item['content'] = $content->find('')->html();
            return $item;
        });

//打印结果
        dump($data->all());
    }

}
