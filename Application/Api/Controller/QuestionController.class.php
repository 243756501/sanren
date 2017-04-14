<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015-8-21
 * Time: 10:18:20
 */

namespace Api\Controller;


class QuestionController extends BaseController
{
    private $model;
    private $mid;

    public function __construct()
    {
        parent::__construct();
        $this->model = D('Api/Question');
        $this->mid = $this->isLogin();
    }
    /**获取问答分类
     * @author 胡佳雨修改 <hjy@ourstu.com>.
     */
    public function getQuestionType()
    {
        $aTypeId = POST_I('id','intval',0);
        $condition['status'] = 1;
        $typeMod = M('QuestionCategory');
        if ($aTypeId) {
            $condition['id'] = $aTypeId;
            $category = $typeMod->where($condition)->find();
            if($category){
                $category['child'] = $typeMod->where(array('status'=>1,'pid'=>$aTypeId))->order('sort asc')->select();
            }
        } else{
            $condition['pid'] = 0;
            $category = $typeMod->where($condition)->order('sort asc')->select();
            foreach ($category as &$value) {
                $condition['pid'] = $value['id'];
                $value['child'] = $typeMod->where($condition)->order('sort asc')->select();
            }
            unset($value);
        }
        $this->ajaxSuccess($category);
    }

    /** 获取问题列表
     * @author 胡佳雨修改 <hjy@ourstu.com>.
     */
    public function getQuestionList()
    {
        $aPage = POST_I('page', 'intval', 1);
        $aType = POST_I('type', 'text');
        $aCate = POST_I('id', 'intval');
        $where['status'] = 1;
        $order = 'create_time desc';
        if (empty($aCate)) {
            switch ($aType) {
                case 'wait':
                    $where['best_answer'] = 0;
                    $where['update_time'] = array('gt', get_time_ago('month', 1));
                    break;
                case 'hot':
                    $order = 'answer_num desc';
                    $where['create_time'] = array('gt', time() - 2592000);//一月内
                    break;
                default:
                    break;
            }
        } else {
            $first = M('QuestionCategory')->where(array('id' => $aCate, 'status' => 1))->find();
            if ($first['pid'] == 0) {
                $second = M('QuestionCategory')->where(array('pid' => $first['id'], 'status' => 1))->field('id')->select();
                $ids = array();
                foreach ($second as $key => $value) {
                    $ids[$key] = $value['id'];
                }
                unset($value);
                array_push($ids, $first['id']);
                $where['category'] = array('in', $ids);
            } else {
                $where['category'] = $first['id'];
            }
        }
        $list = M('Question')->where($where)->order($order)->page($aPage)->limit(10)->select();
        foreach ($list as $key => &$qValue) {
            $qValue = $this->model->fmtQuestion($qValue);
        }
        unset($qValue);
        $this->ajaxSuccess($list);
    }

    /**获取某一用户的问题列表
     * @author 胡佳雨修改 <hjy@ourstu.com>.
     */
    public function getUserQuestion(){
        $aUid = POST_I('uid', 'intval');
        $this->ajaxError(array('uid'=>$aUid));
        $aPage = POST_I('page', 'intval',1);
        $where['status']=1;
        $where['uid']=$aUid;
        $list = M('Question')->where($where)->page($aPage)->order('update_time desc')->limit(10)->select();
        foreach ($list as $key => &$qValue) {
            $qValue = $this->model->fmtQuestion($qValue);
        }
        $this->ajaxSuccess($list);
    }

    /**获取问题详情
     * @author 胡佳雨修改 <hjy@ourstu.com>.
     */
    public function getQuestionDetail()
    {
        $aId = POST_I('id','intval');
        $this->ajaxError(array('id'=>$aId));
        $question = M('Question')->where(array('id'=>$aId))->find();
        if($question){
            $question = $this->model->fmtQuestion($question,true);
            $question['best_answer'] = $question['best_answer']? $this->model->getAnswer($question['best_answer'],false,$this->mid): null;
        }
        $this->ajaxSuccess($question);
    }


    /**获取问题答案列表
     * @author 胡佳雨修改 <hjy@ourstu.com>.
     */
    public function getAnswerList()
    {
        $aId = POST_I('id', 'intval');
        $this->ajaxError(array('id'=>$aId));
        $aPage = POST_I('page', 'intval',1);
        $order = 'support desc,create_time desc';
        $answerList = M('QuestionAnswer')->where(array('question_id'=>$aId,'status'=>1))->order($order)->page($aPage)->limit(10)->select();
        foreach ($answerList as &$answer) {
            $answer = $this->model->fmtAnswer($answer,false ,$this->mid);
        }
        unset($answer);
        $this->ajaxSuccess($answerList);
    }

