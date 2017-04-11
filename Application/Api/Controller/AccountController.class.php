<?php


namespace Api\Controller;


class AccountController extends BaseController
{
    private $mobileRegx;
    public function __construct()
    {
        parent::__construct();
        $this->mobileRegx = "/^(1[3|4|5|7|8])[0-9]{9}$/";
    }

    /*登陆*/
    public function login()
    {
        $mid = $this->isLogin();
        if($mid){
            $uid = $mid;
        }else{
            $aUsername = $username = I_POST('username');
            $aPassword = I_POST('password');
            check_username($aUsername, $email, $mobile, $aUnType);
            $uid = UCenterMember()->login($username, $aPassword, $aUnType);
        }
        $pos['cid'] = I_POST('cid');
        $pos['token'] = I_POST('token');
        if ($uid > 0) {
            $return = D('Api/Account')->login($uid, $pos);
            $this->apiSuccess($return,D('Api/User')->getUserInfo($uid,$uid));
        } else {
            $this->apiError($uid . '，' . $this->showLoginError($uid));
        }
    }

    /*注销*/
    public function logout(){
        $mid = $this->requireIsLogin();
        M('UserLocation')->where(array('uid'=>$mid))->delete();
        $this->ajaxSuccess('操作成功!');
    }

    public function register()
    {
        if (!modC('REG_SWITCH', '', 'USERCONFIG')) {
            $this->apiError('注册已关闭', 403);
        }
        //获取参数
        $aUsername = I_POST('username');
        $aEmail = I_POST('email');
        $aMobile = I_POST('mobile');
        $aNickname = I_POST('nickname');
        $aPassword = I_POST('password');
        $aRegVerify = I_POST('reg_verify');
        $aRegType = I_POST('reg_type');
        $aRole = I_POST('role');
        $aCode = I_POST('invite_code');//邀请码
        if(!preg_match("/^[\\~!@#$%^&*()-_=+|{}\[\],.?\/:;\'\"\d\w]+$/",$aPassword)){
            $this->apiError('密码有中文，或者数字，特殊符号存在!!');
        }
        $aType = I_POST('type');//邀请码类型
        if (!$aRole) {
            $this->apiError('请选择角色。');
        }
        /**/
        if (!check_reg_type($aRegType)) {
            $this->apiError('该类型未开放注册。');
        }
        $aUnType = 0;
        if ($aRegType == 'username') {
            if (empty($aUsername)) {
                $this->apiError('用户名不能为空');
            }
            preg_match("/^[a-zA-Z0-9_]{" . modC('USERNAME_MIN_LENGTH', 2, 'USERCONFIG') . "," . modC('USERNAME_MAX_LENGTH', 32, 'USERCONFIG') . "}$/", $aUsername, $match);
            if (!$match) {
                $this->apiError('用户名只允许英文字母和数字且长度必须在' . modC('USERNAME_MIN_LENGTH', 2, 'USERCONFIG') . '-' . modC('USERNAME_MAX_LENGTH', 32, 'USERCONFIG') . '位之间！');
            }
            $check_mobile = preg_match($this->mobileRegx, $aUsername, $match_mobile);
            $check_email = preg_match("/[a-z0-9_\-\.]+@([a-z0-9_\-]+?\.)+[a-z]{2,3}/i", $aUsername, $match_email);
            if ($check_mobile || $check_email) {
                $this->apiError('用户名格式不正确');
            }
            $aUnType = 1;
        }
        if ($aRegType == 'email') {
            if (empty($aEmail)) {
                $this->apiError('邮箱不能为空');
            }
            $check_email = preg_match("/[a-z0-9_\-\.]+@([a-z0-9_\-]+?\.)+[a-z]{2,3}/i", $aEmail, $match_email);
            if (!$check_email) {
                $this->apiError('邮箱格式不正确');
            }
            if (modC('EMAIL_VERIFY_TYPE', 0, 'USERCONFIG') == 2) {
                if (!D('Common/Verify')->checkVerify($aEmail, $aRegType, $aRegVerify, 0)) {
                    $this->apiError('邮箱验证失败，输入的验证码有误');
                }
            }
            $aUnType = 2;
        }

        if ($aRegType == 'mobile') {
            if (empty($aMobile)) {
                $this->apiError('手机不能为空');
            }
            $check_mobile = preg_match($this->mobileRegx, $aMobile, $match_mobile);
            if (!$check_mobile) {
                $this->apiError('手机格式不正确');
            }
            if (modC('MOBILE_VERIFY_TYPE', 0, 'USERCONFIG') == 1) {
                if (!D('Common/Verify')->checkVerify($aMobile, $aRegType, $aRegVerify, 0)) {
                    $this->apiError('手机验证失败，输入的验证码有误');
                }
            }
            $aUnType = 3;
        }
        if ($aRegType == 'invite') {
            if (!mb_strlen($aCode)) {
                $this->apiError('邀请码格式错误');
            }
            $this->inCode();
            $aUnType = 4;

        }


        if (!$this->checkInviteCode($aCode)) {
            $this->apiError('非法邀请码!!');
        }

        $uid = UCenterMember()->register($aUsername, $aNickname, $aPassword, $aEmail, $aMobile, $aUnType);
        if (0 < $uid) {
            $this->initInviteUser($uid, $aCode, $aRole);
            $this->initRoleUser($aRole, $uid); //初始化角色用户
            if (modC('EMAIL_VERIFY_TYPE', 0, 'USERCONFIG') == 1 && $aUnType == 2) {
                set_user_status($uid, 3);
                $verify = D('Common/Verify')->addVerify($aEmail, 'email', $uid);
                $res = $this->sendActivateEmail($aEmail, $verify, $uid); //发送激活邮件
                $this->apiSuccess('注册成功，请登录邮箱进行激活');
            }
            $authInfo = D('Api/Account')->getOpenId($uid);
            $this->ajaxSuccess($authInfo);
        } else {
            //注册失败，显示错误信息
            $this->apiError($uid . '，' . $this->showRegError($uid));
        }
    }


