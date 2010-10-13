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

// xLeech .::. take edit
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
require_once ("include/user_functions.php");


dbconn();

loggedinorreturn();

    $lang = array_merge( load_language('global'), load_language('takeedit') );

    if (!mkglobal('name:descr:type'))
      stderr($lang['takedit_failed'], $lang['takedit_no_data']);

    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    if ( !is_valid_id($id) )
      stderr($lang['takedit_failed'], $lang['takedit_no_data']);
        
    
    $res = mysql_query("SELECT owner, filename, save_as FROM torrents WHERE id = $id");
    
    if ( false == mysql_num_rows($res) )
      stderr($lang['takedit_failed'], $lang['takedit_no_data']);
      
    $row = mysql_fetch_assoc($res);

    if ($CURUSER['id'] != $row['owner'] && $CURUSER['class'] < UC_MODERATOR)
      stderr($lang['takedit_failed'], $lang['takedit_not_owner']);

    $updateset = array();

    $fname = $row['filename'];
    preg_match('/^(.+)\.torrent$/si', $fname, $matches);
    $shortfname = $matches[1];
    $dname = $row['save_as'];

    $nfoaction = $_POST['nfoaction'];
    if ($nfoaction == 'update')
    {
      $nfofile = $_FILES['nfo'];
      if (!$nfofile) die("No data " . var_dump($_FILES));
      if ($nfofile['size'] > 65535)
        stderr($lang['takedit_failed'], $lang['takedit_nfo_error']);
      $nfofilename = $nfofile['tmp_name'];
      if (@is_uploaded_file($nfofilename) && @filesize($nfofilename) > 0)
        $updateset[] = "nfo = " . sqlesc(str_replace("\x0d\x0d\x0a", "\x0d\x0a", file_get_contents($nfofilename)));
    }
    else
      if ($nfoaction == 'remove')
        $updateset[] = 'nfo = ""';

$poster = $_POST["poster"];
$updateset[] = "poster = " . sqlesc($poster);

    $updateset[] = "name = " . sqlesc($name);
    $updateset[] = "search_text = " . sqlesc(searchfield("$shortfname $dname $name"));
    $updateset[] = "descr = " . sqlesc($descr);
    $updateset[] = "ori_descr = " . sqlesc($descr);
    $updateset[] = "category = " . (0 + $type);
    //if ($CURUSER["admin"] == "yes") {
    if ($CURUSER['class'] > UC_MODERATOR)
{
    if (isset($_POST["banned"]))
    {
        $updateset[] = "banned = 'yes'";
        $_POST["visible"] = 0;
    } else
        $updateset[] = "banned = 'no'";


        /// Set freeleech on Torrent Time Based
    if (isset($_POST['free_length']) && ($free_length = 0 + $_POST['free_length'])) {
        if ($free_length == 255)
            $free = 1;

        elseif ($free_length == 42)
            $free = (86400 + time());

        else
            $free = (time() + $free_length * 604800);

        $updateset[] = "free = ".sqlesc($free );
        write_log("Torrent $id ($name) set free for ".($free != 1 ? "
	 Until ".get_date($Free, 'DATE') : 'Unlimited')." by $CURUSER[username]");
    }
    
     if (isset($_POST['fl']) && ($_POST['fl'] == 1))
    {
        $updateset[] = "free = '0'";
        write_log("Torrent $id ($name) No Longer Free. Removed by $CURUSER[username]");
    }
    /// end freeleech mod
}
    $updateset[] = "visible = '" . ( isset($_POST['visible']) ? 'yes' : 'no') . "'";

    mysql_query("UPDATE torrents SET " . join(",", $updateset) . " WHERE id = $id");

    write_log(sprintf($lang['takedit_log'], $id, $name, $CURUSER['username']));
    
    $returnto = "{$TBDEV['baseurl']}/details.php?id=$id&amp;edited=1";
    
    header("Location: $returnto");


?>
