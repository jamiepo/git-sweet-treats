<?
class Driver_mysql extends Driver_ {

	function getConnectionSpecific (&$conn, $array) {
		$conn->PConnect($array['host'], $array['user'], $array['password'], $array['db']);
	}

	function alterModifiedColumn(&$conn, $myErdTable) {
		$sql="ALTER TABLE $myErdTable CHANGE modified modified DATETIME DEFAULT NULL";
		$rs = $conn->Execute($sql);
		if (!$rs) {
			print formatError($conn->ErrorMsg(), $conn->ErrorNo(), basename(__FILE__), $sql);
			exit;
		}
	}

	/*
	function createTable($conn, $myErdTable) {
		$sql="
					CREATE TABLE $myErdTable (
			  id int(10) unsigned NOT NULL auto_increment,
			  table1 varchar(255) NOT NULL default '',
			  field_index1 mediumint(8) unsigned default NULL,
			  field_name1 varchar(255) NOT NULL default '',
			  cardinality1 char(1) NOT NULL default '',
			  table2 varchar(255) NOT NULL default '',
			  field_index2 mediumint(8) unsigned default NULL,
			  field_name2 varchar(255) NOT NULL default '',
			  cardinality2 char(1) NOT NULL default '',
			  relationship varchar(255) NOT NULL default '',
			  rec_type enum('R','T','D') NOT NULL default 'R',
			  diagram_id varchar(255) NOT NULL default '0',
			  modified timestamp(14) NOT NULL,
			  PRIMARY KEY  (id)
			)";
		$rs = $conn->Execute($sql);
		if (!$rs) {
			print formatError($conn->ErrorMsg(), $conn->ErrorNo(), basename(__FILE__), $sql);
			exit;
		}
		return $rs;
	}
	*/
}
?>