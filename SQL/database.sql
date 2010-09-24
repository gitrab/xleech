-- phpMyAdmin SQL Dump
-- version 3.3.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 23, 2010 at 08:58 PM
-- Server version: 5.1.37
-- PHP Version: 5.2.10-2ubuntu6.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `cancer`
--

-- --------------------------------------------------------

--
-- Table structure for table `avps`
--

CREATE TABLE IF NOT EXISTS `avps` (
  `arg` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `value_s` text COLLATE utf8_unicode_ci NOT NULL,
  `value_i` int(11) NOT NULL DEFAULT '0',
  `value_u` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`arg`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `avps`
--

INSERT INTO `avps` (`arg`, `value_s`, `value_i`, `value_u`) VALUES
('lastcleantime', '', 0, 1247059621),
('seeders', '', 0, 1),
('leechers', '', 0, 0),
('loadlimit', '12.5-1246045258', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `bans`
--

CREATE TABLE IF NOT EXISTS `bans` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `added` int(11) NOT NULL,
  `addedby` int(10) unsigned NOT NULL DEFAULT '0',
  `comment` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `first` int(11) DEFAULT NULL,
  `last` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `first_last` (`first`,`last`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `bans`
--


-- --------------------------------------------------------

--
-- Table structure for table `blocks`
--

CREATE TABLE IF NOT EXISTS `blocks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `blockid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `userfriend` (`userid`,`blockid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `blocks`
--


-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cat_desc` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'No Description',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=15 ;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `image`, `cat_desc`) VALUES
(1, 'Appz/PC ISO', 'cat_appz_0day.png', 'No Description'),
(2, 'Games/PC ISO', 'cat_games_pciso.png', 'No Description'),
(4, 'Music', 'cat_music_mp3.png', 'No Description'),
(5, 'Episodes', 'cat_tv_xvid.png', 'No Description'),
(6, 'XXX', 'cat_xxx_xvid.png', 'No Description'),
(8, 'Games/PS2', 'cat_games_ps2.png', 'No Description'),
(9, 'Anime', 'cat_anime.png', 'No Description'),
(10, 'Movies/XviD', 'cat_movies_xvid.png', 'No Description'),
(11, 'Movies/DVD-R', 'cat_movies_dvdr.png', 'No Description'),
(13, 'Appz/misc', 'cat_appz_0day.png', 'No Description'),
(14, 'Misc', 'cat_dox.png', 'Misc');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user` int(10) unsigned NOT NULL DEFAULT '0',
  `torrent` int(10) unsigned NOT NULL DEFAULT '0',
  `added` int(11) NOT NULL,
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  `ori_text` text COLLATE utf8_unicode_ci NOT NULL,
  `editedby` int(10) unsigned NOT NULL DEFAULT '0',
  `editedat` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user` (`user`),
  KEY `torrent` (`torrent`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `comments`
--


-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE IF NOT EXISTS `countries` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `flagpic` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=101 ;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `name`, `flagpic`) VALUES
(1, 'Sweden', 'sweden.gif'),
(2, 'United States of America', 'usa.gif'),
(3, 'Russia', 'russia.gif'),
(4, 'Finland', 'finland.gif'),
(5, 'Canada', 'canada.gif'),
(6, 'France', 'france.gif'),
(7, 'Germany', 'germany.gif'),
(8, 'China', 'china.gif'),
(9, 'Italy', 'italy.gif'),
(10, 'Denmark', 'denmark.gif'),
(11, 'Norway', 'norway.gif'),
(12, 'United Kingdom', 'uk.gif'),
(13, 'Ireland', 'ireland.gif'),
(14, 'Poland', 'poland.gif'),
(15, 'Netherlands', 'netherlands.gif'),
(16, 'Belgium', 'belgium.gif'),
(17, 'Japan', 'japan.gif'),
(18, 'Brazil', 'brazil.gif'),
(19, 'Argentina', 'argentina.gif'),
(20, 'Australia', 'australia.gif'),
(21, 'New Zealand', 'newzealand.gif'),
(22, 'Spain', 'spain.gif'),
(23, 'Portugal', 'portugal.gif'),
(24, 'Mexico', 'mexico.gif'),
(25, 'Singapore', 'singapore.gif'),
(67, 'India', 'india.gif'),
(62, 'Albania', 'albania.gif'),
(26, 'South Africa', 'southafrica.gif'),
(27, 'South Korea', 'southkorea.gif'),
(28, 'Jamaica', 'jamaica.gif'),
(29, 'Luxembourg', 'luxembourg.gif'),
(30, 'Hong Kong', 'hongkong.gif'),
(31, 'Belize', 'belize.gif'),
(32, 'Algeria', 'algeria.gif'),
(33, 'Angola', 'angola.gif'),
(34, 'Austria', 'austria.gif'),
(35, 'Yugoslavia', 'yugoslavia.gif'),
(36, 'Western Samoa', 'westernsamoa.gif'),
(37, 'Malaysia', 'malaysia.gif'),
(38, 'Dominican Republic', 'dominicanrep.gif'),
(39, 'Greece', 'greece.gif'),
(40, 'Guatemala', 'guatemala.gif'),
(41, 'Israel', 'israel.gif'),
(42, 'Pakistan', 'pakistan.gif'),
(43, 'Czech Republic', 'czechrep.gif'),
(44, 'Serbia', 'serbia.gif'),
(45, 'Seychelles', 'seychelles.gif'),
(46, 'Taiwan', 'taiwan.gif'),
(47, 'Puerto Rico', 'puertorico.gif'),
(48, 'Chile', 'chile.gif'),
(49, 'Cuba', 'cuba.gif'),
(50, 'Congo', 'congo.gif'),
(51, 'Afghanistan', 'afghanistan.gif'),
(52, 'Turkey', 'turkey.gif'),
(53, 'Uzbekistan', 'uzbekistan.gif'),
(54, 'Switzerland', 'switzerland.gif'),
(55, 'Kiribati', 'kiribati.gif'),
(56, 'Philippines', 'philippines.gif'),
(57, 'Burkina Faso', 'burkinafaso.gif'),
(58, 'Nigeria', 'nigeria.gif'),
(59, 'Iceland', 'iceland.gif'),
(60, 'Nauru', 'nauru.gif'),
(61, 'Slovenia', 'slovenia.gif'),
(63, 'Turkmenistan', 'turkmenistan.gif'),
(64, 'Bosnia Herzegovina', 'bosniaherzegovina.gif'),
(65, 'Andorra', 'andorra.gif'),
(66, 'Lithuania', 'lithuania.gif'),
(68, 'Netherlands Antilles', 'nethantilles.gif'),
(69, 'Ukraine', 'ukraine.gif'),
(70, 'Venezuela', 'venezuela.gif'),
(71, 'Hungary', 'hungary.gif'),
(72, 'Romania', 'romania.gif'),
(73, 'Vanuatu', 'vanuatu.gif'),
(74, 'Vietnam', 'vietnam.gif'),
(75, 'Trinidad & Tobago', 'trinidadandtobago.gif'),
(76, 'Honduras', 'honduras.gif'),
(77, 'Kyrgyzstan', 'kyrgyzstan.gif'),
(78, 'Ecuador', 'ecuador.gif'),
(79, 'Bahamas', 'bahamas.gif'),
(80, 'Peru', 'peru.gif'),
(81, 'Cambodia', 'cambodia.gif'),
(82, 'Barbados', 'barbados.gif'),
(83, 'Bangladesh', 'bangladesh.gif'),
(84, 'Laos', 'laos.gif'),
(85, 'Uruguay', 'uruguay.gif'),
(86, 'Antigua Barbuda', 'antiguabarbuda.gif'),
(87, 'Paraguay', 'paraguay.gif'),
(89, 'Thailand', 'thailand.gif'),
(88, 'Union of Soviet Socialist Republics', 'ussr.gif'),
(90, 'Senegal', 'senegal.gif'),
(91, 'Togo', 'togo.gif'),
(92, 'North Korea', 'northkorea.gif'),
(93, 'Croatia', 'croatia.gif'),
(94, 'Estonia', 'estonia.gif'),
(95, 'Colombia', 'colombia.gif'),
(96, 'Lebanon', 'lebanon.gif'),
(97, 'Latvia', 'latvia.gif'),
(98, 'Costa Rica', 'costarica.gif'),
(99, 'Egypt', 'egypt.gif'),
(100, 'Bulgaria', 'bulgaria.gif');

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE IF NOT EXISTS `files` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `torrent` int(10) unsigned NOT NULL DEFAULT '0',
  `filename` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `size` bigint(20) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `torrent` (`torrent`),
  FULLTEXT KEY `filename` (`filename`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `files`
--


-- --------------------------------------------------------

--
-- Table structure for table `forums`
--

CREATE TABLE IF NOT EXISTS `forums` (
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `minclassread` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `minclasswrite` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `postcount` int(10) unsigned NOT NULL DEFAULT '0',
  `topiccount` int(10) unsigned NOT NULL DEFAULT '0',
  `minclasscreate` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `forums`
--


-- --------------------------------------------------------

--
-- Table structure for table `friends`
--

CREATE TABLE IF NOT EXISTS `friends` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `friendid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `userfriend` (`userid`,`friendid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `friends`
--


-- --------------------------------------------------------

--
-- Table structure for table `invite_codes`
--

CREATE TABLE IF NOT EXISTS `invite_codes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sender` int(10) unsigned NOT NULL DEFAULT '0',
  `receiver` varchar(32) NOT NULL DEFAULT '0',
  `code` varchar(32) NOT NULL DEFAULT '',
  `invite_added` int(10) NOT NULL,
  `status` enum('Pending','Confirmed') NOT NULL DEFAULT 'Pending',
  PRIMARY KEY (`id`),
  KEY `sender` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `invite_codes`
--


-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sender` int(10) unsigned NOT NULL DEFAULT '0',
  `receiver` int(10) unsigned NOT NULL DEFAULT '0',
  `added` int(11) NOT NULL,
  `subject` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'No Subject',
  `msg` text COLLATE utf8_unicode_ci,
  `unread` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'yes',
  `poster` bigint(20) unsigned NOT NULL DEFAULT '0',
  `location` smallint(6) NOT NULL DEFAULT '1',
  `saved` enum('no','yes') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `messages`
--


-- --------------------------------------------------------

--
-- Table structure for table `modscredits`
--

CREATE TABLE IF NOT EXISTS `modscredits` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `category` enum('Addon','Forum','Message/Email','Display/Style','Staff/Tools','Browse/Torrent/Details','Misc') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Misc',
  `status` enum('Complete','In-Progress') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Complete',
  `tbdevlnk` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `credit` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(120) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `modscredits`
--


-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE IF NOT EXISTS `news` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL DEFAULT '0',
  `added` int(11) NOT NULL,
  `body` text COLLATE utf8_unicode_ci NOT NULL,
  `headline` varchar(150) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'TBDEV.NET News',
  PRIMARY KEY (`id`),
  KEY `added` (`added`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `news`
--


-- --------------------------------------------------------

--
-- Table structure for table `peers`
--

CREATE TABLE IF NOT EXISTS `peers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `torrent` int(10) unsigned NOT NULL DEFAULT '0',
  `passkey` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `peer_id` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `ip` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `port` smallint(5) unsigned NOT NULL DEFAULT '0',
  `uploaded` bigint(20) unsigned NOT NULL DEFAULT '0',
  `downloaded` bigint(20) unsigned NOT NULL DEFAULT '0',
  `to_go` bigint(20) unsigned NOT NULL DEFAULT '0',
  `seeder` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `started` int(11) NOT NULL,
  `last_action` int(11) NOT NULL,
  `connectable` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'yes',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `agent` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `finishedat` int(10) unsigned NOT NULL DEFAULT '0',
  `downloadoffset` bigint(20) unsigned NOT NULL DEFAULT '0',
  `uploadoffset` bigint(20) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `torrent_peer_id` (`torrent`,`peer_id`),
  KEY `torrent` (`torrent`),
  KEY `torrent_seeder` (`torrent`,`seeder`),
  KEY `last_action` (`last_action`),
  KEY `connectable` (`connectable`),
  KEY `userid` (`userid`),
  KEY `passkey` (`passkey`),
  KEY `torrent_connect` (`torrent`,`connectable`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `peers`
--


-- --------------------------------------------------------

--
-- Table structure for table `pmboxes`
--

CREATE TABLE IF NOT EXISTS `pmboxes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `boxnumber` tinyint(4) NOT NULL DEFAULT '2',
  `name` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `pmboxes`
--


-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE IF NOT EXISTS `posts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `topicid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `added` int(11) NOT NULL,
  `body` text COLLATE utf8_unicode_ci,
  `editedby` int(10) unsigned NOT NULL DEFAULT '0',
  `editedat` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `topicid` (`topicid`),
  KEY `userid` (`userid`),
  FULLTEXT KEY `body` (`body`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `posts`
--


-- --------------------------------------------------------

--
-- Table structure for table `readposts`
--

CREATE TABLE IF NOT EXISTS `readposts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `topicid` int(10) unsigned NOT NULL DEFAULT '0',
  `lastpostread` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `topicid` (`topicid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `readposts`
--


-- --------------------------------------------------------

--
-- Table structure for table `reputation`
--

CREATE TABLE IF NOT EXISTS `reputation` (
  `reputationid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `reputation` int(10) NOT NULL DEFAULT '0',
  `whoadded` int(10) NOT NULL DEFAULT '0',
  `reason` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dateadd` int(10) NOT NULL DEFAULT '0',
  `postid` int(10) NOT NULL DEFAULT '0',
  `userid` mediumint(8) NOT NULL DEFAULT '0',
  PRIMARY KEY (`reputationid`),
  KEY `userid` (`userid`),
  KEY `whoadded` (`whoadded`),
  KEY `multi` (`postid`,`userid`),
  KEY `dateadd` (`dateadd`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `reputation`
--


-- --------------------------------------------------------

--
-- Table structure for table `reputationlevel`
--

CREATE TABLE IF NOT EXISTS `reputationlevel` (
  `reputationlevelid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `minimumreputation` int(10) NOT NULL DEFAULT '0',
  `level` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`reputationlevelid`),
  KEY `reputationlevel` (`minimumreputation`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=16 ;

--
-- Dumping data for table `reputationlevel`
--

INSERT INTO `reputationlevel` (`reputationlevelid`, `minimumreputation`, `level`) VALUES
(1, -999999, 'is infamous around these parts'),
(2, -50, 'can only hope to improve'),
(3, -10, 'has a little shameless behaviour in the past'),
(4, 0, 'is an unknown quantity at this point'),
(5, 10, 'is on a distinguished road'),
(6, 50, 'will become famous soon enough'),
(7, 150, 'has a spectacular aura about'),
(8, 250, 'is a jewel in the rough'),
(9, 350, 'is just really nice'),
(10, 450, 'is a glorious beacon of light'),
(11, 550, 'is a name known to all'),
(12, 650, 'is a splendid one to behold'),
(13, 1000, 'has much to be proud of'),
(14, 1500, 'has a brilliant future'),
(15, 2000, 'has a reputation beyond repute');

-- --------------------------------------------------------

--
-- Table structure for table `searchcloud`
--

CREATE TABLE IF NOT EXISTS `searchcloud` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `searchedfor` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `howmuch` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `searchedfor` (`searchedfor`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `searchcloud`
--


-- --------------------------------------------------------

--
-- Table structure for table `sitelog`
--

CREATE TABLE IF NOT EXISTS `sitelog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `added` int(11) NOT NULL,
  `txt` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `added` (`added`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `sitelog`
--


-- --------------------------------------------------------

--
-- Table structure for table `stylesheets`
--

CREATE TABLE IF NOT EXISTS `stylesheets` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uri` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `stylesheets`
--

INSERT INTO `stylesheets` (`id`, `uri`, `name`) VALUES
(1, '1.css', '(default)'),
(2, '2.css', 'Large text');

-- --------------------------------------------------------

--
-- Table structure for table `topics`
--

CREATE TABLE IF NOT EXISTS `topics` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `subject` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `locked` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `forumid` int(10) unsigned NOT NULL DEFAULT '0',
  `lastpost` int(10) unsigned NOT NULL DEFAULT '0',
  `sticky` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `views` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `subject` (`subject`),
  KEY `lastpost` (`lastpost`),
  KEY `locked_sticky` (`locked`,`sticky`),
  KEY `forumid` (`forumid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `topics`
--


-- --------------------------------------------------------

--
-- Table structure for table `torrents`
--

CREATE TABLE IF NOT EXISTS `torrents` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `info_hash` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `filename` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `save_as` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `search_text` text COLLATE utf8_unicode_ci NOT NULL,
  `descr` text COLLATE utf8_unicode_ci NOT NULL,
  `ori_descr` text COLLATE utf8_unicode_ci NOT NULL,
  `category` int(10) unsigned NOT NULL DEFAULT '0',
  `size` bigint(20) unsigned NOT NULL DEFAULT '0',
  `added` int(11) NOT NULL,
  `type` enum('single','multi') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'single',
  `numfiles` int(10) unsigned NOT NULL DEFAULT '0',
  `comments` int(10) unsigned NOT NULL DEFAULT '0',
  `views` int(10) unsigned NOT NULL DEFAULT '0',
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  `times_completed` int(10) unsigned NOT NULL DEFAULT '0',
  `leechers` int(10) unsigned NOT NULL DEFAULT '0',
  `seeders` int(10) unsigned NOT NULL DEFAULT '0',
  `last_action` int(11) NOT NULL,
  `visible` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'yes',
  `banned` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `owner` int(10) unsigned NOT NULL DEFAULT '0',
  `numratings` int(10) unsigned NOT NULL DEFAULT '0',
  `ratingsum` int(10) unsigned NOT NULL DEFAULT '0',
  `nfo` blob NOT NULL,
  `client_created_by` char(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'unknown',
  `poster` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT 'pic/noposter.jpg',
  PRIMARY KEY (`id`),
  UNIQUE KEY `info_hash` (`info_hash`),
  KEY `owner` (`owner`),
  KEY `visible` (`visible`),
  KEY `category_visible` (`category`,`visible`),
  FULLTEXT KEY `ft_search` (`search_text`,`ori_descr`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `torrents`
--


-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `passhash` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `secret` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `passkey` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `email` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `status` enum('pending','confirmed') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'pending',
  `added` int(11) NOT NULL,
  `last_login` int(11) NOT NULL,
  `last_access` int(11) NOT NULL,
  `editsecret` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `privacy` enum('strong','normal','low') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'normal',
  `stylesheet` int(10) DEFAULT '1',
  `info` text COLLATE utf8_unicode_ci,
  `acceptpms` enum('yes','friends','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'yes',
  `ip` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `class` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `language` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'en',
  `avatar` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `av_w` smallint(3) unsigned NOT NULL DEFAULT '0',
  `av_h` smallint(3) unsigned NOT NULL DEFAULT '0',
  `uploaded` bigint(20) unsigned NOT NULL DEFAULT '0',
  `downloaded` bigint(20) unsigned NOT NULL DEFAULT '0',
  `title` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `country` int(10) unsigned NOT NULL DEFAULT '0',
  `notifs` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `modcomment` text COLLATE utf8_unicode_ci NOT NULL,
  `enabled` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'yes',
  `avatars` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'yes',
  `donor` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `warned` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `warneduntil` int(11) NOT NULL DEFAULT '0',
  `torrentsperpage` int(3) unsigned NOT NULL DEFAULT '0',
  `topicsperpage` int(3) unsigned NOT NULL DEFAULT '0',
  `postsperpage` int(3) unsigned NOT NULL DEFAULT '0',
  `deletepms` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'yes',
  `savepms` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `reputation` int(10) NOT NULL DEFAULT '10',
  `time_offset` varchar(5) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `dst_in_use` tinyint(1) NOT NULL DEFAULT '0',
  `auto_correct_dst` tinyint(1) NOT NULL DEFAULT '1',
  `invites` int(10) unsigned NOT NULL DEFAULT '1',
  `invitedby` int(10) unsigned NOT NULL DEFAULT '0',
  `invite_rights` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'yes',
  `uploadpos` int(11) NOT NULL DEFAULT '1',
  `forumpost` int(11) NOT NULL DEFAULT '1',
  `downloadpos` int(11) NOT NULL DEFAULT '1',
  `chatpost` int(11) NOT NULL DEFAULT '1',
  `immunity` int(11) NOT NULL DEFAULT '0',
  `leechwarn` int(11) NOT NULL DEFAULT '0',
  `sendpmpos` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `ip` (`ip`),
  KEY `uploaded` (`uploaded`),
  KEY `downloaded` (`downloaded`),
  KEY `country` (`country`),
  KEY `last_access` (`last_access`),
  KEY `enabled` (`enabled`),
  KEY `warned` (`warned`),
  KEY `pkey` (`passkey`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `users`
--


