<?php


namespace Api\Model;
use Common\Model\ContentHandlerModel;
use Think\Model;

class QuestionModel extends Model
{
    /**
     * @param $id
     * @param null $is_detail
     * @param int $mid 登陆者ID
     * @return mixed
     * @author 胡佳雨修改 <hjy@ourstu.com>.
     */
    public function getQuestion($id,$is_detail = null,$mid = 0)
    {
        $Question = D('Question/Question')->where(array('id' => $id,'status'=>1))->find();
        $Question = $this->fmtQuestion($Question,$is_detail,$mid);
        return $Question;
    }

    /**把数据库的原始信息做处理
     * @param array $question 问答ID
     * @param mixed $is_detail 标记符，用于识别需要的数据类型（简单还是详情）
     * @param int $mid 登陆者ID
     * @return mixed
     * @author 胡佳雨 <hjy@ourstu.com>.
     */
    public function fmtQuestion($question,$is_detail = null,$mid=0){
        if($question){
            if($is_detail){
                $question['description'] = fmatDtlContent($question['description']);
                $question['share_url']=U('question/index/detail',array('id'=>$question['id']),true,true);
            }else{
                $question['description'] = fmt_list_content($question['description']);
            }
            $question['user'] = D('Api/User')->getUser($question['uid'],$is_detail?1:0);
        }
        return $question;
    }

    /**获取答案信息
     * @param $id
     * @param mixed $is_detail 详细还是简单类型的数据
     * @param int $mid 登陆者ID
     * @return mixed
     * @author 胡佳雨修改 <hjy@ourstu.com>.
     */
    public function getAnswer($id,$is_detail = null,$mid=0){
        $answer = M('QuestionAnswer')->where(array('id' => $id,'status'=>1))->find();
        $answer = $this->fmtAnswer($answer,$is_detail,$mid);
        return $answer;
    }

    /**
     * @param array $answer
     * @param mixed $is_detail
     * @param int $mid
     * @return mixed
     * @author 胡佳雨 <hjy@ourstu.com>.
     */
    public function fmtAnswer($answer,$is_detail = null,$mid=0){
        if($answer){
            if($is_detail){
                $answer['content'] = fmatDtlContent($answer['content']);
            }else{
                $answer['content'] = fmt_list_content($answer['content']);
            }
            if($mid){
                $supportInfo = M('QuestionSupport')->where(array('uid'=>$mid,'row'=>$answer['id']))->find();
                $answer['is_support'] = $supportInfo? 1: 0;
            }
            $answer['user'] = D('Api/User')->getUser($answer['uid'],1);
        }
        return $answer;
    }

    /**保存答案到数据库，并返回答案信息
     * @param $data
     * @param $mid
     * @return mixed
     * @author 胡佳雨 <hjy@ourstu.com>.
     */
    public function editAnswerData($data,$mid=0)
    {
        $res = D('Question/QuestionAnswer')->editData($data);
        $answer = $this->getAnswer($data['id']?$data['id']:$res,false,$mid);
        return $answer;
    }

    /**发布/编辑问题
     * @param $data
     * @param $mid
     * @return bool|mixed
     * @author 胡佳雨修改 <hjy@ourstu.com>.
     */
    public function editQuestion($data,$mid=0)
    {
        $res = D('Question/Question')->editData($data);
        $question = $this->getQuestion($data['id']? $data['id']: $res,false,$mid);
        return $question;
    }

    /**
     * @param $id
     * @param int $type
     * @return mixed
     * @deprecated 1.6.4+
     */
    public function changeNum($id, $type = 1)
    {

        if ($type) {
            $field = 'support';
        } else {
            $field = 'oppose';
        }
        $res = D('Question/QuestionAnswer')->where(array('id' => $id))->setInc($field);
        return $res;
    }
}