<?
/********************/
$i=0; // DONT TOUCH!
/********************/

$i++; # i=1
$cfg['Servers'][$i]['type']     	= 'mysql'; # database type
$cfg['Servers'][$i]['host']     	= 'cecontacts.db.7003830.hostedresource.com'; # hostname or IP address
$cfg['Servers'][$i]['user']     	= 'cecontacts'; # user
$cfg['Servers'][$i]['password']		= 'AssWash0!2'; # password
$cfg['Servers'][$i]['db']       	= 'cecontacts'; # the database name
$cfg['Servers'][$i]['project']  	= 'photomania'; # a unique project name for this configuration
$cfg['Servers'][$i]['stub']  		= 'test_';
			# Only files with this prefix will be included,
			# or set to '' to include all tables
			# (prefixes are removed from webpages for display purposes).
$cfg['Servers'][$i]['enabled']= 'true';
?>