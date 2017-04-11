/**
 * Created by Administrator on 2017/2/17 0017.
 */
$(function () {
    re_bind();

});

var re_bind = function () {
    change_select();//选择导航栏类型
    change_module();//系统导航栏模块选择
    add_one();//添加一个新导航栏
    remove_li();//移除导航栏
    fix_form();//拖动导航栏
    bind_color();//选择颜色
    add_flag();//排序
    target_change();
    status_change();
    //bind_chose_icon();
};

$('[data-role="reset_nav"]').click(function () {
    var url = $('.form-horizontal').attr('acton');
    $.post(url,{event:'reset'}, function (msg) {
        if (msg.status) {
            toast.success(msg.info);
            setTimeout(function () {
                window.location.reload();
            }, 300);
        } else {
            toast.error(msg.info);
        }
    }, 'json')
});

var target_change = function(){
    $('.target').change(function(){
        $(this).closest('.new-blank').find('.target_input').val($(this).is(':checked')?1:0);
    })
};
var status_change = function () {
    $('.status').change(function(){
        $(this).closest('.status-view').find('.status_input').val($(this).is(':checked')?1:0);
    })
};
var bind_chose_icon = function(){
    $('.select-os-icon').select_os_icon();
};
var bind_color = function () {
    $('.simpleColorContainer').remove();
    $('.simple_color_callback').simpleColor({
        boxWidth: 15,
        boxHeight: 15,
        cellWidth: 15,
        cellHeight: 15,
        chooserCSS: { 'z-index': 1200 },
        displayCSS: { 'border': 0 }
    });
};
var change_select = function () {
    $('.nav-type').unbind('change');
    $('.nav-type').change(function () {
        var obj = $(this);
        switch (obj.val()) {
            case 'module':
                obj.closest('li>div').children('select.module').show().change();
                obj.closest('li>div').children('input.url').hide();
                obj.closest('li').children('.new-blank').hide();
                obj.closest('li').children('.band-text')[0].querySelector('.remark').value = obj.closest('li>div').children('select.module').find("option:selected").attr('data-remark');
                obj.closest('li').children('.new-blank')[0].querySelector('.target_input').value = 0;
                break;
            case 'custom':
                obj.closest('li>div').children('select.module').hide();
                obj.closest('li>div').children('input.url').show();
                obj.closest('li>div').children('input.name').val('');
                obj.closest('li>div').children('input.title').val('');
                obj.closest('li>div').children('input.url').val('');
                obj.closest('li').children('.new-blank').show();
                obj.closest('li').children('.band-text')[0].querySelector('.remark').value = '';
                break;
        }
    })
};


var change_module = function () {
    $('.module').unbind('change');
    $('.module').change(function () {
        var obj = $(this);
        var text = obj.find("option:selected").text();
        var value = obj.val();
        obj.closest('li').children('.band-text')[0].querySelector('.remark').value = obj.find("option:selected").attr('data-remark');
        obj.closest('li>div').children('input.name').val(obj.find("option:selected").attr('data-name'));
        obj.closest('li>div').children('input.title').val(text);
        obj.closest('li>div').children('input.url').val(value);
        obj.closest('li>div').next().children('select.chosen-icons').attr('data-value','icon-'+obj.find("option:selected").data('icon'));
        re_bind();
    })

};


var fix_form = function () {
    $('.channel-ul').sortable({trigger: '.sort-handle-1', selector: 'li', dragCssClass: '',finish:function(){
        re_bind();
    }
    });
    $('.channel-ul .ul-2').sortable({trigger: '.sort-handle-2', selector: 'li', dragCssClass: '',finish:function(){
        re_bind();
    }});

};

var add_one = function () {
    $('.add-one').unbind('click');
    $('.add-one').click(function () {
        $(this).closest('.pLi').after($('#one-nav').html());
        re_bind();
    })
};

var remove_li = function () {
    $('.remove-li').unbind('click');
    $('.remove-li').click(function () {
        if( $(this).parents('form').find('.pLi').length > 1){
            $(this).closest('li').remove();
            re_bind();
        }else{
            updateAlert('不能再减了~');
        }

    })
};

var add_flag = function () {
    $('.channel-ul .pLi').each(function (index, element) {
        $(this).attr('data-id', index);
        $(this).find('.sort').val($(this).attr('data-order'));
    });
};

/*$('#up').click(function () {
 var uploader = WebUploader.create({
 // swf文件路径
 swf: './Public/js/ext/webuploader/js/Uploader.swf',
 // 文件接收服务端。
 server: U('Core/File/uploadPicture'),

 // 内部根据当前运行是创建，可能是input元素，也可能是flash.
 pick: '#up',
 accept: {
 title: 'Images',
 extensions: 'gif,jpg,jpeg,bmp,png',
 mimeTypes: 'image/!*'
 }
 });


 });*/
/*$('#up').click(function () {
 var uploader = WebUploader.create({

 // 选完文件后，是否自动上传。
 auto: true,

 // swf文件路径
 swf: './Public/js/ext/webuploader/js/Uploader.swf',

 // 文件接收服务端。
 server: U('Core/File/uploadPicture'),

 // 选择文件的按钮。可选。
 // 内部根据当前运行是创建，可能是input元素，也可能是flash.
 pick: '#up',

 // 只允许选择图片文件。
 accept: {
 title: 'Images',
 extensions: 'gif,jpg,jpeg,bmp,png',
 mimeTypes: 'image/!*'
 }
 });
 uploader.on('uploadError', function (file) {
 console.log(file);
 toast.error('上传出错。')
 });
 })*/
var uploadimg=function (obj) {
    $(obj).next().click()
};
var upload_img=function (obj) {
    var name=$(obj).val();
    alert(name);
    console.log($('#'+$(obj).attr('id')));
    // $(obj).next().val(name);
    $.ajaxFileUpload({
            url: U('Admin/Channel/uploadimg'),
            secureuri: false,
            fileElementId: 'aa',
            dataType:'json',
            success:function (msg) {
                if(msg=='1'){
                    toast.success('ok');
                }else{
                    toast.error('no');
                }

            }
        }
    );
};
