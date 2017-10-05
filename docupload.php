<?php
	include'/accessctrl.php';
	
	//declaration of variables
	
	$uEmail = '';
	$uPassword = '';
	$docTitle = '';
	$docCount = 0;
	$fileDestinatn = '';
	$docDescriptn = '';
	$docSig = '';
	$orgName = '';
	$deptName = '';
	$moni='';
	$Uploadtime = '';
	$errorMsg = '';
	$updfilepath = '';
	
	//initializing super globals for refilling form on event of failed authentication.
	if(!isset($_REQUEST['docClass'])){
		$_REQUEST['docClass'] = "";
	}
	
	if(isset($_POST['SignOut']) == "SignOut"){
		
		//$_SESSION['login_status'] = '';
		unset($_SESSION['login_status']);
		unset($_SESSION['uEmail']);
		unset($_SESSION['uPassword']);
		header("Location:byepage.php");
		exit;
	}
	
	//retreiving some of the user's data
	$usersOtherNames = explode(" ", mysqli_result($result, 0,'user_other_names'));
	$firstName = $usersOtherNames[0];
	$useremail = mysqli_result($result, 0,'user_Email');

	/**

    * This function can be used to inspect and collect form variables
    *
    * @access private
    *
    * @param string $type  The type of variable can be bool, float, numeric, string, array, or object
    * @param string $string The variable name you would like to check
    * @param string $length The maximum length of the variable
    *
    * return bool
    */

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

/**
* function to check that all form variables are set
*/

