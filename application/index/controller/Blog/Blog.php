<?php
/**
 * Created by PhpStorm.
 * User: wcx
 * Date: 2018/7/4
 * Time: 15:59
 */

namespace app\index\controller\Blog;

use think\Loader;
use think\Request;
use think\Validate;
class blog
{
    public function index(){
        // 1.独立验证
//        $validate = new Validate([
//            'name'  => 'require|max:25',
//            'email' => 'email'
//        ]);
//        $data = [
//            'name'  => 'thinkphp',
//            'email' => 'thinkphp@qq.com'
//        ];
//        if (!$validate->check($data)) {
//            dump($validate->getError());
//        }
       // 2.验证器验证  推荐
        $data['age'] = 30;
        $data['name'] = 'zhangsansdfsdfsdfsdfsdfsdfsdfsdfsdfsdfsdfsdfsdf';
      //  $validate = Loader::validate('Blog');
         $validate = validate('Blog');
        if(!$validate->batch()->check($data)){
            dump($validate->getError());
        }

    }
    public function find(Request $request){
        $param = $request->param('');
       echo $param['id'];
    }


}