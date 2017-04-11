<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015-8-21
 * Time: 10:18:20
 */

namespace Api\Controller;


class PublicController extends BaseController
{
    public function auth()
    {
        $app_id = 'wxfe10072601b3246d';
        $secret = '4bcf7f9faee045a5879fc6635f698032';
        $aCode = I('post.code', '', 'text');
        $aEncryptedData = I('post.encryptedData','','text');
        $aEncryptedData=str_replace(' ','+',$aEncryptedData);
        $aIv = I('post.iv', '', 'text');
        $aIv=str_replace(' ','+',$aIv);
        $url = 'https://api.weixin.qq.com/sns/jscode2session?appid=' . $app_id . '&secret=' . $secret . '&js_code=' . $aCode . '&grant_type=authorization_code';
        $data = json_decode(file_get_contents($url), true);
        require_once('./Application/Api/Lib/wxBizDataCrypt.php');
        $open_id = $data['openid'];
        $session_key = $data['session_key'];

        $ivArr=array($aIv,'+'.$aIv);
        $encryArr=array($aEncryptedData,'+'.$aEncryptedData);
        if ($open_id) {
            $timestamp=time();
            $pc = new \WXBizDataCrypt($app_id, $session_key);
            $i=0;
            do{
                for ($q=0;$q<2;$q++){
                    for ($p=0;$p<2;$p++){
                        $errCode = $pc->decryptData($encryArr[$q], $ivArr[$p], $info);
                        $info = json_decode($info, true);
                        if($info['unionId']){
                            $Info=$info;
                            break;
                        }
                        $i++;
                    }
                }
            }
            while($i<4);


            if(empty($Info['unionId'])){
                $this->ajaxReturn(array('code' => 400, 'info' => '获取unionId失败', 'data' => $Info));
            }
            unset($Info['watermark']);
            $union_id = $Info['unionId'];
            $mid =$this->addUser($union_id, $Info);
            $token = json_encode(array('open_id' => $open_id,'timestamp' => $timestamp,'mid'=>$mid,'union_id'=>$union_id));
            $this->ajaxReturn(array('code' => 200, 'token' => $token, 'timestamp' => $timestamp));
        } else {
            $this->ajaxReturn(array('code' => 400, 'info' => '参数错误', 'data' => $data));
        }
    }
    public function addUser($union_id = '', $info = array())
    {
        $map = array('type'=> 'weixin','type_uid'=>$union_id);
        $syncModel = M('sync_login');
        $check =$syncModel->where($map)->find();
        if (!$check['id']) {
            $password = create_rand(10);
            $ucenterModel = UCenterMember();
            $username = uniqid();
            $nickname = $info['nickName'];
            $email=$username.'@ocenter.com';
            //去除特殊字符。
            $nickname = preg_replace('/[^A-Za-z0-9_\x80-\xff\s\']/', '', $nickname);
            // 截取字数
            $nickname = mb_substr($nickname, 0, 32, 'utf-8');

            $uid = $ucenterModel->register($username, $nickname, $password,$email,'',2);
            if ($uid > 0) {

                $data_s['uid'] = $uid;
                $data_s['type_uid'] = $union_id;
                $data_s['oauth_token']='f9c96961902f533121180b081fadf89c';
                $data_s['oauth_token_secret'] = $union_id;
                $data_s['type'] = 'weixin';

                if (!($syncModel->where($data_s)->count())) {
                    $syncModel->add($data_s);
                }
                $avatar['uid']=$uid;
                $avatar['path']=$info['avatarUrl'];
                $avatar['create_time']=time();
                $avatar['status']=1;
                $avatar['is_temp']=0;
                $avatar['driver']='weixin';
                M('avatar')->add($avatar);
                $ucenterModel->initRoleUser(1, $uid); //初始化角色用户

            }
            $mid=$uid;
        } else {
            $ava=query_user('avatar512',$check['uid']);
            $flag=strpos($ava['avatar512'],$info['avatarUrl']);
            if($flag===false){
                M('avatar')->where(array('uid'=>$check['uid']))->save(array('path'=>$info['avatarUrl']));
            }

            $mid = $check['uid'];
        }

        return $mid;
    }
    //微信小程序上传图片
    public function uploadFile(){
        $type=I('post.type','','text');
        if($type=='image'){
            R('Core/File/uploadPicture');
        }else if($type=='voice'){
            R('Core/File/uploadFile');
        }
    }
    public function support()
    {
        $mid = $this->requireIsLogin();
        $appname = I_POST('appname', 'text');
        $table = I_POST('table', 'text');
        $row = I_POST('row', 'intval');


        $aJump = I_POST('jump', 'text');
        $message_uid =  D($table)->where(array('id'=>$row))->getField('uid');// I_POST('uid', 'intval');

        $support['appname'] = $appname;
        $support['table'] = $table;
        $support['row'] = $row;
        $support['uid'] = $mid;
        $supportModel = D('Support');
        if ($supportModel->where($support)->count()) {
            $this->apiError('您已经赞过，不能再赞了。');
        } else {
            $support['create_time'] = time();
            $supInfo = $supportModel->where($support)->add($support);
            if ($supInfo) {
                $this->clearCache($support);
                $user = query_user(array('nickname'), $mid);
                D('Common/Message')->sendMessage($message_uid, $title = $user['nickname'] . '赞了您。', $user['nickname'] . '给您点了个赞。', $aJump, array('id' => $row),$mid);

                //微博转发推送
                $pushData['content'] = $table;
                $pushData['payload'] = array('type' => 'os_w_support','info' => $row,'content' => $user['nickname'] . '给您的微博点了个赞。');
                $tagUid = M('Weibo')->where(array('id'=>$row))->getField('uid');   //获取原微博发布人uid用于推送
                igetuiPush(array($tagUid),$pushData);     //使用的单推方法
                $user= D('Api/User')->getUser($mid);
                $this->apiSuccess('感谢您的支持。',$user);
            } else {
                $this->apiError('点赞失败。');
            }
        }
    }


