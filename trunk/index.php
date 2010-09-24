<?php
/*
+------------------------------------------------
| TBDev.net BitTorrent Tracker PHP
| =============================================
| by CoLdFuSiOn
| (c) 2003 - 2009 TBDev.Net
| http://www.tbdev.net
| =============================================
| svn: http://sourceforge.net/projects/tbdevnet/
| Licence Info: GPL
+------------------------------------------------
| $Date$
| $Revision$
| $Author$
| $URL$
+------------------------------------------------
| Kidvision style
*/
ob_start("ob_gzhandler");

require_once "include/bittorrent.php";
require_once "include/user_functions.php";

dbconn(true);

loggedinorreturn();

	$lang = array_merge( load_language('global'), load_language('index') );
	//$lang = ;
	
	$HTMLOUT = '';

$a = @mysql_fetch_assoc(@mysql_query("SELECT id,username FROM users WHERE status='confirmed' ORDER BY id DESC LIMIT 1")) or die(mysql_error());
if ($CURUSER)
 $latestuser = "<a href='userdetails.php?id=" . $a["id"] . "'>" . $a["username"] . "</a>";
else
 $latestuser = $a['username'];

	$registered = number_format(get_row_count("users"));
	//$unverified = number_format(get_row_count("users", "WHERE status='pending'"));
	$torrents = number_format(get_row_count("torrents"));
	//$dead = number_format(get_row_count("torrents", "WHERE visible='no'"));

	$r = mysql_query("SELECT value_u FROM avps WHERE arg='seeders'") or sqlerr(__FILE__, __LINE__);
	$a = mysql_fetch_row($r);
	$seeders = 0 + $a[0];
	$r = mysql_query("SELECT value_u FROM avps WHERE arg='leechers'") or sqlerr(__FILE__, __LINE__);
	$a = mysql_fetch_row($r);
	$leechers = 0 + $a[0];
	if ($leechers == 0)
 	$ratio = 0;
	else
 	$ratio = round($seeders / $leechers * 100);
	$peers = number_format($seeders + $leechers);
	$seeders = number_format($seeders);
	$leechers = number_format($leechers);


	//stdhead();

	$adminbutton = '';
	
	if (get_user_class() >= UC_ADMINISTRATOR)
 	$adminbutton = "&nbsp;&nbsp;<span style='color:#191919; font-size:10px;'><a href='admin.php?action=news'>[Add]</a></span>\n";
 	
	$HTMLOUT .= "<div style='text-align:left;width:950px;border:0px;padding:5px;'>
	<div id='headindex'>{$lang['news_title']}{$adminbutton}</div>
