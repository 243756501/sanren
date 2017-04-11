<?php


namespace Api\Controller;

class  MessageController extends BaseController
{

    /**获取消息分类
     * @author 胡佳雨 <hjy@ourstu.com>.
     */
    public function getMessageType(){
        $mid = $this->requireIsLogin();
        $messageModel = D('Common/Message');
        $messageTypeList = $messageModel->getMyMessageSessionList($mid);
        foreach($messageTypeList as &$value){
            $value['detail']['logo'] = get_attach_path($value['detail']['logo']);
        }
        $this->apiSuccess($messageTypeList);
    }

    /**获取消息列表
     * @author 胡佳雨 <hjy@ourstu.com>.
     */
    public function getMessageList(){

        $mid = $this->requireIsLogin();
        $messageType = POST_I('type','text','');
        $aPage = POST_I('page','intval',1);
        $messageMod = D('Api/Message');
        $msgMod = M('Message');
        $map['to_uid'] = $mid;
        if($messageType){
            $messageType = ucfirst($messageType);
            $map['type'] = $messageType;
        }
        $map['status'] = 1;
        $messages = $msgMod->where($map)->order('id desc')->page($aPage,10)->select();
        foreach($messages as &$messageInfo){
            $messageInfo = $messageMod->dealMessage($messageInfo);
        }
        unset($messageInfo);
        //设置未读消息为已读
        D('Common/Message')->setAllReaded($mid,$messageType);
        if($messageType =="Common_announce"){//对于公告消息，设置公告送达;没有新消息时，不做该步骤
            $map['is_read'] = 0;
            $unreadCount =  $msgMod->where($map)->count();
            if($unreadCount){
                D('Common/AnnounceArrive')->setAllArrive($mid);
            }
        }
        $this->apiSuccess($messages);
    }

    /**
     * 设置已读
     */
    public function setAllReaded()
    {
        $uid = $this->requireIsLogin();
        $id = I_POST('id','intval');
        if($id){
            $messages = D('message')->where(array('to_uid='=> $uid, 'is_read'=>0 ,'id'=>$id))->setField('is_read', 1);
        }else{

            $messages = D('message')->where(array('to_uid='=> $uid, 'is_read'=>0))->setField('is_read', 1);
        }

        if ($messages) {
            $this->apiSuccess('设置成功');
        } else {
            $this->apiError('设置失败');
        }
    }
}