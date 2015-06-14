<?
 /************************************************
	ABOUT
 ************************************************/
define('MY_WEBSITE', 'www.uvukov.org');
define('MY_PROJECT', 'E.R.D.iagrammer');
define('MY_VERSION', 'v2.0');

 /************************************************
	SETTINGS
	if you install this application on-line, you will suely keep it in a password-protected directory.
	if you're happy to send database passwords over an open network, set to true.
	otherwise, set to false, and the settings can be configured manually in a text editor.
 ************************************************/
define('ENABLE_SETTINGS', 'false');

/************************************************
	PATHS
 ************************************************/
define('ADODB', '../../adodb'); # the location of the ADOBD library.
define('CLASSES', './classes'); # the location of my classes.
define('MY_DIAGRAM', './diagrams/blank.png');	# the location of my diagram. Make sure it's WRITABLE.
define('MY_PROJECTS', '../_db.php');	# location of project settings file

/************************************************
 ERD TABLE NAME
  For every database, a table is created to hold the E.R.D. data.
 	This table is auto-generated.
	Make sure it has a unique name. Avoid upper-case.
 ************************************************/
define('ERD_TABLE', '_erd_');

/************************************************
	SUPPORTED DATABASES
 ************************************************/
$supportedDatabases = Array('mysql', 'access');

/************************************************
	NAV BAR
 ************************************************/
$upDirectoryLink = 'Uvukov-Local'; # this link takes one up 2 directories.

/************************************************
	CANVAS
 ************************************************/
$width= 	600;								# Default canvas width for new diagrams.
$height= 	415; 								# Default canvas height for new diagrams.
$connectingPhrase='has'; 			# The verb nature of the relationships?!@#?.
$myCardinalities = Array('1','N','0'); # Any characters you want. The first is the default.

/************************************************
	DEFAULT FONT COLOURS
 ************************************************/
$colours = Array();
$colours['cnvs']=		'FFFFFF';	// the canvas
$colours['tbbg']=		'FFFFFF';	// db table backgrounds
$colours['brdrs']=	'000000';	// db table borders
$colours['txt']=		'000000'; // db table text and cardinalities
$colours['lns']= 		'CCCCCC'; // db table connecting lines
$colours['info']=		'999999'; // header and footer info


/************************************************
 SIZE SCHEMES
 	Font sizes are restriced to 1, 2, 3, 4 or 5 for built-in fonts.
 	See php function imagestring(), or include your own fonts.

	tableText -> font size for the fields in a table
 	header		-> font size for a table header, and the cardinalities
 	rh 				-> the height of each row in the table, (tempered by font sizes)
 	tWidth 		-> width of the tables. Long table/fieldNames will spill out
 	$handle		-> length of handle connecting tables to dotted relationship lines.
 ************************************************/
$ss= Array();
$ss[0]= Array('tableText'=>1, 'header'=>1, 'rh'=>10, 'tWidth'=>65, 'handle'=>11);
$ss[1]= Array('tableText'=>2, 'header'=>3, 'rh'=>14, 'tWidth'=>90, 'handle'=>11);
$ss[2]= Array('tableText'=>4, 'header'=>5, 'rh'=>16, 'tWidth'=>100, 'handle'=>11);
$ss[3]= Array('tableText'=>5, 'header'=>5, 'rh'=>16, 'tWidth'=>110, 'handle'=>11);

$s=1; 	// default SIZE SCHEMES. 0 to 3 (determined by size of $ss Array)
?>