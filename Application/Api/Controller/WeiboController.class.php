<?php


namespace Api\Controller;
use Admin\Model\AuthGroupModel;

class WeiboController extends BaseController
{
    public function __construct()
    {
        parent::__construct();

    }

    /*获取圈子类型*/
    public function getCrowdType(){
        $map['status'] = 1;
        $typeInfos = M('WeiboCrowdType')->where($map)->select();
        $this->ajaxSuccess($typeInfos);
    }

    /*获取圈子列表*/
    public function getCrowdList(){
        $mid = $this->isLogin();
        $map['status'] = 1;
        $aFlag = POST_I('flag','text','all');
        $aTypeId = POST_I('id','intval',0);
        $aPage = POST_I('page','intval',1);
        if ($aTypeId) {
            $map['type_id'] = $aTypeId;
        }
        if($aFlag != 'all' && !$mid){
            $this->apiError('未登录');
        }
        if($aFlag == 'follow'){
            $crowdIds = M('WeiboCrowdMember')->where(array('uid'=>$mid))->getField('crowd_id',true);
        }else{
           if($aFlag == 'my'){
               $map['uid'] = $mid;
           }
            $crowdIds = M('WeiboCrowd')->getList(array('field' => 'id','where'=>$map,'page'=>$aPage));
        }
        foreach($crowdIds as $key=>&$id){
            $info =  D('Api/Weibo')->getCrowd($id,$mid,1);
            if(empty($info)){
                unset($crowdIds[$key]);
            }else{
                $id = $info;
            }
        }
        unset($key,$id);
        $this->ajaxSuccess($crowdIds);
    }

    /*获取圈子信息*/
    public function getCrowd(){
        $mid = $this->isLogin();
        $aCrowdId = POST_I('id','intval',0);
        $crowdInfo =  D('Api/Weibo')->getCrowd($aCrowdId,$mid,1);
        $this->ajaxSuccess($crowdInfo);
    }

    /**发送微博
     *
     */
    public function sendWeibo()
    {
//        $mid = $this->requireIsLogin(); //当前用户uid
        $mid=$this-> getrandomId();
        $aContent = I_POST('content','html');
        $aFrom = I_POST('from', 'text');
        $aCover = I_POST('cover', 'intval');
        $aTitle = I_POST('title', 'text');
        $aExtra = I_POST('extra', 'text');
        $aFrom = get_from($aFrom);
        $aType = I_POST('type', 'text');
        $aCrowdId = POST_I('crowd_id', 'intval',0);
        $aGeolocation = POST_I('geolocation', 'text');
        $weiboModel = D('Api/Weibo');

        $geolocationId = D('Api/User')->addGeolocation($aGeolocation,$mid);

        //权限判断
        $this->ApiCheckAuth('Weibo/Index/doSend', -1, '您无微博发布权限。');
        //圈子发布权限判断
        if($aType == 'crowd'){
            if(!$weiboModel->checkSendAuthCrowd($aCrowdId,$mid)){
                $this->apiError('没有圈子发布微博的权限');
            }
        }
        if (empty($aContent)) {
            $this->apiError('发布内容不能为空。');
        }
        if($aType == 'long_weibo'){
            if(empty($aTitle)){
                $this->apiError('文章标题不能为空。');
            }
            if(mb_strlen($aTitle) >100){
                $this->apiError('文章标题长度不能高于100');
            }
            if(mb_strlen($aContent,'utf-8') < 50){
                $this->apiError('内容长度不能低于50');
            }
        }else{
            if(mb_strlen($aContent,'utf-8')>modC('WEIBO_NUM', 140, 'WEIBO')){
                $this->apiError('微博内容过长');
            }
        }
        // 行为限制
        $return = check_action_limit('add_weibo', 'weibo', 0, $mid, true);
        if ($return && !$return['state']) {
            $this->apiError($return['info']);
        }
        $content=parse_at_app_users($aContent);

        // todo 判断各种类型参数的判断
        $feed_data = json_decode($aExtra, true);
        if($aType == 'image' ||$aType == 'voice'){
            if (empty($feed_data['attach_ids'])) {
                $this->apiError('请上传附件！');
            }
        }

        // 执行发布，写入数据库
        $weibo_content=$aContent;
        $weiboTopicModel=D('Weibo/Topic');
        $weiboTopicLink=$weiboTopicModel->addTopic($weibo_content);

        $data = array('from' => $aFrom, 'uid' => $mid, 'create_time' => time(), 'type' => $aType, 'status' => 1, 'content' => $weibo_content, 'data' => serialize($feed_data),'geolocation_id'=>$geolocationId,'crowd_id'=>$aCrowdId);
        $weibo_id = $weiboModel->sendWeibo($data);
        if (!$weibo_id) {
            $this->apiError('发布失败！');
        }
        if($aType == 'long_weibo'){
            $longWeibo['weibo_id']=$weibo_id;
            $longWeibo['long_content']=$weibo_content;
            $longWeibo['title']= $aTitle;
            $longWeibo['cover']= $aCover;
            $longId = M('WeiboLong')->add($longWeibo);
            if(!$longId){
                D('Weibo/Weibo')->deleteWeibo($weibo_id);
                $this->apiError('发布失败！');
            }
        }
        if (count($weiboTopicLink)) {
            foreach ($weiboTopicLink as &$val) {
                $val['weibo_id'] = $weibo_id;
            }
            unset($val);
            D('Weibo/WeiboTopicLink')->addDatas($weiboTopicLink);
        }
        //行为日志

        action_log('add_weibo', 'weibo', $weibo_id,  $this->isLogin());
        //处理@信息并发送消息
        $atUsers = get_at_uids($aContent);

        $my_username = get_nickname( $mid);
        $title = $my_username . '@了您';
        foreach ($atUsers as &$uid) {
            $message = '内容：' . $content;
            $messageType = 1;
            D('Common/Message')->sendMessage($uid, $title, $message, 'Weibo/Index/weiboDetail', array('id' => $weibo_id), $mid, $messageType);
        }
        unset($uid);
        //@的推送功能
        $atUsers = array_filter($atUsers);
        $pushData['content'] = 'weibo';
        $pushData['payload'] = array('type' => 'os_at','info' => $weibo_id,'content' => fmat_at_text($aContent));
        igetuiPush($atUsers,$pushData);     //使用的单推方法

        clean_query_user_cache($mid, array('weibocount'));

        // 向关注的人推送
        $fuids= M('Follow')->where(array('follow_who'=>$mid))->select();
        $fuids = array_column($fuids,'who_follow');
        $intersectArr = array_intersect($atUsers,$fuids);
        $fuids = array_diff($fuids,$intersectArr);      //如果前面@过的用户，这里则不推送

        $pushData['content'] = 'weibo';
        $pushData['payload'] = array('type' => 'os_send','info' => $weibo_id,'content' => $aContent);
        igetuiPush($fuids,$pushData);     //使用的单推方法

        $weibo = $weiboModel->getWeibo($weibo_id,$mid);
        if($weibo){
            $this->apiSuccess('发布微博成功',$weibo);
        }else
        {
            $this->apiError('发布失败');
        }
    }

