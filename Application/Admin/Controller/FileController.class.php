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
        $dir  = $_GET['dir'];
        $info = uploadpic($dir);
        $this->ajaxReturn($info);
    }
}