<?php


namespace Api\Controller;


class AttachmentController extends BaseController
{
    public function __construct()
    {
        parent::__construct();

    }

    //上传附件
    //Todo 还未考虑七牛上传的情况,暂时只支持本地服务器上传
    public function uploadAttach(){
        $mid = $this->isLogin();
        $aData = POST_I('data');
        $aType = POST_I('type');
        if (preg_match('/^(data:\s*\w+\/(\w+);base64,)/', $aData, $result)) {
            $base64_body = substr(strstr($aData, ','), 1);
            empty($aExt) && $aExt = $result[2];
        } else {
            $base64_body = $aData;
        }
        $fileData = base64_decode($base64_body);
        if (strpos($fileData, '<?php') !==false) {
            $this->apiError( '非法操作');
        }

        $tagModel = M('Attachment');
        $driver = 'local';
        $savePath = '/Uploads/Attachment/';

        $md5 = md5($base64_body);
        $sha1 = sha1($base64_body);
        if($aType == 'voice'){
            $tagModel = M('Voice');
            if(!in_array($aExt,array('amr','3gp','wav','acc','mp3'))){
                $this->apiError('非法操作');
            }
            $driver = modC('PICTURE_UPLOAD_DRIVER', 'local', 'config');
            $savePath = '/Uploads/Attachment/Voice/';
        }elseif( $aType == 'image'){
            $tagModel = M('Picture');
            if(!in_array($aExt,array('jpg','gif','png','jpeg'))){
                $this->apiError('非法操作');
            }
            $driver = modC('PICTURE_UPLOAD_DRIVER','local','config');
            $savePath = '/Uploads/Picture/';
        }
        $driver = check_driver_is_exist($driver);
        $check = $tagModel->where(array('md5' => $md5, 'sha1' => $sha1))->find();
        if ($check) {
            //已存在则直接返回信息
            $return['id'] = $check['id'];
            $return['path'] = get_attach_path($check['path']);
            $return['type'] = $check['type'];
            $return['size'] = $check['size'];
            $this->apiSuccess($return);
        } else {
            //不存在则上传并返回信息

            $path = $savePath . $md5 . '.' . $aExt;
            if($driver == 'local'){
                //本地上传
                mkdir('.' . $savePath, 0777, true);
                $res = file_put_contents('.' . $path, $fileData);
            }else{
                //使用云存储
                $res = false;
                $name = get_addon_class($driver);
                if (class_exists($name)) {
                    $class = new $name();
                    if (method_exists($class, 'uploadBase64')) {
                        $path = $class->uploadBase64($base64_body,$path);
                        $res = true;
                    }
                }
            }
            if($res){
                if($aType == 'voice'){
                    $data['uid'] = $mid;
                    $data['driver'] = $driver;
                    $data['type'] = $aExt;
                }elseif( $aType == 'image'){
                    $data['type'] = $driver;
                }
                $data['size'] = strlen($fileData);
                $data['path'] = $path;
                $data['md5'] = $md5;
                $data['sha1'] = $sha1;
                $data['status'] = 1;
                $data['create_time'] = time();
                $id = $tagModel->add($data);
                $this->apiSuccess(array('id' => $id, 'path' => get_attach_path($path),'type' =>$aExt,'size'=>$res));
            }
        }
    }

    public function uploadPicture()
    {
        $aData = I_POST('data');
        $aExt = I_POST('ext');
        if ($aData == '' || $aData == 'undefined') {
            $this->apiError('参数错误');
        }

        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $aData, $result)) {
            $base64_body = substr(strstr($aData, ','), 1);
            empty($aExt) && $aExt = $result[2];
        } else {
            $base64_body = $aData;
        }

        if(!in_array($aExt,array('jpg','gif','png','jpeg'))){
            $this->ajaxReturn(array('status'=>0,'info'=>'非法操作'));
        }
        $hasPhp=base64_decode($base64_body);
        if (strpos($hasPhp, '<?php') !==false) {
            $this->ajaxReturn(array('status' => 0, 'info' => '非法操作'));
        }

        $pictureModel = D('Picture');
        $md5 = md5($base64_body);
        $sha1 = sha1($base64_body);

        $check = $pictureModel->where(array('md5' => $md5, 'sha1' => $sha1))->find();

        if ($check) {
            //已存在则直接返回信息
            $return['id'] = $check['id'];
            $return['path'] = render_picture_path_without_root($check['path']);

            $this->apiSuccess($return);
        } else {
            //不存在则上传并返回信息
            $driver = modC('PICTURE_UPLOAD_DRIVER','local','config');
            $driver = check_driver_is_exist($driver);
            $date = date('Y-m-d');
            $saveName = uniqid();
            $savePath = '/Uploads/Picture/' . $date . '/';

            $path = $savePath . $saveName . '.' . $aExt;
            if($driver == 'local'){
                //本地上传
                mkdir('.' . $savePath, 0777, true);
                $data = base64_decode($base64_body);
                $rs = file_put_contents('.' . $path, $data);
            }
            else{
                $rs = false;
                //使用云存储
                $name = get_addon_class($driver);
                if (class_exists($name)) {
                    $class = new $name();
                    if (method_exists($class, 'uploadBase64')) {
                        $path = $class->uploadBase64($base64_body,$path);
                        $rs = true;
                    }
                }
            }
            if ($rs) {
                $pic['type'] = $driver;
                $pic['path'] = $path;
                $pic['md5'] = $md5;
                $pic['sha1'] = $sha1;
                $pic['status'] = 1;
                $pic['create_time'] = time();
                $id = $pictureModel->add($pic);
                $this->apiSuccess(array('id' => $id, 'path' => render_picture_path_without_root($path)));
            } else {
                $this->apiError('写入文件失败');
            }

        }
    }


    public function getPicture()
    {
        $aId = I('get.id');

        $pictureModel = D('Picture');
        $picture = $pictureModel->where(array('id' => $aId))->find();
        if ($picture) {
            $return['id'] = $picture['id'];
            $return['path'] = render_picture_path_without_root($picture['path']);
            $this->apiSuccess($return);
        } else {
            $this->apiError('找不到图片');
        }

    }


}