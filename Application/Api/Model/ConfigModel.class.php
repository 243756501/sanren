<?php
/**
 * 用于客户端获取网站端的配置项
 */

namespace Api\Model;

use Think\Model;

class ConfigModel extends Model{

    //集合调用下面每一个mod配置项，当有需要网站端的模块配置时都需要在这里添加上
    /**
     * @param $aModList array 需要获取的模块名集合
     * @return array
     * @author 胡佳雨 <hjy@ourstu.com>.
     */
    public function getModCfg($aModList = array()){
        $baseMods = array('base','system','account','plus');          //app配置
        $appMods = array('weibo','news','question','issue','event','forum','group','shop','store','cat','people');      //模块配置
        $result = array();
        if(!empty($aModList)){
            foreach($aModList as $vName){
                if(in_array($vName,$baseMods) || in_array($vName,$appMods)){
                    $modConfig = $vName.'Config';
                    $result[$vName] = $this->$modConfig();
                }else{
                    $condition['name'] = array('like','%'.$vName.'%');
                    $condition['status'] = 1;
                    $result[$vName] = M('Config')->where($condition)->select();
                }
            }
            unset($vName);
        }else{
            foreach($baseMods as $vName){
                $modConfig = $vName.'Config';
                $result[$vName] = $this->$modConfig();
            }
            unset($vName);
            $appMods = $result['base']['mod_list'];
            foreach($appMods as $mod){
                $name = strtolower($mod['name']);
                $modConfig = $name.'Config';
                $result[$name] = $this->$modConfig();
            }
            unset($mod);
        }
        return $result;
    }

    //系统配置项
    public function systemConfig(){
        $tmpCfg['score_list'] = D('Ucenter/Score')->getTypeList();                                      //积分类型
        return $tmpCfg;
    }

    //获取用户个人和注册信息，后面也雷同获取其他模块的配置信息
    public function accountConfig(){
        $tmpCfg['can_reg'] = modC('USER_ALLOW_REGISTER', 1, '');                                        //是否允许注册
        $tmpCfg['reg_class'] = modC('_INVITE_REGISTER_TYPE', 'normal', '');                             //开放用户注册的类型(邀请注册和普通注册)
        $tmpCfg['reg_type'] = modC('REG_SWITCH', 'mobile', 'USERCONFIG');                               //开放用户注册的类别(手机注册，邮箱注册，用户名注册)
        $tmpCfg['reg_role'] = D('Admin/Role')->selectByMap(array('status' => 1,'invite' => 0), 'sort asc', 'id,title');         //开放用户注册的角色
        $tmpCfg['reg_step'] = json_decode(modC('REG_STEP', '', 'USERCONFIG'),true);                    //用户注册后需要进行的步骤
        $tmpCfg['can_skip'] = modC('REG_CAN_SKIP', '', 'USERCONFIG');                                   //用户注册后可以跳过的步骤
        $tmpCfg['user_board'] = json_decode(modC('UCENTER_KANBAN', '', 'USERCONFIG'),true);            //用户资料中心展示的信息
        $tmpCfg['email_verify_type'] = modC('EMAIL_VERIFY_TYPE', 0, 'USERCONFIG');                      //邮箱注册验证类型(0.不验证,1.注册后发送激活邮件,2.注册前发送验证邮件)
        $tmpCfg['mobile_verify_type'] = modC('MOBILE_VERIFY_TYPE', 0, 'USERCONFIG');                    //手机注册验证类型(0.不验证,1.注册前发送验证邮件)
        $tmpCfg['sms_resend_time'] = modC('SMS_RESEND', 60, 'USERCONFIG');                              //短信重发时间
        $tmpCfg['nickname_min'] = modC('NICKNAME_MIN_LENGTH', 2, 'USERCONFIG');                         //昵称最小长度
        $tmpCfg['nickname_max'] = modC('NICKNAME_MAX_LENGTH', 32, 'USERCONFIG');                        //昵称最大长度
        $tmpCfg['username_min'] = modC('USERNAME_MIN_LENGTH', 5, 'USERCONFIG');                         //用户名注册的用户名最小长度
        $tmpCfg['username_max'] = modC('USERNAME_MAX_LENGTH', 32, 'USERCONFIG');                        //用户名注册的用户名最大长度
        return $tmpCfg;
    }

    //获取APP的基本配置信息
    public function baseConfig(){
        $aVersion = I_POST('version');
        $versions = modC('APP_NEW_VERSION','1.0.0','Api');
        $tmpCfg['version'] = $versions;                                                         //最新版本号
        $tmpCfg['update'] = check_update($aVersion);                                            //升级状态码0：不需要升级；1：全新升级；2：覆盖升级；3：差量升级
        $tmpCfg['out_link'] = modC('APP_OUT_LINK', '', 'Api');                                  //站外下载链接
        $tmpCfg['apk_link'] = get_file_url(modC('APP_APK_FILE', '', 'Api'));                    //安卓包下载地址
        $tmpCfg['patch_link'] = get_file_url(modC('APP_PATCH_FILE', '', 'Api'));                //补丁包下载地址
        $tmpCfg['nav_change'] = modC('IS_NEED_ADMIN', 0, 'Api');                                //是否开启导航控制模式
        $tmpCfg['nav_time'] = S('app_mod_change_time');                                         //导航最新调整时间
        return $tmpCfg;
    }

