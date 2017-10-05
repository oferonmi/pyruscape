<?php //access control script
include_once("/doc_cabinet_connect.php");
include_once("/errorPrompt.php");

session_start();

//setting time zone.
date_default_timezone_set('Africa/Lagos');

$rez = "";
$row = 0;
$field = "";

//mysqli equivalent of mysql_result()
function mysqli_result($rez, $row, $field){
	$rez->data_seek($row);
	$dataRow = $rez->fetch_assoc();
	return $dataRow[$field];
}

function mysqli_result2($rez, $row, $field = 0){
	$rez->data_seek($row);
	$dataRow = $rez->fetch_array();
	return $dataRow[$field];
}

//$uEmail = isset($_POST['uEmail']) ? $_POST['uEmail'] : $_SESSION['uEmail'];
if(isset($_POST['uEmail'])){
	$uEmail = $_POST['uEmail'];
}elseif(isset($_SESSION['uEmail'])){
	$uEmail = $_SESSION['uEmail'];
}

//$uPassword = isset($_POST['uPassword']) ? $_POST['uPassword'] : $_SESSION['uPassword'];
if(isset($_POST['uPassword'])){
	$uPassword = $_POST['uPassword'];
}elseif(isset($_SESSION['uPassword'])){
	$uPassword = $_SESSION['uPassword'];
}

$errorMsg3 ="";

if(!isset($uEmail)) {

	$errorMsg3 = "<li><b>Please Sign In</b>: You do need to sign in to continue.</li>";
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Sign In</title>
		<link rel = "stylesheet" type ="text/css" href = "css/bootstrap.min.css">
		<link rel = "stylesheet" type = "text/css" href = "css/codedoc.css">
		
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
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
					<form class="form-inline">
						<div id ="headr-login2">
						  <label>
							<p>Don't have an account yet?
						  </label>
						  <button type="submit" class="btn">Sign up</button>
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
		?>
		
		<!--CONTENT SECTION-->
		<div class = "container">
			<div class = "span12">
				<div class = "row-fluid">
					<div class = "span2"></div>
					<div class = "span8" style = "margin-top:2.5em;">
						<div id = "signin-form1">
							<form class="form-horizontal" method="post" action = "<?php echo $_SERVER['PHP_SELF'];?>">
							
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
		<div class="footr" style = "margin-top:2.2em;">
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
	exit;
	}
	$_SESSION['login_status'] = "1";
	$_SESSION['uEmail'] = "$uEmail";
	$_SESSION['uPassword'] = "$uPassword";
	
	//Crosscheck login details
	$dblink = dbconnect('doc_cabinet');

	$query = sprintf("SELECT * FROM users_details WHERE user_Email ='%s' AND user_PW ='%s'",
					mysqli_real_escape_string($dblink, $uEmail),
					md5(mysqli_real_escape_string($dblink, $uPassword)));

	$result = mysqli_query($dblink, $query);
	
	if (!$result) {
		$message  = 'Invalid query: ' . mysqli_error($dblink) . "\n";
		$message .= 'Whole query: ' . $query;
		die($message);
		errorCall($message);
	}

	
	if (mysqli_num_rows($result) == 0) {
		$_SESSION['login_status'] = "";
		unset($_SESSION['uEmail']);
		unset($_SESSION['uPassword']);
		
		$errorMsg3 = "<li><b>Access Denied</b>: Looks like your <b>Email Address and/or Password may be incorrect</b>.</li>";
		$errorMsg3 .= "<li>If you've not Signed up on Pyruscape, you do need to do that <a href =\"signup.php\">here</a>.</li>";
?>	

<!DOCTYPE html>
<html>
	<head>
		<title>Access Denied</title>
		<link rel = "stylesheet" type ="text/css" href = "css/bootstrap.min.css">
		<link rel = "stylesheet" type = "text/css" href = "css/codedoc.css">
		
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
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
					<form class="form-inline">
						<div id ="headr-login2">
						  <label>
							<p>Don't have an account yet?
						  </label>
						  <button type="submit" class="btn">Sign up</button>
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
		?>
		
		<!--CONTENT SECTION-->
		<div class = "container">
			<div class = "span12">
				<div class = "row-fluid">
					<div class = "span2"></div>
					<div class = "span8">
						<div id = "signin-form">
							<form class="form-horizontal" method="post" action = "<?=$_SERVER['PHP_SELF']?>">
							
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
	exit;
	}
?>