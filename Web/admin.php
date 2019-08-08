<?php

//include files
	require("include/dbcon.inc.php");
	require("include/navbar.inc.php");
	//include("include/error.inc.php");
	require("include/validation.inc.php");
	//require("include/upload_logo.inc.php");
	//require("include/db_user.inc.php");
	require("include/combo.inc.php");

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

//authorize check
	$user_canteen=null;
	if($user_type[0]==1){
		$sql_quary="SELECT staff_canteen FROM tbl_user,tbl_staff WHERE tbl_user.user_id=tbl_staff.staff_user_id AND tbl_user.user_username=:user_username;";
		$sql = $conn->prepare($sql_quary);
		$sql->bindparam(":user_username",$user_name);
		$sql->execute();
		$numRow = $sql->fetchAll();
		if(count($numRow)==1){
			foreach ($numRow as $row){
				$user_canteen=$row["staff_canteen"];
			}
		}
	}
	else{
		header("Location:index.php");
	}


//get request
	$request="welcome";
	$navid="welcome";
	if(isset($_GET["request"])){
		$request=$_GET["request"];
	}
	if(isset($_GET["navid"])){
		$navid=$_GET["navid"];
	}


//all messages
	$message=array("","","");//title, style, message

//variables for all
		$err=0;


//food category
	//food category variables
	$txt_category_id="";
	$txt_category_name="";
	$txt_category_id_err="";

	//get category id from address bar
	if(isset($_GET["category_id"])){
		if($_GET["category_id"]!=""){
			$category_id=$_GET["category_id"];
		}
		else{
			header("Location:index.php");
		}
	}

	//delete member type
	if(isset($_POST["btn_category_del"])){
		//get sn from post
		$category_id=$_POST["hid_category_id"];

		//delete category
		$sql_quary = "DELETE FROM `tbl_food_type` WHERE `food_type_id`=:category_id;";
		$sql = $conn->prepare($sql_quary);
		$sql->bindparam(":category_id",$category_id);
		$sql->execute();

		//success message
		$message=array("Success!","alert-success","Food Category Successfully Removed.");
	}

	//add material category
	if(isset($_POST["btn_category_add"])){
		//get data from post
		//$txt_category_id=$_POST["txt_category_id"];
		$txt_category_name=$_POST["txt_category_name"];
		//$txt_category_description=$_POST["txt_category_description"];
		

		//if all vaildatons corrct inset data
		if($err==0){
			$sql_quary="INSERT INTO `tbl_food_type` (`food_type_id`,`food_type_name`) VALUES (NULL,:category_name);";
			$sql = $conn->prepare($sql_quary);
			$sql->bindparam(":category_name",$txt_category_name);
			$sql->execute();

			//success message
			$message=array("Success!","alert-success","Food Category Successfully Inserted.");
			$request="category-check";
		}
	}

	//edit material category
	if(isset($_POST["btn_category_edit"])){
		//get data from post
		$txt_category_name=$_POST["txt_category_name"];
		$category_id=$_POST["hid_category_id"];

		//update data
		$sql_quary="UPDATE `tbl_food_type` SET `food_type_name`=:category_name WHERE `food_type_id`=:category_id;";
		$sql = $conn->prepare($sql_quary);
		$sql->bindparam(":category_name",$txt_category_name);
		$sql->bindparam(":category_id",$category_id);
		$sql->execute();

		//success message
		$message=array("Success!","alert-success","Food Category Successfully Updated.");
		$request="category-check";
	}

	//prapeire for edit material category
	if($request=="category-edit"){
		$sql_quary="SELECT * FROM `tbl_food_type` WHERE `food_type_id`=:category_id;";
		$sql = $conn->prepare($sql_quary);
		$sql->bindparam(":category_id",$category_id);
		$sql->execute();
		$numRows = $sql->fetchAll();
		if(count($numRows)==1){
			foreach($numRows as $row){
				$txt_category_name=$row["food_type_name"];

			}
		}
	}

