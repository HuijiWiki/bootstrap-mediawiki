-- ----------------------------
-- Table structure for `common_css`
-- ----------------------------
DROP TABLE IF EXISTS /*_*/`common_css`;
CREATE TABLE /*_*/common_css (
  `css_id` tinyint(2) NOT NULL AUTO_INCREMENT COMMENT 'css文件id',
  `css_name` varbinary(50) NOT NULL COMMENT 'css文件名',
  `css_content` blob NOT NULL COMMENT '内容',
  `css_status` tinyint(2) NOT NULL COMMENT '状态, 1-on 2-off',
  `update_date` datetime NOT NUll COMMENT '更新时间',
  PRIMARY KEY (`css_id`)
)/*$wgDBTableOptions*/;
