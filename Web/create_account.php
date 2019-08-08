<?php
//include files
	require("include/dbcon.inc.php");
	require("include/combo.inc.php");
	require("include/validation.inc.php");
	$txt_user_type="";
	$txt_user_fname="";
	$txt_cus_dob="";
	$txt_cus_std_id="";
	$ddl_staff_canteen="";
	$ddl_user_type="";

	$txt_username_err="";
	$txt_cus_std_id_err="";
	$err=0;
//add new member and update members
	if(isset($_POST["btn_submit"])){
		//get data
		$ddl_user_type=$_POST["ddl_user_type"];
		$txt_user_fname=$_POST["txt_user_fname"];
		$txt_username=$_POST["txt_username"];
		$txt_password=$_POST["txt_password"];
		$txt_password_re=$_POST["txt_password_re"];
		$ddl_staff_canteen=$_POST["ddl_staff_canteen"];
		$txt_cus_std_id=$_POST["txt_cus_std_id"];
		$txt_cus_dob=$_POST["txt_cus_dob"];
		$err=0;

		//check whether username used @ sign
		if(strpos($txt_username, "@")==true){
    		$txt_username_err="You Can't use @ for your username";
			$txt_username="";
			$err=1;
		}

			//check whether username alrady exists
			$sql_quary = "SELECT * FROM `tbl_user` WHERE `user_username`=:user_name;";
			$query_params=array(":user_name"=>$txt_username);
			if(val_duplicate($sql_quary,$query_params)==1){
				$txt_username_err="Username already exists. Please try another usename.";
				$txt_username="";
				$err=1;
			}

			//check wherher student id adredy exists
			$sql_quary = "SELECT * FROM `tbl_customer` WHERE `cus_std_id`=:cus_std_id;";
			$query_params=array(":cus_std_id"=>$txt_cus_std_id);
			if(val_duplicate($sql_quary,$query_params)==1){
				$txt_cus_std_id_err="Customer Student ID already exists. Please try another id.";
				$txt_cus_std_id="";
				$err=1;
			}

		if($err==0){
			$txt_password=md5($txt_password);
			$stt_user_active="0";
			$sql_quary="INSERT INTO `tbl_user` (`user_name`,`user_username`, `user_password`, `user_type`, `user_active`) VALUES (:user_name,:user_username,:user_password,:user_type,:user_active);";
			$sql = $conn->prepare($sql_quary);
			$sql->bindparam(":user_name",$txt_user_fname);
			$sql->bindparam(":user_username",$txt_username);
			$sql->bindparam(":user_password",$txt_password);
			$sql->bindparam(":user_type",$ddl_user_type);
			$sql->bindparam(":user_active",$stt_user_active);
			$sql->execute();

			if($ddl_user_type=="0"){
				$sql_quary="INSERT INTO `tbl_customer` (`cus_user_id`, `cus_std_id`, `cus_dob`) VALUES (LAST_INSERT_ID(),:cus_std_id,:cus_dob);";
				$sql = $conn->prepare($sql_quary);
				$sql->bindparam(":cus_std_id",$txt_cus_std_id);
				$sql->bindparam(":cus_dob",$txt_cus_dob);
				$sql->execute();
			}
			else{
				$sql_quary="INSERT INTO `tbl_staff` (`staff_canteen`, `staff_user_id`) VALUES (:staff_canteen,LAST_INSERT_ID());";
				$sql = $conn->prepare($sql_quary);
				$sql->bindparam(":staff_canteen",$ddl_staff_canteen);
				$sql->execute();
			}

			//success message
			$message=array("Success!","alert-success","Request Successfully Placed.");
			$request="category-check";
			header("Location:index.php");
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  	<title>Welcome - Canteen Network</title>
  	<meta charset="utf-8">
  	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/custom_css.css">
	<script src="js/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
</head>
<body onload="select_user_type_js()">
	<form role="form" action="create_account.php" method="post" class="form-horizontal">
		<div class="container">
				<div class="form-group">
					<div class="col-sm-12" style="text-align: center;">
						<h3 id="a"><u>Request Membership Of Canteen Network</u></h3>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" for="ddl_user_type">User Type:</label>
					<div class="col-sm-9">
				      	<?php 
				      	$c_sql="SELECT * FROM tbl_user_type;";
				      	create_combo_custom($c_sql,"ddl_user_type","user_type_id","user_type_name","form-control",$ddl_user_type,"onchange=' select_user_type_js()'"); 
				      	?>
				    </div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" for="txt_user_fname">Full Name:</label>
				    <div class="col-sm-9">
				      	<input type="text" name="txt_user_fname" id="txt_user_fname" class="form-control" required="true" maxlength="150"/>
				    </div>
				</div>
				<div class="form-group" id="cus_1">
					<label class="control-label col-sm-3" for="txt_cus_std_id">Student ID:</label>
				    <div class="col-sm-9">
				      	<input type="text" name="txt_cus_std_id" id="txt_cus_std_id" class="form-control" maxlength="150"/>
				    </div>
				</div>
				<div class="form-group" id="cus_2">
					<label class="control-label col-sm-3" for="txt_cus_dob">DOB:</label>
				    <div class="col-sm-9">
				      	<input type="date" name="txt_cus_dob" id="txt_cus_dob" class="form-control"/>
				    </div>
				</div>
				<div class="form-group" id="staff_1">
					<label class="control-label col-sm-3" for="ddl_staff_canteen">Canteen you work:</label>
					<div class="col-sm-9">
				      	<?php 
				      	$c_sql="SELECT * FROM tbl_canteen;";
				      	create_combo_noreq($c_sql,"ddl_staff_canteen","can_id","can_name","form-control",$ddl_staff_canteen); 
				      	?>
				    </div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" for="txt_username">Username:</label>
				    <div class="col-sm-9">
				      	<input type="text" name="txt_username" id="txt_username" class="form-control" required="true"/>
				    </div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" for="txt_password">Password:</label>
				    <div class="col-sm-9">
				      	<input type="password" name="txt_password" id="txt_password" class="form-control" required="true" maxlength="50"/>
				    </div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" for="txt_password_re">Re-enter Password:</label>
				    <div class="col-sm-9">
				      	<input type="password" name="txt_password_re" id="txt_password_re" class="form-control" required="true" maxlength="50"/>
				    </div>
				</div>
				<div class="form-group"> 
				    <div class="col-sm-offset-3 col-sm-9">
				    	<button type="submit" name="btn_submit" id="btn_submit" class="btn btn-success" style="margin: 0px;"><span class="glyphicon glyphicon-user"></span> Request For Account</button>
				    	<a href="index.php" class="btn btn-success" style="float: right;">Login <span class="glyphicon glyphicon-log-in"></span></a>
				    </div>
				    <div class="col-sm-5" style="text-align: right;"></div>
				</div>
			</div>
		</div>
	</form>
</body>
</html>
<script type="text/javascript">
function select_user_type_js() {
	var e = document.getElementById("ddl_user_type");
	var str_user_type = e.options[e.selectedIndex].value;
  	var staff_1 = document.getElementById("staff_1");
  	var cus_1 = document.getElementById("cus_1");
  	var cus_2 = document.getElementById("cus_2");
	if (str_user_type === "1") {
	  staff_1.style.display = "block";
	  cus_1.style.display = "none";
	  cus_2.style.display = "none";
	} else if(str_user_type === "0") {
	  staff_1.style.display = "none";
	  cus_1.style.display = "block";
	  cus_2.style.display = "block";
	} else {
		staff_1.style.display = "none";
	  	cus_1.style.display = "none";
	  	cus_2.style.display = "none";
	}
}
</script>