/*-------------------------------------------------------------------------------------------------------------*/
###################################################################################
#################################################################################################
######################################################################################################
#########################################################################################################
//canteens
	$txt_canteen_name="";
	$txt_canteen_reg_no="";
	$txt_canteen_location="";
	$canteen_id="";
	if(isset($_GET["canteen_id"])){
		if($_GET["canteen_id"]!=""){
			$canteen_id=$_GET["canteen_id"];
		}
		else{
			header("Location:index.php");
		}
	}
//delete canteen
	if(isset($_POST["btn_canteen_del"])){
		$canteen_id=$_POST["hid_canteen_id"];
		$sql_quary = "DELETE FROM `tbl_canteen` WHERE `can_id`=:canteen_id;";
		$sql = $conn->prepare($sql_quary);
		$sql->bindparam(":canteen_id",$canteen_id);
		$sql->execute();
		$message=array("Success!","alert-success","Canteen Successfully Removed.");
	}

//add canteen
	if(isset($_POST["btn_canteen_add"])){
		$txt_canteen_name=$_POST["txt_canteen_name"];
		$txt_canteen_reg_no=$_POST["txt_canteen_reg_no"];
		$txt_canteen_location=$_POST["txt_canteen_location"];
		$sql_quary="INSERT INTO `tbl_canteen` (`can_id`,`can_name`,`can_reg_number`,`can_location`) VALUES (NULL, :canteen_name, :can_reg_number,:can_location);";
		echo $sql_quary;
		$sql = $conn->prepare($sql_quary);
		$sql->bindparam(":canteen_name",$txt_canteen_name);
		$sql->bindparam(":can_reg_number",$txt_canteen_reg_no);
		$sql->bindparam(":can_location",$txt_canteen_location);
		$sql->execute();
		$message=array("Success!","alert-success","Canteen Successfully Inserted.");
		$request="canteen-check";
	}

//edit canteen
	if(isset($_POST["btn_canteen_edit"])){
		$txt_canteen_name=$_POST["txt_canteen_name"];
		$txt_canteen_reg_no=$_POST["txt_canteen_reg_no"];
		$txt_canteen_location=$_POST["txt_canteen_location"];
		$canteen_id=$_POST["hid_canteen_id"];
		$sql_quary="UPDATE `tbl_canteen` SET `can_name`=:can_name,`can_reg_number`=:can_reg_number, `can_location`=:can_location WHERE `can_id`=:canteen_id;";
		$sql = $conn->prepare($sql_quary);
		$sql->bindparam(":can_name",$txt_canteen_name);
		$sql->bindparam(":can_reg_number",$txt_canteen_reg_no);
		$sql->bindparam(":can_location",$txt_canteen_location);
		$sql->bindparam(":canteen_id",$canteen_id);
		$sql->execute();
		$message=array("Success!","alert-success","Canteen Successfully Updated.");
		$request="canteen-check";
	}

//Canteen edit request
	if($request=="canteen-edit"){
		$sql_quary="SELECT * FROM `tbl_canteen` WHERE `can_id`=:canteen_id;";
		$sql = $conn->prepare($sql_quary);
		$sql->bindparam(":canteen_id",$canteen_id);
		$sql->execute();
		$numRows = $sql->fetchAll();
		if(count($numRows)==1){
			foreach($numRows as $row){
				$txt_canteen_name=$row["can_name"];
				$txt_canteen_reg_no=$row["can_reg_number"];
				$txt_canteen_location=$row["can_location"];
			}
		}
	}

