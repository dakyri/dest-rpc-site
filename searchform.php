<?php
////////////////////////////////////////////////////////
// search form for rpc datbase
////////////////////////////////////////////////////////
	form_header("publications.php", "searchForm", "POST", "", "");
	table_header(2, 2, "", "", "", "", "left", "top");
	echo "<tr><td><b>Title</b></td><td>";
	text_input("search_title", $search_title, 60, 200);
	echo "</td></tr>";
	echo "<tr><td><b>Principal RMIT Author</b><br>(name or staff/student number, may be a list)</td><td>";
	text_input("search_rmit_author", $search_rmit_author, 50, 200);
	echo "</td></tr>";
	echo "<tr><td><b>Other RMIT Author, Staff/Student Number</b></td><td>";
	text_input("search_stnumber", $search_stnumber, 10, 20);
	echo "</td></tr>";
	echo "<tr><td><b>Other Author, Name(s)</b></td><td>";
	text_input("search_author", $search_author, 50, 200);
	echo "</td></tr>";
	echo "<tr><td><b>For years</b></td><td>";
	text_input("search_year", $search_year, 10, 10);
	echo "</td></tr>";
	echo "<tr><td><b>Keywords</b></td><td>";
	text_input("search_keywords", $search_keywords, 80, 200);
	echo "</td></tr>";
	echo "<tr><td><b>For status</b></td><td>";
	select_array("search_status",
			array("any","primary", "nprimary", "school","portfolio", "nschool", "nportfolio"), "",
			array("Any", "Passed Primary Checks", "Not Passed Primary Checks", "Accepted by School", "Accepted by Portfolio", "Pending Acceptance by School", "Pending Acceptance by Portfolio"), "",
			$search_status? $search_status:"any");
	echo "</td></tr>";
	echo "<tr><td>&nbsp;</td></tr>";
	echo "<tr><td>&nbsp;</td></tr>";
	echo "<tr><td><b>Show Authors</b></td><td>";
	checkbox_input("show_authors", "true", $show_authors?($show_authors=="true"):true);
	echo "</td></tr>";
	echo "<tr><td><b>Show Subjects</b></td><td>";
	select_array("show_subjects",
			array("none","name","rfc","both"), "",
			array("None", "As name", "As RFC code", "As Name and RFC code"), "",
			$show_subjects? $show_subjects:"name");
	echo "</td></tr>";
	echo "<tr><td><b>Show Associated Research Groups and Institutions</b></td><td>";
	checkbox_input("show_affiliations", "true", $show_affiliations?($show_affiliations=="true"):true);
	echo "<tr><td><b>Show Supporting Materials</b></td><td>";
	checkbox_input("show_supporting", "true", $show_supporting?($show_supporting=="true"):true);
	echo "</td></tr>";
	echo "<tr><td><b>Find Citations</b></td><td>";
	checkbox_input("show_citations", "true", $show_citations?($show_citations=="true"):false);
	echo "</td></tr>";
	echo "<tr><td><b>Show Timestamp</b></td><td>";
	checkbox_input("show_timestamp", "true", $show_timestamp?($show_timestamp=="true"):false);
	echo "</td></tr>";
	echo "<tr><td><b>Show in ARC bibliographic format</b></td><td>";
	checkbox_input("show_arc", "true", $show_arc?($show_arc=="true"):true);
	echo "</td></tr>";
	echo "<tr><td>";
	br();
	submit_input("searchMode", "Search");
	echo "</td></tr>";
	table_tail();
	br("all");
	echo "<p><sup>*</sup> Wildcards may be used in text search fields above: '_' matches a single character, and '%' matches any number of characters</p>";
	form_tail();
	br("all");
?>