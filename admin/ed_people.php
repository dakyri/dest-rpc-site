<?php
	error_reporting(0);
	import_request_variables("gpc");
	require("../common/necessary.php");
	require("../common/adminlib.php");

	$action_msg = "";
	$login_user_code = NULL;
	
	if ($db_u && $db_p) {
		$mysql = get_database($database_name, $database_host, $db_u, $db_p);
		if ($mysql <= 0) {
			errorpage("database $database_name on $database_host not accessible to user '$db_u' on this password\n<BR>".
			mysql_error());
		}
	} else if ($usr_u && $usr_p) {
		$mysql = get_database(
						$database_name,
						$database_host,
						$database_pleb_user,
						$database_pleb_passwd);
	//	echo "msq $mysql<br>";
		if ($mysql > 0) {
			$query = "select * from people where stnumber='$usr_u'";
			$result = mysql_query($query);
			if ($result > 0) {
				$nitems = mysql_num_rows($result);
				if ($nitems > 0) {
					$row = mysql_fetch_object($result);
					if ($row->passwd == md5($usr_p)) { // cookies should be set then
						require("../private/local.php");
						$login_user_code = $row->code;
						$key = $login_user_code;
						mysql_close($mysql);
						$mysql = get_database(
										$database_name,
										$database_host,
										$database_mod_user,
										$database_mod_passwd);
						if (!$mysql) {
							errorpage("Database error. ".mysql_error());
						}
					} else {
						errorpage("Not permitted. Password for $usr_u is incorrect");
					}
				} else {
					errorpage("Not permitted. User $usr_u doesn't exist in this system");
				}
				mysql_free_result($result);
			} else {
				errorpage("Not permitted. User $usr_u doesn't exist in this system");
			}
		} else {
			errorpage("System not accessible: database error:<br>".mysql_error());
		}
	} else {
		errorpage("Not permitted. Please log on with the correct username and password!");
	}

	$kind_values = fetch_type_values("people", "kind");
	$gender_values = fetch_type_values("people", "gender");
	$properties_values = fetch_type_values("people", "properties");

	if (isset($edAction)) {
		if ($edAction == "Create people") {
			$query = "create table people (".
					"code int not null auto_increment primary key,".
					"stnumber tinytext,".
					"passwd tinytext,".
					"surname tinytext,".
					"firstname tinytext,".
					"title tinytext,".
					"kind enum('staff','student','admin'),".
					"gender enum('M','F'),".
					"properties set('author'),".
					"location tinytext,".
					"address text,".
					"city tinytext,".
					"postcode tinytext,".
					"phone tinytext,".
					"mobile tinytext,".
					"fax tinytext,".
					"web tinytext,".
					"email tinytext,".
					"description text)";
			$create_result = mysql_query($query);
			if (!$create_result) {
				errorpage("create error ".mysql_error());
			}
			$kind_values = fetch_type_values("people", "kind");
			$gender_values = fetch_type_values("people", "gender");
			$properties_values = fetch_type_values("people", "properties");
		} elseif ($edAction == "Delete items" && sizeof($del) > 0) {
			$query = "delete from people where code in (".list_string($del).")";
			$delete_result = mysql_query($query);
			if (!$delete_result) {
				errorpage(mysql_error());
			}
		} elseif ($edAction=="Modify selected" || ($login_user_code && $edAction=="Submit changes")) {
			$set = "";
			if ($stnumber) $set = set_item($set, "stnumber", strtolower($stnumber));
			$set = set_item($set, "surname", $surname);
			$set = set_item($set, "firstname", $firstname);
			$set = set_item($set, "title", $title);
			if (isset($passwd)) {
				if ($passwd != "") {
					$set = set_item($set, "passwd", md5($passwd));
				}
			} else if (isset($enc_passwd)) {
				if ($enc_passwd != "") {
					$set = set_item($set, "passwd", $enc_passwd);
				}
			}
			if ($kind) $set = set_item($set, "kind", $kind);
			$set = set_item($set, "gender", $gender);
			$set = set_item($set, "location", $location);
			$set = set_item($set, "address", $address);
			$set = set_item($set, "city", $city);
			$set = set_item($set, "postcode", $postcode);
			$set = set_item($set, "phone", $phone);
			$set = set_item($set, "mobile", $mobile);
			$set = set_item($set, "fax", $fax);
			$set = set_item($set, "web", $web);
			$set = set_item($set, "email", $email);
			$set = set_item($set, "properties", list_string($property));
			$set = set_item($set, "description", $description);
			$where = "code = $key";
			if ($set && $where) {
				$query = "update people set $set where $where";
//				echo "modify = $query<BR>";
				$update_result = mysql_query($query);
				if (!$update_result) {
					errorpage("Update error ".mysql_error());
				}
			}
		} elseif ($edAction == "Insert item") {
			$set = "";
			if ($stnumber) $set = set_item($set, "stnumber", strtolower($stnumber));
			$set = set_item($set, "surname", $surname);
			$set = set_item($set, "firstname", $firstname);
			$set = set_item($set, "title", $title);
			if (isset($passwd)) {
				if ($passwd != "") {
					$set = set_item($set, "passwd", md5($passwd));
				}
			} else if (isset($enc_passwd)) {
				if ($enc_passwd != "") {
					$set = set_item($set, "passwd", $enc_passwd);
				}
			}
			if ($kind) $set = set_item($set, "kind", $kind);
			$set = set_item($set, "gender", $gender);
			$set = set_item($set, "location", $location);
			$set = set_item($set, "address", $address);
			$set = set_item($set, "city", $city);
			$set = set_item($set, "postcode", $postcode);
			$set = set_item($set, "phone", $phone);
			$set = set_item($set, "mobile", $mobile);
			$set = set_item($set, "fax", $fax);
			$set = set_item($set, "web", $web);
			$set = set_item($set, "email", $email);
			$set = set_item($set, "properties", list_string($property));
			$set = set_item($set, "description", $description);
			$where = "code = $key";
			if ($set) {
				$query = "insert into people set $set";
//				echo "insert = $query<BR>";
				$insert_result = mysql_query($query);
				if (!$insert_result) {
					errorpage(mysql_error());
				}
			}
		} elseif ($edAction == "Modify kind") {
			$kind_values = modify_enumerated(
					"people", "kind", "enum",
					$kind_values, $add_property, $del_property);
		} elseif ($edAction == "Modify property") {
			$properties_values = modify_enumerated(
					"people", "properties", "set",
					$properties_values, $add_property, $del_property);
		}
	}
	standard_page_top($login_user_code?"Edit personal details":"Edit The People List", "../style/default.css", "page-noframe",
					$login_user_code?"../images/title/edit_personal.gif":"../images/title/edit_people.gif", 560, 72,
					$login_user_code?"Edit Personal Details":"Edit the People List", "../common/necessary.js");
	if ($action_msg)
		echo $action_msg;

	if ($login_user_code) {
		$query = "select * from people where code='$login_user_code'";
	} else {
		$query = "select * from people";
		$order = "stnumber";
		if ($order) {
			$query .= " order by " . $order;
		}
		if ($g_page_len > 0) {
			if (! $pageno || $pageno < 1)
				$pageno = 1;
			$offset = $g_page_len * ($pageno-1);
			$query .= " limit $offset, $g_page_len ";
		}
	}


	$result = mysql_query($query);
	if ($result == 0) {
		if (mysql_errno() == MYSQL_ER_NO_SUCH_TABLE) {
			createtable_form("createPeople", "ed_people.php", "user list", "Create people", "edAction", "", "");
			page_bottom_menu();
			standard_page_bottom();
			exit;
		} else {
			echo "<br>OOps: ",mysql_error(), "<br>";
			$nitems = 0;
		}
	} else {
		$nitems = mysql_num_rows($result);
	}
