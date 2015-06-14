<?
/****************************************************************
 * N.B. this switch affects the ADOBD debug switch ($conn->debug) below
 * set to 1/0 to switch on/off
 ****************************************************************/
define('DEBUG',0);

require_once("../_config.php");
require_once(ADODB.'/adodb.inc.php');	   # load code common to ADOdb
require_once(ADODB.'/_db.php');
require_once("./inc/functions.php");
require_once(MY_PROJECTS);

Header("Pragma: no-cache");
Header("Expires: Thu, 26-Oct-1972 12:00:00");

$msg=			'';
$refresh= false;				# occasionally attach a querystring to url

isset($_GET['action'])? $action= $_GET['action']: $action='';
if (isset($_POST['action'])) $action= $_POST['action'];

# $db : the index of the project. note; each project may have many diagrams.
# $id : the name of the diagram.
if (isset($_COOKIE['ERD'])) {
	list($id, $db) = split(":", $_COOKIE["ERD"], 2);
}else{
	# first time here? throw the switch a dummy.
	# behave as if the user has picked first db from dropdown.
	$action= 'switchDB';
	$_POST['newDatabase']= 1;
}

switch ($action) {
	case 'switchDB':
	$db= $_POST['newDatabase']; // refresh db connection and object table
	break;
}

# create a connection
$conn = &ADONewConnection($cfg['Servers'][$db]['type']);

/***************************************
 * WARNING: Because of ADODB debug facility (i think), switching on debug will make it impossible to set cookies.
 * Default the project to the first one.
 ***************************************/
if (DEBUG == 1) {
	$db= 1;	# default to first project
	$conn->Close();
	$conn = &ADONewConnection($cfg['Servers'][$db]['type']);
	$conn->debug= true;
}


$dbName= $cfg['Servers'][$db]['db'];

# Load a database Driver
# so far, we don't need any
include('./classes/class.factory.php');
$myDriver = Factory::makeDriver($cfg['Servers'][$db]['type'], CLASSES);

/*********************************
 * connect to the db.
 *********************************/
$myDriver->getConnection ($conn, $cfg['Servers'][$db]);

/**************************************************
 * - generate project names if necessaary.
 * - generate project dropdown
 **************************************************/
$databases= "<select onchange='document.formSwitch.submit()' name='newDatabase' style='width:130px;'>";
for ($i=1; $i<=count($cfg['Servers']); $i++) {
	if ($cfg['Servers'][$i]['enabled']== 'true') {
		$cfg['Servers'][$i]['project']= str_replace(" ", "", $cfg['Servers'][$i]['project']);
		if ($cfg['Servers'][$i]['project']=='')
			$cfg['Servers'][$i]['project']=$cfg['Servers'][$i]['db'].'-'.$cfg['Servers'][$i]['stub'];
		$db==$i? $selected='selected': $selected='';
		$databases.="<option value='$i' $selected>".$cfg['Servers'][$i]['project'];
	}
}
$databases.= "</select>";

/**************************************************
 * generate the tables array for drop-down menus.
 **************************************************/
$excluded= array();;
$stub=$cfg['Servers'][$db]['stub'];
$stubLength= strlen($stub);
$tablesAllDD= "<option value=''>"; // for the schema only

$theEntireTables= $conn->MetaTables('TABLES');
/************************
 remove ~tables in msaccess
 ***************************/
foreach ($theEntireTables as $i => $value) {
	if (isset($theEntireTables[$i])) {
		if (substr($theEntireTables[$i],0,1)=='~') {
			$theEntireTables= my_array_delete($theEntireTables, $i);
		}
	}
}


for ($i=0; $i< count($theEntireTables); $i++) {
	$tShort= substr($theEntireTables[$i],0,$stubLength);
	if (substr($theEntireTables[$i],0,$stubLength)== $stub && $theEntireTables[$i]<> ERD_TABLE) {
		$theTables[]= $theEntireTables[$i];
		$tablesAllDD.= 	"<option value='".$theEntireTables[$i]."'>".substr($theEntireTables[$i], $stubLength);$tShort;
	}else{
		$excluded[]= $theEntireTables[$i];
	}
}
$attributes= "$width:$height:$colours[cnvs]:$colours[tbbg]:$colours[brdrs]:$colours[lns]:$colours[txt]:$s"; // default, just in case

$erd_table= ERD_TABLE; # cos i cant pass ERD_TABLE in something like $sql = $conn->GetInsertSQL(ERD_TABLE, $record);
?>