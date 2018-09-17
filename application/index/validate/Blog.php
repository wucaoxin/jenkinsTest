<?php
/**
 * Created by PhpStorm.
 * User: wcx
 * Date: 2018/7/6
 * Time: 10:35
 */

namespace app\index\validate;


use think\Validate;

class Blog extends Validate
{
    protected $rule = [
                'name'=>'require|max:25',
                'age'=>'require|number|between:1,20'
    ];
 //适用于验证规则中有|的 比如正则表达式
//           protected $rules = [
//            'name'  => ['require','max'=>25],
//            'age'   => ['number','between'=>'1,120'],
//            ];
    protected $message = [
        'name.require' => '名称必须',
        'name.max'     => '名称最多不能超过25个字符',
        'age.number'   => '年龄必须是数字',
        'age.require'   => '年龄必须1',
        'age.between'  => '年龄必须在1~120之间',
        'email'        => '邮箱格式错误',
    ];

}