<?php
/**
 * Created by PhpStorm.
 * User: fely
 * Date: 1/19/15
 * Time: 1:57 PM
 */

namespace Admin\Controller;

use Think\Upload;
use Think\Log;

class FileController extends AdminController{

    protected function _initialize() {
        parent::_initialize();
    }

    /**
     * 登录即可
     * @return bool
     */
    public function checkDynamic() {
        if (get_user_id()) {
            return true;
        }
        return false;
    }

    /**
     * 上传图片
     */
    public function uploadPicture(){
        $return  = array('status' => 1, 'info' => '上传成功', 'data' => '');
        $setting['rootPath'] = "uploads/picture/";
        makeDir(ROOT_PATH."/".$setting['rootPath']);
        $Upload = new Upload($setting, "local", C("UPLOAD_LOCAL_CONFIG"));
        $info   = $Upload->upload($_FILES);
        if ($info) {
            $return['data'] = "/" . $setting['rootPath'] . $info['Filedata']['savepath'] . $info['Filedata']['savename'];
        }
        else {
            $return['info'] =  $Upload->getError();
            $return['status'] = 0;
        }
        Log::record(json_encode($return) , Log::INFO);
        $this->ajaxReturn($return);
    }
    public function upload(){
        $return  = array('status' => 1, 'info' => '上传成功', 'data' => '');
        /* 调用文件上传组件上传文件 */
        $setting['rootPath'] = "uploads/excel/";
        makeDir(ROOT_PATH."/".$setting['rootPath']);
        $Upload = new Upload($setting,"local",C("FILE_UPLOAD"));
        $info = $Upload->upload( );
        /* 记录附件信息 */
        if($info){
            $return['path'] = "/" . $setting['rootPath'] . $info['Filedata']['savepath'] . $info['Filedata']['savename'];
        } else {
            $return['status'] = 0;
            $return['info']   = $Upload->getError();
        }
        /* 返回JSON数据 */
        $this->ajaxReturn($return);
    }

    public function upload_cert(){ //证书
        $return  = array('status' => 1, 'info' => '上传成功', 'data' => '');
        /* 调用文件上传组件上传文件 */
        $setting['rootPath'] = "uploads/".getCustomerID()."/cert/";
        makeDir(ROOT_PATH."/".$setting['rootPath']);
        $Upload = new Upload($setting,"local",C("FILE_UPLOAD"));
        $info = $Upload->upload_cert( );
        /* 记录附件信息 */
        if($info){
            $return['path'] = "/" . $setting['rootPath'] . $info['Filedata']['savepath'] . $info['Filedata']['savename'];
        } else {
            $return['status'] = 0;
            $return['info']   = $Upload->getError();
        }
        /* 返回JSON数据 */
        $this->ajaxReturn($return);
    }
}