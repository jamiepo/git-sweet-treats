<?
require("inc/_init.php");

if(!@filesize(MY_DIAGRAM)) $action='refresh';	# The page will need to be re-freshed.

$reload=	true; # sometimes the canvas needs to be re-generated
switch ($action) {
	case 'add':	// index actions
		$record = array();
		$record['project_id']= 		$cfg['Servers'][$db]['project'];
		$record['diagram_id']= 		$id;
		$record['rec_type']= 			'T';
		$record['table1']= 				$_POST['table'];
		$record['field_index1']= 	$_POST['xcoor'];
		$record['field_index2']= 	$_POST['ycoor'];
		$record['modified']= getNow($conn);
		$sql = $conn->GetInsertSQL($erd_table, $record);
		$rs = $conn->Execute($sql);
		if (!$rs) die( formatError($conn->ErrorMsg(), $conn->ErrorNo(), basename(__FILE__), $sql));
		break;

	case 'move':
		$sql= "select * from ".ERD_TABLE." WHERE project_id='".$cfg['Servers'][$db]['project']."' AND diagram_id='$id' AND table1='$_POST[table]' and rec_type='T'";
		$rs = $conn->Execute($sql);
		if (!$rs) die(formatError($conn->ErrorMsg(), $conn->ErrorNo(), basename(__FILE__), $sql));
		$record = array();
		$record['field_index1'] = $_POST['xcoor'];
		$record['field_index2'] = $_POST['ycoor'];
		$record['modified']= getNow($conn);
		$sql = $conn->GetUpdateSQL($rs, $record);
		$rs = $conn->Execute($sql);
		if (!$rs) die(formatError($conn->ErrorMsg(), $conn->ErrorNo(), basename(__FILE__), $sql));
		break;

	case 'remove':
		$sql= "delete from ".ERD_TABLE." WHERE project_id='".$cfg['Servers'][$db]['project']."' AND diagram_id='$id' AND table1='$_POST[table]' and rec_type='T'";
		$rs = $conn->Execute($sql);
		if (!$rs) die(formatError($conn->ErrorMsg(), $conn->ErrorNo(), basename(__FILE__), $sql));
		break;

	case 'settings':
		// remove hash from colours
		while (list ($key, $val) = each ($_POST)) {
			/*		if(substr($val, 0, 1)=='#') {			$_POST[$key]= substr($_POST[$key],1);		}		*/
			$_POST[$key]= str_replace('#','',$_POST[$key]);
		}
		$newID=	$_POST['newID'];
		$attributes= "$_POST[width]:$_POST[height]:$_POST[canvas]:$_POST[tBG]:$_POST[tBorders]:$_POST[tConnectors]:$_POST[tText]:$_POST[sizeScheme]";

		$sql= "select * from ".ERD_TABLE." where project_id='".$cfg['Servers'][$db]['project']."' AND diagram_id='$id' AND rec_type='D'";
		$rs = $conn->Execute($sql);
		if (!$rs) die(formatError($conn->ErrorMsg(), $conn->ErrorNo(), basename(__FILE__), $sql));
		$record = array();
		$record['table1']= $attributes;
		$record['modified']= getNow($conn);
		$sql= $conn->GetUpdateSQL($rs, $record); # If the data has not changed, no sql is returned
		if ($sql<>'') {
			$rs = $conn->Execute($sql);
			if (!$rs) die(formatError($conn->ErrorMsg(), $conn->ErrorNo(), basename(__FILE__), $sql." ..."));
		}


		if ($id<>$newID) {
			$sql= "select * from ".ERD_TABLE." where project_id='".$cfg['Servers'][$db]['project']."' AND diagram_id='$id'";
			$rs = $conn->Execute($sql);
			if (!$rs) die( formatError($conn->ErrorMsg(), $conn->ErrorNo(), basename(__FILE__), $sql));
			$record = array();
			$record['diagram_id']= $newID;
			$record['modified']= getNow($conn);
			$sql= $conn->GetUpdateSQL($rs, $record);
			$rs = $conn->Execute($sql);
			if (!$rs) die( formatError($conn->ErrorMsg(), $conn->ErrorNo(), basename(__FILE__), $sql));
		}
		$id= $newID; // redefine with new id
		break;

	case 'switchDiagrams':
		$id= $_POST['id']; // redefine with new posted id
		break;

	case 'new':		// new diagram. create a new data record using defaults
		$id= $_POST['newID'];
		$record = array();
		$record['table1']= $attributes;
		$record['rec_type']= 'D';
		$record['diagram_id']= $id;
		$record['modified']= getNow($conn);
		$sql = $conn->GetInsertSQL(ERD_TABLE, $record);
		$rs = $conn->Execute($sql);
		if (!$rs) die( formatError($conn->ErrorMsg(), $conn->ErrorNo(), basename(__FILE__), $sql));
		break;

	case 'switchDB':
		// This switch/case is in init.php. Just make sure the default don't set reload to false.
		break;

	case 'deleteDiagram':
		$sql = "delete from ".ERD_TABLE." where project_id='".$cfg['Servers'][$db]['project']."' AND diagram_id='$_POST[deleteID]'";
		$rs = $conn->Execute($sql);
		if (!$rs) die( formatError($conn->ErrorMsg(), $conn->ErrorNo(), basename(__FILE__), $sql));
		$reload= false;
		break;

	case 'refresh':
		/***********************************
		 Recreate the image. Possibly...
		  - inadvertently deleted.
			- returning from schema.php, with changes
		 ***********************************/
		break;

	default:
		$reload= false;
		break;
}

