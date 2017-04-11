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

class UserModel extends Model
{
    /**获取用户不同详细程度的信息
     * @param String $uid 目标用户uid
     * @param int $type 所需要信息的详细程度 固定参数0,1....,详细程度依次增加（后期可以按需扩展）
     * @param array $afield 扩展字段
     * @return array
     * @author 胡佳雨 <hjy@ourstu.com>.
     */
    public function getUser($uid,$type = 0,$afield = array()){
        $fields = array('uid','title','nickname','signature');
        switch($type){
            case 1:
                array_push($fields,'rank_link');
                break;
            default:
                break;
        }
        $fields = array_unique(array_merge($fields,$afield));
        $memberInfo = query_user($fields,$uid);
        $avatar = $this->getHead($uid);
        $memberInfo = array_merge($memberInfo, $avatar);
        return $memberInfo;
    }

    /**获取登陆人(最详细的)用户信息
     * @param $uid
     * @param $mid
     * @return array|null
     * @author 胡佳雨 <hjy@ourstu.com>.
     */
    public function getUserInfo($uid, $mid)
    {
        $userFields = array('uid', 'title', 'nickname','username', 'email', 'mobile', 'rank_link','score1','score2','score3','score4','score6','score8',
            'fans','sex','weibocount', 'following','is_following','is_followed', 'signature', 'pos_province','con_check','total_check','login',
            'pos_city', 'pos_district','expand_info'
        );
        $memberInfo = query_user($userFields, $uid);
        $titleModel = D('Ucenter/Title');
        $memberInfo['level'] = $titleModel->getCurrentTitleInfo($uid);
        $score_type = modC('SHOP_SCORE_TYPE', '1', 'Shop');
        $memberInfo['now_shop_score']=$memberInfo['score'.$score_type];
        $memberInfo['province'] = $this->getPos($memberInfo['pos_province']);
        $memberInfo['city'] = $this->getPos($memberInfo['pos_city']);
        $memberInfo['district'] = $this->getPos($memberInfo['pos_district']);
        if($mid==$uid){
            $memberInfo['is_self'] = 1;
        }else{
            $memberInfo['is_self'] = 0;
        }
        $userGroup = M('auth_group_access')->where(array('uid'=>$uid))->select();
        $user_group = array();
        if($userGroup){
            foreach($userGroup as $key=>$value){
                $user_group[$key] = $value['group_id'];
            }
        }
        $memberInfo['user_group'] = $user_group;
        $memberInfo['is_admin'] = is_administrator($uid);
        $memberInfo['message_unread_count']=D('Message')->where(array('to_uid'=>$uid,'is_read'=>0))->count();
        $avatar = $this->getHead($uid);
        $myTags = D('Ucenter/UserTagLink')->getUserTag($uid);
        $memberInfo = array_merge($memberInfo, $avatar, array('tags' => $myTags ? $myTags : array()));
        return $memberInfo;
    }

    /**比getUserInfo少一些字段的个人信息获取函数
     * @param $uid      String|int 查询目标uid
     * @param $mUid     String|int 登录者ID
     * @return array|null
     * @author 胡佳雨 <hjy@ourstu.com>.
     */
    public function getNormUserInfo($uid,$mUid=0){
        $memberInfo = query_user(array('uid', 'title', 'nickname', 'email', 'mobile', 'rank_link', 'fans','sex', 'following', 'signature', 'pos_province', 'pos_city', 'pos_district'), $uid);
        $titleModel = D('Ucenter/Title');
        $memberInfo['level'] = $titleModel->getCurrentTitleInfo($uid);
        $avatar = $this->getHead($uid);
        $userGroup = M('auth_group_access')->where(array('uid'=>$uid))->select();
        $user_group = array();
        if($userGroup){
            foreach($userGroup as $key=>$value){
                $user_group[$key] = $value['group_id'];
            }
        }
        $memberInfo['user_group'] = $user_group;
        $memberInfo['is_admin'] = is_administrator($uid);
        $memberInfo = array_merge($memberInfo, $avatar);
        $memberInfo['is_self'] = $uid ==$mUid?1:0;
        $follow = M('Follow')->where(array('who_follow' => $mUid, 'follow_who' => $uid))->find();
        $memberInfo['is_following'] = $follow ? true : false;
        return $memberInfo;
    }

    /**
     * @deprecated 1.6.4
     */
    public function getUserSimpleInfo($uid)
    {
        return $this->getUser($uid,1);
    }


    public function getUserCID($uids)
    {
        $CIDS= M('UserLocation')->where(array('uid'=>array('in',$uids)))->select();
        foreach($CIDS as &$b){
            $b=array('cid'=>$b['ClientID'],'token'=>$b['token']);
        }
        unset($b);
        return $CIDS;
    }

    /**
     * @deprecated 1.6.4
     */
    public function getUserReduceInfo($uid)
    {
        return $this->getUser($uid);
    }
    /**
     * @deprecated 1.6.4-
     */
    public function getInfo($uid)
    {
        return $this->getUser($uid);
    }
    public function parseAvatar($avatar)
    {
        if(strpos($avatar, 'http')===false){
            if(strpos($avatar,'Uploads/Avatar') === false){
                $avatar = '/Uploads/Avatar' .$avatar;
            }
            return is_https() . str_replace('//', '/', $_SERVER['HTTP_HOST'] . '/' .$avatar);
        }else{
            return strpos($avatar,'Uploads/Avatar') === false?$avatar:str_replace('Uploads/Avatar','',$avatar);
        }
    }

