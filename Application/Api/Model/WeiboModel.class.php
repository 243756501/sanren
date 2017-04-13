<?php


namespace Api\Model;

use Think\Model;

class WeiboModel extends Model
{
    protected $tableName = 'weibo';

    /**根据圈子ID获取圈子数据
     * @param $id
     * @param $mid
     * @param $flag int 是否需要详细处理
     * @return mixed
     * @author 胡佳雨 <hjy@ourstu.com>.
     */
    public function getCrowd($id,$mid = 0,$flag = 0){
        $crowdInfo = M('WeiboCrowd')->where(array('id'=>$id,'status'=>1))->find();
        if($flag && $crowdInfo){
            $crowdInfo['logo'] = get_one_pic($crowdInfo['logo']);
            $crowdInfo['type_str'] = $crowdInfo['type']?'私密':'公共';
            $crowdInfo['user'] = D('Api/user')->getUser($crowdInfo['uid']);
            if($mid){
                $res = M('WeiboCrowdMember')->where(array('crowd_id'=>$crowdInfo['id'],'uid'=>$mid))->find();
                $crowdInfo['is_follow'] = $res?1:0;
                $crowdInfo['is_check'] = $res['status'] == 1?1:0;
                $crowdInfo['permission'] = is_administrator($mid)?3:$res['position'];
                if($crowdInfo['permission'] > 1){
                    $crowdInfo['check_count'] = M('WeiboCrowdMember')->where(array('crowd_id'=>$crowdInfo['id'],'status'=>0))->count();
                    $crowdInfo['member_ids'] = M('WeiboCrowdMember')->where(array('crowd_id'=>$crowdInfo['id']))->getField('uid',true);
                }
            }
        }
        return $crowdInfo;
    }

    /*设置圈子权限*/
    public function setCrowdPerms($crowdId,$uid,$position)
    {
        return M('WeiboCrowdMember')->where(array('crowd_id'=>$crowdId,'uid'=>$uid,'status'=>1))->setField('position',$position);
    }

    /*获得目标用户在圈子里面的权限 */
    public function getCrowdPerms($crowdId,$uid)
    {
        if(is_administrator($uid)){
            return 3;
        }
        $res = M('WeiboCrowdMember')->where(array('crowd_id'=>$crowdId,'uid'=>$uid,'status'=>1))->find();
        return $res?$res['position']:0;
    }

    /*检测圈子发布微博权限*/
    public function checkSendAuthCrowd($crowdId,$uid)
    {
        $res = M('WeiboCrowdMember')->where(array('crowd_id'=>$crowdId,'uid'=>$uid))->find();
        if($res){
            $crowdInfo= M('WeiboCrowd')->where(array('crowd_id'=>$crowdId))->find();
            $res = $crowdInfo['allow_user_post'] == -1 ? $res: 0;
        }
        return $res;
    }

    public function sendWeibo($data)
    {

        if (!$data) {
            return false;
        }

        $weibo_id = $this->add($data);
        if (!$weibo_id) {
            return false;
        }
        return $weibo_id;

    }

