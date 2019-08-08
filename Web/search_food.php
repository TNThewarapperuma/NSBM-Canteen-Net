<?php
//include files
	require("include/dbcon.inc.php");
	require("include/navbar.inc.php");
	require("include/combo.inc.php");
	require("include/spilit_result.inc.php");

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

//get request - get action of the page from url
	//request variable is used to store page action [default action is welcome]
	$request="start";

	//navid variable is used to set side navbar selected item [default is welcome]
	//$navid="welcome";

	//get data from address bar
	if(isset($_GET["request"])){
		$request=$_GET["request"];
	}


//basic messages
	//all types of basic massages of the page stored in the array [one message for each time]
	//array index stuctue - title, style, message
	$message=array("","","");

/*-----------------------------------------------------------------------------------------------------------*/

//search materials
	$page=1;
	$search_result=null;
	$search_result_data=array();
	$txt_keyword="";
	$ddl_canteen=null;
	$ddl_food_type=null;
	$data_get_success=0;
	$query_send=false;

	//get search infomation from address bar when using page navigator
	if(isset($_GET["search_info"])){
		$search_info=$_GET["search_info"];
		$search_info=unserialize(base64_decode($_GET["search_info"]));
		$data_get_success=1;

		$page=$search_info[0];
		$ddl_canteen=$search_info[1];
		$ddl_food_type=$search_info[2];
		$txt_keyword=$search_info[3];
	}

	//get search details from post(search buttion clicking)
	if(isset($_POST["btn_food_search"])){
		$txt_keyword=$_POST["txt_keyword"];
		$ddl_canteen=$_POST["ddl_canteen"];
		$ddl_food_type=$_POST["ddl_food_type"];
	}

	if((isset($_POST["btn_food_search"]))||$data_get_success==1){
		$sql_quary="";
		//$search_param=$txt_material_search;
		$txt_keyword_like=$txt_keyword;
		//if($data_get_success!=1){
			$txt_keyword_like="%".$txt_keyword."%";
		//}

		$sql_quary="SELECT * FROM tbl_food LEFT JOIN tbl_canteen ON tbl_food.food_canteen_id=tbl_canteen.can_id LEFT JOIN tbl_food_type ON tbl_food.food_type_id=tbl_food_type.food_type_id WHERE food_name like :txt_keyword";

		$query_params=array(":txt_keyword"=>$txt_keyword_like);
		if($ddl_canteen!=""){
			$sql_quary.=" AND tbl_food.food_canteen_id=:food_canteen_id";
			$query_params[":food_canteen_id"]=$ddl_canteen;
		}

		if($ddl_food_type!=""){
			$sql_quary.=" AND tbl_food.food_type_id=:food_type_id";
			$query_params[":food_type_id"]=$ddl_food_type;
		}
		
		$sql_quary.=" ORDER BY tbl_food.food_name";
		//echo "<br/><br/><br/>".$sql_quary;
		//echo $txt_keyword_like;
			
		$search_result=spilit_result($page,10,$sql_quary,$query_params);

		$search_result_data=$search_result[0];

		$query_send=true;
	}
/*-----------------------------------------------------------------------------------------------------------*/

?>
<!DOCTYPE html>
<html lang="en">
<head>
  	<title>Search - Canteen Network</title>
  	<meta charset="utf-8">
  	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/custom_css.css">
	<script src="js/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
</head>
<body>
	<?php navbar($user_name,$user_type,"material_search"); ?>
		<div class="container-fuled" style="overflow: hidden;">
			<div class="row">
				<div class="col-sm-12" style="padding: 10px 70px;">
				<div class="row">
				<?php
					if($message[0]!=""){
						echo "<div class=\"alert $message[1]\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a><strong>$message[0]</strong> $message[2]</div>";
					}

					if($request=="start"){
				?>
					<form role="form" action="search_food.php?request=start" method="post" class="form-horizontal">
						<div class="container search-box">
						<div class="form-group">
							<label class="control-label col-sm-1" for="ddl_canteen">Canteen:</label>
							<div class="col-sm-2">
								<?php
									$c_sql="SELECT * FROM `tbl_canteen`;";
									create_combo_noreq($c_sql,"ddl_canteen","can_id","can_name","form-control",$ddl_canteen);
								?>
							</div>
							<label class="control-label col-sm-1" for="ddl_food_type">Category:</label>
							<div class="col-sm-2">
								<?php
									$c_sql="SELECT * FROM `tbl_food_type`;";
									create_combo_noreq($c_sql,"ddl_food_type","food_type_id","food_type_name","form-control",$ddl_food_type);
								?>
							</div>
							<div class="col-sm-2">
							    <input type="text" name="txt_keyword" id="txt_keyword" class="form-control" value="<?php echo $txt_keyword; ?>" placeholder="What kind of food do you like?" autofocus/>
							</div>
							<div class="col-sm-1">
								<input type="submit" name="btn_food_search" id="btn_food_search" class="btn btn-success" value="Search"/>
							</div>
						</div>
						<?php if(count($search_result_data)>0){ ?>
						</div>
						<?php } ?>
						</div>
					<?php
						if(count($search_result_data)>0){
								echo "<h4>Search Results</h4>";
								foreach ($search_result_data as $row){
									echo "<table style=\"margin:6px;\">";
									echo "<tr><td rowspan=6><img src=\"images/food_thumb.png\" style=\"width:50px;\"/></td></tr>";
									echo "<tr><td>Name: <b>".$row["food_name"]."</b></td></tr>";
									echo "<tr><td>Rate: <b>".$row["food_rate"]."</b></td></tr>";
									echo "<tr><td>Category: <b>".$row["food_type_name"]."</b></td></tr>";
									echo "<tr><td>Canteen Available: <b>".$row["can_name"]."</b></td></tr>";
									if($row["food_canteen_id"]==$user_canteen){
										//Edit Delete
										echo "<tr><td><b><a href=\"food.php?edit_food=".$row["food_id"]."\">Edit</a>
										&nbsp;&nbsp;
										<a href=\"food.php?del_food=".$row["food_id"]."\">Delete</a></b></td></tr>";
									}
									echo "</tr></table>";
								}

							echo "<div class=\"container\" style=\"width:100%;\"><ul class=\"pager\">";
							$page_range=page_range(3,$search_result[1],$page);
							for ($i=$page_range[0]; $i<=$page_range[1]; $i++) {
		        				if ($i==$page){
		        					echo "<li><a class=\"selected-pager\" href=\"#\">".$i."</a></li>";
		        				}
		        				else{
		        					$search_info_arr=array($i,$ddl_canteen,$ddl_food_type,$txt_keyword);
		        					$search_info_arr_str=base64_encode(serialize($search_info_arr));
		        					echo "<li><a href=\"search_food.php?request=start&search_info=".$search_info_arr_str."\">".$i."</a></li>";
		        				}
		            		}
		            		echo "</ul></div>";
	            		}
	            		else{
	            			if($query_send==true){
	            				echo "<div style=\"width:100%; padding:10px;\"><h3>No Results!</h3></div>";
	            			}
							else{
								echo "<div style=\"width:100%; padding:20px;\"><div class=\"col-sm-12\" style=\"text-align:center;\"><img src=\"images/search.png\" style=\"height:200px;\"/><br/>Powerd By Canteen Network.</div></div>";
							}
						}
		  			?>
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