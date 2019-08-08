<?php
//include files
	require("include/dbcon.inc.php");
	require("include/navbar.inc.php");
	require("include/combo.inc.php");
	//require("include/password.inc.php");
	require("include/spilit_result.inc.php");
//member add & edit variables
*/
	$txt_user_type="";
	$txt_user_fname="";
	$txt_cus_dob="";
	$txt_cus_std_id="";

	$btn_name="btn_add_member";
	$btn_val="Submit";

	$txt_username_err="";
	$txt_cus_std_id_err="";
	$hid_search="";
	$hid_page="";

//add new member and update members
	if(isset($_POST["btn_add_member"]){
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
		if(strpos($txt_member_username, "@")==true){
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
		}

		//if all vaildations are corrct and the action is insert,- inset data
		if(($err==0)&&(isset($_POST["btn_add_member"]))){
			//insert data
				$sql_quary = "INSERT INTO `tbl_user` (`user_sn`, `user_name`, `user_fname`, `user_lname`, `user_password`, `user_active`, `user_type`,`user_last_login`) VALUES (NULL, :user_name, :user_fname, :user_lname, :user_password, :user_active, 0, NULL);INSERT INTO `tbl_member` (`member_sn`, `member_id`, `member_user_sn`, `member_type`, `member_email`, `member_address`, `member_phone`, `member_gurentee`, `member_gurentee_address`, `member_occupation`, `member_nic`, `member_open_login`, `member_insert_date`) VALUES (NULL, :member_id, LAST_INSERT_ID(), :member_type, :member_email, :member_address, :member_phone, :member_gurentee, :member_gurentee_address, :member_occupation, :member_nic, :member_open_login, NOW());";
				$sql = $conn->prepare($sql_quary);
				$sql->bindparam(':user_name',$txt_member_username);
				$sql->bindparam(':user_fname',$txt_member_fname);
				$sql->bindparam(':user_lname',$txt_member_lname);
				$sql->bindparam(':user_password',$member_password);
				$sql->bindparam(':user_active',$opt_member_active);
				$sql->bindparam(':member_id',$txt_member_id);
				$sql->bindparam(':member_type',$ddl_member_type);
				$sql->bindparam(':member_email',$txt_member_email);
				$sql->bindparam(':member_address',$txt_member_address);
				$sql->bindparam(':member_phone',$txt_member_phone);
				$sql->bindparam(':member_gurentee',$txt_member_gurentee);
				$sql->bindparam(':member_gurentee_address',$txt_member_gurentee_address);
				$sql->bindparam(':member_occupation',$txt_member_occupation);
				$sql->bindparam(':member_nic',$txt_member_nic);
				$sql->bindparam(':member_open_login',$chk_open_login);
				$sql->execute();

				$msg="Your New Smart Library login information. Username- ".$txt_member_username." Password- ".$password_no_enc;
				$server="$_SERVER[HTTP_HOST]";

				if($server=="localhost"){
					$message=array("IMPORTANT!","alert alert-warning","Member Account Created Sucessfully. But can't send emails using localhost server. <br/><b>Inform member about username and password manually</b><br/>Username- ".$txt_member_username."<br/>Password- ".$password_no_enc);
				}
				else{
					$mail_sent=email($_SESSION["basic_settings"]["lib_name"],$msg, $txt_member_email);

					if($mail_sent=="1"){
						$message=array("Success!","alert alert-success","Library mebmber account sucessfully created.login infomation were sent to member's email.");
					}
					else{
						$message=array("Sorry!","alert alert-warning","Member Account Created Sucessfully. But can't send emails by this server.");
					}
				}

				$request="welcome";
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  	<title>Members - Smart Library</title>
  	<meta charset="utf-8">
  	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/<?php echo $_SESSION["basic_settings"]["lib_theme"] ?>">
	<script src="js/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
</head>
<body>
	<?php navbar($user_name,$user_type,"members"); ?>
		<div class="container-fuled" style="overflow: hidden;">
			<div class="row">
				<div class="col-sm-2">
					<ul class="v-nav">
						<li class="v-nav-li"><a id="welcome" href="members.php?request=welcome&navid=welcome">Start Members</a></li>
				  		<li class="v-nav-li"><a id="member-check" href="members.php?request=member-check&navid=member-check">Check and Edit Members</a></li>
				  		<li class="v-nav-li"><a id="member-add" href="members.php?request=member-add&navid=member-add">Add New Member</a></li>
				  		<li class="v-nav-li"><a id="member-bc" href="members.php?request=member-bc&navid=member-bc&bc_req=cancel">Member Cards</a></li>
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
						<h3>Start mange members</h3>
						<p>You can Add new members to system, Genarate Member Cards, Send new passwords to members who lost there account passwords, Check members information and edit them from this members magemant panel.</p>
						<br/>
						<h3>Members Statistics</h3>
				<?php
						//all registerd members
						$sql_quary_arr=array();
						array_push($sql_quary_arr,"SELECT \"Total members of the library\", count(`user_sn`) AS `all_members` FROM `tbl_user` INNER JOIN `tbl_member` ON `tbl_user`.`user_sn`=`tbl_member`.`member_user_sn`;");

						//all active members
						array_push($sql_quary_arr,"SELECT \"Total active members\", count(`user_sn`) AS `active_members` FROM `tbl_user` INNER JOIN `tbl_member` ON `tbl_user`.`user_sn`=`tbl_member`.`member_user_sn` WHERE `user_active`=1;");

						//all members who can use online accounts
						array_push($sql_quary_arr,"SELECT \"Number of members who can use online accounts\", count(`user_sn`) AS `online_members` FROM `tbl_user` INNER JOIN `tbl_member` ON `tbl_user`.`user_sn`=`tbl_member`.`member_user_sn` WHERE `member_open_login`=1 AND `user_active`=1;");

						//all members registerd this week
						array_push($sql_quary_arr,"SELECT \"Registerd members of this week\", count(`user_sn`) AS `this_week_members` FROM `tbl_user` INNER JOIN `tbl_member` ON `tbl_user`.`user_sn`=`tbl_member`.`member_user_sn` WHERE YEARWEEK(`member_insert_date`)=YEARWEEK(NOW());");

						//online account useage this week
						array_push($sql_quary_arr,"SELECT \"Online account useage of this week\", count(`user_sn`) AS `this_week_online` FROM `tbl_user` INNER JOIN `tbl_member` ON `tbl_user`.`user_sn`=`tbl_member`.`member_user_sn` WHERE YEARWEEK(`user_last_login`)=YEARWEEK(NOW());");

						echo "<table class=\"table-no-css\" style=\"width:auto;\">";
						for($x=0; $x<count($sql_quary_arr); $x++){
							$sql = $conn->prepare($sql_quary_arr[$x]);
							$sql->execute();
							$numRows=$sql->fetchAll();
							if(count($numRows)>0){
								foreach ($numRows as $row) {
									echo "<tr class=\"tr-no-css\"><th class=\"td-no-css\" style=\"color:black;\">".$row[0]."</th><th class=\"th-no-css\">:</td><td class=\"td-no-css\">".$row[1]."</td></tr>";
								}
							}
						}
						
						//number of members on member type
						$sql_quary="SELECT `member_type_name`, count(`user_sn`) AS `count` FROM `tbl_user` INNER JOIN `tbl_member` ON `tbl_user`.`user_sn`=`tbl_member`.`member_user_sn` LEFT JOIN `tbl_member_type` ON `tbl_member`.`member_type`=`tbl_member_type`.`member_type_sn` GROUP BY `member_type`;";
						$sql = $conn->prepare($sql_quary);
						$sql->execute();
						$numRows=$sql->fetchAll();
						if(count($numRows)>0){
							foreach ($numRows as $row) {
								echo "<tr class=\"tr-no-css\"><th class=\"td-no-css\" style=\"color:black;\">Total <u>".$row["member_type_name"]."</u> Members</th><th class=\"th-no-css\">:</td><td class=\"td-no-css\">".$row["count"]."</td></tr>";
							}
						}
						echo "</table>";
				?>
				<?php
					}
					elseif(($request=="member-add")||($request=="member-edit")){
						if($request=="member-add"){ 
				?>
							<form role="form" action="members.php?request=member-add&navid=member-add" method="post" class="form-horizontal">
						<?php	}else{  ?>
							<form role="form" action="members.php?request=member-edit&navid=member-check" method="post" class="form-horizontal">
						<?php } ?>
							<div class="form-group">
								<label class="control-label col-sm-2" for="ddl_member_type">Member Type:</label>
								<div class="col-sm-6">
									<?php
										$c_sql="SELECT * FROM `tbl_member_type`;";
										create_combo($c_sql,"ddl_member_type","member_type_sn","member_type_name","form-control",$ddl_member_type);
									?>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2" for="txt_member_id">Member ID:</label>
								<div class="col-sm-6">
								    <input type="text" name="txt_member_id" id="txt_member_id" class="form-control" required="true" maxlength="10" value="<?php echo $txt_member_id; ?>"/>
								    <div class="form-err">
								    	<?php if($txt_member_id_err!=""){ echo $txt_member_id_err; } ?>
								    </div>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2" for="txt_member_username">Username:</label>
								<div class="col-sm-6">
								    <input type="text" name="txt_member_username" id="txt_member_username" class="form-control" required="true" maxlength="50" value="<?php echo $txt_member_username; ?>"/>
								    <div class="form-err">
								    	<?php if($txt_member_username_err!=""){ echo $txt_member_username_err; } ?>
								    </div>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2" for="txt_member_fname">First Name:</label>
								<div class="col-sm-6">
								    <input type="text" name="txt_member_fname" id="txt_member_fname" class="form-control" required="true" maxlength="50" value="<?php echo $txt_member_fname; ?>"/>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2" for="txt_member_lname">Last Name:</label>
								<div class="col-sm-6">
								    <input type="text" name="txt_member_lname" id="txt_member_lname" class="form-control"  maxlength="50" value="<?php echo $txt_member_fname; ?>"/>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2" for="txt_member_nic">NIC Number:</label>
								<div class="col-sm-6">
								    <input type="text" name="txt_member_nic" id="txt_member_nic" class="form-control" maxlength="12" value="<?php echo $txt_member_nic; ?>"/>
								    <div class="form-err">
								    	<?php if($txt_member_nic_err!=""){ echo $txt_member_nic_err; } ?>
								    </div>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2" for="txt_member_email">E-mail:</label>
								<div class="col-sm-6">
								    <input type="email" name="txt_member_email" id="txt_member_email" class="form-control" required="true" maxlength="100" value="<?php echo $txt_member_email; ?>"/>
								    <div class="form-err">
								    	<?php if($txt_member_email_err!=""){ echo $txt_member_email_err; } ?>
								    </div>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2" for="txt_member_phone">Phone:</label>
								<div class="col-sm-6">
								    <input type="text" name="txt_member_phone" id="txt_member_phone" class="form-control" required="true" maxlength="10" value="<?php echo $txt_member_phone; ?>"/>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2" for="txt_member_address">Address:</label>
								<div class="col-sm-6">
								    <input type="textarea" name="txt_member_address" id="txt_member_address" class="form-control" required="true" maxlength="256" value="<?php echo $txt_member_address; ?>"/>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2" for="txt_member_occupation">Occupation:</label>
								<div class="col-sm-6">
								    <input type="text" name="txt_member_occupation" id="txt_member_occupation" class="form-control" maxlength="50" value="<?php echo $txt_member_occupation; ?>"/>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2" for="txt_member_gurentee">Gurentee:</label>
								<div class="col-sm-6">
								    <input type="text" name="txt_member_gurentee" id="txt_member_gurentee" class="form-control" maxlength="50" value="<?php echo $txt_member_gurentee; ?>"/>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2" for="txt_member_gurentee_address">Gurentee Address:</label>
								<div class="col-sm-6">
								    <input type="textarea" name="txt_member_gurentee_address" id="txt_member_gurentee_address" class="form-control" maxlength="256" value="<?php echo $txt_member_gurentee_address; ?>"/>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2" for="chk_open_login">Open Login:</label>
								<div class="col-sm-6">
								    <input type="checkbox" <?php if($chk_open_login==1){echo "checked";} ?> name="chk_open_login" id="chk_open_login"> *if this checked member can access to member area
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2" for="opt_member_active">Account Status:</label>
								<div class="col-sm-6">
									<label class="radio-inline"><input type="radio" name="opt_member_active" <?php if($opt_member_active==1){ echo "checked";} ?> value="1">Active</label>
									<label class="radio-inline"><input type="radio" name="opt_member_active" <?php if($opt_member_active==0){echo "checked";} ?>  value="0">Deactive</label>
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-offset-2 col-sm-6">
								    <input type="submit" name="<?php echo $btn_name; ?>" id="<?php echo $btn_name; ?>" class="btn btn-success" value="<?php echo $btn_val; ?>"/>
								    <input type="reset" class="btn btn-warning" value="Reset" style="float: right;"/>
								    <?php if($request=="member-edit"){ ?>
										<a href="members.php?request=member-check&navid=member-check&search=<?php echo $hid_search; ?>&page=<?php echo $hid_page; ?>"><input type="button" class="btn btn-danger" value="Cancel" style="float: right; margin-right: 10px;"/></a>
									<?php } ?>
								</div>
							</div>
							<?php if($request=="member-edit"){ ?>
								<input type="hidden" name="hid_user_sn" id="hid_user_sn" value="<?php echo $user_sn; ?>">
								<input type="hidden" name="hid_search" id="hid_search" value="<?php echo $hid_search; ?>">
								<input type="hidden" name="hid_page" id="hid_page" value="<?php echo $hid_page; ?>">
							<?php } ?>
						</form>
				<?php
					}
					elseif($request=="member-check"){
				?>
					<form role="form" action="members.php?request=member-check&navid=member-check" method="post" class="form-horizontal">
						<div class="container search-box">
						<div class="form-group">
							<label class="control-label col-sm-3" for="txt_member_search">Search By ID OR Name:</label>
							<div class="col-sm-4">
							    <input type="text" name="txt_member_search" id="txt_member_search" class="form-control" value="<?php echo $txt_member_search; ?>" autofocus/>
							</div>
							<div class="col-sm-1">
								<input type="submit" name="btn_search_member" id="btn_search_member" class="btn btn-danger" value="Search"/>
							</div>
						</div>
						</div>
					<?php
						if(count($search_result_data)>0){
							echo "<h4>Search Results</h4>";
							echo "<table style=\"width:100%;\"><tr><th>Member ID</th><th>Member Type</th><th>Name</th><th>Phone</th><th>Address</th><th>Open Login</th><th>Acive</th><th>Action</th></tr>";
							foreach ($search_result_data as $row){
								$member_open_login="No";
								$user_active="No";
								if($row["member_open_login"]==1){
									$member_open_login="Yes";
								}
								if($row["user_active"]==1){
									$user_active="Yes";
								}
								echo "<tr><td>".$row["member_id"]."</td><td>".$row["member_type_name"]."</td><td>".$row["user_lname"].", ".$row["user_fname"]."</td><td>".$row["member_phone"]."</td><td>".$row["member_address"]."</td><td>".$member_open_login."</td><td>".$user_active."</td><td><a href=\"members.php?request=member-info&navid=member-check&member-info=".$row["user_sn"]."&search=".$txt_member_search."&page=".$page."\"><span class=\"glyphicon glyphicon-eye-open\"></span> More Info</a>&emsp;<a href=\"members.php?request=member-edit&navid=member-check&member-edit=".$row["user_sn"]."&search=".$txt_member_search."&page=".$page."\"><span class=\"glyphicon glyphicon-pencil\"></span> Edit</a></td></tr>";
							}
							echo "</table>";
						}

						echo "<div class=\"container\" style=\"width:100%;\"><ul class=\"pager\">";
						$page_range=page_range($_SESSION["basic_settings"]["lib_search_page_links"],$search_result[1],$page);
						for ($i=$page_range[0]; $i<=$page_range[1]; $i++) {
        					if ($i==$page){
        						echo "<li><a class=\"selected-pager\" href=\"#\">".$i."</a></li>";
        					}
        					else{
        						echo "<li><a href=\"members.php?request=member-check&navid=member-check&search=".$txt_member_search."&page=".$i."\">".$i."</a></li>";
        					}
            			}
            			echo "</ul></div>";
		  			?>
		  			</form>
				<?php
					}
					elseif($request=="member-bc"){
				?>
						<form role="form" action="members.php?request=member-bc&navid=member-bc" method="post" class="form-horizontal">
							<h3>Member ID Cards</h3>
						<?php
							if($bc_req=="bc_show"){
						?>
								<div class="form-group">
									<div class="col-sm-4">
										<a href="members.php?request=member-bc&navid=member-bc&bc_req=cancel"><input type="button" class="btn btn-warning" value="Start New" style="margin-left: 0px;" /></a>
										<a href="members.php?request=member-bc&navid=member-bc&bc_req=bc_out_info"><input type="button" class="btn btn-info" value="Back" /></a>
									</div>
								</div>
							<?php
								echo $all_barcode_code;
							}
							elseif($bc_req=="bc_add"){
								$txt_barcode_range_start="";
								$txt_barcode_range_end="";
								$txt_barcode_item="";
								if($_SESSION["barcode_data"]["search_type"]=="range"){
						?>
							<div class="form-group">
								<label class="control-label col-sm-2">Range Of Member Id:</label>
								<div class="col-sm-3">
								    <input type="text" name="txt_barcode_range_start" id="txt_barcode_range_start" class="form-control" maxlength="50" value="<?php echo $_SESSION["barcode_data"]["range_start"]; ?>" placeholder="Next"/>
								</div>
								<div class="col-sm-3">
								    <input type="text" name="txt_barcode_range_end" id="txt_barcode_range_end" class="form-control" maxlength="50" value="<?php echo $_SESSION["barcode_data"]["range_end"]; ?>" placeholder="End"/>
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-offset-2 col-sm-6" class="form-err">
								    <?php if($range_err!=""){ echo $range_err; } ?>
								</div>
							</div>
						<?php
								}
								elseif($_SESSION["barcode_data"]["search_type"]=="item"){
						?>
							<div class="form-group">
								<label class="control-label col-sm-2" for="txt_barcode_item">Member Id:</label>
								<div class="col-sm-5"">
									<input type="text" name="txt_barcode_item" id="txt_barcode_item" class="form-control" maxlength="50" autofocus/>
									<div class="form-err">
								    	<?php if($txt_barcode_item_err!=""){ echo $txt_barcode_item_err; } ?>
								    </div>
								</div>
								<div class="col-sm-1">
									<input type="submit" name="btn_barcode_add" id="btn_barcode_add" class="btn btn-info" value="Add"/>
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-offset-2 col-sm-6">
									<?php
										if(count($_SESSION["barcode_data"]["item_list"])>0){
											for($x=0; $x<count($_SESSION["barcode_data"]["item_list"]); $x++){
												if(($x%5==0)&&($x!=0)){
													echo "<br/><br/>";
												}
												$y=$_SESSION["barcode_data"]["item_list"][$x];
												echo "<div class=\"label label-danger label-tags\">".$y."<a href=\"members.php?request=member-bc&navid=member-bc&bc_del=".$x."\"><span class=\"glyphicon glyphicon-remove-sign\"></span></a></div>";
											}
										}
									?>
								</div>
							</div>
							
						<?php
								}
						?>
							<div class="form-group">
								<div class="col-sm-offset-2 col-sm-6">
									<input type="submit" name="btn_barcode_list_submit" id="btn_barcode_list_submit" class="btn btn-info" value="Next"/>
									<a href="members.php?request=member-bc&navid=member-bc&bc_req=cancel"><input type="button" class="btn btn-warning" value="Cancel" style="float: right; margin-left: 10px;" /></a>
									<a href="members.php?request=member-bc&navid=member-bc"><input type="button" class="btn btn-info" value="Back" style="float: right;" /></a>
								</div>
							</div>
						<?php
							}
							elseif($bc_req=="bc_out_info") {
						?>
							<div class="form-group">
								<label class="control-label col-sm-2" for="ddl_barcode_out_type">Report Format: </label>
								<div class="col-sm-6">
								    <select name="ddl_barcode_out_type" id="ddl_barcode_out_type" class="form-control">
										<option value="show">Show Barcods</option>
										<option value="download">Download PDF</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-offset-2 col-sm-6">
									<input type="submit" name="btn_barcode_out" id="btn_barcode_out" class="btn btn-info" value="Get Member Cards"/>
									<a href="members.php?request=member-bc&navid=member-bc&bc_req=cancel"><input type="button" class="btn btn-warning" value="Start New" style="float: right; margin-left: 10px;" /></a>
									<a href="members.php?request=member-bc&navid=member-bc"><input type="button" class="btn btn-info" value="Back" style="float: right;" /></a>
								</div>
							</div>
						<?php
							}
							else{
						?>
							<div class="form-group">
								<label class="control-label col-sm-2" for="ddl_barcode_search_type">Search Method:</label>
								<div class="col-sm-6">
								    <select name="ddl_barcode_search_type" id="ddl_barcode_search_type" class="form-control">
								    	<?php 
								    		$x=null;
								    		if($_SESSION["barcode_data"]["search_type"]=="item"){
								    			echo "<option value=\"range\">By range</option><option value=\"item\" selected>By Item</option>";
								    		}
								    		else{
								    			echo "<option value=\"range\" selected>By range</option><option value=\"item\">By Item</option>";
								    		}
								    	?>
										
									</select>
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-offset-2 col-sm-6">
									<input type="submit" name="btn_barcode_search" id="btn_barcode_search" class="btn btn-info" value="Next"/>
								</div>
							</div>
						<?php
							}
						?>
						</form>
				<?php
					}
					elseif($request=="member-info"){
						if(count($search_result_data)>0){
							$txt_page="";
							if(isset($_GET["search"])){
								$txt_member_search=$_GET["search"];
							}
							if(isset($_GET["page"])){
								$txt_page=$_GET["page"];
							}
							echo "<div style=\"width:100%;\"><a href=\"members.php?request=member-check&navid=member-check&search=".$txt_member_search."&page=".$txt_page."\" class=\"btn btn-warning\" style=\"width: 100%; border-radius: 0px;\">Back To Search Results</a></div>";
							foreach ($search_result_data as $row){
								$member_open_login="No";
								$user_active="No";
								if($row["member_open_login"]==1){
									$member_open_login="Yes";
								}
								if($row["user_active"]==1){
									$user_active="Yes";
								}
								echo "<h3>Infomation Of Member</b> ".$row["user_fname"]." ".$row["user_lname"]."</h3>";
								echo "<b>Member ID</b> :&emsp;".$row["member_id"];
								echo "<br/><b>First Name</b> :&emsp;".$row["user_fname"];
								echo "<br/><b>Member ID</b> :&emsp;".$row["user_lname"];
								echo "<br/><b>Member Type</b> :&emsp;".$row["member_type_name"];
								echo "<br/><b>Email</b> :&emsp;".$row["member_email"];
								echo "<br/><b>Address</b> :&emsp;".$row["member_address"];
								echo "<br/><b>Telephone</b> :&emsp;".$row["member_phone"];
								echo "<br/><b>Gurentee</b> :&emsp;".$row["member_gurentee"];
								echo "<br/><b>Gurentees Address</b> :&emsp;".$row["member_gurentee_address"];
								echo "<br/><b>Occupation</b> :&emsp;".$row["member_occupation"];
								echo "<br/><b>Member NIC</b> :&emsp;".$row["member_nic"];
								echo "<br/><b>Open Login Availability</b> :&emsp;".$member_open_login;
								echo "<br/><b>Account Active</b> :&emsp;".$user_active;
								echo "<br/><b>Registerd Date</b> :&emsp;".$row["member_insert_date"];
							}
						}
						else{
							header("Location:index.php");
						}
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