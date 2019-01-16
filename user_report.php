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


$query = "SELECT U.UserName, Listed, IFNULL(Sold,0) as Sold, Purchased, Rated ".
    "FROM RegularUser U left join UL on U.UserName=UL.UserName ".
"left join US on U.UserName=US.UserName ".
"left join UP on U.UserName=UP.UserName ".
"left join UR on U.UserName=UR.UserName order by Listed DESC;";



         
$result = mysqli_query($db, $query);
//include('lib/show_queries.php');
    
//if (!empty($result) && (mysqli_num_rows($result) > 0) ) {
  //  $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
 //   $count = mysqli_num_rows($result);

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
                            <div class="subtitle">User Report</div>
                            <table style="width:100%">
  
                                <tr>
                                    <td class="heading" >UserName</td>
                                    <td class="heading" >Listed</td>
                                    <td class="heading" >Sold</td>
                                    <td class="heading" >Purchased</td>
                                    <td class="heading" >Rated</td>
                                </tr>
                                                                
                                <?php                               

                                    
                                    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                                        print "<tr>";
                                        print "<td>{$row['UserName']}</td>";
                                        print "<td>{$row['Listed']}</td>";
                                        print "<td>{$row['Sold']}</td>";
                                        print "<td>{$row['Purchased']}</td>";
                                        print "<td> {$row['Rated']} </td>";
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
