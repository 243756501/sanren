<?php

/**
 * Created by PhpStorm.
 * User: 胡佳雨
 */
namespace Api\Model;

use Think\Model;


require_once(dirname(__FILE__).'/'.'../Plus/Getui/igetui/IGtPush.php');
require_once(dirname(__FILE__).'/'.'../Plus/Getui/protobuf/pbmessage.php');
require_once(dirname(__FILE__).'/'.'../Plus/Getui/exception/RequestException.php');
require_once(dirname(__FILE__).'/'.'../Plus/Getui/igetui/IGtReq.php');
require_once(dirname(__FILE__).'/'.'../Plus/Getui/igetui/IGtMessage.php');
require_once(dirname(__FILE__).'/'.'../Plus/Getui/igetui/IGtAppMessage.php');
require_once(dirname(__FILE__).'/'.'../Plus/Getui/igetui/IGtListMessage.php');
require_once(dirname(__FILE__).'/'.'../Plus/Getui/igetui/IGtSingleMessage.php');
require_once(dirname(__FILE__).'/'.'../Plus/Getui/igetui/IGtAPNPayload.php');
require_once(dirname(__FILE__).'/'.'../Plus/Getui/igetui/IGtTarget.php');
require_once(dirname(__FILE__).'/'.'../Plus/Getui/igetui/template/IGtBaseTemplate.php');
require_once(dirname(__FILE__).'/'.'../Plus/Getui/igetui/template/IGtLinkTemplate.php');
require_once(dirname(__FILE__).'/'.'../Plus/Getui/igetui/template/IGtNotificationTemplate.php');
require_once(dirname(__FILE__).'/'.'../Plus/Getui/igetui/template/IGtTransmissionTemplate.php');
require_once(dirname(__FILE__).'/'.'../Plus/Getui/igetui/template/IGtNotyPopLoadTemplate.php');
require_once(dirname(__FILE__).'/'.'../Plus/Getui/igetui/template/IGtAPNTemplate.php');

/*推送的配置文件*/
define('APPID', modC('IGT_APPID','','Api'));
define('APPKEY', modC('IGT_APPKEY','','Api'));
define('MASTERSECRET', modC('IGT_MASTERSECRET','','Api'));
define('HOST', 'http://sdk.open.api.igexin.com/apiex.htm');


class IgtModel extends Model
{

//所有推送接口均支持四个消息模板，依次为通知弹框下载模板，通知链接模板，通知打开app模板，透传模板
//注：IOS离线推送需通过APN进行转发，需填写pushInfo字段，目前仅不支持通知弹框下载功能

    //通知弹框下载模板
    function IGtNotyPopLoadTemplate(){
        $template =  new \IGtNotyPopLoadTemplate();

        $template ->set_appId(APPID);//应用appid
        $template ->set_appkey(APPKEY);//应用appkey
        //通知栏
        $template ->set_notyTitle("个推");//通知栏标题
        $template ->set_notyContent("个推最新版点击下载");//通知栏内容
        $template ->set_notyIcon("");//通知栏logo
        $template ->set_isBelled(true);//是否响铃
        $template ->set_isVibrationed(true);//是否震动
        $template ->set_isCleared(true);//通知栏是否可清除
        //弹框
        $template ->set_popTitle("弹框标题");//弹框标题
        $template ->set_popContent("弹框内容");//弹框内容
        $template ->set_popImage("");//弹框图片
        $template ->set_popButton1("下载");//左键
        $template ->set_popButton2("取消");//右键
        //下载
        $template ->set_loadIcon("");//弹框图片
        $template ->set_loadTitle("地震速报下载");
        $template ->set_loadUrl("http://dizhensubao.igexin.com/dl/com.ceic.apk");
        $template ->set_isAutoInstall(false);
        $template ->set_isActived(true);
        //$template->set_duration(BEGINTIME,ENDTIME); //设置ANDROID客户端在此时间区间内展示消息

        return $template;
    }

