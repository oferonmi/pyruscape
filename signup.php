<?php
//setting time zone.
date_default_timezone_set('Africa/Lagos');

//variables declaration

$userEmail = '';
$userPW = '';
$userPWverifier = '';
$sName = '';
$otherNames = '';
$userDofB = '';
$userMofB = '';
$userYofB = '';
$userCofE = '';
$userDofE = '';
$userPhoneNo = '';
$userCofR = '';
$Vstatus = 'no';
$errorMsg2 = '';
$gender = '';

function dataInspect($string, $type, $length){

  // assign the type
  $type = 'is_'.$type;

  if(!$type($string))
    {
    return FALSE;
    }
  // checking to see if there is anything in the string
  elseif(empty($string))
    {
    return FALSE;
    }
  // checking the string length
  elseif(strlen($string) > $length)
    {
    return FALSE;
    }
  else
    {
    // if all is ok, we return TRUE
    return TRUE;
    }
}

//function to check for validity of email.
function emailcheckr($string){
	if(stristr($string, '@') === FALSE) {
	   return FALSE;
	}else{
		return TRUE;
	}

}

/**
* function to check that all form variables are set
*/

function SetCheck(){
	if( isset($_POST['SignupSubmit']) == "Sign up"){
		return isset($_POST['userEmail'], $_POST['userPW'],$_POST['userPWverifier'], $_POST['sName'], $_POST['otherNames'], $_POST['userDofB'],
		$_POST['userMofB'], $_POST['userYofB'],$_POST['userCofE'],$_POST['userDofE'], $_POST['userCofR'],$_POST['userPhoneNo']);
	}
}

function emptyCheck(){
	if(empty($_POST['userEmail']))
		{
			return FALSE;
		}
	elseif(empty($_POST['userPW']))
		{
			return FALSE;
		}
	elseif(empty($_POST['sName']))
		{
			return FALSE;
		}
	elseif(empty($_POST['otherNames']))
		{
			return FALSE;
		}
	elseif(empty($_POST['userCofE']))
		{
			return FALSE;
		}
	elseif(empty($_POST['userDofE']))
		{
			return FALSE;
		}
	elseif(empty($_POST['userPhoneNo']))
		{
			return FALSE;
		}
	else
		{
			return TRUE;
		}
}

/**
* function to monitor and screen out negative numbers.
*/

function NumCheck($num){
	if($num > 0 )
    {
		return TRUE;
    }else{
		return FALSE;
	}
}

//Variable declaration.
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

//initializing super globals for refilling form on event of failed authentication.
if(!isset($_REQUEST['userCofR'])){
	$_REQUEST['userCofR'] = "";
}

if(!isset($_REQUEST['gender'])){
	$_REQUEST['gender'] = "";
}

if(!isset($_REQUEST['userDofB'])){
	$_REQUEST['userDofB'] = "";
}

if(!isset($_REQUEST['userMofB'])){
	$_REQUEST['userMofB'] = "";
}

