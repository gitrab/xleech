<?php
/*
+------------------------------------------------
|   TBDev.net BitTorrent Tracker PHP
|   =============================================
|   by CoLdFuSiOn
|   (c) 2003 - 2009 TBDev.Net
|   http://www.tbdev.net
|   =============================================
|   svn: http://sourceforge.net/projects/tbdevnet/
|   Licence Info: GPL
+------------------------------------------------
|   $Date$
|   $Revision$
|   $Author$
|   $URL$
+------------------------------------------------
*/

// CyBerFuN.ro & xList.ro & xLeech.in & xDNS.ro

// xLeech .::. admin
// http://www.cyberfun.ro/
// http://xList.ro/
// http://xDnS.ro/
// http://xLeech.in/
// Modified By cybernet2u

// xLeech v1.2

// http://xleech-source.co.cc/
// https://xleech.svn.sourceforge.net/svnroot/xleech
// http://sourceforge.net/projects/xleech/
// http://xleech.sourceforge.net/

    define('IN_TBDEV_ADMIN', TRUE);

    require_once "include/bittorrent.php";
    require_once "include/user_functions.php";

    dbconn(false);

    loggedinorreturn();
    
    $lang = array_merge( load_language('global'), load_language('admin') );
  
    if ($CURUSER['class'] < UC_MODERATOR)
      stderr("{$lang['admin_user_error']}", "{$lang['admin_unexpected']}");
  
  
    $action = isset($_GET["action"]) ? $_GET["action"] : '';
    $forum_pic_url = $TBDEV['pic_base_url'] . 'forumicons/';
  
    define( 'F_IMAGES', $TBDEV['pic_base_url'] . 'forumicons');
    define( 'POST_ICONS', F_IMAGES.'/post_icons');
    
    $ad_actions = array('bans'            => 'bans', 
                        'adduser'         => 'adduser', 
                        'stats'           => 'stats', 
                        'delacct'         => 'delacct', 
                        'testip'          => 'testip', 
                        'usersearch'      => 'usersearch', 
                        'mysql_overview'  => 'mysql_overview', 
                        'mysql_stats'     => 'mysql_stats', 
                        'categories'      => 'categories', 
                        'newusers'        => 'newusers', 
                        'resetpassword'   => 'resetpassword',
                        'docleanup'       => 'docleanup',
                        'log'             => 'log',
                        'news'            => 'news',
			'freeleech'       => 'freeleech',
			'freeslots'       => 'freeslots',
			'freeusers'       => 'freeusers',
                        'forummanage'     => 'forummanage',
                        'cheaters'        => 'cheaters'
                        );
    
    if( in_array($action, $ad_actions) AND file_exists( "admin/{$ad_actions[ $action ]}.php" ) )
    {
      require_once "admin/{$ad_actions[ $action ]}.php";
    }
    else
    {
      require_once "admin/index.php";
    }
    
?>
