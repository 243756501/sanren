<?php
/*
 * 随机获取设置的随机Id
 */
function getrandomId(){
    $array=D('anonymous')->where('id>0')->select();
    $len=count($array);
    $ran=rand(1,$len);
    if($array[$ran]['uid'])
    {
        return $array[$ran]['uid'];
    }
    else
    {
        $this->getrandomId();
    }
}



/**统一的推送接口
 * @param $uidList
 * @param $data
 * @author 胡佳雨 <hjy@ourstu.com>.
 */
function igetuiPush($uidList,$data){
    $ITGSWITCH= modC('IGT_OPEN',0,'Api');        //是否开启了推送功能
    if($ITGSWITCH){
        $cidList = array();
        $tidList = array();  //android的cid和tid相同，而IOS的tid和cid不相同
        foreach($uidList as $uid){
            $user = M('UserLocation')->where(array('uid'=>$uid))->find();
            array_push($cidList,$user['ClientID']);
//            if($user['ClientID'] == $user['token']){
//            }else{
//                array_push($tidList,$user['token']);
//            }
        }
        unset($uid);
        if(!empty($cidList)){
            D('Api/Igt')->pushMessageToList($cidList,$data);   //推送
        }
//        if(!empty($tidList)){
//            $this->pushAPNL($tidList,$data);            //向苹果aps推送
//        }
    }
}
function is_https(){
    //TODO
//    if(modC('APP_HTTPS', 1, 'Api')){
//        return 'https://';
//    }
    return 'http://';
}

/**
 * I_POST  获取post提交的参数
 * @param string $key
 * @param mixed $filter
 * @return mixed
 * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
 */
function I_POST($key = '', $filter = 'text')
{
    $value = I('post.' . $key,'',$filter);
    if (empty($value)) {
        $value = I('put.' . $key,'',$filter);
    }

    return $value;
}

/**I_POST的增强版本，可设置默认值
 * @param string $key
 * @param string $filter
 * @param mixed $default 默认值
 * @return mixed
 * @author 胡佳雨 <hjy@ourstu.com>.
 */
function POST_I($key = '',$filter = 'text',$default = '')
{
    $value = I('post.' . $key,$default,$filter);
    if (empty($value)) {
        $value = I('put.' . $key,$default,$filter);
    }

    return $value;
}

/**
 * api_encode  加密
 * @param $txt
 * @param null $key
 * @return string
 * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
 */
function api_encode($txt, $key = null)
{
    $key = empty($key) ? C('DATA_AUTH_KEY') : $key;
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-=_";
    $nh = rand(0, 64);
    $ch = $chars[$nh];
    $mdKey = md5($key . $ch);
    $mdKey = substr($mdKey, $nh % 8, $nh % 8 + 7);
    $txt = base64_encode($txt);
    $tmp = '';
    $i = 0;
    $j = 0;
    $k = 0;
    for ($i = 0; $i < strlen($txt); $i++) {
        $k = $k == strlen($mdKey) ? 0 : $k;
        $j = ($nh + strpos($chars, $txt [$i]) + ord($mdKey[$k++])) % 64;
        $tmp .= $chars[$j];
    }
    return $ch . $tmp;
}

/**
 * api_decode  解密
 * @param $txt
 * @param null $key
 * @return string
 * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
 */
function api_decode($txt, $key = null)
{
    $key = empty($key) ? C('DATA_AUTH_KEY') : $key;

    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-=_";
    $ch = $txt[0];
    $nh = strpos($chars, $ch);
    $mdKey = md5($key . $ch);
    $mdKey = substr($mdKey, $nh % 8, $nh % 8 + 7);
    $txt = substr($txt, 1);
    $tmp = '';
    $i = 0;
    $j = 0;
    $k = 0;
    for ($i = 0; $i < strlen($txt); $i++) {
        $k = $k == strlen($mdKey) ? 0 : $k;
        $j = strpos($chars, $txt[$i]) - $nh - ord($mdKey[$k++]);
        while ($j < 0) {
            $j += 64;
        }
        $tmp .= $chars[$j];
    }
    return base64_decode($tmp);
}

function get_attach_path($path){
    return is_bool(strpos($path, 'http')) ?  is_https().str_replace('//','/',$_SERVER['HTTP_HOST'] .'/'. $path) : $path;
}

function render_picture_path_without_root($path){
    $path = strpos($path,'Uploads/Avatar')?$path:str_replace('Uploads/Avatar','',$path);
    return get_attach_path($path);
}


function del_dir($dir)
{
    //先删除目录下的文件：
    $dh = opendir($dir);
    while ($file = readdir($dh)) {
        if ($file != "." && $file != "..") {
            $fullpath = $dir . "/" . $file;
            if (!is_dir($fullpath)) {
                unlink($fullpath);
            } else {
                $this->deldir($fullpath);
            }
        }
    }

    closedir($dh);
    //删除当前文件夹：
    if (rmdir($dir)) {
        return true;
    } else {
        return false;
    }
}



function get_from($from_en){
    switch($from_en){
        case 'MI 4LTE':$from = '小米4';break;
        case 'iPhone':$from = 'IPhone客户端';break;
        case 'HM 2A':$from = '红米2A';break;
        default :
            $from =$from_en;
            if(strpos($from_en,'H60') === 0){
                $from = '华为荣耀6';
            }

    }
    return $from;
}


