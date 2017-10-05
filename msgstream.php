<?php
	include'/accessctrl.php';
	
	$sentStatus = "";
	$errJar = "";
	$msgRecepient = "";
	$msgBody = "";
	$emailAddress = "";
	$errJar = "";
	
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
	$useremail = mysqli_result($result, 0,'user_Email');
	
	//function to check that all form variables are set.
	function SetCheck(){
		if( isset($_POST['msgSend']) == "Submit"){
			return isset($_POST['msgRecepient'], $_POST['msgBody']);
		}
	}
	
	//function for ensuring that recepient's email is registered on the network.
	function emailcheck($emailAddress){
		
		//TEST POINT.
		//echo $emailAddress;
		
		$query3 = sprintf("SELECT * FROM users_details WHERE user_Email ='%s'",
							mysqli_real_escape_string($dblink, $emailAddress)
						);
		$result3 = mysqli_query($dblink, $query3);
		
		if(mysqli_num_rows($result3) > 0){
			return TRUE;
		}else{
			global $errJar;
			$errJar .= "<li>The <b>email</b> you're attempting to send a message to, is <b>not registered on Pyruscape</b>.</li>";
			return FALSE;
		}
	}
	
	//function to screen out sender's email address.
	function eCheck($emailAddress){
	
		//TEST POINTS.
		//echo $_SESSION['uEmail'];
		//echo $emailAddress; 
		
		if($emailAddress != $_SESSION['uEmail']){
			return TRUE;
		}else{
			global $errJar;
			$errJar .= "<li>You are <b>attempting to send an email to yourself</b>.</li>";
			return FALSE;
		}
	}
	
	// check all our variables are set and move their value to database.
	if(SetCheck() == TRUE)
    {
		if(!empty($_POST['msgRecepient']) && emailcheck($_POST['msgRecepient']) == TRUE && eCheck($_POST['msgRecepient']) == TRUE)
			{
				$msgRecepient = $_POST['msgRecepient'];
			}
		else
			{
				$errJar .= '<li>Please enter the <b>email address</b> of the intended recepient of your message.</li>.';
			}
			
		if(!empty($_POST['msgBody']))
			{
				$msgBody = $_POST['msgBody'];
			}
		else
			{
				$errJar .= '<li>The <b>body of your message</b> seems to be <b>empty</b>.</li>.';
			}
			
		//moving data to database.
		if($msgRecepient!="" && $msgBody!=""){
			$sendntime = date('h:i:sA');
			$sendndate = date('d-m-Y');
			
			include_once("/doc_cabinet_connect.php");
			
			$query2 = sprintf("INSERT INTO msgs_log (msg_body, msg_sendr, msg_recievr, doc_related_to, msg_sent_date, msg_sent_time) 
							VALUES ('%s','%s','%s','%s','%s','%s')",
							mysqli_real_escape_string($dblink, $msgBody),
							mysqli_real_escape_string($dblink, $useremail),
							mysqli_real_escape_string($dblink, $msgRecepient),
							"none",
							mysqli_real_escape_string($dblink, $sendndate),
							mysqli_real_escape_string($dblink, $sendntime)
							);
							
			$result2 = mysqli_query($dblink, $query2) or die("result2 related error: ". mysqli_error($dblink));
			
			if($result2 == FALSE ){
				$errJar = "<li><b>Your message was not sent.</b> please try again.</li>";
			}else{
				$sentStatus = "You <b>message</b> has been <b>sent</b>";
			}
		}else{
			if(!empty($errJar)){
				$errorMsg = "<p>Oops! looks like you didn't type anything into one of the fields or you entered a wrong value.</p>";
				$errorMsg .= "<ul>" .$errJar. "</ul>";
			}
		}
	}
	
	if($_SESSION['login_status'] != ''){
?>

<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $firstName;?>'s Messages - Pyruscape&trade;</title>
		
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
		
	<!--FLAG SECTION-->
			<?php
				if(!empty($errorMsg)){
					echo('<div class = "redflag"><div class ="container"><div class = "row-fluid"><div class = "errFlagMgr">'.$errorMsg.'</div></div></div></div>');
				}
				
				if(!empty($sentStatus)){
					echo('<div class = "greenflag"><div class = "container"><div class = "row-fluid"><div class = "successFlag">'.$sentStatus.'</div></div></div></div>');
				}
			?>
		
	<!--MENU SECTION-->
		<div class="menu">
			<div class="container">
				<div class ="span12">
					<div class = "menuLinks">
						<div class="menulist">
							<a href ="yourdocstream.php"><i class = '  icon-list-alt'></i> <?php echo $firstName;?>'s Document Stream</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<a href ="profilepage.php?uemail=<?php echo $useremail;?>"><i class = 'icon-user'></i> <?php echo $firstName;?>'s Profile</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<a href ="notifier.php"><i class = ' icon-bell'></i> Notifications</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
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
					<h2 style = "margin-left:1.3em;"><?php echo $firstName;?>'s Messages</h2>
					<a href = "#msgCompArea" class = "btn btn-success" style = "margin-left:35em; margin-top:-6em;">Compose Message</a><hr>
				</div>
			</div>
		</div>
		
		<div class = "container">
			<div class = "span12">
				<div class = "row-fluid">
					<!--User's document list section.-->
					<div class = "span8" style = "margin-left:1.3em;">
						<?php
							//retieving messages directed to current user.
							$query1 = sprintf("SELECT * FROM msgs_log WHERE msg_recievr ='%s' ORDER BY msg_id DESC",
											mysqli_real_escape_string($dblink, $useremail)
											);

							$result1 = mysqli_query($dblink, $query1);
							
							if(mysqli_num_rows($result1) > 0){
								echo "<div class = 'subtitle'><h4 class = 'subtitleBit'><p class = 'docHeadr' style = 'padding-top:0.5em;'>Current Messages for ".$firstName."</p></h4><hr></div>";
								for($i = 0; $i <mysqli_num_rows($result1); $i++ ){
									$mSendrEmail = mysqli_result($result1, $i,'msg_sendr');
									
									$query4 = sprintf("SELECT * FROM users_details WHERE user_Email ='%s'",
										mysqli_real_escape_string($dblink, $mSendrEmail)
									);
									$result4 = mysqli_query($dblink, $query4);
									$mSendrName = mysqli_result($result4, 0,'user_surname').", ".mysqli_result($result4, 0,'user_other_names');
									
									$mbody = mysqli_result($result1, $i,'msg_body');
									$msgSdate = mysqli_result($result1, $i,'msg_sent_date');
									$msgStime = mysqli_result($result1, $i,'msg_sent_time');
									
									$msgConversatn = "<div class ='streamunit'><div class ='streamunitBit'>
														<p><b>From:</b> <a href ='profilepage.php?uemail=$mSendrEmail'><em class = 'acpnyndata' style = 'font-size:14px;'>".$mSendrName."</em></a></p>
														<p>".$mbody."</p>
														<p><b>On:</b> <em class = 'acpnyndata'>".$msgSdate."</em> <b>Around:</b> <em class = 'acpnyndata'>".$msgStime."</em></p>
													</div><hr></div>";
									echo $msgConversatn;
								}
							}else{
								echo "<div class = 'alert'><div class = 'docHeadr' id = 'nomsg'><b>There is no message</b> that is directed to you at the moment.</div></div>";
							}
						?>
						
						<!-- field for composing and sending messages-->
						<div class = 'subtitle'>
							<h4 class = 'subtitleBit'><p class = "docHeadr" style = 'padding-top:0.5em;'>Compose and send a message below</p></h4><hr>
						</div>
				
						<form method="post" action = "<?php echo $_SERVER['PHP_SELF'];?>" id = "msgCompArea">
							<table class="table">
								<tr>
									<td>To:</td><td><input class="span6" type="text" placeholder="Email of document recepient" name= "msgRecepient" /></td>
								</tr>
								<tr>
									<td>Body of Message:</td><td><textarea  class="span6" rows = "6" placeholder="Write  your message here" name="msgBody"></textarea></td>
								</tr>
								<tr>
									<td></td>
									<td>
										<input class="btn btn-success" type="submit" name="msgSend" value="Send"/>
									</td>
								</tr>
							</table>
						</form>
					</div>
					
					<div class = "span2">
					</div>
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