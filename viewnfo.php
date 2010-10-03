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

// xLeech .::. view NFO
// http://www.cyberfun.ro/
// http://xList.ro/
// http://xDnS.ro/
// http://xLeech.in/
// Modified By cybernet2u

// xLeech v1.2

// http://xleech-source.co.cc/
// https://xleech.svn.sourceforge.net/svnroot/xleech

require ("include/bittorrent.php");
require ("include/user_functions.php");
require ("include/bbcode_functions.php");
dbconn(false);
loggedinorreturn();

    $lang = array_merge( load_language('global'), load_language('viewnfo') );
    
    $id = 0 + $_GET["id"];
    if ($CURUSER['class'] < UC_POWER_USER || !is_valid_id($id))
      die;

    $r = mysql_query("SELECT name, nfo FROM torrents WHERE id=$id") or sqlerr();
    $a = mysql_fetch_assoc($r) or die("{$lang['text_puke']}");
    $nfo = htmlspecialchars($a["nfo"]);
    $HTMLOUT = '';
    
    
    $HTMLOUT .= "<h1>{$lang['text_nfofor']}<a href='details.php?id=$id'>{$a['name']}</a></h1>\n";
    $HTMLOUT .= "<table border='1' cellspacing='0' cellpadding='5'><tr><td class='text'>\n";
    $HTMLOUT .= "<pre>" . format_urls(htmlentities($nfo, ENT_QUOTES, 'UTF-8')) . "</pre>\n";
    $HTMLOUT .= "</td></tr></table>\n";
    $HTMLOUT .= "<p align='center'>{$lang['text_forbest']}" .
      "<a href='http://{$_SERVER['HTTP_HOST']}/misc/linedraw.ttf'>{$lang['text_linedraw']}</a>{$lang['text_font']}</p>\n";
    
    print stdhead() . $HTMLOUT . stdfoot();
?>
