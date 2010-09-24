ALTER TABLE  `users` CHANGE  `chatpost`  `chatpost` ENUM(  'yes',  'no' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT  'yes' ;
ALTER TABLE `torrents` ADD `free` int(11) unsigned NOT NULL default '0';
ALTER TABLE `users` ADD `freeslots` int(11) unsigned NOT NULL default '5';
ALTER TABLE `users` ADD `free_switch` int(11) unsigned NOT NULL default '0';
ALTER TABLE `users` ADD INDEX (`free_switch`);
CREATE TABLE `freeslots` (
  `tid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `double` int(10) unsigned NOT NULL default '0',
  `free` int(10) unsigned NOT NULL default '0',
  UNIQUE KEY `tid_uid` (`tid`,`uid`)
);