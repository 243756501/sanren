<?php


namespace Api\Controller;

use Think\Controller\RestController;
use Admin\Model\AuthRuleModel;

class BaseController extends RestController
{
    protected $appKey;
    protected $appVersion;
    protected $openId;
    protected $accessToken;

    public function __construct()
    {

        parent::__construct();
        header('Access-Control-Allow-Origin:*');
        $this->appKey = md5(base64_encode(modC('ACCESS_TOKEN','OpenSNS','Api')));
        $this->appVersion = modC('APP_MIN_VERSION','','Api');
        //todo 安全性验证

        $aToken = I_POST('access_token');
        $open_id = I_POST('open_id');
//        if ($aToken != $this->appKey) {
//            $this->apiReturn(400, '无效的access_token');
//        }
        if($this->appVersion){             //版本控制
            $aVersion = I_POST('version');
            if(!$aVersion || compare_version($aVersion,$this->appVersion) > 0){
                $this->apiError( '不支持的版本号，请升级最新客户端');
            }
        }
        $this->accessToken = $aToken;

        $this->openId = $open_id;
    }


    public function getToken()
    {
        return md5(base64_encode('opensns'));
    }

    public function parseOpenId()
    {
        $str = api_decode($this->openId);
        $return = array();
        $array = explode('|', $str);
        $return['uid'] = $array[0];
        $return['username'] = $array[1];
        $return['last_login_time'] = $array[2];
        $return['role_id'] = $array[3];
        $return['audit'] = $array[4];
        return $return;
    }

    public function isLogin()
    {
        if (empty($this->openId)) {
            return 0;
        } else {
            $auth = $this->parseOpenId();
            return $auth['uid'];
        }
    }


    public function requireIsLogin(){
        $uid = $this->isLogin();
        if(!$uid){
            $this->apiError('请先登录！');
        }
        return $uid;
    }

    /**聚合的返回函数 2016.8.15
     * @param $result
     * @author 胡佳雨 <hjy@ourstu.com>.
     */
    public function ajaxSuccess($result){
        $this->apiSuccess('返回成功', $result);
    }

    /**必要参数的验证提示
     * @param array $args 待验证参数对象组
     * @author 胡佳雨 <hjy@ourstu.com>.
     */
    public function ajaxError($args){
        foreach($args as $key =>$value){
            if(empty($value))
                $this->apiError($key.'参数错误');
        }
    }

    /**Api专用的权限检测
     * @param string $rule
     * @param int $except_uid
     * @param string $msg
     * @return bool
     */
    public  function ApiCheckAuth($rule ='',$except_uid =-1,$msg = ''){
        if (!check_auth($rule, $except_uid)) {
            $this->apiError(empty($msg) ? '您无操作权限。' : $msg);
        }
    }

    /**Api专用的行为限制
     * @param null $action
     * @param null $model
     * @param null $record_id
     * @param null $user_id
     * @param bool $ip
     * @author 胡佳雨 <hjy@ourstu.com>.
     */
    public function apiCheckActionLimit($action = null, $model = null, $record_id = null, $user_id = null, $ip = false)
    {
        $return = check_action_limit($action, $model, $record_id, $user_id, $ip);
        if ($return && !$return['state']) {
            $this->apiError($return['info']);
        }
    }
}