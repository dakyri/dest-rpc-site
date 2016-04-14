<?php

function attrib_string($attribs)
{
	$a = "";
	reset($attribs);
	while (list($key, $val) = each($attribs)) {
		$a .= " $key=\"$val\"";
	}
	return $a;
}

function hidden_field($n,$v)
{
	echo hidden_field_str($n, $v);
}

function hidden_field_str($n,$v)
{
 	return "<INPUT TYPE=\"hidden\" NAME=\"$n\" ID=\"$n\" VALUE=\"$v\">";
}

function form_header($action, $name, $method="POST", $target="", $encoding="")
{
   echo "<FORM ACTION=\"$action\" NAME=\"$name\" METHOD=\"$method\"";
	if ($target) {
		echo " TARGET=\"$target\" ";
   }
   if ($encoding) {
		echo " ENCTYPE=\"$encoding\"";
   }
   echo">\n";
}

function table_header($space, $pad, $class="", $bgcolor="", $border="", $width="", $align="", $valign="")
{
   echo "<TABLE CELLSPACING=\"$space\" CELLPADDING=\"$pad\"";
	if ($class) {
		echo " class=\"$class\" ";
   }
   if ($border != "") {
		echo " border=\"$border\" ";
   }
	if ($width) {
		echo " width=\"$width\" ";
   }
	if ($align) {
		echo " align=\"$align\" ";
   }
	if ($valign) {
		echo " valign=\"$valign\" ";
   }
   if ($bgcolor) {
		echo " BGCOLOR=\"$bgcolor\"";
   }
   echo">\n";
}

function table_tail()
{
	echo "</table>";
}

function form_tail()
{
	echo "</form>";
}

function table_row($class="")
{
	echo "<TR";
	if ($class) {
		echo " class=\"$class\" ";
   }
	echo ">";
}

function table_rend()
{
	echo "</tr>";
}

function table_data($h="", $v="", $cs=1, $class="", $width=NULL)
{
	echo table_data_str($h, $v, $cs, $class, $width);
}
	
function table_data_str($h, $v, $cs=1, $class="", $width=NULL)
{
 	$s = "<TD";
 	if ($class) $s .= " CLASS=\"$class\"";
 	if ($h) $s .= " ALIGN=\"$h\"";
 	if ($v) $s .= " VALIGN=\"$v\"";
 	if ($cs > 1) $s .= " COLSPAN=\"$cs\"";
 	if ($width) $s .= " WIDTH=\"$width\"";
 	if ($class != "") $s .= " class=\"$class\" ";
	$s .= ">";
 	
 	return $s;
}

function table_dend()
{
	echo "</td>";
}


function table_data_string($txt, $align="LEFT", $width=0, $class="")
{
	echo "<TD ALIGN=\"$align\"";
	if ($class != "") echo " class=\"$class\" ";
	if (width > 0) {
		echo " WIDTH=\"$width%>\"";
	} else {
		echo ">";
	}
	if ($txt!="") {
		echo $txt;
	} else {
		echo '&nbsp';
	}
	echo "</TD>";
}


function upload_input($n, $v, $class="", $size=NULL)
{
 	echo upload_input_str($n, $v, $class, $size);
}

function upload_input_str($n, $v, $class="", $size=NULL)
{
 	$str = "<INPUT TYPE=\"file\" NAME=\"$n\" VALUE=\"$v\"";
	if ($size) $str .= " size=\"$size\" ";
	if ($class != "") $str .= " class=\"$class\" ";
 	$str .= ">";
 	return $str;
}


function checkbox_input($n,$v, $c=false, $class="", $dis=false, $oc=NULL)
{
 	echo checkbox_input_str($n, $v, $c, $class, $dis, $oc);
}

function checkbox_input_str($n,$v, $c=false, $class="", $dis=false, $oc=NULL)
{
 	$str = "<INPUT TYPE=\"checkbox\" NAME=\"$n\" VALUE=\"$v\"";
	if ($class != "") echo " class=\"$class\" ";
 	if ($c) {
 		$str .= " CHECKED";
 	}
	if ($dis) {
		$str .= " disabled";
	}
	if ($oc) {
		$str .= " onChange=\"$oc\"";
	}
 	$str .= ">";
 	return $str;
}