if ($action=='switchDB') {
	# pick a random diagram_id from that db. either:
	# - no cookies have been set (first time here?), db defaults to '1', see '$action= 'switchDB';' in init.php.
	# - user has selected new db from dropdown
	$sqlAttribs = "SELECT * from ".ERD_TABLE." where rec_type='D'"; // get any one LIMIT 1
}else{
	$sqlAttribs = "SELECT * from ".ERD_TABLE." WHERE project_id='".$cfg['Servers'][$db]['project']."' AND diagram_id='$id' AND rec_type='D'"; // LIMIT 1
}

#	Check: does the data record exist?
$rs= myRecordExists(ERD_TABLE, $sqlAttribs, $cfg['Servers'][$db], $db, $attributes, $myDriver);

while (!$rs->EOF) {
	$attributes= $rs->fields['table1'];
	$id= $rs->fields['diagram_id'];
	$rs->MoveNext();
}
$rs->Close(); # optional

list($width, $height, $colours['cnvs'], $colours['tbbg'], $colours['brdrs'], $colours['lns'], $colours['txt'], $s) = split(":", $attributes, 8);
// if headers have already been sent by ADODB, no cookies can be set.
if (!setcookie ("ERD", $id.":".$db, time()+36000000, '/') && DEBUG==0) {
	die(cookieAlert());
}

if ($reload) {	// resize/refill/regenerate the canvas
	$im = ImageCreate ($width, $height) or die ("Cannot Initialize new GD image stream");
	$hex2 = hexrgb($colours['cnvs']);
	$thecolor = ImageColorAllocate ($im, $hex2["r"], $hex2["g"], $hex2["b"]);
	ImageColorTransparent($im, $thecolor);
	ImagePng($im, MY_DIAGRAM);
	ImageDestroy($im);
	reloader(MY_PROJECT, $id, $msg);
	exit;
}

require("inc/_canvas.php");

/**************************************************
 generate move/add/remove drop-downs
 **************************************************/
$tablesAddedDD= 	"";
$tablesNotAddedDD="";
$tablesNotAdded= array();

if (!isset($tablesAdded)) {
	$tablesAdded[]=''; // A fresh diagram. Do this so it dont trip array_search()
}

if (isset($theTables)) { // there may be no tables available
	for ($i=0; $i<count($theTables); $i++) {
		$fTable= substr($theTables[$i],$stubLength);
		// Note:  Prior to PHP 4.2.0, array_search() returns NULL on failure instead of FALSE.
		if ( array_search($theTables[$i], $tablesAdded )=== null || array_search($theTables[$i], $tablesAdded )=== false) {
			$tablesNotAddedDD.= "<option value='$theTables[$i]'>$fTable";
			$tablesNotAdded[]= $theTables[$i]; // not necessary, but handy for debugging
		}else{
			$tablesAddedDD.= 	"<option value='$theTables[$i]'>$fTable";
		}
	}
}
if ($tablesNotAddedDD<>"") {
	$tablesNotAddedDD= "<option value='' selected>Add...".$tablesNotAddedDD;
	$sltAdd= "
		<tr>
			<td align='right'>
				<select class='nohilite' name='tableAdd' style='width:100px;' onchange='javascript:addMoveTable(this.form, this, \"add\");'>
					$tablesNotAddedDD
				</select>
			</td>
		</tr>";
}else{
	$sltAdd="";
}

