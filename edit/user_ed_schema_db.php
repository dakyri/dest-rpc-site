<?php
///////////////////////////////////////////////
// database edit: main wrapper.
// most of the work done by schema_edit_framework
// this sets up globals for that, plus handles
// database opening/authorization
//////////////////////////////////////////////////
	error_reporting(3);
	import_request_variables("gp");
	if ($edit_pubs_for_user) {
		$form_edit_pubs_for_user = $edit_pubs_for_user;
	}
	import_request_variables("c");
	$edit_pubs_for_user = $form_edit_pubs_for_user;
	if (!isset($action_msg)) {
		$action_msg = "";
	}

	require("../common/necessary.php");
	require("../common/adminlib.php");
	require("../common/sqlschema_types.php");
	require("../private/local.php");
	
/////////////////////////////////////////////////////////
// database access
////////////////////////////////////////////////////////
	unset($login_user_code);
	unset($login_user_row);
	if ($usr_u && $usr_u != "" && ($usr_p && $usr_p != "")) {
		$mysql = get_database(
						$database_name,
						$database_host,
						$database_pleb_user,
						$database_pleb_passwd);
		if ($mysql > 0) {
			$query = "select * from people where stnumber='$usr_u'";
			$result = mysql_query($query);
			if ($result > 0) {
				$nitems = mysql_num_rows($result);
				if ($nitems > 0) {
					$row = mysql_fetch_object($result);
					if ($row->passwd == md5($usr_p)) {
						setcookie("usr_u", $usr_u, 0, "/destrpc/");
						setcookie("usr_p", $usr_p, 0, "/destrpc/");
						$login_user_row = $row;
					}
				}
			}
		}
	}
	
	if (!isset($login_user_row)) {
//		setcookie("usr_p_enc", md5($usr_p));
		header("Location: index.php");
		exit();
	}
	if ($database_mod_user != "") {
		mysql_close($mysql);
		$mysql = get_database($database_name, $database_host, $database_mod_user, $database_mod_passwd);
		if ($mysql <= 0) {
			errorpage("database $database_name on $database_host not accessible to user '$db_u' on this password\n<BR>".
			mysql_error());
		}
//		setcookie("db_passwd", $db_p);
//		setcookie("db_user", $db_u);
	} else {
		errorpage("No no no. Not Allowed!");
	}
	if (file_exists("../$schema_base_directory/$schema_idx_name")) {
		$uploaded_schema = uncache_variable("../$schema_base_directory/$schema_idx_name");
	}
	if (!$uploaded_schema) {
		$uploaded_schema = array();
	}

	if (!isset($sqlschema) || !$sqlschema) {
		reset($uploaded_schema);
		list($sqlschema,$schemaregfile) = each($uploaded_schema);
		if (!$sqlschema) {
			errorpage("It appears no publication formats have been uploaded.<br>Please contact the administrator of this system.");
		}
	}
	if (!$uploaded_schema[$sqlschema]) {
		errorpage("Sorry. Schema '$sqlschema' is not registered with this system.");
	}
	
	unset($row); // important for php expression in setting base

	$schema_tables = uncache_variable("../$schema_base_directory/$sqlschema"."_tables.ser");
	$schema_types = uncache_variable("../$schema_base_directory/$sqlschema"."_types.ser");
	
	$schema_edit_form_action="user_ed_schema_db.php";
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
	$schema_edit_upload_base = "../$upload_base/";
	$schema_edit_framework_base = "../common/";
	$schema_edit_list_length = 6;
	$schema_edit_show_basic_instructions = true;
	$schema_edit_global_instructions = "";
	$schema_edit_insert_auto_increment_reqd = true;
	$schema_edit_insert_lock_timeout = 30; //  only needed if need the auto_increment value
	$schema_edit_columnar = true;
	$schema_edit_columnar_label_width = "30%";
	
	$edit_author_stnumber = $login_user_row->stnumber;	// set automatically as an expression attr in sqlschema
	$edit_author_row = $login_user_row;
	$schema_edit_preamble = true;
	
function schema_edit_preamble_hook($sqlschema)
{
	global $login_user_row;
	global $edit_pubs_for_user;
	global $admin_edit;
	
	echo "Currently logged in as <b>$login_user_row->stnumber</b>...<br>";
	if ($admin_edit) {
		echo "<form action=\"$schema_edit_form_action\" method=\"post\">";
		hidden_field("sqlschema",$sqlschema);
		echo "<b>Edit publications for (staff/student number):</b>";
		text_input(
			"edit_pubs_for_user",
			$edit_pubs_for_user?$edit_pubs_for_user:$login_user_row->stnumber,
			15, 15, "form.submit()",
			"");
		echo "</form>";
	}
}

	$admin_edit = ($login_user_row->kind == "admin");

	if ($admin_edit) {
		if ($edit_pubs_for_user) {
			$result = mysql_query("select * from people where stnumber='$edit_pubs_for_user'");
			if ($result > 0) {
				$nitems = mysql_num_rows($result);
				if ($nitems > 0) {
					$u_row = mysql_fetch_object($result);
					$edit_author_row = $u_row;
					setcookie("edit_pubs_for_user", $edit_pubs_for_user);
				} else {
					$action_msg = "$edit_pubs_for_user not found<br>";
				}
			} else {
				errorpage("Mysql error on people database: ".mysql_error());
			}
		} else {
			$action_msg = "user not set<br>";
		}
			
		$edit_author_stnumber = $edit_author_row->stnumber;
	}
	require("../common/sqlschema_edit_framework.php");
?>
