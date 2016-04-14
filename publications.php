<?php
	import_request_variables("gp");
	require("common/necessary.php");
	require("common/sqlschema_types.php");
	
	function supporting_materials($row, $base)
	{
		if ($row->description) {
			echo "<p>$row->description</p>\n";
		}
		if ($row->web) {
			echo "<br>Web site: ";
			echo anchor_str($row->web, $row->web, "_blank");
			echo "\n";
		}
 		$mat = split("&", $row->material);
 		$matyp = split("&", $row->material_kind);
 		$has_sm = false;
 		for ($aui=0; $aui<count($mat); $aui++) {
 			if ($mat[$aui]) {
 				$has_sm = true;
 				break;
 			}
 		}
 		if ($has_sm) {
			echo "<BR>&nbsp;&nbsp;Supporting materials:<br>";
			echo "\n";
			table_header(1, 1, "", "", 0);
			echo "\n";
	 		for ($aui=0; $aui<count($mat); $aui++) {
	 			if ($mat[$aui]) {
		 			table_row();
					echo "\n";
		 			$nm = rawurldecode($mat[$aui]);
		 			table_data_string("&nbsp;&nbsp;");
					echo "\n";
	 				table_data_string(anchor_str($nm, $base.$nm, "", ""));
					echo "\n";
	 				table_data_string("[".rawurldecode($matyp[$aui])."]");
					echo "\n";
	 				table_rend();
					echo "\n";
	 			}
	 		}
			table_tail();
			echo "\n";
		}
	}

	function subject_breakdown($row, $show_subjects)
	{
		if ($show_subjects && $show_subjects != "none") {
			global $rfcd_type;
			table_header(1, 1, "", "", 1);
			echo "\n";
			$stn = split("&", $row->rfcd_code);
			$sura = split("&", $row->rfcd_split);
			for ($aui=0; $aui<count($stn); $aui++) {
				if ($sura[$aui]) {
					table_row();
					$subjn = "";
					switch ($show_subjects) {
						case "rfc":
							$subjn = rawurldecode($stn[$aui]);
							break;
						case "both":
							$subjn = $rfcd_type->Label(rawurldecode($stn[$aui]))." (".rawurldecode($stn[$aui]).")";
							break;
						case "name":
						default:
							$subjn = $rfcd_type->Label(rawurldecode($stn[$aui]));
							break;
					}
					
					table_data_string($subjn);
					echo "\n";
					table_data_string(rawurldecode($sura[$aui])."%");
					echo "\n";
					table_rend();
					echo "\n";
				}
			}
			table_tail();
			echo "\n";
		}
	}
	
	function author_list($row)
	{
		global $rsc_type;
	
		table_header(1, 1, "", "", 1);
		$stn = split("&", $row->stnumber);
		$sura = split("&", $row->surname);
		$fura = split("&", $row->firstname);
		$ata= split("&", $row->author_title);
		$typa = split("&", $row->type);
		$sca= split("&", $row->school_code);
		$son = split("&", $row->school_org_name);
		for ($aui=0; $aui<count($stn); $aui++) {
			if ($sura[$aui]) {
				table_row();
				echo "\n";
				echo "<td>",rawurldecode($ata[$aui]), " ",rawurldecode($fura[$aui]), " ", rawurldecode($sura[$aui]),"</td>";
				table_data_string(rawurldecode($stn[$aui]));
				echo "\n";
				$rmit_school_nm = $rsc_type->Label(rawurldecode($sca[$aui]));
				table_data_string($sca[$aui] >= 0?("$rmit_school_nm (RMIT)"):rawurldecode($son[$aui]));
				echo "\n";
				table_rend();
				echo "\n";
			}
		}
		table_tail();
	}
	
	function scholar_searches($row, $auth)
	{
		table_data("right", "top", 1, "", "30%");
		if ($auth) {
			echo anchor_str(
				"Author/Title search on Google Scholar",
				"http://scholar.google.com/scholar?hl=en&lr=&q=".urlencode("author:$auth \"$row->title\""),
				"", "", "");
		}
		br();
		echo "\n";
		echo anchor_str(
				"Title search on Google Scholar",
				"http://scholar.google.com/scholar?hl=en&lr=&q=".urlencode("\"$row->title\""),
				"", "", "");
		br();
		echo "\n";
		echo anchor_str(
				"Keyword search on Google Scholar",
				"http://scholar.google.com/scholar?hl=en&lr=&q=".urlencode("$row->keywords"),
				"", "", "");
		br();
		echo "\n";
		table_dend();
	}
	
	function publication_details($row)
	{
		echo "Published: $row->publisher, $row->publication_place, $row->publication_year";
		echo "\n";
	}
	
	function timestamp_display($row)
	{
		if ($row->create_timestamp) {
			echo "&nbsp;&nbsp;Entry created on $row->create_timestamp<br>";
			echo "\n";
		}
		if ($row->edit_timestamp) {
			echo "&nbsp;&nbsp;Last modified on $row->edit_timestamp<br>";
			echo "\n";
		}
	}
	
	function affiliation_breakdown($row)
	{
		global	$vrii_type;
		global	$rg_type;
		if ($row->vrii) {
			if ($row->vrii != "unaligned") {
				if ($vrii_type) {
					$vrnm = $vrii_type->Label($row->vrii);
				} else {
					$vrnm = ucfirst($row->vrii);
				}
				if ($vrnm) {
					echo "&nbsp;&nbsp;<b>VRII</b> : $vrnm<br>";
					echo "\n";
				}
			}
		}
		if ($row->research_group) {
			$rg = explode(",",$row->research_group);
			if ($vrii_type) {
				$vrnm = $rg_type->Label($rg[0]);
			} else {
				$vrnm = ucfirst($rg[0]);
			}
			echo "&nbsp;&nbsp;<b>Research Group: $vrnm</b>";
			for ($i=1; $i<count($rg); $i++) {
				if ($vrii_type) {
					$vrnm = $vrii_type->Label($rg[$i]);
				} else {
					$vrnm = ucfirst($rg[$i]);
				}
				echo ", ",$vrnm;
			}
			br();
			echo "\n";
		}
		if ($row->vrii || $row->research_group) {
//			br();
		}
	}
	
	function arc_format($row, $table, $i)
	{
		if ($row->title) {
			table_row();
			table_data("left", "top");
			echo $i, ". ";
			table_dend();
			
			table_data("left", "top");
			echo "<b>",$row->title, "</b>\n";
			table_dend();
			table_rend();
			
			$stn = split("&", $row->stnumber);
			$sura = split("&", $row->surname);
			$fura = split("&", $row->firstname);
			echo "<tr><td>&nbsp;</td><td>\n";
			for ($aui=0; $aui<count($stn); $aui++) {
				if ($sura[$aui]) {
					$fnm = explode(" ", rawurldecode($fura[$aui]));
					$cnm = "";
					reset($fnm);
					while (list($k, $v) = each($fnm)) {
						if ($v  && $v{0}) {
							$cnm .= strtoupper($v{0});
							$cnm .= ". ";
						}
					}
					$cnm .= rawurldecode($sura[$aui]);
					if ($aui > 0) {
						echo ", ";
					}
					$srch_st = rawurldecode($stn[$aui]);
					if ($srch_st) {
						echo anchor_str($cnm, "publications.php?show_arc=true&search_stnumber=$srch_st");
					} else {
						echo $cnm;
					}
				}
			}
			echo "</td></tr>\n";
			echo "<tr><td>&nbsp;</td><td>\n";
			switch ($table) {
				case "book":
					if ($row->publisher) {
						echo "$row->publisher";
						if ($row->publication_place) {
							echo ", $row->publication_place";
						}
					}
					echo " ($row->publication_year)";
					break;
				case "chapter":
					echo "$row->book_title";
					if ($row->start_page && $row->end_page) {
						echo ", $row->start_page-$row->end_page";
					}
					if ($row->publisher) {
						echo ", $row->publisher";
					}
					if ($row->publication_place) {
						echo ", $row->publication_place";
					}
					echo " ($row->publication_year)";
					break;
				case "journal":
					echo "$row->journal_name";
					if ($row->volume) {
						echo "<b> $row->volume</b>";
					}
					if ($row->edition && ereg("[[:digit:]]+",$row->editon)) {
						echo ", $row->edition";
					}
					if ($row->start_page && $row->end_page) {
						echo ", $row->start_page-$row->end_page";
					}
					echo " ($row->publication_year)";
					break;
				case "conference":
					echo "$row->publication_title";
					if ($row->editor) {
						echo ", (Ed:$row->editor)";
					}
					if ($row->start_page && $row->end_page) {
						echo ", $row->start_page-$row->end_page";
					}
					if ($row->publisher) {
						echo ", $row->publisher";
					}
					if ($row->publication_place) {
						echo ", $row->publication_place";
					}
					echo " ($row->publication_year)";
					break;
				default:
					if ($row->publisher) {
						echo ", $row->publisher";
					}
					if ($row->publication_place) {
						echo ", $row->publication_place";
					}
					echo " ($row->publication_year)";
					break;
			}
			echo "</td></tr>\n";
			
	 		$mat = split("&", $row->material);
	 		$matyp = split("&", $row->material_kind);
	 		$has_sm = false;
	 		for ($aui=0; $aui<count($mat); $aui++) {
	 			if ($mat[$aui] && ($matyp[$aui]=="paper" || $matyp[$aui]=="chapter")) {
	 				$has_sm = true;
	 				break;
	 			}
	 		}
	 		if ($has_sm) {
	 			$upbase = upload_base($table);

				echo "<tr><td>&nbsp;</td><td>\n";
				echo "[",anchor_str("<font color='#55aa55'>Download ".ucfirst($matyp[$aui])."</font>", "$upbase$row->code/".$mat[$aui]),"]\n";
				echo "</td></tr>\n";
			}
	 		
			table_row();
			echo "<td>&nbsp;</td>\n";
			table_rend();
			
			return true;
		}
		return false;
	}
	