/*-------------------------------------------------------------------------------------------------------------------*/
$user_id=null;
//get user id from address bar
	if(isset($_GET["user_id"])){
		if($_GET["user_id"]!=""){
			$user_id=$_GET["user_id"];
		}
		else{
			header("Location:index.php");
		}
	}

	//delete user
	if(isset($_POST["btn_user_del"])){
		//get user_id
		$user_id=$_POST["hid_user_id"];

		//delte user
		$sql_quary="DELETE FROM `tbl_user` WHERE `user_id`=:user_id;";
		$sql = $conn->prepare($sql_quary);
		$sql->bindparam(':user_id',$user_id);
		$sql->execute();
		$sql_quary="DELETE FROM `tbl_staff` WHERE `staff_user_id`=:user_id;";
		$sql = $conn->prepare($sql_quary);
		$sql->bindparam(':user_id',$user_id);
		$sql->execute();
		$sql_quary="DELETE FROM `tbl_customer` WHERE `cus_user_id`=:user_id;";
		$sql = $conn->prepare($sql_quary);
		$sql->bindparam(':user_id',$user_id);
		$sql->execute();

		//success message
		$message=array("Success!","alert-success","User Successfully Removed.");
	}

	//accept user
	if(isset($_POST["btn_user_add"])){
		//get user_id
		$user_id=$_POST["hid_user_id"];
		$sql_quary="UPDATE `tbl_user` SET `user_active`=1 WHERE `user_id`=:user_id;";
		$sql = $conn->prepare($sql_quary);
		$sql->bindparam(":user_id",$user_id);
		$sql->execute();
		$message=array("Success!","alert-success","User Successfully Accepted.");
		$request="user-check";
	}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  	<title>Admin - Canteen Network</title>
  	<meta charset="utf-8">
  	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/custom_css.css">
	<script src="js/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
