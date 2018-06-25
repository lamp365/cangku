<?php
/**
 * Created by PhpStorm.
 * User: Huge
 * Date: 2016/3/5 0005
 * Time: 14:00
 */

namespace Admin\Controller;


class CustomerController extends AdminController
{
    private $status;
    private $Priority;
    private $feedback;
    private $emp;
    private $close;
    private $pay;
    protected function _initialize(){
        parent::_initialize();
        $this->dal = M("customer");
        $this->dal_cs = M("customerstatus");
        $this->dal_cr = M("customerrole");
        $this->dal_ck = M("customerrank");
        $this->close = M("customerclose");
        $this->status = M("feedbackstatus");
        $this->Priority = M("feedbackpriority");
        $this->feedback = M("feedback");
        $this->emp = M("employee");
        $this->pay = M("paytype");
        $this->support = M("support");
    }

    public function customer(){
        //客户状态
        $cs =  $this->dal_cs -> where(array('DelFlag'=>0)) -> field('CtmStatusID,CtmStatusName')-> select();
        $this->assign('info',$cs);
        //客户等级
        $ck = $this->dal_ck -> where(array('DelFlag'=>0)) -> field("CtmRankID,CtmRankName") -> select();
        $this->assign('rank',$ck);
        $this->display();
    }
    public function index(){
        $this->display();
    }

    public function loadCustomer(){
        // sort:id order:desc limit:10 offset:0

        $map['C.DelFlag'] = 0;
        //$page = i('page');
        //$rows = i('rows');
        $offset = i("offset");
        $state = i("ctmState");//客户状态
        $ctmName = i("ctmName");//所属客户
        $address = i("address");//地址查询
        $timeA = i("timeA");//时间开始查询
        $timeB = i("timeB");//时间结束查询
        $ctmR = i("ctmR");//客户等级查询
        $ctmS = i("ctmS");//客户来源查询
        $limit = i("limit");
        $search_key = i('search_key');

        $search_value = i('search');
        if(!empty($search_value)){
            $map["C.ShortName|C.Identifier"] = array("like","%".$search_value."%");
        }
        if(!empty($state) && isset($state)){
            $map["C.CtmStatusID"] = $state;
        }
        if(!empty($ctmR) && isset($ctmR)){
            $map["C.CtmRankID"] = $ctmR;
        }
        if(!empty($ctmS) && isset($ctmS)){
            $map["C.Sources"] = $ctmS;
        }

        if(!empty($address) && isset($address)){
            $map['c.Address'] = array('like',"%$address%");
        }

        //如果只有一个开始时间存在
        if(!empty($timeA) && empty($timeB)){
            $map['c.CreateTime'] = array('egt',$timeA);
        }
        //如果只有一个结束时间存在
        if(!empty($timeB) && empty($timeA)){
            $map['c.CreateTime'] = array('elt',$timeB);
        }
        //如果两个都存在
        if(!empty($timeB) && !empty($timeA)){
            $map['c.CreateTime'] = array('BETWEEN',array($timeA,$timeB));
        }
        if(!get_user_name()){
            if(get_user_type()==3){
                $map['c.CustomerID'] = get_user_id();
            }else if(get_user_type()==1){
                $depar =$this->emp -> where(array('EmployeeID' => get_user_id())) -> getField('DepartmentNum');
                $priority =$this->emp -> where(array('EmployeeID' => get_user_id())) -> getField('isPriority');

                if($depar == '1001' && $priority == 2){
                   // dump($depar);
                   $map['c.DeveloperID'] = get_user_id();
                   $map['c.Sources'] = get_user_type();
                }
            }else{
                $map['c.DeveloperID'] = get_user_id();
               // $map['c.Sources'] = get_user_type();
            }
        }
        $sort = i('sort');
        $order = i('order');
        $reorder = "C.Sort desc";
        if(!empty($sort)){
            $reorder = "C.".$sort." ".$order;
        }

        if(get_user_type()==1){
            if(isset($ctmName) && !empty($ctmName)){
                $map['E.Name'] = array('like',"%$ctmName%");
            }
            $list =  $this->dal->alias("C")
                ->join("customerstatus S on S.CtmStatusID = C.CtmStatusID","left")
                ->join("customerrank K on K.CtmRankID = C.CtmRankID","left")
                ->join("customerrole R on R.CtmRoleID = C.CtmRoleID","left")
                ->join("employee E on E.EmployeeID = C.DeveloperID","left")
                ->where($map)->field("C.*,S.CtmStatusName,K.CtmRankName,R.CtmRoleName")->limit($offset,$limit)->order($reorder)->select();
            $count =  $this->dal->alias("C")
                ->join("customerstatus S on S.CtmStatusID = C.CtmStatusID","left")
                ->join("customerrank K on K.CtmRankID = C.CtmRankID","left")
                ->join("customerrole R on R.CtmRoleID = C.CtmRoleID","left")
                ->join("employee E on E.EmployeeID = C.DeveloperID","left")
                ->where($map)->field("C.*,S.CtmStatusName,K.CtmRankName,R.CtmRoleName")->limit($offset,$limit)->order($reorder)->count();

        }else{
            $list =  $this->dal->alias("C")
                ->join("customerstatus S on S.CtmStatusID = C.CtmStatusID","left")
                ->join("customerrank K on K.CtmRankID = C.CtmRankID","left")
                ->join("customerrole R on R.CtmRoleID = C.CtmRoleID","left")
                ->where($map)->field("C.*,S.CtmStatusName,K.CtmRankName,R.CtmRoleName")->limit($offset,$limit)->order($reorder)->select();
            $count =  $this->dal->alias("C")
                ->join("customerstatus S on S.CtmStatusID = C.CtmStatusID","left")
                ->join("customerrank K on K.CtmRankID = C.CtmRankID","left")
                ->join("customerrole R on R.CtmRoleID = C.CtmRoleID","left")
                ->where($map)->field("C.*,S.CtmStatusName,K.CtmRankName,R.CtmRoleName")->limit($offset,$limit)->order($reorder)->count();

        }

        foreach($list as $ct => $c){
            $list[$ct]["Status"] = $c["Status"] == 0 ? "正常" : "锁定";
            $list[$ct]["Sources"] = $c["Sources"] > 1 ? "代理" : "直营";
            if($c['DeveloperID']){
                if($c["Sources"] == 1){
                    $list[$ct]["DeveloperName"] = $this->emp -> where(array('EmployeeID' => $c['DeveloperID'])) -> getField('Name');
                }else{
                    $list[$ct]["DeveloperName"] = M('agent') -> where(array('AgentID' => $c['DeveloperID'])) -> getField('CompanyName');
                }
            }else{
                $list[$ct]["DeveloperName"] = '公共用户';
            }
            $list[$ct]['Address'] = "<span title='".$c['Address']."'>".msubstr($c['Address'],0,3)."</span>";
            //查找这个客户是否买了产品没有
            $productID = M('contract') -> where(array('Identifier' => $c['Identifier'])) -> getField('CttProductID');
            if($productID){
                 $produ = M('product') -> where(array('ProductID'=>array('in',$productID))) -> getField('ProductName',true);
                 $list[$ct]['ProductName'] = implode(',',$produ);
            }
        }
        $list_array= array("total"=>$count,"rows"=>$list?$list:array());
        echo json_encode($list_array);
    }
    //添加会员信息
    public function addCustomer(){
       if(IS_POST){
            $data = i('post.');
            $data['Identifier'] = getCustomerIdentifier();
            $data['ModifyTime'] = date('Y-m-d H:i:s');
            $data['CreateTime'] = date('Y-m-d H:i:s');
            $data['OperatorID'] = $this->user['EmployeeID'];
            $where['LoginName'] = $data['LoginName'];
            //admin添加的都是公共的客户
           if(!get_user_name()){
               $data['DeveloperID'] = $this->user['EmployeeID'];
           }

            $data['Sources'] = get_user_type();
            $result = $this->dal -> where($where) -> find();
            if($result){
                $this -> error('账号已经存在，请重新输入账号！');
                exit;
            }
           if($data['ShortName']){
               $r = $this->dal -> where(array('ShortName'=>$data['ShortName'])) -> find();
               if($r){
                   $this -> error('该简称已经被使用，请重新输入简称！');
                   exit;
               }
           }
            $res = $this->dal -> add($data);
            if($res){
                //为会员赋权限
                $dutyinfo = M('duty') -> where(array('Type'=> 3,'Status'=>1)) -> find();
                if($dutyinfo){
                    M('employee_duty') -> add(array('MemberID'=>$res,'DutyID'=>$dutyinfo['ID'],'Type'=> 3));
                }else{
                    $result =  M('duty') -> add(array('DutyName'=>'客户权限组','Type'=>3,'Module'=>'admin'));
                    M('employee_duty') -> add(array('MemberID'=>$res,'DutyID'=>$result,'Type'=> 3));
                }
                //添加成功要给初始化密码
                $string = get_string();
                $sa['Random']= $string->rand_string(6,1);
                $sa["Password"] = md5(md5($sa['Random']).$sa['Random']);
                $this->dal -> where(array('CustomerID'=>$res)) -> save($sa);
                $this -> success('添加成功！您账号的初始密码为'.$sa['Random']);
            }else{
                $this -> error('添加失败！');
            }

       }else{
           $where['DelFlag'] = 0;
           $res['cs'] = $this->dal_cs -> where($where) -> getField("CtmStatusID,CtmStatusName");
           $res['cr'] = $this->dal_cr -> where($where) -> getField("CtmRoleID,CtmRoleName");
           $res['ck'] = $this->dal_ck -> where($where) ->getField("CtmRankID,CtmRankName");
           $res['co'] = $this->close -> where($where) -> getField("CtmCloseID,CtmCloseName");
         //  $arr =$this->dal_cs ->where($where)->order("Sort asc")->getField("CtmStatusID,CtmStatusName");
           $this->assign('cus',$res);
           $this -> display('editCustomer');
       }
    }
    //修改会员
    public function editCustomer(){
        $id = i('id');
        if(IS_POST){
            $data = i('post.');
            $data['ModifyTime'] = date('Y-m-d H:i:s');
            $data['OperatorID'] = $this->user['EmployeeID'];
            $where['CustomerID'] = $id;
            //为会员赋权限
            $dutyinfo = M('duty') -> where(array('Type'=> 3,'Status'=>1)) -> find();
            if($dutyinfo){
                $d = M('employee_duty') -> where(array('MemberID'=>$id,'DutyID'=>$dutyinfo['ID'],'Type'=> 3)) -> find();
                if(!$d){
                    M('employee_duty') -> add(array('MemberID'=>$id,'DutyID'=>$dutyinfo['ID'],'Type'=> 3));
                }
            }else{
                $result =  M('duty') -> add(array('DutyName'=>'客户权限组','Type'=>3,'Module'=>'admin'));
                M('employee_duty') -> add(array('MemberID'=>$id,'DutyID'=>$result,'Type'=> 3));
            }

            if($data['ShortName']){
                $map['ShortName']=$data['ShortName'];
                $map['CustomerID']=array('neq',$id);
                $r = $this->dal -> where($map) -> find();
                if($r){
                    $this -> error('该简称已经被使用，请重新输入简称！');
                    exit;
                }
            }

            $this->dal -> where($where) -> save($data);
            $this -> success('修改成功！');
        }else{
            $where['DelFlag'] = 0;
            $res['cs'] = $this->dal_cs -> where($where) -> getField("CtmStatusID,CtmStatusName");
            $res['cr'] = $this->dal_cr -> where($where) -> getField("CtmRoleID,CtmRoleName");
            $res['ck'] = $this->dal_ck -> where($where) ->getField("CtmRankID,CtmRankName");
            $res['co'] = $this->close -> where($where) -> getField("CtmCloseID,CtmCloseName");
            $where['CustomerID'] = $id;
            $info = $this->dal -> where($where) -> find();
            $this->assign('cus',$res);
            $this->assign('info',$info);
            $this -> display();
        }
    }
    //删除会员
    public function delCustomer(){
        //$array_id = explode(';',$_POST['ids']);
        $arr["CustomerID"] = array("in",$_POST['ids']);
        $data['DelFlag'] = 1;
        $data['ModifyTime'] = date('Y-m-d H:i:s');
        $data['OperatorID'] = $this->user['EmployeeID'];
        if($this->dal->where($arr)->save($data) !== false){
            $this -> success('删除成功！');
        }else{
            $this -> error("删除失败！");
        }

    }
    //重置密码
    public function editPWD(){

        if(IS_POST){
            $data = i('post.');
            $id = i('id');
            if($data['pass'] != $data['repass']){
                $this -> error('两次密码不一致！');
                exit;
            }
            $string = get_string();
            $sa['Random']= $string->rand_string(6,1);
            $sa["Password"] = md5(md5($data['pass']).$sa['Random']);
            $this->dal -> where(array('CustomerID'=>$id)) -> save($sa);
            $this -> success('重置成功！');

        }else{

            $this -> display();
        }
    }
    //查看客户的所有信息
    public function customerInfo(){
        //查看市场信息
        $id = i('id');
        $where['Identifier'] = $this->dal -> where(array('CustomerID'=>$id,'DelFlag'=>0)) -> getfield('Identifier');
        $title = $this->dal -> where(array('CustomerID'=>$id)) -> getfield('FullName');
       // dump($where);
        $zhengz = "/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg]))[\'|\"].*?[\/]?>/";
        $market_info = M('market') -> where($where) -> order('GenTime desc') -> limit(0,10) ->select();
        foreach($market_info as &$m){
            $m['EmpName'] = $this->emp -> where(array('EmployeeID' => $m['EmpID'])) -> getfield('Name');
            $m['Content'] = preg_replace($zhengz,'',htmlspecialchars_decode($m['Content']));
        }
        //查看实施信息
        $main_info = M('maintain') -> where(array('CustomerID'=>$id,'DelFlag'=>0)) -> order('TraceTime desc') -> limit(0,10) ->select();
        foreach($main_info as &$main){
            $main['EmpName'] = $this->emp -> where(array('EmployeeID' => $main['EmployeeID'])) -> getfield('Name');
            $main['Content'] = preg_replace($zhengz,'',htmlspecialchars_decode($main['Content']));
        }
        //查看问题
        $feed_info = $this->feedback -> where(array('CustomerID'=>$id,'DelFlag'=>0)) -> order('FbTime desc') -> limit(0,10) ->select();
        $type = get_user_type();
        foreach($feed_info as &$feed){
            if($type == 1){
                $feed['EmpName'] = $this->emp -> where(array('EmployeeID' => $feed['CtmID'])) -> getfield('Name');
            }else if($type == 2){
                $feed['EmpName'] = M('agent') -> where(array('AgentID' => $feed['CtmID'])) -> getfield('CompanyName');
            }else{
                $feed['EmpName'] = M('customer') -> where(array('CustomerID' => $feed['CtmID'])) -> getfield('ShortName');
            }
            if($feed['FbStatusID']){
                $feed['statusName'] = $this->status -> where(array('FbStatusID' => $feed['FbStatusID'])) -> getfield('FbStatusName');
            }else{
                $feed['statusName'] = '待解决';
            }
            $feed['Description'] = preg_replace($zhengz,'',htmlspecialchars_decode($feed['Description']));
        }
        $this->assign('market_info',$market_info);
        $this->assign('main_info',$main_info);
        $this->assign('feed_info',$feed_info);
        $this->assign('title',$title);
        $this->display();
    }