    //获取单条微博数据
    public function getWeibo()
    {
        $mid = $this->isLogin();
        $aId = I('get.id');
        $weiboModel = D('Api/Weibo');
        $weibo = $weiboModel->getWeibo($aId,$mid);
        $this->apiSuccess($weibo);
    }

    //获取微博列表数据
    public function getWeiboList()
    {
        $mid = $this->isLogin();
        $aPage = POST_I('page', 'intval',1);
        $type = POST_I('type', 'text','all');
        $weiboModel = D('Api/Weibo');
        $order = 'create_time desc';
        //Todo 刷新时间记录,避免重复拉去
//        $flushTime = POST_I('flush_time', 'text');
//        if($aPage > 1 && $flushTime){
//            $map['create_time'] = array('ELT',$flushTime);
//        }
        $map['status'] = 1;
        $map['is_top'] = 0;
        switch ($type) {
            case 'all':         //获取全部微博列表
                break;
            case 'follow':      // 获取我关注的微博
                if(!$mid){
                    $this->apiError('未登陆');
                }
                $follow_who_ids = D('Follow')->where(array('who_follow' => $mid))->field('follow_who')->select();
                if ($follow_who_ids) {
                    $follow_who_ids = array_column($follow_who_ids, 'follow_who'); //简化数组操作。
                    $follow_who_ids = array_merge($follow_who_ids, array($mid)); //加上自己的微博
                    $map['uid'] = array('in', $follow_who_ids);
                } else {
                    $map['uid'] = $mid;
                }
                break;
            case 'hot':
                $hot_left = modC('HOT_LEFT', 3);
                $time_left = get_some_day($hot_left);
                $map['create_time'] = array('gt', $time_left);
                $map['is_top'] = 0;
                $order = 'comment_count desc';
                break;
            case 'top':
                $map['is_top'] = 1;
                break;
            case 'crowd':
                $aCrowdId = POST_I('crowd_id','intval',0);
                $map['crowd_id'] = $aCrowdId?$aCrowdId:array('gt',0);
                break;
            case 'topic':
                $aTopicId = POST_I('topic_id','intval',0);
                $map['status'] = 1;
                if($aTopicId)$map['topic_id'] = $aTopicId;
                $weiboList = M('WeiboTopicLink')->where($map)->page($aPage)->limit(10)->getField('weibo_id',true);
                break;
            case 'user':
                $aUid = POST_I('uid','intval',0);
                $this->ajaxError(array('uid'=>$aUid));
                $map['uid'] = $aUid;
                break;
        }
        if($type != 'crowd'){   //屏蔽私密圈子的微博
            $invisibleList = D('Weibo/WeiboCrowd')->getInvisible();
            if (!empty($invisibleList)) {
                $invisible = array_column($invisibleList,'id');
                $map['crowd_id'] = array('not in',$invisible);
            }
        }
        $weiboList = $type == 'topic'?$weiboList: $weiboModel->where($map)->order($order)->page($aPage,10)->select();
        //获取每个微博详情
        foreach ($weiboList as &$weibo) {
            $weibo = $weiboModel->getWeibo($weibo,$mid);
        }
        unset($weibo);
        //返回微博列表
        $this->ajaxSuccess($weiboList);
    }

    //删除微博
    public function deleteWeibo()
    {
        $mid = $this->requireIsLogin();
        $aId = I('get.id', 0, 'intval');
        if (empty($aId)) {
            $this->apiError('参数错误');
        }
        $weiboModel = D('Api/Weibo');
        //从数据库中删除微博、以及附属评论
        $result = $weiboModel->where(array('id' => $aId, 'status' => 1))->find();
        if (!$result) {
            $this->apiError('找不到此微博');
        }
        //权限判断
        $this->ApiCheckAuth('Weibo/Index/doDelWeibo', $result['uid'], '您无删除微博评论权限。');

        $res = $weiboModel->delWeibo($aId,$mid);
        if (!$res) {
            $this->apiError('删除失败');
        } else {
            M('WeiboComment')->where(array('weibo_id' => $aId))->setField('status', -1);
            action_log('del_weibo', 'weibo', $aId, $mid);
            $this->apiSuccess('删除成功');
        }
    }

