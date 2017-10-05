<?php
include_once'/accessctrl.php';
$alttxt = "";
$areaMkr = "";
//collection of variables passed in URL.
	if($_GET['title'] !=""){
		$title = $_GET['title'];
	}

//retreiving some of the user's data
	$usersOtherNames = explode(" ", mysql_result($result, 0,'user_other_names'));
	$firstName = $usersOtherNames[0];
	$CUEmail = mysql_result($result, 0,'user_Email');
	$fullname = mysql_result($result, 0,'user_surname').', '.mysql_result($result, 0,'user_other_names');
	
//Accessing document details
	$query2 = sprintf("SELECT * FROM doc_files_details WHERE doc_title ='%s'",
						mysql_real_escape_string($title)
					);	
	$result2 = mysql_query($query2) or die("Error in query: $query2.".mysql_error());
	$docID = mysql_result($result2, 0,'doc_id');
	$uemail = mysql_result($result2, 0,'user_email');
	
//Accessing minute database.	
	$query3 = sprintf("SELECT * FROM doc_minutes  WHERE doc_title ='%s'",
					mysql_real_escape_string($title)
					);
	$result3 = mysql_query($query3) or die("Error in query: $query3.".mysql_error());
?>
<html>
	<head>
		<title><?=$title?>'(s) Track</title>
		<link rel = "stylesheet" type ="text/css" href = "css/bootstrap.min.css">
		<link rel = "stylesheet" type = "text/css" href = "css/codedoc.css">
		<link rel = "stylesheet" type ="text/css" href = "css/bootstrap-fileupload.min.css">
	</head>
	
	<body>
	
	<script src="js/jquery-1.8.1.js"></script>
    <script src="js/bootstrap.min.js"></script>
	<script src = "js/docindex.js"></script>
	
	<div class="headr">
		<div class="container">
		  <div class="span12">
			<div class="row-fluid">
			  <!--SITE LOGO-->
			  <div class="span4">Project CodeDoc</div>
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
							<a href ="<?=$docpgurl?>"> Document's Page</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<a href ="yourdocstream.php"><?=$firstName?>'s Document Stream</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<a href =""><?=$firstName?>'s Profile</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<a href ="">In Notifications</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<a href ="">Out Notifications</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<a href ="">Messages</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<a href ="">Reminders</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						</div>
					</div>
				</div>
			</div>
		</div>
	<!--CONTENT SECTION-->
	
	<div id = "frontpgcontent">
		<div class="container">
		  <div class="span12">
			<div class="row-fluid">
				<img src="<?='docinfographics.php?title='.$title?>" usemap = "#dispMarkers"/>
				<?php
					//indicating clickable area on document's reception date points.
					if(mysql_num_rows($result3) > 0 && !empty($title)){
							$k = 1;
							$dispdate = "";
							
							for($j = 0;$j < mysql_num_rows($result3); $j++){
								if($dispdate != mysql_result($result3, $j,'date_of_dis')){
										$k = 1;
								}
								
								$ToEmail = mysql_result($result3, $j,'minute_respondant_email');
								$datepnt = explode("-", mysql_result($result3, $j,'date_of_dis'));
								$datept = $datepnt[0];
								$xcod = 13+($datept*29);
								$ycod = 450-($datept*10);
								
								$query4 = sprintf("SELECT * FROM users_details WHERE user_Email ='%s'",
											mysql_real_escape_string($ToEmail)
											);
								$result4 = mysql_query($query4);
								$rfullname = mysql_result($result4, 0,'user_surname').', '.mysql_result($result4, 0,'user_other_names');
								
								//Accessing minute database to check for instances of same date.
								$dispdate = mysql_result($result3, $j,'date_of_dis');
								$query5 = sprintf("SELECT * FROM doc_minutes  WHERE doc_title ='%s' AND date_of_dis ='%s'",
												mysql_real_escape_string($title),
												mysql_real_escape_string($dispdate)
												);
								$result5 = mysql_query($query5) or die("Error in query: $query5.".mysql_error());
								
								if(mysql_num_rows($result5) == 1){
									$alttxt .= $rfullname;
									//ImageString($basePix, 4,$xcod , $ycod-20, $rfullname, $text_color2);
								}elseif(mysql_num_rows($result5) > 1){
									$alttxt .= $rfullname."\n";
									//ImageString($basePix, 4,$xcod , $ycod-(28*$k), $rfullname, $text_color2);
									$k++;
								}	
								
								//Imagefilledellipse($basePix, $xcod, $ycod, 10, 10, $text_color);
								$areaMkr .= "<area shape='circle' coords='".$xcod.",".$ycod.",15' alt='".$alttxt."'href='' />";
								
							}
					}	
					
					//indicating the integers on the scale
					$i = 10;
					for($n = 0; $n < 32; $n++){
						$areaMkr .= "<area shape='circle' coords='".$i.",460,15' alt='".$alttxt."' href=''/>";
						//Imagestring($basePix, 2, $i, 460, $n, $text_color);
						$i = $i + 29;
					}
					
					$infographMap ="<map name='dispMarkers'>".$areaMkr."</map>";
					echo $infographMap;
				?>
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
					<a href ="">Project CodeDoc</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
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