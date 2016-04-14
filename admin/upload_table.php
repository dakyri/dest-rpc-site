<?php
	error_reporting(3);
	import_request_variables("gpc");
	require_once("../common/necessary.php");
	require_once("../common/adminlib.php");
	require_once("../common/sqlschema_types.php");

	if ($db_u && $db_u != "") {
		$mysql = get_database($database_name, $database_host, $db_u, $db_p);
		if ($mysql <= 0) {
			errorpage("database $database_name on $database_host not".
			" accessible to user '$db_u' on this password\n<BR>".
			mysql_error());
		}
//		setcookie("db_passwd", $db_p);
//		setcookie("db_user", $db_u);
	} else {
		errorpage("No no no. Not Allowed!");
	}
	
function upload_table($mysql, $upload, $table, $fields)
{
	global	$action_msg;
	
	$dbinfo = file($upload);
	$i = 0;
	$n_fields = count($fields);
	for ($j=0; $j<$n_fields; $j++) {
		$$fields[$j] = "";
	}
	$up_cnt = 0;
	while ($i < sizeof($dbinfo)) {
		$dbline = $dbinfo[$i];
		$i++;
		$dbline = str_replace("'", "\'", $dbline);
		$dbline = str_replace("\n", "", $dbline);
		if (strlen($dbline) == 0 || $dbline[0] == " " || $i == sizeof($dbinfo)) {
			$up_cnt++;
			$set = "";
			for ($j=0; $j<$n_fields; $j++) {
				$set=set_item($set, $fields[$j], $$fields[$j]);
			}
			
			if ($set) {
				$query = "insert into $table set $set";
				$insert_result = mysql_query($query, $mysql);
				
				if (!$insert_result) {
					$err = mysql_errno();
					if ($err = MYSQL_ER_DUP_ENTRY) {
						$action_msg .= "Duplicate key on entry $up_cnt: ignored<br>";
					} else {
						errorpage("mysql error on insert: ".mysql_error());
					}
				}
			}
			for ($j=0; $j<$n_fields; $j++) {
				$$fields[$j] = "";
			}
		} else {
			for ($j=0; $j<$n_fields; $j++) {
				if ($x = match_db_item($dbline, $fields[$j])) {
					$$fields[$j] = $x;
					break;
				}
			}
		}
	}
	return true;
}


function upload_db_table($mysql, $upload, $table_name)
{
	$mvf = "../temp/updb";
	if (!move_uploaded_file($upload, $mvf)) {
		errorpage("Move uploaded from '$upload' to '$mvf' is a nono");
	}

	$fields = array();
	$query = "select * from $table_name";
	$result = mysql_query($query, $mysql);
	$i = 0;
	while ($i < mysql_num_fields($result)) {
	    $meta = mysql_fetch_field($result);
	    if ($meta) {
//	        echo "No information available<br />\n";
				$fields[] = $meta->name;
//blob:         $meta->blob
//max_length:   $meta->max_length
//multiple_key: $meta->multiple_key
//name:         $meta->name
//not_null:     $meta->not_null
//numeric:      $meta->numeric
//primary_key:  $meta->primary_key
//table:        $meta->table
//type:         $meta->type
//unique_key:   $meta->unique_key
//unsigned:     $meta->unsigned
//zerofill:     $meta->zerofill";
	    }
	    $i++;
	}

	
	$ret = upload_table($mysql, $mvf/*$upload*/, $table_name, $fields);
	return $ret;
}


	if ($edAction == "Upload") {
		if (upload_db_table($mysql, $HTTP_POST_FILES["upload"]["tmp_name"], $datapage_name)) {
			$action_msg .= "<B>Your upload of backup data to database '$database_name' appears to have been successful</B>";
		} else {
			errorpage("Upload client database error");
		}
	} 
	standard_page_top("Upload Databases from local text files", "../style/default.css", "page-noframe", "../images/title/upload_database_backup.gif", 560, 72, "Upload table data", "../common/necessary.js");
	br("all");
	echo $action_msg;
	
	if (file_exists("../$schema_base_directory/$schema_idx_name")) {
		$uploaded_schema = uncache_variable("../$schema_base_directory/$schema_idx_name");
	}
	if (!$uploaded_schema) {
		$uploaded_schema = array();
	}
?>
<p>
This option is used principally for installing backups, or moving the site.
</p>
<?php
	reset($uploaded_schema);
	while (list($key,$val) = each($uploaded_schema)) {
		$unc = uncache_variable("../$schema_base_directory/$key"."_tables.ser");
		if ($unc) {
			reset($unc);
			while (list($ukey, $uval) = each($unc)) {
				upload_form("uploadDataForm", "upload_table.php", $uval->name, "a local textfile", "edAction", "Upload", "blackvinyl",
					"Upload a text file of data into the $key database", "upload", "datapage_name");
			}
		}
  	}
	upload_form("uploadDataForm", "upload_table.php", people, "a local textfile", "edAction", "Upload", "blackvinyl",
					"Upload a text file of data into the people database", "upload", "datapage_name");
	page_bottom_menu();
	standard_page_bottom();
?>
