<?php
function val_duplicate($sql_quary,$query_params){
	global $conn;
	$ret_val=0;
	$sql = $conn->prepare($sql_quary);
	foreach($query_params as $key => &$val) {
    	$sql->bindparam($key,$val);
	}
	$sql->execute();
	$numRows = $sql->fetchAll();
	if(count($numRows)>0){
		$ret_val=1;
	}
	return $ret_val;
}

?>