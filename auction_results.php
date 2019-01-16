<?php

	include('lib/common.php');
	// written by GTusername4
	
	if (!isset($_SESSION['UserName'])) {
		header('Location: login.php');
		exit();
	}

	//Update database, calculate the winners of auctions that have ended;
    // N (username, itemid, maxprice) from bids
	$query = "update items i inner join (select UserName,ItemID,Price from bids ".
        "where (ItemID, Price) in (select ItemID, max(price) as Price from bids group by ItemID)) N ".
        "set i.Winner = N.UserName, i.SalePrice=N.Price where i.ItemID = N.ItemID and Price>=i.MinPrice and i.Winner is NULL and AuctionEnd<NOW()";
    $result = mysqli_query($db, $query);

	
	// Find all the items with auction end early than the current time from Items table
	$NowTime = date('Y-m-d H:i:s',time());
	$queryfinish = "select ItemID, ItemName,SalePrice,Winner, AuctionEnd From Items where AuctionEnd < now() order by AuctionEnd desc";
	$resultfinish = mysqli_query($db, $queryfinish);
	
    include('lib/show_queries.php');

	
//  if ( !is_bool($result) && (mysqli_num_rows($result) > 0) ) {
//      
//  } else {
//      array_push($error_msg,  "Query ERROR: Failed to get User profile...<br>" . __FILE__ ." line:". __LINE__ );
//  }
?>

<?php include("lib/header.php"); ?>
	<style>
		table, th, td {
			text-align: center  !important;
			vertical-align: middle;
			border: 1px solid black;
		}
	</style>
<title>GTBay Auction</title>
</head>

<body>
		<div id="main_container">
    		<?php include("lib/menu.php"); ?>
			
		    <div class="center_content">
			<div class="subtitle">Auction Results</div>
		    	<table class="table"> 
		    	<tr>
					<th  style='text-align: center;'>ItemID</th>
					<th  style='text-align: center;'>ItemName</th>
					<th  style='text-align: center;'>SalePrice</th>
					<th  style='text-align: center;'>Winner</th>
					<th  style='text-align: center;'>Auction Ended Time</th>
				</tr>
		    	<?php
		    		
		    		while($row = mysqli_fetch_array($resultfinish, MYSQLI_ASSOC)){
		    			echo '<tr>';
		    			echo '<td>'.$row['ItemID'].'</td><td>'.$row['ItemName'].'</td><td>'.$row['SalePrice'].'</td><td>'.$row['Winner'].'</td><td>'.$row['AuctionEnd'].'</td>';
		    			echo '</tr>';
		    		}
		    		
		    		 ?>
        		</table>

                <?php include("lib/error.php"); ?>
                    
				<div class="clear"></div> 		
			</div>    


				 
		</div>
</body>
</html>