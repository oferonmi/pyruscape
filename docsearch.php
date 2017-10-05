<?php
	include'/accessctrl.php';
	
	$orgName ="";
	$orglist ="";
	$orgSanAr[] = "";
	$orgNom = "";
	$deptNom = "";
	$sbox = "";
	$deptSanAr[] = "";
	$deptName="";
	$selectAtribute = "";
	$svalue = "";
	
	//retreiving some of the user's data
	$usersOtherNames = explode(" ", mysqli_result($result, 0,'user_other_names'));
	$CUEmail = mysqli_result($result, 0,'user_Email');
	$firstName = $usersOtherNames[0];
	$useremail = mysqli_result($result, 0,'user_Email');
	
	//retrieving data from the document details table.
	$query1 = "SELECT * FROM doc_files_details";
	$result1 = mysqli_query($dblink, $query1);
	
	if(isset($_POST['orgNom'])){
		//collecting choosen name of organisation.
		$orgNom = $_POST['orgNom'];
		
		//collecting departments associated with organisation.
		$query2 = sprintf("SELECT doc_dept_name FROM doc_files_details WHERE doc_org_name ='%s'",
							mysqli_real_escape_string($dblink, $orgNom)
						) ;
		$result2 = mysqli_query($dblink, $query2);
		
		if(!empty($_POST['sbox'])){
			$svalue = "value ='".$_POST['sbox']."'";
		}
	}
	
	//for processing search inputs.
	//function to check that all form variables are set
	function SetCheck(){
		if( isset($_POST['findDoc']) == "Find Document"){
			return isset($_POST['sbox'], $_POST['orgNom'],$_POST['deptNom']);
		}
	}
	
	//collecting form variables
	if(SetCheck() == TRUE){
		if(!empty($_POST['sbox'])){
			$sbox = $_POST['sbox'];
		}
		
		if($_POST['orgNom'] !=""){
			$orgNom = $_POST['orgNom'];
		}
		
		if($_POST['deptNom'] !=""){
			$deptNom = $_POST['deptNom'];
		}
		
		$squery = str_replace(" ", "+", $sbox );
		//echo $squery;
		if($sbox !=""){
			header("Location:docsearchresult.php?q=".$squery."&org=".$orgNom."&dept=".$deptNom."&email=".$CUEmail);
		exit;
		}
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $firstName;?>'s Search Page</title>
		
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
			<div class = "span12">
				<div class = "row-fluid">
					<div class = "scontent">
						<h3>Alright, Let's get going with finding your Document or Memo;</h3><hr>
						<ul class = "slist">
							<li>Just type a <b>keyword</b>, you think would be found <b>in the title 
							of this document or memo</b>, into the search box below.</li><br>
							<li>Also select the <b>Organisation within which the document was generated</b> as well 
							as the <b>specific Department within the Organisation</b>.</li>
						</ul><hr>
						<form class = "sinput" method = "post" action ="docsearch.php">
							<input class = "sbox" type="text" name="sbox" <?php echo $svalue;?> placeholder = "Type in your search term here"/></input><br/>
								<select  name = "orgNom" onchange = 'this.form.submit();'>
								  <option value=""> Choose Organisation</option>
									<?php
										for($i =0;$i< mysqli_num_rows($result1);$i++){
											$valueCache = mysqli_result($result1, $i,'doc_org_name');
											$orgSanAr[$i] = $valueCache;
										}
										
										$orgSanAr = array_unique($orgSanAr);
										
										foreach ($orgSanAr as $value){
											if($value == $_POST['orgNom']){
												$selectAtribute = 'selected= "selected"';
											}else{
												$selectAtribute ="";
											}
											$orgName .="<option value='".$value."' ".$selectAtribute.">".$value."</option>";
										}
										echo $orgName;
									?>
								</select>
							
								<select name = "deptNom">
								  <option value="">Choose Department</option>
								  <?php
									if(!empty($orgNom)){
										for($j =0;$j< mysqli_num_rows($result2);$j++){
											$deptNomCache = mysqli_result($result2, $j,'doc_dept_name');
											$deptSanAr[$j] = $deptNomCache;
										}
											
										$deptSanAr = array_unique($deptSanAr);
										
										foreach ($deptSanAr as $val){
											if($val == $_POST['deptNom']){
												$selAtr = 'selected= "selected"';
											}else{
												$selAtr ="";
											}
											 $deptName .="<option value='".$val."'".$selAtr.">".$val."</option>";
										}
										echo $deptName;
									}
								  ?>
								</select><br>
							<button class="btn" type="submit" name="findDoc" value="Find Document"><b>Find Document</b></button>
						</form>
					</div><hr>
				</div>
			</div>
		</div>
		
		<!--FOOTER-->
		<div class="footr" style = "margin-top:4.2em;">
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