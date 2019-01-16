<?php
	include('lib/common.php');
	// written by GTusername2
	
	if (!isset($_SESSION['UserName'])) {
	    header('Location: login.php');
	    exit();
	}
	$username=$_GET['UserName'];
	$itemid=$_GET['ItemID'];
	$sql="delete from Ratings where UserName = ".$username." and ItemID=".$itemid;
	$result = mysqli_query($db, $sql);
	header("Location: view_ratings.php?ItemID=".$_GET['ItemID']);
?>