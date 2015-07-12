SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


CREATE TABLE IF NOT EXISTS `blog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `datecreated` datetime NOT NULL,
  `lastedited` datetime NOT NULL,
  `poster` int(11) NOT NULL,
  `title` varchar(1024) NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=39 ;

INSERT INTO `blog` (`id`, `datecreated`, `lastedited`, `poster`, `title`, `content`) VALUES
(28, '2015-07-10 23:07:20', '0000-00-00 00:00:00', 1, 'fhgjtjghj', ''),
(29, '2015-07-10 23:07:27', '0000-00-00 00:00:00', 1, 'ghjtyjtyjtyjt', ''),
(30, '2015-07-10 23:07:31', '0000-00-00 00:00:00', 1, 'yjtyjtyjtyjty', ''),
(31, '2015-07-10 23:07:36', '0000-00-00 00:00:00', 1, 'tjtyjtyjtjt', ''),
(32, '2015-07-10 23:07:39', '0000-00-00 00:00:00', 1, 'jytjtjydjtyjfgj', ''),
(33, '2015-07-10 23:07:42', '0000-00-00 00:00:00', 1, 'jtyjjtyejrytjhty', ''),
(34, '2015-07-10 23:07:46', '0000-00-00 00:00:00', 1, 'tryjtrydjdryjhgyjdtyj', ''),
(35, '2015-07-10 23:07:51', '0000-00-00 00:00:00', 1, 'gdhfjtydbthfythjydtjhty', ''),
(36, '2015-07-10 23:07:55', '0000-00-00 00:00:00', 1, 'dtyjdgjhdtyjhdth', ''),
(37, '2015-07-10 23:07:58', '0000-00-00 00:00:00', 1, 'gjtdyjtdhtyjhnghfjdtyjytfd', ''),
(38, '2015-07-10 23:08:09', '0000-00-00 00:00:00', 1, 'fgjtdyjthgjfgj', '');

CREATE TABLE IF NOT EXISTS `css_selectors` (
  `sid` int(11) NOT NULL AUTO_INCREMENT,
  `s_name` varchar(128) NOT NULL,
  `style_color_id` int(11) NOT NULL,
  PRIMARY KEY (`sid`),
  UNIQUE KEY `id` (`sid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=27 ;

INSERT INTO `css_selectors` (`sid`, `s_name`, `style_color_id`) VALUES
(1, 'website_bg', 10),
(2, 'content_bg', 1),
(3, 'content_text', 23),
(4, 'content_link', 23),
(5, 'menu_bg', 12),
(6, 'menu_text', 1),
(7, 'icon', 12),
(8, 'menu_bg_hover', 13),
(9, 'footer_bg', 13),
(10, 'footer_text', 1),
(11, 'footer_link', 1),
(12, 'selection', 11),
(13, 'menu_text_hover', 1),
(15, 'blog_title_text', 1),
(17, 'header_bg', 1),
(18, 'android_tab', 10),
(20, 'slider_cap_bg', 23),
(21, 'slider_arrows', 10),
(22, 'slider_bullets', 10),
(23, 'slider_cap_text', 1),
(24, 'blog_title_bg', 12),
(25, 'blog_body_bg', 1),
(26, 'blog_body_text', 23);