?>

<SCRIPT LANGUAGE="javascript">

function display_selected(sel)
{
	document.edPeopleForm.key.value = catalog_data[sel][0];

<?php if (!$login_user_code): ?>
	document.edPeopleForm.stnumber.value = catalog_data[sel][1];
<?php endif; ?>
	document.edPeopleForm.enc_passwd.value = catalog_data[sel][2];
	document.edPeopleForm.firstname.value = catalog_data[sel][3];
	document.edPeopleForm.surname.value = catalog_data[sel][4];
	document.edPeopleForm.title.value = catalog_data[sel][5];
<?php if (!$login_user_code): ?>
	document.edPeopleForm.kind.selectedIndex = catalog_data[sel][6];
<?php endif; ?>
	document.edPeopleForm.gender.selectedIndex = catalog_data[sel][7];
// properties 3
	typz = catalog_data[sel][8].split(",");
	obj = document.edPeopleForm;
	for (i=0; i<obj.elements.length; i++) {
		if (obj.elements[i].name == "property[]") {
			obj.elements[i].checked = false;
			for (j=0; j<typz.length; j++) {
				if (obj.elements[i].value == typz[j]) {
					obj.elements[i].checked = true;
				}
			}
		}
	}
	document.edPeopleForm.location.value = catalog_data[sel][9];
	document.edPeopleForm.address.value = catalog_data[sel][10];
	document.edPeopleForm.phone.value = catalog_data[sel][11];
	document.edPeopleForm.fax.value = catalog_data[sel][12];
	document.edPeopleForm.mobile.value = catalog_data[sel][13];
	document.edPeopleForm.email.value = catalog_data[sel][14];
	document.edPeopleForm.web.value = catalog_data[sel][15];
	document.edPeopleForm.city.value = catalog_data[sel][16];
	document.edPeopleForm.postcode.value = catalog_data[sel][17];

	document.edPeopleForm.description.value = catalog_data[sel][18];
}

