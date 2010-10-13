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

// xLeech .::. page verify
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

// session so that repeated access of this page cannot happen without the calling script.
//
// You use the create function with the sending script, and the check function with the
// receiving script...
//
// You need to pass the value of $task from the calling script to the receiving script. While
// this may appear dangerous, it still only allows a one shot at the receiving script, which
// effectively stops flooding.
// page verify by Retro
     
      class page_verify
      {
      function page_verify ()
      {
      if (session_id () == '')
      {
      session_start ();
      }
      }
    
      function create ($task_name = 'Default')
      {
      global $CURUSER;
      $_SESSION['Task_Time'] = time ();
      $_SESSION['Task'] = md5('user_id:' . $CURUSER['id'] . '::taskname-' . $task_name . '::' . $_SESSION['Task_Time']);
      $_SESSION['HTTP_USER_AGENT'] = $_SERVER['HTTP_USER_AGENT'];
      }
      
      function check ($task_name = 'Default')
      {
      global $CURUSER, $TBDEV, $lang;
      $returl = (isset($CURUSER)?htmlspecialchars($_SERVER['HTTP_REFERER']):$TBDEV['baseurl']."/login.php"); 
      $returl = str_replace('&', '&', $returl); 
      if (isset($_SESSION['HTTP_USER_AGENT']) && $_SESSION['HTTP_USER_AGENT'] != $_SERVER['HTTP_USER_AGENT'])
      stderr("Error", "Please resubmit the form. <a href='".$returl."'>Click HERE</a>",false);
      if ($_SESSION['Task'] != md5('user_id:' . $CURUSER['id'] . '::taskname-' . $task_name . '::' . $_SESSION['Task_Time']))
      stderr("Error", "Please resubmit the form. <a href='".$returl."'>Click HERE</a>",false);
      $this->create ();
      }
      }
?>