function text_area($n, $v, $w="", $h="", $oc="", $class="", $dis=false)
{
	echo text_area_str($n,$v,$w,$h,$oc, $class, $dis);
}

function text_area_str($n, $v, $w="", $h="", $oc="", $class="", $dis=false)
{
	$str = "<textarea name=\"$n\"";
	if ($class != "") echo " class=\"$class\" ";
	if ($w) {
		$str .= " cols=\"$w\"";
	}
	if ($h) {
		$str .= " rows=\"$h\"";
	}
	if ($oc) {
		$str .= " onChange=\"$oc\"";
	}
	if ($dis) {
		$str .= " disabled";
	}
	$str .= ">";
	$str .= $v;
	$str .= "</textarea>\n";
	return $str;
}

function text_input($n, $v, $s="", $l="", $oc="", $class="", $dis=false)
{
	echo text_input_str($n,$v,$s,$l,$oc, $class, $dis);
}

function text_input_str($n, $v, $s="", $l="", $oc="", $class="", $dis=false)
{
	$str = "<input type=\"text\" name=\"$n\" value=\"$v\"";
	if ($class != "") echo " class=\"$class\" ";
	if ($s) {
		$str .= " size=\"$s\"";
	}
	if ($l) {
		$str .= " maxlength=\"$l\"";
	}
	if ($oc) {
		$str .= " onChange=\"$oc\"";
	}
	if ($dis) {
		$str .= " disabled";
	}
	$str .= ">\n";
	return $str;
}

function password_input($n, $v, $s="", $class="", $dis=false, $id=NULL)
{
	echo "<input type=\"password\" name=\"$n\" value=\"$v\"";
	if ($class != "") echo " class=\"$class\" ";
	if ($id) echo " id=\"$id\"";
	if ($dis) echo " disabled";
	if ($s) {
		echo " size=\"$s\"";
	}
	echo ">\n";
}

function submit_input($n, $v, $oc="", $class="", $dis=false, $id=NULL)
{
	echo "<input type=\"submit\"";
	if ($n) echo " name=\"$n\"";
	if ($id) echo " id=\"$id\"";
	if ($v) echo " value=\"$v\"";
	if ($class != "") echo " class=\"$class\" ";
	if ($dis) echo " disabled";
	if ($oc) {
		echo " onClick=\"$oc\"";
	}
	echo ">\n";
}

function button_input($n, $v, $oc="", $class="", $dis=false, $id=NULL)
{
	echo "<input type=\"button\"";
	if ($n) echo " name=\"$n\"";
	if ($v) echo " value=\"$v\"";
	if ($id) echo " id=\"$id\"";
	if ($class != "") echo " class=\"$class\" ";
	if ($dis) echo " disabled";
	if ($oc) {
		echo " onClick=\"$oc\"";
	}
	echo ">\n";
}

function option_input($v, $l, $sel=false)
{
	echo option_input_str($v, $l, $sel);
}

function option_input_str($v, $l, $sel=false)
{
	$str .= "<OPTION value=\"$v\"";
	if ($sel) {
		$str .= " SELECTED";
	}
	$str .= ">$l</OPTION>\n";
	return $str;
}

function select_menu_header($n, $s, $m=false, $class="", $oc="", $dis=false)
{
	echo select_menu_header_str($n, $s, $m, $class, $oc, $dis);
}

function select_menu_header_str($n, $s, $m=false, $class="", $oc="", $dis=false)
{
	$str = "<SELECT NAME=\"$n\" SIZE=\"$s\"";
	if ($class != "") echo " class=\"$class\" ";
	if ($m) {
		$str .= " MULTIPLE";
	}
	if ($oc) {
		$str .= " onChange=\"$oc\"";
	}
	if ($dis) {
		$str .= " disabled";
	}
	$str .= ">";
	
	return $str;
}


function select_menu_tail()
{
	echo select_menu_tail_str();
}

function select_menu_tail_str()
{
	return "</SELECT>";
}

