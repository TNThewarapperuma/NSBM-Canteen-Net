<?php
		$Database = "canteen_system";
		$Hostname = "localhost";
		$Username = "root";
		$Password = "";
		
		try{
			global $conn;
			$conn = new PDO("mysql:host=$Hostname;dbname=$Database", $Username,$Password);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$conn->exec ("SET NAMES utf8");
		}
		catch(PDOException $exp)
		{
			echo "Error! ".$exp->getMessage();
		}
?>