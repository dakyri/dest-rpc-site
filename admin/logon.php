<?php

import_request_variables("gp");
require_once("../common/necessary.php");

if (!$login_type || $login_type == "") {
	$login_type = "user";
}
if ($login_type == "user" && $usr_p && $usr_u) {
	$mysql = get_database(
			$database_name,
			$database_host,
			$database_pleb_user,
			$database_pleb_passwd);
	if ($mysql > 0) {
		$query = "select * from people where name='$usr_u'";
		$result = mysql_query($query);
		if ($result > 0) {
			$nitems = mysql_num_rows($result);
			if ($nitems > 0) {
				$row = mysql_fetch_object($result);
				if ($row->passwd == md5($usr_p)) {
// yay... someone's on our team
					setcookie("usr_u", $usr_u, 0, "/destrpc/");
					setcookie("usr_p_enc", md5($usr_p), 0, "/destrpc/");
					header("Location: index.php");
					exit();
				}
			}
		}
	}
}
	import_request_variables("c");
	standard_page_top(($login_type=="admin")?"Administrative Logon":"User Logon", "../style/default.css", "page-noframe",
			"../images/title/database_admin.gif", 560, 72, "Logon", "../common/necessary.js");
	if (isset($login_msg) && $login_msg != "") {
		echo "<p>$login_msg</p>";
	}
?>
<FORM ACTION="<?php echo ($login_type=="admin")?"index.php":"logon.php"; ?>"
	<?php // if ($login_type!="admin") echo " TARGET=\"mainFrame\" "; 
	?>
	METHOD=POST NAME="logonForm"
	onSubmit="return true;"
>
<TABLE WIDTH=90% CELLSPACING=2 CELLPADDING=0>
 <TR><TD>
  <FONT SIZE=+0><B>Database User Name</B></FONT>
  <INPUT type="text" class="form-textin" NAME="db_u" MAXLENGTH=128 SIZE=16>
 <TR><TD>
  <FONT SIZE=+0><B>Database Password</B></FONT>
  <INPUT type="password" class="form-textin" NAME="db_p" MAXLENGTH=128 SIZE=16>
 <TR><TD>
  <INPUT TYPE=SUBMIT class="form-button" VALUE="Administrate This">
</TABLE>
</FORM>
<SCRIPT LANGUAGE="javascript">
	document.logonForm.db_p.defaultValue = get_cookie("db_p"); 
	document.logonForm.db_u.value = get_cookie("db_u");
</SCRIPT>
<?php
	page_bottom_menu();
	standard_page_bottom();
?>