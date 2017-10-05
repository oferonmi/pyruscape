<?php
	include'/accessctrl.php';
	
	if(isset($_POST['SignOut']) == "SignOut"){
		
		//$_SESSION['login_status'] = '';
		unset($_SESSION['login_status']);
		unset($_SESSION['uEmail']);
		unset($_SESSION['uPassword']);
		header("Location:signin.php");
		exit;
	}
	
	//retreiving some of the user's data
	$usersOtherNames = explode(" ", mysqli_result($result, 0,'user_other_names'));
	$firstName = $usersOtherNames[0];
	$CUEmail = mysqli_result($result, 0,'user_Email');
	
	if($_SESSION['login_status'] != ''){
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Notifications - Pyruscape&trade;</title>
		
		<link rel = "stylesheet" type ="text/css" href = "css/bootstrap.min.css">
		<link rel = "stylesheet" type = "text/css" href = "css/codedoc.css">
		<link rel = "stylesheet" type ="text/css" href = "css/bootstrap-fileupload.min.css">
	</head>
	
	<body>
	
	<script src="js/jquery-1.8.1.js"></script>
    <script src="js/bootstrap.min.js"></script>
	
	<!--HEADER SECTION-->
		<div class="headr">
			<div class="container">
			  <div class="span12">
				<div class="row-fluid">
				  <!--SITE LOGO-->
				  <div class="span4"><img src = "img/pyrusclogo v4.gif"></div>
				  <!--LOGIN FORM ON HEADER-->
				  <div class="span8">
					<form class="form-inline" method="post" action="yourdocstream.php">
						<div id ="headr-logout">
						  <button type="submit" class="btn" name ="SignOut" value = "SignOut">Sign out</button>
						</div>
					</form>
				  </div>
				</div>
			  </div>
			</div>
		</div>
		
	<!--MENU SECTION-->
		<div class="menu">
			<div class="container">
				<div class ="span12">
					<div class = "menuLinks">
						<div class="menulist">
							<a href ="yourdocstream.php"><i class = '  icon-list-alt'></i> <?php echo $firstName;?>'s Document Stream</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<a href ="profilepage.php?uemail=<?php echo $CUEmail;?>"><i class = 'icon-user'></i> <?php echo $firstName;?>'s Profile</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<a href ="msgstream.php"><i class = '  icon-envelope'></i> Messages</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<a href ="reminder.php"><i class = ' icon-check'></i> Reminders</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						</div>
					</div>
				</div>
			</div>
		</div>
	
	<!--CONTENT SECTION-->
		<div class = "container">
			<div class = "row-fluid">
				<div class = "span12">
					<h2 style  = "margin-left:1.3em;"><?php echo $firstName;?>'s Notifications History</h2><hr>
				</div>
			</div>
				
			<div class = "row-fluid">
				<!--User's document list section.-->
				<div class = "span8">
					<?php
						// Accessing the minute database
						$query1 = sprintf("SELECT * FROM doc_minutes WHERE minute_respondant_email ='%s' ORDER BY user_ID DESC LIMIT 0, 5",
											mysqli_real_escape_string($dblink, $CUEmail)
											);
						$result1 = mysqli_query($dblink, $query1) or die("Error in query: $query1.".mysqli_error($dblink));
						
						if(mysqli_num_rows($result1) > 0){
							while($row = mysqli_fetch_object($result1)){
								//retrieving details of source of notification.
								$query2 = sprintf("SELECT * FROM users_details WHERE user_Email ='%s'",
													mysqli_real_escape_string($dblink, $row->minute_author_email)
												);

								$result2 = mysqli_query($dblink, $query2);
								$minAuthorEmail = mysqli_result($result2, 0,'user_Email');
								$minAuthor = mysqli_result($result2, 0,'user_surname').",".mysqli_result($result2, 0,'user_other_names');
								
								//Retrieving associated document details.
								$query3 = sprintf("SELECT * FROM doc_files_details WHERE doc_title ='%s'",
												mysqli_real_escape_string($dblink, $row->doc_title)
												);	
								$result3 = mysqli_query($dblink, $query3) or die("Error in query: $query3.".mysqli_error($dblink));
								$AssocDocID = mysqli_result($result3, 0,'doc_id');
								$docUploadrEmail = mysqli_result($result3, 0,'user_email');
								
								$docURL = "docpage.php?docID=$AssocDocID&amp;title="
								.stripslashes(htmlentities($row->doc_title, ENT_QUOTES))."&amp;email=$docUploadrEmail";
								
								//Notification cards.
								echo "<div class ='streamunit' style = 'margin-left:2em;'><div class ='streamunitBit'>
								<a href = 'profilepage.php?uemail=$minAuthorEmail'><em class = 'acpnyndata' style = 'font-size:14px;'>".$minAuthor."</em></a> minuted on the document titled; 
								<a href = '$docURL'><b class = 'acpnyndata' style = 'font-size:14px;'>".$row->doc_title."</b></a>, <b>On:</b>
								<em class = 'acpnyndata'>".$row->date_of_dis."</em> <b>At</b>: <em class = 'acpnyndata'>".$row->time_of_dis.
								"</em>.</div><hr></div>" ;
							}
						}else{
							echo "<p class = 'alert'>You don't have a notification at the momemt.<p>";
						}
					?>	
				</div>
			</div>
		</div>
	
	<!--FOOTER-->
		<div class="footr">
		  <div class="container">
			<div class ="span12">
				<div class = "footrLinks">
					<div class="copyright">&copy;&nbsp;<?php echo date("Y");?>
						<a href ="">Kaelahi Technologies</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<a href ="">About Us</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<a href ="">Contact</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<a href ="">Career</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<a href ="">FAQ</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<a href ="">Privacy Statement</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<a href ="">Terms and Conditions</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<a href ="">Blog</a>
					</div>
				</div>
			</div>
		  </div>
		</div>
		
	</body>
</html>
<?php
}else{
	header("Location:signin.php");
	exit;
}
?>