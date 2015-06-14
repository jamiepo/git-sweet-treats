<?
/*********************************
 get the ADODB field type
 *********************************/
function getFieldType($conn, $table, $index) {
	$sql= "select * from $table";
	$rs = $conn->Execute($sql);
	if (!$rs) die( formatError($conn->ErrorMsg(), $conn->ErrorNo(), basename(__FILE__), $sql));
	$fld= $rs->FetchField($index);
	$type= $rs->MetaType( $fld->type, $fld->max_length, $fld);
	return $type;
}


function postHeader($page, $msg, $refresh) {
	global $upDirectoryLink;
	$refresh? $q= '?action=refresh': $q= '';
	$myLinks[]='Home';$myUrls[]="index.php";
	$myLinks[]='E.R.D.';$myUrls[]="erd.php".$q;
	$myLinks[]='Schema';$myUrls[]="schema.php";
	if (ENABLE_SETTINGS== 'true') {
		$myLinks[]='Settings';$myUrls[]="settings.php";
	}
	$myLinks[]=$upDirectoryLink;$myUrls[]="../../";
	$end='&nbsp;|&nbsp;';
	$max=count($myLinks)-1;
	print "<div id='top'>";
	for($i=0;$i< $max; $i++) {
		$myLinks[$i]==$page? $class='navon': $class='navoff';
		print "<a class='$class' href='$myUrls[$i]'>$myLinks[$i]</a>$end";
	}
	$myLinks[$max]==$page? $class='navon': $class='navoff';
	print "<a class='$class' href='$myUrls[$max]'>$myLinks[$max]</a>";
	print "</div>";
	if ($msg<>'') print "<div class='msg'>$msg</div>";
	print "<div id='main'>";
}


function getMenu($arrayValues, $arrayNames, $name, $class='', $default=null, $defName='') {
 	$menu= "<select name ='$name' class='".$class."'>";
	if ($default==null) {
		$menu.= "<option value=''>".$defName;
	}
	for ($i=0; $i<count($arrayValues); $i++)	{
		$menu.= "<option value='".$arrayValues[$i]."'";
		if ($arrayValues[$i]==$default) $menu.= " selected";
		$menu.= ">".$arrayNames[$i];
	}
	$menu.= "</select>";
	return $menu;
}

function array_to_string($array) {
  $str='';
  for ($i=0; $i<count($array)-1; $i++) {
  	$str.= $array[$i].', ';
  }
  if (count($array)>1) {
  	$str= substr($str,0,strlen($str)-2);
  	$str.= ' and '.$array[count($array)-1];
  }else{
  	$str= $array[0];
  }

  return $str;
}

function getNow(&$conn) {
	$ts= time();
	$now= $conn->DBTimeStamp($ts);
	$now= str_replace("'","",$now);
	return $now;
}

/*************************************
		Check; does the data record exist?
	 *************************************/
function myRecordExists($myErdTable, $sqlAttribs, $proj, $db, $attributes, $myDriver=null) {
	global $conn, $msg, $debug;
	$createRecord= true;
	if (in_array ( $myErdTable, $conn->MetaTables())) {
		$conn->SetFetchMode(ADODB_FETCH_ASSOC);
		$rs = $conn->SelectLimit($sqlAttribs, 1);
		if (!$rs) die( formatError($conn->ErrorMsg(), $conn->ErrorNo(), basename(__FILE__), $sqlAttribs));
		if ($rs->RecordCount()>0) {
			$createRecord= false;
		}
	}else{
		# Assume table does not exist. Create a new table.
		createTable($conn, $myErdTable, $proj);
		$myDriver->alterModifiedColumn($conn, $myErdTable);
		$rs = $conn->Execute($sqlAttribs);
		if (!$rs) die( formatError($conn->ErrorMsg(), $conn->ErrorNo(), basename(__FILE__), $sqlAttribs));
		$rs->Close();
	}

	if ($createRecord) {
		# create a data record
		$id = passwordGenerator(6);
		$record = array();
		$record['project_id']= $proj['project'];
		$record['diagram_id']= $id;
		$record['rec_type']= 'D';
		$record['table1']= $attributes;
		$record['modified']= getNow($conn);
		$sql = $conn->GetInsertSQL($myErdTable, $record);
		if ($sql=="") die("empty sql string: sql=&quot;".$sql."&quot;");
		$rs = $conn->Execute($sql);

		if (!$rs) die( formatError($conn->ErrorMsg(), $conn->ErrorNo(), basename(__FILE__), $sql));
		$rs->Close(); # optional
		$msg.= "A new record has been inserted into table '$myErdTable'. 'diagram_id'='$id'.<br />";
		$sqlAttribs = "SELECT * from $myErdTable WHERE project_ID='".$proj['project']."' AND diagram_id='$id'AND rec_type='D'";
		$conn->SetFetchMode(ADODB_FETCH_ASSOC);
		$rs = $conn->Execute($sqlAttribs);
		if (!$rs) die( formatError($conn->ErrorMsg(), $conn->ErrorNo(), basename(__FILE__), $sqlAttribs));
	}
	return $rs;
}

