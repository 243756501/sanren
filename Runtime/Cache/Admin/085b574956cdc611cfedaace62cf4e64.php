<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo ($meta_title); ?>|<?php echo L('_SNS_BACKSTAGE_MANAGE_');?></title>
    <link href="/shanren/Public/favicon.ico" type="image/x-icon" rel="shortcut icon">


    <!--OC 自定义样式-->
    <link rel="stylesheet" href="/shanren/Application/Admin/Static/css/oc.css" media="all">
    <!--OC 自定义样式 end-->
    <link rel="stylesheet" href="/shanren/Public/static/os-icon/simple-line-icons.min.css" media="all">
    <link rel="stylesheet" type="text/css" href="/shanren/Application/Admin/Static/css/oc/admin.css" media="all">
    <!--adminlte-->
    <link rel="stylesheet" href="/shanren/Application/Admin/Static/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/shanren/Application/Admin/Static/adminlte/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="/shanren/Application/Admin/Static/adminlte/dist/css/skins/_all-skins.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="/shanren/Application/Admin/Static/adminlte/plugins/iCheck/flat/blue.css">
    <link href="/shanren/Application/Admin/Static/bootstrap/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
    <link href="/shanren/Application/Admin/Static/bootstrap/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css"/>
    <link href="/shanren/Application/Admin/Static/bootstrap/css/components.min.css" rel="stylesheet" id="style_components" type="text/css">

    <link rel="stylesheet" href="/shanren/Application/Admin/Static/css/adminlte.css" media="all">
    <link rel="stylesheet" href="/shanren/Application/Admin/Static/css/namecard.css" media="all">
    <!--adminlte end-->
    <!-- BEGIN THEME LAYOUT STYLES -->
    <link href="/shanren/Application/Admin/Static/bootstrap/plugins/layouts/layout/css/layout.min.css" rel="stylesheet" type="text/css"/>
    <link href="/shanren/Application/Admin/Static/adminlte/plugins/jQueryUI/jquery-ui.min.css" rel="stylesheet" type="text/css"/>
    <!-- END THEME LAYOUT STYLES -->

    <!--[if lt IE 9]>
    <script type="text/javascript" src="/shanren/Public/static/jquery-1.10.2.min.js"></script>
    <![endif]--><!--[if gte IE 9]><!-->
    <script type="text/javascript" src="/shanren/Public/js/jquery-2.0.3.min.js"></script>
    <script type="text/javascript" src="/shanren/Application/Admin/Static/js/jquery.mousewheel.js"></script>
    <!--<![endif]-->
    
    <script type="text/javascript">
        var ThinkPHP = window.Think = {
            "ROOT": "/shanren", //当前网站地址
            "APP": "/shanren/index.php?s=", //当前项目地址
            "PUBLIC": "/shanren/Public", //项目公共目录地址
            "DEEP": "<?php echo C('URL_PATHINFO_DEPR');?>", //PATHINF<?php echo L("_O_SEGMENTATION_");?>
            "MODEL": ["<?php echo C('URL_MODEL');?>", "<?php echo C('URL_CASE_INSENSITIVE');?>", "<?php echo C('URL_HTML_SUFFIX');?>"],
            "VAR": ["<?php echo C('VAR_MODULE');?>", "<?php echo C('VAR_CONTROLLER');?>", "<?php echo C('VAR_ACTION');?>"],
            'URL_MODEL': "<?php echo C('URL_MODEL');?>"
        }
        var _ROOT_ = "/shanren";
    </script>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
    <header data-role="top-sidebar" class="main-header">
        <!-- Logo -->
        <?php $logo = get_cover(modC('LOGO',0,'Config'),'path'); $logo = $logo?$logo:'/shanren/Public/images/logo.png'; ?>
        <a href="<?php echo U('Index/index');?>" class="logo">
            <img style="height: 38px;margin-top: -6px" src="<?php echo ($logo); ?>" alt="logo" class="logo-default">
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>
            <div class="collapse navbar-collapse navbar-collapse-example">
                <ul class="nav navbar-nav top-menu">
                    <?php if(is_array($__MENU__["main"])): $i = 0; $__LIST__ = $__MENU__["main"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menu): $mod = ($i % 2 );++$i; if(($menu["hide"]) != "1"): ?><li data-id="<?php echo ($menu["id"]); ?>" class="mega-menu-dropdown <?php echo ((isset($menu["class"]) && ($menu["class"] !== ""))?($menu["class"]):''); ?>">
                                <a href="<?php echo (u($menu["url"])); ?>" class="dropdown-toggle " data-hover="dropdown"
                                   data-close-others="true">
                                    <?php if(($menu["icon"]) != ""): ?><i class="icon-<?php echo ($menu["icon"]); ?>"></i>&nbsp;<?php endif; ?>
                                    <?php echo ($menu["title"]); ?>
                                    <i class="fa fa-angle-down"></i>
                                </a>
                                <ul class="dropdown-menu" style="min-width: 700px;">
                                    <li>
                                        <!-- Content container to add padding -->
                                        <div class="mega-menu-content">
                                            <div class="row">
                                                <?php $k=0; ?>
                                                <?php if(is_array($menu["children"])): $i = 0; $__LIST__ = $menu["children"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$children): $mod = ($i % 2 );++$i; $k++; if(($k%4)==1){ $style="clear:left"; }else{ $style=""; } ?>
                                                    <div class="col-md-3" style="<?php echo ($style); ?>">
                                                        <ul class="mega-menu-submenu">
                                                            <li><h3><?php echo ($key); ?></h3></li>
                                                            <?php if(is_array($children)): $i = 0; $__LIST__ = $children;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$child): $mod = ($i % 2 );++$i;?><li>
                                                                    <a href="<?php echo (u($child["url"])); ?>"><?php echo ($child["title"]); ?></a>
                                                                </li><?php endforeach; endif; else: echo "" ;endif; ?>
                                                        </ul>
                                                    </div><?php endforeach; endif; else: echo "" ;endif; ?>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="javascript:;" onclick="clear_cache()"><i class="icon-trash"></i> <?php echo L('_CACHE_CLEAR_');?></a>
                    </li>
                    <li><a target="_blank" href="<?php echo U('Home/Index/index');?>"><i class="icon-copy"></i>
                        <?php echo L('_FORESTAGE_OPEN_');?></a></li>
                    <li class="dropdown" style="margin-right: 15px;">
                        <?php $avatar = query_user(array('avatar128')); ?>
                        <a style="padding: 13px 15px 12px" href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img  src="<?php echo ($avatar["avatar32"]); ?>" class="avatar-img  small-img">
                            <?php echo session('user_auth.username');?>
                        </a>
                        <ul class="dropdown-menu name-card" role="menu">
                            <div class="head-box">
                                <img src="<?php echo ($avatar["avatar128"]); ?>" class="avatar-img">
                                <p> <?php echo session('user_auth.username');?>
                                    <small>注册于2016年7月</small>
                                </p>
                            </div>
                            <div class="btn-box">
                                <a href="<?php echo U('User/updatePassword');?>" class="btn">修改密码/昵称</a>
                                <a href="<?php echo U('Public/logout');?>" class="btn pull-right"><?php echo L('_EXIT_');?></a>
                            </div>
                        </ul>
                    </li>
                    <!-- Control Sidebar Toggle Button -->
                    <!--<li style="  margin-right: 15px;">
                        <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                    </li>-->
                    <script>
                        function clear_cache() {
                            $.get('/shanren/cc.php');
                            toast.success("<?php echo L('_CACHE_CLEAR_SUCCESS_'); echo L('_PERIOD_');?>");
                        }
                    </script>
                </ul>
            </div>

        </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside data-role="left-sidebar"  class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu">
                <li class="header">模块</li>

                <?php if(is_array($__MODULE_MENU__)): $i = 0; $__LIST__ = $__MODULE_MENU__;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i; if($v['is_setup'] AND $v['admin_entry']): if(!empty($v["children"])): ?><li class="treeview">
                                <a href="<?php echo U($v['admin_entry']);?>" title="<?php echo (text($v["alias"])); ?>">
                                    <i class="fa fa-<?php echo ($v['icon']); ?>"></i>
                                    <span><?php echo ($v["alias"]); ?></span>
                                </a>
                                <ul class="treeview-menu">
                                    <?php if(is_array($v["children"])): $i = 0; $__LIST__ = $v["children"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$children): $mod = ($i % 2 );++$i;?><li class="heading">
                                            <h4 class="uppercase"><i class="fa fa-chevron-circle-down"></i> <?php echo ($key); ?></h4>
                                        </li>
                                        <?php if(is_array($children)): $i = 0; $__LIST__ = $children;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$child): $mod = ($i % 2 );++$i;?><li><a href="<?php echo (u($child["url"])); ?>"><i class="fa fa-circle-o"></i>
                                                <?php echo ($child["title"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; ?>
                                </ul>
                            </li>
                            <?php else: ?>
                            <li>
                                <a href="<?php echo U($v['admin_entry']);?>" title="<?php echo (text($v["alias"])); ?>">
                                    <i class="fa fa-<?php echo ($v['icon']); ?>"></i>
                                    <span><?php echo ($v["alias"]); ?></span>
                                </a>
                            </li><?php endif; endif; endforeach; endif; else: echo "" ;endif; ?>
            </ul>
        </section>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" style="min-height: 900px;">
        <ul class="sub_menu">

            <?php if(!empty($__MENU__["child"])): ?><li class="treeview">

                    <ul class="treeview-menu">
                        <?php if(is_array($__MENU__["child"])): $i = 0; $__LIST__ = $__MENU__["child"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$children): $mod = ($i % 2 );++$i; if(!empty($children)): ?><li class="heading">
                                    <h4 class="uppercase"><i class="fa fa-chevron-circle-down"></i> <?php echo ($key); ?></h4>
                                </li>
                                <?php if(is_array($children)): $i = 0; $__LIST__ = $children;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$child): $mod = ($i % 2 );++$i;?><li><a href="<?php echo (u($child["url"])); ?>"><i class="fa fa-circle-o"></i>
                                        <?php echo ($child["title"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; endif; endforeach; endif; else: echo "" ;endif; ?>
                    </ul>
                </li><?php endif; ?>
        </ul>
        <div style="padding:10px;padding-left:0;padding-bottom:10px;left: 335px;position:absolute;right: 0;bottom: 0;top: 50px;overflow: auto">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                
            </section>

            <!-- Main content -->
            <section class="content">
                <div id="main-content">
                    <?php if(($update) == "1"): ?><div id="top-alert" class="alert alert-success alert-dismissable"
                             style="position: absolute;left: 50%;margin-left: -150px;width: 300px;box-shadow: rgba(95, 95, 95, 0.4) 3px 3px 3px;z-index:999">
                            <i class="icon-ok-sign" style="font-size: 28px"></i> &nbsp;&nbsp;
                            <?php echo L('_VERSION_UPDATE_',array('href'=> '<a class="alert-link" href="'.U('Cloud/update').'">' ));?></a>
                            <button class="close fixed" style="margin-top: 4px;">&times;</button>
                        </div><?php endif; ?>

                    <div id="main" style="overflow-y:auto;overflow-x:hidden;">
                        
                            <!-- nav -->
                            <?php if(!empty($_show_nav)): ?><div class="breadcrumb">
                                    <span><?php echo L('_YOUR_POSITION_'); echo L('_COLON_');?></span>
                                    <?php $i = '1'; ?>
                                    <?php if(is_array($_nav)): foreach($_nav as $k=>$v): if($i == count($_nav)): ?><span><?php echo ($v); ?></span>
                                            <?php else: ?>
                                            <span><a href="<?php echo ($k); ?>"><?php echo ($v); ?></a>&gt;</span><?php endif; ?>
                                        <?php $i = $i+1; endforeach; endif; ?>
                                </div><?php endif; ?>
                            <!-- nav -->
                        

                        <div class="admin-main-container">
                            

    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <a href="<?php echo U('Admin/Index/index');?>">首页 </a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>仪表盘</span>
            </li>
        </ul>
    </div>
    <!-- END PAGE BAR -->
    <!-- BEGIN PAGE TITLE-->
    <h3 class="page-title"> 仪表盘
        <small>仪表盘</small>
    </h3>

    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption">
                <i class="icon-bubble font-green-sharp"></i>
                <span class="caption-subject font-green-sharp sbold">常用操作</span>
            </div>
            <div class="actions">
                <div class="btn-group">
                    <a class="btn btn-danger" href="<?php echo U('Index/index',array_merge($_GET,array('refresh'=>1)));?>">重置粉丝数（粉丝数不同步的时候使用）</a>

                </div>
            </div>
        </div>
        <div class="portlet-body">
            <div>

                <div class="tiles tile-group ten-wide ">

                    <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$data): $mod = ($i % 9 );++$i; if($mod == 8): ?><div class="live-tile accent exclude tile-id" id="tile_<?php echo ($data['id']); ?>" data-speed="750" data-delay="3000" style="width: 151px;background-color: <?php echo ($data['tile_bg']); ?>">
                                <a href="<?php echo ($data["url_vo"]); ?>"><span class="tile-title"><?php echo ($data["title_vo"]); ?></span></a>
                                <div class="tile-box">
                                    <p class="menu"><a href="<?php echo ($data["url"]); ?>">
                                        <i id="icon_set" class="icon-<?php echo ($data['icon']); ?>"></i>
                                    </a></p>

                                    <div>
                                        <a href="<?php echo ($data["url"]); ?>"><?php echo ($data["title"]); ?></a>
                                    </div>
                                    <div class="tile-setting" data-id="<?php echo ($data["id"]); ?>">
                                        <a><i class="icon-settings"></i></a>
                                    </div>
                                    <div class="tile-del">
                                        <a data-id="<?php echo ($data["id"]); ?>" href="javascript:void(0);" onclick="deltile($(this))" >
                                            <i class="icon-trash"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <?php else: ?>
                            <div class="live-tile accent exclude tile-id" id="tile_<?php echo ($data['id']); ?>" data-speed="750" data-delay="3000" style="background-color: <?php echo ($data['tile_bg']); ?>">
                                <a href="<?php echo ($data["url_vo"]); ?>"><span class="tile-title"><?php echo ($data["title_vo"]); ?></span></a>
                                <div class="tile-box">
                                    <p class="menu"><a href="<?php echo ($data["url"]); ?>">
                                        <i id="icon_set" class="icon-<?php echo ($data['icon']); ?>"></i>
                                    </a></p>

                                    <div>
                                        <a href="<?php echo ($data["url"]); ?>"><?php echo ($data["title"]); ?></a>
                                    </div>
                                    <div class="tile-setting" data-id="<?php echo ($data["id"]); ?>">
                                        <a><i class="icon-settings"></i></a>
                                    </div>
                                    <div class="tile-del">
                                        <a data-id="<?php echo ($data["id"]); ?>" href="javascript:void(0);" onclick="deltile($(this))" >
                                            <i class="icon-trash"></i>
                                        </a>
                                    </div>
                                </div>
                            </div><?php endif; endforeach; endif; else: echo "" ;endif; ?>

                </div>

            </div>
            <!-- Activate live tiles -->
            <script type="text/javascript">
                $(function () {
                    $(".live-tile, .flip-list").not(".exclude").liveTile();
                    $(".tiles").sortable({
                        update: function(event, ui) {
                            var ids = $(this).sortable("toArray");
                            var url = "<?php echo U('Admin/Index/sortTile');?>";
                            $.post(url, {ids: ids}, function(msg) {

                            })
                        }
                    });
                    $(".tiles").disableSelection();
                });

                function deltile(tile) {
                    if (confirm("你确定要删除此常用操作吗？")) {
                        var id = tile.attr('data-id');
                        var url = "<?php echo U('Admin/Index/delTile');?>";
                        $.post(url, {id: id}, function (msg) {
                            if (msg.status) {
                                toast.success(msg.info);
                                setTimeout(function () {
                                    var tileId = tile.attr('data-id');
                                    if(tileId == msg.tile_id){
                                        $('#tile_'+tileId).hide();
                                    }
                                }, 1000);
                            } else {
                                toast.error(msg.info);
                            }
                        }, 'json')
                    }
                }

                var tile_setting;
                $('.tile-setting').click(function () {
                    sessionStorage['id'] = $(this).attr('data-id');
                    var id = $(this).attr('data-id');
                    var tile_bg = $('#tile_'+id).css("background-color").colorHex();
                    var icon = $('#tile_'+id).find("i#icon_set").attr('class');

                    $(".icon-chose").find("i").removeClass().addClass(icon);
                    $("input[name='icon_chose']").val(icon);
                    $(".simpleColorDisplay").css("background-color", tile_bg);
                    $("input[name='color_chose']").val(tile_bg);
                    $('#tile_setting').modal();
                });

                //十六进制颜色值的正则表达式
                var reg = /^#([0-9a-fA-f]{3}|[0-9a-fA-f]{6})$/;
                /*RGB颜色转换为16进制*/
                String.prototype.colorHex = function(){
                    var that = this;
                    if(/^(rgb|RGB)/.test(that)){
                        var aColor = that.replace(/(?:\(|\)|rgb|RGB)*/g,"").split(",");
                        var strHex = "#";
                        for(var i=0; i<aColor.length; i++){
                            var hex = Number(aColor[i]).toString(16);
                            if(hex === "0"){
                                hex += hex;
                            }
                            strHex += hex;
                        }
                        if(strHex.length !== 7){
                            strHex = that;
                        }
                        return strHex;
                    }else if(reg.test(that)){
                        var aNum = that.replace(/#/,"").split("");
                        if(aNum.length === 6){
                            return that;
                        }else if(aNum.length === 3){
                            var numHex = "#";
                            for(var i=0; i<aNum.length; i+=1){
                                numHex += (aNum[i]+aNum[i]);
                            }
                            return numHex;
                        }
                    }else{
                        return that;
                    }
                };
            </script>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="row">

        <div class="col-md-6 col-sm-6">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-cursor font-purple"></i>
                        <span class="caption-subject font-purple bold uppercase">相关信息</span>
                    </div>

                </div>
                <div class="portlet-body">
                    <div class="row">

                        <div class="col-md-4">
                            <div class="easy-pie-chart">
                                <a target="_blank" class="title" href="http://os.opensns.cn/question">
                                    <div>
                                        <i class="icon-question" style="font-size:32px;padding: 8px"></i></div>
                                    <?php echo L('_Q_AND_A_');?>
                                    <i class="icon-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                        <div class="margin-bottom-10 visible-sm"></div>
                        <div class="col-md-4">
                            <div class="easy-pie-chart">
                                <a target="_blank" class="title" href="http://os.opensns.cn/book/index/index.html">
                                    <div>
                                        <i class="fa fa-book" style="font-size:32px;padding: 10px"></i></div>

                                    <?php echo L('_DOCUMENT_CENTER_');?>
                                    <i class="icon-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                        <div class="margin-bottom-10 visible-sm"></div>
                        <div class="col-md-4">
                            <div class="easy-pie-chart">
                                <a target="_blank" class="title" href="http://os.opensns.cn/weibo">
                                    <div>
                                        <i class="fa fa-commenting-o" style="font-size:32px;padding: 8px"></i></div>
                                    <?php echo L('_OFFICIAL_GROUP_');?>
                                    <i class="icon-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-md-6 col-sm-6">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-share font-blue"></i>
                        <span class="caption-subject font-blue bold uppercase"><?php echo L('_SYSTEM_INFO_');?></span>
                    </div>

                </div>
                <div class="portlet-body">
                    <div class="scroller" style="height:237px;" data-always-visible="1" data-rail-visible="0">
                        <table class="table table-bordered table-striped ">
                            <tr>
                                <th style="width: 200px"><?php echo L('_SERVER_OS_');?></th>
                                <td><?php echo (PHP_OS); ?></td>
                            </tr>
                            <tr>
                                <th><?php echo L('_THINKPHP_VERSION_');?></th>
                                <td><?php echo (THINK_VERSION); ?></td>
                            </tr>
                            <tr>
                                <th><?php echo L('_RUNTIME_ENVIR_');?></th>
                                <td><?php echo ($_SERVER['SERVER_SOFTWARE']); ?></td>
                            </tr>
                            <tr>
                                <th><?php echo L('_MYSQL_VERSION_');?></th>
                                <?php $system_info_mysql = M()->query("select version() as v;"); ?>
                                <td><?php echo ($system_info_mysql["0"]["v"]); ?></td>
                            </tr>
                            <tr>
                                <th><?php echo L('_LIMIT_UPLOAD_');?></th>
                                <td><?php echo ini_get('upload_max_filesize');?>

                                    <a href="http://os.opensns.cn/book/index/read/section_id/93.html"
                                       target="_blank"><?php echo L('_MODIFY_HOW_TO_');?></a></td>
                            </tr>
                            <tr>
                                <th><?php echo L('_OS_VERSION_');?></th>
                                <td><?php echo file_get_contents('./Data/version.ini');?></td>
                            </tr>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>


            <div class="with-padding-lg">
                <div class="count clearfix">

                </div>
            </div>
            <div class="with-padding-lg">
                <div class="" style="width:700px;clear: both;margin: auto">
                    <div class="hd cf">
                        <h5><?php echo ($addons_config["title"]); ?></h5>

                        <div class="title-opt">
                        </div>
                    </div>
                    <div class="bd">
                        <div class="">

                        </div>
                    </div>
                </div>

    </div>
</div>


    <div class="clearfix">
        <div class="col-xs-4">

        </div>
        <div class="col-xs-4">

        </div>
        <div class="col-xs-4">

        </div>

    </div>
    <script>
        $('#main-content').css('left', 0);
        $(function () {
            $('#myChart').highcharts({
                chart: {
                    type: "spline",
                    style: {
                        fontFamily: '"Microsoft Yahei", "宋体"'
                    }
                },
                title: {
                    text: "<?php echo L('_USER_INCREASE_RECENT_',array('count_day'=>$count['count_day']));?>",
                    x: -20 //center
                },
                xAxis: {
                    categories: eval('<?php echo ($count["last_day"]["days"]); ?>'),
                    title: {
                        text: "<?php echo L('_MEMBER_REG_TODAY_');?>",
                        enabled: false
                    }
                },
                yAxis: {
                    title: ''
                },
                legend: {
                    layout: 'vertical',
                    verticalAlign: 'middle',
                    borderWidth: 0,
                    enabled: false
                },
                series: [{
                    name: "<?php echo L('_MEMBER_REG_TODAY_');?>",
                    data: eval('<?php echo ($count["last_day"]["data"]); ?>'),
                    enable: true
                }], credits: {enabled: false}
            });
        });


    </script>
    <?php echo hook('adminRightBtn', array());?>

                        </div>

                    </div>
                </div>
            </section>
            <!-- /.content -->
        </div>

    </div>
    <!-- /.content-wrapper -->
    <footer class="main-footer">
        
    </footer>
    <!-- Control Sidebar -->
    <!--<aside class="control-sidebar control-sidebar-dark" style="position: fixed;height: auto;">
        &lt;!&ndash; Create the tabs &ndash;&gt;
        <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
            <li class="active"><a href="#control-sidebar-home-tab" data-toggle="tab" aire-expend="true"><i class="fa fa-home"></i></a></li>
            <li><a href="#control-sidebar-stats-tab" data-toggle="tab"><i class="fa fa-gear"></i></a></li>
            <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
        </ul>
        &lt;!&ndash; Tab panes &ndash;&gt;
        <div class="tab-content">
            &lt;!&ndash; Home tab content &ndash;&gt;
            <div class="tab-pane active" id="control-sidebar-home-tab">
                <h3 class="control-sidebar-heading">Recent Activity</h3>
                <ul class="control-sidebar-menu">
                    <li>
                        <a href="javascript:void(0)">
                            <i class="menu-icon fa fa-birthday-cake bg-red"></i>

                            <div class="menu-info">
                                <h4 class="control-sidebar-subheading">Langdon's Birthday</h4>

                                <p>Will be 23 on April 24th</p>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)">
                            <i class="menu-icon fa fa-user bg-yellow"></i>

                            <div class="menu-info">
                                <h4 class="control-sidebar-subheading">Frodo Updated His Profile</h4>

                                <p>New phone +1(800)555-1234</p>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)">
                            <i class="menu-icon fa fa-envelope-o bg-light-blue"></i>

                            <div class="menu-info">
                                <h4 class="control-sidebar-subheading">Nora Joined Mailing List</h4>

                                <p>nora@example.com</p>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)">
                            <i class="menu-icon fa fa-file-code-o bg-green"></i>

                            <div class="menu-info">
                                <h4 class="control-sidebar-subheading">Cron Job 254 Executed</h4>

                                <p>Execution time 5 seconds</p>
                            </div>
                        </a>
                    </li>
                </ul>
                &lt;!&ndash; /.control-sidebar-menu &ndash;&gt;

                <h3 class="control-sidebar-heading">Tasks Progress</h3>
                <ul class="control-sidebar-menu">
                    <li>
                        <a href="javascript:void(0)">
                            <h4 class="control-sidebar-subheading">
                                Custom Template Design
                                <span class="label label-danger pull-right">70%</span>
                            </h4>

                            <div class="progress progress-xxs">
                                <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)">
                            <h4 class="control-sidebar-subheading">
                                Update Resume
                                <span class="label label-success pull-right">95%</span>
                            </h4>

                            <div class="progress progress-xxs">
                                <div class="progress-bar progress-bar-success" style="width: 95%"></div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)">
                            <h4 class="control-sidebar-subheading">
                                Laravel Integration
                                <span class="label label-warning pull-right">50%</span>
                            </h4>

                            <div class="progress progress-xxs">
                                <div class="progress-bar progress-bar-warning" style="width: 50%"></div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)">
                            <h4 class="control-sidebar-subheading">
                                Back End Framework
                                <span class="label label-primary pull-right">68%</span>
                            </h4>

                            <div class="progress progress-xxs">
                                <div class="progress-bar progress-bar-primary" style="width: 68%"></div>
                            </div>
                        </a>
                    </li>
                </ul>
                &lt;!&ndash; /.control-sidebar-menu &ndash;&gt;

            </div>
            &lt;!&ndash; /.tab-pane &ndash;&gt;
            &lt;!&ndash; Stats tab content &ndash;&gt;
            <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
            &lt;!&ndash; /.tab-pane &ndash;&gt;
            &lt;!&ndash; Settings tab content &ndash;&gt;
            <div class="tab-pane" id="control-sidebar-settings-tab">
                <form method="post">
                    <h3 class="control-sidebar-heading">General Settings</h3>

                    <div class="form-group">
                        <label class="control-sidebar-subheading">
                            Report panel usage
                            <input type="checkbox" class="pull-right" checked>
                        </label>

                        <p>
                            Some information about this general settings option
                        </p>
                    </div>
                    &lt;!&ndash; /.form-group &ndash;&gt;

                    <div class="form-group">
                        <label class="control-sidebar-subheading">
                            Allow mail redirect
                            <input type="checkbox" class="pull-right" checked>
                        </label>

                        <p>
                            Other sets of options are available
                        </p>
                    </div>
                    &lt;!&ndash; /.form-group &ndash;&gt;

                    <div class="form-group">
                        <label class="control-sidebar-subheading">
                            Expose author name in posts
                            <input type="checkbox" class="pull-right" checked>
                        </label>

                        <p>
                            Allow the user to show his name in blog posts
                        </p>
                    </div>
                    &lt;!&ndash; /.form-group &ndash;&gt;

                    <h3 class="control-sidebar-heading">Chat Settings</h3>

                    <div class="form-group">
                        <label class="control-sidebar-subheading">
                            Show me as online
                            <input type="checkbox" class="pull-right" checked>
                        </label>
                    </div>
                    &lt;!&ndash; /.form-group &ndash;&gt;

                    <div class="form-group">
                        <label class="control-sidebar-subheading">
                            Turn off notifications
                            <input type="checkbox" class="pull-right">
                        </label>
                    </div>
                    &lt;!&ndash; /.form-group &ndash;&gt;

                    <div class="form-group">
                        <label class="control-sidebar-subheading">
                            Delete chat history
                            <a href="javascript:void(0)" class="text-red pull-right"><i class="fa fa-trash-o"></i></a>
                        </label>
                    </div>
                    &lt;!&ndash; /.form-group &ndash;&gt;
                </form>
            </div>
            &lt;!&ndash; /.tab-pane &ndash;&gt;
        </div>
    </aside>-->
    <!-- /.control-sidebar -->
    <!-- Add the sidebar's background. This div must be placed
         immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>