///////////////////////////////////////////
// get database and table formats
///////////////////////////////////////////
	$mysql = get_database(
					$schema_database_name,
					$database_host,
					$database_pleb_user,
					$database_pleb_passwd);

	if ($mysql < 0) {
		errorpage("Can't open database:".mysql_error());
	}
	$schema_idx_path = "$schema_base_directory/$schema_idx_name";
	if (file_exists($schema_idx_path)) {
		$uploaded_schema = uncache_variable($schema_idx_path);
	} else {
		errorpage("publication format index '$schema_idx_path' does not exist");
	}
	if (!$uploaded_schema) {
		errorpage("publication format index '$schema_idx_path' is empty");
	}
	while (list($k,$v)=each($uploaded_schema)) {
		$scv = uncache_variable("$schema_base_directory/$k"."_tables.ser");
		$schema_table[$k] = reset($scv);
	}
	reset($schema_table);
	$v=current($schema_table);
	$schema_types = uncache_variable("$schema_base_directory/$v->name"."_types.ser");
	$rsc_type = &$schema_types["rsc-type"];
	$rfcd_type = &$schema_types["rfcd-type"];
	$vrii_type = &$schema_types["vrii-type"];
	$rg_type = &$schema_types["research-group-type"];
	
///////////////////////////////////////////////////////////////
// top of the page
///////////////////////////////////////////////////////////////
	standard_page_top("DEST Research Publications Database", "style/default.css", "page-noframe", "images/title/dest_rpc.gif", 700, 72, "DEST Research Publication Collection", "common/necessary.js");
	br("all");
	
	require("searchform.php");
	br();br();br();
	$where = "";
	$au_where = "";
	if ($search_rmit_author) {
		$authors = split('[, ]', $search_rmit_author);
		$au_where = "";
		reset($authors);
		while (list($au_key,$au_val) = each($authors)) {
			if ($au_val) {
				$au_where = like_clause($au_where, "stnumber",$au_val, "||");
				$au_where = like_clause($au_where, "firstname",$au_val, "||");
				$au_where = like_clause($au_where, "surname", $au_val,"||");
			}
		}
		$au_query = "select * from people";
		$au_query .= " where ($au_where) and (kind != 'admin')";
		if ($au_order) {
			$au_query .= " order $au_order";
		}
//		echo $au_query;
		$au_result = mysql_query($au_query);
		if (!$au_result) {
			echo "Database error: ", mysql_error();
		} else {
			$n_au_found = mysql_num_rows($au_result);
			if ($n_au_found >0) {
				table_header(0,0);
				for($ai=0; $ai < $n_au_found; $ai++) {
					$au_row = mysql_fetch_object($au_result);
					table_row();
					table_data("left", "top", 2);
					div("result-head", "Publications for $au_row->stnumber, $au_row->title $au_row->firstname $au_row->surname");
					table_dend();
					table_rend();
					
					reset($schema_table);
					while (list($k,$v)=each($schema_table)) {
						$upbase = upload_base($k);
						$qwh = "first_author_stnumber='$au_row->stnumber'";
						if ($search_status && $search_status != 'any') {
							switch ($search_status) {
							case 'primary': 
								$qwh = "($qwh) and primary_checked";
								break;
							case 'nprimary': 
								$qwh = "($qwh) and not primary_checked";
								break;
							case 'school': 
								$qwh = "($qwh) and school_checked";
								break;
							case 'portfolio': 
								$qwh = "($qwh) and portfolio_checked";
								break;
							case 'nschool': 
								$qwh = "($qwh) and not school_checked";
								break;
							case 'nportfolio': 
								$qwh = "($qwh) and not portfolio_checked";
								break;
							}
						}
						if ($search_year) {
							$yind = strpos($search_year, '-');
							if ($yind === false) {
								$qwh = "$qwh and publication_year='$search_year'";
							} elseif ($yind == 0) {
								$years = substr($search_year, 1,4);
								$qwh = "$qwh and publication_year<='$years'";
							} elseif ($yind == (strlen($search_year)-1)) {
								$years = substr($search_year, 0,4);
								$qwh = "$qwh and publication_year>='$years'";
							} else {
								$years = explode('-', $search_year);
								$qwh = "$qwh and (publication_year>='$years[0]') and (publication_year<='$years[1]')";
							}
						}
						$query = "select * from $v->name where $qwh";
						//echo $query;
						$result = mysql_query($query);
						if ($result > 0) {
							$nitems = mysql_num_rows($result);
							if ($nitems > 0) {
								table_row();
								table_data("left", "top", 2);
								div("result-title",	$v->label?"<B>$nitems $v->label":"<b>$nitems".ucfirst($v->name),
											(($nitems > 1)? "s</b>":"</B>"));
								table_dend();
								table_rend();
								$arc_count = 1;
								for($i=0; $i < $nitems; $i++) {
									if ($show_arc) {
										$row = mysql_fetch_object($result);
										if (arc_format($row, $v->name, $arc_count)) {
											$arc_count++;
										}
									} else {
										table_row();
										echo "<td align=\"left\">";
										
										$row = mysql_fetch_object($result);
										div("result-title", ($i+1).". <b>\"$row->title\"</b>");
										div("result-body");
										publication_details($row);
										if ($show_authors) {
											author_list($row);
										}
										if ($show_subjects) {
											subject_breakdown($row, $show_subjects);
										}
										if ($show_affiliations) {
											affiliation_breakdown($row);
										}
										if ($show_timestamp) {
											timestamp_display($row);
										}
										if ($show_supporting) {
											supporting_materials($row, "$upbase$row->code/");
										}
										div();
										br();
										
										table_dend();
										scholar_searches($row, $au_row->surname);
										table_rend();
									}
								}
							}
						} else {
							echo "Mysql error ".mysql_error();
						}
					}
				}
				table_tail();
				br();br();
			} else {
				div("There are no RMIT principal authors in this collection matching your request.", "result-head");
			}
		}
	} else {
		$kt = split('[, ]', $search_title);
		$ka = split('[, ]', $search_author);
		$ku = split('[, ]', $search_stnumber);
		$kw = split('[, ]', $search_keywords);
		$au_where = "";
		reset($kw);
		while (list($au_key,$au_val) = each($kw)) {
			if ($au_val) {
				$au_where = like_clause($au_where, "keywords","%$au_val%", "||");
			}
		}
		reset($ka);
		while (list($au_key,$au_val) = each($ka)) {
			if ($au_val) {
				$au_where = like_clause($au_where, "surname","%$au_val%", "||");
			}
		}
		reset($ku);
		while (list($au_key,$au_val) = each($ku)) {
			if ($au_val) {
				$au_where = like_clause($au_where, "stnumber","%$au_val%", "||");
			}
		}
		reset($kt);
		while (list($au_key,$au_val) = each($kt)) {
			if ($au_val) {
				$au_where = like_clause($au_where, "title","%$au_val%", "||");
			}
		}
		if ($au_where) {
			div("result-head", "Publications found matching the given criteria");
			table_header(0,0);
			reset($schema_table);
			while (list($k,$v)=each($schema_table)) {
				$upbase = upload_base($k);
				$qwh = $au_where;
				if ($search_status && $search_status != 'any') {
					switch ($search_status) {
						case 'primary': 
							$qwh = "($qwh) and primary_checked";
							break;
						case 'nprimary': 
							$qwh = "($qwh) and not primary_checked";
							break;
						case 'school': 
							$qwh = "($qwh) and school_checked";
							break;
						case 'portfolio': 
							$qwh = "($qwh) and portfolio_checked";
							break;
						case 'nschool': 
							$qwh = "($qwh) and not school_checked";
							break;
						case 'nportfolio': 
							$qwh = "($qwh) and not portfolio_checked";
							break;
					}
				}
				if ($search_year) {
					$yind = strpos($search_year, '-');
					if ($yind === false) {
						$qwh = "$qwh and publication_year='$search_year'";
					} elseif ($yind == 0) {
						$years = substr($search_year, 1,4);
						$qwh = "$qwh and publication_year<='$years'";
					} elseif ($yind == (strlen($search_year)-1)) {
						$years = substr($search_year, 0,4);
						$qwh = "$qwh and publication_year>='$years'";
					} else {
						$years = explode('-', $search_year);
						$qwh = "$qwh and (publication_year>='$years[0]') and (publication_year<='$years[1]')";
					}
				}
				$query = "select * from $v->name where $qwh";
//				echo $query."<br>";
				$result = mysql_query($query);
				if ($result > 0) {
					$nitems = mysql_num_rows($result);
					if ($nitems > 0) {
						table_row();
						table_data("left", "top", 2);
						div("result-title",	$v->label?"<B>$nitems $v->label":"<b>$nitems".ucfirst($v->name),
								(($nitems > 1)? "s</b>":"</B>"));
						table_dend();
						table_rend();
						$arc_count = 1;
						for($i=0; $i < $nitems; $i++) {
							if ($show_arc) {
								$row = mysql_fetch_object($result);
								if (arc_format($row, $v->name, $arc_count)) {
									$arc_count++;
								}
							} else {
								table_row();
								echo "<td align=\"left\">";
								
								$row = mysql_fetch_object($result);
								div("result-title",	($i+1).". <b>\"$row->title\"</b>");
								div("result-body");
								publication_details($row);
								if ($show_authors) {
									author_list($row);
								}
								if ($show_subjects) {
									subject_breakdown($row, $show_subjects);
								}
								if ($show_affiliations) {
									affiliation_breakdown($row);
								}
								if ($show_timestamp) {
									timestamp_display($row);
								}
								if ($show_supporting) {
									$authname_query = "select * from people where stnumber='$row->first_author_stnumber'";
									$auresult = mysql_query($authname_query);
									if ($auresult > 0) {
										$nauitems = mysql_num_rows($auresult);
										if ($nauitems > 0) {
											$authname_row = mysql_fetch_object($auresult);
											supporting_materials($row, "$upbase$row->code/");
										}
									}
								}
								div();
								br();
								
								table_dend();
								$ska = split('&', $row->surname);
								scholar_searches($row, $ska[0]);
								table_rend();
							}
						}
					}
				} else {
					echo "Mysql error ".mysql_error();
				}
			}
			table_tail();
			br();br();
		}
		table_tail();
	}
	page_bottom_menu();
	standard_page_bottom();
?>
