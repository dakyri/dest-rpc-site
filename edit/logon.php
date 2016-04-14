<?php

import_request_variables("gp");
require_once("../common/necessary.php");
error_reporting(0);

	if (!$login_type || $login_type == "") {
		$login_type = "user";
	}
	if ($login_type == "user" && $usr_p && $usr_u) {
		setcookie("usr_u", $usr_u);
		setcookie("usr_p", $usr_p);
//					setcookie("usr_p_enc", md5($usr_p));
		header("Location: user_ed_schema_db.php");
		exit();
	}
	import_request_variables("c");
	standard_page_top(($login_type=="admin")?"Administrative Logon":"User Logon", "../style/default.css", "page-noframe",
			($login_type=="admin")?"../images/title/database_admin.gif": "../images/title/database_admin.gif", 560, 72, "Logon", "../common/necessary.js");
	if (isset($login_msg) && $login_msg != "") {
		echo "<p>$login_msg</p>";
	}
?>
<FORM ACTION="<?php echo ($login_type=="admin")?"index.php":"user_ed_schema_db.php"; ?>"
	<?php // if ($login_type!="admin") echo " TARGET=\"mainFrame\" "; 
	?>
	METHOD=POST NAME="logonForm"
	onSubmit="return true;"
>
<TABLE WIDTH=90% CELLSPACING=2 CELLPADDING=0>
 <TR><TD>
<?php if ($login_type=="admin"): ?>
  <FONT SIZE=+0><B>Database User Name</B></FONT>
  <INPUT type="text" class="form-textin" NAME="db_u" MAXLENGTH=128 SIZE=16>
<?php	else: ?>
  <FONT SIZE=+0><B>User Name</B></FONT>
  <INPUT type="text" class="form-textin" NAME="usr_u" MAXLENGTH=128 SIZE=16>
<?php endif; ?>
 <TR><TD>
<?php if ($login_type=="admin"): ?>
  <FONT SIZE=+0><B>Database Password</B></FONT>
  <INPUT type="password" class="form-textin" NAME="db_p" MAXLENGTH=128 SIZE=16>
<?php	else: ?>
  <FONT SIZE=+0><B>Password</B></FONT>
  <INPUT type="password" class="form-textin" NAME="usr_p" MAXLENGTH=128 SIZE=16>
<?php endif; ?>
 <TR><TD>
<?php if ($login_type=="admin"): ?>
  <INPUT TYPE=SUBMIT class="form-button" VALUE="Administrate This">
<?php	else: ?>
  <INPUT TYPE=SUBMIT class="form-button" VALUE="Hi there"><BR>
<?php endif; ?>
</TABLE>
</FORM>

<SCRIPT LANGUAGE="javascript">
<?php if ($login_type=="admin"): ?>
	document.logonForm.db_p.defaultValue = get_cookie("db_p"); 
	document.logonForm.db_u.value = get_cookie("db_u");
<?php	else: ?>
	document.logonForm.usr_p.defaultValue = get_cookie("usr_p"); 
	document.logonForm.usr_u.value = get_cookie("usr_u");
<?php endif; ?>
</SCRIPT>
<?php
	standard_page_bottom();
?>