    //微博评论功能
    public function sendComment()
    {
        $mid = $this->requireIsLogin();
        $aContent = I_POST('content', 'text'); //说点什么的内容
        $aWeiboId = I_POST('weibo_id', 'intval'); //要评论的微博的ID
        $aCommentId = I_POST('to_comment_id', 'intval');
        $weiboModel = D('Api/Weibo');
        if (empty($aContent)) {
            $this->apiError('评论内容不能为空。');
        }

        $this->ApiCheckAuth('Weibo/Index/doComment', -1, '您无微博评论权限。');
        $return = check_action_limit('add_weibo_comment', 'weibo_comment', 0, $mid, true); //行为限制
        if ($return && !$return['state']) {
            $this->apiError($return['info']);
        }

        $comment_id = $weiboModel->sendComment($aWeiboId, $aContent, $aCommentId, $mid); //发布评论
        if (!$comment_id) {
            $this->apiError('添加数据库失败！');
        }
        D('Weibo/WeiboCache')->cleanCache($aWeiboId);
        //行为日志
        action_log('add_weibo_comment', 'weibo_comment', $comment_id, $mid);

        //通知微博作者
        $weibo = $weiboModel->getWeibo($aWeiboId,$mid);
        $weiboModel->sendCommentMessage($weibo['uid'], $aWeiboId, "评论内容：$aContent", $mid);


        //通知回复的人

        $toComment = $weiboModel->getComment($aCommentId);
        if ($toComment['uid'] != $weibo['uid']) {
            $weiboModel->sendCommentMessage($toComment['uid'], $aWeiboId, "回复内容：$aContent", $mid);
        }
        $userList = get_at_uids($aContent);
        $weiboModel->sendAtMessage($userList, $aWeiboId, $aContent, $mid);

        //推送功能
        $userList = array_merge(array($weibo['uid']),$userList,array($toComment['uid']));
        $userList = array_filter($userList);
        $pushData = array('mod'=>'weibo','type' => 'os_reply','arg' => $weibo['id'],'content' => mb_substr(fmat_at_text($aContent),0,50));
//        $userList = array(168,7082);
        igetuiPush($userList,$pushData);

        // 返回数据给客户端
        $comment = $weiboModel->getComment($comment_id);
        $this->apiSuccess('返回成功。', $comment);
    }

    /*删除评论*/
    public function deleteComment()
    {
        $aCommentId = I('get.id', 'intval');

        $mid = $this->requireIsLogin();
        $weiboModel = D('Api/Weibo');
        $comment = $weiboModel->getComment($aCommentId);

        // 权限判断
        $this->ApiCheckAuth('Weibo/Index/doDelComment', $comment['uid'], '您无删除微博评论权限。');

        //$result = D('Weibo/WeiboComment')->deleteComment($aCommentId);
        $result=M('weibo_comment')->where(array('id'=>$aCommentId))->setField('status',-1);
        M('Weibo')->where(array('id' => $comment['weibo_id']))->setDec('comment_count');
        S('weibo_' . $comment['weibo_id'], null);
        if (!$result) {
            $this->apiError('删除失败');
        }
        action_log('del_weibo_comment', 'weibo_comment', $aCommentId, $mid);
        $this->apiSuccess('删除成功');

    }

    /**获取微博话题列表
     * @author 胡佳雨修改 <hjy@ourstu.com>.
     */
    public function getTopicList()
    {
        $aType = POST_I('type','','week');
        $aPage = POST_I('page','intval',1);

        if($aType == 'hot'){
            $topicList = D('Weibo/Topic')->getHotTopicList();
        }else{
            $map['status'] = 1;
            $time = $aType == 'day'? 24: 24*7;
            $res = D('Weibo/Topic')->getHot($time, 10, $aPage);
            $topicList = $res[0];
        }
        foreach($topicList as &$topic){
            $topic = D('Api/Weibo')->dealTopic($topic);
        }
        unset($topic);
        $this->ajaxSuccess($topicList);
    }

    /**获取单个话题数据
     * @author 胡佳雨修改 <hjy@ourstu.com>.
     */
    public function getTopic()
    {
        $aId = POST_I('id','intval');
        $this->ajaxError(array('id'=>$aId));
        $topic = M('WeiboTopic')->where(array('status'=>1,'id'=>$aId))->find();
        $topic = D('Api/weibo')->dealTopic($topic);
        $this->apiSuccess('返回成功',$topic);
    }

    /**
     * @author 胡佳雨修改
     * @deprecated 1.6.7版本以后放弃使用
     */
    public function getTopicDetail()
    {
        $mid = $this->isLogin();
        $aPage = I_POST('page','intval');
        if (empty($aPage)) {
            $aPage = 1;
        }
        $aId = I('get.id','intval');
        $topic=D('Weibo/weiboTopic')->where(array('status'=>1,'id'=>$aId))->find();
        D('Weibo/weiboTopic')->where(array('status'=>1,'id'=>$aId))->setInc('read_count');
        if(!$topic){
            $this->apiError('这个话题可能已被删除或屏蔽');
        }

        $map['content'] = array('like', "%#{$topic['name']}#%");
        $map['status']=1;
        $weiboModel = D('Api/Weibo');
        $weibos = $weiboModel->getList(array('field' => 'id', 'order' => 'create_time desc', 'page' => $aPage, 'where' => $map));
        foreach ($weibos as &$e) {
            $e = $weiboModel->getWeibo($e,$mid);
        }
        unset($e);
        $this->apiSuccess('返回成功',$weibos);
    }

    public function getComment()
    {
        $aId = I('get.id', 'intval');
        $aPage = I_POST('page', 'intval');
        if (empty($aPage)) {
            $aPage = 1;
        }
        $order = modC('COMMENT_ORDER', 0, 'WEIBO') == 1 ? 'create_time asc' : 'create_time desc';
        $weiboModel = D('Api/Weibo');
        $weiboCommentModel = D('Api/WeiboComment');
        $Comment = $weiboCommentModel->getList(array('field' => 'id', 'where' => array('status' => 1, 'weibo_id' => $aId), 'order' =>$order, 'page' => $aPage));

        foreach ($Comment as &$e) {
            $e = $weiboModel->getComment($e);
        }
        unset($e);
        //返回微博列表
        $this->apiSuccess('获取成功', $Comment);
    }