    public function supportList()
    {
        $appname = I_POST('appname', 'text');
        $table = I_POST('table', 'text');
        $row = I_POST('row', 'text');

        $support['appname'] = $appname;
        $support['table'] = $table;
        $support['row'] = $row;
        $user = D('Support')->where($support)->field('uid')->select();

        foreach($user as &$e){
            $e= D('Api/User')->getUser($e['uid']);
        }
       if(!$user){
           $this->apiSuccess('暂无人点赞');
    }
        $this->apiSuccess('返回成功。',$user);

    }

    public function clearCache($support)
    {
        unset($support['uid']);
        unset($support['create_time']);
        $cache_key = "support_count_" . implode('_', $support);
        S($cache_key, null);
    }

    public function mySupport(){
        $mid = $this->requireIsLogin();
        $appname = I_POST('appname', 'text');
        $table = I_POST('table', 'text');

        $support['appname'] = $appname;
        $support['table'] = $table;
        $support['uid'] = $mid;
        $rows = D('Support')->where($support)->field('row')->select();
        $this->apiSuccess(getSubByKey($rows,'row'));
    }

    /**
     * 签到操作
     */
    public function checkin()
    {
        $mid= $this->requireisLogin();
        $time = get_some_day(0);
        $name = get_addon_class('CheckIn');
        $class = new $name();
        $checkMod = M('checkin');
        $isCheck = $checkMod->where(array('create_time' => array('egt', $time), 'uid' => $mid))->find();
        if (!$isCheck) {
            $res = $class->doCheckIn();
            if ($res) {
                $data = $checkMod->where(array('uid'=> $mid))->order('create_time desc')->find();
                $data['create_time'] = friendlyDate($data['create_time']);
                $this->apiSuccess('签到成功',$data);
            } else {
                $this->apiError('重复签到！');
            }
        } else {
            $this->apiError('重复签到！');
        }
    }


    /**获取签到信息
     * @auth 陈一枭
     */
    public function getCheckInfo()
    {
        //TODO 缓存
        $mid= $this->requireisLogin();
        $map['uid'] = $mid;
        $checkInfo = D('checkin')->where($map)->order('create_time desc')->find();
        $zeroTime = array('gt', strtotime(date('Ymd')));
        if($checkInfo){
            $has_checked = (int)$checkInfo['create_time'] >= $zeroTime[1]?1:0;
            //是否有签到记录
            $userInfo = M('Member')->where($map)->field('con_check,total_check')->find();
            $total_num = $userInfo['total_check'];
            //更新连续签到和累计签到的数据
            $this->apiSuccess('返回成功！', array(
                'con_num' => $userInfo['con_check'],
                'total_num' => $total_num,
                'over_rate' => $this->getOverRate($total_num),
                'has_checked' => $has_checked,
            ));
        }else{
            //更新连续签到和累计签到的数据
            $this->apiSuccess('返回成功！', array(
                'con_num' => 0,
                'total_num' => 0,
                'over_rate' => 0,
                'has_checked' => 0,
            ));
        }
    }

    public function getCheckPeople()
    {
        $aPage = I_POST('page', 'intval');


        if (empty($aPage)) {
            $aPage = 1;
        }
        $aType=I_POST('type','text');

        $rank = D('Api/Public')->getRank($aType,$aPage);
        if($rank){
            $this->apiSuccess('返回成功。', $rank);
        }else{
            $this->apiError('无查询数据');
        }
    }

