<?php
	include'/accessctrl.php';
	
	//collection of variables passed in URL.
	if($_GET['uemail'] !=""){
		$assocEmail = $_GET['uemail'];
	}
	
	if(isset($_GET['rNote']) !=""){
		$rNote = $_GET['rNote'];
	}
	
	//Email address and other details of the user requesting profile
	$CUEmail = mysqli_result($result, 0,'user_Email');
	$usersOtherNames1 = explode(" ", mysqli_result($result, 0,'user_other_names'));
	$firstName1 = $usersOtherNames1[0];
	$fullname1 = mysqli_result($result, 0,'user_surname').', '.mysqli_result($result, 0,'user_other_names');
	
	//collect profile update details.
	//for profile piture and signature
	if(isset($_POST['pixchange']) == "change"){
	
		if($_FILES["profilepixfile"]["tmp_name"] !=""){
			
			//picking up uploaded profile picture.
			$fileDestinatn = 'users_img/profile_pix/'.$fullname1;
			if(!file_exists($fileDestinatn)){
				mkdir($fileDestinatn, 0, true);
			}
			
			$safeExts = array("jpg", "jpeg", "gif", "png");
			
			$Extracts = explode(".", $_FILES["profilepixfile"]["name"]);
			$Exts = end($Extracts);
			$Exts = strtolower($Exts);
			
			if((($_FILES["profilepixfile"]["type"] == "image/gif")||($_FILES["profilepixfile"]["type"] == "image/jpg")||
				($_FILES["profilepixfile"]["type"] == "image/jpeg")||($_FILES["profilepixfile"]["type"] == "image/pjpeg")||
				($_FILES["profilepixfile"]["type"] == "image/png")) && in_array($Exts, $safeExts) && !empty($_FILES["profilepixfile"])){
				
				$proflpixpath = "users_img/profile_pix/".$fullname1."/". $_FILES["profilepixfile"]["name"];
				move_uploaded_file($_FILES["profilepixfile"]["tmp_name"], $proflpixpath);
			}else{
				$errJar .="<li>Looks like you're <b>trying to upload a profile picture that has a format we don't support for now</b>.</li>";
			}
			
			//storing profile picture file path in database
			if($proflpixpath != ""){
				$query2 = sprintf("UPDATE users_details SET profile_pix_path = '%s' WHERE user_Email = '%s'",
								mysqli_real_escape_string($dblink, $proflpixpath),
								mysqli_real_escape_string($dblink, $CUEmail)
							);

			$result2 = mysqli_query($dblink, $query2);
			}
		}
		
		if($_FILES["Sigpixfile"]["tmp_name"] !=""){
			
			//picking up uploaded signature picture.
			$fileDestinatn = 'users_img/SigImg/'.$fullname1;
			if(!file_exists($fileDestinatn)){
				mkdir($fileDestinatn, 0, true);
			}
			
			$safeExts = array("jpg", "jpeg", "gif", "png");
			
			$Extracts = explode(".", $_FILES["Sigpixfile"]["name"]);
			$Exts = end($Extracts);
			$Exts = strtolower($Exts);
			
			if((($_FILES["Sigpixfile"]["type"] == "image/gif")||($_FILES["Sigpixfile"]["type"] == "image/jpg")||
				($_FILES["Sigpixfile"]["type"] == "image/jpeg")||($_FILES["Sigpixfile"]["type"] == "image/pjpeg")||
				($_FILES["Sigpixfile"]["type"] == "image/png")) && in_array($Exts, $safeExts) && !empty($_FILES["Sigpixfile"])){
				
				$sigpath = "users_img/SigImg/".$fullname1."/". $_FILES["Sigpixfile"]["name"];
				move_uploaded_file($_FILES["Sigpixfile"]["tmp_name"], $sigpath);
			}else{
				$errJar .="<li>Looks like you're <b>trying to upload a Signature that has a picture format we don't support for now</b>.</li>";
			}
			
			//storing Signature picture file path in database
			if($sigpath != ""){
				$query2 = sprintf("UPDATE users_details SET user_signature_path = '%s' WHERE user_Email = '%s'",
								mysqli_real_escape_string($dblink, $sigpath),
								mysqli_real_escape_string($dblink, $CUEmail)
							);

			$result2 = mysqli_query($dblink, $query2);
			}
		}
	}
	
	//for gender.
	if(isset($_POST['upbtn0']) == "submit" && isset($_POST['txt0']) != ""){
			$gndr = $_POST['txt0'];
			
			//query for updating database.
			$query2 = sprintf("UPDATE users_details SET gender = '%s' WHERE user_Email = '%s'",
								mysqli_real_escape_string($dblink, $gndr),
								mysqli_real_escape_string($dblink, $CUEmail)
							);

			$result2 = mysqli_query($dblink, $query2);
	}
	//for phone number.
	if(isset($_POST['upbtn1']) == "submit" && isset($_POST['txt1']) != ""){
			$fNumber = $_POST['txt1'];
			
			//query for updating database.
			$query2 = sprintf("UPDATE users_details SET user_phone_number = '%s' WHERE user_Email = '%s'",
								mysqli_real_escape_string($dblink, $fNumber),
								mysqli_real_escape_string($dblink, $CUEmail)
							);

			$result2 = mysqli_query($dblink, $query2);
	}
	//for Country of Residence.
	if(isset($_POST['upbtn2']) == "submit" && isset($_POST['txt2']) != ""){
			$CountofR = $_POST['txt2'];
			
			//query for updating database.
			$query2 = sprintf("UPDATE users_details SET user_country_of_res = '%s' WHERE user_Email = '%s'",
								mysqli_real_escape_string($dblink, $CountofR),
								mysqli_real_escape_string($dblink, $CUEmail)
							);

			$result2 = mysqli_query($dblink, $query2);
	}
	//for Current Job Title.
	if(isset($_POST['upbtn3']) == "submit" && isset($_POST['txt3']) != ""){
			$jTitle = $_POST['txt3'];
			
			//query for updating database.
			$query2 = sprintf("UPDATE users_details SET job_title = '%s' WHERE user_Email = '%s'",
								mysqli_real_escape_string($dblink, $jTitle),
								mysqli_real_escape_string($dblink, $CUEmail)
							);

			$result2 = mysqli_query($dblink, $query2);
	}
	//for Current Place of Employment.
	if(isset($_POST['upbtn4']) == "submit" && isset($_POST['txt4']) != ""){
			$cPofE = $_POST['txt4'];
			
			//query for updating database.
			$query2 = sprintf("UPDATE users_details SET company_of_employ = '%s' WHERE user_Email = '%s'",
								mysqli_real_escape_string($dblink, $cPofE),
								mysqli_real_escape_string($dblink, $CUEmail)
							);

			$result2 = mysqli_query($dblink, $query2);
	}
	//for Current Department.
	if(isset($_POST['upbtn5']) == "submit" && isset($_POST['txt5']) != ""){
			$cDofE = $_POST['txt5'];
			
			//query for updating database.
			$query2 = sprintf("UPDATE users_details SET dept_of_employ = '%s' WHERE user_Email = '%s'",
								mysqli_real_escape_string($dblink, $cDofE),
								mysqli_real_escape_string($dblink, $CUEmail)
							);

			$result2 = mysqli_query($dblink, $query2);
	}
	//for Previous Places of Employment.
	if(isset($_POST['upbtn6']) == "submit" && isset($_POST['txt6']) != ""){
			$PPofE = $_POST['txt6'];
			
			//query for updating database.
			$query2 = sprintf("UPDATE users_details SET pre_comp_of_employ = '%s' WHERE user_Email = '%s'",
								mysqli_real_escape_string($dblink, $PPofE),
								mysqli_real_escape_string($dblink, $CUEmail)
							);

			$result2 = mysqli_query($dblink, $query2);
	}
	//for Higher Institution(s) Attended.
	if(isset($_POST['upbtn7']) == "submit" && isset($_POST['txt7']) != ""){
			$HIattend = $_POST['txt7'];
			
			//query for updating database.
			$query2 = sprintf("UPDATE users_details SET hi_inst_attended = '%s' WHERE user_Email = '%s'",
								mysqli_real_escape_string($dblink, $HIattend),
								mysqli_real_escape_string($dblink, $CUEmail)
							);

			$result2 = mysqli_query($dblink, $query2);
	}
	//for Other Institution(s) Attended.
	if(isset($_POST['upbtn8']) == "submit" && isset($_POST['txt8']) != ""){
			$OIattend = $_POST['txt8'];
			
			//query for updating database.
			$query2 = sprintf("UPDATE users_details SET other_inst_attended = '%s' WHERE user_Email = '%s'",
								mysqli_real_escape_string($dblink, $OIattend),
								mysqli_real_escape_string($dblink, $CUEmail)
							);

			$result2 = mysqli_query($dblink, $query2);
	}
	//for Other Qualifications.
	if(isset($_POST['upbtn9']) == "submit" && isset($_POST['txt9']) != ""){
			$OQual = $_POST['txt9'];
			
			//query for updating database.
			$query2 = sprintf("UPDATE users_details SET other_qualifn = '%s' WHERE user_Email = '%s'",
								mysqli_real_escape_string($dblink, $OQual),
								mysqli_real_escape_string($dblink, $CUEmail)
							);

			$result2 = mysqli_query($dblink, $query2);
	}
	//for Technical Interest.
	if(isset($_POST['upbtn10']) == "submit" && isset($_POST['txt10']) != ""){
			$TechInterest = $_POST['txt10'];
			
			//query for updating database.
			$query2 = sprintf("UPDATE users_details SET tech_interest = '%s' WHERE user_Email = '%s'",
								mysqli_real_escape_string($dblink, $TechInterest),
								mysqli_real_escape_string($dblink, $CUEmail)
							);

			$result2 = mysqli_query($dblink, $query2);
	}
	//for General Interest.
	if(isset($_POST['upbtn11']) == "submit" && isset($_POST['txt11']) != ""){
			$GenInterest = $_POST['txt11'];
			
			//query for updating database.
			$query2 = sprintf("UPDATE users_details SET general_interest = '%s' WHERE user_Email = '%s'",
								mysqli_real_escape_string($dblink, $GenInterest),
								mysqli_real_escape_string($dblink, $CUEmail)
							);

			$result2 = mysqli_query($dblink, $query2);
	}
	//for 'What Can you Do' section.
	if(isset($_POST['upbtn12']) == "submit" && isset($_POST['txt12']) != ""){
			$WCYD = $_POST['txt12'];
			
			//query for updating database.
			$query2 = sprintf("UPDATE users_details SET know_how = '%s' WHERE user_Email = '%s'",
								mysqli_real_escape_string($dblink, $WCYD),
								mysqli_real_escape_string($dblink, $CUEmail)
							);

			$result2 = mysqli_query($dblink, $query2);
	}
	//for 'About Yourself' section.
	if(isset($_POST['upbtn13']) == "submit" && isset($_POST['txt13']) != ""){
			$AYself = $_POST['txt13'];
			
			//query for updating database.
			$query2 = sprintf("UPDATE users_details SET bio = '%s' WHERE user_Email = '%s'",
								mysqli_real_escape_string($dblink, $AYself),
								mysqli_real_escape_string($dblink, $CUEmail)
							);

			$result2 = mysqli_query($dblink, $query2);
	}
	
	
	//retieving profile info of requested profile.
	$query1 = sprintf("SELECT * FROM users_details WHERE user_Email ='%s'",
					mysqli_real_escape_string($dblink, $assocEmail)
					);

	$result1 = mysqli_query($dblink, $query1);
	
	//retreiving some of the user's data
	$usersOtherNames = explode(" ", mysqli_result($result1, 0,'user_other_names'));
	$firstName = $usersOtherNames[0];
	$fullname = mysqli_result($result1, 0,'user_surname').', '.mysqli_result($result1, 0,'user_other_names');
	$gender = mysqli_result($result1, 0,'gender');
	$fone = mysqli_result($result1, 0,'user_phone_number');
	$comp = mysqli_result($result1, 0,'company_of_employ');
	$dept = mysqli_result($result1, 0,'dept_of_employ');
	$PPlaceofE = mysqli_result($result1, 0,'pre_comp_of_employ');
	$hiInst = mysqli_result($result1, 0,'hi_inst_attended');
	$otherInst = mysqli_result($result1, 0,'other_inst_attended');
	$otherQual = mysqli_result($result1, 0,'other_qualifn');
	$techInterest = mysqli_result($result1, 0,'tech_interest');
	$genInterest = mysqli_result($result1, 0,'general_interest');
	$knowhow = mysqli_result($result1, 0,'know_how');
	$bio = mysqli_result($result1, 0,'bio');
	$jobTitle = mysqli_result($result1, 0,'job_title');
	$CountryofRes = mysqli_result($result1, 0,'user_country_of_res');
	$ppixpath = mysqli_result($result1, 0,'profile_pix_path');
	$spixpath = mysqli_result($result1, 0,'user_signature_path');
	
	//checking to see if edit option should be displayed.
	if($assocEmail == $CUEmail){
		for($i=0;$i<14;$i++){
			$editOp1[$i] = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button id = 'editbtn".$i."' class = 'btn'";
			$editOp2[$i] = "><i class = 'update'>Edit</i></button>";
			$edit[$i] = "onclick = edit".$i."()";
		}
	}else{
		for($i=0;$i<14;$i++){
			$editOp1[$i] ="";
			$editOp2[$i] ="";
			$edit[$i] ="";
		}
	}
	
	
	
