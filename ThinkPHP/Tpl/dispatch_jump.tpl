<?php
    if(C('LAYOUT_ON')) {
        echo '{__NOLAYOUT__}';
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>跳转提示</title>
    <link rel="stylesheet" href="__PUBLIC__/Admin/css/sweetalert.css">
<style type="text/css">
*{ padding: 0; margin: 0; }
body{ background: #fff; font-family: '微软雅黑'; color: #333; font-size: 16px; }
.system-message{ padding: 24px 48px; }
.system-message h1{ font-size: 100px; font-weight: normal; line-height: 120px; margin-bottom: 12px; }
.system-message .jump{ padding-top: 10px}
.system-message .jump a{ color: #333;}
.system-message .success,.system-message .error{ line-height: 1.8em; font-size: 36px }
.system-message .detail{ font-size: 12px; line-height: 20px; margin-top: 12px; display:none}
</style>
    <script type="text/javascript" src='__PUBLIC__/Static/jquery-1.11.2.min.js'></script>
    <script type="text/javascript" src="__PUBLIC__/Admin/js/jquery.js"></script>
    <script>
        // 现在window.$和window.jQuery是1.11版本:
        console.log($().jquery); // => '1.11.0'
        var $jq = jQuery.noConflict(true);
        // 现在window.$和window.jQuery被恢复成1.5版本:
        console.log($().jquery); // => '1.5.0'
        // 可以通过$jq访问1.11版本的jQuery了
    </script>
    <script src="__PUBLIC__/Admin/js/sweetalert.min.js"></script>
</head>
<body>

<?php if(isset($message)) {?>
    <script>
swal("Good!","<?php  echo $message;?>","success");
    </script>
<?php }else{?>
<script>
swal("OMG","<?php  echo $error;?>","error");
</script>
<?php }?>

    <input type="hidden" id="gourl" value="<?php echo($jumpUrl); ?>">

<script type="text/javascript">
(function(){
setTimeout(function () {
    window.location.href = $("#gourl").val();
},2000);
})();

</script>
</body>
</html>