    //通知链接模板
    function IGtLinkTemplate(){
        $template =  new \IGtLinkTemplate();
        $template ->set_appId(APPID);//应用appid
        $template ->set_appkey(APPKEY);//应用appkey
        $template ->set_title("请输入通知标题");//通知栏标题
        $template ->set_text("请输入通知内容");//通知栏内容
        $template ->set_logo("");//通知栏logo
        $template ->set_isRing(true);//是否响铃
        $template ->set_isVibrate(true);//是否震动
        $template ->set_isClearable(true);//通知栏是否可清除
        $template ->set_url("http://www.igetui.com/");//打开连接地址
        //$template->set_duration(BEGINTIME,ENDTIME); //设置ANDROID客户端在此时间区间内展示消息
        //iOS推送需要设置的pushInfo字段
//        $apn = new IGtAPNPayload();
//        $apn->alertMsg = "alertMsg";
//        $apn->badge = 11;
//        $apn->actionLocKey = "启动";
//    //        $apn->category = "ACTIONABLE";
//    //        $apn->contentAvailable = 1;
//        $apn->locKey = "通知栏内容";
//        $apn->title = "通知栏标题";
//        $apn->titleLocArgs = array("titleLocArgs");
//        $apn->titleLocKey = "通知栏标题";
//        $apn->body = "body";
//        $apn->customMsg = array("payload"=>"payload");
//        $apn->launchImage = "launchImage";
//        $apn->locArgs = array("locArgs");
//
//        $apn->sound=("test1.wav");;
//        $template->set_apnInfo($apn);
        return $template;
    }

    //通知打开app模板
    function IGtNotificationTemplate($data){
        $template =  new \IGtNotificationTemplate();
        $template->set_appId(APPID);//应用appid
        $template->set_appkey(APPKEY);//应用appkey
        $template->set_transmissionType(2);//透传消息类型,收到消息是否立即启动应用：1为立即启动，2则广播等待客户端自启动
        $template->set_transmissionContent("这里是透传消息");//透传内容
        $template->set_isRing(true);//是否响铃
        $template->set_isVibrate(true);//是否震动
        $template->set_isClearable(true);//通知栏是否可清除
        $template->set_title($data['title']);//通知栏标题
        $template->set_text($data['content']);//通知栏内容
        $template->set_logo('http://upload.opensns.cn/Uploads_Avatar_7082_572b049f121c6.png?imageMogr2/crop/!110x110a0a0/thumbnail/128x128!');//通知栏logo
        //$template->set_duration(BEGINTIME,ENDTIME); //设置ANDROID客户端在此时间区间内展示消息
        //iOS推送需要设置的pushInfo字段
//        $apn = new \IGeTui\IGtAPNPayload();
//        $apn->alertMsg = "alertMsg";
//        $apn->badge = 11;
//        $apn->actionLocKey = "启动";
//            $apn->category = "ACTIONABLE";
//            $apn->contentAvailable = 1;
//        $apn->locKey = "通知栏内容";
//        $apn->title = "通知栏标题";
//        $apn->titleLocArgs = array("titleLocArgs");
//        $apn->titleLocKey = "通知栏标题";
//        $apn->body = "body";
//        $apn->customMsg = array("payload"=>"payload");
//        $apn->launchImage = "launchImage";
//        $apn->locArgs = array("locArgs");
//
//        $apn->sound=("test1.wav");;
//        $template->set_apnInfo($apn);
        return $template;
    }

    //透传消息模板
    function IGtTransmissionTemplate($data){
        $template =  new \IGtTransmissionTemplate();
        $template->set_appId(APPID);//应用appid
        $template->set_appkey(APPKEY);//应用appkey
        $template->set_transmissionType(2);//透传消息类型
        $template->set_transmissionContent(json_encode($data));//透传内容
        //$template->set_duration(BEGINTIME,ENDTIME); //设置ANDROID客户端在此时间区间内展示消息

        return $template;
    }

//服务端推送接口，支持三个接口推送
//1.PushMessageToSingle接口：支持对单个用户进行推送
//2.PushMessageToList接口：支持对多个用户进行推送，建议为50个用户
//3.pushMessageToApp接口：对单个应用下的所有用户进行推送，可根据省份，标签，机型过滤推送

