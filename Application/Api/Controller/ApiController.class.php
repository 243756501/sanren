<?php

namespace Admin\Controller;

use Admin\Builder\AdminListBuilder;
use Admin\Builder\AdminConfigBuilder;

class ApiController extends AdminController
{
    private $modList = array('Weibo','News','Question','Forum','Shop','Store','People','Issue','Event','Group','Cat');
    private $tabName;

    public function __construct()
    {
        parent::__construct();
        $this->tabName = C( 'db_prefix' );
    }

    public function index()
    {
        $this->redirect('config');
        $this->navCtrl();
        $this->anonymous();
    }
    public function config()
    {
        $meduleMod = M('Module');
        if(!$meduleMod->where(array('name'=>'Weibo'))->getField('app_has')){         //初始化一些客户端需要添加的字段名
            $medel = D();
            $medel->execute("ALTER TABLE  `".$this->tabName."module` ADD  `app_has` tinyint(2) NOT NULL DEFAULT 0 COMMENT '手机客户端是否也拥有该模块';");
            foreach($this->modList as $mod){
                $conditon['name'] = $mod;
                $meduleMod->where($conditon)->setField('app_has',1);
            }
            unset($mod);
        }
        $builder = new AdminConfigBuilder();
        $data = $builder->handleConfig();
        $data['accesstoken'] = md5(base64_encode($data['ACCESS_TOKEN']));
        $builder->title('基本设置')
            ->data($data)
            ->keyText('ACCESS_TOKEN', 'access_token', '应用的access_token，输入一个字符串，系统将进行加密。')
            ->keyDefault('ACCESS_TOKEN', 'xxxxxxx')
            ->keyLabel('accesstoken', '以下部分为access_token', '加密后的token，应用最终使用的access_token')
            ->keyLabel('','')
//            ->keyBool('APP_HTTPS', '网站是否开启了https', '默认开启。ps:如果网站没有开启https，ios客户端图片将无法显示，请谨慎选择')
//            ->keyDefault('APP_HTTPS', 1)
            ->keyBool('IS_NEED_ADMIN', '是否开启底部导航控制模式', '若开启，用户则可以自由调整底部导航栏')
            ->keyDefault('IS_NEED_ADMIN', 1)
            ->button( '打数据库补丁',array('class'=>'ajax-get btn btn-default btn-danger','style'=>'float:right','url'=>U('executeSQL')))
            ->buttonSubmit('', '保存');
        $builder->display();
    }

    public function  anonymous(){
        $data = D('anonymous')->where('id>0')->select();
        $listBuilder = new AdminListBuilder();
        $listBuilder->title('随机用户操作')
            ->keyId('uid')
            ->keyDoActionEdit('deleteAnonymousId?id=###','删除')
            ->data($data)
            ->buttonNew(U("addAnonymousId"),'新增','')
        ;
        ;
        $listBuilder->display();
    }
    /*
   * 删除对应随机id
   */
    public  function deleteAnonymousId($id)
    {
        D('anonymous')->where(array('id'=>$id))->delete();
        $this->redirect('anonymous');
    }
    public function addAnonymousId()
    {
        if(IS_POST)
        {
            $data['uid'] = I('post.uid', '', 'intval');
            if($data['uid']){
                $is_val=D('anonymous')->where(array('uid'=>$data['uid']))->select();
                $is_member=D('member')->where(array('uid'=>$data['uid']))->select();
                if($is_member){
                    if($is_val)
                    {
                        $this->error('该用户id已经存在');
                        return;
                    }
                    D('anonymous')->add($data);
                    $this->success('新增成功！',U('anonymous'));
                }
                else
                {
                    $this->error('');
                }
            }
            else if($data['uid']=="")
            {
                $this->error('不能为空！');
            }
            else
            {
                $this->error('插入失败！');
            }
        }
            $builder=new AdminConfigBuilder();
            $builder->keyText('uid', '请输入你想添加的uid')
                ->buttonSubmit('', '添加');
            $builder->display();
    }

