-- -----------------------------
-- 表结构 `ocenter_question`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `ocenter_question` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `category` int(11) NOT NULL COMMENT '问题分类',
  `title` varchar(200) NOT NULL COMMENT '问题标题',
  `description` text NOT NULL COMMENT '问题描述',
  `answer_num` int(10) NOT NULL DEFAULT '0' COMMENT '回答数',
  `best_answer` int(11) NOT NULL COMMENT '最佳答案id',
  `good_question` int(10) NOT NULL DEFAULT '0' COMMENT '好问题（用于好问题排序：数值=支持-反对）',
  `status` tinyint(4) NOT NULL,
  `is_recommend` tinyint(2) NOT NULL DEFAULT '0' COMMENT '是否被推荐',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  `leixing` int(11) NOT NULL COMMENT '类型',
  `score_num` int(11) NOT NULL COMMENT '数额',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1298 DEFAULT CHARSET=utf8 COMMENT='问题表';


-- -----------------------------
-- 表结构 `ocenter_question_answer`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `ocenter_question_answer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `content` text NOT NULL COMMENT '回答内容',
  `support` int(10) NOT NULL DEFAULT '0' COMMENT '支持数',
  `oppose` int(10) NOT NULL DEFAULT '0' COMMENT '反对数',
  `status` tinyint(4) NOT NULL,
  `update_time` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8 COMMENT='问题回答表';


-- -----------------------------
-- 表结构 `ocenter_question_category`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `ocenter_question_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(25) NOT NULL,
  `pid` int(11) NOT NULL,
  `sort` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='问题分类表';


-- -----------------------------
-- 表结构 `ocenter_question_support`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `ocenter_question_support` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `tablename` varchar(25) NOT NULL COMMENT '表名：question；question_answer',
  `row` int(11) NOT NULL COMMENT '行号',
  `type` int(11) NOT NULL COMMENT '类型：0：反对，1：支持',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='问题支持反对表';

-- -----------------------------
-- 表内记录 `ocenter_question`
-- -----------------------------

-- -----------------------------
-- 表内记录 `ocenter_question_answer`
-- -----------------------------

-- -----------------------------
-- 表内记录 `ocenter_question_category`
-- -----------------------------
INSERT INTO `ocenter_question_category` VALUES ('1', '默认分类', '0', '1', '1');


--
-- 表的结构 `ocenter_question_rank`
--

CREATE TABLE IF NOT EXISTS `ocenter_question_rank` (
  `uid` int(11) NOT NULL,
  `support_count` int(11) NOT NULL COMMENT '回答总被点赞数',
  `answer_count` int(11) NOT NULL COMMENT '回答数',
  `best_answer_count` int(11) NOT NULL COMMENT '总最佳回答数'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='问答达人表';
