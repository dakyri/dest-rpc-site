<?php
//////////////////////////////////////////////////
// database edit: main wrapper.
// more general version than the specific user one
// most of the work done by schema_edit_framework
// this sets up globals for that, plus handles
// database opening/authorization
//////////////////////////////////////////////////
	error_reporting(3);
	import_request_variables("gp");
	if ($edit_pubs_for_user) {
		$posted_user = $edit_pubs_for_user;
		setcookie("edit_pubs_for_user", $edit_pubs_for_user);
	} else {
		$posted_user = false;
	}
	import_request_variables("c");
	if ($posted_user) {
		$edit_pubs_for_user = $posted_user;
	}

	$action_msg = "";

	require("../common/necessary.php");
	require("../common/adminlib.php");
	require("../common/sqlschema_types.php");
	
/////////////////////////////////////////////////////////
// database access
////////////////////////////////////////////////////////

	unset($login_user_code);
	unset($login_user_row);
	if ($db_u && $db_u != "") {
		$mysql = get_database($database_name, $database_host, $db_u, $db_p);
		if ($mysql <= 0) {
			errorpage("database $database_name on $database_host not accessible to user '$db_u' on this password\n<BR>".
			mysql_error());
		}
//		setcookie("db_passwd", $db_p);
//		setcookie("db_user", $db_u);
	} else {
		errorpage("No no no. Not Allowed!");
	}
	
	if ($edit_pubs_for_user) {
		$result = mysql_query("select * from people where stnumber='$edit_pubs_for_user'");
		if ($result > 0) {
			$nitems = mysql_num_rows($result);
			if ($nitems > 0) {
				$row = mysql_fetch_object($result);
				$login_user_code = $row->code;
				$login_user_row = $row;
				setcookie("edit_pubs_for_user", $edit_pubs_for_user);
			} else {
				$action_msg = "$edit_pubs_for_user not found<br>";
			}
		} else {
			errorpage("Mysql error on people database: ".mysql_error());
		}
	}
	
	if (!isset($login_user_code)) {
		$result = mysql_query("select * from people");
		if ($result > 0) {
			$nitems = mysql_num_rows($result);
			if ($nitems > 0) {
				$row = mysql_fetch_object($result);
				$login_user_row = $row;
			} else {
				errorpage("User database empty");
			}
		}
	}

	unset($row);
	if (file_exists("../$schema_base_directory/$schema_idx_name")) {
		$uploaded_schema = uncache_variable("../$schema_base_directory/$schema_idx_name");
	}
	if (!$uploaded_schema) {
		$uploaded_schema = array();
	}

	if (!isset($sqlschema) || !$sqlschema) {
		errorpage("Sorry. You can't edit a schema database without specifying a schema.");
	}
	if (!$uploaded_schema[$sqlschema]) {
		errorpage("Sorry. Schema '$sqlschema' is not registered with this system.");
	}
	
	$schema_tables = uncache_variable("../$schema_base_directory/$sqlschema"."_tables.ser");
	$schema_types = uncache_variable("../$schema_base_directory/$sqlschema"."_types.ser");
// global configuration variables relevant to the schema edit framework
	$schema_edit_form_action="ed_schema_db.php";
	$schema_edit_form_target="";
	$schema_edit_use_selector=true;
	$schema_edit_selector_text="Select publication format";
	$schema_edit_page_title = "Edit DEST Research Publications Database";
	$schema_edit_stylesheet = "../style/default.css";
	$schema_edit_body_style = "page-noframe";
	$schema_edit_title_img = "../images/title/edit_database.gif";
	$schema_edit_title_img_w = 560;
	$schema_edit_title_img_h = 72;
	$schema_edit_title_img_alt =  "Edit DEST Publications";
	$schema_edit_common_js = "../common/necessary.js";
	$schema_edit_upload_base = "../$upload_base";
	$schema_edit_framework_base = "../common/";
	$schema_edit_list_length = 6;
	$schema_edit_show_basic_instructions = true;
	$schema_edit_global_instructions = "";
	$schema_edit_insert_auto_increment_reqd = true;
	$schema_edit_insert_lock_timeout = 30;
	$schema_edit_columnar = true;	
	$schema_edit_columnar_label_width = "30%";

// global variables used by the schema
	$edit_author_stnumber = $login_user_row->stnumber;
	$admin_edit = true;
	
	$schema_edit_preamble = true;
	
function schema_edit_preamble_hook($sqlschema)
{
	global $login_user_row;
	global $edit_pubs_for_user;
	global $admin_edit;
	
	echo "<form action=\"$schema_edit_form_action\" method=\"post\">";
	hidden_field("sqlschema",$sqlschema);
	echo "<b>Edit publications for (staff/student number):</b>";
	text_input("edit_pubs_for_user", $edit_pubs_for_user?$edit_pubs_for_user:$login_user_row->stnumber, 15, 15, "form.submit()", "");
	echo "</form>\n";
}
	require("../common/sqlschema_edit_framework.php");
?>
