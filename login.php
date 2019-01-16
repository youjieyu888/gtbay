<?php
include('lib/common.php'); //run lib/common.php
// written by GTusername1

if($showQueries){
  array_push($query_msg, "showQueries currently turned ON, to disable change to 'false' in lib/common.php");
}

if(isset($_POST['register'])){
    header(REFRESH_TIME . 'register.php');
}

//Note: known issue with _POST always empty using PHPStorm built-in web server: Use *AMP server instead
if( isset($_POST['login'])) {

	$UserName = mysqli_real_escape_string($db, $_POST['UserName']);
	$Password = mysqli_real_escape_string($db, $_POST['Password']);

    if (empty($UserName)) {
            array_push($error_msg,  "Please enter a valid username.");
    }

	if (empty($Password)) {
			array_push($error_msg,  "Please enter a password.");
	}
	if(empty($UserName)){
        echo "<script>alert('Please enter username')</script>";
    }
    if(empty($Password)){
        echo "<script>alert(\"Please enter password\")</script>";
    }
    if ( !empty($UserName) && !empty($Password) )   {

        $query = "SELECT Password FROM RegularUser WHERE UserName='$UserName';";
        $result = mysqli_query($db, $query);
        $count = mysqli_num_rows($result); 
        
        if (!empty($result) && ($count > 0) ) {
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC); //return array, ASSOC means row is colname-value dictionary
            $storedPassword = $row['Password'];
            
            $options = [
                'cost' => 8,
            ];
             //convert the plaintext passwords to their respective hashses
             // 'michael123' = $2y$08$kr5P80A7RyA0FDPUa8cB2eaf0EqbUay0nYspuajgHRRXM9SgzNgZO
            $storedHash = password_hash($storedPassword, PASSWORD_DEFAULT , $options);   //may not want this if $storedPassword are stored as hashes (don't rehash a hash)
            $enteredHash = password_hash($Password, PASSWORD_DEFAULT , $options);
            
            if($showQueries){
                array_push($query_msg, "Plaintext entered password: ". $Password);
                //Note: because of salt, the entered and stored password hashes will appear different each time
                array_push($query_msg, "Entered Hash:". $enteredHash);
                array_push($query_msg, "Stored Hash:  ". $storedHash . NEWLINE);  //note: change to storedHash if tables store the plaintext password value
                //unsafe, but left as a learning tool uncomment if you want to log passwords with hash values
                //error_log('email: '. $enteredEmail  . ' password: '. $enteredPassword . ' hash:'. $enteredHash);
            }
            
            //depends on if you are storing the hash $storedHash or plaintext $storedPassword 
            if (password_verify($Password, $storedHash) ) {
                array_push($query_msg, "Password is Valid! ");
                $_SESSION['UserName'] = $UserName;
                //$_SESSION['isAdmin']=false;
                $query = "SELECT R.UserName, Position " .
                    "FROM RegularUser R LEFT JOIN Administrator A ON R.UserName=A.UserName " .
                    "WHERE R.UserName='$UserName'";
                $result = mysqli_query($db, $query);
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                if($row['Position']==null){$_SESSION['isAdmin']=false;}
                else{$_SESSION['isAdmin']=true;}
                array_push($query_msg, "logging in... ");
                header(REFRESH_TIME . 'view_profile.php');		//to view the password hashes and login success/failure
                
            } else {
                echo "<script>alert(\"The username doesn't match the password\")</script>";
            }
            
        } else {
            echo "<script>alert(\"The username entered does not exist\")</script>";
            }
    }
}
?>

<?php include("lib/header.php"); ?>
<title>GTBay Login</title>
</head>
<body>
    <div id="main_container">
        <div id="header">
            <div class="logo">
                <img src="img/GTbay_logo_animation.gif" style="width: 820px;" border="0" alt="" title="GT Online Logo"/>
            </div>
        </div>

        <div class="center_content">
            <div class="text_box">

                <form action="login.php" method="post" enctype="multipart/form-data">
                    <div class="title">GTBay Login</div>
                    <div class="login_form_row">
                        <label class="login_label">UserName</label>
                        <input type="text" name="UserName" value="" class="login_input"/>
                    </div>
                    <div class="login_form_row">
                        <label class="login_label">Password</label>
                        <input type="password" name="Password" value="" class="login_input"/>
                    </div>
                    <div class="login_form_row">
                    <input class="my_button" type="submit"  name="login" value="login"/>
                    <input class="my_button" type="submit"  name="register" value="register"/>
                    </div>
                    <form/>
                </div>

                <?php //include("lib/error.php"); ?>

                <div class="clear"></div>
            </div>
   
            <!-- 
			<div class="map">
			<iframe style="position:relative;z-index:999;" width="820" height="600" src="https://maps.google.com/maps?q=801 Atlantic Drive, Atlanta - 30332&t=&z=14&ie=UTF8&iwloc=B&output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"><a class="google-map-code" href="http://www.embedgooglemap.net" id="get-map-data">801 Atlantic Drive, Atlanta - 30332</a><style>#gmap_canvas img{max-width:none!important;background:none!important}</style></iframe>
			</div>
             -->

        </div>
    </body>
</html>