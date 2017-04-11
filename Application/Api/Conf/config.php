<?php

/**接口开发的一些规范（随时增加）：
 * 1.接口返回数据统一使用json格式；
 * 2.接口正常运行获得的返回数据code码统一使用200（不管查询数据结果是true还是false），接口运行报错code码统一为400，并返回错误原因（暂时没有做找出原因的处理）
 * 3.查询数据尽量使用缓存机制并考虑安全性
 * 4.为了兼容，废弃使用的函数或者方法使用deprecated标明，不要轻易删除，还需注明放弃使用的版本。
 */

return array(

    'URL_ROUTER_ON' => true,   // 是否开启URL路由  //todo
    'URL_ROUTE_RULES' => array( //定义路由规则

        /*账户相关*/
        array('authorization', 'Account/login', '', array('request_args' => array('method' => 'POST'))),  //登录
        array('logout', 'Account/logout', '', array('request_args' => array('method' => 'POST'))),  //注销
        array('account', 'Account/register', '', array('request_args' => array('method' => 'POST'))),  //注册
        array('verify', 'Account/sendVerify', '', array('request_args' => array('method' => 'POST'))),  //向邮箱和手机号码发送验证码
        array('account', 'Account/changeAccount', '', array('request_args' => array('method' => 'PUT'))),  //更改账户或密码
        array('password', 'Account/handlePassword', '', array('request_args' => array('method' => 'PUT'))),  //更改密码
        array('check_code', 'Account/inCode', '', array('request_args' => array('method' => 'GET'))),  //检测邀请码
        /*账户相关* 旧版1.6.4以下使用*/
        array('role', 'Account/getRole', '', array('request_args' => array('method' => 'GET'))),  //获取角色列表
        array('reg_verify', 'Account/sendVerify', '', array('request_args' => array('method' => 'POST'))),  //向邮箱和手机号码发送验证码
        array('reset_password', 'Account/doReset', '', array('request_args' => array('method' => 'POST'))),  //重置密码/没使用过/
        array('find_password', 'Account/findPassword', '', array('request_args' => array('method' => 'POST'))),  //找回密码/没使用过/
        /*账户相关end*/

        /*用户相关*/
        array('user/:id\d', 'User/info', '', array('request_args' => array('method' => 'GET'))),  //获取用户信息
        array('user_data', 'User/userData', '', array('request_args' => array('method' => 'GET'))),  //获取用户信息(直接从数据库取)
        array('score_type', 'User/scoreType', '', array('request_args' => array('method' => 'GET'))),  //获取用户信息(直接从数据库取)
        array('user_list', 'User/UsersInfo', '', array('request_args' => array('method' => 'GET'))),  //获取列表用户信息
        array('avatar', 'User/uploadAvatar', '', array('request_args' => array('method' => 'POST'))),  //上传用户头像
        array('follow', 'User/doFollow', '', array('request_args' => array('method' => 'POST'))),  //关注
        array('follow', 'User/endFollow', '', array('request_args' => array('method' => 'DELETE'))),  //取消关注
        array('friends/:id\d', 'User/FollowFansList', '', array('request_args' => array('method' => 'GET'))),  //好友列表&关注列表&粉丝列表
        array('user', 'User/setProfile', '', array('request_args' => array('method' => 'PUT'))),  //修改用户基本信息
        array('field', 'User/setField', '', array('request_args' => array('method' => 'PUT'))),  //修改用户拓展信息
        array('rank', 'User/getRankInfo', '', array('request_args' => array('method' => 'GET'))),  //排行榜信息
        array('rank_member', 'User/getRankMember', '', array('request_args' => array('method' => 'GET'))),  //排行榜成员
        /*用户相关end*/

        /*配置相关*/
        array('mod_config', 'Config/getModConfig', '', array('request_args' => array('method' => 'GET'))),  //获取配置项
        array('nav_list', 'Config/getNavList', '', array('request_args' => array('method' => 'GET'))),  //获取导航列表
        /*旧接口*/
        array('config', 'Config/config', '', array('request_args' => array('method' => 'GET'))),  //获取配置项
        array('app_config', 'Config/appConfig', '', array('request_args' => array('method' => 'GET'))),  //获取配置项
        array('mod', 'Config/modList', '', array('request_args' => array('method' => 'GET'))),  //获取应用配置项
        array('versions', 'Config/versions', '', array('request_args' => array('method' => 'GET'))),  //获取版本号信息
        /*配置相关end*/



        array('checking', 'Check/checking', '', array('request_args' => array('method' => 'GET'))),  //验证信息
        array('picture', 'Attachment/uploadPicture', '', array('request_args' => array('method' => 'POST'))),  // 上传图片
        array('attachment', 'Attachment/uploadAttach', '', array('request_args' => array('method' => 'POST'))),  // 上传附件
        array('picture/:id\d', 'Attachment/getPicture', '', array('request_args' => array('method' => 'GET'))),  //获取图片


        /*微博相关*/
        array('weibo/:id\d', 'Weibo/getWeibo', '', array('request_args' => array('method' => 'GET'))),  //获取微博     ps:写路由的时候后面加参数的需要放在不带参数的前面
        array('weibo', 'Weibo/getWeiboList', '', array('request_args' => array('method' => 'GET'))),  //获取微博列表
        array('weibo_top/:id\d', 'Weibo/setTop', '', array('request_args' => array('method' => 'PUT'))), //设置置顶
        array('weibo', 'Weibo/sendWeibo', '', array('request_args' => array('method' => 'POST'))),  //发表微博
        array('weibo/:id\d', 'Weibo/deleteWeibo', '', array('request_args' => array('method' => 'DELETE'))), //删除微博
        array('repost', 'Weibo/sendRepost', '', array('request_args' => array('method' => 'POST'))), //转发微博
        array('weibo_comment', 'Weibo/sendComment', '', array('request_args' => array('method' => 'POST'))), //发表微博回复
        array('weibo_comment/:id\d', 'Weibo/deleteComment', '', array('request_args' => array('method' => 'DELETE'))), //删除微博回复
        array('weibo_comment/:id\d', 'Weibo/getComment', '', array('request_args' => array('method' => 'GET'))), //微博回复列表
        array('weibo_topic', 'Weibo/getTopicList', '', array('request_args' => array('method' => 'GET'))), //微博话题
        array('weibo_topic_info', 'Weibo/getTopic', '', array('request_args' => array('method' => 'GET'))), //微博话题详情
        array('weibo_crowd_type', 'Weibo/getCrowdType', '', array('request_args' => array('method' => 'GET'))), //获取圈子分类
        array('weibo_crowd_info', 'Weibo/getCrowd', '', array('request_args' => array('method' => 'GET'))), //获取圈信息
        array('weibo_crowd', 'Weibo/getCrowdList', '', array('request_args' => array('method' => 'GET'))), //获取圈列表
        array('weibo_crowd', 'Weibo/handleCrowd', '', array('request_args' => array('method' => 'POST'))), //加入圈子
        array('weibo_crowd', 'Weibo/modifyCrowd', '', array('request_args' => array('method' => 'PUT'))), //修改圈子信息
        array('weibo_crowd_member', 'Weibo/getCrowdMember', '', array('request_args' => array('method' => 'GET'))), //获取圈子成员列表
        array('weibo_crowd_member', 'Weibo/manageCrowdMember', '', array('request_args' => array('method' => 'POST'))), //圈子成员管理
        array('weibo_crowd_check', 'Weibo/checkCrowdMember', '', array('request_args' => array('method' => 'POST'))), //圈子成员审核
        array('weibo_crowd_score', 'Weibo/setCrowdContribution', '', array('request_args' => array('method' => 'PUT'))), //圈子贡献设置
        array('weibo_crowd_admin', 'Weibo/setCrowdAdmin', '', array('request_args' => array('method' => 'PUT'))), //设置圈子管理员
        array('weibo_crowd_auth', 'Weibo/setCrowdAuth', '', array('request_args' => array('method' => 'PUT'))), //圈子所有权转移
        array('weibo_crowd_invite', 'Weibo/checkInvite', '', array('request_args' => array('method' => 'POST'))), //接受/拒绝加圈子邀请
        array('weibo_crowd_create', 'Weibo/createCrowd', '', array('request_args' => array('method' => 'POST'))), //创建圈子
        array('weibo_crowd_del', 'Weibo/delCrowd', '', array('request_args' => array('method' => 'DELETE'))), //解散圈子
        /*微博相关配置*/
        array('weibo_config', 'Weibo/weiboConfig', '', array('request_args' => array('method' => 'GET'))), //获取配置项参数
        /*微博相关配置*/
        array('weibo_topic_list/:id\d', 'Weibo/getTopicDetail', '', array('request_args' => array('method' => 'GET'))), //话题微博列表    1.6.7已弃用
        /*微博相关end*/


        /*专辑相关*/
        array('issue_type', 'Issue/getIssueModules', '', array('request_args' => array('method' => 'GET'))),  //获取专辑分类
        array('issue_list/:id\d', 'Issue/getIssueList', '', array('request_args' => array('method' => 'GET'))),  //获取专辑列表
        array('issue/:id\d', 'Issue/getIssueDetail', '', array('request_args' => array('method' => 'GET'))), //获取专辑详情
        array('issue_comment/:id\d', 'Issue/getIssueComments', '', array('request_args' => array('method' => 'GET'))), //获取专辑回复
        array('issue_comment/:id\d', 'Issue/sendIssueComment', '', array('request_args' => array('method' => 'POST'))), //回复专辑
        array('issue_comment/:id\d', 'Issue/delIssue', '', array('request_args' => array('method' => 'PUT'))), //删除专辑回复
        array('issue/:id\d', 'Issue/sendIssue', '', array('request_args' => array('method' => 'PUT'))), //编辑专辑
        array('issue', 'Issue/sendIssue', '', array('request_args' => array('method' => 'POST'))), //发表专辑


        /*资讯相关*/
        array('news_category', 'News/getCategory', '', array('request_args' => array('method' => 'GET'))),  //获取资讯二级分类
        array('my_news', 'News/getMyNewsAll', '', array('request_args' => array('method' => 'GET'))), //获取我的资讯
        array('news_recommend', 'News/getRecommendNews', '', array('request_args' => array('method' => 'GET'))), //获取推荐资讯
        array('news_hot', 'News/getHotNewsAll', '', array('request_args' => array('method' => 'GET'))), //获取热门资讯
        array('news_detail/:id\d', 'News/getNewsDetail', '', array('request_args' => array('method' => 'GET'))), //资讯详情
        array('news_comment/:id\d', 'News/getNewsComments', '', array('request_args' => array('method' => 'GET'))), //资讯回复列表
        array('news_comment/:id\d', 'News/sendNewsComment', '', array('request_args' => array('method' => 'POST'))), //回复资讯
        array('news_comment/:id\d', 'News/delNewsComment', '', array('request_args' => array('method' => 'DELETE'))), //删除资讯回复
        array('news/:id\d', 'News/sendNews', '', array('request_args' => array('method' => 'PUT'))), //编辑资讯
        array('news', 'News/getNewsAll', '', array('request_args' => array('method' => 'GET'))), //获取资讯列表
        array('news', 'News/sendNews', '', array('request_args' => array('method' => 'POST'))), //发送资讯


        /*论坛相关*/
        array('forum_type', 'Forum/forumType', '', array('request_args' => array('method' => 'GET'))),  //获取论坛类型
        array('forum', 'Forum/forum', '', array('request_args' => array('method' => 'GET'))),  //获取论坛
        array('forum_follow', 'Forum/getFollowPost', '', array('request_args' => array('method' => 'PUT'))),  //获取关注帖子
        array('forum_top', 'Forum/getTopPost', '', array('request_args' => array('method' => 'GET'))), //获取置顶帖子
        array('forum_recommend', 'Forum/getRecommendPost', '', array('request_args' => array('method' => 'GET'))), //获取推荐帖子
        array('forum_post_detail/:id\d', 'Forum/getPost', '', array('request_args' => array('method' => 'GET'))), //帖子详情
        array('forum_post/:id\d', 'Forum/sendPost', '', array('request_args' => array('method' => 'PUT'))), //编辑帖子
        array('forum_post', 'Forum/sendPost', '', array('request_args' => array('method' => 'POST'))), //发布帖子
        array('forum_post', 'Forum/getPosts', '', array('request_args' => array('method' => 'GET'))), //获取帖子列表
        array('forum_lzl_comment/:id\d', 'Forum/getCommentList', '', array('request_args' => array('method' => 'GET'))), //帖子lzl评论列表
        array('forum_comment/:id\d', 'Forum/getPostComments', '', array('request_args' => array('method' => 'GET'))), //帖子评论列表
        array('forum_comment', 'Forum/sendPostComment', '', array('request_args' => array('method' => 'POST'))), //帖子评论
        array('forum_lzl', 'Forum/sendComment', '', array('request_args' => array('method' => 'POST'))), //回复评论
        array('forum_coll', 'Forum/collectionPost', '', array('request_args' => array('method' => 'POST'))), //帖子收藏

        /*消息相关*/
        array('message_type', 'Message/getMessageType', '', array('request_args' => array('method' => 'GET'))), //获取消息分类
        array('message', 'Message/getMessageList', '', array('request_args' => array('method' => 'GET'))), //获取消息列表
        array('message', 'Message/setAllReaded', '', array('request_args' => array('method' => 'PUT'))), //消息设置为已读

        /*问答相关*/
        array('question_type', 'Question/getQuestionType', '', array('request_args' => array('method' => 'GET'))), //获取问答的分类
        array('question_list', 'Question/getQuestionList', '', array('request_args' => array('method' => 'GET'))), //获取某一分类或者类型下的问题列表
        array('question_user', 'Question/getUserQuestion', '', array('request_args' => array('method' => 'GET'))), //获取某一个用户的问答列表
        array('question_detail', 'Question/getQuestionDetail', '', array('request_args' => array('method' => 'GET'))), //获取问题详情
        array('answer_list', 'Question/getAnswerList', '', array('request_args' => array('method' => 'GET'))), //答案列表
        array('answer_detail', 'Question/getAnswerDetail', '', array('request_args' => array('method' => 'GET'))), //答案详情
        array('question', 'Question/sendQuestion', '', array('request_args' => array('method' => 'POST'))), //发送问题
        array('question', 'Question/delQuestion', '', array('request_args' => array('method' => 'DELETE'))), //删除问题
        array('answer', 'Question/editAnswer', '', array('request_args' => array('method' => 'POST'))), //发送答案
        array('answer', 'Question/delAnswer', '', array('request_args' => array('method' => 'DELETE'))), //删除答案
        array('evaluate', 'Question/evaluate', '', array('request_args' => array('method' => 'POST'))), //评价答案
        array('answer_best', 'Question/setBestAnswer', '', array('request_args' => array('method' => 'POST'))), //设置最佳答案
        array('question_search', 'Question/searchQuestion', '', array('request_args' => array('method' => 'GET'))), //设置最佳答案

        /*积分商城相关*/
        array('shop_cate', 'Shop/getShopCategory', '', array('request_args' => array('method' => 'GET'))), //商品分类
        array('shop_index', 'Shop/getIndex', '', array('request_args' => array('method' => 'GET'))), //商品最新和最热
        array('shop_goods_list', 'Shop/getGoodsList', '', array('request_args' => array('method' => 'GET'))), //商品列表
        array('shop_consignee', 'Shop/consigneeInfo', '', array('request_args' => array('method' => 'POST'))), //商品兑换
        array('shop_orders', 'Shop/myOrders', '', array('request_args' => array('method' => 'GET'))), //我的订单
        array('shop_goods_detail', 'Shop/getGoodsDetail', '', array('request_args' => array('method' => 'GET'))), //商品详情
        array('shop_goods_comment', 'Shop/getGoodsComment', '', array('request_args' => array('method' => 'GET'))), //商品点评列表
        array('shop_del_comment', 'Shop/delGoodsComment', '', array('request_args' => array('method' => 'DELETE'))), //删除商品点评
        array('shop_send_comment', 'Shop/sendGoodsComment', '', array('request_args' => array('method' => 'POST'))), //发送商品点评

        /*微店相关*/
        array('store_cate', 'Store/getCategory', '', array('request_args' => array('method' => 'GET'))), //商品分类
        array('store_goods/:id\d', 'Store/getGoods', '', array('request_args' => array('method' => 'GET'))), //微店商品（单个）
        array('store_goods', 'Store/getGoods', '', array('request_args' => array('method' => 'GET'))), //微店商品（列表）
        array('store_shop/:id\d', 'Store/getShopList', '', array('request_args' => array('method' => 'GET'))), //微店商品（单个）
        array('store_shop', 'Store/getShopList', '', array('request_args' => array('method' => 'GET'))), //微店商店（列表）
        array('store_order_detail', 'Store/getOrder', '', array('request_args' => array('method' => 'GET'))), //我的订单详情
        array('store_order', 'Store/endOrder', '', array('request_args' => array('method' => 'PUT'))), //取消订单
        array('store_order', 'Store/getOrder', '', array('request_args' => array('method' => 'GET'))), //我的订单
        array('store_confirm', 'Store/doneOrder', '', array('request_args' => array('method' => 'POST'))), //确认订单
        array('store_response', 'Store/getResponse', '', array('request_args' => array('method' => 'GET'))), //商品点评
        array('store_response', 'Store/sendResponse', '', array('request_args' => array('method' => 'PUT'))), //发送商品点评
        array('store_car', 'Store/getMyCar', '', array('request_args' => array('method' => 'GET'))), //我的购物车
        array('store_fav', 'Store/getMyFav', '', array('request_args' => array('method' => 'GET'))), //我的收藏
        array('store_adv', 'Store/getAdv', '', array('request_args' => array('method' => 'GET'))), //广告
        array('store_pay/:id\d', 'Store/payOut', '', array('request_args' => array('method' => 'POST'))), //付钱
        array('store_pay', 'Store/pay', '', array('request_args' => array('method' => 'POST'))), //下单
        array('store_info', 'Store/center', '', array('request_args' => array('method' => 'GET'))), //获取个人中心信息
        array('store_shop', 'Store/createShop', '', array('request_args' => array('method' => 'POST'))), //创建商店
        array('store_shop', 'Store/createShop', '', array('request_args' => array('method' => 'PUT'))), //编辑商店
        array('store_fav', 'Store/doFav', '', array('request_args' => array('method' => 'POST'))), //收藏
        array('store_end', 'Store/endFav', '', array('request_args' => array('method' => 'DELETE'))), //取消收藏
        array('store_car', 'Store/addCar', '', array('request_args' => array('method' => 'POST'))), //加入购物车
        array('store_car', 'Store/removeCar', '', array('request_args' => array('method' => 'DELETE'))), //移除购物车
        array('store_search', 'Store/search', '', array('request_args' => array('method' => 'GET'))), //搜索商品
        array('store_trans_list', 'Store/getTransList', '', array('request_args' => array('method' => 'GET'))), //获取用户送货地址信息列表
        array('store_cfg_trans', 'Store/setTransInfo', '', array('request_args' => array('method' => 'POST'))), //设置用户送货地址信息
        array('store_del_trans', 'Store/delTrans', '', array('request_args' => array('method' => 'DELETE'))), //删除用户送货地址信息

        /*Ping++部分*/
        array('pingxx_pay', 'Pingxx/pay', '', array('request_args' => array('method' => 'POST'))),  //付款
        array('pingxx_check_recharge', 'Pingxx/checkRecharge', '', array('request_args' => array('method' => 'GET'))),  //验证pingxx充值是否开启，如果开启返回相关配置
        array('pingxx_ctre_order', 'Pingxx/createRechOrder', '', array('request_args' => array('method' => 'POST'))),  //生成充值订单



        /*IM部分*/
        array('IMToken', 'Public/getWuKongToken', '', array('request_args' => array('method' => 'GET'))),  //悟空token
        array('RYIMToken', 'Public/getRongyunToken', '', array('request_args' => array('method' => 'GET'))),  //融云token
        /*透传推送部分*/
        array('pushMessage', 'Public/pushMessage', '', array('request_args' => array('method' => 'POST'))),  //推送消息

        /*找人相关*/
        array('people_list', 'People/peopleList', '', array('request_args' => array('method' => 'GET'))),  //获取人群角色列表
        array('people_type', 'People/peopleType', '', array('request_args' => array('method' => 'GET'))),  //获取人群角色列表
        array('people_near', 'People/nearbyPeople', '', array('request_args' => array('method' => 'GET'))),  //附近的人
        array('people_near', 'People/clearNear', '', array('request_args' => array('method' => 'PUT'))),  //清除附近的人信息

        /*活动相关*/
        array('event_type', 'Event/getEventModules', '', array('request_args' => array('method' => 'GET'))),  //获取活动分类
        array('event_list', 'Event/getEventsAll', '', array('request_args' => array('method' => 'GET'))),  //获取某个分类下的活动
        array('event_recommend', 'Event/getRecommend', '', array('request_args' => array('method' => 'GET'))),  //获取推荐活动
        array('event_my', 'Event/getWeEvents', '', array('request_args' => array('method' => 'GET'))),  //获取我的活动
        array('event_member', 'Event/getEventMember', '', array('request_args' => array('method' => 'GET'))),  //获取参与活动人员
        array('event_audit', 'Event/userAudit', '', array('request_args' => array('method' => 'POST'))),  //获取参与活动人员
        array('event_kick', 'Event/kickOut', '', array('request_args' => array('method' => 'POST'))),  //移除参加人员
        array('event_detail/:id\d', 'Event/eventDetail', '', array('request_args' => array('method' => 'GET'))),  //获取单个活动详情
        array('event_join', 'Event/joinEvents', '', array('request_args' => array('method' => 'POST'))),  //参加活动
        array('event_quit', 'Event/endJoin', '', array('request_args' => array('method' => 'DELETE'))),  //退出活动
        array('event_comment', 'Event/getEventComments', '', array('request_args' => array('method' => 'GET'))),  //获取某个活动的回复列表
        array('event_comment', 'Event/sendEventComment', '', array('request_args' => array('method' => 'POST'))),  //获取某个活动的回复列表
        array('event_comment', 'Event/delCommont', '', array('request_args' => array('method' => 'DELETE'))),  //删除活动回复
        array('event_end/:id\d', 'Event/endEvents', '', array('request_args' => array('method' => 'PUT'))),  //关闭活动
        array('event_del/:id\d', 'Event/deleteEvents', '', array('request_args' => array('method' => 'PUT'))),  //删除活动
        array('event', 'Event/addEvents', '', array('request_args' => array('method' => 'POST'))),  //添加活动
        array('event/:id\d', 'Event/addEvents', '', array('request_args' => array('method' => 'PUT'))),  //编辑活动

        /*公共部分*/
        array('file', 'Public/uploadFile', '', array('request_args' => array('method' => 'POST'))),  //上传微信小程序的图片
        array('auth', 'Public/auth', '', array('request_args' => array('method' => 'POST'))),  //获得微信unionid
        array('support', 'Public/support', '', array('request_args' => array('method' => 'POST'))),  //点赞
        array('support_list', 'Public/supportList', '', array('request_args' => array('method' => 'GET'))), //获取点赞人数列表
        array('my_support', 'Public/mySupport', '', array('request_args' => array('method' => 'GET'))), //我的点赞
        array('check', 'Public/checkin', '', array('request_args' => array('method' => 'POST'))), //签到
        array('check', 'Public/getCheckInfo', '', array('request_args' => array('method' => 'GET'))), //获取签到信息
        array('check_rank', 'Public/getCheckPeople', '', array('request_args' => array('method' => 'GET'))), //获取签到排行榜
        array('sync', 'Oauth/getSync', '', array('request_args' => array('method' => 'GET'))), //获取账号绑定信息
        array('sync', 'Oauth/setSync', '', array('request_args' => array('method' => 'PUT'))), //设置账号绑定
        array('oauth', 'Oauth/oauth', '', array('request_args' => array('method' => 'POST'))), //获取Oauth授权
        array('oauth_sync', 'Oauth/sync', '', array('request_args' => array('method' => 'POST'))), //同步授权绑定
        array('report', 'Public/Report', '', array('request_args' => array('method' => 'POST'))), //举报
        array('report_type', 'Public/AddonsReportConfig', '', array('request_args' => array('method' => 'GET'))), //获取举报类型
        array('music', 'Public/getMusic', '', array('request_args' => array('method' => 'GET'))), //获取虾米音乐真实地址

        /*群组部分*/
        array('group_type', 'Group/getGroupType', '', array('request_args' => array('method' => 'GET'))),  //获取群组分类
        array('group_all', 'Group/getGroupAll', '', array('request_args' => array('method' => 'GET'))), //返回所有群组信息
        array('group_detail', 'Group/getGroupDetail', '', array('request_args' => array('method' => 'GET'))), //返回群组详情
        array('group_post_top', 'Group/getTopPost', '', array('request_args' => array('method' => 'GET'))), //返回置顶帖子信息
        array('group_member', 'Group/getGroupMember', '', array('request_args' => array('method' => 'GET'))), //返回群组成员信息
        array('group_my', 'Group/getWeGroupAll', '', array('request_args' => array('method' => 'GET'))), //返回我的群组信息
        array('group_post_all', 'Group/getPostAll', '', array('request_args' => array('method' => 'GET'))), //返回群组下的帖子信息
        array('group/:id\d', 'Group/addGroup', '', array('request_args' => array('method' => 'PUT'))), //编辑群组
        array('group', 'Group/addGroup', '', array('request_args' => array('method' => 'POST'))), //添加群组
        array('group_post/:id\d', 'Group/sendPost', '', array('request_args' => array('method' => 'PUT'))), //编辑帖子
        array('group_post', 'Group/sendPost', '', array('request_args' => array('method' => 'POST'))), //编辑帖子
        array('group_post_detail', 'Group/postDetail', '', array('request_args' => array('method' => 'GET'))), //帖子详情
        array('group', 'Group/endGroup', '', array('request_args' => array('method' => 'PUT'))), //解散群组
        array('group_post_PRM', 'Group/getPostPRM', '', array('request_args' => array('method' => 'GET'))), //得到帖子的点赞回复数
        array('group_notice', 'Group/getNotice', '', array('request_args' => array('method' => 'GET'))), //公告信息
        array('group_user', 'Group/joinGroup', '', array('request_args' => array('method' => 'POST'))), //加入群组
        array('group_user', 'Group/quitGroup', '', array('request_args' => array('method' => 'DELETE'))), //退出群组
        array('group_friend', 'Group/GroupInvite', '', array('request_args' => array('method' => 'POST'))), //邀请好友加入
        array('group_member/:id\d', 'Group/rejectGroupPeople', '', array('request_args' => array('method' => 'DELETE'))), //剔除组员
        array('group_member/:id\d', 'Group/addGroupPeople', '', array('request_args' => array('method' => 'POST'))), //添加组员（审核组员）接口
        array('group_post_type', 'Group/addPostCategory', '', array('request_args' => array('method' => 'POST'))), //新建帖子分类操作
        array('group_post_type', 'Group/delPostCategory', '', array('request_args' => array('method' => 'PUT'))), //删除帖子分类
        array('group_post_type', 'Group/PostCategory', '', array('request_args' => array('method' => 'GET'))), //展示帖子分类
        array('group_post_reply', 'Group/getPostReply', '', array('request_args' => array('method' => 'GET'))), //展示帖子回复信息
        array('group_post_reply', 'Group/doReplyPost', '', array('request_args' => array('method' => 'POST'))), //回复帖子
        array('group_post_lzl', 'Group/doPostLzl', '', array('request_args' => array('method' => 'POST'))), //回复帖子lzl
        array('group_post_lzl', 'Group/PostLzl', '', array('request_args' => array('method' => 'GET'))), //展示帖子楼中楼
        array('group_post_hot', 'Group/getHotPost', '', array('request_args' => array('method' => 'GET'))), //展示热门帖子
        array('group_post_bookmark', 'Group/postBookmark', '', array('request_args' => array('method' => 'POST'))), //帖子收藏
        array('group_post_bookmark', 'Group/RejectBookmark', '', array('request_args' => array('method' => 'DELETE'))), //取消帖子收藏
        array('group_post_bookmark', 'Group/getBookmark', '', array('request_args' => array('method' => 'GET'))), //取消帖子收藏

       /*分类相关*/
        array('cat_type', 'Cat/getCatType', '', array('request_args' => array('method' => 'GET'))), //分类
        array('cat_send', 'Cat/addCat', '', array('request_args' => array('method' => 'POST'))), //发布分类信息
        array('cat_list', 'Cat/getCatList', '', array('request_args' => array('method' => 'GET'))), //分类信息列表
        array('cat_detail', 'Cat/getCatDetail', '', array('request_args' => array('method' => 'GET'))), //分类信息详情
        array('cat_score', 'Cat/doScore', '', array('request_args' => array('method' => 'POST'))), //分类信息打分
        array('cat_fav', 'Cat/doFav', '', array('request_args' => array('method' => 'POST'))), //信息收藏
        array('cat_fav', 'Cat/disFav', '', array('request_args' => array('method' => 'DELETE'))), //删除信息
        array('cat_we', 'Cat/getWeSend', '', array('request_args' => array('method' => 'GET'))), //我的分类信息
        array('cat_recom', 'Cat/getRecomList', '', array('request_args' => array('method' => 'GET'))), //获取推荐信息
        array('cat_del', 'Cat/delInfo', '', array('request_args' => array('method' => 'DELETE'))), //删除
        array('cat_we_fav', 'Cat/getWeFav', '', array('request_args' => array('method' => 'GET'))), //我的分类信息收藏
        array('cat_reply/:id\d', 'Cat/getReply', '', array('request_args' => array('method' => 'GET'))), //信息评论
        array('cat_reply/:id\d', 'Cat/sendCatReply', '', array('request_args' => array('method' => 'POST'))), //发布评论
        array('cat_reply/:id\d', 'Cat/delCatReply', '', array('request_args' => array('method' => 'DELETE'))), //删除评论
        array('cat_send_info/:id\d', 'Cat/sendInfo', '', array('request_args' => array('method' => 'GET'))), //发送消息
        array('cat_mail', 'Cat/getMail', '', array('request_args' => array('method' => 'GET'))), //获取我的信箱
        array('cat_center', 'Cat/getUcData', '', array('request_args' => array('method' => 'GET'))), //获取用户分类信息数据
    ),

    'NEED_VERIFY' => true,//此处控制默认是否需要审核，该配置项为了便于部署起见，暂时通过在此修改来设定。

);

