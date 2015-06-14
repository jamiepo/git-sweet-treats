<?
//made it this far so the image exists.
$im = ImageCreateFromPNG(MY_DIAGRAM);
// first clear the canvas.
$hex2 = hexrgb($colours['cnvs']);
$thecolor = ImageColorAllocate ($im, $hex2["r"], $hex2["g"], $hex2["b"]);
ImageFilledRectangle($im, 0, 0, $width, $height, $thecolor);

$myTables= Array(); // Used for plotting the connecting relationships.
$sql = "SELECT table1 as aTable, field_index1, field_index2 from ".ERD_TABLE." where project_id='".$cfg['Servers'][$db]['project']."' AND diagram_id='$id' AND rec_type='T' order by table1";
$rs = $conn->Execute($sql);
if (!$rs) {
	print formatError($conn->ErrorMsg(), $conn->ErrorNo(), basename(__FILE__), $sql);
	exit;
}
while (!$rs->EOF) {
	$aTable= $rs->fields['aTable'];
	$myX= $rs->fields['field_index1'];
	$myY= $rs->fields['field_index2'];
	$sql="select * from $aTable";
	$rsFields = $conn->Execute($sql);
	if ($rsFields) {
		$tablesAdded[]= $aTable;
		$myTables[$aTable] = Array();
		$myTables[$aTable]['x']= $myX;
		$myTables[$aTable]['y']= $myY;
		$fields= $rsFields->FieldCount();

		// erase anything under this table
		$hex2 = hexrgb($colours['tbbg']);
		$thecolor = ImageColorAllocate ($im, $hex2["r"], $hex2["g"], $hex2["b"]);
		ImageFilledRectangle($im, $myX, $myY, $myX+$ss[$s]['tWidth'], $myY+(($fields+1)*$ss[$s]['rh']), $thecolor);

		$hex2 = hexrgb($colours['brdrs']);
		$thecolor = ImageColorAllocate ($im, $hex2["r"], $hex2["g"], $hex2["b"]);
		ImageRectangle      ($im, $myX, $myY, $myX+$ss[$s]['tWidth'], $myY+(($fields+1)*$ss[$s]['rh']), $thecolor);
		ImageLine($im, $myX, $myY+$ss[$s]['rh'], $myX+$ss[$s]['tWidth'], $myY+$ss[$s]['rh'], $thecolor);

		// write table header
		$fName= substr($aTable, $stubLength);
		$hex2 = hexrgb($colours['txt']);
		$thecolor = ImageColorAllocate ($im, $hex2["r"], $hex2["g"], $hex2["b"]);
		imagestring($im, $ss[$s]['header'], $myX+2, $myY+1, $fName, $thecolor);

		// write the table fields
		for ($i=0; $i < $fields; $i++) {
			$fld= $rsFields->FetchField($i);
			$fName= $fld->name;
			/***********************************************************************************
			 DB TYPE SWITCH REQUIRED
			 ***********************************************************************************/
/*
			$flags = mysql_field_flags($queryFields, $i);
			$array=(explode(" ",$flags)); 		//$field_name=mysql_field_name($query, $i);
			if (array_search('primary_key', $array)) {
				$fName.= " ".$pkChar;
			}elseif (array_search('unique_key', $array)) {
				$fName.= " ".$ukChar;
			}
*/
			$hex2 = hexrgb($colours['txt']);
			$thecolor = ImageColorAllocate ($im, $hex2["r"], $hex2["g"], $hex2["b"]);
			imagestring($im, $ss[$s]['tableText'], $myX+2, $myY+1 + (($i+1)*$ss[$s]['rh']),$fName , $thecolor);
		}
	}else{
		/* in mysql one would check the error number.
		 If mysql_errno()==1146 (Message: Table '%s.%s' doesn't exist)
		 	then create the table, otherwise HALT.
		 However, to make this generic, assume the error is 'no such table' */
		//if (mysql_errno()==1146) {
		$sql = "delete from ".ERD_TABLE." where project_id='".$cfg['Servers'][$db]['project']."' AND diagram_id='$id' AND table1='$aTable' and rec_type='T'";
		$rsDel = $conn->Execute($sql);
		if (!$rsDel) {
			print formatError($conn->ErrorMsg(), $conn->ErrorNo(), basename(__FILE__), $sql);
			exit;
		}
		$msg.= "MISSING: $aTable not found. All co-ordinate data has been removed.<br />";
	}
	$rs->MoveNext();
}
$rs->Close(); # optional



// Connect the tables
$sql = "SELECT table1, field_index1, cardinality1, table2, field_index2, cardinality2, relationship from ".ERD_TABLE." where project_id='".$cfg['Servers'][$db]['project']."' AND diagram_id='$id' AND rec_type='R'";
$rs = $conn->Execute($sql);
if (!$rs) die( formatError($conn->ErrorMsg(), $conn->ErrorNo(), basename(__FILE__), $sql));