    public function navCtrl(){
        $navMod = M('AppNav');
        $time = time();
        if (IS_POST) {
            S('app_mod_change_time',$time);
            if($_POST['event'] == 'reset'){
                D()->execute('TRUNCATE TABLE '.$this->tabName.'app_nav');
                $this->success('操作成功');
            }else{
                $postInfo = $_POST['nav'];
                if(count($postInfo)>0){
                    D()->execute('TRUNCATE TABLE '.$this->tabName.'app_nav');
                    for ($i = 0; $i < count(reset($postInfo)); $i++) {
                        $data[$i]['name'] = op_t($postInfo['name'][$i]);
                        $data[$i]['title'] = op_t($postInfo['title'][$i]);
                        if($data[$i]['title'] && !$navMod->where($data[$i])->find()){
                            $data[$i]['type'] = op_t($postInfo['type'][$i]);
                            $data[$i]['url'] = op_t($postInfo['url'][$i]);
                            $data[$i]['target'] = op_t($postInfo['target'][$i]);
                            $data[$i]['status'] = op_t($postInfo['status'][$i]);
                            $data[$i]['remark'] = op_t($postInfo['remark'][$i]);
                            $data[$i]['title_color'] = op_t($postInfo['title_color'][$i]);
                            $data[$i]['icon_color'] = op_t($postInfo['icon_color'][$i]);
                            $data[$i]['create_time'] = $time;
                            $data[$i]['update_time'] = $time;
                            $navMod->add($data[$i]);
                        }
                    }
                }
                $this->success('操作成功');
            }
        }else{
            if(!$navMod->count()){
                D()->execute("CREATE TABLE IF NOT EXISTS `".$this->tabName."app_nav` (
              `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
              `name` char(30) NOT NULL COMMENT '系统模块名,如weibo',
              `title` char(30) NOT NULL COMMENT '文字标题,如微博',
              `url` char(100) NOT NULL COMMENT '导航连接',
              `type` char(18) NOT NULL COMMENT '导航类型(自定义或者系统)',
              `sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '导航排序',
              `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
              `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
              `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
              `target` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '是否新窗口打开',
              `icon` varchar(20) NOT NULL,
              `title_color` varchar(30) NOT NULL,
              `icon_color` varchar(30) NOT NULL,
              `remark` text NOT NULL COMMENT '备注信息',
              PRIMARY KEY (`id`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;");
                $navMod = M('AppNav');
                S('app_mod_change_time',$time);
                foreach($this->modList as $key=>$modName){
                    $modInfo =  M('Module')->where(array('name'=>$modName))->find();
                    $channel = M('Channel')->where(array('url'=>array('like','%'.$modName.'%')))->find();
                    $data['name'] = $modName;
                    $data['title'] = $channel?$channel['title']:$modInfo['alias'];
                    $data['type'] = 'module';
                    $data['url'] = $modInfo['entry'];
                    $data['create_time'] = time();
                    $data['update_time'] = time();
                    $data['status'] = 1;
                    $data['target'] = 0;
                    $data['remark'] = $channel['remark']?$channel['remark']:$modInfo['summary'];
                    $navMod->add($data);
                }
                unset($key,$modName);
            }
            $list = $navMod->order('sort asc,id asc')->select();
            $systemMods = M('Module')->where(array('app_has' => 1))->select();
            $this->assign('module', $systemMods);
            $this->assign('list', $list);
            $this->display(T('Api@default/Api/navctrl'));
        }
    }

    public function versionCtrl(){
        //显示页面
        $builder = new AdminConfigBuilder();
        $data = $builder->handleConfig();
        $builder->title('版本控制')
            ->data($data)
            ->keyLabel('','版本控制规则--')
            ->keyLabel('','****所有版本号都必须严格依照“1.0.0”此类格式。列如：1.1.1')
            ->keyLabel('','****版本升级：版本号第一位变动，需要用户先卸载旧版本客户端再安装新版客户端，列如版本号由1.1.1升级为2.1.1')
            ->keyLabel('','****功能升级：版本号第二位变动，需要下载新版客户端覆盖安装升级，列如2.1.1升级为2.2.0')
            ->keyLabel('','****补丁升级：版本号第三位变动，需要下载‘补丁升级包’安装升级，列如2.1.1升级为2.1.2')
            ->keyLabel('','')
            ->keyText('APP_NEW_VERSION', '当前版本号', '客户端最新版本号，每次升级新版本后都需要手动更改')
            ->keyText('APP_PATCH_VERSION', '补丁基础版本号', '可以使用下面‘补丁升级包’的最低版本号  注意：一般只是最后一位比‘当前版本号’的小')
            ->keyText('APP_MIN_VERSION', '最低版本号', '只有此版本号到最新版本号之间的版本才可以正常使用。不填表示支持所有版本')
            ->keyLabel('','')
            ->keySingleFile('APP_APK_FILE', '安卓客户端安装包', '最新版的安卓安装包，.apk文件')
            ->keySingleFile('APP_PATCH_FILE', '补丁升级包', '修复BUG等小版本升级补丁，.wgtu文件')
            ->keyLabel('','***如果上传安装包和补丁文件失败，有可能是服务器文件上传设置没有添加.apk和.wgtu格式，或者单文件上传容量限制造成的')
            ->keyLabel('','')
            ->keyText('APP_OUT_LINK', 'APP外部下载地址','存放在第三方服务器上的安卓或者苹果越狱安装包')
            ->buttonSubmit('', '保存');
        $builder->display();
    }

    /**执行数据库语句
     * @author 胡佳雨 <hjy@ourstu.com>.
     */
    public function executeSQL(){
        $module = D();
        $module->execute("ALTER TABLE  `".$this->tabName."sync_login` ADD  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '状态描述';");
        $module->execute("ALTER TABLE  `".$this->tabName."cat_entity` ADD  `app_icon` INT(9) NOT NULL COMMENT '手机客户端显示的图标';");
        $module->execute("ALTER TABLE  `".$this->tabName."store_order` ADD  `r_id` INT(9) NOT NULL COMMENT '关联的配送地址id';");
        $module->execute("ALTER TABLE  `".$this->tabName."weibo_long` ADD  `cover` INT(11) NOT NULL DEFAULT 0;");
        $module->execute("ALTER TABLE  `".$this->tabName."picture` ADD  `size` INT(11) NOT NULL DEFAULT 0;");
        $module->execute("ALTER TABLE  `".$this->tabName."weibo` ADD  `geolocation_id` INT (11) NOT NULL COMMENT '地理信息id';");
        $module->execute("ALTER TABLE  `".$this->tabName."user_location` ADD  `geolocation_id` INT (11) NOT NULL COMMENT '地理信息id';");
        $module->execute("ALTER TABLE  `".$this->tabName."store_goods` ADD  `total_price` DECIMAL(10,2) NOT NULL COMMENT '微店增加一个总价字段';");
        $module->execute("CREATE TABLE IF NOT EXISTS `".$this->tabName."voice` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `uid` int(11) NOT NULL COMMENT '上传者id',
  `driver` varchar(20) NOT NULL COMMENT '上传驱动',
  `path` varchar(255) NOT NULL COMMENT '上传路径',
  `md5` varchar(50) NOT NULL COMMENT 'md5',
  `sha1` varchar(50) NOT NULL COMMENT 'sha1',
  `status` tinyint(4) NOT NULL COMMENT '状态',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `type` int(11) NOT NULL COMMENT '类型,文件后缀',
  `size` CHAR(11) NOT NULL COMMENT '体积,单位byte',
  `extra` text NOT NULL COMMENT '额外信息',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;");
        $module->execute("
CREATE TABLE IF NOT EXISTS `".$this->tabName."user_geolocation` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `uid` INT(11) NOT NULL,
  `coords_type` CHAR(11) NOT NULL COMMENT '坐标系类型',
  `lng` DECIMAL(10,5) DEFAULT '0.000' COMMENT '经度',
  `lat` DECIMAL(10,5) NOT NULL DEFAULT '0.000' COMMENT '纬度',
  `altitude` INT(10) NOT NULL DEFAULT '0' COMMENT '海拔',
  `create_time` BIGINT(18) UNSIGNED NOT NULL COMMENT '地址的创建时间',
  `country` VARCHAR(30) NOT NULL COMMENT '国家',
  `province` VARCHAR(30) NOT NULL COMMENT '省',
  `city` VARCHAR(30) NOT NULL COMMENT '市',
  `district` VARCHAR(30) NOT NULL COMMENT '区',
  `street` VARCHAR(30) NOT NULL COMMENT '街道',
  `cityCode` INT(9) NOT NULL COMMENT '城市代码',
  `address` VARCHAR(128) NOT NULL COMMENT '详细地址',
  `only` INT(1) NOT NULL COMMENT '是否唯一',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户位置信息';");
        $this->success('操作成功');
    }

   public function setCatIcon($page = 1, $r = 20){
       $data = D('cat_entity')->where('status>-1')->page($page, $r)->order('sort desc')->select();
       $totalCount = D('cat_entity')->where('status>-1')->count();
       $listBuilder = new AdminListBuilder();

       $listBuilder->title('客户端图标')
           ->keyId()
           ->key('alias', '分类名称','')
           ->keyImage('app_icon','客户端图标')
           ->keyDoActionEdit('editCat?entity_id=###','修改')
           ->data($data)
           ->pagination($totalCount, $r);
       $listBuilder->display();
   }


    /**分类信息分类图标
     * @param int $entity_id
     * @author 胡佳雨 <hjy@ourstu.com>.
     */
    public function editCat($entity_id=0){
        $entity_id = intval($entity_id);
        $configBuilder = new AdminConfigBuilder();
        if ($entity_id != 0) {
            $entity = D('cat_entity')->find($entity_id);
        }
        if (IS_POST) {
            $entity = D('cat_entity')->create();
            $entity['status'] = 1;
            $rs = D('cat_entity')->save($entity);
            if ($rs) {
                $this->success('操作成功。');
            } else {
                $this->error('操作失败。');
            }
        }else{
            $configBuilder->title('客户端图标配置')
                ->keyId()
                ->keyLabel('alias', '中文名称')
                ->keySingleImage('app_icon','选择客户端图标')
                ->buttonSubmit()
                ->buttonBack()
                ->data($entity)
                ->display();
        }
    }

    /**用于配置个推服务的授权信息
     * @author 胡佳雨 <hjy@ourstu.com>.
     */
    public function igtConfig(){
        $builder = new AdminConfigBuilder();
        $data = $builder->handleConfig();
        $builder->title('个推配置')
            ->data($data)
            ->keyBool('IGT_OPEN','是否开启推送服务','默认为否')
            ->keyDefault('IGT_OPEN', 0)
            ->keyText('IGT_APPID', 'appid', '个推所给的APPID')
            ->keyText('IGT_APPKEY', 'apkey', '个推所给的APPKEY')
            ->keyText('IGT_MASTERSECRET', 'mastersecret', '个推所给的MASTERSECRET')
            ->buttonSubmit('', '保存');
        $builder->display();
    }

    /**IM服务配置项
     * @author 胡佳雨 <hjy@ourstu.com>.
     */
    public function IMConfig(){
        $builder = new AdminConfigBuilder();
        $data = $builder->handleConfig();
        $builder->title('IM配置')
            ->data($data)
            ->keyBool('IM_OPEN','是否开启IM服务','默认为否')
            ->keyDefault('IM_OPEN', 0)
            ->keyLabel('','这里只是设置是否允许客户端启用IM，还需要在‘扩展->插件管理’安装启用的IM插件，然后‘设置’IM服务商提供的授权凭证')
            ->buttonSubmit('', '保存');
        $builder->display();
    }

    /**第三方登陆配置项
     * @author 胡佳雨 <hjy@ourstu.com>.
     */
    public function syncLoginConfig(){
        $builder = new AdminConfigBuilder();
        $data = $builder->handleConfig();
        $builder->title('第三方登陆配置')
            ->data($data)
            ->keyCheckBox('SYNCLOGIN_TYPE','选择需要开启的第三方登陆','不选择则表示不开启',array('qq' => 'QQ', 'weixin' =>'微信', 'sina' => '新浪微博'))
            ->buttonSubmit('', '保存');
        $builder->display();
    }
}