    /**单推接口案例(当推送的目标不是很多时建议也使用单推)
     * @param array $uidList 用户第列表
     * @param object $data 推送数据
     * @author 胡佳雨 <hjy@ourstu.com>.
     */
    function pushMessageToSingle($uidList,$data){
        $igt = new \IGeTui(HOST,APPKEY,MASTERSECRET);
        $template =  $this->IGtTransmissionTemplate($data);
        $tidList = array();  //android的cid和tid相同，而IOS的tid和cid不相同
        foreach($uidList as $uid){
            $user = M('UserLocation')->where(array('uid'=>$uid))->find();
            array_push($tidList,$user['ClientID']);
        }
        unset($uid);
        //个推信息体
        $message = new \IGtSingleMessage();

        $message->set_isOffline(true);//是否离线
        $message->set_offlineExpireTime(3600*1000*12);//离线时间
        $message->set_data($template);//设置推送消息类型
        //$message->set_PushNetWorkType(0);//设置是否根据WIFI推送消息，1为wifi推送，0为不限制推送
        //接收方限制為50个，太多会报错改为只用群推接口//Todo
        for($index = 0;$index< count($tidList) && $index < 50;$index++){
            $target = new \IGtTarget();
            $target->set_appId(APPID);
            $target->set_clientId($tidList[0]);
            try {
                $rep = $igt->pushMessageToSingle($message, $target);//在线发送成功
            }catch(\RequestException $e){
                $requstId =$e->getRequestId();
                $rep = $igt->pushMessageToSingle($message, $target,$requstId);      //不在线时
            }
        }
    }

    /**
     * @param array $cidList
     * @param object $data
     * @throws \Exception
     * @author 胡佳雨 <hjy@ourstu.com>.
     */
    function pushMessageToList($cidList,$data){
        putenv("needDetails=true");
        $igt = new \IGeTui(HOST,APPKEY,MASTERSECRET);
        //$igt = new IGeTui('',APPKEY,MASTERSECRET);//此方式可通过获取服务端地址列表判断最快域名后进行消息推送，每10分钟检查一次最快域名

        $template = $this->IGtTransmissionTemplate($data);
        //个推信息体
        $message = new \IGtListMessage();
        $message->set_isOffline(true);//是否离线
        $message->set_offlineExpireTime(3600*12*1000);//离线时间
        $message->set_data($template);//设置推送消息类型
        $message->set_PushNetWorkType(0);//设置是否根据WIFI推送消息，1为wifi推送，0为不限制推送
        $contentId = $igt->getContentId($message);
        //接收方1
        $targetList = array();
        foreach($cidList as $key => $cid){
            $target = new \IGtTarget();
            $target->set_appId(APPID);
            $target->set_clientId($cid);
            $targetList[$key] = $target;
        }
        $rep = $igt->pushMessageToList($contentId, $targetList);
    }

    //IOS群推
    public function pushAPNL($tidList,$data)
    {
        //APN高级推送
        $igt = new \IGeTui(HOST, APPKEY, MASTERSECRET);
        $template = new \IGtAPNTemplate();
        $apn = new \Igetui\IGtAPNPayload();
        $alertmsg = new \Igetui\DictionaryAlertMsg();
        $alertmsg->body = "body";
        $alertmsg->actionLocKey = "ActionLockey";
        $alertmsg->locKey = "LocKey";
        $alertmsg->locArgs = array("locargs");
        $alertmsg->launchImage = "launchimage";
//        IOS8.2 支持
        $alertmsg->title = "Title";
        $alertmsg->titleLocKey = "TitleLocKey";
        $alertmsg->titleLocArgs = array("TitleLocArg");
        $apn->alertMsg = $alertmsg;

        $apn->badge = 0;
        $apn->sound = "com.gexin.ios.silence";
        $apn->add_customMsg("payload", "payload");
//        $apn->contentAvailable=1;
//        $apn->category="ACTIONABLE";
        $template->set_apnInfo($apn);


        //多个用户推送接口
        putenv("needDetails=true");
        $listmessage = new \IGtListMessage();
        $listmessage->set_data($template);
        $contentId = $igt->getAPNContentId(APPID, $listmessage);
        $ret = $igt->pushAPNMessageToList(APPID, $contentId, $tidList);
        return $ret;
    }

//用户状态查询
    public function getUserStatus($CID)
    {
        $igt = new \IGeTui(HOST, APPKEY, MASTERSECRET);
        $rep = $igt->getClientIdStatus(APPID, $CID);
        return $rep;
    }

//推送任务停止
    public function stoptask($pushId)
    {
        $igt = new \IGeTui(HOST, APPKEY, MASTERSECRET);
        $igt->stop($pushId);
    }

    //通过服务端设置ClientId的标签
    public function setTag($CID)
    {
        $igt = new \IGeTui(HOST, APPKEY, MASTERSECRET);
        $tagList = array('', '中文', 'English');
        $rep = $igt->setClientTag(APPID, $CID, $tagList);
        return $rep;
    }

    public function getUserTags($CID)
    {
        $igt = new \IGeTui(HOST, APPKEY, MASTERSECRET);
        $rep = $igt->getUserTags(APPID, $CID);
        return $rep;
    }
}