function H5U($url = '', $vars = '', $suffix = true, $domain = false){
    $url = preg_replace("/(?<=#)[\s\S]*$/","",$url);
    $link =  require('./Application/Api/Conf/router.php');
    $url_mob = $link['router'][$url];
    return U($url_mob, $vars , $suffix, $domain);
}

//解析虾米
function ipcxiami($location){
    $count = (int)substr($location, 0, 1);
    $url = substr($location, 1);
    $line = floor(strlen($url) / $count);
    $loc_5 = strlen($url) % $count;
    $loc_6 = array();
    $loc_7 = 0;
    $loc_8 = '';
    $loc_9 = '';
    $loc_10 = '';
    while ($loc_7 < $loc_5){
        $loc_6[$loc_7] = substr($url, ($line+1)*$loc_7, $line+1);
        $loc_7++;
    }
    $loc_7 = $loc_5;
    while($loc_7 < $count){
        $loc_6[$loc_7] = substr($url, $line * ($loc_7 - $loc_5) + ($line + 1) * $loc_5, $line);
        $loc_7++;
    }
    $loc_7 = 0;
    while ($loc_7 < strlen($loc_6[0])){
        $loc_10 = 0;
        while ($loc_10 < count($loc_6)){
            $loc_8 .= @$loc_6[$loc_10][$loc_7];
            $loc_10++;
        }
        $loc_7++;
    }
    $loc_9 = str_replace('^', 0, urldecode($loc_8));
    return $loc_9;
}



function getXiaMiUrl($ID){
    if(!empty($ID)){
        $url= 'http://www.xiami.com/widget/json-single/sid/'.$ID;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_USERAGENT, '');
        curl_setopt($ch, CURLOPT_REFERER,'b');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $content = curl_exec($ch);
        curl_close($ch);
        $result = json_decode($content,true);
        return $result;
    }else{
        return false;
    }

}

/**详情的简洁格式
 * @param String
 * @return String
 * @author 胡佳雨 <hjy@ourstu.com>.
 */
function fmt_list_content($content){
    $content = strip_tags($content,'<img>');
    $content = preg_replace("/<img[^>]+>/",'[图片]',$content);
    $content = mb_substr($content, 0, 100, 'utf-8');
    return $content;
}

/** fmatDtlContent 用于过滤掉html文本的大量冗余属性
* @param $content
* @return string
*/
function fmatDtlContent($content){
    $content = preg_replace("/style=.+?['|\"]/i","",$content);
    $content = preg_replace("/<a[^>]*>/","", $content);
    $content = preg_replace("/<\/a>/","", $content);
    return $content;
}

function fmat_at_text($text){
    $text = preg_replace('/\[at:(\d)+\]/','',$text);
    return $text;
}

/**根据ID获取一张图片，如果传入width则返回缩略图，否则取原图
 * @param String $cover_id
 * @param int $width
 * @return String
 * @author 胡佳雨 <hjy@ourstu.com>.
 */
function get_one_pic($cover_id,$width = null){
    $path = $width ? getThumbImageById($cover_id, $width) : get_cover($cover_id,'path');
    return $path?get_attach_path($path):'';
}

/**获取文件url
 * @param int $id 文件id
 * @return String
 * @author 胡佳雨 <hjy@ourstu.com>.
 */
function get_file_url($id){
    $res = M('File')->where(array('id' => $id))->find();
    $resUrl = '';
    if($res) {
        $resUrl = $res['driver'] == 'local' ? is_https().$_SERVER['HTTP_HOST'] . $res['savepath'] . $res['savename'] : $res['savepath'];
    }
    return $resUrl;
}

/**获取典型(类似声音这类)附件的url地址
 * @param $id int
 * @param $modName string 表名
 * @return mixed
 * @author 胡佳雨 <hjy@ourstu.com>.
 */
function get_attach_url($id,$modName){
    $modName = $modName?$modName:'Attachment';
    $map['id'] = $id;
    $map['status'] = 1;
    $info = M($modName)->where($map)->find();
    $resUrl = null;
    if($info){
        $resUrl = get_attach_path($info['path']);
    }
    return $resUrl;
}
/**比较两个版本号的大小
 * @param String $version1 版本号1
 * @param String $version2  版本2
 * @return int $version1比$version2大返回负数，相等返回0，小返回正数
 * @author 胡佳雨 <hjy@ourstu.com>.
 */
function compare_version($version1,$version2){
    $version1 = (int)str_replace('.','', $version1);
    $version2 = (int)str_replace('.','', $version2);
    return $version2 - $version1;
}

/**检测版本号
 * @param $version
 * @return int
 * @author 胡佳雨 <hjy@ourstu.com>.
 */
function check_update($version){
    $newVers = modC('APP_NEW_VERSION','1.0.0','Api');
    $newArr = explode('.',$newVers);
    $oldArr = explode('.',$version);
    if($newArr[0] != $oldArr[0]){
        return 1;
    }
    if($newArr[1] != $oldArr[1]){
        return 2;
    }
    if($newArr[2] > $oldArr[2]){
        $patchVers = modC('APP_PATCH_VERSION','','Api');
        if(compare_version($patchVers,$version) >=0){
            return 3;
        }else{
            return 2;
        }
    }
    return 0;
}

/*解析微博模块的话题*/
function get_weibo_topic_info($content){
    //正则表达式匹配
    $topic_pattern = "/\[topic:([0-9]+)\]/";
    preg_match_all($topic_pattern, $content, $users);

    //返回话题列表
    return array_unique($users[1]);
}