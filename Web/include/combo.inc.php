<?php
//sql_query, combo name, combo_value_colomn name, combo text column name, combo css class, combo selected 
function create_combo($c_sql,$c_name,$c_value,$c_text,$c_style,$c_selected){
	global $conn;
	echo "<select name=\"$c_name\" id=\"$c_name\" class=\"$c_style\" required=\"true\">";
	echo "<option></option>";
	$sql = $conn->prepare($c_sql);
	$sql->execute();			
	$numRows = $sql->fetchAll();
	if(count($numRows)>0){
		foreach($numRows as $row){
			if($row[$c_value]==$c_selected){
				echo "<option selected value=".$row[$c_value].">".$row[$c_text]."</option>";
			}
			else{
				echo "<option value=".$row[$c_value].">".$row[$c_text]."</option>";
			}
		}
	}
	echo"</select>";
}


//===================not required combo=======================
function create_combo_noreq($c_sql,$c_name,$c_value,$c_text,$c_style,$c_selected){
	global $conn;
	echo "<select name=\"$c_name\" id=\"$c_name\" class=\"$c_style\">";
	echo "<option></option>";
	$sql = $conn->prepare($c_sql);
	$sql->execute();			
	$numRows = $sql->fetchAll();
	if(count($numRows)>0){
		foreach($numRows as $row){
			if($row[$c_value]==$c_selected){
				echo "<option selected value=".$row[$c_value].">".$row[$c_text]."</option>";
			}
			else{
				echo "<option value=".$row[$c_value].">".$row[$c_text]."</option>";
			}
		}
	}
	echo"</select>";
}

//sql_query, combo name, combo_value_colomn name, combo text column name, combo css class, combo selected, other tags 
function create_combo_custom($c_sql,$c_name,$c_value,$c_text,$c_style,$c_selected,$other_tags){
	global $conn;
	echo "<select name=\"$c_name\" id=\"$c_name\" class=\"$c_style\" $other_tags>";
	echo "<option></option>";
	$sql = $conn->prepare($c_sql);
	$sql->execute();			
	$numRows = $sql->fetchAll();
	if(count($numRows)>0){
		foreach($numRows as $row){
			if($row[$c_value]==$c_selected){
				echo "<option selected value=".$row[$c_value].">".$row[$c_text]."</option>";
			}
			else{
				echo "<option value=".$row[$c_value].">".$row[$c_text]."</option>";
			}
		}
	}
	echo"</select>";
}
?>