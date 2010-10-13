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

// xLeech .::. video formats
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
require ("include/user_functions.php");

dbconn(false);

    $lang = array_merge( load_language('global'), load_language('videoformats') );
    
    $HTMLOUT = '';
    
    $HTMLOUT .= "<table class='main' width='750' border='0' cellspacing='0' cellpadding='0'><tr><td class='embedded'>
    {$lang['videoformats_body']}
    </td></tr></table>
    </td></tr></table>
    <br />";

    print stdhead("{$lang['videoformats_header']}") . $HTMLOUT . stdfoot();
?>
