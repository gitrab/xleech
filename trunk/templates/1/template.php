<?php
function stdhead($title = "", $msgalert = true) {
    global $CURUSER, $TBDEV, $lang;

    if (!$TBDEV['site_online'])
      die("Site is down for maintenance, please check back again later... thanks<br />");

    //header("Content-Type: text/html; charset=iso-8859-1");
    //header("Pragma: No-cache");
    if ($title == "")
        $title = $TBDEV['site_name'] .(isset($_GET['tbv'])?" (".TBVERSION.")":'');
    else
        $title = $TBDEV['site_name'].(isset($_GET['tbv'])?" (".TBVERSION.")":''). " :: " . htmlspecialchars($title);
        
    if ($TBDEV['msg_alert'] && $msgalert && $CURUSER)
    {
      $res = mysql_query("SELECT COUNT(*) FROM messages WHERE receiver=" . $CURUSER["id"] . " && unread='yes'") or sqlerr(__FILE__,__LINE__);
      $arr = mysql_fetch_row($res);
      $unread = $arr[0];
    }

       $htmlout = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"
    \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
    <!-- *************************************************************************************** -->
    <!--                    KIDVISION UNIQUEPIXELS 1.0 | www.kidvision.me                        -->
    <!-- *************************************************************************************** -->
    <html xmlns='http://www.w3.org/1999/xhtml'>
    <head>
    <meta name='generator' content='TBDev.net' />
    <meta http-equiv='Content-Language' content='en-us' />
    <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
    <meta name='MSSmartTagsPreventParsing' content='TRUE' />
    <title>{$title}</title>
    <link rel='stylesheet' href='templates/1/1.css' type='text/css' />
    <script type='text/javascript' src='scripts/java_klappe.js'></script>
    </head>
    <body>
    <table width='950' cellspacing='0' cellpadding='0' style='background: transparent' align='center'>
    <tr>
    <td class='clear'>
    <div id='logostrip'>";
    if ($CURUSER) 
    { 
    $htmlout .= "
    <ul id='iconbar'>
    <li><a href='{$TBDEV['baseurl']}/index.php'>{$lang['gl_home']}</a></li>
    <li><a href='{$TBDEV['baseurl']}/browse.php'>{$lang['gl_browse']}</a></li>
    <li><a href='{$TBDEV['baseurl']}/search.php'>{$lang['gl_search']}</a></li>
    <li><a href='{$TBDEV['baseurl']}/upload.php'>{$lang['gl_upload']}</a></li>
    <li><a href='{$TBDEV['baseurl']}/forums.php'>{$lang['gl_forums']}</a></li>
    <li><a href='{$TBDEV['baseurl']}/rules.php'>{$lang['gl_rules']}</a></li>
    <li><a href='{$TBDEV['baseurl']}/credits.php'>CREDITS</a></li>
    <li><a href='{$TBDEV['baseurl']}/my.php'>{$lang['gl_profile']}</a></li>
    ";
        if (get_user_class() >= UC_POWER_USER)
        {
    $htmlout .= "
    <li><a href='{$TBDEV['baseurl']}/staff.php'>{$lang['gl_staff']}</a></li>";
    }
    if( $CURUSER['class'] >= UC_MODERATOR )
    $htmlout .= "
    <li><a href='{$TBDEV['baseurl']}/admin.php'>{$lang['gl_admin']}</a></li>";
    $htmlout .= "</ul><br />"; 
    }     
    $htmlout .= StatusBar(); 
    $htmlout .="</div></td></tr></table><br /><br />
    <table class='mainouter' width='100%' border='0' cellspacing='0' cellpadding='10'>
    <tr><td align='center' class='outer' style='padding-top: 20px; padding-bottom: 20px'>";
    
   if ($TBDEV['msg_alert'] && isset($unread) && !empty($unread))
   {
   $htmlout .= "<table border='0' cellspacing='0' cellpadding='10' bgcolor='red'>
   <tr><td style='padding: 10px; background: #890537'>\n
   <b><a href='{$TBDEV['baseurl']}/messages.php'><font color='white'>".sprintf($lang['gl_msg_alert'], $unread) . ($unread > 1 ? "s" : "") . "!</font></a></b>
   </td></tr></table><br />\n";
   }
   return $htmlout;
   } // 

