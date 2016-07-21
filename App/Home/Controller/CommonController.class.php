<?php
/**
 * Author: huanglele
 * Date: 2016/7/21
 * Time: 上午 10:43
 * Description:
 */

namespace Home\Controller;

use Think\Controller;

class CommonController extends Controller
{
    protected $uid = null;

    //判断登录
    public function _initialize()
    {
        $this->uid =  session('uid');
        if (!$this->uid){    //判断没有登录
           //判断是否是登录操作
            $action = strtolower(ACTION_NAME);
            if ($action == 'login'){    //登录操作

            } else {
                //记录url
                session('loginJumpUrl',$_SERVER['REQUEST_URI']);
                $this->login();
            }
        }
    }

    //微信登录
    public function login()
    {
        $tools = new \Org\Wxpay\UserApi();
        $openId = $tools->GetOpenid();
        $wxInfo = $tools->getInfo();
        if(!$wxInfo || isset($wxInfo['errcode'])){
            $this->error('微信授权出错',U('index/index'));
        }
        $info = getWxUserInfo($openId);
        if(!$info || isset($info['errcode'])){
            var_dump($info);die;
            $this->error('登录出了点状况',U('index/index'));
        }

        //判断之前是否存储过用户资料
        $M = M('user');
        $data = array_merge($info,$wxInfo);

        session('openid',$openId);

        if(isset($data['headimgurl'])){
            $data['headimgurl'] = trim($data['headimgurl'],'0').'64';
        }
        $uInfo = $M->where(array('openid'=>$openId))->field('id')->find();
        $uid = $uInfo['uid'];
        $data['last_time'] = time();    //写入最后登录时间
        $jump = session('loginJumpUrl');
        if(!$jump){
            $jump = U('index/index');
        }
        session('loginJumpUrl',null);
        if($uid){
            $data['id'] = $uid;
            M('user')->save($data);
            session('uid',$uid);
            header("Location:$jump");
        }else{
            //第一次登录 添加到用户表里面
            $data['coin'] =  0;
            $r = $M->add($data);
            if($r){
                session('uid',$r);
                session('agent',0);
                header("Location:$jump");
            }
        }
    }

    public function _empty(){
        $this->index();
    }

}