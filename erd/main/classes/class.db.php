<?
class Driver_ {

	function getConnectionSpecific (&$conn, $array) { // abstract
		//
	}

	function getConnection (&$conn, $array) {
		$this->getConnectionSpecific ($conn, $array);
		if (!$conn->IsConnected()) {
			print "No Connection. Make sure your database server is running, and check the settings.";
			print "<br />type: &quot;".$array['type']."&quot;";
			print "<br />host: &quot;".$array['host']."&quot;";
			print "<br />user: &quot;".$array['user']."&quot;";
			print "<br />db: &quot;".$array['db']."&quot;";
			print "<br /><a href='settings.php?p=all'>go to settings &gt;&gt;&gt;</a>";
			print "<br /><br />[File= &quot;".__FILE__."&quot;]";
			exit;
		}
	}

	function alterModifiedColumn($conn, $myErdTable) { // abstract
		/***************************************************
		 in the CreateTableSQL function in ADODB,
		 the 'T' data type creates a date/time in access,
		 but a timestamp in mysql. this function therefore
		 alters the datatype to a date/time where necessary.
		 ***************************************************/
	}

	/*
	function createTable($myErdTable) {
		// no longr required, so far...
	}
	*/
}

?>