    /*转发微博，需要登录*/
    public function sendRepost()
    {
        $mid = $this->requireIsLogin();
        $aContent = I_POST('content', 'text'); //说点什么的内容
        $aSourceId = I_POST('source_id', 'intval'); //获取该微博源ID
        $aWeiboId = I_POST('weibo_id', 'intval'); //要转发的微博的ID
        $aBeComment = I_POST('becomment', 0, 'intval');
        $aFrom = I_POST('from', 'text');
        $aFrom = get_from($aFrom);
        $aType = 'repost';

        if (empty($aContent)) {
            $this->apiError('转发内容不能为空');
        }
        if(strlen($aContent)>modC('WEIBO_NUM', 140, 'WEIBO')){
            $this->apiError('微博内容过长');
        }

        $this->ApiCheckAuth('Weibo/Index/doSendRepost', -1, '您无微博转发权限。');

        $return = check_action_limit('add_weibo', 'weibo', 0, $mid, true);
        if ($return && !$return['state']) {
            $this->apiError($return['info']);
        }
        $weiboModel = D('Api/Weibo');

        $feed_data = array();
        $sourse = $weiboModel->getWeibo($aSourceId,$mid);

        $feed_data['source'] = $sourse;
        $feed_data['sourceId'] = $aSourceId;
        //发送微博
        $data = array('from' => $aFrom, 'uid' => $mid, 'create_time' => time(), 'type' => $aType, 'status' => 1, 'content' => $aContent, 'data' => serialize($feed_data),);
        $weibo_id = $weiboModel->sendWeibo($data);

        if ($aBeComment) {
            $weiboModel->sendComment($aWeiboId, $aContent, 0, $mid);
        }
        if ($weibo_id) {
            $weiboModel->where(array('id' => $aSourceId))->setInc('repost_count');
            $aWeiboId != $aSourceId && $weiboModel->where(array('id' => $aWeiboId))->setInc('repost_count');
            S('weibo_' . $aWeiboId, null);
            S('weibo_' . $aSourceId, null);
            D('Weibo/WeiboCache')->cleanCache($aWeiboId);
            D('Weibo/WeiboCache')->cleanCache($aSourceId);
        }
        $user = query_user(array('nickname'), $mid);
        $toUid = $weiboModel->where(array('id' => $aWeiboId))->getField('uid');
        D('Common/Message')->sendMessage($toUid, $user['nickname'] . '转发了您的微博！', '转发提醒', 'Weibo/Index/weiboDetail', array('id' => $weibo_id), $mid, 1);

        //微博转发推送
        $pushData['content'] = 'weibo';
        $pushData['payload'] = array('type' => 'os_forward','info' => $weibo_id,'content' => $aContent);
        igetuiPush(array($toUid),$pushData);     //使用的单推方法


        $weibo = $weiboModel->getWeibo($weibo_id,$mid);

        //返回成功结果
        $this->apiSuccess('转发成功', $weibo);
    }

    //微博置顶操作
    public function setTop()
    {
        $aId = I('get.id', 'intval');
        $mid = $this->requireIsLogin();
        $this->ApiCheckAuth('Weibo/Index/setTop', -1, '置顶失败，您不具备管理权限。');

        $weiboModel = D('Weibo');
        $weibo = $weiboModel->find($aId);
        if (!$weibo) {
            $this->apiError('置顶失败，微博不存在。');
        }
        if ($weibo['is_top'] == 0) {
            if ($weiboModel->where(array('id' => $aId))->setField('is_top', 1)) {
                action_log('set_weibo_top', 'weibo', $aId, $mid);
                S('weibo_' . $aId, null);

                $this->apiSuccess('置顶成功。');
            } else {
                $this->apiError('置顶失败。');
            };
        } else {
            if ($weiboModel->where(array('id' => $aId))->setField('is_top', 0)) {
                action_log('set_weibo_down', 'weibo', $aId, $mid);
                S('api_weibo_' . $aId, null);
                $this->apiSuccess('取消置顶成功。');
            } else {
                $this->apiError('取消置顶失败。');
            };
        }
    }

