<?php
	include'/accessctrl.php';
	
	$docRepEmail = '';
	$docMinute = '';
	$minutes = '';
	$date = '';
	$time = '';
	$errJar = '';
	$FromName = '';
	$ToName = '';
	$ToEmail = '';
	$fromEmail = '';
	$todo = '';
	$resDocActivity = '';
	$statusTrack = '';
	$selectTrack = 'opened';
	$num_rows = '';
	$checked = '';
	$checked2 = '';
	$docStatus = 'opened';
	$closureNote = '';
	$closureFlag = '';
	$assocEmail = '';
	$docID = '';
	$docTitle = '';
	$docClass = 'Confidential';
	$annotateAllbtn = "";
	$annotatebtn ="";
	
	//collection of variables passed in URL.
	if(isset($_GET['docID']) !=""){
		$docID = $_GET['docID'];
	}
	
	if(isset($_GET['title']) !=""){
		$docTitle = $_GET['title'];
	}
	
	if(isset($_GET['email']) !=""){
		$assocEmail = $_GET['email'];
	}
	
	global $dCount, $dDescriptn, $dSig, $dOrg ,$dDept ,$dMoni ,$dPath ,$dUTime, $ToEmail, $fromEmail, $CUEmail;
	
	//retreiving some of the user's data
	$usersOtherNames = explode(" ", mysqli_result($result, 0,'user_other_names'));
	$firstName = $usersOtherNames[0];
	$CUEmail = mysqli_result($result, 0,'user_Email');
	$fullname = mysqli_result($result, 0,'user_surname').', '.mysqli_result($result, 0,'user_other_names');
	$useremail = mysqli_result($result, 0,'user_Email');
	
	//retreiving document details.
		$query1 = sprintf("SELECT * FROM doc_files_details WHERE doc_id ='%s' AND doc_title ='%s' AND user_email ='%s'",
					mysqli_real_escape_string($dblink, $docID),
					mysqli_real_escape_string($dblink, $docTitle),
					mysqli_real_escape_string($dblink, $assocEmail)
					);

		$result1 = mysqli_query($dblink, $query1) or die("Error in query: $query1.".mysqli_error($dblink));
		
		if(mysqli_num_rows($result1) == 1){
			while($row = mysqli_fetch_object($result1)){
				$dCount = $row->doc_count;
				$dDescriptn = $row->doc_description;
				$dSig = $row->doc_signatory;
				$dOrg = $row->doc_org_name;
				$dDept = $row->doc_dept_name;
				$dMoni = $row->doc_assoc_money_amount;
				$dPath = $row->doc_file_path;
				$dUTime = $row->doc_upload_time;
				$DandT = explode(" ",$dUTime);
				$docClass = $row->doc_class;
				}
		}else{
			//NOTE: temporary handling
			$message  = 'Invalid query: ' . mysqli_error($dblink) . "\n";
			$message .= 'Whole query: ' . $query1;
			//die($message);
			//errorCall($message);					
		}
		
	
	/**
	* function to check that all form variables are set
	*/

	function SetCheck(){
		if( isset($_POST['MinuteSubmit']) == "Submit"){
			return isset($_POST['docRepEmail'],$_POST['docMinute']);
		}
	}
	
	
	// check all our variables are set and move their value to database
	if(SetCheck() == TRUE ){
		$query11 = sprintf("SELECT * FROM users_details WHERE user_Email ='%s'",
					mysqli_real_escape_string($dblink, $_POST['docRepEmail'])
					);

		$result11 = mysqli_query($dblink, $query11);
		
		//checking that a minute recepient is registered with the website as well as that the email is not being sent to one self.
		if(!empty($_POST['docRepEmail']) && $_POST['docRepEmail']!= $CUEmail && mysqli_num_rows($result11) > 0 )
			{
			$docRepEmail = $_POST['docRepEmail'];
			}
		else
			{
			//TO DO: send email to entered unregistered email.
			$errJar .= '<li>Hmmm, it appears your colleage is not a registered user of Pyruscape.
			Do try alerting him or her to check his or her email inbox, One more thing,
			don\'t send a Minute to yourself.</li>';
			}
			
		if(!empty($_POST['docMinute']))
			{
			$docMinute = $_POST['docMinute'];
			}
		else
			{
				$errJar .= '<li>You don\'t seem to have minuted on this document</li>.';
			}

		//moving data to  minitue log database.
		if(!empty($_POST['docMinute']) && !empty($_POST['docRepEmail']) && $docRepEmail!='' && $docMinute!='')
			{
				$year = date("Y");
				$month = date("m");
				$day = date("d");
				$date = sprintf("%02d-%02d-%04d", $day, $month, $year);
				$time = date('h:i:s A');
				$query2 = sprintf("INSERT INTO doc_minutes(doc_title, sig_email, minute_on_doc, minute_author_email, minute_respondant_email, time_of_dis, date_of_dis)
					VALUES('%s','%s','%s','%s','%s','%s','%s')",
					mysqli_real_escape_string($dblink, $docTitle),
					mysqli_real_escape_string($dblink, $dSig),
					mysqli_real_escape_string($dblink, $docMinute),
					mysqli_real_escape_string($dblink, $CUEmail),
					mysqli_real_escape_string($dblink, $docRepEmail),
					mysqli_real_escape_string($dblink, $time),
					mysqli_real_escape_string($dblink, $date)
					);
					
				$result2 = mysqli_query($dblink, $query2) or die("Error in query: $query.".mysqli_error($dblink));
				$updateStatus = "Your <b>Minute was successfully updated and sent</b>";
			}			
	}
	
	
	if(isset($_POST['docStatus'])){
		//collecting document status.
		$docStatus = $_POST['docStatus'];
		
		//updating database with document status.
		$sql = sprintf("UPDATE doc_files_details SET doc_status = '%s' WHERE doc_title = '%s'",
						 mysqli_real_escape_string($dblink, $docStatus), 
						 mysqli_real_escape_string($dblink, $docTitle)
					);
		
		mysqli_query($dblink, $sql) or die('Error in query: $sql.'.mysqli_error($dblink));
	}
	
	if(!empty($errJar)){
				$errorMsg = "<p> Please make sure <b>not to leave the recepient's email and minute body fields, blank</b>. Details on what's wrong is given below.</p>";
				$errorMsg .= "<ul>" .$errJar. "</ul>";
			}
	
	//INITIAL POSITION FOR RETRIEVING DOCUMENT MINUTE
	
	//Extacting Document's To-do item
	$query6 = sprintf("SELECT * FROM doc_minutes  WHERE doc_title ='%s' ORDER BY user_ID DESC LIMIT 1",
					mysqli_real_escape_string($dblink, $docTitle)
					);

	$result6 = mysqli_query($dblink, $query6) or die("Error in query: $query6.".mysqli_error($dblink));
	
	if(mysqli_num_rows($result6) > 0){	
		while($row = mysqli_fetch_object($result6)){
		
			//Obtaining fullname of most recent minute's respondent
			$query7 = sprintf("SELECT * FROM users_details WHERE user_Email ='%s'",
					mysqli_real_escape_string($dblink, $row->minute_respondant_email)
					);

			$result7 = mysqli_query($dblink, $query7);
			$toName2 = mysqli_result($result7, 0,'user_surname').", ".mysqli_result($result7, 0,'user_other_names');
			
			//Obtaining fullname of the author of the most recent minute.
			$query8 = sprintf("SELECT * FROM users_details WHERE user_Email ='%s'",
					mysqli_real_escape_string($dblink, $row->minute_author_email)
					);

			$result8 = mysqli_query($dblink, $query8);
			$fromName3 = mysqli_result($result8, 0,'user_surname').", ".mysqli_result($result8, 0,'user_other_names');
			
			if($row->minute_respondant_email == $CUEmail){
				$todo .= "<li>There is a minute on this document. <b>".$toName2."</b> needs to <b>read it</b> or <b>provide a response</b> to it.</li>";
				
				$resDocActivity ="<li><b>".$fromName3."</b> minuted on this document and sent it to <b>".$toName2."</b></li>";
				
			}else{
				$todo .= "<li>You probably need to <b>remind</b> <b>".$toName2."</b> to act on this document you sent to him/her.</li>";
				
				$resDocActivity .= "<li><b>".$fromName3."</b> minuted on this document.</li>";
			}
		}
	}
	