if(!isset($_REQUEST['userYofB'])){
	$_REQUEST['userYofB'] = "";
}
//Sanitizing and collecting field entries.
if(SetCheck() == TRUE)
{
	$errJar2 = "";
	if(!empty($_POST['userEmail']) && dataInspect($_POST['userEmail'], 'string', 150) != FALSE)
			{
				if(emailcheckr($_POST['userEmail']) != FALSE){
					$userEmail = $_POST['userEmail'];
				}else{
					$errJar2 .= '<li>Please <b>enter a valid email address</b>.</li>';
				}
			}
		else
			{
			$errJar2 .= '<li>Please enter your <b>email address</b>.</li>';
			}
			
		//Collecting Retyped password
		$userPWverifier = $_POST['userPWverifier'];
		
		if($_POST['userPW'] == $userPWverifier)
		{
			if(!empty($_POST['userPW']) && strlen($_POST['userPW'])>5 && strlen($_POST['userPW']) <= 12)
				{
				$userPW = $_POST['userPW'];
				}
			else
				{
				$errJar2 .= '<li>Please re-enter your <b>password</b>. Not less than 6 characters or more than 12 characters.</li>';
				}
		}
		else
			{
			$errJar2 .= '<li>Looks like your <b>password and your retyped password don\'t match up</b>.</li>';
			}
		
		if(!empty($_POST['sName']) && dataInspect($_POST['sName'], 'string', 150) != FALSE)
			{
			$sName = $_POST['sName'];
			}
		else
			{
			$errJar2 .= '<li>Please enter your <b>Surname</b>.</li>';
			}
			
		if(!empty($_POST['otherNames']) && dataInspect($_POST['otherNames'], 'string', 150) != FALSE)
			{
			$otherNames = $_POST['otherNames'];
			}
		else
			{
			$errJar2 .= '<li>Fill in your <b>other names</b>.</li>';
			}
			
		if(isset($_POST['gender'])== "")
			{
				$errJar2 .= '<li>You have to <b>indicate your gender</b></li>';
			}
		else
			{
				$gender = $_POST['gender'];
			}
			
		if(!empty($_POST['userCofE']) && dataInspect($_POST['userCofE'], 'string', 150) != FALSE)
			{
			$userCofE = $_POST['userCofE'];
			}
		else
			{
			$errJar2 .= '<li>Fill in the <b>Current Company you\'re employed in</b>.</li>';
			}
			
		if(!empty($_POST['userDofE']) && dataInspect($_POST['userDofE'], 'string', 150) != FALSE)
			{
			$userDofE = $_POST['userDofE'];
			}
		else
			{
			$errJar2 .= '<li>Fill in the <b>Current Department within the Company you\'re employed in</b>.</li>';
			}
			
		if(dataInspect($_POST['userPhoneNo'], 'numeric',20) == TRUE && NumCheck($_POST['userPhoneNo']) == TRUE)
			{
				$userPhoneNo = (int)$_POST['userPhoneNo'];
			}
			else
			{
			$errJar2 .="<li>Make sure the <b>phone number</b> you entered is <b>not negative or more than 20 characters</b>.</li>";
			}
			
			$userDofB = (int)$_POST['userDofB'];
			$userMofB = (int)$_POST['userMofB'];
			$userYofB = (int)$_POST['userYofB'];
			$userCofR = $_POST['userCofR'];
			
		if(!empty($errJar2)){
			$errorMsg2 = "<ul>" .$errJar2. "</ul>";
		}
		
		//moving field data to database.
		include("/doc_cabinet_connect.php");
		$dblink = dbconnect('doc_cabinet');
		$query = sprintf("SELECT * FROM users_details WHERE user_Email ='%s'",
						mysqli_real_escape_string($dblink, $userEmail)
						);
		$result = mysqli_query($dblink, $query);
		
		if(mysqli_num_rows($result) > 0){
			$errorMsg2 .= '<ul><li>Someone is already using the email: <b>'.$userEmail.'</b>. Try registering again using another email.</li></ul>';
		}else{
			//Making sure no duplicate email address is used.
			if($userEmail == "" && $_POST['userEmail'] == "" )
			{
				$errorMsg2 .= "<ul><li>Ummmh, Looks like you've <b>not entered any email</b></li></ul>";//TO DO include a link to a page for password retrieval.
			}
			elseif(empty($errJar2) && $userEmail !="")
			{
				$query1 = sprintf("INSERT INTO users_details(user_Email, user_PW, user_surname, user_other_names, user_birth_day, user_birth_month,
								user_birth_year, company_of_employ, dept_of_employ, user_country_of_res, user_phone_number, verification_status, bio, 
								profile_pix_path, user_signature_path, gender)
							VALUES('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s', '%s', '%s', '%s', '%s', '%s')",
							mysqli_real_escape_string($dblink, $userEmail),
							md5(mysqli_real_escape_string($dblink, $userPW)),
							mysqli_real_escape_string($dblink, $sName),
							mysqli_real_escape_string($dblink, $otherNames),
							mysqli_real_escape_string($dblink, $userDofB),
							mysqli_real_escape_string($dblink, $userMofB),
							mysqli_real_escape_string($dblink, $userYofB),
							mysqli_real_escape_string($dblink, $userCofE),
							mysqli_real_escape_string($dblink, $userDofE),
							mysqli_real_escape_string($dblink, $userCofR),
							mysqli_real_escape_string($dblink, $userPhoneNo),
							mysqli_real_escape_string($dblink, $Vstatus),
							"Not specified",
							"img/propix.jpg",
							"img/sigpix2.jpg",
							mysqli_real_escape_string($dblink, $gender)
							);
							
				$result1 = 	mysqli_query($dblink, $query1) or die("Error related to query1: ".mysqli_error($dblink));
				
				$RegStatus = "";
				if(!$result1)
				{
					$errorMsg2 .= "<ul><li>Oops! Looks like you would have to re-register. We are really sorry for this.</li></ul>";
				}
				else
				{
					//send email to new user
					$emailBody = "Hi,".$sName.",".$otherNames."<br><br>
								Thank you for choosing Pyruscape<br><br>.
								
								To make sure your account stays active in the long run, click <a href = \"\">here</a><br><br>.
								
								If you have any problem using <b>Pyruscape</b>, you can reach us at <b>complaints@pyruscape.com</b><br><br>
								
								Regards,<br><br><br>
								
								The Pyruscape Team";
								
					mail($userEmail,"Keep Your Pyrusape Account Active", $emailBody, "From: Pyruscape");
					
					$RegStatus = "You've <b>successfully Signed up</b>. Now <b>update your profile</b> 
					OR go to your <b>document stream</b> and <b>start uploading documents</b> right away.";
					
					session_start();
					$_SESSION['login_status'] = "1";
					$_SESSION['uEmail'] = $userEmail;
					$_SESSION['uPassword'] = $userPW;
					
					if ($_SESSION['login_status'] = "1"){
						//TO DO: direct user to profile page.
						header("Location:profilepage.php?rNote=".$RegStatus."&uemail=".$userEmail);
						exit;
					}
					
				}
			}else{
				$errorMsg2 .= "<ul><li>We had a little hitch. <b>Please try registering again</b>.</li></ul>";
			}
		}
}

?>

