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

// xLeech .::. smilies
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

require ("include/bittorrent.php");
require_once ("include/user_functions.php");
require_once ("include/html_functions.php");
require_once ("include/emoticons.php");

dbconn(false);
loggedinorreturn();

    $lang = load_language('global');
    
    $HTMLOUT = stdhead();
    $HTMLOUT .= begin_main_frame();
    $HTMLOUT .= insert_smilies_frame();
    $HTMLOUT .= end_main_frame();
    $HTMLOUT .= stdfoot();
    print $HTMLOUT ;

?>