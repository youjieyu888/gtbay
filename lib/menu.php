
			<div id="header">
                <div class="logo"><img class="headerimage" src="img/GTbay_logo_animation.gif" style="width: 820px;" border="0" alt="" title="GT Online Logo"/></div>
			</div>
			
			<div class="nav_bar">
				<ul>
                    <li><a href="view_profile.php" <?php if($current_filename=='view_profile.php') echo "class='active'"; ?>>My profile</a></li>
                    <li><a href="sell_item.php" <?php if($current_filename=='sell_item.php') echo "class='active'"; ?>>New Item For Auction</a></li>
					<li><a href="search_item.php" <?php if(strpos($current_filename, 'search_item.php') !== false) echo "class='active'"; ?>>Item Search</a></li>
                    <li><a href="auction_results.php" <?php if($current_filename=='auction_results.php') echo "class='active'"; ?>>Auction Results</a></li>

                    <?php
                    if(isset($_SESSION['isAdmin'])){
                        if(isset($_SESSION['Position'])||$_SESSION['isAdmin']==true){
                    echo " <li><a href=\"category_report.php\"";
                    if($current_filename=='category_report.php') echo "class='active'";
                    echo ">Category Report</a></li>";
                    echo "<li><a href=\"user_report.php\"";
                    if($current_filename=='user_report.php') echo "class='active'";
                    echo ">User Report</a></li>";
                    }}?>

                    <li><a href="logout.php" <?php if($current_filename=='logout.php') echo "class='active'"; ?>>Log Out</a></li>
				</ul>
			</div>