//retreving document status.
		$sql1 = sprintf("SELECT * FROM doc_files_details  WHERE doc_title ='%s'",
						mysqli_real_escape_string($dblink, $docTitle)
						);

		$sqlresult1 = mysqli_query($dblink, $sql1) or die("Error in query: $sql1.".mysqli_error($dblink));
								
	if(mysqli_num_rows($sqlresult1)!=0){
		$selectTrack = mysqli_result($sqlresult1, 0,'doc_status');
	}
							
		if($selectTrack == 'opened'){
			$checked= "checked = 'checked'";
			$checked2 = "";
		}elseif($selectTrack == 'closed'){
			$checked2 = "checked = 'checked'";
			$checked = "";
									
			$closureNote = "You <b>can no longer minute</b> on this document for now.";
			$closureFlag = "<div class = 'closureflag'>
								<div class = 'row-fluid'>
									<div class = 'errFlagMgr'>
										".$closureNote."
									</div>
								</div>
							</div><br>";
		}	
	
	//this leads to the page showing the documents track.
	if(isset($_POST['trace']) == "trace"){
		$dTitle = explode(" ", $docTitle);
		$plusSepTitle = implode("+", $dTitle);
		header("Location:doctrack.php?title=".$plusSepTitle);
		exit;
	}
	
	//Handles Annotation of onsite PDF documents.
	if(isset($_POST['Annotate']) == "Annotate"){
		if(!empty($_POST['DocAnnotation'])){
			$DocAnnotation = $_POST['DocAnnotation'];
			//Keeps track of annotations.
			
			$year = date("Y");
			$month = date("m");
			$day = date("d");
			$date = sprintf("%02d-%02d-%04d", $day, $month, $year);
			$time = date('h:i:s A');
			
			$query9 = sprintf("INSERT INTO doc_annotations(doc_title, annotation, annotator_email, annotation_date, annotation_time)
					VALUES('%s','%s','%s','%s','%s')",
					mysqli_real_escape_string($dblink, $docTitle),
					mysqli_real_escape_string($dblink, $DocAnnotation),
					mysqli_real_escape_string($dblink, $CUEmail),
					mysqli_real_escape_string($dblink, $date),
					mysqli_real_escape_string($dblink, $time)
					);
					
			$result9 = mysqli_query($dblink, $query9) or die("Error in query: $query9.".mysqli_error($dblink));
			$updateStatus = "Your <b>Annotation was successfully added.</b>";
			
			
		}else{
			$errorMsg = '<p>You don\'t appear to have entered any comment for annotation on this document</p>.';
		}
	}
	
	//This is for handling user's action of logging out.
	if(isset($_POST['SignOut']) == "SignOut"){
		unset($_SESSION['login_status']);
		unset($_SESSION['uEmail']);
		unset($_SESSION['uPassword']);
		header("Location:signin.php");
		exit;
	}
	
	if($_SESSION['login_status'] != ''){
?>

<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $docTitle;?> - Pyruscape&trade;</title>
		
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
					elseif(!empty($updateStatus)){
						echo('<div class = "greenflag"><div class = "container"><div class = "row-fluid"><div class = "successFlag">'.$updateStatus.'</div></div></div></div>');
					}
				?>
	
	<!--MENU SECTION-->
		<div class="menu">
			<div class="container">
				<div class ="span12">
					<div class = "menuLinks">
						<div class="menulist">
							<a href ="yourdocstream.php"><i class = '  icon-list-alt'></i> <?php echo $firstName; ?>'s Document Stream</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<a href ="profilepage.php?uemail=<?php echo $useremail; ?>"><i class = 'icon-user'></i> <?php echo $firstName;?>'s Profile</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<a href ="notifier.php"><i class = ' icon-bell'></i> Notifications</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<a href ="msgstream.php"><i class = '  icon-envelope'></i> Messages</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<a href ="reminder.php"><i class = ' icon-check'></i> Reminders</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						</div>
					</div>
				</div>
			</div>
		</div>
		
	
	<!--CONTENT SECTION-->
			<div class="container">
				<div class ="span12">
					<div class = "docHeadr">
						<?php
							$curURL = $_SERVER['PHP_SELF'].'?docID='.$_GET['docID'].'&amp;title='.$_GET['title'].'&amp;email='.$_GET['email'];
							
							//Getting the count of minutes on document.
							$query3 = sprintf("SELECT * FROM doc_minutes  WHERE doc_title ='%s' ORDER BY user_ID DESC",
												mysqli_real_escape_string($dblink, $docTitle)
												);

							$result3 = mysqli_query($dblink, $query3) or die("Error in query: $query3.".mysqli_error($dblink));
								
							$num_rows = mysqli_num_rows($result3);
							
							if(!empty($docTitle)){                              
								include'/pdfConvertr.php';
								include'/pdfannotator.php';
								echo "<h3>".$docTitle."</h3>";
								//Create a time gap for created PDFs to be ready for display.
								sleep(4);
								echo "<h5>A <i class = 'acpnyndata'>".$dOrg."'s</i> document&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Signed by:<i class = 'acpnyndata'> ".$dSig."</i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Uploaded on:<i class = 'acpnyndata'> ".$DandT[0]."</i>
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;At: <i class = 'acpnyndata'>".$DandT[1]."</i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Minute(s):<i class = 'acpnyndata'> ".$num_rows."</i></h5>" ;
								
								//echo $Exts."<br>";
								if($Exts ==  "pdf"){
									echo "<iframe src='".htmlentities($dPath, ENT_QUOTES)."' width='920' height='600'></iframe><br><br>";
								}
								
								if($Exts ==  "jpg" || $Exts ==  "jpeg" || $Exts == "gif" || $Exts == "png"){
									echo "<img src ='".htmlentities($dPath , ENT_QUOTES)."'/><br><br>";							
								}
								
								echo "<a href='".htmlentities($dPathclone, ENT_QUOTES)."' target='_blank' class = 'btn btn-success'>Download The Original Document</a><br><br>";
							}else{
								echo"Oops! Looks like the you're <b>not cleared by your employer to view the document</b> you're requesting for.<br><br>";
							}
						?>
					</div>
				</div>
			</div>
		
		<div class = "container">
			<div class = "span12">
				<div class = "row-fluid">
					<div class = "span4">
					<div class = 'docpagesidebar'>
						<div class = 'subtitle'>
							<h5 class = 'subtitleBit'>Document's To-Do Item</h5><hr>
						</div>
							<?php
								//displaying to-do item.
								if($todo != '' && $selectTrack == 'opened'){
									echo "<div class = 'itemdisp'><ul>".$todo."</ul><hr></div>";
								}else{
									echo "<div class = 'itemdisp'><ul> There is <b>no percieved task</b> to be carried out on this document.</ul><hr></div>";
								}
							?>
						
						<div class = 'subtitle'>
							<h5 class = 'subtitleBit' >Recent Activities on Document</h5><hr>
						</div>
							<?php
								//displaying details of most recent activity carried out on document.
								if($resDocActivity != ''){
									echo "<div class = 'itemdisp'><ul>".$resDocActivity."</ul><hr></div>";
								}else{
									echo "<div class = 'itemdisp'><ul> Looks like <b>no activity</b> has been carried out on this document.</ul><hr></div>";
								}
							?>
						
						<div class = 'subtitle'>
							<h5 class = 'subtitleBit'>Trace Document</h5><hr>
						</div>
						<div>
							<form method = "post" action = "<?php echo($_SERVER['PHP_SELF'].'?docID='.$_GET['docID'].'&amp;title='.$_GET['title'].'&amp;email='.$_GET['email'])?>">
								<p class = 'doctracktext'>
									Click &nbsp;&nbsp;<button type="submit" class="btn" name ="trace" value = "trace"><b>Trace >></b></button>&nbsp;&nbsp; to monitor document's movement.
								</p>
							</form>
							<hr>
						</div>
						<?php
							//displaying of the section for setting of document's status.
							if($assocEmail == $CUEmail){
								$tweakedURL = $_SERVER['PHP_SELF'].'?docID='.$_GET['docID'].'&amp;title='.$_GET['title'].'&amp;email='.$_GET['email'];
								
								echo"<div class = 'subtitle'><h5 class = 'subtitleBit'>Set Document's Status</h5><hr></div>";
								echo"<div class = 'docStatusArea' id = 'statusArea'>
										<form method = 'post' action = '".$tweakedURL."'>
											<input type = 'radio' name = 'docStatus' value = 'opened' id = 'opened' onclick = 'this.form.submit();' ".$checked."/> Opened &nbsp;&nbsp;
											<input type = 'radio' name = 'docStatus' value = 'closed' id = 'closed' onclick = 'this.form.submit();' ".$checked2."/> Closed
										</form>
									</div>
									<hr>";
								
							}
						?>
					</div>
					</div>
					
					<!--document preview & minute list section.-->
					<div class = "span8">
					    <!--document description section.-->
						<div class = 'subtitle'>
							<div class = 'subtitleBit'>
								<h4>Document description</h4>
							</div>
						<hr>
						</div>
						<div class = "docDetails">
							<?php
								if(!empty($docTitle)){
									echo $dDescriptn;
								}else{
									echo "<div class = 'nostream'>
											<ul>
												<li><b>Document description don't exist</b> and <b>are not applicable</a> in this case.</li>
											</ul>
										</div>";
								}
							?>
						</div>
						
						<!--Annotation of document section.-->
						<?php
							if($Exts ==  "pdf"){
								echo"<div class = 'subtitle'>
										<div class = 'subtitleBit'>
											<h4>Document Annotation Section</h4>
										</div>
									<hr>
									</div>
									
									<div>
										<form method = 'post' action ='".$curURL."' style = 'margin-left:3em;'>
											<input class = 'span8' type = 'text' name ='DocAnnotation' placeholder ='Enter brief comment for annotation on document here'/>
											<button class = 'btn' type = 'submit' name = 'Annotate' value = 'Annotate' style = 'margin-top:-0.55em;'>Annotate Document</button><br>
											Example: Approved, Returned etc.
										</form>
									</div>";
							}
						?>
						
						<!--minute list section.-->
						<div class = 'subtitle'>
							<div class = 'subtitleBit'>
								<h4>Minutes</h4>
								<?php
									if($selectTrack == 'opened'){
										echo "<p class = 'pointerWrapper'>Click <b><a href ='#minuteSec'class= 'minuteSecPointer'> >>here</a></b> to <b>minute on and/or send</b> this document.<p>";
									}
								?>
							</div>
						<hr>
						</div>
							<?php
								if($closureNote != ''){
									echo $closureFlag;
								}
								
								//retrieving minutes on document.
								$query3 = sprintf("SELECT * FROM doc_minutes  WHERE doc_title ='%s' ORDER BY user_ID DESC",
												mysqli_real_escape_string($dblink, $docTitle)
												);

								$result3 = mysqli_query($dblink, $query3) or die("Error in query: $query3.".mysqli_error($dblink));
								
								$num_rows = mysqli_num_rows($result3);
								
								$curURL = $_SERVER['PHP_SELF'].'?docID='.$_GET['docID'].'&amp;title='.$_GET['title'].'&amp;email='.$_GET['email'];
								
								if(mysqli_num_rows($result3) > 0){	
									while($row = mysqli_fetch_object($result3)){
										if(!empty($docTitle)){	
											$ToEmail = $row->minute_respondant_email;
											$fromEmail = $row->minute_author_email;
											
											//retieving relevant accompanying user details for minute.
											//From: Extracting Senders details
								
											$query4 = sprintf("SELECT * FROM users_details WHERE user_Email ='%s'",
												mysqli_real_escape_string($dblink,$fromEmail)
												);

											$result4 = mysqli_query($dblink, $query4);
											$FromName = mysqli_result($result4, 0,'user_surname').", ".mysqli_result($result4, 0,'user_other_names');
											
											//To: Extracting Recepient details
											$query5 = sprintf("SELECT * FROM users_details WHERE user_Email ='%s'",
												mysqli_real_escape_string($dblink, $ToEmail)
												);

											$result5 = mysqli_query($dblink, $query5);
											$ToName = mysqli_result($result5, 0,'user_surname').", ".mysqli_result($result5, 0,'user_other_names');
											
											$minutes .= "<div class ='streamunit'>
															<div class ='streamunitBit'> <strong>To</strong>: <em class = 'acpnyndata'>
																<a href ='profilepage.php?uemail=$ToEmail' class = 'profilelead'>".$ToName."</a></em>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>From:</strong> <em class = 'acpnyndata'>
																<a href ='profilepage.php?uemail=$fromEmail' class = 'profilelead'>".$FromName."</a></em>
																<hr>".$row->minute_on_doc."<hr> 
																<strong>Date:</strong><em class = 'acpnyndata'>".$row->date_of_dis."</em>&nbsp;&nbsp; 
																<strong>Time:</strong> <em class = 'acpnyndata'>".$row->time_of_dis."</em>
															</div>
															<hr>
														</div>";
										}else{
											$minutes = "<div class = 'nostream'>
															<ul>
																<li><b>Minutes don't exist</b> and <b>may not be applicable</a> in this case.</li>
															</ul>
														</div>";	
										}
									}
								}else{
									$minutes = "<div class = 'nostream'>
													<ul>
														<li>This document has <b>not been minuted upon</b>.</li>
													</ul>
												</div>";
								}
								
								echo $minutes;
							?>	
					
						<div class = 'minuteSec' id = 'minuteSec'>
							<form method="post" action = "<?php echo $_SERVER['PHP_SELF'].'?docID='.$_GET['docID'].'&amp;title='.$_GET['title'].'&amp;email='.$_GET['email'];?>">
								<table class="table">
									<tr>
										<td>To:</td><td><input class="span6" type="text" placeholder="Email of document recepient" name= "docRepEmail" /></td>
									</tr>
									<tr>
										<td>Body of Minute:</td><td><textarea  class="span6" placeholder="Minute on this document/Memo" name="docMinute"></textarea></td>
									</tr>
									<tr>
										<td></td>
										<td>
										<input class="btn" type="submit" name="MinuteSubmit" value="Minute on and Send Document"
										<?php if($selectTrack == 'closed') echo 'disabled ="disabled"';?>/>
										</td>
									</tr>
								</table>
							</form>
						</div><hr>
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