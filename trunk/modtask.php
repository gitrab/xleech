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
require_once ("include/bittorrent.php");
require_once ("include/user_functions.php");
require_once ("include/page_verify.php");

dbconn(false);

loggedinorreturn();

$lang = load_language('modtask');

$newpage = new page_verify(); 
$newpage->check('modtask');

if ($CURUSER['class'] < UC_MODERATOR) stderr("{$lang['modtask_user_error']}", "{$lang['modtask_try_again']}");

// Correct call to script
if ((isset($_POST['action'])) && ($_POST['action'] == "edituser"))
    {
    // Set user id
    if (isset($_POST['userid'])) $userid = $_POST['userid'];
    else stderr("{$lang['modtask_user_error']}", "{$lang['modtask_try_again']}");

    // and verify...
    if (!is_valid_id($userid)) stderr("{$lang['modtask_error']}", "{$lang['modtask_bad_id']}");
	require_once ("include/validator.php");
	if (!validate($_POST['validator'], "ModTask_$userid" )) die ("Invalid" );

    // Fetch current user data...
    $res = mysql_query("SELECT * FROM users WHERE id=".sqlesc($userid)) or sqlerr(__FILE__, __LINE__);
    $user = mysql_fetch_assoc($res) or sqlerr(__FILE__, __LINE__);

    //== Check to make sure your not editing someone of the same or higher class
     if ($CURUSER["class"] <= $user['class'] && ($CURUSER['id'] != $userid && $CURUSER["class"] < UC_ADMINISTRATOR))
	stderr('Error','You cannot edit someone of the same or higher class.. injecting stuff arent we? Action logged');

    $updateset = array();

    if ((isset($_POST['modcomment'])) && ($modcomment = $_POST['modcomment'])) ;
    else $modcomment = "";
	
    if (($user['immunity'] >= 1) && ($CURUSER['class'] < UC_SYSOP))
     stderr("Error", "This user is immune to your commands !");

    // Set class

    if ((isset($_POST['class'])) && (($class = $_POST['class']) != $user['class']))
{
if ($class >= UC_SYSOP || ($class >= $CURUSER['class']) || ($user['class'] >= $CURUSER['class']))
stderr("{$lang['modtask_user_error']}", "{$lang['modtask_try_again']}");
if (!is_valid_user_class($class) || $CURUSER["class"] <= $_POST['class']) stderr( ("Error"), "Bad class :P");

    // Notify user
    $what = ($class > $user['class'] ? "{$lang['modtask_promoted']}" : "{$lang['modtask_demoted']}");
    $msg = sqlesc(sprintf($lang['modtask_have_been'], $what)." '" . get_user_class_name($class) . "' {$lang['modtask_by']} ".$CURUSER['username']);
    $added = time();
    mysql_query("INSERT INTO messages (sender, receiver, msg, added) VALUES(0, $userid, $msg, $added)") or sqlerr(__FILE__, __LINE__);

    $updateset[] = "class = ".sqlesc($class);

    $modcomment = get_date( time(), 'DATE', 1 ) . " - $what to '" . get_user_class_name($class) . "' by $CURUSER[username].\n". $modcomment;
    }

    // Clear Warning - Code not called for setting warning
    if (isset($_POST['warned']) && (($warned = $_POST['warned']) != $user['warned']))
    {
    $updateset[] = "warned = " . sqlesc($warned);
    $updateset[] = "warneduntil = 0";
    if ($warned == 'no')
    {
    $modcomment = get_date( time(), 'DATE', 1 ) . "{$lang['modtask_warned']}" . $CURUSER['username'] . ".\n". $modcomment;
    $msg = sqlesc("{$lang['modtask_warned_removed']}" . $CURUSER['username'] . ".");
    $added = time();
    mysql_query("INSERT INTO messages (sender, receiver, msg, added) VALUES (0, $userid, $msg, $added)") or sqlerr(__FILE__, __LINE__);
    }
    }

    // Set warning - Time based
    if (isset($_POST['warnlength']) && ($warnlength = 0 + $_POST['warnlength']))
    {
    unset($warnpm);
    if (isset($_POST['warnpm'])) $warnpm = $_POST['warnpm'];

    if ($warnlength == 255)
    {
    $modcomment = get_date( time(), 'DATE', 1 ) . "{$lang['modtask_warned_by']}" . $CURUSER['username'] . ".\n{$lang['modtask_reason']} $warnpm\n" . $modcomment;
    $msg = sqlesc("{$lang['modtask_warning_received']}".$CURUSER['username'].($warnpm ? "\n\n{$lang['modtask_reason']} $warnpm" : ""));
    $updateset[] = "warneduntil = 0";
    }
    else
    {
    $warneduntil = (time() + $warnlength * 604800);
    $dur = $warnlength . "{$lang['modtask_week']}" . ($warnlength > 1 ? "s" : "");
    $msg = sqlesc(sprintf($lang['modtask_warning_duration'], $dur).$CURUSER['username'].($warnpm ? "\n\nReason: $warnpm" : ""));
    $modcomment = get_date( time(), 'DATE', 1 ) . sprintf($lang['modtask_warned_for'], $dur) . $CURUSER['username'] . ".\n{$lang['modtask_reason']} $warnpm\n" . $modcomment;
    $updateset[] = "warneduntil = ".$warneduntil;
    }
    $added = time();
    mysql_query("INSERT INTO messages (sender, receiver, msg, added) VALUES (0, $userid, $msg, $added)") or sqlerr(__FILE__, __LINE__);
    $updateset[] = "warned = 'yes'";
    }

// invite rights
	   if ((isset($_POST['invite_rights'])) && (($invite_rights = $_POST['invite_rights']) != $user['invite_rights'])){
	   if ($invite_rights == 'yes')
	   {
	   $modcomment = get_date( time(), 'DATE', 1 ) . " - Invite rights enabled by " . htmlspecialchars($CURUSER['username']) . ".\n" . $modcomment;
	   $msg = sqlesc("Your invite rights have been given back by " . htmlspecialchars($CURUSER['username']) . ". You can invite users again.");
	   $added = time();
	   mysql_query("INSERT INTO messages (sender, receiver, msg, added) VALUES (0, $userid, $msg, $added)") or sqlerr(__FILE__, __LINE__);
	   }
	   elseif ($invite_rights == 'no'){
	   $modcomment = get_date( time(), 'DATE', 1 ) . " - Invite rights disabled by " . htmlspecialchars($CURUSER['username']) . ".\n" . $modcomment;
	   $msg = sqlesc("Your invite rights have been removed by " . htmlspecialchars($CURUSER['username']) . ", probably because you invited a bad user.");
	   $added = time();
	   mysql_query("INSERT INTO messages (sender, receiver, msg, added) VALUES (0, $userid, $msg, $added)") or sqlerr(__FILE__, __LINE__);
	   }
	   $updateset[] = "invite_rights = " . sqlesc($invite_rights);
	   }
	   
	   // change invite amount
	   if ((isset($_POST['invites'])) && (($invites = $_POST['invites']) != ($curinvites = $user['invites'])))
	   {
	   $modcomment = get_date( time(), 'DATE', 1 ) . " - Invite amount changed to ".$invites." from ".$curinvites." by " . htmlspecialchars($CURUSER['username']) . ".\n" . $modcomment;
	   $updateset[] = "invites = " . sqlesc($invites);
	   }

    // Clear donor - Code not called for setting donor
    if (isset($_POST['donor']) && (($donor = $_POST['donor']) != $user['donor']))
    {
    $updateset[] = "donor = " . sqlesc($donor);
    $updateset[] = "warneduntil = 0";
    if ($donor == 'no')
    {
    $modcomment = get_date( time(), 'DATE', 1 ) . "{$lang['modtask_donor_removed']}".$CURUSER['username'].".\n". $modcomment;
    $msg = sqlesc("{$lang['modtask_donor_expired']}");
    $added = time();
    mysql_query("INSERT INTO messages (sender, receiver, msg, added) VALUES (0, $userid, $msg, $added)") or sqlerr(__FILE__, __LINE__);
    }
    }

    // Set donor - Time based
    if ((isset($_POST['donorlength'])) && ($donorlength = 0 + $_POST['donorlength']))
    {
    if ($donorlength == 255)
    {
    $modcomment = get_date( time(), 'DATE', 1 ) . "{$lang['modtask_donor_set']}" . $CURUSER['username'] . ".\n" . $modcomment;
    $msg = sqlesc("{$lang['modtask_received_donor']}".$CURUSER['username']);
    $updateset[] = "donoruntil = 0";
    }
    else
    {
    $donoruntil = (time() + $donorlength * 604800);
    $dur = $donorlength . "{$lang['modtask_week']}" . ($donorlength > 1 ? "s" : "");
    $msg = sqlesc(sprintf($lang['modtask_donor_duration'], $dur) . $CURUSER['username']);
    $modcomment = get_date( time(), 'DATE', 1 ) . sprintf($lang['modtask_donor_for'], $dur) . $CURUSER['username']."\n".$modcomment;
    $updateset[] = "donoruntil = ".$donoruntil;
    }
    $added = time();
    mysql_query("INSERT INTO messages (sender, receiver, msg, added) VALUES (0, $userid, $msg, $added)") or sqlerr(__FILE__, __LINE__);
    $updateset[] = "donor = 'yes'";
    }

    // Enable / Disable
    if ((isset($_POST['enabled'])) && (($enabled = $_POST['enabled']) != $user['enabled']))
    {
    if ($enabled == 'yes')
    $modcomment = get_date( time(), 'DATE', 1 ) . " {$lang['modtask_enabled']}" . $CURUSER['username'] . ".\n" . $modcomment;
    else
    $modcomment = get_date( time(), 'DATE', 1 ) . "{$lang['modtask_disabled']}" . $CURUSER['username'] . ".\n" . $modcomment;

    $updateset[] = "enabled = " . sqlesc($enabled);
    }
    /* If your running the forum post enable/disable, uncomment this section
    // Forum Post Enable / Disable
    if ((isset($_POST['forumpost'])) && (($forumpost = $_POST['forumpost']) != $user['forumpost']))
    {
    if ($forumpost == 'yes')
    {
    $modcomment = gmdate("Y-m-d")." - Posting enabled by ".$CURUSER['username'].".\n" . $modcomment;
    $msg = sqlesc("Your Posting rights have been given back by ".$CURUSER['username'].". You can post to forum again.");
    $added = time();
    mysql_query("INSERT INTO messages (sender, receiver, msg, added) VALUES (0, $userid, $msg, $added)") or sqlerr(__FILE__, __LINE__);
    }
    else
    {
    $modcomment = gmdate("Y-m-d")." - Posting disabled by ".$CURUSER['username'].".\n" . $modcomment;
    $msg = sqlesc("Your Posting rights have been removed by ".$CURUSER['username'].", Please PM ".$CURUSER['username']." for the reason why.");
    $added = time();
    mysql_query("INSERT INTO messages (sender, receiver, msg, added) VALUES (0, $userid, $msg, $added)") or sqlerr(__FILE__, __LINE__);
    }
    $updateset[] = "forumpost = " . sqlesc($forumpost);
    } */

    // Change Custom Title
    if ((isset($_POST['title'])) && (($title = $_POST['title']) != ($curtitle = $user['title'])))
    {
    $modcomment = get_date( time(), 'DATE', 1 ) . "{$lang['modtask_custom_title']}'".$title."' from '".$curtitle."' {$lang['modtask_by']} " . $CURUSER['username'] . ".\n" . $modcomment;

    $updateset[] = "title = " . sqlesc($title);
    }

    // The following code will place the old passkey in the mod comment and create
    // a new passkey. This is good practice as it allows usersearch to find old
    // passkeys by searching the mod comments of members.

    // Reset Passkey
    if ((isset($_POST['resetpasskey'])) && ($_POST['resetpasskey']))
    {
    $newpasskey = md5($user['username'].time().$user['passhash']);
    $modcomment = get_date( time(), 'DATE', 1 ) . "{$lang['modtask_passkey']}".sqlesc($user['passkey'])."{$lang['modtask_reset']}".sqlesc($newpasskey)."{$lang['modtask_by']}" . $CURUSER['username'] . ".\n" . $modcomment;

    $updateset[] = "passkey=".sqlesc($newpasskey);
    }

    /* This code is for use with the safe mod comment modification. If you have installed
    the safe mod comment mod, then uncomment this section...

    // Add Comment to ModComment
    if ((isset($_POST['addcomment'])) && ($addcomment = trim($_POST['addcomment'])))
    {
    $modcomment = gmdate("Y-m-d") . " - ".$addcomment." - " . $CURUSER['username'] . ".\n" . $modcomment;
    } */

    /// Set download posssible Time based
    if (isset($_POST['downloadpos']) && ($downloadpos =
    0 + $_POST['downloadpos']))
    {
    unset($disable_pm);
    if (isset($_POST['disable_pm']))
        $disable_pm = $_POST['disable_pm'];
    $subject = sqlesc('Notification!');
    $added = time();
    
    if ($downloadpos == 255)
    {
        $modcomment = get_date($added, 'DATE', 1)." - Download disablement by ".
		$CURUSER['username'].".\nReason: $disable_pm\n".$modcomment;
        $msg = sqlesc("Your Downloading rights have been disabled by ".$CURUSER['username'].($disable_pm ?
            "\n\nReason: $disable_pm" : ''));
        $updateset[] = 'downloadpos = 0';
    } elseif ($downloadpos == 42)
    {
        $modcomment = get_date($added, 'DATE', 1)." - Download disablement status removed by ".
		$CURUSER['username'].".\n".$modcomment;
        $msg = sqlesc("Your Downloading rights have been restored by ".
		$CURUSER['username'].".");
		$updateset[] = 'downloadpos = 1';
    } else
    {
        $downloadpos_until = ($added + $downloadpos * 604800);
        $dur = $downloadpos.' week'.($downloadpos > 1 ? 's' : '');
        $msg = sqlesc("You have received $dur Download disablement from ".
		$CURUSER['username'].($disable_pm ? "\n\nReason: $disable_pm" : ''));
        $modcomment = get_date($added, 'DATE', 1)." - Download disablement for $dur by ".
		$CURUSER['username'].".\nReason: $disable_pm\n".$modcomment;
        $updateset[] = "downloadpos = ".$downloadpos_until;
    }

    mysql_query("INSERT INTO messages (sender, receiver, subject, msg, added) 
	             VALUES (0, $userid, $subject, $msg, $added)") or sqlerr(__file__, __line__);
   }
    /// Set upload posssible Time based
    if (isset($_POST['uploadpos']) && ($uploadpos =
    0 + $_POST['uploadpos']))
    {
    unset($updisable_pm);
    if (isset($_POST['updisable_pm']))
        $updisable_pm = $_POST['updisable_pm'];
    $subject = sqlesc('Notification!');
    $added = time();
    
    if ($uploadpos == 255)
    {
        $modcomment = get_date($added, 'DATE', 1)." - Upload disablement by ".
		$CURUSER['username'].".\nReason: $updisable_pm\n".$modcomment;
        $msg = sqlesc("Your Uploading rights have been disabled by ".$CURUSER['username'].($updisable_pm ?
            "\n\nReason: $updisable_pm" : ''));
        $updateset[] = 'uploadpos = 0';
    } elseif ($uploadpos == 42)
    {
        $modcomment = get_date($added, 'DATE', 1)." - Upload disablement status removed by ".
		$CURUSER['username'].".\n".$modcomment;
        $msg = sqlesc("Your Uploading rights have been restored by ".
		$CURUSER['username'].".");
		$updateset[] = 'uploadpos = 1';
    } else
    {
        $uploadpos_until = ($added + $uploadpos * 604800);
        $dur = $uploadpos.' week'.($uploadpos > 1 ? 's' : '');
        $msg = sqlesc("You have received $dur Upload disablement from ".
		$CURUSER['username'].($updisable_pm ? "\n\nReason: $updisable_pm" : ''));
        $modcomment = get_date($added, 'DATE', 1)." - Upload disablement for $dur by ".
		$CURUSER['username'].".\nReason: $updisable_pm\n".$modcomment;
        $updateset[] = "uploadpos = ".$uploadpos_until;
    }

    mysql_query("INSERT INTO messages (sender, receiver, subject, msg, added) 
	             VALUES (0, $userid, $subject, $msg, $added)") or sqlerr(__file__, __line__);
   }
    /// Set Forum posting posssible Time based
    if (isset($_POST['forumpost']) && ($forumpost =
    0 + $_POST['forumpost']))
    {
    unset($forumdisable_pm);
    if (isset($_POST['forumdisable_pm']))
        $forumdisable_pm = $_POST['forumdisable_pm'];
    $subject = sqlesc('Notification!');
    $added = time();
    
    if ($forumpost == 255)
    {
        $modcomment = get_date($added, 'DATE', 1)." - Forum Posting disablement by ".
		$CURUSER['username'].".\nReason: $forumdisable_pm\n".$modcomment;
        $msg = sqlesc("Your posting rights have been disabled by ".$CURUSER['username'].($forumdisable_pm ?
            "\n\nReason: $forumdisable_pm" : ''));
        $updateset[] = 'forumpost = 0';
    } elseif ($forumpost == 42)
    {
        $modcomment = get_date($added, 'DATE', 1)." - Posting disablement status removed by ".
		$CURUSER['username'].".\n".$modcomment;
        $msg = sqlesc("Your posting rights have been restored by ".
		$CURUSER['username'].".");
		$updateset[] = 'forumpost = 1';
    } else
    {
        $forumpost_until = ($added + $forumpost * 604800);
        $dur = $forumpost.' week'.($forumpost > 1 ? 's' : '');
        $msg = sqlesc("You have received $dur Posting disablement from ".
		$CURUSER['username'].($forumdisable_pm ? "\n\nReason: $forumdisable_pm" : ''));
        $modcomment = get_date($added, 'DATE', 1)." - Forum posting disablement for $dur by ".
		$CURUSER['username'].".\nReason: $forumdisable_pm\n".$modcomment;
        $updateset[] = "forumpost = ".$forumpost_until;
    }

    mysql_query("INSERT INTO messages (sender, receiver, subject, msg, added) 
	             VALUES (0, $userid, $subject, $msg, $added)") or sqlerr(__file__, __line__);
   }
   /// Set shoutbox posssible Time based
    if (isset($_POST['chatpost']) && ($chatpost =
    0 + $_POST['chatpost']))
    {
    unset($chatdisable_pm);
    if (isset($_POST['chatdisable_pm']))
        $chatdisable_pm = $_POST['chatdisable_pm'];
    $subject = sqlesc('Notification!');
    $added = time();
    
    if ($chatpost == 255)
    {
        $modcomment = get_date($added, 'DATE', 1)." - Shout disablement by ".
		$CURUSER['username'].".\nReason: $chatdisable_pm\n".$modcomment;
        $msg = sqlesc("Your Shoutbox rights have been disabled by ".$CURUSER['username'].($chatdisable_pm ?
            "\n\nReason: $chatdisable_pm" : ''));
        $updateset[] = 'chatpost = 0';
    } elseif ($chatpost == 42)
    {
        $modcomment = get_date($added, 'DATE', 1)." - Shoutbox disablement status removed by ".
		$CURUSER['username'].".\n".$modcomment;
        $msg = sqlesc("Your Shoutbox rights have been restored by ".
		$CURUSER['username'].".");
		$updateset[] = 'chatpost = 1';
    } else
    {
        $chatpost_until = ($added + $chatpost * 604800);
        $dur = $chatpost.' week'.($chatpost > 1 ? 's' : '');
        $msg = sqlesc("You have received $dur Shoutbox disablement from ".
		$CURUSER['username'].($chatdisable_pm ? "\n\nReason: $chatdisable_pm" : ''));
        $modcomment = get_date($added, 'DATE', 1)." - Shoutbox disablement for $dur by ".
		$CURUSER['username'].".\nReason: $chatdisable_pm\n".$modcomment;
        $updateset[] = "chatpost = ".$chatpost_until;
    }

    mysql_query("INSERT INTO messages (sender, receiver, subject, msg, added) 
	             VALUES (0, $userid, $subject, $msg, $added)") or sqlerr(__file__, __line__);
   }
   /// Set Immunity Status Time based
   if (isset($_POST['immunity']) && ($immunity =
   0 + $_POST['immunity']))
   {
   unset($immunity_pm);
    if (isset($_POST['immunity_pm']))
        $immunity_pm = $_POST['immunity_pm'];
    $subject = sqlesc('Notification!');
    $added = time();

    if ($immunity == 255)
    {
        $modcomment = get_date($added, 'DATE', 1)." - Immune Status enabled by ".
		$CURUSER['username'].".\nReason: $immunity_pm\n".$modcomment;
        $msg = sqlesc("You have received immunity Status from ".$CURUSER['username'].($immunity_pm ?
            "\n\nReason: $immunity_pm" : ''));
        $updateset[] = 'immunity = 1';
    } elseif ($immunity == 42)
    {
        $modcomment = get_date($added, 'DATE', 1)." - Immunity Status removed by ".
		$CURUSER['username'].".\n".$modcomment;
        $msg = sqlesc("Your Immunity Status has been removed by ".
		$CURUSER['username'].".");
		$updateset[] = 'immunity = 0';
    } else
    {
        $immunity_until = ($added + $immunity * 604800);
        $dur = $immunity.' week'.($immunity > 1 ? 's' : '');
        $msg = sqlesc("You have received $dur Immunity Status from ".
		$CURUSER['username'].($immunity_pm ? "\n\nReason: $immunity_pm" : ''));
        $modcomment = get_date($added, 'DATE', 1)." - Immunity Status for $dur by ".
		$CURUSER['username'].".\nReason: $immunity_pm\n".$modcomment;
        $updateset[] = "immunity = ".$immunity_until;
    }

    mysql_query("INSERT INTO messages (sender, receiver, subject, msg, added) 
	             VALUES (0, $userid, $subject, $msg, $added)") or sqlerr(__file__, __line__);
   }
   /// Set leechwarn Status Time based
   if (isset($_POST['leechwarn']) && ($leechwarn =
   0 + $_POST['leechwarn']))
   {
   unset($leechwarn_pm);
    if (isset($_POST['leechwarn_pm']))
        $leechwarn_pm = $_POST['leechwarn_pm'];
    $subject = sqlesc('Notification!');
    $added = time();

    if ($leechwarn == 255)
    {
        $modcomment = get_date($added, 'DATE', 1)." - leechwarn Status enabled by ".
		$CURUSER['username'].".\nReason: $leechwarn_pm\n".$modcomment;
        $msg = sqlesc("You have received leechwarn Status from ".$CURUSER['username'].($leechwarn_pm ?
            "\n\nReason: $leechwarn_pm" : ''));
        $updateset[] = 'leechwarn = 1';
    } elseif ($leechwarn == 42)
    {
        $modcomment = get_date($added, 'DATE', 1)." - leechwarn Status removed by ".
		$CURUSER['username'].".\n".$modcomment;
        $msg = sqlesc("Your leechwarn Status has been removed by ".
		$CURUSER['username'].".");
		$updateset[] = 'leechwarn = 0';
    } else
    {
        $leechwarn_until = ($added + $leechwarn * 604800);
        $dur = $leechwarn.' week'.($leechwarn > 1 ? 's' : '');
        $msg = sqlesc("You have received $dur leechwarn Status from ".
		$CURUSER['username'].($leechwarn_pm ? "\n\nReason: $leechwarn_pm" : ''));
        $modcomment = get_date($added, 'DATE', 1)." - leechwarn Status for $dur by ".
		$CURUSER['username'].".\nReason: $leechwarn_pm\n".$modcomment;
        $updateset[] = "leechwarn = ".$leechwarn_until;
    }

    mysql_query("INSERT INTO messages (sender, receiver, subject, msg, added) 
	             VALUES (0, $userid, $subject, $msg, $added)") or sqlerr(__file__, __line__);
   }

    // Avatar Changed
    if ((isset($_POST['avatar'])) && (($avatar = $_POST['avatar']) != ($curavatar = $user['avatar'])))
    {
      
      $avatar = trim( urldecode( $avatar ) );
  
      if ( preg_match( "/^http:\/\/$/i", $avatar ) 
        or preg_match( "/[?&;]/", $avatar ) 
        or preg_match("#javascript:#is", $avatar ) 
        or !preg_match("#^https?://(?:[^<>*\"]+|[a-z0-9/\._\-!]+)$#iU", $avatar ) 
      )
      {
        $avatar='';
      }
      
      if( !empty($avatar) ) 
      {
        $img_size = @GetImageSize( $avatar );

        if($img_size == FALSE || !in_array($img_size['mime'], $TBDEV['allowed_ext']))
          stderr("{$lang['modtask_user_error']}", "{$lang['modtask_not_image']}");

        if($img_size[0] < 5 || $img_size[1] < 5)
          stderr("{$lang['modtask_user_error']}", "{$lang['modtask_image_small']}");
      
        if ( ( $img_size[0] > $TBDEV['av_img_width'] ) OR ( $img_size[1] > $TBDEV['av_img_height'] ) )
        { 
            $image = resize_image( array(
                             'max_width'  => $TBDEV['av_img_width'],
                             'max_height' => $TBDEV['av_img_height'],
                             'cur_width'  => $img_size[0],
                             'cur_height' => $img_size[1]
                        )      );
                        
          }
          else 
          {
            $image['img_width'] = $img_size[0];
            $image['img_height'] = $img_size[1];
          }
      
        $updateset[] = "av_w = " . $image['img_width'];
        $updateset[] = "av_h = " . $image['img_height'];
      }
      
      $modcomment = get_date( time(), 'DATE', 1 ) . "{$lang['modtask_avatar_change']}".htmlspecialchars($curavatar)."{$lang['modtask_to']}".htmlspecialchars($avatar)."{$lang['modtask_by']}" . $CURUSER['username'] . ".\n" . $modcomment;

      $updateset[] = "avatar = ".sqlesc($avatar);
    }
// === Enable / Disable chat box rights
    if ((isset($_POST['chatpost'])) && (($chatpost = $_POST['chatpost']) != $user['chatpost'])) {
        $modcomment = get_date( time(), 'DATE', 1 ) . " {$lang['modtask_chatpos']} " . sqlesc($chatpost) .
            " {$lang['modtask_by']} " . $CURUSER['username'] . ".\n" . $modcomment;
        $updateset[] = "chatpost = " . sqlesc($chatpost);
    }
// Set higspeed Upload Enable / Disable
    if ((isset($_POST['highspeed'])) && (($highspeed = $_POST['highspeed']) != $user['highspeed'])) {
        if ($highspeed == 'yes') {
            $modcomment = get_date( time(), 'DATE', 1 ) . " - Highspeed Upload enabled by " . $CURUSER['username'] .".\n" . $modcomment;
            $subject = sqlesc("Highspeed uploader status.");
            $msg = sqlesc("You  have been set as a high speed uploader by  " . $CURUSER['username'] .". You can now upload torrents using highspeeds without being flagged as a cheater  .");
            $added = sqlesc(time());
            mysql_query("INSERT INTO messages (sender, receiver, msg, subject, added) VALUES (0, $userid, $msg, $subject, $added)") or sqlerr(__file__, __line__);
        } elseif ($highspeed == 'no') {
            $modcomment = get_date( time(), 'DATE', 1 ) . " - Highspeed Upload disabled by " . $CURUSER['username'] .".\n" . $modcomment;
            $subject = sqlesc("Highspeed uploader status.");
            $msg = sqlesc("Your highspeed upload setting has been disabled by " . $CURUSER['username'] .". Please PM " . $CURUSER['username'] . " for the reason why.");
            $added = sqlesc(time());
            mysql_query("INSERT INTO messages (sender, receiver, msg, subject, added) VALUES (0, $userid, $msg, $subject, $added)") or sqlerr(__file__, __line__);
        } 
        else
        die(); // Error
        $updateset[] = "highspeed = " . sqlesc($highspeed);
        }
    /* Uncomment if you have the First Line Support mod installed...

    // Support
    if ((isset($_POST['support'])) && (($support = $_POST['support']) != $user['support']))
    {
    if ($support == 'yes')
    {
    $modcomment = gmdate("Y-m-d") . " - Promoted to FLS by " . $CURUSER['username'] . ".\n" . $modcomment;
    }
    elseif ($support == 'no')
    {
    $modcomment = gmdate("Y-m-d") . " - Demoted from FLS by " . $CURUSER['username'] . ".\n" . $modcomment;
    }
    else
    stderr("{$lang['modtask_user_error']}", "{$lang['modtask_try_again']}");

    $supportfor = $_POST['supportfor'];

    $updateset[] = "support = " . sqlesc($support);
    $updateset[] = "supportfor = ".sqlesc($supportfor);
    } */
// change freeslots
if ((isset($_POST['freeslots'])) && (($freeslots = $_POST['freeslots']) != ($curfreeslots = $user['freeslots'])))
{
    $modcomment = get_date(time(), 'DATE', 1)." - freeslots amount changed to '".$freeslots."' from '".
	$curfreeslots."' by " . $CURUSER['username'] . ".\n" . $modcomment;
}
$updateset[] = 'freeslots = '.sqlesc($freeslots);

/// Set Freeleech Status Time based
 if (isset($_POST['free_switch']) && ($free_switch =
    0 + $_POST['free_switch']))
{
    unset($free_pm);
    if (isset($_POST['free_pm']))
        $free_pm = $_POST['free_pm'];
    $subject = sqlesc('Notification!');
    $added = time();

    if ($free_switch == 255)
    {
        $modcomment = get_date($added, 'DATE', 1)." - Freeleech Status enabled by ".
		$CURUSER['username'].".\nReason: $free_pm\n".$modcomment;
        $msg = sqlesc("You have received Freeleech Status from ".$CURUSER['username'].($free_pm ?
            "\n\nReason: $free_pm" : ''));
        $updateset[] = 'free_switch = 1';
    } elseif ($free_switch == 42)
    {
        $modcomment = get_date($added, 'DATE', 1)." - Freeleech Status removed by ".
		$CURUSER['username'].".\n".$modcomment;
        $msg = sqlesc("Your Freeleech Status has been removed by ".
		$CURUSER['username'].".");
		$updateset[] = 'free_switch = 0';
    } else
    {
        $free_until = ($added + $free_switch * 604800);
        $dur = $free_switch.' week'.($free_switch > 1 ? 's' : '');
        $msg = sqlesc("You have received $dur Freeleech Status from ".
		$CURUSER['username'].($free_pm ? "\n\nReason: $free_pm" : ''));
        $modcomment = get_date($added, 'DATE', 1)." - Freeleech Status for $dur by ".
		$CURUSER['username'].".\nReason: $free_pm\n".$modcomment;
        $updateset[] = "free_switch = ".$free_until;
    }

    mysql_query("INSERT INTO messages (sender, receiver, subject, msg, added) 
	             VALUES (0, $userid, $subject, $msg, $added)") or sqlerr(__file__, __line__);
}
    // Add ModComment... (if we changed something we update otherwise we dont include this..)
	if (($CURUSER['class'] == UC_SYSOP && ($user['modcomment'] != $_POST['modcomment'] || $modcomment != $_POST['modcomment'])) || ($CURUSER['class'] < UC_SYSOP && $modcomment != $user['modcomment']))
	 $updateset[] = "modcomment = " . sqlesc($modcomment);

    mysql_query("UPDATE users SET " . implode(", ", $updateset) . " WHERE id=".sqlesc($userid)) or sqlerr(__FILE__, __LINE__);

    $returnto = $_POST["returnto"];
    header("Location: {$TBDEV['baseurl']}/$returnto");

    stderr("{$lang['modtask_user_error']}", "{$lang['modtask_try_again']}");
    }

stderr("{$lang['modtask_user_error']}", "{$lang['modtask_no_idea']}");

?>