while (!$rs->EOF) {
	# Note:  list() only works on numerical arrays and assumes the numerical indices start at 0.
	# Therefore...one way to use the list function with non-numerical keys is to use the array_values() function
	list($table1, $field_index1, $cardinality1, $table2, $field_index2, $cardinality2, $relationship) = array_values($rs->fields);

	$table1F=substr($table1, $stubLength);
	$table2F=substr($table2, $stubLength);

	if (isset($myTables[$rs->fields['table1']]) && isset($myTables[$rs->fields['table2']])) {
		$x1=$myTables[$table1]['x'];
		$y1=$myTables[$table1]['y'];
		$y1=$y1+(floor($ss[$s]['rh']/2))+($ss[$s]['rh']*($rs->fields['field_index1']+1));

		$x2=$myTables[$table2]['x'];
		$y2=$myTables[$table2]['y'];
		$y2=$y2+(floor($ss[$s]['rh']/2))+($ss[$s]['rh']*($rs->fields['field_index2']+1));

		// determine which sides of the tables to connect to
		$start= -1; $end= -1;
		if ($x2-$x1> $ss[$s]['tWidth']) {
			$x1=$x1+$ss[$s]['tWidth'];
			$start= 1;
		}elseif ($x2-$x1< $ss[$s]['tWidth'] && $x2-$x1> $ss[$s]['tWidth']/2) {
			$x1=$x1+$ss[$s]['tWidth'];
			$start= 1;
		}elseif ($x1-$x2> $ss[$s]['tWidth']) {
			$x2=$x2+$ss[$s]['tWidth'];
			$end= 1;
		}

		$hex2 = hexrgb($colours['lns']);
		$thecolor = ImageColorAllocate ($im, $hex2["r"], $hex2["g"], $hex2["b"]);
		ImageLine($im, $x1, $y1, $x1+($start*$ss[$s]['handle']), $y1, $thecolor); // stickyout bit - start point
		ImageLine($im, $x2, $y2, $x2+($end*$ss[$s]['handle']), $y2, $thecolor); // stickyout bit - end point
		$style=array($thecolor, $thecolor, IMG_COLOR_TRANSPARENT, IMG_COLOR_TRANSPARENT );
		imagesetstyle($im, $style);
		ImageLine($im, $x1+($start*$ss[$s]['handle']), $y1, $x2+($end*$ss[$s]['handle']), $y2, IMG_COLOR_STYLED); // connector

		// mark cardinalities
		if ($start==-1) $start=-3;
		if ($end==-1) $end=-3;
		$hex2 = hexrgb($colours['txt']);
		$thecolor = ImageColorAllocate ($im, $hex2["r"], $hex2["g"], $hex2["b"]);
		imagestring($im, $ss[$s]['header'], $x1+$start*3, $y1-$ss[$s]['rh'], $rs->fields['cardinality1'], $thecolor);
		imagestring($im, $ss[$s]['header'], $x2+$end*3, 	$y2-$ss[$s]['rh'], $rs->fields['cardinality2'], $thecolor);
	}else{
		if (!isset($myTables[$rs->fields['table1']])) {
			$msg.= "<br />Add table <u>$table1F</u> for the $table1F/$table2F relationship.";
		}
		if (!isset($myTables[$rs->fields['table2']])) {
			$msg.= "<br />Add table <u>$table2F</u> for the $table1F/$table2F relationship.";
		}
	}
	$rs->MoveNext();
}
$rs->Close(); # optional

// write footer info
// comment out these 3 lines if the colours are wrong
$hex2 = hexrgb($colours['cnvs']);
$thecolor = ImageColorAllocate ($im, $hex2["r"], $hex2["g"], $hex2["b"]);
ImageFilledRectangle($im, 0, $height-10, $width, $height, $thecolor);


$hex2 = hexrgb($colours['info']);
$thecolor = ImageColorAllocate ($im, $hex2["r"], $hex2["g"], $hex2["b"]);
$tmp= "db:".$dbName. "(".$cfg['Servers'][$db]['type'].")";
$stub==''? $tmpStub='': $tmpStub= " (".$stub.")";
$tmp.=" | project:".$cfg['Servers'][$db]['project'].$tmpStub;
$tmp.=" | id:$id (".$filesize = byte_format(filesize(MY_DIAGRAM), 1).")";
$tmp.=" | ".getLastModified($conn, ERD_TABLE, $id);
$tmp.=" | ".MY_PROJECT.": ".MY_VERSION;
$tmp.=" | ".MY_WEBSITE;
imagestring($im, 1, 1, $height-8, $tmp, $thecolor);

ImagePng($im, MY_DIAGRAM);
ImageDestroy($im);
?>