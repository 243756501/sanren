<?php if (!defined('THINK_PATH')) exit();?><div data-position="one-weibo" style="max-width: 680px;<?php if($can_hide): ?>[top_hide]<?php endif; ?>" data-role="id_weibo" id="weibo_<?php echo ($weibo["id"]); ?>" <?php if($can_hide): ?>class="top_can_hide"<?php else: ?>class=""<?php endif; ?>>
    <div class="all-wrap">
        <?php if($weibo['is_top'] == 1): ?><div class="ribbion-green"></div>
            <?php elseif($weibo['is_hot'] == 1): ?>
            <div class="hot-comment-weibo"></div>
            <?php elseif($weibo['is_first'] == 1): ?>
            <div class="new-user-first-weibo"></div><?php endif; ?>
        <div class="weibo-content">
            <div class="content-head">
                <div class="avat-box pull-left">
                    <a href="<?php echo ($weibo["user"]["space_url"]); ?>" ucard="<?php echo ($weibo["user"]["uid"]); ?>">
                        <?php echo ($weibo["user"]["avatar_html128"]); ?>
                    </a>
                    <div class="show-follow pull-right">
                        <div class="follow-btn" style="display: none;">
                            [follow:<?php echo ($weibo['uid']); ?>]
                        </div>
                    </div>
                </div>
                <div class="op-box pull-right">
                    <div class="op-tb op-top">
                        <a ucard="<?php echo ($weibo["user"]["uid"]); ?>" href="<?php echo ($weibo["user"]["space_url"]); ?>" class="user_name">
                            [nickname:<?php echo ($weibo['uid']); ?>]
                        </a>
                        <?php if(modC('SHOW_TITLE',1)): ?><small class="font_grey"><?php echo ($weibo["user"]["title"]); ?></small><?php endif; ?>
                        <?php echo W('Common/UserRank/render',array($weibo['uid']));?>
                        <!--隐藏操作列表-->
                        <?php if($can_hide == 1): ?><div class="pull-right show-operate-wrap">
                                <a class="pull-right iconfont icon-cuo" data-role="hide_top_weibo_list" data-weibo-id="<?php echo ($weibo['id']); ?>" title="隐藏" style="color: #AEAEAE;"></a>
                            </div><?php endif; ?>
                    </div>
                    <div class="op-tb op-bottom">
                        <a data-hover="查看详情" class="wb-time" href="<?php echo U('Weibo/Index/weiboDetail',array('id'=>$weibo['id']));?>">
                            [time:<?php echo ($weibo["create_time"]); ?>]
                        </a>
                    </div>
                </div>
            </div>
            <div class="content-info row">
                <?php echo ($weibo["fetchContent"]); ?>
                <div class="form-where">
                    <div class="where w-left">
                        <span><?php echo L('_FROM_');?> <?php if($weibo['from'] == ''): echo L('_PC_');?> <?php else: ?><strong><?php echo ($weibo["from"]); ?></strong><?php endif; ?></span>
                        <span><?php echo hook('giveReward',array('type'=>$MODULE_ALIAS.'/'.$MODULE_ALIAS,'url'=>"Weibo/Index/weiboDetail?id=$weibo[id]",'data'=>array('user-id'=>$weibo['user']['uid'])));?></span>
                    </div>
                    <div class="where w-right  bottom-operate" data-weibo-id="<?php echo ($weibo["id"]); ?>">
                        <?php $weiboCommentTotalCount = $weibo['comment_count']; ?>
                        <div class="col-xs-3 operate-color">
    <a title="喜欢" data-role="support-weibo" table="weibo" row="<?php echo ($weibo['id']); ?>" uid="<?php echo ($weibo['uid']); ?>" jump="weibo/index/weibodetail">
        <i class="weibo_like icon-heart-empty"></i>
        <span id="support_Weibo_weibo_<?php echo ($weibo['id']); ?>_pos"><span id="support_Weibo_weibo_<?php echo ($weibo['id']); ?>"><?php echo ((isset($support_count) && ($support_count !== ""))?($support_count):0); ?></span> </span>
    </a>
</div>
<div class=" col-xs-3 operate-color" data-role="weibo_comment_btn"  data-weibo-id="<?php echo ($weibo["id"]); ?>">
   <i class="os-icon-bubbles"></i> <?php echo ($weiboCommentTotalCount); ?>
</div>
<div class="col-xs-3 operate-color">
    <?php $sourceId =$weibo['data']['sourceId']?$weibo['data']['sourceId']:$weibo['id']; ?>
    <a title="<?php echo L('_REPOST_');?>"  data-role="send_repost"  href="<?php echo U('Weibo/Index/sendrepost',array('sourceId'=>$sourceId,'weiboId'=>$weibo['id']));?>"><i class="os-icon-share-alt"></i> <?php echo ($weibo["repost_count"]); ?></a>