</div>
<?php if($report){ ?>
<div class="report_feedback" title="<?php echo L('_REPORT_EXPERIENCE_PLEASE_FILL_');?>" data-toggle="modal" data-target="#myModal">
    <div class="report_icon"></div>
    <span class="label label-badge label-danger report_point">1</span>
</div>
<div class="modal fade in" id="myModal" aria-hidden="false" style="display: none">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="report_form" action="<?php echo U('admin/admin/submitReport');?>" method="post">


                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span
                            class="sr-only"><?php echo L('_CLOSE_');?></span></button>
                    <h4 class="modal-title"><?php echo L('_REPORT_EXPERIENCE_');?></h4>
                </div>

                <div class="modal-body">

                    <div class="row">
                        <!-- 帖子分类 -->
                        <div class="col-sm-12">
                            <div><?php echo L('_THANKS_HOPE_');?></div>

                            <label class="item-label"><?php echo L('_MOOD_MY_');?></label>

                            <div>
                                <select name="q1" class="report-select" style="width:400px;">
                                    <option value="0"><?php echo L('_ELECT_PLEASE_');?></option>
                                    <option><?php echo L('_HAPPY_');?></option>
                                    <option><?php echo L('_AGONY_');?></option>
                                    <option><?php echo L('_LOVE_');?></option>
                                    <option><?php echo L('_EXPECT_');?></option>
                                </select>
                            </div>

                            <label class="item-label"><?php echo L('_LOVE_MY_OPTION_');?></label>

                            <div>
                                <select name="q2" class="report-select" style="width:400px;">
                                    <option value="0"><?php echo L('_ELECT_PLEASE_');?></option>
                                    <?php if(is_array($this_update)): $i = 0; $__LIST__ = $this_update;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$option): $mod = ($i % 2 );++$i;?><option value="<?php echo (htmlspecialchars($option)); ?>"><?php echo (htmlspecialchars($option)); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                </select>
                            </div>

                            <label class="item-label"><?php echo L('_HATE_MY_OPTION_');?>
                            </label>

                            <div>
                                <select name="q3" class="report-select" style="width:400px;">
                                    <option value="0"><?php echo L('_ELECT_PLEASE_');?></option>
                                    <?php if(is_array($this_update)): $i = 0; $__LIST__ = $this_update;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$option): $mod = ($i % 2 );++$i;?><option value="<?php echo (htmlspecialchars($option)); ?>"><?php echo (htmlspecialchars($option)); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                </select>
                            </div>


                            <label class="item-label"><?php echo L('_EXPECTATION__MY_OPTION_');?>
                            </label>

                            <div>
                                <select name="q4" class="report-select" style="width:400px;">
                                    <option value="0"><?php echo L('_ELECT_PLEASE_');?></option>
                                    <?php if(is_array($future_update)): $i = 0; $__LIST__ = $future_update;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$option): $mod = ($i % 2 );++$i;?><option value="<?php echo (htmlspecialchars($option)); ?>"><?php echo (htmlspecialchars($option)); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <?php if(strval($report['url'])!=''){ ?>
                    <a class="pull-left" href="<?php echo ($report['url']); ?>" target="_blank"><?php echo L('_UPDATE_LOOK_');?></a>
                    <?php } ?>
                    <button type="submit" class="btn ajax-post" target-form="report_form"><?php echo L('_CONFIRM_');?></button>
                </div>

            </form>
        </div>
    </div>
