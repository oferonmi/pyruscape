<?php
	include'/accessctrl.php';
	
	if(isset($_POST['SignOut']) == "SignOut"){
		
		//$_SESSION['login_status'] = '';
		unset($_SESSION['login_status']);
		unset($_SESSION['uEmail']);
		unset($_SESSION['uPassword']);
		header("Location:byepage.php");
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
		<title>Reminders</title>
		
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
							<a href ="notifier.php"><i class = ' icon-bell'></i> Notifications</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<a href ="msgstream.php"><i class = '  icon-envelope'></i> Messages</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						</div>
					</div>
				</div>
			</div>
		</div>
	
	<!--CONTENT SECTION-->
		<div class = "container">
			<div class = "row-fluid">
				<div class = "span12">
					<h2 style  = "margin-left:1.3em;">Reminders for <?php echo $firstName;?></h2><hr>
				</div>
			</div>
			
			<div class = "row-fluid">
				<!--User's document list section.-->
				<div class = "span12">
					<?php
						echo "<br><br><br><br><br><div class = 'alert' style ='font-size:17px;'><br>You <b>don't have any reminder</b> at the moment, but sit tight, 
						our engineers are preparing something special for you.<br><br></div><br><br><br><br><br><br><br>
						<br><br>";
					?>
						
				</div>
			</div>
		</div>
	
	<!--FOOTER-->
		<div class="footr" style = "margin-top:1.2em;">
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