    /**获取答案详情
     * @author 胡佳雨修改 <hjy@ourstu.com>.
     */
    public function getAnswerDetail()
    {
        $aId = POST_I('id', 'intval');
        $this->ajaxError(array('id'=>$aId));
        $answer = $this->model->getAnswer($aId,true, $this->mid);
        $this->ajaxSuccess($answer);
    }

    /**编辑或者发布答案
     * @author 胡佳雨修改 <hjy@ourstu.com>.
     */
    public function editAnswer()
    {
        $mid = $this->requireIsLogin();
        $aQuestionId = POST_I('question_id','intval');
        $aAnswerId = POST_I('answer_id', 'intval');
        $aContent = POST_I('content', 'filter_content');
        $this->ajaxError(array('question_id'=>$aQuestionId));
        $ANSWER_MIN_NUM = modC('QUESTION_ANSWER_MIN_NUM', 3, 'Question');
        if (mb_strlen($aContent, 'utf-8') < $ANSWER_MIN_NUM) {
            $this->apiError('回答内容不能少于' . $ANSWER_MIN_NUM . '个字！');
        }
        $data['question_id'] = $aQuestionId;
        $data['content'] = $aContent;

        if ($aAnswerId) {       //修改答案
            $now_answer = D('Question/QuestionAnswer')->where(array('id' => $aAnswerId, 'status' => 1))->find();
            $this->ApiCheckAuth('Question/Answer/edit', $now_answer['uid'], '没有编辑该答案的权限');
            $this->apiCheckActionLimit('edit_answer', 'question_answer', $now_answer['id'], $mid);
            $data['id'] = $aAnswerId;
        } else {        //新的回答
            $this->ApiCheckAuth('Question/Answer/add', -1, '没有回答的权限');
            $this->apiCheckActionLimit('add_answer', 'question_answer', 0, $mid);
        }
       $resAnswer = $this->model->editAnswerData($data,$mid);
        if ($resAnswer) {
            //处理@
            D('Common/ContentHandler')->handleAtWho($data['content'], 'Question/Index/detail#', array('id' => $data['question_id']));
            //发送消息
            $question = M('Question')->find($aQuestionId);
            D('Common/Message')->sendMessage($question['uid'], $resAnswer['user']['nickname'] . '回答了你的问题【' . $question['title'] . '】或编辑了 Ta 的答案，快去看看吧！', '问题被回答', 'Question/Index/detail', array('id' => $aQuestionId), $mid, 1);
            //Todo
            ////推送功能
        }
        $this->ajaxSuccess($resAnswer);
    }





    public function  insertQuestionComm($questionId,$commdata,$time)
    {
        $mid = getrandomId();
        $aQuestionId = $questionId;
        $atime=$time;
        $commdata=json_decode($commdata,true);
        $commdata=$commdata["data"];
        for($i=0;$i<count($commdata);$i++) {
            $atime=getrandomTime($atime);
            $aAnswerId = $commdata[$i]["answer_id"];
            if(!$aAnswerId)
            {
                $aAnswerId=0;
            }
            $aContent=$commdata[$i]["content"];
            $this->ajaxError(array('question_id'=>$aQuestionId));
            $ANSWER_MIN_NUM = modC('QUESTION_ANSWER_MIN_NUM', 3, 'Question');
            if (mb_strlen($aContent, 'utf-8') < $ANSWER_MIN_NUM) {
                $this->apiError('回答内容不能少于' . $ANSWER_MIN_NUM . '个字！');
            }
            $data['question_id'] = $aQuestionId;
            $data['content'] = $aContent;

            $resAnswer = $this->model->editAnswerData2($data,$mid);
        }
    }

