<?php
function upload_logo($file_upload_input_name){
	$target_dir="images/";
	$target_file = $target_dir.basename($_FILES[$file_upload_input_name]["name"]);
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
	$error="";
	//echo $file_upload_input_name;

	$check="";
	echo $tmp_nm;
	
	if($imageFileType!="png"){
	    $error="Sorry, .png files are allowed.";
	}
    elseif($check==false){
        $error="File is not an image-".$check["mime"];
    }
	elseif($_FILES[$file_upload_input_name]["size"]>50000){
	    $error="Sorry, your file is too large.";
	}

	if($error!=""){
		return $error;
	}
	else{
		$upload_new_info = $target_dir."logo.".$imageFileType;
		if(move_uploaded_file($_FILES[$file_upload_input_name]["tmp_name"], $upload_new_info)){
			return true;
	    } 
	    else{
	        return "Sorry, Something wrong with uploading your file.";
	    }
	}
}
?>