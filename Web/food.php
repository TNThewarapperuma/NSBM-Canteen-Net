<?php
//include files
	require("include/dbcon.inc.php");
	require("include/navbar.inc.php");
	require("include/combo.inc.php");
	require("include/validation.inc.php");
	require("include/spilit_result.inc.php");
	require("include/upload_img.inc.php");

//start session
session_start();

//login check
	//create variable for store username
	$user_name="";
	$user_type=array();

	//check user login and get details from session
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
	//check whether user have authorze to access to this page
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

	//get data from address bar
	$request="food-add";
	if(isset($_GET["edit_food"])){
		$request="edit_food";
	}

//basic messages
	//all types of basic massages of the page stored in the array [one message for each time]
	//array index stuctue - title, style, message
	$message=array("","","");

/*-----------------------------------------------------------------------------------------------------------*/

//following variable is used to get data from POST when inserting and updateing food details
	//error message varibles
	$txt_food_rate_err="";//error message of food price
	$txt_food_req_code_err="";	

	//input varibles
	$ddl_food_type="";
	$txt_food_req_code="";
	$txt_food_name="";
	$txt_food_rate="";

	//updating detils trackig data variables
	$food_id=null;	//when updating this use to make data set uniqe

	//error message show
	$err=false;	//this variable reprsents if user inputs are crroect or wrong $err=false ->no eerors

	//this variables used to set inset/updte button name and value
	$btn_name="btn_food_add";
	$btn_val="Submit";
	//if page action is edit materils above variables chage
	if($request=="edit_food"){
		$btn_name="btn_food_edit";
		$btn_val="Update";
	}
/*-----------------------------------------------------------------------------------------------------------*/

//add and edit new food
	if((isset($_POST["btn_food_add"]))||(isset($_POST["btn_food_edit"]))){
		
		$ddl_food_type=$_POST["ddl_food_type"];
		$txt_food_req_code=$_POST["txt_food_req_code"];
		$txt_food_name=$_POST["txt_food_name"];
		$txt_food_rate=$_POST["txt_food_rate"];
		

		//if this is an update get tracking data from hidden boxes
		if(isset($_POST["btn_food_edit"])){
			$food_id=$_POST["hid_food_id"];	//food sn[uniqe]
		}

		//data validation
		//check whether price is numeric 
		if(!(is_numeric($txt_food_rate))){
			$err=true;
			$txt_food_rate="Food Rate you entered not valid. please check.";
			$txt_food_rate="";
		}
		
		//if this is an insert - check whether food request code alredy exists
		//if(isset($_POST["btn_food_add"])){
			$sql_quary="SELECT `food_id` FROM `tbl_food` WHERE `food_req_code`=:food_req_code AND `food_canteen_id`=:canteen_id";
			$query_params=array(":food_req_code"=>$txt_food_req_code,":canteen_id"=>$user_canteen);
			if($food_id!=null){
				$sql_quary.=" and food_id!=:food_id";
				$query_params[":food_id"]=$food_id;
			}
			if(val_duplicate($sql_quary,$query_params)==1){
				$err=true;
				$txt_food_req_code="";
				$txt_food_req_code_err="Food Request Code Alredy Exists.";
			}
		//}

		//insert food details
		if(($err==false)&&(isset($_POST["btn_food_add"]))){
			$last_food_sn="";//this is use store last inset id of following query
			$sql_quary = "INSERT INTO tbl_food VALUES(null,:food_type,:food_req_code,:food_name,:food_rate,:food_canteen);";
			$sql = $conn->prepare($sql_quary);
			$sql->bindparam(":food_type",$ddl_food_type);
			$sql->bindparam(":food_req_code",$txt_food_req_code);
			$sql->bindparam(":food_name",$txt_food_name);
			$sql->bindparam(":food_rate",$txt_food_rate);
			$sql->bindparam(":food_canteen",$user_canteen);
			//echo "<br/><br/><br/>".$user_canteen;
			$sql->execute();
			//$last_food_sn=$conn->lastInsertId();

			//set message that quey is success
			$message=array("Success!","alert-success","New Food Successfully Added.");

			//clear vars
			$ddl_food_type="";
			$txt_food_req_code="";
			$txt_food_name="";
			$txt_food_rate="";

			//set request to add new food
			$request="food-add";
		}
		//update food details
		elseif(($err==false)&&(isset($_POST["btn_food_edit"]))){
			$sql_quary="UPDATE `tbl_food` SET food_type_id=:food_type, food_name=:food_name, food_req_code=:food_req_code, food_rate=:food_rate WHERE food_canteen_id=:food_canteen AND food_id=:food_id;";
			$sql = $conn->prepare($sql_quary);
			$sql->bindparam(":food_type",$ddl_food_type);
			$sql->bindparam(":food_name",$txt_food_name);
			$sql->bindparam(":food_req_code",$txt_food_req_code);
			$sql->bindparam(":food_rate",$txt_food_rate);
			$sql->bindparam(":food_canteen",$user_canteen);
			$sql->bindparam(":food_id",$food_id);
			$sql->execute();

			//echo "<br/><br/><br/>".$food_id."+";
			//echo $user_canteen;


			//set message that query is success
			$message=array("Success!","alert-success","Food Item Successfully Updated.");

			header("Location:search_food.php");

		}
	}

