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
require_once "include/bittorrent.php";
require_once "include/user_functions.php";
require_once "include/html_functions.php";
require_once "include/bbcode_functions.php";

dbconn(false);

loggedinorreturn();

$lang = array_merge( load_language('global'), load_language('userdetails') );

function maketable($res)
    {
      global $TBDEV, $lang;
      
      $htmlout = '';
      
      $htmlout .= "<table class='main' border='1' cellspacing='0' cellpadding='5'>" .
        "<tr><td class='colhead' align='center'>{$lang['userdetails_type']}</td><td class='colhead'>{$lang['userdetails_name']}</td><td class='colhead' align='center'>{$lang['userdetails_ttl']}</td><td class='colhead' align='center'>{$lang['userdetails_size']}</td><td class='colhead' align='right'>{$lang['userdetails_se']}</td><td class='colhead' align='right'>{$lang['userdetails_le']}</td><td class='colhead' align='center'>{$lang['userdetails_upl']}</td>\n" .
        "<td class='colhead' align='center'>{$lang['userdetails_downl']}</td><td class='colhead' align='center'>{$lang['userdetails_ratio']}</td></tr>\n";
      foreach ($res as $arr)
      {
        if ($arr["downloaded"] > 0)
        {
          $ratio = number_format($arr["uploaded"] / $arr["downloaded"], 3);
          $ratio = "<font color='" . get_ratio_color($ratio) . "'>$ratio</font>";
        }
        else
          if ($arr["uploaded"] > 0)
            $ratio = "{$lang['userdetails_inf']}";
          else
            $ratio = "---";
      $catimage = "{$TBDEV['pic_base_url']}caticons/{$arr['image']}";
      $catname = htmlspecialchars($arr["catname"]);
      $catimage = "<img src=\"".htmlspecialchars($catimage) ."\" title=\"$catname\" alt=\"$catname\" width='42' height='42' />";
      $ttl = (28*24) - floor((time() - $arr["added"]) / 3600);
      if ($ttl == 1) $ttl .= "<br />{$lang['userdetails_hour']}"; else $ttl .= "<br />{$lang['userdetails_hours']}";
      $size = str_replace(" ", "<br />", mksize($arr["size"]));
      $uploaded = str_replace(" ", "<br />", mksize($arr["uploaded"]));
      $downloaded = str_replace(" ", "<br />", mksize($arr["downloaded"]));
      $seeders = number_format($arr["seeders"]);
      $leechers = number_format($arr["leechers"]);
        $htmlout .= "<tr><td style='padding: 0px'>$catimage</td>\n" .
        "<td><a href='details.php?id=$arr[torrent]&amp;hit=1'><b>" . htmlspecialchars($arr["torrentname"]) .
        "</b></a></td><td align='center'>$ttl</td><td align='center'>$size</td><td align='right'>$seeders</td><td align='right'>$leechers</td><td align='center'>$uploaded</td>\n" .
        "<td align='center'>$downloaded</td><td align='center'>$ratio</td></tr>\n";
      }
      $htmlout .= "</table>\n";
      return $htmlout;
    }

    $id = 0 + $_GET["id"];

    if (!is_valid_id($id))
      stderr("{$lang['userdetails_error']}", "{$lang['userdetails_bad_id']}");
    
    
    
    $r = @mysql_query("SELECT * FROM users WHERE id=$id") or sqlerr();
    $user = mysql_fetch_assoc($r) or stderr("{$lang['userdetails_error']}", "{$lang['userdetails_no_user']}");
    if ($user["status"] == "pending") die;
    $r = mysql_query("SELECT t.id, t.name, t.seeders, t.leechers, c.name AS cname, c.image FROM torrents t LEFT JOIN categories c ON t.category = c.id WHERE t.owner = $id ORDER BY t.name") or sqlerr(__FILE__,__LINE__);
    if (mysql_num_rows($r) > 0)
    {
      $torrents = "<table class='main' border='1' cellspacing='0' cellpadding='5'>\n" .
        "<tr><td class='colhead'>{$lang['userdetails_type']}</td><td class='colhead'>{$lang['userdetails_name']}</td><td class='colhead'>{$lang['userdetails_seeders']}</td><td class='colhead'>{$lang['userdetails_leechers']}</td></tr>\n";
      while ($a = mysql_fetch_assoc($r))
      {
        //$r2 = mysql_query("SELECT name, image FROM categories WHERE id=$a[category]") or sqlerr(__FILE__, __LINE__);
        //$a2 = mysql_fetch_assoc($r2);
        $cat = "<img src=\"". htmlspecialchars("{$TBDEV['pic_base_url']}caticons/{$a['image']}") ."\" title=\"{$a['cname']}\" alt=\"{$a['cname']}\" />";
          $torrents .= "<tr><td style='padding: 0px'>$cat</td><td><a href='details.php?id=" . $a['id'] . "&amp;hit=1'><b>" . htmlspecialchars($a["name"]) . "</b></a></td>" .
            "<td align='right'>{$a['seeders']}</td><td align='right'>{$a['leechers']}</td></tr>\n";
      }
      $torrents .= "</table>";
    }

    if ($user['ip'] && ($CURUSER['class'] >= UC_MODERATOR || $user['id'] == $CURUSER['id']))
    {
        $dom = @gethostbyaddr($user['ip']);
        $addr = ($dom == $user['ip'] || @gethostbyname($dom) != $user['ip']) ? $user['ip'] : $user['ip'].' ('.$dom.')';
    }


    if ($user['added'] == 0)
      $joindate = "{$lang['userdetails_na']}";
    else
      $joindate = get_date( $user['added'],'');
    $lastseen = $user["last_access"];
    if ($lastseen == 0)
      $lastseen = "{$lang['userdetails_never']}";
    else
    {
      $lastseen = get_date( $user['last_access'],'',0,1);
    }


      $res = mysql_query("SELECT COUNT(*) FROM comments WHERE user=" . $user['id']) or sqlerr();
      $arr3 = mysql_fetch_row($res);
      $torrentcomments = $arr3[0];
      $res = mysql_query("SELECT COUNT(*) FROM posts WHERE userid=" . $user['id']) or sqlerr();
      $arr3 = mysql_fetch_row($res);
      $forumposts = $arr3[0];

    //if ($user['donated'] > 0)
    //  $don = "<img src='{$TBDEV['pic_base_url']}starbig.gif' alt='' />";
    $country = '';
    $res = mysql_query("SELECT name,flagpic FROM countries WHERE id=".$user['country']." LIMIT 1") or sqlerr();
    if (mysql_num_rows($res) == 1)
    {
      $arr = mysql_fetch_assoc($res);
      $country = "<td class='embedded'><img src=\"{$TBDEV['pic_base_url']}flag/{$arr['flagpic']}\" alt=\"". htmlspecialchars($arr['name']) ."\" style='margin-left: 8pt' /></td>";
    }

    //if ($user["donor"] == "yes") $donor = "<td class='embedded'><img src='{$TBDEV['pic_base_url']}starbig.gif' alt='Donor' style='margin-left: 4pt' /></td>";
    //if ($user["warned"] == "yes") $warned = "<td class='embedded'><img src=\"{$TBDEV['pic_base_url']}warnedbig.gif\" alt='Warned' style='margin-left: 4pt' /></td>";

    $res = mysql_query("SELECT p.torrent, p.uploaded, p.downloaded, p.seeder, t.added, t.name as torrentname, t.size, t.category, t.seeders, t.leechers, c.name as catname, c.image FROM peers p LEFT JOIN torrents t ON p.torrent = t.id LEFT JOIN categories c ON t.category = c.id WHERE p.userid=$id") or sqlerr();

    while ($arr = mysql_fetch_assoc($res))
    {
        if ($arr['seeder'] == 'yes')
            $seeding[] = $arr;
        else
            $leeching[] = $arr;
    }

    
    $HTMLOUT = '';
    
    $enabled = $user["enabled"] == 'yes';
    $HTMLOUT .= "<p></p><table class='main' border='0' cellspacing='0' cellpadding='0'>".
    "<tr><td class='embedded'><h1 style='margin:0px'>{$user['username']}" . get_user_icons($user, true) . "</h1></td>$country</tr></table><p></p>\n";

    if (!$enabled)
      $HTMLOUT .= "<p><b>{$lang['userdetails_disabled']}</b></p>\n";
    elseif ($CURUSER["id"] <> $user["id"])
    {
      $r = mysql_query("SELECT id FROM friends WHERE userid=$CURUSER[id] AND friendid=$id") or sqlerr(__FILE__, __LINE__);
      $friend = mysql_num_rows($r);
      $r = mysql_query("SELECT id FROM blocks WHERE userid=$CURUSER[id] AND blockid=$id") or sqlerr(__FILE__, __LINE__);
      $block = mysql_num_rows($r);

      if ($friend)
        $HTMLOUT .= "<p>(<a href='friends.php?action=delete&amp;type=friend&amp;targetid=$id'>{$lang['userdetails_remove_friends']}</a>)</p>\n";
      elseif($block)
        $HTMLOUT .= "<p>(<a href='friends.php?action=delete&amp;type=block&amp;targetid=$id'>{$lang['userdetails_remove_blocks']}</a>)</p>\n";
      else
      {
        $HTMLOUT .= "<p>(<a href='friends.php?action=add&amp;type=friend&amp;targetid=$id'>{$lang['userdetails_add_friends']}</a>)";
        $HTMLOUT .= " - (<a href='friends.php?action=add&amp;type=block&amp;targetid=$id'>{$lang['userdetails_add_blocks']}</a>)</p>\n";
      }
    }

    $HTMLOUT .= begin_main_frame();

    $HTMLOUT .= "<table width='100%' border='1' cellspacing='0' cellpadding='5'>
    <tr><td class='rowhead' width='1%'>{$lang['userdetails_joined']}</td><td align='left' width='99%'>{$joindate}</td></tr>
    <tr><td class='rowhead'>{$lang['userdetails_seen']}</td><td align='left'>{$lastseen}</td></tr>";

    if ($CURUSER['class'] >= UC_MODERATOR)
      $HTMLOUT .= "<tr><td class='rowhead'>{$lang['userdetails_email']}</td><td align='left'><a href='{$TBDEV['baseurl']}/email-gateway.php?id={$user['id']}'>{$user['email']}</a></td></tr>\n";
    if (isset($addr))
      $HTMLOUT .= "<tr><td class='rowhead'>{$lang['userdetails_address']}</td><td align='left'>$addr</td></tr>\n";

    //  if ($user["id"] == $CURUSER["id"] || $CURUSER['class'] >= UC_MODERATOR)
    //	{

    $HTMLOUT .= "<tr><td class='rowhead'>{$lang['userdetails_uploaded']}</td><td align='left'>".mksize($user["uploaded"])."</td></tr>
    <tr><td class='rowhead'>{$lang['userdetails_downloaded']}</td><td align='left'>".mksize($user["downloaded"])."</td></tr>";

    if ($user["downloaded"] > 0)
    {
      $sr = $user["uploaded"] / $user["downloaded"];
      if ($sr >= 4)
        $s = "w00t";
      else if ($sr >= 2)
        $s = "grin";
      else if ($sr >= 1)
        $s = "smile1";
      else if ($sr >= 0.5)
        $s = "noexpression";
      else if ($sr >= 0.25)
        $s = "sad";
      else
        $s = "cry";
      $sr = floor($sr * 1000) / 1000;
      $sr = "<table border='0' cellspacing='0' cellpadding='0'><tr><td class='embedded'><font color='" . get_ratio_color($sr) . "'>" . number_format($sr, 3) . "</font></td><td class='embedded'>&nbsp;&nbsp;<img src=\"{$TBDEV['pic_base_url']}smilies/{$s}.gif\" alt='' /></td></tr></table>";
      $HTMLOUT .= "<tr><td class='rowhead' style='vertical-align: middle'>Share ratio</td><td align='left' valign='middle' style='padding-top: 1px; padding-bottom: 0px'>$sr</td></tr>\n";
    }
    //}

    //if ($user['donated'] > 0 && ($CURUSER['class'] >= UC_MODERATOR || $CURUSER["id"] == $user["id"]))
    //  print("<tr><td class='rowhead'>Donated</td><td align='left'>$user[donated]</td></tr>\n");
    if ($user["avatar"])
    $HTMLOUT .= "<tr><td class='rowhead'>{$lang['userdetails_avatar']}</td><td align='left'><img src='" . htmlspecialchars($user["avatar"]) . "' width='{$user['av_w']}' height='{$user['av_h']}' alt='' /></td></tr>\n";
    $HTMLOUT .= "<tr><td class='rowhead'>{$lang['userdetails_class']}</td><td align='left'>" . get_user_class_name($user["class"]) . "</td></tr>\n";
    $HTMLOUT .= "<tr><td class='rowhead'>{$lang['userdetails_comments']}</td>";
    if ($torrentcomments && (($user["class"] >= UC_POWER_USER && $user["id"] == $CURUSER["id"]) || $CURUSER['class'] >= UC_MODERATOR))
      $HTMLOUT .= "<td align='left'><a href='userhistory.php?action=viewcomments&amp;id=$id'>$torrentcomments</a></td></tr>\n";
    else
      $HTMLOUT .= "<td align='left'>$torrentcomments</td></tr>\n";
    $HTMLOUT .= "<tr><td class='rowhead'>{$lang['userdetails_posts']}</td>";

    if ($forumposts && (($user["class"] >= UC_POWER_USER && $user["id"] == $CURUSER["id"]) || $CURUSER['class'] >= UC_MODERATOR))
      $HTMLOUT .= "<td align='left'><a href='userhistory.php?action=viewposts&amp;id=$id'>$forumposts</a></td></tr>\n";
    else
      $HTMLOUT .= "<td align='left'>$forumposts</td></tr>\n";

    if ($CURUSER['class'] >= UC_MODERATOR && $user['invitedby'] > 0 || $user['id'] == $CURUSER['id'] && $user['invitedby'] > 0) {
		 $invitedby = mysql_query('SELECT username FROM users WHERE id = ' . sqlesc($user['invitedby']));
		 $invitedby2 = mysql_fetch_array($invitedby);
      $HTMLOUT .= "<tr><td class='rowhead'>{$lang['userdetails_invby']}</td><td align='left'><a href='{$TBDEV['baseurl']}/userdetails.php?id=".$user['invitedby']."'>'".htmlspecialchars($invitedby2['username'])."'</a></td></tr>"; }

    if (isset($torrents))
      $HTMLOUT .= "<tr valign='top'><td class='rowhead'>{$lang['userdetails_uploaded_t']}</td><td align='left'>$torrents</td></tr>\n";
      
    if (isset($seeding))
      $HTMLOUT .= "<tr valign=top><td class=rowhead>{$lang['userdetails_cur_seed']}</td><td align=left>".maketable($seeding)."</td></tr>\n";
      
    if (isset($leeching))
       $HTMLOUT .= "<tr valign=top><td class=rowhead>{$lang['userdetails_cur_leech']}</td><td align=left>".maketable($leeching)."</td></tr>\n";
       //=== start snatched
    $count_snatched='';
    if ($CURUSER['class'] >= UC_MODERATOR){
    if (isset($_GET["snatched_table"])){
    $HTMLOUT .="<tr><td class='clearalt6' align='right' valign='top'><b>Snatched stuff:</b><br />[ <a href=\"userdetails.php?id=$id\" class=\"sublink\">Hide list</a> ]</td><td class='clearalt6'>";
    
    $res = mysql_query(
    "SELECT sn.start_date AS s, sn.complete_date AS c, sn.last_action AS l_a, sn.seedtime AS s_t, sn.seedtime, sn.leechtime AS l_t, sn.leechtime, sn.downspeed, sn.upspeed, sn.uploaded, sn.downloaded, sn.torrentid, sn.start_date, sn.complete_date, sn.seeder, sn.last_action, sn.connectable, sn.agent, sn.seedtime, sn.port, cat.name, cat.image, t.size, t.seeders, t.leechers, t.owner, t.name AS torrent_name ".
    "FROM snatched AS sn ".
    "LEFT JOIN torrents AS t ON t.id = sn.torrentid ".
    "LEFT JOIN categories AS cat ON cat.id = t.category ".
    "WHERE sn.userid=$id ORDER BY sn.start_date DESC") or die(mysql_error());

    $HTMLOUT .= "<table border='1' cellspacing='0' cellpadding='5' align='center'><tr><td class='colhead' align='center'>Category</td><td class='colhead' align='left'>Torrent</td>".
    "<td class='colhead' align='center'>S / L</td><td class='colhead' align='center'>Up / Down</td><td class='colhead' align='center'>Torrent Size</td>".
    "<td class='colhead' align='center'>Ratio</td><td class='colhead' align='center'>Client</td></tr>";
    while ($arr = mysql_fetch_assoc($res)){
    //=======change colors
    $count2='';
    $count2= (++$count2)%2;
    $class = 'clearalt'.($count2==0?'6':'7');
    //=== speed color red fast green slow ;)
    if ($arr["upspeed"] > 0)
    $ul_speed = ($arr["upspeed"] > 0 ? mksize($arr["upspeed"]) : ($arr["seedtime"] > 0 ? mksize($arr["uploaded"] / ($arr["seedtime"] + $arr["leechtime"])) : mksize(0)));
    else
    $ul_speed = mksize(($arr["uploaded"] / ( $arr['l_a'] - $arr['s'] + 1 )));
    if ($arr["downspeed"] > 0)
    $dl_speed = ($arr["downspeed"] > 0 ? mksize($arr["downspeed"]) : ($arr["leechtime"] > 0 ? mksize($arr["downloaded"] / $arr["leechtime"]) : mksize(0)));
    else
    $dl_speed = mksize(($arr["downloaded"] / ( $arr['c'] - $arr['s'] + 1 )));
    
    $dlc="";
    switch (true){
    case ($dl_speed > 600):
    $dlc = 'red';
    break;
    case ($dl_speed > 300 ):
    $dlc = 'orange';
    break;
    case ($dl_speed > 200 ):
    $dlc = 'yellow';
    break;
    case ($dl_speed < 100 ):
    $dlc = 'Chartreuse';
    break;
    }

    if ($arr["downloaded"] > 0){
    $ratio = number_format($arr["uploaded"] / $arr["downloaded"], 3);
    $ratio = "<font color='" . get_ratio_color($ratio) . "'><b>Ratio:</b><br />$ratio</font>";
    }
    else
    if ($arr["uploaded"] > 0)
    $ratio = "Inf.";
    else
    $ratio = "N/A"; 
 
    $HTMLOUT .= "<tr><td class='$class' align='center'>".($arr['owner'] == $id ? "<b><font color='orange'>Torrent owner</font></b><br />" : "".($arr['complete_date'] != '0'  ? "<b><font color='lightgreen'>Finished</font></b><br />" : "<b><font color='red'>Not Finished</font></b><br />")."")."<img src='{$TBDEV['pic_base_url']}caticons/$arr[image]' alt='$arr[name]' title='$arr[name]' /></td>"."
    <td class='$class'><a class='altlink' href='{$TBDEV['baseurl']}/details.php?id=$arr[torrentid]'><b>$arr[torrent_name]</b></a>".($arr['complete_date'] != '0'  ?"<br />"."
    <font color='yellow'>started: ".get_date($arr['start_date'], 0,1) ."</font><br />
    " : " "."<font color='yellow'>started:".get_date($arr['start_date'], 0,1) ."</font><br /><font color='orange'>Last Action:".get_date($arr['last_action'], 0,1) ."</font>"." 
    ".get_date($arr['complete_date'], 0,1) ." ".($arr['complete_date'] == '0'  ? "".($arr['owner'] == $id ? "" : "[ ".mksize($arr["size"] - $arr["downloaded"])." still to go ]")."" : "")."")." ".get_date($arr['complete_date'], 0,1) ." ".($arr['complete_date'] != '0'  ? "<br />"."
    <font color='silver'>Time to download: ".($arr['leechtime'] != '0' ? mkprettytime($arr['leechtime']) : mkprettytime($arr['c'] - $arr['s'])."")."</font> <font color='$dlc'>[ DLed at: $dl_speed ]</font>"."
    <br />" : "<br />")."<font color='lightblue'>".($arr['seedtime'] != '0' ? "Total seeding time: ".mkprettytime($arr['seedtime'])." </font><font color='$dlc'> " : "Total seeding time: N/A").""."
    </font><font color='lightgreen'> [ up speed: ".$ul_speed." ] </font>".get_date($arr['complete_date'], 0,1) ."".($arr['complete_date'] == '0'  ? "<br /><font color='$dlc'>Download speed: $dl_speed</font>" : "")."</td>"."
    <td align='center' class='$class'>Seeds: ".$arr['seeders']."<br />Leech: ".$arr['leechers']."</td><td align='center' class='$class'><font color='lightgreen'>Uploaded:<br />"."
    <b>".$uploaded = mksize($arr["uploaded"])."</b></font><br /><font color='orange'>Downloaded:<br /><b>".$downloaded = mksize($arr["downloaded"])."</b></font></td>"."
    <td align='center' class='$class'>".mksize($arr["size"])."<br />Difference of:<br /><font color='orange'><b>".mksize($arr['size'] - $arr["downloaded"])."</b></font></td>"."
    <td align='center' class='$class'>'".$ratio."'<br />".($arr['seeder'] == 'yes' ? "<font color='lightgreen'><b>seeding</b></font>" : "<font color='red'><b>Not seeding</b></font>").""."
    </td><td align='center' class='$class'>".$arr["agent"]."<br />port: ".$arr["port"]."<br />".($arr["connectable"] == 'yes' ? "<b>Connectable:</b> <font color='lightgreen'>Yes</font>"."
    " : "<b>Connectable:</b> <font color='red'><b>no</b></font>")."</td></tr>\n";
    }
    $HTMLOUT .= "</table></td></tr>\n";
    }
    else
    $HTMLOUT .= tr("{$lang['userdetails_snatched_stuff']}<br />","[ <a href=\"userdetails.php?id=$id&amp;snatched_table=1\" class=\"sublink\">{$lang['userdetails_show']}</a> ]  - $count_snatched <font color='red'><b>{$lang['userdetails_staff_only']}</b></font>", 1);
    }
    //=== end snatched
    if ($user["info"])
     $HTMLOUT .= "<tr valign='top'><td align='left' colspan='2' class='text' bgcolor='#F4F4F0'>" . format_comment($user["info"]) . "</td></tr>\n";

    if ($CURUSER["id"] != $user["id"])
      if ($CURUSER['class'] >= UC_MODERATOR)
        $showpmbutton = 1;
      elseif ($user["acceptpms"] == "yes")
      {
        $r = mysql_query("SELECT id FROM blocks WHERE userid={$user['id']} AND blockid={$CURUSER['id']}") or sqlerr(__FILE__,__LINE__);
        $showpmbutton = (mysql_num_rows($r) == 1 ? 0 : 1);
      }
      elseif ($user["acceptpms"] == "friends")
      {
        $r = mysql_query("SELECT id FROM friends WHERE userid=$user[id] AND friendid=$CURUSER[id]") or sqlerr(__FILE__,__LINE__);
        $showpmbutton = (mysql_num_rows($r) == 1 ? 1 : 0);
      }
    if (isset($showpmbutton))
      $HTMLOUT .= "<tr>
      <td colspan='2' align='center'>
      <form method='get' action='sendmessage.php'>
        <input type='hidden' name='receiver' value='{$user["id"]}' />
        <input type='submit' value='{$lang['userdetails_msg_btn']}' class='btn' />
      </form>
      </td></tr>";

    $HTMLOUT .= "</table>\n";

    if ($CURUSER['class'] >= UC_MODERATOR && $user["class"] < $CURUSER['class'])
    {
      $HTMLOUT .= begin_frame("{$lang['userdetails_edit_user']}", true);
      $HTMLOUT .= "<form method='post' action='modtask.php'>\n";
      $HTMLOUT .= "<input type='hidden' name='action' value='edituser' />\n";
      $HTMLOUT .= "<input type='hidden' name='userid' value='$id' />\n";
      $HTMLOUT .= "<input type='hidden' name='returnto' value='userdetails.php?id=$id' />\n";
      $HTMLOUT .= "<table class='main' border='1' cellspacing='0' cellpadding='5'>\n";
      $HTMLOUT .= "<tr><td class='rowhead'>{$lang['userdetails_title']}</td><td colspan='2' align='left'><input type='text' size='60' name='title' value='" . htmlspecialchars($user['title']) . "' /></td></tr>\n";
      $avatar = htmlspecialchars($user["avatar"]);
      $HTMLOUT .= "<tr><td class='rowhead'>{$lang['userdetails_avatar_url']}</td><td colspan='2' align='left'><input type='text' size='60' name='avatar' value='$avatar' /></td></tr>\n";
      // we do not want mods to be able to change user classes or amount donated...
      if ($CURUSER["class"] < UC_ADMINISTRATOR)
        $HTMLOUT .= "<input type='hidden' name='donor' value='$user[donor]' />\n";
      else
      {
        $HTMLOUT .= "<tr><td class='rowhead'>{$lang['userdetails_donor']}</td><td colspan='2' align='left'><input type='radio' name='donor' value='yes'" .($user["donor"] == "yes" ? " checked='checked'" : "")." />{$lang['userdetails_yes']} <input type='radio' name='donor' value='no'" .($user["donor"] == "no" ? " checked='checked'" : "")." />{$lang['userdetails_no']}</td></tr>\n";
      }

      if ($CURUSER['class'] == UC_MODERATOR && $user["class"] > UC_VIP)
        $HTMLOUT .= "<input type='hidden' name='class' value='{$user['class']}' />\n";
      else
      {
        $HTMLOUT .= "<tr><td class='rowhead'>Class</td><td colspan='2' align='left'><select name='class'>\n";
        if ($CURUSER['class'] == UC_MODERATOR)
          $maxclass = UC_VIP;
        else
          $maxclass = $CURUSER['class'] - 1;
        for ($i = 0; $i <= $maxclass; ++$i)
          $HTMLOUT .= "<option value='$i'" . ($user["class"] == $i ? " selected='selected'" : "") . ">" . get_user_class_name($i) . "</option>\n";
        $HTMLOUT .= "</select></td></tr>\n";
      }

      $modcomment = htmlspecialchars($user["modcomment"]);
      $HTMLOUT .= "<tr><td class='rowhead'>{$lang['userdetails_comment']}</td><td colspan='2' align='left'><textarea cols='60' rows='6' name='modcomment'>$modcomment</textarea></td></tr>\n";
      $warned = $user["warned"] == "yes";

      $HTMLOUT .= "<tr><td class='rowhead'" . (!$warned ? " rowspan='2'": "") . ">{$lang['userdetails_warned']}</td>
      <td align='left' width='20%'>" .
      ( $warned
      ? "<input name=warned value='yes' type='radio' checked='checked' />{$lang['userdetails_yes']}<input name='warned' value='no' type='radio' />{$lang['userdetails_no']}"
      : "{$lang['userdetails_no']}" ) ."</td>";

      if ($warned)
      {
        $warneduntil = $user['warneduntil'];
        if ($warneduntil == 0)
          $HTMLOUT .= "<td align='center'>{$lang['userdetails_dur']}</td></tr>\n";
        else
        {
          $HTMLOUT .= "<td align='center'>{$lang['userdetails_until']} ".get_date($warneduntil, 'DATE');
          $HTMLOUT .= " (" . mkprettytime($warneduntil - time())  . " {$lang['userdetails_togo']})</td></tr>\n";
        }
      }
      else
      {
        $HTMLOUT .= "<td>{$lang['userdetails_warn_for']} <select name='warnlength'>\n";
        $HTMLOUT .= "<option value='0'>{$lang['userdetails_warn0']}</option>\n";
        $HTMLOUT .= "<option value='1'>{$lang['userdetails_warn1']}</option>\n";
        $HTMLOUT .= "<option value='2'>{$lang['userdetails_warn2']}</option>\n";
        $HTMLOUT .= "<option value='4'>{$lang['userdetails_warn4']}</option>\n";
        $HTMLOUT .= "<option value='8'>{$lang['userdetails_warn8']}</option>\n";
        $HTMLOUT .= "<option value='255'>{$lang['userdetails_warninf']}</option>\n";
        $HTMLOUT .= "</select>{$lang['userdetails_pm_comm']}</td></tr>\n";
        $HTMLOUT .= "<tr><td colspan='2' align='left'><input type='text' size='60' name='warnpm' /></td></tr>";
      }
//==Download disable
     if ($CURUSER['class'] >= UC_MODERATOR) {
	   $downloadpos = $user['downloadpos'] != 1;
     $HTMLOUT .= "<tr><td class='rowhead'".(!$downloadpos ? ' rowspan="2"' : '').">{$lang['userdetails_dpos']}</td>
 	   <td align='left' width='20%'>".($downloadpos ? "<input name='downloadpos' value='42' type='radio' />{$lang['userdetails_r_dload_disbl']}" : "{$lang['userdetails_disablement_ss']}")."</td>\n";

     if ($downloadpos)
     {
     if ($user['downloadpos'] == 0)
     $HTMLOUT .= "<td align='center'>{$lang['userdetails_unlimited_duration']}</td></tr>";
     else
     $HTMLOUT .= "<td align='center'>{$lang['userdetails_until_']}".get_date($user['downloadpos'], 'DATE'). " (".mkprettytime($user['downloadpos'] - time()). " to go)</td></tr>";
     } else
     {
     $HTMLOUT .= "<td>{$lang['userdetails_disable_for']}<select name='downloadpos'>
     <option value='0'>------</option>
     <option value='1'>1 {$lang['userdetails_week']}</option>
     <option value='2'>2 {$lang['userdetails_weeks']}</option>
     <option value='4'>4 {$lang['userdetails_weeks']}</option>
     <option value='8'>8 {$lang['userdetails_weeks']}</option>
     <option value='255'>{$lang['userdetails_unlimited']}</option>
     </select></td></tr>
     <tr><td colspan='2' align='left'>{$lang['userdetails_pm_comment']}<input type='text' size='60' name='disable_pm' /></td></tr>";
     }
     }
     //==Upload disable
     if ($CURUSER['class'] >= UC_MODERATOR) {
	   $uploadpos = $user['uploadpos'] != 1;
     $HTMLOUT .= "<tr><td class='rowhead'".(!$uploadpos ? ' rowspan="2"' : '').">{$lang['userdetails_upos']}</td>
 	   <td align='left' width='20%'>".($uploadpos ? "<input name='uploadpos' value='42' type='radio' />{$lang['userdetails_r_uload_disbl']}" : "{$lang['userdetails_disablement_ss']}")."</td>\n";

     if ($uploadpos)
     {
     if ($user['uploadpos'] == 0)
     $HTMLOUT .= "<td align='center'>{$lang['userdetails_unlimited_duration']}</td></tr>";
     else
     $HTMLOUT .= "<td align='center'>{$lang['userdetails_until_']}".get_date($user['uploadpos'], 'DATE'). " (".mkprettytime($user['uploadpos'] - time()). " to go)</td></tr>";
     } else
     {
     $HTMLOUT .= "<td>{$lang['userdetails_disable_for']}<select name='uploadpos'>
     <option value='0'>------</option>
     <option value='1'>1 {$lang['userdetails_week']}</option>
     <option value='2'>2 {$lang['userdetails_weeks']}</option>
     <option value='4'>4 {$lang['userdetails_weeks']}</option>
     <option value='8'>8 {$lang['userdetails_weeks']}</option>
     <option value='255'>{$lang['userdetails_unlimited']}</option>
     </select></td></tr>
     <tr><td colspan='2' align='left'>{$lang['userdetails_pm_comment']}<input type='text' size='60' name='updisable_pm' /></td></tr>";
     }
     }
     //==Posting disable
     if ($CURUSER['class'] >= UC_MODERATOR) {
	   $forumpost = $user['forumpost'] != 1;
     $HTMLOUT .= "<tr><td class='rowhead'".(!$forumpost ? ' rowspan="2"' : '').">{$lang['userdetails_fpost']}</td>
 	   <td align='left' width='20%'>".($forumpost ? "<input name='forumpost' value='42' type='radio' />{$lang['userdetails_r_posting_disbl']}" : "{$lang['userdetails_disablement_ss']}")."</td>\n";

     if ($forumpost)
     {
     if ($user['forumpost'] == 0)
     $HTMLOUT .= "<td align='center'>{$lang['userdetails_unlimited_duration']}</td></tr>";
     else
     $HTMLOUT .= "<td align='center'>{$lang['userdetails_until_']}".get_date($user['forumpost'], 'DATE'). " (".mkprettytime($user['forumpost'] - time()). " to go)</td></tr>";
     } else
     {
     $HTMLOUT .= "<td>{$lang['userdetails_disable_for']}<select name='forumpost'>
     <option value='0'>------</option>
     <option value='1'>1 {$lang['userdetails_week']}</option>
     <option value='2'>2 {$lang['userdetails_weeks']}</option>
     <option value='4'>4 {$lang['userdetails_weeks']}</option>
     <option value='8'>8 {$lang['userdetails_weeks']}</option>
     <option value='255'>{$lang['userdetails_unlimited']}</option>
     </select></td></tr>
     <tr><td colspan='2' align='left'>{$lang['userdetails_pm_comment']}<input type='text' size='60' name='forumdisable_pm' /></td></tr>";
     }
     }
     //==Shoutbox disable
     if ($CURUSER['class'] >= UC_MODERATOR) {
	   $chatpost = $user['chatpost'] != 1;
     $HTMLOUT .= "<tr><td class='rowhead'".(!$chatpost ? ' rowspan="2"' : '').">{$lang['userdetails_chatpos']}</td>
 	   <td align='left' width='20%'>".($chatpost ? "<input name='chatpost' value='42' type='radio' />{$lang['userdetails_r_shout_disbl']}" : "{$lang['userdetails_disablement_ss']}")."</td>\n";

     if ($chatpost)
     {
     if ($user['chatpost'] == 0)
     $HTMLOUT .= "<td align='center'>{$lang['userdetails_unlimited_duration']}</td></tr>";
     else
     $HTMLOUT .= "<td align='center'>{$lang['userdetails_until_']}".get_date($user['chatpost'], 'DATE'). " (".mkprettytime($user['chatpost'] - time()). " to go)</td></tr>";
     } else
     {
     $HTMLOUT .= "<td>{$lang['userdetails_disable_for']}<select name='chatpost'>
     <option value='0'>------</option>
     <option value='1'>1 {$lang['userdetails_week']}</option>
     <option value='2'>2 {$lang['userdetails_weeks']}</option>
     <option value='4'>4 {$lang['userdetails_weeks']}</option>
     <option value='8'>8 {$lang['userdetails_weeks']}</option>
     <option value='255'>{$lang['userdetails_unlimited']}</option>
     </select></td></tr>
     <tr><td colspan='2' align='left'>{$lang['userdetails_pm_comment']}<input type='text' size='60' name='chatdisable_pm' /></td></tr>";
     }
     }
     //==Immunity
     if ($CURUSER['class'] >= UC_MODERATOR) {
	   $immunity = $user['immunity'] != 0;
     $HTMLOUT .= "<tr><td class='rowhead'".(!$immunity ? ' rowspan="2"' : '').">{$lang['userdetails_immunity']}</td>
 	   <td align='left' width='20%'>".($immunity ? "<input name='immunity' value='42' type='radio' />{$lang['userdetails_r_immune_s']}" : "{$lang['userdetails_immunity_ss']}")."</td>\n";

      if ($immunity)
      {
      if ($user['immunity'] == 1)
      $HTMLOUT .= "<td align='center'>{$lang['userdetails_unlimited_duration']}</td></tr>";
      else
      $HTMLOUT .= "<td align='center'>{$lang['userdetails_until_']}".get_date($user['immunity'], 'DATE'). " (".
            mkprettytime($user['immunity'] - time()). " to go)</td></tr>";
     } else
     {
     $HTMLOUT .= "<td>{$lang['userdetails_immunity_for']}<select name='immunity'>
     <option value='0'>------</option>
     <option value='1'>1 {$lang['userdetails_week']}</option>
     <option value='2'>2 {$lang['userdetails_weeks']}</option>
     <option value='4'>4 {$lang['userdetails_weeks']}</option>
     <option value='8'>8 {$lang['userdetails_weeks']}</option>
     <option value='255'>{$lang['userdetails_unlimited']}</option>
     </select></td></tr>
     <tr><td colspan='2' align='left'>{$lang['userdetails_pm_comment']}<input type='text' size='60' name='immunity_pm' /></td></tr>";
     }
     }
     //==End
     //==Leech Warnings
     if ($CURUSER['class'] >= UC_MODERATOR) {
	   $leechwarn = $user['leechwarn'] != 0;
     $HTMLOUT .= "<tr><td class='rowhead'".(!$leechwarn ? ' rowspan="2"' : '').">{$lang['userdetails_leechwarn']}</td>
 	   <td align='left' width='20%'>".($leechwarn ? "<input name='leechwarn' value='42' type='radio' />{$lang['userdetails_r_lw_s']}" : "{$lang['userdetails_n_l_w_ss']}")."</td>\n";

      if ($leechwarn)
      {
      if ($user['leechwarn'] == 1)
      $HTMLOUT .= "<td align='center'>{$lang['userdetails_unlimited_duration']}</td></tr>";
      else
      $HTMLOUT .= "<td align='center'>{$lang['userdetails_until_']}".get_date($user['leechwarn'], 'DATE'). " (".
            mkprettytime($user['leechwarn'] - time()). " to go)</td></tr>";
     } else
     {
     $HTMLOUT .= "<td>{$lang['userdetails_leech_warn_for']}<select name='leechwarn'>
     <option value='0'>------</option>
     <option value='1'>1 {$lang['userdetails_week']}</option>
     <option value='2'>2 {$lang['userdetails_weeks']}</option>
     <option value='4'>4 {$lang['userdetails_weeks']}</option>
     <option value='8'>8 {$lang['userdetails_weeks']}</option>
     <option value='255'>{$lang['userdetails_unlimited']}</option>
     </select></td></tr>
     <tr><td colspan='2' align='left'>{$lang['userdetails_pm_comment']}<input type='text' size='60' name='leechwarn_pm' /></td></tr>";
     }
     }
     //==End
//==Pm disable
     if ($CURUSER['class'] >= UC_MODERATOR) {
	   $sendpmpos = $user['sendpmpos'] != 1;
     $HTMLOUT .= "<tr><td class='rowhead'".(!$sendpmpos ? ' rowspan="2"' : '').">{$lang['userdetails_pmpos']}</td>
 	   <td align='left' width='20%'>".($sendpmpos ? "<input name='sendpmpos' value='42' type='radio' />{$lang['userdetails_r_pm_disbl']}" : "{$lang['userdetails_disablement_ss']}")."</td>\n";

     if ($sendpmpos)
     {
     if ($user['sendpmpos'] == 0)
     $HTMLOUT .= "<td align='center'>{$lang['userdetails_unlimited_duration']}</td></tr>";
     else
     $HTMLOUT .= "<td align='center'>{$lang['userdetails_until_']}".get_date($user['sendpmpos'], 'DATE'). " (".mkprettytime($user['sendpmpos'] - time()). " to go)</td></tr>";
     } else
     {
     $HTMLOUT .= "<td>{$lang['userdetails_disable_for']}<select name='sendpmpos'>
     <option value='0'>------</option>
     <option value='1'>1 {$lang['userdetails_week']}</option>
     <option value='2'>2 {$lang['userdetails_weeks']}</option>
     <option value='4'>4 {$lang['userdetails_weeks']}</option>
     <option value='8'>8 {$lang['userdetails_weeks']}</option>
     <option value='255'>{$lang['userdetails_unlimited']}</option>
     </select></td></tr>
     <tr><td colspan='2' align='left'>{$lang['userdetails_pm_comment']}<input type='text' size='60' name='pmdisable_pm' /></td></tr>";
     }
     }
      $HTMLOUT .= "<tr><td class='rowhead'>{$lang['userdetails_chatpos']}</td><td colspan='2' align='left'><input type='radio' name='chatpost' value='yes'" .($user["chatpost"] == "yes" ? " checked='checked'" : "")." />{$lang['userdetails_yes']} <input type='radio' name='chatpost' value='no'" .($user["chatpost"] == "no" ? " checked='checked'" : "")." />{$lang['userdetails_no']}</td></tr>\n";
      if ($CURUSER["class"] < UC_SYSOP)
      $HTMLOUT .="<input type=\"hidden\" name=\"highspeed\" value=\"$user[highspeed]\" />\n";
      else {
      $HTMLOUT .= "<tr><td class='rowhead'>{$lang['userdetails_highspeed']}</td><td class='row' colspan='2' align='left'><input type='radio' name='highspeed' value='yes' " .($user["highspeed"] == "yes" ? " checked='checked'" : "") ." />Yes <input type='radio' name='highspeed' value='no' " . ($user["highspeed"] == "no" ? " checked='checked'" : "") . " />No</td></tr>\n";
           }
      $HTMLOUT .= "<tr><td class='rowhead'>{$lang['userdetails_enabled']}</td><td colspan='2' align='left'><input name='enabled' value='yes' type='radio'" . ($enabled ? " checked='checked'" : "") . " />{$lang['userdetails_yes']} <input name='enabled' value='no' type='radio'" . (!$enabled ? " checked='checked'" : "") . " />{$lang['userdetails_no']}</td></tr>\n";
      $HTMLOUT .= "<tr><td class='rowhead'>{$lang['userdetails_invright']}</td><td class='row' colspan='2' align='left'><input type='radio' name='invite_rights' value='yes'" .($user["invite_rights"]=="yes" ? " checked='checked'" : "") . " />{$lang['userdetails_yes']}<input type='radio' name='invite_rights' value='no'" .($user["invite_rights"]=="no" ? " checked='checked'" : "") . " />{$lang['userdetails_no']}</td></tr>\n";
      $HTMLOUT .= "<tr><td class='rowhead' align='left'><b>{$lang['userdetails_invites']}</b></td><td colspan='2' align='left' class='rowhead'><input type='text' size='3' name='invites' value='" . htmlspecialchars($user['invites']) . "' /></td></tr>\n";
      $HTMLOUT .= "<tr><td class='rowhead'>{$lang['userdetails_reset']}</td><td colspan='2'><input type='checkbox' name='resetpasskey' value='1' /><font class='small'>{$lang['userdetails_pass_msg']}</font></td></tr>";
      //$HTMLOUT .= "</td></tr>";
      $HTMLOUT .= "<tr><td colspan='3' align='center'><input type='submit' class='btn' value='{$lang['userdetails_okay']}' /></td></tr>\n";
      $HTMLOUT .= "</table>\n";
      $HTMLOUT .= "</form>\n";
      $HTMLOUT .= end_frame();
    }
    $HTMLOUT .= end_main_frame();
    
    
    print stdhead("{$lang['userdetails_details']} " . $user["username"]) . $HTMLOUT . stdfoot();

?>