    /**获取签到超过的比例
     * @param $total_num
     * @return string
     * @auth 陈一枭
     */
    private function getOverRate($total_num)
    {
        $map['total_check'] = array('gt', intval($total_num));
        $over_count = D('Member')->where($map)->count();

        $users_count = D('Member')->count('uid');

        $over_rate = ((1 - number_format($over_count / $users_count, '3')) * 100) . "%";
        return $over_rate;
    }




    public function AddonsReportConfig()
    {
        $config = M('Addons')->where(array('name' => 'Report'))->find();
        $config = json_decode($config['config'], ture);
        $config = explode("\r\n", $config['meta']);
//        $config=array('type'=>implode(",",$config));
        if(!$config){
            $this->apiError('举报类型获取失败');
        }
        $this->apiSuccess('举报类型返回成功', $config);
    }

    /**举报
 *
 * */
    public function Report()
    {
        $mid= $this->requireisLogin();
        $param['url']=I_POST('url');
        $param['type']=I_POST('type');
        $param['data']=I_POST('data');
        $param['reason'] = I_POST('reason');
        $param['content'] = I_POST('content','op_t');
        $data['uid'] = $mid;
        $data['url'] = $param['url']?$param['url']:'';
        $data['reason'] = $param['reason'];        // 举报原因
        $data['content'] = $param['content'];      // 举报描述
        if(empty($param['data'])){
            $this->apiError('请选择举报目标');
        }
        if(empty($param['reason'])){
            $this->apiError('请选择举报类型');
        }
        if(empty($param['content'])){
            $this->apiError('请填写举报内容');
        }

        $data['type'] = $param['type']?$param['type']:'';
        $data['data'] = json_encode($param['data'])?json_encode($param['data']):'';
        $data['create_time']=time();
        $data['update_time']=time();
        $data['status']=time();
        $data = M('Report')->create($data);
        $result= M('Report')->add($data);
        if ($result){
            D('Common/Message')->sendMessageWithoutCheckSelf('1', '有新的举报。', '有一封举报，请到后台查看。');
            $this->apiSuccess('举报成功');
        } else {
            $this->apiError('举报失败');
        }
    }


/**会话token
 *
 **/
    public function getWuKongToken()
    {
        $mid = $this->requireIsLogin();
        $model = D('Addons://Wukong/WukongAuth');
        $result = $model->getLoginModel($mid, 'web');
        if($result){
            $this->apiSuccess('返回成功',$result);
        }else{
            $this->apiError('返回失败');
        }
    }


/**会话token
 *
 **/
    public function getRongyunToken()
    {
        $mid = $this->requireIsLogin();
        $userInfo = D('User')->getUserReduceInfo($mid);
        if($userInfo){
            $name = $userInfo['nickname'];
            $avatar = $userInfo['avatar128'];
            $model = D('Addons://Rongyun/Rongyun');
            $result = $model->getToken($mid,$name,$avatar);
            if($result){
                $this->apiSuccess('返回成功',$result);
            }else{
                $this->apiError('返回失败');
            }
        }else{
            $this->apiError('用户信息获取失败');
        }
    }



/**推送消息
     *
     **/
    public function pushMessage()
    {
        $this->requireisLogin();
        $aFrom=I_POST('from');//推送来源
        $aType= I_POST('type');//推送类型
        $aPushType= I_POST('push_type');//推送类型  1.通知弹框下载功能模板 2.通知打开链接功能模板 3.通知透传功能模板 4.透传功能模板;
        $aContent= I_POST('content');//推送内容
        $uids= I_POST('uids');//推送人id 数组
        $uids=explode(',',$uids);
        $data['content']=$aContent;
        $data['from']=$aFrom;
        $data['type']=$aType;
        $data['cids']=$uids;
        if(empty($aPushType)){
            $aPushType=4;
        }
        switch ($aType) {
            case 'single'://单人推送
               $res= D('Api/Igt')->pushMessageToSingle($aPushType,$data);
                break;
            case 'list'://列表推送
//                $res= D('Api/Igt')-> pushMessageToList($aPushType,$data);
                break;
            default :
                $this->apiError('请填写正确的发送类型');
        }
        if($res['result']==='ok'){
                $this->apiSuccess('发送成功');
        }else{
            $this->apiError('发送失败');
        }

    }


    public function getMusic(){
       $id=I_POST('mid','int');
       $res=getXiaMiUrl($id);
//        dump($res);
        if($res){
            $result=ipcxiami($res['location']);
//            dump($result);
            $result=array('src'=>$result);
            $this->apiSuccess('获取成功',$result);
        }else{
            $this->apiError('获取失败');
        }
    }

} 