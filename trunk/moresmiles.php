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

// xLeech .::. More Smiles
// http://www.cyberfun.ro/
// http://xList.ro/
// http://xDnS.ro/
// http://xLeech.in/
// Modified By cybernet2u

// xLeech v1.2

// http://xleech-source.co.cc/
// https://xleech.svn.sourceforge.net/svnroot/xleech

require_once ("include/bittorrent.php");
require_once ("include/bbcode_functions.php");

dbconn(false);

$lang = array_merge( load_language('global') );

loggedinorreturn();
		$htmlout = '';
    $htmlout = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"
		\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
		<html xmlns='http://www.w3.org/1999/xhtml'>
		<head>
    <meta name='generator' content='TBDev.net' />
	  <meta name='MSSmartTagsPreventParsing' content='TRUE' />
		<title>More Smilies</title>
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
    function SmileIT(smile,form,text){
    window.opener.document.forms[form].elements[text].value = window.opener.document.forms[form].elements[text].value+' '+smile+' ';
    window.opener.document.forms[form].elements[text].focus();
    window.close();
    }
    </script>
    <table class='list' width='100%' cellpadding='1' cellspacing='1'>";
    $count='';
    while ((list($code, $url) = each($smilies))) {
    if ($count % 3 == 0)
    $htmlout .= " \n<tr>";
    $htmlout .= "\n\t<td class=\"list\" align=\"center\"><a href=\"javascript: SmileIT('" . str_replace("'", "\'", $code) . "','" . htmlspecialchars($_GET["form"]) . "','" . htmlspecialchars($_GET["text"]) . "')\"><img border='0' src='./pic/smilies/" . $url . "' alt='' /></a></td>";
    $count++;
    if ($count % 3 == 0)
    $htmlout .= "\n</tr>";
    }
    $htmlout .= "</tr></table><div align='center'><a href='javascript: window.close()'>[ Close Window ]</a></div></body></html>";

print $htmlout;
