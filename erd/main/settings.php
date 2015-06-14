<?php
# a stripped down set of inclusions and initialisations
require_once("../_config.php");
require_once("./inc/functions.php");
if (ENABLE_SETTINGS== 'false') {
	settingsDisabled();
	exit;
}

$msg=			'';
$refresh= false;				# occasionally attach a querystring to url


require_once(MY_PROJECTS);

if (isset($_COOKIE['ERD'])) {
	list($id, $db) = split(":", $_COOKIE["ERD"], 2);
}else{
	$db=0;
}

isset($_POST['action'])? $action= $_POST['action']: $action='';

switch ($action) {
	case 'updateProject':
		$i= $_POST['index'];
		$cfg['Servers'][$i]['type']= $_POST['strType'];
		$cfg['Servers'][$i]['host']= $_POST['strHost'];
		$cfg['Servers'][$i]['user']= $_POST['strUser'];
		$cfg['Servers'][$i]['password']= $_POST['strPassword'];
		$cfg['Servers'][$i]['db']= $_POST['strDB'];
		$cfg['Servers'][$i]['project']= $_POST['strProject'];
		$cfg['Servers'][$i]['stub']= $_POST['strStub'];
		if (!isset($_POST['strEnabled'])) $_POST['strEnabled']='false';
		$cfg['Servers'][$i]['enabled']= $_POST['strEnabled'];

		$format= "Updated project no.".$i." %s<br />";
		if ($_POST['index']== $db) {
			$msg.= sprintf($format, " (current project)");
		}else{
			$msg.= sprintf($format, "");
		}
		$rewrite= true;
		break;

	case 'deleteProject':
		$i= $_POST['index'];
		$tmp['Servers'][0]['type']= 'dummy'; # all this cos array[0] aint set
		$cfg['Servers']= array_merge($tmp['Servers'], $cfg['Servers']);
		$cfg['Servers']= my_array_delete($cfg['Servers'], $i);
		if (isset($cfg['Servers'][0])) {
			unset($cfg['Servers'][0]);
		}
		setcookie ("ERD", "0:0", time(), '/');
		$msg.= "Project deleted. The cookie was deleted too. Default project to open has been reset";
		$rewrite= true;
		break;

	case 'addSettings':
		$i= count($cfg['Servers'])+1;
		$cfg['Servers'][$i]['type']= $_POST['strType'];
		$cfg['Servers'][$i]['host']= $_POST['strHost'];
		$cfg['Servers'][$i]['user']= $_POST['strUser'];
		$cfg['Servers'][$i]['password']= $_POST['strPassword'];
		$cfg['Servers'][$i]['db']= $_POST['strDB'];
		$cfg['Servers'][$i]['project']= $_POST['strProject'];
		$cfg['Servers'][$i]['stub']= $_POST['strStub'];
		$cfg['Servers'][$i]['enabled']= 'true';
		$msg.= "The new project has been added to the end of the list.";
		$rewrite= true;
		break;

	default:
		$rewrite= false;
		break;
}

