<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
		header("Location: /index.php/admin");
		$huge = new \Huge\Test();
		echo $huge->sayHello();
	}

}