    //获取第三方插件的配置信息
    public function plusConfig(){
        //第三方配置
        $tmpCfg['sync_login'] = modC('SYNCLOGIN_TYPE','','Api');
        //推送配置
        $tmpCfg['igt'] = modC('IGT_OPEN',0,'Api');
        //IM配置（多个IM）
        $tmpCfg['im'] = null;
        $im_switch = modC('IM_OPEN',0,'Api');
        if($im_switch){
            $wkImconfig =  get_addon_config('Wukong');
            $ryImconfig =  get_addon_config('Rongyun');
            $wkIm['open'] = 0;
            $ryIm['open'] = 0;
            if($wkImconfig){
                $wkIm['open'] = $wkImconfig['app_open'];
            }
            if($ryImconfig){
                $ryIm['open'] = $ryImconfig['app_open'];
                $ryIm['app_key'] = $ryImconfig['app_key'];
            }
            $tmpCfg['im'] = array('wukong'=>$wkIm,'rongyun'=>$ryIm);
        }
        return $tmpCfg;
    }

    public function weiboConfig(){
        $tmpCfg['show_title'] = modC('SHOW_TITLE',0,'WEIBO');                                           //是否显示用户等级
        $tmpCfg['weibo_max'] = modC('WEIBO_NUM',140,'WEIBO');                                           //微博发布最大字数限制
        $tmpCfg['info'] = modC('WEIBO_INFO', '把自己的见闻分享给大家吧！', 'WEIBO');                    //微博公告
        $tmpCfg['can_topic'] = modC('CAN_TOPIC', 1, 'WEIBO');                                           //是否允许发布图片微博
        $tmpCfg['can_image'] = modC('CAN_IMAGE', 1, 'WEIBO');                                           //是否允许发布话题微博
        $tmpCfg['default_topic'] = modC('USE_TOPIC', '', 'WEIBO');                                      //默认的话题类型
        $tmpCfg['weibo_tab'] = json_decode(modC('WEIBO_DEFAULT_TAB','', 'WEIBO'),true);                 //开启的微博tab列表
        return $tmpCfg;
    }

    public function newsConfig(){
        $tmpCfg['guest_can_comment'] = modC('INDEX_LOCAL_COMMENT_CAN_GUEST',0,'NEWS');                  //是否允许游客评论
        return $tmpCfg;
    }

    public function questionConfig(){
        $tmpCfg['need_audit'] = modC('QUESTION_NEED_AUDIT',0,'QUESTION');                               //投稿是否需要审核
        $tmpCfg['answer_min'] = modC('QUESTION_ANSWER_MIN_NUM',5,'QUESTION');                           //回答问题的最小字数限制
        return $tmpCfg;
    }

    public function issueConfig(){
        $tmpCfg['need_audit'] = modC('NEED_VERIFY',0,'ISSUE');                                          //投稿是否需要审核
        $tmpCfg['guest_can_comment'] = modC('ISSUECONTENT_LOCAL_COMMENT_CAN_GUEST',0,'ISSUE');          //是否允许游客评论
        return $tmpCfg;
    }

    public function eventConfig(){
        $tmpCfg['need_audit'] = modC('NEED_VERIFY',0,'EVENT');                                          //投稿是否需要审核
        $tmpCfg['guest_can_comment'] = modC('EVENT_LOCAL_COMMENT_CAN_GUEST',1,'EVENT');                 //是否允许游客评论
        return $tmpCfg;
    }

    public function forumConfig(){
        $tmpCfg['img_max'] = modC('LIMIT_IMAGE',10,'FORUM');                                            //帖子包含图片数限制
        $tmpCfg['hot_forum'] = modC('HOT_FORUM','','FORUM');                                            //热门论坛
        $tmpCfg['reco_forum'] = modC('RECOMMAND_FORUM','','FORUM');                                     //推荐论坛
        return $tmpCfg;
    }

    public function groupConfig(){
        $tmpCfg['need_audit'] = modC('GROUP_NEED_VERIFY',0,'GROUP');                                    //创建群组是否需要审核
        $tmpCfg['build_max'] = modC('GROUP_LIMIT','','GROUP');                                          //每个人允许创建的群组个数
        $tmpCfg['reco_forum'] = modC('RECOMMAND_FORUM','','GROUP');                                     //推荐论坛
        return $tmpCfg;
    }

    public function shopConfig(){
        $tmpCfg['score_type'] = modC('SHOP_SCORE_TYPE',1,'SHOP');                                       //商城兑换使用的积分类型
        $tmpCfg['hot_critical_value'] = modC('SHOP_HOT_SELL_NUM',3,'SHOP');                             //商城热销阀值（销量超过该值时，商品为热销商品）
        $tmpCfg['guest_can_comment'] = modC('GOODSDETAIL_LOCAL_COMMENT_CAN_GUEST',0,'SHOP');           //是否允许游客评论
        return $tmpCfg;
    }

    public function storeConfig(){
        $tmpCfg['score_type'] = modC('CURRENCY_TYPE',4,'STORE');                                        //微店使用的货币类型 （即用户积分类型，默认为id为4的余额）
        $tmpCfg['center_words'] = modC('CENTER_WORDS','买买买，剁剁剁！','STORE');                      //账户中心签名
        $tmpCfg['child_li_num'] = modC('STORE_CHILD_LI_NUM',5,'STORE');                                 //微店二级分类展示数目
        $tmpCfg['store_verify'] = modC('NEED_VERIFY_STORE',0,'STORE');                                  //微店开店是否需要审核 （默认无需审核）
        $tmpCfg['goods_verify'] = modC('NEED_VERIFY_GOODS',0,'STORE');                                  //发布商品是否需要审核 （默认无需审核）
        return $tmpCfg;
    }

    public function catConfig(){
        return null;
    }

    public function peopleConfig(){
        return null;
    }
}