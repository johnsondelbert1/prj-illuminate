ALTER TABLE `forums` ADD `visible` VARCHAR(1024) NOT NULL DEFAULT 'a:1:{i:0;s:3:"any";}';
ALTER TABLE `site_info` ADD `forum_post_custom_user_data` VARCHAR(1024) NOT NULL AFTER `require_email_activation`;
ALTER TABLE `users` ADD `forum_signature` TEXT NOT NULL;
ALTER TABLE `forum_posts` CHANGE `poster` `poster` INT(11) NOT NULL;
ALTER TABLE `users` ADD `forum_post_count` INT(11) NOT NULL AFTER `chng_pass_authcode_date`;
ALTER TABLE `forum_threads` CHANGE `creator` `creator` INT NOT NULL;
UPDATE `site_info` SET `version` = '1.5.1' WHERE `site_info`.`id` = 1;