<?php

import_request_variables("gp");
require_once("../common/necessary.php");
error_reporting(0);
	if (!$logout && $sch_p) {
		setcookie("sch_u", $sch_u, 0, "/destrpc/");
		setcookie("sch_p", $sch_p, 0, "/destrpc/");
		header("Location: schoolview.php");
		exit();
	}
	import_request_variables("c");
	if ($logout) {
		setcookie("sch_u", "", 0, "/destrpc/");
		setcookie("sch_p", "", 0, "/destrpc/");
		unset($sch_u);
		unset($sch_p);
		unset($sch_p_e);
	} elseif ($sch_p) {
		header("Location: schoolview.php");
		exit();
	}
	standard_page_top(($login_type=="admin")?"Administrative Logon":"User Logon", "../style/default.css", "page-noframe",
			($login_type=="admin")?"../images/title/database_admin.gif": "../images/title/database_admin.gif", 560, 72, "School Logon", "../common/necessary.js");
	if (isset($login_msg) && $login_msg != "") {
		echo "<p><b>$login_msg</b></p>";
	}
?>
<FORM ACTION="schoolview.php"
<?php // if ($login_type!="admin") echo " TARGET=\"mainFrame\" "; 
?>
	METHOD=POST NAME="logonForm"
	onSubmit="return true;">
<p>Welcome to the school administrator's view of the DEST Research Publications Database</p>
<TABLE WIDTH=90% CELLSPACING=2 CELLPADDING=0>
 <TR><TD>
  <FONT SIZE=+0><B>Please enter the school administrators password</B></FONT>
  </td><td>
  <input type="hidden" name="sch_u" value="<?php echo $dest_school_admin; ?>">
  <INPUT type="password" class="form-textin" NAME="sch_p" MAXLENGTH=128 SIZE=16>
  </td>
</TABLE>
</FORM>
<p>If you are having trouble logging on, please enable cookies in your browser, and accept cookies from this site.
If difficulties persist, please contact the site administrator</p>
<SCRIPT LANGUAGE="javascript">
//	document.logonForm.sch_p.defaultValue = get_cookie("sch_p"); 
//	document.logonForm.sch_u.value = get_cookie("sch_u");
</SCRIPT>
<?php
	standard_page_bottom();
?>