CREATE TABLE IF NOT EXISTS `features` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `twitterfeed` tinyint(1) NOT NULL,
  `twitteruser` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `forms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `u_name` varchar(16) NOT NULL,
  `creator` int(11) NOT NULL,
  `date_created` datetime NOT NULL,
  `editor` int(11) NOT NULL,
  `last_edited` datetime NOT NULL,
  `email_to` varchar(128) NOT NULL,
  `email_from` varchar(128) NOT NULL,
  `submit_value` varchar(128) NOT NULL,
  `field_names` text NOT NULL,
  `field_types` text NOT NULL,
  `field_descs` text NOT NULL,
  `field_placeholders` text NOT NULL,
  `field_maxchars` text NOT NULL,
  `field_validators` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

INSERT INTO `forms` (`id`, `name`, `u_name`, `creator`, `date_created`, `editor`, `last_edited`, `email_to`, `email_from`, `submit_value`, `field_names`, `field_types`, `field_descs`, `field_placeholders`, `field_maxchars`, `field_validators`) VALUES
(1, 'Test', 'eruyg3244ui3htrf', 1, '2014-10-08 20:11:41', 1, '2015-01-21 20:28:42', 'trist.beach@gmail.com', 'trist.beach@gmail.com', 'Send Comment', 'a:5:{i:0;s:5:"First";i:1;s:4:"Last";i:2;s:5:"Email";i:3;s:8:"Comments";i:4;s:3:"Zip";}', 'a:5:{i:0;s:4:"text";i:1;s:4:"text";i:2;s:4:"text";i:3;s:8:"textarea";i:4;s:4:"text";}', 'a:5:{i:0;s:10:"First Name";i:1;s:9:"Last Name";i:2;s:0:"";i:3;s:22:"qwedewqwefwefwefweffwe";i:4;s:0:"";}', 'a:5:{i:0;s:5:"First";i:1;s:4:"Last";i:2;s:5:"Email";i:3;s:0:"";i:4;s:0:"";}', 'a:5:{i:0;s:0:"";i:1;s:0:"";i:2;s:0:"";i:3;s:4:"5000";i:4;s:0:"";}', 'a:5:{i:0;s:8:"notempty";i:1;s:8:"notempty";i:2;s:5:"email";i:3;s:4:"none";i:4;s:5:"phone";}'),
(3, 'lol', 'hk4vys4mm1m07uif', 1, '2014-11-22 11:13:50', 0, '0000-00-00 00:00:00', '', '', '', '', '', '', '', '', ''),
(4, 'Contact Us', 'dnnqdkqc0kwndr6c', 1, '2014-11-22 12:22:05', 1, '2014-11-22 14:05:17', 'info@ssmechanicalcda.com', 'no-reply@ssmechanicalcda.com', 'Submit', 'a:8:{i:0;s:10:"First Name";i:1;s:9:"Last Name";i:2;s:5:"Email";i:3;s:12:"Phone Number";i:4;s:25:"Preferred Appointment Day";i:5;s:24:"Best Time to Contact You";i:6;s:20:"Service For Estimate";i:7;s:14:"Comments/Notes";}', 'a:8:{i:0;s:4:"text";i:1;s:4:"text";i:2;s:4:"text";i:3;s:4:"text";i:4;s:4:"text";i:5;s:4:"text";i:6;s:4:"text";i:7;s:8:"textarea";}', 'a:8:{i:0;s:0:"";i:1;s:0:"";i:2;s:0:"";i:3;s:0:"";i:4;s:0:"";i:5;s:0:"";i:6;s:0:"";i:7;s:0:"";}', 'a:8:{i:0;s:0:"";i:1;s:0:"";i:2;s:0:"";i:3;s:0:"";i:4;s:0:"";i:5;s:28:"Morning, Afternoon, Evening?";i:6;s:0:"";i:7;s:0:"";}', 'a:8:{i:0;s:0:"";i:1;s:0:"";i:2;s:3:"128";i:3;s:0:"";i:4;s:0:"";i:5;s:3:"256";i:6;s:0:"";i:7;s:4:"5000";}', 'a:8:{i:0;s:8:"notempty";i:1;s:8:"notempty";i:2;s:5:"email";i:3;s:8:"notempty";i:4;s:4:"none";i:5;s:8:"notempty";i:6;s:8:"notempty";i:7;s:4:"none";}'),
(5, 'testing', 'grmifohtqf97rsj2', 1, '2015-01-06 20:24:45', 0, '0000-00-00 00:00:00', '', '', '', '', '', '', '', '', ''),
(6, 'testing', 're8bgd2wp3vvlol2', 1, '2015-01-06 20:28:33', 0, '0000-00-00 00:00:00', '', '', '', '', '', '', '', '', ''),
(7, 'lol', 'c8kle88u9iskv29u', 1, '2015-05-29 18:29:20', 0, '0000-00-00 00:00:00', '', '', '', '', '', '', '', '', '');

CREATE TABLE IF NOT EXISTS `forums` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE latin1_german2_ci NOT NULL,
  `description` text COLLATE latin1_german2_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci AUTO_INCREMENT=23 ;

INSERT INTO `forums` (`id`, `name`, `description`) VALUES
(18, 'Test Forum 2', 'Desc.'),
(19, 'Test3', ''),
(20, 'test4', '');

CREATE TABLE IF NOT EXISTS `forum_posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `forumid` int(11) NOT NULL,
  `threadid` int(11) NOT NULL,
  `poster` varchar(50) COLLATE latin1_german2_ci NOT NULL,
  `date` datetime NOT NULL,
  `lasteditdate` datetime NOT NULL,
  `message` text COLLATE latin1_german2_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci AUTO_INCREMENT=98 ;

CREATE TABLE IF NOT EXISTS `forum_threads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `forumid` int(11) NOT NULL,
  `creator` varchar(50) COLLATE latin1_german2_ci NOT NULL,
  `name` varchar(100) COLLATE latin1_german2_ci NOT NULL,
  `lastpostdate` datetime NOT NULL,
  `pinned` tinyint(1) NOT NULL DEFAULT '0',
  `locked` tinyint(1) NOT NULL DEFAULT '0',
  `datestarted` datetime NOT NULL,
  `views` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci AUTO_INCREMENT=48 ;

CREATE TABLE IF NOT EXISTS `galleries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `subgalleries` varchar(1024) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

INSERT INTO `galleries` (`id`, `name`, `subgalleries`) VALUES
(1, 'Gallery 1', 'a:2:{i:0;s:1:"3";i:1;s:1:"4";}'),
(3, 'Gallery 2', ''),
(4, 'Test', '');