    public function insertQuestion()
    {
        $mid=getrandomId();
        $acomm=I_POST('comment','text');
        $time=getrandomTime();
        $need_audit = modC('QUESTION_NEED_AUDIT', 1, 'Question');
        $aId = POST_I('id', 'intval');
        $data['title'] = POST_I('title', 'text');
        $this->ajaxError(array('title'=>$data['title']));
        $data['category'] = POST_I('category', 'intval',0);
        $data['description'] = POST_I('description', 'filter_content');
        $data['leixing'] = POST_I('score_type','intval',1);
        $data['score_num'] = POST_I('score_num','intval',0);
        $data['uid']=$mid;
        if( $data['score_num'] < 0){
            $this->apiError('悬赏必须大于0');
        }
        $data['status'] = $need_audit? 2: 1;
        $data['id'] = $aId;
        $question = $this->model->editQuestion2($data,$mid,$time);
        if($question) {
            $this->insertQuestionComm($question, $acomm, $time);
            $this->apiSuccess('发布成功,等待审核');
        }
    }

    /**发布或者编辑问题
     * @author 胡佳雨 <hjy@ourstu.com>.
     */
    public function sendQuestion()
    {
        $mid = $this->requireIsLogin();
        $need_audit = modC('QUESTION_NEED_AUDIT', 1, 'Question');
        $aId = POST_I('id', 'intval');
        $data['title'] = POST_I('title', 'text');
        $this->ajaxError(array('title'=>$data['title']));
        $data['category'] = POST_I('category', 'intval',0);
        $data['description'] = POST_I('description', 'filter_content');
        $data['leixing'] = POST_I('score_type','intval',1);
        $data['score_num'] = POST_I('score_num','intval',0);
        if( $data['score_num'] < 0){
            $this->apiError('悬赏必须大于0');
        }
        $user=M('Member')->where(array('uid'=>$mid))->find();
        if($user['score'.$data['leixing']] < $data['score_num']){
            $this->apiError('您的财富值不足。');
        }
        $data['status'] = $need_audit? 2: 1;
        if ($aId) {
            $tmpQuestion = M('Question')->find($aId);
            $this->ApiCheckAuth('Question/Index/edit', $tmpQuestion['uid'], '没有修改该问题权限！');
            $this->apiCheckActionLimit('edit_question', 'question', $aId, $mid);
            $data['id'] = $aId;
        } else {
            $data['uid'] = $mid;
            $this->ApiCheckAuth('Question/Index/add', -1, '没有发布问题的权限！');
            $this->apiCheckActionLimit('add_question', 'question', 0, $mid);
        }
        $question = $this->model->editQuestion($data,$mid);
        if($question){
            if($data['score_num']>0){
                D('Ucenter/Score')->setUserScore($mid,$data['score_num'],$data['leixing'] ,'dec');
            }
            if (D('Common/Module')->isInstalled('Weibo')) {//安装了微博模块
                //同步到微博
                $postUrl = U('Question/Index/detail', array('id' => $aId),true,true);
                D('Weibo/Weibo')->addWeibo("我问了一个问题【" . $data['title'] . "】：" . $postUrl);
            }
            $this->ajaxSuccess($question);
        }else{
            $this->apiSuccess('发布成功,等待审核');
        }
    }

    /**支持|反对 答案
     * @author 胡佳雨修改 <hjy@ourstu.com>.
     */
    public function evaluate()
    {
        $mid = $this->requireIsLogin();
        $aId = POST_I('id','intval');
        $this->ajaxError(array('id'=>$aId));
        $aType = I_POST('type', 'intval');//1 顶  0 踩
        $answer = M('QuestionAnswer')->where(array('id' => $aId, 'status' => 1))->find();
        if ($answer['uid'] == $mid) {
            $this->apiError('不能支持、反对自己的回答！');
        }
        if (!M('QuestionSupport')->where(array('uid' => $mid, 'tablename' => 'QuestionAnswer', 'row' => $aId))->count()) {
            $res = D('Question/QuestionSupport')->addData('QuestionAnswer',$aId,$aType);
            if ($res) {
                $result = D('Question/QuestionAnswer')->changeNum($aId, $aType);
            }
            if ($result) {
                //发送消息
                $question = $this->model->getQuestion($answer['question_id'],false);
                if ($aType) {
                    $tip = '用户' . $question['user']['nickname'] . '支持了你关于问题' . $question['title'] . '的回答。';
                    $title = '答案被支持';
                } else {
                    $tip = '你的关于问题' . $question['title'] . '的回答被某些不同意见的人反对了。';
                    $title = '答案被反对';
                }
                D('Common/Message')->sendMessage($answer['uid'], $title, $tip, 'Question/index/detail', array('id' => $answer['question_id']), 0, 1);
                //发送消息 end
                action_log('support_answer', 'question_answer_support', $aId, $mid);
                $this->apiSuccess('操作成功！');
            }
            $this->apiError('操作失败!');
        } else {
            $this->apiError('你已经支持或反对过该回答！');
        }
    }

