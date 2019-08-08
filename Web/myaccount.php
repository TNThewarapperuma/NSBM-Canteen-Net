<?php
//include files
	require("include/dbcon.inc.php");
	require("include/navbar.inc.php");
	require("include/nfi.inc.php");

session_start();
//login check
	$user_name="";
	$user_type=array();
	if(isset($_SESSION["user_name"])){
		if($_SESSION["user_name"]!=""){
			$user_name=$_SESSION["user_name"];
			$user_type=$_SESSION["user_type"];
		}
		else{
			header("Location:index.php");
		}
	}
	else{
		header("Location:index.php");
	}

//get request
	$request="my-details";

	if(isset($_GET["request"])){
		$request=$_GET["request"];
	}

//all sucess messages
	$message=array("","","");//title, style, message

//change password
	//current password
	$change_password=0;
	if(isset($_POST["btn_now_pw_check"])){
		$now_pw=md5($_POST["txt_member_password"]);
		$sql_quary = "SELECT `user_id` FROM `tbl_user` WHERE `user_username`=:user_name AND `user_password`=:user_password;";
		$sql = $conn->prepare($sql_quary);
		$sql->bindparam(':user_name',$user_name);
		$sql->bindparam(':user_password',$now_pw);
		$sql->execute();
		$numRows = $sql->fetchAll();
		if(count($numRows)==1){
			$change_password=1;
		}
		else{
			$message=array("Wrong!","alert-warning","Password that you entered is not correct.");
		}
	}

	//update to new password
	$password_change_err="";
	$err=0;
	$user_f_name=null;
	$staff_canteen=null;
	$cus_dob=null;
	$cus_std_id=null;
	if(isset($_POST["btn_pw_change"])){
		$new_pw=$_POST["txt_member_new_password"];
		$re_new_pw=$_POST["txt_member_new_password_re"];
		if(strlen($new_pw)<8){
			$password_change_err="Password should be contain at least 8 characters";
			$err=1;
			$change_password=1;
		}
		elseif($new_pw!=$re_new_pw){
			$password_change_err="Password dosn't match!";
			$err=1;
			$change_password=1;
		}
		if($err==0){
			$new_pw=md5($new_pw);
			$sql_quary="UPDATE `tbl_user` SET `user_password`=:user_password WHERE `user_username`=:user_name;";
			$sql = $conn->prepare($sql_quary);
			$sql->bindparam(':user_password',$new_pw);
			$sql->bindparam(':user_name',$user_name);
			$sql->execute();
			$message=array("sucess!","alert-success","Your password sucessfully changed.");
			$request=$navid="my-status";
		}
	}

//my details
	if($request=="my-details"){
		$sql_quary="";
		if($user_type[0]==1){
			$sql_quary = "SELECT * FROM `tbl_user`,`tbl_staff`,`tbl_canteen` WHERE `tbl_user`.`user_id` = `tbl_staff`.`staff_user_id` AND `tbl_canteen`.`can_id`=`tbl_staff`.`staff_canteen` AND `tbl_user`.`user_username`=:user_name;";
		}
		elseif($user_type[0]==0){
			$sql_quary = "SELECT * FROM `tbl_user`,`tbl_customer` WHERE `tbl_user`.`user_id` = `tbl_customer`.`cus_user_id` AND `tbl_user`.`user_username`=:user_name;";
		}
		$sql = $conn->prepare($sql_quary);
		$sql->bindparam(':user_name',$user_name);
		$sql->execute();
		$numRows = $sql->fetchAll();
		if(count($numRows)==1){
			foreach($numRows as $row){
				$user_f_name=$row["user_name"];
				if($user_type[0]==0){
					$cus_std_id=$row["cus_std_id"];
					$cus_dob=$row["cus_dob"];
				}
				elseif($user_type[0]==1){
					$staff_canteen=$row["can_name"];
				}
			}
		}
	}

//delete account
	if(isset($_POST["btn_deactivate"])){
		$user_id=name_from_id('tbl_user', $user_name, 'user_username', 'user_id');
		$sql_quary="DELETE FROM `tbl_user` WHERE `user_username`=:user_name;";
		$sql = $conn->prepare($sql_quary);
		$sql->bindparam(':user_name',$user_name);
		$sql->execute();
		$sql_quary="DELETE FROM `tbl_staff` WHERE `staff_user_id`=:user_id;";
		$sql = $conn->prepare($sql_quary);
		$sql->bindparam(':user_id',$user_id);
		$sql->execute();
		$sql_quary="DELETE FROM `tbl_customer` WHERE `cus_user_id`=:user_id;";
		$sql = $conn->prepare($sql_quary);
		$sql->bindparam(':user_id',$user_id);
		$sql->execute();
		header("Location:index.php");
	}
	?>
