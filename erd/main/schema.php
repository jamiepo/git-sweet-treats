<?php
require("inc/_init.php");

switch ($action) {
	case 'add':
		$temp1=explode('/', $_POST['child_1']);
		$temp2=explode('/', $_POST['child_2']);
		$table1= $_POST['parent_1'];
		$table2= $_POST['parent_2'];

		$mType1= getFieldType($conn, $table1, $temp1[0]);
		$mType1Temp= $mType1;
		if ($mType1=='I' || $mType1=='N' || $mType1=='R') {
			$mType1Temp= 'I';
		}

		$mType2= getFieldType($conn, $table2, $temp2[0]);
		$mType2Temp= $mType2;
		if ($mType2=='I' || $mType2=='N' || $mType2=='R') {
			$mType2Temp= 'I';
		}

		if ($mType1Temp<>$mType2Temp) {
			$msg.= '<br />The data types do not match: '.$mType1.'-'.$mType2;
			break;
		}

		$record = array();
		$record['project_id']= 		$cfg['Servers'][$db]['project'];
		$record['diagram_id']= 		$id;
		$record['rec_type']= 			'R';
		$record['table1']= 				$table1;
		$record['field_index1']= 	$temp1[0];
		$record['field_name1']= 	$temp1[1];
		$record['cardinality1']= 	$_POST['cardinality1'];
		$record['table2']= 				$table2;
		$record['field_index2']= 	$temp2[0];
		$record['field_name2']= 	$temp2[1];
		$record['cardinality2']= 	$_POST['cardinality2'];
		$record['relationship']= 	$_POST['relationship'];
		$record['modified']= 			getNow($conn);
		$sql = $conn->GetInsertSQL($erd_table, $record);
		$rs = $conn->Execute($sql);
		if (!$rs) die( formatError($conn->ErrorMsg(), $conn->ErrorNo(), basename(__FILE__), $sql));
		$msg.= '<br />New relationship added: '.$table1.' &lt;-&gt; '.$table2;
		break;

	case 'update':
		$temp1=explode('/', $_POST['field1']);
		$temp2=explode('/', $_POST['field2']);

		$table1= $_POST['table1'];
		$table2= $_POST['table2'];

		$mType1= getFieldType($conn, $table1, $temp1[0]);
		$mType1Temp= $mType1;
		if ($mType1=='I' || $mType1=='N' || $mType1=='R') {
			$mType1Temp= 'I';
		}

		$mType2= getFieldType($conn, $table2, $temp2[0]);
		$mType2Temp= $mType2;
		if ($mType2=='I' || $mType2=='N' || $mType2=='R') {
			$mType2Temp= 'I';
		}

		if ($mType1Temp<>$mType2Temp) {
			$msg.= '<br />The data types do not match: '.$mType1.'-'.$mType2;
			break;
		}

		$record = array();
		$record['field_index1']= 	$temp1[0];
		$record['field_name1']= 	$temp1[1];
		$record['cardinality1']= 	$_POST['cardinality1'];
		$record['field_index2']= 	$temp2[0];
		$record['field_name2']= 	$temp2[1];
		$record['cardinality2']= 	$_POST['cardinality2'];
		$record['relationship']= 	$_POST['relationship'];
		$record['modified']= 			getNow($conn);
		$sql= "select * from ".ERD_TABLE." WHERE id='".$_POST['editID']."'";
		$rs = $conn->Execute($sql);
		if (!$rs) die(formatError($conn->ErrorMsg(), $conn->ErrorNo(), basename(__FILE__), $sql));
		$sql = $conn->GetUpdateSQL($rs, $record);
		$rs = $conn->Execute($sql);
		if (!$rs) die(formatError($conn->ErrorMsg(), $conn->ErrorNo(), basename(__FILE__), $sql));
		$refresh= true;
		break;


	case 'deleteRelationship':
		$sql= "delete from ".ERD_TABLE." WHERE id='".$_POST['editID']."'";
		$rs = $conn->Execute($sql);
		if (!$rs) die(formatError($conn->ErrorMsg(), $conn->ErrorNo(), basename(__FILE__), $sql));
		$refresh= true;
		break;

	case 'switchDB':
		$sqlAttribs = "SELECT * from ".ERD_TABLE." WHERE rec_type='D'"; // get any one ( LIMIT 1
		$rs= myRecordExists(ERD_TABLE, $sqlAttribs, $cfg['Servers'][$db], $db, $attributes, $myDriver);
		while (!$rs->EOF) {
			$id= $rs->fields['diagram_id'];
			$rs->MoveNext();
		}
		$rs->Close(); # optional
		if (!setcookie ("ERD", "$id:$db", time()+36000000, '/') && !$debug) {
			die(cookieAlert());
		}
		$refresh= true;
		break;

	default:
}

$sltTables1="<select style='width:100px' name='parent_1'>$tablesAllDD</select>";
$sltTables2="<select style='width:100px' name='parent_2'>$tablesAllDD</select>";

