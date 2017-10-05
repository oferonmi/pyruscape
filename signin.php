<?php
	//Declaration of variables
	$uEmail = '';
	$uPassword = '';
	$errorMsg3 = '';
	$numofRows = 0;
	$errJar3 = "";
	
	if(isset($_GET['pNote']) !=""){
		$pNote = $_GET['pNote'];
	}
	
	function SetCheck()
	{	
		
		if( isset($_POST['SigninSubmit']) == "Sign in" || isset($_POST['formSubmit']) == "Submit")
		{
			return isset($_POST['uEmail'], $_POST['uPassword']);
		}
	}
	
	function mysqli_result2($rez, $row, $field = 0){
		$rez->data_seek($row);
		$dataRow = $rez->fetch_array();
		return $dataRow[$field];
	}
		
	//Cleaning up and collecting login details
	if(SetCheck() == TRUE)
	{
			$uEmail = $_POST['uEmail'];
			$uPassword = $_POST['uPassword'];
		
			$uEmail = htmlspecialchars($_POST['uEmail']);
			$uPassword = htmlspecialchars($_POST['uPassword']);
		
		
		//Checking login details against database
		include("/doc_cabinet_connect.php");
				
		if(dbconnect('doc_cabinet')){
			$dblink = dbconnect('doc_cabinet');
			
			$uEmail = $uEmail;
			$uPassword = $uPassword;
			
			//Crosscheck login details
			$query = sprintf("SELECT * FROM users_details WHERE user_Email ='%s' AND user_PW ='%s'",
					mysqli_real_escape_string($dblink, $uEmail),
					md5(mysqli_real_escape_string($dblink, $uPassword))
					);
			$result = mysqli_query($dblink, $query);
			
			if($result)
				{
					if(@mysqli_result2($result,0,0) > 0)
						{
							session_start();
							$_SESSION['login_status'] = "1";
							$_SESSION['uEmail'] = "$uEmail";
							$_SESSION['uPassword'] = "$uPassword";
							
							if ($_SESSION['login_status'] = "1")
								{
									header("Location:yourdocstream.php");
									exit;
								}
						}
						else
						{
							$errJar3 .="<li>Either your <b>Email Address and/or Password may be incorrect</b>.</li>";
							session_start();
							$_SESSION['login_status'] = '';
						}
				}
				else
				{
					//$errjar3 .="<li>Oops! Please <b>Reload the page</b> and <b>try Signing In again</b>.</li>";
				}
		}else{
			$errJar3 .= '<li>Oops! Please <b>Reload the page</b> and <b>try Signing In again</b>.</li>';
		}
		
		if(!empty($errJar3)){
			$errorMsg3 .= "<ul>" .$errJar3. "</ul>";
		}
	}
	
	//Script for directing to sign up page.
	if(isset($_POST['SignUp']) == "SignUp"){
	
		header("Location:signup.php");
		exit;
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Sign In</title>
		<link rel = "stylesheet" type ="text/css" href = "css/bootstrap.min.css">
		<link rel = "stylesheet" type = "text/css" href = "css/codedoc.css">
	</head>
	<body>
		<script src="js/jquery-1.8.1.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src = "js/docindex.js"></script>
		
		<!--HEADER SECTION-->
		<div class="headr">
			<div class="container">
			  <div class="span12">
				<div class="row-fluid">
				  <!--SITE LOGO-->
				  <div class="span8"><img src = "img/pyrusclogo v4.gif"></div>
				  <!--LOGIN FORM ON HEADER-->
				  <div class="span4">
					<form class="form-inline" method="post" action="signin.php">
						<div id ="headr-login2">
						  <label>
							<p>Don't have an account yet?
						  </label>
						  <button type="submit" class="btn" name ="SignUp" value = "SignUp">Sign up</button>
						</div>
					</form>
				  </div>
				</div>
			  </div>
			</div>
		</div>
		
		<!--FLAG SECTION-->
		<?php
			if(!empty($errorMsg3)){
					echo('<div class = "redflag"><div class ="container"><div class = "row-fluid"><div class = "errFlagMgr">'.$errorMsg3.'</div></div></div></div>');
				}
				
			if(!empty($pNote)){
				echo('<div class = "greenflag"><div class = "container"><div class = "row-fluid"><div class = "successFlag">'.$pNote.'</div></div></div></div>');
			}
		?>
		
		<!--CONTENT SECTION-->
		<div class = "container">
			<div class = "row-fluid">
				<div class = "span12" style = "margin-top:2em;">
					<h3 class = "docHeadr">Already a User of Pyruscape? Sign In.</h3><hr>
				</div>
			</div>
			<div class = "row-fluid">
				<div class = "span12">
					<div class = "span2"></div>
					<div class = "span8">
						<div id = "signin-form1" style = "margin-top:7em;">
							<form class="form-horizontal" method="post" action = "signin.php">
								<table>
									<tr>
										<td>
										  <div class="control-group">
											<label class="control-label" for="inputEmail">Email</label>
											<div class="controls">
											  <input type="text" id="inputEmail" placeholder="Email" name  = "uEmail">
											</div>
										  </div>
										</td>
									</tr>
									<tr>
										<td>
										  <div class="control-group">
											<label class="control-label" for="inputPassword">Password</label>
											<div class="controls">
											  <input type="password" id="inputPassword" placeholder="Password" name = "uPassword">
											</div>
										  </div>
										</td>
									</tr>
									<tr>
										<td>
										  <div class="control-group">
											<div class="controls">
											  <label class="checkbox">
												<input type="checkbox"> Remember me
											  </label>
											  <button type="submit" class="btn" name = "SigninSubmit" value = "Sign in">Sign in</button>
											</div>
										  </div>
										</td>
									</tr>
								</table>
							</form>
						</div>
					</div>
					<div class = "span2"></div>
				</div>
			</div>
		</div>
		
		<!--FOOTER-->
		<div class="footr" style = "margin-top:-0.5em;">
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