</div>
<?php } ?>

<!--adminlte-->
    <!-- FastClick -->
<script src="/shanren/Application/Admin/Static/adminlte/plugins/fastclick/fastclick.js"></script>
<script src="/shanren/Application/Admin/Static/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/shanren/Application/Admin/Static/bootstrap/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
<script src="/shanren/Application/Admin/Static/adminlte/dist/js/app.min.js"></script>
<link rel="stylesheet" href="/shanren/Application/Admin/Static/bootstrap/plugins/bootstrap-toastr/toastr.min.css">
<script src="/shanren/Application/Admin/Static/bootstrap/plugins/bootstrap-toastr/toastr.min.js" type="text/javascript"></script>
<script src="/shanren/Application/Admin/Static/adminlte/plugins/jQueryUI/jquery-ui.min.js" type="text/javascript"></script>
<!--adminlte end-->
<script type="text/javascript" src="/shanren/Application/Admin/Static/js/com/com.toast.class.js"></script>

<script type="text/javascript">
    (function () {
        var ThinkPHP = window.Think = {
            "ROOT": "/shanren", //当前网站地址
            "APP": "/shanren/index.php?s=", //当前项目地址
            "PUBLIC": "/shanren/Public", //项目公共目录地址
            "DEEP": "<?php echo C('URL_PATHINFO_DEPR');?>", //PATHINFO分割符
            "MODEL": ["<?php echo C('URL_MODEL');?>", "<?php echo C('URL_CASE_INSENSITIVE');?>", "<?php echo C('URL_HTML_SUFFIX');?>"],
            "VAR": ["<?php echo C('VAR_MODULE');?>", "<?php echo C('VAR_CONTROLLER');?>", "<?php echo C('VAR_ACTION');?>"],
            'URL_MODEL': "<?php echo C('URL_MODEL');?>"
        }
    })();