    //统计客户的数据
    public function countCustomer(){
        $where['CustomerID'] = i('id');
        $where['DelFlag'] = 0;
        $DeveloperID = $this->dal -> where($where) -> getfield('DeveloperID');//获取该客户的所属人id
        $eName = $this->emp -> where(array('EmployeeID' => $DeveloperID)) -> getfield('Name');
        //统计各个状态下所属人都有多少客户
        $map['DelFlag'] = 0;
        //查找所有的状态
        $info = $this->dal_cs -> where($map) ->select();
        $w['DeveloperID'] = $DeveloperID;
        foreach($info as $k => $v){
            $w['CtmStatusID'] = $v['CtmStatusID'];
            $info[$k]['count'] = $this->dal -> where($w) -> count();
        }

        $this->assign('info',$info);
        $this->assign('eName',$eName);
        $this -> display();
    }

    public function loadMoreCusInfo(){
        $id = i('id');
        $type = i('type');
        $count = i('time');
        switch($type){
            case 1:
                //查看市场信息
                $where['Identifier'] = $this->dal -> where(array('CustomerID'=>$id,'DelFlag'=>0)) -> getfield('Identifier');
                // dump($where);
                $info = M('market') -> where($where)-> field('Result,Content,GenTime as time,EmpID') -> order('GenTime desc') -> limit(10*$count,10*($count+1)) ->select();
                foreach($info as &$m){
                    $m['EmpName'] = $this->emp -> where(array('EmployeeID' => $m['EmpID'])) -> getfield('Name');
                }
                $class['yanse'] = 'lazur-bg';
                $class['tubiao'] = 'fa-coffee';
            break;
            case 2:
                //查看实施信息
                $info = M('maintain') -> where(array('CustomerID'=>$id,'DelFlag'=>0))-> field('Result,Content,TraceTime as time,EmployeeID') -> order('TraceTime desc') -> limit(10*$count,10*($count+1)) ->select();
                foreach($info as &$main){
                    $main['EmpName'] = $this->emp -> where(array('EmployeeID' => $main['EmployeeID'])) -> getfield('Name');
                }
                $class['yanse'] = 'blue-bg';
                $class['tubiao'] = 'fa-file-text';
                break;
            case 3:
                //查看问题
                $info = $this->feedback -> where(array('CustomerID'=>$id,'DelFlag'=>0)) -> field('CtmID,FbStatusID,Description as Content,FbTime as time') -> order('FbTime desc') -> limit(10*$count,10*($count+1)) ->select();
                $type = get_user_type();
                foreach($info as &$feed){
                    if($type == 1){
                        $feed['EmpName'] = $this->emp -> where(array('EmployeeID' => $feed['CtmID'])) -> getfield('Name');
                    }else if($type == 2){
                        $feed['EmpName'] = M('agent') -> where(array('AgentID' => $feed['CtmID'])) -> getfield('CompanyName');
                    }else{
                        $feed['EmpName'] = M('customer') -> where(array('CustomerID' => $feed['CtmID'])) -> getfield('ShortName');
                    }
                    if($feed['FbStatusID']){
                        $feed['Result'] = $this->status -> where(array('FbStatusID' => $feed['FbStatusID'])) -> getfield('FbStatusName');
                    }else{
                        $feed['Result'] = '待解决';
                    }

                }
                $class['yanse'] = 'navy-bg';
                $class['tubiao'] = 'fa-briefcase';
                break;
        }
        if($info){
            $this->assign('class',$class);
            $this->assign('info',$info);
            $this->success($this->fetch(),"",true);
        }else{
            $this->error('没有更多数据了！');
        }

    }
    //客户的联系人
    public function contacts(){

        if(IS_POST){
            $type = I('type');
            switch($type){
                case 1:
                    $data = i('post.');
                    $data['ModifyTime'] = date('Y-m-d H:i:s');
                    $data['OperatorID'] = $this->user['EmployeeID'];
                    M('contacts') -> add($data);
                    $this -> success('添加成功');
                    break;
                case 2:
                    $info = i('post.');
                    $where['ContactsID'] = $info['cid'];
                    $data[$info['name']] = $info['value'];
                    $data['ModifyTime'] = date('Y-m-d H:i:s');
                    $data['OperatorID'] = $this->user['EmployeeID'];
                    M('contacts') -> where($where) -> save($data);
                    $this -> success('修改成功');
                    break;
                case 3:
                    $cid = i('cid');
                    //在设定默认联系人之前要判断是否有默认联系人，有的话就改为不默认
                    $where['CustomerID'] = M('contacts') -> where(array('ContactsID' => $cid)) -> getField('CustomerID');
                    $where['Default'] = 0;
                    $where['DelFlag'] = 0;
                    $res = M('contacts') -> where($where) -> find();
                    if($res){
                        M('contacts') -> where(array('ContactsID' => $res['ContactsID'])) -> save(array('Default' => 1));
                    }else{
                        M('contacts') -> where(array('ContactsID' => $cid)) -> save(array('Default' => 0));
                        $result = M('contacts') -> where(array('ContactsID' => $cid)) -> find();
                        $data['CtmRoleID'] = $result['CtmRoleID'];
                        $data['Mobile'] = $result['Phone'];
                        $data['Contacter'] = $result['Name'];
                        $this->dal -> where(array('CustomerID'=>$result['CustomerID'])) -> save($data);
                    }
                    $this -> success('设置成功');
                    break;
                case 4:
                    $cid = i('cid');
                    $data['DelFlag'] = 1;
                    $data['ModifyTime'] = date('Y-m-d H:i:s');
                    $data['OperatorID'] = $this->user['EmployeeID'];
                    M('contacts') -> where(array('ContactsID' => $cid)) -> save($data);
                    $this -> success('删除成功');
                    break;
                default :
                    $this -> success('未知错误！');
                    break;
            }
        }else{
            $where['DelFlag'] = 0;
            //联系人角色
            $cr = $this->dal_cr -> where($where) -> getField("CtmRoleID,CtmRoleName");
            $this -> assign('cr',$cr);
            //客户联系人
            $where['CustomerID'] = i('id');
            $info =  M('contacts') -> where($where) -> select();
            $this -> assign('infos',$info);
            $this -> display();
        }

    }
    //支付方式
    public function payType(){
        $this->assign("crumbs_title","支付方式");
        $this->display();
    }
    //加载支付方式
    public function loadPayType(){
        $value = i('search');
        $where['DelFlag'] = 0;
        $offset = i("offset");
        $limit = i("limit");
        if($value){
            $where['PayName'] = array('LIKE',"%$value%");
        }
        $count = $this->pay -> where($where) -> order('Sort desc') -> count();
        $res = $this->pay -> where($where) -> limit($offset,$limit)-> order('Sort desc') -> select();
        foreach($res as &$v){
            $v['OperatorName'] =$this->emp -> where(array('EmployeeID' => $v['OperatorID'])) -> getField('Name');
            if($v['PayType'] ==1){
                $v['TypeName'] = '现金';
            }elseif($v['PayType'] == 2){
                $v['TypeName'] = '刷卡';
            }elseif($v['PayType'] == 3){
                $v['TypeName'] = '转账';
            }
        }
        //$list_array= $res?$res:array();
        $list_array= array("total"=>$count,"rows"=>$res?$res:array());
        echo json_encode($list_array);
    }
    //添加支付方式
    public function addPayType(){
        if(IS_POST){
            $data = i('post.');
            $data['ModifyTime'] = date('Y-m-d H:i:s');
            $data['OperatorID'] = $this->user['EmployeeID'];
            $this->pay -> add($data);
            $this -> success('添加成功！');
        }else{

            $this -> display('editPayType');
        }
    }
    //修改支付方式
    public function editPayType(){
        $id = i('id');
        $where['PayID'] = $id;
        $where['DelFlag'] = 0;
        if(IS_POST){
            $data = i('post.');
            $data['ModifyTime'] = date('Y-m-d H:i:s');
            $data['OperatorID'] = $this->user['EmployeeID'];
            $this -> pay -> where($where) -> save($data);
            $this->success('修改成功！');
        }else{
            $res = $this-> pay -> where($where)-> find();
            $this->assign('info',$res);
            $this->display();
        }
    }
    //删除支付方式
    public function delPayType(){
        $array_id['PayID'] = array('in',$_POST['ids']);
        $data['DelFlag'] = 1;
        $data['ModifyTime'] = date('Y-m-d H:i:s');
        $data['OperatorID'] = $this->user['EmployeeID'];
        $this -> pay -> where($array_id) -> save($data);
        $this -> success('删除成功！');

    }
    //客户状态
    public function customerstatus(){
        $this->assign("crumbs_title","客户状态");
        $this->display();
    }
    //加载客户状态
    public function loadCustomerStatus(){
        $value = i('search');
        $offset = i("offset");
        $limit = i("limit");
        $where['DelFlag'] = 0;
        if($value){
            $where['CtmStatusName'] = array('LIKE',"%$value%");
        }

        $count = $this->dal_cs -> where($where) -> order('Sort desc') -> count();
        $res = $this->dal_cs -> where($where) -> limit($offset,$limit) -> order('Sort desc') -> select();
        foreach($res as &$v){
            $v['OperatorName'] =$this->emp -> where(array('EmployeeID' => $v['OperatorID'])) -> getField('Name');
        }
        $list_array= array("total"=>$count,"rows"=>$res?$res:array());
        echo json_encode($list_array);
    }
    //新增客户状态
    public function addCustomerStatus(){
        if(IS_POST){
            $data = i('post.');
            $data['OperatorID'] = $this->user['EmployeeID'];
            $data['ModifyTime'] = date('Y-m-d H:i:s');
            $this->dal_cs -> add($data);
            $this->success('增加成功！');
        }else{
            $this-> display('editCustomerStatus');
        }

    }
    //修改客户状态
    public function editCustomerStatus(){
        $id = i('id');
        $where['CtmStatusID'] = $id;
        $where['DelFlag'] = 0;
        if(IS_POST){
            $data = i('post.');
            $data['ModifyTime'] = date('Y-m-d H:i:s');
            $data['OperatorID'] = $this->user['EmployeeID'];
            $this -> dal_cs -> where($where) -> save($data);
            $this->success('修改成功！');
        }else{
            //客户信息
            $res = $this-> dal_cs -> where($where)-> find();
            $this->assign('info',$res);
            $this-> display();
        }
    }
    //删除客户状态
    public function delCtmStatus(){
        $array_id['CtmStatusID'] = array('in',$_POST['ids']);
        $data['DelFlag'] = 1;
        $data['ModifyTime'] = date('Y-m-d H:i:s');
        $data['OperatorID'] = $this->user['EmployeeID'];

        $this -> dal_cs -> where($array_id) -> save($data);

        $this -> success('删除成功！');
    }
    //客户等级
    public function customerRank(){
        $this->assign("crumbs_title","客户等级");
        $this->display();
    }
    //加载客户等级
    public function loadCustomerRank(){
        $value = i('search');
        $offset = i("offset");
        $limit = i("limit");
        $where['DelFlag'] = 0;
        if($value){
            $where['CtmRankName'] = array('LIKE',"%$value%");
        }
        $count = $this->dal_ck -> where($where) -> order('Sort desc') -> count();
        $res = $this->dal_ck -> where($where) -> limit($offset,$limit) -> order('Sort desc') -> select();
        foreach($res as &$v){
            $v['OperatorName'] =$this->emp -> where(array('EmployeeID' => $v['OperatorID'])) -> getField('Name');
        }
        $list_array= array("total"=>$count,"rows"=>$res?$res:array());
        echo json_encode($list_array);
    }
    //增加客户等级
    public function addCustomerRank(){
        if(IS_POST){
            $data = i('post.');
            $data['OperatorID'] = $this->user['EmployeeID'];
            $data['ModifyTime'] = date('Y-m-d H:i:s');
            $this->dal_ck -> add($data);
            $this->success('增加成功！');
        }else{
            $this-> display('editCustomerRank');
        }
    }
    //修改客户等级
    public function editCustomerRank(){
        $id = i('id');
        $where['CtmRankID'] = $id;
        $where['DelFlag'] = 0;
        if(IS_POST){
            $data = i('post.');
            $data['ModifyTime'] = date('Y-m-d H:i:s');
            $data['OperatorID'] = $this->user['EmployeeID'];
            $this -> dal_ck -> where($where) -> save($data);
            $this->success('修改成功！');
        }else{
            //客户信息
            $res = $this-> dal_ck -> where($where)-> find();
            $this->assign('info',$res);
            $this-> display();
        }
    }
    //删除客户等级
    public function delCustomerRank(){
        $array_id['CtmRankID'] = array('in',$_POST['ids']);
        $data['DelFlag'] = 1;
        $data['ModifyTime'] = date('Y-m-d H:i:s');
        $data['OperatorID'] = $this->user['EmployeeID'];
        $this -> dal_ck -> where($array_id) -> save($data);

        $this -> success('删除成功！');
    }
    //角色关系
    public function customerRole(){
        $this->assign("crumbs_title","角色关系");
        $this->display();
    }
    //加载角色关系
    public function loadCustomerRole(){
        $value = i('search');
        $offset = i("offset");
        $limit = i("limit");
        $where['DelFlag'] = 0;
        if($value){
            $where['CtmRoleName'] = array('LIKE',"%$value%");
        }
        $count = $this->dal_cr -> where($where) -> order('Sort desc') -> count();
        $res = $this->dal_cr -> where($where) -> limit($offset,$limit) -> order('Sort desc') -> select();
        foreach($res as &$v){
            $v['OperatorName'] =$this->emp -> where(array('EmployeeID' => $v['OperatorID'])) -> getField('Name');
        }
        $list_array= array("total"=>$count,"rows"=>$res?$res:array());
        echo json_encode($list_array);
    }
    //新增角色关系
    public function addCustomerRole(){
        if(IS_POST){
            $data = i('post.');
            $data['OperatorID'] = $this->user['EmployeeID'];
            $data['ModifyTime'] = date('Y-m-d H:i:s');
            $this->dal_cr -> add($data);
            $this->success('增加成功！');
        }else{
            $this-> display('editCustomerRole');
        }
    }
    //修改角色关系
    public function editCustomerRole(){
        $id = i('id');
        $where['CtmRoleID'] = $id;
        $where['DelFlag'] = 0;
        if(IS_POST){
            $data = i('post.');
            $data['ModifyTime'] = date('Y-m-d H:i:s');
            $data['OperatorID'] = $this->user['EmployeeID'];
            $this -> dal_cr -> where($where) -> save($data);
            $this->success('修改成功！');
        }else{
            //角色关系
            $res = $this-> dal_cr -> where($where)-> find();
            $this->assign('info',$res);
            $this-> display();
        }
    }
    //删除角色关系
    public function delCustomerRole(){
        $array_id['CtmRoleID'] = array('in',$_POST['ids']);
        $data['DelFlag'] = 1;
        $data['ModifyTime'] = date('Y-m-d H:i:s');
        $data['OperatorID'] = $this->user['EmployeeID'];
        $this -> dal_cr -> where($array_id) -> save($data);
        $this -> success('删除成功！');
    }
    //亲密程度
    public function customerClose(){

        $this->assign("crumbs_title","亲密程度");
        $this->display();
    }
    //加载亲密程度
    public function loadCustomerClose(){
        $value = i('search');
        $where['DelFlag'] = 0;
        $offset = i("offset");
        $limit = i("limit");
        if($value){
            $where['CtmCloseName'] = array('LIKE',"%$value%");
        }
        $count = $this->close -> where($where) -> order('Sort desc') -> count();
        $res = $this->close -> where($where) -> limit($offset,$limit) -> order('Sort desc') -> select();
        foreach($res as &$v){
            $v['OperatorName'] =$this->emp -> where(array('EmployeeID' => $v['OperatorID'])) -> getField('Name');
        }
        $list_array= array("total"=>$count,"rows"=>$res?$res:array());
        echo json_encode($list_array);
    }
    //添加亲密程度
    public function addCustomerClose(){
        if(IS_POST){
            $data = i('post.');
            $data['OperatorID'] = $this->user['EmployeeID'];
            $data['ModifyTime'] = date('Y-m-d H:i:s');
            $this->close -> add($data);
            $this->success('增加成功！');
        }else{
            $this-> display('editCustomerClose');
        }
    }
    //修改亲密程度
    public function editCustomerClose(){
        $id = i('id');
        $where['CtmCloseID'] = $id;
        $where['DelFlag'] = 0;
        if(IS_POST){
            $data = i('post.');
            $data['ModifyTime'] = date('Y-m-d H:i:s');
            $data['OperatorID'] = $this->user['EmployeeID'];
            $this -> close -> where($where) -> save($data);
            $this->success('修改成功！');
        }else{
            //角色关系
            $res = $this-> close -> where($where)-> find();
            $this->assign('info',$res);
            $this-> display();
        }
    }
    //删除亲密程度
    public function delCustomerClose(){
        $array_id['CtmCloseID'] = array('in',$_POST['ids']);
        $data['DelFlag'] = 1;
        $data['ModifyTime'] = date('Y-m-d H:i:s');
        $data['OperatorID'] = $this->user['EmployeeID'];
        $this -> close -> where($array_id) -> save($data);
        $this -> success('删除成功！');
    }
    //客户问题需求状态
    public function demandState(){
        $this->display();
    }
    //加载客户问题需求列表
    public function loadDemandState(){
        $value = i('search');
        $offset = i("offset");
        $limit = i("limit");
        $where['DelFlag'] = 0;
        if($value){
            $where['FbStatusName'] = array('LIKE',"%$value%");
        }
        $count = $this->status -> where($where) -> order('Sort desc') -> count();
        $res = $this->status -> where($where) -> limit($offset,$limit) -> order('Sort desc') -> select();
        foreach($res as &$v){
            $v['OperatorName'] =$this->emp -> where(array('EmployeeID' => $v['OperatorID'])) -> getField('Name');
    }
        $list_array= array("total"=>$count,"rows"=>$res?$res:array());
        echo json_encode($list_array);

    }
    //添加问题状态
    public function addDemandState(){
        if(IS_POST){
            $_POST['OperatorID'] = $this->user['EmployeeID'];
            $_POST['ModifyTime'] = date('Y-m-d H:i:s');
            $this->status -> add($_POST);
            $this->success('增加成功！');
        }else{
            $this-> display('editDemandState');
        }
    }
    //修改问题状态
    public function editDemandState(){
        $id = i('id');
        if(IS_POST){
            $_POST['ModifyTime'] = date('Y-m-d H:i:s');
            $_POST['OperatorID'] = $this->user['EmployeeID'];
            $this -> status -> where(array('FbStatusID'=>$id,'DelFlag'=>0)) -> save($_POST);
            $this->success('修改成功！');
        }else{
            //客户信息
            $where['FbStatusID'] = $id;
            $where['DelFlag'] = 0;
            $res = $this-> status -> where($where)-> find();
            $this->assign('info',$res);
            $this-> display();
        }

    }
    //删除问题状态
    public function delDemandState(){
        $array_id['FbStatusID'] = array('in',$_POST['ids']);
        $data['DelFlag'] = 1;
        $data['OperatorID'] = $this->user['EmployeeID'];
        $data['ModifyTime'] = date('Y-m-d H:i:s');
        $this -> status -> where($array_id) -> save($data);
        $this -> success('删除成功！');
    }
    //问题优先级
    public function demandPriority(){
        $this->assign("crumbs_title","问题优先级");
        $this->display();
    }
    //加载问题优先级列表
    public function loadDemandPriority(){
        $value = i('search');
        $offset = i("offset");
        $limit = i("limit");
        $where['DelFlag'] = 0;
        if($value){
            $where['FbPriorityName'] = array('LIKE',"%$value%");
        }
        $count = $this->Priority -> where($where) -> order('Sort desc') -> count();
        $res = $this->Priority -> where($where) -> limit($offset,$limit) -> order('Sort desc') -> select();
        foreach($res as &$v){
            $v['OperatorName'] =$this->emp -> where(array('EmployeeID' => $v['OperatorID'])) -> getField('Name');
        }
        $list_array= array("total"=>$count,"rows"=>$res?$res:array());
        echo json_encode($list_array);
    }
    //添加问题优先级
    public function addDemandPriority(){
        if(IS_POST){
            $_POST['OperatorID'] = $this->user['EmployeeID'];
            $_POST['ModifyTime'] = date('Y-m-d H:i:s');
            $this->Priority -> add($_POST);
            $this->success('增加成功！');
        }else{
            $this-> display('editDemandPriority');
        }
    }
    //修改问题优先级
    public function editDemandPriority(){
        $id = i('id');
        $where['FbPriorityID'] = $id;
        $where['DelFlag'] = 0;
        if(IS_POST){
            $_POST['ModifyTime'] = date('Y-m-d H:i:s');
            $_POST['OperatorID'] = $this->user['EmployeeID'];
            $this -> Priority -> where(array($where)) -> save($_POST);
            $this->success('修改成功！');
        }else{
            //客户信息
            $res = $this-> Priority -> where($where)-> find();
            $this->assign('info',$res);
            $this-> display();
        }
    }
    //删除问题优先级
    public function delDemandPriority(){
        $array_id['FbPriorityID'] = array('in',$_POST['ids']);
        $data['DelFlag'] = 1;
        $data['OperatorID'] = $this->user['EmployeeID'];
        $data['ModifyTime'] = date('Y-m-d H:i:s');
        $this -> Priority -> where($array_id) -> save($data);
        $this -> success('删除成功！');
    }
    //客户问题需求
    public function demand(){
        //问题状态
        $res = $this -> status -> where(array('DelFlag'=>0)) -> select();
        $type = get_user_type();
        //问题优先级
        $result = $this ->Priority -> where(array('DelFlag'=>0)) -> select();

        //查出开发人员的所有
        $where['DepartmentNum'] = 3;
        $where['DelFlag'] = 0;
        $where['Status'] = 0;
        $resu = $this->emp -> where($where) -> select();
        $this->assign('infos',$resu);

        $this->assign('info',$res);
        $this->assign('result',$result);
        $this->assign('type',$type);
        $this->display();
    }
    public function loadDemand(){
        $fbtype = i('fbtype');
        $state = i('state');
        $receive = i('receive');
        $follow = i('follow');
        $allot = i('allot');
        $name = i('name');
        $priority = i('priority');
        $timeA = i("timeA");
        $timeB = i("timeB");
        $timeC = i("timeC");
        $timeD = i("timeD");
        $timeE = i("timeE");
        $timeF = i("timeF");
        $bianHao = i("bianHao");
        $accept = i("accept");
        $emp = i("emp");
        if($timeA && !$timeB){
            $where["F.FbTime"] = array("gt",$timeA);
        }
        if($timeA && $timeB){
            $where["F.FbTime"] = array("between",array($timeA,$timeB));
        }
        if(!$timeA && $timeB){
            $where["F.FbTime"] = array("lt",$timeB);
        }
        if($timeC && !$timeD){
            $where["F.PlanTime"] = array("gt",$timeC);
        }
        if($timeC && $timeD){
            $where["F.PlanTime"] = array("between",array($timeC,$timeD));
        }
        if(!$timeC && $timeD){
            $where["F.PlanTime"] = array("lt",$timeD);
        }
        if($timeE && empty($timeF)){
            $where["F.SolveTime"] = array("gt",$timeE);
        }
        if($timeE && $timeF){
            $where["F.SolveTime"] = array("between",array($timeE,$timeF));
        }
        if(empty($timeE) && $timeF){
            $where["F.SolveTime"] = array("lt",$timeF);
        }
        if(!empty($emp) && isset($emp)){
            $where["F.EmpID"] = $emp;
        }
       if($fbtype){
           $where['F.FBtype'] = $fbtype;
       }
        if($allot==1){
            $where['F.allotted'] = array('GT',0);
        }else if($allot==2){
            $where['F.allotted'] = array('eq',0);
        }
        if($state){
            $isState = I('isState');
            if($isState ==2){
                $where['F.FbStatusID'] = array('neq',$state);
            }else{
                $where['F.FbStatusID'] = $state;
            }
       }
        if($bianHao){
           $where['F.FeedbackID'] = $bianHao;
       }
        if($priority){
           $where['F.FbPriority'] = $priority;
       }
        if($receive){
           $where['F.Receive'] = $receive;
       }
        if($accept){
           if($accept==3){
               $where['FBtype']=1;
               $where['Accept']=array('EXP','is null');
           }else{
               $where['FBtype']=1;
               $where['Accept']=$accept;
           }
       }
        if($name){
            //$where['c.ShortName'] = array('like',"%$name%");
            $where['c.ShortName|e.Name'] = array('like',"%$name%");

        }

        if(!get_user_name()){
            if(get_user_type()==1){
                $depar =$this->emp -> where(array('EmployeeID' => get_user_id())) -> getField('DepartmentNum');
                if($depar == '1001'){
                    $where['F.CtmID'] = get_user_id();
                    $where['F.Sources'] = get_user_type();
                }
                if($follow){
                    $user = get_user_id().',';
                    $where['F.Follows'] = array('like',"%{$user}%");
                }

            }else if(get_user_type()==3){

                $where['F.CustomerID'] = get_user_id();//当他是客户，就能看到跟自己想关的问题

            }else{
                $where['F.CtmID'] = get_user_id();
                $where['F.Sources'] = get_user_type();
            }
        }else{
            if($follow){
                $user = get_user_id().',';
                $where['F.Follows'] = array('like',"%{$user}%");
            }
        }

        switch(get_user_type()){
            case 1:
                $table = $this->emp;
                $field = 'Name';
                $m = 'EmployeeID';
                break;
            case 2:
                $table = M('agent');
                $field = 'CompanyName';
                $m = 'AgentID';
                break;
            case 3:
            $table = $this ->dal;
            $field = 'ShortName';
            $m = 'CustomerID';
            break;
        }
        $search_value = i('search');
        $offset = i("offset");
        $limit = i("limit");
        if($search_value){
            $where['F.Title|F.Description'] = array('like',"%$search_value%");
        }
        $sort = i('sort');

        if($sort){
            if($sort=='FbPriorityName'){
                $sort= 'FbPriority';
            }
            $order ='F.'.$sort.' '.i('order');
            /// dump($order);exit;
        }else{
            $order = 'F.FbTime desc';
        }
        $where['F.DelFlag'] = 0;
        $count = $this->feedback -> alias("F")
            ->join('customer as c on c.CustomerID = F.CustomerID')
            ->join('employee as e on e.EmployeeId = F.CtmID','LEFT')
            -> field('c.ShortName as CustomerName,e.Name as EmployeeName,F.*')
            -> where($where)
            -> order($order)
            -> limit($offset,$limit)
            -> count();
        $res = $this->feedback -> alias("F")
            ->join('customer as c on c.CustomerID = F.CustomerID')
            ->join('employee as e on e.EmployeeId = F.CtmID','LEFT')
            -> field('c.ShortName as CustomerName,e.Name as EmployeeName,F.*')
            -> where($where)
            -> order($order)
            -> limit($offset,$limit)
            -> select();
        $zhengz = "/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg]))[\'|\"].*?[\/]?>/";
        foreach($res as &$v){
            $tmp_allot_name = $this -> emp -> where(array('EmployeeID' => $v['allotted'])) -> getField('Name');//分配者名称
            $tmp_opert_name = $this -> emp -> where(array('EmployeeID' => $v['EmpID'])) -> getField('Name');//领取人名称
            $v['AllotName'] = $tmp_allot_name . '/' . $tmp_opert_name;

            $v['type'] = $v['FBtype']==1?'系统新功能':'系统漏洞';
            $v['Description'] = htmlspecialchars_decode($v['Description']);
            //过滤掉内容的图片
            $v['Description'] = preg_replace($zhengz,'',$v['Description']);
            $v['Description'] = strip_tags($v['Description']);
            //截取字符串
            $v['Description']= msubstr($v['Description'],0,40);
            //问题状态
            //过滤时间的小时分；
            $arr = explode(' ',$v['PlanTime']);
            $v['PlanTime'] = $arr[0];

            if($v['FBtype']==1 && $v['Accept']==null){
                $v['State'] = '未受理';
            }else{
                if($v['FbStatusID']){
                    $v['State'] = $this -> status -> where(array('FbStatusID' => $v['FbStatusID'])) -> getField('FbStatusName');

                }else{
                    $v['State'] = '待解决';
                }
            }

            $v['FbPerson'] = $table -> where(array($m => $v['CtmID'])) -> getField($field);
            //处理状态
            $v['FbPriorityName'] = $this -> Priority -> where(array('FbPriorityID' => $v['FbPriority'])) -> getField('FbPriorityName');
            //操作人

           // $v['OperatorName'] =$this->emp -> where(array('EmployeeID' => $v['OperatorID'])) -> getField('Name');


        }
        $list_array= array("total"=>$count,"rows"=>$res?$res:array());
        echo json_encode($list_array);

    }
    //添加客户需求
    public function addDemand(){
        $userID = get_user_id();
        $type = get_user_type();
        if(IS_POST){
            $data = i('post.');
            $data['Sources'] = $type;
            if($type ==3){
                $data['CustomerID'] = $userID;
            }else{
                $data['CustomerID'] = $this->dal ->where(array('ShortName'=>$data['ShortName'])) -> getfield('CustomerID');
                if(!$data['ShortName'] || !$data['CustomerID']){
                    $this -> error('请选择客户！');
                }
                if($type==1){
                    $data['Follows'] = $userID.',';
                }
            }
            $data['Title'] = M('feedbacktitle') -> where(array('FbTitleID'=>$data['titleID'])) -> getField("FbTitleName");
            $data['CtmID'] = $userID;

            //问题状态被写死了要改在这里
            $data['FbStatusID'] = 1;
            $data['Receive'] = 1;

            $data['FbTime'] = date('Y-m-d H:i:s');
           if($type ==1){
               $data['OperatorID'] = $userID;
           }
            $data['ModifyTime'] = date('Y-m-d H:i:s');
            $log['RelationID'] =$this->feedback -> add($data);
            $log['Type'] = 1;
            $log['Time'] = date('Y-m-d H:i:s');

            if($type==1){

                $name = $this -> emp -> where(array('EmployeeID'=>$userID)) -> getField("Name");
                $log['Content'] = $name.'添加了这个问题';
            }else if($type==2){
                $name = M('agent') -> where(array('AgentID'=>$userID)) -> getField("CompanyName");
                $log['Content'] = '代理'.$name.'添加了这个问题';
            }else{
                $name = $this -> dal -> where(array('CustomerID'=>$userID)) -> getField("ShortName");
                $log['Content'] = '客户'.$name.'添加了这个问题';
            }
            M('crmlog')->add($log);

            $this ->success('提交问题成功！');
        }else{

            $where['DelFlag'] = 0;
            $Priority = $this -> Priority -> where($where) -> order('Sort desc') -> getField("FbPriorityID,FbPriorityName");
            $where['Status'] = 0;
            $res = M('customer') -> where($where) -> getField("CustomerID,ShortName");
            $title = M('feedbacktitle') -> where($where) -> getField("FbTitleID,FbTitleName");
            $this->assign('cInfo',$res);
            $this->assign('title',$title);
            $this -> assign('Priority',$Priority);
            $this -> assign('CustomerID',$type);

            //如果有选择就id就遍历下内容$id
            $id = i('id');
            if($id){
                $a = explode(',',$id);
                $info = $this->feedback -> where(array('FeedbackID'=>$a[0])) -> find();
                $info['ShortName'] = $this->dal ->where(array('CustomerID'=>$info['CustomerID'])) -> getfield('ShortName');
                $info['TitleID'] = M('feedbacktitle') -> where(array('FbTitleName'=>$info['Title'])) -> getField("FbTitleID");
                $this->assign('info',$info);
            }

            $this -> display();
        }
    }
    //编辑
    public function editDemand(){
        $id = i('id');
        $userID = get_user_id();
        $type = get_user_type();
        if(IS_POST){
            $where['FeedbackID'] = $id;
            if($type==3){
                $_POST['CustomerID'] = $userID;
            }else{
                $_POST['OperatorID'] = $userID;
                $_POST['CustomerID'] = $this->dal ->where(array('ShortName'=>$_POST['ShortName'])) -> getfield('CustomerID');
                if(!$_POST['ShortName'] || !$_POST['CustomerID']){
                    $this -> error('请选择客户！');
                }
            }
            $_POST['Title'] = M('feedbacktitle') -> where(array('FbTitleID'=>$_POST['titleID'])) -> getField("FbTitleName");
            $_POST['ModifyTime'] = date('Y-m-d H:i:s');
            $res = $this->feedback -> where($where) -> save($_POST);

            $log['RelationID'] =$id;
            $log['Type'] = 1;
            $log['Time'] = date('Y-m-d H:i:s');

            if($type==1){

                $name = $this -> emp -> where(array('EmployeeID'=>$userID)) -> getField("Name");
                $log['Content'] = $name.'修改了这个问题';
            }else if($type==2){
                $name = M('agent') -> where(array('AgentID'=>$userID)) -> getField("CompanyName");
                $log['Content'] = '代理'.$name.'修改了这个问题';
            }else{
                $name = $this -> dal -> where(array('CustomerID'=>$userID)) -> getField("ShortName");
                $log['Content'] = '客户'.$name.'修改了这个问题';
            }
            M('crmlog')->add($log);

            $this ->success('编辑成功！');
        }else{
            $where['DelFlag'] = 0;
            $Priority = $this -> Priority -> where($where) -> order('Sort desc') -> getField("FbPriorityID,FbPriorityName");
            $where['Status'] = 0;
            $res = M('customer') -> where($where) -> getField("CustomerID,FullName");
            $where['FeedbackID'] = $id;
            $info = $this->feedback -> where($where) -> find();
            if(empty($info['FbStatusID'])){
                $info['stateName'] = '已提交，等待解决';
            }else{
                $info['stateName'] = $this->status -> where(array('FbStatusID' => $info['FbStatusID'])) -> getField('FbStatusName');
            }
            if(empty($info['PlanTime'])){
                $info['PlanTime'] = '暂无';
            }
            if(empty($info['SolveTime'])){
                $info['SolveTime'] = '暂无';
            }
            if(empty($info['EmpID'])){
                $info['EmpName'] = '暂无';
            }else{
                $info['EmpName'] = $this->emp -> where(array('EmployeeID' => $info['EmpID'])) -> getField('Name');
            }
            $info['ShortName'] = $this->dal ->where(array('CustomerID'=>$info['CustomerID'])) -> getfield('ShortName');
            $info['TitleID'] = M('feedbacktitle') -> where(array('FbTitleName'=>$info['Title'])) -> getField("FbTitleID");
            $title = M('feedbacktitle') -> where($where) -> getField("FbTitleID,FbTitleName");
            $this->assign('title',$title);
            $this -> assign('CustomerID',$type);
            $this -> assign('Priority',$Priority);
            $this->assign('cInfo',$res);
            $this->assign('info',$info);
            $this -> display();
        }
    }
    //删除问题需求
    public function delDemand(){
        $array_id['FeedbackID'] = array('in',$_POST['ids']);
        $data['DelFlag'] = 1;
        $data['OperatorID'] = $this->user['EmployeeID'];
        $data['ModifyTime'] = date('Y-m-d H:i:s');
        $this -> feedback -> where($array_id) -> save($data);


        $log['Type'] = 1;
        $log['Time'] = date('Y-m-d H:i:s');
        $name = $this -> emp -> where(array('EmployeeID'=> $data['OperatorID'])) -> getField("Name");
        $log['Content'] = $name.'删除了这个问题';

        $arr = explode(',',$array_id['FeedbackID']);
        foreach($arr as $v){
            $log['RelationID'] =$v;
            M('crmlog')->add($log);
        }


        $this -> success('删除成功！');

    }
    //查看问题需求详情
    public function seeDemand(){
        $id = i('id');
        $where['FeedbackID'] = $id;
        $info = $this->feedback -> where($where) -> find();
        $info['typeName'] = $info['FBtype']==1?'系统新功能':'系统漏洞';
        $info['EmpName'] = $this -> emp -> where(array('EmployeeID'=>$info['EmpID'])) -> getField('Name');
        $info['Description'] = htmlspecialchars_decode($info['Description']);
        $this->assign('info',$info);

        //日记查询
        $map['type'] = 1;
        $map['RelationID'] = $id;
        $data = M('crmlog')->where($map)->select();
        $this->assign('data',$data);
        $this->display();
    }
    //领取问题需求
    public function receiveDemand(){
        $id = i('id');
        if(IS_POST){
            $where['FeedbackID'] = $id;
            $Receive = $this->feedback -> where($where) -> getField('Receive');
            $Follows = $this->feedback -> where($where) -> getField('Follows');
            if($Receive == 2){
                $this -> success('该问题已经领取了！');
                exit;
            }
            $data = i('post.');
            $data['Receive'] = 2;
            $data['Follows'] = $Follows.get_user_id().',';
            $data['OperatorID'] = $this->user['EmployeeID'];
            $data['EmpID'] = $this->user['EmployeeID'];
            $data['ModifyTime'] = date('Y-m-d H:i:s');
            if(empty($data["PlanTime"])) unset($data["PlanTime"]);
            $res = $this->feedback -> where($where) -> save($data);
            $log['Type'] = 1;
            $log['RelationID'] = $id;
            $log['Time'] = date('Y-m-d H:i:s');
            $name = $this -> emp -> where(array('EmployeeID'=> $data['OperatorID'])) -> getField("Name");
            $log['Content'] = $name.'领取了这个问题';
            M('crmlog')->add($log);

            $this ->success('领取成功！');

        }else{
            $where['DelFlag'] = 0;
            //查找解决状态
            $tInfo = $this->status -> where($where) -> order('Sort') -> getField('FbStatusID,FbStatusName');
            $where['FeedbackID'] = $id;
            $info = $this->feedback -> where($where) -> find();
            $info['customerName'] = M('customer') -> where(array('CustomerID' => $info['CustomerID'])) -> getField('FullName');
            $info['type'] = $info['FBtype']==1?'系统新功能':'系统漏洞';
            if(!empty($info['FbPriority'])){
                $info['FbPriorityName'] = $this -> Priority -> where(array('FbPriorityID' => $info['FbPriority'])) -> getField('FbPriorityName');
            }
            $this -> assign('tInfo',$tInfo);
            $this -> assign('info',$info);
            $this -> display();
        }

    }
    //确认问题
    public function confirmDemand(){
        $id = i('id');

        if(IS_POST){
            $where['FeedbackID'] = $id;
            //$info = $this->feedback -> where($where) -> getfield('Receive');
            $Follows = $this->feedback -> where($where) -> getfield('Follows');
            //if($info == 3){
            //    $this->error('该问题已经确认了！');
            //}
            //
            $data = i('post.');
            $data['OperatorID'] = $this->user['EmployeeID'];
            $data['ModifyTime'] = date('Y-m-d H:i:s');
            //$data['Receive'] = 3;
            $data['Follows'] = $Follows.get_user_id().',';
            $this->feedback -> where($where) -> save($data);

            $log['Type'] = 1;
            $log['RelationID'] = $id;
            $log['Time'] = date('Y-m-d H:i:s');
            $name = $this -> emp -> where(array('EmployeeID'=> $data['OperatorID'])) -> getField("Name");
            $tInfo = $this->status -> where(array('FbStatusID'=>$data['FbStatusID'])) -> getField('FbStatusName');
            $log['Content'] = $name.'确认了这个问题的状态：'.$tInfo;
            M('crmlog')->add($log);
            $this -> success('确认成功！');
        }else{
            $where['DelFlag'] = 0;
            //查找解决状态
            $tInfo = $this->status -> where($where) -> order('Sort') -> getField('FbStatusID,FbStatusName');
            $where['FeedbackID'] = $id;
            $info = $this->feedback -> field('FbStatusID,Title,FeedbackID,SolveTime,Note,EmpID') -> where($where) -> find();//print_r($info);die();
            $info['EmpName'] = $this -> emp -> where(array('EmployeeID'=>$info['EmpID'])) -> getField('Name');
            $this -> assign('tInfo',$tInfo);
            $this -> assign('info',$info);

            $this -> display();
        }
    }
    //批量确认问题
    public function allConfirm(){

        if(IS_POST){
            $id = I('id');
            $data['FbStatusID'] =I('FbStatusID');
            $data['OperatorID'] = get_user_id();
            $data['ModifyTime'] = date('Y-m-d H:i:s');
            $where['FeedbackID'] = array('in',$id);

            $this->feedback -> where($where) -> save($data);
            $arr  = explode(',',$id);
            $tInfo = $this->status -> where(array('FbStatusID'=>$data['FbStatusID'])) -> getField('FbStatusName');
            $log['Type'] = 1;
            $log['Time'] = date('Y-m-d H:i:s');
            $name = $this -> emp -> where(array('EmployeeID'=> $data['OperatorID'])) -> getField("Name");
            $log['Content'] = $name.'批量确认了这个问题的状态：'.$tInfo;
            M('crmlog')->add($log);
            foreach($arr as $v){
                $log['RelationID'] = $v;
                M('crmlog')->add($log);
            }
            $this -> success('修改状态成功！');
        }else{
            $where['DelFlag'] = 0;
            //查找解决状态
            $tInfo = $this->status -> where($where) -> order('Sort') -> getField('FbStatusID,FbStatusName');
            $this -> assign('tInfo',$tInfo);
            $this -> display();
        }

    }
    //分配
    public function allottedDemand(){
        $where['DepartmentNum'] = 3;
        $where['DelFlag'] = 0;
        $where['Status'] = 0;
        $res = $this->emp -> where($where) -> select();
        $this->assign('infos',$res);
        $this -> display();
    }
    //分配流程
    public function editAllottedDemand(){
        $id = i('ids');
        $fid = i('cid');
        $array = explode(',',$id);
        $data['allotted'] = get_user_id();
        $data['PlanTime'] = i('planTime');
        $data['OperatorID'] = get_user_id();
        $data['ModifyTime'] = date('Y-m-d H:i:s');
        //$data['FBtype'] = 4;
        $content = '';
        $zhengz = "/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg]))[\'|\"].*?[\/]?>/";
        $log['Type'] = 1;
        $log['Time'] = date('Y-m-d H:i:s');
        $name = $this -> emp -> where(array('EmployeeID'=> $data['OperatorID'])) -> getField("Name");
        $log['Content'] = $name.'分配了这个问题';
        foreach($array as $v){
            $info = $this->feedback -> where(array('FeedbackID'=>$v)) -> find();
            $info['Description'] = htmlspecialchars_decode($info['Description']);
            //过滤掉内容的图片
            $info['Description'] = preg_replace($zhengz,'',$info['Description']);
            $info['Description'] = msubstr($info['Description'],0,20);
            $content .= $info['FeedbackID'].':'.strip_tags($info['Description']).'<br>';
            $data['EmpID'] = $fid;

            $data['FbStatusID'] = 4;

            $data['Follows'] = $info['Follows'].$fid.',';
            $this->feedback -> where(array('FeedbackID'=>$v)) -> save($data);
            $log['RelationID'] = $v;
            M('crmlog')->add($log);
        }

        $title = '请查看关于我的问题，领取相关问题！';
        $info = $this->emp -> where(array('EmployeeID'=>$fid)) -> find();
      //  $content = '温馨提醒，有bug要修改拉！请上果园CRM系统进行领取我的问题！';
       // echo $info['Email'];
        $res = sendEmail($info['Email'],$title,$content);

        $this -> success('分配成功！');

        //$this -> success('分配成功！');
    }
    //需求受理
    public function acceptDemand(){
        if(IS_POST){
            $data = I('post.');
            if($data['Accept']==1){
                $data['FbStatusID'] =5;//问题的状态改为不予解决 5
            }
            $res = $this->feedback -> where(array('FeedbackID'=>$data['id'])) -> find();
            if($res['Accept']==2){
                $this->error('该问题已经受理过了，不能在受理了！');
            }
            if($res['FBtype']==2){
                $this->error('该问题所属系统漏洞不能受理了！');
            }
            $data['OperatorID'] = get_user_id();
            $data['FbStatusID'] =1;
            $data['ModifyTime'] = date('Y-m-d H:i:s');
            $this->feedback -> where(array('FeedbackID'=>$data['id'])) -> save($data);
            $log['Type'] = 1;
            $log['Time'] = date('Y-m-d H:i:s');
            $name = $this -> emp -> where(array('EmployeeID'=> $data['OperatorID'])) -> getField("Name");
            $log['Content'] = $name.'受理了这个问题';
            $log['RelationID'] = $data['id'];
            M('crmlog')->add($log);

            $this->success('操作成功！');
        }else{
            $this -> display();
        }
    }

