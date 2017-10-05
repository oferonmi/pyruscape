<?php
	//setting time zone.
	date_default_timezone_set('Africa/Lagos');

	include("/doc_cabinet_connect.php");
	$dblink = dbconnect('doc_cabinet');

	$errJar = "";
	$errMsg = "";
	$pwEmail = "";
	$dispScript = "";
	$actcode = "";
	$NewPW = "";
	$ReNewPw = "";
	
	global $dispScript, $clonedEmail, $PEmail;
	

/*------------------------------------------------------------------------------------------------------------------------
	Handles activation code generation.
--------------------------------------------------------------------------------------------------------------------------*/
	if(isset($_POST['emailSubbtn']) == "change"){
		if(!empty($_POST['pwEmail']))
			{
				global $clonedEmail;
				$pwEmail = $_POST['pwEmail'];
				$clonedEmail = $pwEmail;
				
				//Generating of the activation code.
				$babyACode = rand();
				$aCode = "".$babyACode."";
				
				$queryi = sprintf("SELECT users_details WHERE user_Email = '%s'",
							mysqli_real_escape_string($dblink, $pwEmail));
				$resulti  = mysqli_query($dblink, $queryi);
				
				if($resulti > 0){
				
					$query = sprintf("UPDATE users_details SET act_code = '%s' WHERE user_Email ='%s'",
								mysqli_real_escape_string($dblink, $aCode),
								mysqli_real_escape_string($dblink, $pwEmail));
					$result  = mysqli_query($dblink, $query);
					
					//Sending email with activation code to user.
					if($result == TRUE){
						$emailBody = "Hi,<br><br>
									We got a <b>complaint</a> from you, of recent, <b>about forgetting the password</b> to your Pyruscape Account.<br><br>.
										
									Use the Activation code below to change the password to your Account.<br><br>
									
									The Activation code is : ".$aCode."<br><br>
										
									If you have any problem using <b>Pyruscape</b>, you can reach us at <b>complaints@pyruscape.com</b><br><br>
									
									Regards,<br><br><br>
										
									The Pyruscape Team";
										
						mail($pwEmail,"Keep Your Pyrusape Account Active", $emailBody, "From: Pyruscape");
					
						
						//Preparing the new password change display page script.
						$dispScript = '<div class = "row-fluid">
						<div class = "span12"><h3 class = "docHeadr" style = "margin-left:auto; margin-right:auto; margin-top:1em;">We\'ve sent an Activation code to your email. Change your password here.</h3><hr></div>
						</div>
						
						<div class = "row-fluid">
							<div class = "span3"></div>
							<div class = "span6">
								<div class="hero-unit" style = "margin-top:1.5em; margin-bottom:3em;">
									<div style = "margin-left:6.5em;"><img src="img/pyruspix.jpg" width="150" height="113" alt="pyruspix"/></div>
									<form method = "post" action = "PassChanger.php?PEmail='.$pwEmail.'" style = "margin-top:1em;">
										<p>Use a password that can\'t be easily guessed by  others.</p>
										<input class = "span12" type = "text" name = "actcode" placeholder = "Enter the Activation Code here"/><br>
										<input class = "span12" type = "password" name = "NewPW" placeholder = "Enter a new password here"/><br>
										<input class = "span12" type = "password" name = "ReNewPw" placeholder = "Re-enter your new password here"/><br>
										<button class = "btn btn-success" name = "PWchangebtn" value = "PWchange">Change Password</button>
									</form>
								</div>
							</div>
							<div class = "span3"></div>
						</div>';
					}
				}else{
					$dispScript = '<div class = "row-fluid">
							<div class = "span12"><h3 class = "docHeadr" style = "margin-left:auto; margin-right:auto; margin-top:1em;">If you forgot your password, then you are on the right page.</h3><hr></div>
						</div>
						<div class = "row-fluid">
							<div class = "span3"></div>
							<div class = "span6">
								<div class="hero-unit" style = "margin-top:1.5em; margin-bottom:3em;">
									<div style = "margin-left:6.5em;"><img src="img/pyruspix.jpg" width="150" height="113" alt="pyruspix" style="opacity:0.4;filter:alpha(opacity=40)" /></div>
									<form method = "post" action = "PassChanger.php" style = "margin-top:1em;">
										<p>Enter your email address into the field below so that we can help you resolve the password issue you\'re having.</p>
										<input class = "span12" type = "text" name = "pwEmail" placeholder = "Provide your email address here"/><br>
										<button class = "btn btn-success" name = "emailSubbtn" value = "change">Submit</button>
									</form>
								</div>
							</div>
							<div class = "span3"></div>
						</div>';
						
					$errMsg = '<div class = "page-alert"><div class = "alert" style = "background-color:#ffcc99;"><div class = "redflag"><div class ="container"><div class = "row-fluid">
						<div class = "errFlagMgr">There is <b>no Pyruscape Account that uses the email you provided</b>.Please gives us the email you used to register on Pyruscape.</div></div></div></div></div></div>';
				}
			}
		else
			{
				$errMsg = '<div class = "page-alert"><div class = "alert" style = "background-color:#ffcc99;"><div class = "redflag"><div class ="container"><div class = "row-fluid">
				<div class = "errFlagMgr">Please enter your <b>email address</b>.</div></div></div></div></div></div>';
			}
	}
	