// reflect the database...
// obviously, for big db's, we'll only reflect the current window's worth
catalog_data = [
<?php
	if ($nitems > 0)
		mysql_data_seek($result, 0);
	for($i=0; $i < $nitems; $i++) {
		$row = mysql_fetch_object($result);
		echo "['$row->code',",
			"'",quothi($row->stnumber),"',",
			"'",quothi($row->passwd),"',",
			"'",quothi($row->firstname),"',",
			"'",quothi($row->surname),"',",
			"'",quothi($row->title),"',",
			index_of($row->kind, $kind_values),",",
			index_of($row->gender, $gender_values),",",
			"'$row->properties',",
			"'",quothi($row->location),"',",
			"'",quothi($row->address),"',",
			" '$row->phone', '$row->fax', '$row->mobile',",
			" '$row->email', '$row->web',",
			" '$row->city', '$row->postcode','",
			quothi($row->description),"' ]";
			if ($i < $nitems-1) {
				echo ",\n";
			}
	}
?>
];
window.focus();

</SCRIPT>

<?php
	if (!$login_user_code) {
		paginator("people", $mysql, $g_page_len, $pageno, "edclients.php", "pageno", "#1111aa", "#000000", "name", "");
	}
?>
<FORM ACTION="ed_people.php" NAME="edPeopleForm" METHOD="POST">
 <div class="edit-controls">
 <TABLE ALIGN="center" CELLPADDING=2 CELLSPACING=0>
  <TR>
<?php if (!$login_user_code): ?>
   <TD><B><INPUT TYPE=SUBMIT NAME="edAction" VALUE="Delete items"></B>
   <TD><B><INPUT TYPE=SUBMIT NAME="edAction" VALUE="Modify selected"></B>
   <TD><B><INPUT TYPE=SUBMIT NAME="edAction" VALUE="Insert item"></B>
<?php else: ?>
   <TD><B><INPUT TYPE=SUBMIT NAME="edAction" VALUE="Submit changes"></b>
<?php endif; ?>
   <TD><B><INPUT TYPE=RESET NAME="edAction" VALUE="Clear Fields"></b>
  </TABLE></div>
<?php if (!$login_user_code): ?>
<TABLE WIDTH=90% ALIGN=CENTER BORDER=0 CELLSPACING=0 CELLPADDING=0>
<TR BGCOLOR="#ffffff">
<TH ALIGN=LEFT><FONT SIZE=-1>d</FONT>
<TH ALIGN=LEFT><FONT SIZE=-1>s</FONT>
<TH ALIGN=LEFT WIDTH=15%><FONT SIZE=-1>Staff/student number</FONT>
<TH ALIGN=LEFT><FONT SIZE=-1>Name</FONT>
<TH ALIGN=LEFT><FONT SIZE=-1>kind</FONT>
<TH ALIGN=LEFT><FONT SIZE=-1>email</FONT>
</TR>
<?php
	if ($nitems > 0)
		mysql_data_seek($result, 0);
		
	for($i=0; $i < $nitems; $i++) {
		$row = mysql_fetch_object($result);
		echo "<TR VALIGN=CENTER>\n";
		if (index_of($row->code, $del) >= 0) {
			$td = "<INPUT TYPE=CHECKBOX name='del[]' value=$row->code CHECKED>\n";
		} else {
			$td = "<INPUT TYPE=CHECKBOX name='del[]' value=$row->code>\n";
		}
		table_data_string($td, "LEFT", 1);
		$td = "<INPUT TYPE=RADIO name='selected' value=$i VALIGN=CENTER";
		if ($i == $selected) {
			 $td .= " CHECKED";
		} else {
		}
		$td .= " onClick=\"display_selected($i);\">\n";
		table_data_string($td, "LEFT", 1);

  		table_data_string($row->stnumber, "LEFT", 10);
		table_data_string("$row->title $row->firstname $row->surname", "LEFT", 20);
		table_data_string($row->kind, "LEFT", 10);
    	table_data_string($row->email, "LEFT");
		echo "</TR>";
	}
?>
</TABLE><BR>
<?php	endif; ?>

<TABLE>
<TR>
   <TABLE><TR>
    <td><B>Title</B><BR><input type="text" name="title" size="3"></td>
    <td><B>First Name(s)</B><BR><input type="text" name="firstname" size="20"></td>
    <td><B>Surname</B><BR><input type="text" name="surname" size="20"></td>
   </TABLE>
