<?php

include('lib/common.php');
// written by GTusername4

if (!isset($_SESSION['UserName'])|| $_SESSION['isAdmin']==false) {
    header('Location: login.php');
    exit();
}

$query = "update items i inner join (select UserName,ItemID,Price from bids ".
    "where (ItemID, Price) in (select ItemID, max(price) as Price from bids group by ItemID)) N ".
    "set i.Winner = N.UserName, i.SalePrice=N.Price where i.ItemID = N.ItemID and Price>=i.MinPrice and i.Winner is NULL and AuctionEnd<NOW()";
$result = mysqli_query($db, $query);


$query = "SELECT Category.CategoryName, IFNULL(COUNT(ItemID),0) AS `Total Items`, MIN(GetNowPrice) AS `Min Price`, MAX(GetNowPrice) AS `Max Price`, ROUND( AVG(GetNowPrice),2) AS `Average Price` FROM Category LEFT JOIN Items ON Category.CategoryName=Items.CategoryName GROUP BY CategoryName ORDER BY CategoryName";

         
$result = mysqli_query($db, $query);
include('lib/show_queries.php');
    
//if (!empty($result) && (mysqli_num_rows($result) > 0) ) {
   // $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
  //  $count = mysqli_num_rows($result);

//} else {
   //     array_push($error_msg,  "SELECT ERROR: User profile <br>" . __FILE__ ." line:". __LINE__ );
//}

?>

<?php include("lib/header.php"); ?>
        <title>GTBey Category Report </title>
        <style>
        table, th, td {
            border: 1px solid black;
            text-align:center;
        }
</style>
    </head>
    
    <body>
        <div id="main_container">
            <?php include("lib/menu.php"); ?>
            
            <div class="center_content">
                <div class="center_left">        
                    
                    <div class="features">      
                        <div class="profile_section">
                            <div class="subtitle">Category Report</div>
                            <table style="width:100%">
  
                                <tr>
                                    <td class="heading" >Categor Name</td>
                                    <td class="heading" >Total Items</td>
                                    <td class="heading" >Min Price</td>
                                    <td class="heading" >Max Price</td>
                                    <td class="heading" >Average Price</td>
                                </tr>
                                                                
                                <?php                               
                                  //  $query = "SELECT CategoryName, COUNT(ItemID) AS `Total Items`, MIN(GetNowPrice) AS `Min Price`, MAX(GetNowPrice) AS `Max Price`, ROUND( AVG(GetNowPrice),2) AS `Average Price` \n". "FROM Items \n". "GROUP BY CategoryName ORDER BY CategoryName";
                                             
                                   // $result = mysqli_query($db, $query);
                                     //if (!empty($result) && (mysqli_num_rows($result) == 0) ) {
                                   //      array_push($error_msg,  "SELECT ERROR: find Friendship <br>" . __FILE__ ." line:". __LINE__ );
                                  //  }
                                    
                                    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                                        print "<tr>";
                                        print "<td>{$row['CategoryName']}</td>";
                                        print "<td>{$row['Total Items']}</td>";
                                        print "<td>{$row['Min Price']}</td>";
                                        print "<td>{$row['Max Price']}</td>";
                                        print "<td> {$row['Average Price']} </td>";
                                        print "</tr>";                          
                                    }                                   
                                ?>
                            </table>                        
                        </div>  
                     </div> 
                </div> 
                
                <?php include("lib/error.php"); ?>
                    
                <div class="clear"></div> 

         
        </div>



</body>
</html>
