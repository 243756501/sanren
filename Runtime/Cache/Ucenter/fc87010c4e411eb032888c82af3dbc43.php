<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">

<?php echo hook('syncMeta');?>

<?php $oneplus_seo_meta = get_seo_meta($vars,$seo); ?>
<?php if($oneplus_seo_meta['title']): ?><title><?php echo ($oneplus_seo_meta['title']); ?></title>
    <?php else: ?>
    <title><?php echo modC('WEB_SITE_NAME',L('_OPEN_SNS_'),'Config');?></title><?php endif; ?>
<?php if($oneplus_seo_meta['keywords']): ?><meta name="keywords" content="<?php echo ($oneplus_seo_meta['keywords']); ?>"/><?php endif; ?>
<?php if($oneplus_seo_meta['description']): ?><meta name="description" content="<?php echo ($oneplus_seo_meta['description']); ?>"/><?php endif; ?>

<link rel="shortcut icon" type="image/x-icon" href="favicon.ico" media="screen" />

<!-- zui -->
<link href="/shanren/Public/zui/css/zui.css" rel="stylesheet">

<link href="/shanren/Public/zui/css/zui-theme.css" rel="stylesheet">
<link href="/shanren/Public/static/os-icon/simple-line-icons.min.css" rel="stylesheet">
<link href="/shanren/Public/static/os-loading/loading.css" rel="stylesheet">
<link href="/shanren/Public/css/core.css" rel="stylesheet"/>
<link type="text/css" rel="stylesheet" href="/shanren/Public/js/ext/magnific/magnific-popup.css"/>
<!--<script src="/shanren/Public/js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="/shanren/Public/js/com/com.functions.js"></script>

<script type="text/javascript" src="/shanren/Public/js/core.js"></script>-->
<script src="/shanren/Public/js.php?f=js/jquery-2.0.3.min.js,js/com/com.functions.js,static/os-loading/loading.js,js/core.js,js/com/com.toast.class.js,js/com/com.ucard.js"></script>



<!--Style-->
<!--合并前的js-->
<?php $config = api('Config/lists'); C($config); $count_code=C('COUNT_CODE'); ?>
<script type="text/javascript">
    var ThinkPHP = window.Think = {
        "ROOT": "/shanren", //当前网站地址
        "APP": "/shanren/index.php?s=", //当前项目地址
        "PUBLIC": "/shanren/Public", //项目公共目录地址
        "DEEP": "<?php echo C('URL_PATHINFO_DEPR');?>", //PATHINFO分割符
        "MODEL": ["<?php echo C('URL_MODEL');?>", "<?php echo C('URL_CASE_INSENSITIVE');?>", "<?php echo C('URL_HTML_SUFFIX');?>"],
        "VAR": ["<?php echo C('VAR_MODULE');?>", "<?php echo C('VAR_CONTROLLER');?>", "<?php echo C('VAR_ACTION');?>"],
        'URL_MODEL': "<?php echo C('URL_MODEL');?>",
        'WEIBO_ID': "<?php echo C('SHARE_WEIBO_ID');?>"
    }
    var cookie_config={
        "prefix":"<?php echo C('COOKIE_PREFIX');?>",// cookie 名称前缀
        "path" :"<?php echo C('COOKIE_PATH');?>", // cookie 保存路径
        "domain":"<?php echo C('COOKIE_DOMAIN');?>" // cookie 有效域名
    }
    var Config={
        'GET_INFORMATION':<?php echo modC('GET_INFORMATION',1,'Config');?>,
        'GET_INFORMATION_INTERNAL':<?php echo modC('GET_INFORMATION_INTERNAL',10,'Config');?>*1000,
        'WEBSOCKET_ADDRESS':"<?php echo modC('WEBSOCKET_ADDRESS',gethostbyname($_SERVER['SERVER_NAME']),'Config');?>",
        'WEBSOCKET_PORT':<?php echo modC('WEBSOCKET_PORT',8000,'Config');?>
    }
    var weibo_comment_order = "<?php echo modC('COMMENT_ORDER',0,'WEIBO');?>";