</script>
<script type="text/javascript" src="/shanren/Public/static/think.js"></script>
<script type="text/javascript" src="/shanren/Application/Admin/Static/js/common.js"></script>


<script type="text/javascript">
    +function () {
        $('[data-role="left-sidebar"] .treeview').hover(function () {
            var height=$(this).find('.treeview-menu').height();
            var top_height=$(this).offset().top;
            var total_height=$('[data-role="left-sidebar"]').height()+$('[data-role="left-sidebar"]').offset().top;
            if(total_height<top_height+height){
                var top=total_height-top_height-height;
                $(this).find('.treeview-menu').css('top',top);
            }
        });
        $('[data-role="top-sidebar"] .mega-menu-dropdown').hover(function () {
            var width= $(this).find('.dropdown-menu').width();
            var left_width=$(this).offset().left;
            var total_width=document.body.scrollWidth;
            if(total_width<left_width+width){
                var left=total_width-left_width-width;
                $(this).find('.dropdown-menu').css('left',left);
            }
        });
        var $window = $(window), $subnav = $("#subnav"), url;
        $window.resize(function () {
            $("#main").css("min-height", $window.height() - 130);
        }).resize();

        // 导航栏超出窗口高度后的模拟滚动条
        var sHeight = $(".sidebar").height();
        var subHeight = $(".subnav").height();
        var diff = subHeight - sHeight; //250
        var sub = $(".subnav");
        if (diff > 0) {
            $(window).mousewheel(function (event, delta) {
                if (delta > 0) {
                    if (parseInt(sub.css('marginTop')) > -10) {
                        sub.css('marginTop', '0px');
                    } else {
                        sub.css('marginTop', '+=' + 10);
                    }
                } else {
                    if (parseInt(sub.css('marginTop')) < '-' + (diff - 10)) {
                        sub.css('marginTop', '-' + (diff - 10));
                    } else {
                        sub.css('marginTop', '-=' + 10);
                    }
                }
            });
        }
    }();
    highlight_subnav("<?php echo U('Admin'.'/'.CONTROLLER_NAME.'/'.ACTION_NAME,$_GET);?>")

    function displaySubMenu(li) {
        var subMenu = li.getElementsByTagName("ul")[0];
        subMenu.style.display = "block";
    }
    function hideSubMenu(li) {
        var subMenu = li.getElementsByTagName("ul")[0];
        subMenu.style.display = "none";
    }
