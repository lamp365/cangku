<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/public/Static/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="/public/Static/bootstrap/css/bootstrap-theme.min.css" />
    <title>Document</title>
    <style>
        body{
            background: url('/public/Home/img/login_bg.jpg') no-repeat;
            background-size: 100%;
            height: 100%;
        }
        .main{
            width: 450px;
            height: 420px;
            margin: 150px auto;
            padding: 40px;
            background-color: #ffffff;
            border-radius: 4px;
            box-sizing: border-box;
            box-shadow: 0 1px 10px rgba(0, 0, 0, 0.2);
            position: relative;
        }
        .title{
            background: url('/public/Home/img/login_head.jpg') no-repeat;
            background-size: 100%;
            /*background: red;*/
            height: 78px;
            width: 100%;
            position: absolute;
            left: -25px;
            line-height: 70px;
            margin-top: 16px;
        }
        .title span{
            margin-left: 64px;
            font-size: 18px;
            color: #ffffff;
        }
        .cont{
            clear: both;
        }
        input[type='text']{
            border:1px solid #DCDEE0;
            vertical-align: middle;
            border-radius: 3px;
            height: 50px;
            padding: 0px 16px;
            font-size: 14px;
            color: #555555;
            outline: none;
            width: 100%;
        }
        .hr15{
            height: 20px;
            border: none;
            width: 100%;
            padding: 0px;
            margin: 0px;
        }
        input[type='submit']{
            display:inline-block;
            vertical-align:middle;
            padding:12px 24px;
            margin:0px;
            font-size:18px;
            line-height:24px;
            text-align:center;
            white-space:nowrap;
            vertical-align:middle;
            cursor:pointer;
            color:#ffffff;
            background-color:#27A9E3;
            border-radius:3px;
            border:none;
            -webkit-appearance:none;
            outline:none;
            width:100%;
        }
        #sub{
            display: inline-block;
            vertical-align: middle;
            padding: 12px 24px;
            margin: 0px;
            font-size: 18px;
            line-height: 24px;
            text-align: center;
            white-space: nowrap;
            vertical-align: middle;
            cursor: pointer;
            color: #ffffff;
            background-color: #27A9E3;
            border-radius: 3px;
            border: none;
            -webkit-appearance: none;
            outline: none;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="main">
        <div class="title">
            <span>网站标题</span>
        </div>
        <div style="position: relative;height: 120px;width: 100%"></div>
        <div class="cont">
            <input type="text" id="mobile" name="mobile" placeholder="请输入登录手机">
            <hr class="hr15">
            <input type="text" id="password" name="password" placeholder="请输入账户密码">
            <hr class="hr15">
            <button   type="submit" id="sub">登 录</button>

        </div>
    </div>
</body>
<script type="text/javascript" src='/public/Static/jquery-1.11.2.min.js'></script>
<script>
    //ajax 登录
    $('#sub').click(function(){


        var phone = $('#mobile').val();
        var password = $('#password').val();
        if(!phone || !password){
            alert('手机号码或者密码不能为空');
            return false;
        }
        var info = {phone:phone,password:password}
        $.post("/signin", info, function (data) {

            console.log(data);
            if (data.status == 1) {
                alert('登录成功');
                location.href = '/center'
            } else {
                alert(data.info)
            }

        })

    })

</script>
</html>