<!DOCTYPE html>
<html lang="en">
<head>
  	<title>My Account - Canteen Network</title>
  	<meta charset="utf-8">
  	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/custom_css.css">
	<script src="js/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<style type="text/css">
        .panel-heading, .panel-body{
        	padding: 3px 6px;
        }
	</style>
</head> 
<body>
	<?php navbar($user_name,$user_type,"my_account"); ?>
				<div class="col-sm-10" style="padding: 10px 70px;">
				<div class="row well">
				<?php
					if($message[0]!=""){
						echo "<div class=\"alert $message[1]\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a><strong>$message[0]</strong> $message[2]</div>";
					}
					if($request=="my-details"){
				?>
						<form role="form" action="myaccount.php?request=my-details&navid=my-details" method="post" class="form-horizontal">
							<h3>My Details</h3>
							<table class="table-no-css" style="width: auto;">
								<tr class="tr-no-css">
									<th class="th-no-css">Name:</th>
									<td class="td-no-css"><?php echo $user_f_name; ?></td>
								</tr>
							<?php if($user_type[0]==0){ ?>
								<tr class="tr-no-css">
									<th class="th-no-css">Student ID:</th>
									<td class="td-no-css"><?php echo $cus_std_id; ?></td>
								</tr>
								<tr class="tr-no-css">
									<th class="th-no-css">DOB:</th>
									<td class="td-no-css"><?php echo $cus_dob; ?></td>
								</tr>
							<?php } else{ ?>
								<tr class="tr-no-css">
									<th class="th-no-css">Canteen :</th>
									<td class="td-no-css"><?php echo $staff_canteen; ?></td>
								</tr>
							<?php }?>
							</table>
							<div class="form-group">
								<div class="col-sm-6">
									<a href="myaccount.php?request=my-acc-deactivate" class="btn btn-danger">Delete Account</a>
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-6">
									<a href="myaccount.php?request=my-pw-chg" class="btn btn-info">Change Password</a>
								</div>
							</div>
						</form>
				<?php
					}
					elseif($request=="my-pw-chg"){
				?>
					<form role="form" action="myaccount.php?request=my-pw-chg&navid=my-details" method="post" class="form-horizontal">
						<?php 
							if($change_password==1){
						?>
							<div class="form-group">
								<label class="control-label col-sm-2" for="txt_member_fee">New Password:</label>
								<div class="col-sm-6">
									<input type="password" name="txt_member_new_password" id="txt_member_new_password" class="form-control" maxlength="50" autofocus/>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2" for="txt_member_fee">Re-Enter New Password:</label>
								<div class="col-sm-6">
									<input type="password" name="txt_member_new_password_re" id="txt_member_new_password_re" class="form-control" maxlength="50"/>
									<div class="form-err">
									<?php if($password_change_err!=""){ echo "<br/>".$password_change_err; } ?>
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-offset-2 col-sm-6">
									<input type="submit" id="btn_pw_change" name="btn_pw_change" class="btn btn-success" value="Change Password"/>
									<a href="myaccount.php?request=my-details&navid=my-details" class="btn btn-warning" style="float: right;">Cancel</a>
								</div>
							</div>
						<?php	
							}
							else{
						?>
							<h3>Change the password</h3>
							<p>Before you change the password you must verify your current password</p>
							<div class="form-group">
								<label class="control-label col-sm-2" for="txt_member_fee">Current Password:</label>
								<div class="col-sm-6">
									<input type="password" name="txt_member_password" id="txt_member_password" class="form-control" maxlength="50" autofocus/>
								</div>
								<div class="col-sm-3">
									<input type="submit" id="btn_now_pw_check" name="btn_now_pw_check" class="btn my-btn" value="Continue"/>
									&nbsp;
									<a href="myaccount.php?request=my-details&navid=my-details" class="btn btn-warning">Cancel</a>
								</div>
							</div>
						<?php		
							}
						?>
					</form>
				<?php
					}
					elseif($request=="my-acc-deactivate"){
				?>
					<form role="form" action="myaccount.php?request=my-acc-deactivate&navid=my-details" method="post" class="form-horizontal">
						<h3>Are you sure?</h3>
						<p>If you Delete your account every account details will be removed.</p>
						<div class="form-group">
							<div class="col-sm-4">
							    <input type="submit" id="btn_deactivate" name="btn_deactivate" class="btn btn-danger" value="Understand & Wish to continue"/>
							    <a href="myaccount.php?request=my-details&navid=my-details" class="btn btn-warning" style="float: right;">NotNow</a>
							</div>
						</div>
					</form>
				<?php
					}
					else{
						header("Location:index.php");
					}
				?>
			</div>
		</div>
		</div>
	</div>
	<?php
		include("include/fotter.inc.php");
	?>
</body>
</html>