    public function MoCustomer(){
        $this -> display();
    }
    //加载公共客户
    public function loadMoCustomer(){
        // sort:id order:desc limit:10 offset:0
        $map['C.DelFlag'] = 0;
        //$page = i('page');
        //$rows = i('rows');
        $offset = i("offset");
        $limit = i("limit");
        $search_key = i('search_key');
        $search_value = i('search');

        $map['c.DeveloperID'] = array('exp','IS NULL');
        $sort = i('sort');
        $order = i('order');
        $reorder = "C.Sort desc";
        if(!empty($sort)){
            $reorder = "C.".$sort." ".$order;
        }
        $count = $this->dal->alias("C")->where($map)->order($reorder)->count();
        $list =  $this->dal->alias("C")
            ->join("customerstatus S on S.CtmStatusID = C.CtmStatusID","left")
            ->join("customerrank K on K.CtmRankID = C.CtmRankID","left")
            ->join("customerrole R on R.CtmRoleID = C.CtmRoleID","left")
            ->where($map)->field("C.*,S.CtmStatusName,K.CtmRankName,R.CtmRoleName")->limit($offset,$limit)->order($reorder)->select();
        foreach($list as $ct => $c){
            $list[$ct]["Status"] = $c["Status"] == 0 ? "正常" : "锁定";
            $list[$ct]["Sources"] = $c["Sources"] > 1 ? "代理" : "直营";
            if($c['DeveloperID']){
                if($c["Sources"] == 1){
                    $list[$ct]["DeveloperName"] = $this->emp -> where(array('EmployeeID' => $c['DeveloperID'])) -> getField('Name');
                }else{
                    $list[$ct]["DeveloperName"] = M('agent') -> where(array('AgentID' => $c['DeveloperID'])) -> getField('CompanyName');
                }
            }else{
                $list[$ct]["DeveloperName"] = '公共用户';
            }
        }
        $list_array= array("total"=>$count,"rows"=>$list?$list:array());
        echo json_encode($list_array);
    }
    public function addMoCustomer(){
        if(IS_POST){
            $data = i('post.');
            $data['Identifier'] = getCustomerIdentifier();
            $data['ModifyTime'] = date('Y-m-d H:i:s');
            $data['CreateTime'] = date('Y-m-d H:i:s');
            $data['OperatorID'] = $this->user['EmployeeID'];
            $where['LoginName'] = $data['LoginName'];
            $data['Sources'] = get_user_type();
            $result = $this->dal -> where($where) -> find();
            if($result){
                $this -> error('账号已经存在，请重新输入账号！');
                exit;
            }
            $res = $this->dal -> add($data);
            if($res){
                //为会员赋权限
                $dutyinfo = M('duty') -> where(array('Type'=> 3,'Status'=>1)) -> find();
                if($dutyinfo){
                    M('employee_duty') -> add(array('MemberID'=>$res,'DutyID'=>$dutyinfo['ID'],'Type'=> 3));
                }else{
                    $result =  M('duty') -> add(array('DutyName'=>'客户权限组','Type'=>3,'Module'=>'admin'));
                    M('employee_duty') -> add(array('MemberID'=>$res,'DutyID'=>$result,'Type'=> 3));
                }
                //添加成功要给初始化密码
                $string = get_string();
                $sa['Random']= $string->rand_string(6,1);
                $sa["Password"] = md5(md5($sa['Random']).$sa['Random']);
                $this->dal -> where(array('CustomerID'=>$res)) -> save($sa);
                $this -> success('添加成功！您账号的初始密码为'.$sa['Random']);
            }else{
                $this -> error('添加失败！');
            }

        }else{
            $where['DelFlag'] = 0;
            $res['cs'] = $this->dal_cs -> where($where) -> getField("CtmStatusID,CtmStatusName");
           // $res['cr'] = $this->dal_cr -> where($where) -> getField("CtmRoleID,CtmRoleName");
            $res['ck'] = $this->dal_ck -> where($where) ->getField("CtmRankID,CtmRankName");
            $res['co'] = $this->close -> where($where) -> getField("CtmCloseID,CtmCloseName");
            //  $arr =$this->dal_cs ->where($where)->order("Sort asc")->getField("CtmStatusID,CtmStatusName");
            $this->assign('cus',$res);
            $this -> display('editMoCustomer');
        }
    }
    public function editMoCustomer(){

        $id = i('id');
        if(IS_POST){
            $data = i('post.');
            $data['ModifyTime'] = date('Y-m-d H:i:s');
            $data['OperatorID'] = $this->user['EmployeeID'];
            $where['CustomerID'] = $id;
            //为会员赋权限
            $dutyinfo = M('duty') -> where(array('Type'=> 3,'Status'=>1)) -> find();
            if($dutyinfo){
                $d = M('employee_duty') -> where(array('MemberID'=>$id,'DutyID'=>$dutyinfo['ID'],'Type'=> 3)) -> find();
                if(!$d){
                    M('employee_duty') -> add(array('MemberID'=>$id,'DutyID'=>$dutyinfo['ID'],'Type'=> 3));
                }
            }else{
                $result =  M('duty') -> add(array('DutyName'=>'客户权限组','Type'=>3,'Module'=>'admin'));
                M('employee_duty') -> add(array('MemberID'=>$id,'DutyID'=>$result,'Type'=> 3));
            }
            $this->dal -> where($where) -> save($data);
            $this -> success('修改成功！');
        }else{
            $where['DelFlag'] = 0;
            $res['cs'] = $this->dal_cs -> where($where) -> getField("CtmStatusID,CtmStatusName");
         //  $res['cr'] = $this->dal_cr -> where($where) -> getField("CtmRoleID,CtmRoleName");
            $res['ck'] = $this->dal_ck -> where($where) ->getField("CtmRankID,CtmRankName");
            $res['co'] = $this->close -> where($where) -> getField("CtmCloseID,CtmCloseName");
            $where['CustomerID'] = $id;
            $info = $this->dal -> where($where) -> find();
            $this->assign('cus',$res);
            $this->assign('info',$info);
            $this -> display();
        }
    }
    //分配
    public function allotMoCustomer(){
        if(IS_POST){
            $data = i('post.');
            $where['CustomerID'] = array('in',$data['ids']);
            $res = $this->dal -> field('Sources') ->where($where) -> select();
            $info['DeveloperID'] = $data['cid'];
            foreach($res as $v){
                if($v['Sources']==1){
                    $this->dal -> where($where) -> save($info);
                }else{
                    $tishi = '代理的客户不能被分配！其他';
                }
            }
            $success = $tishi.'分配成功！';
            $this ->success($success);
        }else{
            $where['DepartmentNum'] = 1001;
            $where['DelFlag'] = 0;
            $where['Status'] = 0;
            $res = $this->emp -> where($where) -> select();
            $this->assign('infos',$res);
            $this -> display();
        }
    }
    //领取
    public function collectMoCustomer(){
        $ids = i('ids');
        $where['CustomerID'] = array('in',$ids);
        $info['DeveloperID'] = get_user_id();
        $this->dal -> where($where) -> save($info);
        $this ->success('领取成功！');
    }
    //导入客户到公共池
    public function importMoCustomer(){

        if(IS_POST){

            $ext = explode('.',$_POST['path']);
            $data = $this -> importExecl(ROOT_PATH.$_POST['path'],$ext[1]);
            foreach($data as $v){
                $v['CreateTime'] = date('Y-m-d H:i:s');
                $v['ModifyTime'] = date('Y-m-d H:i:s');
                $v['OperatorID'] = get_user_id();
                $v['Identifier'] = getCustomerIdentifier();
                $string = get_string();
                $v['Random']= $string->rand_string(6,1);
                $v['Sources']= 1;
                $v['Password'] = get_guoyuanPWD($v['Password'],$v['Random']);
                $this->dal -> add($v);
            }
            unlink(ROOT_PATH.$_POST['path']);
            $this -> success('导入成功！');
           // dump($data);
        }else{
            $this -> display();
        }
    }
    //下载模板
    public function download(){
        header("Content-type:application/xls");
        $filename = ROOT_PATH.'/Public/moban.xls';
        header("Content-Disposition:attachment;filename='moban.xls'");
        readfile("$filename");
    }
    //
    public function importExecl($filename,$ext){
        if(!file_exists($filename)){
            return array("error"=>0,'message'=>'文件没有被找到!');
        }
        if(strtolower($ext) == "xlsx"){
            $objReader = get_excel_read();
        }else{
            $objReader = get_excel_reader();
        }
        $PHPReader = $objReader->load($filename, "utf-8" );

        $sheet = $PHPReader->getSheet(0);
        $highestRow = $sheet->getHighestRow(); // 取得总行数
        $highestColumn = $sheet->getHighestColumn(); // 取得总列数
        $dateFormat = get_excel_dateFormat();
        $bill = array();
        for($i=2;$i<=$highestRow;$i++){
            $j = $i - 2;
            $bill[$j]["LoginName"] = $PHPReader->getActiveSheet()->getCell("A".$i)->getValue();
            $bill[$j]["ShortName"] = $PHPReader->getActiveSheet()->getCell("B".$i)->getValue();
            $bill[$j]["FullName"] =  $PHPReader->getActiveSheet()->getCell("C".$i)->getValue();
            $bill[$j]["CtmStatusID"] =  $PHPReader->getActiveSheet()->getCell("D".$i)->getValue();
            $bill[$j]["CtmRankID"] =  $PHPReader->getActiveSheet()->getCell("E".$i)->getValue();
            $bill[$j]["Contacter"] =  $PHPReader->getActiveSheet()->getCell("F".$i)->getValue();
            $bill[$j]["CtmRoleID"] =  $PHPReader->getActiveSheet()->getCell("G".$i)->getValue();
            $bill[$j]["Telephone"] =  $PHPReader->getActiveSheet()->getCell("H".$i)->getValue();
            $bill[$j]["Mobile"] =  $PHPReader->getActiveSheet()->getCell("I".$i)->getValue();
            $bill[$j]["Email"] =  $PHPReader->getActiveSheet()->getCell("J".$i)->getValue();
            $bill[$j]["WeChat"] =  $PHPReader->getActiveSheet()->getCell("K".$i)->getValue();
            $bill[$j]["Status"] =  $PHPReader->getActiveSheet()->getCell("L".$i)->getValue();
            $bill[$j]["Address"] =  $PHPReader->getActiveSheet()->getCell("M".$i)->getValue();
            $bill[$j]["CtmCloseID"] =  $PHPReader->getActiveSheet()->getCell("N".$i)->getValue();
            $bill[$j]["Password"] =  $PHPReader->getActiveSheet()->getCell("O".$i)->getValue();
            /*
            for($j=ord("A");$j<=ord($highestColumn);$j++){
                if($j == ord("A"))
                if($j==ord("C") || $j == ord("D")) /转换
                    echo gmdate("Y-m-d H:i:s", $dateFormat::ExcelToPHP($PHPReader->getActiveSheet()->getCell(chr($j).$i)->getValue()))."==";
                else if($j == ord("H")) //公式计算
                    echo $PHPReader->getActiveSheet()->getCell("E".$i)->getValue() * $PHPReader->getActiveSheet()->getCell("F".$i)->getValue()."==" ;
                else
                    echo $PHPReader->getActiveSheet()->getCell(chr($j).$i)->getValue() . "==";
            }*/
        }
        return $bill;
    }
    //文件上传
    public function uploadFile(){

        //判断类型
        $array = explode('.',$_FILES['import']['name']);
        if($array[1] != 'xls'){
            $info['state'] = 0;
            $info['info'] = '文件格式不对！';
        }
        //拼装新的路劲
        //新的文件名
        $fileName=md5(rand(1,10000)).time().'.'.$array[1];
        //新的目录
        $dir=__APP__.'/uploads/xls';
        if(!file_exists($dir)){
            mkdir($dir,0777);
            dump($dir);
        }
        $newFileName=$dir.'/'.$fileName;
        if(move_uploaded_file($_FILES['import']['tmp_name'],$newFileName)){
            $info['state'] = 1;
            $info['info'] = $fileName;
        }else{
            $info['state'] = 0;
            $info['info'] = '文件加载失败！';
        }
        $this->ajaxReturn($info);
    }
    //客户问题需求标题
    public function fbTitle(){
        $this->display();
    }
    //加载客户问题需求标题
    public function loadFbTitle(){
        $value = i('search');
        $offset = i("offset");
        $limit = i("limit");
        $where['DelFlag'] = 0;
        if($value){
            $where['FbStatusName'] = array('LIKE',"%$value%");
        }
        $count = M('feedbacktitle') -> where($where) -> order('Sort desc') -> count();
        $res = M('feedbacktitle') -> where($where) -> limit($offset,$limit) -> order('Sort desc') -> select();
        foreach($res as &$v){
            $v['OperatorName'] =$this->emp -> where(array('EmployeeID' => $v['OperatorID'])) -> getField('Name');
        }
        $list_array= array("total"=>$count,"rows"=>$res?$res:array());
        echo json_encode($list_array);

    }
    //添加问题标题
    public function addFbTitle(){
        if(IS_POST){
            $_POST['OperatorID'] = $this->user['EmployeeID'];
            $_POST['ModifyTime'] = date('Y-m-d H:i:s');
            M('feedbacktitle') -> add($_POST);
            $this->success('增加成功！');
        }else{
            $this-> display('editFbTitle');
        }
    }
    //修改问题标题
    public function editFbTitle(){
        $id = i('id');
        if(IS_POST){
            $_POST['ModifyTime'] = date('Y-m-d H:i:s');
            $_POST['OperatorID'] = $this->user['EmployeeID'];
            M('feedbacktitle') -> where(array('FbTitleID'=>$id,'DelFlag'=>0)) -> save($_POST);
            $this->success('修改成功！');
        }else{
            //客户信息
            $where['FbTitleID'] = $id;
            $where['DelFlag'] = 0;
            $res = M('feedbacktitle') -> where($where)-> find();
            $this->assign('info',$res);
            $this-> display();
        }

    }
    //删除问题标题
    public function delFbTitle(){
        $array_id['FbTitleID'] = array('in',$_POST['ids']);
        $data['DelFlag'] = 1;
        $data['OperatorID'] = $this->user['EmployeeID'];
        $data['ModifyTime'] = date('Y-m-d H:i:s');
        M('feedbacktitle') -> where($array_id) -> save($data);
        $this -> success('删除成功！');
    }

