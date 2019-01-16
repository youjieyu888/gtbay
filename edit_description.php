<?php

include('lib/common.php');
// written by GTusername2

if (!isset($_SESSION['UserName'])) {
    header('Location: login.php');
    exit();
}
$id=$_GET['ItemID'];
//what to do after click 'request a friend'

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $Description = mysqli_real_escape_string($db, $_REQUEST['new_description']);

    if (empty($Description)) {
        array_push($error_msg,  "Error: You must provide a description ");
    }

   if(isset($_POST['cancel_button'])){
       header(REFRESH_TIME . 'Bid.php?ItemID='.$id);
   }
    if(isset($_POST['update_button'])){
        $query = "UPDATE Items SET Description='$Description' WHERE ItemID=$id";

        $queryID = mysqli_query($db, $query);
        header(REFRESH_TIME . 'Bid.php?ItemID='.$id);
    }

}
?>

<?php include("lib/header.php"); ?>
<title>GTBay Edit Description</title>
</head>

<body>
<div id="main_container">
    <?php include("lib/menu.php"); ?>

    <div class="center_content">
        <div class="center_left">
            <div class="title_name">Edit Description</div>
            <div class="features">

                <div class="profile_section">
                    <form name="requestform" action="edit_description.php?ItemID=<?php echo $id;?>" method="POST">
                        <table>
                            <tr>
                                <td class="item_label">New Description</td>
                                <td><textarea  name="new_description" rows="5" cols="50"><?php
                                        $querydes="SELECT Description FROM Items WHERE ItemID=$id;";
                                        $resultdes = mysqli_query($db, $querydes);
                                        $rowdes=mysqli_fetch_array($resultdes, MYSQLI_ASSOC);
                                        echo $rowdes['Description'];?></textarea></td>
                            </tr>
                        </table>
                        <td><button class="my_button" type="submit" name="cancel_button">Cancel</button></td>
                        <td><button class="my_button" type="submit" name="update_button">Update Description</button></td>
                    </form>
                </div>
            </div>
        </div>



        <div class="clear"></div>
    </div>



</div>
</body>
</html>