    /**圈子的加入\退出操作
     * @author 胡佳雨 <hjy@ourstu.com>.
     */
    public function handleCrowd(){
        $mid = $this->requireIsLogin();
        $crowdId = POST_I('id', 'intval', 0);
        $handle = POST_I('flag', 'text', 'join');
        $this->ajaxError(array('id'=>$crowdId,'flag'=>$handle));

        $weiboMod = D('Api/weibo');
        if (!$weiboMod->getCrowd($crowdId)) {
            $this->apiError('圈子不存在');
        }
        if($handle == 'join'){
            if (D('Weibo/WeiboCrowdMember')->getIsJoin($mid,$crowdId)) {
                $this->apiError('已加入圈子');
            }
            $num = count(D('Weibo/WeiboCrowdMember')->getUserJoin($mid));
            if ($num >= modC('JOIN_CROWD_NUM', '5', 'Weibo')) {
                $this->apiError('超出圈子人数上限');
            }
            $data['crowd_id'] = $crowdId;
            $data['uid'] = $mid;

            $crowd = $weiboMod->getCrowd($crowdId);
            if ($crowd['type'] == 1) {
                // 圈子为私有的。
                $data['status'] = 0;
            } else {
                // 圈子为公共的
                $data['status'] = 1;
            }
            $res = D('Weibo/WeiboCrowdMember')->addMember($data);
            if ($res) {
                if ($crowd['type'] == 1) {
                    S('crowd_joined_' . $mid, null);
                    send_message($crowd['uid'], '加入圈子审核', get_nickname($mid) . '请求加入圈子' . "【{$crowd['title']}】", 'Weibo/Crowd/crowd', array('id' => $crowdId, 'type' => 'check'), $mid, 'Weibo_crowd');
                    $info = '加入圈子成功，等待管理员审核！';
                }else{
                    S('crowd_joined_' . $mid, null);
                    D('Weibo/WeiboCrowd')->changeCrowdNum($crowdId);
                    send_message($crowd['uid'], '加入圈子提醒', get_nickname($mid) . '已加入圈子' . "【{$crowd['title']}】", 'Weibo/Crowd/crowd', array('id' => $crowdId, 'type' => 'member'), $mid, 'Weibo_crowd');
                    $info = '加入圈子成功';
                }
                $this->apiSuccess($info);
            }
            $this->apiError('操作失败');
        }elseif($handle == 'quit'){
            if (!D('Weibo/WeiboCrowdMember')->getIsJoin($mid,$crowdId)) {
                $this->apiError('还未加入圈子');
            }
            $list = D('Weibo/WeiboCrowdMember')->getMycreateCrowd($mid);
            $ids = getSubByKey($list, 'crowd_id');
            if (in_array($crowdId, $ids)) {
                $this->apiError('圈子创始人只能解散圈子');
            }
            $data['crowd_id'] = $crowdId;
            $data['uid'] = $mid;
            $crowd = $weiboMod->getCrowd($crowdId);
            $res = D('Weibo/WeiboCrowdMember')->delMember(array('crowd_id' => $crowdId, 'uid' => $mid));
            if ($res) {
                if ($crowd['member_count'] > 0) {
                    D('Weibo/WeiboCrowd')->changeCrowdNum($crowdId, 'member', 'dec');
                }
                S('crowd_joined_' . $mid, null);
                send_message($crowd['uid'], '退出圈子提醒', get_nickname($mid) . '已退出圈子' . "【{$crowd['title']}】", 'Ucenter/Index/information', array('uid' => $mid), $mid, 'Weibo_crowd');
                $this->apiSuccess('退出圈子成功');
            }
            $this->apiError('操作失败');
        }
        $this->apiError('非法操作 - '.$handle);
    }

    /**获取圈子成员列表(区分私有审核通过和非通过)
     * @author 胡佳雨 <hjy@ourstu.com>.
     */
    public function getCrowdMember()
    {
        $crowdId = POST_I('id','intval',0);
        $this->ajaxError(array('id'=>$crowdId));
        $aFlag = POST_I('flag','text');
        $aPage = POST_I('page','intval',1);
        $map['crowd_id'] = $crowdId;    //所有成员
        $map['status'] = 1;
        if($aFlag == 'normal'){     //普通成员
            $map['position'] = 1;
        }elseif($aFlag == 'check'){     //待审核成员
            $map['status'] = 0;
        }elseif($aFlag == 'admin'){     //管理员
            $map['position'] = array('GT',1);
        }
        $crowdMembers = M('WeiboCrowdMember')->where($map)->page($aPage,10)->select();
        foreach($crowdMembers as &$member){
            $member['user'] = D('Api/User')->getUser($member['uid']);
        }
        unset($member);
        $this->ajaxSuccess($crowdMembers);
    }

    /**圈子成员管理(邀请\移除 需管理员权限)
     * @author 胡佳雨 <hjy@ourstu.com>.
     */
    public function manageCrowdMember()
    {
        $mid = $this->requireIsLogin();
        $manageFlag = POST_I('flag','text','add');
        $crowdId = POST_I('id','intval',0);
        $aUid = POST_I('uid','intval',0);
        $this->ajaxError(array('crowd_id'=>$crowdId,'uid'=>$aUid));

        $weiboMod = D('Api/weibo');
        $midPermis = $weiboMod->getCrowdPerms($crowdId,$mid);
        if($midPermis != 3 && $midPermis != 2){
            $this->apiError('您不是管理员,无法操作');
        }
        $memberMod = M('WeiboCrowdMember');
        $map['uid'] = $aUid;
        $map['crowd_id'] = $crowdId;
        $data = $memberMod->where($map)->find();
        $crowdInfo = $weiboMod->getCrowd($crowdId);
        if($manageFlag == 'add'){
            if($data && $data['status'] != 2){
                $this->apiError('操作失败');
            }else{
                if(empty($data)){
                    $map['status'] = 2;
                    D('Weibo/WeiboCrowdMember')->addMember($map);
                }
                send_message($aUid, '邀请加入圈子', get_nickname($mid).' 邀请您加入圈子' . "【{$crowdInfo['title']}】", 'Weibo/Crowd/crowd', array('id' => $crowdId, 'type' => 'member','action'=>'invite'), $mid, 'Weibo_crowd');
                $this->apiSuccess('操作成功');
            }
        }elseif($manageFlag == 'remove'){
            if(!$data){
                $this->apiError('操作失败,目标不存在');
            }
            if ($data['position'] == 3) {
                $this->apiError('操作失败,无法删除创建者');
            }
            if($data['position'] == $midPermis){
                $this->apiError('操作失败,没有权限删除同级管理员');
            }
            $res = $memberMod->where($map)->delete();
            if($res){
                D('Weibo/WeiboCrowd')->changeCrowdNum($crowdId,'member','dec');
                S('crowd_joined_'.$aUid,null);
                send_message($aUid, '您已被移出圈子', get_nickname($aUid) . '被管理员移出圈子' . "【{$crowdInfo['title']}】", 'Weibo/Crowd/crowd', array('id' => $crowdId, 'type' => 'member'), $mid, 'Weibo_crowd');
                $this->apiSuccess('操作成功');
            }
        }else{
            $this->apiError('非法操作 - '.$manageFlag);
        }
        $this->apiError('操作失败');
    }