    //售前需求
    public function support(){
        $this->display();
    }

    public function loadSupport(){
        $value = i('search');
        $where['DelFlag'] = 0;
        $offset = i("offset");
        $limit = i("limit");
        if($value){
            $where['PayName'] = array('LIKE',"%$value%");
        }
        $count = $this->support -> where($where) ->  order('CreateTime desc') -> count();
        $res = $this->support -> where($where) -> limit($offset,$limit)-> order('CreateTime desc') ->select();
        foreach($res as &$v){
            //客户的名称
            $v['CustomerName'] = $this->dal ->where(array('CustomerID'=>$v['CustomerID'])) -> getfield('ShortName');
            $v['SponsorName'] = $this->emp ->where(array('EmployeeID'=>$v['SponsorID'])) -> getfield('Name');
            $v['AuditName'] = $this->emp ->where(array('EmployeeID'=>$v['AuditID'])) -> getfield('Name');
            $v['ProductName'] = $this->emp ->where(array('EmployeeID'=>$v['ProductID'])) -> getfield('Name');
            $v['AppointName'] = $this->emp ->where(array('EmployeeID'=>$v['AppointID'])) -> getfield('Name');
            $v['StatusName'] = getSupportStatus($v['Status']);
            $v['Remark'] = htmlspecialchars_decode($v['Remark']);
        }
        //$list_array= $res?$res:array();
        $list_array= array("total"=>$count,"rows"=>$res?$res:array());
        echo json_encode($list_array);
    }
    //添加售前
    public function addSupport(){
        if(IS_POST){
            $cusName = I('ShortName');
            $data = I('post.');
            unset($data['ShortName']);
            $data['DelFlag'] = 0;
            $data['SponsorID'] = get_user_id();
            $data['CreateTime'] = date('Y-m-d H:i:s');
            $data['ResponseTime'] = date('Y-m-d H:i:s');
            $data['CustomerID'] = $this->dal ->where(array('ShortName'=>$cusName)) -> getfield('CustomerID');
            if(!$cusName || !$data['CustomerID']){
                $this -> error('请选择客户！');
            }
            $res = $this->support -> add($data);
            if($res){
                $info['DepartmentNum'] = 1001;
                $info['isPriority'] = 1;
                $info['DelFlag'] = 0;
                $content = '温馨提醒：请登录果园CRM系统尽快处理售前编号为'.$res.'记录，并审核该售前申请！';
                $person = $this->emp -> field('Email') -> where($info) -> select();
                foreach($person as $v){
                    sendEmail($v['Email'],'有新售前啦！',$content);
                }
            }
            $this->success('申请成功！');
        }else{
            $this -> display('editSupport');
        }
    }