function stdfoot() {
  global $TBDEV;
  return 
  base64_decode("PHAgYWxpZ249J2NlbnRlcic+PGEgaHJlZj0naHR0cDovL3ZhbGlkYXRvci53My5vcmcnPjxpbWcgc3JjPSdwaWMvbG9naW4veGh0bWxfdmFsaWQucG5nJyBhbHQ9J1hodG1sIHZhbGlkJy8+PC9hPiZuYnNwOyZuYnNwOzxhIGhyZWY9J2h0dHA6Ly9qaWdzYXcudzMub3JnL2Nzcy12YWxpZGF0b3IvY2hlY2svJz48aW1nIHNyYz0ncGljL2xvZ2luL2Nzc192YWxpZC5wbmcnIGFsdD0nWGh0bWwgdmFsaWQnIC8+PC9hPiZuYnNwOyZuYnNwOzxhIGhyZWY9J2h0dHA6Ly90YmRldi5uZXQnPjxpbWcgc3JjPSdwaWMvbG9naW4vdGJkZXZfcG93ZXIucG5nJyBhbHQ9J1RCREVWJy8+PC9hPiZuYnNwOyZuYnNwOzxhIGhyZWY9J2h0dHA6Ly9raWR2aXNpb24ubWUnPjxpbWcgc3JjPSdwaWMvbG9naW4va2lkdmlzaW9uX2Rlc2lnbi5wbmcnIGFsdD0nRGVzaWduJy8+PC9hPg==");
  $htmlout .="
  </td></tr></table>\n
    </body></html>\n";
}

function stdmsg($heading, $text)
{
    $htmlout = "<table class='main' width='750' border='0' cellpadding='0' cellspacing='0'>
    <tr><td class='embedded'>\n";
    
    if ($heading)
      $htmlout .= "<h2>$heading</h2>\n";
    
    $htmlout .= "<table width='100%' border='1' cellspacing='0' cellpadding='10'><tr><td class='text'>\n";
    $htmlout .= "{$text}</td></tr></table></td></tr></table>\n";
  
    return $htmlout;
}

function StatusBar() {

	global $CURUSER, $TBDEV, $lang;
	
	if (!$CURUSER)
		return "<tr><td colspan='2'>Yeah Yeah!</td></tr>";


	$upped = mksize($CURUSER['uploaded']);
	
	$downed = mksize($CURUSER['downloaded']);
	
	$ratio = $CURUSER['downloaded'] > 0 ? $CURUSER['uploaded'] / $CURUSER['downloaded'] : 0;
	
	$ratio = number_format($ratio, 2);

	$IsDonor = '';
	if ($CURUSER['donor'] == "yes")
	
	$IsDonor = "<img src='pic/theme/star.png' alt='donor' title='donor' />";


	$warn = '';
	if ($CURUSER['warned'] == "yes")
	
	$warn = "<img src='pic/theme/warn.png' alt='warned' title='warned' />";
	
	$res1 = @mysql_query("SELECT COUNT(*) FROM messages WHERE receiver=" . $CURUSER["id"] . " AND unread='yes'") or sqlerr(__LINE__,__FILE__);
	
	$arr1 = mysql_fetch_row($res1);
	
	$unread = $arr1[0];
	
	$inbox = ($unread == 1 ? "$unread&nbsp;{$lang['gl_msg_singular']}" : "$unread&nbsp;{$lang['gl_msg_plural']}");

	
	$res2 = @mysql_query("SELECT seeder, COUNT(*) AS pCount FROM peers WHERE userid=".$CURUSER['id']." GROUP BY seeder") or sqlerr(__LINE__,__FILE__);
	
	$seedleech = array('yes' => '0', 'no' => '0');
	
	while( $row = mysql_fetch_assoc($res2) ) {
		if($row['seeder'] == 'yes')
			$seedleech['yes'] = $row['pCount'];
		else
			$seedleech['no'] = $row['pCount'];
		
	}
	
/////////////// REP SYSTEM /////////////
//$CURUSER['reputation'] = 49;

	$member_reputation = get_reputation($CURUSER, 1);
////////////// REP SYSTEM END //////////

	$StatusBar = '';
		$StatusBar = 
		"<div id='statusbar'>
        {$lang['gl_msg_welcome']}, <a href='userdetails.php?id={$CURUSER['id']}'>{$CURUSER['username']}</a>
		$IsDonor$warn&nbsp;|&nbsp;[<a href='logout.php'>logout</a>]&nbsp;&nbsp;|&nbsp;&nbsp;Invites:&nbsp;<a href='{$TBDEV['baseurl']}/invite.php'>{$CURUSER['invites']}</a>
		&nbsp;&nbsp;|&nbsp;&nbsp;{$lang['gl_uploaded']}: {$upped}<!--&nbsp;&nbsp;|&nbsp;&nbsp;{$lang['gl_ratio']}:{$ratio}-->&nbsp;&nbsp;|&nbsp;&nbsp;<a href='messages.php'>$inbox</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href='http://twitter.com/kidvisionme' target='_blank'><img src='templates/1/pic/twitter.png' alt='Twitter' /></a>&nbsp;&nbsp;<a href='http://www.facebook.com/kidvisionme' target='_blank'><img src='templates/1/pic/facebook.png' alt='Facebook' /></a>
  </div>";
	
	return $StatusBar;

}

?>