    /**
     * sendActivateEmail   发送激活邮件
     * @param $account
     * @param $verify
     * @return bool|string
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    private
    function sendActivateEmail($account, $verify, $uid)
    {
        $url = 'http://' . $_SERVER['HTTP_HOST'] . U('ucenter/member/doActivate?account=' . $account . '&verify=' . $verify . '&type=email&uid=' . $uid);
        $content = modC('REG_EMAIL_ACTIVATE', '{$url}', 'USERCONFIG');
        $content = str_replace('{$url}', $url, $content);
        $content = str_replace('{$title}', modC('WEB_SITE_NAME', 'OpenSNS开源社交系统', 'Config'), $content);
        $res = send_mail($account, modC('WEB_SITE_NAME', 'OpenSNS开源社交系统', 'Config') . '激活信', $content);
        return $res;
    }


    /**
     * 初始化角色用户信息
     * @param $role_id
     * @param $uid
     * @return bool
     * @author 郑钟良<zzl@ourstu.com>
     */
    private
    function initRoleUser($role_id = 0, $uid)
    {
        $memberModel = D('Member');
        $role = D('Role')->where(array('id' => $role_id))->find();
        $user_role = array('uid' => $uid, 'role_id' => $role_id, 'step' => "start");
        if ($role['audit']) { //该角色需要审核
            $user_role['status'] = 2; //未审核
        } else {
            $user_role['status'] = 1;
        }
        $result = D('UserRole')->add($user_role);
        if (!$role['audit']) { //该角色不需要审核
            $memberModel->initUserRoleInfo($role_id, $uid);
        }
        $memberModel->initDefaultShowRole($role_id, $uid);
        return $result;
    }

    public
    function showLoginError($code = 0)
    {
        switch ($code) {
            case -1:
                $error = '用户不存在或被禁用';
                break;
            case -2:
                $error = '密码错误';
                break;
            default:
                $error = '未知错误24';
        }
        return $error;
    }

    public
    function showRegError($code = 0)
    {
        switch ($code) {
            case -1:
                $error = '用户名长度必须在4-32个字符以内！';
                break;
            case -2:
                $error = '用户名被禁止注册！';
                break;
            case -3:
                $error = '用户名被占用！';
                break;
            case -4:
                $error = '密码长度必须在6-30个字符之间！';
                break;
            case -5:
                $error = '邮箱格式不正确！';
                break;
            case -6:
                $error = '邮箱长度必须在4-32个字符之间！';
                break;
            case -7:
                $error = '邮箱被禁止注册！';
                break;
            case -8:
                $error = '邮箱被占用！';
                break;
            case -9:
                $error = '手机格式不正确！';
                break;
            case -10:
                $error = '手机被禁止注册！';
                break;
            case -11:
                $error = '手机号被占用！';
                break;
            case -20:
                $error = '用户名只能由数字、字母和"_"组成！';
                break;
            case -30:
                $error = '昵称被占用！';
                break;
            case -31:
                $error = '昵称被禁止注册！';
                break;
            case -32:
                $error = '昵称只能由数字、字母、汉字和"_"组成！';
                break;
            case -33:
                $error = '昵称不能少于两个字！';
                break;
            default:
                $error = '未知错误24';
        }
        return $error;
    }

