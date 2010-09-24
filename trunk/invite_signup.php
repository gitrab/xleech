<?php
require_once ("include/bittorrent.php");
require_once ("include/page_verify.php");
require_once ROOT_PATH.'/include/user_functions.php';
dbconn();


$lang = load_language('global');

$newpage = new page_verify(); 
$newpage->create('invite_signup');

$HTMLOUT='';
$res = mysql_query("SELECT COUNT(*) FROM users") or sqlerr(__FILE__, __LINE__);
$arr = mysql_fetch_row($res);
if ($arr[0] >= $TBDEV['maxusers'])
stderr("Sorry", "The current user account limit (" . number_format($TBDEV['maxusers']) . ") has been reached. Inactive accounts are pruned all the time, please check back again later...");

$HTMLOUT .="
Note: You need cookies enabled to sign up or log in.
<form method='post' action='{$TBDEV['baseurl']}/take_invite_signup.php'>
<table border='1' cellspacing='0' cellpadding='10'>
<tr><td align='right' class='heading'>Desired username:</td><td align='left'><input type='text' size='40' name='wantusername' /></td></tr>
<tr><td align='right' class='heading'>Pick a password:</td><td align='left'><input type='password' size='40' name='wantpassword' /></td></tr>
<tr><td align='right' class='heading'>Enter password again:</td><td align='left'><input type='password' size='40' name='passagain' /></td></tr>
<tr><td align='right' class='heading'>Enter invite-code:</td><td align='left'><input type='text' size='40' name='invite' /></td></tr>
<tr valign='top'><td align='right' class='heading'>Email address:</td><td align='left'><input type='text' size='40' name='email' />
<table width='250' border='0' cellspacing='0' cellpadding='0'><tr><td class='embedded'><font class='small'>The email address must be valid.
You will receive a confirmation email which you need to respond to. The email address won't be publicly shown anywhere.</font></td></tr>
</table>
</td></tr>
<tr><td align='right' class='heading'></td><td align='left'><input type='checkbox' name='rulesverify' value='yes' /> I will read the site rules page.<br />
<input type='checkbox' name='faqverify' value='yes' /> I agree to read the FAQ before asking questions.<br />
<input type='checkbox' name='ageverify' value='yes' /> I am at least 13 years old.</td></tr>
<tr><td colspan='2' align='center'><input type='submit' value='Sign up! (PRESS ONLY ONCE)' style='height: 25px' /></td></tr>
</table>
</form>";

print stdhead('Invites') . $HTMLOUT . stdfoot();
die;
?>
