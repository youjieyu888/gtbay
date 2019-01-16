<?php

	include('lib/common.php');
	// written by GTusername2
	
	if (!isset($_SESSION['UserName'])) {
	    header('Location: login.php');
	    exit();
	}
	
	//get info from the last page, $id 's id is colname, $id is value
	$id = mysqli_real_escape_string($db, $_REQUEST['ItemID']);
	
	$query = "SELECT * " .
	    "FROM Items " .
	    "WHERE ItemID = $id";
	
	$result = mysqli_query($db, $query);
	include('lib/show_queries.php');
	
	if (!is_bool($result) && (mysqli_num_rows($result) > 0) ) {
	    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
	    $count = mysqli_num_rows($result);
	
	    $ItemName = $row['ItemName'] ;
	    $Description = $row['Description'] ;
	    $CategoryName =$row['CategoryName'];
	    $Condition=$row['Condition'];
	    $Returnable=$row['Returnable'];
	    $GetNowPrice=$row['GetNowPrice'];
	    $AuctionEnd=$row['AuctionEnd'];
	} else {
	    array_push($error_msg,  "SELECT ERROR: itemID: " . $id ."<br>".  __FILE__ ." line:". __LINE__ );
	}
	
	$query2 = "SELECT * FROM Bids WHERE ItemID = '$id' " .
	    " ORDER BY Price DESC, Time DESC LIMIT 4";
	    
	$result2 = mysqli_query($db, $query2);
	include('lib/show_queries.php');
	
	if (!is_bool($result2) && (mysqli_num_rows($result2) > 0) ) {
	    $row2 = mysqli_fetch_array($result2, MYSQLI_ASSOC);
	    $count2 = mysqli_num_rows($result2);
	
	    $Price = $row2['Price'] ;
	}

    $query3 = "SELECT IFNULL(Price,0) as Price FROM Bids WHERE ItemID = '$id' " .
          " ORDER BY Price DESC, Time DESC LIMIT 1";

    $result3 = mysqli_query($db, $query3);
    if (!is_bool($result3) && (mysqli_num_rows($result3) > 0) ) {
        $row3 = mysqli_fetch_array($result3, MYSQLI_ASSOC);
        $MaxPrice = $row3['Price']+1;
    }elseif(!is_bool($result3) && (mysqli_num_rows($result3) == 0) ) {
    $MaxPrice = $row['StartBid'] ;}


    if (isset($_POST['bid']) && isset($_POST['Price']) )   {
    	if (!empty($row['Winner'])){
    		echo "<script>alert('This item already has winner.')</script>";
    	}
    	else{
	        $id = mysqli_real_escape_string($db, $_REQUEST['ItemID']);
	        $query3 = "SELECT IFNULL(Price,0) as Price FROM Bids WHERE ItemID = '$id' " .
	            " ORDER BY Price DESC, Time DESC LIMIT 1";
	
	        $result3 = mysqli_query($db, $query3);
	        include('lib/show_queries.php');
	
	        if (!is_bool($result3) && (mysqli_num_rows($result3) > 0) ) {
	            $row3 = mysqli_fetch_array($result3, MYSQLI_ASSOC);
	            $MaxPrice = $row3['Price']+1;
                if($_POST['Price']<$MaxPrice){
                    echo "<script>alert('Your bid must be at least $1 higher than the current highest bid.')</script>";
                }elseif($GetNowPrice!=null && $_POST['Price']>=$GetNowPrice){
                    echo "<script>alert('You can click Get It Now!')</script>";
                }else{
                    $query = "INSERT INTO Bids (ItemID, UserName,Price, `Time`) " .
                        "VALUES ($id, '{$_SESSION['UserName']}', '{$_POST['Price']}',NOW())";
                    $queryID = mysqli_query($db, $query);
                }
	        }elseif(!is_bool($result3) && (mysqli_num_rows($result3) == 0) ) {
                $MaxPrice = $row['StartBid'] ;
                if($_POST['Price']<$MaxPrice){
                    echo "<script>alert('Your bid must not be lower than the minimum bid.')</script>";
                }elseif($GetNowPrice!=null && $_POST['Price']>$GetNowPrice) {
                    echo "<script>alert('You can click Get It Now!')</script>";
                }else{
                    $query = "INSERT INTO Bids (ItemID, UserName,Price, `Time`) " .
                        "VALUES ($id, '{$_SESSION['UserName']}', '{$_POST['Price']}',NOW())";
                    $queryID = mysqli_query($db, $query);
                }
            }
            header(REFRESH_TIME );
        }
    }


    if (isset($_POST['cancel'])){
        header(REFRESH_TIME . 'url=search_results.php');
    }
    if (isset($_GET['get'])){
        $query = "UPDATE Items SET Winner='{$_SESSION['UserName']}', AuctionEnd=NOW(),SalePrice=GetNowPrice ".
        "WHERE ItemID=$id";

        $queryID = mysqli_query($db, $query);

        header(REFRESH_TIME . 'url=search_item.php');
        echo "<script>alert('You got this item!')</script>";
    }




?>
<script language="javascript" type="text/javascript">
    function windowClose() {
        window.open('','_parent','');
        window.close();
    }
</script>