    /**处理入圈邀请
     * @author 胡佳雨 <hjy@ourstu.com>.
     */
    public function checkInvite(){
        $mid = $this->requireIsLogin();
        $aFlag = POST_I('flag','intval',0);
        $crowdId = POST_I('id','intval',0);

        $weiboMod = D('Api/weibo');
        $memberMod = M('WeiboCrowdMember');
        $map['crowd_id'] = $crowdId;
        $map['uid'] = $mid;
        $map['status'] = 2;
        $mbInfo = $memberMod->where($map)->find();
        if($mbInfo){
            $crowdInfo = $weiboMod->getCrowd($crowdId);
            if($aFlag){
                $memberMod->where($map)->setField('status',1);
                S('crowd_joined_' . $mid, null);
                D('Weibo/WeiboCrowd')->changeCrowdNum($crowdId,'member');
                send_message($crowdInfo['uid'], '新成员加入', get_nickname($mid) . ' 接受邀请加入圈子' . "【{$crowdInfo['title']}】", 'Weibo/Crowd/crowd', array('id' => $crowdId, 'type' => 'member','action'=>'invite_check'), $mid, 'Weibo_crowd');
            }else{
                send_message($crowdInfo['uid'], '拒绝邀请', get_nickname($mid) . ' 拒绝加入圈子' . "【{$crowdInfo['title']}】", 'Weibo/Crowd/crowd', array('id' => $crowdId, 'type' => 'member','action'=>'invite_check'), $mid, 'Weibo_crowd');
                $memberMod->where($map)->delete();
            }
            $this->apiSuccess('操作成功');
        }
        $this->apiError('操作失败');
    }

    /**提升/撤销圈子管理员（需创建者权限）
     * @author 胡佳雨 <hjy@ourstu.com>.
     */
    public function setCrowdAdmin()
    {
        $mid = $this->requireIsLogin();
        $aFlag = POST_I('flag','intval',0);
        $crowdId = POST_I('id','intval',0);
        $aUid = POST_I('uid','intval',0);
        $this->ajaxError(array('crowd_id'=>$crowdId,'uid'=>$aUid));
        $weiboMod = D('Api/weibo');
        $midPermis = $weiboMod->getCrowdPerms($crowdId,$mid);
        if($midPermis != 3){
            $this->apiError('操作失败,没有操作权限');
        }
        if($mid == $aUid){
            $this->apiError('操作失败,无法操作自己');
        }
        if(is_administrator($aUid)){
            $this->apiError('操作失败,超级管理员拥有所有权限，不建议使用');
        }
        $tagPms = $weiboMod->getCrowdPerms($crowdId,$aUid);
        $map['crowd_id'] = $crowdId;
        $map['uid'] = $aUid;
        $map['status'] = 1;
        $crowdInfo = $weiboMod->getCrowd($crowdId);
        if($aFlag){ //提升
            if($tagPms == 1){
                $res = $weiboMod->setCrowdPerms($crowdId,$aUid,2);
                send_message($aUid, '圈子动态', '您已成为圈子'."【{$crowdInfo['title']}】"."的管理员",  'Weibo/Crowd/crowd', array('id' => $crowdId, 'type' => 'member'), $mid, 'Weibo_crowd');
            }
        }else{  //撤销
            if($tagPms == 2){
                $res = $weiboMod->setCrowdPerms($crowdId,$aUid,1);
                send_message($aUid, '圈子动态', '您已被取消圈子'. "【{$crowdInfo['title']}】"."的管理员",  'Weibo/Crowd/crowd', array('id' => $crowdId, 'type' => 'member'), $mid, 'Weibo_crowd');
            }
        }
        if($res){
            $this->apiSuccess('操作成功');
        }
        $this->apiError('操作失败');
    }

    /**圈子所有权转移（需创建者权限）
     * @author 胡佳雨 <hjy@ourstu.com>.
     */
    public function setCrowdAuth(){
        $mid = $this->requireIsLogin();
        $crowdId = POST_I('id','intval',0);
        $aUid = POST_I('uid','intval',0);
        $this->ajaxError(array('crowd_id'=>$crowdId,'uid'=>$aUid));
        $weiboMod = D('Api/weibo');
        if(is_administrator($aUid)){
            $this->apiError('操作失败,超级管理员拥有所有权限，无意义操作');
        }
        $midPermis = $weiboMod->getCrowdPerms($crowdId,$mid);
        if($midPermis != 3){
            $this->apiError('操作失败,没有操作权限');
        }
        if($mid == $aUid){
            $this->apiError('操作失败,无意义的操作');
        }
        $tagPms = $weiboMod->getCrowdPerms($crowdId,$aUid);
        if($tagPms && $tagPms != 3){
            $res = $weiboMod->setCrowdPerms($crowdId,$aUid,3);
            if($res){
                $crowdInfo = $weiboMod->getCrowd($crowdId);
                $weiboMod->setCrowdPerms($crowdId,$crowdInfo['uid'],1);
                M('WeiboCrowd')->where(array('id'=>$crowdId))->setField('uid',$aUid);
                send_message($aUid, '圈子动态', get_nickname($mid).'把圈子'. "【{$crowdInfo['title']}】"."转移给您",  'Weibo/Crowd/crowd', array('id' => $crowdId, 'type' => 'member'), $mid, 'Weibo_crowd');
                $this->apiSuccess('操作成功');
            }
        }
        $this->apiError('操作失败');
    }

