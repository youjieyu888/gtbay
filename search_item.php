<?php

include('lib/common.php');
// written by GTusername3

if (!isset($_SESSION['UserName'])) {//a variable is set or not
    header('Location: login.php');
    exit();
}



$query = "SELECT FirstName, LastName " .
    "FROM RegularUser R " .
    "WHERE R.UserName = '{$_SESSION['UserName']}'";

$result = mysqli_query($db, $query);
include('lib/show_queries.php');

if (!is_bool($result) && (mysqli_num_rows($result) > 0) ) {
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $count = mysqli_num_rows($result);
    $user_name = $row['FirstName'] . " " . $row['LastName'];
} else {
    array_push($error_msg,  "SELECT ERROR: User profile <br>" . __FILE__ ." line:". __LINE__ );
}

/* if form was submitted, then execute query to search for friends */
//if ($_SERVER['REQUEST_METHOD'] == 'POST') {


//}
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

                <div class="">
                    <div class="subtitle">Item Search</div>

                    <form name="searchform" action="search_results.php" method="POST">
                        <table>
                            <tr>
                                <td class="item_label">Keyword</td>
                                <td><input type="text" name="Keyword" /></td>
                            </tr>
                            <tr>
                                <td class="item_label">Category</td>
                                <td>
                                    <select name="Category">
                                        <option value="" ></option>
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
                            <tr>
                                <td class="item_label">Minimum Price $</td>
                                <td><input type="number" onblur="this.value=parseFloat(this.value).toFixed(2);if (this.value!=''&&(isNaN(this.value) || this.value<0)){CheckPriceWarning('5');this.focus();} else{CheckPriceOk('5');};" name="MinPrice"  step="0.01"/>
                            	<p id="Check_5" style="display: inline;" ></p>
                            	</td>
                            </tr>
                            <tr>
                                <td class="item_label">Maximum Price $</td>
                                <td><input type="number" onblur="this.value=parseFloat(this.value).toFixed(2);if (this.value!=''&&(isNaN(this.value) || this.value<=0)){CheckPriceWarning('6');this.focus();} else{CheckPriceOk('6');};" name="MaxPrice" step="0.01"/>
                            	<p id="Check_6" style="display: inline;" ></p>
                            	</td>
                            </tr>
                            <tr><!--condition-->
                                <td class="item_label">Condition</td>
                                <td>
                                    <select name="Condition">
                                        <option value=0></option>
                                        <option value=5>New</option>
                                        <option value=4 >Very Good</option>
                                        <option value=3 >Good</option>
                                        <option value=2 >Fair</option>
                                        <option value=1 >Poor</option>
                                    </select>
                                </td>
                            </tr>
                        </table>
                        <a href="javascript:searchform.reset();" class="my_button">Cancel</a>
                        <a href="javascript:searchform.submit();" class="my_button">Search</a>
                        <br> <br> <br>

                    </form>
                </div>


                <div class="" >

                	<?php //var_dump($result); ?>

                </div>
            </div>
        </div>


        <div class="clear"></div>
    </div>


</div>
</body>
</html>