?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $firstName;?>'s Profile - Pyruscape&trade;</title>
		
		<link rel = "stylesheet" type ="text/css" href = "css/bootstrap.min.css">
		<link rel = "stylesheet" type = "text/css" href = "css/codedoc.css">
		<link rel = "stylesheet" type ="text/css" href = "css/bootstrap-fileupload.min.css">
		
		<script type="text/javascript">
			//functions to handle profile details update. 
		function edit0()
		{
			document.getElementById('editbtn0').disabled = "disabled";
			var editArea = document.getElementById('edit0');
			
			var updateform = document.createElement("form");
			updateform.setAttribute("method", "post");
			updateform.setAttribute("action", "profilepage.php?uemail=<?php echo $assocEmail;?>");
						
			var eHandle = document.createElement("textarea");
			eHandle.setAttribute("name", "txt0");
						
			var updatebtn = document.createElement("input");
			updatebtn.setAttribute("type", "submit");
			updatebtn.setAttribute("value", "Update");
			updatebtn.setAttribute("class", "btn");
			updatebtn.setAttribute("name", "upbtn0");
						
			updateform.appendChild(eHandle);
			updateform.appendChild(updatebtn);
						
			editArea.appendChild(updateform);
		}
		
		function edit1()
		{
			document.getElementById('editbtn1').disabled = "disabled";
			var editArea = document.getElementById('edit1');
			
			var updateform = document.createElement("form");
			updateform.setAttribute("method", "post");
			updateform.setAttribute("action", "profilepage.php?uemail=<?php echo $assocEmail;?>");
						
			var eHandle = document.createElement("textarea");
			eHandle.setAttribute("name", "txt1");
						
			var updatebtn = document.createElement("input");
			updatebtn.setAttribute("type", "submit");
			updatebtn.setAttribute("value", "Update");
			updatebtn.setAttribute("class", "btn");
			updatebtn.setAttribute("name", "upbtn1");
						
			updateform.appendChild(eHandle);
			updateform.appendChild(updatebtn);
						
			editArea.appendChild(updateform);
		}
		
		function edit2()
		{
			document.getElementById('editbtn2').disabled = "disabled";
			var editArea = document.getElementById('edit2');
			
			var updateform = document.createElement("form");
			updateform.setAttribute("method", "post");
			updateform.setAttribute("action", "profilepage.php?uemail=<?php echo $assocEmail;?>");
						
			var eHandle = document.createElement("textarea");
			eHandle.setAttribute("name", "txt2");
						
			var updatebtn = document.createElement("input");
			updatebtn.setAttribute("type", "submit");
			updatebtn.setAttribute("value", "Update");
			updatebtn.setAttribute("class", "btn");
			updatebtn.setAttribute("name", "upbtn2");
						
			updateform.appendChild(eHandle);
			updateform.appendChild(updatebtn);
						
			editArea.appendChild(updateform);
		}
		
		function edit3()
		{
			document.getElementById('editbtn3').disabled = "disabled";
			var editArea = document.getElementById('edit3');
			
			var updateform = document.createElement("form");
			updateform.setAttribute("method", "post");
			updateform.setAttribute("action", "profilepage.php?uemail=<?php echo $assocEmail;?>");
						
			var eHandle = document.createElement("textarea");
			eHandle.setAttribute("name", "txt3");
						
			var updatebtn = document.createElement("input");
			updatebtn.setAttribute("type", "submit");
			updatebtn.setAttribute("value", "Update");
			updatebtn.setAttribute("class", "btn");
			updatebtn.setAttribute("name", "upbtn3");
						
			updateform.appendChild(eHandle);
			updateform.appendChild(updatebtn);
						
			editArea.appendChild(updateform);
		}
		
		function edit4()
		{
			document.getElementById('editbtn4').disabled = "disabled";
			var editArea = document.getElementById('edit4');
			
			var updateform = document.createElement("form");
			updateform.setAttribute("method", "post");
			updateform.setAttribute("action", "profilepage.php?uemail=<?php echo $assocEmail;?>");
						
			var eHandle = document.createElement("textarea");
			eHandle.setAttribute("name", "txt4");
						
			var updatebtn = document.createElement("input");
			updatebtn.setAttribute("type", "submit");
			updatebtn.setAttribute("value", "Update");
			updatebtn.setAttribute("class", "btn");
			updatebtn.setAttribute("name", "upbtn4");
						
			updateform.appendChild(eHandle);
			updateform.appendChild(updatebtn);
						
			editArea.appendChild(updateform);
		}
		
		function edit5()
		{
			document.getElementById('editbtn5').disabled = "disabled";
			var editArea = document.getElementById('edit5');
			
			var updateform = document.createElement("form");
			updateform.setAttribute("method", "post");
			updateform.setAttribute("action", "profilepage.php?uemail=<?php echo $assocEmail;?>");
						
			var eHandle = document.createElement("textarea");
			eHandle.setAttribute("name", "txt5");
						
			var updatebtn = document.createElement("input");
			updatebtn.setAttribute("type", "submit");
			updatebtn.setAttribute("value", "Update");
			updatebtn.setAttribute("class", "btn");
			updatebtn.setAttribute("name", "upbtn5");
						
			updateform.appendChild(eHandle);
			updateform.appendChild(updatebtn);
						
			editArea.appendChild(updateform);
		}
		
		function edit6()
		{
			document.getElementById('editbtn6').disabled = "disabled";
			var editArea = document.getElementById('edit6');
			
			var updateform = document.createElement("form");
			updateform.setAttribute("method", "post");
			updateform.setAttribute("action", "profilepage.php?uemail=<?php echo $assocEmail;?>");
						
			var eHandle = document.createElement("textarea");
			eHandle.setAttribute("name", "txt6");
						
			var updatebtn = document.createElement("input");
			updatebtn.setAttribute("type", "submit");
			updatebtn.setAttribute("value", "Update");
			updatebtn.setAttribute("class", "btn");
			updatebtn.setAttribute("name", "upbtn6");
						
			updateform.appendChild(eHandle);
			updateform.appendChild(updatebtn);
						
			editArea.appendChild(updateform);
		}
		
		function edit7()
		{
			document.getElementById('editbtn7').disabled = "disabled";
			var editArea = document.getElementById('edit7');
			
			var updateform = document.createElement("form");
			updateform.setAttribute("method", "post");
			updateform.setAttribute("action", "profilepage.php?uemail=<?php echo $assocEmail;?>");
						
			var eHandle = document.createElement("textarea");
			eHandle.setAttribute("name", "txt7");
						
			var updatebtn = document.createElement("input");
			updatebtn.setAttribute("type", "submit");
			updatebtn.setAttribute("value", "Update");
			updatebtn.setAttribute("class", "btn");
			updatebtn.setAttribute("name", "upbtn7");
						
			updateform.appendChild(eHandle);
			updateform.appendChild(updatebtn);
						
			editArea.appendChild(updateform);
		}
		
		function edit8()
		{
			document.getElementById('editbtn8').disabled = "disabled";
			var editArea = document.getElementById('edit8');
			
			var updateform = document.createElement("form");
			updateform.setAttribute("method", "post");
			updateform.setAttribute("action", "profilepage.php?uemail=<?php echo $assocEmail;;?>");
						
			var eHandle = document.createElement("textarea");
			eHandle.setAttribute("name", "txt8");
						
			var updatebtn = document.createElement("input");
			updatebtn.setAttribute("type", "submit");
			updatebtn.setAttribute("value", "Update");
			updatebtn.setAttribute("class", "btn");
			updatebtn.setAttribute("name", "upbtn8");
						
			updateform.appendChild(eHandle);
			updateform.appendChild(updatebtn);
						
			editArea.appendChild(updateform);
		}
		
		function edit9()
		{
			document.getElementById('editbtn9').disabled = "disabled";
			var editArea = document.getElementById('edit9');
			
			var updateform = document.createElement("form");
			updateform.setAttribute("method", "post");
			updateform.setAttribute("action", "profilepage.php?uemail=<?php echo $assocEmail;?>");
						
			var eHandle = document.createElement("textarea");
			eHandle.setAttribute("name", "txt9");
						
			var updatebtn = document.createElement("input");
			updatebtn.setAttribute("type", "submit");
			updatebtn.setAttribute("value", "Update");
			updatebtn.setAttribute("class", "btn");
			updatebtn.setAttribute("name", "upbtn9");
						
			updateform.appendChild(eHandle);
			updateform.appendChild(updatebtn);
						
			editArea.appendChild(updateform);
		}
		
		function edit10()
		{
			document.getElementById('editbtn10').disabled = "disabled";
			var editArea = document.getElementById('edit10');
			
			var updateform = document.createElement("form");
			updateform.setAttribute("method", "post");
			updateform.setAttribute("action", "profilepage.php?uemail=<?php echo $assocEmail;?>");
						
			var eHandle = document.createElement("textarea");
			eHandle.setAttribute("name", "txt10");
						
			var updatebtn = document.createElement("input");
			updatebtn.setAttribute("type", "submit");
			updatebtn.setAttribute("value", "Update");
			updatebtn.setAttribute("class", "btn");
			updatebtn.setAttribute("name", "upbtn10");
						
			updateform.appendChild(eHandle);
			updateform.appendChild(updatebtn);
						
			editArea.appendChild(updateform);
		}
		
		function edit11()
		{
			document.getElementById('editbtn11').disabled = "disabled";
			var editArea = document.getElementById('edit11');
			
			var updateform = document.createElement("form");
			updateform.setAttribute("method", "post");
			updateform.setAttribute("action", "profilepage.php?uemail=<?php echo $assocEmail;?>");
						
			var eHandle = document.createElement("textarea");
			eHandle.setAttribute("name", "txt11");
						
			var updatebtn = document.createElement("input");
			updatebtn.setAttribute("type", "submit");
			updatebtn.setAttribute("value", "Update");
			updatebtn.setAttribute("class", "btn");
			updatebtn.setAttribute("name", "upbtn11");
						
			updateform.appendChild(eHandle);
			updateform.appendChild(updatebtn);
						
			editArea.appendChild(updateform);
		}
		
		function edit12()
		{
			document.getElementById('editbtn12').disabled = "disabled";
			var editArea = document.getElementById('edit12');
			
			var updateform = document.createElement("form");
			updateform.setAttribute("method", "post");
			updateform.setAttribute("action", "profilepage.php?uemail=<?php echo $assocEmail;?>");
						
			var eHandle = document.createElement("textarea");
			eHandle.setAttribute("name", "txt12");
						
			var updatebtn = document.createElement("input");
			updatebtn.setAttribute("type", "submit");
			updatebtn.setAttribute("value", "Update");
			updatebtn.setAttribute("class", "btn");
			updatebtn.setAttribute("name", "upbtn12");
						
			updateform.appendChild(eHandle);
			updateform.appendChild(updatebtn);
						
			editArea.appendChild(updateform);
		}
		
		function edit13()
		{
			document.getElementById('editbtn13').disabled = "disabled";
			var editArea = document.getElementById('edit13');
			
			var updateform = document.createElement("form");
			updateform.setAttribute("method", "post");
			updateform.setAttribute("action", "profilepage.php?uemail=<?php echo $assocEmail;?>");
						
			var eHandle = document.createElement("textarea");
			eHandle.setAttribute("name", "txt13");
						
			var updatebtn = document.createElement("input");
			updatebtn.setAttribute("type", "submit");
			updatebtn.setAttribute("value", "Update");
			updatebtn.setAttribute("class", "btn");
			updatebtn.setAttribute("name", "upbtn13");
						
			updateform.appendChild(eHandle);
			updateform.appendChild(updatebtn);
						
			editArea.appendChild(updateform);
		}
		
		function LoadEditBackEnd(){
				var x = 0;
				for (x=0;x<14;x++){
					document.getElementById('editbtn'+x).disabled = false;
				}
		}
		</script>
	</head>
	
	<body onload = "LoadEditBackEnd()">
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
			if(!empty($rNote)){
					echo('<div class = "greenflag"><div class = "container"><div class = "row-fluid"><div class = "successFlag">'.$rNote.'</div></div></div></div>');
				}
		?>
		
		<!--MENU SECTION-->
		<div class="menu">
			<div class="container">
				<div class ="span12">
					<div class = "menuLinks">
						<div class="menulist">
							<a href ="yourdocstream.php"><i class = '  icon-list-alt'></i> <?php echo $firstName1;?>'s Document Stream</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
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
					<h2 class = "proTitle"><?php echo $fullname;?>'s Profile</h2>
				</div>
			</div>
		</div>
		
		<!--CONTENT SECTION-->
		<div class = "container">
			<div class = "span12">
				<div class = "row-fluid">
					<div class = "profilebody">
						<div class = "span2">
							<form method = "post" action = "profilepage.php?uemail=<?php echo $assocEmail;?>" enctype = "multipart/form-data">
								<div class = "fileupload fileupload-new" data-provides = "fileupload">
									<div class = "fileupload-new thumbnail" style = "width:142px; height:157px;">
										<?php
											if(!empty($ppixpath)){
												echo "<img src = '".$ppixpath."'/>";
											}else{
												echo "<img src = 'img/propix.jpg'/>";
											}
										?>
									</div>
									<?php
										if($assocEmail == $CUEmail){
											echo '<div class = "fileupload-preview fileupload-exists thumbnail" style = "max-width:142px; max-height:157px;">
												</div>
												<div>
														<span class = "btn btn-file">
															<span class = "fileupload-new"><span class = "update">Change Profile Picture</span></span>
															<span class = "fileupload-exists"><span class = "update">Change</span></span>
															<input type ="file" id = "profilepixfile" name = "profilepixfile"/>
														</span>
														<a href = "#" class = "btn fileupload-exists" data-dismiss = "fileupload"><span class = "update">Remove</span></a>
												</div>';
										}
									?>
								</div><br>
								
								<div class = "fileupload fileupload-new" data-provides = "fileupload">
									<?php
										if($assocEmail == $CUEmail){
											//setting signature picture path.
											if(!empty($spixpath)){
												$imgpath = "<img src = '".$spixpath."'/>";
											}else{
												$imgpath = "<img src = 'img/sigpix2.jpg'/>";
											}
											
											echo'<div class = "fileupload-new thumbnail" style = "width:131px; height:64px;">
													'.$imgpath.'
												</div>';
											echo '<div class = "fileupload-preview fileupload-exists thumbnail" style = "max-width:131px; max-height:64px;">
												</div>
												<div>
														<span class = "btn btn-file">
															<span class = "fileupload-new"><span class = "update">Change Signature</span></span>
															<span class = "fileupload-exists"><span class = "update">Change</span></span>
															<input type ="file" type ="file" id = "Sigpixfile" name = "Sigpixfile"/>
														</span>
														<a href = "#" class = "btn fileupload-exists" data-dismiss = "fileupload"><span class = "update">Remove</span></a>
												</div>';
										}
									?>
								</div><br>
								<?php
									if($assocEmail == $CUEmail){
										echo '<button class = "btn" name = "pixchange" value = "change"><span class = "update">Save Changes</span></button>';
									}
								?>
							</form>
						</div>
						<div class = "span10">
							<table class = "table table-striped table-hover">
								<tr>
									<th><h4>Personal Information:</h4></th><th></th><th></th>
								</tr>
								<tr>
									<td>Gender:</td><td><i><?php echo $gender;?></i><?php echo $editOp1[0]." ".$edit[0]." ".$editOp2[0];?></td><td><div id = "edit0"></div></td>
								</tr>
								<tr>
									<td>Phone Number:</td><td><i><?php echo $fone;?></i><?php echo $editOp1[1]." ".$edit[1]." ".$editOp2[1];?></td><td><div id = "edit1"></div></td>
								</tr>
								<tr>
									<td>Email Address:</td><td><b><i><?php echo $assocEmail;?></i></b></td><td></td>
								</tr>
								<tr>
									<td>Country of Residence:</td><td><i><?php echo $CountryofRes;?></i><?php echo $editOp1[2]." ".$edit[2]." ".$editOp2[2];?></td><td><div id = "edit2"></div></td>
								</tr>
								<tr>
									<th><h4>Job Information:</h4></th><th></th><th></th>
								</tr>
								<tr>
									<td>Current Job Title:</td><td><b><i><?php echo $jobTitle;?></i></b><?php echo $editOp1[3]." ".$edit[3]." ".$editOp2[3];?></td><td><div id = "edit3"></div></td>
								</tr>
								<tr>
									<td>Current Place of Employment:</td><td><b><i><?php echo $comp;?></i></b><?php echo $editOp1[4]." ".$edit[4]." ".$editOp2[4];?></td><td><div id = "edit4"></div></td>
								</tr>
								<tr>
									<td>Current Department:</td><td><b><i><?php echo $dept;?></i></b><?php echo $editOp1[5]." ".$edit[5]." ".$editOp2[5];?></td><td><div id = "edit5"></div></td>
								</tr>
								<tr>
									<td>Previous Places of Employment:</td><td><i><?php echo $PPlaceofE;?></i><?php echo $editOp1[6]." ".$edit[6]." ".$editOp2[6];?></td><td><div id = "edit6"></div></td>
								</tr>
								<tr>
									<th><h4>Qualifications:</h4></th><th></th><th></th>
								</tr>
								<tr>
									<td>Higher Institution(s) Attended:</td><td><i><?php echo $hiInst;?></i><?php echo $editOp1[7]." ".$edit[7]." ".$editOp2[7];?></td><td><div id = "edit7"></div></td>
								</tr>
								<tr>
									<td>Other Institution(s) Attended:</td><td><i><?php echo $otherInst;?></i><?php echo $editOp1[8]." ".$edit[8]." ".$editOp2[8];?></td><td><div id = "edit8"></div></td>
								</tr>
								<tr>
									<td>Other Qualifications:</td><td><i><?php echo $otherQual;?></i><?php echo $editOp1[9]." ".$edit[9]." ".$editOp2[9];?></td><td><div id = "edit9"></div></td>
								</tr>
								<tr>
									<th><h4>Interests:</h4></th><th></th><th></th>
								</tr>
								<tr>
									<td>Technical Interest:</td><td><i><?php echo $techInterest;?></i><?php echo $editOp1[10]." ".$edit[10]." ".$editOp2[10];?></td><td><div id = "edit10"></div></td>
								</tr>
								<tr>
									<td>General Interest:</td><td><i><?php echo $genInterest;?></i><?php echo $editOp1[11]." ".$edit[11]." ".$editOp2[11];?></td><td><div id = "edit11"></div></td>
								</tr>
								<tr>
									<th><h4>Personal sales pitch:</h4></th><th></th><th></th>
								</tr>
								<tr>
									<td>What Can you Do?</td><td><i><?php echo $knowhow;?></i><?php echo $editOp1[12]." ".$edit[12]." ".$editOp2[12];?></td><td><div id = "edit12"></div></td>
								</tr>
								<tr>
									<td>About Yourself:</td><td><i><?php echo $bio;?></i><?php echo $editOp1[13]." ".$edit[13]." ".$editOp2[13];?></td><td><div id = "edit13"></div></td>
								</tr>
							</table>
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