if ($rewrite) {
	$content = "<?\r\n";
	$content.= "/********************/\r\n";
	$content.= "\$i=0; // DONT TOUCH!\r\n";
	$content.= "/********************/\r\n";
	for ($i=1; $i<count($cfg['Servers'])+1; $i++) {
		$content.= "\r\n\$i++; # i=".$i."\r\n";
		$content.= "\$cfg['Servers'][\$i]['type']= '".$cfg['Servers'][$i]['type']."';\r\n";
		$content.= "\$cfg['Servers'][\$i]['host']= '".$cfg['Servers'][$i]['host']."';\r\n";
		$content.= "\$cfg['Servers'][\$i]['user']= '".$cfg['Servers'][$i]['user']."';\r\n";
		$content.= "\$cfg['Servers'][\$i]['password']= '".$cfg['Servers'][$i]['password']."';\r\n";
		$content.= "\$cfg['Servers'][\$i]['db']= '".$cfg['Servers'][$i]['db']."';\r\n";
		$content.= "\$cfg['Servers'][\$i]['project']= '".$cfg['Servers'][$i]['project']."';\r\n";
		$content.= "\$cfg['Servers'][\$i]['stub']= '".$cfg['Servers'][$i]['stub']."';\r\n";
		$content.= "\$cfg['Servers'][\$i]['enabled']= '".$cfg['Servers'][$i]['enabled']."';\r\n";
	}
	$content.= "?>";
	$tmp= MY_PROJECTS;
	if(!empty($tmp) && !empty($content)){
		$fp = fopen(MY_PROJECTS,"w");
		$b = fwrite($fp,$content);
		fclose($fp);
		//@chmod(MY_PROJECTS, '0777');
		//@chown(MY_PROJECTS, 'nobody');
		if($b != -1){
			$success= TRUE;
		} else {
			$msg.= "Can't write File [no fwrite]";
			$success= FALSE;
		}
	} else {
		$msg.= "Can't write File [no filename | no content]";
		$success= FALSE;
	}
}

isset($_GET['p'])? $p= $_GET['p']: $p='all';

$page= 'Settings';
require("inc/_header.php");
postHeader($page, $msg, $refresh);

print "<p>To disable the settings page. see readme.txt.</p>";
print "<p><a href='$_SERVER[PHP_SELF]?p=add'>[add a project]</a> <a href='$_SERVER[PHP_SELF]?p=all'>[view current projects]</a> <a href='$_SERVER[PHP_SELF]?p=dis'>[view disabled projects]</a></p>";
$q='?p='.$p;