    /**处理微博数据
     * @param $weibo
     * @param $mid
     * @param $flag int 是否需要详细处理
     * @return mixed
     * @author 胡佳雨 <hjy@ourstu.com>.
     */
    public function getWeibo($weibo, $mid, $flag= 1)
    {
        if(!is_array($weibo)){
            $weibo = $this->where(array('id' => $weibo, 'status' => 1))->find();
        }
        if($flag && $weibo){
            $weibo['data'] = unserialize($weibo['data']);
            $weibo['share_url'] = is_https().$_SERVER['HTTP_HOST'].'/weibo/index/weibodetail/id/'.$weibo['id'];
            $weibo['from'] = empty($weibo['from']) ? '网站端' : get_from($weibo['from']);
            $weibo['support_count'] = D('Support')->where(array('appname' => 'Weibo', 'table' => 'weibo', 'row' => $weibo['id']))->count();
            $support = D('Support')->where(array('appname' => 'Weibo', 'table' => 'weibo', 'row' => $weibo['id'], 'uid' => $mid))->find();
            if ($support) {
                $weibo['is_support'] = 1;
            } else {
                $weibo['is_support'] = 0;
            }
            $weibo['user'] = D('Api/User')->getUser($weibo['uid']);
            if($weibo['type'] == 'voice'){
                $voiceId = $weibo['data']['attach_ids'];
                $weibo['attach']['voice'] = get_attach_url($voiceId,'Voice');
            }elseif ($weibo['type'] == 'image') {
                $img_ids = explode(',', $weibo['data']['attach_ids']);
                $weibo['image_count'] = count($img_ids);
                $img = array();
                foreach ($img_ids as $k => $v) {
                    $img['ori'][$k] = render_picture_path_without_root(get_cover($v, 'path'));
                    $img['thumb'][$k] = render_picture_path_without_root(getThumbImageById($v, 200, 200));
                }
                unset($v);
                $weibo['attach']['image'] = $img;
            }elseif ($weibo['type'] == 'repost') {
                $weibo['sourceWeibo'] = $this->getWeibo($weibo['data']['sourceId'], $mid);
                if ($weibo['sourceWeibo'] == null) {
                    $weibo['sourceWeibo']['type'] = null;
                }
            }elseif($weibo['type'] == 'video'){
                $weibo['video_id']=str_replace('http://v.youku.com/v_show/id_','',$weibo['data']['video_url']);
                $weibo['video_mp4_url']='http://v.youku.com/player/getRealM3U8/vid/'. $weibo['data']['video_id'].'/type/mp4/v.m3u8';
                $weibo['video_flv_url']='http://v.youku.com/player/getRealM3U8/vid/'. $weibo['data']['video_id'].'/type/video.m3u8';
            }elseif ($weibo['type'] == 'redbag') {
                $weibo['data'] = array();
            }elseif($weibo['type'] == 'share'){
                if($weibo['data']['from'] == '资讯'){
                    $weibo['data']['site_link'] = '/news'.strrchr( $weibo['data']['site_link'],'/');
                }
            }

            if (modC('WEIBO_BR', 0, 'Weibo')) {
                $weibo['content'] = str_replace('/br', '<br/>', $weibo['content']);
                $weibo['content'] = str_replace('/nb', '&nbsp', $weibo['content']);
            } else {
                $weibo['content'] = str_replace('/br', '', $weibo['content']);
                $weibo['content'] = str_replace('/nb', '', $weibo['content']);
            }

            if($weibo['type']==='long_weibo'){
                $weibo['content'] = text($weibo['content']);
                $long_weibo = M('WeiboLong')->where(array('weibo_id'=>$weibo['id']))->find();
                $long_weibo['cover'] = get_one_pic($long_weibo['cover']);
                $weibo['long_weibo']=$long_weibo;

            }
            //处理话题信息start
            $topic = get_weibo_topic_info($weibo['content']);
            if (!empty($topic)) {
                $topics = M('WeiboTopic')->where(array('id' => array('in', $topic)))->getField('id,uadmin,name,status');
                $arr = array();
                foreach ($topics as $e) {
                    array_push($arr,$e);
                    $weibo['content'] = str_replace("[topic:" . $e['id'] . "]", '', $weibo['content']);
                }
                $weibo['topic_info'] = $arr;
                unset($e);
            } else {
                $weibo['topic_info'] = null;
            }
            //处理话题信息end
            //处理圈子
            if($weibo['crowd_id']){
                $crowdInfo = $this->getCrowd($weibo['crowd_id']);
                $weibo['crowd_info'] = array('id'=>$crowdInfo['id'],'title'=>$crowdInfo['title'],'uid'=>$crowdInfo['uid']);
            }
            //地理位置信息
            if($weibo['geolocation_id']){
                $geolMod = M('UserGeolocation');
                $weibo['geolocation'] = $geolMod->where(array('id'=>$weibo['geolocation_id'],'uid'=>$weibo['uid']))->find();
            }
            //新人微博
            $beforeWeibo = $this->where(array('status' => 1, 'create_time' => array('lt', $weibo['create_time']), 'uid' => $weibo['uid']))->find();
            $weibo['is_first'] = $beforeWeibo?0:1;
            $weibo['content'] = parse_at_app_users($weibo['content']);
        }
        return $weibo;
    }



    public function delWeibo($id, $mid)
    {
        $weibo = $this->getWeibo($id, $mid);
        //从数据库中删除微博、以及附属评论
        $result = $this->where(array('id' => $id))->save(array('status' => -1, 'comment_count' => 0));
        M('WeiboComment')->where(array('weibo_id' => $id))->setField('status', -1);
        D('Weibo/Topic')->afterDelWeibo($id);
        if ($weibo['type'] == 'repost') {
            $this->where(array('id' => $weibo['data']['sourceId']))->setDec('repost_count');
            S('api_weibo_' . $weibo['data']['sourceId'], null);
        }
        S('weibo_' . $id, null);
        return $result;
    }


