CREATE TABLE IF NOT EXISTS `site_gallery_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  `gallery_id` int(11) NOT NULL,
  `type` varchar(64) NOT NULL DEFAULT 'image',
  `description` TEXT NOT NULL,
  `url` varchar(128) NOT NULL,
  `position` int(11) NOT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;
ALTER TABLE `galleries` ADD `date_created` DATETIME NOT NULL AFTER `name`, ADD `creator` INT(11) NOT NULL AFTER `date_created`, ADD `thumb_width` INT(11) NOT NULL DEFAULT '150' AFTER `creator`, ADD `thumb_height` INT(11) NOT NULL DEFAULT '100' AFTER `thumb_width`, ADD `thumb_scale_type` VARCHAR(64) NOT NULL DEFAULT 'scale_height' AFTER `thumb_height`, ADD `type` VARCHAR(64) NOT NULL DEFAULT 'page', ADD `dir` VARCHAR(128) NOT NULL DEFAULT 'site-galleries/';
ALTER TABLE `blog` ADD `gallery_id` INT(11) NOT NULL;
UPDATE `site_info` SET `version` = '1.6' WHERE `site_info`.`id` = 1;