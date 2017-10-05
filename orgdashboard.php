<?php
	include'/accessctrl.php';
	$adminEmail = "";
	$orgbizname = "";
	$empEmail = "";
	$disableAttr = "";
	$disableAttr2 = "";
	$notifyTracker = "";
	$activityTracker = "";
	
	global $adminEmail, $orgbizname, $empEmail;
	
	//retreiving some of the user's data
	$usersOtherNames = explode(" ", mysqli_result($result, 0,'user_other_names'));
	$CUEmail = mysqli_result($result, 0,'user_Email');
	$firstName = $usersOtherNames[0];
	
	//collection of variables passed in URL.
	if(isset($_GET['admin']) !=""){
		$adminEmail = $_GET['admin'];
	}
	
	if(isset($_GET['orgname']) !=""){
		$orgbizname = $_GET['orgname'];
	}
	
	if(isset($_GET['empEmail']) != ""){
		$empEmail = $_GET['empEmail'];
	}
	
	//retrieving registered organisation or business details.
	$query1 = sprintf("SELECT * FROM reg_biz_details WHERE biz_admin_email ='%s' AND biz_name ='%s' ORDER BY biz_ID DESC",
					mysqli_real_escape_string($dblink, $adminEmail),
					mysqli_real_escape_string($dblink, $orgbizname)
					);

	$result1 = mysqli_query($dblink, $query1);
	
	$bizname = mysqli_result($result1, 0,'biz_name');
	$bizdescriptn = mysqli_result($result1, 0,'biz_descriptn');
	$logopath = mysqli_result($result1, 0,'biz_logo_path');
	
	//handling admin control actions.
	//grant access.
	if(isset($_POST['grantClr']) == 'submit'){
		$accessStatus = "Granted";
		
		//query for updating access status to 'Granted'.
		$query4 = sprintf("UPDATE users_details SET doc_access_status = '%s' WHERE user_Email = '%s'",
							mysqli_real_escape_string($dblink, $accessStatus),
							mysqli_real_escape_string($dblink, $empEmail)
						);

		$result4 = mysqli_query($dblink, $query4);
	}
	
	//revoke access.
	if(isset($_POST['revokeClr']) == 'submit'){
		$accessStatus = "Revoked";
		
		//query for updating access status to 'Revoked'.
		$query4 = sprintf("UPDATE users_details SET doc_access_status = '%s' WHERE user_Email = '%s'",
							mysqli_real_escape_string($dblink, $accessStatus),
							mysqli_real_escape_string($dblink, $empEmail)
						);

		$result4 = mysqli_query($dblink, $query4);
	}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<title><?php echo $bizname;?>'s Dashboard - Pyruscape&trade;</title>
		
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
		
		<!--CONTENT SECTION-->
		<div class = "container">
			<div class = "dbody">
				<div class ="span12">
				   <div class = "row-fluid">
					   <div class ="span2">
							<div class = "fileupload fileupload-new" data-provides = "fileupload">
								<div class = "fileupload-new thumbnail" style = "width:142px; height:148px;">
									<?php
										if(!empty($logopath)){
											echo "<img src = '".$logopath."'/>";
										}else{
											echo "<img src = 'img/logopix2.jpg'/>";
										}
									?>
								</div>
											
								<div class = "fileupload-preview fileupload-exists thumbnail" style = "max-width:142px; max-height:148px;">
								</div>
											
								<div>
									<span class = "btn btn-file">
										<span class = "fileupload-new"><i class = 'update'>Change Logo</i></span>
										<span class = "fileupload-exists"><i class = 'update'>Change</i></span>
										<input type ="file"/>
									</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<a href = "#" class = "btn fileupload-exists" data-dismiss = "fileupload"><i class = 'update'>Remove</i></a>
								</div>
							</div>	
					   </div>
					   
					   <div class ="span10">
					   <div class ="orgheadr">
							<h2 class ="bizheadr">&nbsp;&nbsp;&nbsp;<?php echo $bizname;?>'s Dashboard</h2>
							<div class = 'subtitle'>
								<div class = 'subtitleBit'>
									<h4><?php echo $bizname;?>'s Description</h4>
								</div>
								<hr>
							</div>
							<p class = "bizdescriptn">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $bizdescriptn;?></p><br><hr>
					   </div>
					   </div>
				   </div>
				</div>
			</div><br>
		
			<div class = "dconbody">
			<div class = "span12">
					<div class="row">
						<div class = "tabbable tabbable-bordered">
						   <ul class="nav nav-tabs" id = "dashTabs">
								<li class = "active"><a href = "#docslist" data-toggle="tab"><i class = 'update'>Documents List</i></a></li>
								<li><a href = "#empslist" data-toggle="tab"><i class = 'update'>Employees' List</i></a></li>
								<li><a href = "#orgnotif" data-toggle="tab"><i class = 'update'>Notifications</i></a></li>
								<li><a href = "#orgactivlog" data-toggle="tab"><i class = 'update'>Activity Log</i></a></li>
						   </ul>
					  
							<div class="tab-content">
								<div class = "tab-pane active" id = "docslist">
									<p>
									<div class = 'subtitle'>
										<div class = 'subtitleBit'>
											<h4><?php echo $bizname;?>'s Documents</h4>
										</div>
										<hr>
									</div>
									
									<?php
										//retrieving registered organisation or business' documents records.
										//$query2 = sprintf("SELECT * FROM doc_files_details WHERE doc_org_name ='%s' ORDER BY doc_id DESC",
														//mysql_real_escape_string($bizname)
														//);
										//$result2 = mysql_query($query2);
										
										$query2 = sprintf("SELECT * FROM doc_files_details WHERE doc_org_name ='%s' ORDER BY doc_id DESC",
															mysqli_real_escape_string($dblink, $bizname)
														);
										$result2 = mysqli_query($dblink, $query2);
										
										$orgbiznom =  mb_strtolower($bizname, 'UTF-8');
										
										if(mysqli_num_rows($result2) > 0){
											while($row = mysqli_fetch_object($result2)){
												$docorg = mb_strtolower($row->doc_org_name, 'UTF-8');
										
												similar_text ( $orgbiznom, $docorg, $cmparpercent);
												if($cmparpercent >= 95){
													echo "<a href ='docpage.php?docID=$row->doc_id&amp;title=".stripslashes(htmlentities($row->doc_title, ENT_QUOTES))."&amp;email=$row->user_email'>";
													echo "<div class ='streamunit'>";
													echo "<div class ='streamunitBit'>";
													echo "<b>".$row->doc_title."</b><br>";
													echo $row->doc_description."<hr>";
													
													//Extracting date and time of upload
													$uploadtime = explode(" ", $row->doc_upload_time);
													
													echo "<i class ='acpnynparam'>Signed by:</i> <em class = 'acpnyndata'>".$row->doc_signatory."</em>&nbsp;&nbsp;&nbsp;&nbsp;
														<i class ='acpnynparam'>Owned by:</i> <em class = 'acpnyndata'>".$row->doc_org_name."</em>&nbsp;&nbsp;&nbsp;&nbsp;
														<i class ='acpnynparam'>Documents in set:</i> <em class = 'acpnyndata'>".$row->doc_count."</em>&nbsp;&nbsp;&nbsp;&nbsp;
														<i class ='acpnynparam'>Uploaded On:</i> <em class = 'acpnyndata'>".$uploadtime[0]."</em>&nbsp;&nbsp;&nbsp;&nbsp;
														<i class ='acpnynparam'>At:</i> <em class = 'acpnyndata'>".$uploadtime[1]."</em>";
													echo "</div>";
													echo "<hr>";
													echo "</div>";
													echo "</a>";
												}
											}
										}else{
												echo"<div class = 'noemplist'>
														<p class = 'alert'><b>Your employees and yourself</b> have <b>not uploaded any document or memo yet</b>.<br>
														Upload your documents or memos to <strong>Pyruscape</strong> so you can dispatch and monitor them easily and efficiently.</p><br><br>
													</div>";
										}
									?>
									</p>
								</div>
								
								<div class = "tab-pane" id = "empslist">
									<p>
										<div class = 'subtitle'>
											<div class = 'subtitleBit'>
												<h4><?php echo $bizname;?>'s Employees' List</h4>
											</div>
											<hr>
										</div>
										
										<?php
											//retrieving registered employees records.
											$query3 = sprintf("SELECT * FROM users_details WHERE company_of_employ ='%s' ORDER BY user_ID DESC",
															mysqli_real_escape_string($dblink, $bizname)
															);
											$result3 = mysqli_query($dblink, $query3);
											
											if(mysqli_num_rows($result3) > 0){
												echo "<table class = 'table table-striped table-hover'>
														<tr>
															<th>Name of Employee</th><th>Department</th><th>Job title</th><th>Access Status</th><th>Controls</th>
														</tr>";
														
												while($row = mysqli_fetch_object($result3)){
													//disabling buttons appropriately.
													if($row->doc_access_status == "Granted"){
														$disableAttr = "disabled = 'disabled'";
														$disableAttr2 = "";
													}else{
														$disableAttr = "";
														$disableAttr2 = "disabled = 'disabled'";
													}
													
													echo "<tr>
															<td><a href ='profilepage.php?uemail=".$row->user_Email."' class = 'update'>".$row->user_surname.",".$row->user_other_names."</a></td>
															<td>".$row->dept_of_employ."</td><td>".$row->job_title."</td><td>".$row->doc_access_status."</td>
															<td>
																<form  method = 'post' action = 'orgdashboard.php?empEmail=".$row->user_Email."
																&amp;orgname=".stripslashes(htmlentities($orgbizname, ENT_QUOTES))."&amp;admin=".stripslashes(htmlentities($adminEmail, ENT_QUOTES))."'>
																	<button type = 'submit' class ='btn' name = 'grantClr' value = 'Grant Access' ".$disableAttr."><i class = 'update'>Grant Access</i></button>
																	<button type = 'submit' class ='btn' name = 'revokeClr' value = 'Revoke Access' ".$disableAttr2."><i class = 'update'>Revoke Access</i></button>
																</form>
															</td>
														</tr>";
												}
												
												echo "</table>";
											}else{
												echo "<div class = 'noemplist'>
														<p class = 'alert'><b>Non of your employees have registered</b> on <b>Pyruscape</b>.<br>
														Invite them to <b>register on Pyruscape</b> so they can be listed here.</p><br><br>
													</div>";
											}
										?>
									</p>
								</div>
								
								<div class = "tab-pane" id = "orgnotif">
									<p>
										<div class = 'subtitle'>
											<div class = 'subtitleBit'>
												<h4><?php echo $bizname;?>'s Notifications</h4>
											</div>
											<hr>
										</div>
										
										<?php
											if($notifyTracker !=""){
											//notifications enlist here.
											}else{
												echo"<div class = 'nolist'>
														<p class = 'alert'>There's <b>no notification</b> related to ".$bizname." at the moment</p>
													</div>";
											}
										?>
									</p>
								</div>
								
								<div class = "tab-pane" id = "orgactivlog">
									<p>
										<div class = 'subtitle'>
											<div class = 'subtitleBit'>
												<h4><?php echo $bizname;?>'s Activity Log</h4>
											</div>
											<hr>
										</div>
										
										<?php
											if($activityTracker !=""){
											//organisation related activities enlist here.
											}else{
												echo"<div class = 'nolist'>
														<p class = 'alert'>There's been <b>no sign of any activity</b> related to ".$bizname .".</p>
													</div>";
											}
										?>
									</p>
								</div>
							</div>
						   
						   <script>
								$(function () {
									$('#dashTabs a:first').tab('show');
								})
						   </script>
						</div>
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