function image_tag_str($img_src, $img_width, $img_height, $img_title, $img_alt, $class="", $img_algn="")
{
	$s = "";
	if ($img_algn == "center") {
		$center = true;
		$img_algn = NULL;
	} else {
		$center = false;
	}
	if ($center) {
		$s .= "<div align=\"center\">\n";
	}
	$s .= "<img src=\"$img_src\"";
	if ($class != "") $s .= " class=\"$class\" ";
	if ($img_width > 0) $s .= " width=\"$img_width\" ";
	if ($img_height > 0) $s .= " height=\"$img_height\" ";
	if ($img_title) $s .= " title=\"$img_title\" ";
	if ($img_alt) $s .= " alt=\"$img_alt\"";
	if ($img_algn) $s .= " align=\"$img_algn\"";
	$s .= ">\n";
	if ($center) {
		$s .= "</div>\n";
	}
	return $s;
}

function image_tag($img_src, $img_wid, $img_height, $img_title, $img_alt, $class="", $alg="")
{
	echo image_tag_str($img_src, $img_wid, $img_height, $img_title, $img_alt, $class, $alg);
}

function	select_array($name, $values, $class="", $labels=NULL, $oc=NULL, $iv=NULL, $dis=false)
{
	if ($labels==NULL) {
		$labels=&$values;
	}
	echo "<SELECT NAME=\"$name\"";
	if ($class) echo " class=\"$class\"";
	if ($oc) echo " onChange=\"$oc\"";
	if ($dis) echo " disabled";
	echo " SIZE=1>";
	for ($i=0; $i<sizeof($values); $i++) {
		if (isset($values[$i])) {
			if ($iv && $values[$i] == $iv) {
				echo "<OPTION selected value=\"$values[$i]\">\n" . "$labels[$i]";
			} else {
				echo "<OPTION value=\"$values[$i]\">\n" . "$labels[$i]";
			}
		}
	}
	echo "</SELECT>";
}

function	select_multiple_array($name, $values, $size, $class="", $labels=NULL, $oc=NULL, $iv=NULL, $dis=false)
{
	if ($labels==NULL) {
		$labels=&$values;
	}
	echo "<SELECT MULTIPLE NAME=\"$name\"";
	if ($class != "") echo " class=\"$class\"";
	if ($oc) echo " onChange=\"$oc\"";
	if ($dis) echo " disabled";
	echo " SIZE=\"$size\">";
	for ($i=0; $i<sizeof($values); $i++) {
		if (isset($values[$i])) {
			if ($iv && $values[$i] == $iv) {
				echo "<OPTION selected value=\"$values[$i]\">\n" . "$labels[$i]";
			} else {
				echo "<OPTION value=\"$values[$i]\">\n" . "$labels[$i]";
			}
		}
	}
	echo "</SELECT>";
}

function checkedbox_array($name, $values, $class="", $labels=NULL, $dis=false)
{
	if ($labels==NULL) {
		$labels=&$values;
	}
	for ($i=0; $i<sizeof($values); $i++) {
		if (isset($values[$i])) {
			echo "<INPUT TYPE=\"CHECKBOX\" NAME=\"$name\"";
			if ($class != "") echo " class=\"$class\"";
			if ($dis) echo " disabled";
			echo " VALUE=$values[$i] CHECKED>$labels[$i]<BR>\n";
		}
	}
}

function checkbox_array($name, $values, $class="", $labels=NULL, $dis=false)
{
	if ($labels==NULL) {
		$labels=&$values;
	}
	for ($i=0; $i<sizeof($values); $i++) {
		if (isset($values[$i])) {
			echo "<INPUT TYPE=\"CHECKBOX\" NAME=\"$name\"";
			if ($class != "") echo " class=\"$class\"";
			if ($dis) echo " disabled";
			echo " VALUE=$values[$i]>$labels[$i]<BR>\n";
		}
	}
}

function checkbox($name, $values, $class="", $labels=NULL)
{
	echo checkbox_str($name, $values, $class, $labels);
}

function checkedbox($name, $values, $class="", $labels=NULL)
{
	echo checkbox_str($name, $values, $class, $labels);
}

function checkedbox_str($name, $values, $class="", $labels=NULL)
{
	if ($labels==NULL) {
		$labels=&$values;
	}
	$str = "";
	if (isset($values)) {
		$str =  "<INPUT TYPE=\"CHECKBOX\" NAME=\"$name\"";
		if ($class != "") $str .=  " class=\"$class\"";
		$str .=  " VALUE=$values CHECKED>$labels<BR>\n";
	}
	return $str;
}