</div>
<div class="share_button col-xs-3  operate-color" style="padding: 0px;position: relative;">
    <span class="cpointer weibo_share_btn_<?php echo ($weibo["id"]); ?>" data-weibo-id="<?php echo ($weibo["id"]); ?>">
        <a data-role="weibo_share_btn" class="share-btn" title="分享"><i class="os-icon-share"></i><!--<span class="share_count" title="累计分享0次" style="margin-left: 5px;">0</span>--></a>
    </span>
    <div class="share_block" data-url="<?php echo U('Weibo/Index/weibodetail',array('id'=>$weibo['id']),true,true);?>" data-text="<?php echo (text($weibo['content'])); ?>" data-dec="分享动态" style="display: none;">
        <div class="bdsharebuttonbox" data-tag="share_feedlist">
            <a class="bds_qzone" data-cmd="qzone" title="分享到QQ空间" data-id="<?php echo ($weibo["id"]); ?>">QQ空间</a>
            <a class="bds_tsina" data-cmd="tsina" title="分享到新浪动态" data-id="<?php echo ($weibo["id"]); ?>">新浪动态</a>
            <a class="bds_tqq" data-cmd="tqq" title="分享到腾讯动态" data-id="<?php echo ($weibo["id"]); ?>">腾讯动态</a>
            <a class="bds_weixin" data-cmd="weixin" title="分享到微信" data-id="<?php echo ($weibo["id"]); ?>">微信</a>
            <a class="bds_sqq" data-cmd="sqq" title="分享到QQ好友" data-id="<?php echo ($weibo["id"]); ?>">QQ好友</a>
            <!--<a class="bds_count" data-cmd="count" style="display: none;"></a>-->
        </div>
        <div style="position: relative;">
            <div class="tip"></div>
            <div class="tip-xs"></div>
        </div>
    </div>
    <script src="/shanren/Application/Weibo/Static/js/bdshare.js"></script>
</div>
                    </div>
                </div>
            </div>
        </div>

        </div>
        <div class="weibo-bottom weibo-comment-list row" <?php if(modC('SHOW_COMMENT',1)): ?>style="display: block;margin:0;" <?php else: ?> style="display: none;"<?php endif; ?> data-weibo-id="<?php echo ($weibo["id"]); ?>">
            <div class="top-triangle-border"></div>
            <div class="top-triangle-content"></div>
            <div class="bottom-top">
                <div class="pull-left left-like-list text-more text-color">
                    <a title="喜欢" data-role="support-weibo" class="text-color" table="weibo" row="<?php echo ($weibo['id']); ?>" uid="<?php echo ($weibo['uid']); ?>" jump="weibo/index/weibodetail">
                        <i class="weibo_like icon-heart-empty"></i>
                        <?php if(count($supportedUserList) == 0): ?><span class="support-text">点赞</span><?php endif; ?>
                    </a>
                    <span id="supporter_Weibo_weibo_<?php echo ($weibo['id']); ?>">
                        <?php if(count($supportedUserList) > 0): if(is_array($supportedUserList)): $i = 0; $__LIST__ = $supportedUserList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$one_support): $mod = ($i % 2 );++$i; if($key >= 1): ?>，<?php endif; ?>
                                <a ucard="<?php echo ($one_support['uid']); ?>" href="<?php echo ($one_support['space_url']); ?>" class="text-color">[nickname:<?php echo ($one_support['uid']); ?>]</a><?php endforeach; endif; else: echo "" ;endif; endif; ?>
                    </span>
                </div>
                <a class="pull-right right-say-button" data-role="show-comment-input" data-id="<?php echo ($weibo['id']); ?>"><i class="iconfont icon-you"></i>说一句</a>
            </div>
            <div class="comment-list-block">
                <?php if(modC('SHOW_COMMENT',1)): ?><div class=" weibo-comment-block">
                        <div class="weibo-comment-container">
                            <?php echo W('Weibo/Comment/someComment',array('weibo_id'=>$weibo['id'],'un_prase_comment'=>$un_prase_comment));?>
                        </div>
                    </div><?php endif; ?>
            </div>
        </div>
    </div>
</if>
<style>
    .suofang {MARGIN: auto;WIDTH: 200px;}
    .suofang img{MAX-WIDTH: 100%!important;HEIGHT: auto!important;width:expression(this.width > 300 ? "300px" :this.width)!important;}
</style>