<?php include("lib/header.php"); ?>
<title>GTBay Item For Sale</title>
<style>
	table, th, td {
		text-align: left  !important;
		vertical-align: middle;
		padding-left: 5px;
	}

	.long {
		width: 50%;
	}
	.item_label {
		width: 20%;
		padding-right: 20px;
	}
	table {
		border-top:2px solid black;
		border-bottom:2px solid black;
	}

</style>
</head>

<body>
<div id="main_container">
    <?php include("lib/menu.php"); ?>

    <div class="center_content">
        <div class="center_left">
            <div class="title_name">GTBay Item For Sale</div>
            <div class="features">

                <div class="profile_section">
                    <div class="subtitle">Item Information</div>
                    <form name="iteminfo" action="Bid.php">
                    	<input type="hidden" name="ItemID" value=<?php echo $id;?>>
                        <table style="width:180%">
                            <tr>
                                <td class="item_label">Item ID</td>
                                <td class="long"><?php print $id; ?></td>
                                <?php print "<td><a href='view_ratings.php?ItemID=$id'>View Ratings</a></td>";?>
                            </tr>
                            <tr>
                                <td class="item_label">Item Name</td>
                                <td class="long"><?php print $ItemName; ?></td>
                            </tr>
                            <tr>
                                <td class="item_label">Description</td>
                                <td class="long"><?php print $Description; ?></td>
                                <?php if($_SESSION['UserName']==$row['UserName']){
                                print "<td><a href='edit_description.php?ItemID=$id'>Edit Description</a></td>";}
                                ?>
                            </tr>
                            <tr>
                                <td class="item_label">Category</td>
                                <td class="long"><?php print $CategoryName; ?></td>
                            </tr>
                            <tr>
                                <td class="item_label">Condition</td>
                                <td class="long"><?php print $Condition; ?></td>
                            </tr>
                            <tr>
                                <td class="item_label">Returns Accepted?</td>
                                <td><?php if($row['Returnable']==1){print "Yes";}
                                else{print "No";}?></td>
                            </tr>
                            <tr>

                                <td class="item_label">Get It Now price</td>
                                <?php if($row['GetNowPrice']!=null){
                                    echo "<td class='long'>".'$'.$GetNowPrice."</td>";
                                    if($_SESSION['UserName']!=$row['UserName']){
                                        echo "<td><button style='margin-left:0' class='my_button' type=\"submit\" name=\"get\">Get It Now!</button></td>";
                                    }else{
                                        echo "<td><button style='margin-left:0' class='my_button_grey' >Get It Now!</button></td>";
                                    }}else {echo "<td>"."N/A"."</td>";}
                                ?>
                            </tr>
                            <tr>
                                <td class="item_label">Auction Ends</td>
                                <td class='long'><?php print $AuctionEnd; ?></td>
                            </tr>
                        </table>
                    </form>
                    <div class="subtitle">Latest bids</div>
                    <!--<form name="latestbids" action="<?php echo "Bid.php?ItemID=".$id; ?>">-->
                    <table>
                        <tr>
                            <td class='heading'>Bid Amount</td>
                            <td class='heading'>Time of Bid</td>
                            <td class='heading'>Username</td>
                        </tr>
                        <?php
                        $query2 = "SELECT * FROM Bids WHERE ItemID = '$id' " .
                            " ORDER BY Price DESC, Time DESC LIMIT 4";

                        $result2 = mysqli_query($db, $query2);
                        include('lib/show_queries.php');

                        if (isset($result2)) {
                            while ($row2 = mysqli_fetch_array($result2, MYSQLI_ASSOC)){
                                print "<tr>";
                                print "<td>{$row2['Price']}</td>";
                                print "<td>{$row2['Time']}</td>";
                                print "<td>{$row2['UserName']}</td>";
                                print "</tr>";
                            }
                        }	?>
                    </table>
			<br>
                    <!--</form>-->
                    <form name="mybid" action="<?php echo "Bid.php?ItemID=".$id; ?>" method="POST">
                        <table>
                            <tr>
                                <td class="item_label">Your bid      $</td>
                                <td><input type="number" name="Price" method="POST" step="0.01"></td>
                            </tr>
                            <tr>
                                <td class="item_label">    </td>
                                <td class="item_label"><?php echo "(minimum bid $".$MaxPrice.")" ?></php></td>
                            </tr>
                        </table>
                        <button class='my_button' onclick="windowClose();">Cancel</button>

                        <!--<
                        <!--<button class='my_button' onclick="goBack()">Cancel</button>-->
                        <?php if($_SESSION['UserName']!=$row['UserName']){
                                print "<input class='my_button' type=\"submit\" name=\"bid\" value=\"Bid On This Item\">";
                        }else{print "<button class='my_button_grey' >Bid On This Item</button>";}?>


                    </form>

                    <!--<input type="hidden" name="friend_name" value="<?php //print $friend_name; ?>" />
                    <input type="hidden" name="home_town" value="<?php //print $home_town; ?>" />
                    <input type="hidden" name="friend_email" value="<?php //print $friend_email; ?>" />-->



                </div>
            </div>
        </div>

        <?php include("lib/error.php"); ?>

        <div class="clear"></div>
    </div>


</div>
</body>
</html>
<script type="text/javascript">
    function alertfunc1() {
        alert ("You can not bid the item listed by yourself.");
    }

    function alertfunc2() {
        alert ("You can not buy the item listed by yourself.");
    }
</script>