CREATE TABLE IF NOT EXISTS `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) COLLATE latin1_german2_ci NOT NULL,
  `content` text COLLATE latin1_german2_ci NOT NULL,
  `position` int(11) NOT NULL,
  `issubpage` tinyint(1) NOT NULL DEFAULT '0',
  `parent` int(11) NOT NULL,
  `galleries` varchar(1024) COLLATE latin1_german2_ci NOT NULL,
  `doc_folder` varchar(128) COLLATE latin1_german2_ci NOT NULL,
  `forms` text COLLATE latin1_german2_ci NOT NULL,
  `visible` int(1) NOT NULL DEFAULT '1',
  `published` tinyint(1) NOT NULL DEFAULT '1',
  `type` varchar(64) COLLATE latin1_german2_ci NOT NULL DEFAULT 'Custom',
  `target` varchar(16) COLLATE latin1_german2_ci NOT NULL DEFAULT '_self',
  `banner` tinyint(1) NOT NULL DEFAULT '1',
  `slider` int(11) NOT NULL DEFAULT '0',
  `icon` varchar(128) COLLATE latin1_german2_ci NOT NULL,
  `url` varchar(1024) COLLATE latin1_german2_ci NOT NULL DEFAULT 'http://',
  `views` int(11) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `creator` int(11) NOT NULL,
  `lastedited` datetime NOT NULL,
  `editor` int(11) NOT NULL,
  `category` varchar(128) COLLATE latin1_german2_ci NOT NULL,
  `horiz_menu` tinyint(1) NOT NULL DEFAULT '1',
  `vert_menu` tinyint(1) NOT NULL DEFAULT '0',
  `horiz_menu_visible` tinyint(1) NOT NULL DEFAULT '1',
  `vert_menu_visible` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci AUTO_INCREMENT=100 ;

INSERT INTO `pages` (`id`, `name`, `content`, `position`, `issubpage`, `parent`, `galleries`, `doc_folder`, `forms`, `visible`, `published`, `type`, `target`, `banner`, `slider`, `icon`, `url`, `views`, `created`, `creator`, `lastedited`, `editor`, `category`, `horiz_menu`, `vert_menu`, `horiz_menu_visible`, `vert_menu_visible`) VALUES
(92, 'Galleries', '', 4, 0, 0, 'a:1:{i:0;s:1:"1";}', '', '', 1, 1, 'Custom', '_self', 0, 1, '', '', 508, '2014-05-09 03:22:58', 1, '2015-07-10 13:39:41', 1, '', 1, 1, 1, 1),
(65, 'Form', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam rutrum lobortis facilisis. Vivamus ac bibendum enim. Nulla eget porta turpis. Cras odio diam, lobortis a fringilla posuere, auctor ac tortor. Integer molestie mollis justo, eget rhoncus est porta id. In pellentesque augue vitae nisl mollis elementum. Sed ultricies sed odio at vestibulum. Curabitur pulvinar magna semper elementum accumsan. In et sodales elit. Praesent quis ipsum dui. Nullam non sem diam.</p>\r\n<p>Nam porttitor, odio quis vehicula aliquet, mi tellus aliquam enim, eu consectetur lorem massa ut arcu. Phasellus pharetra tellus massa, id rhoncus odio gravida a. Pellentesque consectetur lectus a molestie scelerisque. Nullam nec tortor tristique felis vehicula vulputate eget vitae neque. Aenean erat lorem, feugiat eget metus aliquam, fermentum tempus tortor. Aliquam dignissim lorem libero, ac rutrum sem iaculis eu. Nulla pretium faucibus lobortis.</p>\r\n<p>Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nullam sit amet ultricies urna. Cras commodo rhoncus felis sed venenatis. Suspendisse ornare urna id nunc tempus, facilisis euismod velit venenatis.</p>', 3, 0, 0, '', '', 'a:1:{i:0;s:1:"4";}', 1, 1, 'Custom', '_self', 0, 0, '', '', 509, '2014-05-03 21:12:12', 1, '2015-06-22 20:02:02', 1, '', 0, 1, 1, 1),
(67, 'Staff', '<p>Page 3</p>\r\n<p><a href="../uploads/test/Earth_Eastern_Hemisphere.jpg">Download File</a></p>\r\n<p>sdfds</p>', 4, 0, 0, '', '', '', 1, 1, 'Staff', '_self', 0, 1, '', '', 424, '2014-05-03 22:50:13', 1, '2015-07-10 23:33:52', 1, '', 1, 0, 1, 1),
(69, 'Gallery 1', '<p>Gallery Test</p>', 5, 1, 92, 'a:1:{i:0;s:1:"1";}', '', '', 1, 1, 'Custom', '_self', 0, 0, '', '', 192, '2014-05-03 23:22:56', 1, '2015-07-10 00:04:01', 1, '', 1, 1, 1, 1),
(70, 'Gallery 2', '', 6, 1, 92, 'a:1:{i:0;s:1:"3";}', '', '', 0, 1, 'Custom', '_self', 0, 0, '', '', 70, '2014-05-03 23:23:07', 1, '2015-07-10 00:04:06', 1, '', 1, 1, 1, 1),
(71, 'Forums', '', 8, 0, 0, '', '', '', 1, 1, 'Forum', '_self', 0, 0, '', '', 92, '2014-05-03 23:32:19', 1, '2015-06-22 20:01:36', 1, '', 1, 0, 1, 1),
(99, 'Text', '<h1>Lorem ipsum</h1>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus eget bibendum dolor. Vivamus ornare lectus eu metus convallis egestas. In eu orci nec est viverra accumsan. Sed sed lacus enim. Proin et urna vitae magna lacinia convallis vitae vel diam. Quisque eget porttitor risus. Sed eleifend metus nec sapien pellentesque interdum. Phasellus vehicula ligula sit amet massa pharetra iaculis. Quisque sit amet dictum lorem, id hendrerit velit.</p>\r\n<p>Curabitur vitae libero eu risus pharetra hendrerit. Pellentesque ornare orci sed fringilla lobortis. Sed quam turpis, gravida eget augue a, dapibus laoreet purus. Pellentesque consectetur enim ligula, vitae elementum risus congue vitae. Suspendisse ante justo, varius in ornare nec, aliquam id tortor. Vestibulum vulputate lacinia tincidunt. Integer dolor ligula, condimentum ut venenatis ut, egestas non urna.</p>\r\n<p>Sed at odio sed justo fringilla efficitur. Cras pretium, ipsum a hendrerit convallis, felis libero pharetra eros, a tristique ante eros vel lorem. Vivamus a diam pellentesque elit suscipit tincidunt. Suspendisse efficitur ultrices lectus, vitae rhoncus tortor elementum ac. Sed mattis ligula a imperdiet commodo. Sed facilisis tortor risus, vitae egestas libero rhoncus ac. Donec vel ex urna. Integer fermentum tellus et tincidunt ullamcorper. Duis risus enim, gravida ut dui id, vehicula congue nisl. Integer varius pellentesque metus vel tristique. Nam hendrerit orci nec odio laoreet, non sollicitudin nisl ornare.</p>\r\n<p>Aliquam eu porttitor diam. Vivamus dignissim neque ac enim feugiat, nec mollis lacus scelerisque. Aliquam venenatis ut sem venenatis rhoncus. Praesent eleifend porta mi, sit amet finibus arcu. Donec eros nulla, commodo vitae metus et, dapibus congue purus. Donec hendrerit ligula sit amet sem ornare, vel suscipit velit tempor. Suspendisse scelerisque ex eu posuere tempor. Donec eleifend, ex vitae maximus elementum, quam arcu dictum justo, at tempor lorem eros at lacus. Ut semper, augue posuere ullamcorper sollicitudin, felis risus eleifend augue, vitae sagittis augue justo vel quam. Quisque quis metus lorem. Sed tincidunt pellentesque nibh, in sollicitudin erat finibus quis. Donec id condimentum nibh, eu ornare augue. Phasellus quam lectus, ultricies ut bibendum eget, facilisis eget mauris. Duis non lacus at libero hendrerit posuere at et urna.</p>\r\n<p>Sed eu sem mattis, imperdiet erat vitae, hendrerit risus. Aliquam id mi tellus. Nullam ac tortor sem. Phasellus egestas maximus placerat. In a sollicitudin felis. Ut quis quam ac mi accumsan tincidunt. Curabitur in faucibus metus. Cras non accumsan mi. Curabitur consequat pellentesque euismod. Aliquam vel velit bibendum, facilisis dolor at, iaculis nulla. Sed vel rhoncus lorem. Sed vitae dignissim turpis. Etiam a viverra augue, et eleifend lectus. Aenean et enim sit amet enim varius iaculis. Ut malesuada elementum augue nec luctus. Phasellus sit amet aliquet dui.</p>\r\n<p><span style="font-size: 18pt;"><a href="http://www.google.com">Google</a></span></p>', 12, 0, 0, '', '', '', 1, 1, 'Custom', '_self', 0, 0, '', '', 58, '2015-07-09 23:48:24', 1, '2015-07-09 23:53:46', 1, '', 1, 0, 1, 1),
(86, 'News', '', 1, 0, 0, '', '', '', 1, 1, 'Blog', '_self', 0, 0, '', '', 717, '2014-05-09 02:55:24', 1, '2015-06-16 20:44:31', 1, '', 1, 1, 1, 1),
(95, 'Gallery 3', '', 3, 1, 92, 'a:1:{i:0;s:1:"4";}', '', '', 1, 1, 'Custom', '_self', 0, 0, '', '', 26, '2014-10-30 22:22:31', 1, '2015-07-10 00:03:50', 1, '', 1, 1, 1, 1),
(96, 'Documents', '', 1, 0, 0, '', 'new/', '', 1, 1, 'Custom', '_self', 0, 0, '', '', 77, '2014-12-08 19:47:47', 1, '2015-07-08 17:45:54', 1, '', 1, 1, 0, 0),
(97, 'Client Login', '', 12, 0, 0, '', '', '', 1, 1, 'Link', '_self', 0, 0, '', 'http://cc-wd.info/home/clientarea.php', 0, '2015-01-07 13:42:22', 1, '2015-05-12 22:32:02', 1, '', 0, 0, 1, 1);

CREATE TABLE IF NOT EXISTS `ranks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `created` datetime NOT NULL,
  `permissions` text NOT NULL,
  `color` varchar(7) NOT NULL,
  `deletable` tinyint(1) NOT NULL DEFAULT '1',
  `editable` tinyint(1) NOT NULL DEFAULT '1',
  `admin_rank` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