    /**设置为最佳答案
     * @author 胡佳雨修改 <hjy@ourstu.com>.
     */
    public function setBestAnswer()
    {
        $mid = $this->requireIsLogin();
        $aQuestion = POST_I('question_id', 'intval');
        $aAnswerId = POST_I('answer_id', 'intval');
        $this->ajaxError(array('question_id'=>$aQuestion,'answer_id'=>$aAnswerId));
        $question = M('Question')->find($aQuestion);
        $answer =D('QuestionAnswer')->find($aAnswerId);
        if ($question && $answer && $aQuestion == $answer['question_id']) {
            $this->ApiCheckAuth('Question/Answer/setBest', $question['uid']);
            if ($question['best_answer']) {
                $this->apiError('已有最佳答案！不能重复设置');
            }
            $result = D('Question/Question')->editData(array('id' => $aQuestion, 'best_answer' => $aAnswerId));
            if ($result) {
                $tip = '在问题【' . $question['title'] . '】中你的回答被设为最佳答案。';
                D('Ucenter/Score')->setUserScore($answer['uid'],$question['score_num'],$question['leixing'] ,'inc');
                D('Common/Message')->sendMessage($answer['uid'], '答案被设为最佳答案', $tip, 'Question/index/detail', array('id' => $aQuestion), $mid, 1);
                //Todo
                //个推推送
                $this->apiSuccess('操作成功！');
            }
        }
        $this->apiError('操作失败！');
    }

    /**删除问题
     * @author 胡佳雨 <hjy@ourstu.com>.
     */
    public function delQuestion(){
        $this->requireIsLogin();
        $aQuestionId = POST_I('question_id', 'intval');
        $this->ajaxError(array('question_id'=>$aQuestionId));
        $questionInfo = M('Question')->where(array('id'=>$aQuestionId,'status'=>1))->find();
        if($questionInfo){
            $this->ApiCheckAuth('Question/Answer/delQuestion', $questionInfo['uid']);
            $rs = M('Question')->where(array('id'=>$aQuestionId))->save(array('status'=>-1));
            if($rs){
                $this->apiSuccess('操作成功！');
            }
        }
        $this->apiError('操作失败！');
    }

    /**删除答案
     * @author 胡佳雨 <hjy@ourstu.com>.
     */
    public function delAnswer(){
        $this->requireIsLogin();
        $aAnswerId = POST_I('answer_id', 'intval');
        $this->ajaxError(array('answer_id'=>$aAnswerId));
        $answerInfo = M('QuestionAnswer')->where(array('id'=>$aAnswerId,'status'=>1))->find();
        if($answerInfo){
            $this->ApiCheckAuth('Question/Answer/delAnswer', $answerInfo['uid']);
            $data['status'] = 0;
            $res = M('QuestionAnswer')->where(array('id'=>$aAnswerId))->save($data);
            if($res){
                $questionInfo = M('Question')->find($answerInfo['question_id']);
                if($questionInfo['answer_num'] > 0){
                    M('Question')->where(array('id'=>$questionInfo['id']))->setDec('answer_num',1);     //答案数目减少1,但是不能为负数
                }
                if($questionInfo['best_answer'] == $aAnswerId){         //如果删除的是最佳答案，则重置最佳答案为空
                    M('Question')->where(array('id'=>$questionInfo['id']))->setField(array('best_answer'=>0));
                }
                $this->apiSuccess('操作成功!');
            }
        }
        $this->apiError('操作失败!');
    }

    /**搜索问题
     * @author 胡佳雨 <hjy@ourstu.com>.
     */
    public function searchQuestion(){
        {
            $aKeywords = POST_I('key');
            if($aKeywords){
                $map['status'] = 1;
                $map['title'] = array('like', '%' . $aKeywords . '%');
                $list = M('Question')->where($map)->order('create_time desc')->limit(20)->select();
                foreach ($list as &$qValue) {
                    $qValue = $this->model->fmtQuestion($qValue);
                }
                unset($qValue);
                $this->ajaxSuccess($list);
            }
            $this->apiError('搜寻条件无意义');
        }
    }
}