<?
class Driver_access extends Driver_ {

	function getConnectionSpecific (&$conn, $array) {
		$conn->PConnect($array['db']);
	}

	function alterModifiedColumn($conn, $myErdTable) {
		//
	}

	/*
	function createTable($conn, $myErdTable) {
		$sql="
					CREATE TABLE $myErdTable (
					  id AUTOINCREMENT ,
					  table1 text(255),
					  field_index1 integer,
					  field_name1 text(255),
					  cardinality1 text(1),
					  table2 text(255),
					  field_index2 integer,
					  field_name2 text(255),
					  cardinality2 text(1),
					  relationship text(255),
						rec_type text(1),
					  diagram_id text(255),
					  modified date,
					  PRIMARY KEY(id)
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