INSERT INTO `ranks` (`id`, `name`, `created`, `permissions`, `color`, `deletable`, `editable`, `admin_rank`) VALUES
(1, 'Web Master', '2014-05-22 22:30:20', 'a:9:{s:5:"Pages";a:3:{s:9:"add_pages";a:3:{s:5:"value";i:1;s:9:"disp_name";s:3:"Add";s:11:"description";s:61:"Enables members of this rank to add a new page to the website";}s:10:"edit_pages";a:3:{s:5:"value";i:1;s:9:"disp_name";s:4:"Edit";s:11:"description";s:111:"Enables members of this rank to edit existing pages on the website, edit the staff, and edit the slider banner.";}s:12:"delete_pages";a:3:{s:5:"value";i:1;s:9:"disp_name";s:6:"Delete";s:11:"description";s:59:"Enables members of this rank to delete pages on the website";}}s:4:"Blog";a:3:{s:9:"post_blog";a:3:{s:5:"value";i:1;s:9:"disp_name";s:4:"Post";s:11:"description";s:44:"Enables members of this rank to post a blog.";}s:9:"edit_blog";a:3:{s:5:"value";i:1;s:9:"disp_name";s:4:"Edit";s:11:"description";s:52:"Enables members of this rank to edit existing blogs.";}s:11:"delete_blog";a:3:{s:5:"value";i:1;s:9:"disp_name";s:6:"Delete";s:11:"description";s:39:"Enables members of this rank to delete.";}}s:5:"Forum";a:8:{s:16:"add_delete_forum";a:3:{s:5:"value";i:1;s:9:"disp_name";s:19:"Add & Delete Forums";s:11:"description";s:54:"Enables members of this rank to add and delete forums.";}s:10:"edit_forum";a:3:{s:5:"value";i:1;s:9:"disp_name";s:10:"Edit Forum";s:11:"description";s:68:"Enables members of this rank to edit a forum''s name and description.";}s:10:"add_thread";a:3:{s:5:"value";i:1;s:9:"disp_name";s:11:"Post Thread";s:11:"description";s:60:"Enables members of this rank to post a thread in the forums.";}s:15:"reply_to_thread";a:3:{s:5:"value";i:1;s:9:"disp_name";s:15:"Reply to Thread";s:11:"description";s:64:"Enables members of this rank to reply to a thread in the forums.";}s:11:"edit_thread";a:3:{s:5:"value";i:1;s:9:"disp_name";s:9:"Edit Post";s:11:"description";s:66:"Enables members of this rank to edit their own post in the forums.";}s:16:"pin_unpin_thread";a:3:{s:5:"value";i:1;s:9:"disp_name";s:18:"Pin & Unpin Thread";s:11:"description";s:54:"Enables members of this rank to pin and unpin threads.";}s:18:"lock_unlock_thread";a:3:{s:5:"value";i:1;s:9:"disp_name";s:20:"Lock & Unlock Thread";s:11:"description";s:56:"Enables members of this rank to lock and unlock threads.";}s:13:"delete_thread";a:3:{s:5:"value";i:1;s:9:"disp_name";s:13:"Delete Thread";s:11:"description";s:63:"Enables members of this rank to delete threads from the forums.";}}s:5:"Users";a:5:{s:9:"add_users";a:3:{s:5:"value";i:1;s:9:"disp_name";s:9:"Add Users";s:11:"description";s:87:"Enables members of this rank to add users to the website and change those users'' ranks.";}s:12:"delete_users";a:3:{s:5:"value";i:1;s:9:"disp_name";s:9:"Ban Users";s:11:"description";s:59:"Enables members of this rank to ban users from the website.";}s:11:"create_rank";a:3:{s:5:"value";i:1;s:9:"disp_name";s:12:"Create Ranks";s:11:"description";s:60:"Enables members of this rank to create ranks on the website.";}s:9:"edit_rank";a:3:{s:5:"value";i:1;s:9:"disp_name";s:10:"Edit Ranks";s:11:"description";s:67:"Enables members of this rank to edit existing ranks on the website.";}s:11:"delete_rank";a:3:{s:5:"value";i:1;s:9:"disp_name";s:12:"Delete Ranks";s:11:"description";s:60:"Enables members of this rank to delete ranks on the website.";}}s:9:"Uploading";a:8:{s:12:"upload_files";a:3:{s:5:"value";i:1;s:9:"disp_name";s:12:"Upload Files";s:11:"description";s:60:"Enables members of this rank to upload files to the website.";}s:12:"delete_files";a:3:{s:5:"value";i:1;s:9:"disp_name";s:12:"Delete Files";s:11:"description";s:62:"Enables members of this rank to delete files from the website.";}s:12:"rename_files";a:3:{s:5:"value";i:1;s:9:"disp_name";s:12:"Rename Files";s:11:"description";s:69:"Enables members of this rank to rename uploaded files on the website.";}s:12:"create_files";a:3:{s:5:"value";i:1;s:9:"disp_name";s:12:"Create Files";s:11:"description";s:64:"Enables members of this rank to create new files on the website.";}s:10:"edit_files";a:3:{s:5:"value";i:1;s:9:"disp_name";s:10:"Edit Files";s:11:"description";s:58:"Enables members of this rank to edit files on the website.";}s:14:"create_folders";a:3:{s:5:"value";i:1;s:9:"disp_name";s:14:"Create Folders";s:11:"description";s:62:"Enables members of this rank to create folers to put files in.";}s:14:"rename_folders";a:3:{s:5:"value";i:1;s:9:"disp_name";s:14:"Rename Folders";s:11:"description";s:47:"Enables members of this rank to rename folders.";}s:14:"delete_folders";a:3:{s:5:"value";i:1;s:9:"disp_name";s:14:"Delete Folders";s:11:"description";s:47:"Enables members of this rank to delete folders.";}}s:9:"Galleries";a:4:{s:11:"add_gallery";a:3:{s:5:"value";i:1;s:9:"disp_name";s:13:"Add Galleries";s:11:"description";s:61:"Enables members of this rank to add galleries to the website.";}s:12:"edit_gallery";a:3:{s:5:"value";i:1;s:9:"disp_name";s:14:"Edit Galleries";s:11:"description";s:56:"Enables members of this rank to edit existing galleries.";}s:14:"delete_gallery";a:3:{s:5:"value";i:1;s:9:"disp_name";s:16:"Delete Galleries";s:11:"description";s:66:"Enables members of this rank to delete galleries from the website.";}s:14:"rename_gallery";a:3:{s:5:"value";i:1;s:9:"disp_name";s:16:"Rename Galleries";s:11:"description";s:49:"Enables members of this rank to rename galleries.";}}s:7:"Sliders";a:4:{s:10:"add_slider";a:3:{s:5:"value";i:1;s:9:"disp_name";s:10:"Add Slider";s:11:"description";s:59:"Enables members of this rank to add sliders to the website.";}s:11:"edit_slider";a:3:{s:5:"value";i:1;s:9:"disp_name";s:11:"Edit Slider";s:11:"description";s:54:"Enables members of this rank to edit existing sliders.";}s:13:"delete_slider";a:3:{s:5:"value";i:1;s:9:"disp_name";s:13:"Delete Slider";s:11:"description";s:64:"Enables members of this rank to delete sliders from the website.";}s:13:"rename_slider";a:3:{s:5:"value";i:1;s:9:"disp_name";s:13:"Rename Slider";s:11:"description";s:47:"Enables members of this rank to rename sliders.";}}s:5:"Forms";a:3:{s:8:"add_form";a:3:{s:5:"value";i:1;s:9:"disp_name";s:9:"Add Forms";s:11:"description";s:42:"Enables members of this rank to add forms.";}s:9:"edit_form";a:3:{s:5:"value";i:1;s:9:"disp_name";s:10:"Edit Forms";s:11:"description";s:52:"Enables members of this rank to edit existing forms.";}s:11:"delete_form";a:3:{s:5:"value";i:1;s:9:"disp_name";s:12:"Delete Forms";s:11:"description";s:45:"Enables members of this rank to delete forms.";}}s:7:"Website";a:6:{s:13:"cpanel_access";a:3:{s:5:"value";i:1;s:9:"disp_name";s:13:"CPanel Access";s:11:"description";s:56:"Enables members of this rank to access the admin CPanel.";}s:18:"edit_site_settings";a:3:{s:5:"value";i:1;s:9:"disp_name";s:24:"Edit Website Information";s:11:"description";s:61:"Enables members of this rank to edit the website information.";}s:16:"edit_site_colors";a:3:{s:5:"value";i:1;s:9:"disp_name";s:19:"Edit Website Colors";s:11:"description";s:64:"Enables members of this rank to modify the website theme colors.";}s:21:"upload_favicon_banner";a:3:{s:5:"value";i:1;s:9:"disp_name";s:25:"Upload Favicon and Banner";s:11:"description";s:75:"Enables members of this rank to upload a favicon and banner to the website.";}s:11:"edit_socnet";a:3:{s:5:"value";i:1;s:9:"disp_name";s:20:"Edit Social Networks";s:11:"description";s:85:"Enables members of this rank to edit the social networks the website is connected to.";}s:21:"edit_google_analytics";a:3:{s:5:"value";i:1;s:9:"disp_name";s:21:"Edit Google Analytics";s:11:"description";s:70:"Enables members of this rank to edit the Google Analytics information.";}}}', '#FF0000', 0, 0, 1),
(6, 'Member', '2014-05-22 22:30:20', 'a:6:{s:4:"Blog";a:1:{s:9:"post_blog";a:1:{s:5:"value";s:1:"1";}}s:5:"Forum";a:2:{s:10:"add_thread";a:1:{s:5:"value";s:1:"1";}s:15:"reply_to_thread";a:1:{s:5:"value";s:1:"1";}}s:5:"Users";a:5:{s:9:"add_users";a:1:{s:5:"value";s:1:"1";}s:12:"delete_users";a:1:{s:5:"value";s:1:"1";}s:11:"create_rank";a:1:{s:5:"value";s:1:"1";}s:9:"edit_rank";a:1:{s:5:"value";s:1:"1";}s:11:"delete_rank";a:1:{s:5:"value";s:1:"1";}}s:9:"Uploading";a:2:{s:12:"create_files";a:1:{s:5:"value";s:1:"1";}s:14:"create_folders";a:1:{s:5:"value";s:1:"1";}}s:7:"Sliders";a:4:{s:10:"add_slider";a:1:{s:5:"value";s:1:"1";}s:11:"edit_slider";a:1:{s:5:"value";s:1:"1";}s:13:"delete_slider";a:1:{s:5:"value";s:1:"1";}s:13:"rename_slider";a:1:{s:5:"value";s:1:"1";}}s:7:"Website";a:1:{s:13:"cpanel_access";a:1:{s:5:"value";s:1:"1";}}}', '#696969', 1, 1, 0),
(9, 'Content Editor', '2014-05-22 22:58:06', 'a:6:{s:5:"Pages";a:3:{s:9:"add_pages";a:1:{s:5:"value";s:1:"1";}s:10:"edit_pages";a:1:{s:5:"value";s:1:"1";}s:12:"delete_pages";a:1:{s:5:"value";s:1:"1";}}s:4:"Blog";a:3:{s:9:"post_blog";a:1:{s:5:"value";s:1:"1";}s:9:"edit_blog";a:1:{s:5:"value";s:1:"1";}s:11:"delete_blog";a:1:{s:5:"value";s:1:"1";}}s:5:"Forum";a:6:{s:16:"add_delete_forum";a:1:{s:5:"value";s:1:"1";}s:10:"edit_forum";a:1:{s:5:"value";s:1:"1";}s:10:"add_thread";a:1:{s:5:"value";s:1:"1";}s:15:"reply_to_thread";a:1:{s:5:"value";s:1:"1";}s:11:"edit_thread";a:1:{s:5:"value";s:1:"1";}s:13:"delete_thread";a:1:{s:5:"value";s:1:"1";}}s:5:"Users";a:5:{s:9:"add_users";a:1:{s:5:"value";s:1:"1";}s:12:"delete_users";a:1:{s:5:"value";s:1:"1";}s:11:"create_rank";a:1:{s:5:"value";s:1:"1";}s:9:"edit_rank";a:1:{s:5:"value";s:1:"1";}s:11:"delete_rank";a:1:{s:5:"value";s:1:"1";}}s:9:"Galleries";a:4:{s:11:"add_gallery";a:1:{s:5:"value";s:1:"1";}s:12:"edit_gallery";a:1:{s:5:"value";s:1:"1";}s:14:"delete_gallery";a:1:{s:5:"value";s:1:"1";}s:14:"rename_gallery";a:1:{s:5:"value";s:1:"1";}}s:7:"Website";a:6:{s:13:"cpanel_access";a:1:{s:5:"value";s:1:"1";}s:18:"edit_site_settings";a:1:{s:5:"value";s:1:"1";}s:16:"edit_site_colors";a:1:{s:5:"value";s:1:"1";}s:18:"edit_contact_email";a:1:{s:5:"value";s:1:"1";}s:21:"upload_favicon_banner";a:1:{s:5:"value";s:1:"1";}s:21:"edit_google_analytics";a:1:{s:5:"value";s:1:"1";}}}', '#0800FF', 1, 1, 0),
(10, 'Test', '2014-11-14 17:32:41', '', '', 1, 1, 0);

