<?php
//include files
	require("include/dbcon.inc.php");
session_start();
//logout
	session_unset(); 

//login
	$txt_username=null;
	$txt_password=null;
	$login_err=null;

	if(isset($_POST['btn_login'])){
			$txt_username=$_POST["txt_username"];
			$txt_password=md5($_POST["txt_password"]);
			$sql_quary = "SELECT * FROM tbl_user WHERE user_username=:txt_username AND user_password=:txt_password;";
			$sql = $conn->prepare($sql_quary);
			$sql->bindparam(':txt_username',$txt_username);
			$sql->bindparam(':txt_password',$txt_password);
			$sql->execute();
			$numRows = $sql->fetchAll();
			if(count($numRows)==1){
				foreach($numRows as $row){
					if($row['user_active']=="1"){
						if($row["user_type"]=="0"){
							$_SESSION["user_name"]=$row['user_username'];
							$_SESSION["user_type"]=0;
							header("Location:home.php");
						}
						elseif($row["user_type"]=="1"){
							$_SESSION["user_name"]=$row['user_username'];
							$_SESSION["user_type"]=array($row['user_type']);
							$sql_quary = "SELECT * FROM tbl_staff WHERE staff_user_id=:user_id";
							$sql = $conn->prepare($sql_quary);
							$sql->bindparam(':user_id',$row["user_id"]);
							$sql->execute();
							$numRows = $sql->fetchAll();
							if(count($numRows)==1){
								foreach($numRows as $row){
									$_SESSION["staff_canteen"][1]=$row['staff_canteen'];
								}
							}
							header("Location:home.php");
						}
					}
					else{
						$login_err="Can't access to this account right now. Please report to Admin.";
					}
				}
			}
			else{
				$login_err="Username or password not correct! Please Retry.";
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
<body>
	<form role="form" action="index.php" method="post" class="form-horizontal">
		<div class="container">
			<div class="well" style="padding: 0px !important;">
				<img src="images/cover.jpg" style="width: 100%; border-radius: 10px 10px 0px 0px; padding-bottom: 15px;">
				<?php
					if(isset($_GET["error_message"])){
						echo "<div class=\"alert alert-danger\" style=\"margin:0px 10px;margin-bottom:7px;\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a><strong>Oops! </strong>Someting wrong in the progress. Please retry or inform to your admin.</div>";
					}
				?>
				<div class="form-group">
					<label class="control-label col-sm-3" for="txt_username">Username:</label>
				    <div class="col-sm-9">
				      	<input type="text" name="txt_username" id="txt_username" class="form-control" required="true" maxlength="150" autofocus/>
				    </div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" for="txt_password">Password:</label>
				    <div class="col-sm-9">
				      	<input type="password" name="txt_password" id="txt_password" class="form-control" required="true" maxlength="50"/>
				    </div>
				</div>
				<div class="form-group">
					<div class="col-sm-12" style="text-align: right;">
						<?php if($login_err!=null){echo "<font class=\"form-err\">".$login_err."</font><br/>";} ?>
					</div>
				</div>
				<div class="form-group"> 
				    <div class="col-sm-offset-3 col-sm-9">
				    	<button type="submit" name="btn_login" id="btn_login" class="btn btn-success" style="margin: 0px;">Login <span class="glyphicon glyphicon-log-in"></span></button>
				    	<a href="create_account.php" class="btn btn-warning">Create Account <span class="glyphicon glyphicon-user"></span></a>
				    	<a href="about.php" class="btn btn-info" style="float: right;">About Canteen Network <span class="glyphicon glyphicon-triangle-right"></span></a>
				    </div>
				    <div class="col-sm-5" style="text-align: right;"></div>
				</div>
			</div>
		</div>
	</form>
</body>
</html>