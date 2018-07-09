<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title></title>
    <link rel="stylesheet" href="/public/Home/css/index.css" />
    
</head>
<body>
<div class="contain">
    <header class="header">
        <div class="new-head">
            <div class="logo"><img src="/public/Home/img/logo.png"/></div>
            <div class="head_men">
                <nav class="men">
                    <ul>
                        <li><a href="#">首页</a></li>
                        <li><a href="#">关于我们</a></li>
                        <li class="action"><a href="#">个人中心</a></li>
                    </ul>
                </nav>
                <div class="head_right">
                    <div class="ShippingCenter">
                        <i class="icon-regin"></i>
                        <div class="text">退出登录</div>
                    </div>
                    <div class="ShippingCenter action">
                        <i class="icon-ShippingCenter"></i>
                        <div class="text">报货中心</div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    

    <section class="section">
        <div class="section-title">
            <i class="icon-title"></i>
            <h2>平台公告：</h2>
            欢迎有您，我们将竭力为您服务！
        </div>
        <div class="row">
            <div class="row_men">
                <div class="row_regin">
                    <div class="logo_rgi"><img src="/public/Home/img/logo_rgi.png"/></div>
                    <div class="text_row">
                        <h3>kevin[<span>组员</span>]</h3>
                        <div class="phone">13960947857</div>
                        <div class="text_suz">属组：<span>12312请问请问请问请问3</span></div>
                        <div class="text_suz">余额：￥<span>123123</span></div>
                    </div>
                    <nav class="row_regin_men">
                        <ul>
    <li class="center_menu <?php if($cont_name == 'Center'): ?>action<?php endif; ?>"><a href="<?php echo U('Center/index');?>"><i class="zeone"></i><span class="text">账户信息</span></a></li>
    <li class="center_menu <?php if($cont_name == 'Member'): ?>action<?php endif; ?>"><a href="<?php echo U('Member/index');?>"><i class="one"></i><span class="text">成员查看</span></a></li>
    <li class="center_menu <?php if($cont_name == 'Shop'): ?>action<?php endif; ?>"><a href="<?php echo U('Shop/index');?>"><i class="two"></i><span class="text">小组店铺</span></a></li>
    <li class="center_menu <?php if($cont_name == 'Baohuo'): ?>action<?php endif; ?>"><a href="<?php echo U('Baohuo/index');?>"><i class="four"></i><span class="text">小组报货</span></a></li>
    <li class="center_menu <?php if($cont_name == 'Kucun'): ?>action<?php endif; ?>"><a href="<?php echo U('Kucun/index');?>"><i class="four"></i><span class="text">小组库存</span></a></li>
    <li class="center_menu <?php if($cont_name == 'Money'): ?>action<?php endif; ?>"><a href="<?php echo U('Money/index');?>"><i class="three"></i><span class="text">资金记录</span></a></li>
    <li class="center_menu <?php if($cont_name == 'Borrow'): ?>action<?php endif; ?>"><a href="<?php echo U('Borrow/index');?>"><i class="five"></i><span class="text">借出记录</span></a></li>
    <li class="center_menu <?php if($cont_name == 'BackChan'): ?>action<?php endif; ?>"><a href="<?php echo U('BackChan/index');?>"><i class="five2"></i><span class="text">退回厂家</span></a></li>
    <li class="center_menu <?php if($cont_name == 'Black'): ?>action<?php endif; ?>"><a href="<?php echo U('Black/index');?>"><i class="senve"></i><span class="text">黑名榜单</span></a></li>
</ul>
                    </nav>
                </div>
            </div><!--left菜单-->
            <div class="row_right">
                <div class="input_row">
                    <label class="label_text">用户账号：<input class="input_text" type="text" name="" value="13980829676" placeholder="" readonly="readonly"/></label>
                    <label class="label_text">用户名稱：<input class="input_text" type="text" name="" value="" placeholder=""/></label>
                    <label class="label_text">用戶頭像：<div class="logo_rgi"><img src="/public/Home/img/logo_rgi.png"/></div>
                        <input class="input_file" type="file" name="" value=""/></label>
                    <button type="button" class="btn">保存信息</button>
                </div>
            </div>
        </div>
    </section>


    <footer class="footer">
        Copyright @ QT Network Company Ltd. All Rights Reserved. 2013-2017 青春之火
    </footer>
</div>

</body>



</html>