function SetCheck(){
	if( isset($_POST['formSubmit']) == "Submit"){
		return isset($_POST['docTitle'], $_POST['moni'],$_POST['docDescriptn'], $_POST['docSig'], $_POST['orgName'], $_POST['deptName']);
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

// check all our variables are set and move their value to database
  if(SetCheck() == TRUE)
    {
		$errJar = "";
	
		if(!empty($useremail) && dataInspect($useremail, 'string', 150) != FALSE)
			{
			$uEmail = $useremail;
			}
		else
			{
			$errJar .= '<li>Please enter your <b>email address</b></li>.';
			}
		
		if(!empty($_SESSION['uPassword']) && dataInspect($_SESSION['uPassword'], 'string', 150) != FALSE)
			{
				$uPassword = $_SESSION['uPassword'];
			}
		else
			{
			$errJar .= '<li>Please enter your <b>password</b></li>.';
			}
		
		if(!empty($_POST['docTitle']) && dataInspect($_POST['docTitle'], 'string', 150) != FALSE)
			{
			$docTitle = $_POST['docTitle'];
			}
		else
			{
			$errJar .= '<li>Please enter the <b>uploaded document\'s Title</b>. It should be no more than <b>150</b> characters.</li>';
			}
		if(!empty($_POST['docDescriptn']))
			{
			$docDescriptn = $_POST['docDescriptn'];
			}
		else
			{
			$errJar .='<li>You need to fill in the <b>Main Document\'s Description</b> field</li>.';
			}
    
		if(!empty($_POST['docSig']) && dataInspect($_POST['docSig'], 'string', 40) != FALSE)
			{
			$docSig = $_POST['docSig'];
			}
		else
			{
			$errJar .= '<li>You have to enter the <b>Name of the Signatory</b> to this document. It should be no more than <b>40</b> characters.</li>';
			}
			
		if(!empty($_POST['orgName']) && dataInspect($_POST['orgName'], 'string', 100) != FALSE)
				{
				$orgName = $_POST['orgName'];
				}
			else
				{
				$errJar .= '<li>You didn\'t indicated the <b>Name of your Organisation</b>. Don\'t exceed <b>100</b> characters.</li>';
				}
				
		if(!empty($_POST['deptName']) && dataInspect($_POST['deptName'], 'string', 100) != FALSE)
				{
				$deptName = $_POST['deptName'];
				}
			else
				{
				$errJar .= '<li>You didn\'t indicated the <b>Name of your Department</b> in the Organisation. Don\'t exceed <b>100</b> characters.</li>';
				}		
		
		if(dataInspect($_POST['moni'], 'numeric', 14) == TRUE && NumCheck($_POST['moni']) == TRUE)
			{
			$moni = $_POST['moni'];
			}
		else
			{
			$moni = 0;
			}
			
		if(isset($_POST['docClass'])== "")
			{
				$errJar2 .= '<li>You have to <b>indicate the document class</b></li>';
			}
		else
			{
				$docClass = $_POST['docClass'];
			}
			
		if(dataInspect($_POST['docCount'], 'numeric',5) == TRUE && NumCheck($_POST['docCount']) == TRUE)
			{
				$docCount = (int)$_POST['docCount'];
				
				if($docCount!="" && $docTitle!="" && $docDescriptn!="" && $docSig!=""
					 && $orgName!="" && $deptName!="" && $uEmail!="" && $uPassword!="" && $docClass!=""){
					
					for($i=0; $i < $docCount; $i++)
					{
						$safeExts = array("jpg", "jpeg", "gif", "png", "pdf", "doc", "ppt", "xls", "txt", "wp", "wp4",
						"wp5", "wp6", "wp7", "wpd", "docx", "docxml", "dotx", "xlsx", "pptx", "ppa", "pps", "ppz",
						"adp", "accdb", "accdc", "accdp", "mdb", "", "pptm", "pps", "ppsx", "ppsm", "potx", "potm", "docm", "rtf",
						"mdb", "xl", "xla", "xll", "xlk", "xld", "xlv", "xlt", "xlc", "xlm", "xlsb", "xlsm", "xlw",);
						
						$Extracts = explode(".", $_FILES["file".$i]["name"]);
						
						$Exts = end($Extracts);
						
						$Exts = strtolower($Exts);
						
						if((($_FILES["file".$i]["type"] == "image/gif")||($_FILES["file".$i]["type"] == "image/jpg")||
						($_FILES["file".$i]["type"] == "image/jpeg")||($_FILES["file".$i]["type"] == "image/pjpeg")||
						($_FILES["file".$i]["type"] == "image/png")||($_FILES["file".$i]["type"] == "image/png")||
						($_FILES["file".$i]["type"] == "text/plain")||($_FILES["file".$i]["type"] == "application/txt")||
						($_FILES["file".$i]["type"] == "browser/internal")||($_FILES["file".$i]["type"] == "text/anytext")||
						($_FILES["file".$i]["type"] == "widetext/plain")||($_FILES["file".$i]["type"] == "widetext/paragraph")||
						($_FILES["file".$i]["type"] == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet")||
						($_FILES["file".$i]["type"] == "application/excel")||($_FILES["file".$i]["type"] == "application/x-excel")||
						($_FILES["file".$i]["type"] == "application/x-msexcel")||($_FILES["file".$i]["type"] == "application/vnd.ms-excel")||
						($_FILES["file".$i]["type"] == "application/wordperfect")||($_FILES["file".$i]["type"] == "application/x-wpwin")||
						($_FILES["file".$i]["type"] == "application/rtf")||($_FILES["file".$i]["type"] == "application/x-rtf")||
						($_FILES["file".$i]["type"] =="application/pdf")||($_FILES["file".$i]["type"] =="application/vnd.ms-powerpoint")||
						($_FILES["file".$i]["type"] =="application/x-mspowerpoint")||($_FILES["file".$i]["type"] =="application/mspowerpoint")||
						($_FILES["file".$i]["type"] =="application/msword")||($_FILES["file".$i]["type"] =="application/x-msaccess")||
						($_FILES["file".$i]["type"] =="application/vnd.openxmlformats-officedocument.wordprocessingml.document")||
						($_FILES["file".$i]["type"] =="application/vnd.openxmlformats-officedocument.wordprocessingml.template")||
						($_FILES["file".$i]["type"] =="application/vnd.ms-word.document.macroEnabled.12")||
						($_FILES["file".$i]["type"] =="application/vnd.ms-word.template.macroEnabled.12")||
						($_FILES["file".$i]["type"] =="application/vnd.openxmlformats-officedocument.spreadsheetml.template")||
						($_FILES["file".$i]["type"] =="application/vnd.ms-excel.sheet.macroEnabled.12")||
						($_FILES["file".$i]["type"] =="application/vnd.ms-excel.template.macroEnabled.12")||
						($_FILES["file".$i]["type"] =="application/vnd.ms-excel.addin.macroEnabled.12")||
						($_FILES["file".$i]["type"] =="application/vnd.ms-excel.sheet.binary.macroEnabled.12")||
						($_FILES["file".$i]["type"] =="application/vnd.openxmlformats-officedocument.presentationml.presentation")||
						($_FILES["file".$i]["type"] =="application/vnd.openxmlformats-officedocument.presentationml.template")||
						($_FILES["file".$i]["type"] =="application/vnd.openxmlformats-officedocument.presentationml.slideshow")||
						($_FILES["file".$i]["type"] =="application/vnd.ms-powerpoint.addin.macroEnabled.12")||
						($_FILES["file".$i]["type"] =="application/vnd.ms-powerpoint.presentation.macroEnabled.12")||
						($_FILES["file".$i]["type"] =="application/vnd.ms-powerpoint.template.macroEnabled.12")||
						($_FILES["file".$i]["type"] =="application/vnd.ms-powerpoint.slideshow.macroEnabled.12"))&&
						($_FILES["file".$i]["size"] < 200000000) && in_array($Exts, $safeExts) && !empty($_FILES["file".$i]) )
						{
						
							//for collecting the content or files of the generated upload fields.
							$fileDestinatn = 'uploads/'.$_POST['docTitle'];
					
							if(!file_exists($fileDestinatn)){
								mkdir($fileDestinatn, 0, true);
							}
							
							if( $_FILES["file".$i]["error"]> 0){
								$errJar .="You've not Uploaded any file or something seems wrong with file you uploaded. try uploading it again";
							}elseif (file_exists("uploads/".$_POST['docTitle']."/". $_FILES["file".$i]["name"])){
								$errJar .="<li>You <b>can't upload thesame file twice</b>. Upload a different file please.</li>";
							}else{
								$updfilepath .= "uploads/".$_POST['docTitle']."/". $_FILES["file".$i]["name"];
								move_uploaded_file($_FILES["file".$i]["tmp_name"], "uploads/".$_POST['docTitle']."/". $_FILES["file".$i]["name"]);
							}
						}else{
							$errJar .="<li>Looks like you're <b>trying to upload a file type we really don't deal with for now</b> or the <b>size of your file is too large</b> or <b>you haven't uploaded any file.</b>.</li>";
						}
					}
				}
			}
		else
			{
			$errJar .="<li>Make sure the <b>number</b> you entered for the number of documents to upload is not negative or greater than <b>5</b> .</li>";
			}
		
		//Test beacons for variable dump
		/**echo "The value of uploaded file path: ".$updfilepath."\n";
		echo "The value of uploaded docCount: ".$docCount."\n";
		echo "The value of uploaded docTitle: ".$docTitle."\n";
		echo "The value of uploaded docDescriptn: ".$docDescriptn."\n";
		echo "The value of uploaded docSig: ".$docSig."\n";
		echo "The value of uploaded orgName: ".$orgName."\n";
		echo "The value of uploaded deptName: ".$deptName."\n";
		echo "The value of uploaded uEmail: ".$uEmail."\n";
		echo "The value of uploaded uPassword: ".$uPassword."\n";**/
		
		//pushing form data to database.
		if($docCount!="" && $docTitle!="" && $docDescriptn!="" && $docSig!=""
		&& $updfilepath!="" && $orgName!="" && $deptName!="" && $uEmail!="" && $uPassword!="" && $docClass!=""){
			include_once("/doc_cabinet_connect.php");
			//dbconnect('doc_cabinet');
			$Uploadtime = date('d-m-Y h:i:sA');
			$query1 = sprintf("INSERT INTO doc_files_details (doc_count, doc_title, doc_description, doc_signatory, doc_org_name, doc_dept_name,
							doc_assoc_money_amount, doc_file_path, doc_upload_time, user_email, user_password, doc_status, doc_class)
							VALUES('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')",
							mysqli_real_escape_string($dblink, $docCount),
							mysqli_real_escape_string($dblink, $docTitle),
							mysqli_real_escape_string($dblink, $docDescriptn),
							mysqli_real_escape_string($dblink, $docSig),
							mysqli_real_escape_string($dblink, $orgName),
							mysqli_real_escape_string($dblink, $deptName),
							mysqli_real_escape_string($dblink, $moni),
							mysqli_real_escape_string($dblink, $updfilepath),
							$Uploadtime,
							mysqli_real_escape_string($dblink, $uEmail),
							md5(mysqli_real_escape_string($dblink, $uPassword)),
							'opened',
							mysqli_real_escape_string($dblink, $docClass)
							);
							
			$uploadStatus = "";
			
			$result1 = mysqli_query($dblink, $query1) or die("result1 related error: ". mysqli_error($dblink));
			
			if($result1 == FALSE ){
				$errorMsg .= "<ul>Oops! Looks like <b>you would have to upload your files again</b>.</ul>";
			}else{
				$uploadStatus = "Document(s) <b>successfully uploaded</b>";
				
				if ($_SESSION['login_status'] = "1")
					{
						header("Location:yourdocstream.php?uploadNotice=$uploadStatus");
						exit;
					}
			}
		}
	
		if(!empty($errJar)){
			$errorMsg = "<p> Please make sure to <b>fill out the entire form always</b>. Details on what's wrong is given below.</p>";
			$errorMsg .= "<ul>" .$errJar. "</ul>";
		}
	}else{
		if(isset($_SESSION['login_status']) != '1'){
			$_SESSION['login_status'] = '';
		}
	}
	
	if($_SESSION['login_status'] != ''){
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Document Upload Center</title>
		
		<link rel = "stylesheet" type ="text/css" href = "css/bootstrap.min.css">
		<link rel = "stylesheet" type = "text/css" href = "css/codedoc.css">
		<link rel = "stylesheet" type ="text/css" href = "css/bootstrap-fileupload.min.css">
	</head>
	
	<body>
	
	<script src="js/jquery-1.8.1.js"></script>
    <script src="js/bootstrap.min.js"></script>
	<script src="js/bootstrap-fileupload.min.js"></script>
	<script src = "js/docindex.js"></script>
	
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
			<div class = "row-fluid">
				<div class = "span12">
					<h2 class="downloadhdr"><i class="icon-download"></i>Document Upload Center</h2>
				</div>
			</div>
		
			<div class = "span12">
				<div class = "row-fluid">
					<div class = "span2">
					</div>
					
					<!--Document upload section.-->
					<div class = "span8">
						<div id="upload-form">
							<form action = "docupload.php" method = "post" enctype = "multipart/form-data" class = "uploadr">
								<table>
									<tr>
										<td></td>
										<td>
											<hr>
											<p class = "uploadCountNote"><i><b>A QUICK NOTE:</b> You would be doing this once, so make sure to enter the exact <u>number</u> of <u>related documents</u> you want to upload below. You can <b>Reload</b> this page if you didn't create the <u>exact</u> number of upload fields required for a particular collection of related document, though.</i></p>
											<input type = "text" autocomplete = "off" id = "count" name = "docCount" placeholder = "Number of Documents to upload"/>
											<input class = "btn btn-success" id = "loadBtn" onclick = "showUpload('file')" type = "button" name = "uploadGen" value = "Load "/>
											
										</td>
									</tr>
								</table>
								<div class = 'downloadhdr'>
										<span id = "fooBar">
											<!-- CONTENT BELOW WOULD BE DISPLAYED FOR USER'S SPECIFIED NUMBER OF TIME-->
											<!--<div class ="fileupload fileupload-new" data-provides = "fileupload">
											<div class = "input-apend">
											<div class = "uneditable-input span2">
											<i class ="icon-file fileupload-exists"></i>
											<span class = "fileupload-preview"></span>
											</div>
											<span class = "btn btn-file">
											<span class = "fileupload-new">Select file</span>
											<span class = "fileupload-exists">Change</span>
											<input type = "file"/>
											</span>
											<a href = "#" class = "btn fileupload-exists" data-dismiss = "fileupload">Remove</a>
											</div>
											</div>-->
										</span>
									<table class = "table table-hover">	
									<tr>
										<td><label>Document/Memo's Title:</label></td>
										<td><input class ='input-xlarge' type="text" placeholder="Main Document's Title" name= "docTitle" value= "<?php echo $docTitle;?>"/></td>
									</tr>
									<tr>
										<td><label>Documented Money Amount:</label></td>
										<td><input class ='input-xlarge' type="text" placeholder="Money Amount stated(optional)" name="moni" value= "<?php echo $moni;?>"/></td>
									</tr>
									<tr>
										<td><label>Document Description:</label></td>
										<td><textarea rows="6" class ='input-xlarge' placeholder="Main Document's Description" name="docDescriptn" ><?php echo $docDescriptn;?></textarea></td>
									</tr>
									<tr>
										<td><label>Document Signatory:</label></td>
										<td><input class ='input-xlarge' type="text" placeholder="Main Document's Signatory" name="docSig" value= "<?php echo $docSig;?>"/></td>
									</tr>
									<tr>
										<td><label>Organisation of Origin:</label></td>
										<td><input class ='input-xlarge' type="text" placeholder="Organisation" name="orgName" value= "<?php echo $orgName;?>"/></td>
									</tr>
									<tr><td><label>Department of Origin:</label></td>
										<td><input class ='input-xlarge' type="text" placeholder="Department" name="deptName" value= "<?php echo $deptName;?>"/></td>
									</tr>
									<tr>
										<td>How would you classify this document?&nbsp;&nbsp;</td>
										<td>
											<input type = "radio" name = "docClass" value = "Confidential" <?php if($_REQUEST['docClass'] == "Confidential") echo "checked"; ?>> Confidential
											<input type = "radio" name = "docClass" value = "Draft" <?php if($_REQUEST['docClass'] == "Draft") echo "checked"; ?>> Draft
											<input type = "radio" name = "docClass" value = "Public" <?php if($_REQUEST['docClass'] == "Public") echo "checked"; ?>> Public
										</td>
									</tr>
									</table>
									<input  id = "upsubmitbtn" class="btn btn-success" type="submit" name="formSubmit" value="Submit"/>
								</div>
							</form>
						</div>
					</div>
					
					<div class = "span2">
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