</script>

<script src="/shanren/Public/lang.php?module=<?php echo strtolower(MODULE_NAME);?>&lang=<?php echo LANG_SET;?>"></script>

<script src="/shanren/Public/expression.php"></script>

<!-- Bootstrap库 -->
<!--
<?php $js[]=urlencode('/static/bootstrap/js/bootstrap.min.js'); ?>

&lt;!&ndash; 其他库 &ndash;&gt;
<script src="/shanren/Public/static/qtip/jquery.qtip.js"></script>
<script type="text/javascript" src="/shanren/Public/Core/js/ext/slimscroll/jquery.slimscroll.min.js"></script>
<script type="text/javascript" src="/shanren/Public/static/jquery.iframe-transport.js"></script>
-->
<!--CNZZ广告管家，可自行更改-->
<!--<script type='text/javascript' src='http://js.adm.cnzz.net/js/abase.js'></script>-->
<!--CNZZ广告管家，可自行更改end-->
<!-- 自定义js -->
<!--<script src="/shanren/Public/js.php?get=<?php echo implode(',',$js);?>"></script>-->

<?php D('Pushing')->doRun(); $key = C('DATA_AUTH_KEY'); $timestamp = time(); $signature = md5(is_login().$timestamp.$key); ?>
<script>
    //全局内容的定义
    var _ROOT_ = "/shanren";
    var MID = "<?php echo is_login();?>";
    var SIGNATURE = "<?php echo ($signature); ?>";
    var TIMESTAMP = "<?php echo ($timestamp); ?>";
    var MODULE_NAME="<?php echo MODULE_NAME; ?>";
    var ACTION_NAME="<?php echo ACTION_NAME; ?>";
    var CONTROLLER_NAME ="<?php echo CONTROLLER_NAME; ?>";
    var initNum = "<?php echo modC('WEIBO_NUM',140,'WEIBO');?>";
    function adjust_navbar(){
        $('#sub_nav').css('top',$('#nav_bar').height());
        $('#main-container').css('padding-top',$('#nav_bar').height()+$('#sub_nav').height()+20)
    }
</script>

