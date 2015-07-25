ALTER TABLE `pages` CHANGE `visible` `visible` VARCHAR(1024) NOT NULL DEFAULT 'a:1:{i:0;s:3:"any";}';
UPDATE `pages` SET `visible`='a:1:{i:0;s:3:"any";}';
CREATE TABLE IF NOT EXISTS `blog_comments` (
  `bc_id` int(11) NOT NULL AUTO_INCREMENT,
  `blog_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `poster_id` int(11) NOT NULL,
  `date_posted` datetime NOT NULL,
  `editor_id` int(11) NOT NULL,
  `date_edited` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
ALTER TABLE `blog` ADD `comments_allowed` BOOLEAN NOT NULL DEFAULT TRUE ;
UPDATE `site_info` SET `version` = '1.3.1' WHERE `site_info`.`id` = 1;