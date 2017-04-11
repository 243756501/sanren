<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-11-29
 * Time: 下午5:22
 * @author 郑钟良<zzl@ourstu.com>
 */

namespace Api\Model;

use Think\Model;

class AccountModel extends Model
{
    public function login($uid,$pos){

        $time = time();
        $user = D('Member')->field(true)->find($uid);
        if (!$user['last_login_role']) {
            $user['last_login_role'] = $user['show_role'];
        }

        /* 更新登录信息 */
        $data = array(
            'uid' => $user['uid'],
            'login' => array('exp', '`login`+1'),
            'last_login_time' => $time,
            'last_login_ip' => get_client_ip(1),
            'last_login_role' => $user['last_login_role'],
        );
        $aGeolocation = POST_I('geolocation', 'text');
        $geolocationId = D('Api/User')->addGeolocation($aGeolocation,$uid,1);
        $re['geolocation_id'] = $geolocationId;
        $re['ClientID']=$pos['cid'];
        $re['token']=$pos['token'];
        $re['uid']=$user['uid'];
        $re['last_confirm_time'] = time();
        M('UserLocation')->where(array('ClientID'=>$pos['cid']))->delete();
        if(M('UserLocation')->where(array('uid'=>$user['uid']))->find()){
            M('UserLocation')->where(array('uid'=>$user['uid']))->save($re);
        }else{
            M('UserLocation')->add($re);
        }
        D('Member')->save($data);
        $result = $this->getOpenId($user);
        action_log('user_login', 'member', $uid, $uid);
        return  $result;
    }

    //生成openId
    public function getOpenId($user){
        if(!is_array($user)){
            $user = D('Member')->field(true)->find($user);
        }
        $map['uid'] = $user['uid'];
        $map['role_id'] = $user['last_login_role'];
        $audit = D('UserRole')->where($map)->getField('status');
        //判断角色用户是否审核 end

        /*记录登录SESSION和COOKIES*/
        $auth = array(
            'uid' => $user['uid'],
            'username' => get_username($user['uid']),
            'last_login_time' => $user['last_login_time'],
            'role_id' => $user['last_login_role'],
            'audit' => $audit,
        );
        session('user_auth', $auth);
        session('user_auth_sign', data_auth_sign($auth));
        $result['open_id'] = api_encode(implode('|', $auth));
        $result['auth'] = $auth;
        $result['timestamp'] = time();
        return $result;
    }
} 