<?php

import_request_variables("gp");
require_once("../common/necessary.php");
error_reporting(0);
	if (!$logout && $usr_p && $usr_u) {
		setcookie("usr_u", $usr_u, 0, "/destrpc/");
		setcookie("usr_p", $usr_p, 0, "/destrpc/");
		header("Location: user_ed_schema_db.php");
		exit();
	}
	import_request_variables("c");
	if ($logout) {
		setcookie("usr_u", "", 0, "/destrpc/");
		setcookie("usr_p", "", 0, "/destrpc/");
		unset($usr_u);
		unset($usr_p);
		unset($usr_p_e);
	} elseif ($usr_p_e && $usr_u) {
		header("Location: user_ed_schema_db.php");
		exit();
	}
	standard_page_top(($login_type=="admin")?"Administrative Logon":"User Logon", "../style/default.css", "page-noframe",
			($login_type=="admin")?"../images/title/database_admin.gif": "../images/title/database_admin.gif", 560, 72, "Logon", "../common/necessary.js");
	if (isset($login_msg) && $login_msg != "") {
		echo "<p>$login_msg</p>";
	}
?>
<FORM ACTION="user_ed_schema_db.php"
<?php // if ($login_type!="admin") echo " TARGET=\"mainFrame\" "; 
?>
	METHOD=POST NAME="logonForm"
	onSubmit="return true;">
<TABLE WIDTH=90% CELLSPACING=2 CELLPADDING=0>
 <TR><TD>
  <FONT SIZE=+0><B>User Name</B></FONT>
  <INPUT type="text" class="form-textin" NAME="usr_u" MAXLENGTH=128 SIZE=16>
 <TR><TD>
  <FONT SIZE=+0><B>Password</B></FONT>
  <INPUT type="password" class="form-textin" NAME="usr_p" MAXLENGTH=128 SIZE=16>
 <TR><TD>
  <INPUT TYPE=SUBMIT class="form-button" VALUE="Hi there"><BR>
</TABLE>
</FORM>
<p>If you are having trouble logging on, please enable cookies in your browser, and accept cookies from this site.
If difficulties persist, please contact the site administrator</p>
<SCRIPT LANGUAGE="javascript">
//	document.logonForm.usr_p.defaultValue = get_cookie("usr_p"); 
//	document.logonForm.usr_u.value = get_cookie("usr_u");
</SCRIPT>
<?php
	page_bottom_menu();
	standard_page_bottom();
?>
