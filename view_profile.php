<?php

include('lib/common.php');
// written by GTusername4

if (!isset($_SESSION['UserName'])) {
	header('Location: login.php');
	exit();
}


    // ERROR: demonstrating SQL error handlng, to fix
    // replace 'sex' column with 'gender' below:
    $query = "SELECT R.UserName, FirstName, LastName, Position " .
		 "FROM RegularUser R LEFT JOIN Administrator A ON R.UserName=A.UserName " .
		 "WHERE R.UserName='".$_SESSION['UserName']."'";

    $result = mysqli_query($db, $query);
    include('lib/show_queries.php');
 
    if ( !empty($result) && (mysqli_num_rows($result) > 0) ) {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    } else {
        array_push($error_msg,  "Query ERROR: Failed to get User profile...<br>" . __FILE__ ." line:". __LINE__ );
    }



?>

<?php include("lib/header.php"); ?>
<title>GTBay Profile</title>
</head>

<body>
		<div id="main_container">
    <?php include("lib/menu.php"); ?>

    <div class="center_content">
        <div class="center_left">
            <div class="title_name">
                <?php print $row['FirstName'] . ' ' . $row['LastName']; ?>
            </div>          
            <div class="features">   
            
                <div class="profile_section">
                    <div class="subtitle">View Profile</div>   
                    <table>
                        <tr>
                            <td class="item_label">User Name</td>
                            <td>
                                <?php  print $row['UserName']; ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="item_label">First Name</td>
                            <td>
                                <?php  print $row['FirstName']; ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="item_label">Last Name</td>
                            <td>
                                <?php  print $row['LastName']; ?>
                            </td>
                        </tr>

                        <?php  if ($row['Position']!=null){
                            $_SESSION['isAdmin']=true;
                            echo "<tr>";
                            echo "<td class='item_label'>Position</td>";
                            echo "<td>";
                            echo  $row['Position'] ;
                            echo "</td>";
                            echo "</tr>";}else{$_SESSION['isAdmin']=false;}
                        ?>


                    </table>						
                </div>



            </div> 			
        </div> 

                <?php include("lib/error.php"); ?>
                    
				<div class="clear"></div> 		
			</div>    


				 
		</div>
	</body>
</html>