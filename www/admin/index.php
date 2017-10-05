<?php
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

// check all our variables are set
  if(SetCheck() == TRUE)
    {
	
		$errJar = "";
		if(dataInspect($_POST['docCount'], 'numeric',5) == TRUE && NumCheck($_POST['docCount']) == TRUE)
			{
				$docCount = (int)$_POST['docCount'];
				
				//for collecting the content or files of the generated upload fields.
				
				$fileDestinatn = 'uploads/'.$_POST['docTitle'];
				if(!file_exists($fileDestinatn)){
					mkdir($fileDestinatn, 0, true);
				}
				
				for($i=0; $i < $docCount; $i++)
				{
					//$fileMarker[] = $_POST['file'.i];
					
					$safeExts = array("jpg", "jpeg", "gif", "png", "pdf", "doc", "ppt", "xls", "docx", "xlsx", "pptx", 
					"adp", "accdb", "accdc", "accdp", "mdb", "", "pptm", "pps", "ppsx", "ppsm", "potx", "potm", "docm",
					"mdb", "xl", "xlc", "xlm", "xlsb", "xlsm", "xlw",);
					$Extracts = explode(".", $_FILES["file".$i]["name"]);
					$Exts = end($Extracts);
					$Exts = strtolower($Exts);
					
					if((($_FILES["file".$i]["type"] == "image/gif")||($_FILES["file".$i]["type"] == "image/jpg")||
					($_FILES["file".$i]["type"] == "image/jpeg")||($_FILES["file".$i]["type"] == "image/pjpeg")||
					($_FILES["file".$i]["type"] == "image/png")||($_FILES["file".$i]["type"] == "image/png")||
					($_FILES["file".$i]["type"] == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet")||
					($_FILES["file".$i]["type"] =="application/pdf")||($_FILES["file".$i]["type"] =="application/vnd.ms-powerpoint")||
					($_FILES["file".$i]["type"] =="application/msword")||($_FILES["file".$i]["type"] =="application/x-msaccess"))&&
					($_FILES["file".$i]["size"] < 10000000) && in_array($Exts, $safeExts) && !empty($_FILES["file".$i]) )
					{
						if( $_FILES["file".$i]["error"]> 0){
							$errJar .="You've not Uploaded any file or something seems wrong with file you uploaded. try uploading it again";
						}elseif (file_exists("uploads/". $_FILES["file".$i]["name"])){
							$errJar .="<li>You <b>can't upload thesame file twice</b>. Upload a different file please.</li>";
						}else{
							move_uploaded_file($_FILES["file".$i]["tmp_name"], "uploads/".$_POST['docTitle']."/". $_FILES["file".$i]["name"]);
						}
					}else{
						$errJar .="<li>Looks like you're <b>trying to upload a file type we really don't deal with for now</b> or the <b>size of your file is too large</b> or <b>you haven't uploaded any file.</b>.</li>";
					}
				}
			}
		else
			{
			$errJar .="<li>Make sure the <b>number</b> you entered for the number of documents to upload is not negative or greater than <b>5</b> .</li>";
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
			$errJar .='<li>You need to fill in the <b>Main Document\'s Description</b> field</li>';
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
		
			
		if(!empty($errJar)){
			$errorMsg = "<p> Please make sure to <b>fill out the entire form always</b>. Details on what's wrong is given below.</p>";
			$errorMsg .= "<ul>" .$errJar. "</ul>";
		}	
		
	}else{
		//header("Location:index.php");
	}
  
?>

<!DOCTYPE html>
<html>
	<head>
		<title></title>
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
			  <div class="span6">Project CodeDoc</div>
			  <!--LOGIN FORM ON HEADER-->
			  <div class="span6">
				<form class="form-inline">
					<div id ="headr-login">
					  <input type="text" class="input-small" placeholder="Email">
					  <input type="password" class="input-small" placeholder="Password">
					  <label class="checkbox">
						<input type="checkbox"> Remember me
					  </label>
					  <button type="submit" class="btn">Sign in</button>
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
				else{
						//echo('<div class = "greenflag"><div class = "container"><div class = "row-fluid">'.$initMsg.'</div></div></div>');
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
						<li>Get your documents or memos uploaded or prepare them on site.</li><br>
						<li>Make use of good tools to aid the seamless dispatch of these documents or memos across your organisation.</li><br>
						<li>Get the ability to monitor your document or memo's movement across your organisation.</li><br>
					</ul>
				</div>
			  </div>
			  <!--DOC UPLOAD SECTION-->
			  <div class="span4">
				<div id="upload-form">
					<h3>Ok! Marketing Talk aside; Take a spin right away.</h3>
					
						<form action = "index.php" method = "post" enctype = "multipart/form-data">
						<table>
						<!--<script src = "js/docindex.js"></script>-->
							<tr>
								<td></td>
								<td>
									
									<hr>
									<p class = "uploadCountNote"><i><b>A QUICK NOTE:</b> You would be doing this once so, make sure to enter the exact <u>number</u> of <u>related documents</u> you want to upload below. You can <b>Reload</b> this page if you didn't create the <u>exact</u> number of upload fields required for a particular collection of related document, though.</i></p>
									<input type = "text" autocomplete = "off" id = "count" name = "docCount" placeholder = "Number of Documents to upload"/>
									<input class = "btn" id = "loadBtn" onclick = "showUpload('file')" type = "button" name = "uploadGen" value = "Load "/>
									<hr>
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
								<td></td><td><input type="text" placeholder="Main Document's Title" name= "docTitle" /></td>
							</tr>
							<tr>
								<td></td><td><input type="text" placeholder="Money Amount stated(optional)" name="moni"/></td>
							</tr>
							<tr>
								<td></td><td><textarea placeholder="Main Document's Description" name="docDescriptn"></textarea></td>
							</tr>
							<tr>
								<td></td><td><input type="text" placeholder="Main Document's Signatory" name="docSig"/></td>
							</tr>
							<tr>
								<td></td><td><input type="text" placeholder="Organisation" name="orgName"/></td>
							</tr>
							<tr>
								<td></td><td><input type="text" placeholder="Department" name="deptName"/></td>
							</tr>
							<tr>
								<td></td><td><input class="btn" type="submit" name="formSubmit" value="Submit"/></td>
							</tr>
						</table>
					</form>
				</div>
			  </div>
			  
			  <!--DOC PROCESSING CENTER ACCESS-->
			  <div class="span4">
				<div id="docprocentr">
					<h3>Want to prepare your document or memo on site?</h3>
					<table>
						<tr>
							<td>
								<hr>
								<p>
									Go to the <em class = "DPC">Document Processing Center</em><br>
									<a class="btn" href=""> >> </a>
								</p>
								<hr>
							</td>
						</tr>
					</table>
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