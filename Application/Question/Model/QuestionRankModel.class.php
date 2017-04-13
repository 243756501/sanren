<?php
/**
 * Created by PhpStorm.
 * User: 王杰
 * Date: 2017/3/8
 * Time: 15:44
 */

namespace Question\Model;

use Think\Model;

class QuestionRankModel extends Model
{
    public function getRank($uid = 0)
    {
        $uid = empty($uid) ? is_login() : $uid;
        $tag = 'question_rank_'.$uid;
        $rank = S($tag);
        if (empty($rank)) {
            $rank = $this->where(array('uid'=>$uid))->find();
            S($tag,$rank,60*60*2);
        }
        return $rank;
    }

    public function addData($data = array())
    {
        $res = $this->add($data);
        return $res;
    }

}