    //修改售前
    public function editSupport(){
        $id = I('id');
        if(IS_POST){
            $cusName = I('ShortName');
            $data = I('post.');
            unset($data['ShortName']);
            $data['CustomerID'] = $this->dal ->where(array('ShortName'=>$cusName)) -> getfield('CustomerID');
            if(!$cusName || !$data['CustomerID']){
                $this -> error('请选择客户！');
            }
            $this->support -> where(array('SupportID'=>$id)) -> save($data);
            $this->success('修改成功！');
        }else{
            $res = $this->support -> where(array('SupportID' =>$id)) -> find();
            $res['ShortName'] = $this->dal ->where(array('CustomerID'=>$res['CustomerID'])) -> getfield('ShortName');
            $this -> assign('info',$res);
            $this -> display();
        }

    }
    //删除售前
    public function delSupport(){

        $arr["SupportID"] = array("in",$_POST['ids']);
        $data['DelFlag'] = 1;
        if($this->support->where($arr)->save($data) !== false){
            $this -> success('删除成功！');
        }else{
            $this -> error("删除失败！");
        }
    }
    //审核售前
    public function auditSupport(){
        $id = I('id');
        if(IS_POST){
            $data = I('post.');
            $data['AuditID'] = get_user_id();
            $data['AuditTime'] = date('Y-m-d H:i:s');
            $data['ResponseTime'] = date('Y-m-d H:i:s');
           // dump($data);
            //审核的时候判断下状态的值，如果大于0就已经审核过了，不能在审核了
            $Status = $this->support -> where(array('SupportID'=>$id)) -> getfield('Status');
            if($Status == 0){
                $result = $this->support -> where(array('SupportID'=>$id)) -> save($data);
                if($result){
                    if($data['Status']==1){
                        $info['DepartmentNum'] = 7;
                        $info['isPriority'] = 1;
                        $content = '温馨提醒：请登录果园CRM系统尽快处理售前编号为'.$id.'记录，并指派项目负责人！';
                        $person = $this->emp -> field('Email') -> where($info) -> select();
                        foreach($person as $v){
                           sendEmail($v['Email'],'有新客户啦！',$content);
                        }
                    }
                    $this -> success('审核成功！');
                }else{
                        $this -> error('审核失败！');
                }

            }else{
                $this -> error('该售前已经审核过了！');
            }
        }else{
            $res = $this->support -> field('CustomerID,SupportID,SponsorID') -> where(array('SupportID' =>$id)) -> find();
            $res['ShortName'] = $this->dal ->where(array('CustomerID'=>$res['CustomerID'])) -> getfield('ShortName');
            $res['SponsorName'] = $this->emp ->where(array('EmployeeID'=>$res['SponsorID'])) -> getfield('Name');
            $this -> assign('info',$res);
            $this -> display();
        }
    }
    //指派售前
    public function appointSupport(){
        if(IS_POST){
            $data = I('post.');
            $info = $this->support -> where(array('SupportID'=>$data['id'])) -> find();
            if($info['Status']<3){
                if($info['Status']==2){
                    $this -> error('该申请被驳回，不能指派！');
                }
                $data['ProductID'] = get_user_id();
                $data['AppointTime'] = date('Y-m-d H:i:s');
                $data['Status'] = 3;
                $result = $this->support -> where(array('SupportID'=>$data['id'])) -> save($data);

                if($result){
                    $name =$this->emp ->where(array('EmployeeID'=>$data['AppointID'])) -> getfield('Name');
                    $content = '温馨提醒：请登录果园CRM系统尽快处理售前编号为'.$data['id'].'记录，跟进记录，与指派人'.$name.'一起完成对客户的拜访！';
                    $SponsorEmail = $this->emp -> where(array('EmployeeID'=>$info['SponsorID'])) -> getfield('Email');
                    sendEmail($SponsorEmail,'已指派人！'.$name,$content);
                    $AuditIDEmail = $this->emp -> where(array('EmployeeID'=>$info['AuditID'])) -> getfield('Email');
                    $content = '温馨提醒：请登录果园CRM系统尽快处理售前编号为'.$data['id'].'记录，已经处理！';
                    sendEmail($AuditIDEmail,'已经指派人！'.$name,$content);
                    $this -> success('指派成功！');
                }else{
                    $this -> error('指派失败！');
                }
            }else{

                $this -> error('该申请已经指派过了，不能在指派了！');
            }

        }else{
            $res = $this->emp ->where(array('DepartmentNum'=>7)) -> getField("EmployeeID,Name");
            $this->assign('info',$res);
            $this->display();
        }
    }
    //更改售前需求结果
    public function appointRes(){
        if(IS_POST){
            $id = I('id');
            $data['Status'] = I('Status');
            $this->support -> where(array('SupportID'=>$id)) -> save($data);
            $this->success('更改成功！');
        }else{
            $this->display();
        }
    }
    //查看售前
    public function seeSupport(){
        $id = I('id');
        $res = $this->support -> where(array('SupportID' =>$id)) -> find();
        $res['ShortName'] = $this->dal ->where(array('CustomerID'=>$res['CustomerID'])) -> getfield('ShortName');
        $res['Remark'] = htmlspecialchars_decode($res['Remark']);
        $this -> assign('info',$res);
        $this -> display();

    }



















}