";
 	
	$res = mysql_query("SELECT * FROM news WHERE added + ( 3600 *24 *45 ) >
					".time()." ORDER BY added DESC LIMIT 10") or sqlerr(__FILE__, __LINE__);
					
	if (mysql_num_rows($res) > 0)
	{
 	require_once "include/bbcode_functions.php";

 	$button = "";
 	
 	while($array = mysql_fetch_assoc($res))
 	{
 	if (get_user_class() >= UC_ADMINISTRATOR)
 	{
 	$button = "<span style='color:#191919; font-size:10px;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='admin.php?action=news&amp;mode=edit&amp;newsid={$array['id']}'>[{$lang['news_edit']}]</a>&nbsp;&nbsp;<a href='admin.php?action=news&amp;mode=delete&amp;newsid={$array['id']}'>[{$lang['news_delete']}]</a></span\n";
 	}
 	
		$HTMLOUT .= "<div id='headlineindex'>{$array['headline']}{$button}</div>
	 	<div id='newshold'>".format_comment($array['body'])."</div>

";
 	
 	}
 	
	}

	$HTMLOUT .= "</div>
\n";

 if (get_user_class() >= UC_POWER_USER) {
 	
// 09 poster mod - UNCOMMENT IF YOU HAVE THIS
/* $query = "SELECT id, name, poster FROM torrents WHERE poster <> '' ORDER BY added DESC limit 15";
	$result = mysql_query( $query );
	$num = mysql_num_rows( $result );
	// count rows
	$HTMLOUT .="<script type='text/javascript' src='{$TBDEV['baseurl']}/scripts/scroll.js'></script>";
	$HTMLOUT .= "<div><div id='headindex'>{$lang['index_latest']}</div>
";
	$HTMLOUT .="<div style=\"overflow:hidden\">
	<div id=\"marqueecontainer\" onmouseover=\"copyspeed=pausespeed\" onmouseout=\"copyspeed=marqueespeed\"> 
	<span id=\"vmarquee\" style=\"position: absolute; width: 100%;\"><span style=\"white-space: nowrap;\">";
	$i = 20;
	while ( $row = mysql_fetch_assoc( $result ) ) {
 	$id = (int) $row['id'];
 	$name = htmlspecialchars( $row['name'] );
 	$poster = htmlspecialchars( $row['poster'] );
 	$name = str_replace( '_', ' ' , $name );
 	$name = str_replace( '.', ' ' , $name );
 	$name = substr( $name, 0, 50 );
 	if ( $i == 0 )
 	$HTMLOUT .= "</span></span><span id=\"vmarquee2\" style=\"position: absolute; width: 98%;\"></span></div></div><div style=\"overflow:hidden\">
 	<div id=\"marqueecontainer\" onmouseover=\"copyspeed=pausespeed\" onmouseout=\"copyspeed=marqueespeed\"> <span id=\"vmarquee\" style=\"position: absolute; width: 98%;\"><span style=\"white-space: nowrap;\">";
 	$HTMLOUT .= "<a href='{$TBDEV['baseurl']}/details.php?id=$id'><img src='" . htmlspecialchars( $poster ) . "' alt='$name' title='$name' width='100' height='120' border='0' /></a>";
 	$i++;
	}
	$HTMLOUT .= "</span></span><span id=\"vmarquee2\" style=\"position: absolute; width: 98%;\"></span></div></div></div>
\n";
	//== end 09 poster mod
 */
 // === TbDev 09 Shoutbox USE SHOUT UNCOMMENT THIS
/* if ($CURUSER['show_shout'] === "yes") {
 $commandbutton = '';
 $refreshbutton = '';
 $smilebutton = '';
 if ($CURUSER['class'] >= UC_ADMINISTRATOR){
 $commandbutton = "<a href=\"javascript:popUp('shoutbox_commands.php')\">{$lang['index_shoutbox_commands']}</a>\n";}
 $refreshbutton = "<a href='shoutbox.php' target='sbox'>{$lang['index_shoutbox_refresh']}</a>\n";
 $smilebutton = "<a href=\"javascript:PopMoreSmiles('shbox','shbox_text')\">{$lang['index_shoutbox_smilies']}</a>\n";
 $HTMLOUT .= "<form action='shoutbox.php' method='get' target='sbox' name='shbox' onsubmit='mysubmit()' />
 <div><div id='headindex'>{$lang['index_shout']} <span style='color:#fff; font-size:10px;'>[<a href='shoutbox.php?show_shout=1&amp;show=no'>{$lang['index_shoutbox_close']}</a>]</span></div>

 
 <iframe src='shoutbox.php' width='950px' height='200px' frameborder='0' name='sbox' marginwidth='0' marginheight='0'></iframe>
 <br/>
 <br/>
 <div align='center'>
 <script type=\"text/javascript\" src=\"scripts/shout.js\"></script> 
 <input type='text' maxlength='180' name='shbox_text' size='100' />
 <input class='button' type='submit' value='{$lang['index_shoutbox_send']}' />
 <input type='hidden' name='sent' value='yes' />
 

 <a href=\"javascript:SmileIT(':-)','shbox','shbox_text')\"><img border='0' src='{$TBDEV['baseurl']}/pic/smilies/smile1.gif' alt='Smile' title='Smile' /></a> 
 <a href=\"javascript:SmileIT(':smile:','shbox','shbox_text')\"><img border='0' src='{$TBDEV['baseurl']}/pic/smilies/smile2.gif' alt='Smiling' title='Smiling' /></a> 
 <a href=\"javascript:SmileIT(':-D','shbox','shbox_text')\"><img border='0' src='{$TBDEV['baseurl']}/pic/smilies/grin.gif' alt='Grin' title='Grin' /></a> 
 <a href=\"javascript:SmileIT(':lol:','shbox','shbox_text')\"><img border='0' src='{$TBDEV['baseurl']}/pic/smilies/laugh.gif' alt='Laughing' title='Laughing' /></a> 
 <a href=\"javascript:SmileIT(':w00t:','shbox','shbox_text')\"><img border='0' src='{$TBDEV['baseurl']}/pic/smilies/w00t.gif' alt='W00t' title='W00t' /></a> 
 <a href=\"javascript:SmileIT(':blum:','shbox','shbox_text')\"><img border='0' src='{$TBDEV['baseurl']}/pic/smilies/blum.gif' alt='Rasp' title='Rasp' /></a> 
 <a href=\"javascript:SmileIT(';-)','shbox','shbox_text')\"><img border='0' src='{$TBDEV['baseurl']}/pic/smilies/wink.gif' alt='Wink' title='Wink' /></a> 
 <a href=\"javascript:SmileIT(':devil:','shbox','shbox_text')\"><img border='0' src='{$TBDEV['baseurl']}/pic/smilies/devil.gif' alt='Devil' title='Devil' /></a> 
 <a href=\"javascript:SmileIT(':yawn:','shbox','shbox_text')\"><img border='0' src='{$TBDEV['baseurl']}/pic/smilies/yawn.gif' alt='Yawn' title='Yawn' /></a> 
 <a href=\"javascript:SmileIT(':-/','shbox','shbox_text')\"><img border='0' src='{$TBDEV['baseurl']}/pic/smilies/confused.gif' alt='Confused' title='Confused' /></a> 
 <a href=\"javascript:SmileIT(':o)','shbox','shbox_text')\"><img border='0' src='{$TBDEV['baseurl']}/pic/smilies/clown.gif' alt='Clown' title='Clown' /></a> 
 <a href=\"javascript:SmileIT(':innocent:','shbox','shbox_text')\"><img border='0' src='{$TBDEV['baseurl']}/pic/smilies/innocent.gif' alt='Innocent' title='innocent' /></a> 
 <a href=\"javascript:SmileIT(':whistle:','shbox','shbox_text')\"><img border='0' src='{$TBDEV['baseurl']}/pic/smilies/whistle.gif' alt='Whistle' title='Whistle' /></a> 
 <a href=\"javascript:SmileIT(':unsure:','shbox','shbox_text')\"><img border='0' src='{$TBDEV['baseurl']}/pic/smilies/unsure.gif' alt='Unsure' title='Unsure' /></a> 
 <a href=\"javascript:SmileIT(':blush:','shbox','shbox_text')\"><img border='0' src='{$TBDEV['baseurl']}/pic/smilies/blush.gif' alt='Blush' title='Blush' /></a> 
 <a href=\"javascript:SmileIT(':hmm:','shbox','shbox_text')\"><img border='0' src='{$TBDEV['baseurl']}/pic/smilies/hmm.gif' alt='Hmm' title='Hmm' /></a> 
 <a href=\"javascript:SmileIT(':hmmm:','shbox','shbox_text')\"><img border='0' src='{$TBDEV['baseurl']}/pic/smilies/hmmm.gif' alt='Hmmm' title='Hmmm' /></a> 
 <a href=\"javascript:SmileIT(':huh:','shbox','shbox_text')\"><img border='0' src='{$TBDEV['baseurl']}/pic/smilies/huh.gif' alt='Huh' title='Huh' /></a> 
 <a href=\"javascript:SmileIT(':look:','shbox','shbox_text')\"><img border='0' src='{$TBDEV['baseurl']}/pic/smilies/look.gif' alt='Look' title='Look' /></a> 
 <a href=\"javascript:SmileIT(':rolleyes:','shbox','shbox_text')\"><img border='0' src='{$TBDEV['baseurl']}/pic/smilies/rolleyes.gif' alt='Roll Eyes' title='Roll Eyes' /></a> 
 <a href=\"javascript:SmileIT(':kiss:','shbox','shbox_text')\"><img border='0' src='{$TBDEV['baseurl']}/pic/smilies/kiss.gif' alt='Kiss' title='Kiss' /></a> 
 <a href=\"javascript:SmileIT(':blink:','shbox','shbox_text')\"><img border='0' src='{$TBDEV['baseurl']}/pic/smilies/blink.gif' alt='Blink' title='Blink' /></a> 
 <a href=\"javascript:SmileIT(':baby:','shbox','shbox_text')\"><img border='0' src='{$TBDEV['baseurl']}/pic/smilies/baby.gif' alt='Baby' title='Baby' /></a><br/>
 </div>
 <div id='shoutbox'>{$refreshbutton} {$smilebutton} {$commandbutton}</div>
 
 
 </div>
 
\n";
 }
 if ($CURUSER['show_shout'] === "no") {
 $HTMLOUT .="<div id='headindex'>{$lang['index_shoutbox']} <span style='color:#fff; font-size:10px;'>[<a href='{$TBDEV['baseurl']}/shoutbox.php?show_shout=1&amp;show=yes'>{$lang['index_shoutbox_open']}</a>]</span></div></div>

";
 }
 //==end 09 shoutbox
	*/
	/* UN COMMENT TO USE ACTIVE USERS ON INDEX
	$file = "./cache/active.txt";
$expire = 30; // 30 seconds
if (file_exists($file) && filemtime($file) > (time() - $expire)) {
$active3 = unserialize(file_get_contents($file));
} else {
$dt = sqlesc(time() - 180);
$active1 = mysql_query("SELECT id, username, class, warned, donor FROM users WHERE last_access >= $dt ORDER BY class DESC") or sqlerr(__FILE__, __LINE__);
	while ($active2 = mysql_fetch_assoc($active1)) {
 	$active3[] = $active2;
	}
	$OUTPUT = serialize($active3);
	$fp = fopen($file, "w");
	fputs($fp, $OUTPUT);
	fclose($fp);
} // end else
$activeusers = "";
if (is_array($active3))
foreach ($active3 as $arr) {
	if ($activeusers) $activeusers .= ",\n";
	$activeusers .= "<span style=\"white-space: nowrap;\">"; 
	$arr["username"] = "<font color='#" . get_user_class_color($arr['class']) . "'> " . htmlspecialchars($arr['username']) . "</font>";
	$donator = $arr["donor"] === "yes";
	$warned = $arr["warned"] === "yes";

	if ($CURUSER)
 	$activeusers .= "<a href='userdetails.php?id={$arr["id"]}'><b>{$arr["username"]}</b></a>";
	else
 	$activeusers .= "<b>{$arr["username"]}</b>";
	if ($donator)
 	$activeusers .= "<img src='{$TBDEV['pic_base_url']}star.gif' alt='Donated' />";
	if ($warned)
 	$activeusers .= "<img src='{$TBDEV['pic_base_url']}warned.gif' alt='Warned' />";
	$activeusers .= "</span>";
}

if (!$activeusers)
	$activeusers = "{$lang['index_noactive']}";
	
	$HTMLOUT .= "<div><div id='headindex'>{$lang['index_active']}</div>
";
 $HTMLOUT .="<table id='activeindex'>
		<tr>
		<td id='activeindex'>{$activeusers}</td>";
 $HTMLOUT .="</tr></table></div>

";

 	$HTMLOUT .="<div id='activeindex2'><span style='color:#4080B0'>Sysop</span> | <span style='color:#B000B0'>Administrator</span> | <span style='color:#FE2E2E'>Moderator</span> | <span style='color:#256903'>Code-Team</span> | <span style='color:#04ab27'>Graphic-Team</span> | <span style='color:#0000FF'>Uploader</span> | <span style='color:#009F00'>VIP</span> | <span style='color:#f9a200'>Power User</span> | <span style='color:#8E35EF'>User</span> | <span style='color:#b1b1b1'>Warned <img src='/pic/warned.gif' /></span></div>";
*/
	$HTMLOUT .= "

<div><div id='headindex'>{$lang['stats_title']}</div>

	
 	<table align='center' class='statindex' border='0' cellspacing='5' cellpadding='5'>
 	<tr>
 	<td class='rowhead'>{$lang['stats_regusers']}</td><td align='right'>{$registered}</td>
 	<td class='rowhead'>{$lang['stats_torrents']}</td><td align='right'>{$torrents}</td>
 	";
		}
	if (isset($peers)) 
	{ 
 	$HTMLOUT .= "<td class='rowhead'>{$lang['stats_peers']}</td><td align='right'>{$peers}</td>
 	<td class='rowhead'>{$lang['stats_seed']}</td><td align='right'>{$seeders}</td>
 	<td class='rowhead'>{$lang['stats_leech']}</td><td align='right'>{$leechers}</td>
 	<td class='rowhead'>{$lang['stats_sl_ratio']}</td><td align='right'>{$ratio}</td></tr>";

	} 

 	$HTMLOUT .= "</table>
 	</div>";
	
	$HTMLOUT .= "";
	$HTMLOUT .= "
<div><font class='small'>Welcome to our newest member, <b>$latestuser</b>!</font></div>


\n"; 

$HTMLOUT .= "<div><div id='headindex'>{$lang['index_dis']}</div>
";
$HTMLOUT .= "<div><div id='newshold'>{$lang['foot_disclaimer']}</div>
";

///////////////////////////// FINAL OUTPUT //////////////////////

	print stdhead('Home') . $HTMLOUT . stdfoot();
?>
