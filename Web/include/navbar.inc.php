<?php
function navbar($user_name, $user_type, $active_tab){
$tab_list = array("home"=>"", "my_account"=>"", "food_search"=>"", "staff"=>"","food"=>"","admin"=>"");
foreach($tab_list as $key => $val) {
    if($key==$active_tab){
    	$tab_list[$key]="class=\"navbar-active\"";
    }
}
?>
<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
  <div class="container-fluid" id="navfluid">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigationbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="home.php">NSBM Canteen Net</a>
    </div>
    <div class="collapse navbar-collapse" id="navigationbar">
	    <ul class="nav navbar-nav">
	      	<li id="nav_home"><a href="home.php" <?php echo $tab_list["home"]; ?>>Home</a></li>
	      	<li id="nav_my_account"><a href="myaccount.php" <?php echo $tab_list["my_account"]; ?>>My Account</a></li>
	      	<li id="nav_search"><a href="search_food.php" <?php echo $tab_list["food_search"]; ?>>Foods&Canteens</a></li>
	      	<?php if($user_type[0]==1){ ?>
			    	<li id="nav_food"><a href="food.php" <?php echo $tab_list["food"]; ?>>Food</a></li>
			    	<li id="nav_admin"><a href="admin.php" <?php echo $tab_list["admin"]; ?>>Admin</a></li>
		    <?php } ?>
	    </ul>
	    <ul class="nav navbar-nav navbar-right">
	      	<li><a href="index.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
	    </ul>
	</div>
  </div>
</nav>
<div style="padding-top: 51px; width: 100%;"></div>
<?php
}
?>