    /**获取注册角色列表
     * @deprecated 1.6.4版本以后放弃使用，已经整合到getModConfig里
     */
    public function getRole()
    {

        $map_role['status'] = 1;
        $map_role['invite'] = 0;
        $roleList = D('Admin/Role')->selectByMap($map_role, 'sort asc', 'id,title');
        $this->apiSuccess($roleList);
    }

    /**向邮箱或者手机发送验证码
     * @author 胡佳雨修改 <hjy@ourstu.com>.
     */
    public function sendVerify()
    {
        $uid = $this->isLogin();
        $aAccount = $cUsername = POST_I('account');
        $aType = POST_I('type');
        $aAction = POST_I('action','text');
        $this->ajaxError(array('account'=>$aAccount,'type'=>$aType,'action'=>$aAction));
        if($aAction == 'change'&& !$uid){
            $this->apiError('未登录');
        }
        $typeStr = $aType == 'mobile' ? '手机' : '邮箱';
        if (!check_reg_type($aType)) {
            $this->apiError($typeStr . '验证功能已关闭！');
        }

        $checkIsExist = UCenterMember()->where(array($aType => $aAccount))->find();
        if($aAction != 'find' && $checkIsExist){
            $this->apiError('该' . $typeStr . '已被其他用户使用！');
        }
        if($aAction == 'find' && !$checkIsExist){
            $this->apiError('不存在的账号');
        }else{
            $uid = $checkIsExist['id'];
        }

        $time = time();
        if($aType == 'mobile'){
            $resend_time = (int)modC('SMS_RESEND', '60', 'USERCONFIG');
            $last_time = (int)D('Common/Verify')->where(array('account' => $aAccount, 'type' => $aType))->getField('create_time');
            if ($last_time && ($last_time + $resend_time) > $time) {
                $this->apiError('请' . ($resend_time - ($time - $last_time)) . '秒后再发');
            }
        }
        check_username($cUsername, $cEmail, $cMobile);

        if ($aType == 'email' && empty($cEmail)) {
            $this->apiError('请验证邮箱格式是否正确');
        }else if ($aType == 'mobile' && empty($cMobile)) {
            $this->apiError('请验证手机格式是否正确');
        }
        $verify = D('Common/Verify')->addVerify($aAccount, $aType,$uid,0);
        $res =  A('Ucenter/Member')->doSendVerify($aAccount, $verify, $aType);

        if ($res === true) {
            $this->apiSuccess('发送成功，请注意查收');
        } else {
            $this->apiError($res);
        }

    }

    /**修改账号的手机和邮箱
     * @author 胡佳雨修改 <hjy@ourstu.com>.
     */
    public function changeAccount()
    {
        $uid = $this->requireIsLogin();
        $aAction = POST_I('action');
        $aAccount = POST_I('account');
        $aVerify = POST_I('verify');
        $this->ajaxError(array('account'=>$aAccount,'verify'=>$aVerify,'action'=>$aAction));
        $ucenterMod = UCenterMember();
        if( $aAction == 'mobile'){
            $regex = "/^(1[3|4|5|7|8])[0-9]{9}$/";
            $type_cn = '手机';
        }else if($aAction == 'email'){
            $regex = "/[a-z0-9_\-\.]+@([a-z0-9_\-]+?\.)+[a-z]{2,3}/i";
            $type_cn = '邮箱';
        }else{
            $this->apiError('不存在的操作，action参数错误');
        }
        if (!preg_match($regex, $aAccount)) {
            $this->apiError($type_cn . '格式不正确');
        }
        $checkIsExist = $ucenterMod->where(array($aAction => $aAccount))->find();
        if($checkIsExist){
            $this->apiError('该' . $type_cn . '已被使用！');
        }
        $verify = D('Verify')->checkVerify($aAccount, $aAction, $aVerify, $uid);
        if (!$verify) {
            $this->apiError('验证码无效');
        }
        $res = $ucenterMod->where(array('id' => $uid))->save(array($aAction => $aAccount));
        if($res){
            $this->apiSuccess('操作成功');
        }else{
            $this->apiError('操作失败');
        }
    }