<TR>
 <TABLE>
  <TR>
<?php if (!$login_user_code): ?>
   <TD><B>Staff/Student Number</B><BR><INPUT TYPE=TEXT NAME="stnumber"  MAXLENGTH=48 SIZE=20></TD>
<? endif; ?>
   <td colspan=2><B>Password</B><BR>
  The password is an arbitrary length string of printable characters. Leave this field blank if you do not wish to change it.<BR>
   <input type="password" name="passwd" size="20"><input type="hidden" name="enc_passwd" size="20"></td>
  <tr>
   <TD><B>Gender</B><BR><?php	select_array("gender", $gender_values); ?></TD>
<?php if (!$login_user_code): ?>
   <TD><B>Kind</B><BR><?php	select_array("kind", $kind_values, NULL, NULL, "staff"); ?></TD>
<? endif; ?>
   <td><B>RMIT Location</B><BR><input type="text" name="location" size="30"></TD></td></tr>
</TABLE>
  <tr>
   <TD rowspan="3"><B>Postal Address</B><BR><TEXTAREA NAME="address"  COLS=50 ROWS=5></TEXTAREA></TD>
  <tr>
   <table><tr>
    <td><B>City</B><BR><input type="text" name="city" size="30"></td>
    <td><B>Postcode</B><BR><input type="text" name="postcode" size="20"></td>
    <td></td>
   </tr></table>
<TR>
 <TABLE>
  <TR>
   <TD><B>Phone</B><BR><INPUT TYPE=TEXT NAME="phone"  MAXLENGTH=32 SIZE=16></TD>
   <TD><B>Fax</B><BR><INPUT TYPE=TEXT NAME="fax"  MAXLENGTH=32 SIZE=16></TD>
   <TD><B>Mobile</B><BR><INPUT TYPE=TEXT NAME="mobile"  MAXLENGTH=32 SIZE=16></TD>
  <TR>
   <TD colspan="2"><B>Email</B><BR><INPUT TYPE=TEXT NAME="email"  MAXLENGTH=64 SIZE=32></TD>
   <TD><B>Web</B><BR><INPUT TYPE=TEXT NAME="web"  MAXLENGTH=64 SIZE=40></TD>
 </TABLE>
<TR>
<TD>
 <TABLE CELLPADDING=0 CELLSPACING=0>
  <TR>
   <TD WIDTH=60%><B>Description</B><BR><TEXTAREA NAME="description"  COLS=50 ROWS=5></TEXTAREA>
   <TD WIDTH=15% ALIGN=center VALIGN=top><B>Properties</B><BR><?php checkbox_array("property[]", $properties_values); ?>
 </TABLE>
<TR><TD>
 <div class="edit-controls">
 <TABLE ALIGN="center" CELLPADDING=2 CELLSPACING=0>
  <TR>
<?php if (!$login_user_code): ?>
   <TD><B><INPUT TYPE=SUBMIT NAME="edAction" VALUE="Delete items"></B>
   <TD><B><INPUT TYPE=SUBMIT NAME="edAction" VALUE="Modify selected"></B>
   <TD><B><INPUT TYPE=SUBMIT NAME="edAction" VALUE="Insert item"></B>
<?php else: ?>
   <TD><B><INPUT TYPE=SUBMIT NAME="edAction" VALUE="Submit changes"></b>
<?php endif; ?>
   <TD><B><INPUT TYPE=RESET NAME="edAction" VALUE="Clear Fields"></b>
  </TABLE></div>

<INPUT TYPE=HIDDEN NAME="key" VALUE="">
<TR><TD ALIGN="top">
</TABLE>
</FORM>
<?php
	if (!$login_user_code) {
		br();br();
		changeproperty_form("changeKindForm", "ed_people.php", "person", "kind", $kind_values, "whitevinyl",
			"This facility allows you to extend the values that the 'kind' field may take in the site user database. This allows the site to be substantially modified without impacting directly on the database behind it. This may be useful if you are adding new web services that use this as a user database.");
		changeproperty_form("changePropForm", "ed_people.php", "person", "property", $properties_values, "whitevinyl",
			"This facility allows you to extend the range of values that the 'property' set field in the site user database may take. This allows the site to be substantially modified without impacting directly on the database behind it. This may be useful if you are adding new web services that use this as a user database.");

	}
	if ($login_user_code):
?>
<SCRIPT LANGUAGE="javascript">
	display_selected(0);
</SCRIPT>
<?php
	else:
?>
<SCRIPT LANGUAGE="javascript">
	display_selected(<?php echo $selected?$selected:0; ?>);
</SCRIPT>
<?php
	endif;
	mysql_free_result($result);
	page_bottom_menu();
	standard_page_bottom();
?>