switch ($p) {
	case 'add';
	print "<form onsubmit='javascript:return vValidateProject(this);' name='fAddSetting' method='post' action='$_SERVER[PHP_SELF]?p=all'>";
	print "<input type='hidden' name='action' value='addSettings' />";
	print "<table align='center' cellpadding='2'>";
	print "<tr><th colspan='3'>Add new Project</th></tr>";
	print "<tr>";
	print "<td>project <font color='red'>*</font></td>";
	print "<td><input type='text' name='strProject' value='' /></td>";
	print "<td>What do you want to call this database/stub combination?</td>";
	print "</tr><tr>";
	print "<td>db <font color='red'>*</font></td>";
	print "<td><input type='text' name='strDB' value='' /></td>";
	print "<td>Enter the database name.</td>";
	print "</tr><tr>";
	print "<td>stub</td>";
	print "<td><input type='text' name='strStub' value='' /></td>";
	print "<td>To exclude certain tables, enter a prefix for the included tables (e.g. 'tbl_').</td>";
	print "</tr><tr>";
	print "<td>type <font color='red'>*</font></td>";
	$strTypes= "<select name='strType'>";
	$strTypes.= "<option value=''>";
	for ($j=0; $j<count($supportedDatabases); $j++) {
		$strTypes.= "<option value='".$supportedDatabases[$j]."'>".$supportedDatabases[$j];
	}
	$strTypes.= "</select>";
	print "<td>".$strTypes."</td>";
	print "<td>What kind of database are you connecting to?</td>";
	print "</tr><tr>";
	print "<td>host</td>";
	print "<td><input type='text' name='strHost' value='' /></td>";
	print "<td>What is the host. Required in mysql (e.g. localhost). Optional in access?</td>";
	print "</tr><tr>";
	print "<td>user</td>";
	print "<td><input type='text' name='strUser' value='' /></td>";
	print "<td>Enter the database user name.</td>";
	print "</tr><tr>";
	print "<td>password</td>";
	print "<td><input type='password' name='strPassword' value='' /></td>";
	print "<td>Enter the database password.</td>";
	print "</tr><tr>";
	print "<td colspan='3' align='right'><font color='red'>*</font> required</td>";
	print "</tr>";
	print "</tr><tr>";
	print "<td colspan='3' align='right'><input class='btn' type='submit' value='add' /></td>";
	print "</tr>";
	print "</table>";
	print "</form><br />\r\n";
	break;

	case 'del':
	$i= $_GET['id'];
	print "<form name='f".$i."' method='post' action='$_SERVER[PHP_SELF]?p=".$_GET['history']."'>";
	print "<input type='hidden' name='index' value='".$i."' />";
	print "<input type='hidden' name='action' value='deleteProject' />";
	print "<table width='400' align='center' cellpadding='2'>";
	print "<tr><th colspan='2'>DELETE ".$cfg['Servers'][$i]['project']." (no. ".$i.")?</th></tr>";
	print "<tr>";
	print "<td style='text-align:center;'><input class='btn' type='submit' value='Yes' /></td>";
	print "<td style='text-align:center;'><input class='btn' type='button' value='no' onClick='history.back()' /></td>";
	print "</tr>";
	print "</table>";
	print "</form><br />\r\n";
	break;

	case ('all' || 'dis'):
	for ($i=1; $i<count($cfg['Servers'])+1; $i++) {
		if ( ($cfg['Servers'][$i]['enabled']=='true' && $p=='all') || ($cfg['Servers'][$i]['enabled']=='false' && $p=='dis')) {
			print "<form onsubmit='javascript:return vValidateProject(this);' name='f".$i."' method='post' action='$_SERVER[PHP_SELF]".$q."'>";
			print "<input type='hidden' name='index' value='".$i."' />";
			print "<input type='hidden' name='action' value='updateProject' />";
			print "<table align='center' cellpadding='2'>";
			$i==$db? $current='(current)': $current='';
			print "<tr><th colspan='10'>".$i.". ".$cfg['Servers'][$i]['project']." ".$current."</th></tr>";
			print "<tr>";
			print "<td>project</td>";
			print "<td><input type='text' name='strProject' value='".$cfg['Servers'][$i]['project']."' /></td>";
			print "<td>db</td>";
			print "<td><input type='text' name='strDB' value='".$cfg['Servers'][$i]['db']."' /></td>";
			print "<td>stub</td>";
			print "<td><input type='text' name='strStub' value='".$cfg['Servers'][$i]['stub']."' /></td>";
			print "<td>type</td>";
			$strTypes= "<select name='strType'>";
			for ($j=0; $j<count($supportedDatabases); $j++) {
				$cfg['Servers'][$i]['type'] == $supportedDatabases[$j]? $selected='selected': $selected='';
				$strTypes.= "<option value='".$supportedDatabases[$j]."' ".$selected.">".$supportedDatabases[$j];
			}
			$strTypes.= "</select>";
			print "<td>".$strTypes."</td>";
			print "<td rowspan='2' align='right'><input class='btn' type='submit' name='btnSave' value='save' /></td>";
			print "<td rowspan='2' align='right'><input class='btn' type='button' value='delete' onclick='javascript:deleteProject(".$i.",\"".$p."\");' /></td>";

			print "</tr><tr>";
			print "<td>host</td>";
			print "<td><input type='text' name='strHost' value='".$cfg['Servers'][$i]['host']."' /></td>";
			print "<td>user</td>";
			print "<td><input type='text' name='strUser' value='".$cfg['Servers'][$i]['user']."' /></td>";
			print "<td>password</td>";
			print "<td><input type='password' name='strPassword' value='".$cfg['Servers'][$i]['password']."' /></td>";
			$format = "<input type='checkbox' name='strEnabled' value='true' %s />";
			$cfg['Servers'][$i]['enabled']=='true'? $checked='checked': $checked='';
			$sltEnabled= sprintf($format, $checked);
			print "<td>enabled?</td>";
			print "<td align='center'>".$sltEnabled."</td>";
			print "</tr>";
			print "</table>";
			print "</form><br />\r\n";
		}else{
			print "<p style='text-align:center;'>".$i.". ".$cfg['Servers'][$i]['project'].": enabled= ".$cfg['Servers'][$i]['enabled']."</p>";
		}
	}
	break;
}
require('inc/_footer.php');
?>