    /**密码操作
     * @author 胡佳雨 <hjy@ourstu.com>.
     */
    public function handlePassword()
    {
        $action = POST_I('action').'Password';
        if(method_exists($this, $action)){
            $this->$action();
        }else{
            $this->apiError('action参数错误，请仔细查看接口文档');
        }
    }

    /**找回密码操作 供handlePassword调用
     * @author 胡佳雨 <hjy@ourstu.com>.
     */
    public function findPassword(){
        $aType = POST_I('type','text','mobile');
        $aAccount = POST_I('account');
        $aVerify = POST_I('verify');
        $aPassword = POST_I('password');
        $this->ajaxError(array('account'=>$aAccount,'verify'=>$aVerify,'password'=>$aPassword));
        $typeStr = $aType == 'mobile' ? '手机' : '邮箱';
        $ucMemberModel = UCenterMember();
        $errorInfo = '';
        if (check_reg_type($aType)) {
            $userInfo = $ucMemberModel->where(array($aType=>$aAccount))->find();
            if($userInfo){
                $res = M('Verify')->where(array('account' => $aAccount, 'type' => $aType, 'verify' => $aVerify,))->find();
                if($res){
                    $data = array('password' => $aPassword);
                    $data = $ucMemberModel->create($data);
                    if ($data) {
                        $ucMemberModel->where(array('id' => $userInfo['id']))->save($data);
                        M('Verify')->where(array('account' => $aAccount, 'type' => $aType))->delete();
                        clean_query_user_cache($userInfo['id'], 'password');//删除缓存
                        M('UserToken')->where('uid=' . $userInfo['id'])->delete();
                        $this->apiSuccess('操作成功！');
                    }else{
                        $errorInfo = '密码格式不正确';
                    }
                }else{
                    $errorInfo = '无效的验证码';
                }
            }else{
                $errorInfo = '错误的账号';
            }
        }else{
            $errorInfo = $typeStr . '验证功能已关闭！';
        }
        $this->apiError('操作失败！'.$errorInfo);
    }

    /**修改密码操作 供handlePassword调用
     * @author 胡佳雨修改 <hjy@ourstu.com>.
     */
    public function changePassword()
    {
        $mUid = $this->requireIsLogin();
        $aOldPassword = POST_I('old_password');
        $aNewPassword = POST_I('password');
        $this->ajaxError(array('旧密码'=>$aOldPassword,'新密码'=>$aNewPassword));

        if ($aOldPassword == $aNewPassword) {
            $this->apiError('新密码不能与旧密码相同');
        }

        $ucMemberModel = UCenterMember();
        if (!$ucMemberModel->verifyUser($mUid, $aOldPassword)) {
            $this->apiError('旧密码不正确。');
        }

        $data = array('password' => $aNewPassword);
        $data = $ucMemberModel->create($data);
        if (!$data) {
            $code = $ucMemberModel->getError();
            $this->apiError($this->showRegError($code));
        }

        $res = $ucMemberModel->where(array('id' => $mUid))->setField('password', $data['password']);
        if ($res) {
            clean_query_user_cache($mUid, 'password');//删除缓存
            M('UserToken')->where('uid=' . $mUid)->delete();
            $this->apiSuccess('操作成功。');
        } else {
            $this->apiError('操作失败。');
        }
    }


    public function changeUsername()
    {
        $aUsername = I_POST('username', 'text');
        $uid = $this->requireIsLogin();
        if (empty($aUsername)) {
            $this->apiError('用户名不能为空！');
        }

        //验证用户名是否是字母和数字
        preg_match("/^[a-zA-Z0-9_]{" . modC('USERNAME_MIN_LENGTH', 2, 'USERCONFIG') . "," . modC('USERNAME_MAX_LENGTH', 32, 'USERCONFIG') . "}$/", $aUsername, $match);
        if (!$match) {
            $this->apiError('用户名只允许英文字母和数字且长度必须在' . modC('USERNAME_MIN_LENGTH', 2, 'USERCONFIG') . '-' . modC('USERNAME_MAX_LENGTH', 32, 'USERCONFIG') . '位之间！');
        }
        $mUcenter = UCenterMember();

        //判断用户是否已设置用户名
        $username = $mUcenter->where(array('id' => $uid))->getField('username');
        if (empty($username)) {
            //判断修改的用户名是否已存在
            $id = $mUcenter->where(array('username' => $aUsername))->getField('id');
            if ($id) {
                $this->apiError('该用户名已经存在！');
            } else {
                //修改用户名
                $rs = $mUcenter->where(array('id' => $uid))->save(array('username' => $aUsername));
                if (!$rs) {
                    $this->apiError('设置失败！');
                }
                $this->apiSuccess('设置成功！');
            }
        }
        $this->apiError('用户名已经确定不允许修改！');

    }