<!DOCTYPE html>
<html>
	<head>
		<title>Sign Up - Pyruscape&trade;</title>
		
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
				  <div class="span6"><img src = "img/pyrusclogo v4.gif"></div>
				  <!--LOGIN FORM ON HEADER-->
				  <div class="span6">
					<form class="form-inline" method="post" action="signin.php">
						<div id ="headr-login2">
						  <input type="text" class="input-small" placeholder="Email" name  = "uEmail">
						  <input type="password" class="input-small" placeholder="Password" name = "uPassword">
						  <label class="checkbox">
							<input type="checkbox"> Remember me
						  </label>
						  <button type="submit" class="btn" name = "SigninSubmit" value = "Sign in">Sign in</button>
						</div>
					</form>
				  </div>
				</div>
			  </div>
			</div>
		</div>
		
		<!--FLAG SECTION-->
		<?php
			if(!empty($errorMsg2)){
					echo('<div class = "redflag"><div class ="container"><div class = "row-fluid"><div class = "errFlagMgr">'.$errorMsg2.'</div></div></div></div>');
				}
		?>
		<!--CONTENT SECTION-->
		<div class = "container">
			<div class  = "row-fluid">
				<div class = "span12">
					<h2 class = "docHeadr" style = "margin-top:0.6em;">Registration/Sign-up Page</h2><hr>
					<p class = "docHeadr" style = "font-size:18px;">Fill in the forms below and submit so you can begin to use Pyruscape.</p>
				</div>
			</div>
		</div>
		<div class = "container">
			<div class  = "span12">
				<div class = "row-fluid">
					<div class = "span2"></div>
					<div class = "span8">
						<div id = "signup-form" style = "margin-top:1em;">
							<form method = "post" action = "signup.php" id = "signupform">
								<table>
									<tr>
										<td></td><td><input type="text" placeholder="Email" name= "userEmail" value= "<?php echo $userEmail;?>"/></td><td></td>
									</tr>
									<tr>
										<td></td><td><input type="password" placeholder="Password" name= "userPW" />
										<p style = "font-size:0.7em; color:#ff0000;">Password must be greater than six (6) and less than twelve (12) characters.</p></td>
										<td>&nbsp;&nbsp;&nbsp;<input type="password" placeholder="Retype Password" name= "userPWverifier" /><br><br></td>
									</tr>
									<tr>
										<td></td><td><input type="text" placeholder="Surname" name= "sName" value= "<?php echo $sName;?>"/></td>
										<td>&nbsp;&nbsp;&nbsp;<input type="text" placeholder="Other Names" name= "otherNames" value= "<?php echo $otherNames;?>"/></td>
									</tr>
									<tr>
										<td></td>
										<td>
											Date of Birth<br>
											<select class = "span4" form = "signupform" name = "userDofB">
											  <option value = "" <?php if($_REQUEST['userDofB'] == "") echo "selected";?>>Day</option>
											  <?php 
												  for($i = 1; $i <= 31; $i++)
												  {
													if($_REQUEST['userDofB'] == $i){ 
														$dRetainr = "selected"; 
													}else{
														$dRetainr = "";
													}
													echo('<option value ='.$i.' '.$dRetainr.'>'.$i.'</option>');
												  }
											  ?>
											</select>
											
											<select class = "span4" form = "signupform" name = "userMofB">
											  <option value = "" <?php if($_REQUEST['userMofB'] == "") echo "selected";?>>Month</option>
											  <?php 
												  for($k = 1; $k <= 12; $k++)
												  {
													if($_REQUEST['userMofB'] == $k){ 
														$mRetainr = "selected"; 
													}else{
														$mRetainr = "";
													}
													echo('<option value ='.$k.' '.$mRetainr.'>'.$k.'</option>');
												  }
											  ?>
											</select>
											
											<select class = "span4" form = "signupform" name = "userYofB">
											  <option value = "" <?php if($_REQUEST['userYofB'] == "") echo "selected"; ?>>Year</option>
											  <?php 
													for($x = 82; $x >= 0; $x--)
													{
														$year = (date("Y")-18)-$x;
														if($_REQUEST['userYofB'] == $year){ 
															$yRetainr = "selected"; 
														}else{
															$yRetainr = "";
														}
														echo('<option value ='.$year.' '.$yRetainr.'>'.$year.'</option>');
													}
											  ?>
											</select>
										</td>
										<td></td>
									</tr>
									<tr>
										<td></td>
										<td>
											Gender<br>
											<input type = "radio" name = "gender" value = "Male" <?php if($_REQUEST['gender'] == "Male") echo "checked"; ?> > Male
											<input type = "radio" name = "gender" value = "Female" <?php if($_REQUEST['gender'] == "Female") echo "checked"; ?>> Female
										</td>
										<td></td>
									</tr>
									<tr>
										<td></td><td><input type="text" placeholder="Current Company Employed in" name= "userCofE" value= "<?php echo $userCofE;?>"/></td>
										<td>&nbsp;&nbsp;&nbsp;<input type="text" placeholder="Department within Company" name= "userDofE" value= "<?php echo $userDofE;?>"/></td>
									</tr>
									
									<tr>
										<td></td>
										<td>
											<select class = "span12" name = "userCofR" form = "signupform" >
												<option <?php if($_REQUEST['userCofR'] == "") echo "selected"; ?> value = "">Country of Residence</option>
												<option <?php if($_REQUEST['userCofR'] =="Afghanistan") echo "selected"; ?> value = "Afghanistan">Afghanistan</option>
												<option <?php if($_REQUEST['userCofR'] == "Albania") echo "selected"; ?> value = "Albania">Albania</option>
												<option <?php if($_REQUEST['userCofR'] == "Algeria") echo "selected"; ?> value = "Algeria">Algeria</option>
												<option <?php if($_REQUEST['userCofR'] == "Andorra") echo "selected"; ?> value = "Andorra">Andorra</option>
												<option <?php if($_REQUEST['userCofR'] == "Angola") echo "selected"; ?> value = "Angola">Angola</option>
												<option <?php if($_REQUEST['userCofR'] == "Antigua and Barbuda") echo "selected"; ?> value = "Antigua and Barbuda">Antigua and Barbuda</option>
												<option <?php if($_REQUEST['userCofR'] == "Argentina") echo "selected"; ?> value = "Argentina">Argentina</option>
												<option <?php if($_REQUEST['userCofR'] == "Armenia") echo "selected"; ?> value = "Armenia">Armenia</option>
												<option <?php if($_REQUEST['userCofR'] == "Aruba") echo "selected"; ?> value = "Aruba">Aruba</option>
												<option <?php if($_REQUEST['userCofR'] == "Australia") echo "selected"; ?> value = "Australia">Australia</option>
												<option <?php if($_REQUEST['userCofR'] == "Austria") echo "selected"; ?> value = "Austria">Austria</option>
												<option <?php if($_REQUEST['userCofR'] == "Azerbaijan") echo "selected"; ?> value = "Azerbaijan">Azerbaijan</option>
												<option <?php if($_REQUEST['userCofR'] == "Bahamas") echo "selected"; ?> value = "Bahamas">Bahamas, The </option>
												<option <?php if($_REQUEST['userCofR'] == "Bahrain") echo "selected"; ?> value = "Bahrain">Bahrain</option>
												<option <?php if($_REQUEST['userCofR'] == "Bangladesh") echo "selected"; ?> value = "Bangladesh">Bangladesh</option>
												<option <?php if($_REQUEST['userCofR'] == "Barbados") echo "selected"; ?> value = "Barbados">Barbados</option>
												<option <?php if($_REQUEST['userCofR'] == "Belarus") echo "selected"; ?> value = "Belarus">Belarus</option>
												<option <?php if($_REQUEST['userCofR'] == "Belgium") echo "selected"; ?> value = "Belgium">Belgium</option>
												<option <?php if($_REQUEST['userCofR'] == "Belize") echo "selected"; ?> value = "Belize">Belize</option>
												<option <?php if($_REQUEST['userCofR'] == "Benin") echo "selected"; ?> value = "Benin">Benin</option>
												<option <?php if($_REQUEST['userCofR'] == "Bhutan") echo "selected"; ?> value = "Bhutan">Bhutan</option>
												<option <?php if($_REQUEST['userCofR'] == "Bolivia") echo "selected"; ?> value = "Bolivia">Bolivia</option>
												<option <?php if($_REQUEST['userCofR'] == "Bosnia and Herzegovina") echo "selected"; ?> value = "Bosnia and Herzegovina">Bosnia and Herzegovina</option>
												<option <?php if($_REQUEST['userCofR'] == "Botswana") echo "selected"; ?> value = "Botswana">Botswana</option>
												<option <?php if($_REQUEST['userCofR'] == "Brazil") echo "selected"; ?> value = "Brazil">Brazil</option>
												<option <?php if($_REQUEST['userCofR'] == "Brunei") echo "selected"; ?> value = "Brunei">Brunei</option> 
												<option <?php if($_REQUEST['userCofR'] == "Bulgaria") echo "selected"; ?> value = "Bulgaria">Bulgaria</option>
												<option <?php if($_REQUEST['userCofR'] == "Burkina Faso") echo "selected"; ?> value = "Burkina Faso">Burkina Faso</option>
												<option <?php if($_REQUEST['userCofR'] == "Burma") echo "selected"; ?> value = "Burma">Burma</option>
												<option <?php if($_REQUEST['userCofR'] == "Burundi") echo "selected"; ?> value = "Burundi">Burundi</option>
												<option <?php if($_REQUEST['userCofR'] == "Cambodia") echo "selected"; ?> value = "Cambodia">Cambodia</option>
												<option <?php if($_REQUEST['userCofR'] == "Cameroon") echo "selected"; ?> value = "Cameroon">Cameroon</option>
												<option <?php if($_REQUEST['userCofR'] == "Canada") echo "selected"; ?> value = "Canada">Canada</option>
												<option <?php if($_REQUEST['userCofR'] == "Cape Verde") echo "selected"; ?> value = "Cape Verde">Cape Verde</option>
												<option <?php if($_REQUEST['userCofR'] == "Central African Republic") echo "selected"; ?> value = "Central African Republic">Central African Republic</option>
												<option <?php if($_REQUEST['userCofR'] == "Chad") echo "selected"; ?> value = "Chad">Chad</option>
												<option <?php if($_REQUEST['userCofR'] == "Chile") echo "selected"; ?> value = "Chile">Chile</option>
												<option <?php if($_REQUEST['userCofR'] == "China") echo "selected"; ?> value = "China">China</option>
												<option <?php if($_REQUEST['userCofR'] == "Colombia") echo "selected"; ?> value = "Colombia">Colombia</option>
												<option <?php if($_REQUEST['userCofR'] == "Comoros") echo "selected"; ?> value = "Comoros">Comoros</option>
												<option <?php if($_REQUEST['userCofR'] == "Congo, Democratic Republic of the") echo "selected"; ?> value = "Congo, Democratic Republic of the">Congo, Democratic Republic of the</option>
												<option <?php if($_REQUEST['userCofR'] == "Congo, Republic of the") echo "selected"; ?> value = "Congo, Republic of the">Congo, Republic of the</option>
												<option <?php if($_REQUEST['userCofR'] == "Costa Rica") echo "selected"; ?> value = "Costa Rica">Costa Rica</option>
												<option <?php if($_REQUEST['userCofR'] == "Cote d'Ivoire") echo "selected"; ?> value = "Cote d'Ivoire">Cote d'Ivoire</option>
												<option <?php if($_REQUEST['userCofR'] == "Croatia") echo "selected"; ?> value = "Croatia">Croatia</option>
												<option <?php if($_REQUEST['userCofR'] == "Cuba") echo "selected"; ?> value = "Cuba">Cuba</option>
												<option <?php if($_REQUEST['userCofR'] == "Curacao") echo "selected"; ?> value = "Curacao">Curacao</option>
												<option <?php if($_REQUEST['userCofR'] == "Cyprus") echo "selected"; ?> value = "Cyprus">Cyprus</option>
												<option <?php if($_REQUEST['userCofR'] == "Czech Republic") echo "selected"; ?> value = "Czech Republic">Czech Republic</option>
												<option <?php if($_REQUEST['userCofR'] == "Denmark") echo "selected"; ?> value = "Denmark">Denmark</option>
												<option <?php if($_REQUEST['userCofR'] == "Djibouti") echo "selected"; ?> value = "Djibouti">Djibouti</option>
												<option <?php if($_REQUEST['userCofR'] == "Dominica") echo "selected"; ?> value = "Dominica">Dominica</option>
												<option <?php if($_REQUEST['userCofR'] == "Dominican Republic") echo "selected"; ?> value = "Dominican Republic">Dominican Republic</option>
												<option <?php if($_REQUEST['userCofR'] == "East Timor") echo "selected"; ?> value = "East Timor">East Timor</option>
												<option <?php if($_REQUEST['userCofR'] == "Ecuador") echo "selected"; ?> value = "Ecuador">Ecuador</option>
												<option <?php if($_REQUEST['userCofR'] == "Egypt") echo "selected"; ?> value = "Egypt">Egypt</option>
												<option <?php if($_REQUEST['userCofR'] == "El Salvador") echo "selected"; ?> value = "El Salvador">El Salvador</option>
												<option <?php if($_REQUEST['userCofR'] == "Equatorial Guinea") echo "selected"; ?> value = "Equatorial Guinea">Equatorial Guinea</option>
												<option <?php if($_REQUEST['userCofR'] == "Eritrea") echo "selected"; ?> value = "Eritrea">Eritrea</option>
												<option <?php if($_REQUEST['userCofR'] == "Estonia") echo "selected"; ?> value = "Estonia">Estonia</option>
												<option <?php if($_REQUEST['userCofR'] == "Ethiopia") echo "selected"; ?> value = "Ethiopia">Ethiopia</option>
												<option <?php if($_REQUEST['userCofR'] == "Fiji") echo "selected"; ?> value = "Fiji">Fiji</option>
												<option <?php if($_REQUEST['userCofR'] == "Finland") echo "selected"; ?> value = "Finland">Finland</option>
												<option <?php if($_REQUEST['userCofR'] == "France") echo "selected"; ?> value = "France">France</option>
												<option <?php if($_REQUEST['userCofR'] == "Gabon") echo "selected"; ?> value = "Gabon">Gabon</option>
												<option <?php if($_REQUEST['userCofR'] == "Gambia") echo "selected"; ?> value = "Gambia">Gambia, The</option>
												<option <?php if($_REQUEST['userCofR'] == "Georgia") echo "selected"; ?> value = "Georgia">Georgia</option>
												<option <?php if($_REQUEST['userCofR'] == "Germany") echo "selected"; ?> value = "Germany">Germany</option>
												<option <?php if($_REQUEST['userCofR'] == "Ghana") echo "selected"; ?> value = "Ghana">Ghana</option>
												<option <?php if($_REQUEST['userCofR'] == "Greece") echo "selected"; ?> value = "Greece">Greece</option>
												<option <?php if($_REQUEST['userCofR'] == "Grenada") echo "selected"; ?> value = "Grenada">Grenada</option>
												<option <?php if($_REQUEST['userCofR'] == "Guatemala") echo "selected"; ?> value = "Guatemala">Guatemala</option>
												<option <?php if($_REQUEST['userCofR'] == "Guinea") echo "selected"; ?> value = "Guinea">Guinea</option>
												<option <?php if($_REQUEST['userCofR'] == "Guinea-Bissau") echo "selected"; ?> value = "Guinea-Bissau">Guinea-Bissau</option>
												<option <?php if($_REQUEST['userCofR'] == "Guyana") echo "selected"; ?> value = "Guyana">Guyana</option>
												<option <?php if($_REQUEST['userCofR'] == "Haiti") echo "selected"; ?> value = "Haiti">Haiti</option>
												<option <?php if($_REQUEST['userCofR'] == "Holy See") echo "selected"; ?> value = "Holy See">Holy See</option>
												<option <?php if($_REQUEST['userCofR'] == "Honduras") echo "selected"; ?> value = "Honduras">Honduras</option>
												<option <?php if($_REQUEST['userCofR'] == "Hong Kong") echo "selected"; ?> value = "Hong Kong">Hong Kong</option>
												<option <?php if($_REQUEST['userCofR'] == "Hungary") echo "selected"; ?> value = "Hungary">Hungary</option>
												<option <?php if($_REQUEST['userCofR'] == "Iceland") echo "selected"; ?> value = "Iceland">Iceland</option>
												<option <?php if($_REQUEST['userCofR'] == "India") echo "selected"; ?> value = "India">India</option>
												<option <?php if($_REQUEST['userCofR'] == "Indonesia") echo "selected"; ?> value = "Indonesia">Indonesia</option>
												<option <?php if($_REQUEST['userCofR'] == "Iran") echo "selected"; ?> value = "Iran">Iran</option>
												<option <?php if($_REQUEST['userCofR'] == "Iraq") echo "selected"; ?> value = "Iraq">Iraq</option>
												<option <?php if($_REQUEST['userCofR'] == "Ireland") echo "selected"; ?> value = "Ireland">Ireland</option>
												<option <?php if($_REQUEST['userCofR'] == "Israel") echo "selected"; ?> value = "Israel">Israel</option>
												<option <?php if($_REQUEST['userCofR'] == "Italy") echo "selected"; ?> value = "Italy">Italy</option>
												<option <?php if($_REQUEST['userCofR'] == "Jamaica") echo "selected"; ?> value = "Jamaica">Jamaica</option>
												<option <?php if($_REQUEST['userCofR'] == "Japan") echo "selected"; ?> value = "Japan">Japan</option>
												<option <?php if($_REQUEST['userCofR'] == "Jordan") echo "selected"; ?> value = "Jordan">Jordan</option>
												<option <?php if($_REQUEST['userCofR'] == "Kazakhstan") echo "selected"; ?> value = "Kazakhstan">Kazakhstan</option>
												<option <?php if($_REQUEST['userCofR'] == "Kenya") echo "selected"; ?> value = "Kenya">Kenya</option>
												<option <?php if($_REQUEST['userCofR'] == "Kiribati") echo "selected"; ?> value = "Kiribati">Kiribati</option>
												<option <?php if($_REQUEST['userCofR'] == "Kosovo") echo "selected"; ?> value = "Kosovo">Kosovo</option>
												<option <?php if($_REQUEST['userCofR'] == "Kuwait") echo "selected"; ?> value = "Kuwait">Kuwait</option>
												<option <?php if($_REQUEST['userCofR'] == "Kyrgyzstan") echo "selected"; ?> value = "Kyrgyzstan">Kyrgyzstan</option>
												<option <?php if($_REQUEST['userCofR'] == "Laos") echo "selected"; ?> value = "Laos">Laos</option>
												<option <?php if($_REQUEST['userCofR'] == "Latvia") echo "selected"; ?> value = "Latvia">Latvia</option>
												<option <?php if($_REQUEST['userCofR'] == "Lebanon") echo "selected"; ?> value = "Lebanon">Lebanon</option>
												<option <?php if($_REQUEST['userCofR'] == "Lesotho") echo "selected"; ?> value = "Lesotho">Lesotho</option>
												<option <?php if($_REQUEST['userCofR'] == "Liberia") echo "selected"; ?> value = "Liberia">Liberia</option>
												<option <?php if($_REQUEST['userCofR'] == "Libya") echo "selected"; ?> value = "Libya">Libya</option>
												<option <?php if($_REQUEST['userCofR'] == "Liechtenstein") echo "selected"; ?> value = "Liechtenstein">Liechtenstein</option>
												<option <?php if($_REQUEST['userCofR'] == "Lithuania") echo "selected"; ?> value = "Lithuania">Lithuania</option>
												<option <?php if($_REQUEST['userCofR'] == "Luxembourg") echo "selected"; ?> value = "Luxembourg">Luxembourg</option>
												<option <?php if($_REQUEST['userCofR'] == "Macau") echo "selected"; ?> value = "Macau">Macau</option>
												<option <?php if($_REQUEST['userCofR'] == "Macedonia") echo "selected"; ?> value = "Macedonia">Macedonia</option>
												<option <?php if($_REQUEST['userCofR'] == "Madagascar") echo "selected"; ?> value = "Madagascar">Madagascar</option>
												<option <?php if($_REQUEST['userCofR'] == "Malawi") echo "selected"; ?> value = "Malawi">Malawi</option>
												<option <?php if($_REQUEST['userCofR'] == "Malaysia") echo "selected"; ?> value = "Malaysia">Malaysia</option>
												<option <?php if($_REQUEST['userCofR'] == "Maldives") echo "selected"; ?> value = "Maldives">Maldives</option>
												<option <?php if($_REQUEST['userCofR'] == "Mali") echo "selected"; ?> value = "Mali">Mali</option>
												<option <?php if($_REQUEST['userCofR'] == "Malta") echo "selected"; ?> value = "Malta">Malta</option>
												<option <?php if($_REQUEST['userCofR'] == "Marshall Islands") echo "selected"; ?> value = "Marshall Islands">Marshall Islands</option>
												<option <?php if($_REQUEST['userCofR'] == "Mauritania") echo "selected"; ?> value = "Mauritania">Mauritania</option>
												<option <?php if($_REQUEST['userCofR'] == "Mauritius") echo "selected"; ?> value = "Mauritius">Mauritius</option>
												<option <?php if($_REQUEST['userCofR'] == "Mexico") echo "selected"; ?> value = "Mexico">Mexico</option>
												<option <?php if($_REQUEST['userCofR'] == "Micronesia") echo "selected"; ?> value = "Micronesia">Micronesia</option>
												<option <?php if($_REQUEST['userCofR'] == "Moldova") echo "selected"; ?> value = "Moldova">Moldova</option>
												<option <?php if($_REQUEST['userCofR'] == "Monaco") echo "selected"; ?> value = "Monaco">Monaco</option>
												<option <?php if($_REQUEST['userCofR'] == "Mongolia") echo "selected"; ?> value = "Mongolia">Mongolia</option>
												<option <?php if($_REQUEST['userCofR'] == "Montenegro") echo "selected"; ?> value = "Montenegro">Montenegro</option>
												<option <?php if($_REQUEST['userCofR'] == "Morocco") echo "selected"; ?> value = "Morocco">Morocco</option>
												<option <?php if($_REQUEST['userCofR'] == "Mozambique") echo "selected"; ?> value = "Mozambique">Mozambique</option>
												<option <?php if($_REQUEST['userCofR'] == "Namibia") echo "selected"; ?> value = "Namibia">Namibia</option>
												<option <?php if($_REQUEST['userCofR'] == "Nauru") echo "selected"; ?> value = "Nauru">Nauru</option>
												<option <?php if($_REQUEST['userCofR'] == "Nepal") echo "selected"; ?> value = "Nepal">Nepal</option>
												<option <?php if($_REQUEST['userCofR'] == "Netherlands") echo "selected"; ?> value = "Netherlands">Netherlands</option>
												<option <?php if($_REQUEST['userCofR'] == "Netherlands Antilles") echo "selected"; ?> value = "Netherlands Antilles">Netherlands Antilles</option>
												<option <?php if($_REQUEST['userCofR'] == "New Zealand") echo "selected"; ?> value = "New Zealand">New Zealand</option>
												<option <?php if($_REQUEST['userCofR'] == "Nicaragua") echo "selected"; ?> value = "Nicaragua">Nicaragua</option>
												<option <?php if($_REQUEST['userCofR'] == "Niger") echo "selected"; ?> value = "Niger">Niger</option>
												<option <?php if($_REQUEST['userCofR'] == "Nigeria") echo "selected"; ?> value = "Nigeria">Nigeria</option>
												<option <?php if($_REQUEST['userCofR'] == "North Korea") echo "selected"; ?> value = "North Korea">North Korea</option>
												<option <?php if($_REQUEST['userCofR'] == "Norway") echo "selected"; ?> value = "Norway">Norway</option>
												<option <?php if($_REQUEST['userCofR'] == "Oman") echo "selected"; ?> value = "Oman">Oman</option>
												<option <?php if($_REQUEST['userCofR'] == "Pakistan") echo "selected"; ?> value = "Pakistan">Pakistan</option>
												<option <?php if($_REQUEST['userCofR'] == "Palau") echo "selected"; ?> value = "Palau">Palau</option>
												<option <?php if($_REQUEST['userCofR'] == "Palestinian Territories") echo "selected"; ?> value = "Palestinian Territories">Palestinian Territories</option>
												<option <?php if($_REQUEST['userCofR'] == "Panama") echo "selected"; ?> value = "Panama">Panama</option>
												<option <?php if($_REQUEST['userCofR'] == "Papua New Guinea") echo "selected"; ?> value = "Papua New Guinea">Papua New Guinea</option>
												<option <?php if($_REQUEST['userCofR'] == "Paraguay") echo "selected"; ?> value = "Paraguay">Paraguay</option>
												<option <?php if($_REQUEST['userCofR'] == "Peru") echo "selected"; ?> value = "Peru">Peru</option>
												<option <?php if($_REQUEST['userCofR'] == "Philippines") echo "selected"; ?> value = "Philippines">Philippines</option>
												<option <?php if($_REQUEST['userCofR'] == "Poland") echo "selected"; ?> value = "Poland">Poland</option>
												<option <?php if($_REQUEST['userCofR'] == "Portugal") echo "selected"; ?> value = "Portugal">Portugal</option>
												<option <?php if($_REQUEST['userCofR'] == "Qatar") echo "selected"; ?> value = "Qatar">Qatar</option>
												<option <?php if($_REQUEST['userCofR'] == "Romania") echo "selected"; ?> value = "Romania">Romania</option>
												<option <?php if($_REQUEST['userCofR'] == "Russia") echo "selected"; ?> value = "Russia">Russia</option>
												<option <?php if($_REQUEST['userCofR'] == "Rwanda") echo "selected"; ?> value = "Rwanda">Rwanda</option>
												<option <?php if($_REQUEST['userCofR'] == "Saint Kitts and Nevis") echo "selected"; ?> value = "Saint Kitts and Nevis">Saint Kitts and Nevis</option>
												<option <?php if($_REQUEST['userCofR'] == "Saint Lucia") echo "selected"; ?> value = "Saint Lucia">Saint Lucia</option>
												<option <?php if($_REQUEST['userCofR'] == "Saint Vincent and the Grenadines") echo "selected"; ?> value = "Saint Vincent and the Grenadines">Saint Vincent and the Grenadines</option>
												<option <?php if($_REQUEST['userCofR'] == "Samoa") echo "selected"; ?> value = "Samoa">Samoa</option> 
												<option <?php if($_REQUEST['userCofR'] == "San Marino") echo "selected"; ?> value = "San Marino">San Marino</option>
												<option <?php if($_REQUEST['userCofR'] == "Sao Tome and Principe") echo "selected"; ?> value = "Sao Tome and Principe">Sao Tome and Principe</option>
												<option <?php if($_REQUEST['userCofR'] == "Saudi Arabia") echo "selected"; ?> value = "Saudi Arabia">Saudi Arabia</option>
												<option <?php if($_REQUEST['userCofR'] == "Senegal") echo "selected"; ?> value = "Senegal">Senegal</option>
												<option <?php if($_REQUEST['userCofR'] == "Serbia") echo "selected"; ?> value = "Serbia">Serbia</option>
												<option <?php if($_REQUEST['userCofR'] == "Seychelles") echo "selected"; ?> value = "Seychelles">Seychelles</option>
												<option <?php if($_REQUEST['userCofR'] == "Sierra Leone") echo "selected"; ?> value = "Sierra Leone">Sierra Leone</option>
												<option <?php if($_REQUEST['userCofR'] == "Singapore") echo "selected"; ?> value = "Singapore">Singapore</option>
												<option <?php if($_REQUEST['userCofR'] == "Sint Maarten") echo "selected"; ?> value = "Sint Maarten">Sint Maarten</option>
												<option <?php if($_REQUEST['userCofR'] == "Slovakia") echo "selected"; ?> value = "Slovakia">Slovakia</option>
												<option <?php if($_REQUEST['userCofR'] == "Slovenia") echo "selected"; ?> value = "Slovenia">Slovenia</option>
												<option <?php if($_REQUEST['userCofR'] == "Solomon Islands") echo "selected"; ?> value = "Solomon Islands">Solomon Islands</option>
												<option <?php if($_REQUEST['userCofR'] == "Somalia") echo "selected"; ?> value = "Somalia">Somalia</option>
												<option <?php if($_REQUEST['userCofR'] == "South Africa") echo "selected"; ?> value = "South Africa">South Africa</option>
												<option <?php if($_REQUEST['userCofR'] == "South Korea") echo "selected"; ?> value = "South Korea">South Korea</option>
												<option <?php if($_REQUEST['userCofR'] == "South Sudan") echo "selected"; ?> value = "South Sudan">South Sudan</option>
												<option <?php if($_REQUEST['userCofR'] == "Spain") echo "selected"; ?> value = "Spain">Spain</option> 
												<option <?php if($_REQUEST['userCofR'] == "Sri Lanka") echo "selected"; ?> value = "Sri Lanka">Sri Lanka</option>
												<option <?php if($_REQUEST['userCofR'] == "Sudan") echo "selected"; ?> value = "Sudan">Sudan</option>
												<option <?php if($_REQUEST['userCofR'] == "Suriname") echo "selected"; ?> value = "Suriname">Suriname</option>
												<option <?php if($_REQUEST['userCofR'] == "Swaziland") echo "selected"; ?> value = "Swaziland">Swaziland</option> 
												<option <?php if($_REQUEST['userCofR'] == "Sweden") echo "selected"; ?> value = "Sweden">Sweden</option>
												<option <?php if($_REQUEST['userCofR'] == "Switzerland") echo "selected"; ?> value = "Switzerland">Switzerland</option>
												<option <?php if($_REQUEST['userCofR'] == "Syria") echo "selected"; ?> value = "Syria">Syria</option>
												<option <?php if($_REQUEST['userCofR'] == "Taiwan") echo "selected"; ?> value = "Taiwan">Taiwan</option>
												<option <?php if($_REQUEST['userCofR'] == "Tajikistan") echo "selected"; ?> value = "Tajikistan">Tajikistan</option>
												<option <?php if($_REQUEST['userCofR'] == "Tanzania") echo "selected"; ?> value = "Tanzania">Tanzania</option>
												<option <?php if($_REQUEST['userCofR'] == "Thailand") echo "selected"; ?> value = "Thailand">Thailand </option>
												<option <?php if($_REQUEST['userCofR'] == "Timor-Leste") echo "selected"; ?> value = "Timor-Leste">Timor-Leste</option>
												<option <?php if($_REQUEST['userCofR'] == "Togo") echo "selected"; ?> value = "Togo">Togo</option>
												<option <?php if($_REQUEST['userCofR'] == "Tonga") echo "selected"; ?> value = "Tonga">Tonga</option>
												<option <?php if($_REQUEST['userCofR'] == "Trinidad and Tobago") echo "selected"; ?> value = "Trinidad and Tobago">Trinidad and Tobago</option>
												<option <?php if($_REQUEST['userCofR'] == "Tunisia") echo "selected"; ?> value = "Tunisia">Tunisia</option>
												<option <?php if($_REQUEST['userCofR'] == "Turkey") echo "selected"; ?> value = "Turkey">Turkey</option>
												<option <?php if($_REQUEST['userCofR'] == "Turkmenistan") echo "selected"; ?> value = "Turkmenistan">Turkmenistan</option>
												<option <?php if($_REQUEST['userCofR'] == "Tuvalu") echo "selected"; ?> value = "Tuvalu">Tuvalu</option>
												<option <?php if($_REQUEST['userCofR'] == "Uganda") echo "selected"; ?> value = "Uganda">Uganda</option>
												<option <?php if($_REQUEST['userCofR'] == "Ukraine") echo "selected"; ?> value = "Ukraine">Ukraine</option>
												<option <?php if($_REQUEST['userCofR'] == "United Arab Emirates") echo "selected"; ?> value = "United Arab Emirates">United Arab Emirates</option>
												<option <?php if($_REQUEST['userCofR'] == "United Kingdom") echo "selected"; ?> value = "United Kingdom">United Kingdom</option>
												<option <?php if($_REQUEST['userCofR'] == "United State of America") echo "selected"; ?> value = "United State of America">United State of America</option>
												<option <?php if($_REQUEST['userCofR'] == "Uruguay") echo "selected"; ?> value = "Uruguay">Uruguay</option>
												<option <?php if($_REQUEST['userCofR'] == "Uzbekistan") echo "selected"; ?> value = "Uzbekistan">Uzbekistan</option>
												<option <?php if($_REQUEST['userCofR'] == "Vanuatu") echo "selected"; ?> value = "Vanuatu">Vanuatu</option>
												<option <?php if($_REQUEST['userCofR'] == "Venezuela") echo "selected"; ?> value = "Venezuela">Venezuela</option>
												<option <?php if($_REQUEST['userCofR'] == "Vietnam") echo "selected"; ?> value = "Vietnam">Vietnam</option>
												<option <?php if($_REQUEST['userCofR'] == "Yemen") echo "selected"; ?> value = "Yemen">Yemen</option>
												<option <?php if($_REQUEST['userCofR'] == "Zambia") echo "selected"; ?> value = "Zambia">Zambia</option>
												<option <?php if($_REQUEST['userCofR'] == "Zimbabwe") echo "selected"; ?> value = "Zimbabwe">Zimbabwe</option>
												
												<!--TO DO: place list of countries in the world, here-->
											</select>
										</td>
										<td>&nbsp;&nbsp;&nbsp;<input type="text" placeholder="Phone Number" name= "userPhoneNo" value= "<?php echo $userPhoneNo;?>"/></td>
									</tr>
									<tr>
										<td></td><td><input class="btn btn-success" type="submit" name="SignupSubmit" value="Sign up"/></td><td></td>
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
		<div class="footr" style = "margin-top:0.2em;">
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
