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
|  
|  $Authors = pdq, putyn, sir_snugglebunny, Bigjoos for 09
+------------------------------------------------
*/

// CyBerFuN.ro & xList.ro & xLeech.in & xDNS.ro

// xLeech .::. shoutbox commands
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

require_once ("include/bittorrent.php");

dbconn(false);	

loggedinorreturn();

$lang = array_merge( load_language('global'));

if ($CURUSER['class'] < UC_MODERATOR)
die();

    $htmlout = '';
    $htmlout = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"
		\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
		<html xmlns='http://www.w3.org/1999/xhtml'>
		<head>
    		<meta name='generator' content='TBDev.net' />
	  <meta name='MSSmartTagsPreventParsing' content='TRUE' />
		<title>Shoutbox Commands</title>
    <link rel='stylesheet' href='templates/1/1.css' type='text/css' />
    </head>
    <body>
<!-- Piwik -->
<script type=\"text/javascript\">
var pkBaseURL = ((\"https:\" == document.location.protocol) ? \"https://stats.xdns.ro/\" : \"http://stats.xdns.ro/\");
document.write(unescape(\"%3Cscript src='\" + pkBaseURL + \"piwik.js' type='text/javascript'%3E%3C/script%3E\"));
</script><script type=\"text/javascript\">
try {
var piwikTracker = Piwik.getTracker(pkBaseURL + \"piwik.php\", 15);
piwikTracker.trackPageView();
piwikTracker.enableLinkTracking();
} catch( err ) {}
</script><noscript><p><img src=\"http://stats.xdns.ro/piwik.php?idsite=15\" style=\"border:0\" alt=\"\" /></p></noscript>
<!-- End Piwik Tag -->
<script type=\"text/javascript\">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-18179445-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
    <script type='text/javascript'>
    function command(command,form,text){
    window.opener.document.forms[form].elements[text].value = window.opener.document.forms[form].elements[text].value+command+' ';
    window.opener.document.forms[form].elements[text].focus();
    window.close();
    }
    </script>
  <table class='list' width='100%' cellpadding='1' cellspacing='1'>
  <tr>
  <td align='center'><b>empty</b>. To use type /EMPTY [username here without the brackets]</td>
	<td align='center'><b>gag</b>. To use type /GAG [username here without the brackets]</td>
	</tr>
	<tr>
	<td align='center'><b><input type='text' size='20' value='/EMPTY' onclick=\"command('/EMPTY','shbox','shbox_text')\" /></b></td>
	<td align='center'><b><input type='text' size='20' value='/GAG' onclick=\"command('/GAG','shbox','shbox_text')\" /></b></td>
	</tr>
  <tr>
	<td align='center'><b>ungag</b>. To use type /UNGAG [username here without the brackets]</td>
	<td align='center'><b>disable</b>. To use type /DISABLE [username here without the brackets]</td>
	</tr>
	<tr>
	<td align='center'><b><input type='text' size='20' value='/UNGAG' onclick=\"command('/UNGAG','shbox','shbox_text')\" /></b></td>
	<td align='center'><b><input type='text' size='20' value='/DISABLE' onclick=\"command('/DISABLE','shbox','shbox_text')\" /></b></td>
	</tr>
	<tr>
	<td align='center'><b>enable</b>. To use type /ENABLE [username here without the brackets]</td>
	<td align='center'><b>warn</b>. To use type /WARN [username here without the brackets]</td>
	</tr>
	<tr>
	<td align='center'><b><input type='text' size='20' value='/ENABLE' onclick=\"command('/ENABLE','shbox','shbox_text')\" /></b></td>
	<td align='center'><b><input type='text' size='20' value='/WARN' onclick=\"command('/WARN','shbox','shbox_text')\" /></b></td>
	</tr>
	<tr>
	<td align='center'><b>unwarn</b>. To use type /UNWARN [username here without the brackets]</td>
  <td align='center'><b>System</b>. To use type /System [text here without the brackets]</td>
	</tr>
	<tr>
	<td align='center'><b><input type='text' size='20' value='/UNWARN' onclick=\"command('/UNWARN','shbox','shbox_text')\" /></b></td>
	<td align='center'><b><input type='text' size='20' value='/System' onclick=\"command('/System','shbox','shbox_text')\" /></b></td>
	</tr>
	<tr>
	<td align='center'><b>private</b>. To use type /private [username here without the brackets] then rest of your text</td>
	</tr>
	<tr>
	<td align='center'><b><input type='text' size='20' value='/private' onclick=\"command('/private','shbox','shbox_text')\" /></b></td>
	</tr></table><br /><div align='center'><a class='altlink' href='javascript: window.close()'><b>[ Close window ]</b></a>\n</div></body></html>";
	
print $htmlout;
