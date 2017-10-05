 <?php
include'/accessctrl.php';

//Varible initialisation.
$bizname = "";
$bizDescriptn = "";
$Extracts = "";
$Exts = "";
$logopath = "";
$errorMsg = "";
	
//retreiving some of the user's data.
$usersOtherNames = explode(" ", mysqli_result($result, 0,'user_other_names'));
$CUEmail = mysqli_result($result, 0,'user_Email');
$firstName = $usersOtherNames[0];
	

//function to check that all form variables are set.
function Check(){
	if( isset($_POST['BizRegBtn']) == "register"){
		return isset($_POST['bizname'], $_POST['bizDescriptn']);
	}
}

// check all our variables are set and move their value to database
  if(Check() == TRUE){
	$errJar = "";
	if(!empty($_POST['bizname']))
			{
			$bizname = $_POST['bizname'];
			}
		else
			{
			$errJar .= '<li>Please type in the <b>name of your Business or Organisation</b>.</li>';
			}
			
	if(!empty($_POST['bizDescriptn']))
			{
			$bizDescriptn = $_POST['bizDescriptn'];
			}
		else
			{
			$errJar .= '<li>Please provide a brief <b>Description of your Business or Organisation</b>.</li>';
			}

	//picking up uploaded logo
	$fileDestinatn = 'bizlogos/'.$_POST['bizname'];
	if(!file_exists($fileDestinatn)){
		mkdir($fileDestinatn, 0, true);
	}
	
	$safeExts = array("jpg", "jpeg", "gif", "png");
	
	$Extracts = explode(".", $_FILES["logofile"]["name"]);
	$Exts = end($Extracts);
	$Exts = strtolower($Exts);
	
	if((($_FILES["logofile"]["type"] == "image/gif")||($_FILES["logofile"]["type"] == "image/jpg")||
		($_FILES["logofile"]["type"] == "image/jpeg")||($_FILES["logofile"]["type"] == "image/pjpeg")||
		($_FILES["logofile"]["type"] == "image/png")) && in_array($Exts, $safeExts) && !empty($_FILES["logofile"])){
		
		$logopath = "bizlogos/".$_POST['bizname']."/". $_FILES["logofile"]["name"];
		move_uploaded_file($_FILES["logofile"]["tmp_name"], $logopath);
	}else{
		$errJar .="<li>Looks like you're <b>trying to upload a picture type we really don't deal with for now, or you're not uploading your logo.</b>.</li>";
	}
	
	if($bizname != "" && $bizDescriptn != "" && $logopath != ""){
		//Moving collected data to database
		$query1 = sprintf("INSERT INTO reg_biz_details(biz_name, biz_descriptn, biz_admin_email, biz_logo_path)
						VALUES('%s','%s','%s','%s')",
						mysqli_real_escape_string($dblink, $bizname),
						mysqli_real_escape_string($dblink, $bizDescriptn),
						mysqli_real_escape_string($dblink, $CUEmail),
						$logopath);
		$result1= mysqli_query($dblink, $query1);
		
		if($result1 == FALSE){
			$errorMsg .= "<ul>Oops! There is a little problem. Try registering again.</ul>";
		} else{
			header("Location:orgdashboard.php?admin=".$CUEmail."&amp;orgname=".stripslashes(htmlentities($bizname, ENT_QUOTES)));
			exit;
		}
	}else{
		$errorMsg = "<p> Please make sure to <b>fill out the entire form</b>.</p>";
	}
	
	if(!empty($errJar)){
				$errorMsg = "<p> Please make sure to <b>fill out the entire form always</b>.</p>";
				$errorMsg .= "<ul>" .$errJar. "</ul>";
			}
 }
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Organisation Registration - Pyruscape&trade;</title>
		
		<link rel = "stylesheet" type ="text/css" href = "css/bootstrap.min.css">
		<link rel = "stylesheet" type = "text/css" href = "css/codedoc.css">
		<link rel = "stylesheet" type ="text/css" href = "css/bootstrap-fileupload.min.css">
	</head>
	
	<body>
		<script src="js/jquery-1.8.1.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/bootstrap-fileupload.min.js"></script>
		
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
			?>
		
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
							<a href ="reminder.php"><i class = ' icon-check'></i> Reminders</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<!--PROFILE TITLE SECTION-->
		<div class = "container">
			<div class = "span12">
				<div class = "row-fluid">
					<h2 class = "regTitle">Business/Organisation Registration Page</h2>
				</div>
			</div>
		</div>
		
		<!--CONTENT SECTION-->
		<div class = "container">
			<div class = "bizregbody">
				<div class ="span12">
					<div class = "row-fluid">
						<div class = "span2">
						</div>
						<div class = "span8">
							<form method = "post" action = "orgregistration.php" enctype = "multipart/form-data">
								<table class = "table">
									<tr>
										<td>
										</td>
										<td>
											<div class = "fileupload fileupload-new" data-provides = "fileupload">
												<div class = "fileupload-new thumbnail" style = "width:142px; height:148px;">
													<img src = "img/logopix2.jpg"/>
												</div>
												
												<div class = "fileupload-preview fileupload-exists thumbnail" style = "max-width:142px; max-height:148px;">
												</div>
												
												<div>
													<span class = "btn btn-file">
														<span class = "fileupload-new"><i class = 'update'>Select Logo</i></span>
														<span class = "fileupload-exists"><i class = 'update'>Change</i></span>
														<input type ="file" id = "logofile" name = "logofile"/>
													</span>
													<a href = "#" class = "btn fileupload-exists" data-dismiss = "fileupload"><i class = 'update'>Remove</i></a>
												</div>
											</div>	
										</td>
										
									</tr>
									<tr>
										<td>Business/Organisation Name: &nbsp;</td><td><input class="span5" type = "text" name = "bizname" width = "100%" placeholder = "Type in your business name here"/></td>
									</tr>
									<tr>
										<td>Description of Business:  </td><td><textarea  class="span5" name = "bizDescriptn" width = "100%" placeholder = "Give a brief description of your business here"></textarea></td>
									</tr>
									<tr>
										<td></td><td><button class = "btn" name = "BizRegBtn" value = "register">Register</button></td>
									</tr>
								</table>
							</form>
						</div>
						<div class = "span2">
						</div>
					</div>
				</div>
			</div>
		</div><br>
		
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