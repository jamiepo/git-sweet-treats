E.R.D.iagrammer
---------------
version: 2.0
date:    September 2005
author:  david t watson
email:   info AT uvukov DOT org

The latest version can be found at http://www.uvukov.org.
This software is still highly beta, and only a few databases are supported.
If you are willing and able to test another, please let me know.
This program has been tested on PHP 4.3.8, GD version 2.0.1
This program is Copyright (c) 2005 David Watson and can be distributed under the GPL.


INSTALLATION
------------
1. Move this folder and its contents to your website.

2. If this is on-line, you will probably want to password protect this application.
	(especially if define('ENABLE_SETTINGS', 'false'); is set to true.

3. You will need ADOdb. Download it from http://sourceforge.net/projects/adodb/ and move it to your website.

4. Open '_config.php' in a text editor.

5. Set ADODB to the correct path.

6. Set ERD_TABLE. For each database, a new table is created, named ERD_TABLE. 
	Make sure the name won't clash with any current table in your database.

7. Set ENABLE_SETTINGS. 
	if you're happy to send database passwords over an open network, 
	and the application is in a password-protected directory, set to true, otherwise set to false.

8. if ENABLE_SETTINGS== false
	A- Open '_db.php' in a text editor and enter your settings:
	$cfg['Servers'][$i]['type']     	= 'mysql'; # database type
	$cfg['Servers'][$i]['host']     	= 'localhost'; # hostname or IP address
	$cfg['Servers'][$i]['user']     	= 'myname'; # user									$cfg['Servers'][$i]['password']		= 'mypassword'; # password
	$cfg['Servers'][$i]['db']       	= 'mydatabase'; # the database name
	$cfg['Servers'][$i]['project']  	= 'monkeyboy'; # a unique project name for this configuration
	$cfg['Servers'][$i]['stub']  		= 'test_'; 
			# Only files with this prefix will be included,
			# or set to '' to include all tables
			# (prefixes are removed from webpages for display purposes).
	$cfg['Servers'][$i]['enabled']= 'true';

9.Point your browser to the 'main' folder.



EXTENDING THE APPLICATION
-------------------------
In _config.php, add your databes to the $supportedDatabases array.
Add you class to the 'classes' directory - call it 'class.db+DBTYPE+.php'.

DATABASE TABLES
---------------
One extra table is created in each database.
To avoid creating more than one table, the column names do not necessarily correspond to their functionality.
This table holds the records for each project.
Each project has up to 3 types of record, determined by the rec_type column.
field: rec_type, values: D,T,R
	D - Data. Holds the formatting data for the diagram.
	T - Tables. Defines a table, and it's co-ordinates in the diagram.
	R - Relationships. Defines a relationship between 2 tables (2 records of type 'T').



MSACCESS NOTES
--------------
you may have touble connecting to MSAccess.
see http://forums.belution.com/en/sql/000/015/18s.shtml



THANKS
------
PHP Draw 0.2
------------
The inspiration for this project.
http://xpenguin.com/phpdraw.php

Matt Kruse
----------
http://www.mattkruse.com/javascript/
In schema.php the dynamic dropdowns are enabled by Matt's dynamicOptionList.js file.

Flooble
-------
http://www.flooble.com/
flooble.js: A pop-up color picker.