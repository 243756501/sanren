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
                <a href="<?php echo U('Admin/Index/index');?>">首页  </a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span><?php echo ($title); ?></span>
            </li>
        </ul>
        <div class="page-toolbar">
            <a class="btn btn-danger" data-role="addTo"><i class="icon-plus"></i> 添加到常用操作</a>
<?php $controller = CONTROLLER_NAME; $current = M('Menu')->where("url like '%$controller/" . ACTION_NAME . "' AND pid > 0")->field('id')->find(); ?>
<input type="hidden" id="current" value="<?php echo ($current); ?>">

<script>
    $('[data-role="addTo"]').click(function () {
        var id = "<?php echo ($current['id']); ?>";
        var url = "<?php echo U('Admin/Index/addTo');?>";
        $.post(url, {id: id}, function (msg) {
            if (msg.status) {
                console.log(msg);
                toast.success(msg.info);
                /*setTimeout(function () {
                 window.location.reload();
                 }, 500);*/
            } else {
                toast.error(msg.info);
            }
        }, 'json')
    });
</script>

        </div>
    </div>

    <div class="main-title">
        <h2><?php echo ($title); ?>            <?php if($suggest): ?>（<?php echo ($suggest); ?>）<?php endif; ?></h2>
    </div>

    <div class="with-padding">
        <div class="tab-wrap tabbable-custom" style="margin-bottom: 5px">
            <ul class="nav nav-tabs group_nav">
                <?php if(is_array($group)): $i = 0; $__LIST__ = $group;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vGroup): $mod = ($i % 2 );++$i;?><li class="<?php if( $i == 1): ?>active<?php endif; ?>"><a href="javascript:"><?php echo ($key); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
            <div class="tab-content">
                <form action="<?php echo ($savePostUrl); ?>" method="post" class="form-horizontal">
                    <?php if($group){ ?>
                    <!--看板-->
                    <?php if(is_array($group)): $i = 0; $__LIST__ = $group;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vGroup): $mod = ($i % 2 );++$i;?><div class="group_list" style="<?php if($i != 1): ?>display: none;<?php endif; ?>">
                            <?php if(is_array($keyList)): $i = 0; $__LIST__ = $keyList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$field): $mod = ($i % 2 );++$i; if(in_array($field['name'],$vGroup)||(is_array($field['name'])&&in_array(implode('|', $field['name']),$vGroup))){ ?>
                                <label class="item-label"><?php echo (htmlspecialchars($field["title"])); ?>
    <?php if($field['subtitle']): ?><span class="check-tips">（<?php echo ($field["subtitle"]); ?>）</span><?php endif; ?>
</label>
<?php if($field['name'] == 'action'): ?><p style="color: #f00;"><?php echo L("_DEVELOPMENT_STAFF_ATTENTION_"); echo L("_YOU_USE_A_FIELD_NAMED_ACTION_");?>，<?php echo L("_BECAUSE_THIS_FIELD_NAME_WILL_BE_WITH_FORM_");?>[action]<?php echo L("_CONFLICT_WHICH_CAUSES_THE_FORM_TO_BE_UNABLE_TO_COMMIT_PLEASE_USE_ANOTHER_NAME_");?></p><?php endif; ?>
<div class="controls ">
<?php switch($field["type"]): case "text": ?><input type="text" name="<?php echo ($field["name"]); ?>" value="<?php echo (htmlspecialchars($field["value"])); ?>"
               class="text input-large form-control" style="width: 400px"/><?php break;?>

    <?php case "label": echo ($field["value"]); break;?>


    <?php case "hidden": ?><input type="hidden" name="<?php echo ($field["name"]); ?>" value="<?php echo ($field["value"]); ?>" class="text input-large"/><?php break;?>
    <?php case "readonly": ?><input type="hidden" name="<?php echo ($field["name"]); ?>" value="<?php echo ($field["value"]); ?>" class="text input-large form-control"
               style="width: 400px" placeholder=<?php echo L("_NO_NEED_TO_FILL_IN_WITH_DOUBLE_");?> readonly/>
        <p class="lead" ><?php echo ($field["value"]); ?></p><?php break;?>
    <?php case "area_readonly": ?><p class="lead" ><?php echo ($field["value"]); ?></p><?php break;?>
    <?php case "integer": ?><input type="text" name="<?php echo ($field["name"]); ?>" value="<?php echo ($field["value"]); ?>" class="text input-large form-control"
               style="width: 400px"/><?php break;?>
    <?php case "uid": ?><input type="text" name="<?php echo ($field["name"]); ?>" value="<?php echo ($field["value"]); ?>" class="text input-large form-control"
               style="width: 100px"/><?php break;?>
    <?php case "select": ?><select name="<?php echo ($field["name"]); ?>" class="form-control" style="width:auto;">
            <?php if(is_array($field["opt"])): $i = 0; $__LIST__ = $field["opt"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$option): $mod = ($i % 2 );++$i; $selected = $field['value']==$key ? 'selected' : ''; ?>
                <option value="<?php echo ($key); ?>"
                <?php echo ($selected); ?>><?php echo (htmlspecialchars($option)); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
        </select><?php break;?>
    <?php case "colorPicker": $colorPicker = 1; ?>
        <div class="color-picker" style="width:100px;height: 30px;">
            <input type="text" name="<?php echo ($field["name"]); ?>" class="simple_color_callback form-control" onchange="setColorPicker(this);" value="<?php echo ((isset($field["value"]) && ($field["value"] !== ""))?($field["value"]):''); ?>" style="width: 100px;"/>
        </div><?php break;?>
    <?php case "radio": if(is_array($field["opt"])): $i = 0; $__LIST__ = $field["opt"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$option): $mod = ($i % 2 );++$i; $checked = $field['value']==$key ? 'checked' : ''; $inputId = "id_$field[name]_$key"; ?>
            <label for="<?php echo ($inputId); ?>"> <input id="<?php echo ($inputId); ?>" name="<?php echo ($field["name"]); ?>" value="<?php echo ($key); ?>" type="radio"
                <?php echo ($checked); ?>/>
                <?php echo ($option); ?></label> &nbsp;&nbsp;&nbsp;&nbsp;<?php endforeach; endif; else: echo "" ;endif; break;?>
    <?php case "icon": ?><div class='icon-chose' title=<?php echo L("_SELECT_ICON_WITH_DOUBLE_");?> style="width: 400px;">
            <select name="<?php echo ($field["name"]); ?>" title=<?php echo L("_SELECT_ICON_WITH_DOUBLE_");?> class="chosen-icons" data-value="<?php echo ((isset($field["value"]) && ($field["value"] !== ""))?($field["value"]):'icon-star'); ?>"></select>
        </div>
        <?php if(!$have_icon){ $have_icon=1; ?>
        <script src="/shanren/Application/Admin/Static/zui/lib/chosen/chosen.icons.min.js"></script>
        <link href="/shanren/Application/Admin/Static/zui/lib/chosen/chosen.icons.css" rel="stylesheet">
        <?php } ?>
        <script>
            $(function(){
                $('.chosen-container').remove()
                $('form select.chosen-icons').attr('class','chosen-icons');
                $('form select.chosen-icons').data('zui.chosenIcons',null);
                $('form select.chosen-icons').data('chosen',null);
                $('form select.chosen-icons').chosenIcons();
            });
        </script><?php break;?>

    <?php case "singleFile": echo W('Common/UploadFile/render',array(array('name'=>$field['name'],'value'=>$field['value']))); break;?>
    <?php case "multiFile": echo W('Common/UploadMultiFile/render',array(array('name'=>$field['name'],'limit'=>9,'value'=>$field['value']))); break;?>
    <?php case "singleImage": ?><div class="controls">
            <div id="upload_single_image_<?php echo ($field["name"]); ?>" style="padding-bottom: 5px;"><?php echo L("_SELECT_PICTURES_");?></div>
            <input class="attach" type="hidden" name="<?php echo ($field["name"]); ?>" value="<?php echo ($field['value']); ?>"/>
            <div class="upload-img-box">
                <div class="upload-pre-item popup-gallery">

                <?php if(!empty($field["value"])): ?><div class="each">
                    <a href="<?php echo (get_cover($field["value"],'path')); ?>" title=<?php echo L("_CLICK_TO_SEE_THE_BIG_PICTURE_WITH_DOUBLE_");?>>
                        <img src="<?php echo (get_cover($field["value"],'path')); ?>">
                    </a>
                        <div class="text-center opacity del_btn" ></div>
                        <div onclick="admin_image.removeImage($(this),'<?php echo ($field["value"]); ?>')"  class="text-center del_btn"><?php echo L("_DELETE_");?></div>
                    </div><?php endif; ?>
                </div>
            </div>
        </div>
        <script>
            $(function () {
                var uploader_<?php echo ($field["name"]); ?>= WebUploader.create({
                    // 选完文件后，是否自动上传。
                    auto: true,
                    // swf文件路径
                    swf: 'Uploader.swf',
                    // 文件接收服务端。
                    server: "<?php echo U('File/uploadPicture',array('session_id'=>session_id()));?>",
                    // 选择文件的按钮。可选。
                    // 内部根据当前运行是创建，可能是input元素，也可能是flash.
                    pick: '#upload_single_image_<?php echo ($field["name"]); ?>',
                    // 只允许选择图片文件
                    accept: {
                        title: 'Images',
                        extensions: 'gif,jpg,jpeg,bmp,png',
                        mimeTypes: 'image/*'
                    }
                });
                uploader_<?php echo ($field["name"]); ?>.on('fileQueued', function (file) {
                    uploader_<?php echo ($field["name"]); ?>.upload();
                });
                /*上传成功**/
                uploader_<?php echo ($field["name"]); ?>.on('uploadSuccess', function (file, data) {
                    if (data.status) {
                        $("[name='<?php echo ($field["name"]); ?>']").val(data.id);
                        $("[name='<?php echo ($field["name"]); ?>']").parent().find('.upload-pre-item').html(
                                ' <div class="each"><a href="'+ data.path+'" title=<?php echo L("_CLICK_TO_SEE_THE_BIG_PICTURE_WITH_DOUBLE_");?>><img src="'+ data.path+'"></a><div class="text-center opacity del_btn" ></div>' +
                                        '<div onclick="admin_image.removeImage($(this),'+data.id+')"  class="text-center del_btn"><?php echo L("_DELETE_");?></div></div>'
                        );
                        uploader_<?php echo ($field["name"]); ?>.reset();
                    } else {
                        updateAlert(data.info);
                        setTimeout(function () {
                            $('#top-alert').find('button').click();
                            $(that).removeClass('disabled').prop('disabled', false);
                        }, 1500);
                    }
                });
            })
        </script><?php break;?>

    <?php case "multiImage": ?><div class="controls multiImage">
            <div id="upload_multi_image_<?php echo ($field["name"]); ?>" style="padding-bottom: 5px;"><?php echo L("_SELECT_PICTURES_");?></div>
            <input class="attach" type="hidden" name="<?php echo ($field["name"]); ?>" value="<?php echo ($field['value']); ?>"/>
            <div class="upload-img-box">
                <div class="upload-pre-item popup-gallery">

                    <?php if(!empty($field["value"])): $aIds = explode(',',$field['value']); ?>
                        <?php if(is_array($aIds)): $i = 0; $__LIST__ = $aIds;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$aId): $mod = ($i % 2 );++$i;?><div class="each">
                                <a href="<?php echo (get_cover($aId,'path')); ?>" title=<?php echo L("_CLICK_TO_SEE_THE_BIG_PICTURE_WITH_DOUBLE_");?>>
                                    <img src="<?php echo (get_cover($aId,'path')); ?>">
                                </a>
                                <div class="text-center opacity del_btn" ></div>
                                <div onclick="admin_image.removeImage($(this),'<?php echo ($aId); ?>')"  class="text-center del_btn"><?php echo L("_DELETE_");?></div>
                            </div><?php endforeach; endif; else: echo "" ;endif; endif; ?>
                </div>
            </div>
        </div>
        <script>
            $(function () {
                var id = "#upload_multi_image_<?php echo ($field["name"]); ?>";
                var limit = parseInt('<?php echo ($field["opt"]); ?>');
                var uploader_<?php echo ($field["name"]); ?>= WebUploader.create({
                    // 选完文件后，是否自动上传。
                      // sw<?php echo L("_F_FILE_PATH_");?>
                    swf: 'Uploader.swf',
                    // 文件接收服务端。
                    server: "<?php echo U('File/uploadPicture',array('session_id'=>session_id()));?>",
                    // 选择文件的按钮。可选。
                    // 内部根据当前运行是创建，可能是input元素，<?php echo L("_AND_IT_COULD_BE_FLASH_");?>.
                    //pick: '#upload_multi_image_<?php echo ($field["name"]); ?>',
                    pick: {'id': id, 'multi': true},
                    fileNumLimit: limit,
                    // 只允许<?php echo L("_SELECT_PICTURES_");?>文件。
                    accept: {
                        title: 'Images',
                        extensions: 'gif,jpg,jpeg,bmp,png',
                        mimeTypes: 'image/*'
                    }
                });
                uploader_<?php echo ($field["name"]); ?>.on('fileQueued', function (file) {
                    uploader_<?php echo ($field["name"]); ?>.upload();
                });
                uploader_<?php echo ($field["name"]); ?>.on('uploadFinished', function (file) {
                    uploader_<?php echo ($field["name"]); ?>.reset();
                });
                /*上传成功**/
                uploader_<?php echo ($field["name"]); ?>.on('uploadSuccess', function (file, data) {
                          if (data.status) {
                            var ids = $("[name='<?php echo ($field["name"]); ?>']").val();
                            ids = ids.split(',');
                          if( ids.indexOf(data.id) == -1){
                                var rids = admin_image.upAttachVal('add',data.id, $("[name='<?php echo ($field["name"]); ?>']"));
                              if(rids.length>limit){
                                  updateAlert(<?php echo L('_EXCEED_THE_PICTURE_LIMIT_WITH_SINGLE_');?>);
                                  return;
                              }
                              $("[name='<?php echo ($field["name"]); ?>']").parent().find('.upload-pre-item').append(
                                        ' <div class="each"><a href="'+ data.path+'" title=<?php echo L("_CLICK_TO_SEE_THE_BIG_PICTURE_WITH_DOUBLE_");?>><img src="'+ data.path+'"></a><div class="text-center opacity del_btn" ></div>' +
                                                '<div onclick="admin_image.removeImage($(this),'+data.id+')"  class="text-center del_btn"><?php echo L("_DELETE_");?></div></div>'
                                );
                            }else{
                                updateAlert(<?php echo L('_THE_PICTURE_ALREADY_EXISTS_WITH_SINGLE_');?>);
                            }
                        } else {
                            updateAlert(data.info);
                            setTimeout(function () {
                                $('#top-alert').find('button').click();
                                $(that).removeClass('disabled').prop('disabled', false);
                            }, 1500);
                        }
                });
            })
        </script><?php break;?>

    <?php case "checkbox": $importCheckBox = true; ?>
        <?php $field['value_array'] = explode(',', $field['value']); ?>
        <?php if(is_array($field["opt"])): $i = 0; $__LIST__ = $field["opt"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$option): $mod = ($i % 2 );++$i; $checked = in_array($key,$field['value_array']) ? 'checked' : ''; $inputId = "id_$field[name]_$key"; ?>
            <label for="<?php echo ($inputId); ?>"> <input type="checkbox" value="<?php echo ($key); ?>" id="<?php echo ($inputId); ?>" class="oneplus-checkbox"
                                            data-field-name="<?php echo ($field["name"]); ?>" <?php echo ($checked); ?>/>
                <?php echo ($option); ?></label><?php endforeach; endif; else: echo "" ;endif; ?>
        <input type="hidden" name="<?php echo ($field["name"]); ?>" class="oneplus-checkbox-hidden"
               data-field-name="<?php echo ($field["name"]); ?>" value="<?php echo ($field["value"]); ?>"/><?php break;?>
    <?php case "editor": echo W('Common/Ueditor/editor',array($field['name'],$field['name'],$field['value'],$field['style']['width'],$field['style']['height'],$field['config'])); break;?>
    <?php case "textarea": ?><textarea name="<?php echo ($field["name"]); ?>" class="text input-large form-control"
                  style="height: 8em;width: 400px;height: 200px"><?php echo (htmlspecialchars($field["value"])); ?></textarea><?php break;?>
    <?php case "time": $importDatetimePicker = true; if(!$field['value']){ $field['value'] = time(); } ?>
        <input type="hidden" name="<?php echo ($field["name"]); ?>" value="<?php echo ($field["value"]); ?>"/>
        <div class="input-group date">
            <div class="input-group-addon">
                <i class="fa fa-calendar"></i>
            </div>
            <input type="text" data-field-name="<?php echo ($field["name"]); ?>" class="text input-large form-time time form-control pull-left"
                   style="width: 282px" value="<?php echo (time_format($field["value"],'H:i')); ?>" placeholder=<?php echo L("_PLEASE_CHOOSE_TIME_WITH_DOUBLE_");?>/>
        </div><?php break;?>
    <?php case "date": $importDatetimePicker = true; if(!$field['value']){ $field['value'] = time(); } ?>

        <input type="hidden" name="<?php echo ($field["name"]); ?>" value="<?php echo ($field["value"]); ?>"/>
        <div class="input-group date">
            <div class="input-group-addon">
                <i class="fa fa-calendar"></i>
            </div>
            <input type="text" data-field-name="<?php echo ($field["name"]); ?>" class="text input-large form-date time form-control pull-left"
                   style="width: 282px" value="<?php echo (time_format($field["value"],'Y-m-d')); ?>" placeholder=<?php echo L("_PLEASE_CHOOSE_TIME_WITH_DOUBLE_");?>/>
        </div><?php break;?>
    <?php case "datetime": $importDatetimePicker = true; if(!$field['value']){ $field['value'] = time(); } ?>
        <input type="hidden" name="<?php echo ($field["name"]); ?>" value="<?php echo ($field["value"]); ?>"/>
        <div class="input-group date">
            <div class="input-group-addon">
                <i class="fa fa-calendar"></i>
            </div>
            <input type="text" data-field-name="<?php echo ($field["name"]); ?>" class="text form-datetime time form-control pull-left"
                   style="width: 282px" value="<?php echo (time_format($field["value"])); ?>" placeholder=<?php echo L("_PLEASE_CHOOSE_TIME_WITH_DOUBLE_");?>/>
        </div><?php break;?>

    <!--添加城市选择（需安装城市联动插件,css样式不好处理排版有点怪）-->
    <?php case "city": ?><style type="text/css">
    			.form-control {
				display:inline-block;
				width: 120px;
				}
			</style>
            <!--修正在编辑信息时无法正常显示已经保存的地区信息-->
            <?php echo hook('J_China_City',array('province'=>$field['value']['0'],'city'=>$field['value']['1'],'district'=>$field['value']['2'],'community'=>$field['value']['3'])); break;?>

    <!--弹出窗口选择并返回值（目前只支持返回ID）开始->
    <?php case "dataselect": ?><input type="text" name="<?php echo ($field["name"]); ?>" id="<?php echo ($field["name"]); ?>" value="<?php echo (htmlspecialchars($field["value"])); ?>"
               class="text input-large form-control" style="width: 400px;display:inline-block;"/><input class="btn" style="margin-left:10px" type="button" value=<?php echo L("_CHOICE_WITH_DOUBLE_");?> onclick="openwin('<?php echo ($field["opt"]); ?>','600','500')">
			     <script type="text/javascript">
						//弹出窗口
						function openwin(url,width,height){
						    var l=window.screen.width ;
						    var w= window.screen.height;
						    var al=(l-width)/2;
						    var aw=(w-height)/2;
						    var OpenWindow=window.open(url,<?php echo L("_POP_UP_WINDOW_WITH_DOUBLE_");?>,"toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,width="+width+",height="+height+",top="+aw+",left="+al+"");
						    OpenWindow.focus();
						if(OpenWindow!=null){ //弹出窗口关闭事件
						//if(window.attachEvent) OpenWindow.attachEvent("onbeforeunload",   quickOut);
						if(window.attachEvent) OpenWindow.attachEvent("onunload",   quickOut);
						}
						}
						//关闭触发方法
						function quickOut()
						{
						alert(<?php echo L("_THE_WINDOW_IS_CLOSED_WITH_DOUBLE_");?>);
						}
				 </script><?php break;?>
	<!--弹出窗口选择并返回值（目前只支持返回ID）结束 -->

    <?php case "nestable": $has_nestable=1; ?>
        <input type="hidden" name="<?php echo ($field["name"]); ?>" value='<?php echo json_encode($field["value"]);?>'/>
        <div class="nestables nestable-group" data-name="<?php echo ($field["name"]); ?>">
            <?php foreach($field['value'] as $key =>$nestable){ ?>
            <div class="portlet light bordered" data-id="<?php echo ($nestable['data-id']); ?>" data-title="<?php echo ($nestable['title']); ?>" style="float:left;margin-right:10px;width: <?php echo ((isset($nestable['width']) && ($nestable['width'] !== ""))?($nestable['width']):300); ?>px;">
                <div class="portlet-title">
                    <div class="caption">
                        <span class="caption-subject font-green sbold uppercase"><strong><?php echo ($nestable['title']); ?></strong></span>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="dd nestable_list_one" id="nestable_list_<?php echo ($key+1); ?>">
                        <?php if(!empty($nestable["items"])): ?><ol class="dd-list">
                                <?php if(is_array($nestable["items"])): $i = 0; $__LIST__ = $nestable["items"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li class="dd-item" data-id="<?php echo ($vo["data-id"]); ?>" data-title="<?php echo ($vo["title"]); ?>">
                                        <div class="dd-handle"> <?php echo ($vo["title"]); ?> </div>
                                    </li><?php endforeach; endif; else: echo "" ;endif; ?>
                            </ol>
                            <?php else: ?>
                            <div class="dd-empty"></div><?php endif; ?>
                    </div>
                </div>
            </div>
            <?php } ?>
            <div class="clearfix"></div>
        </div><?php break;?>
    <?php case "chosen": $chosen_select2=true; ?>
        <select name="<?php echo ($field["name"]); ?>[]" class="form-control select2 select2-hidden-accessible" multiple="" data-placeholder="<?php echo ($field["title"]); ?>" style="width: 400px" tabindex="-1" aria-hidden="true">
            <?php if( key($field['opt']) === 0){ ?>
            <?php if(is_array($field['opt'])): $i = 0; $__LIST__ = $field['opt'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$option): $mod = ($i % 2 );++$i; $selected = in_array(reset($option),$field['value'])? 'selected' : ''; ?>
                <option value="<?php echo reset($option);?>" <?php echo ($selected); ?>><?php echo (htmlspecialchars(end($option))); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
            <?php }else{ foreach($field['opt'] as $optgroupkey =>$optgroup){ ?>
            <optgroup label="<?php echo ($optgroupkey); ?>">
                <?php if(is_array($optgroup)): $i = 0; $__LIST__ = $optgroup;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$option): $mod = ($i % 2 );++$i; $selected = in_array(reset($option),$field['value'])? 'selected' : ''; ?>
                    <option value="<?php echo reset($option);?>" <?php echo ($selected); ?>><?php echo (htmlspecialchars(end($option))); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
            </optgroup>
            <?php } } ?>
        </select><?php break;?>
    <?php case "multiInput": ?><div class="clearfix" style="<?php echo ($field['style']); ?>">
        <?php $field['name'] = is_array($field['name'])?$field['name']:explode('|',$field['name']); foreach($field['name'] as $key=>$val){ ?>
        <?php switch($field['config'][$key]['type']): case "text": ?><input type="text" name="<?php echo ($val); ?>" value="<?php echo (htmlspecialchars($field['value'][$key])); ?>"
                       class=" pull-left text form-control" style="<?php echo ($field['config'][$key]['style']); ?>" placeholder="<?php echo ($field['config'][$key]['placeholder']); ?>"/><?php break;?>
            <?php case "select": ?><select name="<?php echo ($val); ?>" class="pull-left form-control" style="<?php echo ($field['config'][$key]['style']); ?>" >
                    <?php foreach($field['config'][$key]['opt'] as $key_opt =>$option){ ?>
                    <?php $selected = $field['value'][$key]==$key_opt ? 'selected' : ''; ?>
                    <option value="<?php echo ($key_opt); ?>"<?php echo ($selected); ?>><?php echo (htmlspecialchars($option)); ?></option>
                    <?php } ?>
                </select><?php break; endswitch;?>
        <?php } ?>
        </div><?php break;?>

    <?php case "autoComplete": $delimiter = $field['opt']['delimiter']?$field['opt']['delimiter']:','; ?>

        <input type="text" name="<?php echo ($field["name"]); ?>" value="<?php echo (htmlspecialchars($field["value"])); ?>"
               class="text input-large form-control" style="width: 400px"/>

        <script src="/shanren/Public/js/ext/tokeninput/jquery.tokeninput.js"></script>
        <link href="/shanren/Public/js/ext/tokeninput/token-input-facebook.css" rel="stylesheet">

        <script>
            $(function () {
                $('[name="<?php echo ($field["name"]); ?>"]').tokenInput("<?php echo ($field['opt']['url']); ?>", {
                    theme: "facebook",
                    preventDuplicates: true,
                    tokenDelimiter: "<?php echo ($delimiter); ?>",
                    value: '<?php echo (htmlspecialchars($field["value"])); ?>'
                });
            });
        </script><?php break;?>



    <?php case "userDefined": echo ($field["definedHtml"]); break;?>

    <?php default: ?>
    <span style="color: #f00;"><?php echo L("_ERROR_"); echo L("_COLON_"); echo L("_UNKNOWN_FIELD_TYPE_"); echo ($field["type"]); ?></span>
    <input type="hidden" name="<?php echo ($field["name"]); ?>" value="<?php echo (htmlspecialchars($field["value"])); ?>"/><?php endswitch;?>