    public function inCode()
    {
        $aCode = I_POST('code', 'op_t');
        $result['status'] = 0;
        if (!mb_strlen($aCode)) {
            $this->apiError('邀请码格式错误');
        }
        $invite = $this->getByCode($aCode);
        if ($invite) {
            if ($invite['end_time'] > time()) {
                $re=M('invite_type')->where(array('id'=>$invite['invite_type']))->find();
                $invite['roles']=$re['roles'];
                $invite['title']=$re['title'];
                $this->apiSuccess($invite,'邀请码有效');
            } else {
                $this->apiError('邀请码过期');
            }
        } else {
            $this->apiError('不存在该邀请码！请核对邀请码！');
        }
    }



    public function getByCode($code = '')
    {

        $map['code'] = $code;
        $map['status'] = 1;
        $data =M('Invite')->where($map)->find();
        if ($data) {
            $data['user'] = query_user(array('uid', 'nickname'), abs($data['uid']));
            return $data;
        }
        return null;
    }

    //密码重置
    public function doReset()
    {
        $password = I_POST('password');
        $repassword = I_POST('repassword');
        //确认两次输入的密码正确
        if ($password != $repassword) {
            $this->apiError('您两次输入的密码不一致');
        }
        $uid = I_POST('uid');
        $verify = I_POST('verify');
        //确认验证信息正确
        $expectVerify = $this->getResetPasswordVerifyCode($uid);
        if ($expectVerify != $verify) {
            $this->apiError('验证信息无效');
        }

        //将新的密码写入数据库
        $data = array('id' => $uid, 'password' => $password);
        $model = UCenterMember();
        $data = $model->create($data);
        if (!$data) {
            $this->apiError('密码格式不正确');
        }
        $result = $model->where(array('id' => $uid))->save($data);
        if ($result === false) {
            $this->apiError('数据库写入错误');
        }
        //显示成功消息
        $this->apiSuccess('密码重置成功');
    }

    private
    function getResetPasswordVerifyCode($uid)
    {
        $user = UCenterMember()->where(array('id' => $uid))->find();
        $clear = implode('|', array($user['uid'], $user['username'], $user['last_login_time'], $user['password']));
        $verify = thinkox_hash($clear, UC_AUTH_KEY);
        return $verify;
    }

    private function initInviteUser($uid = 0, $code = '', $role = 0)
    {
        if ($code != '') {
            $inviteModel = D('Ucenter/Invite');
            $invite = $this->getByCode($code);
            $data['inviter_id'] = abs($invite['uid']);
            $data['uid'] = $uid;
            $data['invite_id'] = $invite['id'];
            $result = D('Ucenter/InviteLog')->addData($data, $role);
            if ($result) {
                D('Ucenter/InviteUserInfo')->addSuccessNum($invite['invite_type'], abs($invite['uid']));

                $invite_info['already_num'] = $invite['already_num'] + 1;
                if ($invite_info['already_num'] == $invite['can_num']) {
                    $invite_info['status'] = 0;
                }
                $inviteModel->where(array('id' => $invite['id']))->save($invite_info);

                $map['id'] = $invite['invite_type'];
                $invite_type = D('Ucenter/InviteType')->getSimpleData($map);
                if ($invite_type['is_follow']) {
                    $followModel = D('Common/Follow');
                    $followModel->addFollow($uid, abs($invite['uid']), 1);
                    $followModel->addFollow(abs($invite['uid']), $uid, 1);
                }
                if ($invite['uid'] > 0) {
                    D('Ucenter/Score')->setUserScore(array($invite['uid']), $invite_type['income_score'], $invite_type['income_score_type'], 'inc', '', 0, '邀请奖励');
                }
            }
        }
        return true;
    }


    /**
     * 判断邀请码是否可用
     * @param string $code
     * @return bool
     * @author 郑钟良<zzl@ourstu.com>
     */
    private function checkInviteCode($code = '')
    {
        if ($code == '') {
            return true;
        }
        $invite = D('Ucenter/Invite')->getByCode($code);
        if ($invite['end_time'] >= time()) {
            $map['id'] = $invite['invite_type'];
            $invite_type = D('Ucenter/InviteType')->getSimpleData($map);
            if ($invite_type) {
                return true;
            }
        }
        return false;
    }
}