CREATE TABLE IF NOT EXISTS `site_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(1024) NOT NULL DEFAULT 'Second Generation Design',
  `version` varchar(64) NOT NULL,
  `date_run` datetime NOT NULL,
  `base_url` varchar(256) NOT NULL,
  `timezone` varchar(128) NOT NULL DEFAULT 'America/Los_Angeles',
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `homepage` varchar(1024) NOT NULL,
  `contact_email` varchar(128) NOT NULL,
  `copyright_text` text NOT NULL,
  `default_rank` int(11) NOT NULL,
  `meta_tags` text NOT NULL,
  `style_js_link_tags` text NOT NULL,
  `g_analytics_enabled` tinyint(1) NOT NULL DEFAULT '0',
  `g_analytics_code` text NOT NULL,
  `footer_content` text NOT NULL,
  `logo_url` varchar(256) NOT NULL,
  `facebook_url` varchar(256) NOT NULL,
  `facebook_enabled` tinyint(1) NOT NULL DEFAULT '0',
  `googleplus_url` varchar(256) NOT NULL,
  `googleplus_enabled` tinyint(1) NOT NULL DEFAULT '0',
  `twitter_url` varchar(256) NOT NULL,
  `twitter_enabled` tinyint(1) NOT NULL DEFAULT '0',
  `instagram_url` varchar(256) NOT NULL,
  `instagram_enabled` tinyint(1) NOT NULL DEFAULT '0',
  `linkedin_url` varchar(256) NOT NULL,
  `linkedin_enabled` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

INSERT INTO `site_info` (`id`, `name`, `version`, `date_run`, `base_url`, `timezone`, `published`, `homepage`, `contact_email`, `copyright_text`, `default_rank`, `meta_tags`, `style_js_link_tags`, `g_analytics_enabled`, `g_analytics_code`, `footer_content`, `logo_url`, `facebook_url`, `facebook_enabled`, `googleplus_url`, `googleplus_enabled`, `twitter_url`, `twitter_enabled`, `instagram_url`, `instagram_enabled`, `linkedin_url`, `linkedin_enabled`) VALUES
(1, 'Project Illuminate', '1.3', '0000-00-00 00:00:00', 'http://trist.2gd.net', 'America/Los_Angeles', 1, '86', 'test@website.com', '', 6, '<meta name="description" content="Project Illuminate, Second Gen Design" />', '', 0, '<script>\r\n  (function(i,s,o,g,r,a,m){i[''GoogleAnalyticsObject'']=r;i[r]=i[r]||function(){\r\n  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),\r\n  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)\r\n  })(window,document,''script'',''//www.google-analytics.com/analytics.js'',''ga'');\r\n\r\n  ga(''create'', ''UA-45206341-1'', ''auto'');\r\n  ga(''require'', ''displayfeatures'');\r\n  ga(''send'', ''pageview'');\r\n\r\n</script>', '<p>sssssss</p>\r\n<p>ddddd</p>', 'http://trist.2gd.net', 'http://www.facebook.com/2ndgendesign', 1, 'http://plus.google.com', 1, 'http://www.twitter.com', 1, 'http://www.instagram.com', 1, 'http://www.linkedin.com', 1);

CREATE TABLE IF NOT EXISTS `site_layout` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_color` varchar(7) NOT NULL,
  `contentbg_color` varchar(7) NOT NULL,
  `sitebg_color` varchar(7) NOT NULL,
  `accent_color` varchar(7) NOT NULL,
  `text_color` varchar(7) NOT NULL,
  `bg_repeat` varchar(64) NOT NULL,
  `bg_position` varchar(64) NOT NULL,
  `bg_size` varchar(64) NOT NULL,
  `bg_fixed` tinyint(1) NOT NULL DEFAULT '1',
  `use_bg_color` tinyint(1) NOT NULL DEFAULT '1',
  `custom_css` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

