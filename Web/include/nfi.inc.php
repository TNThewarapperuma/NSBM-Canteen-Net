<?php
	//nfi - Name From ID
	//that class use to get name from id
		
	function name_from_id($nfi_table, $nfi_id, $nfi_id_col, $nfi_name_col){
		global $conn;
		$sql_quary = "SELECT * FROM ".$nfi_table." WHERE ".$nfi_id_col."='".$nfi_id."';";
		$sql = $conn->prepare($sql_quary);
		$sql->execute();
			
		$numRows = $sql->fetchAll();
			if(count($numRows)>0){
				foreach($numRows as $row){
					return $row[$nfi_name_col];
				}
			}
	}
?>