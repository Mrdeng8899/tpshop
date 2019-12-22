<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;

class Admin extends Controller
{
    /*
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        // 显示列表 分页+ 查询
        $params=input();
//        dump($params["keyword"]);die;
//        $where=[];
        if(!empty($params['keyword'])){
//            $keyword=$params['keyword'];
//            $where['username']=['like',"%{$keyword}%"];
            $list= \app\admin\model\Admin::where('username','like',"%{$params['keyword']}%")->paginate(5,false,['query'=>['keyword'=>$params]]);
            return view('list',['list'=>$list]);
        }else{
            $list= \app\admin\model\Admin::paginate(5);
            return view('list',['list'=>$list]);
        }

    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
        return view('add');
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
//        echo 111;die;
        // 接收参数
        $params=input();
        $param['create_time']=time();
//        echo $params;
        // 参数检测
        $rule=[
          'username|用户名','require',
          'call|电话号码','require|integer',
          'email|邮箱','require|email',
          'role|角色','require',
          'password|密码','require',
//          'passwords|确定密码','require',
        ];
        $validate=$this->validate($params,$rule);
        if($validate !==true){
            $res=[
              'code'=>400,
              'msg'=>$validate,
            ];
            return json_encode($res);
        }
        // 添加数据
        $list=\app\admin\model\Admin::create($params,true);
//        echo $list;die;
        $res=[
            'code'=>200,
            'msg'=>'success',
        ];
        return json_encode($res);
        // 显示列表
//        $list= \app\admin\model\Admin::select();
//        return view('list',['list'=>$list]);
//        echo '添加成功';
       }
    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        // 查询参数 展示在页面上面
        $admins=\app\admin\model\Admin::find($id);
//        dump($admins);die();
//        dump($admins);die;
        return view('edit',['admins'=>$admins,'id'=>$id]);
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
        $params=input();
//        dump($params);die;
        // 定义检测的数据
        $rule=[
          'username|登入名字','require',
           'call|电话号码','require|regular:1[3-9]\d{9}',
            'email|邮箱','require|email',
            'role|角色名称','require'
        ];
        $validate=$this->validate($params,$rule);
        if($validate !=true){
//            $this->error($validate);
            $res=[
              'code'=>400,
              'msg'=>$validate,
            ];
            return json_encode($res);
        }
        // 可以修改数据
        \app\admin\model\Admin::update($params,["id"=>$id],true);
//        $this->success("修改成功",'admin/admin/index');
        $res=[
            'code'=>200,
            'msg'=>'success',
        ];
        return json_encode($res);
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        if($id==1){
            //不能删除超级管理员
            $this->error("不能删除此管理员");
        }
        $res=\app\admin\model\Admin::destroy($id);
        $this->success("删除成功");
    }
}
