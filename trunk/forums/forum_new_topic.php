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

// xLeech .::. forum new topic
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

if ( ! defined( 'IN_TBDEV_FORUM' ) )
{
	print "{$lang['forum_new_topic_access']}";
	exit();
}


    $forumid = (int)$_GET["forumid"];

    if (!is_valid_id($forumid))
      header("Location: {$TBDEV['baseurl']}/forums.php");


    $HTMLOUT = stdhead("{$lang['forum_new_topic_newtopic']}");

    $HTMLOUT .= begin_main_frame();

    $HTMLOUT .= insert_compose_frame($forumid);

    $HTMLOUT .= end_main_frame();

    $HTMLOUT .= stdfoot();
    
    print $HTMLOUT;

    die;

?>