$cardinalities="<option value='".$myCardinalities[0]."' selected>".$myCardinalities[0];
for ($i=0;$i<count($myCardinalities);$i++) {
	$cardinalities.="<option value='$myCardinalities[$i]'>$myCardinalities[$i]";
}
$format= "<select name='%s'>$cardinalities</select>";
$sltCardinalities1= sprintf($format, 'cardinality1');
$sltCardinalities2= sprintf($format, 'cardinality2');

//$msg.=  checkRelationships($id, $conn, ERD_TABLE, $stubLength, DEBUG);
$page= "Schema";
require("inc/_header.schema.php");
postHeader($page, $msg, $refresh);

$title = "project: <form style='inline' method='POST' action='$_SERVER[PHP_SELF]' name='formSwitch'>";
$title.= "<input type='hidden' name='action' value='switchDB'>".$databases."</form>";
$title.= " db: ".$dbName." (".$cfg['Servers'][$db]['type'].")";
if ($stub<>'') $title.= ' | stub: '.$stub;
print <<<END
<a name='top'></a><h4>$title</h4>

<table align='center' cellpadding='1' cellspacing='1'>
<form onsubmit='javascript:return vAddRelationship(this);' action='$_SERVER[PHP_SELF]' method='post' name='formRelationships'>
<input type='hidden' name='action' value='add'>
	<tr>
		<th class='title' colspan='9'>Define A New Relationship</th>
	</tr><tr>
		<th>Table 1</th><th>Field 1</th><th>C 1</th><th>Relationship</th><th>Table 2</th><th>Field 2</th><th>C 2</th><th>&nbsp;</th><th>&nbsp;</th>
	</tr><tr>
		<td>
			$sltTables1
		</td><td>
			<select class='relations' NAME='child_1'><SCRIPT>dol_1.printOptions('child_1')</SCRIPT></select>
		</td><td>
		 $sltCardinalities1
		</td><td>
		 <input style='width:100px; text-align:center;' type='text' name='relationship' value='$connectingPhrase'>
		</td><td>
			$sltTables2
		</td><td>
			<select class='relations' NAME='child_2'><SCRIPT>dol_2.printOptions('child_2')</SCRIPT></select>
		</td><td align='center'>
		 $sltCardinalities2
		</td><td align='center'>
			<input type='submit' class='btn' value='add' />
		</td><td align='center'>

		</td>
	</tr>
	</form>
	<tr>
		<th class='title' colspan='9'>Current Relationships</th>
	</tr>
END;
$sql="select * from ".ERD_TABLE." where project_id='".$cfg['Servers'][$db]['project']."' AND diagram_id='$id' AND rec_type='R' order by table1, table2";
$conn->SetFetchMode(ADODB_FETCH_ASSOC);
$rs = $conn->Execute($sql);
if (!$rs) die( formatError($conn->ErrorMsg(), $conn->ErrorNo(), basename(__FILE__), $sql));
while (!$rs->EOF) {
	print "<form action='$_SERVER[PHP_SELF]' method='post' name='formRelationships".$rs->fields['id']."'>";
	print "<input type='hidden' name='action' value='update'>";
	print "<input type='hidden' name='editID' value='".$rs->fields['id']."'>";
	print "<tr>";
	print "<td><a href='#".substr($rs->fields['table1'],$stubLength)."'>";
	print substr($rs->fields['table1'],$stubLength)."</a>";
	print "<input type='hidden' name='table1' value='".$rs->fields['table1']."'></td>";
	print "<td>";
	if (isset($myTableFields[$rs->fields['table1']]['values'])) {
		print getMenu($myTableFields[$rs->fields['table1']]['values'], $myTableFields[$rs->fields['table1']] ['names'] , 'field1', 'relations', $rs->fields['field_index1'].'/'.$rs->fields['field_name1']);
	}else{
		print "MISSING TABLE";
	}
	print checkRelationships2(1, $id, $conn, ERD_TABLE, $stubLength, $rs->fields['id']);
	print "</td>";
	print "<td align='center'>";
	print "<select name='cardinality1'>";
	$cardinalities="<option value='".$myCardinalities[0]."' selected>".$myCardinalities[0];
	for ($i=0;$i<count($myCardinalities);$i++) {
		$rs->fields['cardinality1']== $myCardinalities[$i]? $selected='selected': $selected='';
		print "<option value='$myCardinalities[$i]' $selected>$myCardinalities[$i]";
	}
	print "</select>";
	print "</td>";
	print "<td align='center'>";
	print "<input style='width:100px; text-align:center;' type='txt' name='relationship' value='".$rs->fields['relationship']."'>";
	print "</td>";

	print "<td><a href='#".substr($rs->fields['table2'],$stubLength)."'>";
	print substr($rs->fields['table2'],$stubLength)."</a>";
	print "<input type='hidden' name='table2' value='".$rs->fields['table2']."'></td>";

	print "<td>";
	if (isset($myTableFields[$rs->fields['table2']]['values'])) {
		print getMenu($myTableFields[$rs->fields['table2']]['values'], $myTableFields[$rs->fields['table2']] ['names'] , 'field2', 'relations', $rs->fields['field_index2'].'/'.$rs->fields['field_name2']);
	}else{
		print "MISSING TABLE";
	}
	print checkRelationships2(2, $id, $conn, ERD_TABLE, $stubLength, $rs->fields['id']);
	print "</td>";
	print "<td align='center'>";
	print "<select name='cardinality2'>";
	$cardinalities="<option value='".$myCardinalities[0]."' selected>".$myCardinalities[0];
	for ($i=0;$i<count($myCardinalities);$i++) {
		$rs->fields['cardinality2']== $myCardinalities[$i]? $selected='selected': $selected='';
		print "<option value='$myCardinalities[$i]' $selected>$myCardinalities[$i]";
	}
	print "</select>";
	print "</td>";
	print "<td align='center'><input name='btnSave' class='btn' type='submit' value='save'></td>";

	print "<td align='center'>";
	print "<input name='btnDelete' class='btn' type='button' value='delete' onclick='javascript:vDeleteRelationship(\"formRelationships".$rs->fields['id']."\", \"deleteRelationship\")' />";
	print "</td>";
	print "</tr>";
	print "</form>";
	$rs->MoveNext();
}
$rs->Close(); # optional
print "</table><br />";

