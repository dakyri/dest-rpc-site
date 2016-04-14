<?php
	error_reporting(3);
	import_request_variables("gpc");

	$action_msg = "";

	require_once("../common/necessary.php");
	require_once("../common/adminlib.php");
	require_once("../common/sqlschema_types.php");
	require_once("../common/parse_sqlschema.php");

	if ($db_u && $db_u != "") {
		$mysql = get_database($schema_database_name, $database_host, $db_u, $db_p);
		if ($mysql <= 0) {
			errorpage("database $schema_database_name on $database_host not".
			" accessible to user '$db_u' on this password\n<BR>".
			mysql_error());
		}
//		setcookie("db_passwd", $db_p);
//		setcookie("db_user", $db_u);
	} else {
		errorpage("No no no. Not Allowed!");
	}
	
function upload_schema($mysql, $filename, $schemaname, $schemabase, $schema_database, $schema_master, $mode)
{
	global	$action_msg;
	global	$schema_tables;
	global	$schema_types;
	
	global	$update_msg;

	if (!($fp = fopen($filename, "r"))) {
		errorpage("Can't open '$filename' for input");
	}
	if (!($parser = new_sqlschema_parser($schemabase))) {
		errorpage("Can't create parser");
	}
	if ($err=parse_sqlschema($parser, $fp)) {	// at least try and parse it
		errorpage($err);
	}
	
	$update_msg = "";

	if ($mode>=1) {	// do an xml upload.
		// save uploaded file to templates
		$schema_xml_filename = "$schemabase/$schemaname.xsql";
		if (!($xmlsave_fp = fopen($schema_xml_filename, "w"))) {
			errorpage("Can't create template file '$schema_xml_filename'");
		}
		fseek($fp, 0);
		while (($str = fread($fp, 1024)) != '') {
			fwrite($xmlsave_fp, $str, strlen($str));
		}
		fclose($xmlsave_fp);
		@chmod($schema_xml_filename, 0664);
		$update_msg .= "<p>Saved schema '$schema_xml_filename'</p>";
		if ($mode >= 2) {	// regenerate variables associated with this schema
		
			if (cache_variable($schema_tables, "$schemabase/$schemaname"."_tables.ser")) {
				$update_msg .= "<p>Cached schema table variables, $schemabase/$schemaname"."_tables.ser</p>";
			} else {
				$update_msg .= "<p>Failed to cache schema table variables</p>";
			}
			if (cache_variable($schema_types, "$schemabase/$schemaname"."_types.ser")) {
				$update_msg .= "<p>Cached schema type variables, $schemabase/$schemaname"."_types.ser</p>";
			} else {
				$update_msg .= "<p>Failed to cache schema type variables</p>";
			}
			
			if (file_exists("$schemabase/$schema_master")) {
				$uploaded_schema = uncache_variable("$schemabase/$schema_master");
			} else {
				echo "not exists","$schemabase/$schema_master";
			}
			
			if (!$uploaded_schema) {
				$uploaded_schema = array();
			}
			$uploaded_schema[$schemaname] = "$schemabase/$schemaname"."_tables.ser";
			if (cache_variable($uploaded_schema, "$schemabase/$schema_master")) {
				$update_msg .= "<p>Added '$schemaname' to master schema index</p>";
			} else {
				$update_msg .= "<p>Failed to add '$schemaname' to master schema index</p>";
			}
			
			if ($mode >= 3) {	// create associated tables
				require("../common/sqlschema_creat.php");			
			} else {
// at least try and check out all associated enum and set are still aok
// 
//				require("../common/sqlschema_mod_set_enum.php");			
			}
				
		}
	}
	
	return true;
}


	if (isset($schemaAction) && $schemaAction) {
		$tmp_name = $HTTP_POST_FILES["upload"]["tmp_name"];
		$org_name = $HTTP_POST_FILES["upload"]["name"];
		$typ_name = $HTTP_POST_FILES["upload"]["type"];
		$ulerrc = $HTTP_POST_FILES["upload"]["error"];
		$size = $HTTP_POST_FILES["upload"]["size"];
//		echo "$tmp_name $typ_name $org_name, err = $ulerrc, size = $size<br>";
		$sch_name = basename($org_name, ".xsql");
		switch($uploadMode) {
			case "upload":
				if (upload_schema($mysql, $tmp_name, $sch_name, "../$schema_base_directory", $schema_database_name, $schema_idx_name, 1)) {
					$action_msg .= $update_msg;
					$action_msg .= "<B>Your upload of database schema '$org_name' appears to have been successful</B>";
				} else {
					errorpage("Upload database schema error");
				}
				break;
				
			case "refresh":
				if (upload_schema($mysql, $tmp_name, $sch_name, "../$schema_base_directory", $schema_database_name, $schema_idx_name, 2)) {
					$action_msg .= $update_msg;
					$action_msg .= "<B>Your refresh from database schema '$org_name' appears to have been successful</B>";
				} else {
					errorpage("Upload database schema error");
				}
				break;
				
			case "build":
				if (upload_schema($mysql, $tmp_name, $sch_name, "../$schema_base_directory", $schema_database_name, $schema_idx_name, 3)) {
					$action_msg .= $update_msg;
					$action_msg .= "<B>Your upload and build from database schema '$org_name' appears to have been successful</B>";
				} else {
					errorpage("Upload database schema error");
				}
				break;
				
			default:
				$action_msg = "Unusual request $uploadMode";
				break;
		}
	} 
	standard_page_top("Upload database schema from local xml files",
				 "../style/default.css", "page-noframe",
				 "../images/title/upload_schema.gif", 560, 72, "Upload database schema",
				 "../common/necessary.js");
	br("all");
	echo $action_msg;
?>
<P>
This option is used for uploading new database template schema files, or refreshing caches associated with uploaded schemas.
</p>
<P>
These are XML files defining the basic database layout, edit text, and usage information for tables in this database. 
</p>

<?php
	upload_form("rebuildSchemaForm", "upload_schema.php", "build", "a local xml file", "schemaAction", "Build", "blackvinyl",
			"Upload xml database schema, completely building required tables", "upload", "uploadMode",
			"Completely regenerating the database form requires a certain amount of caution. Also endure the database is backed up, clear the current database, regenerate it, and then reload the old data");
	br();
	br();
	upload_form("refreshSchemaForm", "upload_schema.php", "refresh", "a local xml file", "schemaAction", "Refresh", "blackvinyl",
			"Upload xml database schema, refreshing cache variables only", "upload", "uploadMode",
			"This is the option typically necessary for changing comments and embedded php actions. Use only when not modifying the database per se");
	br();
	br();
	upload_form("uploadSchemaForm", "upload_schema.php", "upload", "a local xml file", "schemaAction", "Upload", "blackvinyl",
			"Upload xml database schema data to the site", "upload", "uploadMode",
			"This places a support file (such as xml definitions) in the appropriate place in the server. To be used these definitions still have to be processed by one of the options above");
	page_bottom_menu();
	standard_page_bottom();
?>
