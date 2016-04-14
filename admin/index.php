<?php
	import_request_variables("gpc");
	error_reporting(0);
	require_once("../common/necessary.php");
	if ($logout == "admin") {
		$db_u = "";
		$db_p = "";
	}
	if (!$db_u || $db_u == "" || !$db_p || $db_p == "") {
		setcookie("db_u", "", 0, "/destrpc/");
		setcookie("db_p", "", 0, "/destrpc/");
		header("Location: logon.php?login_type=admin");
		exit();
	}
	$mysql = get_database($database_name, $database_host, $db_u, $db_p);
	if ($mysql <= 0) {
		header("Location: logon.php?login_type=admin&login_msg=Invalid+login...");
		exit();
	}
	setcookie("db_u", $db_u, 0, "/destrpc/");
	setcookie("db_p", $db_p, 0, "/destrpc/");
	standard_page_top("DEST RPC: Admistrative Zone", "../style/default.css", "page-noframe", "../images/title/database_admin.gif", 560, 72, "DEST RPC Admin Zone", "../common/necessary.js");
	if (file_exists("../$schema_base_directory/$schema_idx_name")) {
		$uploaded_schema = uncache_variable("../$schema_base_directory/$schema_idx_name");
	}
	if (!$uploaded_schema) {
		$uploaded_schema = array();
	}
?>
<font size="3"><P>No doubt you want to administer some of this ...</p>
<ul>
<?php
	reset($uploaded_schema);
	while (list($key,$val) = each($uploaded_schema)) {
  		echo "<li><a href=\"ed_schema_db.php?sqlschema=$key\">Modify $key database</a></li>";
  	}
?>
  <li><a href="ed_people.php">Modify people database</a></li>
  <li><a href="upload_schema.php">Upload a new database schema</a></li>
  <li><a href="download_table.php">Download backup files from a database</a></li>
  <li><a href="upload_table.php">Upload backup files to a database</a></li>
  <li><a href="clear_table.php">Clear database tables</a></li>
</ul>
<?php
	page_bottom_menu();
	standard_page_bottom();
?>