if ($tablesAddedDD<>"") {
	$tablesToRemoveDD= "<form method='POST' action='$_SERVER[PHP_SELF]' name='formRemove'>";
	$tablesToRemoveDD.= "<input type='hidden' name='action' value='remove'>";
	$tablesToRemoveDD.= "<table cellspacing='0' cellpadding='2' width='100%'>";
	$tablesToRemoveDD.= "<tr><td align='right'>";
	$tablesToRemoveDD.= "<select class='nohilite' name='table' style='width:100px;' onchange='javascript:document.formRemove.submit()'>";
	$tablesToRemoveDD.= "<option value='' selected>Remove...";
	$tablesToRemoveDD.= $tablesAddedDD;
	$tablesToRemoveDD.= "</select>";
	$tablesToRemoveDD.= "</td></tr></table></form>";

	$tablesToMoveDD = "<tr><td align='right'>";
	$tablesToMoveDD.= "<select class='nohilite' name='tableMove' style='width:100px;' onchange='javascript:addMoveTable(this.form, this, \"move\");'>";
	$tablesToMoveDD.= "<option value='' selected>Move...";
	$tablesToMoveDD.= $tablesAddedDD;
	$tablesToMoveDD.= "</select>";
	$tablesToMoveDD.= "</td></tr>";

}else{
	$tablesToRemoveDD= "";
	$tablesToMoveDD= "";
}

/**************************************************
 generate the size schema drop-down
 **************************************************/
$ssList='';
for ($i=0; $i< count($ss); $i++) {
	$s==$i? $selected='selected': $selected='';
	$ssList.="<option value='$i' $selected>$i";
}

/**************************************************
 * generate the diagrams drop-down
 **************************************************/
$diagramCount=0;
$sql = "SELECT * from ".ERD_TABLE." WHERE project_id='".$cfg['Servers'][$db]['project']."' AND diagram_id<>'". $id ."' AND rec_type='D' order by diagram_id";
$rs = $conn->Execute($sql);
if (!$rs) die( formatError($conn->ErrorMsg(), $conn->ErrorNo(), basename(__FILE__), $sql));
$diagramListOpen= "<option value='' selected>Open...";
$diagramListDelete= "<option value='' selected>Delete...";
while (!$rs->EOF) {
	$diagramCount++;
	$diagramListOpen.="<option value='".$rs->fields['diagram_id']."'>".$rs->fields['diagram_id'];
	$diagramListDelete.="<option value='".$rs->fields['diagram_id']."'>".$rs->fields['diagram_id'];
	$rs->MoveNext();
}
$rs->Close(); # optional

/**************************************************
 * Prepend a hash to colours, for presentation
 **************************************************/
while (list ($key, $val) = each ($colours)) {
	$colours[$key]='#'.$val;
}

$fHeight=$height.'px';
// An odd 1px gap at foot of diagram in IE. Fine in Mozilla. Cannae fix it

/*
if (DEBUG==1) {
	$msg.= "<table align='center'>";
	$msg.= "<tr><th>DB Tables</th><th>Project Tables</th><th>Added</th><th>Not Added</th><th>Excluded</th></tr>";
	$msg.= "<tr>";
	$msg.= "<td class='msg'><pre>".print_r_log($theEntireTables)."</pre></td>";
	$msg.= "<td class='msg'><pre>".print_r_log($theTables)."</pre></td>";
	$msg.= "<td class='msg'><pre>".print_r_log($tablesAdded)."</pre></td>";
	$msg.= "<td class='msg'><pre>".print_r_log($tablesNotAdded)."</pre></td>";
	$msg.= "<td class='msg'><pre>".print_r_log($excluded)."</pre></td>";
	$msg.= "</tr></table>";
}
*/

