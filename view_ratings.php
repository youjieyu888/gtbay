<?php

	include('lib/common.php');
	// written by GTusername2
	
	if (!isset($_SESSION['UserName'])) {
	    header('Location: login.php');
	    exit();
	}
	
	$id=$_GET['ItemID'];
	//just for itemname
	$query1="SELECT ItemName,UserName FROM Items WHERE ItemID=$id";
	//for comments

	$result1=mysqli_query($db, $query1);
    $row1 = mysqli_fetch_array($result1, MYSQLI_ASSOC);
    //$row2 = mysqli_fetch_array($result2, MYSQLI_ASSOC);
    $ItemName=$row1['ItemName'];


    $query2="SELECT R.ItemID, R.UserName, R.Rating, R.Comments, R.`DateTime`, I.ItemName".
    " FROM Ratings R LEFT JOIN Items I ON I.ItemID=R.ItemID ".
    "WHERE I.ItemName='".$ItemName."' ORDER BY R.DateTime DESC";
    $result2=mysqli_query($db, $query2);
//	include('lib/show_queries.php');
	

	
	$query3="SELECT AVG(Rating) as avgrating  FROM Ratings R  WHERE R.ItemID in (SELECT ItemID FROM Items where ItemName='{$ItemName}');";
	$result3=mysqli_query($db,$query3);
	$row3 = mysqli_fetch_array($result3, MYSQLI_ASSOC);
//	include('lib/show_queries.php');
    if ( isset($_POST['delete'])) {
        $id=$_POST['ItemID'];
        $sql="delete from Ratings where UserName = '".$_SESSION['UserName']."' and ItemID=".$id.";";
        $result = mysqli_query($db, $sql);
        if($result){
        header(REFRESH_TIME ."view_ratings.php?ItemID=".$id);
        }else{
            array_push($error_msg, "delete failed".  __FILE__ ." line:". __LINE__ );
        }
    }
	//Add rating
	//date_default_timezone_set('CST');
	//$AddDateTime=date('Y-m-d H:i:s',time());
	if ( isset($_POST['RateButton'])) {

		$AddItemID=$_POST['ItemID'];
		$AddDateTime=date('Y-m-d H:i:s',time());
		$AddComments=$_POST['Comments'];
		$AddUserName=$_SESSION['UserName'];
		$AddRating=$_POST['Rating'];
		//exist or not
		$sql3 = "SELECT * FROM Ratings WHERE ItemID = ".$id." AND UserName = '".$AddUserName."'";
		$resultExist = mysqli_query($db, $sql3);
		if (!is_bool($resultExist) && (mysqli_num_rows($resultExist) > 0) ) { //Update
			$query = "update ratings set DateTime =now(), Comments = '$AddComments', Rating = '$AddRating' where ItemID = $id and UserName = '$AddUserName'";
			$result = mysqli_query($db, $query);
		}
		else{//add
			$query = "INSERT INTO Ratings (ItemID, DateTime, Comments, UserName, Rating) VALUES ('$AddItemID','$AddDateTime','$AddComments','$AddUserName','$AddRating')";
			$result = mysqli_query($db, $query);
		}
		
	  //  include('lib/show_queries.php');
		
	    //if ($result  == False) {
	      //  array_push($error_msg, "insert failed".  __FILE__ ." line:". __LINE__ );
	   // }
	  //  else{
        header(REFRESH_TIME ."view_ratings.php?ItemID=".$id);
	  //  }
	}
	if(isset($_POST['Cancel'])){
        header(REFRESH_TIME . 'Bid.php?ItemID='.$id);
    }
?>
	<script type="text/javascript">
		function DeleteR(username,itemid){
			window.location.href="delete_rate.php?UserName="+username+"&ItemID="+itemid;
			window.event.returnValue=false;
//			header('Location:login.php');
		}
	</script>
<script>
    function goBack() {
        window.history.back();
    }
</script>
<?php include("lib/header.php"); ?>

<title>GTBay View Ratings</title>
</head>

<body>
<div id="main_container">
    <?php include("lib/menu.php"); ?>

    <div class="center_content">
        <div class="center_left">
            <div class="title_name">Item Ratings</div>
            <div class="features">
                <div class="profile_section">
                    <form name="requestform" action="view_ratings.php?ItemID=<?php echo $id;?>" method="POST">
                        <table>

                            <tr>
                                <td class="item_label">Item ID</td>
                                <td><?php print $id; ?></td>
                            </tr>
                            <tr>
                                <td class="item_label">Item Name</td>
                                <td><?php echo $row1['ItemName']; ?></td>
                            </tr>
                            <tr>
                                <td class="item_label">Average Rating</td>
                                <td><?php if($row3['avgrating']!=null) {echo round($row3['avgrating'],1);}
                                else {echo "N/A";}?></td>
                            </tr>
                        </table>
                        <hr>
                       <table border="1">
                       	<tr>
                            <td class="item_label">Rated by</td>
                            <td class="item_label">Rating</td>
                            <td class="item_label" colspan="2">Comments</td>
                            <td class="item_label" >Date</td>
                        </tr>


                           <?php
                           while($row2 = mysqli_fetch_array($result2, MYSQLI_ASSOC)){
                               if ($row2['UserName'] ==$_SESSION['UserName']){
                                   echo "<tr><td>".$row2['UserName']."</td>";
                                   echo "<td>".$row2['Rating']."</td>";
                                   echo "<td >".$row2['Comments']."</td>";
                                   echo "<td><button name='delete' type='submit'>Delete</button></td>";
                                   echo "<td>".$row2['DateTime']."</td></tr>";
                               }

                               else{
                                   echo "<tr><td>".$row2['UserName']."</td>";
                                   echo "<td>".$row2['Rating']."</td>";
                                   echo "<td colspan='2'>".$row2['Comments']."</td>";
                                   echo "<td>".$row2['DateTime']."</td></tr>";
                               }
                           }
                           ?>

		    				<tr>
		    					<td> 
		    						<input type="text" disabled value="<?php echo $_SESSION['UserName'];?>" name="UserName">
		    					</td>
		    					<td>
			    					<select name="Rating">
                                        <option value=0>0</option>
			    						<option value=1>1</option>
			    						<option value=2>2</option>
			    						<option value=3>3</option>
			    						<option value=4>4</option>
			    						<option value=5>5</option>
			    					</select>
		    					</td>
		    					<td> 
		    						<textarea rows="4" cols="50" name="Comments"></textarea>
		    					</td>

		    				</tr>
                       </table>
                           <td>
                               <?php if ($_SESSION['UserName'] == $row1['UserName']){
                                   echo "<button class='my_button_grey' >Rate</button>";

                               }
                               else{
                                   echo "<button  class='my_button'  name='RateButton'  type='submit'>Rate</button>";
                               }
                               echo "<input type=\"hidden\" name=\"ItemID\" value=$id>";
                               ?>
                               <button class='my_button' name="Cancel" value="Cancel">Cancel</button>

                           </td>

                    </form>
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
  function myFunction () {
    alert ("You can not add rate/comment for the item listed by yourself.");
  }
</script>