    /**调整圈子(需管理员权限)
     * @author 胡佳雨 <hjy@ourstu.com>.
     */
    public function modifyCrowd()
    {
        $mid = $this->requireIsLogin();
        $crowdId = POST_I('id','intval',0);
        $this->ajaxError(array('id'=>$crowdId));
        $aTitle = POST_I('title');
        $aLogo = POST_I('logo');
        $aIntro = POST_I('intro');
        $aNotice = POST_I('notice');
        $aCrowdType = POST_I('type','intval');
        $aJoinType = POST_I('join_type','intval');
        $aPay = POST_I('pay','intval');
        $aPayType = POST_I('pay_type','intval');
        $aVisible = POST_I('visible','intval');
        $aAllow = POST_I('allow','intval');

        $weiboMod = D('Api/weibo');
        $crowdInfo = $weiboMod->getCrowd($crowdId);
        if(!$crowdInfo || $crowdInfo['status'] != 1){
            $this->apiError('操作失败,目标状态异常');
        }
        $midPermis = $weiboMod->getCrowdPerms($crowdId,$mid);
        if($midPermis != 3 && $midPermis != 2){
            $this->apiError('您不是管理员,无法操作');
        }
        if($aTitle && utf8_strlen($aTitle)> 20){
            $this->apiError('圈子名称不得多于20字');
        }
        if($aIntro && utf8_strlen($aIntro) > 100){
            $this->apiError('圈子介绍不得高于100字');
        }
        if($aNotice && utf8_strlen($aNotice)> 60){
            $this->apiError('圈子公告不得高于60字');
        }
        //写入数据库
        $crowdModel = M('WeiboCrowd');
        $data['id'] = $crowdId;
        $data['type'] = $crowdInfo['type'];
        if($aCrowdType){
            //圈子由私密变为公共时，需要处理待审核的成员
            if($data['type'] == 1 && $aCrowdType == 1){
                $checkUids = M('WeiboCrowdMember')->where(array('crowd_id'=>$crowdId,'status'=>0))->getField('uid',true);
                foreach($checkUids as $ckUid){
                    $res = D('Weibo/WeiboCrowdMember')->setStatus($ckUid, $crowdId, 1);
                    if($res){
                        D('Weibo/WeiboCrowd')->changeCrowdNum($crowdId);
                        S('crowd_joined_'.$ckUid,null);
                        send_message($ckUid, '您的加入圈子请求已通过', get_nickname($ckUid) . '加入' . "【{$crowdInfo['title']}】"."成功",  'Weibo/Crowd/crowd', array('id' => $crowdId, 'type' => 'member'), $mid, 'Weibo_crowd');
                    }
                }
            }
            $data['type'] = $aCrowdType == 1?0:1;
        }
        if($aAllow){
            $data['allow_user_post'] = $aAllow == 1?0:-1;
        }
        if ($data['type'] == 1) {
            if($aJoinType == 1){
                $data['pay_type'] = 0;
                $data['need_pay'] = 0;
            }else{
                $data['need_pay'] = $aPay?$aPay:$crowdInfo['need_pay'];
                $data['pay_type'] = $aPayType?$aPayType:$crowdInfo['pay_type'];
            }
            if($aVisible){
                $data['invisible'] = $aVisible == 1?0:1;
            }else{
                $data['invisible'] = $crowdInfo['invisible'];
            }
        } else {
            $data['pay_type'] = 0;
            $data['need_pay'] = 0;
            $data['invisible'] = 0;
        }
        $data['title'] = $aTitle?$aTitle:$crowdInfo['title'];
        $data['logo'] = $aLogo?$aLogo:$crowdInfo['logo'];
        $data['intro'] = $aIntro?$aIntro:$crowdInfo['intro'];
        $data['notice'] = $aNotice?$aNotice:$crowdInfo['notice'];
        $data = $crowdModel->create($data);
        $res = $crowdModel->where(array('id' => $crowdId))->save($data);
        if($res){
            S('crowd_by_' . $crowdId, null);
            $result = $weiboMod->getCrowd($crowdId,$mid,1);
            $this->apiSuccess('操作成功',$result);
        }
        $this->apiError('操作失败');
    }

    /**审核成员（针对私有圈子 需管理员权限）
     * @author 胡佳雨 <hjy@ourstu.com>.
     */
    public function checkCrowdMember()
    {
        $mid = $this->requireIsLogin();
        $crowdId = POST_I('id','intval',0);
        $aUid = POST_I('uid', 'intval');
        $aFlag = POST_I('flag', 'intval',0);
        $this->ajaxError(array('id'=>$crowdId,'uid'=>$aUid));
        $weiboMod = D('Api/weibo');
        $midPermis = $weiboMod->getCrowdPerms($crowdId,$mid);
        if($midPermis != 3 && $midPermis != 2){
            $this->apiError('您不是管理员,无法操作');
        }
        $crowd = $weiboMod->getCrowd($crowdId);
        $crowdMemberMod = D('Weibo/WeiboCrowdMember');
        if($crowdMemberMod->where(array('crowd_id'=>$crowdId,'uid'=>$aUid,'status'=>1))->find()){
            $this->apiError('操作失败，已通过审查');
        }
        if($aFlag){
            if ($crowd['need_pay'] > 0) {
                $money = query_user('score'.$crowd['pay_type'],$aUid);
                if ($money['score'.$crowd['pay_type']] > $crowd['need_pay']) {
                    D('Ucenter/Score')->setUserScore($aUid, $crowd['need_pay'], $crowd['pay_type'], 'dec', 'weibo');
                } else {
                    send_message($aUid, '余额不足', get_nickname($aUid) . $crowd['pay_type_title'].'余额不足,加入' . "【{$crowd['title']}】"."失败,请获得该类积分后再来",  'Weibo/Crowd/crowd', array('id' => $crowdId, 'type' => 'member'), is_login(), 'Weibo_crowd');
                    D('Weibo/WeiboCrowdMember')->delMember(array('crowd_id'=>$crowdId,'uid'=>$aUid));
                    $this->apiError('审核失败，该成员余额不足以支付入圈费！');
                }
            }
            $res = D('Weibo/WeiboCrowdMember')->setStatus($aUid, $crowdId, 1);
            if ($res) {
                D('Weibo/WeiboCrowd')->changeCrowdNum($crowdId);
                S('crowd_joined_'.$aUid,null);
                send_message($aUid, '您的加入圈子请求已通过', get_nickname($aUid) . '加入' . "【{$crowd['title']}】"."成功",  'Weibo/Crowd/crowd', array('id' => $crowdId, 'type' => 'member'), $mid, 'Weibo_crowd');
                $this->apiSuccess('操作成功');
            }
            $this->apiError('操作失败');
        }else{
            D('Weibo/WeiboCrowdMember')->delMember(array('crowd_id'=>$crowdId,'uid'=>$aUid));
            send_message($aUid, '您加入圈子的请求被拒绝', get_nickname($aUid) . '加入' . "【{$crowd['title']}】"."失败",  'Weibo/Crowd/crowd', array('id' => $crowdId, 'type' => 'member'), $mid, 'Weibo_crowd');
            $this->apiSuccess('操作成功');
        }
    }