$msg.=  checkRelationships($id, $conn, ERD_TABLE, $stubLength);
$page= "E.R.D.";
require("inc/_header.erd.php");
postHeader($page, $msg, $refresh);
$path= MY_DIAGRAM;
print <<<END
<table cellpadding='1' cellspacing='0' align='center' style='border:3px solid #ccc;'>
	<tr>
		<td align='center' valign='top'>
			<table cellpadding='0' cellspacing='0' style='border:1px solid #ccc;'>
				<tr>
					<td width='16px'>
						<img src='images/corner.png' height='16px' width='16px'></td>
					<td style='background: url(images/ttopruler.png) repeat-x left;'>
						<img height='16' width='$width' src='images/spacer.gif'></td>
				</tr><tr>
					<td width='16px' style='margin:0; background: url(images/lleftruler.png) repeat-y top;'>
						<img src='images/spacer.gif' height='$height' width='16' /></td>
					<td height='$fHeight'>
						<div id='imgLayer' style='height:$fHeight'>
							<script language='JavaScript' src='js/browserdetect.js'>
							// write start of link in js
							</script>
							<img name='drawing' src='./inc/showimage.php?p=$path' width='$width' height='$height' /></A>
						</div>
					</td>
				</tr>
			</table>
		</td>
		<td valign='top' style='border:0px solid #ccc;'>