/*-----------------------------------------------------------------------------------------------------------*/

//load food data fror change
	if(isset($_GET["edit_food"])){
		//get food id for edit
		$food_id=$_GET["edit_food"];

		//create query for get data
		$sql_quary="SELECT * FROM `tbl_food` WHERE `food_id`=:food_id AND food_canteen_id=:food_canteen;";
		$sql = $conn->prepare($sql_quary);
		$sql->bindparam(":food_id",$food_id);
		$sql->bindparam(":food_canteen",$user_canteen);
		$sql->execute();
		$numRows = $sql->fetchAll();
		if(count($numRows)==1){
			foreach ($numRows as $row){
				$ddl_food_type=$row["food_type_id"];
				$txt_food_rate=$row["food_rate"];
				$txt_food_name=$row["food_name"];
				$txt_food_req_code=$row["food_req_code"];
				
				//set request to open edit form
				$request="edit_food";
			}
		}
	}

	//load food data fror change
	if(isset($_GET["del_food"])){
		//get food id for edit
		$food_id=$_GET["del_food"];

		//create query for get data
		$sql_quary="DELETE FROM `tbl_food` WHERE `food_id`=:food_id AND food_canteen_id=:food_canteen;";
		$sql = $conn->prepare($sql_quary);
		$sql->bindparam(":food_id",$food_id);
		$sql->bindparam(":food_canteen",$user_canteen);
		$sql->execute();
		header("Location:search_food.php");
	}
/*-----------------------------------------------------------------------------------------------------------*/

?>
<!DOCTYPE html>
<html lang="en">
<head>
  	<title>Foods - Canteen Network</title>
  	<meta charset="utf-8">
  	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/custom_css.css">
	<script src="js/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
</head>
<body>
	<?php navbar($user_name,$user_type,"food"); ?>
		<div class="container-fuled" style="overflow: hidden;">
				<div class="col-sm-10" style="padding: 10px 70px;">
				<div class="well row">
				<?php
					if($message[0]!=""){
						echo "<div class=\"alert $message[1]\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a><strong>$message[0]</strong> $message[2]</div>";
					}

					if(($request=="food-add")||($request=="edit_food")){
						if($request=="food-add"){ 
				?>
							<form role="form" action="food.php?request=food-add" method="post" class="form-horizontal">
						<?php	}else{  ?>
							<form role="form" action="food.php?edit_food=<?php echo $food_id; ?>" method="post" class="form-horizontal">
						<?php } ?>
							<div class="form-group">
								<label class="control-label col-sm-2" for="ddl_food_type">food Type:</label>
								<div class="col-sm-6">
									<?php
										$c_sql="SELECT * FROM `tbl_food_type`;";
										create_combo($c_sql,"ddl_food_type","food_type_id","food_type_name","form-control",$ddl_food_type);
									?>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2" for="txt_food_req_code">Food Request Code:</label>
								<div class="col-sm-6">
								    <input type="text" name="txt_food_req_code" id="txt_food_req_code" class="form-control" required="true" maxlength="10" value="<?php echo $txt_food_req_code; ?>"/>
								    <div class="form-err">
								    	<?php if($txt_food_req_code_err!=""){ echo $txt_food_req_code_err; } ?>
								    </div>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2" for="txt_food_name">Food Name:</label>
								<div class="col-sm-6">
								    <input type="text" name="txt_food_name" id="txt_food_name" class="form-control" required="true" maxlength="100" value="<?php echo $txt_food_name; ?>" placeholder="Name Of the Food Item"/>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2" for="txt_food_rate">Rate:</label>
								<div class="col-sm-6">
								    <input type="text" name="txt_food_rate" id="txt_food_rate" class="form-control" required="true" maxlength="10" value="<?php echo $txt_food_rate; ?>" placeholder="Rate Of the Food Item"/>
								</div>
							</div>
							
							<div class="form-group">
								<div class="col-sm-offset-2 col-sm-6">
								    <input type="submit" name="<?php echo $btn_name; ?>" id="<?php echo $btn_name; ?>" class="btn btn-success" value="<?php echo $btn_val; ?>"/>
								<?php
									if($request=="edit_food"){
										echo "<a href=\"search_food.php\"><input type=\"button\" class=\"btn btn-danger\" value=\"Cancel\" style=\"float: right;\" /></a>";
									}
								?>
								    <input type="reset" name="btn_reset" id="btn_reset" class="btn btn-warning" value="Reset" style="float: right; margin-right: 10px;"/>
								</div>
							</div>
							<?php if($request=="edit_food"){ ?>
								<input type="hidden" name="hid_food_id" id="hid_food_id" value="<?php echo $food_id; ?>"/>
							<?php } ?>
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