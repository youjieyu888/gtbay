<?php

include('lib/common.php');
// written by GTusername4


//if( isset($_POST['cancel'])) {
 //   header(REFRESH_TIME . 'login.php');
//}
//input
if (isset($_POST['register'])) {

    $FirstName = mysqli_real_escape_string($db, $_POST['FirstName']);
    $LastName = mysqli_real_escape_string($db, $_POST['LastName']);
    $UserName = mysqli_real_escape_string($db, $_POST['UserName']);
    $Password1 = mysqli_real_escape_string($db, $_POST['Password1']);
    $Password2 = mysqli_real_escape_string($db, $_POST['Password2']);
    if($Password1!=$Password2){
        echo "<script>alert('Confirm Password and Password are different.')</script>";
    }else {
        $query = "INSERT INTO RegularUser(UserName, Password, FirstName, LastName) " .
            "VALUES ('$UserName', '$Password1','$FirstName', '$LastName');";
        $result = mysqli_query($db, $query);
        if($result==false){
            echo "<script>alert('Username already exist!')</script>";
        }
        else{
            header(REFRESH_TIME . 'login.php');
            echo "<script>alert('registration successful!')</script>";
        }
    }



}  //end of if($_POST)




?>

<?php include("lib/header.php"); ?>

<title>GTBay Register</title>
</head>

<body>
<div id="main_container">

    <div id="header">
        <div class="logo"><img class="headerimage" src="img/GTbay_logo_animation.gif" style="width: 820px;" border="0" alt="" title="GT Online Logo"/></div>
    </div>

    <div class="center_content">
        <div class="center_left">
            <div class="features">

                <div class="profile_section">
                    <div class="subtitle">GTBay New User Registration</div>

                    <form name="profileform" action="register.php" method="post">

                        <table>
                            <tr><!--name-->
                                <td class="item_label">First Name</td>
                                <td>
                                    <input type="text" name="FirstName" maxlength="50" required >
                                </td>
                            </tr>
                            <tr><!--name-->
                                <td class="item_label">Last Name</td>
                                <td>
                                    <input type="text" name="LastName" maxlength="50" required >
                                </td>
                            <tr><!--name-->
                                <td class="item_label">Username</td>
                                <td>
                                    <input type="text" name="UserName" maxlength="20" required >
                                </td>
                            <tr><!--name-->
                                <td class="item_label">Password</td>
                                <td>
                                    <input type="password" name="Password1" maxlength="20" required >
                                </td>
                            </tr>
                            <tr><!--name-->
                                <td class="item_label">Confirm Password</td>
                                <td>
                                    <input type="password" name="Password2" maxlength="20" required >
                                </td>
                            </tr>
                        </table>

                        <input class="my_button" type="button" name="Cancel" value="Cancel" onclick="window.location.href='login.php'" />
                        <button class="my_button" type="submit" name="register">Register</button>

                    </form>
                </div>


            </div>
        </div>



        <div class="clear"></div>
    </div>


</div>
</body>
</html>