</div>
                                <?php } endforeach; endif; else: echo "" ;endif; ?>
                        </div><?php endforeach; endif; else: echo "" ;endif; ?>

                    <?php }else{ ?>
                    <?php if(is_array($keyList)): $i = 0; $__LIST__ = $keyList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$field): $mod = ($i % 2 );++$i;?><label class="item-label"><?php echo (htmlspecialchars($field["title"])); ?>
    <?php if($field['subtitle']): ?><span class="check-tips">（<?php echo ($field["subtitle"]); ?>）</span><?php endif; ?>
</label>
<?php if($field['name'] == 'action'): ?><p style="color: #f00;"><?php echo L("_DEVELOPMENT_STAFF_ATTENTION_"); echo L("_YOU_USE_A_FIELD_NAMED_ACTION_");?>，<?php echo L("_BECAUSE_THIS_FIELD_NAME_WILL_BE_WITH_FORM_");?>[action]<?php echo L("_CONFLICT_WHICH_CAUSES_THE_FORM_TO_BE_UNABLE_TO_COMMIT_PLEASE_USE_ANOTHER_NAME_");?></p><?php endif; ?>
<div class="controls ">
<?php switch($field["type"]): case "text": ?><input type="text" name="<?php echo ($field["name"]); ?>" value="<?php echo (htmlspecialchars($field["value"])); ?>"
               class="text input-large form-control" style="width: 400px"/><?php break;?>

    <?php case "label": echo ($field["value"]); break;?>


    <?php case "hidden": ?><input type="hidden" name="<?php echo ($field["name"]); ?>" value="<?php echo ($field["value"]); ?>" class="text input-large"/><?php break;?>
    <?php case "readonly": ?><input type="hidden" name="<?php echo ($field["name"]); ?>" value="<?php echo ($field["value"]); ?>" class="text input-large form-control"
               style="width: 400px" placeholder=<?php echo L("_NO_NEED_TO_FILL_IN_WITH_DOUBLE_");?> readonly/>
        <p class="lead" ><?php echo ($field["value"]); ?></p><?php break;?>
    <?php case "area_readonly": ?><p class="lead" ><?php echo ($field["value"]); ?></p><?php break;?>
    <?php case "integer": ?><input type="text" name="<?php echo ($field["name"]); ?>" value="<?php echo ($field["value"]); ?>" class="text input-large form-control"
               style="width: 400px"/><?php break;?>
    <?php case "uid": ?><input type="text" name="<?php echo ($field["name"]); ?>" value="<?php echo ($field["value"]); ?>" class="text input-large form-control"
               style="width: 100px"/><?php break;?>
    <?php case "select": ?><select name="<?php echo ($field["name"]); ?>" class="form-control" style="width:auto;">
            <?php if(is_array($field["opt"])): $i = 0; $__LIST__ = $field["opt"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$option): $mod = ($i % 2 );++$i; $selected = $field['value']==$key ? 'selected' : ''; ?>
                <option value="<?php echo ($key); ?>"
                <?php echo ($selected); ?>><?php echo (htmlspecialchars($option)); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
        </select><?php break;?>
    <?php case "colorPicker": $colorPicker = 1; ?>
        <div class="color-picker" style="width:100px;height: 30px;">
            <input type="text" name="<?php echo ($field["name"]); ?>" class="simple_color_callback form-control" onchange="setColorPicker(this);" value="<?php echo ((isset($field["value"]) && ($field["value"] !== ""))?($field["value"]):''); ?>" style="width: 100px;"/>
        </div><?php break;?>
    <?php case "radio": if(is_array($field["opt"])): $i = 0; $__LIST__ = $field["opt"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$option): $mod = ($i % 2 );++$i; $checked = $field['value']==$key ? 'checked' : ''; $inputId = "id_$field[name]_$key"; ?>
            <label for="<?php echo ($inputId); ?>"> <input id="<?php echo ($inputId); ?>" name="<?php echo ($field["name"]); ?>" value="<?php echo ($key); ?>" type="radio"
                <?php echo ($checked); ?>/>
                <?php echo ($option); ?></label> &nbsp;&nbsp;&nbsp;&nbsp;<?php endforeach; endif; else: echo "" ;endif; break;?>
    <?php case "icon": ?><div class='icon-chose' title=<?php echo L("_SELECT_ICON_WITH_DOUBLE_");?> style="width: 400px;">
            <select name="<?php echo ($field["name"]); ?>" title=<?php echo L("_SELECT_ICON_WITH_DOUBLE_");?> class="chosen-icons" data-value="<?php echo ((isset($field["value"]) && ($field["value"] !== ""))?($field["value"]):'icon-star'); ?>"></select>
        </div>
        <?php if(!$have_icon){ $have_icon=1; ?>
        <script src="/shanren/Application/Admin/Static/zui/lib/chosen/chosen.icons.min.js"></script>
        <link href="/shanren/Application/Admin/Static/zui/lib/chosen/chosen.icons.css" rel="stylesheet">
        <?php } ?>
        <script>
            $(function(){
                $('.chosen-container').remove()
                $('form select.chosen-icons').attr('class','chosen-icons');
                $('form select.chosen-icons').data('zui.chosenIcons',null);
                $('form select.chosen-icons').data('chosen',null);
                $('form select.chosen-icons').chosenIcons();
            });
        </script><?php break;?>

    <?php case "singleFile": echo W('Common/UploadFile/render',array(array('name'=>$field['name'],'value'=>$field['value']))); break;?>
    <?php case "multiFile": echo W('Common/UploadMultiFile/render',array(array('name'=>$field['name'],'limit'=>9,'value'=>$field['value']))); break;?>
    <?php case "singleImage": ?><div class="controls">
            <div id="upload_single_image_<?php echo ($field["name"]); ?>" style="padding-bottom: 5px;"><?php echo L("_SELECT_PICTURES_");?></div>
            <input class="attach" type="hidden" name="<?php echo ($field["name"]); ?>" value="<?php echo ($field['value']); ?>"/>
            <div class="upload-img-box">
                <div class="upload-pre-item popup-gallery">

                <?php if(!empty($field["value"])): ?><div class="each">
                    <a href="<?php echo (get_cover($field["value"],'path')); ?>" title=<?php echo L("_CLICK_TO_SEE_THE_BIG_PICTURE_WITH_DOUBLE_");?>>
                        <img src="<?php echo (get_cover($field["value"],'path')); ?>">
                    </a>
                        <div class="text-center opacity del_btn" ></div>
                        <div onclick="admin_image.removeImage($(this),'<?php echo ($field["value"]); ?>')"  class="text-center del_btn"><?php echo L("_DELETE_");?></div>
                    </div><?php endif; ?>
                </div>
            </div>
        </div>
        <script>
            $(function () {
                var uploader_<?php echo ($field["name"]); ?>= WebUploader.create({
                    // 选完文件后，是否自动上传。
                    auto: true,
                    // swf文件路径
                    swf: 'Uploader.swf',
                    // 文件接收服务端。
                    server: "<?php echo U('File/uploadPicture',array('session_id'=>session_id()));?>",
                    // 选择文件的按钮。可选。
                    // 内部根据当前运行是创建，可能是input元素，也可能是flash.
                    pick: '#upload_single_image_<?php echo ($field["name"]); ?>',
                    // 只允许选择图片文件
                    accept: {
                        title: 'Images',
                        extensions: 'gif,jpg,jpeg,bmp,png',
                        mimeTypes: 'image/*'
                    }
                });
                uploader_<?php echo ($field["name"]); ?>.on('fileQueued', function (file) {
                    uploader_<?php echo ($field["name"]); ?>.upload();
                });
                /*上传成功**/
                uploader_<?php echo ($field["name"]); ?>.on('uploadSuccess', function (file, data) {
                    if (data.status) {
                        $("[name='<?php echo ($field["name"]); ?>']").val(data.id);
                        $("[name='<?php echo ($field["name"]); ?>']").parent().find('.upload-pre-item').html(
                                ' <div class="each"><a href="'+ data.path+'" title=<?php echo L("_CLICK_TO_SEE_THE_BIG_PICTURE_WITH_DOUBLE_");?>><img src="'+ data.path+'"></a><div class="text-center opacity del_btn" ></div>' +
                                        '<div onclick="admin_image.removeImage($(this),'+data.id+')"  class="text-center del_btn"><?php echo L("_DELETE_");?></div></div>'
                        );
                        uploader_<?php echo ($field["name"]); ?>.reset();
                    } else {
                        updateAlert(data.info);
                        setTimeout(function () {
                            $('#top-alert').find('button').click();
                            $(that).removeClass('disabled').prop('disabled', false);
                        }, 1500);
                    }
                });
            })
        </script><?php break;?>

    <?php case "multiImage": ?><div class="controls multiImage">
            <div id="upload_multi_image_<?php echo ($field["name"]); ?>" style="padding-bottom: 5px;"><?php echo L("_SELECT_PICTURES_");?></div>
            <input class="attach" type="hidden" name="<?php echo ($field["name"]); ?>" value="<?php echo ($field['value']); ?>"/>
            <div class="upload-img-box">
                <div class="upload-pre-item popup-gallery">

                    <?php if(!empty($field["value"])): $aIds = explode(',',$field['value']); ?>
                        <?php if(is_array($aIds)): $i = 0; $__LIST__ = $aIds;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$aId): $mod = ($i % 2 );++$i;?><div class="each">
                                <a href="<?php echo (get_cover($aId,'path')); ?>" title=<?php echo L("_CLICK_TO_SEE_THE_BIG_PICTURE_WITH_DOUBLE_");?>>
                                    <img src="<?php echo (get_cover($aId,'path')); ?>">
                                </a>
                                <div class="text-center opacity del_btn" ></div>
                                <div onclick="admin_image.removeImage($(this),'<?php echo ($aId); ?>')"  class="text-center del_btn"><?php echo L("_DELETE_");?></div>
                            </div><?php endforeach; endif; else: echo "" ;endif; endif; ?>
                </div>
            </div>
        </div>
        <script>
            $(function () {
                var id = "#upload_multi_image_<?php echo ($field["name"]); ?>";
                var limit = parseInt('<?php echo ($field["opt"]); ?>');
                var uploader_<?php echo ($field["name"]); ?>= WebUploader.create({
                    // 选完文件后，是否自动上传。
                      // sw<?php echo L("_F_FILE_PATH_");?>
                    swf: 'Uploader.swf',
                    // 文件接收服务端。
                    server: "<?php echo U('File/uploadPicture',array('session_id'=>session_id()));?>",
                    // 选择文件的按钮。可选。
                    // 内部根据当前运行是创建，可能是input元素，<?php echo L("_AND_IT_COULD_BE_FLASH_");?>.
                    //pick: '#upload_multi_image_<?php echo ($field["name"]); ?>',
                    pick: {'id': id, 'multi': true},
                    fileNumLimit: limit,
                    // 只允许<?php echo L("_SELECT_PICTURES_");?>文件。
                    accept: {
                        title: 'Images',
                        extensions: 'gif,jpg,jpeg,bmp,png',
                        mimeTypes: 'image/*'
                    }
                });
                uploader_<?php echo ($field["name"]); ?>.on('fileQueued', function (file) {
                    uploader_<?php echo ($field["name"]); ?>.upload();
                });
                uploader_<?php echo ($field["name"]); ?>.on('uploadFinished', function (file) {
                    uploader_<?php echo ($field["name"]); ?>.reset();
                });
                /*上传成功**/
                uploader_<?php echo ($field["name"]); ?>.on('uploadSuccess', function (file, data) {
                          if (data.status) {
                            var ids = $("[name='<?php echo ($field["name"]); ?>']").val();
                            ids = ids.split(',');
                          if( ids.indexOf(data.id) == -1){
                                var rids = admin_image.upAttachVal('add',data.id, $("[name='<?php echo ($field["name"]); ?>']"));
                              if(rids.length>limit){
                                  updateAlert(<?php echo L('_EXCEED_THE_PICTURE_LIMIT_WITH_SINGLE_');?>);
                                  return;
                              }
                              $("[name='<?php echo ($field["name"]); ?>']").parent().find('.upload-pre-item').append(
                                        ' <div class="each"><a href="'+ data.path+'" title=<?php echo L("_CLICK_TO_SEE_THE_BIG_PICTURE_WITH_DOUBLE_");?>><img src="'+ data.path+'"></a><div class="text-center opacity del_btn" ></div>' +
                                                '<div onclick="admin_image.removeImage($(this),'+data.id+')"  class="text-center del_btn"><?php echo L("_DELETE_");?></div></div>'
                                );
                            }else{
                                updateAlert(<?php echo L('_THE_PICTURE_ALREADY_EXISTS_WITH_SINGLE_');?>);
                            }
                        } else {
                            updateAlert(data.info);
                            setTimeout(function () {
                                $('#top-alert').find('button').click();
                                $(that).removeClass('disabled').prop('disabled', false);
                            }, 1500);
                        }
                });
            })
        </script><?php break;?>

    <?php case "checkbox": $importCheckBox = true; ?>
        <?php $field['value_array'] = explode(',', $field['value']); ?>
        <?php if(is_array($field["opt"])): $i = 0; $__LIST__ = $field["opt"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$option): $mod = ($i % 2 );++$i; $checked = in_array($key,$field['value_array']) ? 'checked' : ''; $inputId = "id_$field[name]_$key"; ?>
            <label for="<?php echo ($inputId); ?>"> <input type="checkbox" value="<?php echo ($key); ?>" id="<?php echo ($inputId); ?>" class="oneplus-checkbox"
                                            data-field-name="<?php echo ($field["name"]); ?>" <?php echo ($checked); ?>/>
                <?php echo ($option); ?></label><?php endforeach; endif; else: echo "" ;endif; ?>
        <input type="hidden" name="<?php echo ($field["name"]); ?>" class="oneplus-checkbox-hidden"
               data-field-name="<?php echo ($field["name"]); ?>" value="<?php echo ($field["value"]); ?>"/><?php break;?>
    <?php case "editor": echo W('Common/Ueditor/editor',array($field['name'],$field['name'],$field['value'],$field['style']['width'],$field['style']['height'],$field['config'])); break;?>
    <?php case "textarea": ?><textarea name="<?php echo ($field["name"]); ?>" class="text input-large form-control"
                  style="height: 8em;width: 400px;height: 200px"><?php echo (htmlspecialchars($field["value"])); ?></textarea><?php break;?>
    <?php case "time": $importDatetimePicker = true; if(!$field['value']){ $field['value'] = time(); } ?>
        <input type="hidden" name="<?php echo ($field["name"]); ?>" value="<?php echo ($field["value"]); ?>"/>
        <div class="input-group date">
            <div class="input-group-addon">
                <i class="fa fa-calendar"></i>
            </div>
            <input type="text" data-field-name="<?php echo ($field["name"]); ?>" class="text input-large form-time time form-control pull-left"
                   style="width: 282px" value="<?php echo (time_format($field["value"],'H:i')); ?>" placeholder=<?php echo L("_PLEASE_CHOOSE_TIME_WITH_DOUBLE_");?>/>
        </div><?php break;?>
    <?php case "date": $importDatetimePicker = true; if(!$field['value']){ $field['value'] = time(); } ?>

        <input type="hidden" name="<?php echo ($field["name"]); ?>" value="<?php echo ($field["value"]); ?>"/>
        <div class="input-group date">
            <div class="input-group-addon">
                <i class="fa fa-calendar"></i>
            </div>
            <input type="text" data-field-name="<?php echo ($field["name"]); ?>" class="text input-large form-date time form-control pull-left"
                   style="width: 282px" value="<?php echo (time_format($field["value"],'Y-m-d')); ?>" placeholder=<?php echo L("_PLEASE_CHOOSE_TIME_WITH_DOUBLE_");?>/>
        </div><?php break;?>
    <?php case "datetime": $importDatetimePicker = true; if(!$field['value']){ $field['value'] = time(); } ?>
        <input type="hidden" name="<?php echo ($field["name"]); ?>" value="<?php echo ($field["value"]); ?>"/>
        <div class="input-group date">
            <div class="input-group-addon">
                <i class="fa fa-calendar"></i>
            </div>
            <input type="text" data-field-name="<?php echo ($field["name"]); ?>" class="text form-datetime time form-control pull-left"
                   style="width: 282px" value="<?php echo (time_format($field["value"])); ?>" placeholder=<?php echo L("_PLEASE_CHOOSE_TIME_WITH_DOUBLE_");?>/>
        </div><?php break;?>

    <!--添加城市选择（需安装城市联动插件,css样式不好处理排版有点怪）-->
    <?php case "city": ?><style type="text/css">
    			.form-control {
				display:inline-block;
				width: 120px;
				}
			</style>
            <!--修正在编辑信息时无法正常显示已经保存的地区信息-->
            <?php echo hook('J_China_City',array('province'=>$field['value']['0'],'city'=>$field['value']['1'],'district'=>$field['value']['2'],'community'=>$field['value']['3'])); break;?>

    <!--弹出窗口选择并返回值（目前只支持返回ID）开始->
    <?php case "dataselect": ?><input type="text" name="<?php echo ($field["name"]); ?>" id="<?php echo ($field["name"]); ?>" value="<?php echo (htmlspecialchars($field["value"])); ?>"
               class="text input-large form-control" style="width: 400px;display:inline-block;"/><input class="btn" style="margin-left:10px" type="button" value=<?php echo L("_CHOICE_WITH_DOUBLE_");?> onclick="openwin('<?php echo ($field["opt"]); ?>','600','500')">
			     <script type="text/javascript">
						//弹出窗口
						function openwin(url,width,height){
						    var l=window.screen.width ;
						    var w= window.screen.height;
						    var al=(l-width)/2;
						    var aw=(w-height)/2;
						    var OpenWindow=window.open(url,<?php echo L("_POP_UP_WINDOW_WITH_DOUBLE_");?>,"toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,width="+width+",height="+height+",top="+aw+",left="+al+"");
						    OpenWindow.focus();
						if(OpenWindow!=null){ //弹出窗口关闭事件
						//if(window.attachEvent) OpenWindow.attachEvent("onbeforeunload",   quickOut);
						if(window.attachEvent) OpenWindow.attachEvent("onunload",   quickOut);
						}
						}
						//关闭触发方法
						function quickOut()
						{
						alert(<?php echo L("_THE_WINDOW_IS_CLOSED_WITH_DOUBLE_");?>);
						}
				 </script><?php break;?>
	<!--弹出窗口选择并返回值（目前只支持返回ID）结束 -->

    <?php case "nestable": $has_nestable=1; ?>
        <input type="hidden" name="<?php echo ($field["name"]); ?>" value='<?php echo json_encode($field["value"]);?>'/>
        <div class="nestables nestable-group" data-name="<?php echo ($field["name"]); ?>">
            <?php foreach($field['value'] as $key =>$nestable){ ?>
            <div class="portlet light bordered" data-id="<?php echo ($nestable['data-id']); ?>" data-title="<?php echo ($nestable['title']); ?>" style="float:left;margin-right:10px;width: <?php echo ((isset($nestable['width']) && ($nestable['width'] !== ""))?($nestable['width']):300); ?>px;">
                <div class="portlet-title">
                    <div class="caption">
                        <span class="caption-subject font-green sbold uppercase"><strong><?php echo ($nestable['title']); ?></strong></span>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="dd nestable_list_one" id="nestable_list_<?php echo ($key+1); ?>">
                        <?php if(!empty($nestable["items"])): ?><ol class="dd-list">
                                <?php if(is_array($nestable["items"])): $i = 0; $__LIST__ = $nestable["items"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li class="dd-item" data-id="<?php echo ($vo["data-id"]); ?>" data-title="<?php echo ($vo["title"]); ?>">
                                        <div class="dd-handle"> <?php echo ($vo["title"]); ?> </div>
                                    </li><?php endforeach; endif; else: echo "" ;endif; ?>
                            </ol>
                            <?php else: ?>
                            <div class="dd-empty"></div><?php endif; ?>
                    </div>
                </div>
            </div>
            <?php } ?>
            <div class="clearfix"></div>
        </div><?php break;?>
    <?php case "chosen": $chosen_select2=true; ?>
        <select name="<?php echo ($field["name"]); ?>[]" class="form-control select2 select2-hidden-accessible" multiple="" data-placeholder="<?php echo ($field["title"]); ?>" style="width: 400px" tabindex="-1" aria-hidden="true">
            <?php if( key($field['opt']) === 0){ ?>
            <?php if(is_array($field['opt'])): $i = 0; $__LIST__ = $field['opt'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$option): $mod = ($i % 2 );++$i; $selected = in_array(reset($option),$field['value'])? 'selected' : ''; ?>
                <option value="<?php echo reset($option);?>" <?php echo ($selected); ?>><?php echo (htmlspecialchars(end($option))); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
            <?php }else{ foreach($field['opt'] as $optgroupkey =>$optgroup){ ?>
            <optgroup label="<?php echo ($optgroupkey); ?>">
                <?php if(is_array($optgroup)): $i = 0; $__LIST__ = $optgroup;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$option): $mod = ($i % 2 );++$i; $selected = in_array(reset($option),$field['value'])? 'selected' : ''; ?>
                    <option value="<?php echo reset($option);?>" <?php echo ($selected); ?>><?php echo (htmlspecialchars(end($option))); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
            </optgroup>
            <?php } } ?>
        </select><?php break;?>
    <?php case "multiInput": ?><div class="clearfix" style="<?php echo ($field['style']); ?>">
        <?php $field['name'] = is_array($field['name'])?$field['name']:explode('|',$field['name']); foreach($field['name'] as $key=>$val){ ?>
        <?php switch($field['config'][$key]['type']): case "text": ?><input type="text" name="<?php echo ($val); ?>" value="<?php echo (htmlspecialchars($field['value'][$key])); ?>"
                       class=" pull-left text form-control" style="<?php echo ($field['config'][$key]['style']); ?>" placeholder="<?php echo ($field['config'][$key]['placeholder']); ?>"/><?php break;?>
            <?php case "select": ?><select name="<?php echo ($val); ?>" class="pull-left form-control" style="<?php echo ($field['config'][$key]['style']); ?>" >
                    <?php foreach($field['config'][$key]['opt'] as $key_opt =>$option){ ?>
                    <?php $selected = $field['value'][$key]==$key_opt ? 'selected' : ''; ?>
                    <option value="<?php echo ($key_opt); ?>"<?php echo ($selected); ?>><?php echo (htmlspecialchars($option)); ?></option>
                    <?php } ?>
                </select><?php break; endswitch;?>
        <?php } ?>
        </div><?php break;?>

    <?php case "autoComplete": $delimiter = $field['opt']['delimiter']?$field['opt']['delimiter']:','; ?>

        <input type="text" name="<?php echo ($field["name"]); ?>" value="<?php echo (htmlspecialchars($field["value"])); ?>"
               class="text input-large form-control" style="width: 400px"/>

        <script src="/shanren/Public/js/ext/tokeninput/jquery.tokeninput.js"></script>
        <link href="/shanren/Public/js/ext/tokeninput/token-input-facebook.css" rel="stylesheet">

        <script>
            $(function () {
                $('[name="<?php echo ($field["name"]); ?>"]').tokenInput("<?php echo ($field['opt']['url']); ?>", {
                    theme: "facebook",
                    preventDuplicates: true,
                    tokenDelimiter: "<?php echo ($delimiter); ?>",
                    value: '<?php echo (htmlspecialchars($field["value"])); ?>'
                });
            });
        </script><?php break;?>



    <?php case "userDefined": echo ($field["definedHtml"]); break;?>

    <?php default: ?>
    <span style="color: #f00;"><?php echo L("_ERROR_"); echo L("_COLON_"); echo L("_UNKNOWN_FIELD_TYPE_"); echo ($field["type"]); ?></span>
    <input type="hidden" name="<?php echo ($field["name"]); ?>" value="<?php echo (htmlspecialchars($field["value"])); ?>"/><?php endswitch;?>
