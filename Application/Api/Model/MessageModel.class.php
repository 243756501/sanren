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

class messageModel extends Model
{

    public function dealMessage($messageInfo){
        if (in_array($messageInfo['type'], array('1', '2', '3', '0', '4', '5'))) {
            $messageInfo['type'] = 'Common_system';
        }
        $messageInfo['content'] = D('Common/Message')->getContent($messageInfo['content_id']);
        if (is_array($messageInfo['content']['content'])) {
            $messageInfo['content']['untoastr'] = 1;
        }
        if(!$messageInfo['content']['user']){
            $messageInfo['content']['user'] = array();
        }
        if($messageInfo['type'] == 'Weibo'){
            $messageInfo['content']['weibo_data'] = D('Api/weibo')->getWeibo($messageInfo['content']['args']['id'],is_login());
        }
        return $messageInfo;
    }

}