/*-------------------------------------------------------------------------------------------------------------------------
Handling password change.
--------------------------------------------------------------------------------------------------------------------------*/
		if(isset($_POST['PWchangebtn']) == 'PWchange'){
			if(isset($_GET['PEmail']) !=""){
			$PEmail = $_GET['PEmail'];
		}
		
		//Preparing the new password change display page script.
				$dispScript = '<div class = "row-fluid">
				<div class = "span12"><h3 class = "docHeadr" style = "margin-left:auto; margin-right:auto; margin-top:1em;">We\'ve sent an Activation code to your email. Change your password here.</h3><hr></div>
				</div>
				
				<div class = "row-fluid">
					<div class = "span3"></div>
					<div class = "span6">
						<div class="hero-unit" style = "margin-top:1.5em; margin-bottom:3em;">
							<div style = "margin-left:6.5em;"><img src="img/pyruspix.jpg" width="150" height="113" alt="pyruspix"/></div>
							<form method = "post" action = "PassChanger.php?PEmail='.$PEmail.'" style = "margin-top:1em;">
								<p>Use a password that can\'t be easily guessed by  others.</p>
								<input class = "span12" type = "text" name = "actcode" placeholder = "Enter the Activation Code here"/><br>
								<input class = "span12" type = "password" name = "NewPW" placeholder = "Enter a new password here"/><br>
								<input class = "span12" type = "password" name = "ReNewPw" placeholder = "Re-enter your new password here"/><br>
								<button class = "btn btn-success" name = "PWchangebtn" value = "PWchange">Change Password</button>
							</form>
						</div>
					</div>
					<div class = "span3"></div>
				</div>';
		
		if(!empty($_POST['actcode'])){
			$actcode = $_POST['actcode'];
		}else{
			$errJar = '<li>Please enter your <b>Activation code</b>.</li>';
		}
		
		if(!empty($_POST['NewPW'])){
			$NewPW = $_POST['NewPW'];
		}else{
			$errJar .= '<li>Please enter your <b>New Password</b>.</li>';
		}
		
		if(!empty($_POST['ReNewPw'])){
			$ReNewPw = $_POST['ReNewPw'];
		}else{
			$errJar .= '<li>Please <b>verify</b> your <b>New Password</b>.</li>';
		}
		
		if($NewPW != $ReNewPw){
			$errMsg = '<div class = "page-alert"><div class = "alert" style = "background-color:#ffcc99;">
			<div class = "redflag"><div class ="container"><div class = "row-fluid">
			<div class = "errFlagMgr">Looks like the <b>Passwords</b> you entered don\'t match.</div></div></div></div></div></div>';
		}
		
		if(($NewPW == $ReNewPw) && $actcode !="" && $errJar == "" && $errMsg == ""){
			//Verifying Activation code.
			$query2 = sprintf("SELECT * FROM users_details WHERE user_Email ='%s' AND act_code ='%s'",
							mysqli_real_escape_string($dblink, $PEmail),
							mysqli_real_escape_string($dblink, $actcode));
			$result2 = mysqli_query($dblink, $query2);
			
			if(mysqli_num_rows($result2)> 0){
				//Carrying out the actual change of password.
				$query3 = sprintf("UPDATE users_details SET user_PW = '%s' WHERE user_Email ='%s'",
							md5(mysqli_real_escape_string($dblink, $NewPW)),
							mysqli_real_escape_string($dblink, $PEmail));
				$result3 = mysqli_query($dblink, $query3);
				
				if($result3 == TRUE){
					//creating notification on password change
					$passCngNotif = "Your <b>password</b> was <b>sucessfully changed</b>. 
					You can now Sign In and start using you account.";
					
					header("Location:signin.php?pNote=".$passCngNotif);
					exit;
				}
			}
		}
		
		if(!empty($errJar)){
			$errMsg = '<div class = "page-alert"><div class = "alert" style = "background-color:#ffcc99;">
			<div class = "redflag"><div class ="container"><div class = "row-fluid">
			<div class = "errFlagMgr"><ul>'.$errJar.'</ul></div></div></div></div></div></div>';
		}
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Change of Password - Pyruscape&trade;</title>
		
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
					<form class="form-inline" method="post" action="signin.php">
						<div id ="headr-login">
							<input type="text" class="input-small" placeholder="Email" name  = "uEmail" >
							 <input type="password" class="input-small" placeholder="Password" name = "uPassword">
							 <label class="checkbox" style = "color:#ffffff;">
								<input type="checkbox"> Remember me
							 </label>
							 <button type="submit" class="btn" name = "SigninSubmit" value = "Sign in">Sign in</button>&nbsp;
							 <button type="submit" class="btn" name ="SignUp" value = "SignUp">Register</button><br><br>
						</div>
					</form>
				  </div>
				</div>
			  </div>
			</div>
		</div>
	
	<!--FLAG SECTION-->
			<?php
				if(!empty($errMsg)){
					echo $errMsg; //('<div class = "redflag"><div class ="container"><div class = "row-fluid"><div class = "errFlagMgr">'.$errorMsg.'</div></div></div></div>');
				}
			?>
	
	<!--CONTENT SECTION-->
		<div class = "container">
			<div class = "span12">
				<?php
					if($dispScript == ""){
						echo '<div class = "row-fluid">
							<div class = "span12"><h3 class = "docHeadr" style = "margin-left:auto; margin-right:auto; margin-top:1em;">If you forgot your password, then you are on the right page.</h3><hr></div>
						</div>
						<div class = "row-fluid">
							<div class = "span3"></div>
							<div class = "span6">
								<div class="hero-unit" style = "margin-top:1.5em; margin-bottom:3em;">
									<div style = "margin-left:6.5em;"><img src="img/pyruspix.jpg" width="150" height="113" alt="pyruspix" style="opacity:0.4;filter:alpha(opacity=40)" /></div>
									<form method = "post" action = "PassChanger.php" style = "margin-top:1em;">
										<p>Enter your email address into the field below so that we can help you resolve the password issue you\'re having.</p>
										<input class = "span12" type = "text" name = "pwEmail" placeholder = "Provide your email address here"/><br>
										<button class = "btn btn-success" name = "emailSubbtn" value = "change">Submit</button>
									</form>
								</div>
							</div>
							<div class = "span3"></div>
						</div>';
					}else{
						echo $dispScript;
					}
				?>
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