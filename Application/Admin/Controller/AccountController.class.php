<?php
/**
 * Created by PhpStorm.
 * User: Huge
 * Date: 2016/1/23 0023
 * Time: 10:42
 */
namespace Admin\Controller;

class AccountController extends AdminController {

    protected function _initialize(){
        $this->admin_authoried = false;
        $this->dal = M("employee");
        Vendor("Wifisoft/String");
    }

    public function index(){

    }


    public function Login(){
        if(IS_POST){
            $typeName = 'employee';
            $typeID = 'employeeID';
            $name = 'employee_num';
            $data['employee_num']=I('LoginName');
            if(empty($data['employee_num'])){
                $this->error('账号输入错误!');
            }


            $password=I('Password');

            if(empty($password)){
                $this->error('密码输入错误!');
            }
            $data['del']=0;
            $user= M($typeName)->where($data)->find();

            $current_user['name'] = $user['name'];



            if($user){
                if($user['sstatus'] == 1){
                    $this->error('用户已被锁定，暂时无法登录!');
                }else{
                    if($user['password']!=get_guoyuanPWD($password,$user['random'])){
                        $this->error('密码错误!');
                    }else{
                        $current_user['employeen_num'] = $user[$name];
                        $current_user['name'] = $user['name'];
                        $current_user['position'] = $user['position'];
                        $current_user['pic'] = $user['pic'];
                        $current_user['employeeID'] = $user[$typeID];
                        $current_user['Mobile'] = $user['Mobile'];
                        session("current_user" , $current_user);//写入Session 登录成功

                        $url = U('index/index');
                        $this->success('登录成功', $url);//
                    }
                }
            }
            else{
                $this->error('无此用户!');
            }
        }
        else{
            $count = $this->dal->where(array("status"=>0,"del"=>0))->count();
            if($count <= 0){
                $add["employee_num"] = "admin";
                $string = get_string();
                $add['random']= $string->rand_string(6,1);
                $add["password"] = md5(md5("123456").$add['random']);
                $add['register_time'] = time_format();
                $this->dal->add($add);
            }
            $sets = M('siteconfig')->find();
            $this->assign('sets',$sets);
            $this->display('Account/Login');
        }
    }
    public function vcode(){
        $Verify = new \Think\Verify();
        $Verify->entry();

    }


    public function logout() {
        session_destroy();
        redirect("/admin/account/Login");
    }

    /** 验证码是否正确.
     * @param $VerifyCode
     * @return bool
     */
    function VerifyCodeIsRight($VerifyCode){
        if($_SESSION['VerifyCode']==$VerifyCode){
            return true;
        }
        else
        {
            return false;
        }
    }
}