<?php
	import_request_variables("gpc");
	error_reporting(0);
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

function dump_table($mysql, $db_file, $table, $order, $fields)
{

	$query = "select * from $table";
	if ($order) {
		$query .= " order by " . $order;
	}

	$result = mysql_query($query, $mysql);
	if ($result == 0) {
		errorpage(mysql_error());
	} else {
		$nitems = mysql_num_rows($result);
	}
	$n_fields = count($fields);
	for($i=0; $i < $nitems; $i++) {
		$row = mysql_fetch_object($result);
		for ($j=0; $j<$n_fields; $j++) {
			dump_db_item($db_file, $row, $fields[$j]);
		}
		fwrite($db_file, "\n");
	}
	return true;
}

function dump_db_table($mysql, $db_file, $table_name, $order_str)
{
	$fields = array();
	$query = "select * from $table_name";
	$result = mysql_query($query, $mysql);
	$i = 0;
	while ($i < mysql_num_fields($result)) {
	    $meta = mysql_fetch_field($result);
	    if ($meta) {
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

	dump_table($mysql, $db_file, $table_name, $order_str, $fields);
	return true;
}




	if ($edAction == "Download") {
		$dbtxtname = tempnam("/tmp", "shop-");
		if (!($db_file = fopen($dbtxtname, "w+"))) {
			errorpage("Can't open temporary file '$dbtxtname' for output.<BR>");
		}
		switch($datapage_name) {
			case "people":

				if (!dump_db_table($mysql, $db_file, "people", "stnumber")) {
					errorpage("Download wierdass error");
				}
				break;
			default:
				if (!dump_db_table($mysql, $db_file, $datapage_name, "")) {
					errorpage("Download wierdass error");
				}
				break;
		}

		rewind($db_file);
		
		header("Content-Type: application/octet-stream");
		fpassthru($db_file);
		fclose($db_file);
		exit;
	}
	standard_page_top("Download databases from local text files", "../style/default.css", "page-noframe", "../images/title/make_database_backup.gif", 560, 72, "Download table data", "../common/necessary.js");
	br("all");
	
	if (file_exists("../$schema_base_directory/$schema_idx_name")) {
		$uploaded_schema = uncache_variable("../$schema_base_directory/$schema_idx_name");
	}
	if (!$uploaded_schema) {
		$uploaded_schema = array();
	}
?>
<P>This option is used principally for making backups, or moving the site.</p>
<?php
	reset($uploaded_schema);
	while (list($key,$val) = each($uploaded_schema)) {
		$unc = uncache_variable("../$schema_base_directory/$key"."_tables.ser");
		if ($unc) {
			reset($unc);
			while (list($ukey, $uval) = each($unc)) {
				$download_nm = "$uval->name-".date("d-m-y");
				download_form("download$uval->name"."Form", "download_table.php/$download_nm?", $uval->name,
						 "a local textfile", "edAction", "Download", "blackvinyl");
			}
		}
  	}
	$download_nm = "people-".date("d-m-y");
	download_form("downloadPeopleForm", "download_table.php/$download_nm?", "people",
			 "a local textfile", "edAction", "Download", "blackvinyl");
	page_bottom_menu();
	standard_page_bottom();
?>
