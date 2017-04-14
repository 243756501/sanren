<?php if (!defined('THINK_PATH')) exit();?><div class="weibo_post_box">
    <p class="comment-area">
        <input type="hidden"  name="reply_id" value="0"/>

        <input type="text" placeholder="评论（Ctrl+Enter）" id="text_<?php echo ($weiboId); ?>" rows="2" data-weibo-id="<?php echo ($weiboId); ?>"
               class="comment-input  weibo-comment-content comment_text_inputor">

        <a style="margin-right: 10px" href="javascript:" class="" onclick="insertFace($(this))"><i style="color: #999;" class="os-icon-emoticon-smile"></i> </a>
        <a  data-role="do_comment" id="btn_<?php echo ($weiboId); ?>" data-type="weibo-list" data-weibo-id="<?php echo ($weiboId); ?>">
            <i class="os-icon-paper-plane"></i> </a>
    </p>

    <div id="emot_content" class="emot_content" style="position: absolute;    right: 425px;
    top: 45px;"></div>
    <!--评论列表-->
</div>
<div id="show_comment_<?php echo ($weiboId); ?>" class="weibo_comment_list" data-comment-count="<?php echo ($weiboCommentTotalCount); ?>" style="border-bottom: none;">
    <?php if(is_array($comments)): $i = 0; $__LIST__ = $comments;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$comment): $mod = ($i % 2 );++$i;?><div <?php if($i>5){ ?> style="display: none"  <?php } ?> >
            <!--comment_detail start-->
<div id="comment_<?php echo ($comment["id"]); ?>" data-role="comment_content_hover" class="row weibo_comment" data-weibo-id="<?php echo ($comment["weibo_id"]); ?>"
     data-comment-id="<?php echo ($comment["id"]); ?>">
    <div class="clearfix">
        <div class="col-xs-1" style="width: 8%">
            <div class="" style="overflow: hidden;  padding-top: 5px;">
                <a href="<?php echo ($comment["user"]["space_url"]); ?>" ucard="<?php echo ($comment["user"]["uid"]); ?>">
                    <img src="<?php echo ($comment["user"]["avatar64"]); ?>" class="avatar-img"/>
                </a>
            </div>
        </div>
        <div class="col-xs-11  comment-content" style="width: 92%;padding-left: 5px;padding-right: 0;margin-top: 10px;">
            <a href="<?php echo ($comment["user"]["space_url"]); ?>" ucard="<?php echo ($comment["user"]["uid"]); ?>">[nickname:<?php echo ($comment["user"]["uid"]); ?>]</a>：
            <span class="weibo-comment text-muted"><?php echo ($comment["content"]); ?></span>
            <div style="margin-left: 20px;color: #C5C5C5;display: inline-block;">
                <a id="comment_support_show_<?php echo ($comment["id"]); ?>" title="喜欢" data-role="support_btn" table="<?php echo ($table); ?>" row="<?php echo ($comment["id"]); ?>" uid="<?php echo ($comment["uid"]); ?>" jump="weibo/index/weibodetail" weibo_id="<?php echo ($weiboId); ?>" <?php if($comment['count'] > 0): ?>class="text-muted show-always"<?php else: ?>class="text-muted" style="display:none;"<?php endif; ?>>
                    <?php if($comment["supported"] > 0): ?><i id="support_<?php echo ($appname); ?>_<?php echo ($table); ?>_<?php echo ($comment["id"]); ?>_icon" class="iconfont icon-dianzan-already"></i>
                        <?php else: ?>
                        <i id="support_<?php echo ($appname); ?>_<?php echo ($table); ?>_<?php echo ($comment["id"]); ?>_icon" class="iconfont icon-dianzan"></i><?php endif; ?>
                    <span id="support_<?php echo ($appname); ?>_<?php echo ($table); ?>_<?php echo ($comment["id"]); ?>_pos"><span id="support_<?php echo ($appname); ?>_<?php echo ($table); ?>_<?php echo ($comment["id"]); ?>" class="text-muted"><?php echo ($comment["count"]); ?></span> </span>
                </a>
                <span id="comment_time_show_<?php echo ($comment["id"]); ?>" style="display: none;">●&nbsp;[time:<?php echo ($comment["create_time"]); ?>]</span>
            </div>
            <div id="comment_action_show_<?php echo ($comment["id"]); ?>" class="pull-right operate-buttons text-muted" style="display: none;">
                <a href="javascript:" data-role="weibo_reply" data-user-nickname="<?php echo ($comment["user"]["real_nickname"]); ?>"><?php echo L('_REPLY_');?></a>&nbsp;|
                <a title="喜欢" data-role="support_btn" table="<?php echo ($table); ?>" row="<?php echo ($comment["id"]); ?>" uid="<?php echo ($comment["uid"]); ?>" jump="weibo/index/weibodetail" weibo_id="<?php echo ($weiboId); ?>">赞</a>
            </div>
        </div>
    </div>
</div>
<!--comment_detail end-->
</div><?php endforeach; endif; else: echo "" ;endif; ?>
<?php $pageCount = ceil($weiboCommentTotalCount / 10); ?>
<div class="pager" style="display: none!important;margin: 11px 20px 0;">
    <?php echo getPageHtml('weibo_page',$pageCount,array('weibo_id'=>$weiboId,'position'=>'weibo-list'),$page);?>
</div>
</div>
<?php if(count($comments)>5){ ?>
<div style="width: 100%;height: 24px;text-align: center;line-height: 16px;">
    <a id="show_all_comment_<?php echo ($weiboId); ?>" href="javascript:" onclick="show_comment('<?php echo ($weiboId); ?>');" style="width: 100%;display: block;"><i class="iconfont icon-xiangxia"></i></a>
</div>
<?php } ?>


<script>
    $(function () {
        var weiboid = '<?php echo ($weiboId); ?>';
        $('#text_' + weiboid + '').keypress(function (e) {
            if (e.ctrlKey && e.which == 13 || e.which == 10) {
                $('#btn_' + weiboid + '').click();
            }
        });
    });
</script>