<?php

include('lib/common.php');
// written by GTusername4

if (!isset($_SESSION['UserName'])) {
    header('Location: login.php');
    exit();
}



//input
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $ItemName = mysqli_real_escape_string($db, $_POST['ItemName']);
    $Description = mysqli_real_escape_string($db, $_POST['Description']);
    $Condition = mysqli_real_escape_string($db, $_POST['Condition']);
    $StartBid = mysqli_real_escape_string($db, $_POST['StartBid']);
    $MinPrice = mysqli_real_escape_string($db, $_POST['MinPrice']);
    $AuctionLen = mysqli_real_escape_string($db, $_POST['AuctionLen']);


    $AuctionLen = mysqli_real_escape_string($db, $_POST['AuctionLen']);
    $CategoryName = mysqli_real_escape_string($db, $_POST['CategoryName']);
    if(!isset($_POST['Returnable'])){
        $_POST['Returnable']=0;
        $Returnable=mysqli_real_escape_string($db, $_POST['Returnable']);
    }else{
        $Returnable=mysqli_real_escape_string($db, $_POST['Returnable']);
    }





    if ( isset($_POST['sell'])&&!empty($_POST['ItemName']) && !empty($_POST['Description']) && !empty($_POST['CategoryName'])&& !empty($_POST['Condition'])&& !empty($_POST['StartBid'])&& !empty($_POST['MinPrice']) && !empty($_POST['AuctionLen']) )   {
        if($MinPrice<$StartBid){
            echo "<script>alert('Minimum sale price must not be lower than the starting bid price')</script>";
        }elseif(!empty($_POST['GetNowPrice'])){
            $GetNowPrice = mysqli_real_escape_string($db, $_POST['GetNowPrice']);
            if( $GetNowPrice<=$StartBid){
                echo "<script>alert('Get It Now price must be higher than Start Bid.')</script>";
            }elseif( $GetNowPrice<=$MinPrice){
                echo "<script>alert('Get It Now price must be higher than Minimum Sale Price.')</script>";
            }else{
                $query = "INSERT INTO Items (ItemName, Description, CategoryName, `Condition`, Returnable, StartBid," .
                "MinPrice, AuctionLen, GetNowPrice, UserName, AuctionEnd) " .
                "VALUES ('$ItemName', '$Description','$CategoryName', '$Condition', $Returnable," .
                "$StartBid, $MinPrice, '$AuctionLen',$GetNowPrice, '{$_SESSION['UserName']}', ADDTIME(NOW(),'$AuctionLen'));";
                $result = mysqli_query($db, $query);
                include('lib/show_queries.php');
                echo "<script>alert('Item listed for sale.')</script>";}
        }else{
            $query = "INSERT INTO Items (ItemName, Description, CategoryName, `Condition`, Returnable, StartBid," .
                "MinPrice, AuctionLen, UserName, AuctionEnd) " .
                "VALUES ('$ItemName', '$Description','$CategoryName', '$Condition', $Returnable," .
                "$StartBid, $MinPrice, '$AuctionLen', '{$_SESSION['UserName']}', ADDTIME(NOW(),'$AuctionLen'));";
            $result = mysqli_query($db, $query);
            include('lib/show_queries.php');
            echo "<script>alert('Item listed for sale.')</script>";
        }







    }

}  //end of if($_POST)

?>

<?php include("lib/header.php"); ?>

<title>GTBay List an Item for Sale</title>
</head>

<body>
<div id="main_container">
    <?php include("lib/menu.php"); ?>

    <div class="center_content">
        <div class="">
            <div class="features">

                <div class="">
                    <div class="subtitle">List an Item for Sale</div>

                    <form name="profileform" action="sell_item.php" method="post" >
                        <table>
                            <tr><!--name-->
                                <td class="item_label_wq" >Item Name</td>
                                <td>
                                    <input type="text" name="ItemName" required >
                                </td>
                            </tr>
                            <tr><!--description-->
                                <td class="item_label_wq">Description</td>
                                <td>
                                    <textarea rows="5" cols="50"  name="Description" required></textarea>
                                </td>
                            </tr>
                            <tr><!--category-->
                                <td class="item_label_wq">Category</td>
                                <td>
                                    <select name="CategoryName">
                                        <option value="Art" >Art</option>
                                        <option value="Books" >Books</option>
                                        <option value="Electronics" >Electronics</option>
                                        <option value="Home & Garden" >Home & Garden</option>
                                        <option value="Sporting Goods" >Sporting Goods</option>
                                        <option value="Toys" >Toys</option>
                                        <option value="Other" >Other</option>
                                    </select>
                                </td>
                            </tr>
                            <tr><!--condition-->
                                <td class="item_label_wq">Condition</td>
                                <td>
                                    <select name="Condition">
                                        <option value='New'>New</option>
                                        <option value='Very Good' >Very Good</option>
                                        <option value='Good' >Good</option>
                                        <option value='Fair' >Fair</option>
                                        <option value='Poor' >Poor</option>
                                    </select>
                                </td>
                            </tr>
                            <tr><!-- startbid-->
                                <td class="item_label_wq">Start auction bidding at $ </td>
                                <td >
                                    <input type="number" onblur="this.value=parseFloat(this.value).toFixed(2);if (this.value!=''&&(isNaN(this.value) || this.value<=0)){CheckPriceWarning('1');this.focus();} else{CheckPriceOk('1');};" name="StartBid" step="0.01" required>
                                    	<p id="Check_1" style="display: inline;" ></p>
                                </td>
                            </tr>
                            <tr><!--min-->
                                <td class="item_label_wq">Minimum sale price $ </td>
                                <td>
                                    <input type="number" onblur="this.value=parseFloat(this.value).toFixed(2);if (this.value!=''&&(isNaN(this.value) || this.value<=0)){CheckPriceWarning('2');this.focus();} else{CheckPriceOk('2');};" name="MinPrice"  step="0.01" required>
                                	<p id="Check_2" style="display: inline;" ></p>
                                </td>
                            </tr>
                            <tr><!--auction ends-->
                                <td class="item_label_wq">Auction ends in</td>
                                <td>
                                    <select name="AuctionLen">
                                        <option value='24:00:00'>1 day</option>
                                        <option value='72:00:00' >3 days</option>
                                        <option value='120:00:00' >5 days</option>
                                        <option value='168:00:00' >7 days</option>
                                    </select>
                                </td>
                            </tr>
                            <tr><!--get it now-->
                                <td class="item_label_wq">Get It Now price</td>
                                <td>
                                    <input type="number" onblur="if(this.value!=null){this.value=parseFloat(this.value).toFixed(2);if (((isNaN(this.value) || this.value<=0)&& this.value!='')){CheckPriceWarning('3');this.focus();} else{CheckPriceOk('3');}};" name="GetNowPrice"  step="0.01" >(optional)
                                    <p id="Check_3" style="display: inline;" ></p>
                                </td>
                            </tr>
                            <tr><!--returnable-->
                                <td class="item_label_wq">Returns Accepted?</td>
                                <td>
                                    <input type="checkbox" name="Returnable" value="1" >
                                </td>
                            </tr>
                        </table>

                        <button class="my_button" type="reset" name="cancel">Cancel</button>
                        <button class="my_button" type="submit" name="sell">List My Item</button>

                    </form>
                </div>


            </div>
        </div>



        <div class="clear"></div>
    </div>


</div>
</body>
</html>
