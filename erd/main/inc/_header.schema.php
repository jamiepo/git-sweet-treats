<?
// set-up dropdowns for setting tables/fields

$myTableFields= array();

$nl="\n"; //$nl='';
$jsDropDowns="// set-up dropdowns for setting tables/fields".$nl;
for ($i=1; $i< 3; $i++) {
	$jsDropDowns.= "var dol_$i = new DynamicOptionList();$nl";
	$jsDropDowns.= "dol_$i.setFormName(\"formRelationships\");$nl";
	$jsDropDowns.= "dol_$i.addDependentFields(\"parent_$i\",\"child_$i\");$nl";
	$previous_field_name='';
	$start='';

	for ($j=0; $j<count($theTables); $j++) {
		$table_name= $theTables[$j];
		$fNames= $conn->MetaColumnNames($table_name,$numericIndex=true);
		$field_result= $conn->MetaColumns($table_name,false);
		$index=0;

		# collect field names for menus
		$myTableFields[$table_name]= array();

		foreach ($fNames as $k => $value) {

			$x= strtoupper($fNames[$k]);
			$field_name= $field_result[$x]->name;
			$myTableFields[$table_name]['values'][$index]= $index.'/'.$field_name;
			$myTableFields[$table_name]['names'][$index]= $field_name;
			//print "<pre>".print_r_log($field_result[$x])."</pre>";

			if ($previous_field_name<>$field_name && $start<>'' && $previous_field_name<>'') {
				$jsDropDowns.= $start.");$nl";
				$start='';
			}
			if ($previous_field_name<>$field_name) {
				$start="dol_$i.forValue(\"$table_name\").addOptionsTextValue(\"$field_name\", \"$index/$field_name\"";
				$buffer='';
			}elseif ($previous_field_name==$field_name) {
				$start.=",\"$table_name\"";
				$buffer='';
			}
			$index++;
			$previous_field_name= $field_name;
		}

		$jsDropDowns.= $start.");$nl";
		$jsDropDowns.= "dol_$i.selectFirstOption = true;$nl";
		$previous_field_name= '';
		$jsDropDowns.= " //check $nl";
	}
}
//exit;
?>
<html>
<head>
<title><?=MY_PROJECT.": ".$page?></title>
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<META HTTP-EQUIV="Expires" CONTENT="Thu, 26-Oct-1972 12:00:00">
<link rel="stylesheet" href="css/style.css">
<link rel="shortcut icon" href="../favicon.ico" type="image/x-icon">
<script language='JavaScript' type='text/javascript' src='js/code.js'></script>
<script language='JavaScript' type='text/javascript' src='js/dynamicOptionList.js'>
	// courtesy of http://www.mattkruse.com/javascript/
</script>
<script language='JavaScript' type='text/javascript'>
<?=$jsDropDowns?>
</script></head><body onLoad='initDynamicOptionLists();'>