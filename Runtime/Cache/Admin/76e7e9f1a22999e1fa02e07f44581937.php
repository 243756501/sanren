<?php if (!defined('THINK_PATH')) exit(); if(!$isLoadScript){ ?>
<script type="text/javascript" charset="utf-8" src="/shanren/Public/js/ext/webuploader/js/webuploader.js"></script>
<link href="/shanren/Public/js/ext/webuploader/css/webuploader.css" type="text/css" rel="stylesheet">
<?php } ?>

<div id="file_list_<?php echo ($id); ?>">
    <?php if(empty($file)): else: ?><div><?php echo ($file["name"]); ?> <a onclick="remove_file_<?php echo ($id); ?>(this,<?php echo ($file["id"]); ?>)"><i class="icon-trash"></i></a> </div><?php endif; ?>

</div>

<div id="uploader_<?php echo ($id); ?>">
    <div class="btns">
        <div id="picker"><?php echo L('_FILE_SELECT_');?></div>
    </div>

</div>
<input name="<?php echo ($name); ?>" id="file_upload_<?php echo ($id); ?>" type="hidden" value="<?php echo ($value); ?>">
<script>
    var id = "#uploader_<?php echo ($id); ?>";
    var $list_<?php echo ($id); ?>=$('#file_list_<?php echo ($id); ?>');
    var uploader_<?php echo ($id); ?> = WebUploader.create({

        // swf文件路径
        swf: 'Uploader.swf',

        // 文件接收服务端。
        server: "<?php echo U('Core/File/uploadFile');?>",

        // 选择文件的按钮。可选。
        // 内部根据当前运行是创建，可能是input元素，也可能是flash.
        pick: id,

        // 不压缩image, 默认如果是jpeg，文件上传前会压缩一把再上传！
        resize: false
    });
    // 当有文件被添加进队列的时候
    uploader_<?php echo ($id); ?>.on( 'fileQueued', function( file ) {
        uploader_<?php echo ($id); ?>.upload();
        toast.showLoading();
    });
    // 文件上传过程中创建进度条实时显示。
    uploader_<?php echo ($id); ?>.on( 'uploadSuccess', function( file ,ret ) {

        if(ret.status==1){
            toast.success("<?php echo L('_SUCCESS_UPLOAD_'); echo L('_PERIOD_');?>");
            $list_<?php echo ($id); ?>.html('<div>'+ret.data.file.name+' <a onclick="remove_file_<?php echo ($id); ?>'+'(this,'+ret.data.file.id+')"><i class="icon-trash"></i></a></div>');
           $('#file_upload_<?php echo ($id); ?>').val(ret.data.file.id);
        }else{
            toast.error("<?php echo L('_FAIL_UPLOAD_'); echo L('_PERIOD_');?>"+ret.info);
        }
        console.log(ret)


    });

    uploader_<?php echo ($id); ?>.on( 'uploadError', function( file ) {
        toast.error("<?php echo L('_ERROR_UPLOAD_'); echo L('_PERIOD_');?>")
    });

    uploader_<?php echo ($id); ?>.on( 'uploadComplete', function( file ) {
      toast.hideLoading();
    });

    function remove_file_<?php echo ($id); ?>(obj, attachId) {
        $('#file_upload_<?php echo ($id); ?>').val('');
        $(obj).parent('div').remove();
    }
</script>