</div><?php endforeach; endif; else: echo "" ;endif; ?>
                    <?php } ?>
                    <br/>

                    <div class="form-item">
                        <?php if(is_array($buttonList)): $i = 0; $__LIST__ = $buttonList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$button): $mod = ($i % 2 );++$i;?><button <?php echo ($button["attr"]); ?>><?php echo ($button["title"]); ?></button>  &nbsp;<?php endforeach; endif; else: echo "" ;endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>


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

    <?php if($chosen_select2): ?><link rel="stylesheet" href="/shanren/Application/Admin/Static/adminlte/plugins/select2/select2.min.css">
        <script src="/shanren/Application/Admin/Static/adminlte/plugins/select2/select2.full.min.js"></script>
        <script>
            $(function(){
                $(".select2").select2();
            })
        </script><?php endif; ?>
    <?php if($has_nestable): ?><link href="/shanren/Application/Admin/Static/bootstrap/plugins/jquery-nestable/jquery.nestable.css" rel="stylesheet" type="text/css"/>
        <script src="/shanren/Application/Admin/Static/bootstrap/plugins/jquery-nestable/jquery.nestable.js" type="text/javascript"></script>
        <script src="/shanren/Application/Admin/Static/bootstrap/js/ui-nestable.min.js" type="text/javascript"></script>
        <script>
            $('.nestables').find('.dd').nestable({
                maxDepth:1
            });
            $('.nestable_list_one').on('change',function(){
                var obj=$(this).parents('.nestables');
                var nestable=new Array();
                obj.find('.portlet').each(function(index,element){
                    if ($(element).data('id')) {
                        nestable[index] =  new Object();
                        nestable[index]['data-id'] =  $(element).data('id');
                        nestable[index]['title'] =  $(element).data('title');
                        nestable[index]['items'] =  $(element).find('.dd').nestable('serialize');
                        nestable[index]['items'].forEach(function(li){
                            li['data-id']=li['id'];
                        });
                    }
                });
                var nestable_str=JSON.stringify(nestable);
                var flag=obj.data('name');
                $('[name="'+flag+'"]').val(nestable_str);
            });
        </script><?php endif; ?>

    <?php if($importDatetimePicker): ?><link href="/shanren/Application/Admin/Static/bootstrap/plugins/datetimepicker/datetimepicker.css" rel="stylesheet" type="text/css">
        <script type="text/javascript" src="/shanren/Application/Admin/Static/bootstrap/plugins/datetimepicker/datetimepicker.min.js"></script>

        <script>
            $('.form-datetime').datetimepicker({
                language: "zh-CN",
                autoclose: true,
                format: 'yyyy-mm-dd hh:ii'
            });
            $('.form-date').datetimepicker({
                language: "zh-CN",
                minView: 2,
                autoclose: true,
                format: 'yyyy-mm-dd'
            });
            $('.form-time').datetimepicker({
                language: "zh-CN",
                minView: 0,
                startView:1,
                autoclose: true,
                format: 'hh:ii'
            });
            $('.time').change(function () {
                var fieldName = $(this).attr('data-field-name');
                var dateString = $(this).val();
                var date = new Date(dateString);
                var timestamp = date.getTime();
                $('[name=' + fieldName + ']').val(Math.floor(timestamp / 1000));
            });
        </script><?php endif; ?>
    <?php if($colorPicker): ?><script type="text/javascript" src="/shanren/Application/Admin/Static/js/jquery.simple-color.js"></script>
        <script>
            $(function(){
                $('.simple_color_callback').simpleColor({
                    boxWidth:20,
                    cellWidth: 20,
                    cellHeight: 20,
                    chooserCSS:{ 'z-index': 500 },
                    displayCSS: { 'border': 0 ,
                        'width': '32px',
                        'height': '32px',
                        'margin-top': '-32px'
                    },
                    onSelect: function(hex, element) {
                        $('#tw_color').val('#'+hex);
                    }
                });
                $('.simple_color_callback').show();
                $('.simpleColorContainer').css('margin-left','105px');
                $('.simpleColorDisplay').css('border','1px solid #DFDFDF');
            });
            var setColorPicker=function(obj){
                var color=$(obj).val();
                $(obj).parents('.color-picker').find('.simpleColorDisplay').css('background',color);
            }
        </script><?php endif; ?>

    <?php if($importCheckBox): ?><script>
            $(function () {
                function implode(x, list) {
                    var result = "";
                    for (var i = 0; i < list.length; i++) {
                        if (result == "") {
                            result += list[i];
                        } else {
                            result += ',' + list[i];
                        }
                    }
                    return result;
                }

                $('.oneplus-checkbox').change(function (e) {
                    var fieldName = $(this).attr('data-field-name');
                    var checked = $('.oneplus-checkbox[data-field-name=' + fieldName + ']:checked');
                    var result = [];
                    for (var i = 0; i < checked.length; i++) {
                        var checkbox = $(checked.get(i));
                        result.push(checkbox.attr('value'));
                    }
                    result = implode(',', result);
                    $('.oneplus-checkbox-hidden[data-field-name=' + fieldName + ']').val(result);
                });
            })
        </script><?php endif; ?>

    <script type="text/javascript">
        $(function () {
            $('.group_nav li a').click(function () {
                $('.group_list').hide();
                $('.group_list').eq($(".group_nav li a").index(this)).show();
                $('.group_nav li').removeClass('active');
                $(this).parent().addClass('active');
            })
        })
        Think.setValue("type", <?php echo ((isset($info["type"]) && ($info["type"] !== ""))?($info["type"]):0); ?>);
        Think.setValue("group", <?php echo ((isset($info["group"]) && ($info["group"] !== ""))?($info["group"]):0); ?>);
        //导航高亮
        highlight_subnav('<?php echo U('Config / index');?>');
    </script>
    <link type="text/css" rel="stylesheet" href="/shanren/Public/js/ext/magnific/magnific-popup.css"/>
    <script type="text/javascript" src="/shanren/Public/js/ext/magnific/jquery.magnific-popup.min.js"></script>

    <script type="text/javascript" charset="utf-8" src="/shanren/Public/js/ext/webuploader/js/webuploader.js"></script>
    <link href="/shanren/Public/js/ext/webuploader/css/webuploader.css" type="text/css" rel="stylesheet">


    <script>
        $(document).ready(function () {
            $('.popup-gallery').each(function () { // the containers for all your galleries
                $(this).magnificPopup({
                    delegate: 'a',
                    type: 'image',
                    tLoading: '<?php echo L("_LOADING_");?>#%curr%...',
                    mainClass: 'mfp-img-mobile',
                    gallery: {
                        enabled: true,
                        navigateByImgClick: true,
                        preload: [0, 1] // Will preload 0 - before current, and 1 after the current image

                    },
                    image: {
                        tError: '<a href="%url%"><?php echo L("_PICTURE_");?>#%curr%</a><?php echo L("_COULD_NOT_BE_LOADED_");?>',
                        titleSrc: function (item) {
                            /*           return item.el.attr('title') + '<small>by Marsel Van Oosten</small>';*/
                            return '';
                        },
                        verticalFit: true
                    }
                });
            });
            <?php echo ($myJs); ?>
        });
    </script>



</body>
</html>