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

// xLeech .::. validator
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

function validator($context){
       global $CURUSER;
       $timestamp = time();
       $hash = hash_hmac("sha1", $CURUSER['secret'], $context.$timestamp);
       return substr($hash, 0, 20).dechex($timestamp);
}
function validatorForm($context){
       return "<input type=\"hidden\" name=\"validator\" value=\"".validator($context)."\"/>";
}

function validate($validator, $context, $seconds = 0){
       global $CURUSER;
       $timestamp = hexdec(substr($validator, 20));
       if($seconds && time() > $timestamp + $seconds)
               return False;
       $hash=substr(hash_hmac("sha1", $CURUSER['secret'], $context.$timestamp), 0, 20);
       if (substr($validator, 0, 20) != $hash)
               return False;
       return True;
}
?>
