<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>安装</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- zui -->
    <link href="/shanren/Public/zui/css/zui.css" rel="stylesheet">
    <script src="/shanren/Public/static/jquery-1.10.2.min.js"></script>
</head>

<body style="background:  rgb(230, 234, 234)">
<div class="container" style="background: white;margin-top: 50px;margin-bottom: 50px;width:800px">
    <div class="with-padding row">
        <ul class="nav nav-secondary">
            
    <li class="active"><a href="javascript:;">OpenSNS安装向导</a></li>

        </ul>
        <div>

        </div>
        <div class="article">
            
    <h1 class="text-center">错误提示</h1>
    <table class="table">
        <caption><h2><?php echo ($title); ?><br/><br/><span class="text-danger" ><?php echo ($info); ?></span></h2></caption>

    </table>



            <div>
                
    <a class="btn btn-primary btn-block btn-large"  onclick="history.go(-1)">上一步</a>


            </div>
        </div>
    </div>
    <style>
        body{
            font-family: Arial, "Microsoft Yahei",'新宋体';
        }
    </style>

</div>

</body>
</html>