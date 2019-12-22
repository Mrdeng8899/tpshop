<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;

class Login extends Controller
{
    public function login(){
        return view();
    }
    public function dologin(){
        // 接收参数
        $params=input();
//        dump($params);die;
        // 定义验证的参数
        $rule=[
          'username'=>'require',
          'password'=>'require',
        ];
        // 参数检测
        $validate=$this->validate($params,$rule);
        // 判断是否正确
        if($validate !=true){
            $this->error("账号或者密码错误 请重新输入");
        }
        // 调用封装的加密函数

        $password=encrypt_password($params['password']);
        // 查询数据表
       $user=\app\admin\model\Login::where('l_username','=',$params['username'])->where('l_password','=',$password)->find();
//       dump($user);die;
       if($user){
        session('user_info',$user->toArray());
           $res=[
               'code'=>200,
               'msg'=>"登入成功",
           ];
           return $res;
       }else{
           $res=[
              'code'=>400,
             'msg'=>"登入失败",
           ];
           return $res;die;
       }

    }
}