print "<table align='center' cellpadding='1' cellspacing='1'>";
for ($j=0; $j<count($theTables); $j++) {
	$sql= "select * from $theTables[$j]";
	$rs = $conn->Execute($sql);
	if (!$rs) die( formatError($conn->ErrorMsg(), $conn->ErrorNo(), basename(__FILE__), $sql));
	$fields = $rs->FieldCount();
	print "<tr>";
	print "<th><a name='".substr($theTables[$j],$stubLength)."'></a>";
	print "<img src='images/table.png' width='12px' height='12px' /> ";
	print substr($theTables[$j],$stubLength)."</th><th colspan='2' style='text-align:right;'><small>fields: $fields | records: ".$rs->RecordCount()."</small> <a href='#top'>[top]</a></th>";
	print "</tr>";

	$fNames= $conn->MetaColumnNames($theTables[$j],$numericIndex=true);
	$field_result= $conn->MetaColumns($theTables[$j],false);
	for ($i=0; $i < $fields; $i++) {

		$x= strtoupper($fNames[$i]);
		$flags = '';
		//$flags.= 'field_result: ';
		//$flags.= $field_result[$x]->type;
		if(isset($field_result[$x]->primary_key) && $field_result[$x]->primary_key==1) 		$flags.= ' primary_key ';
		//if(isset($field_result[$x]->auto_increment) && $field_result[$x]->auto_increment==1) $flags.= 'auto_increment, ';
		if(isset($field_result[$x]->not_null) && $field_result[$x]->not_null==1) 		$flags.= ' not_null ';

		$primaryKeys= '<br /><br />MetaPrimaryKeys';
		$tmp = $conn->MetaPrimaryKeys($theTables[$j]); # dont work for access
		if (isset($tmp) && $tmp<>'') {
			if (in_array ( $fNames[$i], $conn->MetaPrimaryKeys($theTables[$j]))) {
				$primaryKeys.= '[primary key]';
			}
		}

		$fld= $rs->FetchField($i);
		$mType= $rs->MetaType( $fld->type, $fld->max_length, $fld);
		$flags2 = '';
		//$flags2.= 'fld: ';
		$flags2.= '['.$mType.'] '.$fld->type.' ('.$fld->max_length.')';
		//$flags.= "<pre>".print_r_log($field_result[$x])."</pre>";
		print "<tr>";
		print "<td>".$field_result[$x]->name."</td>";
		//$field_result[$x]->type
		//print "<td>".$fld->type." (".$field_result[$x]->max_length.")</td>";

		print "<td>".$flags2."</td>";
		print "<td>".$flags."</td>";
		print "</tr>";
	}
}

print "</table>";
print <<<END
<br /><table align='center'><tr><td>
<ul>
  <li><b>C</b>: <b>C</b>haracter fields that should be shown in a &lt;input type="text"&gt;
    tag.</li>
  <li><b>X</b>: Te<b>X</b>t, large text fields that should be shown in a &lt;textarea&gt;</li>
  <li><b>B</b>: <b>B</b>lobs, or Binary Large Objects. Typically images.
  </li><li><b>D</b>: <b>D</b>ate field</li>

  <li><b>T</b>: <b>T</b>imestamp field</li>
  <li><b>L</b>: <b>L</b>ogical field (boolean or bit-field)</li>
  <li><b>I</b>: <b>I</b>nteger field</li>
  <li><b>N</b>: <b>N</b>umeric field. Includes autoincrement, numeric, floating point, real and integer. </li>
  <li><b>R</b>: Se<b>R</b>ial field. Includes serial, autoincrement integers. This works for selected databases. </li>
</ul>
</td></tr></table>
END;
require('inc/_footer.php');
?>