    /**获取头像老接口
     * @deprecated 1.6.4-
     */
    public function getAvatar($uid)
    {
        return $this->getHead($uid);
    }

    /**获取头像新接口
     * @param $uid
     * @return \ArrayObject
     * @author 胡佳雨 <hjy@ourstu.com>.
     */
    public function getHead($uid)
    {
        $avatarArr = query_user(array( 'avatar128'), $uid);
        unset($avatarArr['avatar32']);
        unset($avatarArr['avatar64']);
        unset($avatarArr['avatar256']);
        foreach($avatarArr as &$value){
            $value = $this->parseAvatar($value);
        }
        return $avatarArr;
    }

    /**设置扩展信息
     * @param string $field
     * @param string $value
     * @return bool|mixed
     */
    public function setExpandInfo($field, $value = '')
    {
        $mid = is_login();
        $expandInfo = M('FieldSetting')->where(array('field_name'=>$field))->find();
        $result = null;
        if ($expandInfo) {
            if(D('Field')->where(array('field_id' => $expandInfo['id'], 'uid' => $mid))->find()){
                $data = array('field_data' => $value, 'changeTime' => time());
                 $result = D('Field')->where(array('field_id' => $expandInfo['id'], 'uid' => $mid))->save($data);
            }else{
                $role= M('UserRole')->where(array('uid'=>$mid))->getField('role_id');
                $data = array('field_data' => $value, 'changeTime' => time(),'role_id'=>$role, 'createTime' => time(),'field_id' => $expandInfo['id'], 'uid' => $mid);
                $result = D('field')->add($data);
            }
        }
        return $result;
    }

    public function getExpandInfo($uid, $result)
    {

        $map['status'] = 1;
        $field_group = D('field_group')->where($map)->select();
        $field_group_ids = array_column($field_group, 'id');
        $map['profile_group_id'] = array('in', $field_group_ids);
        $fields_list = D('field_setting')->where($map)->getField('id,field_name,form_type,visiable');
        $fields_list = array_combine(array_column($fields_list, 'field_name'), $fields_list);
        $map_field['uid'] = $uid;
        foreach ($fields_list as $key => $val) {
            $map_field['field_id'] = $val['id'];
            $field_data = D('field')->where($map_field)->getField('field_data');
            if ($field_data == null || $field_data == '') {
                $fields_list[$key]['data'] = $field_data;
            } else {
                if ($val['form_type'] == "checkbox") {
                    $field_data = explode('|', $field_data);
                }
                $fields_list[$key]['data'] = $field_data;
            }
        }
        $result = $fields_list;
        return $result;

    }


    public function getPos($pos_code)
    {
        $pos = D('District')->where(array('id' => $pos_code))->find();
        $pos=$pos['name'];
        return $pos;
    }

    /**保存地理位置信息
     * @param mixed $geolocation
     * @param int $uid
     * @param int $only
     * @return int
     * @author 胡佳雨 <hjy@ourstu.com>.
     */
    public function addGeolocation($geolocation,$uid = 0,$only = 0)
    {
        $geolocationId = 0;
        $geolMod = M('UserGeolocation');
        $geolocation = is_array($geolocation)?$geolocation:json_decode($geolocation,true);
        if(!empty($geolocation)){
            $data['uid'] = $uid;
            $data['only'] = $only?1:0;
            $data['coords_type'] = $geolocation['coordsType'];
            $data['lng'] = $geolocation['coords']['longitude'];
            $data['lat'] = $geolocation['coords']['latitude'];
            $data['altitude'] = $geolocation['coords']['altitude'];
            $data['create_time'] = $geolocation['timestamp']/1000;
            $data['country'] = $geolocation['address']['country'];
            $data['province'] = $geolocation['address']['province'];
            $data['city'] = $geolocation['address']['city'];
            $data['district'] = $geolocation['address']['district'];
            $data['street'] = $geolocation['address']['street'];
            $data['cityCode'] = $geolocation['address']['cityCode'];
            $data['address'] = $geolocation['addresses'];
            $map = array('uid'=>$uid,'only'=>1);
            $geoInfo = $geolMod->where($map)->find();
            if($only && $geoInfo){
                $geolocationId = $geoInfo['id'];
                $geolMod->where($map)->save($data);
            }else{
                $geolocationId = $geolMod->add($data);
            }
        }else{
            if($only){
                $geolMod->where(array('uid'=>$uid,'only'=>1))->delete();
            }
        }
        return $geolocationId;
    }

    /**获取排行榜成员
     * @param $type
     * @param $page
     * @param $limit
     * @return mixed
     * @author 胡佳雨 <hjy@ourstu.com>.
     */
    public function getRankUser($type,$page,$limit){
        $order = array($type =>'desc','uid'=>'asc');
        $user_list = M('Member')->where(array('status' => 1))->field(array('uid',$type))->order($order)->page($page,$limit)->select();
        foreach ($user_list as $key => &$val) {
            $tempInfo = $this->getUser($val['uid']);
            $tempInfo['ranking'] = ($page - 1) * $limit + $key + 1;
            $tempInfo['rank_type'] = $type;
            $tempInfo['rank_value'] = intval($val[$type]);
            $val = $tempInfo;
        }
        unset($val);
        return $user_list;
    }
} 