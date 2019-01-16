<?php

include('lib/common.php');
// written by GTusername2

if (!isset($_SESSION['UserName'])) {
    header('Location: login.php');
    exit();
}

$query = "update items i inner join (select UserName,ItemID,Price from bids ".
    "where (ItemID, Price) in (select ItemID, max(price) as Price from bids group by ItemID)) N ".
    "set i.Winner = N.UserName, i.SalePrice=N.Price where i.ItemID = N.ItemID and Price>=i.MinPrice and i.Winner is NULL and AuctionEnd<NOW()";
$result = mysqli_query($db, $query);

$Keyword = mysqli_real_escape_string($db, $_POST['Keyword']);
$Category = mysqli_real_escape_string($db, $_POST['Category']);
$MinPrice = mysqli_real_escape_string($db, $_POST['MinPrice']);
$MaxPrice = mysqli_real_escape_string($db, $_POST['MaxPrice']);
$Condition = mysqli_real_escape_string($db, $_POST['Condition']);

$query = "SELECT s1.ItemID, s1.ItemName, s1.Price, s1.UserName, s1.GetNowPrice, s1.AuctionEnd, s1.StartBid, s1.CategoryName, s1.Level " .
    "FROM searchview AS s1 LEFT JOIN searchview s2 ON s1.ItemID=s2.ItemID AND s1.Price<s2.Price WHERE s2.Price is NULL ";
//disable full_group_by in cmd
//set global sql_mode='STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';
//set session sql_mode='STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';

//if (!empty($Keyword) or !empty($Category) or !empty($MinPrice) or !empty($MaxPrice) or !($Condition)) {
//$query = $query . " AND (1=0 ";

if (!empty($Keyword)) {
    $query = $query . " AND (s1.Description LIKE '%$Keyword%' OR s1.ItemName LIKE '%$Keyword%') ";
}
if (!empty($Category)) {
    $query = $query . " AND s1.CategoryName='$Category' ";
}
if (!empty($Condition)) {
    $query = $query . " AND s1.`Level`>=$Condition ";
}
if (empty($MinPrice)) {
    $MinPrice = 0;
}
if (empty($MaxPrice)) {
    $MaxPrice = 999999;
}
$query = $query . " AND NOW()<s1.AuctionEnd GROUP BY s1.ItemID HAVING IFNULL(MAX(s1.Price), s1.StartBid) BETWEEN $MinPrice AND $MaxPrice ORDER BY s1.AuctionEnd DESC;";
//}

//    $query = $query . " ORDER BY last_name, first_name";

$result2 = mysqli_query($db, $query);

//include('lib/show_queries.php');

if (mysqli_affected_rows($db) == -1) {
    array_push($error_msg,  "<br>SELECT ERROR:Failed to find items ... <br>" . __FILE__ ." line:". __LINE__ );
}
?>

<?php include("lib/header.php"); ?>
<title>GTBay Item Search</title>
</head>

<body>
<div id="main_container">
    <?php include("lib/menu.php"); ?>

    <div class="center_content">
        <div class="center_left">
            <div class="title_name"></div>
            <div class="features">

                <div class='subtitle'>Search Results</div>
                <table border="1">
                    <tr>
                        <td class='heading'>ID</td>
                        <td class='heading'>Item Name</td>
                        <td class='heading'>Current Bid</td>
                        <td class='heading'>High Bidder</td>
                        <td class='heading'>Get It Now Price</td>
                        <td class='heading'>Auction Ends</td>
                    </tr>
                    <?php
                    if (isset($result2)) {
                        if(!empty($result2) && (mysqli_num_rows($result2) > 0) ){
                            while ($row = mysqli_fetch_array($result2, MYSQLI_ASSOC)){
                                $id = urlencode($row['ItemID']); //replace non-aphabetical char
                                print "<tr>";
                                print "<td>{$row['ItemID']}</td>";
                                $_SESSION['ItemID']=$id;
                                print "<td><a href='Bid.php?ItemID=$id', target='_blank'>{$row['ItemName']}</a></td>";
                                print "<td>{$row['Price']}</td>";
                                print "<td>{$row['UserName']}</td>";
                                print "<td>{$row['GetNowPrice']}</td>";
                                print "<td>{$row['AuctionEnd']}</td>";
                                print "</tr>";
                            }
                        }else{echo "No result is found.";}
                    }	?>
                </table>
            </div>
        </div>


        <div class="clear"></div>
    </div>


</div>
</body>
</html>