function createTable($conn, $tabname, $project) {
	# Then create a data dictionary object, using this connection
  $dict = NewDataDictionary($conn, $project['type']);

  # We have a portable declarative data dictionary format in ADOdb, similar to SQL.
  # Field types use 1 character codes, and fields are separated by commas.

  $flds = "
  			id I(10) AUTO KEY,
  			project_id 		C(255) 	DEFAULT '',
  			diagram_id 		C(255) 	DEFAULT '',
  			rec_type 			C(1) 		DEFAULT '',
  			table1 				C(255) 	DEFAULT '',
  			field_index1	I 			DEFAULT NULL,
  			field_name1 	C(255)	DEFAULT '',
			  cardinality1 	C(1) 		DEFAULT '',
			  table2 				C(255) 	DEFAULT '',
			  field_index2 	I 			DEFAULT NULL,
			  field_name2 	C(255) 	DEFAULT '',
			  cardinality2 	C(1) 		DEFAULT '',
			  relationship 	C(255) 	DEFAULT '',
			  modified 			T DEFTIMESTAMP
  ";

	/* notes:
	 1. rec_type was originally enum('R','T','D') NOT NULL default 'R',
	 2. msaccess don't like this: field_index1	I(10) DEFAULT NULL. need to remove the (10).
	 */
  $sqlarray = $dict->CreateTableSQL($tabname, $flds);
  if ($sqlarray=="") die("empty sql");
  $status= $dict->ExecuteSQLArray($sqlarray);
  if ($status==0 || $status== 1) {
  	$msg= "status(".$status.") RETURNS: 0 if failed, 1 if executed all but with errors, 2 if executed successfully.";
  	$msg.="<br /><pre>".print_r_log($sqlarray)."</pre>";
  	die($msg);
  }
}



/*************************************************
	generate a random key of characters and numbers.
	Handy for random logins or verifications.
 *************************************************/
function passwordGenerator($length) {
	// RANDOM KEY PARAMETERS
	$keychars = "abcdefghijklmnopqrstuvwxyz0123456789";

	// RANDOM KEY GENERATOR
	$randkey = "";
	for ($i=0;$i< $length;$i++)
  	$randkey .= substr($keychars, rand(1, strlen($keychars) ), 1);

  return $randkey;

  # $x= (md5 (uniqid (rand()))); # for a VERY random
}

/***************************************
 Delete an item in an array
 ***************************************/
function my_array_delete($array, $item) {
   if (isset($array[$item]))
       unset($array[$item]);
   return array_merge($array);
}

function byte_format($input, $dec=0) {
  $prefix_arr = array(" B", "KB", "MB", "GB", "TB");
  $value = round($input, $dec);
  $i=0;
  while ($value>1024) {
     $value /= 1024;
     $i++;
  }
  $return_str = round($value, $dec).$prefix_arr[$i];
  return $return_str;
}



function getLastModified($conn, $myErdTable, $id) {
	$sql = "select modified from $myErdTable WHERE diagram_id='$id' order by modified DESC";
	$rs = $conn->SelectLimit($sql, 1);
	if (!$rs) {
		print formatError($conn->ErrorMsg(), $conn->ErrorNo(), basename(__FILE__), $sql);
		exit;
	}
	if ($rs->RecordCount()==0) {
		$count= '';
	}else{
		$count= $conn->UserTimeStamp($rs->fields['modified'], "d/m/Y H:i");
	}
	$rs->Close(); # optional
	return $count;
}


