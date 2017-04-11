<?php


namespace Api\Controller;


use Common\Api\ConfigApi;

class ConfigController extends BaseController
{
    public function __construct()
    {
        parent::__construct();

    }

    /**获取导航列表
     * @author 胡佳雨 <hjy@ourstu.com>.
     */
    public function getNavList()
    {
        $result = M('AppNav')->where(array('status'=>1))->select();
        $this->ajaxSuccess($result);
    }

    /**获取账号注册配置
     * @deprecated 1.6.4版本以后放弃使用，已经整合到getModConfig里
     */
    public function config()
    {
        $config['REGISTER_TYPE'] = modC('REGISTER_TYPE', 'normal', 'Invite');
        $config['REG_SWITCH'] = modC('REG_SWITCH', '', 'USERCONFIG');
        $config['EMAIL_VERIFY_TYPE'] = modC('EMAIL_VERIFY_TYPE', 0, 'USERCONFIG');
        $config['MOBILE_VERIFY_TYPE'] = modC('MOBILE_VERIFY_TYPE', 0, 'USERCONFIG');
        $config['NICKNAME_MIN_LENGTH'] = modC('NICKNAME_MIN_LENGTH', 2, 'USERCONFIG');
        $config['NICKNAME_MAX_LENGTH'] = modC('NICKNAME_MAX_LENGTH', 32, 'USERCONFIG');
        $config['USERNAME_MIN_LENGTH'] = modC('USERNAME_MIN_LENGTH', 2, 'USERCONFIG');
        $config['USERNAME_MAX_LENGTH'] = modC('USERNAME_MAX_LENGTH', 32, 'USERCONFIG');
        $config['SMS_RESEND'] = modC('SMS_RESEND', '60', 'USERCONFIG');
        $this->apiSuccess($config);
    }

    /**获取模块配置
     * 可以获取单一模块，也可以同时获取多个模块的配置信息
     * @author 胡佳雨 <hjy@ourstu.com>.
     */
    public function getModConfig()
    {
        $modName = I_POST('mod_name','text');
        $aModList = $modName?array_filter(explode(',',$modName)):null;
        $result = D('Api/Config')->getModCfg($aModList);
        $this->apiSuccess($result);
    }

    /**用于获取网站后台对客户端的一些配置项
     * @author 胡佳雨 <hjy@ourstu.com>.
     * @deprecated 1.6.4版本以后放弃使用，已经整合到getModConfig里
     */
    public function appConfig(){
        $resultData = array();
        //IM配置（多个IM）
        $wkImconfig =  get_addon_config('Wukong');
        $ryImconfig =  get_addon_config('Rongyun');
        $wkIm = null;
        $ryIm = null;
        if($wkImconfig){
            $wkIm = array('open' => $wkImconfig['app_open']);
        }
        if($ryImconfig){
            $ryIm = array('open' => $ryImconfig['app_open'],'app_key' => $ryImconfig['app_key']);
        }
        $resultData['im'] = array('wukong'=>$wkIm,'rongyun'=>$ryIm);
        $this->apiSuccess('返回成功', $resultData);
    }

    /**获取版本信息和安装包下载地址
     * @deprecated 1.6.4版本以后放弃使用，已经整合到getModConfig里
     */
    public function versions()
    {

        $code = I_POST('versions');
        $versions = modC('APP_NEW_VERSION','','Api');
        if($code!=$versions){
                    $Config['out_link'] = modC('APP_OUT_LINK', '', 'Api');
                    $res['id'] = modC('APP_APK_FILE', '', 'Api');
                    $res = M('File')->where(array('id' => $res['id']))->find();
                    $Config['link'] = $_SERVER['HTTP_HOST'] . $res['savepath'] . $res['savename'];
                    $Config['is_change'] = 1;
        }else{
            $Config['is_change'] = 0;
            $this->apiSuccess($Config);
        }
        if (!$versions) {
            $this->apiError('获取不到版本号');
        }
        $this->apiSuccess($Config);
    }



    /**获取模块开启顺序配置
     * @deprecated 1.6.4版本以后放弃使用，已经整合到getModConfig里
     */
    public function modList()
    {
        $adv = json_decode(modC('SHOW_MOD_TAB', '', 'Api'), true);
        foreach ($adv as $key => &$v) {
            if ($v['data-id'] == 'disable') {
                unset($adv[$key]);
            }
            $v['title'] = $v['data-id'];
            unset($v['data-id']);
        }
        foreach ($adv[1]['items'] as $k => &$i) {
            $ad[$k]['name'] = $i['data-id'];
            $ad[$k]['title'] = $i['title'];
            $b[$k] = $i['data-id'];
        }
        $list = array('mod' => md5(implode(',', $b)), 'list' => $ad, 'need' => modC('IS_NEED_ADMIN', 0, 'Api'));

        $this->apiSuccess('返回成功', $list);
    }


}