</script>

    <script src="/shanren/Application/Admin/Static/bootstrap/plugins/counterup/jquery.waypoints.min.js" type="text/javascript"></script>
    <script src="/shanren/Application/Admin/Static/bootstrap/plugins/counterup/jquery.counterup.min.js" type="text/javascript"></script>
    <link href="/shanren/Application/Admin/Static/bootstrap/plugins/metro/MetroJs.min.css" type="text/css" rel="stylesheet">
    <script src="/shanren/Application/Admin/Static/bootstrap/plugins/metro/MetroJs.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="/shanren/Application/Admin/Static/js/highcharts.js"></script>
    <script type="text/javascript" src="/shanren/Application/Admin/Static/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>

    <script src="/shanren/Application/Admin/Static/js/jquery.simple-color.js"></script>
    <link rel="stylesheet" href="/shanren/Application/Admin/Static/css/tile.css" media="all">


    <?php echo hook('adminIndexModal', array());?>
    <div class="modal fade" id="settingCount">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span
                            class="sr-only"><?php echo L('_CLOSE_');?></span></button>
                    <h4 class="modal-title"><?php echo L('_STATISTICS_SET_');?></h4>
                </div>
                <div class="modal-body">
                    <div class="with-padding">
                        <label><?php echo L('_DISPLAY_DAYS_DEFAULT_');?> </label><input class="form-control" name="count_day" value="<?php echo ($count["count_day"]); ?>">

                    </div>


                </div>
                <div class="modal-footer">
                    <button class="btn " data-role="saveCountSetting">
                        <i class="icon-ok"></i> <?php echo L('_SAVE_');?>
                    </button>
                    <button class="btn " data-dismiss="modal">
                        <i class="icon-remove"></i> <?php echo L('_CANCEL_');?>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="tile_setting" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="">设置图标和颜色</h4>
                </div>
                <div class="modal-body" style="height: 100px">
                    <div class="col-xs-3">
                        <h4>选择图标</h4>
                        <div class="pull-left icon-chose" title=<?php echo L("__WITH_DOUBLE_");?>>
                            <a class="icon-selector"> <i class=""></i><input name="icon_chose" title=<?php echo L("__WITH_DOUBLE_");?> type="hidden" value=""></a>
                        </div>
                    </div>

                    <div>
                        <h4>背景颜色</h4>
                        <div class="pull-left color-chose" title=<?php echo L("_SELECT_THE_ICON_BACKGROUND_COLOR_WITH_DOUBLE_");?>>
                            <input name="color_chose" class="simple_color_callback" value="{default='#000000'}"/>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button class="btn " data-role="saveTileSetting" data-id="<?php echo ($data["id"]); ?>" href="javascript:void(0);" onclick="setTile()">
                        <i class="icon-ok"></i> <?php echo L('_SAVE_');?>
                    </button>
                    <button class="btn " data-dismiss="modal">
                        <i class="icon-remove"></i> <?php echo L('_CANCEL_');?>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="icon_selector" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">选择图标</h4>
                </div>
                <div class="modal-body">
                    <div class="icons-list">
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-user"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-user-female"></span></span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-users"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-user-follow"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true"
                                                          class="icon-user-following"></span>  </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-user-unfollow"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-trophy"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-speedometer"></span>  </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-social-youtube"></span></span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-social-twitter"></span></span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-social-tumblr"></span>  </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-social-facebook"></span></span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-social-dropbox"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true"
                                                          class="icon-social-dribbble"></span>  </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-shield"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-screen-tablet"></span></span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true"
                                                          class="icon-screen-smartphone"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true"
                                                          class="icon-screen-desktop"></span>  </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-plane"></span></span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-notebook"></span></span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-moustache"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-mouse"></span>  </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-magnet"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-magic-wand"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-hourglass"></span></span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-graduation"></span>  </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-ghost"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true"
                                                          class="icon-game-controller"></span>  </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-fire"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-eyeglasses"></span></span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-envelope-open"></span></span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true"
                                                          class="icon-envelope-letter"></span>  </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-energy"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-emoticon-smile"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-disc"></span></span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-cursor-move"></span></span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-crop"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-credit-card"></span></span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-chemistry"></span></span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-bell"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-badge"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-anchor"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-action-redo"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-action-undo"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-bag"></span>  </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-basket"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-basket-loaded"></span></span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-book-open"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-briefcase"></span></span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-bubbles"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-calculator"></span>  </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-call-end"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-call-in"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-call-out"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-compass"></span>  </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-cup"></span>  </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-diamond"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-direction"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-directions"></span>  </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-docs"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-drawer"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-drop"></span>  </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-earphones"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-earphones-alt"></span>  </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-feed"></span>  </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-film"></span>  </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-folder-alt"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-frame"></span>  </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-globe"></span></span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-globe-alt"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-handbag"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-layers"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-map"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-picture"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-pin"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-playlist"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-present"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-printer"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-puzzle"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-speech"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-vector"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-wallet"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-arrow-down"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-arrow-left"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-arrow-right"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-arrow-up"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-bar-chart"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-bulb"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-calendar"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-control-end"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true"
                                                          class="icon-control-forward"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-control-pause"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-control-play"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-control-rewind"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-control-start"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-cursor"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-dislike"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-equalizer"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-graph"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-grid"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-home"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-like"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-list"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-login"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-logout"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-loop"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-microphone"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-music-tone"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-music-tone-alt"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-note"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-pencil"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-pie-chart"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-question"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-rocket"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-share"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-share-alt"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-shuffle"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-size-actual"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true"
                                                          class="icon-size-fullscreen"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-support"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-tag"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-trash"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-umbrella"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-wrench"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-ban"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-bubble"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-camcorder"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-camera"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-check"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-clock"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-close"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-cloud-download"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-cloud-upload"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-doc"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-envelope"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-eye"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-flag"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-folder"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-heart"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-info"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-key"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-link"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-lock"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-lock-open"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-magnifier"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-magnifier-add"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true"
                                                          class="icon-magnifier-remove"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-paper-clip"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-paper-plane"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-plus"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-pointer"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-power"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-refresh"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-reload"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-settings"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-star"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-symbol-female"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-symbol-male"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-target"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-volume-1"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-volume-2"></span> </span>
                                            </span>
                                            <span class="item-box">
                                                <span class="item">
                                                    <span aria-hidden="true" class="icon-volume-off"></span> </span>
                                            </span>