function checkRelationships($id, $conn, $myErdTable, $stubLength) {
	// check that the fieldname/fieldindex in the table match the actuality
	$msg='';
	$sql = "SELECT * from $myErdTable WHERE diagram_id='$id' AND rec_type='R'";
	$conn->SetFetchMode(ADODB_FETCH_ASSOC);
	$rs = $conn->Execute($sql);
	if (!$rs) die( formatError($conn->ErrorMsg(), $conn->ErrorNo(), basename(__FILE__), $sql));
	for ($i=1; $i< 3; $i++) { // 2 'tables' per record
		$rs->MoveFirst(); # note, some databases do not support MoveFirst
		while (!$rs->EOF) {
			$table= $rs->fields["table$i"];
			$fName= $rs->fields["field_name$i"];
			$sql="select * from $table";
			$rs2 = $conn->Execute($sql);
			if ($rs2) {
				$fields = $rs2->FieldCount(); // mysql_num_fields($query2);
				for ($j=0; $j < $fields; $j++) {
					$tmp= "<b>".substr($table, $stubLength).".$fName</b> (index $j). ";
					$fld= $rs2->FetchField($j);
					if ($fld->name==$fName && $rs->fields["field_index$i"]<>$j) {
						$msg.= $tmp."RIGHT NAME, WRONG INDEX.<br />";
					}elseif ($fld->name <>$fName && $rs->fields["field_index$i"]==$j) {
						$msg.= $tmp."WRONG NAME, RIGHT INDEX.<br />";
					}
				}
				# if its the last field that's been removed from a table, and that was the field that was part of a relationship...
				if ($rs->fields["field_index$i"]>= $fields) {
					$msg.= "<b>".substr($table, $stubLength).".[MISSING]</b> (index $j). DISAPPEARED<br />";
				}

			}else{
				/***************************************
				 if the table does not exist...
				 these errors are picked up in canvas.php

				 $msg.= "MISSING: Table: ".substr($table, $stubLength)." not found.<br />";
				 ***************************************/
			}
			$rs->MoveNext();
		}

	}
	if ($msg<>'') {
		$tmp= "The following tables have been modified/deleted since you last defined their relationships.";
		$tmp.="<br />Relationships in these tables are now corrupt.";
		$tmp.="<br />Delete the relationships, or redefine <a href='schema.php'>here</a>.<br /><br />";
		$tmp.=$msg;
		return $tmp;
	}
	return '';
}

function checkRelationships2($i, $id, $conn, $myErdTable, $stubLength, $record_id) {
	// check that the fieldname/fieldindex in the table match the actuality
	$msg='';
	$sql = "SELECT * from $myErdTable WHERE id='".$record_id."'";
	$conn->SetFetchMode(ADODB_FETCH_ASSOC);
	$rs = $conn->Execute($sql);
	if (!$rs) die( formatError($conn->ErrorMsg(), $conn->ErrorNo(), basename(__FILE__), $sql));

	while (!$rs->EOF) {
		$table= $rs->fields["table$i"];
		$fName= $rs->fields["field_name$i"];
		$sql="select * from $table";
		$rs2 = $conn->Execute($sql);
		if ($rs2) {
			$fields = $rs2->FieldCount(); // mysql_num_fields($query2);
			for ($j=0; $j < $fields; $j++) {
				$tmp= "<b>".substr($table, $stubLength).".$fName</b> (index $j). ";
				$fld= $rs2->FetchField($j);
				if ($fld->name==$fName && $rs->fields["field_index$i"]<>$j) {
					$msg.= $tmp."RIGHT NAME, WRONG INDEX.<br />";
				}elseif ($fld->name <>$fName && $rs->fields["field_index$i"]==$j) {
					$msg.= $tmp."WRONG NAME, RIGHT INDEX.<br />";
				}
			}
			# if its the last field that's been removed from a table, and that was the field that was part of a relationship...
			if ($rs->fields["field_index$i"]>= $fields) {
				$msg.= "<b>".substr($table, $stubLength).".[MISSING]</b> (index $j). DISAPPEARED<br />";
			}
		}
		/***************************************
		 if the table does not exist...
		 these errors are picked up in canvas.php
		 $msg.= "MISSING: Table: ".substr($table, $stubLength)." not found.<br />";
		 ***************************************/
		$rs->MoveNext();
	}
	if ($msg<>'') {
		//print ">$msg";
		$tmp= "<div class='msg'>Table has changed since relationship was defined.";
		$tmp.="<br />Delete, or RE-SAVE this configuration.<font color='red'></font><br />";
		$tmp.=$msg."</div>";;
		return $tmp;
	}
}

