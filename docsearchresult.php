<?php
	include'/accessctrl.php';
	
	$q = "";
	$org ="";
	$dept ="";
	$qcount = 0;
	$titleSanAr = array();
	$titleSanArII = array();
	$slist ="";
	
	//retreiving some of the user's data
	$usersOtherNames = explode(" ", mysqli_result($result, 0,'user_other_names'));
	$CUEmail = mysqli_result($result, 0,'user_Email');
	$firstName = $usersOtherNames[0];
	$useremail = mysqli_result($result, 0,'user_Email');
	
	//for processing search inputs.
	//function to check that all form variables are set
	function mSetCheck(){
		if( isset($_POST['findDoc']) == "Find Document"){
			return isset($_POST['sbox']);
		}
	}
	
	//collecting search form variables
	if(mSetCheck() == TRUE){
		if(!empty($_POST['sbox'])){
			$sbox = $_POST['sbox'];
		}
		
		$orgNom = "";
		$deptNom = "";
		$squery = str_replace(" ", "+", $sbox );
		//echo $squery;
		if($sbox !=""){
			header("Location:docsearchresult.php?q=".$squery."&org=".$orgNom."&dept=".$deptNom."&email=".$CUEmail);
		exit;
		}
	}
	
	//collection of variables passed in URL.
	if(isset($_GET['q']) !=""){
		$q = $_GET['q'];
	}
	
	if(isset($_GET['org']) !=""){
		$org = $_GET['org'];
	}
	
	if(isset($_GET['dept']) !=""){
		$dept = $_GET['dept'];
	}
	
	if(isset($_GET['email']) !=""){
		$assocEmail = $_GET['email'];
	}

	//echo $q;
	//echo $org;
	//echo $dept;
	//echo $assocEmail;
	//creating search result
	if($q != "" && $assocEmail !=""){
		$qArray = explode(" ", $q);
		
		//Accessing the minute database
		$query1 = sprintf("SELECT * FROM doc_minutes WHERE minute_respondant_email ='%s' OR minute_author_email ='%s' ORDER BY user_ID DESC",
							mysqli_real_escape_string($dblink, $assocEmail),
							mysqli_real_escape_string($dblink, $assocEmail)
						);
		$result1 = mysqli_query($dblink, $query1) or die("Error in query: $query1.".mysqli_error($dblink));
		
		if(mysqli_num_rows($result1) > 0){
			for($i =0;$i< mysqli_num_rows($result1);$i++){
				$titleCache = mysqli_result($result1, $i,'doc_title');
				$qcount = 0;
				
				//checking selected title for keywords in search query
				foreach($qArray as $sq){
					$qcount += substr_count(strtolower($titleCache), strtolower($sq));
				}
				
				//putting titles that contain keywords into an array.
				if($qcount >= 1){
					$titleSanAr[$i] = $titleCache;
				}
			}
			
			//removing duplicates from array			
			$titleSanAr = array_unique($titleSanAr);
			
			//Accessing document details
			foreach ($titleSanAr as $title){
				if(!empty($org) || !empty($dept)){
					$query2 = sprintf("SELECT * FROM doc_files_details WHERE doc_title ='%s' AND doc_org_name ='%s' AND doc_dept_name ='%s'",
					mysqli_real_escape_string($dblink, $title),
					mysqli_real_escape_string($dblink, $org),
					mysqli_real_escape_string($dblink, $dept)
					);

					$result2 = mysqli_query($dblink, $query2) or die("Error in query: $query2.".mysqli_error($dblink));
				}else{
					$query2 = sprintf("SELECT * FROM doc_files_details WHERE doc_title ='%s'", mysqli_real_escape_string($dblink, $title));

					$result2 = mysqli_query($dblink, $query2) or die("Error in query: $query2.".mysqli_error($dblink));
				}
				if(mysqli_num_rows($result2) > 0){
					//creating search listing
					while($row = mysqli_fetch_object($result2)){
						$slist .= "<a href ='docpage.php?docID=$row->doc_id&amp;title=".stripslashes(htmlentities($row->doc_title, ENT_QUOTES))."&amp;email=$row->user_email'>";
						$slist .= "<div class ='streamunit'>";
						$slist .= "<div class ='streamunitBit'>";
						$slist .= "<b>".$row->doc_title."</b><br>";
						$slist .= $row->doc_description."<hr>";
												
						//Extracting date and time of upload
						$uploadtime = explode(" ", $row->doc_upload_time);
												
						$slist .= "<i class ='acpnynparam'>Signed by:</i> <em class = 'acpnyndata'>".$row->doc_signatory."</em>&nbsp;&nbsp;&nbsp;&nbsp;
								<i class ='acpnynparam'>Owned by:</i> <em class = 'acpnyndata'>".$row->doc_org_name."</em>&nbsp;&nbsp;&nbsp;&nbsp;
								<i class ='acpnynparam'>Documents in set:</i> <em class = 'acpnyndata'>".$row->doc_count."</em>&nbsp;&nbsp;&nbsp;&nbsp;
								<i class ='acpnynparam'>Uploaded On:</i> <em class = 'acpnyndata'>".$uploadtime[0]."</em>&nbsp;&nbsp;&nbsp;&nbsp;
								<i class ='acpnynparam'>At:</i> <em class = 'acpnyndata'>".$uploadtime[1]."</em>";
						$slist .= "</div>";
						$slist .= "<hr>";
						$slist .= "</div>";
						$slist .= "</a>";
					}
				}
			}
		}
		
		//Accessing document records' database.
		$query3 = sprintf("SELECT * FROM doc_files_details WHERE user_email ='%s' ORDER BY doc_id DESC",
							mysqli_real_escape_string($dblink, $assocEmail)
						);
		$result3 = mysqli_query($dblink, $query3) or die("Error in query: $query3.".mysqli_error($dblink));
		
		//matching keyword against database entry.
		if(mysqli_num_rows($result3) > 0){
			for($i =0;$i< mysqli_num_rows($result3);$i++){
				$titleCache = mysqli_result($result3, $i,'doc_title');
				$qcount = 0;
				
				//checking selected title for keywords in search query
				foreach($qArray as $sq){
					$qcount += substr_count(strtolower($titleCache), strtolower($sq));
				}
				
				//putting titles that contain keywords into an array.
				if($qcount >= 1 && in_array($titleCache, $titleSanAr) == FALSE){
					$titleSanArII[$i] = $titleCache;
				}
			}
			
			//removing duplicates from array			
			$titleSanArII = array_unique($titleSanArII);
			
			//Accessing document details
			foreach ($titleSanArII as $title){
				if(!empty($org) || !empty($dept)){
					$query2 = sprintf("SELECT * FROM doc_files_details WHERE doc_title ='%s' AND doc_org_name ='%s' AND doc_dept_name ='%s'",
					mysqli_real_escape_string($dblink, $title),
					mysqli_real_escape_string($dblink, $org),
					mysqli_real_escape_string($dblink, $dept)
					);

					$result2 = mysqli_query($dblink, $query2) or die("Error in query: $query2.".mysqli_error($dblink));
				}else{
					$query2 = sprintf("SELECT * FROM doc_files_details WHERE doc_title ='%s'", mysqli_real_escape_string($dblink, $title));

					$result2 = mysqli_query($dblink, $query2) or die("Error in query: $query2.".mysqli_error($dblink));
				}
				if(mysqli_num_rows($result2) > 0){
					//creating search listing
					while($row = mysqli_fetch_object($result2)){
						$slist .= "<a href ='docpage.php?docID=$row->doc_id&amp;title=".stripslashes(htmlentities($row->doc_title, ENT_QUOTES))."&amp;email=$row->user_email'>";
						$slist .= "<div class ='streamunit'>";
						$slist .= "<div class ='streamunitBit'>";
						$slist .= "<b>".$row->doc_title."</b><br>";
						$slist .= $row->doc_description."<hr>";
												
						//Extracting date and time of upload
						$uploadtime = explode(" ", $row->doc_upload_time);
												
						$slist .= "<i class ='acpnynparam'>Signed by:</i> <em class = 'acpnyndata'>".$row->doc_signatory."</em>&nbsp;&nbsp;&nbsp;&nbsp;
								<i class ='acpnynparam'>Owned by:</i> <em class = 'acpnyndata'>".$row->doc_org_name."</em>&nbsp;&nbsp;&nbsp;&nbsp;
								<i class ='acpnynparam'>Documents in set:</i> <em class = 'acpnyndata'>".$row->doc_count."</em>&nbsp;&nbsp;&nbsp;&nbsp;
								<i class ='acpnynparam'>Uploaded On:</i> <em class = 'acpnyndata'>".$uploadtime[0]."</em>&nbsp;&nbsp;&nbsp;&nbsp;
								<i class ='acpnynparam'>At:</i> <em class = 'acpnyndata'>".$uploadtime[1]."</em>";
						$slist .= "</div>";
						$slist .= "<hr>";
						$slist .= "</div>";
						$slist .= "</a>";
					}
				}
			}
		}
		
		if(empty($titleSanAr) && empty($titleSanArII)){
			$slist .= "<div class = 'nostream'><h5>You don't appear to have any of such document or memo associated with you yet.</h5>
											<ul>
												<li>Notify your colleages that you now use <strong>Pyruscape</strong>.</li>
												<li>Upload documents or memos to <strong>Pyruscape</strong>.</li>
												<li>Or, you've <strong>not minuted on any of such uploaded document or memo</strong>.</li>
											</ul>
										</div>";			
		}
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $firstName;?>'s Search Result</title>
		
		<link rel = "stylesheet" type ="text/css" href = "css/bootstrap.min.css">
		<link rel = "stylesheet" type = "text/css" href = "css/codedoc.css">
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
							<a href ="profilepage.php?uemail=<?php echo $useremail;?>"><i class = 'icon-user'></i> <?php echo $firstName;?>'s Profile</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<a href ="notifier.php"><i class = ' icon-bell'></i> Notifications</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<a href ="msgstream.php"><i class = '  icon-envelope'></i> Messages</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<a href ="reminder.php"><i class = ' icon-check'></i> Reminders</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<!--CONTENT SECTION-->
		<div class = "container">
			<div class = "sbody">
				<div class ="span12">
				   <div class = "row-fluid">
					<form method = "post" action ="docsearchresult.php">
						<table width = "100%">
							<tr>
								<td class = "stxtbox"><input type="text" class="sboxi span12"  size ="12px" name = "sbox" placeholder="Type in your search term here"/></input></td>
								<td><button class="btn" type="submit" name="findDoc" value="Find Document"><b>Find Document</b></button></td>
							</tr>
						</table>	
					</form>
				   </div>
				</div>
				<div class = "span12">
					<div class = "row-fluid">
						<div class = "span12">
							<?php
								echo $slist;
							?>
						</div>
					</div>
				</div>
				<div class ="span12">
				   <div class = "row-fluid">
					<form method = "post" action ="docsearchresult.php">
						<table width = "100%">
							<tr>
								<td class = "stxtbox"><input type="text" class="sboxi span12"  size ="12px" name = "sbox" placeholder="Type in your search term here"/></input></td>
								<td><button class="btn" type="submit" name="findDoc" value="Find Document"><b>Find Document</b></button></td>
							</tr>
						</table>	
					</form>
				   </div>
				</div><br>
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