</div>

                </div>
            </div>
        </div>
    </div>

    <script>
        $(function(){
            initIconSelector();
            bindColor();
        });

        $('[data-role=saveCountSetting]').click(function () {
            $.post("/shanren/index.php?s=/admin/index/index.html", {count_day: $('[name=count_day]').val()}, function (msg) {
                handleAjax(msg);
            });
        });

        function setTile(){
            var id = sessionStorage['id'];
            var icon = $("input[name='icon_chose']").val();
            var tile_bg = $("input[name='color_chose']").val();
            var url = "<?php echo U('Admin/Index/setTile');?>";

            $.post(url, {id: id, icon: icon, tile_bg: tile_bg}, function (msg) {
                if (msg.status) {
                    toast.success(msg.info);
                    setTimeout(function () {
                        if(id == msg.tile_id){
                            $('#tile_setting').modal('hide');
                            $('#tile_'+id).css("background-color", msg.tile_bg).find("i#icon_set").removeClass().addClass("icon-"+msg.tile_icon);
                        }
                    }, 500);
                } else {
                    toast.error(msg.info);
                }
            }, 'json')
        }

        var icon_selector;
        function initIconSelector() {
            $('.icons-list .item span').click(function(){
                var icon = $(this).attr('class');
                $('#current').val(icon);
                icon_selector.find('input').val(icon);
                icon_selector.find('i').attr('class',icon);
                $('#icon_selector').modal('hide');
            });
            $('.icon-selector').click(function () {
                icon_selector = $(this);
                $('#icon_selector').modal();
            });
        }

        function bindColor() {
            $('.simpleColorContainer').remove();
            $('.simple_color_callback').simpleColor({
                boxWidth: 15,
                boxHeight: 15,
                cellWidth: 15,
                cellHeight: 15,
                chooserCSS: { 'z-index': 1200 },
                displayCSS: { 'border': 0 }
            });
        }
    </script>

</body>
</html>