    public function getComment($id)
    {
        $commentModel = M('weibo_comment');

        $comment = $commentModel->where(array('id' => $id, 'status' => 1))->find();
        if ($comment) {
            $comment['content'] = parse_comment_mob_content($comment['content']);
            $comment['content'] = $comment['content']?$comment['content']:'';
            $comment['user'] = D('Api/User')->getUser($comment['uid']);
            $comment['create_time'] = friendlyDate($comment['create_time']);
            S('api_weibo_comment_' . $id, $comment);
            $comment['can_delete'] = check_auth('Weibo/Index/doDelComment', $comment['uid']);
            return $comment;
        }
    }

    public function sendranComment($weibo_id, $content, $comment_id = 0, $mid = 0,$time=0)
    {
        $commentModel = M('weibo_comment');
        $result = $commentModel->add(array('uid' => $mid, 'status' => 1, 'content' => $content, 'weibo_id' => $weibo_id, 'to_comment_id' => $comment_id, 'create_time' => $time));
        if (!$result) {
            return false;
        } else {
            //增加微博评论数量
            D('Weibo')->where(array('id' => $weibo_id))->setInc('comment_count');
            D('Weibo/WeiboCache')->cleanCache($weibo_id);
        }


        S('weibo_' . $weibo_id, null);
        return $result;
    }

    public function sendComment($weibo_id, $content, $comment_id = 0, $mid = 0)
    {
        $commentModel = M('weibo_comment');
        $result = $commentModel->add(array('uid' => $mid, 'status' => 1, 'content' => $content, 'weibo_id' => $weibo_id, 'to_comment_id' => $comment_id, 'create_time' => time()));
        if (!$result) {
            return false;
        } else {
            //增加微博评论数量
            D('Weibo')->where(array('id' => $weibo_id))->setInc('comment_count');
            D('Weibo/WeiboCache')->cleanCache($weibo_id);
        }


        S('weibo_' . $weibo_id, null);
        return $result;
    }


    public function  sendCommentMessage($uid, $weibo_id, $message, $mid)
    {
        $title = '评论消息';
        $from_uid = $mid;
        $type = 1;
        D('Common/Message')->sendMessage($uid, $title, $message, 'Weibo/Index/weiboDetail', array('id' => $weibo_id), $from_uid, $type);
    }


    public function sendAtMessage($uids, $weibo_id, $content, $from_id = 0)
    {
        $my_username = get_nickname($from_id);
        foreach ($uids as $uid) {
            $message = '内容：' . $content;
            $title = $my_username . '@了您';
            $fromUid = $from_id;
            $messageType = 1;
            D('Common/Message')->sendMessage($uid, $title, $message, 'Weibo/Index/weiboDetail', array('id' => $weibo_id), $fromUid, $messageType);
        }
    }


    public function delComment($id)
    {
        //获取微博编号
        $comment = D('Weibo/WeiboComment')->find($id);
        if ($comment['status'] == -1) {
            return false;
        }
        $weibo_id = $comment['weibo_id'];

        //将评论标记为已经删除
        D('Weibo/WeiboComment')->where(array('id' => $id))->setField('status', -1);
        D('Weibo/WeiboCache')->cleanCache($weibo_id);
        //减少微博的评论数量
        D('Weibo/Weibo')->where(array('id' => $weibo_id))->setDec('comment_count');
        S('weibo_' . $weibo_id, null);
        //返回成功结果
        return true;
    }

    /**处理话题信息
     * @param $topic
     * @return mixed
     * @author 胡佳雨 <hjy@ourstu.com>.
     */
    public function dealTopic($topic){
        if($topic){
            $topic['logo'] =  $topic['logo']?get_one_pic($topic['logo']):0;
            $topic['user'] = $topic['uadmin']?D('Api/User')->getUser($topic['uadmin']):0;
        }
        return $topic;
    }

    /**
     * 根据数组中的某个键值大小进行排序，仅支持二维数组
     *
     * @param array $array 排序数组
     * @param string $key 键值
     * @param bool $asc 默认正序
     * @return array 排序后数组
     */
    public function arraySortByKey(array $array, $key, $asc = true)
    {
        $result = array();
        // 整理出准备排序的数组
        foreach ($array as $k => &$v) {
            $values[$k] = isset($v[$key]) ? $v[$key] : '';
        }
        unset($v);
        // 对需要排序键值进行排序
        $asc ? asort($values) : arsort($values);
        // 重新排列原有数组
        foreach ($values as $k => $v) {
            $result[$k] = $array[$k];
        }

        return $result;
    }




}