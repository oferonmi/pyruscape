<?php
	include'/accessctrl.php';
	
	//variable declarations.
	$title = "";
	$recievercard = "";
	$countAudit = 0;
	$resFullname = "";
	$resDept = "";
	$resOrg = "";
	
	if(isset($_POST['SignOut']) == "SignOut"){
		
		//$_SESSION['login_status'] = '';
		unset($_SESSION['login_status']);
		unset($_SESSION['uEmail']);
		unset($_SESSION['uPassword']);
		header("Location:byepage.php");
		exit;
	}
	
	//collection of variables passed in URL.
	if(isset($_GET['title']) !=""){
		$title = urldecode($_GET['title']);
	}
	
	//retreiving some of the user's data
	$usersOtherNames = explode(" ", mysqli_result($result, 0,'user_other_names'));
	$firstName = $usersOtherNames[0];
	$CUEmail = mysqli_result($result, 0,'user_Email');
	$fullname = mysqli_result($result, 0,'user_surname').', '.mysqli_result($result, 0,'user_other_names');
	
	//Accessing document details
	$query2 = sprintf("SELECT * FROM doc_files_details WHERE doc_title ='%s'",
						mysqli_real_escape_string($dblink, $title)
					);	
	$result2 = mysqli_query($dblink, $query2) or die("Error in query: $query2.".mysqli_error($dblink));
	if (mysqli_num_rows($result2) != FALSE){
		$docID = mysqli_result($result2, 0,'doc_id');
		$uemail = mysqli_result($result2, 0,'user_email');
	}
	
	if($_SESSION['login_status'] != ''){
?>

<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $title;?>'(s) Track - Pyruscape&trade;</title>
		
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
						<?php
							$docpgurl = 'docpage.php?docID='.$docID.'&amp;title='.$title.'&amp;email='.$uemail;
						?>
							<a href = "<?php echo $docpgurl;?>"><i class = '  icon-file'></i> Document's Page</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<a href ="yourdocstream.php"><i class = '  icon-list-alt'></i> <?php echo $firstName;?>'s Document Stream</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<a href ="profilepage.php?uemail=<?php echo $CUEmail;?>"><i class = 'icon-user'></i> <?php echo $firstName;?>'s Profile</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<a href ="notifier.php"><i class = ' icon-bell'></i> Notifications</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<a href ="msgstream.php"><i class = '  icon-envelope'></i> Messages</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<a href =""><i class = ' icon-check'></i> Reminders</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						</div>
					</div>
				</div>
			</div>
		</div>
	
	<!--CONTENT SECTION-->
		<div class = "container">
			<div class = "row-fluid">
				<div class = "span12">
					<h2 class = "docHeadr"><?php echo $title;?>'s Dispatch Trail</h2>
					<p class = "docHeadr" style = "font-size:16px;">Here is a Visual of <b>who got this document lately</b> and <b>who it passed through</b>.</p><hr>
				</div>
			</div>
		</div>
		
		<div class = "container">
			<div class = "span12">
				<div class = "row-fluid">
					<div class = "span2"></div>
					<div class = "span8">
						<?php
							//Accessing minute database.	
							$query3 = sprintf("SELECT * FROM doc_minutes  WHERE doc_title ='%s' ORDER BY user_ID DESC",
												mysqli_real_escape_string($dblink, $title)
											);
							$result3 = mysqli_query($dblink, $query3) or die("Error in query: $query3.".mysqli_error($dblink));
							
							if(mysqli_num_rows($result3)> 0){
								$recievercard = "";
								$countAudit = mysqli_num_rows($result3) - 1;
								//echo $countAudit."<br>";
								//echo mysqli_num_rows($result3);
								
								/**while($row = mysqli_fetch_object($result3))**/for($i = 0; $i < mysqli_num_rows($result3); $i++){
									//Access concerned user's details.
									$query4 = sprintf("SELECT * FROM users_details WHERE user_Email ='%s'",
														mysqli_real_escape_string($dblink, mysqli_result($result3, $i,'minute_respondant_email') /**$row->minute_respondant_email**/)
													);	
									$result4 = mysqli_query($dblink, $query4) or die("Error in query: $query4.".mysqli_error($dblink));
									
									$resFullname = mysqli_result($result4, 0,'user_surname').', '.mysqli_result($result4, 0,'user_other_names');
									$resDept = mysqli_result($result4, 0,'dept_of_employ');
									$resOrg = mysqli_result($result4, 0,'company_of_employ');
									$resEmail = mysqli_result($result3, $i,'minute_respondant_email') /**$row->minute_respondant_email**/;
									$resPixpath = mysqli_result($result4, 0,'profile_pix_path');
									
									//check for profile picture.
									if(!empty($resPixpath)){
										$trailPix = $resPixpath;
									}else{
										$trailPix = 'img/propix_reduced.jpg';
									}
									
									//monitor direction arrow count.
									if($countAudit != 0){
										$trackarrRow = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										<img src = 'img/trackarrow.jpg'/><br><br>";
									}else{
										$trackarrRow = "";
									}
									
									//compile details of concerned users for each card along document track.
									$recievercard .= "<div>
										<table>
											<tr class = 'streamunit'>
												<td><img src = '".$trailPix."'  style = 'max-width:115px; max-height:127px;'/></td>
												<td>
													<br>&nbsp;&nbsp;<em class = 'update'><a href ='profilepage.php?uemail=$resEmail' class = 'profilelead'>".$resFullname."</a></em>&nbsp;&nbsp;<hr>
													&nbsp;&nbsp;From <b>".$resDept."</b> At <b><em class = 'update'>".$resOrg."</em></b>&nbsp;&nbsp;<hr>
													&nbsp;&nbsp;Got document on: <b><em class = 'acpnyndata'>".mysqli_result($result3, $i,'date_of_dis') /**$row->date_of_dis**/."</em></b>&nbsp;&nbsp;
													at: <b><em class = 'acpnyndata'>".mysqli_result($result3, $i,'time_of_dis') /**$row->time_of_dis**/."</em></b><br>&nbsp;&nbsp;
												</td>
											</tr>
										</table><br>
										".$trackarrRow."
									</div>";
									
									if($recievercard != ""){
										echo $recievercard;
									}
									
									$countAudit = $countAudit - 1;
								}
							}else{
								echo"<div class = 'text-center'>
									<br><br><br><p class = 'alert'>The document; <u><i>".$title."</i></u>, 
									<b>has not been dispatched to anyone</b> on the <b>Pyruscape Network</b>.</p><br><br><br>
								</div>";
							}
						?>
					</div>
					<div class = "span2"></div>
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