    /**圈子修改贡献值（需管理员权限）
     * @author 胡佳雨 <hjy@ourstu.com>.
     */
    public function setCrowdContribution()
    {
        $mid = $this->requireIsLogin();
        $aCrowd = POST_I('id','intval',0);
        $aUid = POST_I('uid','intval',0);
        $aScore = POST_I('score','intval',0);
        $this->ajaxError(array('id'=>$aCrowd,'uid'=>$aUid,'score'));
        $midPermis = D('Api/Weibo')->getCrowdPerms($aCrowd,$mid);
        if($midPermis != 3 && $midPermis != 2){
            $this->apiError('您不是管理员,无法操作');
        }
        $res = D('Weibo/WeiboCrowdMember')->setContribution($aCrowd,$aUid,$aScore);
        if ($res) {
            $this->apiSuccess('操作成功');
        }
        $this->apiError('操作失败');
    }

    /**创建圈子
     * @author 胡佳雨 <hjy@ourstu.com>.
     */
    public function createCrowd()
    {
        $mid = $this->requireIsLogin();
        $aType = POST_I('type','intval',0);
        $aLogo = POST_I('logo','intval',0);
        $aTitle = POST_I('title');

        if(empty($aType)){
            $this->apiError('请选择圈子类型');
        }
        if(utf8_strlen($aTitle)<2 || utf8_strlen($aTitle)>10){
            $this->apiError('圈子标题字数需在2-10字内');
        }

        $checkState = modC('CREATE_CROWD_CHECK', '0');
        $status = 1;
        if ($checkState == 1) {
            $status = 2;
        }

        $model = M('WeiboCrowd');
        $data = array(
            'title' => $aTitle, 'create_time' => time(), 'status' => $status, 'logo' => $aLogo,
            'type_id' => $aType, 'type' => $aType, 'uid' => $mid
        );
        $data = $model->create($data);
        $res = $model->add($data);
        if (!$res) {
            $this->apiError('创建失败');
        }
        //添加创建者成员
        D('Weibo/WeiboCrowdMember')->addMember(array('uid' => $mid, 'crowd_id' => $res, 'status' => 1, 'position' => 3));
        D('Weibo/WeiboCrowd')->changeCrowdNum($res, 'member', 'inc');
        $temp = D('Api/weibo')->getCrowd($res,$mid,1);
        if ($checkState == 1) {
            send_message_without_check_self($mid, '等待圈子审核', "您创建的圈子" . "【{$data['title']}】正在审核中,请等待", 'Weibo/Crowd/index', '', $mid, 'Weibo_crowd');
            send_message(array_column(AuthGroupModel::memberInGroup(6),'uid'), '等待圈子审核', get_nickname($mid) . "创建了圈子【{$data['title']}】，请审核", 'Admin/Weibo/Crowd', array('status' => 2), $mid, 'Weibo_crowd');
            $message = '请等待审核';
        }
        //显示成功消息
        S('crowd_create_by_' . $mid, null);
        S('crowd_joined_' . $mid, null);
        $this->apiSuccess($temp,'创建成功'.$message);
    }

    /**解散圈子
     * @author 胡佳雨 <hjy@ourstu.com>.
     */
    public function delCrowd()
    {
        $mid = $this->requireIsLogin();
        $aCrowdId = POST_I('id','intval',0);
        $aVerify = POST_I('verify');
        $this->ajaxError(array('id'=>$aCrowdId,'verify'=>$aVerify));
        $weiboMod =  D('Api/Weibo');
        $midPermis = $weiboMod->getCrowdPerms($aCrowdId,$mid);
        if($midPermis != 3){
            $this->apiError('操作失败，您不是创建者');
        }
        $crowdInfo =$weiboMod->getCrowd($aCrowdId);
        if($aVerify != $crowdInfo['title']){
            $this->apiError('操作失败，验证错误');
        }
        $userArray = M('WeiboCrowdMember')->where(array('crowd_id'=>$aCrowdId,'status'=>1))->field('uid')->select();
        $userArray = array_column($userArray,'uid');
        $crowdRes = D('Weibo/WeiboCrowd')->delCrowd($aCrowdId);

        $memberRes = D('Weibo/WeiboCrowdMember')->delMember(array('crowd_id'=>$aCrowdId));
        if($crowdRes && $memberRes){
            M('Weibo')->where(array('crowd_id'=>$aCrowdId,'status'=>1))->setField(array('crowd_id'=>0));
            S('crowd_joined_'.$mid,null);
            S('crowd_by_'.$aCrowdId,null);
            send_message($userArray, '您加入的圈子已被解散',  '您加入的圈子' . "【{$crowdInfo['title']}】"."已被管理员解散，您已自动退出该圈子", 'Weibo/Crowd/index', '', $mid, 'Weibo_crowd');
            $this->apiSuccess('操作成功');
        } else {
            $this->apiError('操作失败');
        }
    }
}