INSERT INTO `site_layout` (`id`, `menu_color`, `contentbg_color`, `sitebg_color`, `accent_color`, `text_color`, `bg_repeat`, `bg_position`, `bg_size`, `bg_fixed`, `use_bg_color`, `custom_css`) VALUES
(1, '#FFFFFF', '#FFFFFF', '#3498DB', '#2980B9', '#333333', 'no-repeat', 'left top', 'cover', 1, 1, '');

CREATE TABLE IF NOT EXISTS `slider_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `img_name` varchar(1024) NOT NULL,
  `slider_id` int(11) NOT NULL,
  `order` int(11) NOT NULL,
  `caption` text NOT NULL,
  `url` varchar(512) NOT NULL,
  `new_tab` tinyint(1) NOT NULL DEFAULT '0',
  `published` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=43 ;

INSERT INTO `slider_images` (`id`, `img_name`, `slider_id`, `order`, `caption`, `url`, `new_tab`, `published`) VALUES
(35, 'JA60ecM.jpg', 4, 1, 'Futurama', 'http://www.google.com', 1, 1),
(39, '20130624_020822.jpg', 5, 1, '', '', 0, 1),
(40, 'blue.jpg', 1, 1, 'Green', '', 1, 1),
(41, 'purple.jpg', 1, 2, 'Purple', '', 0, 1),
(42, 'red.jpg', 1, 3, 'Red', '', 0, 1);

CREATE TABLE IF NOT EXISTS `slider_names` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `date_created` datetime NOT NULL,
  `creator` int(11) NOT NULL,
  `date_edited` datetime NOT NULL,
  `editor` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

INSERT INTO `slider_names` (`id`, `name`, `date_created`, `creator`, `date_edited`, `editor`) VALUES
(1, 'Test', '0000-00-00 00:00:00', 0, '2015-03-22 15:43:32', 1);

CREATE TABLE IF NOT EXISTS `staff` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_created` datetime NOT NULL,
  `creator` int(11) NOT NULL,
  `last_edited` datetime NOT NULL,
  `editor` int(11) NOT NULL,
  `order` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `role` varchar(128) NOT NULL,
  `bio` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `style_colors` (
  `cid` int(11) NOT NULL AUTO_INCREMENT,
  `c_name` varchar(64) NOT NULL,
  `color_hex` varchar(7) NOT NULL,
  `date_created` datetime NOT NULL,
  `creator` int(11) NOT NULL,
  `date_edited` datetime NOT NULL,
  `editor` int(11) NOT NULL,
  `deletable` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`cid`),
  UNIQUE KEY `id` (`cid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=26 ;

INSERT INTO `style_colors` (`cid`, `c_name`, `color_hex`, `date_created`, `creator`, `date_edited`, `editor`, `deletable`) VALUES
(1, 'Usable Colors', '#FFFFFF', '0000-00-00 00:00:00', 1, '2015-07-12 01:29:16', 1, 0),
(10, 'Site BG', '#1FADFF', '2015-07-10 13:27:46', 1, '2015-07-12 01:29:16', 1, 1),
(11, 'Content BG', '#EEFF00', '2015-07-10 13:28:28', 1, '2015-07-12 01:29:16', 1, 1),
(12, 'Menu', '#065475', '2015-07-10 13:29:02', 1, '2015-07-12 01:29:16', 1, 1),
(13, 'Menu Hover', '#032E40', '2015-07-10 13:29:37', 1, '2015-07-12 01:29:16', 1, 1),
(23, '', '#000000', '2015-07-10 16:52:10', 1, '2015-07-12 01:29:16', 1, 1);

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE latin1_german2_ci NOT NULL,
  `hashed_pass` varchar(40) COLLATE latin1_german2_ci NOT NULL,
  `email` varchar(200) COLLATE latin1_german2_ci NOT NULL,
  `created` datetime NOT NULL,
  `deletable` int(1) NOT NULL DEFAULT '1',
  `rank` int(11) NOT NULL DEFAULT '0',
  `last_logged_in` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci AUTO_INCREMENT=63 ;

INSERT INTO `users` (`id`, `username`, `hashed_pass`, `email`, `created`, `deletable`, `rank`, `last_logged_in`) VALUES
(1, 'Admin', '9d4e1e23bd5b727046a9e3b4b7db57bd8d6ee684', 'secondgendesign@gmail.com', '2014-05-13 17:06:01', 0, 1, '2015-07-10 16:02:30'),
(62, 'Delbert', 'd6cf8b20798f79d9186978670bf6143eb55d4b31', 'johnsondelbert1@gmail.com', '2015-06-16 11:07:33', 1, 1, '2015-06-16 11:07:50'),
(54, 'member', '9d4e1e23bd5b727046a9e3b4b7db57bd8d6ee684', 'secondgendesign@gmail.com', '2014-05-14 00:07:22', 1, 6, '2015-03-22 12:38:52'),
(57, 'new', 'c2a6b03f190dfb2b4aa91f8af8d477a9bc3401dc', 'new@new.com', '2014-05-22 02:39:56', 1, 9, '0000-00-00 00:00:00');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