END;
if (count($cfg)> 0) {
	print <<<END
	<div class='myBox'>
	<h6>DB: <span class='hilite'>$dbName</span></h6>
	<form method='POST' action='$_SERVER[PHP_SELF]' name='formSwitch'>
	<input type='hidden' name='action' value='switchDB'>
	<table cellspacing='0' cellpadding='2' width='100%'>
	<caption>Project</caption>
	<tr>
		<td align='right'>
			$databases
		</td>
	</tr>
	</table>
	</form>
	</div>
END;
}
	print <<<END
	<div class='myBox'>
	<form method='POST' action='$_SERVER[PHP_SELF]' name='formAddOrMove'>
	<input type='hidden' name='action' value=''>
	<input type='hidden' name='table' value=''>
	<table cellspacing='0' cellpadding='2' width='100%'>
	<caption>Tables</caption>
		<tr>
			<td align='right'>
			X <input type='text' style='width:30px' maxlength='4' name='xcoor' value='5' />
			Y <input type='text' style='width:30px' maxlength='4' name='ycoor' value='5' />
			</td>
		</tr>
			$tablesToMoveDD
			$sltAdd
	</table>
	</form>
		$tablesToRemoveDD
	</div>
	<div class='myBox'>
	<form onsubmit='return vCanvasSettings(this, $width, $height);' method='POST' action='$_SERVER[PHP_SELF]' name='formSettings'>
	<input name='action' value='settings' type='hidden'>
	<input name='db' value='$db' type='hidden'>
	<table cellspacing='0' cellpadding='2' width='100%'>
	<caption>Settings</caption>
	<tr>
		<td align='right'>ID <INPUT TYPE='text' NAME='newID' style='width:105px' VALUE='$id'></td>
	</tr><tr>
		<td align='right'>W <input type='text' name='width'  style='width:30px' value='$width' maxlength='4'>
			H <input type='text' name='height' style='width:30px' value='$height' maxlength='4'></td>
	</tr><tr>
		<td align='right'>Sizes <select name='sizeScheme' style='width:60px;'>$ssList</select></td>
	</tr><tr>
		<td align='right'>

			<!-- flooble.com Color Picker start -->
			<a class='clrs' href='javascript:pickColor("pickCanvas");'>Canvas</a> <a href='javascript:pickColor("pickCanvas");' id='pickCanvas' style='border: 1px solid #000000;'>&nbsp;&nbsp;&nbsp;</a>
			<input id='pickCanvasfield' size='7' onChange='relateColor("pickCanvas", this.value);' name='canvas' value='$colours[cnvs]' alt='blank'>
			<script language='javascript'>relateColor('pickCanvas', getObj('pickCanvasfield').value);</script>
			<!-- flooble Color Picker end -->

			</td>
	</tr><tr>
		<td align='right'>
			<!-- flooble.com Color Picker start -->
			<a class='clrs' href='javascript:pickColor("pickTables");'>Tables</a> <a href='javascript:pickColor("pickTables");' id='pickTables' style='border: 1px solid #000000;'>&nbsp;&nbsp;&nbsp;</a>
			<input id='pickTablesfield' size='7' onChange='relateColor('pickTables', this.value);' name='tBG' value='$colours[tbbg]' alt='blank'>
			<script language='javascript'>relateColor('pickTables', getObj('pickTablesfield').value);</script>
			<!-- flooble Color Picker end -->
		</td>
	</tr><tr>
		<td align='right'>
			<!-- flooble.com Color Picker start -->
			<a class='clrs' href='javascript:pickColor("pickText");'>Text</a> <a href='javascript:pickColor("pickText");' id='pickText' style='border: 1px solid #000000;'>&nbsp;&nbsp;&nbsp;</a>
			<input id='pickTextfield' size='7' onChange='relateColor('pickText', this.value);' name='tText' value='$colours[txt]' alt='blank'>
			<script language='javascript'>relateColor('pickText', getObj('pickTextfield').value);</script>
			<!-- flooble Color Picker end -->
		</td>
	</tr><tr>
		<td align='right'>
			<!-- flooble.com Color Picker start -->
			<a class='clrs' href='javascript:pickColor("pickBorders");'>Borders</a> <a href='javascript:pickColor("pickBorders");' id='pickBorders' style='border: 1px solid #000000;'>&nbsp;&nbsp;&nbsp;</a>
			<input id='pickBordersfield' size='7' onChange='relateColor('pickBorders', this.value);' name='tBorders' value='$colours[brdrs]' alt='blank'>
			<script language='javascript'>relateColor('pickBorders', getObj('pickBordersfield').value);</script>
			<!-- flooble Color Picker end -->
		</td>
	</tr><tr>
		<td align='right'>

			<!-- flooble.com Color Picker start -->
			<a class='clrs' href='javascript:pickColor("pickLines");'>Lines</a> <a href='javascript:pickColor("pickLines");' id='pickLines' style='border: 1px solid #000000;'>&nbsp;&nbsp;&nbsp;</a>
			<input id='pickLinesfield' size='7' onChange='relateColor('pickLines', this.value);' name='tConnectors' value='$colours[lns]' alt='blank'>
			<script language='javascript'>relateColor('pickLines', getObj('pickLinesfield').value);</script>
			<!-- flooble Color Picker end -->
		</td>
	</tr><tr>
		<td align='right'><input class='btn' type='submit' value='save' /></td>
	</tr>
	</table>
	</form>
	</div>
	<div class='myBox'>
	<form onsubmit='javascript:return newDiagram(this);' method='POST' action='$_SERVER[PHP_SELF]' name='fNewDiagram'>
	<input type='hidden' name='action' value='new'>
	<table cellspacing='0' cellpadding='2' width='100%'>
	<caption>Diagrams</caption>
	<tr>
		<td align='right'>ID <INPUT TYPE='text' NAME='newID' style='width:105px' VALUE=''></td>
	</tr><tr>
		<td align='right'><input class='btn' type='submit' value='new diagram' /></td>
	</tr>
	</table>
	</form>
	</div>
END;
if ($diagramCount>0) {
	print <<<END
	<div class='myBox'>
	<form method='POST' action='$_SERVER[PHP_SELF]' name='formSwitchDiagrams'>
	<input name='action' value='switchDiagrams' type='hidden'>
	<table cellspacing='0' cellpadding='2' width='100%'>
	<tr>
		<td align='right'><select name='id' style='width:100px;' onchange='document.formSwitchDiagrams.submit()'>$diagramListOpen</select></td>
	</tr>
	</table>
	</form>
	</div>
	<div class='myBox'>
	<form method='POST' action='$_SERVER[PHP_SELF]' name='fDeleteDiagram'>
	<input name='action' value='deleteDiagram' type='hidden'>
	<table cellspacing='0' cellpadding='2' width='100%'>
	<tr>
		<td align='right'><select name='deleteID' style='width:100px;' onchange='javascript:deleteDiagram(this.form)'>$diagramListDelete</select></td>
	</tr>
	</table>
	</form>
	</div>
END;
}
print <<<END
</td>
</tr>
</table>
END;

require("inc/_footer.php");
?>