<audio id="music" src="" autoplay="autoplay"></audio>
<!-- 页面header钩子，一般用于加载插件CSS文件和代码 -->
<?php echo hook('pageHeader');?>
    <link href="/shanren/Application/Ucenter/Static/css/new-login.css" type="text/css" rel="stylesheet">
    <style>
        body{
            background-color: #1abc9c;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="all-box">
        <div class="register-wrap">
            <p class="head">还没有账号?</p>
            <p>注册 <?php echo modC('WEB_SITE_NAME','OpenSNS开源社交系统','Config');?>，或者偷懒使用第三方账号登录</p>
            <?php $register_type=modC('REGISTER_TYPE','normal','Invite'); $register_type=explode(',',$register_type); $only_open_register=0; if(in_array('invite',$register_type)&&!in_array('normal',$register_type)){ $only_open_register=1; } $third=get_addons_status('SyncLogin'); ?>
            <script>
                var ONLY_OPEN_REGISTER = "<?php echo ($only_open_register); ?>";
            </script>
            <a data-url="<?php echo U('Ucenter/Member/register');?>" data-role="do_register">
                <div class="to-reg">
                    立即注册<i class="icon icon-circle-arrow-right"></i>
                </div>
            </a>

            <?php if($third == 1): ?><div class="third-line"></div>
                <div class="third-login">
                    <?php echo hook('syncLogin');?>
                </div><?php endif; ?>
        </div>
        <form action="/shanren/index.php?s=/ucenter/member/login.html" method="post" class="lg_lf_form ">
            <div class="login-wrap">
                <p class="head">登 录</p>
                <!--用户名输入-->
                <div class="input-box">
                    <input type="text" id="inputEmail" placeholder="请输入账号" ajaxurl="/member/checkUserNameUnique.html" errormsg="<?php echo L('_MI_USERNAME_ERROR_');?>" nullmsg="<?php echo L('_MI_USERNAME_');?>" datatype="*4-32" value="" name="username" autocomplete="off">
                </div>
                <!--密码输入-->
                <div class="input-box" id="password_block">
                    <input type="password" id="inputPassword" placeholder="<?php echo L('_NEW_PW_INPUT_');?>" errormsg="<?php echo L('_PW_ERROR_');?>" nullmsg="<?php echo L('_PW_INPUT_ERROR_');?>" datatype="*6-30" name="password">
                    <i onclick="change_show(this)" class="icon-eye-open open-close"></i>
                </div>
                <!--验证码输入，如果开启-->
                <?php if(check_verify_open('login')): ?><div class="input-box">
                        <input type="text" id="verifyCode"  placeholder="<?php echo L('_VERIFY_CODE_');?>"
                               errormsg="<?php echo L('_MI_CODE_NULL_');?>" nullmsg="<?php echo L('_MI_CODE_NULL_');?>" datatype="*5-5" name="verify">
                        <div class="code-box lg_lf_fm_verify">
                            <img class="verifyimg reloadverify  " alt="<?php echo L('_MI_ALT_');?>" src="<?php echo U('verify');?>">
                        </div>
                        <div class="col-xs-11 Validform_checktip text-warning lg_lf_fm_tip col-sm-offset-1"></div>
                        <div class="clearfix"></div>
                    </div><?php endif; ?>
                <div class="clearfix form-group">
                    <div class="col-xs-6" style="padding-left: 0">
                        <label style="color: #848484;font-weight: normal">
                            <input type="checkbox" checked="checked" name="remember" value="1" style="cursor:pointer;">
                            <?php echo L('_REMEMBER_LOGIN_');?>
                        </label>
                    </div>
                    <?php if(check_reg_type('email')||check_reg_type('mobile')){ ?>
                    <div class="col-xs-6 text-right" style="padding-right: 0">
                        <div class=""><a class="" href="<?php echo U('Member/mi');?>"
                                         style="color: #848484;font-size: 12px;"><?php echo L('_FORGET_PW_'); echo L('_QUESTION_');?></a>
                        </div>
                    </div>
                    <?php } ?>
                </div>
                <input name="from" type="hidden" value="<?php echo $_SERVER['HTTP_REFERER'] ?>">
                <?php session('login_http_referer',$_SERVER['HTTP_REFERER']); ?>
                <div class="yes-wrap form-group text-right">
                    <a class="l-around" href="<?php echo U('Weibo/index/index');?>">随便看看</a>
                    <button type="submit" class="login-btn"><?php echo L('_LOGIN_SPACE_');?></button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- jQuery (ZUI中的Javascript组件依赖于jQuery) -->


<!-- 为了让html5shiv生效，请将所有的CSS都添加到此处 -->
<link type="text/css" rel="stylesheet" href="/shanren/Public/static/qtip/jquery.qtip.css"/>


<!--<script type="text/javascript" src="/shanren/Public/js/com/com.notify.class.js"></script>-->

<!-- 其他库-->
<!--<script src="/shanren/Public/static/qtip/jquery.qtip.js"></script>
<script type="text/javascript" src="/shanren/Public/js/ext/slimscroll/jquery.slimscroll.min.js"></script>
<script type="text/javascript" src="/shanren/Public/static/jquery.iframe-transport.js"></script>

<script type="text/javascript" src="/shanren/Public/js/ext/magnific/jquery.magnific-popup.min.js"></script>-->

<!--<script type="text/javascript" src="/shanren/Public/js/ext/placeholder/placeholder.js"></script>
<script type="text/javascript" src="/shanren/Public/js/ext/atwho/atwho.js"></script>
<script type="text/javascript" src="/shanren/Public/zui/js/zui.js"></script>-->
<link type="text/css" rel="stylesheet" href="/shanren/Public/js/ext/atwho/atwho.css"/>

<script src="/shanren/Public/js.php?t=js&f=js/com/com.notify.class.js,static/qtip/jquery.qtip.js,js/ext/slimscroll/jquery.slimscroll.min.js,js/ext/magnific/jquery.magnific-popup.min.js,js/ext/placeholder/placeholder.js,js/ext/atwho/atwho.js,zui/js/zui.js&v=<?php echo ($site["sys_version"]); ?>.js"></script>
<script type="text/javascript" src="/shanren/Public/static/jquery.iframe-transport.js"></script>

<script src="/shanren/Public/js/ext/lazyload/lazyload.js"></script>

<script src="/shanren/Public/js/socket.io.js"></script>
<script type="text/javascript">
    var quickLogin = "<?php echo ($login_type); ?>";
    $(document)
            .ajaxStart(function () {
                $("button:submit").addClass("log-in").attr("disabled", true);
            })
            .ajaxStop(function () {
                $("button:submit").removeClass("log-in").attr("disabled", false);
            });

    function change_show(obj) {
        if ($(obj).hasClass('icon-eye-open')) {
            var value = $('#inputPassword').val().trim();
            var html = '<input type="text" value="' + value + '" id="inputPassword"  placeholder="'+"<?php echo L('_NEW_PW_INPUT_');?>"+'" errormsg="'+"<?php echo L('_PW_ERROR_');?>"+'" nullmsg="'+"<?php echo L('_PW_INPUT_ERROR_');?>"+'" datatype="*6-30" name="password">' +
                    '<i onclick="change_show(this)" class="icon-eye-close open-close">';
            $('#password_block').html(html);
        } else {
            var value = $('#inputPassword').val().trim();
            var html = '<input type="password" value="' + value + '" id="inputPassword"  placeholder="'+"<?php echo L('_NEW_PW_INPUT_');?>"+'" errormsg="'+"<?php echo L('_PW_ERROR_');?>"+'" nullmsg="'+"<?php echo L('_PW_INPUT_ERROR_');?>"+'" datatype="*6-30" name="password">' +
                    '<i onclick="change_show(this)" class="icon-eye-open open-close">';
            $('#password_block').html(html);
        }
    }

    $(function () {
        $("form").submit(function () {
            toast.showLoading();
            var self = $(this);
            $.post(self.attr("action"), self.serialize(), success, "json");
            return false;
            function success(data) {
                if (data.status) {
                    if (data.url==undefined&&quickLogin == "quickLogin") {
                        toast.success("<?php echo L('_WELCOME_RETURN_'); echo L('_PERIOD_');?>"+data.info, "<?php echo L('_TIP_GENTLE_');?>");
                        setTimeout(function () {
                            window.location.reload();
                        }, 1500);
                    } else {
                        toast.success("<?php echo L('_WELCOME_RETURN_REDIRECTING_');?>"+data.info, "<?php echo L('_TIP_GENTLE_');?>");
                        setTimeout(function () {
                            window.location.href = data.url;
                        }, 1500);
                    }
                } else {
                    toast.error(data.info, "<?php echo L('_TIP_GENTLE_');?>");
                    //self.find(".Validform_checktip").text(data.info);
                    //刷新验证码
                    $(".reloadverify").click();
                }
                toast.hideLoading();
            }
        });
        var verifyimg = $(".verifyimg").attr("src");
        $(".reloadverify").click(function () {
            if (verifyimg.indexOf('?') > 0) {
                $(".verifyimg").attr("src", verifyimg + '&random=' + Math.random());
            } else {
                $(".verifyimg").attr("src", verifyimg.replace(/\?.*$/, '') + '?' + Math.random());
            }
        });
    });
</script>
</body>
</html>