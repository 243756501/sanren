INSERT INTO `ocenter_auth_rule` ( `module`, `type`, `name`, `title`, `status`, `condition`) VALUES
( 'News', 1, 'News/Index/edit', '编辑资讯（管理）', 1, '');

ALTER TABLE  `ocenter_news` ADD  `post_time` INT( 11 ) NOT NULL DEFAULT '0';