function checkRelationships3($id, $conn, $myErdTable, $stubLength, $debug) {
	// check that the fieldname/fieldindex in the table match the actuality
	$msg='';
	$sql = "SELECT * from $myErdTable WHERE diagram_id='$id' AND rec_type='R'";
	$conn->SetFetchMode(ADODB_FETCH_ASSOC);
	$rs = $conn->Execute($sql);
	if (!$rs) die( formatError($conn->ErrorMsg(), $conn->ErrorNo(), basename(__FILE__), $sql));
	for ($i=1; $i< 3; $i++) { // 2 'tables' per record
		$rs->MoveFirst(); # note, some databases do not support MoveFirst
		while (!$rs->EOF) {
			$table= $rs->fields["table$i"];
			$fName= $rs->fields["field_name$i"];
			$sql="select * from $table";
			$rs2 = $conn->Execute($sql);
			if ($rs2) {
				$fields = $rs2->FieldCount(); // mysql_num_fields($query2);
				for ($j=0; $j < $fields; $j++) {
					$tmp= "<b>".substr($table, $stubLength).".$fName</b> (index $j). ";
					$fld= $rs2->FetchField($j);
					if ($fld->name==$fName && $rs->fields["field_index$i"]<>$j) {
						$msg.= $tmp."RIGHT NAME, WRONG INDEX.<br />";
					}elseif ($fld->name <>$fName && $rs->fields["field_index$i"]==$j) {
						$msg.= $tmp."WRONG NAME, RIGHT INDEX.<br />";
					}
				}
				# if its the last field that's been removed from a table, and that was the field that was part of a relationship...
				if ($rs->fields["field_index$i"]>= $fields) {
					$msg.= "<b>".substr($table, $stubLength).".[MISSING]</b> (index $j). DISAPPEARED<br />";
				}

			}else{
				/***************************************
				 if the table does not exist...
				 these errors are picked up in canvas.php

				 $msg.= "MISSING: Table: ".substr($table, $stubLength)." not found.<br />";
				 ***************************************/
			}
			$rs->MoveNext();
		}

	}
	if ($msg<>'') {
		$tmp= "The following tables have been modified/deleted since you last defined their relationships.";
		$tmp.="<br />Relationships in these tables are now corrupt.";
		$tmp.="<br />Delete the relationships, or redefine <a href='schema.php'>here</a>.<br /><br />";
		$tmp.=$msg;
		return $tmp;
	}

	// VALID DATA
	if ($debug==1) {
		return 'The relationships seem to be valid.';
	}else{
		return '';
	}
}

function hexrgb($hex) {
	$rgbcolor=array("r"=>hexdec(substr($hex,0,2)),"g"=>hexdec(substr($hex,2,2)),"b"=>hexdec(substr($hex,4,2)));
	return $rgbcolor;
}

function cookieAlert() {
?>
	<html>
	<head>
	<title>Cookie Alert</title>
	<link rel="stylesheet" href="css/style.css">
	</head>
	<body>
	<div id="nav">
		It seems that your browser will not accept cookies.<br />
		The cookie saves a diagram id and an anonymous db reference.<br />
		If you're stuck on this page, chances are you need to re-configure your browser.<br />
		Otherwise it may be a firewall problem (e.g. zonelabs).<br />Try deleting any cookies currently associated with this webpage.<br />
		<a href="index.php">Home</a>
	</div>
	</body></html>
<?
}

function reloader($project, $id, $msg) {
	print <<<END
	<html>
	<head>
	<title>$project: Reloader</title>
	<link rel='stylesheet' href='css/style.css'>
	<meta http-equiv='Refresh' content='0; URL=erd.php'>
	</head>
	<body>
	<div class='box1'>
		LOADING NEW CONFIGURATION FOR <b>$id</b>.<br />
		If nothing happens <a href='erd.php'>click here</a>.<br /><br />
		Your browser will need to accept cookies to use this software.<br />
		If you're stuck on this page, chances are you need to re-configure your browser.<br />
		The cookie saves a diagram id and an anonymous db reference.</div>
	</body></html>
END;
}

function settingsDisabled() {
?>
	<html>
	<head>
	<title>Settings Disabled</title>
	<link rel="stylesheet" href="css/style.css">
	</head>
	<body>
	<div id="nav">
		It seems that the &quot;settings&quot; page has been disabled for security reasons.<br />
		See readme.txt.<br />
		<a href="index.php">Home</a>
	</div>
	</body></html>
<?
}

function formatError($msg, $no, $file, $sql) {
	return "<hr>".$msg."<br /><br />Error No.: ".$no."<br /><br />File: ".$file."<br /><br />SQL: ".$sql."<hr>";
}

function print_r_log($var) {
   ob_start();
   print_r($var);
   $ret_str = ob_get_contents();
   ob_end_clean();

   return $ret_str;
}

function debug() {
	$ArrayListName = array("_GET", "_POST", "_COOKIE");
	$ArrayList = array($_GET, $_POST, $_COOKIE);
	for ($i=0; $i<count($ArrayList); $i++) {
		echo "<br />$ArrayListName[$i]";
		while (list ($key, $val) = each ($ArrayList[$i])) {
	  	echo "<br />".$key."-".$val;
	  }
	  echo "<br><br>";
	}

}
?>