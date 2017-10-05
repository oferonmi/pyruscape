<?php
	//setting time zone.
	date_default_timezone_set('Africa/Lagos');
	
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
	$query1 = '';
	$docClass = '';
	$errJar2 = "";
	
	//initializing super globals for refilling form on event of failed authentication.
	if(!isset($_REQUEST['docClass'])){
		$_REQUEST['docClass'] = "";
	}
	
	session_start();

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
	if( isset($_POST['formSubmit']) == "Upload and Submit"){
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

//mysqli equivalent of mysqli_result().
function mysqli_result2($rez, $row, $field = 0){
		$rez->data_seek($row);
		$dataRow = $rez->fetch_array();
		return $dataRow[$field];
	}

// check all our variables are set and move their value to database
  if(SetCheck() == TRUE)
    {
		$errJar = "";
	
		if(!empty($_POST['uEmail']) && dataInspect($_POST['uEmail'], 'string', 150) != FALSE)
			{
			$uEmail = $_POST['uEmail'];
			}
		else
			{
			$errJar .= '<li>Please enter your <b>email address</b></li>.';
			}
		
		if(!empty($_POST['uPassword']) && dataInspect($_POST['uPassword'], 'string', 150) != FALSE)
			{
			$uPassword = $_POST['uPassword'];
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
			$docDescriptn =$_POST['docDescriptn'];
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
			$moni='';
			}
			
		if(isset($_POST['docClass'])== "")
			{
				$errJar .= '<li>You have to <b>indicate the document class</b></li>';
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
					$safeExts = array("jpg", "jpeg", "gif", "png", "pdf", "doc", "ppt", "xls","xlt","xla","xltx","xlam",
					"xltm","docx", "xlsx", "pptx","pot","ppa","ppam","dotm","dotx","dot","rtf","wp", "wp4",
					"wp5", "wp6", "wp7", "wpd","ppz","adp", "accdb", "accdc", "accdp", "mdb", "", "pptm", "pps", "ppsx", "ppsm", "potx", "potm", "docm",
					"mdb", "xl", "xlc", "xlm", "xlsb", "xlsm", "xlw", "xll", "xlk", "xld", "xlv", "docxml");
					
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
					($_FILES["file".$i]["type"] =="application/pdf")||($_FILES["file".$i]["type"] =="application/vnd.ms-powerpoint")||
					($_FILES["file".$i]["type"] =="application/msword")||($_FILES["file".$i]["type"] =="application/x-msaccess")||
					($_FILES["file".$i]["type"] == "application/wordperfect")||($_FILES["file".$i]["type"] == "application/x-wpwin")||
					($_FILES["file".$i]["type"] == "application/rtf")||($_FILES["file".$i]["type"] == "application/x-rtf")||
					($_FILES["file".$i]["type"] =="application/x-mspowerpoint")||($_FILES["file".$i]["type"] =="application/mspowerpoint")||
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
					/**($_FILES["file".$i]["size"] < 1000000000) &&**/ in_array($Exts, $safeExts) && !empty($_FILES["file".$i]) )
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
			include("/doc_cabinet_connect.php");
			$dblink = dbconnect('doc_cabinet');
			$Uploadtime = date('d-m-Y h:i:sA');
			$query1 = sprintf("INSERT INTO doc_files_details (doc_count, doc_title, doc_description, doc_signatory, doc_org_name, doc_dept_name,
							doc_assoc_money_amount, doc_file_path, doc_upload_time, user_email, user_password, doc_status, doc_class)
							VALUES('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s', '%s')",
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
		}

			$uploadStatus = "";
			
			if($query1 != ""){
				$result1 = mysqli_query($dblink, $query1) or die("result1 related error: ". mysqli_error($dblink)); 
			} else{
				$result1 = "";
			}
			
			if($result1 == FALSE || $result1 == "" ){
				$errorMsg .= "<ul>Oops! There was an <b>issue with uploading your document</b>. 
								Try out another document while our engineers see what's wrong with your upload.</ul>";
			} else{
				$uploadStatus = "Document(s) <b>successfully uploaded</b>";
				
				$uEmail = $_POST['uEmail'];
				$uPassword = $_POST['uPassword'];
			
				$uEmail = htmlspecialchars($_POST['uEmail']);
				$uPassword = htmlspecialchars($_POST['uPassword']);
			
			
				//Checking login details against database
				include_once("/doc_cabinet_connect.php");
				
				if(dbconnect('doc_cabinet')){
					$uEmail = $uEmail;
					$uPassword = $uPassword;
					
					//Crosscheck login details
					$query2 = sprintf("SELECT * FROM users_details WHERE user_Email ='%s' AND user_PW ='%s'",
							mysqli_real_escape_string($dblink, $uEmail),
							md5(mysqli_real_escape_string($dblink, $uPassword)));
					$result2 = mysqli_query($dblink, $query2);
					
					if($result2)
						{
							if(@mysqli_result2($result2,0,0) > 0)
								{
									session_start();
									$_SESSION['login_status'] = "1";
									$_SESSION['uEmail'] = "$uEmail";
									$_SESSION['uPassword'] = "$uPassword";
									
									if ($_SESSION['login_status'] = "1")
										{
											header("Location:yourdocstream.php?uploadNotice=$uploadStatus");
											exit;
										}
								}
								else
								{
									$errJar .="<li>Either your <b>Email Address and/or Password may be incorrect</b>.</li>";
									session_start();
									$_SESSION['login_status'] = '';
								}
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
	
	if($_SESSION['login_status'] == ''){

?>

<!DOCTYPE html>
<html>
	<head>
		<title>Pyruscape&trade; - Welcome!</title>
		<link rel = "stylesheet" type ="text/css" href = "css/bootstrap.min.css">
		<link rel = "stylesheet" type = "text/css" href = "css/codedoc.css">
		<link rel = "stylesheet" type ="text/css" href = "css/bootstrap-fileupload.min.css">
	</head>
	
	<body onpageshow= "document.getElementById('loadBtn').disabled = false">
	
	
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
				<form class="form-inline" method="post" action="signin.php">
					<div id ="headr-login">
						<input type="text" class="input-small" placeholder="Email" name  = "uEmail" >
						 <input type="password" class="input-small" placeholder="Password" name = "uPassword">
						 <label class="checkbox" style = "color:#ffffff;">
							<input type="checkbox"> Remember me
						 </label>
						 <button type="submit" class="btn" name = "SigninSubmit" value = "Sign in">Sign in</button>&nbsp;
						 <button type="submit" class="btn" name ="SignUp" value = "SignUp">Register</button><br><br>
						 <p style = "color:#ffffff; margin-top:-1em;">Click <a href = "Passchanger.php" style = "text-decoration:underline; color:#ffffff;">here</a> if you've forgotten your password.</p>
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
					echo('<div class = "page-alert"><div class = "alert" style = "background-color:#ffcc99;"><div class = "redflag"><div class ="container"><div class = "row-fluid"><div class = "errFlagMgr">'.$errorMsg.'</div></div></div></div></div></div>');
				}
			?>
	<!--CONTENT SECTION-->
	
	<div id = "frontpgcontent">
		<div class="container">
		  <div class="span12">
			<div class="row-fluid">
			<!--VALUE EXPLAINATION-->
			  <div class="span4">
				<div id = "explainr">
					<h3>Dispatch and Monitor your documents</h3>
					<ul>
						<li><b>Upload</b> your documents or memos to Pyruscape.</li><br>
						<li>Make use of good tools to aid the seamless <b>dispatch</b> of these documents or memos across your organisation or department.</li><br>
						<li>Get the ability to <b>track</b> your document or memo's movement across your organisation or department.</li><br>
					</ul>
				</div>
			  </div>
			  <!--DOC UPLOAD SECTION-->
			  <div class="span8">
				<div id="upload-form">
					<h3>Ok! You're Welcome. Marketing Talk aside; Take a spin right away.</h3>
					
						<form action = "index.php" method = "post" enctype = "multipart/form-data">
						<table style = "margin-top:-1.5em;">
						<!--<script src = "js/docindex.js"></script>-->
							<tr>
								<td></td>
								<td>
									
									<hr>
									<div style = "margin-top:-1em;">
										<i>
											<p class = "uploadCountNote"><b>A QUICK NOTE:</b></p>
											<ul class = "uploadCountNote" >
												<li>
													<b>Enter the <u>exact number</u> of <u>related documents</u> 
													you want to upload below</b> and <b>click the Load button</b>
												</li>.
												<li style = "margin-top:-1.5em;">
													Provide details of the documents you're uploading in the form below and 
													click on the Upload and Submit button.
												</li>
											</ul> 
										</i>
									
										<input type = "text" autocomplete = "off" id = "count" name = "docCount" placeholder = "Number of Documents to upload"/>
										<input class = "btn btn-success" id = "loadBtn" onclick = "showUpload('file')" type = "button" name = "uploadGen" value = "Load "/>
									</div>
									<hr style = "margin-top:0.5em;">
								</td>
							</tr>
						</table>
					
						<table>
							<tr>
								<td></td>
								<td>
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
								</td>
							</tr>
							<tr>
								<td></td><td><input type="text" placeholder="Email" name= "uEmail" value= "<?php echo $uEmail;?>"/></td>
								<td>&nbsp;&nbsp;&nbsp;&nbsp;<input type="password" placeholder="Password" name= "uPassword" /></td>
							</tr>
							
							<tr>
								<td></td><td><input type="text" placeholder="Main Document's Title" name= "docTitle" value= "<?php echo $docTitle;?>"/></td>
								<td>&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" placeholder="Money Amount stated(optional)" name="moni" value= "<?php echo $moni;?>"/></td>
							</tr>
							<tr>
								<td></td><td><textarea  rows = "3" placeholder="Main Document's Description" name="docDescriptn" ><?php echo $docDescriptn;?></textarea></td>
								<td>&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" placeholder="Main Document's Signatory" name="docSig" value= "<?php echo $docSig;?>"/></td>
							</tr>
							<tr>
								<td></td><td><input type="text" placeholder="Organisation" name="orgName" value= "<?php echo $orgName;?>"/></td>
								<td>&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" placeholder="Department" name="deptName" value= "<?php echo $deptName;?>"/></td>
							</tr>
							<tr>
								<td></td>
								<td>
									How would you classify this document?&nbsp;&nbsp;
								</td>
								<td>&nbsp;&nbsp;
									<input type = "radio" name = "docClass" value = "Confidential" <?php if($_REQUEST['docClass'] == "Confidential") echo "checked"; ?>> Confidential
									<input type = "radio" name = "docClass" value = "Draft" <?php if($_REQUEST['docClass'] == "Draft") echo "checked"; ?>> Draft
									<input type = "radio" name = "docClass" value = "Public" <?php if($_REQUEST['docClass'] == "Public") echo "checked"; ?>> Public
								</td>
							</tr>
							<tr>
								<td></td><td><input class="btn btn-success" type="submit" name="formSubmit" value="Upload and Submit"/></td>
							</tr>
						</table>
					</form>
				</div>
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
<?php
}elseif($_SESSION['login_status'] == "1"){
	header("Location:yourdocstream.php");
	exit;
}

?>