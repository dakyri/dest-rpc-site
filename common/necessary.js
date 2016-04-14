// Sets cookie values. Expiration date is optional
// Notice the use of escape to encode special characters (semicolons, commas,
// spaces) in the value string. This function assumes that cookie names do not
// have any special characters.

//documentbase = "http://www.katklothing.com/";
documentbase = "http://shakti/kat/";

function set_cookie(name, value, expire) 
{
	document.cookie = name + "=" + escape(value)
	    + ((expire == null) ? "" : ("; expire=" + expire.toUTCString()));
}


// The following function returns a cookie value, given the name of the cookie:

function get_cookie(name)
{
	var search = name + "="
	if (document.cookie.length > 0) { // if there are any cookies
		offset = document.cookie.indexOf(search);
		if (offset != -1) { // if cookie exists 
			offset += search.length 
// set index of beginning of value
			end = document.cookie.indexOf(";", offset) 
// set index of end of cookie value
			if (end == -1) 
				end = document.cookie.length
			return unescape(document.cookie.substring(offset, end))
		}
	}
	return "";
}

function close_admin()
{
	set_cookie("db_user", "");
	set_cookie("db_passwd", "");
	pathbits = location.pathname.split("/");
	if (pathbits.length > 0) {
		from = pathbits[pathbits.length-1];
	} else {
		from = "";
	}
	if (from == "edcatalog.php") {
		location.href = documentbase + "catalog.php";
	} else if (from == "edshops.php") {
		location.href = documentbase + "shops.php";
	} else {
		location.reload();
	}
	return true;
}

function verify_admin()
{
	db_u = get_cookie("db_user");
	db_p = get_cookie("db_passwd");
	pathbits = location.pathname.split("/");
	if (pathbits.length > 0) {
		from = pathbits[pathbits.length-1];
	} else {
		from = "";
	}
//	window.parent.status = "pw: "+db_p;
	if (db_u != "" && db_p != "") {
		if (from == "catalog.php") {
			location.href = documentbase + "edcatalog.php";
		} else if (from == "nav_directory.php") {
			location.href = documentbase + "edshops.php";
		} else {
			location.reload();
		}
	} else {
		location.href = documentbase + "logon.php?from="+from;
//		auth_window=window.open(
//			'logon.php?from='+from,
//			'authWindow',
//			'innerWidth=350,innerHeight=250,screenX=400,screenY=400');
//
//		auth_window.focus();
	}
	return true;
}