</head>
<body>
	<?php navbar($user_name,$user_type,"admin"); ?>
		<div class="container-fuled" style="overflow: hidden;">
			<div class="row">
				<div class="col-sm-2">
					<ul class="v-nav">
						<li class="v-nav-li"><a id="welcome" href="admin.php?request=welcome&navid=welcome">Welcome</a></li>
				  		<li class="v-nav-li"><a id="user-check" href="admin.php?request=user-check&navid=user-check">Pending Users</a></li>
				  		<li class="v-nav-li"><a id="canteen-check" href="admin.php?request=canteen-check&navid=canteen-check">Canteens</a></li>
				  		<li class="v-nav-li"><a id="category-check" href="admin.php?request=category-check&navid=category-check">categories</a></li>
					</ul>
				</div>
				<script type="text/javascript">
					var x = document.getElementById("<?php echo $navid; ?>");
					x.setAttribute("class", "active");
				</script>
				<div class="col-sm-10" style="padding: 10px 70px;">
				<div class="well row">
				<?php
					if($message[0]!=""){
						echo "<div class=\"alert $message[1]\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a><strong>$message[0]</strong> $message[2]</div>";
					}

					if($request=="welcome"){
				?>
						<h3>Start Admin</h3>
						<p>This is the admin area. You Can Manage All Basic Settings Of the System.</p>

				<?php
					}
					elseif($request=="canteen-check"){
				?>
						<form role="form" action="admin.php?request=canteen-check&navid=canteen-check" method="post" class="form-horizontal">
						<h3>Canteens</h3>
							<a href="admin.php?request=canteen-add&navid=canteen-check"><input type="button" class="btn my-btn" style= "width: auto; margin-bottom: 12px;" value="Add New Canteen"></a>
							<h3>All Canteens</h3>
							<div style="overflow-x:auto; ">
				  			<table style="width: 95%;">
				  				<tr><th>Canteen</th><th>Reg number</th><th>Location</th><th>Action</th></tr>
				  				<?php
				  					$sql_quary = "SELECT * FROM `tbl_canteen`;";
									$sql = $conn->prepare($sql_quary);
									$sql->execute();
									$numRows = $sql->fetchAll();
									if(count($numRows)>0){
										foreach($numRows as $row){
											echo "<tr><td>".$row["can_name"]."</td><td>".$row["can_reg_number"]."</td><td>".$row["can_location"]."</td>";
											$remove_btn="<a href=\"admin.php?request=canteen-del&navid=canteen-check&canteen_id=".$row["can_id"]."\"><span class=\"glyphicon glyphicon-remove\"></span> Remove</a>";

											echo "<td><a href=\"admin.php?request=canteen-edit&navid=canteen-check&canteen_id=".$row["can_id"]."\"><span class=\"glyphicon glyphicon-pencil\"></span> Edit</a>&nbsp;&nbsp;".$remove_btn."</td></tr>";
										}
									}
				  				?>
				  			</table>
				  			</div>
						</form>
				<?php
					}
					elseif(($request=="canteen-add")||($request=="canteen-edit")){
				?>
						<form role="form" action="admin.php?request=canteen-check&navid=canteen-check" method="post" class="form-horizontal">
							<?php
								if($request=="canteen-add"){
									echo "<h3>Add New Canteen</h3>";
								}
								elseif($request=="material-edit"){
									echo "<h3>Eidt Canteen Details/h3>";
								}
							?>
							<div class="form-group">
								<label class="control-label col-sm-2" for="txt_canteen_name">Canteen Name:</label>
								<div class="col-sm-6">
								    <input type="text" name="txt_canteen_name" id="txt_canteen_name" class="form-control" required="true" maxlength="50" value="<?php echo $txt_canteen_name; ?>" autofocus/>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2" for="txt_canteen_reg_no">Reg Number:</label>
								<div class="col-sm-6">
								    <input type="text" name="txt_canteen_reg_no" id="txt_canteen_reg_no" class="form-control" required="true" maxlength="50" value="<?php echo $txt_canteen_reg_no; ?>"/>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2" for="txt_canteen_location">Location:</label>
								<div class="col-sm-6">
								    <input type="text" name="txt_canteen_location" id="txt_canteen_location" class="form-control" required="true" maxlength="50" value="<?php echo $txt_canteen_location; ?>"/>
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-offset-2 col-sm-6">
								<?php if($request=="canteen-add"){ ?>
									<input type="submit" id="btn_canteen_add" name="btn_canteen_add" class="btn btn-success" value="Submit"/>
								<?php } else { ?>
									<input type="submit" id="btn_canteen_edit" name="btn_canteen_edit" class="btn btn-success" value="Save Changes"/>
									<input type="hidden" name="hid_canteen_id" id="hid_canteen_id" value="<?php echo $canteen_id; ?>">
								<?php } ?>
									<a href="admin.php?request=canteen-check&navid=canteen-check" class="btn btn-warning" style="float: right;">Cancel</a>
								</div>
							</div>
						</form>
				<?php
					}
					elseif($request=="canteen-del"){
				?>
						<form role="form" action="admin.php?request=canteen-check&navid=canteen-check" method="post" class="form-horizontal">
						<h3>Remove Canteen</h3>
						<?php
							$canteen_name="";
							$sql_quary = "SELECT `can_name` FROM `tbl_canteen` WHERE `can_id`=:canteen_id;";
							$sql = $conn->prepare($sql_quary);
							$sql->bindparam(':canteen_id',$canteen_id);
							$sql->execute();
							$numRows = $sql->fetchAll();
							if(count($numRows)==1){
								$canteen_name=$numRows[0][0];
							}
						?>
							<h3>Are you sure?</h3>
							<p>You are trying to <b>delete</b> the canteen named <b><?php echo $canteen_name; ?></b>. If you want to remove this canteen please confirm it by clicking Remove button below.</p>
							<div class="form-group">
								<div class="col-sm-3">
									<input type="hidden" name="hid_canteen_id" value="<?php echo $canteen_id; ?>">
								    <input type="submit" id="btn_canteen_del" name="btn_canteen_del" class="btn btn-danger" value="Remove"/>
								    <a href="admin.php?request=canteen-check&navid=canteen-check" class="btn btn-warning" style="float: right;">Not Now</a>
								</div>
							</div>
						</form>
				<?php						
					}
					elseif($request=="category-check"){
				?>
						<h3>Food Categories</h3>
							<a href="admin.php?request=category-add&navid=category-check"><input type="button" class="btn my-btn" style= "width: auto; margin-bottom: 12px;" value="Add New Food Category"></a>
							<h3>All Food Categories</h3>
							<div style="overflow-x:auto; ">
				  			<table style="width: 95%;">
				  				<tr><th>Category ID</th><th>Food Category</th><th>Action</th></tr>
				  				<?php
				  					$sql_quary = "SELECT * FROM `tbl_food_type`;";
									$sql = $conn->prepare($sql_quary);
									$sql->execute();
									$numRows = $sql->fetchAll();
									if(count($numRows)>0){
										foreach($numRows as $row){
												$remove_btn="<a href=\"admin.php?request=category-del&navid=category-check&category_id=".$row["food_type_id"]."\"><span class=\"glyphicon glyphicon-remove\"></span> Remove</a>";

											echo "<tr class=\"info\"><td>".$row["food_type_id"]."</td><td>".$row["food_type_name"]."</td><td><a href=\"admin.php?request=category-edit&navid=category-check&category_id=".$row["food_type_id"]."\"><span class=\"glyphicon glyphicon-pencil\"></span> Edit</a>&nbsp;&nbsp;".$remove_btn."</td></tr>";
										}
									}
				  				?>
				  			</table>
				  			</div>
						</form>
				<?php		
					}
					elseif(($request=="category-add")||($request=="category-edit")){
				?>
					<form role="form" action="admin.php?request=<?php echo $request; ?>&navid=category-check" method="post" class="form-horizontal">
							<?php
								if($request=="category-add"){
									echo "<h3>Add New Food Category</h3>";
								}
								elseif($request=="category-edit"){
									echo "<h3>Edit Food Category</h3>";
								}
							?>
							<div class="form-group">
								<label class="control-label col-sm-2" for="txt_category_name">Category Name:</label>
								<div class="col-sm-6">
								    <input type="text" name="txt_category_name" id="txt_category_name" class="form-control" required="true" maxlength="50" value="<?php echo $txt_category_name; ?>" autofocus/>
								</div>
							</div>
							
							<div class="form-group">
								<div class="col-sm-offset-2 col-sm-6">
								<?php if($request=="category-add"){ ?>
									<input type="submit" id="btn_category_add" name="btn_category_add" class="btn btn-success" value="Submit"/>
								<?php } else { ?>
									<input type="submit" id="btn_category_edit" name="btn_category_edit" class="btn btn-success" value="Save Changes"/>
									<input type="hidden" name="hid_category_id" id="hid_category_id" value="<?php echo $category_id; ?>">
								<?php } ?>
									<a href="admin.php?request=category-check&navid=category-check" class="btn btn-warning" style="float: right;">Cancel</a>
								</div>
							</div>
						</form>
				<?php		
					}
					elseif($request=="category-del"){
				?>
						<form role="form" action="admin.php?request=category-check&navid=category-check" method="post" class="form-horizontal">
						<h3>Remove Food Category</h3>
						<?php
							$category_name="";
							$sql_quary = "SELECT `food_type_name` FROM `tbl_food_type` WHERE `food_type_id`=:category_id;";
							$sql = $conn->prepare($sql_quary);
							$sql->bindparam(':category_id',$category_id);
							$sql->execute();
							$numRows = $sql->fetchAll();
							if(count($numRows)==1){
								$category_name=$numRows[0][0];
							}
						?>
							<h3>Are you sure?</h3>
							<p>You are trying to <b>delete</b> the food category named <b><?php echo $category_name; ?></b>. If you want to remove this category please confirm it by clicking Remove button below.</p>
							<div class="form-group">
								<div class="col-sm-3">
									<input type="hidden" name="hid_category_id" value="<?php echo $category_id; ?>">
								    <input type="submit" id="btn_category_del" name="btn_category_del" class="btn btn-danger" value="Remove"/>
								    <a href="admin.php?request=category-check&navid=category-check" class="btn btn-warning" style="float: right;">Not Now</a>
								</div>
							</div>
						</form>
				<?php
					}
					elseif($request=="user-check"){
				?>
						<h3>Pending Users</h3>
							<div style="overflow-x:auto; ">
				  			<table style="width: 95%;">
				  				<tr><th>User ID</th><th>User Type</th><th>Name</th><th>Other</th><th>Action</th></tr>
				  				<?php
				  					$sql_quary = "SELECT * FROM `tbl_user` LEFT JOIN tbl_staff ON tbl_user.user_id=tbl_staff.staff_user_id LEFT JOIN tbl_customer ON tbl_user.user_id=tbl_customer.cus_user_id LEFT JOIN tbl_canteen ON tbl_staff.staff_canteen=tbl_canteen.can_id WHERE user_active=0;";
									$sql = $conn->prepare($sql_quary);
									$sql->execute();
									$numRows = $sql->fetchAll();
									if(count($numRows)>0){
										foreach($numRows as $row){
												$remove_btn="<a href=\"admin.php?request=user-del&navid=user-check&user_id=".$row["user_id"]."\"><span class=\"glyphicon glyphicon-remove\"></span> Remove</a>";
												$edit_btn="<a href=\"admin.php?request=user-add&navid=user-check&user_id=".$row["user_id"]."\"><span class=\"glyphicon glyphicon-pencil\"></span> Accept</a>";
											$user_type="Customer";
											if($row["user_type"]==1){
												$user_type="<font color=\"red\">Staff</font>";
												$other_info="Canteen:".$row["can_name"];
											}
											else{
												$other_info="DOB:".$row["cus_dob"]."<br/>"."Std ID:".$row["cus_std_id"];
											}
											echo "<tr class=\"info\"><td>".$row["user_id"]."</td><td>".$user_type."</td><td>".$row["user_name"]."</td><td>".$other_info."</td><td>".$remove_btn."&nbsp;&nbsp;".$edit_btn."</td></tr>";
										}
									}
				  				?>
				  			</table>
				  			</div>
						</form>
				<?php		
					}
					elseif($request=="user-del"){
				?>
						<form role="form" action="admin.php?request=user-check&navid=user-check" method="post" class="form-horizontal">
						<h3>Refuse and Delete User Account</h3>
						<?php
							$user_name="";
							$sql_quary = "SELECT `user_name` FROM `tbl_user` WHERE `user_id`=:user_id;";
							$sql = $conn->prepare($sql_quary);
							$sql->bindparam(':user_id',$user_id);
							$sql->execute();
							$numRows = $sql->fetchAll();
							if(count($numRows)==1){
								$user_name=$numRows[0][0];
							}
						?>
							<h3>Are you sure?</h3>
							<p>You are going to <b>refuse & delete</b> a user named <b><?php echo $user_name; ?></b>. If you want to delete this please confirm it by clicking Delete button below.</p>
							<div class="form-group">
								<div class="col-sm-3">
									<input type="hidden" name="hid_user_id" value="<?php echo $user_id; ?>">
								    <input type="submit" id="btn_user_del" name="btn_user_del" class="btn btn-danger" value="Delete"/>
								    <a href="admin.php?request=user-check&navid=user-check" class="btn btn-warning" style="float: right;">Not Now</a>
								</div>
							</div>
						</form>
				<?php
					}
					elseif($request=="user-add"){
				?>
						<form role="form" action="admin.php?request=user-check&navid=user-check" method="post" class="form-horizontal">
						<h3>Accept User Account</h3>
						<?php
							$user_name="";
							$sql_quary = "SELECT `user_name` FROM `tbl_user` WHERE `user_id`=:user_id;";
							$sql = $conn->prepare($sql_quary);
							$sql->bindparam(':user_id',$user_id);
							$sql->execute();
							$numRows = $sql->fetchAll();
							if(count($numRows)==1){
								$user_name=$numRows[0][0];
							}
						?>
							<h3>Are you sure?</h3>
							<p>You are going to <b>Conform</b> a user named <b><?php echo $user_name; ?></b> To access to the system. Click Conform Button to Continue.</p>
							<div class="form-group">
								<div class="col-sm-3">
									<input type="hidden" name="hid_user_id" value="<?php echo $user_id; ?>">
								    <input type="submit" id="btn_user_add" name="btn_user_add" class="btn btn-success" value="Conform"/>
								    <a href="admin.php?request=user-check&navid=user-check" class="btn btn-warning" style="float: right;">Not Now</a>
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