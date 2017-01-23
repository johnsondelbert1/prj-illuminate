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
CREATE TABLE IF NOT EXISTS `calendars` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT 'calendar',
  `created` datetime NOT NULL,
  `creator` int(11) NOT NULL,
  `visibility` VARCHAR(1024) NOT NULL DEFAULT 'a:1:{i:0;s:3:"any";}',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
CREATE TABLE IF NOT EXISTS `calendar_events` (
`id` int(11) NOT NULL AUTO_INCREMENT,
  `calendar_id` INT(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `time_start` VARCHAR(255) NOT NULL,
  `time_end` VARCHAR(255) NOT NULL,
  `location` VARCHAR(255) NOT NULL,
  `recurrence` VARCHAR(255) NOT NULL DEFAULT 'none',
  `recurrence_data` TEXT NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=Active, 0=Block',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
ALTER TABLE `galleries` ADD `date_created` DATETIME NOT NULL AFTER `name`, ADD `creator` INT(11) NOT NULL AFTER `date_created`, ADD `thumb_width` INT(11) NOT NULL DEFAULT '150' AFTER `creator`, ADD `thumb_height` INT(11) NOT NULL DEFAULT '100' AFTER `thumb_width`, ADD `thumb_scale_type` VARCHAR(64) NOT NULL DEFAULT 'scale_height' AFTER `thumb_height`, ADD `type` VARCHAR(64) NOT NULL DEFAULT 'page', ADD `dir` VARCHAR(128) NOT NULL DEFAULT 'site-galleries/';
ALTER TABLE `blog` ADD `gallery_id` INT(11) NOT NULL;
ALTER TABLE `site_info` ADD `user_profile_pictures` BOOLEAN NOT NULL DEFAULT FALSE AFTER `require_email_activation`, ADD `user_profiles_visible_loggedin` BOOLEAN NOT NULL DEFAULT TRUE AFTER `user_profile_pictures`;
ALTER TABLE `users` ADD `subscriptions` TEXT NOT NULL;
ALTER TABLE `pages` ADD `calendars` TEXT NOT NULL AFTER `forms`;
UPDATE `site_info` SET `version` = '1.6' WHERE `site_info`.`id` = 1;