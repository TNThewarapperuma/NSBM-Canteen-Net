<?php
//include files
	require("include/dbcon.inc.php");
	require("include/navbar.inc.php");

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
	//echo "<br/><br/><br/>".$_SESSION["user_name"];
	?>
<!DOCTYPE html>
<html lang="en">
<head>
  	<title>Home - Canteen Network</title>
  	<meta charset="utf-8">
  	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/custom_css.css">
	<script src="js/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<style type="text/css">
        .th-no-css{
        	text-align: left !important;
        }
	</style>
</head> 
<body>
	<?php navbar($user_name,$user_type,"home"); ?>
	<div class="container-fuled" style="overflow: hidden;">
		<div class="row">
			<div class="col-sm-12" style="padding: 10px 70px;">
				<div class="well row" style="padding: 0px !important;">
				<?php
					//get name of the user to say welcom
					//$start_message=false;
					$sql_quary="SELECT `user_name` FROM `tbl_user` WHERE `user_username`=:user_username;";
					$sql = $conn->prepare($sql_quary);
					$sql->bindparam(":user_username",$user_name);
					$sql->execute();
					$numRows = $sql->fetchAll();
					if(count($numRows)==1){
						foreach ($numRows as $row){
							echo "<div class=\"welcome_div\"><img src=\"images/search.png\" style=\"width: 150px;\"><font class=\"welcome_text\">Welcome ".$row["user_name"].", Are you hungry?</font></div>";
						}
					}
			?>
				<div class="row">
					<div class="col-sm-12" style="padding: 20px 50px; padding-bottom: 10px;">
						<div class="row">
							<div class="col-sm-3">
								<h4>Leyon's Food -Auditorum</h4>
								<img src="images/leyons.jpg" style="width: 100%;">
								<h5><a href="search_food.php?request=start&search_info=YTo0OntpOjA7aToxO2k6MTtzOjE6IjMiO2k6MjtzOjA6IiI7aTozO3M6MDoiIjt9"><span class="glyphicon glyphicon-cutlery"></span> All Leyon's Foods</a></h5>
							</div>
							<div class="col-sm-3">
								<h4>Gihan's Food -Hostal</h4>
								<img src="images/gihans.jpg" style="width: 100%;">
								<h5><a href="search_food.php?request=start&search_info=YTo0OntpOjA7aToxO2k6MTtzOjE6IjQiO2k6MjtzOjA6IiI7aTozO3M6MDoiIjt9"><span class="glyphicon glyphicon-cutlery"></span> All Gihan's Foods</a></h5>
								<h5><a href="search_food.php?request=start&search_info=YTo0OntpOjA7aToxO2k6MTtzOjE6IjQiO2k6MjtzOjE6IjQiO2k6MztzOjA6IiI7fQ=="><span class="glyphicon glyphicon-cutlery"></span> Bakery Items</a></h5>
								<h5><a href="search_food.php?request=start&search_info=YTo0OntpOjA7aToxO2k6MTtzOjE6IjQiO2k6MjtzOjE6IjUiO2k6MztzOjA6IiI7fQ=="><span class="glyphicon glyphicon-cutlery"></span> Breakfirst</a></h5>
								<h5><a href="search_food.php?request=start&search_info=YTo0OntpOjA7aToxO2k6MTtzOjE6IjQiO2k6MjtzOjE6IjYiO2k6MztzOjA6IiI7fQ=="><span class="glyphicon glyphicon-cutlery"></span> Lunch</a></h5>
								<h5><a href="search_food.php?request=start&search_info=YTo0OntpOjA7aToxO2k6MTtzOjE6IjQiO2k6MjtzOjE6IjciO2k6MztzOjA6IiI7fQ=="><span class="glyphicon glyphicon-cutlery"></span> Dinner</a></h5>
								<h5><a href="search_food.php?request=start&search_info=YTo0OntpOjA7aToxO2k6MTtzOjE6IjQiO2k6MjtzOjE6IjgiO2k6MztzOjA6IiI7fQ=="><span class="glyphicon glyphicon-cutlery"></span> Snaks</a></h5>
								<h5><a href="search_food.php?request=start&search_info=YTo0OntpOjA7aToxO2k6MTtzOjE6IjQiO2k6MjtzOjE6IjkiO2k6MztzOjA6IiI7fQ=="><span class="glyphicon glyphicon-cutlery"></span> Severy Items</a></h5>
								<h5><a href="search_food.php?request=start&search_info=YTo0OntpOjA7aToxO2k6MTtzOjE6IjQiO2k6MjtzOjI6IjEwIjtpOjM7czowOiIiO30="><span class="glyphicon glyphicon-cutlery"></span> Dessorts</a></h5>

							</div>
							<div class="col-sm-3">
								<h4>Snak Bar</h4>
								<img src="images/snak_bar.png" style="width: 100%;">
								<h5><a href="search_food.php?request=start&search_info=YTo0OntpOjA7aToxO2k6MTtzOjE6IjEiO2k6MjtzOjA6IiI7aTozO3M6MDoiIjt9"><span class="glyphicon glyphicon-cutlery"></span> All Snak Bar Foods</a></h5>
								<h5><a href="search_food.php?request=start&search_info=YTo0OntpOjA7aToxO2k6MTtzOjE6IjEiO2k6MjtzOjE6IjEiO2k6MztzOjA6IiI7fQ=="><span class="glyphicon glyphicon-cutlery"></span> Drinks</a></h5>
								<h5><a href="search_food.php?request=start&search_info=YTo0OntpOjA7aToxO2k6MTtzOjE6IjEiO2k6MjtzOjI6IjExIjtpOjM7czowOiIiO30="><span class="glyphicon glyphicon-cutlery"></span> Juices</a></h5>
								<h5><a href="search_food.php?request=start&search_info=YTo0OntpOjA7aToxO2k6MTtzOjE6IjEiO2k6MjtzOjE6IjMiO2k6MztzOjA6IiI7fQ=="><span class="glyphicon glyphicon-cutlery"></span> Foods</a></h5>
							</div>
							<div class="col-sm-3"></div>
						</div>
					</div>
				</div>
			</div>
			</div>
		</div>
	</div>
	<?php
		include("include/fotter.inc.php");
	?>
</body>
</html>