function checkbox_str($name, $values, $class="", $labels=NULL)
{
	if ($labels==NULL) {
		$labels=&$values;
	}
	$str = "";
	if (isset($values)) {
		$str = "<INPUT TYPE=\"CHECKBOX\" NAME=\"$name\"";
		if ($class != "") $str .= " class=\"$class\"";
		$str .= " VALUE=$values>$labels<BR>\n";
	}
	return $str;
}

function embed_swf($swf_animation_name, $swf_animation_file, $swf_w, $swf_h, $swf_live, $swf_alt_img, $swf_alt_txt)
{
?>
<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" 
			id="<?php echo $swf_animation_name ?>"
			 width="<?php echo $swf_w ?>" height="<?php echo $swf_h ?>">
    		<param name=movie value="<?php echo $swf_animation_file ?>">
    		<param name=quality value=high>
   <embed name="<?php echo $swf_animation_name ?>"
   		src="<?php echo $swf_animation_file ?>"
    		quality=high
    		type="application/x-shockwave-flash"
    		<?php echo $swf_live?"swliveconnect=true":"";?> width="<?php echo $swf_w ?>" height="<?php echo $swf_h ?>">
   </embed>
	<noembed>
		<IMG SRC="<?php echo $swf_alt_img ?>"
				WIDTH="<?php echo $swf_w ?>" HEIGHT="<?php echo $swf_h ?>"
				ALT="<?php echo $swf_alt_txt ?>">
	</noembed> 
</object>
<?php
}

function anchor_str($txt, $url, $tgt=NULL, $class=NULL, $oncl="")
{
	$a = "<a";
	if ($url) {
		$a .= " href =\"$url\"";
	}
	if ($tgt) {
		$a .=  " target =\"$tgt\"";
	}
	if ($class) {
		$a .=  " class =\"$cls\"";
	}
	if ($oncl) {
		$a .= " onClick =\"$oncl\"";
	}
	$a .= ">$txt</a>";
	return $a;
}

function font_str($str, $size="", $face="")
{
	if ($size == "" && $face == "") {
		return $str;
	} elseif ($face == "") {
		return "<font size=\"$size\">$str</font>";
	} elseif ($size == "") {
		return "<font face=\"$face\">$str</font>";
	} else {
		return "<font size=\"$size\" face=\"$face\">$str</font>";
	}
}

function div_str($str, $class)
{
	if ($class=="") {
		return $str;
	} else {
		return "<div class=\"$class\">$str</div>";
	}
}

function span_str($str, $class)
{
	if ($class=="") {
		return $str;
	} else {
		return "<span class=\"$class\">$str</span>";
	}
}
function div()
{
	$n = func_num_args();
	if ($n < 1) {
		echo "</div>";
		return;
	}
	$class = func_get_arg(0);
	echo "<div class=\"$class\">";
	for ($i=1; $i<$n; $i++) {
		echo func_get_arg($i);
	}
	if ($n >= 2) echo "</div>";
}

function span()
{
	$n = func_num_args();
	if ($n < 1) {
		echo "</div>";
		return;
	}
	$class = func_get_arg(0);
	echo "<span class=\"$class\">";
	for ($i=1; $i<$n; $i++) {
		echo func_get_arg($i);
	}
	if ($n >= 2) echo "</div>";
}

function br($clear="")
{
	if ($clear) {
		echo "<br clear=\"$clear\">";
	} else {
		echo "<br>";
	}
}

function upload_error_string($erc)
{
	switch ($erc) {
//UPLOAD_ERR_OK
		case 0: return "There is no error, the file uploaded with success. ";
//UPLOAD_ERR_INI_SIZE
		case 1: return "The uploaded file exceeds the upload_max_filesize directive in php.ini. ";
//UPLOAD_ERR_FORM_SIZE
		case 2: return "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the html form. ";
//UPLOAD_ERR_PARTIAL
		case 3: return "The uploaded file was only partially uploaded. ";
//UPLOAD_ERR_NO_FILE
		case 4: return "No file was uploaded. ";
	}
	return "No error";
}

 ?>
