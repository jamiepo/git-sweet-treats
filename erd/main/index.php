<?php
require("inc/_init.php");
$page= "Home";
require("inc/_header.php");
postHeader($page, $msg, $refresh);
?>
<div style='text-align:left;'>
	<h3><?=MY_PROJECT.": ".MY_VERSION?></h3>
	<p>A PHP application to generate Entity Relationship Diagrams for any database supported by <a href='http://sourceforge.net/projects/adodb/' target='_blank'>ADOdb</a>.
	Well, in theory, at least. This application currently only supports a subset of those supported by <a href='http://sourceforge.net/projects/adodb/' target='_blank'>ADOdb</a>: <? print array_to_string($supportedDatabases);?>.
	<blockquote>&quot;<a href='http://sourceforge.net/projects/adodb/' target='_blank'>ADOdb</a> is a PHP and Python database class library to hide the differences between the different databases so you can easily switch databases without changing the code.&quot;</blockquote>
	What's it for again? Building larger applications with more than a few database tables means you need a clear idea of how these tables are related to each other.
	Whilst there does exist software for building ERDs (and even for hooking directly into the database), this is not always so.
	<i><?=MY_PROJECT?></i> allows one to maintain an on-line representation of the database that can be shared amongst other developers. At the same time, whenever the structure of the database changes <i><?=MY_PROJECT?></i> will let you know where exactly you need to alter the ERD.
	</p>
	<p>See a working demonstration <a href='erd.php'>here</a>.</p>
	<p>Download it from <a href='http://sourceforge.net/projects/erdiagrammer/'>Sourceforge.net</a>.</p>
	<h5>Features</h5>
	<ul>
		<li>Driven by <a href='http://sourceforge.net/projects/adodb/' target='_blank'>ADOdb</a>.</li>
		<li>Supports multiple servers and databases.</li>
		<li>For every database, create multiple projects (defined by the table stub), and for every project create multiple diagrams.</li>
		<li>As tables are added, removed amended, <i><?=MY_PROJECT?></i> will let you know where to fix the diagram.</li>
		<li>Define, by the table prefixes, which tables are visible, and which are hidden.</li>
		<li>Customise colours for tables, canvas and text.</li>
		<li>Define your own characters to represent cardinalities.</li>
	</ul>
	<h5>Requirements</h5>
	<ul>
		<li>PHP (+ GD library), a supported database, a Javascript/Cookie enabled browser.</li>
	</ul>
	<h5>Tested</h5>
	<ul>
		<li>PHP 4.3.8.</li>
		<li>Mozilla Firefox 1.0 and Internet Explorer 6.</li>
		<li><? print array_to_string($supportedDatabases);?></li>
	</ul>
	<h5>Bugs</h5>
	<ul>
		<li>If more than 2 colours are used and more than about a dozen tables are showing, sometimes one or two tables are painted out. No idea why. Refreshing the page will fix it. Keep refreshing and it switches between the good and bad diagram. Explanation please?</li>
	</ul>
	<h5>To do</h5>
	<ul>
		<li>Add more database support. If you have a database you'd like plugged in, please let me know.</li>
	</ul>
</div>
<?
require('inc/_footer.php');
?>